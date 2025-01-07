<?php
session_start();
include 'db.php'; // Include database connection

// Check if doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php"); // Redirect to doctor login if not logged in
    exit;
}

$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor details
$stmt = $conn->prepare("SELECT name FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($doctor_name);
$stmt->fetch();
$stmt->close();

// Fetch appointments for the doctor
$appointments = [];
$stmt = $conn->prepare("SELECT 
    appointments.appointment_id, 
    appointments.appointment_date, 
    appointments.status, 
    users.name AS user_name, 
    users.email 
    FROM appointments 
    JOIN users ON appointments.user_id = users.user_id 
    WHERE appointments.doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($appointment_id, $appointment_date, $status, $user_name, $user_email);
while ($stmt->fetch()) {
    $appointments[] = [
        'id' => $appointment_id,
        'date' => $appointment_date,
        'status' => $status,
        'user_name' => $user_name,
        'user_email' => $user_email
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Doctor Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Jitsi Meet External API -->
    <script src='https://meet.jit.si/external_api.js'></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <h1>Welcome, Dr. <?= htmlspecialchars($doctor_name) ?></h1>
        <a href="doctor_logout.php">Logout</a>
    </header>

    <main>
        <section>
            <h2>Your Appointments</h2>
            <div id="appointments">
                <?php if (count($appointments) > 0): ?>
                    <ul>
                        <?php foreach ($appointments as $appointment): ?>
                            <li>
                                <strong>User:</strong> <?= htmlspecialchars($appointment['user_name']) ?> <br>
                                <strong>Email:</strong> <?= htmlspecialchars($appointment['user_email']) ?> <br>
                                <strong>Date & Time:</strong> <?= htmlspecialchars($appointment['date']) ?>
                                <div class="appointment-actions">
                                    <?php if ($appointment['status'] === 'in_consultation'): ?>
                                        <button onclick="joinVideoChat(<?= $appointment['id'] ?>, '<?= $appointment['user_name'] ?>')" class="video-chat-btn">Join Video Consultation</button>
                                    <?php endif; ?>
                                    <a href="cancel_appointment.php?id=<?= $appointment['id'] ?>" class="btn-cancel">Cancel</a>
                                    <a href="rate_patient.php?id=<?= $appointment['id'] ?>" class="btn-rate">Rate Patient</a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No appointments scheduled.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>

    <!-- Video Chat Modal -->
    <div class="modal fade" id="videoChatModal" tabindex="-1" role="dialog" aria-labelledby="videoChatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoChatModalLabel">Video Consultation</h5>
                    <button type="button" class="close" onclick="closeVideoChat()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="meet"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeVideoChat()">End Consultation</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let api = null;

        function joinVideoChat(appointmentId, userName) {
            const domain = 'meet.jit.si';
            const options = {
                roomName: `tds-consultation-${appointmentId}`,
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#meet'),
                configOverwrite: {
                    startWithAudioMuted: false,
                    startWithVideoMuted: false,
                    disableDeepLinking: true,
                    prejoinPageEnabled: false
                },
                interfaceConfigOverwrite: {
                    TOOLBAR_BUTTONS: [
                        'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                        'fodeviceselection', 'hangup', 'chat', 'recording',
                        'settings', 'raisehand', 'videoquality'
                    ],
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_WATERMARK_FOR_GUESTS: false,
                    SHOW_BRAND_WATERMARK: false,
                    DEFAULT_REMOTE_DISPLAY_NAME: userName
                }
            };

            const myModal = new bootstrap.Modal(document.getElementById('videoChatModal'));
            myModal.show();
            api = new JitsiMeetExternalAPI(domain, options);

            // Add event listeners
            api.addEventListeners({
                readyToClose: closeVideoChat,
                videoConferenceLeft: closeVideoChat,
                participantLeft: function(participant) {
                    console.log('Participant left:', participant);
                }
            });
        }

        function closeVideoChat() {
            if (api) {
                api.dispose();
                api = null;
            }
            const myModal = bootstrap.Modal.getInstance(document.getElementById('videoChatModal'));
            myModal.hide();

            // Update appointment status to completed
            fetch('update_appointment_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    appointment_id: appointmentId,
                    status: 'completed'
                })
            });
        }
    </script>
</body>
</html>

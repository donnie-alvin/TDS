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
$stmt = $conn->prepare("SELECT a.id, a.appointment_date, u.name AS user_name, u.email FROM appointments a JOIN users u ON a.user_id = u.id WHERE a.doctor_id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($appointment_id, $appointment_date, $user_name, $user_email);
while ($stmt->fetch()) {
    $appointments[] = [
        'id' => $appointment_id,
        'date' => $appointment_date,
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
                                <a href="cancel_appointment.php?id=<?= $appointment['id'] ?>">Cancel</a>
                                <a href="rate_patient.php?id=<?= $appointment['id'] ?>">Rate Patient</a>
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
</body>
</html> 
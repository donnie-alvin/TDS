<?php
session_start();
include 'db.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit; // Stop further execution
}

// Fetch user profile
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($user_name, $user_email);
$stmt->fetch();
$stmt->close();

// Fetch doctors from the database
$doctors = [];
$stmt = $conn->prepare("SELECT id, name, specialization, location, hospital, description, picture FROM doctors");
$stmt->execute();
$stmt->bind_result($doctor_id, $doctor_name, $specialization, $location, $hospital, $description, $picture);
while ($stmt->fetch()) {
    $doctors[] = [
        'id' => $doctor_id,
        'name' => $doctor_name,
        'specialization' => $specialization,
        'location' => $location,
        'hospital' => $hospital,
        'description' => $description,
        'picture' => $picture
    ];
}
$stmt->close();

// Fetch booked appointments for the user
$appointments = [];
$stmt = $conn->prepare("SELECT a.id, a.appointment_date, d.name, d.specialization FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($appointment_id, $appointment_date, $doctor_name, $doctor_specialization);
while ($stmt->fetch()) {
    $appointments[] = [
        'id' => $appointment_id,
        'date' => $appointment_date,
        'doctor_name' => $doctor_name,
        'specialization' => $doctor_specialization
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
    <title>User Dashboard</title>
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard, <?= htmlspecialchars($user_name) ?></h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <section>
            <h2>Your Profile</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user_name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user_email) ?></p>
            <a href="edit_profile.php">Edit Profile</a>
        </section>

        <section>
            <h2>Your Appointments</h2>
            <div id="appointments">
                <?php if (count($appointments) > 0): ?>
                    <ul>
                        <?php foreach ($appointments as $appointment): ?>
                            <li>
                                <strong>Doctor:</strong> <?= htmlspecialchars($appointment['doctor_name']) ?> <br>
                                <strong>Specialization:</strong> <?= htmlspecialchars($appointment['specialization']) ?> <br>
                                <strong>Date & Time:</strong> <?= htmlspecialchars($appointment['date']) ?>
                                <a href="cancel_appointment.php?id=<?= $appointment['id'] ?>">Cancel</a>
                                <a href="rate_doctor.php?id=<?= $appointment['id'] ?>">Rate Doctor</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No appointments booked yet.</p>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <h2>Search for Doctors</h2>
            <input type="text" id="search" placeholder="Search by name or specialization" onkeyup="searchDoctors()">
            <div class="doctor-list" id="doctor-list">
                <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-card" onclick="window.location.href='doctor_profile.php?id=<?= $doctor['id'] ?>'">
                        <img src="<?= htmlspecialchars($doctor['picture']) ?>" alt="<?= htmlspecialchars($doctor['name']) ?>" class="doctor-image">
                        <h3><?= htmlspecialchars($doctor['name']) ?></h3>
                        <p><?= htmlspecialchars($doctor['specialization']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($doctor['location']) ?></p>
                        <p><strong>Hospital:</strong> <?= htmlspecialchars($doctor['hospital']) ?></p>
                        <p><?= htmlspecialchars($doctor['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>FAQs</h2>
            <p><strong>Q: How do I book an appointment?</strong></p>
            <p>A: Select a doctor and choose a date and time.</p>
            <p><strong>Q: Can I cancel my appointment?</strong></p>
            <p>A: Yes, you can cancel your appointment from your appointments list.</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>

    <script>
        function searchDoctors() {
            const input = document.getElementById('search').value.toLowerCase();
            const doctorCards = document.querySelectorAll('.doctor-card');
            doctorCards.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const specialization = card.querySelector('p').textContent.toLowerCase();
                if (name.includes(input) || specialization.includes(input)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html> 

<?php
session_start();
include 'db.php'; 

// Check if doctor ID is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php"); 
}

$doctor_id = $_GET['id'];

// Fetch doctor details from the database
$stmt = $conn->prepare("SELECT name, specialization, location, hospital, description, picture FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($doctor_name, $specialization, $location, $hospital, $description, $picture);
$stmt->fetch();
$stmt->close();

// Check if doctor exists
if (!$doctor_name) {
    header("Location: dashboard.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= htmlspecialchars($doctor_name) ?>'s Profile</title>
</head>
<body>
    <header>
        <h1><?= htmlspecialchars($doctor_name) ?>'s Profile</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <div class="doctor-profile">
            <img src="<?= htmlspecialchars($picture) ?>" alt="<?= htmlspecialchars($doctor_name) ?>" class="doctor-image">
            <h2><?= htmlspecialchars($doctor_name) ?></h2>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($specialization) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($location) ?></p>
            <p><strong>Hospital:</strong> <?= htmlspecialchars($hospital) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($description) ?></p>
            <a href="make_appointment.php?doctor_id=<?= $doctor_id ?>" class="book-btn">Make an Appointment</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>
</body>
</html> 
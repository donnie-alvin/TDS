<?php
session_start();
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_email']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit; // Stop further execution
}

// Handle doctor removal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];

    // Delete doctor from the database
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    
    if ($stmt->execute()) {
        echo "Doctor removed successfully.";
    } else {
        echo "Error removing doctor: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Remove Doctor</title>
</head>
<body>
    <header>
        <h1>Remove Doctor</h1>
        <a href="dashboard.php">Back to Dashboard</a>
    </header>
    <main>
        <form action="remove_doctor.php" method="POST">
            <input type="hidden" name="doctor_id" value="<?= htmlspecialchars($_POST['doctor_id']) ?>">
            <p>Are you sure you want to remove this doctor?</p>
            <button type="submit">Yes, Remove</button>
        </form>
    </main>
</body>
</html>

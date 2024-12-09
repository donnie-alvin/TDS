<?php
session_start();
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit; // Stop further execution
}

// Check if appointment ID is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php"); // Redirect if no appointment ID is provided
    exit;
}

$appointment_id = $_GET['id'];

// Prepare and execute the deletion query
$stmt = $conn->prepare("DELETE FROM appointments WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $appointment_id, $_SESSION['user_id']);
$stmt->execute();

// Check if the appointment was successfully deleted
if ($stmt->affected_rows > 0) {
    // Redirect to dashboard with success message
    header("Location: dashboard.php?appointment_cancelled=1");
} else {
    // Redirect to dashboard with error message
    header("Location: dashboard.php?appointment_cancelled=0");
}
$stmt->close();
exit;
?>
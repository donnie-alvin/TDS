<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$doctor_id = $_POST['doctor_id'];
$appointment_date = $_POST['appointment_date'];

// Insert the appointment into the database
$stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $doctor_id, $appointment_date);

if ($stmt->execute()) {
    // Fetch doctor's name for confirmation
    $stmt = $conn->prepare("SELECT name FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->bind_result($doctor_name);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['success' => true, 'doctor_name' => $doctor_name]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to book appointment.']);
}

$stmt->close();
$conn->close();
?> 
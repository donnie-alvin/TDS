<?php
session_start();
include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$doctor_id = $_GET['doctor_id'];
$date = $_GET['date']; 

// Fetch available time slots from the database
$stmt = $conn->prepare("
    SELECT available_time 
    FROM doctor_availability 
    WHERE doctor_id = ? AND available_date = ?
");
$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();
$stmt->bind_result($available_time);

$slots = [];
while ($stmt->fetch()) {
    $slots[] = $available_time;
}
$stmt->close();

echo json_encode($slots);
?> 
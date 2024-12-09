<?php
session_start();
include 'db.php'; // Include database connection

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit;
}

$date = $_GET['date'];

// Fetch available doctors for the selected date
$stmt = $conn->prepare("
    SELECT d.id, d.name, d.specialization, d.location, d.hospital, d.description, d.picture 
    FROM doctors d
    JOIN doctor_availability da ON d.id = da.doctor_id
    WHERE da.available_date = ?
");
$stmt->bind_param("s", $date);
$stmt->execute();
$stmt->bind_result($doctor_id, $doctor_name, $specialization, $location, $hospital, $description, $picture);

$doctors = [];
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

echo json_encode($doctors);
?> 
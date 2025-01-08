<?php
session_start();
include 'db.php'; // Include database connection
include 'send_email.php';
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if doctor ID is provided
if (!isset($_GET['doctor_id'])) {
    header("Location: dashboard.php"); // Redirect if no doctor ID is provided
    exit;
}

$doctor_id = $_GET['doctor_id'];

// Fetch doctor details for display
$stmt = $conn->prepare("SELECT name FROM doctors WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($doctor_name);
$stmt->fetch();
$stmt->close();

// Check if doctor exists
if (!$doctor_name) {
    header("Location: dashboard.php"); // Redirect if doctor not found
    exit;
}

// Define hardcoded time slots for each doctor
$time_slots = [
    1 => ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00'],
    2 => ['09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00'],
    3 => ['09:30:00', '10:30:00', '11:30:00', '12:30:00', '13:30:00'],
    4 => ['08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00'], // Dr. Farai Mavhunga
    5 => ['09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00'], // Dr. Rudo Mupfumi
    6 => ['08:30:00', '09:30:00', '10:30:00', '11:30:00', '12:30:00'], // Add more doctors as needed
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['time_slots'];
    $appointment_datetime = $appointment_date . ' ' . $appointment_time; // Create a variable for the concatenated date and time

    // Check if the selected time slot is available
    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND user_id = ?");
    $stmt->bind_param("isi", $doctor_id, $appointment_datetime, $user_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // Slot is already booked
        echo "<script>alert('The selected time slot is already booked. Please choose another time.');</script>";
    } else {
        // Debugging: Log the values being inserted
        error_log("Inserting appointment: User ID: $user_id, Doctor ID: $doctor_id, Appointment Date: $appointment_datetime");
        $user_id = $_SESSION['user_id']; // Get the logged-in user's ID
        $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $doctor_id, $appointment_datetime);
        $stmt->execute();
        $stmt->close();
        
        // Include the email sending file
        

        // Redirect to dashboard or confirmation page
        header("Location: dashboard.php?appointment_success=1");
        
        echo "<script>alert('sending email');</script>";
    
        // Check if user email is set
        if (isset($_SESSION['user_email'])) {
            // Send email to user with appointment details
            $emailSent = sendAppointmentEmail($_SESSION['user_email'], $appointment_date, $appointment_time, $doctor_name);
            if (!$emailSent) {
                echo "Failed to send confirmation email. Please check the logs for more details.";
         
            }
        } else {
            echo "User email is not set in the session.";
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Book Appointment with <?= htmlspecialchars($doctor_name) ?></title>
</head>
<body>
    <header>
        <h1>Book Appointment with <?= htmlspecialchars($doctor_name) ?></h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <form method="POST" action="">
            <label for="appointment_date">Select Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <label for="time_slots">Select Time:</label>
            <select id="time_slots" name="time_slots" required>
                <option value="">Select a time</option>
                <?php
                // Assuming $doctor_id is set and $time_slots is defined
                if (isset($time_slots[$doctor_id])) {
                    foreach ($time_slots[$doctor_id] as $time) {
                        echo "<option value=\"$time\">$time</option>";
                    }
                } else {
                    echo "<option value=\"\">No available time slots</option>";
                }
                ?>
            </select>

            <button type="submit" class="book-btn">Book Appointment</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>

    <script>
        document.getElementById('appointment_date').addEventListener('change', function() {
            const date = this.value;
            const doctorId = <?= json_encode($doctor_id) ?>; // Pass doctor ID to JavaScript

            // Fetch available time slots for the selected date
            fetch(`get_available_times.php?doctor_id=${doctorId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    const timeSelect = document.getElementById('time_slots');
                    timeSelect.innerHTML = '<option value="">Select a time</option>'; // Clear previous options

                    data.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching time slots:', error));
        });
    </script>
</body>
</html>
</create_file>

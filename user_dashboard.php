<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>User Dashboard</h1>
    <h2>Your Profile</h2>
    <p>Email: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
    
    <h2>Your Appointments</h2>
    <div id="appointments">
        <?php
        // Fetch user appointments
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT a.id, a.appointment_date, d.name AS doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($appointment_id, $appointment_date, $doctor_name);
        
        if ($stmt->store_result() && $stmt->num_rows > 0) {
            echo "<ul>";
            while ($stmt->fetch()) {
                echo "<li><strong>Doctor:</strong> " . htmlspecialchars($doctor_name) . " <br><strong>Date & Time:</strong> " . htmlspecialchars($appointment_date) . " <a href='cancel_appointment.php?id=$appointment_id'>Cancel</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No appointments scheduled.</p>";
        }
        $stmt->close();
        ?>
    </div>
    <!-- Additional user functionalities can be added here -->
</body>
</html>

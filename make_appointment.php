<?php
session_start();
include 'db.php'; // Include database connection

// Function to redirect to login page
function redirectToLogin() {
    header("Location: login.php");
    exit; // Stop further execution
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    redirectToLogin();
}

// Handle form submission for appointment booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $doctor_id = htmlspecialchars(trim($_POST['doctor_id'])); // Assuming you have a doctor ID
    $appointment_date = htmlspecialchars(trim($_POST['date']));

    // Validate the appointment details
    if (empty($doctor_id) || empty($appointment_date)) {
        echo "<script>alert('Please fill in all required fields');</script>";
    } else {
        // Check doctor availability
$stmt = $conn->prepare("SELECT * FROM doctor_availability WHERE doctor_id = ? AND available_date = ? AND available_time = ?");
$available_date = date('Y-m-d', strtotime($appointment_date));
$available_time = date('H:i:s', strtotime($appointment_date));
$stmt->bind_param("iss", $doctor_id, $available_date, $available_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Doctor is available, proceed to book the appointment
$stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
$appointment_date = date('Y-m-d H:i:s', strtotime($appointment_date));
$stmt->bind_param("iis", $user_id, $doctor_id, $appointment_date);

if ($stmt->execute()) {
    // Send email notification to the user
$to = htmlspecialchars(trim($_POST['email'])); // Use the user's email address from the form
    $subject = "Appointment Confirmation";
    $message = "Your appointment has been successfully booked. Doctor: " . $doctor_id . ", Date: " . $appointment_date;
    $headers = "From: admin@example.com";

    mail($to, $subject, $message, $headers);

    echo "<script>alert('Appointment booked successfully!');</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "');</script>";
}
        } else {
            echo "<script>alert('Doctor is not available at the selected time. Please choose a different time.');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Make Appointment</title>
</head>
<body>
<header>
    <h1>HealthCare</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="services.php">Services</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="make_appointment.php">Make Appointment</a>
        <a href="login.php">Login</a>
        <a href="doctor_login.php">Doctor Login</a>
    </nav>
</header>

<main>
    <section class="contact" id="contact">
        <h1 class="heading">Make an Appointment</h1>
        <div class="contact-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="contact-form">
                <div class="form-group">
                    <label for="doctor_id">Select Doctor</label>
                    <select id="doctor_id" name="doctor_id" class="box" required>
                        <?php
                        // Fetch doctors from the database
                        $stmt = $conn->prepare("SELECT id, name FROM doctors");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                        }

                        $stmt->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Appointment Date & Time</label>
                    <input type="datetime-local" id="date" name="date" class="box" required>
                </div>
                <input type="submit" value="Make Appointment" name="submit" class="link-btn">
            </form>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Healthcare System. All rights reserved.</p>
</footer>
</body>
</html>

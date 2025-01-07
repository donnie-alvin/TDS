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

// Fetch appointment details
$stmt = $conn->prepare("SELECT doctor_id FROM appointments WHERE appointment_id = ? AND user_id = ?");
$stmt->bind_param("ii", $appointment_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->bind_result($doctor_id);
$stmt->fetch();
$stmt->close();

// Check if appointment exists
if (!$doctor_id) {
    header("Location: dashboard.php"); // Redirect if appointment not found
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    // Check if table exists, if not create it
    $tableCheck = $conn->query("SHOW TABLES LIKE 'doctor_ratings'");
    if ($tableCheck->num_rows == 0) {
        // Table doesn't exist, create it
        $conn->query("CREATE TABLE doctor_ratings (
            rating_id INT PRIMARY KEY AUTO_INCREMENT,
            doctor_id INT NOT NULL,
            user_id INT NOT NULL,
            rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
            feedback TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (doctor_id) REFERENCES doctors(id),
            FOREIGN KEY (user_id) REFERENCES users(user_id)
        )");
    }

    // Insert rating into the database
    $stmt = $conn->prepare("INSERT INTO doctor_ratings (doctor_id, user_id, rating, feedback) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $doctor_id, $_SESSION['user_id'], $rating, $feedback);
    $stmt->execute();
    $stmt->close();

    // Redirect to dashboard with success message
    header("Location: dashboard.php?rating_success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rate Doctor</title>
</head>
<body>
    <header>
        <h1>Rate Your Doctor</h1>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <form method="POST" action="">
            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>

            <label for="feedback">Feedback:</label>
            <textarea id="feedback" name="feedback" rows="4" required></textarea>

            <button type="submit" class="book-btn">Submit Rating</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>
</body>
</html> 
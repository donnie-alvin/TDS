<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_email = $_POST['doctor_email'];
    $doctor_password = $_POST['doctor_password'];

    $stmt = $conn->prepare("SELECT id, password FROM doctors WHERE email = ?");
    $stmt->bind_param("s", $doctor_email);
    $stmt->execute();
    $stmt->bind_result($doctor_id, $hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($doctor_password, $hashed_password)) {
        $_SESSION['doctor_id'] = $doctor_id;
        header("Location: doctor_dashboard.php");
        exit;
    } else {
        echo "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Doctor Login</title>
</head>
<body>
    <header>
        <h1>Doctor Login</h1>
    </header>

    <main>
        <form method="POST" action="">
            <label for="doctor_email">Email:</label>
            <input type="email" id="doctor_email" name="doctor_email" required>

            <label for="doctor_password">Password:</label>
            <input type="password" id="doctor_password" name="doctor_password" required>

            <button type="submit">Login</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Healthcare System. All rights reserved.</p>
    </footer>
</body>
</html> 
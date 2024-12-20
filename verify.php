<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $input_code = htmlspecialchars(trim($_POST['code']));

    // Fetch the confirmation code from the temporary_users table
    $stmt = $conn->prepare("SELECT confirmation_code FROM temporary_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($confirmation_code);
    $stmt->fetch();
    $stmt->close();

    // Compare the input code with the stored confirmation code
    if ($input_code === $confirmation_code) {
        // Move user data to the users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, confirmation_code) SELECT name, email, password, confirmation_code FROM temporary_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Delete the user from the temporary_users table
        $stmt = $conn->prepare("DELETE FROM temporary_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Email verified successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: Invalid confirmation code.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <h2>Verify Your Email</h2>
    <form method="POST" action="">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="text" name="code" placeholder="Enter confirmation code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>

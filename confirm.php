<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $code = htmlspecialchars(trim($_GET['code']));

    // Check the confirmation code in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE confirmation_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Mark as verified
        $updateStmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE confirmation_code = ?");
        $updateStmt->bind_param("s", $code);
        $updateStmt->execute();

        echo "<script>alert('Email verified successfully!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Invalid confirmation code.'); window.location.href='register.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>

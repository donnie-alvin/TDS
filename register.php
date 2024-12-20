<?php
require 'vendor/autoload.php'; // Include Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db.php'; // Include database connection

function sendConfirmationEmail($email, $name, $code) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = 'aphiri1658@gmail.com';            // SMTP username
        $mail->Password   = 'yovq yqmo qhbz kius';                     // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom('aphiri1658@gmail.com', 'Healthcare System');
        $mail->addAddress($email, $name);                        // Add a recipient

        // Content
        $mail->isHTML(true);                                     // Set email format to HTML
        $mail->Subject = 'Email Confirmation';
        $mail->Body    = "Hello $name,<br><br>Please confirm your email by clicking the link below:<br>
                          <a href='http://yourdomain.com/confirm.php?code=$code'>Confirm Email</a><br><br>Thank you!";
        
        if (!$mail->send()) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password
    $confirmation_code = strtoupper(substr(md5(mt_rand()), 0, 6)); // Generate a 6-character confirmation code

    // Check if the email already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Error: Email already in use. Please choose another email.');</script>";
    } else {
        // Insert user into the temporary_users table
        $stmt = $conn->prepare("INSERT INTO temporary_users (name, email, password, confirmation_code) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $confirmation_code);

        if ($stmt->execute()) {
            // Send confirmation email
            sendConfirmationEmail($email, $name, $confirmation_code);
            echo "<script>alert('Registration successful! A confirmation email has been sent.'); window.location.href='verify.php';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

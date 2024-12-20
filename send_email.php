<?php
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send appointment confirmation email
function sendAppointmentEmail($userEmail, $appointmentDate, $appointmentTime, $doctorName) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
        $mail->Username   = 'aphiri1658@gmail.com';              // SMTP username
        $mail->Password   = 'yovq yqmo qhbz kius';               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption
        $mail->Port       = 587;                                  // TCP port to connect to

        //Recipients
        $mail->setFrom('aphiri1658@gmail.com', 'Healthcare System');
        $mail->addAddress($userEmail);                            // Add a recipient

        // Content
        $mail->isHTML(true);                                      // Set email format to HTML
        $mail->Subject = 'Appointment Confirmation';
        $mail->Body    = "Your appointment has been booked.<br>Date: $appointmentDate<br>Time: $appointmentTime<br>Doctor: $doctorName";
        
        $mail->send();
        return true; // Return true if email sent successfully
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}"); // Log the error
        return false; // Return false if email sending failed
    }
}

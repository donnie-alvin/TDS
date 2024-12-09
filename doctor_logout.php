<?php
session_start();
session_destroy(); // Destroy the session
header("Location: doctor_login.php"); // Redirect to doctor login page
exit;
?>
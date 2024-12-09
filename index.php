<?php
session_start();
include 'db.php'; // Include database connection

// Function to redirect to login page
function redirectToLogin() {
    header("Location: login.php");
    exit; // Stop further execution
}

// Check if user is logged in


// Handle form submission for appointment booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $user_id = $_SESSION['user_id']; // Get user ID from session
    $doctor_id = htmlspecialchars(trim($_POST['doctor_id'])); // Assuming you have a doctor ID
    $appointment_date = htmlspecialchars(trim($_POST['date']));

    // Insert appointment into the database
    $stmt = $conn->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $doctor_id, $appointment_date);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Book your appointment</title>
</head>
<body>
<header>
    <h1>HealthCare</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="#services">Services</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
        <a href="make_appointment.php">Make Appointment</a>
    
        <a href="login.php">Login</a>
        <a href="doctor_login.php">Doctor Login</a>

    </nav>
</header>

<main>
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Find a Doctor And Book An Appointment</h1>
            <p>We are a team of 50+ Expert Doctors with 24/7 Service, 2000+ beds, Home appointments, and Video Consultation.</p>
<a href="make_appointment.php" class="MakeAppointment">Make Appointment</a>
<a href="video.php" class="play-video">Play Video</a>
            <li><a href="login.php" class="btn">Login</a></li>;
        </div>
    </section>

    <section class="about" id="about">
        <h2>About Us</h2>
        <div class="about-content">
            <img src="images/about picture.png" alt="About Us" class="about-image">
            <div class="about-text">
                <h3>Who We Are</h3>
                <p>We are dedicated to providing the best healthcare services. Our team of expert doctors is here to ensure your health and well-being.</p>
                <p>With over 50 specialists, we offer a wide range of services, including home appointments and video consultations.</p>
            </div>
        </div>
    </section>

    <section class="services" id="services">
        <h2>Our Services</h2>
        <div class="services-content">
            <div class="service">
                <h3><a href="general-consultation.php" class="service-link">General Consultation</a></h3>
                <p>Get expert advice from our experienced doctors for your health concerns.</p>
            </div>
            <div class="service">
                <h3><a href="home-appointments.php" class="service-link">Home Appointments</a></h3>
                <p>We offer home visits for your convenience and comfort.</p>
            </div>
            <div class="service">
                <h3><a href="video-consultation.php" class="service-link">Video Consultation</a></h3>
                <p>Consult with our doctors from the comfort of your home via video calls.</p>
            </div>
            <div class="service">
                <h3><a href="specialist-care.php" class="service-link">Specialist Care</a></h3>
                <p>Access specialized care from our team of expert specialists.</p>
            </div>
        </div>
    </section>

    <section class="contact" id="contact">
        <h1 class="heading">Make an Appointment</h1>
        <div class="contact-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="contact-form">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your name" class="box" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" class="box" required>
                </div>
                <div class="form-group">
                    <label for="number">Phone Number</label>
                    <input type="tel" id="number" name="number" placeholder="Enter your number" class="box" required>
                </div>
                <div class="form-group">
                    <label for="date">Appointment Date & Time</label>
                    <input type="datetime-local" id="date" name="date" class="box" required>
                </div>
                <input type="hidden" name="doctor_id" value="1"> <!-- Example doctor ID -->
                <input type="submit" value="Make Appointment" name="submit" class="link-btn">
            </form>
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p><strong>Email:</strong> <a href="mailto:info@healthcare.com">info@healthcare.com</a></p>
                <p><strong>Phone:</strong> +1 (234) 567-890</p>
                <p><strong>Address:</strong> 123 Health St, Wellness City, HC 12345</p>
            </div>
        </div>
    </section>

    <section class="how-it-works">
        <h2>How it Works?</h2>
        <div class="steps">
            <div class="step">
                <h3>Search Doctor</h3>
                <p>Keeping your health is our High Priority.</p>
            </div>
            <div class="step">
                <h3>Check Doctor Profile</h3>
                <p>Choose from 100's of top doctors.</p>
            </div>
            <div class="step">
                <h3>Schedule Appointment</h3>
                <p>Schedule an appointment with versatile dates.</p>
            </div>
            <div class="step">
                <h3>Get Solution</h3>
                <p>Schedule an appointment for your requirements.</p>
            </div>
        </div>
    </section>
</main>

<footer>
    <p>&copy; 2024 Healthcare System. All rights reserved.</p>
</footer>
<button id="back-to-top" title="Back to Top">↑</button>
<script>
document.addEventListener('scroll', function() {
    var backToTopButton = document.getElementById('back-to-top');
    if (window.scrollY > 300) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
});

document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
<script>
document.addEventListener('scroll', function() {
    var backToTopButton = document.getElementById('back-to-top');
    if (window.scrollY > 300) {
        backToTopButton.style.display = 'block';
    } else {
        backToTopButton.style.display = 'none';
    }
});

document.getElementById('back-to-top').addEventListener('click', function() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>


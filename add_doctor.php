<?php
session_start();
include 'db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_email']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit; // Stop further execution
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialization = $_POST['specialization'];
    $location = $_POST['location'];
    $hospital = $_POST['hospital'];
    $description = $_POST['description'];
    
    // Handle file upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);

    // Insert doctor into the database
    $stmt = $conn->prepare("INSERT INTO doctors (name, email, password, specialization, location, hospital, description, picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $email, $password, $specialization, $location, $hospital, $description, $target_file);
    
    if ($stmt->execute()) {
        echo "Doctor added successfully.";
    } else {
        echo "Error adding doctor: " . $stmt->error;
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
    <title>Add Doctor</title>
</head>
<body>
    <header>
        <h1>Add New Doctor</h1>
        <a href="dashboard.php">Back to Dashboard</a>
    </header>
    <main>
        <form action="add_doctor.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Doctor's Name" required>
            <input type="email" name="email" placeholder="Doctor's Email" required>
            <input type="password" name="password" placeholder="Doctor's Password" required>
            <input type="text" name="specialization" placeholder="Specialization" required>
            <input type="text" name="location" placeholder="Location" required>
            <input type="text" name="hospital" placeholder="Hospital" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="file" name="picture" required>
            <button type="submit">Add Doctor</button>
        </form>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Home</a>
                </li>
              
                <li class="nav-item">
                    <a class="nav-link" href="doctor_dashboard.php">Doctor Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="confirmLogout()">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-4">Admin Dashboard</h1>

        <!-- User Statistics -->
        <h2>User Statistics</h2>
        <div id="userStats" class="mb-4">
            <?php
            include 'db.php'; // Ensure this file contains the database connection code

            // Total users
            $totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
            // Users by role
            $usersByRole = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            echo "<p>Total Users: $totalUsers</p>";
            echo "<ul>";
            while ($row = $usersByRole->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['role']) . ": " . htmlspecialchars($row['count']) . "</li>";
            }
            echo "</ul>";
            ?>
        </div>

        <!-- Doctor Statistics -->
        <h2>Doctor Statistics</h2>
        <div id="doctorStats" class="mb-4">
            <?php
            // Total doctors
            $totalDoctors = $conn->query("SELECT COUNT(*) as count FROM doctors WHERE role = 'doctor'")->fetch_assoc()['count'];
            // Active doctors
            $activeDoctors = $conn->query("SELECT COUNT(*) as count FROM doctors WHERE role = 'doctor'")->fetch_assoc()['count'];
            echo "<p>Total Doctors: $totalDoctors</p>";
            echo "<p>Active Doctors: $activeDoctors</p>";
            ?>
        </div>

        <!-- Appointment Statistics -->
        <h2>Appointment Statistics</h2>
        <div id="appointmentStats" class="mb-4">
            <?php
            // Total appointments
            $totalAppointments = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];
            // Cancellations
            $totalCancellations = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'cancelled'")->fetch_assoc()['count'];
            echo "<p>Total Appointments: $totalAppointments</p>";
            echo "<p>Total Cancellations: $totalCancellations</p>";
            ?>
        </div>

        <!-- Feedback and Ratings -->
        <h2>Feedback and Ratings</h2>
        <div id="feedbackStats" class="mb-4">
            <?php
            // Average rating
            $averageRating = $conn->query("SELECT AVG(rating) as avg FROM feedback")->fetch_assoc()['avg'];
            echo "<p>Average Doctor Rating: " . round($averageRating, 2) . "</p>";
            ?>
        </div>

        <!-- Charts for visual representation -->
        <h2>Charts</h2>
        <canvas id="userChart" width="400" height="200"></canvas>
        <script>
            var ctx = document.getElementById('userChart').getContext('2d');
            var userChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Total Users', 'Total Doctors'],
                    datasets: [{
                        label: 'User Statistics',
                        data: [<?php echo $totalUsers; ?>, <?php echo $totalDoctors; ?>],
                        backgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'],
                        borderColor: ['rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <!-- View All Users -->
        <h2>View All Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all users
                $result = $conn->query("SELECT user_id, username, email, role FROM users WHERE role = 'client'");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['user_id']) . "</td>
                                <td>" . htmlspecialchars($row['username']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['role']) . "</td>
                                <td>
                                    <a href='change_user_role.php?user_id=" . htmlspecialchars($row['user_id']) . "' class='btn btn-warning btn-sm'>Change Role</a>
                                    <a href='remove_user.php?user_id=" . htmlspecialchars($row['user_id']) . "' class='btn btn-danger btn-sm'>Remove User</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- View All Doctors -->
        <h2>View All Doctors</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Doctor Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch all doctors
                $result = $conn->query("SELECT id, name, email FROM doctors WHERE role = 'doctor'");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>
                                    <a href='remove_doctor.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-sm'>Remove Doctor</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No doctors found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "logout.php"; // Redirect to logout page
        }
    }
    </script>
</body>
</html>

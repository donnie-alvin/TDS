<?php
session_start();
include 'db.php'; // Make sure this path is correct

// Check if user is logged in and is admin
/*if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}*/

// Fetch statistics
// User Statistics
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$totalUsers = $result->fetch_assoc()['count'];

// Doctor Statistics
$result = $conn->query("SELECT COUNT(*) as count FROM doctors");
$totalDoctors = $result->fetch_assoc()['count'];
$activeDoctors = $totalDoctors; // Or add an active status column to doctors table

// Appointment Statistics
$result = $conn->query("SELECT COUNT(*) as count FROM appointments");
$totalAppointments = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'cancelled'");
$totalCancellations = $result->fetch_assoc()['count'];

// User role distribution
$roleQuery = $conn->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
$userRoles = [];
while ($row = $roleQuery->fetch_assoc()) {
    $userRoles[$row['role']] = $row['count'];
}

?>

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
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .stats-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .chart-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .rating-distribution {
            margin-top: 20px;
        }

        .doctor-stats {
            padding: 20px;
        }

        .doctor-stats p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        canvas {
            margin-top: 20px;
            max-height: 300px;
        }
    </style>
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

        <div class="stats-container">
            <div class="stats-card">
                <h2>User Statistics</h2>
                <p>Total Users: <?php echo $totalUsers; ?></p>
                <canvas id="userRoleChart"></canvas>
            </div>
            
            <div class="stats-card">
                <h2>Doctor Statistics</h2>
                <div class="doctor-stats">
                    <p>Total Doctors: <?php echo $totalDoctors; ?></p>
                    <p>Active Doctors: <?php echo $activeDoctors; ?></p>
                    <canvas id="doctorStatsChart"></canvas>
                </div>
            </div>
            
            <div class="stats-card">
                <h2>Appointment Statistics</h2>
                <p>Total Appointments: <?php echo $totalAppointments; ?></p>
                <p>Total Cancellations: <?php echo $totalCancellations; ?></p>
                <canvas id="appointmentChart"></canvas>
            </div>
        </div>

        <div class="chart-container">
            <div class="chart-card">
                <h2>Feedback and Ratings</h2>
                <canvas id="ratingChart"></canvas>
                <div class="rating-distribution">
                    <?php
                    $ratingResult = $conn->query("SELECT AVG(rating) as avg FROM doctor_ratings");
                    if ($ratingResult) {
                        $averageRating = $ratingResult->fetch_assoc()['avg'];
                        echo "<h4>Average Rating: " . ($averageRating ? round($averageRating, 2) : 'No ratings yet') . " / 5</h4>";
                        
                        $ratingDistribution = $conn->query("SELECT rating, COUNT(*) as count FROM doctor_ratings GROUP BY rating ORDER BY rating");
                        if ($ratingDistribution && $ratingDistribution->num_rows > 0) {
                            echo "<canvas id='ratingDistributionChart'></canvas>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

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
                // Fetch all users - removed the role filter
                $result = $conn->query("SELECT user_id, username, email, COALESCE(role, 'user') as role FROM users");

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
                $result = $conn->query("SELECT id, name, email FROM doctors");

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

    <script>
    // User Role Chart
    const userRoleData = {
        labels: ['Users', 'Doctors', 'Admins'],
        datasets: [{
            data: [<?php echo $totalUsers; ?>, <?php echo $totalDoctors; ?>, 1],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
        }]
    };

    new Chart(document.getElementById('userRoleChart'), {
        type: 'doughnut',
        data: userRoleData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Appointment Status Chart
    const appointmentData = {
        labels: ['Completed', 'Cancelled'],
        datasets: [{
            data: [
                <?php echo $totalAppointments - $totalCancellations; ?>,
                <?php echo $totalCancellations; ?>
            ],
            backgroundColor: ['#4BC0C0', '#FF6384']
        }]
    };

    new Chart(document.getElementById('appointmentChart'), {
        type: 'pie',
        data: appointmentData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Rating Distribution Chart
    <?php
    $ratings = array_fill(1, 5, 0); // Initialize array with zeros for ratings 1-5
    $ratingQuery = $conn->query("SELECT rating, COUNT(*) as count FROM doctor_ratings GROUP BY rating");
    if ($ratingQuery) {
        while ($row = $ratingQuery->fetch_assoc()) {
            $ratings[$row['rating']] = $row['count'];
        }
    }
    ?>

    new Chart(document.getElementById('ratingDistributionChart'), {
        type: 'bar',
        data: {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Ratings',
                data: [<?php echo implode(',', $ratings); ?>],
                backgroundColor: '#36A2EB'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Create a bar chart for doctor statistics
    new Chart(document.getElementById('doctorStatsChart'), {
        type: 'bar',
        data: {
            labels: ['Total Doctors', 'Active Doctors'],
            datasets: [{
                label: 'Number of Doctors',
                data: [<?php echo $totalDoctors; ?>, <?php echo $activeDoctors; ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',  // Blue for Total Doctors
                    'rgba(75, 192, 192, 0.8)'   // Green for Active Doctors
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Doctor Statistics Overview'
                }
            }
        }
    });
    </script>
</body>
</html>
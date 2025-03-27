<?php
session_start();
include '../config/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Admin details
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Fetch counts
$total_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments")->fetch(PDO::FETCH_ASSOC)['count'];
$pending_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments WHERE status='pending'")->fetch(PDO::FETCH_ASSOC)['count'];
$completed_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments WHERE status='finished'")->fetch(PDO::FETCH_ASSOC)['count'];

// Function to fetch submission counts for a given period with dates
function getSubmissionCountsWithDates($conn, $period)
{
    $today = date("Y-m-d");
    $startDate = '';

    switch ($period) {
        case 'weekly':
            $startDate = date("Y-m-d", strtotime("-1 week"));
            break;
        case 'monthly':
            $startDate = date("Y-m-d", strtotime("-1 month"));
            break;
        case 'yearly':
            $startDate = date("Y-m-d", strtotime("-1 year"));
            break;
        default:
            $startDate = date("Y-m-d", strtotime("-1 week")); // Default to weekly
            break;
    }

    // Modified SQL query to use created_at
    $sql = "SELECT
                DATE(created_at) AS submission_date,
                COUNT(*) AS total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN status = 'finished' THEN 1 ELSE 0 END) AS completed
            FROM assignments
            WHERE created_at >= '$startDate'
            GROUP BY submission_date
            ORDER BY submission_date";

    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $submission_dates = [];
    $total_counts = [];
    $pending_counts = [];
    $completed_counts = [];

    foreach ($results as $row) {
        $submission_dates[] = $row['submission_date'];
        $total_counts[] = (int)$row['total'];
        $pending_counts[] = (int)$row['pending'];
        $completed_counts[] = (int)$row['completed'];
    }

    return array(
        'submission_dates' => $submission_dates,
        'total_counts' => $total_counts,
        'pending_counts' => $pending_counts,
        'completed_counts' => $completed_counts
    );
}

// Function to fetch user registration counts for a given period
function getUserRegistrationCounts($conn, $period)
{
    $today = date("Y-m-d");
    $startDate = '';

    switch ($period) {
        case 'weekly':
            $startDate = date("Y-m-d", strtotime("-1 week"));
            break;
        case 'monthly':
            $startDate = date("Y-m-d", strtotime("-1 month"));
            break;
        case 'yearly':
            $startDate = date("Y-m-d", strtotime("-1 year"));
            break;
        default:
            $startDate = date("Y-m-d", strtotime("-1 week")); // Default to weekly
            break;
    }

    $sql = "SELECT
                DATE(created_at) AS registration_date,
                COUNT(*) AS user_count
            FROM users
            WHERE created_at >= '$startDate'
            GROUP BY registration_date
            ORDER BY registration_date";

    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $registration_dates = [];
    $user_counts = [];

    foreach ($results as $row) {
        $registration_dates[] = $row['registration_date'];
        $user_counts[] = (int)$row['user_count'];
    }
    $total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch(PDO::FETCH_ASSOC)['total'];
    return array(
        'registration_dates' => $registration_dates,
        'user_counts' => $user_counts,
        'total_users' => $total_users  
    );
}


// Fetch submission counts for different periods
$weekly_data = getSubmissionCountsWithDates($conn, 'weekly');
$monthly_data = getSubmissionCountsWithDates($conn, 'monthly');
$yearly_data = getSubmissionCountsWithDates($conn, 'yearly');

// Fetch user registration counts for different periods
$weekly_user_data = getUserRegistrationCounts($conn, 'weekly');
$monthly_user_data = getUserRegistrationCounts($conn, 'monthly');
$yearly_user_data = getUserRegistrationCounts($conn, 'yearly');

// Fetch assignments data
$pending_assignments = $conn->query("SELECT * FROM assignments WHERE status='pending'")->fetchAll(PDO::FETCH_ASSOC);
$completed_assignments = $conn->query("SELECT * FROM assignments WHERE status='completed'")->fetchAll(PDO::FETCH_ASSOC);
$deadline_assignments = $conn->query("SELECT * FROM assignments WHERE deadline <= CURDATE()")->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent submissions
$recent_submissions = $conn->query("SELECT * FROM assignments ORDER BY deadline DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hudsmer Student Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
        /* Custom styles to fix sidebar */
        body {
            display: flex;
            height: 100vh; /* Ensure the body takes up the full viewport height */
            overflow: hidden; /* Prevent the body from scrolling */
        }

        aside {
            height: 100vh; /* Make the sidebar full height */
            position: sticky; /* Fix the sidebar */
            top: 0; /* Stick it to the top */
            overflow: hidden; /* Hide scrollbars on the sidebar */
        }

        main {
            overflow-y: auto; /* Enable vertical scrolling for the main content */
            flex: 1; /* Take up remaining space */
        }

        .active-button {
            /* You can adjust these values to your liking */
            opacity: 0.7; /* Reduce opacity */
            /* Example for darker background color */
        }
    </style>
    </style>
    <script>
        function showSection(sectionId) {
            document.getElementById("dashboard").style.display = "none";
            document.getElementById("users").style.display = "none";
            document.getElementById("assignments").style.display = "none";
            document.getElementById("pending").style.display = "none";
            document.getElementById("completed").style.display = "none";
            document.getElementById("deadline").style.display = "none";
            document.getElementById(sectionId).style.display = "block";
        }

        function toggleDropdown() {
            var dropdown = document.getElementById("adminDropdown");
            dropdown.classList.toggle("hidden");
        }

        let submissionChart; // Changed from myChart to submissionChart
        let userChart;
        let activeButton = null;

        window.onload = function () {
            // Submission Chart
            const submissionCtx = document.getElementById('submissionChart').getContext('2d');
            submissionChart = new Chart(submissionCtx, {
                type: 'bar', // Changed to bar chart
                data: {
                    labels: <?php echo json_encode($weekly_data['submission_dates']); ?>, // X-axis labels
                    datasets: [
                        {
                            label: 'Total',
                            data: <?php echo json_encode($weekly_data['total_counts']); ?>,
                            backgroundColor: 'rgba(11, 54, 175, 0.7)',
                            borderColor: 'rgb(27, 57, 156)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pending',
                            data: <?php echo json_encode($weekly_data['pending_counts']); ?>,
                            data: <?php echo json_encode($weekly_user_data['user_counts']); ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.7)',
        borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Completed',
                            data: <?php echo json_encode($weekly_data['completed_counts']); ?>,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)', // Green
                            borderColor: 'rgba(40, 167, 69, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Submissions',
                                color: '#6B7280', // Tailwind's gray-500
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#4B5563', // Tailwind's gray-700
                                callback: function (value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    } else {
                                        return '';
                                    }
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Submission Date',
                                color: '#6B7280', // Tailwind's gray-500
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                color: '#4B5563' // Tailwind's gray-700
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Submission Statistics Over Time',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: '#374151' // Tailwind's gray-800
                        },
                        legend: {
                            labels: {
                                color: '#374151' // Tailwind's gray-800
                            }
                        },
                        tooltip: { // Enable tooltips
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat().format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // User Registration Chart
            const userCtx = document.getElementById('userChart').getContext('2d');
            userChart = new Chart(userCtx, {
                type: 'bar', // Changed to bar chart
                data: {
                    labels: <?php echo json_encode($weekly_user_data['registration_dates']); ?>, // X-axis labels
                    datasets: [
    {
        label: 'Total Users',
        data: Array(<?php echo count($weekly_user_data['registration_dates']); ?>).fill(<?php echo $weekly_user_data['total_users']; ?>),
        backgroundColor: 'rgba(11, 54, 175, 0.7)',
        borderColor: 'rgb(27, 57, 156)',
        borderWidth: 1
    },
    {
        label: 'New Users',
        data: <?php echo json_encode($weekly_user_data['user_counts']); ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.7)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }
]

                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Users',
                                color: '#6B7280', // Tailwind's gray-500
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#4B5563', // Tailwind's gray-700
                                callback: function (value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    } else {
                                        return '';
                                    }
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Registration Date',
                                color: '#6B7280', // Tailwind's gray-500
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                color: '#4B5563' // Tailwind's gray-700
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'User Registrations Over Time',
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            color: '#374151' // Tailwind's gray-800
                        },
                        legend: {
                            labels: {
                                color: '#374151' // Tailwind's gray-800
                            }
                        },
                        tooltip: { // Enable tooltips
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#FFFFFF',
                            bodyColor: '#FFFFFF',
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';

                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat().format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        };

        function updateSubmissionChart(period, button) {
            let submissionDates, totalCounts, pendingCounts, completedCounts;

            switch (period) {
                case 'weekly':
                    submissionDates = <?php echo json_encode($weekly_data['submission_dates']); ?>;
                    totalCounts = <?php echo json_encode($weekly_data['total_counts']); ?>;
                    pendingCounts = <?php echo json_encode($weekly_data['pending_counts']); ?>;
                    completedCounts = <?php echo json_encode($weekly_data['completed_counts']); ?>;
                    break;
                case 'monthly':
                    submissionDates = <?php echo json_encode($monthly_data['submission_dates']); ?>;
                    totalCounts = <?php echo json_encode($monthly_data['total_counts']); ?>;
                    pendingCounts = <?php echo json_encode($monthly_data['pending_counts']); ?>;
                    completedCounts = <?php echo json_encode($monthly_data['completed_counts']); ?>;
                    break;
                case 'yearly':
                    submissionDates = <?php echo json_encode($yearly_data['submission_dates']); ?>;
                    totalCounts = <?php echo json_encode($yearly_data['total_counts']); ?>;
                    pendingCounts = <?php echo json_encode($yearly_data['pending_counts']); ?>;
                    completedCounts = <?php echo json_encode($yearly_data['completed_counts']); ?>;
                    break;
                default:
                    submissionDates = <?php echo json_encode($weekly_data['submission_dates']); ?>;
                    totalCounts = <?php echo json_encode($weekly_data['total_counts']); ?>;
                    pendingCounts = <?php echo json_encode($weekly_data['pending_counts']); ?>;
                    completedCounts = <?php echo json_encode($weekly_data['completed_counts']); ?>;
                    break;
            }

            // Update chart data
            submissionChart.data.labels = submissionDates;
            submissionChart.data.datasets[0].data = totalCounts;
            submissionChart.data.datasets[1].data = pendingCounts;
            submissionChart.data.datasets[2].data = completedCounts;
            submissionChart.update();

            // Change active state of buttons
            if (activeButton !== null) {
                activeButton.classList.remove('active-button');
            }
            button.classList.add('active-button');
            activeButton = button;
        }

     function updateUserChart(period, button) {
    let registrationDates, userCounts, totalUsers;

    switch (period) {
        case 'weekly':
            registrationDates = <?php echo json_encode($weekly_user_data['registration_dates']); ?>;
            userCounts = <?php echo json_encode($weekly_user_data['user_counts']); ?>;
            totalUsers = <?php echo json_encode($weekly_user_data['total_users']); ?>;
            break;
        case 'monthly':
            registrationDates = <?php echo json_encode($monthly_user_data['registration_dates']); ?>;
            userCounts = <?php echo json_encode($monthly_user_data['user_counts']); ?>;
            totalUsers = <?php echo json_encode($monthly_user_data['total_users']); ?>; // Corrected this line
            break;
        case 'yearly':
            registrationDates = <?php echo json_encode($yearly_user_data['registration_dates']); ?>;
            userCounts = <?php echo json_encode($yearly_user_data['user_counts']); ?>;
            totalUsers = <?php echo json_encode($yearly_user_data['total_users']); ?>; // Corrected this line too
            break;
        default:
            registrationDates = <?php echo json_encode($weekly_user_data['registration_dates']); ?>;
            userCounts = <?php echo json_encode($weekly_user_data['user_counts']); ?>;
            totalUsers = <?php echo json_encode($weekly_user_data['total_users']); ?>;
            break;
    }

    // Update user chart data
    userChart.data.labels = registrationDates;
    userChart.data.datasets[0].data = Array(registrationDates.length).fill(totalUsers);
    userChart.data.datasets[1].data = userCounts;
    userChart.update();

    // Change active state of buttons
    if (activeButton !== null) {
        activeButton.classList.remove('active-button');
    }
    button.classList.add('active-button');
    activeButton = button;
}

    </script>
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-6">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold">Hudsmer Student Services</h2>
        </div>

        <!-- Admin Info with Dropdown -->
        <div class="mb-6 relative">
            <div onclick="toggleDropdown()"
                class="bg-gray-700 p-4 rounded-md hover:bg-gray-600 transition duration-300 cursor-pointer">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-user-shield mr-2"></i>
                    <?= htmlspecialchars($admin_name) ?>
                </h3>
                <p class="text-sm text-gray-400"><?= htmlspecialchars($admin_email) ?></p>
            </div>

            <!-- Dropdown Menu -->
            <div id="adminDropdown" class="hidden absolute bg-gray-700 rounded-md shadow-lg mt-1 w-full z-10">
                <a href="admin_details.php"
                    class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Edit Profile</a>
                <a href="change_password.php"
                    class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Change Password</a>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav>
            <a href="admin_dashboard.php" onclick="showSection('dashboard')"
                class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
            <a href="users.php" onclick="showSection('users')"
                class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
                <i class="fas fa-users mr-2"></i> Users
            </a>
            <a href="assignments.php" onclick="showSection('assignments')"
                class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
                <i class="fas fa-file-alt mr-2"></i> Assignments
            </a>
            <a href="chat.php" onclick="showSection('chat')"
                class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
                <i class="fas fa-comments mr-2"></i> Chat
            </a>
        </nav>

        <!-- Sign Out -->
        <div class="mt-6">
            <a href="logout.php" class="block mx-auto bg-red-500 text-white py-2 rounded-md hover:bg-red-600 px-4">Sign
                Out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-yellow-200">
        <!-- Dashboard Section -->
        <div id="dashboard">
            <h1 class="text-3xl font-bold mb-5 text-gray-800">Admin Dashboard</h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Submissions -->
                 <a href="assignments.php" class="block bg-white p-6 rounded-lg shadow-md flex items-center hover:bg-gray-100 transition duration-300">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a7 7 0 00-6.707 5.09A4 4 0 00.342 10H5V8H2.535A6 6 0 1116 12.732V14a1 1 0 11-2 0v-2H9a1 1 0 010-2h7a1 1 0 010 2h-.171A7.002 7.002 0 009 2z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-500 text-sm">Total Submissions</h2>
                        <p class="text-3xl font-bold text-gray-800"><?= $total_submissions ?></p>
                    </div>
                </a>

                <!-- Pending Submissions -->
                <a href="assignments.php" class="block bg-white p-6 rounded-lg shadow-md flex items-center hover:bg-yellow-100 transition duration-300">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10A8 8 0 112 10a8 8 0 0116 0zM9 6a1 1 0 012 0v4a1 1 0 01-2 0V6z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-500 text-sm">Pending</h2>
                        <p class="text-3xl font-bold text-gray-800"><?= $pending_submissions ?></p>
                    </div>
                </a>

                <!-- Completed Submissions -->
                <a href="assignments.php" class="block bg-white p-6 rounded-lg shadow-md flex items-center hover:bg-green-100 transition duration-300">
                   <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 01.894.553l5 10a1 1 0 01-1.788.894L10 6.618 6.894 14.447a1 1 0 11-1.788-.894l5-10A1 1 0 0110 3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-500 text-sm">Completed</h2>
                        <p class="text-3xl font-bold text-gray-800"><?= $completed_submissions ?></p>
                    </div>
                </a>
            </div>
        </div>

             <!-- Charts in the same row -->
             <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- User Registration Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex gap-6 items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-700">Users</h2>
    <div class="flex gap-2">
                        <button onclick="updateUserChart('weekly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded mr-2" style="background-color: rgba(54, 162, 235, 0.7);">Weekly</button>
                        <button onclick="updateUserChart('monthly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded mr-2"  style="background-color: rgba(13, 116, 185, 0.7);">Monthly</button>
                        <button onclick="updateUserChart('yearly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded" style="background-color: rgba(11, 90, 143, 0.7);">Yearly</button>
                    </div>
                    </div>
                    <br>
                    <canvas id="userChart" width="400" height="300"></canvas>
                </div>

                <!-- Submission Chart -->
                <div class="bg-white p-4 rounded-lg shadow-md  gap-6">
                <div class="flex gap-6 items-center justify-between">
    <h2 class="text-xl font-semibold text-gray-700">Assignments</h2>

    <div class="flex gap-2">
                        <button onclick="updateSubmissionChart('weekly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded mr-2" style="background-color: rgba(54, 162, 235, 0.7);">Weekly</button>
                        <button onclick="updateSubmissionChart('monthly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded mr-2"  style="background-color: rgba(13, 116, 185, 0.7);">Monthly</button>
                        <button onclick="updateSubmissionChart('yearly', this)"
                            class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded" style="background-color: rgba(11, 90, 143, 0.7);">Yearly</button>
                    </div>
                    </div>
                    <br>
                    <canvas id="submissionChart" width="400" height="300"></canvas>
                </div>
           
        </div>
           <!-- Recent Submissions -->
           <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Recent Submissions</h2>
                    <a href="assignments.php" class="text-blue-500 hover:underline text-sm font-medium">View All</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($recent_submissions)): ?>
                        <?php foreach ($recent_submissions as $submission): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?= htmlspecialchars($submission['subject_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($submission['full_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4"><?= date("M d, Y", strtotime($submission['deadline'])) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $submission['status'] === 'finished' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= ucfirst($submission['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-6 py-4" colspan="4">No recent submissions found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users" class="hidden">
            <h2 class="text-xl font-bold mb-4">Users</h2>
            <p>Display user details here...</p>
        </div>

        <!-- Assignments Section -->
        <div id="assignments" class="hidden">
            <h2 class="text-xl font-bold mb-4">Assignments</h2>
            <p>Display assignment details here...</p>
        </div>
        

        <!-- Pending Assignments -->
        <div id="pending" class="hidden bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Pending Assignments</h2>
            <table class="w-full border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Project</th>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_assignments as $assignment): ?>
                        <tr class="border-b">
                            <td class="p-2"><?= htmlspecialchars($assignment['projectTitle']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($assignment['studentName']) ?></td>
                            <td class="p-2"><?= date("M d, Y", strtotime($assignment['deadline'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Completed Assignments -->
        <div id="completed" class="hidden bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Completed Assignments</h2>
            <table class="w-full border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Project</th>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Completion Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completed_assignments as $assignment): ?>
                        <tr class="border-b">
                            <td class="p-2"><?= htmlspecialchars($assignment['projectTitle']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($assignment['studentName']) ?></td>
                            <td class="p-2"><?= date("M d, Y", strtotime($assignment['deadline'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Deadline Approaching Assignments -->
        <div id="deadline" class="hidden bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Deadline Approaching</h2>
            <table class="w-full border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Project</th>
                        <th class="p-2 text-left">Student</th>
                        <th class="p-2 text-left">Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deadline_assignments as $assignment): ?>
                        <tr class="border-b">
                            <td class="p-2"><?= htmlspecialchars($assignment['projectTitle']) ?></td>
                            <td class="p-2"><?= htmlspecialchars($assignment['studentName']) ?></td>
                            <td class="p-2 text-red-500"><?= date("M d, Y", strtotime($assignment['deadline'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </main>

</body>
</html>

    </main>
</body>

</html>

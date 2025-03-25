<?php
session_start();
include '../config/db.php';

// Admin details
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Fetch counts
$total_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments")->fetch_assoc()['count'];
$pending_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments WHERE status='pending'")->fetch_assoc()['count'];
$completed_submissions = $conn->query("SELECT COUNT(*) AS count FROM assignments WHERE status='finished'")->fetch_assoc()['count'];

// Fetch assignments data
$pending_assignments = $conn->query("SELECT * FROM assignments WHERE status='pending'")->fetch_all(MYSQLI_ASSOC);
$completed_assignments = $conn->query("SELECT * FROM assignments WHERE status='completed'")->fetch_all(MYSQLI_ASSOC);
$deadline_assignments = $conn->query("SELECT * FROM assignments WHERE deadline <= CURDATE()")->fetch_all(MYSQLI_ASSOC);

// Fetch recent submissions
$recent_submissions = $conn->query("SELECT * FROM assignments ORDER BY deadline DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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
    </script>
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-6">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold">Admin Panel</h2>
        </div>

        <!-- Admin Info with Dropdown -->
        <div class="mb-6 relative">
            <div onclick="toggleDropdown()" class="bg-gray-700 p-4 rounded-md hover:bg-gray-600 transition duration-300 cursor-pointer">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-user-shield mr-2"></i>
                    <?= htmlspecialchars($admin_name) ?>
                </h3>
                <p class="text-sm text-gray-400"><?= htmlspecialchars($admin_email) ?></p>
            </div>

            <!-- Dropdown Menu -->
            <div id="adminDropdown" class="hidden absolute bg-gray-700 rounded-md shadow-lg mt-1 w-full z-10">
                <a href="admin_details.php" class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Edit Profile</a>
                <a href="change_password.php" class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Change Password</a>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav>
            <a href="admin_dashboard.php"  class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Dashboard</a>
            <a href="users.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Users</a>
            <a href="assignments.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Assignments</a>
        </nav>

        <!-- Sign Out -->
        <div class="mt-6">
            <a href="logout.php" class="block mx-auto bg-red-500 text-white py-2 rounded-md hover:bg-red-600 px-4">Sign Out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <!-- Dashboard Section -->
        <div id="dashboard">
            <h1 class="text-2xl font-bold mb-4">Admin Dashboard</h1>

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

                 <!-- Recent Submissions -->
                 <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Recent Submissions</h2>
                    <a href="assignments.php" class="text-blue-500 hover:underline text-sm font-medium">View All</a>
                </div>
            </div><div class="overflow-x-auto">
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

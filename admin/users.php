<?php
session_start();
include '../config/db.php';

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Fetch users with assignment count (fixed query)
$users = $conn->query(
    "SELECT users.*, COUNT(assignments.id) AS assignment_count 
    FROM users 
    LEFT JOIN assignments ON users.id = assignments.`student_id`
    GROUP BY users.id"
);

if ($users) {
  $users = $users->fetchAll(PDO::FETCH_ASSOC);

} else {
    die("Query failed: " . $conn->error);

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hudsmer Student Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon" />
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("adminDropdown");
            dropdown.classList.toggle("hidden");
        }
        function filterAssignments(status) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (status === 'total' || row.getAttribute('data-status') === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function searchUsers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        window.onload = () => {
           

            const searchBtn = document.getElementById('searchBtn');
            const searchInput = document.getElementById('searchInput');

            searchBtn.addEventListener('click', searchUsers);
            searchInput.addEventListener('keyup', event => {
                if (event.key === 'Enter') {
                    searchUsers();
                }
            });
        };
    </script>
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
        .filter-container {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }.filter-container input[type="text"] {
            flex-grow: 1; /* Allow the input to take up remaining space */
            margin-right: 0.5rem; /* Add spacing between input and buttons */
            min-width: 200px; /* Minimum width for the input */
        }

        .filter-container button {
            margin-left: 0.5rem; /* Add spacing between buttons */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-6">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold">Hudsmer Student Services</h2>
        </div>

        <!-- Admin Info -->
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
        <a href="admin_dashboard.php" onclick="showSection('dashboard')" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
        <a href="users.php" onclick="showSection('users')" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
            <i class="fas fa-users mr-2"></i> Users
        </a>
        <a href="assignments.php" onclick="showSection('assignments')" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
            <i class="fas fa-file-alt mr-2"></i> Assignments
        </a>
         <a href="chat.php" onclick="showSection('chat')" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2 flex items-center">
            <i class="fas fa-comments mr-2"></i> Chat
        </a>
    </nav>

        <!-- Sign Out -->
        <div class="mt-6">
            <a href="logout.php" class="block mx-auto bg-red-500 text-white py-2 rounded-md hover:bg-red-600 px-4">Sign Out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 bg-yellow-200">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Users</h1>
            <div class="filter-container">
                <input type="text" id="searchInput" placeholder="Search Users..." class="border rounded px-2 py-1">
                <button id="searchBtn" class="bg-gray-500 text-white px-8 py-2 rounded">Search</button>
                
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-yellow-500">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class=" bg-white divide-y divide-gray-200 ">
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($user['id']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($user['name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <?= htmlspecialchars($user['assignment_count']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="user_details.php?id=<?= htmlspecialchars($user['id']) ?>" class="text-blue-500 hover:underline">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>

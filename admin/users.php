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
    $users = $users->fetch_all(MYSQLI_ASSOC);
} else {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById("adminDropdown");
            dropdown.classList.toggle("hidden");
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-6">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold">Admin Panel</h2>
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
            <a href="admin_dashboard.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Dashboard</a>
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
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Users</h1>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignments</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
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

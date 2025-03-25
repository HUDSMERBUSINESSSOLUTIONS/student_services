<?php
session_start();
include '../config/db.php';

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Get the user ID from the GET request
$userId = isset($_GET['id']) ? $_GET['id'] : null;

// If user ID is not provided, redirect to users.php
if ($userId === null) {
    header("Location: users.php");
    exit();
}

// Fetch user details from the database (MySQLi version)
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user not found, redirect to users.php
if (!$user) {
    header("Location: users.php");
    exit();
}

// Fetch all assignments related to this user
$assignmentsStmt = $conn->prepare("SELECT * FROM assignments WHERE student_id = ?");
$assignmentsStmt->bind_param("i", $userId);
$assignmentsStmt->execute();
$assignmentsResult = $assignmentsStmt->get_result();
$assignments = $assignmentsResult->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
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
            <a href="admin_dashboard.php" class="block py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Dashboard</a>
            <a href="users.php" class="block py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Users</a>
            <a href="assignments.php" class="block py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Assignments</a>
        </nav>
         <!-- Sign Out -->

        <div class="mt-6">
            <a href="logout.php" class="block mx-auto bg-red-500 text-white py-2 rounded-md hover:bg-red-600 px-4">Sign Out</a>
        </div>
    </aside>
     <!-- Main Content -->

    <main class="flex-1 p-8 bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-gray-300 relative">
            <h1 class="text-4xl font-bold text-gray-800 mb-6">👤 User Details</h1>
            

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <strong class="block font-semibold text-blue-600 uppercase mb-1">Full Name:</strong>
                    <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($user['name']) ?></p>
                </div>
                <div>
                    <strong class="block font-semibold text-blue-600 uppercase mb-1">Email:</strong>
                    <p class="text-gray-800 font-medium underline bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div>
                    <strong class="block font-semibold text-blue-600 uppercase mb-1">Phone:</strong>
                    <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($user['phone']) ?></p>
                </div>
            </div>
 
            <h2 class="text-2xl font-bold text-gray-800 mb-4">📘 Assignments</h2>
            <table class="w-full border-collapse border border-gray-300 mb-6 text-left">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th class="p-3 border border-gray-400">Subject Name</th>
                        <th class="p-3 border border-gray-400">Deadline</th>
                        <th class="p-3 border border-gray-400">Status</th>
                        <th class="p-3 border border-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($assignments) > 0): ?>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr class="bg-gray-100 border-t border-gray-300">
                                <td class="p-3 border border-gray-400"> <?= htmlspecialchars($assignment['subject_name']) ?> </td>
                                <td class="p-3 border border-gray-400 text-red-500 font-semibold"> <?= htmlspecialchars($assignment['deadline']) ?> </td>
                                <td class="p-3 border border-gray-400">
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full shadow-md <?= $assignment['status'] === 'finished' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= ucfirst($assignment['status']) ?>
                                    </span>
                                </td>
                                <td class="p-3 border border-gray-400">
                                    <a href="view_assignments.php?id=<?= $assignment['id'] ?>" class="text-blue-600 hover:text-blue-800 underline">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center p-4 italic text-gray-500">No assignments found for this user.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <a href="users.php" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-4 rounded-full shadow-md transition-transform transform hover:scale-105">← Back to Users</a>
        </div>
    </main>
</body>
</html>

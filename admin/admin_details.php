<?php
session_start();
include '../config/db.php'; // Ensure this sets $conn as a PDO object

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Fetch admin details securely using PDO
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = :id");
$stmt->execute(['id' => $admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin not found.";
    exit();
}

// Handle messages from previous actions
$success_message = $_SESSION['update_success'] ?? null;
$error_message = $_SESSION['update_error'] ?? null;
unset($_SESSION['update_success'], $_SESSION['update_error']);

// CSRF token setup
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" crossorigin="anonymous" />
</head>

<body class="bg-gray-100 flex bg-yellow-200">

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
                    <?= htmlspecialchars($admin['name'] ?? 'Admin') ?>


                </h3>
                <p class="text-sm text-gray-400"><?= htmlspecialchars($admin['email'] ?? 'admin@example.com') ?></p>
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
    <main class="flex-1 p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Admin Details</h1>

            <!-- Display Success/Errors -->
            <?php if ($success_message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline"><?= htmlspecialchars($success_message) ?></span>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline"><?= htmlspecialchars($error_message) ?></span>
                </div>
            <?php endif; ?>

            <!-- Admin Update Form -->
            <form action="update-admin.php" method="post">
                <input type="hidden" name="admin_id" value="<?= htmlspecialchars($admin['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                           focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight 
                           focus:outline-none focus:shadow-outline">
                </div>

                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none 
                        focus:shadow-outline" type="submit">
                    Update Details
                </button>
            </form>
        </div>
    </main>

</body>
</html>

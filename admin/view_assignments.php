<?php
session_start();
include '../config/db.php';
// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Get the assignment ID from the GET request
$assignmentId = isset($_GET['id']) ? $_GET['id'] : null;

// If assignment ID is not provided, redirect to assignments.php
if ($assignmentId === null) {
    header("Location: assignments.php");
    exit();
}

// Fetch assignment details from the database
$stmt = $conn->prepare("SELECT * FROM assignments WHERE id = ?");
$stmt->execute([$assignmentId]);  // Pass the value directly in the execute array
$assignment = $stmt->fetch(PDO::FETCH_ASSOC);  // Fetch the row as an associative array


// If assignment not found, redirect to assignments.php
if (!$assignment) {
    header("Location: assignments.php");
    exit();
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
        function showSection(sectionId) {
            // document.getElementById("dashboard").style.display = "none";
            // document.getElementById("users").style.display = "none";
            // document.getElementById("assignments").style.display = "none";
            // document.getElementById("pending").style.display = "none";
            // document.getElementById("completed").style.display = "none";
            // document.getElementById("deadline").style.display = "none";
            document.getElementById(sectionId).style.display = "block";
        }

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
</head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-100 flex ">

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
    <main class="flex-1 p-8 bg-yellow-200 to-gray-200 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-gray-300 relative transition hover:shadow-2xl">
        <a href="assignments.php" class="absolute top-4 right-4 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2 px-4 rounded-full shadow-md transition-transform transform hover:scale-105">← Back to Assignments</a>

        <h1 class="text-4xl font-bold text-gray-800 mb-6 text-left tracking-wide">📘 Assignment Details</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Subject Name:</strong>
                <p class="text-gray-800 font-medium italic bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['subject_name'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Subject Code:</strong>
                <p class="text-gray-800 font-medium italic bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['subject_code'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Student ID:</strong>
                <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['student_id'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Deadline:</strong>
                <p class="text-red-500 font-semibold animate-pulse bg-gray-100 p-2 rounded-md border border-red-300">
                    <?= htmlspecialchars($assignment['deadline'] ?? 'N/A') ?>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Phone:</strong>
                <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['phone'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Number of Pages:</strong>
                <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['num_pages'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">University:</strong>
                <p class="text-gray-800 font-medium underline bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['university'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Country:</strong>
                <p class="text-gray-800 font-medium underline bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['country'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Full Name:</strong>
                <p class="text-gray-800 font-medium bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['full_name'] ?? 'N/A') ?></p>
            </div>
            <div>
                <strong class="block font-semibold text-blue-600 uppercase mb-1">Email:</strong>
                <p class="text-gray-800 font-medium underline bg-gray-100 p-2 rounded-md border border-gray-300"><?= htmlspecialchars($assignment['email'] ?? 'N/A') ?></p>
            </div>
        </div>

        <div class="mb-6">
            <strong class="block font-semibold text-blue-600 uppercase mb-1">Assignment Details:</strong>
            <p class="text-gray-800 leading-relaxed border-l-4 border-blue-500 pl-3 italic bg-gray-100 p-2 rounded-md border border-gray-300">
                <?= htmlspecialchars($assignment['assignment_details'] ?? 'N/A') ?>
            </p>
        </div>

        <div class="mb-6">
            <strong class="block font-semibold text-blue-600 uppercase mb-1">Status:</strong>
            <span class="px-3 py-1 text-sm font-semibold rounded-full shadow-md <?= $assignment['status'] === 'finished' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                <?= ucfirst($assignment['status']) ?>
            </span>
        </div>

        <div class="mb-6">
            <strong class="block font-semibold text-blue-600 uppercase mb-1">Uploaded File:</strong>
            <?php if (!empty($assignment['document_path'])): ?>
                <a href="../<?= htmlspecialchars($assignment['document_path']) ?>" class="text-blue-600 hover:text-blue-800 font-semibold underline transition-opacity hover:opacity-80" download>📥 Download File</a>
                &nbsp;&nbsp;&nbsp;
                <a href="../<?= htmlspecialchars($assignment['document_path']) ?>" class="text-blue-600 hover:text-blue-800 font-semibold underline transition-opacity hover:opacity-80" target="_blank">📥 Open File</a>


            <?php else: ?>
                <p class="text-gray-400 italic">No file uploaded</p>
            <?php endif; ?>
        </div>
    </div>
</main>




</body>
</html>

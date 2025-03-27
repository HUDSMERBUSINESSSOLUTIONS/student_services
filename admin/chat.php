<?php
session_start();
include '../config/db.php';  // Ensure this properly connects using PDO

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Fetch admin details securely
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin not found.";
    exit();
}

// Handle search query
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query
$sql = "SELECT users.id, users.name, 
            COUNT(CASE WHEN chats.is_read = 0 AND chats.receiver_id = :admin_id THEN 1 END) AS unread_count,
            chats.message, chats.timestamp
     FROM users
     LEFT JOIN chats ON (chats.sender_id = users.id OR chats.receiver_id = users.id) 
                      AND (chats.sender_id = :admin_id OR chats.receiver_id = :admin_id)
     WHERE users.role = 'student'";

// Add search condition if search term is provided
if (!empty($search_term)) {
    $sql .= " AND users.name LIKE :search_term";
}

$sql .= " GROUP BY users.id 
     ORDER BY MAX(chats.timestamp) DESC";

$user_order_query = $conn->prepare($sql);

// Bind parameters
$user_order_query->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);


// Bind search term if provided
if (!empty($search_term)) {
    $search_param = '%' . $search_term . '%';
    $user_order_query->bindParam(':search_term', $search_param, PDO::PARAM_STR);
}

$user_order_query->execute();
$users = $user_order_query->fetchAll(PDO::FETCH_ASSOC);

// Handle sending messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message'])) {
    $receiver_id = (int)$_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO chats (sender_id, receiver_id, message, is_read) VALUES (?, ?, ?, 0)");
        $success = $stmt->execute([$admin_id, $receiver_id, $message]);

        if ($success) {
            header("Location: chat.php?user=$receiver_id");
            exit();
        } else {
            echo "Failed to send message: " . implode(", ", $stmt->errorInfo());
        }
    } else {
        echo "Message cannot be empty!";
    }
}

// Fetch messages between admin and selected user
$receiver_id = isset($_GET['user']) ? (int)$_GET['user'] : 0;
$messages = [];

if ($receiver_id) {
    $conn->prepare("UPDATE chats SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?")
        ->execute([$receiver_id, $admin_id]);

    $stmt = $conn->prepare("SELECT * FROM chats WHERE 
        (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC");
    $stmt->execute([$receiver_id, $admin_id, $admin_id, $receiver_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hudsmer Student Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        #chatBox {
            min-height: 300px; /* Maintain this height even when empty */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon" />
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
<main class="flex-1 p-4 bg-yellow-200 h-screen overflow-hidden flex flex-col">
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Admin Chat</h1>
    
    <!-- Search Form -->
    <form method="GET" action="chat.php" class="mb-4">
        <div class="relative">
            <input type="text" name="search" placeholder="Search Users..." value="<?= htmlspecialchars($search_term) ?>" class="w-full px-4 py-2 border rounded-lg focus:ring-2 ">
            <button type="submit" class="absolute top-0 right-0 h-full px-4 py-2 bg-green-500 text-white rounded-r-lg focus:outline-none">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>

    <div class="flex flex-grow gap-4">
        <!-- User List -->
        <div class="w-1/4 bg-white p-4 rounded-lg shadow-lg overflow-y-auto" style="max-height: calc(100vh - 150px);">
            <h2 class="text-lg font-semibold mb-2 text-gray-700">Users</h2>
            
            <?php foreach ($users as $user): ?>
                <a href="chat.php?user=<?= $user['id'] ?>" class="block p-2 mb-2 rounded-lg bg-green-200 <?= ($receiver_id == $user['id']) ? 'bg-green-500 text-white' : 'hover:bg-gray-200' ?>">
                    <div class="font-semibold"><?= htmlspecialchars($user['name']) ?>-<?= htmlspecialchars($user['id']) ?>
                        <?php if ($user['unread_count'] > 0): ?>
                            <span class="bg-red-500 text-white text-xs ml-2 px-2 py-1 rounded-full">
                                <?= $user['unread_count'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

 <!-- Chat Section -->
 <div class="w-3/4 bg-white p-4 rounded-lg shadow-lg flex flex-col" style="max-height: calc(100vh - 150px);">
            <?php if ($receiver_id): ?>
                <div id="chatBox" class="overflow-y-auto border-b mb-4 p-2 bg-green-50 rounded-md">
                    <?php if (empty($messages)): ?>
                        <!-- Placeholder for empty chat -->
                        <p class="text-gray-500 text-center">No messages yet.</p>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <div class="mb-2 flex <?= $msg['sender_id'] == $admin_id ? 'justify-end' : 'justify-start' ?>">
                                <div class="p-3 max-w-xs text-sm rounded-2xl <?= $msg['sender_id'] == $admin_id ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-800' ?>">
                                    <?= htmlspecialchars($msg['message']) ?>
                                    <div class="text-xs text-right text-gray-500 mt-1"><?= $msg['timestamp'] ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <form method="POST" action="chat.php?user=<?= $receiver_id ?>" class="flex gap-2">
                    <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
                    <input type="text" name="message" class="w-full p-2 border rounded-lg" placeholder="Type your message..." required>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Send</button>
                </form>
            <?php else: ?>
                <p class="text-gray-500 text-center">Select a user to start chatting.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>



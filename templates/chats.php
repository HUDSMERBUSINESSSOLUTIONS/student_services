<?php
// Start session at the top before any HTML or output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once(__DIR__ . '/../config/db.php');



// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: templates/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

try {
    // Fetch user details securely using MySQLi
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<div class='alert alert-danger'>User not found. Please contact support.</div>";
        exit();
    }

    // Fetch the first admin's details
    $admin_query = $conn->query("SELECT * FROM admins LIMIT 1");
    $admin = $admin_query->fetch_assoc();

    if (!$admin) {
        echo "<div class='alert alert-danger'>No admin available at the moment.</div>";
        exit();
    }

    $admin_id = $admin['id'];
    $admin_name = $admin['name'] ?? 'Admin';

    // Handle sending messages
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
        $message = trim($_POST['message']);
        if (!empty($message)) {
            $stmt = $conn->prepare("INSERT INTO chats (sender_id, receiver_id, message, is_read) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("iis", $user_id, $admin_id, $message);
            $stmt->execute();

            header("Location: chats.php");
            exit();
        } else {
            echo "<div class='alert alert-warning'>Message cannot be empty!</div>";
        }
    }

    // Mark admin's messages as read
    $stmt = $conn->prepare("UPDATE chats SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
    $stmt->bind_param("ii", $admin_id, $user_id);
    $stmt->execute();

    // Fetch messages between user and admin
    $stmt = $conn->prepare("SELECT * FROM chats WHERE 
        (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC");
    $stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

} catch (Exception $e) {
    echo "<div class='alert alert-danger'>Something went wrong: " . htmlspecialchars($e->getMessage()) . "</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
    /* Icon Button */
    .icon-button {
        position: fixed;
        bottom: 80px;
        right: 20px;
        font-size: 24px;
        border-radius: 50%;
        cursor: pointer;
        background-color: #FF9800;
        color: white;
        box-shadow: 0px 4px 6px rgba(89, 32, 201, 0.1);
        z-index: 1000;
        height: 50px;
        width: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Chat Popup */
    .chat-popup {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 400px;
        height: 70%;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: none;
        z-index: 1000;
        animation: slideIn 0.3s forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(50%);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Chat Header */
    .chat-header {
        background-color: #6c63ff;
        color: white;
        padding: 10px;
        border-radius: 10px 10px 0 0;
        position: relative;
        font-weight: bold;
    }

    .close-btn {
        position: absolute;
        right: 10px;
        top: 5px;
        background: transparent;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
    }

    /* Chat Body */
    .chat-body {
        padding: 10px;
        height: calc(100% - 110px);
        overflow-y: auto;
    }
/* Message Bubbles */
.message {
    padding: 8px 12px;
    margin: 5px;
    max-width: 75%;
    border-radius: 15px;
    display: inline-block;
    word-wrap: break-word;
    font-size: 14px;
    line-height: 1.4;
    margin-bottom: 8px;
}

/* Admin Message (Left) */
.admin-message {
    background-color:rgb(227, 226, 236);
    color: #333;
    text-align: left;
    border-radius: 15px 15px 15px 0;
    margin-right: auto;
    align-self: flex-start;
    float: left;
    clear: both;
}

/* User Message (Right) */
.user-message {
    background-color: #6c63ff;
    color: white;
    text-align: right;
    border-radius: 15px 15px 0 15px;
    margin-left: auto;
    align-self: flex-end;
    float: right;
    clear: both;
}


/* User Message (Right) */
.user-message {
    background-color: #6c63ff;
    color: white;
    text-align: right;
    border-radius: 15px 15px 0 15px;
    margin-left: auto;
    margin-bottom: 8px;
    align-self: flex-end;
}


    /* Chat Input */
    .chat-input {
        display: flex;
        padding: 10px;
        border-top: 1px solid #ddd;
        align-items: center;
    }

    .chat-input input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-right: 5px;
    }

    /* Scrollbar Style */
    .chat-body::-webkit-scrollbar {
        width: 8px;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background-color: #6c63ff;
        border-radius: 4px;
    }

    .chat-body::-webkit-scrollbar-track {
        background-color: #f1f1f1;
    }
    .fa-paper-plane{
        color: #6c63ff;
        font-size: 18px;
        cursor: pointer;
    }
</style>

</head>
<body>

<div class="icon-button" onclick="toggleChat()">
    <i class="fas fa-comment"></i>
</div>

<div class="chat-popup" id="chatPopup">
    <div class="chat-header">
        <?php echo $admin_name; ?>
        <button class="close-btn" onclick="toggleChat()">&times;</button>
    </div>
    <div class="chat-body">
    <?php foreach ($messages as $row) : ?>
    <div class="message <?php echo ($row['sender_id'] == $user_id) ? 'user-message' : 'admin-message'; ?>">
        <?php if ($row['sender_id'] != $user_id) : ?>
            <img src="./assets/images/loadinglogo.png" alt="Logo" class="mr-2" style="height: 30px; width:30px; float: left;">
        <?php endif; ?>
        <strong><?php echo ($row['sender_id'] == $user_id) ? 'You' : $admin_name; ?>:</strong>
        <span><?php echo htmlspecialchars($row['message']); ?></span>
    </div>
<?php endforeach; ?>

</div>

    <form action="chats.php" method="POST" class="chat-input">
        <input type="text" name="message" placeholder="Type your message..." required>
        <i class="fas fa-paper-plane"></i>
    </form>
</div>

<script>
    function toggleChat() {
        const chatPopup = document.getElementById('chatPopup');
        chatPopup.style.display = (chatPopup.style.display === 'block') ? 'none' : 'block';
    }
</script>

</body>
</html>

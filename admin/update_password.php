<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: change_password.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New password and confirm password do not match.";
        header("Location: change_password.php");
        exit();
    }

    
    $admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';
    $stmt = $conn->prepare("SELECT password FROM admins WHERE email = ?");
    $stmt->execute([$admin_email]);
    $admin = $stmt->fetch();

    if (!$admin) {
        $_SESSION['error'] = "Invalid admin details.";
        header("Location: change_password.php");
        exit();
    }


    if (!password_verify($current_password, $admin['password'])) {
        $_SESSION['error'] = "Incorrect current password.";
        header("Location: change_password.php");
        exit();
    }

    
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("UPDATE admins SET password = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $admin_email]);

    
    $_SESSION['success'] = "Password updated successfully.";
    header("Location: admin_dashboard.php"); 
    exit();
} else {

    header("Location: change_password.php");
    exit();
}
?>

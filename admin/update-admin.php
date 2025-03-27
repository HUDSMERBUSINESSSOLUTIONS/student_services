<?php
session_start();
include '../config/db.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}


if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token validation failed.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_POST['admin_id'];

    
    $admin_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING); 
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if ($admin_name === false || empty($admin_name)) {
        $_SESSION['update_error'] = "Invalid name.";
        header("Location: admin_details.php");
        exit();
    }

    if ($email === false) {
        $_SESSION['update_error'] = "Invalid email address.";
        header("Location: admin_details.php");
        exit();
    }

    try {
        
        $stmt = $conn->prepare("UPDATE admins SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$admin_name, $email, $admin_id]);

        
        $_SESSION['admin_name'] = $admin_name;
        $_SESSION['admin_email'] = $email;

        
        $_SESSION['update_success'] = "Admin details updated successfully.";

        
        header("Location: admin_details.php");
        exit();

    } catch (PDOException $e) {
        
        $_SESSION['update_error'] = "Error updating record: " . $e->getMessage();
        header("Location: admin_details.php");
        exit();
    }
} else {
    header("Location: admin_details.php");
    exit();
}
?>

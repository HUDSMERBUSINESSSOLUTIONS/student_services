<?php
session_start();
include_once(__DIR__ . '/config/db.php');
 
// Get email from form
$email = trim($_POST['email'] ?? '');
 
if (empty($email)) {
    echo "Please enter your email!";
    exit();
}
 
// Check if the email exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
 
if (!$user) {
    echo "No user found with that email!";
    exit();
}
 
// Generate reset token and save it in the database
$token = bin2hex(random_bytes(32));
$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
$stmt->execute([$token, $email]);
 
// Create reset link
$resetLink = "https://hbsthesis.co.uk/reset_password.php?token=$token";
 
// Prepare email content
$subject = "Password Reset Request";
$message = "
<html>
<head>
<title>Password Reset Request</title>
</head>
<body>
<h3>Password Reset Request</h3>
<p>Hi, click the link below to reset your password:</p>
<p><a href='$resetLink'>$resetLink</a></p>
<br><br>
<p>This link expires in 1 hour.</p>
<p>If you didn't request this, please ignore this email.</p>
</body>
</html>
";
 
// Email headers
$headers = "From: Hudsmer Student Services<support@hbsthesis.co.uk>\r\n";
$headers .= "Reply-To: support@hbsthesis.co.uk\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
 
// Send the email
if (mail($email, $subject, $message, $headers)) {
    echo "<script>
    alert('Email sent successfully!');
    window.location.href='reset_password.php';
</script>";
 
} else {
    echo "Failed to send email. Please try again.";
}
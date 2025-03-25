<?php
session_start();
include_once(__DIR__ . '/../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['username'];
            header("Location: form.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    body {
        font-family: Arial, sans-serif;
    }
    .container-layer{
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                    url('../assets/images/blue-bg.jpg') center/cover no-repeat;
                    display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .container-login {
        background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 350px;
    }

    h2 {
        margin-bottom: 10px;
    }

    p {
        margin-bottom: 20px;
        color: #555;
    }

    label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .input-group {
        position: relative;
        margin: 8px 0;
    }

    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #888;
    }

    input[type="email"], input[type="password"] {
        width: 100%;
        padding: 12px 40px; /* Adjusted to accommodate the icon */
        margin: 2px 0 0 0;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
    }

    .error-text {
        color: red;
        font-size: 14px;
        margin: 4px 0;
        text-align: left;
    }

    .forget-password {
        display: inline-block;
        margin-top: 5px;
        color: #4f46e5;
        font-size: 14px;
        text-align: right;
        margin-left: auto;
        text-decoration: none;
    }

    button {
        background-color: #4f46e5;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 10px;
        width: 100%;
        margin-bottom: 20px;
    }

    button:hover {
        background-color: #3730a3;
    }

    span a {
        color: #4f46e5;
        text-decoration: none;
    }

    span a:hover {
        text-decoration: none;
    }

    .password-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .error-message {
        color: red;
        font-size: 14px;
        margin: 10px 0;
        text-align: center;
    }
</style>

</head>
<body>
    <div class="container-layer">
<div class="container-login">
    <h2>Sign In</h2>
    <p>Welcome back! Sign in to your account</p>
    <form action="" method="POST" onsubmit="return validateForm();">
        <label for="email">Email Address</label>
        <div class="input-group">
            <span class="input-icon"><i class="fas fa-envelope"></i></span>
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
            <div id="email-error" class="error-text"></div>
        </div>

        <div class="password-container">
            <label for="password">Password</label>
            <a href="forgot_password.php" class="forget-password">Forgot Password?</a>
        </div>
        <div class="input-group">
            <span class="input-icon"><i class="fas fa-lock"></i></span>
            <input type="password" id="password" name="password" placeholder="********" required>
            <div id="password-error" class="error-text"></div>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <button type="submit">Login</button>
    </form>
    <span>Don't have an account? <a href="signup.php">Signup</a></span>
</div>
        </div>
<script>
    function validateForm() {
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        let valid = true;

        emailError.textContent = "";
        passwordError.textContent = "";

        if (email.value.trim() === "") {
            emailError.textContent = "Email is required!";
            valid = false;
        }

        if (password.value.trim().length < 8) {
            passwordError.textContent = "Password must be at least 8 characters!";
            valid = false;
        }

        return valid;
    }
</script>
</body>
</html>

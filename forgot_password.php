<?php
session_start();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('./assets/images/blue-bg.jpg') center/cover no-repeat;
                        padding-top:30px;
                        padding-bottom:30px;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
            overflow: hidden;
        }
        .login-container h3 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6366F1;
        }
        .image-container {
            background: #6366F1;
            padding: 10px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .image-container::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 20px;
            background: #6366F1;
            clip-path: polygon(0 0, 25% 100%, 50% 0, 75% 100%, 100% 0);
            
        }
        .image-container img {
            max-width: 100px;
            height: auto;
        }
        .image-container p {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
        }
        @media (max-width: 480px) {
            .signup-container {
                width: 90%;
            }

            .image-container {
                padding: 15px;
            }

            .image-container img {
                max-width: 80px;
            }
        }
    </style>
</head>
 
<body>
    <div class="login-container">
        <div class="image-container">
            <img src="./assets/images/loadinglogo.png" alt="Signup Image">
            <p>Hudsmer Student Services</p>
        </div>
 
    <?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
        unset($_SESSION['error']);
    }
 
    if (isset($_SESSION['success'])) {
        echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
        unset($_SESSION['success']);
    }
    ?>
 
    <form action="password_forgot.php" method="POST" class="p-3">
        <h3 class="text-center">Reset Password</h3>
        <p class="text-center subheading">Enter your registered email, and we’ll send you a link to reset your password.</p>

        <div class="form-group">
            <label for="email">Enter your email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn" style="background-color: #6366F1; color: white; width: 100%; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">
            Reset Password
        </button>
    </form>
 
    <div class="text-center mt-1">
    <a href="templates/login.php" style="color: #6366F1; font-size: 14px;text-decoration-none">Back to Login</a>
</div>
</div>
 
</body>
</html>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
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
        session_start();
        include_once(__DIR__ . '/config/db.php');
 
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            echo "<p class='text-danger text-center'>Invalid reset token!</p>";
            exit();
        }
 
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
        if (!$user) {
            echo "<p class='text-danger text-center'>Invalid or expired reset token!</p>";
            exit();
        }
 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = trim($_POST['new_password']);
            $confirm_password = trim($_POST['confirm_password']);
 
            if ($new_password !== $confirm_password) {
                echo "<p class='text-danger text-center'>Passwords do not match!</p>";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
                $stmt->execute([$hashed_password, $user['id']]);
                echo "<script>
                    alert('Password Reset successfully!');
                    window.location.href='template/login.php';
                </script>";
                exit();
            }
        }
        ?>
        <form method="POST" class="p-3">
        <h3 class="text-center">Reset Password</h3>
        <p class="text-center subheading">Enter your new password below</p>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" style="background: #6366F1; border: none;">Reset Password</button>
        </form>
    </div>
</body>
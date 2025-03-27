<?php
session_start();
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/../components/loading.php');
showLoading();

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->execute([$email]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // No need for fetch_assoc(), just check $result directly
    if ($result && password_verify($password, $result['password'])) {
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['name'] = $result['name'];

        $success_message = "<span style='color: #6366F1; font-weight: bold;'>Login successfully</span>";
        echo "<script>
                setTimeout(() => { window.location.href = '../index.php'; }, 2000);
              </script>";
    } else {
        $error_message = "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon" />
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
                        url('../assets/images/blue-bg.jpg') center/cover no-repeat;
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
        label{
            color: #000;
            font-weight: 500;
            font-size: 14px;
        }
        .input-group{
            border: 1px solid #6366F1;
            background: transparent;
            border-radius: 5px;
        }
        .input-group-text {
            border: none;
            background: transparent;
        }
        .form-control {
            border: none;
            background: transparent;
        }
        .error-text {
            color: red;
            font-size: 14px;
        }
        .forgot-password, .login-container a {
            color: #6366F1;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-password:hover, .login-container a:hover {
            text-decoration: none;
        }
        .error-message {
            color: red;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }
        .toast-container {
            position: fixed;
            bottom: 0;
            right: 20px;
            z-index: 1050;
        }
        .toast {
            background-color: #fff;
            color: #28a745;
            font-size: 14px;
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
            <img src="../assets/images/loadinglogo.png" alt="Signup Image">
            <p>Hudsmer Student Services</p>
        </div>
        <h3 class="text-center">Sign In</h3>
        <p class="text-center subheading">Welcome back! Sign in to your account</p>
        <form action="" method="POST" onsubmit="return validateForm();" class="p-3">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" placeholder="your@email.com" required>
                </div>
                <div id="email-error" class="error-text"></div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password</label>
                    <a href="../forgot_password.php" class="forgot-password">Forgot Password?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" placeholder="********" required>
                </div>
                <div id="password-error" class="error-text"></div>
            </div>
            <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
            <button type="submit" class="btn btn-primary w-100" style="background: #6366F1; border: none;">Login</button>
        </form>
        <p class="text-center mt-3">Don't have an account? <a href="signup.php">Signup</a></p>
    </div>
        <!-- Toast Notification -->
        <div class="toast-container p-3">
        <div id="loginToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <?php echo $success_message; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
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

        <?php if (!empty($success_message)): ?>
            document.addEventListener("DOMContentLoaded", function () {
                var toast = new bootstrap.Toast(document.getElementById('loginToast'));
                toast.show();
            });
        <?php endif; ?>

            document.addEventListener("click", function(event) {
            let loginContainer = document.querySelector(".login-container");

            if (!loginContainer.contains(event.target)) {
                window.history.back();
            }
            });
</script>
</body>
</html>

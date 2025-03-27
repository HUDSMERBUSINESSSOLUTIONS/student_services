<?php
session_start();
include_once(__DIR__ . '/../config/db.php');
include_once(__DIR__ . '/../components/loading.php');
showLoading();

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $error_message = "Email is already registered!";
            header("Location: login.php");
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (:name, :email, :phone, :password)");
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":email", $email, PDO::PARAM_STR);
            $stmt->bindValue(":phone", $phone, PDO::PARAM_STR);
            $stmt->bindValue(":password", $hashed_password, PDO::PARAM_STR);
        
            if ($stmt->execute()) {
                $_SESSION["success"] = "Signup successful! You can now log in.";
                
// Prepare email content
// $subject = "Hudsmer Student Services - Sign Up Successful";
// $message = "
// <html>
// <head>
// <title>Sign Up Successful</title>
// </head>
// <body>
// <h3>Welcome to Our Platform!</h3>
// <p>Hi, your account has been successfully created.</p>
// <p>You can now log in and start using our services.</p>
// <br><br>
// <p>If you didn't sign up, please ignore this email.</p>
// </body>
// </html>
// ";

 
// $headers = "From: Hudsmer Student Services<support@hbsthesis.co.uk>\r\n";
// $headers .= "Reply-To: support@hbsthesis.co.uk\r\n";
// $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
 

// if (mail($email, $subject, $message, $headers)) {
//     echo "<script>
//     alert('Signup successful! You can now log in.');
// </script>";
// header("Location: login.php");
// exit();
 
// } else {
//     echo "Failed to send email. Please try again.";
// }

            }
        }        
        $stmt = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Full-page background and styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('../assets/images/blue-bg.jpg') center/cover no-repeat;
            font-family: Arial, sans-serif;
            padding-top:30px;
            padding-bottom:30px;
        }

        .signup-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            overflow: hidden;
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

        h3 {
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6366F1;
            margin-top: 30px;
        }

        p {
            color: #000;
            font-weight: bold;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 10px 40px;
            border: 1px solid #6366F1;
            background: transparent;
            color: #000;
        }

        .input-group input:focus {
            border: 1px solid #6366F1;
            outline: none;
            box-shadow: none;
            color: #000; 
        }

        .input-group input::placeholder {
            color: rgba(0, 0, 0, 0.5); 
        }

        .input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .error-text {
            color: red;
            font-size: 12px;
            text-align: left;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background: #6366F1;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .login-link {
            display: block;
            margin-top: 10px;
            text-decoration: none;
        }
        .login-link a{
            text-decoration: none;
            color: #6366F1;
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
    <div class="signup-container">
        <div class="image-container">
            <img src="../assets/images/loadinglogo.png" alt="Signup Image">
            <p>Hudsmer Student Services</p>
        </div>
        <h3>Signup</h3>
        <p>Create your account and join us today!</p>
        <form method="POST" action="" onsubmit="return validateForm();" class="p-3">
            <div class="input-group">
                <span class="input-icon"><i class="fas fa-user"></i></span>
                <input type="text" name="name" placeholder="name" required>
            </div>

            <div class="input-group">
                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <span class="input-icon"><i class="fas fa-phone"></i></span>
                <input type="tel" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="input-group">
                <span class="input-icon"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <p id="password-error" class="error-text"></p>
            </div>

            <div class="input-group">
                <span class="input-icon"><i class="fas fa-lock"></i></span>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <p id="confirm-password-error" class="error-text"></p>
            </div>

            <button type="submit">Signup</button>
            <span class="login-link">Already have an account? <a href="login.php">Login</a></span>
        </form>
    </div>
    <!-- Toast Container -->
<div class="toast-container">
    <div id="toast" class="toast">
        <p id="toast-message"></p>
    </div>
</div>

    <script>
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            let valid = true;

            passwordError.textContent = "";
            confirmPasswordError.textContent = "";

            if (password.length < 8) {
                passwordError.textContent = "Password must be at least 8 characters!";
                valid = false;
            }

            if (password !== confirmPassword) {
                confirmPasswordError.textContent = "Passwords do not match!";
                valid = false;
            }

            return valid;
        }
        document.addEventListener("click", function(event) {
            let SignupContainer = document.querySelector(".signup-container");

            if (!SignupContainer.contains(event.target)) {
                window.history.back();
            }
        });
        function showToast(message) {
        const toast = document.getElementById("toast");
        const toastMessage = document.getElementById("toast-message");
        toastMessage.textContent = message;
        toast.style.display = "block";

        setTimeout(() => {
            toast.style.display = "none";
        }, 3000); // Hide after 3 seconds
    }

    // Show toast if there's a success message
    window.onload = function() {
        <?php if (isset($_SESSION["success"])): ?>
            showToast("Register Successfully!");
            <?php unset($_SESSION["success"]); // Remove the message ?>
        <?php endif; ?>
    };
    </script>
</body>
</html>
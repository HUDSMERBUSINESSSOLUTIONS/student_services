<?php
ob_start();

include(__DIR__ . '/../config/db.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['username']); // Use "name" instead
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validation
    if (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match. Please try again.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkEmailQuery);
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Email already registered!';
        } else {
            // Insert user into the database — updated to use "name" and "phone"
            $query = "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                include '../components/toast.php';
                showToast('Signup successful');
                include '../components/loading.php';
                showLoading();
            
                // Redirect to index.php after success
                echo '
                <script>
                    setTimeout(() => {
                        window.location.href = "../templates/index"; // Ensure the correct path
                    }, 3000);
                </script>';
                exit();
            } else {
                $error_message = 'Signup failed. Please try again.';
            }
            
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
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

        .container-signup {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 550px;
        }

        h2 {
            margin-bottom: 10px;
            color: #4f46e5;
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
            font-size: 18px;
            color: #4f46e5;
        }

        input[type="text"], input[type="email"], input[type="password"],input[type="tel"] {
            width: 100%;
            padding: 12px 40px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
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
        }

        button:hover {
            background-color: #3730a3;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
        }

        .error-text {
            color: red;
            font-size: 14px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div class="container-layer">
    <div class="container-signup">
        <h2>Signup</h2>
        <?php if (!empty($error_message)) echo '<p class="error-message">' . $error_message . '</p>'; ?>
        <form method="POST" action="" onsubmit="return validateForm();">
            <div class="input-group">
                <span class="input-icon"><i class="fas fa-user"></i></span>
                <input type="text" name="username" placeholder="Username" required>
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
            <span>Already have an account? <a href="login.php">login</a></span>
        </form>
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
    </script>
    </div>
</body>
</html>

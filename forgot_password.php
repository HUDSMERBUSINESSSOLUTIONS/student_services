<!-- <?php
session_start();
include 'includes/db.php';

function generateOTP($length = 6) {
    return str_pad(mt_rand(0, 999999), $length, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['sendOTP'])) {
        $email = $_POST['email'];
        $checkEmail = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($checkEmail);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $otp = generateOTP();
            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            echo "<script>alert('OTP sent: $otp');</script>"; // Replace with email sending logic
        } else {
            echo "<script>alert('Email not registered!');</script>";
        }
    }

    if (isset($_POST['resetPassword'])) {
        $otp = $_POST['otp'];
        $newPassword = password_hash($_POST['newPassword'], PASSWORD_BCRYPT);

        if ($otp === $_SESSION['otp']) {
            $email = $_SESSION['email'];
            $sql = "UPDATE users SET password=? WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newPassword, $email);

            if ($stmt->execute()) {
                echo "<script>alert('Password reset successful! Redirecting to login...');</script>";
                header("refresh:2;url=login.php");
                session_destroy();
            } else {
                echo "<script>alert('Error updating password!');</script>";
            }
        } else {
            echo "<script>alert('Invalid OTP!');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            display: flex;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .image-container {
            flex: 1;
            background-color: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-container img {
            max-width: 100%;
            max-height: 100%;
        }

        .content {
            flex: 1;
            padding: 40px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #4f46e5;
        }

        .input-group {
            position: relative;
            margin: 15px 0;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
        }

        input[type="email"], input[type="text"], input[type="password"] {
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
            width: 100%;
            margin: 8px 0;
        }

        button:hover {
            background-color: #3730a3;
        }

        a {
            color: #4f46e5;
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .container {
        flex-direction: column;
        padding: 20px;
    }

    .image-container img {
        width: 200px;
        height: auto;
        margin-bottom: 15px;
    }

    .content {
        text-align: center;
    }
        }
    </style>
    <script>
        function toggleForms(step) {
            const otpForm = document.getElementById('otpForm');
            const resetForm = document.getElementById('resetForm');
            const submitButton = document.getElementById('submitButton');

            if (step === 1) {
                otpForm.style.display = 'block';
                resetForm.style.display = 'none';
                submitButton.textContent = 'Send OTP';
                submitButton.name = 'sendOTP';
            } else {
                otpForm.style.display = 'none';
                resetForm.style.display = 'block';
                submitButton.textContent = 'Reset Password';
                submitButton.name = 'resetPassword';
            }
        }
    </script>
</head>
<body onload="toggleForms(1);">
    <div class="wrapper">
        <div class="image-container">
            <img src="assets/images/forgotpassword.png" alt="Forgot Password">
        </div>
        <div class="content">
            <h2>Forgot Password</h2>
            <p>Reset your password quickly and securely. Just follow the steps below.</p>
            <form action="" method="POST">
                <div id="otpForm" class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div id="resetForm" class="input-group" style="display: none;">
                    <i class="fas fa-key input-icon"></i>
                    <input type="text" name="otp" placeholder="Enter OTP" required>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="newPassword" placeholder="New Password" required>
                </div>

                <button id="submitButton" type="submit" name="sendOTP">Send OTP</button>
            </form>
            <p><a href="login.php">Back to Login</a></p>
        </div>
    </div>
</body>
</html> -->

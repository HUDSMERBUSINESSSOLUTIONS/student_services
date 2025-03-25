<?php
session_start();
include '../config/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    try {
        $sql = "SELECT * FROM admins WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
 
        // Execute the statement
        $stmt->execute();
        $admin = $stmt->fetch();
 
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['role'] = "admin";
 
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
 
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    } finally {
        // Ensure cleanup happens no matter what
        if (isset($stmt)) $stmt = null;
        if (isset($conn)) $conn = null;
    }
}
 
 
?>
 
 
 
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hudsmer Student Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../assets/images/favicon.ico" type="image/x-icon" />
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");
           
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.innerHTML = "&#128065;";
            } else {
                passwordField.type = "password";
                eyeIcon.innerHTML = "&#128064;";
            }
        }
    </script>
</head>
<body class="min-h-screen bg-blue-50 flex items-center justify-center">
 
    <div class="flex w-full max-w-4xl shadow-md rounded-lg overflow-hidden">
        <!-- Left Side with Image and Brand -->
        <div class="w-1/2 bg-indigo-600 flex items-center justify-center p-6">
            <div class="text-center">
                <img src="../assets/images/logo2.png" alt="Logo" class="w-32 mx-auto mb-4">
                <h2 class="text-3xl font-bold text-white">HUDSMER STUDENT SERVICES</h2>
               
            </div>
        </div>
 
        <!-- Right Side with Login Form -->
        <div class="w-1/2 bg-white p-8 flex flex-col justify-center">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4 text-center">
                Admin Login</h3>
 
            <?php if (!empty($error)): ?>
                <p class="text-red-500 text-sm mb-4 text-center"><?php echo $error; ?></p>
            <?php endif; ?>
 
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-gray-600">Email</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
 
                <div>
                    <label for="password" class="block text-gray-600">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span id="eye-icon" onclick="togglePassword()" class="absolute right-3 top-3 cursor-pointer text-gray-500">&#128064;</span>
                    </div>
                </div>
 
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">Sign In</button>
            </form>
        </div>
    </div>
 
</body>
</html>
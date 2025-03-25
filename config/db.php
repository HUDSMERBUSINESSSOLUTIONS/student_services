<?php
$servername = "localhost";
$dbname = "assignment";
$username = "root";
$password = "";

// Create MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// // Project: Standard PHP Project

// // Database Configuration (config/db.php)
// $host = 'localhost';
// $user = 'root';
// $password = '';
// $dbname = 'AssignmentDb';

// $conn = new mysqli($host, $user, $password, $dbname);
// if ($conn->connect_error) {
//     die('Connection failed: ' . $conn->connect_error);
// }

// // SQL Queries (SQL/queries.sql)
// $sql = "CREATE TABLE IF NOT EXISTS users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     username VARCHAR(50) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );";
// $conn->query($sql);

// $sql = "CREATE TABLE IF NOT EXISTS admins (
//     admin_id INT AUTO_INCREMENT PRIMARY KEY,
//     username VARCHAR(50) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );";
// $conn->query($sql);

// $sql = "CREATE TABLE IF NOT EXISTS orders (
//     order_id INT AUTO_INCREMENT PRIMARY KEY,
//     user_id INT NOT NULL,
//     subject_name VARCHAR(100) NOT NULL,
//     subject_code VARCHAR(50) NOT NULL,
//     num_pages INT NOT NULL,
//     deadline DATE NOT NULL,
//     assignment_details TEXT,
//     document_path VARCHAR(255),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
// );";
// $conn->query($sql);

// $sql = "CREATE TABLE IF NOT EXISTS messages (
//     message_id INT AUTO_INCREMENT PRIMARY KEY,
//     user_id INT NOT NULL,
//     admin_id INT,
//     message TEXT NOT NULL,
//     sent_by VARCHAR(10) CHECK (sent_by IN ('user', 'admin')),
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
// );";
// $conn->query($sql);

// $sql = "CREATE TABLE IF NOT EXISTS notifications (
//     notification_id INT AUTO_INCREMENT PRIMARY KEY,
//     user_id INT NOT NULL,
//     admin_id INT,
//     message TEXT NOT NULL,
//     is_read BOOLEAN DEFAULT FALSE,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
//     FOREIGN KEY (admin_id) REFERENCES admins(admin_id) ON DELETE CASCADE
// );";
// $conn->query($sql);

?>

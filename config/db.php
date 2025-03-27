<?php

$servername = "localhost";

$dbname = "u540836934_hbsstdservice";

$username = "u540836934_hbsstdservice";

$password = "Hudsmer@123";

$charset = "utf8mb4"; // Recommended encoding
 
try {

    // Define Data Source Name (DSN) for cleaner setup

    $dsn = "mysql:host={$servername};dbname={$dbname};charset={$charset}";
 
    // Set up PDO options

    $options = [

        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,          // Throw exceptions on errors

        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Fetch as associative array

        PDO::ATTR_EMULATE_PREPARES => true,                    // Force emulated prepares to avoid stmt count error

        PDO::ATTR_PERSISTENT => false,                         // Avoid persistent connections

        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true             // Buffer results to avoid limits

    ];
 
    // Create a new PDO instance

    $conn = new PDO($dsn, $username, $password, $options);
 
} catch (PDOException $e) {

    // Log error for admin debugging and show a generic error message to users

    error_log("Database connection error: " . $e->getMessage());

    die("Database connection failed. Please try again later.");

}
 
?>
 
<?php
session_start();
include '../config/db.php';

$query = "SELECT * FROM assignments";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Order Details</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Assignment</th>
            <th>Status</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><a href="order_details.php?id=<?= $row['id'] ?>"><?= $row['full_name'] ?></a></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['assignment_details'] ?></td>
                <td><?= ucfirst($row['status']) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

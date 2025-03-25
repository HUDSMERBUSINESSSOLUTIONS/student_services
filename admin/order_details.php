<?php
session_start();
include '../config/db.php';

if (!isset($_GET['id'])) {
    die("Order ID is required.");
}

$order_id = $_GET['id'];
$query = "SELECT * FROM assignments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Detail</title>
    <link rel="stylesheet" href="styles.css">
    <script src="modal.js"></script>
</head>
<body>
    <h2>Order Detail</h2>
    <p><strong>Full Name:</strong> <?= $order['full_name'] ?></p>
    <p><strong>Email:</strong> <?= $order['email'] ?></p>
    <p><strong>Phone:</strong> <?= $order['phone'] ?></p>
    <p><strong>Subject Code:</strong> <?= $order['subject_code'] ?></p>
    <p><strong>Subject Name:</strong> <?= $order['subject_name'] ?></p>
    <p><strong>Deadline:</strong> <?= $order['deadline'] ?></p>
    <p><strong>Number of Pages:</strong> <?= $order['num_pages'] ?></p>
    <p><strong>Assignment Details:</strong> <?= $order['assignment_details'] ?></p>

    <?php if (!empty($order['document_path'])) : ?>
        <?php if (preg_match("/\.(jpg|jpeg|png|gif)$/", $order['document_path'])) : ?>
            <p><strong>Uploaded File:</strong></p>
            <img src="<?= $order['document_path'] ?>" alt="Assignment Image" class="preview-img" onclick="openModal(this.src)">
            <div id="imageModal" class="modal">
                <span class="close" onclick="closeModal()">&times;</span>
                <img class="modal-content" id="modalImage">
            </div>
        <?php elseif (preg_match("/\.pdf$/", $order['document_path'])) : ?>
            <p><strong>Uploaded File:</strong> <a href="<?= $order['document_path'] ?>" download>Download PDF</a></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

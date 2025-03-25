<?php
session_start();
include '../config/db.php';

// Get admin info
$admin_name = $_SESSION['admin_name'] ?? 'Admin';
$admin_email = $_SESSION['admin_email'] ?? 'admin@example.com';

// Function to update assignment status
function updateAssignmentStatus($conn, $assignmentId, $newStatus) {
    $stmt = $conn->prepare("UPDATE assignments SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $assignmentId]);
}

// Handle status update request
if (isset($_GET['update_status']) && isset($_GET['id']) && isset($_GET['status'])) {
    $assignmentId = (int)$_GET['id'];
    $newStatus = ($_GET['status'] === 'pending') ? 'finished' : 'pending'; // Toggle status
    updateAssignmentStatus($conn, $assignmentId, $newStatus);
    header("Location: assignments.php");
    exit();
}
// Fetch all assignments from the database (mysqli version)
$result = $conn->query("SELECT * FROM assignments");
$assignments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById("adminDropdown");
            dropdown.classList.toggle("hidden");
        }
    </script>
</head>

<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-6">
        <div class="text-center mb-8">
            <h2 class="text-xl font-bold">Admin Panel</h2>
        </div>

        <!-- Admin Info -->
        <div class="mb-6 relative">
            <div onclick="toggleDropdown()" class="bg-gray-700 p-4 rounded-md hover:bg-gray-600 transition duration-300 cursor-pointer">
                <h3 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-user-shield mr-2"></i>
                    <?= htmlspecialchars($admin_name) ?>
                </h3>
                <p class="text-sm text-gray-400"><?= htmlspecialchars($admin_email) ?></p>
            </div>

            <!-- Dropdown Menu -->
            <div id="adminDropdown" class="hidden absolute bg-gray-700 rounded-md shadow-lg mt-1 w-full z-10">
                <a href="admin_details.php" class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Edit Profile</a>
                <a href="change_password.php" class="block py-2 px-4 text-white hover:bg-gray-600 transition duration-300">Change Password</a>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav>
            <a href="admin_dashboard.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Dashboard</a>
            <a href="users.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Users</a>
            <a href="assignments.php" class="block w-full text-left py-2 px-4 rounded-md bg-gray-700 hover:bg-gray-600 mb-2">Assignments</a>
        </nav>

        <!-- Sign Out -->
        <div class="mt-6">
            <a href="logout.php" class="block mx-auto bg-red-500 text-white py-2 rounded-md hover:bg-red-600 px-4">Sign Out</a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold mb-4">Assignments</h1>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment Details</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($assignments as $assignment): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($assignment['id']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($assignment['subject_name'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($assignment['assignment_details'] ?? 'N/A') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($assignment['deadline']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $assignment['status'] === 'finished' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                    <?= ucfirst($assignment['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="view_assignments.php?id=<?= $assignment['id'] ?>" class="text-blue-500 hover:text-blue-700">View</a>

                              

                                <a href="assignments.php?update_status=true&id=<?= $assignment['id'] ?>&status=<?= $assignment['status'] ?>" class="text-<?= $assignment['status'] === 'pending' ? 'green' : 'yellow' ?>-500 hover:text-<?= $assignment['status'] === 'pending' ? 'green' : 'yellow' ?>-700 ml-2">
                                    <?= $assignment['status'] === 'pending' ? 'Complete' : 'Revert to Pending' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>

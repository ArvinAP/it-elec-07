<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

// Database connection
require 'config/db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT medicine_id, name, description, category, unit FROM medicines ORDER BY medicine_id DESC");
    $medicines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | PharmaLink</title>
    <link rel="stylesheet" href="assets/dashboardstyle.css">
</head>
<body>
    <h1>Welcome to PharmaLink Dashboard</h1>

    <p>Hello, User #<?= htmlspecialchars($_SESSION['userID']) ?> 👋</p>

    <nav>
        <ul>
            <li><a href="add_med.php">Add Medicines</a></li>
            <li><a href="add_inventory.php">Add Inventory</a></li>
            <li><a href="view_inventory.php">View Inventory</a></li>
            <li><a href="donate.php">Donate Medicines</a></li>
            <li><a href="request.php">Request Medicines</a></li>
            <li><a href="matches.php">View Matches</a></li>
            <li><a href="components/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <!-- Medicines Table -->
    <section>
        <h2 style="text-align:center;">Medicines List</h2>

        <?php if (count($medicines) === 0): ?>
            <p style="text-align:center;">No medicines found.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicines as $med): ?>
                <tr>
                    <td><?= htmlspecialchars($med['medicine_id']) ?></td>
                    <td><?= htmlspecialchars($med['name']) ?></td>
                    <td><?= htmlspecialchars($med['description']) ?></td>
                    <td><?= htmlspecialchars($med['category']) ?></td>
                    <td><?= htmlspecialchars($med['unit']) ?></td>
                    <td>
                        <a href="edit_med.php?id=<?= $med['medicine_id'] ?>">Edit</a> |
                        <a href="delete_med.php?id=<?= $med['medicine_id'] ?>&csrf_token=<?= $_SESSION['csrf_token'] ?>" 
                        onclick="return confirm('Are you sure you want to delete this medicine?');">Delete</a>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </section>

    <footer>
        <!-- <p>&copy; <?= date("Y") ?> PharmaLink. All rights reserved.</p> -->
    </footer>

    <script>
        let timeoutDuration = 600000; 
        let warningDuration = 5000; 
        let timeout, warning;

        function resetTimer() {
            clearTimeout(timeout);
            clearTimeout(warning);

            warning = setTimeout(() => {
                alert("You will be logged out due to 1 minute of inactivity.");
            }, timeoutDuration - warningDuration);

            timeout = setTimeout(() => {
                window.location.href = "components/logout.php?reason=timeout";
            }, timeoutDuration);
        }

        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeydown = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;
    </script>
</body>
</html>

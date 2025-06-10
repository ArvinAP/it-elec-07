<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | PharmaLink</title>
    <link rel="stylesheet" href="../assets/dashboardstyle.css"> 
</head>
<body>
    <h1>Welcome to PharmaLink Dashboard</h1>

    <p>Hello, User #<?= htmlspecialchars($_SESSION['user_id']) ?> 👋</p>

    <nav>
        <ul>
            <li><a href="add_inventory.php">Add Inventory</a></li>
            <li><a href="view_inventory.php">View Inventory</a></li>
            <li><a href="donate.php">Donate Medicines</a></li>
            <li><a href="request.php">Request Medicines</a></li>
            <li><a href="matches.php">View Matches</a></li>
            <li><a href="../components/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <footer>
        <p>&copy; <?= date("Y") ?> PharmaLink. All rights reserved.</p>
    </footer>
</body>
</html>

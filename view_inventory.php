<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

require 'config/db.php';

// Fetch inventory with medicine names
$sql = "
    SELECT inventory.inventory_id, medicines.name, inventory.quantity, 
           inventory.expiry_date, inventory.batch_number, inventory.date_added
    FROM inventory
    JOIN medicines ON inventory.medicine_id = medicines.medicine_id
    ORDER BY inventory.date_added DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Inventory | PharmaLink</title>
    <link rel="stylesheet" href="assets/dashboardstyle.css">
</head>
<body>
    <h1>Inventory List</h1>

    <nav>
        <ul>
            <li><a href="dashboard.php">View Medicines</a></li>
            <li><a href="add_med.php">Add Medicines</a></li>
            <li><a href="add_inventory.php">Add Inventory</a></li>
            <li><a href="view_inventory.php">View Inventory</a></li>
            <li><a href="donate.php">Donate Medicines</a></li>
            <li><a href="request.php">Request Medicines</a></li>
            <li><a href="matches.php">View Matches</a></li>
            <li><a href="components/logout.php">Log Out</a></li>
        </ul>
    </nav>

    <section>
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Medicine</th>
                        <th>Qty</th>
                        <th>Expiry</th>
                        <th>Batch</th>
                        <th>Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['inventory_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['expiry_date']) ?></td>
                            <td><?= htmlspecialchars($row['batch_number']) ?></td>
                            <td><?= htmlspecialchars($row['date_added']) ?></td>
                            <td>
    <div class="action-buttons">
        <a href="edit_inventory.php?id=<?= $row['inventory_id'] ?>" class="edit-btn">Edit</a>
        <a href="delete_inventory.php?id=<?= $row['inventory_id'] ?>" 
           class="delete-btn"
           onclick="return confirm('Are you sure you want to delete this inventory record?');">
           Delete
        </a>
    </div>
</td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No inventory records found.</p>
        <?php endif; ?>
    </section>

    <footer>
        <p style="text-align:center; color:#999;">&copy; <?= date("Y") ?> PharmaLink. All rights reserved.</p>
    </footer>
</body>
</html>

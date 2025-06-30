<?php
session_start();

require 'config/db.php'; 

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars(trim($_POST['category'] ?? ''), ENT_QUOTES, 'UTF-8');
    $unit = htmlspecialchars(trim($_POST['unit'] ?? ''), ENT_QUOTES, 'UTF-8');

    if ($name === '') $errors[] = "Medicine name is required.";
    if ($unit === '') $errors[] = "Unit is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO medicines (name, description, category, unit) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $category, $unit])) {
            $success = "✅ Medicine added successfully!";
            // Optionally rotate token
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        } else {
            $errors[] = "❌ Failed to add medicine.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Medicine</title>
     <link rel="stylesheet" href="assets/Inventory.css"> 
</head>
<body>
    <h2 style="text-align:center;">Add New Medicine</h2>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $e): ?>
                <li><?= $e ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label>Name*</label><br>
        <input type="text" name="name" required><br>

        <label>Description</label><br>
        <textarea name="description"></textarea><br>

        <label>Category</label><br>
        <input type="text" name="category"><br>

        <label>Unit*</label><br>
        <input type="text" name="unit" required><br>

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <br><button type="submit">Add Medicine</button>
        <a href="dashboard.php">Back</a>
    </form>
</body>
</html>

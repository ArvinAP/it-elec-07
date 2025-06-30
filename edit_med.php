<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userID'])) {
    header("Location: index.php");
    exit();
}

require 'config/db.php'; // This file should define $conn (MySQLi connection)

$error = '';
$success = '';
$medicine = null;

// CSRF token check function
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Get medicine ID from GET parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid medicine ID.");
}

$medicine_id = (int)$_GET['id'];

// Fetch medicine details to pre-fill form
$stmt = $conn->prepare("SELECT medicine_id, name, description, category, unit FROM medicines WHERE medicine_id = ?");
$stmt->bind_param("i", $medicine_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Medicine not found.");
}

$medicine = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check CSRF token
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF token.");
    }

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $unit = trim($_POST['unit'] ?? '');

    if (empty($name)) {
        $error = "Medicine name is required.";
    } else {
        // Update the medicine in the database
        $updateStmt = $conn->prepare("UPDATE medicines SET name = ?, description = ?, category = ?, unit = ? WHERE medicine_id = ?");
        $updateStmt->bind_param("ssssi", $name, $description, $category, $unit, $medicine_id);

        if ($updateStmt->execute()) {
            $success = "Medicine updated successfully.";
            // Refresh medicine info
            $medicine['name'] = $name;
            $medicine['description'] = $description;
            $medicine['category'] = $category;
            $medicine['unit'] = $unit;
        } else {
            $error = "Failed to update medicine. Please try again.";
        }

        $updateStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Medicine | PharmaLink</title>
    <link rel="stylesheet" href="assets/Inventory.css" />
</head>
<body>
    <h1 style="text-align:center;">Edit Medicine</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>" />

        <label for="name">Name *</label><br />
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($medicine['name']) ?>" required /><br />

        <label for="description">Description</label><br />
        <textarea id="description" name="description"><?= htmlspecialchars($medicine['description']) ?></textarea><br />

        <label for="category">Category</label><br />
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($medicine['category']) ?>" /><br />

        <label for="unit">Unit</label><br />
        <input type="text" id="unit" name="unit" value="<?= htmlspecialchars($medicine['unit']) ?>" /><br />

        <button type="submit">Update Medicine</button>
         <a href="dashboard.php">Back</a>
    </form>
</body>
</html>

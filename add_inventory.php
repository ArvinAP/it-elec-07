<?php
require 'config/db.php';

$errors = [];
$success = '';

    $medicine_id = filter_input(INPUT_POST, 'medicine_id', FILTER_VALIDATE_INT);
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $batch_number = trim($_POST['batch_number'] ?? '');
    $expiry_date = $_POST['expiry_date'] ?? null;

    if (!$medicine_id) $errors[] = "Please select a medicine.";
    if (!$quantity || $quantity <= 0) $errors[] = "Quantity must be greater than zero.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO inventory (medicine_id, quantity, expiry_date, batch_number) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$medicine_id, $quantity, $expiry_date, $batch_number])) {
            $success = "✅ Inventory added successfully.";
        } else {
            $errors[] = "❌ Failed to add inventory.";
        }
    }


// Get medicines
$medicines = $pdo->query("SELECT medicine_id, name FROM medicines ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Inventory | PharmaLink</title>
    <link rel="stylesheet" href="assets/Inventory.css"> 
</head>
<body>
      <h2 style="text-align:center;">Add Inventory</h2>

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>


    <form method="post" action="">
        <label for="medicine_id">Medicine:</label>
        <select name="medicine_id" id="medicine_id" required>
            <option value="">--Select Medicine--</option>
            <?php foreach ($medicines as $med): ?>
                <option value="<?= $med['medicine_id'] ?>"><?= htmlspecialchars($med['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" min="1" name="quantity" id="quantity" required><br><br>

        <label for="batch_number">Batch Number:</label>
        <input type="text" name="batch_number" id="batch_number"><br><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="date" name="expiry_date" id="expiry_date"><br><br>

        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <button type="submit">Add Inventory</button>
        <a href="dashboard.php">Back</a>
    </form>
</body>
</html>

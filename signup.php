<?php
session_start();
require 'config/db.php'; // Also sets $csrf_token

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF token check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $fName = trim($_POST['fName']);
        $lName = trim($_POST['lName']);
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validations
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (strlen($password) < 8 || 
                  !preg_match('/[A-Z]/', $password) || 
                  !preg_match('/[\W_]/', $password)) {
            $error = "Password must be at least 8 characters, include an uppercase letter and a special character.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT userID FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already registered.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $insert = $conn->prepare("INSERT INTO users (fName, lName, email, password) VALUES (?, ?, ?, ?)");
                $insert->bind_param("ssss", $fName, $lName, $email, $hashed_password);

                if ($insert->execute()) {
                    $success = "Registration successful. You can now <a href='index.php'>log in</a>.";
                } else {
                    $error = "Something went wrong. Please try again.";
                }

                $insert->close();
            }

            $stmt->close();
        }
    }
}
?>


<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="assets/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="container">
    <div class="logo">
        <!-- <img src="images/logo.png"> -->
    </div>
    <div class="form-content">
        <header>Sign Up</header>

        <?php if (!empty($error)) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
        <?php if (!empty($success)) echo "<p style='color:green;'>" . $success . "</p>"; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

            <div class="field input-field">
                <input type="text" name="fName" placeholder="First Name" class="input" required>
            </div>

            <div class="field input-field">
                <input type="text" name="lName" placeholder="Last Name" class="input" required>
            </div>

            <div class="field input-field">
                <input type="email" name="email" placeholder="Email" class="input" required>
            </div>

            <div class="field input-field">
                <input type="password" name="password" placeholder="Password" class="password" required
                       pattern="(?=.*[A-Z])(?=.*[\W_]).{8,}"
                       title="Password must be at least 8 characters long, include an uppercase letter, and a special character.">
            </div>

            <div class="field input-field">
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="password" required>
            </div>

            <div class="field button-field">
                <button type="submit">Sign Up</button>
            </div>
        </form>

        <div class="form-link">
            <p>Already have an account? <a href="index.php" class="link login-link">Login</a></p>
        </div>
    </div>
</div>
<div class="des">
    <h1>Register to PharmaLink - Medical Inventory System</h1>
</div>
</body>
</html>

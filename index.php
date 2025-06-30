<?php
date_default_timezone_set('Asia/Manila');
session_start();

require 'config/db.php'; // This already sets up the DB and CSRF token ($csrf_token)

// Helper function to check lockout
function isAccountLocked($conn, $email) {
    $stmt = $conn->prepare("SELECT attempts, locked_until FROM login_attempts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $attempts = 0;
    $locked_until = null;

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($attempts, $locked_until);
        $stmt->fetch();
    }

    $stmt->close();
    return ($locked_until !== null && strtotime($locked_until) > time());
}

// Handle failed login
function handleFailedLogin($conn, $email) {
    $threshold = 5;
    $lock_minutes = 1;

    $stmt = $conn->prepare("SELECT attempts FROM login_attempts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $attempts = 0;

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($attempts);
        $stmt->fetch();
        $stmt->close();

        $attempts++;
        if ($attempts >= $threshold) {
            $lock_until = date("Y-m-d H:i:s", strtotime("+$lock_minutes minutes"));
            $update = $conn->prepare("UPDATE login_attempts SET attempts = ?, locked_until = ? WHERE email = ?");
            $update->bind_param("iss", $attempts, $lock_until, $email);
            $update->execute();
            $update->close();
        } else {
            $update = $conn->prepare("UPDATE login_attempts SET attempts = ?, last_attempt = NOW() WHERE email = ?");
            $update->bind_param("is", $attempts, $email);
            $update->execute();
            $update->close();
        }
    } else {
        $stmt->close();
        $insert = $conn->prepare("INSERT INTO login_attempts (email, attempts) VALUES (?, 1)");
        $insert->bind_param("s", $email);
        $insert->execute();
        $insert->close();
    }
}

// Login logic
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF check
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error = "Invalid CSRF token.";
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Backend validation
        if (empty($email) || empty($password)) {
            $error = "Please fill in both email and password.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters long.";
        } elseif (isAccountLocked($conn, $email)) {
            $error = "Account is temporarily locked. Try again later.";
        } else {
            $stmt = $conn->prepare("SELECT userID, password FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($userID, $hashedPassword);
                $stmt->fetch();

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['userID'] = $userID;
                    $_SESSION['email'] = $email;

                    // Reset login attempts
                    $delete = $conn->prepare("DELETE FROM login_attempts WHERE email = ?");
                    $delete->bind_param("s", $email);
                    $delete->execute();
                    $delete->close();

                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid email or password.";
                    handleFailedLogin($conn, $email);
                }
            } else {
                $error = "Invalid email or password.";
                handleFailedLogin($conn, $email);
            }

            $stmt->close();
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
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
        <header>Login</header>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
       <form method="POST" action="" onsubmit="return validateForm()">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">


    <div class="field input-field">
        <input type="email" name="email" placeholder="Email" class="input" required>
    </div>

    <div class="field input-field">
        <input type="password" id="password" name="password" placeholder="Password" class="password" required minlength="6">
    </div>

    <div class="form-link">
        <a href="#" class="forgot-pass">Forgot password?</a>
    </div>

    <div class="field button-field">
        <button type="submit">Login</button>
    </div>
</form>


        <div class="form-link">
            <p>Don't have an account? <a href="signup.php" class="link signup-link">Signup</a></p>
        </div>
    </div>
</div>
<div class="des">
    <h1>Welcome to Medical Inventory System</h1> 
</div>

<script>
function validateForm() {
    const password = document.getElementById("password").value;
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }
    return true;
}
</script>
</body>
</html>

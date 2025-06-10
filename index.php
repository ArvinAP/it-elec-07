<?php
session_start();

$servername = "localhost";
$username = "root";         
$password = "";             
$database = "pharmalink"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize and validate input
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $stmt = $conn->prepare("SELECT userID, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: pages/dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    $stmt->close();
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
        <img src="images/logo.png">
    </div>
    <div class="form-content">
        <header>Login</header>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST" action="">
            <div class="field input-field">
                <input type="email" name="email" placeholder="Email" class="input" required>
            </div>

            <div class="field input-field">
                <input type="password" name="password" placeholder="Password" class="password" required>
            </div>

            <div class="form-link">
                <a href="#" class="forgot-pass">Forgot password?</a>
            </div>

            <div class="field button-field">
                <button type="submit">Login</button>
            </div>
        </form>

        <div class="form-link">
            <p>Don't have an account? <a href="pages/signup.php" class="link signup-link">Signup</a></p>
        </div>
    </div>
</div>
<div class="des">
    <h1>Welcome to Medical Inventory System</h1> 
</div>
</body>
</html>
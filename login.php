<?php
session_start();

// Define page title
$pageTitle = "Login";

// Database connection (update with your credentials)
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'weba1';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Basic validation
    if (empty($email) || empty($password)) {
        $message = "Error: All fields are required.";
    } else {
        // Fetch user from database
        $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Optional: Handle "Remember me" (e.g., set a cookie)
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_token', $token, time() + (86400 * 30), "/"); // 30 days
                $stmt = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->bind_param("si", $token, $user['id']);
                $stmt->execute();
                $stmt->close();
            }

            // Redirect to cart.php after successful login
            header("Location: cart.php");
            exit; // Ensure no further code is executed after redirect
        } else {
            $message = "Error: Invalid email or password.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "include/head.php"; ?>
</head>
<body>
    <?php include "include/header.php"; ?>

    <div style="width: 350px; background-color: #FFFFFF; margin: 50px auto; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center;">
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #333;">Login</h2>
        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Login to continue</p>
        
        <!-- Display success or error message -->
        <?php if ($message): ?>
            <p style="font-size: 14px; color: <?php echo strpos($message, 'Error') === false ? 'green' : 'red'; ?>; margin-bottom: 15px;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Email</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Password</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
            </div>
            <div style="margin: 15px 0; color: #666; font-size: 14px;">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember me</label>
            </div>
            <button type="submit" style="width: 100%; padding: 12px; background-color: #4A90E2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-bottom: 10px;">Login</button>
            <a href="forgot_password.php" style="display: block; text-align: center; color: #4A90E2; text-decoration: none; font-size: 14px; margin-bottom: 10px;">Forgot password?</a>
            <p style="font-size: 14px; color: #666;">Don’t have an account? <a href="signup.php" style="color: #4A90E2; text-decoration: none;">Sign up</a></p>
        </form>
    </div>

    <?php include "include/footer.php"; ?>
</body>
</html>
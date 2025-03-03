<?php
session_start();

// Define page title
$pageTitle = "Sign up";

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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']) ? 1 : 0;

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $message = "Error: All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Error: Invalid email format.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $message = "Error: Email already registered.";
            $stmt->close();
        } else {
            $stmt->close(); // Close the previous statement before reusing

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into database
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hashed_password);
            if ($stmt->execute()) {
                $message = "Sign up successful! Please <a href='login.php' style='color: #4A90E2; text-decoration: none;'>log in</a>.";
            } else {
                $message = "Error: Sign up failed. Try again.";
            }
            $stmt->close();
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
        <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 10px; color: #333;">Sign up</h2>
        <p style="font-size: 14px; color: #666; margin-bottom: 20px;">Sign up to continue</p>
        
        <!-- Display success or error message -->
        <?php if ($message): ?>
            <p style="font-size: 14px; color: <?php echo strpos($message, 'Error') === false ? 'green' : 'red'; ?>; margin-bottom: 15px;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; margin-bottom: 5px; color: #666; font-size: 14px;">Username</label>
                <input type="text" id="name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px;">
            </div>
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
            <button type="submit" style="width: 100%; padding: 12px; background-color: #4A90E2; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-bottom: 10px;">Sign up</button>
            
            <p style="font-size: 14px; color: #666; margin-top: 10px;">Already have an account? <a href="login.php" style="color: #4A90E2; text-decoration: none;">Log in</a></p>
        </form>
    </div>

    <?php include "include/footer.php"; ?>
</body>
</html>
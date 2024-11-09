<?php
session_start();
include 'db.php';  // Include the connection file

$error_message = "";  // Initialize the error message variable

// Check if username is set in the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        $error_message = "Database error: " . htmlspecialchars($conn->error);
    } else {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Log the login event
                $log_query = "INSERT INTO login_logs (username) VALUES (?)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param('s', $user['username']);
                $log_stmt->execute();

                // Redirect based on role
                if ($user['role'] == 'admin') {
                    header('Location: admin_dashboard.php');
                } elseif ($user['role'] == 'lecturer') {
                    header('Location: lecturer_dashboard.php');
                } elseif ($user['role'] == 'prl') {
                    header('Location: prl_dashboard.php');
                }
                exit();
            } else {
                $error_message = "Invalid password!";
            }
        } else {
            $error_message = "User not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    
</head>
<body>
    <div class="login-container">
        <!-- Flashing lights around the login box -->
        <div class="flashing-light"></div>
        <div class="flashing-light"></div>
        <div class="flashing-light"></div>
        <div class="flashing-light"></div>
        <div class="flashing-light"></div>

        <h2>Login</h2>
        <?php if (!empty($error_message)) { ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required value="<?php echo htmlspecialchars($username); ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="green-button">Login</button>

        </form>
        <p>Forgot your password? <a href="password.php" class="green-text">Reset here</a></p>
        <p>Have no account  ? <a href="register.php" class="green-text">Register here</a></p>
        <button type="button" onclick="window.location.href='index.php';" class="submit-btn" style="background-color: #f0ad4e; color: white;">
        <i class="fas fa-arrow-left"></i> Back to Home
    </div>
</body>
</html>

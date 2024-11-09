<?php
session_start();
include 'db.php';

// Function to generate a hard-to-read verification code
function generateVerificationCode($length = 6) {
    return bin2hex(random_bytes($length)); // Generates a random verification code
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify'])) {
        // Verification process
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Check user data against the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND email = ? AND contact = ?");
        $stmt->bind_param("sss", $username, $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a verification code and store it in the session
            $_SESSION['verification_code'] = generateVerificationCode();
            $_SESSION['username'] = $username; // Store username for later use
            $_SESSION['email'] = $email; // Store email for later use

            // Send the verification code to the user's email (this is a placeholder)
            // mail($email, "Your Verification Code", "Your verification code is: " . $_SESSION['verification_code']);
        } else {
            $error = "No matching user found.";
        }
    } elseif (isset($_POST['reset_password'])) {
        // Password reset process
        $new_password = $_POST['new_password'];
        $entered_code = $_POST['entered_code'];

        if ($entered_code === $_SESSION['verification_code']) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $username = $_SESSION['username'];

            // Update password in the database
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_password, $username);

            if ($stmt->execute()) {
                $success = "Your password has been updated successfully! You can now log in.";
                // Clear session variables
                session_unset();
                session_destroy();
            } else {
                $error = "Error updating password.";
            }
        } else {
            $error = "Invalid verification code.";
        }
    } elseif (isset($_POST['resend_code'])) {
        // Resend verification code
        $_SESSION['verification_code'] = generateVerificationCode();
        $email = $_SESSION['email'];
        // Send the verification code to the user's email again
        // mail($email, "Your Verification Code", "Your verification code is: " . $_SESSION['verification_code']);
        $success = "A new verification code has been sent to your email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: green;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: green;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: darkgreen;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        p {
            margin: 10px 0;
        }

        a {
            color: green;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Forgot Password</h2>

    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>

    <?php if (!isset($_SESSION['verification_code'])): ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit" name="verify">Verify</button>
        </form>
    <?php else: ?>
        <p>Your verification code is: <strong><?php echo $_SESSION['verification_code']; ?></strong></p>
        <form method="POST">
            <input type="text" name="entered_code" placeholder="Enter Verification Code" required>
            <input type="password" name="new_password" placeholder="Enter New Password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
        <form method="POST" style="margin-top: 10px;">
            <button type="submit" name="resend_code">Resend Verification Code</button>
        </form>
    <?php endif; ?>
    
    <p>Remembered your password? <a href="login.php">Login here</a></p>
</div>

</body>
</html>

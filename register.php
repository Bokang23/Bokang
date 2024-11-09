<?php
include 'db.php';

$error_message = "";  // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $gender = $_POST['gender'];
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $maiden_name = $gender === 'female' ? trim($_POST['maiden_name']) : null;

    // Check for unique username
    $check_username_query = "SELECT * FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($check_username_query);
    $stmt_check->bind_param('s', $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error_message = "Username already taken. Please choose another.";
    } else {
        // Generate a unique Employee Number (e.g., EMP followed by a unique random number)
        $employee_number = 'EMP' . rand(1000, 9999);

        // Insert the user into the database
        $query = "INSERT INTO users (name, surname, gender, email, contact, username, password, role, maiden_name, employee_number) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            $error_message = "Database error: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param('ssssssssss', $name, $surname, $gender, $email, $contact, $username, $password, $role, $maiden_name, $employee_number);

            if ($stmt->execute()) {
                // Save username in session to prefill the login form
                session_start();
                $_SESSION['username'] = $username;

                // Show the employee number to the user after successful registration
                echo "<script>alert('Registration successful! Your Employee Number is $employee_number. You will be redirected to login.');</script>";
                echo "<script>window.location = 'login.php';</script>";
            } else {
                $error_message = "Error: " . htmlspecialchars($stmt->error);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Space Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">

<script>
        function toggleMaidenName() {
            const gender = document.querySelector('input[name="gender"]:checked').value;
            const maidenNameField = document.getElementById('maiden-name-field');
            maidenNameField.style.display = gender === 'female' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Create Your Account</h2>

        <!-- Display error message if there is one -->
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="name" placeholder="First Name" required>
            <input type="text" name="surname" placeholder="Surname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="contact" placeholder="Contact Number" required>

            <!-- Gender Selection -->
            <div class="gender-selection">
                <label for="male">
                    <input type="radio" id="male" name="gender" value="male" onclick="toggleMaidenName()" required> Male
                </label>
                <label for="female">
                    <input type="radio" id="female" name="gender" value="female" onclick="toggleMaidenName()" required> Female
                </label>
            </div>

            <!-- Maiden Name Field (Hidden if Male) -->
            <div id="maiden-name-field">
                <input type="text" name="maiden_name" placeholder="Maiden Name">
            </div>

            <!-- User Role Selection -->
            <select name="role" required>
                <option value="">Select User Type</option>
                <option value="admin">Faculty Admin</option>
                <option value="lecturer">Lecturer</option>
                <option value="prl">Principal Lecturer</option>
            </select>

            <!-- Username and Password -->
            <div>
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div>
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </form>
        <button type="button" onclick="window.location.href='index.php';" class="submit-btn" style="background-color: #f0ad4e; color: white;">
        <i class="fas fa-arrow-left"></i> Back to Home
    </div>
</body>
</html>

<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Optionally, you can fetch the user's details if needed
$lecturer_id = $_SESSION['user_id']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Link Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f9;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-links {
            margin-top: 20px;
            text-align: center;
        }
        .form-links a {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 15px;
            color: #fff;
            background-color: #007BFF;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .form-links a:hover {
            background-color: #0056b3;
        }
        .logout-btn {
            margin-top: 20px;
            display: block;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #dc3545;
            padding: 10px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Links</h2>
    <div class="form-links">
        <a href="report_form.php">Weekly Report Form</a>
        <a href="other_form.php">Other Form</a> <!-- Replace with actual form links -->
        <!-- Add more links as necessary -->
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>

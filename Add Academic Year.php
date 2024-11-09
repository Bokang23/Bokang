<?php
include('db.php');

$success_message = '';
$error_message = '';

// Add Academic Year Logic
if (isset($_POST['add_academic_year'])) {
    $academic_year = isset($_POST['academic_year']) ? trim($_POST['academic_year']) : '';

    if ($academic_year) {
        $sql = "INSERT INTO academic_years (year) VALUES ('$academic_year')";
        try {
            if ($conn->query($sql) === TRUE) {
                $success_message = "New academic year added successfully!";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        } catch (Exception $e) {
            $error_message = $e->getMessage();
        }
    } else {
        $error_message = "Error: Academic year cannot be empty.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Academic Year</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Consistent Admin Dashboard Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
        }
        .dashboard-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        nav {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .nav-link {
            text-decoration: none;
            color: #007BFF;
            font-weight: bold;
            transition: color 0.3s;
        }
        .nav-link:hover {
            color: #0056b3;
        }
        .error-message, .success-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .form-container {
            margin-top: 20px;
        }
        form {
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .logout-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }
        .logout-btn {
            background-color: #ff5e57;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .logout-btn:hover {
            background-color: #ff3b30;
        }
    </style>

</head>
<body>
    <!-- The page content including navbar and sidebar -->
    <div id="main-content">
        <a href="admin_dashboard.php" class="logout-btn">Back to Admin</a>
    </div>

    <!-- Add Academic Year Form -->
    <div class="form-container" id="add_academic_year_form">
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-title">
                <i class="fas fa-calendar-plus"></i> Add Academic Year
            </div>
            <div class="form-group">
                <input type="text" name="academic_year" placeholder="Academic Year" required>
            </div>
            <button type="submit" name="add_academic_year">Add Academic Year</button>
        </form>
    </div>
</body>
</html>

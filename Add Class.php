<?php
// Include your database connection here
include('db.php');

$success_message = '';
$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    // Capture form inputs
    $class_name = $_POST['class_name'];
    $current_year = $_POST['current_year'];
    $semester = $_POST['semester'];
    $number_of_students = $_POST['number_of_students'];
    $class_code = $_POST['class_code'];

    // Validation
    if (empty($class_name) || empty($current_year) || empty($semester) || empty($number_of_students) || empty($class_code)) {
        $error_messages[] = "All fields are required.";
    } else {
        // Check if the class code already exists in the database
        $check_class_query = "SELECT * FROM classes WHERE class_code = ? AND academic_year = ?";
        $stmt = $conn->prepare($check_class_query);
        $stmt->bind_param("ss", $class_code, $current_year);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_messages[] = "A class with the same class code and academic year already exists.";
        } else {
            // Check if the class name already exists
            $check_name_query = "SELECT * FROM classes WHERE class_name = ?";
            $stmt_name = $conn->prepare($check_name_query);
            $stmt_name->bind_param("s", $class_name);
            $stmt_name->execute();
            $name_result = $stmt_name->get_result();

            if ($name_result->num_rows > 0) {
                $error_messages[] = "A class with the same name already exists.";
            } else {
                // Insert into the database if no duplicates found
                $stmt_insert = $conn->prepare("INSERT INTO classes (class_name, academic_year, semester, number_of_students, class_code) VALUES (?, ?, ?, ?, ?)");
                $stmt_insert->bind_param("sssis", $class_name, $current_year, $semester, $number_of_students, $class_code);

                if ($stmt_insert->execute()) {
                    $success_message = "Class added successfully!";
                } else {
                    $error_messages[] = "Error adding class. Please try again.";
                }

                $stmt_insert->close();
            }

            $stmt_name->close();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
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
<div class="dashboard-container">
    <h1>Add Class</h1>

    <!-- Display error messages -->
    <?php if (!empty($error_messages)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($error_messages as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Display success message -->
    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <!-- Add Class Form -->
    <form method="POST">
        <div class="form-group">
            <input type="text" name="class_name" placeholder="Class Name" required>
        </div>

        <div class="form-group">
            <select name="current_year" required>
                <option value="">Select Academic Year</option>
                <?php
                $year_result = $conn->query("SELECT * FROM academic_years");
                while ($year = $year_result->fetch_assoc()) {
                    echo "<option value='{$year['year']}'>{$year['year']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <select name="semester" required>
                <option value="">Select Semester</option>
                <option value="S1">Semester 1</option>
                <option value="S2">Semester 2</option>
            </select>
        </div>

        <div class="form-group">
            <input type="number" name="number_of_students" placeholder="Number of Students" min="0" required>
        </div>

        <div class="form-group">
            <input type="text" name="class_code" placeholder="Class Code (e.g., DITY1S1)" required>
        </div>

        <button type="submit" name="add_class">Add Class</button>
    </form>

    <!-- Back link to main page -->
    <a class="nav-link" href="admin_dashboard.php">Back to admin dashboard</a>
</div>
</body>
</html>

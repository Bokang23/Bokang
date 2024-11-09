<?php
include('db.php');

// Initialize the message variables
$success_message = '';
$error_message = '';

// Add module functionality
if (isset($_POST['add_module'])) {
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $description = $_POST['description']; // Add description
    $lecturer_id = $_POST['lecturer_id']; // Add lecturer ID
    $academic_year = $_POST['academic_year'];
    $semester = $_POST['semester'];

    // Ensure all required fields are filled
    if (empty($module_name) || empty($module_code) || empty($academic_year) || empty($semester)) {
        $error_message = "Please fill in all required fields!";
    } else {
        // Prepare SQL statement to insert the module
        $stmt = $conn->prepare("INSERT INTO modules (module_name, module_code, description, lecturer_id, academic_year, semester, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param('ssssss', $module_name, $module_code, $description, $lecturer_id, $academic_year, $semester);

        // Execute the query and handle success or failure
        if ($stmt->execute()) {
            $success_message = "Module added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch academic years from the database
$year_result = $conn->query("SELECT * FROM academic_years");
if ($year_result->num_rows > 0) {
    $academic_years = [];
    while ($year = $year_result->fetch_assoc()) {
        $academic_years[] = $year['year']; // Storing academic years in an array
    }
} else {
    $academic_years = []; // In case no academic years are available
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Module</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
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
        .form-group input, .form-group select, .form-group textarea {
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
        .success-message, .error-message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <!-- Navigation back button -->
    <div class="logout-container">
        <a href="admin_dashboard.php" class="logout-btn">Back to Admin</a>
    </div>

    <!-- Success or Error Messages -->
    <?php if ($success_message): ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <!-- Add Module Form -->
    <div class="form-container">
        <form method="POST">
            <div class="form-title">
                <i class="fas fa-book-open"></i> Add Module
            </div>
            <div class="form-group">
                <input type="text" name="module_name" placeholder="Module Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="module_code" placeholder="Module Code" required>
            </div>
            <div class="form-group">
                <textarea name="description" placeholder="Description (Optional)"></textarea>
            </div>
            <div class="form-group">
                <input type="text" name="lecturer_id" placeholder="Lecturer ID (Optional)">
            </div>
            <div class="form-group">
                <label for="academic_year">Select Academic Year:</label>
                <select name="academic_year" id="academic_year" required>
                    <option value="" disabled selected>Select Academic Year</option>
                    <?php
                    // Check if academic years are available
                    if (!empty($academic_years)) {
                        foreach ($academic_years as $year) {
                            echo "<option value='$year'>$year</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No academic years available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="semester">Select Semester:</label>
                <select name="semester" id="semester" required>
                    <option value="" disabled selected>Select Semester</option>
                    <option value="Semester 1">Semester 1</option>
                    <option value="Semester 2">Semester 2</option>
                </select>
            </div>
            <button type="submit" name="add_module">Add Module</button>
        </form>
    </div>
</body>
</html>

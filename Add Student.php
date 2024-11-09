<?php
// Include the database connection
include 'db.php';

if (isset($_POST['add_student'])) {
    $student_name = $_POST['student_name'];
    $student_number = $_POST['student_number'];
    $date_of_birth = $_POST['date_of_birth'];
    $class_name = $_POST['class_name'];
    $academic_year = $_POST['academic_year'];
    $semester = $_POST['semester'];
    $email = $_POST['email'];
    $contacts = $_POST['contacts'];

    // Prepare SQL query to insert student data into the database
    $sql = "INSERT INTO students (name, student_number, date_of_birth, academic_year, semester, email, contacts, class_name) 
        VALUES ('$student_name', '$student_number', '$date_of_birth', '$academic_year', '$semester', '$email', '$contacts', '$class_name')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Student added successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
    <!-- Main content -->
    <div class="content">
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-title">
                    <i class="fas fa-user-graduate"></i> Add Student
                </div>

                <!-- Display error or success message -->
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>

                <!-- Form fields -->
                <div class="form-group">
                    <input type="text" name="student_name" placeholder="Student Name" required>
                </div>
                <div class="form-group">
                    <input type="text" name="student_number" placeholder="Student Number" required>
                </div>
                <div class="form-group">
                    <input type="date" name="date_of_birth" placeholder="Date of Birth" required>
                </div>
                <div class="form-group">
                    <label for="class_name">Class</label>
                    <select name="class_name" required>
                        <option value="">Select a Class</option>
                        <?php
                        // Fetch classes from database
                        $result = $conn->query("SELECT class_name FROM classes");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['class_name']) . "'>" . htmlspecialchars($row['class_name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="academic_year">Academic Year</label>
                    <select name="academic_year" required>
                        <option value="">Select Academic Year</option>
                        <?php
                        // Fetch academic years from database
                        $result = $conn->query("SELECT DISTINCT academic_year FROM students");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['academic_year']) . "'>" . htmlspecialchars($row['academic_year']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" required>
                        <option value="">Select Semester</option>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="text" name="contacts" placeholder="Contact Number" required>
                </div>

                <button type="submit" name="add_student">Add Student</button>
            </form>
        </div>

        <!-- Button to return to Admin Dashboard -->
        <button onclick="window.location.href='admin_dashboard.php'">Back to Admin Dashboard</button>
    </div>
</body>
</html>

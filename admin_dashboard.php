<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in as admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Initialize messages
$error_message = "";
$success_message = "";

// Function to insert into database
function executeInsert($stmt, &$error_message, &$success_message, $success_text) {
    if ($stmt->execute()) {
        $success_message = $success_text;
    } else {
        $error_message = "Error: " . htmlspecialchars($stmt->error);
    }
    $stmt->close();
}

// Add lecturer functionality
if (isset($_POST['add_lecturer'])) {
    $name = $_POST['lecturer_name'] ?? null;
    $surname = $_POST['lecturer_surname'] ?? null;
    $email = $_POST['lecturer_email'] ?? null;
    $contact = $_POST['lecturer_contact'] ?? null;
    $username = $_POST['lecturer_username'] ?? null;
    $gender = $_POST['lecturer_gender'] ?? null;
    $maiden_name = $_POST['lecturer_maiden_name'] ?? null;

    if ($name && $surname && $email && $contact && $gender && $username) {
        $employee_number = "EMP" . rand(1000, 9999);
        $password = password_hash($employee_number, PASSWORD_DEFAULT);
        $role = 'lecturer';

        $stmt = $conn->prepare("INSERT INTO users (name, surname, email, contact, username, password, role, gender, maiden_name, employee_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $name, $surname, $email, $contact, $username, $password, $role, $gender, $maiden_name, $employee_number);

        if ($stmt->execute()) {
            $success_message = "<p>Lecturer added successfully!<br>Employee Number: $employee_number<br>Password: $employee_number</p>";
        } else {
            $error_message = "Error adding lecturer: " . htmlspecialchars($stmt->error);
        }

        $stmt->close();
    } else {
        $error_message = "Please fill in all fields.";
    }
}

// Add academic year functionality
if (isset($_POST['add_academic_year'])) {
    $academic_year = $_POST['academic_year'];
    $stmt = $conn->prepare("INSERT INTO academic_years (year) VALUES (?)");
    $stmt->bind_param('s', $academic_year);
    executeInsert($stmt, $error_message, $success_message, "Academic year added successfully!");
}

// Add semester functionality
if (isset($_POST['add_semester'])) {
    $semester = $_POST['semester'];
    $stmt = $conn->prepare("INSERT INTO semesters (semester) VALUES (?)");
    $stmt->bind_param('s', $semester);
    executeInsert($stmt, $error_message, $success_message, "Semester added successfully!");
}

// Add module functionality
if (isset($_POST['add_module'])) {
    $module_name = $_POST['module_name'];
    $module_code = $_POST['module_code'];
    $academic_year = $_POST['academic_year'];
    $semester = $_POST['semester'];

    $stmt = $conn->prepare("INSERT INTO modules (module_name, module_code, academic_year, semester, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param('ssii', $module_name, $module_code, $academic_year, $semester);
    executeInsert($stmt, $error_message, $success_message, "Module added successfully!");
}

// Add class functionality
if (isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    $current_year = $_POST['current_year'];
    $semester = $_POST['semester'];
    $number_of_students = $_POST['number_of_students'];
    $class_code = $_POST['class_code'];

    $stmt = $conn->prepare("INSERT INTO classes (class_name, current_year, semester, number_of_students, class_code) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('sssis', $class_name, $current_year, $semester, $number_of_students, $class_code);
    executeInsert($stmt, $error_message, $success_message, "Class added successfully!");
}
// Assign lecturer to module functionality
if (isset($_POST['assign_lecturer'])) {
    $lecturer_id = $_POST['lecturer_id'];
    $module_id = $_POST['module_id'];
    $class_id = $_POST['class_id'];
    $semester_id = $_POST['semester_id'];
    $academic_year_id = $_POST['academic_year_id'];

    // Query to check if the assignment already exists
    $check_query = "
        SELECT * FROM lecturer_modules
        WHERE lecturer_id = ? AND module_id = ? AND class_id = ? AND semester_id = ? AND academic_year_id = ?";
    $stmt = $conn->prepare($check_query);

    if ($stmt) {
        // Bind parameters and execute the check query
        $stmt->bind_param("iiiii", $lecturer_id, $module_id, $class_id, $semester_id, $academic_year_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $error_message = "This lecturer is already assigned to this module for the selected class and semester.";
            } else {
                // Close the statement before re-using it for the insert query
                $stmt->close();

                // Insert query to assign the lecturer to the module
                $insert_query = "
                    INSERT INTO lecturer_modules (lecturer_id, module_id, class_id, semester_id, academic_year_id)
                    VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);

                if ($stmt) {
                    $stmt->bind_param("iiiii", $lecturer_id, $module_id, $class_id, $semester_id, $academic_year_id);

                    if ($stmt->execute()) {
                        $success_message = "Lecturer successfully assigned to the module.";

                        // Fetch details for CSV
                        $lecturer_query = "SELECT name, surname FROM users WHERE id = ?";
                        $lecturer_stmt = $conn->prepare($lecturer_query);
                        $lecturer_stmt->bind_param("i", $lecturer_id);
                        $lecturer_stmt->execute();
                        $lecturer_result = $lecturer_stmt->get_result()->fetch_assoc();
                        $lecturer_name = $lecturer_result['name'] . " " . $lecturer_result['surname'];
                        
                        $module_query = "SELECT module_name FROM modules WHERE id = ?";
                        $module_stmt = $conn->prepare($module_query);
                        $module_stmt->bind_param("i", $module_id);
                        $module_stmt->execute();
                        $module_name = $module_stmt->get_result()->fetch_assoc()['module_name'];
                        
                        $class_query = "SELECT class_name FROM classes WHERE id = ?";
                        $class_stmt = $conn->prepare($class_query);
                        $class_stmt->bind_param("i", $class_id);
                        $class_stmt->execute();
                        $class_name = $class_stmt->get_result()->fetch_assoc()['class_name'];
                        
                        $semester_query = "SELECT semester FROM semesters WHERE id = ?";
                        $semester_stmt = $conn->prepare($semester_query);
                        $semester_stmt->bind_param("i", $semester_id);
                        $semester_stmt->execute();
                        $semester_name = $semester_stmt->get_result()->fetch_assoc()['semester'];
                        
                        $year_query = "SELECT year FROM academic_years WHERE id = ?";
                        $year_stmt = $conn->prepare($year_query);
                        $year_stmt->bind_param("i", $academic_years_id);
                        $year_stmt->execute();
                        $year_name = $year_stmt->get_result()->fetch_assoc()['year'];

                        // Append assignment information to CSV file
                        $csv_line = "$lecturer_name,$module_name,$class_name,$semester_name,$year_name\n";
                        if (file_put_contents('Assign.csv', $csv_line, FILE_APPEND) === false) {
                            $error_message = "Failed to write to CSV file.";
                        }
                    } else {
                        $error_message = "Error assigning lecturer: " . htmlspecialchars($stmt->error);
                    }
                } else {
                    $error_message = "Error preparing insert statement: " . htmlspecialchars($conn->error);
                }
            }
        } else {
            $error_message = "Error checking assignment: " . htmlspecialchars($stmt->error);
        }

        // Close the statement if it was initialized
        $stmt->close();
    } else {
        $error_message = "Error preparing check statement: " . htmlspecialchars($conn->error);
    }
}

// Fetch data for dropdowns with JOINs to retrieve full information
// Fetch academic years with error handling
$academic_years_result = $conn->query("SELECT id, year FROM academic_years ORDER BY year DESC");
if (!$academic_years_result) {
    die("Error fetching academic years: " . $conn->error);
}
$academic_years = $academic_years_result->fetch_all(MYSQLI_ASSOC);

$classes = $conn->query("SELECT id, class_name FROM classes")->fetch_all(MYSQLI_ASSOC);
$lecturers = $conn->query("SELECT id, CONCAT(name, ' ', surname) AS lecturer_name FROM users")->fetch_all(MYSQLI_ASSOC);
$modules = $conn->query("SELECT id, module_name FROM modules")->fetch_all(MYSQLI_ASSOC);
$semesters = $conn->query("SELECT id, semester FROM semesters")->fetch_all(MYSQLI_ASSOC);?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    

    <style>/* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f4f8;
    margin: 0;
    padding: 20px;
    
}

h1 {
    text-align: center;
    color: #333;
}

/* Container */
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Navigation */
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

/* Messages */
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

/* Form Container */
.form-container {
    margin-top: 20px;
}

/* Form Styles */
form {
    background: #ffffff;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
}

.form-title i {
    margin-right: 8px;
    color: #007BFF;
}

.form-group {
    margin-bottom: 15px;
}

.form-group input, 
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-group input:focus, 
.form-group select:focus {
    border-color: #007BFF;
    outline: none;
}

button {
    background-color: #007BFF;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #0056b3;
}

/* Media Queries for Responsive Design */
@media (max-width: 600px) {
    .container {
        padding: 15px;
    }

    h1 {
        font-size: 24px;
    }

    button {
        width: 100%;
    }
}

/* Logout button *//* Centering the logout button */
.logout-container {
    display: flex;              /* Enable flexbox */
    justify-content: center;    /* Center items horizontally */
    margin: 20px 0;            /* Add margin for spacing */
}

/* Logout button */
.logout-btn {
    background-color: #ff5e57;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    margin-left: 10px; /* Optional: can remove this for true centering */
    font-size: 0.9em;
}

.logout-btn:hover {
    background-color: #ff3b30;
}

.logout-btn:active {
    background-color: #e63939;
    transform: scale(0.95);
}


</style>

     
</head>
<body>

<h1>Faculty Admin Dashboard</h1>



<?php if ($error_message): ?>
        <div class="alert error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <nav>
    <button onclick="window.location.href='Add Lecturer.php'">Add Lecturer</button>
    <button onclick="window.location.href='Add Academic Year.php'">Add Academic Year</button>
    <button onclick="window.location.href='Add Semester.php'">Add Semester</button>
    <button onclick="window.location.href='Add Module.php'">Add Module</button>
    <button onclick="window.location.href='Add Class.php'">Add Class</button> 
    <button onclick="window.location.href='Assign Lecturer.php'">Assign Lecturer to Module</button>
    <button onclick="window.location.href='Assign_Lecturer_Role.php'">Assign Lecturer Role</button>
    <button onclick="window.location.href='Add Student.php'">Add Student</button> 


    </nav>






<div class="container">
    <?php if ($error_message) : ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if ($success_message) : ?>
        <div class="success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <nav>
    <a href="Profile.php" class="nav-link">Profile</a>
    <a href="views.php" class="nav-link">Views</a>
    </nav>
    <!-- Log out button -->
    <div class="logout-container">
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

   

    









<!-- Assign Lecturer to Module form, initially hidden -->
<div id="assignLecturerForm" style="display: none;">
    <form method="POST">
        <div class="form-title">
            <i class="fas fa-user-tag"></i> Assign Lecturer to Module
        </div>

        <!-- Lecturer Selection -->
        <div class="form-group">
            <select name="lecturer_id" required>
                <option value="">Select Lecturer</option>
                <?php if (isset($lecturers) && !empty($lecturers)): ?>
                    <?php foreach ($lecturers as $lecturer): ?>
                        <option value="<?php echo $lecturer['id']; ?>"><?php echo $lecturer['lecturer_name']; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No lecturers available</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Module Selection -->
        <div class="form-group">
            <select name="module_id" required>
                <option value="">Select Module</option>
                <?php if (isset($modules) && !empty($modules)): ?>
                    <?php foreach ($modules as $module): ?>
                        <option value="<?php echo $module['id']; ?>"><?php echo $module['module_name']; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No modules available</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Class Selection -->
        <div class="form-group">
            <select name="class_id" required>
                <option value="">Select Class</option>
                <?php if (isset($classes) && !empty($classes)): ?>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No classes available</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Semester Selection -->
        <div class="form-group">
            <select name="semester_id" required>
                <option value="">Select Semester</option>
                <?php if (isset($semesters) && !empty($semesters)): ?>
                    <?php foreach ($semesters as $semester): ?>
                        <option value="<?php echo $semester['id']; ?>"><?php echo $semester['semester']; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No semesters available</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Academic Year Selection -->
        <div class="form-group">
            <select name="academic_year_id" required>
                <option value="">Select Academic Year</option>
                <?php if (isset($academic_years) && !empty($academic_years)): ?>
                    <?php foreach ($academic_years as $year): ?>
                        <option value="<?php echo $year['id']; ?>"><?php echo $year['year']; ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No academic years available</option>
                <?php endif; ?>
            </select>
        </div>

        <!-- Submit Button -->
        <div class="form-group">
            <button type="submit" name="assign_lecturer">Assign Lecturer</button>
        </div>
    </form>
</div>

<!-- JavaScript function to toggle the form visibility -->
<script>
function toggleForm() {
    var form = document.getElementById('assignLecturerForm');
    form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
}
</script>


</body>
</html>

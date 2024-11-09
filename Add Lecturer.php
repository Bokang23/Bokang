<?php
include('db.php');

$success_message = '';
$error_messages = [];
if (isset($_POST['add_lecturer'])) {
    // Ensure that each form field is set before accessing $_POST values
    $lecturer_name = isset($_POST['lecturer_name']) ? $_POST['lecturer_name'] : '';
    $lecturer_surname = isset($_POST['lecturer_surname']) ? $_POST['lecturer_surname'] : '';
    $lecturer_email = isset($_POST['lecturer_email']) ? $_POST['lecturer_email'] : '';
    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    // Validate that the required fields are not empty
    if (!empty($lecturer_name) && !empty($lecturer_surname) && !empty($lecturer_email) && !empty($contact_number) && !empty($username) && !empty($gender)) {

        // Check if the email already exists in the database
        $check_email_query = "SELECT * FROM lecturers WHERE lecturer_email = '$lecturer_email'";
        $result = $conn->query($check_email_query);

        if ($result->num_rows > 0) {
            // If email already exists, show an error message
            echo "Error: Email already exists!";
        } else {
            // If email is unique, proceed with insertion
            $sql = "INSERT INTO lecturers (lecturer_name, lecturer_surname, lecturer_email, contact_number, username, gender) 
                    VALUES ('$lecturer_name', '$lecturer_surname', '$lecturer_email', '$contact_number', '$username', '$gender')";

            // Execute the query
            if ($conn->query($sql) === TRUE) {
                // Success message
                echo "Lecturer added successfully!";
            } else {
                // Error message in case of failure
                echo "Error: " . $conn->error;
            }
        }
    } else {
        // Display a message if any field is missing
        echo "All fields are required!";
    }
}




$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student and Lecturer</title>
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
        <!-- Your main content goes here (navbar, sidebar, etc.) -->
        <a href="admin_dashboard.php" class="logout-btn">Back to Admin</a>
    </div>

    <!-- Add Lecturer Form -->
    <div class="form-container" id="add_lecturer_form">
    <form method="POST">
        <div class="form-title">
            <i class="fas fa-user-plus"></i> Add New Lecturer
        </div>
        <div class="form-group">
            <input type="text" name="lecturer_name" placeholder="Lecturer Name" required>
        </div>
        <div class="form-group">
            <input type="text" name="lecturer_surname" placeholder="Lecturer Surname" required>
        </div>
        <div class="form-group">
            <input type="email" name="lecturer_email" placeholder="Lecturer Email" required>
        </div>
        <div class="form-group">
            <input type="text" name="contact_number" placeholder="Contact Number" required> <!-- Fixed here -->
        </div>
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required> <!-- Fixed here -->
        </div>
        <div class="form-group">
            <select name="gender" id="lecturer_gender" onchange="toggleMaidenName()" required> <!-- Fixed here -->
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group" id="maiden-name-group" style="display: none;">
            <input type="text" name="lecturer_maiden_name" placeholder="Maiden Name">
        </div>
        <button type="submit" name="add_lecturer">Add Lecturer</button>
    </form>
</div>


        <script>
        // Function to toggle the Maiden Name field based on gender selection
        function toggleMaidenName() {
            const gender = document.getElementById('lecturer_gender').value;
            const maidenNameGroup = document.getElementById('maiden-name-group');
            maidenNameGroup.style.display = (gender === 'Female') ? 'block' : 'none';
        }

        // Hide everything else except the form you are working on
        function showForm(formId) {
            const allForms = ['add_lecturer_form', 'add_student_form'];
            allForms.forEach(function(id) {
                document.getElementById(id).style.display = 'none'; // Hide all forms
            });
            document.getElementById(formId).style.display = 'block'; // Show the selected form
        }

        // Call showForm('add_student_form') or showForm('add_lecturer_form') based on the button clicked
    </script>
</body>
</html>

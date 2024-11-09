<?php
session_start();
include 'db.php';

// Fetch all users (lecturers) from the database
$usersResult = $conn->query("SELECT id, name, role FROM users WHERE role IN ('lecturer', 'prl')"); // Fetch only lecturers and principal lecturers
if (!$usersResult) {
    die("Error fetching users: " . $conn->error);
}

// Fetch all roles for assignment (you can add more roles here if needed)
$roles = ['lecturer', 'prl'];

// Assign role form handling
$message = '';  // Initialize the message variable
$messageClass = ''; // Initialize the message class for styling

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that user_id and role are set before proceeding
    if (isset($_POST['user_id']) && isset($_POST['role'])) {
        $userId = $_POST['user_id'];
        $role = $_POST['role'];

        // Check if the user already has the selected role
        $checkRoleQuery = "SELECT role FROM users WHERE id = ?";
        $stmt = $conn->prepare($checkRoleQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user['role'] === $role) {
            $message = "This user already has the role '$role'.";
            $messageClass = "error-message";
        } else {
            // Update the user's role in the database
            $updateQuery = "UPDATE users SET role = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $role, $userId);

            if ($stmt->execute()) {
                $message = "Role '$role' assigned successfully!";
                $messageClass = "success-message";
            } else {
                $message = "Error assigning role: " . $stmt->error;
                $messageClass = "error-message";
            }
        }
        $stmt->close();
    } else {
        $message = "Please select a lecturer and role.";
        $messageClass = "error-message";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Lecturer Role</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f8;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 40px;
            padding: 20px;
            background-color: #e9f5ff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            margin-bottom: 15px;
        }

        form {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #007bff;
            font-size: 1em;
        }

        button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .success-message {
            background-color: #28a745;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .error-message {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
            font-weight: bold;
        }

        .role-lecturer {
            background-color: #28a745;
            color: white;
        }

        .role-principal {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Assign Lecturer Role</h1>

        <div class="section">
            <h2>Assign Role to Lecturer</h2>

            <!-- Success or error message -->
            <?php if ($message != ''): ?>
                <div class="<?php echo $messageClass; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="assign_lecturer_role.php">
                <label for="user_id">Select Lecturer</label>
                <select name="user_id" id="user_id" required>
                    <option value="">Select a lecturer</option>
                    <?php while ($row = $usersResult->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?> - <?php echo ucfirst($row['role']); ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="role">Assign Role</label>
                <select name="role" id="role" required>
                    <option value="">Select a role</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?php echo $role; ?>"><?php echo ucfirst(str_replace('_', ' ', $role)); ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Assign Role</button>
            </form>
        </div>

        <div class="nav-links">
            <a href="admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

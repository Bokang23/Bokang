<?php
session_start();
include 'db.php';

// Fetch all users
$usersResult = $conn->query("SELECT * FROM users");
if (!$usersResult) {
    die("Error fetching users: " . $conn->error);
}

// Fetch all modules
$modulesResult = $conn->query("SELECT * FROM modules");
if (!$modulesResult) {
    die("Error fetching modules: " . $conn->error);
}

// Fetch all semesters from the semesters table
$semestersResult = $conn->query("SELECT * FROM semesters");
if (!$semestersResult) {
    die("Error fetching semesters: " . $conn->error);
}

// Modified query to work with existing tables and relationships
$lecturerAssignmentsQuery = "
    SELECT 
        u.id AS lecturer_id,
        u.name AS lecturer_name,
        u.role AS lecturer_role,
        m.module_name,
        c.class_name,
        s.semester
    FROM users u
    LEFT JOIN lecturer_modules lm ON lm.lecturer_id = u.id
    LEFT JOIN modules m ON m.id = lm.module_id
    LEFT JOIN classes c ON c.id = lm.class_id
    LEFT JOIN semesters s ON s.id = lm.semester_id
    WHERE u.role IN ('lecturer', 'principal_lecturer')
    ORDER BY u.name";

$lecturerAssignmentsResult = $conn->query($lecturerAssignmentsQuery);
if (!$lecturerAssignmentsResult) {
    die("Error fetching lecturer assignments: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Admin Dashboard</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #007bff;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #ffffff;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody tr:hover {
            background-color: #e2e2e2;
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
        <h1>Admin Dashboard</h1>

        <div class="section">
            <h2><i class="fas fa-users"></i> All Users</h2>
            <?php if ($usersResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>User Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $usersResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['contact']; ?></td>
                                <td><?php echo $row['role']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No users found.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2><i class="fas fa-book-open"></i> All Modules</h2>
            <?php if ($modulesResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Module ID</th>
                            <th>Module Name</th>
                            <th>Module Code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $modulesResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['module_name']; ?></td>
                                <td><?php echo $row['module_code']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No modules found.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2><i class="fas fa-calendar-alt"></i> Semesters</h2>
            <?php if ($semestersResult->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $semestersResult->fetch_assoc()): ?>
                        <li><?php echo $row['semester']; ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="no-data">No semesters found.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2><i class="fas fa-chalkboard-teacher"></i> Lecturer Assignments</h2>
            <?php if ($lecturerAssignmentsResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Lecturer Name</th>
                            <th>Role</th>
                            <th>Module</th>
                            <th>Class</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $lecturerAssignmentsResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['lecturer_name']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $row['lecturer_role'] === 'lecturer' ? 'role-lecturer' : 'role-principal'; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $row['lecturer_role'])); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['module_name'] ?? 'Not Assigned'; ?></td>
                                <td><?php echo $row['class_name'] ?? 'Not Assigned'; ?></td>
                                <td><?php echo $row['semester'] ?? 'Not Assigned'; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No lecturer assignments found.</p>
            <?php endif; ?>
        </div>

        <div class="nav-links">
            <a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

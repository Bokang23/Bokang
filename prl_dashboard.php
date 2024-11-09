<?php
session_start();
include 'db.php'; // Ensure to include your database connection here

$lecturer_id = $_SESSION['user_id']; // Assuming you store the logged-in lecturer's ID in the session
$classes = $conn->query("SELECT class_name FROM classes");
$modules = $conn->query("SELECT module_name FROM modules"); // Ensure you have a modules table

$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_name'];
    $module_name = $_POST['module_name'];
    $challenges = $_POST['challenges'];
    $recommendations = $_POST['recommendations'];

    // Check if the same report already exists in the database
    $check_query = $conn->prepare("SELECT COUNT(*) FROM lecturer_reports WHERE lecturer_id = ? AND class_name = ? AND module_name = ? AND challenges = ? AND recommendations = ?");
    $check_query->bind_param("issss", $lecturer_id, $class_name, $module_name, $challenges, $recommendations);
    $check_query->execute();
    $check_query->bind_result($count);
    $check_query->fetch();
    $check_query->close();

    if ($count > 0) {
        // If the report already exists, set an error message
        $message = "You have already submitted this report.";
    } else {
        // Prepare the SQL statement to insert the new report
        $stmt = $conn->prepare("INSERT INTO lecturer_reports (lecturer_id, class_name, module_name, challenges, recommendations) VALUES (?, ?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $stmt->bind_param("issss", $lecturer_id, $class_name, $module_name, $challenges, $recommendations);

        // Execute the statement
        if ($stmt->execute()) {
            $message = "Report submitted successfully!";
        } else {
            $message = "Error saving report: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetching last saved data to prevent pre-population (if any)
$last_report_query = $conn->prepare("SELECT * FROM lecturer_reports WHERE lecturer_id = ? ORDER BY report_date DESC LIMIT 1");
if ($last_report_query === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$last_report_query->bind_param("i", $lecturer_id);
$last_report_query->execute();
$last_report = $last_report_query->get_result()->fetch_assoc();
$last_report_query->close();

// Prepare variables for the form
$last_class_name = $last_report ? $last_report['class_name'] : '';
$last_module_name = $last_report ? $last_report['module_name'] : '';
$last_challenges = $last_report ? $last_report['challenges'] : '';
$last_recommendations = $last_report ? $last_report['recommendations'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lecturer Weekly Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f7f9 30%, #e9eff1 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .container {
            background: #fff;
            padding: 30px;
            width: 600px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            position: relative;
            transition: transform 0.2s;
        }
        h2 {
            margin: 0;
            color: #333;
            text-align: center;
            font-size: 28px;
            position: relative;
        }
        h2::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: #007BFF;
            margin: 10px auto;
            border-radius: 5px;
        }
        .form-group {
            margin-top: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .form-group select, .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .form-group select:focus, .form-group input:focus, .form-group textarea:focus {
            border-color: #007BFF;
            outline: none;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 12px 15px;
            border: none;
            color: #fff;
            background-color: #007BFF;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.2s;
        }
        .submit-btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
        .message {
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
            color: green;
        }
        .error-message {
            color: red;
        }
        
        /* Logout button */
        .logout-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .logout-btn {
            background-color: #ff5e57;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 10px;
            font-size: 0.9em;
            display: flex;
            align-items: center;
        }

        .logout-btn i {
            margin-right: 5px;
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

<div class="container">
    <h2><i class="fas fa-file-alt"></i> Weekly Lecturer Report</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="class_name"><i class="fas fa-chalkboard-teacher"></i> Class</label>
            <select name="class_name" required>
                <option value="">Select Class</option>
                <?php while ($row = $classes->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['class_name']) ?>" <?= $last_class_name === $row['class_name'] ? 'disabled' : '' ?>><?= htmlspecialchars($row['class_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="module_name"><i class="fas fa-book"></i> Module</label>
            <select name="module_name" required>
                <option value="">Select Module</option>
                <?php while ($row = $modules->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($row['module_name']) ?>" <?= $last_module_name === $row['module_name'] ? 'disabled' : '' ?>><?= htmlspecialchars($row['module_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="challenges"><i class="fas fa-exclamation-triangle"></i> Challenges</label>
            <textarea name="challenges" id="challenges" rows="4" required><?= htmlspecialchars($last_challenges) ?></textarea>
        </div>

        <div class="form-group">
            <label for="recommendations"><i class="fas fa-comments"></i> Recommendations</label>
            <textarea name="recommendations" id="recommendations" rows="4" required><?= htmlspecialchars($last_recommendations) ?></textarea>
        </div>

        <button type="submit" class="submit-btn">Submit Report</button>
    </form>
    
    <!-- Logout Button -->
    <div class="logout-container">
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

</body>
</html>

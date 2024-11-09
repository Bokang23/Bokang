<?php
session_start();
include 'db.php';

$class_selected = false;
$students = [];
$message = "";

// Handle class selection
if (isset($_POST['select_class'])) {
    $class_name = $_POST['class_name'];
    $lecturer_name = $_POST['lecturer_name']; // Add lecturer name retrieval
    $class_selected = true;

    // Fetch students in the selected class
    $stmt = $conn->prepare("SELECT id, name FROM students WHERE class_name = ?");

    // Check if the statement preparation was successful
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("s", $class_name);
    $stmt->execute();
    $students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Handle attendance form submission
if (isset($_POST['save_data'])) {
    $lecturer_name = $_POST['lecturer_name'];
    $class_name = $_POST['class_name'];
    $present_students = implode(", ", $_POST['present_students'] ?? []);
    $chapter = $_POST['chapter'];
    $learning_outcomes = $_POST['learning_outcomes'];

    // Check if this record already exists
    $checkQuery = $conn->prepare("SELECT * FROM lecturer_roles WHERE lecturer_name = ? AND class_name = ?");

    // Check if the statement preparation was successful
    if ($checkQuery === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $checkQuery->bind_param("ss", $lecturer_name, $class_name);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows == 0) { // Only insert if no duplicate exists
        $stmt = $conn->prepare("INSERT INTO lecturer_roles (lecturer_name, class_name, present_students, chapter, learning_outcomes) VALUES (?, ?, ?, ?, ?)");

        // Check if the statement preparation was successful
        if ($stmt === false) {
            die("Error preparing statement: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("sssss", $lecturer_name, $class_name, $present_students, $chapter, $learning_outcomes);
        if ($stmt->execute()) {
            $message = "Attendance data saved successfully!";
        } else {
            $message = "Error saving attendance data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Attendance record already exists for this class and lecturer.";
    }
    $checkQuery->close();
}

// Fetch available lecturers and classes
$lecturers = $conn->query("SELECT id, name FROM users WHERE role='lecturer'");
$classes = $conn->query("SELECT class_name FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lecturer Roles</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing styles */
        /* Additional improvements can be made here */
    </style>
    <script>
        function selectAllStudents() {
            const checkboxes = document.querySelectorAll('input[name="present_students[]"]');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
            });
        }

        function validateForm() {
            const selectedStudents = document.querySelectorAll('input[name="present_students[]"]:checked').length;
            if (selectedStudents === 0) {
                alert("Please select at least one student as present.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<div class="container">
    <h2><h2>
        <?php if ($class_selected): ?>
            <i class="fas fa-chalkboard-teacher"></i> 
            Class: <?= htmlspecialchars($class_name) ?>
        <?php else: ?>
            Lecturer Roles
        <?php endif; ?>
    </h2></h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (!$class_selected): ?>
        <!-- Form to select class -->
        <form method="POST">
            <div class="form-group">
                <label for="class_name"><i class="fas fa-chalkboard-teacher"></i> Select Class</label>
                <select name="class_name" required>
                    <option value="">Select Class</option>
                    <?php while ($row = $classes->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['class_name']) ?>"><?= htmlspecialchars($row['class_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="lecturer_name"><i class="fas fa-user-tie"></i> Select Lecturer</label>
                <select name="lecturer_name" required>
                    <option value="">Select Lecturer</option>
                    <?php while ($row = $lecturers->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="submit-btn" name="select_class"><i class="fas fa-check"></i> Select Class</button>
        </form>

        <nav>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log out</a>
        </nav>

    <?php else: ?>
        <!-- Form to mark attendance and record data -->
        <form method="POST" onsubmit="return validateForm()">
            <input type="hidden" name="class_name" value="<?= htmlspecialchars($class_name) ?>">
            <input type="hidden" name="lecturer_name" value="<?= htmlspecialchars($lecturer_name) ?>">

            <div class="form-group">
                <label>Mark Present Students</label>
                <?php foreach ($students as $student): ?>
                    <label>
                        <input type="checkbox" name="present_students[]" value="<?= htmlspecialchars($student['name']) ?>">
                        <?= htmlspecialchars($student['name']) ?>
                    </label><br>
                <?php endforeach; ?>
                <div class="select-all" onclick="selectAllStudents()"><i class="fas fa-check-square"></i> Select All</div>
            </div>

            <div class="form-group">
                <label for="chapter"><i class="fas fa-book"></i> Chapter</label>
                <input type="text" name="chapter" id="chapter" required>
            </div>

            <div class="form-group">
                <label for="learning_outcomes"><i class="fas fa-clipboard-check"></i> Learning Outcomes</label>
                <textarea name="learning_outcomes" id="learning_outcomes" rows="3" required></textarea>
            </div>

            <button type="submit" class="submit-btn" name="save_data"><i class="fas fa-save"></i> Save Data</button>
            <div class="logout-container">
            <button type="button" onclick="window.location.href='lecturer_dashboard.php';" class="submit-btn" style="background-color: #f0ad4e; color: white;">
                <i class="fas fa-arrow-left"></i> Back to Class Selection
            </button>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

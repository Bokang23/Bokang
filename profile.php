<?php
session_start();
include 'db.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch the admin's profile information from the database
$admin_id = $_SESSION['user_id']; // Assuming user_id is stored in session
$stmt = $conn->prepare("SELECT name, surname, email, contact, username, gender FROM users WHERE id = ?");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-...your_hash_here..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8; /* Softer background color */
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #ffffff; /* White background for container */
            padding: 30px; /* Increased padding */
            border-radius: 10px; /* More rounded corners */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Slightly stronger shadow */
            max-width: 600px; /* Max width for the container */
            margin: 30px auto; /* Center the container with spacing */
            text-align: left; /* Align text to the left */
        }

        h1 {
            color: #1a73e8; /* Heading color */
            text-align: center; /* Centered heading */
            margin-bottom: 20px; /* Spacing below the heading */
        }

        .profile-detail {
            display: flex; /* Flex display for icon and text */
            align-items: center; /* Center items vertically */
            margin: 10px 0; /* Spacing between profile details */
            padding: 10px; /* Padding for each detail */
            border-bottom: 1px solid #e0e0e0; /* Bottom border for separation */
        }

        .profile-detail:last-child {
            border-bottom: none; /* Remove border from last item */
        }

        .profile-detail i {
            font-size: 1.5em; /* Larger icon size */
            color: #1a73e8; /* Icon color */
            margin-right: 10px; /* Space between icon and text */
        }

        .nav-links {
            text-align: center; /* Centered navigation links */
            margin: 20px 0; /* Spacing around navigation */
        }

        .nav-links a {
            display: inline-block; /* Inline block for links */
            margin: 0 15px; /* Spacing between links */
            text-decoration: none; /* Remove underline */
            color: #1a73e8; /* Link color */
            font-weight: 600; /* Bold links */
            transition: color 0.3s; /* Transition for color change */
        }

        .nav-links a:hover {
            color: #1559b3; /* Darker color on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Profile</h1>
        <div class="profile-detail">
            <i class="fas fa-user"></i>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
        </div>
        <div class="profile-detail">
            <i class="fas fa-user-tag"></i>
            <p><strong>Surname:</strong> <?php echo htmlspecialchars($admin['surname']); ?></p>
        </div>
        <div class="profile-detail">
            <i class="fas fa-envelope"></i>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
        </div>
        <div class="profile-detail">
            <i class="fas fa-phone-alt"></i>
            <p><strong>Contact:</strong> <?php echo htmlspecialchars($admin['contact']); ?></p>
        </div>
        <div class="profile-detail">
            <i class="fas fa-user-circle"></i>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($admin['username']); ?></p>
        </div>
        <div class="profile-detail">
            <i class="fas fa-venus-mars"></i>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($admin['gender']); ?></p>
        </div>
        
        <div class="nav-links">
            <a href="admin_dashboard.php"><i class="fas fa-arrow-left"></i> Back to Dashboard</a> <!-- Link back to dashboard -->
        </div>
    </div>
</body>
</html>

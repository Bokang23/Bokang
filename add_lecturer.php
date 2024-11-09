<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Space Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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

<div class="form-container">

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
                <input type="text" name="lecturer_contact" placeholder="Contact Number" required>
            </div>
            <div class="form-group">
                <input type="text" name="lecturer_username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <select name="lecturer_gender" required>
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
</body>
</html>
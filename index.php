<?php
// index.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Unit Attendance Register</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <style>
        /* Reset some default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
}

/* Navigation Bar */
.navbar {
    background-color: #333;
    color: white;
    padding: 15px 0;
}

.navbar ul {
    list-style: none;
    text-align: center;
}

.navbar ul li {
    display: inline;
    margin: 0 15px;
}

.navbar ul li a {
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    transition: background-color 0.3s;
}

.navbar ul li a:hover {
    background-color: #555;
}

/* Form Container */
.form-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.form-container h2 {
    margin-bottom: 20px;
    text-align: center;
}

/* Form Group */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Submit Button */
.btn {
    width: 100%;
    padding: 10px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #555;
}

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Mark Attendance</a></li>
            <li><a href="#">View Records</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>IT Unit Attendance Register</h2>
        <form action="submit_attendance.php" method="post"  autocomplete="off">
            <div class="form-group">
                <label for="staff_id">Staff ID:</label>
                <input type="text" id="staff_id" name="staff_id" required>
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name"  required>
            </div>
            <label for="shift_type">Shift Type:</label>
            <input type="radio" id="shift_type_day" name="shift_type" value="day" checked>
            <label for="shift_type_day">Day Shift</label>
            <input type="radio" id="shift_type_night" name="shift_type" value="night">
            <label for="shift_type_night">Night Shift</label>
            <div class="form-group">
                <input type="submit" value="Submit Attendance"  class="btn">
            </div>
        </form>
    </div>
</body>
</html>


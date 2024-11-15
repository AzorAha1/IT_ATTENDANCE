<?php
// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_db";

// Connect to MySQL server (without selecting a database yet)
$conn = new mysqli($servername, $username, $password);

// Check connection to MySQL server
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);

// **Comment out the table creation part after the initial setup:**
// // Check if the `staff_attendance` table exists, and create it if it doesn't
// $table_check_query = "SHOW TABLES LIKE 'staff_attendance'";
// $table_exists = $conn->query($table_check_query);

// if ($table_exists->num_rows == 0) {
//     $create_table_query = "CREATE TABLE staff_attendance (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         staff_id VARCHAR(50) NOT NULL,
//         name VARCHAR(100) NOT NULL,
//         date DATE NOT NULL,
//         shift_type ENUM('day', 'night') NOT NULL,
//         time_in TIME,
//         time_out TIME,
//         status ENUM('Present', 'Absent') DEFAULT 'Absent'
//     )";

//     if ($conn->query($create_table_query) === TRUE) {
//         echo "Table 'staff_attendance' created successfully.<br>";
//     } else {
//         die("Error creating table: " . $conn->error);
//     }
// }

// **Test the connection and permissions (optional, remove if not needed)**
// try {
//     $test_insert = "INSERT INTO staff_attendance (staff_id, name, date, shift_type, time_in, status) 
//                     VALUES ('TEST01', 'Test User', CURDATE(), 'day', CURTIME(), 'Present')";
//     if ($conn->query($test_insert)) {
//         echo "Database connection and permissions verified successfully.<br>";
//         // Clean up test data
//         $conn->query("DELETE FROM staff_attendance WHERE staff_id = 'TEST01'");
//     } else {
//         echo "Error: Unable to write to database. " . $conn->error . "<br>";
//     }
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage() . "<br>";
// }
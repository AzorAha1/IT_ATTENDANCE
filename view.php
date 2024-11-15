<?php
// display_attendance.php
session_start();

// Optional: Restrict access to authenticated users/admins
// Uncomment the following lines if you have implemented authentication
/*
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
*/

include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Records</title>
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

/* Table Container */
.table-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background: white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.table-container h2 {
    margin-bottom: 20px;
    text-align: center;
}

/* Form for CSV Download */
.form-group {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
}

.form-group input[type="date"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    margin-right: 10px;
}

.btn {
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

/* Attendance Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ccc;
}

th {
    background-color: #f2f2f2;
}

/* Status Styles */
.status-present {
    background-color: #c8e6c9; /* Light green */
}

.status-absent {
    background-color: #ffcdd2; /* Light red */
}

.status-late {
    background-color: #ffecb3; /* Light yellow */
}

/* Link for Download All */
.table-container a {
    display: block;
    text-align: center;
    margin-top: 10px;
    text-decoration: none;
    color: #333;
    font-size: 16px;
}

.table-container a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Mark Attendance</a></li>
            <li><a href="#">View Records</a></li>
            <!-- Add a logout link if implementing authentication -->
            <!-- <li><a href="logout.php">Logout</a></li> -->
        </ul>
    </nav>

    <div class="table-container">
        <h2>Attendance Records</h2>

        <!-- Date Selection Form for CSV Export -->
        <form action="export_csv.php" method="get" class="form-group"> 
            <input type="date" id="export_date" name="export_date" required>
            <input type="submit" value="Download CSV" class="btn btn-download">
        </form>
        <a href="downlaod.php" style="background: black; color: white; width: 120px;
        border-radius: 10px; padding: 10px">Download All</a>

        <?php
        // Fetch all attendance data or filtered by date if set
        // Optional: You can also display records filtered by date here

        // For initial display, show all records
        $sql = "SELECT * FROM staff_attendance ORDER BY date DESC, time_in DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>";
            // Output data for each row
            while($row = $result->fetch_assoc()) {
                // Determine status class
                // $status_class = '';
                // if($row['status'] == 'Present') {
                //     $status_class = 'status-present';
                // } elseif($row['status'] == 'Absent') {
                //     $status_class = 'status-absent';
                // } elseif($row['status'] == 'Late') {
                //     $status_class = 'status-late';
                // }
                // comment for status until further verification of why its needed
                // <td data-label='Status' class='{$status_class}'>{$row['status']}</td>
                echo "<tr>
                        <td data-label='Staff ID'>{$row['staff_id']}</td>
                        <td data-label='Name'>{$row['name']}</td>
                        <td data-label='Date'>{$row['date']}</td>
                        
                        <td data-label='Time In'>{$row['time_in']}</td>
                        <td data-label='Time Out'>{$row['time_out']}</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p>No attendance records found.</p>";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>

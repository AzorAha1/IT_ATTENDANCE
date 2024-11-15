<?php
// select_time.php

include 'db_connect.php';

// Initialize variables
$staff_id = '';
$date = date('Y-m-d');

// Retrieve Staff ID from GET or POST
if (isset($_GET['staff_id'])) {
    $staff_id = mysqli_real_escape_string($conn, trim($_GET['staff_id']));
} elseif (isset($_POST['staff_id'])) {
    $staff_id = mysqli_real_escape_string($conn, trim($_POST['staff_id']));
} else {
    echo "Staff ID not provided.";
    exit;
}

// Handle form submission for Time In or Time Out
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['time_in'])) {
        // Update time_in with current time
        $time_in = date('H:i:s'); // Current server time
        $stmt_update = $conn->prepare("UPDATE staff_attendance SET time_in = ? WHERE staff_id = ? AND date = ?");
        $stmt_update->bind_param("sss", $time_in, $staff_id, $date);
        if ($stmt_update->execute()) {
            echo "<script>
                    alert('Time In recorded successfully at $time_in.');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        } else {
            echo "Error updating Time In: " . $stmt_update->error;
            exit;
        }
    } elseif (isset($_POST['time_out'])) {
        // Update time_out with current time
        $time_out = date('H:i:s'); // Current server time
        $stmt_update = $conn->prepare("UPDATE staff_attendance SET time_out = ? WHERE staff_id = ? AND date = ?");
        $stmt_update->bind_param("sss", $time_out, $staff_id, $date);
        if ($stmt_update->execute()) {
            echo "<script>
                    alert('Time Out recorded successfully at $time_out.');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        } else {
            echo "Error updating Time Out: " . $stmt_update->error;
            exit;
        }
    }
}

// Fetch the attendance record for the staff member today
$stmt = $conn->prepare("SELECT time_in, time_out FROM staff_attendance WHERE staff_id = ? AND date = ?");
$stmt->bind_param("ss", $staff_id, $date);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($time_in, $time_out);
    $stmt->fetch();

    // Determine which form to display
    $display_time_in = false;
    $display_time_out = false;

    if (is_null($time_in) || $time_in == '') {
        $display_time_in = true;
    } elseif (is_null($time_out) || $time_out == '') {
        $display_time_out = true;
    } else {
        // Both time_in and time_out are set
        echo "<script>
                alert('Attendance for today has already been recorded.');
                window.location.href = 'index.php';
              </script>";
        exit;
    }
} else {
    // No record found, redirect to index.php
    echo "<script>
            alert('No attendance record found. Please submit attendance first.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Time</title>
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
    margin-top: 20px;
}

.form-group input[type="submit"] {
    padding: 10px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    width: 100%; /* Full-width button */
}

.form-group input[type="submit"]:hover {
    background-color: #555;
}

/* Alert Styles */
.alert {
    color: red;
    text-align: center;
    margin: 20px 0;
}

/* Media Queries */
@media (max-width: 600px) {
    .navbar ul li {
        display: block; /* Stack navigation items vertically */
        margin: 10px 0; /* Add vertical margin */
    }

    .form-container {
        margin: 20px; /* Reduced margin for smaller screens */
        padding: 15px; /* Reduced padding */
    }

    .form-group input[type="submit"] {
        padding: 12px; /* Slightly increased padding for better touch target */
        font-size: 18px; /* Larger font size for readability */
    }
}

@media (max-width: 400px) {
    .form-container {
        padding: 10px; /* Further reduce padding for very small screens */
    }

    .form-group input[type="submit"] {
        font-size: 16px; /* Adjust font size */
    }
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
        <h2>Record Time</h2>
        <?php if ($display_time_in): ?>
            <form action="select_time.php" method="post">
                <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
                <p>Click the button below to record your <strong>Time In</strong>.</p>
                <div class="form-group">
                    <input type="submit" name="time_in" value="Record Time In" class="btn">
                </div>
            </form>
        <?php elseif ($display_time_out): ?>
            <form action="select_time.php" method="post">
                <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
                <p>Click the button below to record your <strong>Time Out</strong>.</p>
                <div class="form-group">
                    <input type="submit" name="time_out" value="Record Time Out" class="btn">
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

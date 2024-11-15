<?php
// time.php

session_start();

// Include database connection
include 'db_connect.php';

// Check if Staff ID and Name are set in session
if (!isset($_SESSION['staff_id']) || !isset($_SESSION['name'])) {
    // Redirect to index.php
    header("Location: index.php");
    exit;
}

$staff_id = $_SESSION['staff_id'];
$name = $_SESSION['name'];
$date = date('Y-m-d');

// Fetch attendance record for today
$stmt = $conn->prepare("SELECT * FROM staff_attendance WHERE staff_id = ? AND date = ?");
$stmt->bind_param("ss", $staff_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$attendance = $result->fetch_assoc();

if ($attendance) {
    // Record exists
    if (empty($attendance['time_in'])) {
        // Time In is not set, show Time In form
        $action = 'mark_time_in.php';
        $time_field = 'time_in';
        $button_label = 'Mark Time In';
        $display_time_in = true;
        $display_time_out = false;
    } elseif (empty($attendance['time_out'])) {
        // Time In is set, Time Out is not set, show Time Out form
        $action = 'mark_time_out.php';
        $time_field = 'time_out';
        $button_label = 'Mark Time Out';
        $display_time_in = false;
        $display_time_out = true;
    } else {
        // Both Time In and Time Out are set, display message
        $action = '';
        $time_field = '';
        $button_label = '';
        $display_time_in = false;
        $display_time_out = false;
        $message = "Attendance has already been marked for today.";
    }
} else {
    // No record exists, show Time In form
    $action = 'mark_time_in.php';
    $time_field = 'time_in';
    $button_label = 'Mark Time In';
    $display_time_in = true;
    $display_time_out = false;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Time <?php echo $display_time_out ? 'Out' : ($display_time_in ? 'In' : ''); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <ul>
            <li><a href="index.php">Mark Attendance</a></li>
            <li><a href="display_attendance.php">View Records</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <?php if ($display_time_in || $display_time_out): ?>
            <h2>Mark Time <?php echo $display_time_out ? 'Out' : 'In'; ?></h2>
            <form action="<?php echo $action; ?>" method="post">
                <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($staff_id); ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <div class="form-group">
                    <label for="<?php echo $time_field; ?>">Time <?php echo ucfirst(str_replace('_', ' ', $time_field)); ?>:</label>
                    <input type="time" id="<?php echo $time_field; ?>" name="<?php echo $time_field; ?>" required>
                </div>

                <div class="form-group">
                    <input type="submit" value="<?php echo $button_label; ?>" class="btn">
                </div>
            </form>
        <?php else: ?>
            <h2>Attendance Status</h2>
            <p><?php echo $message; ?></p>
            <a href="index.php" class="btn">Back to Home</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// submit_attendance.php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = mysqli_real_escape_string($conn, trim($_POST['staff_id']));
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $shift_type = mysqli_real_escape_string($conn, trim($_POST['shift_type']));

    if (empty($staff_id) || empty($name)) {
        echo "<script>alert('All fields are required.');window.location.href='index.php';</script>";
        exit;
    }

    $current_time = date('H:i:s');
    $current_hour = (int)date('H');
    $current_date = date('Y-m-d');

    // Function to find the most recent night shift record
    function findNightShiftRecord($conn, $staff_id, $name) {
        // Look for records from yesterday and today
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $today = date('Y-m-d');
        
        $stmt = $conn->prepare(
            "SELECT id, date, time_in, time_out 
            FROM staff_attendance 
            WHERE staff_id = ? 
            AND name = ? 
            AND shift_type = 'night'
            AND date IN (?, ?)
            AND time_out IS NULL
            ORDER BY date DESC, time_in DESC
            LIMIT 1"
        );
        
        $stmt->bind_param("ssss", $staff_id, $name, $yesterday, $today);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Handle night shift logic
    if ($shift_type == 'night') {
        // For sign in after 8 PM (20:00)
        if ($current_hour >= 20) {
            // Check if there is already an active night shift for this staff
            $night_record = findNightShiftRecord($conn, $staff_id, $name);
            
            if ($night_record) {
                echo "<script>alert('You are already signed in for the night shift. Please sign out first.');window.location.href='index.php';</script>";
            } else {
                // This is a night shift sign in
                $insert_stmt = $conn->prepare(
                    "INSERT INTO staff_attendance (staff_id, name, date, shift_type, time_in) 
                    VALUES (?, ?, ?, ?, ?)"
                );
                $insert_stmt->bind_param("sssss", $staff_id, $name, $current_date, $shift_type, $current_time);
                $insert_stmt->execute();
                echo "<script>alert('Night shift Time In marked successfully.');window.location.href='index.php';</script>";
            }
        }
        // For sign out before 8 AM (08:00) the next day
        else if ($current_hour < 8) {
            // Look for an unclosed night shift record
            $night_record = findNightShiftRecord($conn, $staff_id, $name);
            
            if ($night_record) {
                // Update the existing night shift record with time_out
                $update_stmt = $conn->prepare(
                    "UPDATE staff_attendance 
                    SET time_out = ? 
                    WHERE id = ?"
                );
                $update_stmt->bind_param("si", $current_time, $night_record['id']);
                $update_stmt->execute();
                echo "<script>alert('Night shift Time Out marked successfully.');window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('No active night shift found to sign out from.');window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Night shift sign-in is only allowed after 8 PM, and sign-out before 8 AM.');window.location.href='index.php';</script>";
        }
    } else {
        // Handle day shift logic
        // Look for existing record for today
        $stmt = $conn->prepare(
            "SELECT id, time_in, time_out 
            FROM staff_attendance 
            WHERE staff_id = ? 
            AND date = ? 
            AND shift_type = 'day'"
        );
        $stmt->bind_param("ss", $staff_id, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $record = $result->fetch_assoc();

        if ($record) {
            if ($record['time_in'] && !$record['time_out']) {
                $update_stmt = $conn->prepare(
                    "UPDATE staff_attendance 
                    SET time_out = ? 
                    WHERE id = ?"
                );
                $update_stmt->bind_param("si", $current_time, $record['id']);
                $update_stmt->execute();
                echo "<script>alert('Day shift Time Out marked successfully.');window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Day shift attendance for today already completed.');window.location.href='index.php';</script>";
            }
        } else {
            // Only allow day shift sign-in during day hours (8 AM to 8 PM)
            if ($current_hour >= 8 && $current_hour < 20) {
                $insert_stmt = $conn->prepare(
                    "INSERT INTO staff_attendance (staff_id, name, date, shift_type, time_in) 
                    VALUES (?, ?, ?, ?, ?)"
                );
                $insert_stmt->bind_param("sssss", $staff_id, $name, $current_date, $shift_type, $current_time);
                $insert_stmt->execute();
                echo "<script>alert('Day shift Time In marked successfully.');window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Day shift sign-in is only allowed between 8 AM and 8 PM.');window.location.href='index.php';</script>";
            }
        }
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit;
}
?>
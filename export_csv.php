<?php
// export_csv.php
include 'db_connect.php';

// Fetch attendance data
$sql = "SELECT * FROM staff_attendance ORDER BY date DESC, time_in DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Set headers to download file rather than display
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=attendance_records.csv');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output the column headings
    fputcsv($output, array('Staff ID', 'Name', 'Date', 'Time In', 'Time Out'));

    // Output the data
    while($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
} else {
    echo "No attendance records found.";
}

$conn->close();
?>

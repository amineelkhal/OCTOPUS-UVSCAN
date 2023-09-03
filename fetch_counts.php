<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uviscan";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch today's entries
$result_today = $conn->query("SELECT COUNT(*) AS count FROM `entrances` WHERE DATE(`entry_date`) = CURDATE()");
$row_today = $result_today->fetch_assoc();
$today_entries = $row_today['count'];

// Fetch this month's entries
$result_month = $conn->query("SELECT COUNT(*) AS count FROM `entrances` WHERE MONTH(`entry_date`) = MONTH(CURDATE()) AND YEAR(`entry_date`) = YEAR(CURDATE())");
$row_month = $result_month->fetch_assoc();
$month_entries = $row_month['count'];

// Output as JSON
echo json_encode([
    'today_entries' => $today_entries,
    'month_entries' => $month_entries
]);

$conn->close();
?>

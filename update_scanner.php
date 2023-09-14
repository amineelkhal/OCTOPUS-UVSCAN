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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scannerid = $_POST['scannerid'];
    $contrast = $_POST['contrast'];
    $brightness = $_POST['brightness'];
    $hist_eq_intensity = $_POST['hist_eq_intensity'];
    $ip_address = $_POST['ip_address'];
    $capture_duration = $_POST['capture_duration'];
    $crop_pixels = $_POST['crop_pixels'];

    $sql = "UPDATE scanner SET contrast=?, brightness=?, hist_eq_intensity=?, ip_address=?, duration=?, crop=? WHERE scannerid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ddssiid", $contrast, $brightness, $hist_eq_intensity, $ip_address, $capture_duration, $crop_pixels, $scannerid);
    $stmt->execute();
    $stmt->close();

    header('Location: scanners.php'); // Redirect back to the main page after updating
}
?>

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

$scannerid = isset($_GET['scannerid']) ? $_GET['scannerid'] : null;

if($scannerid) {
    $sql = "SELECT contrast, brightness, hist_eq_intensity, picturename, ip_address, duration, crop FROM scanner WHERE scannerid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $scannerid);  // Assuming scannerid is an integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found for the provided scannerid.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No scannerid provided.']);
}

$conn->close();
?>

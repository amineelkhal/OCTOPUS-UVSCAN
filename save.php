<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uviscan";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ( empty($_GET["plate"]) ) $_GET["plate"] = "No plate";
if ( empty($_GET["description"]) ) $_GET["description"] = "No description";

$sql = "INSERT INTO entrances VALUES (NULL, ". $_GET["scannerId"] .", '". $_GET["plate"] ."', '". $_GET["picture"] ."', '". $_GET["scan"] ."', now(), '". $_GET["description"] ."', '". $_GET["color"] ."', '". $_GET["category"] ."', '". $_GET["mark"] ."', '". $_GET["bounds"] ."', 0, NOW())";

if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);


$im = imagecreatefromjpeg($_GET['picture']);

$width = $_GET['width'];
$height = $_GET['height'];
$im2 = imagecrop($im, ['x' => $_GET['x'], 'y' => $_GET['y'], 'width' => $width, 'height' => $height]);
if ($im2 !== FALSE) {
    imagepng($im2, $_GET['picture'].".png");
    imagedestroy($im2);
}
imagedestroy($im);

?>

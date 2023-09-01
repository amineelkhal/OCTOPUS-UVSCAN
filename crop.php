<?php
$im = imagecreatefromjpeg($_GET['path']);

$width = $_GET['width'];
$height = $_GET['height'];
$im2 = imagecrop($im, ['x' => $_GET['x'], 'y' => $_GET['y'], 'width' => $width, 'height' => $height]);
if ($im2 !== FALSE) {
    imagepng($im2, $_GET['id']."-plate.png");
    imagedestroy($im2);
}
imagedestroy($im);
?>
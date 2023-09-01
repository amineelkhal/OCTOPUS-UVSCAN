<?php

$imageArray = [];
$totalGrab = 50;
$width = 1280;
$height = 25;

for($i = 0; $i < $totalGrab; $i++){
    $imageArray[$i] = imagecreatefrompng('assets/scans/temp/1-'. $i .'.png');
}

$merged_image = imagecreatetruecolor($width, $height * $totalGrab);

for($i = 0; $i < $totalGrab; $i++){
    imagecopy($merged_image, $imageArray[$i], 0, $height * $i, 0, 0, $width, $height);
}

imagepng($merged_image, 'assets/scans/merged_image.png');

for($i = 0; $i < $totalGrab; $i++){
    imagedestroy($imageArray[$i]);
    //unlink('assets/scans/temp/1-'. $i .'.png');
}

imagedestroy($merged_image);

?>
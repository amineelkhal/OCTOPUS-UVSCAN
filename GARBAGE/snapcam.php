<?php

//STEP 1 : PICK IMAGE FROM URL
$image_url = 'http://10.1.1.68:9988/onvif/media_service/snapshot';
$image_contents = imagecreatefromjpeg($image_url);

/*
$img_name = 'assets/scans/1-'. time();
$save_to =  $img_name .'.jpg';
file_put_contents($save_to, $image_contents);
print $save_to;
*/

//STEP 2 : CROP IMAGE
$width = 1280;
$height = 25;
$im2 = imagecrop($image_contents, ['x' => 0, 'y' => 0, 'width' => $width, 'height' => $height]);

if ($im2 !== FALSE) {
    imagepng($im2, "assets/scans/temp/". $_GET['scannerId'] .'-'. $_GET['id'] .".png");
    imagedestroy($im2);
}

imagedestroy($image_contents);

?>

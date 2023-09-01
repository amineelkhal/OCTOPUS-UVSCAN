<?php
$image_url = 'http://'. $_GET['ip'] .'/capture/scapture?wfilter=0';
$image_contents = file_get_contents($image_url);

$img_name = 'assets/lprsnaps/'. $_GET['ip']  .'-'. time();
$save_to =  $img_name .'.jpg';
file_put_contents($save_to, $image_contents);
print $save_to;

?>

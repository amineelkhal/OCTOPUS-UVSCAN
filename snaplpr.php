<?php
$image_url = 'http://'. $_GET['ip'] .'/capture/scapture?wfilter=0';
$image_contents = file_get_contents($image_url);

$save_to = 'assets/lprsnaps/'. $_GET['name'];
//$save_to =  $img_name;
file_put_contents($save_to, $image_contents);
print $_GET['name'];

?>

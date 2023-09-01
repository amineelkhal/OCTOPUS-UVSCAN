<?php
$xml = simplexml_load_file('http://'. $_GET['ip'] .'/status.xml');
$i = 0;
$finalValue = '';
foreach ($xml as $element) {
    //print $element;
    if ( $i == 9 )
    $finalValue = $element;
    $i++;
}
if ( $finalValue == 'dn') echo 'on';
else echo 'off';
?>
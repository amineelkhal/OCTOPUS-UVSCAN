<?php

// URL of the MJPEG stream
$streamUrl = "http://". $_GET["ip"] .":9901/livepic.mjpeg?id=92";

// Output file name
$outputFile = "assets/videos/output.mp4";

// Construct the command
$command = "curl " . escapeshellarg($streamUrl) . " | ffmpeg -i - -t 3 " . escapeshellarg($outputFile);

// Execute the command
$output = shell_exec($command);

// (Optional) You can echo the output if needed
echo $output;

?>
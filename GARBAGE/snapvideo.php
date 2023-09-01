<?php
/*$video_url = 'http://10.10.2.12:9901/video.mjpeg';
$video_filename = 'video.mjpg';

$fp = fopen($video_filename, 'w');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $video_url);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);

fclose($fp);*/

$url = 'http://10.10.2.12:9901/video.mjpeg';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$frame_number = 0;
while ($frame_number != 4) {
    $stream = curl_exec($ch);
    if (curl_errno($ch)) {
        break;
    }

    $boundary = substr($stream, 0, strpos($stream, "\r\n"));
    if (!$boundary) {
        break;
    }

    $parts = explode($boundary, $stream);
    foreach ($parts as $part) {
        if (strpos($part, 'Content-Type: image/jpeg') === false) {
            continue;
        }

        $frame = substr($part, strpos($part, "\r\n\r\n") + 4);
        file_put_contents(sprintf('frames/frame%06d.jpg', $frame_number), $frame);
        $frame_number++;
    }
}

curl_close($ch);



?>

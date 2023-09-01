<?php
///https://github.com/PHP-FFMpeg/PHP-FFMpeg
require 'vendor/autoload.php';
$logger = "";
$ffmpeg = FFMpeg\FFMpeg::create(array(
    'ffmpeg.binaries'  => 'libs/FFMpeg/bin/ffmpeg.exe',
    'ffprobe.binaries' => 'libs/FFMpeg/bin/ffprobe.exe',
    'timeout'          => 3600, // The timeout for the underlying process
    'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
));
//$video = $ffmpeg->open('abc.mp4');
$video = $ffmpeg->open('rtsp://admin:admin@10.1.1.68:554/cam/realmonitor');

/***** ADDING WATER MARK */
/*$video
    ->filters()
    ->watermark("assets/images/brand/logo-2.png", array(
        'position' => 'absolute',
        'x' => 20,
        'y' => 20,
    ));

$video->save(new FFMpeg\Format\Video\X264(), "test2.mp4");
/**************************/
/***** GET CAPTURE FROM VIDEO */
/*for( $i = 0; $i < 10; $i++){
    $video
    ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds('0.'.$i))
    ->save('frame'. $i .'.jpg');
}

/***** SAVE GIF */
/*
$video->gif(FFMpeg\Coordinate\TimeCode::fromSeconds(0), new FFMpeg\Coordinate\Dimension(640, 480), 3)->save("test.gif");
*/
/******* CROP VIDEO */
/*
$video->filters()->crop(new FFMpeg\Coordinate\Point("t*100", 0, false), new FFMpeg\Coordinate\Dimension(200, 600));
$video->save(new FFMpeg\Format\Video\X264(), 'video.mp4');
*/

/******* CUT VIDEO */

$clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(0), FFMpeg\Coordinate\TimeCode::fromSeconds(4));
$clip->save(new FFMpeg\Format\Video\X264(), 'video.mp4');


/******* ROTATE VIDEO */
/*
$video->filters()->rotate(FFMpeg\Filters\Video\RotateFilter::ROTATE_180);
$video->save(new FFMpeg\Format\Video\X264(), 'video.mp4');
*/

print "ok";
?>
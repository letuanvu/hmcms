<?php
use PHPImageWorkshop\ImageWorkshop;

$file_id      = $segments[1];
$thumb_width  = $segments[2];
$thumb_height = $segments[3];
$quality      = $segments[4];
$source_file  = get_file_location($file_id);
$type         = getimagesize($source_file);
$type         = $type['mime'];

switch ($type) {
    case 'image/png':
        $type = IMAGETYPE_PNG;
        break;
    case 'image/jpeg':
        $type = IMAGETYPE_JPEG;
        break;
    case 'image/gif':
        $type = IMAGETYPE_GIF;
        break;
    case 'image/bmp':
        $type = IMAGETYPE_BMP;
        break;
    case 'image/x-ms-bmp':
        $type = IMAGETYPE_BMP;
        break;
}
;

switch ($type) {
    case 1:
        $source = imageCreateFromGif($source_file);
        break;
    case 2:
        $source = imageCreateFromJpeg($source_file);
        break;
    case 3:
        $source = imageCreateFromPng($source_file);
        break;
    case 6:
        $source = imageCreateFromBmp($source_file);
        break;
}

$width           = imagesx($source);
$height          = imagesy($source);
$original_aspect = $width / $height;
$thumb_aspect    = $thumb_width / $thumb_height;
if ($original_aspect >= $thumb_aspect) {
    $new_height = $thumb_height;
    $new_width  = $width / ($height / $thumb_height);
} else {
    $new_width  = $thumb_width;
    $new_height = $height / ($width / $thumb_width);
}
$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
imagecopyresampled($thumb, $source, 0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
    0 - ($new_height - $thumb_height) / 2, // Center the image vertically
    0, 0, $new_width, $new_height, $width, $height);

header('Content-type: image/jpeg');
imagejpeg($thumb, null, $quality);
exit;
?>

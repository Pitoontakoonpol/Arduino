<?php

/* #####################
  $Table                Table name to record the existed picture name
  $Field                Field name to record the existed picture name
  $Record               ID number of the record to record the existed picture name
  $ImgDir           Picture Directory
  $ImgName        Picture Name
  $ThmbDir        Thumbnail Directory
  $ThmbName       Thumbnail Name

 */#####################

function img_checkin($Source, $DestName, $MaxSize, $Quality, $Text_Annotation = null) {
    $im = new Imagick($Source);
    $DestDirFile = $DestName . ".jpg";
    $im->thumbnailImage(0, 500); // Do Resize Image
    $im->setImageCompression(imagick::COMPRESSION_JPEG);
    $im->setImageCompressionQuality($Quality);
    $im->writeImage($DestDirFile);

    if ($Text_Annotation) {
        $im->readImage($DestDirFile);
        $IMGwidth = $im->getImageWidth();
        /* Draw Text Background */
        $drawBG = new ImagickDraw();
        $drawBG->setFillColor(black);
        $drawBG->setFillOpacity(0.6);
        $drawBG->rectangle(0, 400, $IMGwidth, 470);
        $im->drawImage($drawBG);

        $draw = new ImagickDraw();
        $draw->setFillColor(white);
        $draw->setFontSize(15);
        $draw->setGravity(Imagick::GRAVITY_SOUTHEAST);
        $im->annotateImage($draw, 15, 35, 0, $Text_Annotation);
        $im->writeImage($DestDirFile);
    }
    $im->destroy();
}

?>

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

function img_up($Table, $Field, $id, $Source, $SourceName, $DestName, $MaxSize, $Quality, $ThmbName = null, $ThmbSize = null, $Text_Annotation = null) {

    include("../../admin/php-db-config.conf");
    $im = new Imagick($Source);
    $Type = $im->getImageFormat();
    if ($Type == 'JPEG') {
        $File_Type = '.jpg';
    } elseif ($Type == 'PNG') {
        $File_Type = '.png';
    } elseif ($Type == 'GIF') {
        $File_Type = '.gif';
    } elseif ($Type == 'BMP') {
        $File_Type = '.bmp';
    }
    if ($File_Type) {
        if (FILE_EXISTS($DestName . ".jpg")) {
            unlink($DestName . ".jpg");
        }
        if (FILE_EXISTS($DestName . ".png")) {
            unlink($DestName . ".png");
        }

        $DestDirFile = $DestName . $File_Type;
        $DestFile = $id . $File_Type;
        if (!copy($Source, $DestDirFile)) {
            echo $Source . ">>" . $DestDirFile;
            echo"Error: Picture failed to copy";
        }
        // Get Image Width and Height
        $Width = $im->getImageWidth();
        $Height = $im->getImageHeight();
        if ($Width < $MaxSize AND $Height < $MaxSize) {
            $Width = $Width;
            $Height = $Height;
        } elseif ($Width > $Height) {
            $Width = $MaxSize;
            $Height = 0;
        } else {
            $Width = 0;
            $Height = $MaxSize;
        }
        // Do Resize Image
        $im->thumbnailImage($Width, $Height);

        if ($File_Type == '.jpg') {    //If file name is JPEG do compress it
            $im->setImageCompression(imagick::COMPRESSION_JPEG);
            $im->setImageCompressionQuality($Quality);
        }
        $im->writeImage($DestDirFile);
        if ($ThmbName AND $ThmbSize) {
            $ThmbDirFile = $ThmbName . $File_Type;
            // Do Crop thumbnail Image
            $im->cropThumbnailImage($ThmbSize, $ThmbSize);
            $im->writeImage($ThmbDirFile);
        }

        if ($Text_Annotation) {
            $im->readImage($DestDirFile);
            $draw = new ImagickDraw();
            $draw->setFillColor(black);
            $draw->setFontSize(14);
            //$draw->setFillAlpha(0.6);
            $draw->setGravity(Imagick::GRAVITY_SOUTHEAST);
            $im->annotateImage($draw, 15, 15, 0, $Text_Annotation);
            $im->writeImage($DestDirFile);
        }
        $im->destroy();

        //Update Image name to DB
        $sqlUD_Picture = "UPDATE `$Table` SET `$Field`='$DestFile' WHERE `ID`='$id'";
        $resultUD_Picture = $conn_db->query($sqlUD_Picture);
    } else {
        echo"Error: Picture format Not Support";
    }
}

function img_del($Table, $Field, $id) {

    include("../../admin/php-db-config.conf");


    $sqlSL_Picture = "SELECT * FROM `$Table` WHERE `ID`='$id'";
    $resultSL_Picture = $conn_db->query($sqlSL_Picture);
    $dbSL_Picture = mysql_fetch_array($resultSL_Picture);
    $ImgName = $dbSL_Picture[$Field];

    $ImgFile = "picture/" . $ImgName;
    $ThmbFile = "picture/thmb/" . $ImgName;



    // Remove Picture
    if ($ImgName AND file_exists($ImgFile)) {
        unlink($ImgFile);
    }

    // Remove Thumbnail
    if ($ImgName AND file_exists($ThmbFile)) {
        unlink($ThmbFile);
    }
    //Update No Image name to DB
    $sqlUD_Picture = "UPDATE `$Table` SET `$Field`='' WHERE `ID`='$id'";
    $resultUD_Picture = $conn_db->query($sqlUD_Picture);
}

?>

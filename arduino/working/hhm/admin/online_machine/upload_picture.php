<?php

/* Getting file name */
$Source = $_FILES["file"]["tmp_name"];
$Picture_Name = $_FILES["file"]["name"];
$pID = $_GET['ID'];
$inputID = $_GET['inputID'];

/* Location */
$dir = "../../machine_pic/$pID/";

if (!FILE_EXISTS($dir)) {
    exec("mkdir $dir");
}

$filenameS = $Source;
$data = getimagesize($filenameS);
$width = $data[0];
$height = $data[1];
$type = $data[2];
if ($type == 2) {
    // JPG
    $filenameD = $dir .$pID."_". $inputID . ".jpg";
} elseif ($type == 3) {
    // PNG
    $filenameD = $dir .$pID."_". $inputID . ".png";
}

$uploadOk = 1;

if ($uploadOk == 0) {
    echo 0;
} else {
    /* Upload file */
    if (move_uploaded_file($Source, $filenameD)) {
        echo $filenameD;
    } else {
        echo 0;
    }
}



if ($type == 3) {
		// PNG -> JPG
		$convert_to = $dir .$pID."_". $inputID . ".jpg";
		$convert_delete = $dir .$pID."_". $inputID . ".png";
		exec("convert $filenameD $convert_to ");
		exec("rm $convert_delete");
		$filenameD = $convert_to;
}


if ($width > $height) {
    exec("convert $filenameD -resize 1000 $filenameD");
    $desc = "From Horizontal<br/>$width > 1000" . "x" . "$height pixel";
} else if ($width < $height) {
			$new_width=(1000/$height)*$width;
    exec("convert $filenameD -resize $new_width $filenameD");
    $desc = "From Vertical <br/>$width > $new_width" . "x" . "$height pixel";
} else if ($width == $height) {

    exec("convert $filenameD -resize 1000 $filenameD");
    $desc = "From Square <br/>$width" . "x" . "$height pixel";
}
echo $desc;

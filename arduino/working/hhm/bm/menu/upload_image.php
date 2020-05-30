<html>
    <head>  
        <script type='text/javascript'>
        </script>

    </head>
    <body>
        <?php
        if ($_GET['do_upload'] == 1) {
            $pID = $_POST['pID'];
            $TotalUploadID = $_POST['TotalUploadID'];
            $TotalUploadBr = $_POST['br'];


            $Source = $_FILES["Picture"]["tmp_name"];
            $Picture_Name = $_FILES["Picture"]["name"];


            $SplitBr = explode(",", $TotalUploadBr);
            $SplitID = explode(",", $TotalUploadID);
            $br = $SplitBr[0];
            $pID = $SplitID[0];

            if ($Picture_Name AND $pID) {
                $Dir = "../../picture/$br/";
                //Check Exist Directory
                if (!FILE_EXISTS($Dir)) {
                    exec("mkdir $Dir");
                }
                $filenameS = $Source;
                $data = getimagesize($filenameS);
                $width = $data[0];
                $height = $data[1];
                $type = $data[2];
                if ($type == 2) {
                    // JPG
                    $filenameD = $Dir . $pID . ".jpg";
                } else if ($type == 3) {
                    // PNG
                    $filenameD = $Dir . $pID . ".png";
                }

                if (move_uploaded_file($Source, $filenameD)) {
                    echo "The file has been uploaded.<br/>";
                } else {
                    echo "Upload Error / File not Support!";
                }

                if ($type == 3) {
                    // PNG -> JPG
                    $convert_to = $Dir . $pID . ".jpg";
                    $convert_delete = $Dir . $pID . ".png";
                    exec("convert $filenameD $convert_to ");
                    exec("rm $convert_delete");
                    $filenameD = $convert_to;
                }


                if ($width > $height) {
                    $new_width = ($width / $height) * 200;
                    $left_offset = ($new_width / 2) - 100;
                    exec("convert $filenameD -resize $new_width $filenameD");
                    exec("convert -crop 200x200+$left_offset+0 $filenameD $filenameD");
                    $desc = "From Horizontal<br/>$width" . "x" . "$height pixel";
                } else if ($width < $height) {

                    $top_offset = ($height / 2) - 100;
                    exec("convert $filenameD -resize 200 $filenameD");
                    exec("convert -crop 200x200+0+50 $filenameD $filenameD");
                    $desc = "From Vertical <br/>$width" . "x" . "$height pixel";
                } else if ($width == $height) {

                    exec("convert $filenameD -resize 200 $filenameD");
                    $desc = "From Square <br/>$width" . "x" . "$height pixel";
                }

                //###LOOP COPY TO Branches
                for ($i = 1; $i <= COUNT($SplitBr); $i++) {
                    $To_Branch = $SplitBr[$i];
                    $To_ID = $SplitID[$i];
                    $To_Dir = "../../picture/$To_Branch/";
                    $To_Target = "../../picture/$To_Branch/$To_ID.jpg";
                    if (!file_exists($To_Dir)) {
                        exec("mkdir $To_Dir");
                    }
                    if ($To_Branch AND $To_ID) {
                        exec("cp $filenameD $To_Target");
                 //   echo "copy $filenameD $To_Target" . "<br/>";
                    }
                }
            }
        }
        ?>
        <form action="<?= $PHP_SELF ?>?do_upload=1" id="uploadForm" method="post" enctype="multipart/form-data">  

            <input type="hidden" name="TotalUploadID" id="TotalUploadID" value="<?= $_GET['TotalID'] ?>">
            <input type="hidden" name="br" id="br" value="<?= $_GET['br'] ?>">
            <input type="file" name="Picture" id="Picture" accept="image/*" onchange="$(this).submit()">
            <input type="submit" value="Upload Image" name="submit" style='font-size:16px;'>
        </form>
    </body>
</html>
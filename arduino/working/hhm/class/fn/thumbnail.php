    <?php

    function thumbnail($imagePath, $width, $height) {
        //The blur factor where &gt; 1 is blurry, &lt; 1 is sharp.
        $imagick = new \Imagick(realpath($imagePath));

        $imagick->resizeImage($width, $height, 0, 1, 1);

        $cropWidth = $imagick->getImageWidth();
        $cropHeight = $imagick->getImageHeight();

        if ($cropZoom) {
            $newWidth = $cropWidth / 2;
            $newHeight = $cropHeight / 2;

            $imagick->cropimage(
                    $newWidth, $newHeight, ($cropWidth - $newWidth) / 2, ($cropHeight - $newHeight) / 2
            );

            $imagick->scaleimage(
                    $imagick->getImageWidth() * 4, $imagick->getImageHeight() * 4
            );
        }
    }
    ?>
<html>
    <head>  
        <script type='text/javascript'>
        </script>

    </head>
    <body>
        <?php
        if ($_GET['do_upload'] == 1) {
            $pID = $_POST['pID'];
            $br = $_POST['br'];
            $Source = $_FILES["Picture"]["tmp_name"];
            $Picture_Name = $_FILES["Picture"]["name"];
            echo $Picture_Name;
        }
        ?>
        <form action="<?= $PHP_SELF ?>?do_upload=1" id="uploadForm" method="post" enctype="multipart/form-data">  
            <input type="file" name="Picture" id="Picture" accept="image/*" onchange="$(this).submit()">
            <input type="submit" value="Upload Image" name="submit" style='font-size:16px;'>
        </form>
    </body>
</html>
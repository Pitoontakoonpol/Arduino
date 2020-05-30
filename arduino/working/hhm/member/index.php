<?php

include("../admin/php-config.conf");
include("../admin/php-db-config.conf");
$b = $_GET['b'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>AmbientPOS Member Register</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../class/css.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="../class/jquery.js"></script>
        <script type="text/javascript">
        function after_register(){
            var b=$("#b").val();
            window.location="../delivery/"+b;
        }</script>
    </head>
    <body>
        <div style="max-width:400px;padding:10px;margin:10px auto;">
            <?php
            if ($b) {
                include("header.php");
                ?>
                <div>
                    <?php
                    include("register_form.php");
                    ?>
                </div>
            <?php } ?>
        </div>
    </body>
</html>

<?php
session_start();
include("admin/php-config.conf");
include("admin/php-db-config.conf");
?>
<html
    <html class="htmlClass">
    <head>
        <title>HHM</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="class/jquery.mobile-1.4.5.min.css">
        <script src="class/jquery-1.11.3.min.js"></script>
        <script src="class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript">
        var ww,wh;
            $(document).ready(function () {
                $( window ).resize(function() {
                  infra();
                });

                header_infra();
                login_status();
            });
            function go_view(ID) {
                window.location.replace("wsmcu/wsclient.php?ID=" + ID);
            }
        </script>
    </head>
    <body>
        <?php
        include("header.php");
        ?>
<div id="MAIN-SECTION">
        <?php
        $sql = "SELECT * FROM online_machine WHERE Active=1 ORDER BY Category,MachineID";

        $result = $conn_db->query($sql);
        while ($db = $result->fetch_array()) {
            foreach ($db as $key => $value) {
                $$key = $value;
            }
            if ($Category!=$Old_Category) {
                ?>
              <div class="CATEGORY-BAR"><?=$Category?></div>


              <?php
            } ?>
            <div class="each-node">
                <div><img src="machine_pic/<?=$ID?>/<?= $ID ?>_File1.jpg" style="width:200px;height:200px;"onclick="go_view(<?= $ID ?>)"></div>
                <div class="each-node-desc">
                    <div class="each-node-title"><?= $Title ?></div>
                    <div class="each-node-price"><b><?= $Price ?></b> <img src="img/hp.png" style="height:20px" align="absmiddle"> <span style="color:gray">/ 1play</span></div>
                </div>
            </div>
            <?php
            unset($ctrl);
            $Old_Category=$Category;
        }
        ?>
      </div>
        <div id="spare"></div>
        <style type="text/css">
            .each-node{
                float:left;
                position:relative;
                background-color:#ddd;
                height:200px;
                width:200px;
                margin:3px;
                box-shadow:0 0 5px grey;
                border-radius:5px;
            }
            .each-node img{
                border-radius:5px;

            }
            .each-node-desc{
                padding:3px;
                position:absolute;
                width:calc(100% - 6px);
                bottom:0px;
                font:normal 14px sans-serif;
                background-color:rgba(255,255,255,0.75);
            }
            .each-node-title{
            }
            .each-node-price{
                color:#ffd700;
            }
            #MAIN-SECTION{
              width:100%;
              max-width:1250px;
              margin:30px auto;
            }
            .CATEGORY-BAR{
              clear:both;
              padding:30px 0 5px 10px;
              font:bold 16px sans-serif;
            }
        </style>
    </body>
</html>

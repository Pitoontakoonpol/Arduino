<?php
include("../php-config.conf");
include("../php-db-config.conf");
$Page_Name = 'Setting';
$Page_Name2 = 'Setting';
?>
<html>
    <head>
        <title>Settings</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="index.js?180121"></script>
    </head>
    <body>
        <?php
        include("../header/header.php");
        include("../../class/fn/amsalert.php");
        ?>
        <div id="SECTION-ADMIN-ACCOUNT" style="width:90%;margin:0 auto;"></div>
        <div id="SECTION-ADMIN-POS" style="width:90%;margin:0 auto;"></div>
        <div id="SECTION-ADMIN-RESTAURANT" style="width:90%;margin:0 auto;"></div>
        <div id="SECTION-STAFF" style="width:90%;margin:0 auto;"></div>
        <div id="spare" style="display:none;position:fixed;left:100px;top:5px;background-color:#aaaaff;padding:10px;">spare</div>
        <style type="text/css">
            .main-table th{
                padding:10px 5px;
                text-align:right;
            }
            .main-table td{
                padding:10px 5px;
                text-align:left;
            }
            .main-table input{
                padding:1px;
                width:220px;
                font-size:22px;
                text-align:left;
            }
            .permission-table{
                width:100%;
            }
            .permission-table th{
                text-align:center;
                padding:10px 0;
                width:80px;
                font:bold 13px sans-serif;
                background-color:#888;
                color:#fff;
            }
            .permission-table td{
                padding:10px 5px;
                font:normal 14px sans-serif;
                text-align:center;
                background-color:#fff;
            }
            .permission-table img{
                width:15px;
            }
            #mod-account th{
                text-align:left;
                padding:8px 8px 8px 50px;
                font:normal 14px sans-serif;
            }
            #mod-account td{
                text-align:left;
                padding:8px;
            }
            .edit_form td{
                background-color:#444;
                color:#fff;
                text-align:left;
            }
            .setting-box {
                width:100%;
                background-color:#eee;
                padding:10px;
                margin:30px 0;
                border-radius:5px;
                box-shadow: 0px 2px 10px #888;
                border:solid 1px #ccc;
            }
            .setting-title {
                font:bold 25px sans-serif;
                padding-left:10px;
                color:#666;
                border-bottom:solid 3px #aaa;
            }
            .submit-button{
                margin-left:20px;
                font:bold 20px sans-serif;
                padding:10px 20px;
                color:#fff;
                height:50px;
                background:url('../../img/button/button_blue.png');
                border:solid 1px #46187b;
                border-radius:2px;
            }
            .location-box{
                width:100%;
                max-width:400px;
                float:left;
                border:solid 1px #FF4500;
                font:bold 25px sans-serif;
                color:#fff;
                padding:20px;
                background:url('../../img/button/button_orange.png');
                background-size: 100% 100%;
                border-radius:2px;
                margin:10px;
                height:25px;
            }

        </style>

    </body>
</html>

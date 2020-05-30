<?php
session_start();
?>
<!DOCTYPE HTML>
<html class="htmlClass">
    <head>  
        <title>Raw Material Management</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="index.js?1"></script>
    </head>
    <body class="bodyClass">
        <?php
        include("../header/header.php");
        include("../../class/fn/amsalert.php");
        ?>
        <div id="spare" style="display:none;padding-left:100px">spare</div>
        <div id="spare_event" style="display:none;padding-left:100px"></div>

        <div id="SECTION-RAWMAT" style="clear:both;overflow:hidden;">loading ...</div>

        <div data-role="popup" id="POPUP-EDIT" data-overlay-theme="b" data-dismissible="false" class="ui-content" rel="external" style="position:fixed;top:20px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <div id="EDIT-TITLE"></div>
            <div id="INNER-EDIT" style="overflow-y:auto;float:left;"></div>
            <div id="INNER-EDIT-TOPUP" style="overflow-y:auto;float:left;"></div>
            <div id="INNER-FOOTER" style="clear:both;">
                <div style="margin:0 auto;width:180px">
                    <button  id="edit-save-button" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b ui-corner-all" onclick="edit_product()">Save</button>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>

        <div data-role="popup" id="DESC" data-overlay-theme="b" class="ui-content" rel="external" style="position:fixed;top:20px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <input type="hidden" id="LAST-DISPLAYID">
            <div id="DESC-TITLE"></div>
            <div data-role="navbar">
                <ul>
                    <li><button onclick="desc('check', 'stockin')">
                            <div class="nav-bar-topic">Stock-in</div>
                            <div class="nav-bar-val" id="val-in" style="color:green;border-top:solid 3px green;">0</div>
                        </button>
                    </li>
                    <li><button onclick="desc('check', 'stockout')">
                            <div class="nav-bar-topic">Stock-out</div>
                            <div class="nav-bar-val" id="val-out" style="color:orange;border-top:solid 3px orange;">0</div>
                        </button></li>
                    <li><button onclick="desc('check', 'pos')">
                            <div class="nav-bar-topic">POS</div>
                            <div class="nav-bar-val" id="val-pos" style="color:blue;border-top:solid 3px blue;">0</div>
                        </button></li>
                    <li><button onclick="desc('check', 'operate')">
                            <div class="nav-bar-topic">Operate</div>
                            <div class="nav-bar-val">&nbsp;</div>
                        </button></li>
                </ul>
            </div>
            <div id="INNER-DESC" style="overflow-y:auto;float:left;width:100%"></div>
            <div style="clear:both;"></div>
        </div>
    </body>
</html>

<style type="text/css">
    #EDIT-TITLE,#DESC-TITLE{
        height:30px;
        padding:5px;
        font:bold 25px sans-serif;
        background-color:#eee;
        text-align:center;
    }
    #INNER-FOOTER{
        background-color:#eee;
        padding:5px;
    }
    .group-color{
        float:left;
        width:50px;
        height:50px;
        margin:2px;
    }
    .group-color img{
        width:30px;
        margin:10px;
    }
    .group-icon{
        float:left;
        width:40px;
        height:40px;
        margin:2px;
        padding:5px;
        background-color:#888;
    }
    .group-icon img{
        width:40px;height:40px;
    }
    #POPUP-EDIT,#DESC{
        position:fixed;
        top:10px;
        right:10px;
        width:100%;
        max-width:400px;
    }
    .nav-bar-topic{
        font:bold 13px sans-serif;
    }
    .nav-bar-val{
        font:bold 15px sans-serif;
    }
</style>
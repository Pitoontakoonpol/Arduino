<?php
session_start();
?>
<!DOCTYPE HTML>
<html class="htmlClass">
    <head>  
        <title>Product Management</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="index.js?170901"></script>
    </head>
    <body class="bodyClass">
        <?php
        include("../header/header.php");
        include("../../class/fn/amsalert.php");
        ?>
        <div id="spare" style="display:none;padding-left:100px">spare</div>
        <div id="spare_event" style="display:none;padding-left:100px"></div>

        <div id="SECTION-GROUP" style="padding-left:100px;">
            <h1 style="height:50px">
                <div style="float:left;">Manage Product Type</div>
                <div style="float:left;margin-left:10px;"><button onclick="save_type_sequence()" class="ui-btn ui-btn-icon-left ui-corner-all ui-icon-save ui-btn-inline ui-btn-b" style="display:none;" id="product_type_save_button">Save Sequence</button></div>
            </h1>
        </div>
        <div id="SECTION-PRODUCT" style="clear:both;overflow:hidden;">loading ...</div>
        <div data-role="popup" id="POPUP-EDIT-GROUP" data-overlay-theme="b" data-dismissible="false" style="max-width:650px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <input type="hidden" id="INNER-GROUPID">
            <input type="hidden" id="INNER-GROUP-TITLE-OLD">
            <input type="text" id="INNER-GROUP-TITLE" style="font:bold 20px sans-serif;">
            <input type="hidden" id="INNER-GROUP-COLOR">
            <input type="hidden" id="INNER-GROUP-ICON">
            <input type="hidden" id="INNER-GROUP-STYLE">
            <div style="clear:both;height:80px;">
                <div style="float:left;padding:10px;border-radius:2px;" id="INNER-GROUP-STYLE-1" class="INNER-GROUP-STYLE"><img src="../../img/bar_style1.png" onclick="change_group_style(1)"></div>
                <div style="float:left;padding:10px;border-radius:2px;" id="INNER-GROUP-STYLE-2" class="INNER-GROUP-STYLE"><img src="../../img/bar_style2.png" onclick="change_group_style(2)"></div>
                <div style="float:left;padding:10px;border-radius:2px;" id="INNER-GROUP-STYLE-3" class="INNER-GROUP-STYLE"><img src="../../img/bar_style3.png" onclick="change_group_style(3)"></div>
            </div>
            <div style="clear:both;">

                <?php
                $total_color = array('000000', '2F4F4F', 'A9A9A9', '8B0000', 'ff0000', 'CD5C5C', 'FF69B4', '8B4513', '006400', '8FBC8F', '32CD32', '3CB371', '00008B', 'C71585', '1E90FF', '808000', 'FF4500', 'FFA500', 'FF6347', 'DC143C', '8B008B', 'D2691E', 'B8860B', 'FFA07A');
                foreach ($total_color AS $BG_Color) {
                    ?>
                    <div class="group-color" id="group-color-<?= $BG_Color ?>" style="background-color:#<?= $BG_Color ?>"></div>
                    <?php
                }
                ?>
            </div>
            <div style="clear:both;">
                <?php
                $total_icon = array('101', '102', '103', '104', '105', '106', '107', '108', '109', '110', '111', '112', '201', '202', '203', '204', '205', '206', '207', '208', '209', '210', '211', '212', '301', '302', '303', '304', '305', '306', '307', '308', '309', '310', '311', '312', '501', '502', '503', '504', '505', '506', '507', '508', '509', '510', '511', '512');
                foreach ($total_icon AS $icon_file) {
                    ?>
                    <div class="group-icon" id="group-icon-<?= $icon_file ?>"><img src="../../img/menu_icon/<?= $icon_file ?>.png"></div>
                    <?php
                }
                ?>
            </div>
            <div style="clear:both;text-align:right">
                <button onclick="zzzsave_inner_type()" class="ui-btn ui-btn-icon-left ui-corner-all ui-icon-save ui-btn-inline ui-btn-b"  id="product_type_save_button">Save Changes</button>
            </div>
        </div>

        <div data-role="popup" id="POPUP-EDIT" data-overlay-theme="b" data-dismissible="false" class="ui-content" rel="external" style="position:fixed;top:20px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <div id="EDIT-TITLE" onclick="branch_mod('product')"></div>
            <div id="INNER-EDIT" style="overflow-y:auto;"></div>
            <div class="INNER-FOOTER" style="clear:both;">
                <div style="margin:0 auto;width:180px;">
                    <button  id="edit-save-button" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b ui-corner-all" onclick="edit_product()">Save</button>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div data-role="popup" id="POPUP-TOPUP" data-overlay-theme="b" data-dismissible="false" class="ui-content" rel=”external” style="position:fixed;top:20px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <div id="TOPUP-TITLE"></div>
            <div id="INNER-TOPUP" style="overflow-y:auto;padding:10px;"></div>
            <div class="INNER-FOOTER" style="clear:both;">
                <div style="margin:0 auto;width:180px">
                    <button  id="topup-save-button" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b ui-corner-all" onclick="zzztopup_product()">Save</button>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div data-role="popup" id="POPUP-STOCK" data-overlay-theme="b" data-dismissible="false" class="ui-content" rel=”external” style="position:fixed;top:20px;">
            <a href="#" data-rel="back" class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-left">Close</a>
            <div id="STOCK-TITLE"></div>
            <div id="INNER-STOCK" style="overflow-y:auto;padding:10px 0;"></div>
            <div class="INNER-FOOTER" style="clear:both;">
                <div style="margin:0 auto;width:180px">
                    <button  id="stock-save-button" class="ui-btn ui-icon-check ui-btn-icon-left ui-btn-b ui-corner-all" onclick="zzzstock_product()">Save</button>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>

    </body>
</html>

<style type="text/css">
    #EDIT-TITLE,#TOPUP-TITLE,#STOCK-TITLE{
        height:30px;
        padding:5px;
        font:bold 25px sans-serif;
        background-color:#eee;
        text-align:center;
    }
    .INNER-FOOTER{
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
    #POPUP-EDIT,#POPUP-TOPUP,#POPUP-STOCK{
        position:fixed;
        top:10px;
        right:10px;
        width:100%;
        max-width:400px;
    }
</style>
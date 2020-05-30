<?php
include("../php-config.conf");
include("../php-db-config.conf");
include("../setting/fn_username.php");
include("../promotion/fn_promotion.php");
$Page_Name = 'Report';
$d = $_GET['d'];
$tab = $_GET['tab'];
$ord = $_GET['ord'];
if ($d == '') {
    $d = $Today00;
}
?>

<html>
    <head>
        <title>Report | AmbientPOS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0,minimal-ui">
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" src="index.js?171016004"></script>
        <script type="text/javascript" src="../fn/ambient_chart.js"></script>
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />

    </head>
    <body>
        <?php
        include("../header/header.php");
        $area_height = 250;
        ?>
        <div id="sub-bar" style="padding:3px 5px;background-color:#000;">
            <div style="float:left;margin-left:60px;">
                <select id="tab" class="fs20" onChange="change_page(0)">
                    <option value='1'>1.Order</option>
                    <option value='2'>2.Order Detail by Order</option>
                    <option value='3'>3.Order Detail by Product</option>
                    <option value='4'>4.Order Detail by Group</option>
                    <option value='5'>5.Stock Detail by Order</option>
                    <option value='5_1'>5.1.Stock Detail by Raw-Material</option>
                    <option value='6'>6.Summary Slip</option>
                    <option value='7'>7.Import</option>
                </select>
            </div>

            <div style="float:left;">
                <select id="time-period" class="fs20" onchange="change_page()">
                    <?php
                    for ($od = 0; $od >= -31; $od--) {
                        $Make_dFrom = date("U", mktime(0, 0, 0, $Month_Now, $Date_Now + $od, $Year_Now));
                        $Make_dTo = date("U", mktime(0, 0, 0, $Month_Now, $Date_Now + $od, $Year_Now));

                        $dFrom_Val = date('Y-m-d', $Make_dFrom);
                        $dTo_Val = date('Y-m-d', $Make_dTo);
                        $dShow = date('D d', $Make_dFrom);

                        if ($od == 0) {
                            $dShow = 'Today';
                        } else if ($od == -1) {
                            $dShow = 'Yesterday';
                        }
                        echo "<option value='$dFrom_Val" . "-" . "$dTo_Val'>$dShow</option>";
                    }
                    for ($om = 0; $om >= -12; $om--) {
                        $Make_mFrom = date("U", mktime(0, 0, 0, $Month_Now + $om, 1, $Year_Now));
                        $Make_mTo = date("U", mktime(0, 0, 0, $Month_Now + $om + 1, 0, $Year_Now));

                        $mFrom_Val = date('Y-m-d', $Make_mFrom);
                        $mTo_Val = date('Y-m-d', $Make_mTo);
                        $mShow = date('MY', $Make_mFrom);

                        echo "<option style='background-color:#ccc;' value='$mFrom_Val" . "-" . "$mTo_Val'>$mShow</option>";
                    }
                    ?>
                    <option value="advance" style='background-color:blue;color:#fff;'>Advance Search</option>
                </select>
            </div>

            <div id="advance-time" style="display:none;float:left;color:#fff;font-size:13px;padding:8px 0 0 10px ;">
                <?php
                $adv_from = date("Y-m-d", $d);
                $adv_to = date("Y-m-d", $d + 86400);
                ?>
                <div style='float:left;width:80px;padding-top:15px'>
                    Time from :
                </div>
                <div style='float:left;width:120px'>
                    <input type="date" id="adv_from" class="fs14" value='<?= $adv_from ?>'>
                </div>
                <div style='float:left;padding:15px 5px 0 5px;'>
                    -
                </div>
                <div style='float:left;width:120px'>
                    <input type="date" id="adv_to" class="fs14" value='<?= $adv_to ?>'></div>
                <div style='float:left;width:100px'>
                    <button class="ui-btn ui-icon-search ui-btn-icon-left ui-corner-all ui-mini" onclick="change_page(1)">Search</button></div>
            </div>
            
            <div style="float:right;padding:8px;">
                    <button class="ui-btn ui-icon-refresh ui-btn-icon-left ui-corner-all ui-mini" onclick="change_page(1)">Refresh</button>
            </div>
            <div style="clear:both;"></div>
        </div>

        <?php
        if ($SESSION_POS_TYPE == 2) {
            ?>
            <div class="fs14 none" style="clear:both;background-color:#ccc;padding:3px 10px;" id="tab2-option">
                View as : 
                <label><input type="checkbox" id="view_order" checked="checked">Order </label>
                <label><input type="checkbox" id="view_discount" checked="checked">Discount </label>
                <label><input type="checkbox" id="view_vat" checked="checked">VAT </label>
                <label><input type="checkbox" id="view_sc" checked="checked">Service Charge </label>
                <button onclick="change_page()">Submit</button>
            </div>
        <?php } ?>

        <div id="tab_result_area" style="clear:both;overflow-y:auto"></div>
        <div id="tab_tax_result" style="clear:both;overflow-y:auto"></div>
        <div id="spare" style="display:none">-- Spare Here --</div>
        <div id="spare1" style="display:none">-- Spare Here --</div>
        <div  data-role="popup" id="SERVICE-DESC-POPUP" data-overlay-theme="b" class="ui-content" style="position:fixed;right:0;top:0;width:100%;max-width:500px;padding:5px;">
            <div style="text-align:center;padding:10px;background-color:#ccc;font:bold 26px sans-serif" id="SERVICE-DESC-TITLE">DESC</div>
            <div data-role="navbar" style='border-bottom:solid 5px #444;'><ul>
                    <li><button onclick="service_desc('0')">Description</button>
                    </li>
                    <li><button onclick="service_operation('0')">Manage Order</button>
                    </li>
                </ul></div>
            <div  id="SERVICE-DESC-DESC" style="clear:both;"></div>
        </div>
        <style type="text/css">
            .hourly{
                width:4%;
                background-color:#000;
                height:<?= $area_height ?>px;
                text-align:center;
                font:normal 13px sans-serif;
                color:#fff;

            }
            .bar{
                background-color:yellow;
                width:100%;
            }
            .comment-hourly{
                font:normal 12px sans-serif;
                text-align:center;
            }
            .report2 {
                background-color:#000;
            }
            .report2 th {
                background-color:#ddd;
                text-align:center;
            }
            .report2 td {
                background-color:#fff;
                text-align:right;
            }
            #peak-session-table td{
                color:#fff;
            }
            .report-box{
                position:relative;
                float:left;
                height:75px;
                width:100%;
                max-width:300px;
                background-color:#444;
                margin:3px;
                color:#fff;
                font:bold 40px sans-serif;
                text-align:center;
                padding:10px;
                border-radius:3px;
            }
            .report-box2{
                position:relative;
                float:left;
                height:130px;
                width:300px;
                background-color:#444;
                margin:3px;
                color:#fff;
                font:bold 40px sans-serif;
                text-align:center;
                padding:10px;
                border-radius:3px;
            }
            .box-title{
                font:bold 20px sans-serif;
                text-align:center;color:#999;
            }
            #FullTaxTitle{
                font:bold 20px sans-serif;
                text-align:center;
                color:#fff;
                background-color:rgba(0,0,0,0.8);

            }
            @media print{
                #tab_result_area{
                    height:auto !important;
                }
                #sub-bar,#HEADER-MAIN-BUTTON,.ui-btn,.cancel_bar{
                    display:none;
                }
                .report2-restaurant tr{
                    font-size:10px;
                }
                .report2-restaurant td{
                    font-size:10px;
                }
            }
        </style>

    </body>
</html>
<?php
include("../php-config.conf");
include("../php-db-config.conf");
include("../../class/fn/amsalert.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Promotion</title>
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" src="index.js?20170821-1"></script>
    </head>
    <body>
        <div id="header-bar"><?php include("../header/header.php"); ?></div>
        <div style="margin-top:60px;font:bold 35px sans-serif;background-color:#ddd">
            <div style="float:left;padding:10px;">At Payment <i style="font-size:20px;">(Percentage)</i></div>
            <div style="float:right;padding:5px 10px;"><button class="ui-btn ui-icon-plus ui-btn-icon-left ui-btn-b ui-corner-all" onclick="popup_at_payment(0)">Add</button></div>
            <div style="clear:both"></div>
        </div>
        <div id="at_payment"><img src="../../img/ajaxloader.gif"></div>
        <div data-role="popup" id="POPUP-AT-PAYMENT" class="ui-content" data-transition="fade" data-overlay-theme="b" style="position:fixed;top:0;right:0;overflow-y:auto;">
            <div style="font:bold 30px sans-serif;padding:10px;background-color:#ddd">At Payment <i style="font-size:15px;">(Percentage)</i></div>
            <input type="hidden" id="POPUP-PAYMENT-ID">
            <div>
                <table>
                    <tr>
                        <th>Code</th>
                        <td id="atpayment-Name-Notice" class="notice"><input type="text" id="atpayment-Name" maxlength="10" placeholder="limit 10 digits"></td>
                    </tr>
                    <tr>
                        <th>Sequence</th>
                        <td id="atpayment-Sequence-Notice" class="notice">
                            <div style="float:left;width:120px;">
                                <select id="atpayment-Sequence">
                                    <option value="0"></option>
                                    <?php
                                    for ($sq = 1; $sq <= 20; $sq++) {
                                        echo"<option value='$sq'>$sq</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Discount</th>
                        <td id="atpayment-Total-Notice" class="notice">
                            <div style="float:left;width:120px;">
                                <select id="atpayment-Total">
                                    <option value=""></option>
                                    <?php
                                    for ($tt = 1; $tt <= 100; $tt++) {
                                        echo"<option>$tt</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div style="float:left;padding:22px 5px">%</div>
                        </td>
                    </tr>
                    <tr>
                        <th valign="top" style="padding-top:10px;">Remark</th>
                        <td><textarea id="atpayment-Remark"></textarea></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td><button onclick="at_payment_submit()" id="POPUP-AT-PAYMENT-BTN">Submit</button></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="spare" style="display:none;">spare</div>
    </body>
</html>

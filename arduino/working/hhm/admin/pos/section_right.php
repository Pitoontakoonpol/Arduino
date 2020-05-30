<script type='text/javascript' src="section_right.js?20180122"></script>
<link rel="stylesheet" type="text/css" href="section_right.css?1001">
<div id="SECTION-RIGHT-INNER">
    <div id="DIALOG-TOTAL-PRICE">0.00</div><!--takes 70h-->
    <div id="DIALOG-TOTAL">
        <div id="DIALOG-TOTAL-REDEEM" val="0" style="text-align:left;float:right;color:#fff;padding:0 3px;">0 <span class="unit-style">redeem</span></div>
        <div id="DIALOG-TOTAL-POINT" val="0" style="text-align:left;float:right;color:#fff;padding:0 3px;">0 <span class="unit-style">point</span></div>
        <div id="DIALOG-TOTAL-QTY" val="0" style="float:right;color:#fff;padding:0 3px;">0 <span class="unit-style">piece</span></div>
        <div><input type='text' id="DIALOG-STANDBY" data-role="none" style="width:100%;background-color:#000;color:#fff;font-size:14px;border:none;"></div>
    </div>

    <div style='clear:both;'></div>
    <div id="DIALOG-LOGO"><!--takes 20h-->
        <img src="../../img/pos_logo.png">
    </div>
    <div id="DIALOG-ORDER-LIST"></div>
    <div id="DIALOG-MAIN-BUTTON">
        <div style="height:25px;">
            <div style="float:left;padding:6px 2px 0 2px;color:#fff;font-size:12px;" id="BRIEF-REPORT">
                <div style="float:left;"><img src="../../img/pos_report_bar1.svg" style="width:12px;padding-left:5px"></div>
                <div style="float:left;" id="BRIEF-TOTAL">0</div>
                <div style="float:left;"><img src="../../img/pos_report_bar2.svg" style="width:12px;padding-left:8px"></div>
                <div style="float:left;" id="BRIEF-QTY">0</div>
                <div style="float:left;"><img src="../../img/pos_report_bar3.svg" style="width:12px;padding-left:8px"></div>
                <div style="float:left;" id="BRIEF-BILL">0</div>
            </div>
            <div style="float:right;padding:2px;color:#fff;font-size:15px;" id="SIGNAL-ACCOUNT"></div>
            <div style="float:right;padding:2px;" id="SIGNAL-NETWORK-NORMAL"><img src="../../img/signal/network_normal.png"></div>
            <div style="float:right;padding:2px;display:none;" id="SIGNAL-NETWORK-ERROR"><img src="../../img/signal/network_error.png"></div>
            <div style="float:right;display:none;padding:2px 8px 0 2px;color:#fff;font-size:15px;" id="SIGNAL-ORDER-CACHE"><img src="../../img/signal/cache.png" align="absmiddle"><span id="TOTAL-CACHE">0</span></div>
        </div>
        <div style="border:solid 1px #fff;clear:both;">
            <div style="width:150px;float:left;"><button class="ui-btn ui-icon-action ui-btn-icon-top" onclick="payment_popup()">Payment</button></div>
            <div style="width:150px;float:left;"><button class="ui-btn ui-icon-user ui-btn-icon-top" onclick="member_popup()"><div id='MEMBER-ACTIVE'>Member</div></button></div>
            <div style="width:30px;float:left;"><button class="ui-btn ui-icon-bars ui-btn-icon-top" style="width:50px;" onclick="$('#HIDDEN-BUTTON').slideToggle(300)">...</button></div>
        </div>
        <div style="clear:both;"></div>
        <div id="HIDDEN-BUTTON" style="display:none;">
            <button class="ui-btn ui-icon-recycle ui-btn-icon-top ui-btn-inline" style="width:87px;" onclick="if (confirm('Confirm Reset?'))
                        order_reset();">Reset</button>
            <button class="ui-btn ui-icon-tag ui-btn-icon-top ui-btn-inline" style="width:87px;" onclick="suspend_popup()">Suspend</button>
            <button class="ui-btn ui-icon-search ui-btn-icon-top ui-btn-inline" style="width:87px;" onclick="$('#DIALOG-STANDBY').focus()">Search</button>
            <button class="ui-btn ui-icon-clock ui-btn-icon-top ui-btn-inline" style="width:89px;" onclick="history_popup()">History</button>
            <div style="clear:both;"></div>
        </div>
    </div>
</div>
<div data-role="popup" id="POPUP-SUSPEND" class="ui-content" data-transition="fade" data-overlay-theme="b">
    <div style="padding:10px;background-color:#ddd;font:bold 25px sans-serif;text-align:center;">Suspend</div>
    <div id="NEW-SUSPEND" style="height:400px;padding:20px;background-color:#ccc">
        <input type="text" id="Suspend-Title" placeholder="Title" style="font-size:30px;text-align:center;">
        <button onclick="add_suspend();" class="ui-btn ui-btn-icon-top ui-icon-plus ui-corner-all ui-btn-b" id="SUSPEND-SAVE-BTN">Add New Suspend</button>
    </div>
    <div id="SUSPEND-LIST"style="width:600px;overflow-y:auto;">no order</div>
</div>
<div data-role="popup" id="POPUP-PAYMENT" data-transition="fade" data-overlay-theme="b">
    <div>
        <table style="font:bold 50px sans-serif;width:100%;text-align:center;" cellspacing='5'cellpadding="0" id='POPUP-MONEY'>
            <tr>
                <td colspan="2">
                    <div style='font:normal 15px sans-serif;'>
                        ยอดเงิน
                    </div>
                    <div id="POPUP-MONEY-TOTAL">0.00</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div style='font:normal 15px sans-serif'>
                        รับเงิน
                    </div><div id="POPUP-MONEY-GET">0.00</div>
                </td>
                <td>
                    <div id="MONEY-REFUND-COVER">
                        <div style='font:normal 15px sans-serif'>
                            ทอนเงิน
                        </div>
                        <div id="POPUP-MONEY-REFUND">0.00</div>
                    </div>
                </td>
            </tr>
        </table>
        <div id="PAYMENT-NOTE-SECTION" style='overflow-y:auto;float:left;width:calc(100% - 250px);'>
            <div id='PAYMENT-NOTE'>
                <?php include("payment_note.php") ?></div>
            <div id='PAYMENT-POINT-HIDE' style='clear:both;display:none;'>
                <div style='font-size:15px;color:#888;margin-top:30px;'><br/>Point</div>
                <div class='PAYMENT-POINT' style="border-radius:10px;background:url('../../img/bank_note/ambientpoint.png');background-size:contain;">
                    <div style="margin:45px 0 0 330px;width:120px;" id="MEMBER-POINT-AVAILABLE">0</div>
                    <div style="margin:0 0 0 330px;width:120px;font:normal 15px sans-serif;">Points.</div>
                </div>
            </div>
            <div id='PAYMENT-CREDIT' style='clear:both;'>
                <div style='font-size:15px;color:#888;margin-top:30px;'><br/>Credit Card / QR-Code</div>
                <?php
                for ($qr = 201; $qr <= 205; $qr++) {
                    ?>
                    <div class='PAYMENT-CREDIT' id="Payment-<?= $qr ?>" style="display:none;border-radius:10px;background:url('../../img/bank_note/<?= $qr ?>.png');background-size:contain;" paymenttype='<?= $qr ?>'></div>
                    <?php
                }
                for ($qr = 301; $qr <= 307; $qr++) {
                    ?>
                    <div class='PAYMENT-QR' id="Payment-<?= $qr ?>" style="display:none;border-radius:10px;background:url('../../img/bank_note/<?= $qr ?>.png');background-size:contain;" paymenttype='<?= $qr ?>'></div>
                    <?php
                }
                $qr = 401;
                ?>
                <div class='PAYMENT-CREDIT' id="Payment-<?= $qr ?>" style="border-radius:10px;background:url('../../img/bank_note/<?= $qr ?>.png');background-size:contain;" paymenttype='<?= $qr ?>'></div>
            </div>
            <div id='PAYMENT-VOUCHER' style='clear:both;display:none;'>
                <div style='font-size:15px;color:#888;margin-top:30px;'><br/>Voucher</div>
                <div class='PAYMENT-VOUCHER' style="border-radius:10px;background:url('../../img/bank_note/voucher.png');background-size:contain;"></div>
            </div>

        </div>
        <div style='background-color:#1a1a1a;float:right;width:240px;' valign="top">
            <div id="PAYMENT-MESSAGE" style="font:normal 15px sans-serif;color:white;"></div>
            <div><input type="text" id="CREDIT-REMARK" style="font:normal 15px sans-serif;display:none;text-align:center;border:none;" onkeyup='credit_remark()'></div>
            <div><input type="text" id="VOUCHER-NUMBER" placeholder="Voucher Number" style="font:normal 15px sans-serif;display:none;text-align:center;"></div>
            <div id="VOUCHER-INFO"style="font:normal 15px sans-serif;display:none;text-align:center;color:#fff;"></div>
            <div id="DISCOUNT-INFO"></div>
            <div id='OPTIONAL-FOOTER-COVER'><input type='hidden' id='OPTIONAL-FOOTER'value=''></div>
            <div style='padding-top:50px;'>
                <button class='ui-btn ui-icon-action ui-btn-icon-top' id='CONFIRM-BUTTON' onclick='submit_order()'>Confirm&Print</button>
                <button class='ui-btn ui-icon-action ui-btn-icon-top' id='REPRINT-BUTTON' onclick='reprint_order()'>Re-Print</button>
            </div>
        </div>
    </div>
</div>
<div data-role="popup" id="POPUP-MEMBER" data-transition="fade" data-overlay-theme="b"  style="padding:10px;">
    <div style="float:left;">
        <input type="number" style="padding:0px;width:300px;width:600px;font:bold 25px sans-serif;border:solid 1px green;" id="MEMBER-SEARCH" disabled="disabled">
    </div>
    <div style="float:left;clear:both;width:calc(100% - 350px);border-radius: 0 10px 10px 10px;overflow-y:auto;background-color:#fff;color:#000;" id="MEMBER-SEARCH-RESULT"></div>
    <div style="float:right;text-align:center;width:350px;">
        <div><?php include("numpad.php") ?></div>
        <div style="clear:both;padding:20px;"><button class="ui-btn ui-icon-search ui-btn-corner-all ui-btn-icon-left " style="font:bold 25px sans-serif;" onclick="search_member()">Search</button></div>
        <div style="clear:both;padding:20px;">
            <select id="member_selection" onchange="$('#MEMBER-SEARCH').val(this.value)">
                <option value="">Phone Number / Member Code</option>
                <option value="Latest Register">Latest Register</option>
                <option value="Maximum Point">Maximum Point</option>
            </select>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<div data-role="popup" id="POPUP-HISTORY" data-transition="fade" data-overlay-theme="b"  style="padding:10px;">
    <div style="padding:10px;background-color:#ddd;font:bold 25px sans-serif;text-align:center;">History</div>
    <div id="POPUP-HISTORY-LIST" style="overflow-y:scroll;height:400px;width:380px;padding:5px;float:left;">loading</div>
    <div id="POPUP-HISTORY-DESC" style="overflow-y:auto;height:400px;width:500px;padding:5px;float:left;"></div>
    <div id="POPUP-HISTORY-OPR" style="padding:5px;float:right;">
        <button style="font-size:20px;" onclick="reprint_history()">Re-Print</button>
        <button style="font-size:20px;display:none;" class="HISTORY-BTN" histsn="" onclick="void_bill(1)">Cancel With Stock</button>
        <button style="font-size:20px;display:none;" class="HISTORY-BTN" histsn="" onclick="void_bill(2)">Cancel Without Stock</button>
    </div>
    <div style="clear:both;"></div>
</div>
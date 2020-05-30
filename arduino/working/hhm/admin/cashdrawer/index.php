<?php
include("../../class/fn/amsalert.php");
?>
<html>
    <head>
        <title>Cash Drawer</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="index.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">

            var Permission_Set_Restaurant = localStorage.Set_Restaurant;
            var usrID = localStorage.usrID;
            var usr = localStorage.usr;
            var br = localStorage.br;
            $(document).ready(function () {
                load_total(0);
                load_drawer();
                $(".PAYMENT-NOTE").bind("tap", function () {
                    var money = parseFloat($(this).attr('money'));
                    calculate_money(money);
                });
            });
            function load_total(Total_Reload) {
                $("#TOTAL-REMAIN").load("load_total.php?br=" + br + "&Total_Reload=" + Total_Reload + '&res=' + Permission_Set_Restaurant);
            }
            function load_drawer() {
                var df = $("#df").val();
                var dt = $("#dt").val();
                $("#cashdrawer").html("<img src='../../img/ajaxloader.gif'>").load("cashdrawer.php?br=" + br + '&df=' + df + '&dt=' + dt + '&res=' + Permission_Set_Restaurant);
            }
            function calculate_money(money) {
                var title = $("#Title").val();
                if (title === 'new___other') {
                    $("#New_Title_Cover").slideDown(300);
                    $("#New_Title").focus();
                } else {
                    $("#New_Title_Cover").slideUp(100).val('');
                }
                var oprType = $("#OPR-TYPE").val();
                if (oprType === '0') {
                    title = 'some';
                }
                var current_total_get = parseFloat($("#PRICE").val());
                var total_get = current_total_get + money;
                $("#PRICE").val(total_get.toFixed(2));
                if (title && total_get > 0) {
                    $("#CONFIRM-BUTTON").slideDown(100);
                } else {
                    $("#CONFIRM-BUTTON").hide();
                }
            }
            function open_form(method) {
                $("#Title").val('');
                $("#Price").val('0');
                $("#Remark").val('');
                if (method === 1) {
                    $("#operation_name").text('เข้า');
                    $("#operation_type").val('1');
                } else if (method === 2) {

                    $("#operation_name").text('ออก');
                    $("#operation_type").val('2');
                }
                $("#cashdrawer_form").slideDown(300);
            }
            function check_other_title(val) {
                if (val === 'new_other') {
                    $("#New_Title").show().focus();
                } else {
                    $("#New_Title").hide().val('');
                }
            }
            function suspend_drawer(id, method, cash) {
                var cfm = confirm("Confirm Cancel?");
                if (cfm !== false) {
                    $(".suspend-button-" + id).hide();
                    $("#spare").load("operation.php?opr=suspend_drawer&id=" + id + "&br=" + br, function () {
                        $(".suspend-" + id).css("text-decoration", "line-through").css("color", "gray");
                        cash = parseFloat(cash);
                        var new_cash = 0;
                        if (method === '1') {
                            var amount_in = $("#amount_in").text();
                            amount_in = amount_in.replace(/,/g, '');
                            amount_in = parseFloat(amount_in);
                            new_cash = amount_in - cash;
                            new_cash = new_cash.toFixed(2);
                            $("#amount_in").text(new_cash);
                        } else if (method === '2') {

                            var amount_out = $("#amount_out").text();
                            amount_out = amount_out.replace(/,/g, '');
                            amount_out = parseFloat(amount_out);
                            new_cash = amount_out - cash;
                            new_cash = new_cash.toFixed(2);
                            $("#amount_out").text(new_cash);
                        }
                    });
                    $("#cancel-btn-" + id).hide(100);
                    $("#title-" + id).css('text-decoration', 'line-through');
                    amsalert("Canceled", "white", "green");
                }
            }
            function launch_popup(method) {
                $("#New_Title_Cover").hide();
                $("#New_Title").css("background-color", '#fff').val('');
                $('#OPR-POPUP').show().popup('open');
                $('#OPR-TYPE').val(method);
                $('#CONFIRM-BUTTON,#CONFIRM-LOADER').hide();
                var opr_name = '';
                if (method === 1) {
                    opr_name = 'Cash-In (นำเงินเข้า)';
                } else if (method === 2) {
                    opr_name = 'Cash-Out (นำเงินออก)';
                } else if (method === 0) {
                    opr_name = 'Send-Money (นำส่งเงิน)';
                }
                $('#OPR-NAME').text(opr_name);
                $('#PRICE').val('0');

                if (method === 0) {
                    $('#Title_Selection').text("");
                } else {
                    $('#Title_Selection').html("<img src='../../img/anonymous.gif'>").load("operation.php?opr=Title_Selection&method=" + method + "&br=" + br);
                }
            }
            function submit_form() {
                var operation_type = $("#OPR-TYPE").val();
                var total_remain = $("#TOTAL-REMAIN").text().replace(/,/, '');
                var amount_send = $("#amount_send").text().replace(/,/, '');
                var amount_in = $("#amount_in").text().replace(/,/, '');
                var amount_out = $("#amount_out").text().replace(/,/, '');
                var Title = $("#Title").val();
                if (Title === 'new___other') {
                    Title = $("#New_Title").val();
                }
                if (operation_type === '0') {
                    Title = 'Send Money';
                }
                if (Title) {
                    var Price = $("#PRICE").val();
                    var Remark = $("#REMARK").val();

                    if (Title && Price) {
                        $("#CONFIRM-BUTTON").slideUp(100);
                        $("#CONFIRM-LOADER").show();
                        var addition = "&Title=" + Title;
                        addition += "&Price=" + Price;
                        addition += "&Remark=" + Remark;
                        addition += "&operation_type=" + operation_type;
                        addition += "&br=" + br;
                        addition += "&totalRemain=" + total_remain;
                        addition += "&amount_send=" + amount_send;
                        addition += "&amount_in=" + amount_in;
                        addition += "&amount_out=" + amount_out;
                        addition += "&usrID=" + usrID;
                        addition += "&usr=" + usr;
                        addition = addition.replace(/ /g, "%20");
                        $("#spare").load("operation.php?opr=add_cash" + addition, function (data) {
                            $("#OPR-POPUP").popup("close");
                            $.post("http://localhost:8080?type=e&please_print=%0A<pulse>%0A");
                            $("#C_Data").prepend(data);
                        });
                    }
                } else {
                    $("#Title").css("border", "solid 2px red");
                    $("#New_Title").css("background-color", "red");
                }
            }
            function check_change() {
                var send_check = $("#send-check").prop("checked");
                var cashin_check = $("#cashin-check").prop("checked");
                var cashout_check = $("#cashout-check").prop("checked");
                var pos_check = $("#pos-check").prop("checked");
                if (send_check === true) {
                    $(".m0").show();
                } else {
                    $(".m0").hide();
                }
                if (cashin_check === true) {
                    $(".m1").show();
                } else {
                    $(".m1").hide();
                }
                if (cashout_check === true) {
                    $(".m2").show();
                } else {
                    $(".m2").hide();
                }
                if (pos_check === true) {
                    $(".m3").show();
                } else {
                    $(".m3").hide();
                }

                var range1 = parseInt($("#range-1").val());
                var range2 = parseInt($("#range-2").val());
                $(".CNODE").filter(function () {
                    return parseInt($(this).attr("cost")) < range1;
                }).hide();
                $(".CNODE").filter(function () {
                    return parseInt($(this).attr("cost")) > range2;
                }).hide();
            }
        </script>
    </head>
    <body>
        <?php
        include("../header/header.php");
        ?>
        <div style="background-color:#000;padding:10px;height:70px;">    
            <div style="float:right;color:#fff;font:bold 50px sans-serif;" id="TOTAL-REMAIN"><img src="../../img/ajaxloader.gif"></div>
        </div>
        <div>
            <div style="float:left;padding:5px;">
                <button onclick="launch_popup(1)" data-rel="popup" class="ui-btn ui-btn-icon-left ui-icon-arrow-d ui-corner-all cash-opr">Cash-In</button>
            </div>
            <div style="float:left;padding:5px;">
                <button onclick="launch_popup(2)" class="ui-btn ui-btn ui-btn-icon-left ui-icon-arrow-u-r ui-corner-all cash-opr">Cash-Out</button>
            </div>
            <div style="float:left;padding:5px;">
                <button onclick="launch_popup(0)" class="ui-btn ui-btn ui-btn-icon-left ui-icon-action ui-corner-all ui-btn-b cash-opr">Send-Money</button>
            </div>
        </div>
        <div style="padding:20px;">
            <div style="clear:both;">
                <div style='float:left;'><input type="date" id="df" value="<?= date('Y-m-d') ?>"></div>
                <div style="float:left;padding:18px 5px 0 5px;">-</div>
                <div style='float:left;'><input type="date" id="dt" value="<?= date('Y-m-d') ?>"></div>
                <div style="float:left;padding:5px;"><button class="ui-btn ui-btn-inline ui-icon-search ui-btn-icon-left ui-corner-all ui-shadow ui-mini" onclick="load_drawer()">Search</button></div>
            </div>
            <div style="clear:both;width:200px;margin-left:20px;" onclick="$('#advance-section').slideToggle(300);$(this).hide(300)">Advance Search</div>
            <div style="clear:both;display:none;width:600px;" id="advance-section">
                <div style="clear:both;float:left;background-color:#000;width:200px;padding:5px 5px 1px 5px;color:#fff;margin:2px;border-radius:3px;">
                    <label style="float:left;width:100px;padding-top:10px;text-align:center;">Send Money</label>
                    <input type="checkbox" value="1" data-role="flipswitch" id="send-check" checked="checked" onchange="check_change()">
                </div>
                <div style="float:left;background-color:green;width:200px;padding:5px 5px 1px 5px;color:#fff;margin:2px;border-radius:3px;">
                    <label style="float:left;width:100px;padding-top:10px;text-align:center;">Cash-In</label>
                    <input type="checkbox" value="1" data-role="flipswitch" id="cashin-check" checked="checked" onchange="check_change()">
                </div>
                <div style="clear:both;float:left;background-color:orange;width:200px;padding:5px 5px 1px 5px;color:#fff;margin:2px;border-radius:3px;">
                    <label style="float:left;width:100px;padding-top:10px;text-align:center;">Cash-Out</label>
                    <input type="checkbox" value="1" data-role="flipswitch" id="cashout-check" checked="checked" onchange="check_change()">
                </div>
                <div style="float:left;background-color:blue;width:200px;padding:5px 5px 1px 5px;color:#fff;margin:2px;border-radius:3px;">
                    <label style="float:left;width:100px;padding-top:10px;text-align:center;">POS</label>
                    <input type="checkbox" value="1" data-role="flipswitch" id="pos-check" checked="checked" onchange="check_change()">
                </div>
                <div style="clear:both;"></div>
                <div data-role="rangeslider" style="width:500px;">
                    <label for="range-1a">Price Range : </label>
                    <input type="range" name="range-1" id="range-1" min="0" max="50000" value="0" onchange="check_change()">
                    <input type="range" name="range-1b" id="range-2" min="0" max="50000" value="50000" onchange="check_change()">
                </div>
            </div>
        </div>

        <div  data-role="popup" id="OPR-POPUP" data-overlay-theme="b" class="ui-content" style="display:none;background-color:#111;color:#fff;border:solid 5px #000;">
            <div id="OPR-NAME" style="font:bold 20px sans-serif;text-align:center;"></div>
            <div id="Title_Selection"></div>
            <div id="New_Title_Cover" style="display:none;">
                <input id="New_Title" type="text" placeholder="ระบุรายละเอียดใหม่">
            </div>
            <div style='float:left;width:500px'>
                <?php
                $money_note = array(1000, 500, 100, 50, 20, 10, 5, 2, 1);
                foreach ($money_note as $money_note_value) {
                    ?>
                    <div class='PAYMENT-NOTE' style="background:url('../../img/bank_note/th/th_<?= $money_note_value ?>.png');background-size:contain;" money='<?= $money_note_value ?>'></div>
                <?php }
                ?>
            </div>
            <div style="float:left;">
                <input type="number" id="PRICE" placeholder="ยอดเงิน" value="0" style="text-align:right;font-size:20px;">
                <input type="text" id="REMARK" placeholder="หมายเหตุ">
                <input type="hidden" id="OPR-TYPE">
                <button onclick="submit_form()" id="CONFIRM-BUTTON" style="display:none;">ยืนยัน</button>
                <div id="CONFIRM-LOADER" style="text-align:center;"><img src="../../img/ajaxloader.gif"></div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div id="cashdrawer" style="clear:both;"><img src="../../img/ajaxloader.gif" style="margin:100px;"></div>
        <div id="spare" style="display:none;"></div>
    </body>
</html>
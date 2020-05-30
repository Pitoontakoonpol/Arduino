localStorage.set_menu_lang = '';
localStorage.set_currency = '';
var br = localStorage.br;
var usr = localStorage.usr;
var usrID = localStorage.usrID;

var permission = localStorage.permission;
var Set_Brief_Report = localStorage.Set_Brief_Report;
var Set_Screen2 = localStorage.Set_Screen2;
var Set_Bill_Title = localStorage.Set_Bill_Title;
var Set_Address_Title = localStorage.Set_Address_Title;
var Set_System_View = localStorage.Set_System_View;
var Set_TAXID = localStorage.Set_TAXID;
var Set_POSID = localStorage.Set_POSID;
var Set_Queue = localStorage.Set_Queue;
var Set_Footer = localStorage.Set_Footer;
var Set_Footer_Option = localStorage.Set_Footer_Option;
var Set_Lang_POS = localStorage.Set_Lang_POS;
var Set_Lang_Bill = localStorage.Set_Lang_Bill;
var Set_Currency = localStorage.Set_Currency;
var permission_split = permission.split("");
var Permission_Void = permission_split[9];
var Login_Time = localStorage.login_time;

$(document).ready(function () {
    $('*').keypress(function (event) {
        if ($("#CREDIT-REMARK").is(":focus")) {
        } else {
            $('#DIALOG-STANDBY').focus();
        }
    });

    $("#SIGNAL-ACCOUNT").html(usr + "@" + br);
    load_section_top(br);
    load_infras();
    set_currency();
    load_brief_report();
});

function load_infras() {
    $("#SECTION-RIGHT,#SECTION-RIGHT-INNER").css("height", window.innerHeight - 60).delay(500).animate({
        right: 0
    }, 300);

    if (Set_System_View === '1') {
        $("#DIALOG-ORDER-LIST-SUPERMARKET").css("height", window.innerHeight);
        $("#SECTION-TOP").hide();
        $("#SECTION-MENU").hide();
    } else {
        $("#DIALOG-ORDER-LIST-SUPERMARKET").hide();
    }

    $("#DIALOG-ORDER-LIST").css({
        height: window.innerHeight - 310
    });
    $("#POPUP-PAYMENT,#POPUP-MEMBER,#POPUP-HISTORY").css({
        width: window.innerWidth - 450,
        height: window.innerHeight - 100
    });
    $("#PAYMENT-NOTE-SECTION").css({
        height: window.innerHeight - 180
    });
    $("#MEMBER-SEARCH-RESULT").css({
        height: window.innerHeight - 150
    });
    $("#POPUP-HISTORY-LIST").css({
        height: window.innerHeight - 200
    });
    $("#HEADER-SECTION").css({
        width: window.innerWidth - 100
    });
    $("#INVOICE-HEADER-LOGO").html("<img src='http://posambient.com/branch_asset/" + br + ".png' style='max-width:220px;'>");
    if (Set_Footer_Option) {
        var Set_Footer_Option_Split = Set_Footer_Option.split(",");
        var total_opt = '';
        for (var opt = 0; opt <= Set_Footer_Option_Split.length; opt++) {
            if (Set_Footer_Option_Split[opt]) {
                total_opt += "<option>" + Set_Footer_Option_Split[opt] + "</option>";
            }
        }
        $('#OPTIONAL-FOOTER-COVER').html("<select id='OPTIONAL-FOOTER' class='ui-content' data-theme='b'><option value=''>Footer Comment</option>" + total_opt + "</select>").trigger("create");
    }
}
function set_currency() {
    if (Set_Currency) {
        $("#NOTE-" + Set_Currency).show();
    } else {
        $("#NOTE-TH").show();
    }
}

function load_section_top(br) {
    $("#SECTION-TOP").load("section_top.php?br=" + br, function () {
        load_section_menu(br);
    });
}
function load_section_menu(br) {
    $("#SECTION-MENU").load("section_menu.php?br=" + br + "&Set_Lang_POS=" + Set_Lang_POS + "&Set_Lang_Bill=" + Set_Lang_Bill, function () {
        $("#SECTION-TOPUP").css("width", window.innerWidth - 350);
        $("#DISCOUNT-INFO").load("discount_info.php?br=" + br, function () {
            $(this).trigger("create");
        });
    });
}
function load_brief_report() {
    if (Set_Brief_Report && Set_Brief_Report > 0) {
        $("#spare").load("report.php?opr=brief&br=" + br);
    } else {
        $("#BRIEF-REPORT").hide();
    }
}
function scroll_to_type(typeid) {
    $("html, body").animate({scrollTop: $("#MENU-TYPE-BAR-" + typeid).offset().top - 70}, 500);
}

//################### Quantity the MENU to List

function order_reset() {
    $(".MENU-ORDER-QTY").slideUp(100).text('0');
    $(".MENU-NODE").css("background-color", '#fff');
    $("#DIALOG-TOTAL-PRICE").text('0.00');
    $("#DIALOG-TOTAL-QTY").html("0 <span class='unit-style'>piece</span>").attr('val', '0');
    $("#DIALOG-TOTAL-POINT").html("0 <span class='unit-style'>point</span>").attr('val', '0');
    $("#DIALOG-TOTAL-REDEEM").html("0 <span class='unit-style'>redeem</span>").attr('val', '0');
    $("#SECTION-TOPUP").slideUp(200);
    $(".ORDER-LIST-NODE").remove();
    $("#MEMBER-SEARCH").val('').css('background-color', '#ccc');
    $("#MEMBER-SEARCH-RESULT").text('');
    $("#MEMBERID,#MEMBER-NAME").val('');
    $("#MEMBERID").val('');
    $("#DIALOG-LOGO").html("<img src='../../img/pos_logo.png'>");
    $("#MEMBER-NAME").val('');
    $("#MEMBER-ACTIVE").text('Member').css("background-color", "transparent");
    $(".SAVING-TOPUP").text('');
    $(".SAVING-TOPUP-PRICE").text('0');

}

function ambient_time(Tformat) {
//#####Get Date
    var d = new Date();
//##### End of Get Date
    if (Tformat === 1) {
        //#####DateU
        var dateU = d.getTime() / 1000;
        return dateU;
    } else if (Tformat === 2) {
        //#####Readable
        var dateRead = d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear() + ' ' + d.getHours();

        if (d.getMinutes() < 10) {
            dateRead += ':0' + d.getMinutes();
        } else {
            dateRead += ':' + d.getMinutes();
        }
        if (d.getSeconds() < 10) {
            dateRead += ':0' + d.getSeconds();
        } else {
            dateRead += ':' + d.getSeconds();
        }
        return dateRead;
    } else if (Tformat === 3) {
        //#####Time Session
        var dateSession = parseInt(d.getHours() * 3600) + parseInt(d.getMinutes() * 60) + parseInt(d.getSeconds());
        dateSession = Math.floor(dateSession / 900);
        return dateSession;
    }
}
function numbering_menu(menuID, qty) {

    var d = new Date();
    var dateU = Math.round(d.getTime() / 1000);
    if (!Login_Time || dateU - Login_Time > 57600) {
        alert("You've log in the system for more than 16Hours now, Please login again!");
        logout();
    }


    $("#INVOICE-PAPER").hide(200);

    $("#SUBMIT-BUTTON,#REPRINT-BUTTON").hide();
//#####Get Menu Data
    if (Set_Lang_POS !== Set_Lang_Bill) {
        var menuName = $("#DISPLAY-DATA-" + menuID).attr('menuBill');
    } else {
        var menuName = $("#DISPLAY-DATA-" + menuID).attr('menuName');
    }
    var menuPrice = $("#DISPLAY-DATA-" + menuID).attr('menuPrice');
    var menuPoint = $("#DISPLAY-DATA-" + menuID).attr('menuPoint');
    var menuRedeem = $("#DISPLAY-DATA-" + menuID).attr('menuRedeem');
    var menuTopup = $("#DISPLAY-DATA-" + menuID).attr('menuTopup');
//####Add the Qty/Price
    var currentMenuQty = $("#MENU-ORDER-QTY-" + menuID).text();
    var currentMenuTopupPrice = $("#SAVING-TOPUP-PRICE-" + menuID).text();
    var currentTotalQty = $("#DIALOG-TOTAL-QTY").attr("val");
    var currentTotalPoint = $("#DIALOG-TOTAL-POINT").attr("val");
    var currentTotalRedeem = $("#DIALOG-TOTAL-REDEEM").attr("val");
    var currentTotalPrice = $("#DIALOG-TOTAL-PRICE").text();
    var newTotalQty = 0;
    var newTotalPoint = 0;
    var newTotalRedeem = 0;
    var newTotalPrice = 0;
    if ((qty > 0 && parseFloat(currentMenuQty) >= 0) || (qty < 0 && parseFloat(currentMenuQty) >= 1)) {
//#####Plus or Minus
        var newMenuQty = parseFloat(currentMenuQty) + qty;
        if (newMenuQty >= 0) {
            newTotalQty = parseFloat(currentTotalQty) + qty;
            newTotalPoint = parseFloat(currentTotalPoint) + parseFloat(menuPoint * qty);
            newTotalRedeem = parseFloat(currentTotalRedeem) + parseFloat(menuRedeem * qty);
            newTotalPrice = parseFloat(currentTotalPrice) + parseFloat(menuPrice * qty);
        } else {
            newMenuQty = 0;
        }
        if (qty < 0) {
            //##Deduct Topup
            //    topup_menu(0, -1);
        }
    } else {
//#####Delete
        var newMenuQty = 0;
        newTotalQty = parseFloat(currentTotalQty) - parseFloat(currentMenuQty);
        newTotalPoint = parseFloat(currentTotalPoint) - parseFloat(menuPoint * currentMenuQty);
        newTotalRedeem = parseFloat(currentTotalRedeem) - parseFloat(menuRedeem * currentMenuQty);
        newTotalPrice = parseFloat(currentTotalPrice) - parseFloat(menuPrice * currentMenuQty);
        if (menuTopup) {
            newTotalPrice = newTotalPrice - parseFloat(currentMenuTopupPrice);
        }
    }
    $("#MENU-ORDER-QTY-" + menuID).text(newMenuQty);
    $("#DIALOG-TOTAL-QTY").html(newTotalQty + " <span class='unit-style'>piece(s)</span>").attr("val", newTotalQty);
    $("#DIALOG-TOTAL-POINT").html(newTotalPoint + " <span class='unit-style'>point(s)</span>").attr("val", newTotalPoint);
    $("#DIALOG-TOTAL-REDEEM").html(newTotalRedeem + " <span class='unit-style'>redeem(s)</span>").attr("val", newTotalRedeem);
    $("#DIALOG-TOTAL-PRICE").text(newTotalPrice.toFixed(2));
    if (newMenuQty > 0) {
        $("#MENU-ORDER-QTY-" + menuID).slideDown(100);
        if ($("#ORDER-LIST-NODE-" + menuID).text()) {
//###plus/minus quantity if Menu already existed
            $("#ORDER-LIST-QTY-" + menuID).text(newMenuQty + "x ");
            $("#ORDER-LIST-TOTAL-PRICE-" + menuID).text(newMenuQty * menuPrice);
            var currentPrintVal = $("#ORDER-LIST-NODE-" + menuID).attr('printval');
            var currentPrintValSplit = currentPrintVal.split('_____');
            var currentPrintValQty = parseFloat(currentPrintValSplit[1]);
            var newPrintVal = currentPrintVal.replace('menu_____' + currentPrintValQty + '_____', 'menu_____' + newMenuQty + '_____');
            $("#ORDER-LIST-NODE-" + menuID).attr("printval", newPrintVal);
        } else {
//###create new order-list-node if Menu NOT existed
            if (Set_System_View === '1') {
                //##### SUPERMARKET View
                var order_listID = 'DIALOG-ORDER-LIST-SUPERMARKET';
                //##### Show Picture
                $.get("../../../pos/picture/" + menuID + ".jpg").done(function () {
                    $("#DIALOG-ORDER-LIST").html("<img src='../../../pos/picture/" + menuID + ".jpg'>");
                }).fail(function () {
                    $.get("../../../pos/picture/" + menuID + ".png").done(function () {
                        $("#DIALOG-ORDER-LIST").html("<img src='../../../pos/picture/" + menuID + ".png'>");
                    }).fail(function () {
                        $("#DIALOG-ORDER-LIST").html("no Picture");
                    });
                });
            } else {
                //##### POS View
                var order_listID = 'DIALOG-ORDER-LIST';
            }
            $("#" + order_listID).append("<div class='ORDER-LIST-NODE PRINT-LINE' printval='menu_____" + newMenuQty + "_____" + menuName + "_____" + menuPrice + "' onclick='order_list_click(this)' id='ORDER-LIST-NODE-" + menuID + "'><div id='ORDER-LIST-DEL-" + menuID + "' onclick='numbering_menu(" + menuID + ",0)'><img src='../../img/action/trash-white-25.png' style='width:15px;'></div><div id='ORDER-LIST-QTY-" + menuID + "'>" + newMenuQty + "x </div><div id='ORDER-LIST-NAME-" + menuID + "'>" + menuName + "</div><div id='ORDER-LIST-PRICE-" + menuID + "'>" + menuPrice + "</div><div id='ORDER-LIST-TOTAL-PRICE-" + menuID + "'>" + menuPrice + "</div><div id='ORDER-TOPUP-" + menuID + "'></div></div>");

        }
    } else {
        $("#MENU-ORDER-QTY-" + menuID).slideUp(100);
        $("#SAVING-TOPUP-" + menuID).text('');
        $("#SAVING-TOPUP-PRICE-" + menuID).text('0');
        $("#SECTION-TOPUP").slideUp(200);
        //###delete order-list-node
        $("#ORDER-LIST-NODE-" + menuID).remove();
    }
//####to Display after adjust
    $(".MENU-NODE").css("background-color", '#fff');
    $("#MENU-NODE-" + menuID).css("background-color", 'orange');
    //################# Show/Hide Topup
    if (menuTopup && newMenuQty > 0) {
        $(".TOPUP-NODE").css("background-color", "#fff"); //Change topup color

        var existTopup = $("#SAVING-TOPUP-" + menuID).text();
        if (existTopup && existTopup.substr(existTopup.length - 3) !== '|') {
            $("#SAVING-TOPUP-" + menuID).append('|');
            $("#ORDER-TOPUP-" + menuID).append("<div class='TOPUP-PARTITION  PRINT-LINE' printval='partition_____'></div>");
        }
        $(".TOPUP-NODE").hide();
        var node_display = menuTopup.replace(/-/g, ",#TOPUP-NODE-").substr(1);
        $(node_display).show();
        $("#SECTION-TOPUP").slideDown(200);
    } else {
        $("#SECTION-TOPUP").slideUp(200);
    }
//################# End of Show/Hide Topup
//#####to Developer
    $("#last_menu_opr").val(menuID);
//alert(Set_Screen2);
    if (Set_Screen2) {
        screen2();
    }
}
function topup_menu(topupID, qty) {
//##### Get Developer Data
    var last_menu_opr = $("#last_menu_opr").val();
    //##### Get Topup Data
    var existTopup = $("#SAVING-TOPUP-" + last_menu_opr).text();
    var topupName = $("#DISPLAY-DATA-TOPUP-" + topupID).attr('topupname');
    var topupPrice = parseFloat($("#DISPLAY-DATA-TOPUP-" + topupID).attr('topupprice'));
    if (existTopup === '') {
        $("#SAVING-TOPUP-" + last_menu_opr).append("|");
    }
    var lastTopupSplit = existTopup.split("|");
    var lastTopupBlock = lastTopupSplit[lastTopupSplit.length - 1];
    //#####Check Existed
    var checkExists = lastTopupBlock.split('-');
    for (var LT = 1; LT < checkExists.length; LT++) {
        if (checkExists[LT] === topupID) {
            var topup_Existed = 1;
            break;
        }
    }
    if (!topup_Existed) {
//##### Adding topup to Menu
        $("#SAVING-TOPUP-" + last_menu_opr).append("-" + topupID);
        $("#ORDER-TOPUP-" + last_menu_opr).append("<div class='ORDER-LIST-TOPUP  PRINT-LINE' printval='topup_____1_____" + topupName + "_____" + topupPrice + "'><div class='ORDER-LIST-TOPUP-NAME'>" + topupName + "</div><div class='ORDER-LIST-TOPUP-PRICE'>" + topupPrice + "</div></div>");
        if (topupPrice) {
            var currentTopupPrice = $("#SAVING-TOPUP-PRICE-" + last_menu_opr).text();
            var currentTotalPrice = $("#DIALOG-TOTAL-PRICE").text();
            var newTopupPrice = parseFloat(currentTopupPrice) + topupPrice;
            var newTotalPrice = parseFloat(currentTotalPrice) + topupPrice;
            $("#SAVING-TOPUP-PRICE-" + last_menu_opr).text(newTopupPrice);
            $("#DIALOG-TOTAL-PRICE").text(newTotalPrice.toFixed(2));
        }
    }
//##### Change Topup Background Color
    $("#TOPUP-NODE-" + topupID).css("background-color", "orange");
    if (Set_Screen2) {
        screen2();
    }
}
function screen2() {
    var total_price = $("#DIALOG-TOTAL-PRICE").text();

    var URI = "<html><head></head><body>";
    URI += "<div style='font:normal 30px sans-serif;color:blue'>Total&nbsp;Amount</h1>";
    URI += "<div style='height:130px;font:bold 120px sans-serif;color:black;'>THB " + total_price + "</div>";
    URI += "</body></html>";
    URI = encodeURIComponent(URI);
    $("#customer_display").load("http://localhost:8080/sec_screen_show?html=" + URI);
}
function prepare_order() {
    //#########When Payment Popup Open
    var prepareMenu = '';
    $(".ORDER-LIST-NODE").each(function () {
        var orderMenuID = $(this).attr('id').replace('ORDER-LIST-NODE-', '');
        var orderLineQty = $("#MENU-ORDER-QTY-" + orderMenuID).text();
        var orderLinePrice = $("#DISPLAY-DATA-" + orderMenuID).attr('menuPrice');
        var orderTopupPrice = $("#SAVING-TOPUP-PRICE-" + orderMenuID).text();
        var orderTopup = $("#SAVING-TOPUP-" + orderMenuID).text();
//#### DETAILED FORMAT          <menuID>_ <qty>_<unitPrice>_<totalTopupPrice>_<topupDetail>
        var orderLineMenu = "," + orderMenuID + "_" + orderLineQty + "_" + orderLinePrice;
        if (orderTopup) {
            orderLineMenu += "_" + orderTopupPrice + "_" + orderTopup;
        }
        prepareMenu += orderLineMenu;
    });
    $("#PREPARE-MENU").val(prepareMenu);


    //##### Prepare Print Data
    var preparePrint = '';
    $(".PRINT-LINE").each(function (index) {
        preparePrint += $(this).attr('printval') + "|||||";
    });
    $("#PREPARE-PRINT").val(preparePrint);
}
function submit_order() {
    //#########When Pressing Confirm button
    var prepareMenu = $("#PREPARE-MENU").val();
    var memberID = $("#MEMBERID").val();
    var memberName = $("#MEMBER-NAME").val().replace(/ /g, "%20");
    var previousPoint = parseInt($("#PREVIOUS-POINT").val());
    var purchasePoint = parseInt($("#DIALOG-TOTAL-POINT").attr("val"));
    var purchaseRedeem = parseInt($("#DIALOG-TOTAL-REDEEM").attr("val"));
    var paymentType = $("#PAYMENT-TYPE").val();
    var totalPiece = $("#DIALOG-TOTAL-QTY").attr('val');
    if (paymentType === 'point') {
        purchasePoint = purchaseRedeem * (-1);
    }
    var discount = $("#DISCOUNT").val().split("___");
    var discountid = discount[1];
    var discount_desc = discount[2];
    discount = parseInt(discount[0]);
    if (!discountid) {
        discountid = 0;
    }

    var paymentTypeRemark = $("#PAYMENT-TYPE-REMARK").val();
    order_reset();
    $("#CONFIRM-BUTTON").hide();
    $("#REPRINT-BUTTON").delay(1000).slideDown(200);

    var preparePrint = $("#PREPARE-PRINT").val();
    var apkPrint = '';
    var totalPrice = 0;
    var totalQty = 0;
    var preparePrintLine = preparePrint.split("|||||");
    for (var printLine = 0; printLine <= preparePrintLine.length - 1; printLine++) {
        if (preparePrintLine[printLine]) {
            var printColumn = preparePrintLine[printLine].split('_____');
            var columnType = printColumn[0];
            var columnQty = printColumn[1];
            var columnName = printColumn[2];
            var columnPrice = printColumn[3];
            if (columnType === 'menu') {
                apkPrint += "%0A_bm_v1_500_30_22_0_25_" + columnQty + "x%20" + columnName + "_____" + comma((columnPrice * columnQty).toFixed(2));
                totalQty = totalQty + columnQty;
                totalPrice = totalPrice + (columnPrice * columnQty);
            } else if (columnType === 'topup') {
                apkPrint += "%0A_bm_v1_500_30_22_0_25_%20%20%20%20%20%20%20%20" + columnName + "_____" + comma((1 * columnPrice).toFixed(2));
                totalPrice = totalPrice + (columnPrice * columnQty);
            } else if (columnType === 'partition') {
                apkPrint += "%0A_bm_v1_500_30_22_0_25_%20%20%20%20%20%20%20%20----------";
            }
        }
    }
    if (apkPrint) {
        var ServiceNoPrev = localStorage.ServiceNo_Last;
        var ServiceQueuePrev = localStorage.ServiceQueue_Last;
        //##### Generate / Get Service Number
        var d = new Date();
        var serviceY = d.getFullYear().toString().substr(2, 2);
        var serviceM = ("0" + (d.getMonth() + 1)).slice(-2);
        var serviceD = ("0" + (d.getDate())).slice(-2);
        var serviceSection1 = serviceY + serviceM + serviceD;
        var opt_footer = $("#OPTIONAL-FOOTER").val().replace(/ /g, "%20");
        if (!ServiceNoPrev || ServiceNoPrev.substring(0, 6) !== serviceSection1) {
            var NextServiceNo = serviceSection1 + ("00000" + br).slice(-5) + "00001";
            var NextServiceQueue = 1;
        } else {
            var NextServiceNo = parseInt(localStorage.ServiceNo_Last) + 1;
            var NextServiceQueue = parseInt(ServiceQueuePrev) + 1;
        }
        localStorage.ServiceNo_Last = NextServiceNo;
        localStorage.ServiceQueue_Last = NextServiceQueue;
        NextServiceQueue = parseInt(NextServiceNo.toString().slice(-5));
        //##### End of Generate / Get Service Number

        var dateU = ambient_time(1);
        var dateRead = ambient_time(2);
        var dateSession = ambient_time(3);

        var apkHeader = "<align_center>%0A_draw_logo_" + br + ".png%0A<feed_line>";
        if (Set_Bill_Title) {
            apkHeader += "%0A<align_center>%0A_bm_v1_500_30_22_0_25_%0A" + Set_Bill_Title;
        }
        if (Set_Address_Title) {
            apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_" + Set_Address_Title;
        }
        if (Set_TAXID) {
            apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Tax%20ID:%20" + Set_TAXID;
        }
        if (Set_POSID) {
            apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Reg%20ID:%20" + Set_POSID;
        }

        apkHeader += "%0A<align_left>%0A_bm_v1_500_25_22_0_25_" + dateRead + "_____No." + NextServiceNo;
        apkHeader += "%0A<align_center>%0A__________________________________________%0A";
        if (Set_Queue === '1') {
            apkHeader += "%0A<align_center>%0A<text_double%201%201>%0AQueue%20No.%20" + NextServiceQueue + "%0A<text_double%200%200>";
            apkHeader += "%0A<align_center>%0A__________________________________________%0A";
        }
        var apkFooter = '';
        if (discount > 0) {
            var totalDiscount = totalPrice * (discount / 100);
            totalPrice = totalPrice - totalDiscount;
            apkFooter += "%0A_bm_v1_500_30_22_0_25_%20%20%20%20%20%20%20%20%20%20Discount%20" + discount_desc + "%20" + discount + "%25_____-" + totalDiscount.toFixed(2);
        }
        apkFooter += "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal:" + comma(totalPrice.toFixed(2)) + "%0A";

        if (memberID) {
            apkFooter += "%0A<align_left>%0A_bm_v1_500_40_22_0_25_Member:%20" + memberName;
        } else {
            memberID = 0;
        }
        if (previousPoint && memberID) {
            apkFooter += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Point%20Before:%20" + previousPoint + "pt.";
        }
        if (purchasePoint && memberID) {
            apkFooter += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Point%20on%20Purchase:%20" + purchasePoint + "pt.";
        } else {
            purchasePoint = 0;
        }
        if (purchasePoint && memberID) {
            var availablePoint = purchasePoint + previousPoint;
            apkFooter += "%0A<align_left>%0A_bm_v1_500_40_22_0_25_Point%20Available:%20" + availablePoint + "pt.";
        }
        apkFooter += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Paid%20by:%20" + paymentType + "%20" + paymentTypeRemark + "%0A";
        if (opt_footer) {
            apkFooter += "%0A<align_center>%0A<text_double%200%200>%0A________________________________________%0A";
            apkFooter += "%0A_bm_v1_500_30_22_10_25_%0A<align_center>%0A" + opt_footer;
        }
        if (Set_Footer) {
            apkFooter += "%0A<align_center>%0A<text_double%200%200>%0A________________________________________%0A";
            apkFooter += "%0A_bm_v1_500_30_22_10_25_%0A<align_center>%0A" + Set_Footer;
        }
        var sendPrint = apkHeader + apkPrint + apkFooter;
        $("#LAST-PRINT").val(sendPrint);
        //#####Cache Order
        var currentCacheOrder = localStorage.cacheOrder;

        //##### Format  ServiceNo,UserID,MemberID,Submit_Time,Date_Session,Menu etc...
        var newCacheOrder = NextServiceNo + "," + usrID + "," + memberID + "," + paymentType + "," + paymentTypeRemark + "," + purchasePoint + "," + totalPrice + "," + discount + "," + discountid + "," + Math.round(dateU) + "," + dateSession + prepareMenu + "|||";
        localStorage.cacheOrder = currentCacheOrder + newCacheOrder;
        $("#orderCache").val(localStorage.cacheOrder);
        sync_server();
        var pulse = 0;
        if (paymentType === 'Cash') {
            pulse = 1;
        }
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
        if (isAndroid) {
            printAPK(sendPrint, pulse);
        } else {
            printLinux(sendPrint, pulse);
        }
        update_brief_report(totalPrice, totalPiece);
    }
    $("#SIGNAL-ORDER-CACHE").show();
    $("#TOTAL-CACHE").text(parseInt($("#TOTAL-CACHE").text()) + 1);
}
function update_brief_report(more_total, more_qty) {

    if (Set_Brief_Report && Set_Brief_Report > 0) {
        var current_ttl = $("#BRIEF-TOTAL").text().replace(/,/, "");
        var current_qty = $("#BRIEF-QTY").text().replace(/,/, "");
        var current_bill = $("#BRIEF-BILL").text().replace(/,/, "");

        $("#BRIEF-TOTAL").text(parseFloat(current_ttl) + parseFloat(more_total));
        $("#BRIEF-QTY").text(parseInt(current_qty) + parseInt(more_qty));
        $("#BRIEF-BILL").text(parseInt(current_bill) + 1);
    }
}
function reprint_order() {
    var sendPrint = $("#LAST-PRINT").val();
    printAPK(sendPrint, 0);
}
function reprint_history() {
    var apkHeader = "<align_center>%0A_draw_logo_" + br + ".png%0A<feed_line>";
    if (Set_Bill_Title) {
        apkHeader += "%0A<align_center>%0A_bm_v1_500_30_22_0_25_%0A" + Set_Bill_Title;
    }
    if (Set_Address_Title) {
        apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_" + Set_Address_Title;
    }
    if (Set_TAXID) {
        apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Tax%20ID:%20" + Set_TAXID;
    }
    if (Set_POSID) {
        apkHeader += "%0A<align_left>%0A_bm_v1_500_30_22_0_25_Reg%20ID:%20" + Set_POSID;
    }
    var sendPrint = $("#apkPrint_History").val();
    printAPK(apkHeader + sendPrint, 0);
}
function printAPK(printVal, pulse) {
    printVal = printVal.replace(/ /g, '%20');
    var printCommand = '';
    if (pulse === 1) {
        printCommand = "http://localhost:8080?type=e&please_print=%0A<pulse>%0A";
    } else {
        printCommand = "http://localhost:8080?type=e&please_print=";
    }
    $("#apkPrint").load(printCommand + printVal + "%0A<cut>%0A");
}
function sync_server() {
    var cOrder = localStorage.cacheOrder;
    var serviceNo_Sync = '';
    if (cOrder) {
        $("#SIGNAL-NETWORK-ERROR").show();
        $("#SIGNAL-NETWORK-NORMAL").hide();
        var cService = cOrder.split('|||');
        var eachService = '';
        var eachOrder = '';
        var eachOrderDetail = '';
        var serviceNo = '';
        var usrID = '';
        var memberID = '';
        var paymentType = '';
        var paymentTypeRemark = '';
        var purchasePoint = '';
        var totalPrice = '';
        var discount = '';
        var promotionID = 0;
        var dateTime = '';
        var dateSession = '';
        var menuID = '';
        var menuQty = '';
        var menuPrice = '';
        var menuTopup = '';
        var menuTopupPrice = '';
        var sqlString = '';
        for (var s = 0; s <= cService.length - 2; s++) {
            //####Break Each ServiceNo
            eachService = cService[s];
            var eService = eachService.split(',');
            serviceNo = eService[0];
            usrID = eService[1];
            memberID = eService[2];
            paymentType = eService[3];
            paymentTypeRemark = eService[4];
            purchasePoint = eService[5];
            totalPrice = eService[6];
            discount = eService[7];
            promotionID = eService[8];
            dateTime = eService[9];
            dateSession = eService[10];
            for (var or = 11; or <= eService.length - 1; or++) {
                //####Break Each Menu in each ServiceNo
                eachOrder = eService[or]; //###Get each Order
                eachOrderDetail = eachOrder.split("_"); //###Get each Order Detail
                menuID = eachOrderDetail[0];
                menuQty = eachOrderDetail[1];
                menuPrice = eachOrderDetail[2];
                menuTopupPrice = eachOrderDetail[3];
                menuTopup = eachOrderDetail[4];

//##### Generate Record Line
                sqlString += "(";
                sqlString += br + ",";
                sqlString += serviceNo + ",";
                sqlString += usrID + ",";
                sqlString += memberID + ",";
                sqlString += "'" + paymentType + "',";
                sqlString += "'" + paymentTypeRemark.replace('Get:', '') + "',";
                sqlString += purchasePoint + ",";
                sqlString += dateTime + ",";
                sqlString += dateSession + ",";
                sqlString += promotionID + ",";
                sqlString += menuID;
                sqlString += "," + menuQty;
                sqlString += "," + menuPrice;
                sqlString += "," + (discount / 100) * menuPrice;
                if (menuTopup) {
                    sqlString += "," + menuTopupPrice;
                    sqlString += ",'" + menuTopup + "'";
                } else {
                    sqlString += ",0";
                    sqlString += ",''";
                }
                sqlString += "),";
                //##### Generate Record Line

            }
            serviceNo_Sync += serviceNo + ",";
        }

//########################Sync Order
        $.post("sync_server.php", {sqlString: sqlString, br: br, memberID: memberID, purchasePoint: purchasePoint, paymentType: paymentType, paymentTypeRemark: paymentTypeRemark, totalPrice: totalPrice})
                .done(function (data) {
                    $("#SYSTEM-NOTE").val(data);
                    clear_order_cache();
                    $("#SIGNAL-NETWORK-ERROR").hide();
                    $("#SIGNAL-NETWORK-NORMAL").show();
                    $("#SIGNAL-ORDER-CACHE").hide();
                    $("#TOTAL-CACHE").text('0');
                    localStorage.cacheStock += serviceNo_Sync;
                    sync_stock();
                });
    }
}
function sync_stock() {
    var stock_sync = localStorage.cacheStock;
    stock_sync = stock_sync.replace(/undefined/g, "");
    $.post("sync_stock.php", {stock_sync: stock_sync, br: br, usrID: usrID})
            .done(function (data) {
                $("#DEVELOPER-REMARK").val(data);
                localStorage.cacheStock = '';
            });
}
function clear_order_cache() {
    localStorage.cacheOrder = '';
    localStorage.ServiceQueue_Last = '';
}
function suspend_popup() {
    var order_list = $('#DIALOG-ORDER-LIST').text();
    $("#Suspend-Title").val('');

    $('#POPUP-SUSPEND').popup('open');
    if (order_list) {
        //### Ready For New SUspend
        $("#NEW-SUSPEND").show();
        $("#SUSPEND-SAVE-BTN").text('Save').attr('disabled', false);
        $("#SUSPEND-LIST").hide();
        $("#CLEAR-SUSPEND-LIST").hide();
    } else {
        //### List current Suspend Bill
        $("#NEW-SUSPEND").hide();
        var suspend_title = localStorage.Suspend_Title;
        var suspend_time = localStorage.Suspend_Time;
        var suspend_title_split = suspend_title.split("|||");
        var suspend_time_split = suspend_time.split("|||");
        var total_suspend = ' ';
        for (var i = 1; i <= suspend_title_split.length - 1; i++) {
            total_suspend += "<div class='suspend-list'><span id='suspend-time-" + i + "'>" + suspend_time_split[i] + "</span><div style='position:absolute;top:0;right:0'><button class='ui-btn ui-icon-print ui-btn-icon-notext ui-corner-all ui-btn-inline ui-btn-b' onclick='print_suspend(" + i + ")'>Print</button><button class='ui-btn ui-icon-back ui-btn-icon-notext ui-corner-all ui-btn-inline ui-btn-b' onclick='recover_suspend(" + i + ")'>Recover</button></div><div style='clear:both;' id='suspend-title-" + i + "'>" + suspend_title_split[i] + "</div></div>";
        }
        $("#SUSPEND-LIST").show().html(total_suspend);

        $("#CLEAR-SUSPEND-LIST").show();
    }
}
function add_suspend() {
    $("#SUSPEND-SAVE-BTN").text('Saving...').attr('disabled', true);
    var suspend_title = $("#Suspend-Title").val();
    var suspend_line = '';
    $(".MENU-ORDER-QTY").each(function () {
        if ($(this).text() !== '0') {
            var activeID = $(this).attr('id').replace("MENU-ORDER-QTY-", "");
            var activeQty = $(this).text();
            var activeTopup = $("#SAVING-TOPUP-" + activeID).text();
            suspend_line += activeID + "," + activeQty + "," + activeTopup + "___";
        }
    });
    var d = new Date();
    var h = d.getHours();
    var i = d.getMinutes();
    var s = d.getSeconds();

    if (i < 10) {
        i = '0' + i;
    }
    if (s < 10) {
        s = '0' + s;
    }

    var suspend_time = h + ":" + i + ":" + s;
    localStorage.Suspend_Title += "|||" + suspend_title;
    localStorage.Suspend_Time += "|||" + suspend_time;
    localStorage.Suspend_Desc += "|||" + suspend_line;
    $("#POPUP-SUSPEND").popup("close");
    order_reset();
}
function recover_suspend(line) {
    var title = $("#suspend-title-" + line).text();
    var cfm = confirm('Confirm Recover ' + title + '?');
    if (cfm !== false) {
        order_reset();
        var Suspend_Desc = localStorage.Suspend_Desc.split("|||");
        var desc_line = Suspend_Desc[line].split("___");

        for (var j = 0; j < desc_line.length; j++) {
            var product_sale = desc_line[j].split(",");
            var productID = product_sale[0];
            var productQty = product_sale[1];
            if (productID) {
                $("#last_menu_opr").val(productID);
                if (!product_sale[2]) {
                    numbering_menu(productID, parseInt(productQty));
                } else {
                    //##### if topup
                    var productTopup = product_sale[2].split("|");
                    for (var q = 1; q <= parseInt(productQty); q++) {
                        numbering_menu(productID, 1);

                        if (productTopup[q]) {
                            var this_topup = productTopup[q].split("-");
                            for (var t = 1; t < this_topup.length; t++) {
                                topup_menu(this_topup[t], 1);
                            }
                        }
                    }
                }
            }
        }
        remove_suspend(line);
        $("#POPUP-SUSPEND").popup('close');
    }
}
function remove_suspend(line) {
    var Suspend_Title = localStorage.Suspend_Title.split("|||");
    var Suspend_Time = localStorage.Suspend_Time.split("|||");
    var Suspend_Desc = localStorage.Suspend_Desc.split("|||");
    var New_Suspend_Title = '';
    var New_Suspend_Time = '';
    var New_Suspend_Desc = '';

    for (var j = 1; j < Suspend_Title.length; j++) {
        if (j !== line) {
            New_Suspend_Title += '|||' + Suspend_Title[j];
            New_Suspend_Time += '|||' + Suspend_Time[j];
            New_Suspend_Desc += '|||' + Suspend_Desc[j];
        }
    }
    localStorage.Suspend_Title = New_Suspend_Title;
    localStorage.Suspend_Time = New_Suspend_Time;
    localStorage.Suspend_Desc = New_Suspend_Desc;


}
function print_suspend(line) {
    var Suspend_Desc = localStorage.Suspend_Desc.split("|||");
    var desc_line = Suspend_Desc[line].split("___");
    var title = $("#suspend-title-" + line).text();
    var time = $("#suspend-time-" + line).text();

    var apkPrint = "%0A_bm_v1_500_60_52_0_55_" + title;
    apkPrint += "%0A_bm_v1_500_30_22_0_25_Updated: " + time;
    apkPrint += "%0A<align_center>%0A__________________________________________%0A";
    var total_suspend_price = 0;
    for (var j = 0; j < desc_line.length; j++) {
        var product_sale = desc_line[j].split(",");
        var productID = product_sale[0];
        var productQty = product_sale[1];
        if (productID) {
            var product_Name = $("#DISPLAY-DATA-" + productID).attr('menuName');
            var product_Price = $("#DISPLAY-DATA-" + productID).attr('menuPrice');
            apkPrint += "%0A_bm_v1_500_30_22_0_25_" + productQty + "x%20" + product_Name + "_____" + comma((product_Price * productQty).toFixed(2));
            total_suspend_price = total_suspend_price + (product_Price * productQty);
        }

    }

    apkPrint += "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal:" + comma(total_suspend_price.toFixed(2)) + "%0A";
    printAPK(apkPrint, 0);

}
function clear_suspend() {
    var cfm = confirm('Confirm?');
    if (cfm !== false) {
        alert('Cleared');
        localStorage.Suspend_Title = "";
        localStorage.Suspend_Time = "";
        localStorage.Suspend_Desc = "";
        $("#POPUP-SUSPEND").popup("close");
    }
}
function check_promotion(menuID) {
    var Pr_Range = $("#Pr_Range").val();
    var Pr_ProductID = Pr_Range.split("___");
    var ppID = '';
    for (var pr = 1; pr <= Pr_ProductID.length - 1; pr++) {
        ppID = Pr_ProductID[pr];
        if (ppID === menuID) {
            operate_promotion(menuID);
            break;
        }
    }
}

function operate_promotion(menuID) {
    $(".pr_list").each(function () {
        var pr_pid = $(this).attr("pr_pid");
        if (pr_pid.indexOf(menuID) >= 0) {
            var prid = $(this).attr("id");
            var pr_type = $(this).attr("pr_type");

            alert(pr_type);
            if (pr_type === '3') {
                alert('t3');
            }
        }
    });
}
function printLinux(printVal, pulse) {
    var last_print = $("#LAST-PRINT").val();
    var print_paper = '';
    last_print = last_print.replace(/%0A%0A/g, "%0A");
    last_print = last_print.replace(/%20/g, " ");

    var expld1 = last_print.split('%0A');
    for (var s = 3; s < expld1.length; s++) {
        var line = expld1[s];
        if (line === '<align_center>') {
            line = "</div><div style='text-align:center;'>";
        } else if (line === '<align_left>') {
            line = "</div><div style='text-align:left;'>";
        } else if (line === '<align_right>') {
            line = "</div><div style='text-align:right;'>";
        } else if (line === '<text_double 1 1>') {
            line = "</div><div style='font:normal 30px sans-serif;text-align:center;'>";
        } else if (line === '<feed_line>') {
            line = "<br/>";
        } else if (line.indexOf('_bm_v1_') >= 0) {
            var manage_line = line.split("_____");
            var line_desc = manage_line[0].split("_");
            var Lhight = parseInt(line_desc[4]) / 2;
            var Fsize = parseInt(line_desc[5]) / 2;
            line = "</div>";
            line += "<div style='clear:both;height:" + Lhight + "px'>";
            line += "<div style='float:left;font-size:" + Fsize + "px'>";
            if (line_desc[8].indexOf("Total:") === 0) {
                line += line_desc[8];
            } else {
                line += line_desc[8];
            }
            line += "</div>";
            if (manage_line[1]) {
                line += "<div style='float:right;font-size:" + Fsize + "px'>";
                line += manage_line[1];
                line += "</div>";
            }
        }

        print_paper += line;
    }

    print_paper = print_paper.substr(6) + "</div>";

    $("#INVOICE-LINE-DATA").html(print_paper);
    $("#INVOICE-PAPER").show();
    $("#POPUP-PAYMENT").popup("close");
}
function history_popup() {

    $("#POPUP-HISTORY-DESC,#POPUP-HISTORY-OPR").hide();
    $("#POPUP-HISTORY").popup("open");
    $("#POPUP-HISTORY-LIST").html("<img src='../../img/ajaxloader.gif'>").load("history.php?opr=list&br=" + br);
}
function history_desc(serviceNo) {
    $(".HISTORY-BTN").attr("histsn", serviceNo);
    var void_status = parseInt($(".h" + serviceNo).attr("void_status"));
    $("#POPUP-HISTORY-DESC").show().html("<img src='../../img/ajaxloader.gif'>").load("history.php?opr=desc&serviceNo=" + serviceNo, function () {

        $("#POPUP-HISTORY-OPR").slideDown(300);
        if (void_status > 0) {
            $(".HISTORY-BTN").hide();
        } else if (Permission_Void === '1') {
            $(".HISTORY-BTN").show();
        } else {
            $(".HISTORY-BTN").hide();
        }
    });
}
function void_bill(type) {
    var ServiceNo = $(".HISTORY-BTN").attr("histsn");
    var cfm;
    if (type === 2) {
        cfm = confirm("ยังไม่ผลิต → ยกเลิก, \nวัตถุดิบที่นำมาผลิตจะไม่ถูกหักนำมาใช้งาน\nยืนยัน ?");
    } else if (type === 1) {
        cfm = confirm("ผลิตแล้ว → ยกเลิก, \nวัตถุดิบที่นำมาผลิตจะยังคงถูกหักนำมาใช้งาน\nยืนยัน ?");
    }

    if (cfm === true) {
        $(".HISTORY-BTN").hide();
        $("#cancel_bar_" + ServiceNo).html("<img src='../../img/ajaxloader.gif'>");
        $("#spare").load("../report/cancel_order.php?cancel_type=" + type + "&serviceNo=" + ServiceNo + "&br=" + br + "&usrID=" + usrID, function () {

            $(".h" + ServiceNo).css("text-decoration", "line-through");
            if (type === 1) {
                $(".h" + ServiceNo).css({backgroundColor: "red", color: 'white'});
                $(".h" + ServiceNo).attr("void_status", '1');
            } else if (type === 2) {
                $(".h" + ServiceNo).css({backgroundColor: "#666", color: 'white'});
                $(".h" + ServiceNo).attr("void_status", '2');
            }

        });
    }
}
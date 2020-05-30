
var StandbyTimeout;
var br = localStorage.br;
$(document).ready(function () {
  $(".PAYMENT-NOTE").bind("tap", function () {
    var money = parseFloat($(this).attr('money'));
    calculate_money(money);
  });
  $(".PAYMENT-POINT").bind("tap", function () {
    calculate_point();
  });
  $(".PAYMENT-CREDIT").bind("tap", function () {
    var paymenttype = $(this).attr('paymenttype');
    calculate_credit(paymenttype);
  });
  $(".PAYMENT-QR").bind("tap", function () {
    var paymenttype = $(this).attr('paymenttype');
    $("#PAYMENT-TYPE").val(paymenttype);
    var total_price_popup = parseFloat($("#POPUP-MONEY-TOTAL").text());
    $("#POPUP-MONEY-GET").text('0.00');
    calculate_money(total_price_popup);
  });
  $(".PAYMENT-VOUCHER").bind("tap", function () {
    $('#VOUCHER-NUMBER').show().focus();
  });

  $('#VOUCHER-NUMBER').keyup(function (e) {
    if (e.keyCode === 13) {
      voucher_info();
    }
  });



  $("#POPUP-MONEY-TOTAL").bind("tap", function () {
    var total_price_popup = parseFloat($("#POPUP-MONEY-TOTAL").text());
    $("#POPUP-MONEY-GET").text('0.00');
    calculate_money(total_price_popup);
  });

  $(".numpad").bind("tap", function () {
    var padValue = $(this).attr('padvalue');
    if (padValue === 'R') {
      var currentLength = $("#MEMBER-SEARCH").val().length;
      $("#MEMBER-SEARCH").val($("#MEMBER-SEARCH").val().substr(0, currentLength - 1));
    } else {
      $("#MEMBER-SEARCH").val($("#MEMBER-SEARCH").val() + padValue);
    }
  });

  $('#DIALOG-STANDBY').keyup(function (e) {
    if (e.keyCode === 13) {
      var standbyValue = $(this).val();
      check_standby_enter(standbyValue);
    }
  });
  
  $('#MEMBER-SEARCH').keyup(function (e) {
    if (e.keyCode === 13) {
      search_member();
    }
  });
  
  $("#POPUP-MEMBER,#POPUP-PAYMENT,#POPUP-SUSPEND").on("popupafterclose", function () {
    //  standby();
  });
  //  standby();
});
/*
 function standby() {
 $("#DIALOG-STANDBY").focus();
 standby_trigger();
 }
 function standby_trigger() {
 StandbyTimeout = setTimeout(function () {
 standby();
 }, 3000);
 }
 */
function check_standby_enter(standbyValue) {
  if (standbyValue) {
    var qty_split = standbyValue.split('*');
    var qty = 1;
    if (qty_split[1]) {
      qty = parseFloat(qty_split[0]);
      standbyValue = qty_split[1];
    }
    var menuID = $("div[menuBarcode='" + standbyValue + "']").attr('menuID');
    if (!menuID) {
      var menuID = $("div[menuCode='" + standbyValue + "']").attr('menuID');
    }
    if (menuID) {
      numbering_menu(menuID, qty);
    }
    $('#DIALOG-STANDBY').val('');
  }
}
function payment_popup() {
  var total_price = parseFloat($("#DIALOG-TOTAL-PRICE").text());
  var point_avail = parseInt($("#MEMBER-POINT-AVAILABLE").text());
  var currentTotalRedeem = $("#DIALOG-TOTAL-REDEEM").attr("val");
  $("#POPUP-MONEY-TOTAL").text(total_price.toFixed(2));
  $("#POPUP-MONEY-REFUND").text((total_price * (-1)).toFixed(2));
  $("#MONEY-REFUND-COVER").css({backgroundColor: 'red', color: 'white'});
  $("#POPUP-MONEY-GET").text('0.00');
  $("#DISCOUNT").val('0').change();
  $("#CONFIRM-BUTTON").hide();
  $("#DISCOUNT").show();
  $('#POPUP-PAYMENT').popup('open');
  $('#PAYMENT-MESSAGE').hide().text('');
  $('#CREDIT-REMARK').hide().val('');
  $("#PAYMENT-TYPE").val('');
  $("#PAYMENT-TYPE-REFUND").val('');
  $("#PAYMENT-TYPE-REMARK").val('');
  $("#VOUCHER-NUMBER").hide().val('');
  $("#VOUCHER-INFO").hide().val('');
  $("#OPTIONAL-FOOTER").val('').change();
  $("#DIALOG-ORDER-LIST img").fadeOut(200);
  if (currentTotalRedeem <= point_avail && currentTotalRedeem > 0) {
    $("#PAYMENT-POINT").show();
  } else {
    $("#PAYMENT-POINT").hide();
  }
  prepare_order();
  clearTimeout(StandbyTimeout);
}
function member_popup() {
  $('#POPUP-MEMBER').popup('open');
  $('#MEMBER-SEARCH').focus();
  clearTimeout(StandbyTimeout);
}



function calculate_money(money) {
  var current_total_get = parseFloat($("#POPUP-MONEY-GET").text());
  var total_price = parseFloat($("#DIALOG-TOTAL-PRICE").text());

  if (money === 'discount') {


    var pre_discount = $("#DISCOUNT").val().split("___");
    var discount = parseFloat(pre_discount[0]);



    var discount = parseFloat($("#DISCOUNT").val());
    var new_total_price = total_price * ((100 - discount) / 100);
    $("#POPUP-MONEY-TOTAL").text(new_total_price.toFixed(2));
    money = 0;
  }
  var total_price_popup = parseFloat($("#POPUP-MONEY-TOTAL").text());
  var total_get = current_total_get + money;
  var total_refund = total_get - total_price_popup;
  $("#POPUP-MONEY-GET").text(total_get.toFixed(2));
  $("#POPUP-MONEY-REFUND").text(total_refund.toFixed(2));
  if (total_get >= total_price_popup) {
    $("#CONFIRM-BUTTON").slideDown(100);
    $("#MONEY-REFUND-COVER").css({backgroundColor: '#00ff00', color: 'black'});
    $("#DISCOUNT").hide();
  } else {
    $("#CONFIRM-BUTTON").hide();
    $("#DISCOUNT").slideDown(100);
    $("#MONEY-REFUND-COVER").css({backgroundColor: 'red', color: 'white'});
  }
  if ($("#PAYMENT-TYPE").val() === '') {
    $("#PAYMENT-TYPE").val('0');
  }
  $("#PAYMENT-TYPE-REFUND").val(total_refund.toFixed(2));
  $("#PAYMENT-MESSAGE").hide().text('');
  $("#CREDIT-REMARK").hide();
}
function calculate_credit(paymenttype) {
  $("#PAYMENT-MESSAGE").show().html('<br/>Connecting EDC...<br/>Please Insert Card');
  $("#CREDIT-REMARK").show().focus();
  $("#PAYMENT-TYPE").val(paymenttype);
}
function credit_remark() {
  var creditRemark = $("#CREDIT-REMARK").val();
  var total_price = parseFloat($("#DIALOG-TOTAL-PRICE").text());
  if (creditRemark.length >= 4) {
    $("#POPUP-MONEY-GET").text(total_price.toFixed(2));
    $("#POPUP-MONEY-REFUND").text('0.00');
    $("#CONFIRM-BUTTON").slideDown();
    $("#DISCOUNT").hide();
    $("#PAYMENT-TYPE-REMARK").val(creditRemark);
  } else {
    $("#POPUP-MONEY-GET").text('0.00');
    $("#POPUP-MONEY-REFUND").text((total_price * (-1)).toFixed(2));
    $("#CONFIRM-BUTTON").hide();
    $("#DISCOUNT").slideDown(100);
  }
}
function calculate_point() {
  $("#PAYMENT-TYPE").val('9');
  var total_price = parseFloat($("#DIALOG-TOTAL-PRICE").text());
  $("#POPUP-MONEY-GET").text(total_price.toFixed(2));
  $("#POPUP-MONEY-REFUND").text('0.00');
  $("#CONFIRM-BUTTON").slideDown();
  $("#DISCOUNT").hide();
}
function voucher_info() {
  var voucherNo = $("#VOUCHER-NUMBER").val();
  $("#VOUCHER-INFO")
          .show()
          .html("<div style='color:#000;text-align:center;'><img src='../../img/ajaxloader.gif'> Voucher Info ... </div>")
          .load("voucher_search_result.php?voucherNo=" + voucherNo + "&br=" + br);
}
function order_list_click(ths) {
  var menuID = ths.id;
  menuID = menuID.replace('ORDER-LIST-NODE-', '');
  $("html, body").animate({scrollTop: $("#MENU-NODE-" + menuID).offset().top - 70}, 500);
}
function search_member() {
  var searchText = $("#MEMBER-SEARCH").val();
  searchText = searchText.replace(/ /g, '%20');
  if (searchText.length >= 3) {
    $("#MEMBER-SEARCH").css("background-color", 'yellow');
    $("#MEMBER-SEARCH-RESULT")
            .html("<div style='color:#000;text-align:center;'><img src='../../img/ajaxloader.gif'> Connecting Server ... </div>")
            .load("member_search_result.php?searchText=" + searchText + "&br=" + br);
  } else {
    $("#MEMBER-SEARCH").css("background-color", 'red');
  }
}
function submit_member(memberID, memberName, PreviousPoint) {
  $("#MEMBERID").val(memberID);
  $("#MEMBER-NAME").val(memberName);
  $("#PREVIOUS-POINT").val(PreviousPoint);
  $("#MEMBER-POINT-AVAILABLE").text(PreviousPoint);
  $("#DIALOG-LOGO").html("<div style='background-color:yellow;'><b>" + memberName + "</b> (" + PreviousPoint + "pt.)</div>");
  $('#POPUP-MEMBER').popup('close');

}
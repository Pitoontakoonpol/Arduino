var br = localStorage.BMbr;
var usr = localStorage.usr;
var usrID = localStorage.usrID;
$(document).ready(function () {
  var innerHeight = window.innerHeight;
  $(".shade").slideUp(1200);
  $(".shade2").delay(500).slideUp(1200);
  $("#tab_result_area").css("height", innerHeight - 90);
  bm_work();
  section1();
});
function bm_work() {
$("#branch_work").load("branch_work.php?bmbr="+br);
}
function section1() {
  var date = $("#time-period").val();
  if (date === 'advance') {
    $('#advance-time').slideDown(200);
    var adv_from = $("#adv_from").val();
    var adv_to = $("#adv_to").val();
    date = adv_from + "-" + adv_to;
  }
  $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/section1.php?d=" + date + "&br=" + br, function () {
    $("#load_section2_button").fadeIn(500);
  });
}
function load_section2() {
  var date = $("#time-period").val();
  var today_record = $("#today_record").text();
  today_record = today_record.replace(/,/g, "");
  $("#load_section2_button").slideUp(300);
  $("#section2").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/section2.php?d=" + date + "&today_record=" + today_record + "&br=" + br);
}
function load_section4() {
  var date = $("#time-period").val();
  var today_record = $("#today_record").text();
  today_record = today_record.replace(/,/g, "");
  $("#load_section4_button").slideUp(300);
  $("#section4").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/section4.php?d=" + date + "&today_record=" + today_record + "&br=" + br);
}

function change_page(submit_advance) {
  var tab = $("#tab").val();
  var date = $("#time-period").val();
  var ord = $("#ord").val();
  var branch_advance=$("#branch_work_select").val();
  var additional = '';

  $('#tab2-option').hide();
  if (date === 'advance') {
    $('#advance-time').slideDown(200);
    var adv_from = $("#adv_from").val();
    var adv_to = $("#adv_to").val();
    if (submit_advance === 1) {
      date = adv_from + "-" + adv_to;
    }
  } else {
    $('#advance-time').slideUp(200);
  }
  if(branch_advance){
    br=branch_advance;
  }
  
  $("#tab_result_area").show();
  $("#tab_tax_result").hide();

  if (tab === '1') {
    $(".print-button-summary").hide();
    $(".print-button").hide();
    $(".export-button").hide();
    if (date != 'advance') {
      section1();
    }
  } else if (tab === '2') {

    $(".print-button-summary").hide();
    $(".print-button").show();
    $(".export-button").show();
    if (!$('input#view_order').is(':checked') && !$('input#view_vat').is(':checked') && !$('input#view_discount').is(':checked') && !$('input#view_sc').is(':checked')) {
      $('input#view_order').prop('checked', true);
      $('input#view_vat').prop('checked', true);
      $('input#view_discount').prop('checked', true);
      $('input#view_sc').prop('checked', true);
      additional += '-vo-vv-vd-vs';
    }

    $('#tab2-option').show();
    if ($('input#view_order').is(':checked')) {
      additional += '-vo';
    }
    if ($('input#view_vat').is(':checked')) {
      additional += '-vv';
    }
    if ($('input#view_discount').is(':checked')) {
      additional += '-vd';
    }
    if ($('input#view_sc').is(':checked')) {
      additional += '-vs';
    }
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/2_order_by_order.php?tab=" + tab + "&br=" + br + "&d=" + date + "&usr=" + usr + "&additional=" + additional);
  } else if (tab === '3') {
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/3_order_by_menu.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord);
  } else if (tab === '4') {
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/4_order_by_type.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord);
  } else if (tab === '5') {
    $(".print-button-summary").hide();
    $(".print-button").hide();
    $(".export-button").hide();
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/5_stock_by_order.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord);
  } else if (tab === '5_1') {
    $(".print-button-summary").hide();
    $(".print-button").hide();
    $(".export-button").hide();
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/5_1stock_by_order.php?tab=" + tab + "&br=" + br + "&d=" + date);
  } else if (tab === '6') {
    var user_select = '';
    $(".summary-user").each(function () {
      if ($(this).prop('checked')) {
        user_select += this.id + "___";

      }
    });
    $(".print-button").hide();
    $(".print-button-summary").show();
    $(".export-button").hide();
    $("#tab_result_area").hide();
    $("#tab_tax_result").show().html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/summary_slip.php?tab=" + tab + "&d=" + date + "&br=" + br + "&user_select=" + user_select);
  } else if (tab === '7') {
    $(".print-button-summary").hide();
    $(".print-button").hide();
    $(".export-button").hide();
    $("#tab_result_area").hide();
    $("#tab_tax_result").show().html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/tax_report.php?tab=" + tab + "&d=" + date + "&br=" + br);
  } else {
  }
}
function order_in_page(order_by) {
  var tab = $("#tab").val();
  var date = $("#time-period").val();
  $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/order_by_bill.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + order_by);

}

function cancel_order(ServiceNo, type) {
  type = parseInt(type);
  var cfm;
  if (type === 2) {
    cfm = confirm("ยังไม่ผลิต → ยกเลิก, \nวัตถุดิบที่นำมาผลิตจะไม่ถูกหักนำมาใช้งาน\nยืนยัน ?");
  } else if (type === 1) {
    cfm = confirm("ผลิตแล้ว → ยกเลิก, \nวัตถุดิบที่นำมาผลิตจะยังคงถูกหักนำมาใช้งาน\nยืนยัน ?");
  }
  if (cfm === true) {
    $("#cancel_bar_" + ServiceNo).html("<img src='../../img/ajaxloader.gif'>");
    $("#spare").load("../../admin/report/cancel_order.php?cancel_type=" + type + "&serviceNo=" + ServiceNo + "&br=" + br + "&usrID=" + usrID, function () {

      $(".h" + ServiceNo).css("text-decoration", "line-through");
      if (type === 1) {
        $(".h" + ServiceNo).css("color", "red");
      } else if (type === 2) {
        $(".h" + ServiceNo).css("color", "#666");
      }
      $("#cancel_bar_" + ServiceNo).hide();
    });
  }
}
function search_member_tax() {
  var mn = $("#mn").val();
  var mt = $("#mt").val();
  if (mn.length >= 3 || mt.length >= 3) {
    $("#search_member_result").html("<img src='../../img/ajax_loader.gif'>").load("../../admin/report/tax.php?operation=search_member&mn=" + mn + "&mt=" + mt + "&br=" + br, function () {

    });
  }
}
function print_history(serviceNo, timeDeliver, tableName) {
  tableName = tableName.replace(/ /g, "%20");
  $("#invoice_view").show().html("<img src='../../img/ajaxloader.gif'> Loading Invoice ...");
  $("#invoice_view").load("../table/operation.php?operation_report=order_list_from_report&serviceNo=" + serviceNo + "&timeDeliver=" + timeDeliver + "&table_name=" + tableName + "&br=" + br);

}
function do_print_slip_history() {

  var POS_ANDROID = $("#POS_ANDROID").val();
  if (POS_ANDROID) {
  } else {
    $(".report2,#sub-bar,#tab2-option").hide();

    $("#invoice_view").css('width', '300px');
    window.print();
    $(".report2,#sub-bar,#tab2-option").show();
    $("#invoice_view").css('width', '350px');
  }
}
function issue_tax(MemberID, Name, Billing_Address, TaxNo) {

  var ServiceNo = $("#sn").val();
  ServiceNo = ServiceNo.substring(1);
  var cfm = confirm("Confirm issue Full Tax Invoice?");
  if (cfm !== false) {
    $("#tax-button-" + ServiceNo).hide();
    $("#find-member").fadeOut(200);
    $("#invoice_view").show().html("<img src='../../img/ajaxloader.gif'> Loading Invoice ...");

    var additional = "&MemberID=" + MemberID;
    additional += "&Name=" + Name;
    additional += "&Billing_Address=" + Billing_Address;
    additional += "&TaxNo=" + TaxNo;
    additional += "&ServiceNo=" + ServiceNo;
    additional = additional.replace(/ /g, "%20");
    $("#invoice_view").html("<img src='../../img/ajaxloader.gif'>").load("../../admin/report/tax.php?operation=issue_tax" + additional + "&br=" + br, function () {

    });
  }
}
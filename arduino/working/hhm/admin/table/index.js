
$(document).ready(function () {
  var screen_width = window.innerWidth;
  $("#table_area").css("width", screen_width - 500);

  var Table_Open = ($("#Table_Open").val());
  var Table_split = Table_Open.split(",");
  for (var i = 0; i < Table_split.length - 1; i++) {
    //alert(Table_split[i]);
    $("#" + Table_split[i]).css("fill", "SandyBrown");
  }

  $(".table_desk").click(function () {
    $(".table_desk").css("stroke", "#836559");
    $(this).css("stroke", "orange");
    $("#current_table").val(this.id);
    var current_color = $(this).css("fill");
    if (current_color === 'rgb(55, 53, 53)') {
      $("#open_table").show();
      $("#make_order").hide();
      $("#add_discount").hide();
      $("#check_out").hide();
    } else if (current_color === 'rgb(244, 164, 96)') {
      $("#open_table").hide();
      $("#make_order").show();
      $("#add_discount").show();
      $("#check_out").show();
      order_list(1, '');
    }
  });
});
function do_open() {
  var table_name = $("#current_table").val();
  $("#spare").load("operation.php?operation=open_table&table_name=" + table_name, function () {
    $("#" + table_name).css("fill", "SandyBrown");
    $("#open_table").hide();
    $("#make_order").show();
    $("#add_discount").show();
    $("#check_out").show();
  });
}
function do_close() {
  var table_name = $("#current_table").val();
  $("#" + table_name).css("fill", "Brown");
  $("#open_table").show();
  $("#make_order").hide();
  $("#add_discount").hide();
  $("#check_out").hide();
}
function order_list(type, payment_type) {
  var table_name = $("#current_table").val();
  $("#cdc_remark").val('');
  $("#cash_remark").val('');
  payment_type = payment_type.replace(/ /g, "%20");
  $("#order_now").load("operation.php?operation=order_list&table_name=" + table_name + "&type=" + type + "&payment_type=" + payment_type, function (data) {
  });
}
function make_order() {
  var table_name = $("#current_table").val();
  table_name = table_name.replace(/ /g, "%20");
  window.location.assign("../pos/?t=" + table_name);

}
function del_order(orderID, menuName) {
  var cfm = confirm('Confirm remove ' + menuName + '?');
  if (cfm !== false) {
    $("#spare").load("operation.php?operation=order_del&delID=" + orderID, function (data) {
      order_list(1, '');
    });
  }
}
function add_discount() {
  var table_name = $("#current_table").val();
  var discount = $('#dc_percent').val();
  $("#order_now").load("operation.php?operation=add_discount&table_name=" + table_name + "&discount=" + discount, function (data) {
    order_list(1, '');
  });
}
function check_out1() {
  $("#Payment_Form").fadeIn(300);
}
function check_out2(payment_type) {
  var cfm = confirm('Confirm Check Out with ' + payment_type + '?');
  if (cfm !== false) {
    var table_name = $("#current_table").val();
    var cdc_remark = $("#cdc_remark").val();
    var cash_remark = $("#cash_remark").val();
    var total_vat = $("#total_vat").val();
    var grand_total = $("#grand_total").val();
    var total_service_charge = $("#total_service_charge").val();
    cash_remark = cash_remark.replace(/ /g, "%20");
    var payment_remark = '';
    if (payment_type === 'Cash') {
      payment_remark = cash_remark;
    } else {
      payment_remark = cdc_remark;
    }
    do_close();
    $("#Payment_Form").fadeOut(300);
    $(".menu_del").hide();
    $("#spare").load("operation.php?operation=check_out&table_name=" + table_name + "&payment_type=" + payment_type + "&payment_remark=" + payment_remark + "&grand_total=" + grand_total + "&total_vat=" + total_vat + "&total_service_charge=" + total_service_charge, function () {
      $(".receipt-header").show();
      $(".invoice-header").hide();
    });
  }
}
function do_print(print_detail, dateU, branchID, pulse, bill_remark) {
  var POS_ANDROID = $("#POS_ANDROID").val();
  if (POS_ANDROID) {
    var paid_by = $('#paid-by').text();
    var print_header = "http://localhost:8080?type=e&please_print=<align_center>%0A_draw_logo_" + branchID + ".png%0A<feed_line>";
    print_header += "%0A<align_center>%0A_bm_v1_500_25_40_0_25_Invoice";
    print_header += "%0A<align_left>%0A_bm_v1_500_25_22_0_25_" + dateU;
    print_header += "%0A<align_center>%0A__________________________________________%0A";
    print_detail += "%0A%0A<text_double%200%200>%0A<align_left>%0ATable : " + bill_remark;
    var print_all = print_header + print_detail;
    if (paid_by) {
      print_all += "%0A<align_left>%0A_bm_v1_500_25_22_0_25_" + paid_by;
    }
    print_all += "%0A<cut>%0A";
    print_all = print_all.replace(/ /g, "%20");
    if (pulse === 1) {
      print_all += "%0A<pulse>%0A";
    }
    $("#spare").load(print_all);

  } else {
    window.print();
  }
}
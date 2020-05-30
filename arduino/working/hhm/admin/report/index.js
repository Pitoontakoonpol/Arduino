var br = localStorage.br;
var usr = localStorage.usr;
var usrID = localStorage.usrID;
var innerWidth = window.innerWidth;
var innerHeight = window.innerHeight;

var permission = localStorage.permission;
var permission_split = permission.split("");
var Permission_Void = permission_split[9];
var Permission_Set_Restaurant = localStorage.Set_Restaurant;
$(document).ready(function () {
    $(".shade").slideUp(1200);
    $(".shade2").delay(500).slideUp(1200);
    $("#tab_result_area").css("height", innerHeight - 90);
    $("#SERVICE-DESC-POPUP").css("height", innerHeight).css("width", innerWidth);
    $("#SERVICE-DESC-DESC").css("height", innerHeight - 100);
    
    section1();
});
function section1() {
    var date = $("#time-period").val();
    if (date === 'advance') {
        $('#advance-time').slideDown(200);
        var adv_from = $("#adv_from").val();
        var adv_to = $("#adv_to").val();
        date = adv_from + "-" + adv_to;
    }
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("section1.php?d=" + date + "&br=" + br + "&ww=" + innerWidth + "&Set_Restaurant=" + Permission_Set_Restaurant, function () {
        $("#load_section2_button").fadeIn(500);
    });
}
function load_section2() {
    var date = $("#time-period").val();
    var today_record = $("#today_record").text();
    today_record = today_record.replace(/,/g, "");
    $("#load_section2_button").slideUp(300);
    $("#section2").html("<img src='../../img/ajaxloader.gif'>").load("section2.php?d=" + date + "&today_record=" + today_record + "&br=" + br + "&Set_Restaurant=" + Permission_Set_Restaurant);
}
function load_section4() {
    var date = $("#time-period").val();
    var today_record = $("#today_record").text();
    today_record = today_record.replace(/,/g, "");
    $("#load_section4_button").slideUp(300);
    $("#section4").html("<img src='../../img/ajaxloader.gif'>").load("section4.php?d=" + date + "&today_record=" + today_record + "&br=" + br);
}

function change_page(submit_advance) {
    var tab = $("#tab").val();
    var date = $("#time-period").val();
    var ord = $("#ord").val();
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
    $("#tab_result_area").show();
    $("#tab_tax_result").hide();

    if (tab === '1') {
        $(".print-button-summary").hide();
        $(".print-button").hide();
        $(".export-button").hide();
        if (date !== 'advance') {
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
        if (Permission_Set_Restaurant === '1') {
            $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("2_order_by_order_restaurant.php?tab=" + tab + "&br=" + br + "&d=" + date + "&usr=" + usr + "&additional=" + additional + "&Permission_Void=" + Permission_Void);
        } else {
            $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("2_order_by_order.php?tab=" + tab + "&br=" + br + "&d=" + date + "&usr=" + usr + "&additional=" + additional + "&Permission_Void=" + Permission_Void);
        }
    } else if (tab === '3') {
        $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("3_order_by_menu.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord + "&Set_Restaurant=" + Permission_Set_Restaurant);
    } else if (tab === '4') {
        $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("4_order_by_type.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord + "&Set_Restaurant=" + Permission_Set_Restaurant);
    } else if (tab === '5') {
        $(".print-button-summary").hide();
        $(".print-button").hide();
        $(".export-button").hide();
        $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("5_stock_by_order.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord + "&Set_Restaurant=" + Permission_Set_Restaurant);
    } else if (tab === '5_1') {
        $(".print-button-summary").hide();
        $(".print-button").hide();
        $(".export-button").hide();
        $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("5_1_stock_by_raw_mat.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord + "&Set_Restaurant=" + Permission_Set_Restaurant);
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
        $("#tab_tax_result").show().html("<img src='../../img/ajaxloader.gif'>").load("summary_slip.php?tab=" + tab + "&d=" + date + "&br=" + br + "&user_select=" + user_select + "&Set_Restaurant=" + Permission_Set_Restaurant);
    } else if (tab === '7') {
        $(".print-button-summary").hide();
        $(".print-button").hide();
        $(".export-button").hide();
        $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("7_import.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + ord);
    } else {
    }
}
function order_in_page(order_by) {
    var tab = $("#tab").val();
    var date = $("#time-period").val();
    $("#tab_result_area").html("<img src='../../img/ajaxloader.gif'>").load("order_by_bill.php?tab=" + tab + "&br=" + br + "&d=" + date + "&ord=" + order_by);

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
        $("#spare").load("cancel_order.php?cancel_type=" + type + "&serviceNo=" + ServiceNo + "&br=" + br + "&usrID=" + usrID, function () {

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
function full_tax1(sn) {
    $("#INVOICE-POPUP").popup("open");
}

function service_desc(serviceNo) {
    if (!serviceNo || serviceNo === '0') {
        serviceNo = $("#SERVICE-DESC-TITLE").text();
    }
    $("#SERVICE-DESC-TITLE").text(serviceNo);
    $("#SERVICE-DESC-POPUP").popup("open");
    $("#SERVICE-DESC-DESC").load("2_service_desc.php?br=" + br + "&serviceNo=" + serviceNo);
}
function service_operation(serviceNo) {
    if (!serviceNo || serviceNo === '0') {
        serviceNo = $("#SERVICE-DESC-TITLE").text();
    }
    $("#SERVICE-DESC-TITLE").text(serviceNo);
    $("#SERVICE-DESC-POPUP").popup("open");
    $("#SERVICE-DESC-DESC").load("2_service_manage.php?br=" + br + "&serviceNo=" + serviceNo, function () {
        $(this).trigger('create');
    });
}
function tax_customer_search() {
    var taxName = $("#Tax-Name").val();
    var taxMobile = $("#Tax-Mobile").val();
    var taxNo = $("#TaxNo").val();
    $("#tax-customer-search-result").load("2_tax_customer_search.php?br=" + br + "&taxName=" + taxName + "&taxMobile=" + taxMobile + "&taxNo=" + taxNo, function (data) {
        $(this).trigger("create");
        if (data) {
            $(".tax-customer-search").slideUp(300);
        }
    });
}
function issue_tax(id) {
    var serviceNo = $("#SERVICE-DESC-TITLE").text();
    var name = $("#customer-name-" + id).text();
    var taxno = $("#customer-taxno-" + id).text();
    var address = $("#customer-address-" + id).text();

    $.post("issue_tax.php", {
        serviceNo: serviceNo,
        memberid: id,
        br: br,
        name: name,
        taxno: taxno,
        address: address
    })
            .done(function (data) {
                $("#spare").text(data);
                service_operation();
            });
}

var br = localStorage.br;
$(document).ready(function () {
    load_infras();
    load_at_payment();
});
function load_at_payment() {
    $("#at_payment").load("at_payment.php?br=" + br, function (data) {
        $(this).trigger("create");
    });
}
function load_infras() {
    $("#POPUP-AT-PAYMENT").css("height", window.innerHeight - 180);
}
function popup_at_payment(id) {
    $(".Notice").css('background-color', '#fff');
    $("#POPUP-PAYMENT-ID").val(id);
    $("#atpayment-Name").val($("#NAME-" + id).text());
    $("#atpayment-Total").val($("#TOTAL-" + id).text().slice(0, -1)).change();
    $("#atpayment-Sequence").val($("#SEQUENCE-" + id).text()).change();
    $("#atpayment-Remark").val($("#REMARK-" + id).text());

    $("#POPUP-AT-PAYMENT").popup("open", function () {

    });
    if (id > 0) {
        $("#POPUP-AT-PAYMENT-BTN").text("Modify");
    } else {
        $("#POPUP-AT-PAYMENT-BTN").text("Add New");
    }
}

function change_active(ths) {
    var id = ths.id;
    var current_status = $("#" + id).is(":checked");
    var next_status = 0;
    if (current_status === true) {
        next_status = 1;
    }
    $("#spare").load("operation.php?opr=change_active&br=" + br + "&id=" + id + "&next_status=" + next_status);
    amsalert('Save Changes', 'white', 'green');
}
function at_payment_submit() {
    var id = $("#POPUP-PAYMENT-ID").val();
    var name = $("#atpayment-Name").val().replace(/ /g, '%20');
    var total = $("#atpayment-Total").val();
    var sequence = $("#atpayment-Sequence").val();
    var remark = $("#atpayment-Remark").val().replace(/ /g, '%20');
    $(".Notice").css('background-color', '#fff');
    if (name === '') {
        $("#atpayment-Name-Notice").css('background-color', 'red');
    } else if (total === '') {
        $("#atpayment-Total-Notice").css('background-color', 'red');
    } else {
        var additional = "&id=" + id;
        additional += "&Name=" + name;
        additional += "&Total=" + total;
        additional += "&Sequence=" + sequence;
        additional += "&Remark=" + remark;
        $("#spare").load("operation.php?opr=atpayment&br=" + br + additional, function () {

            amsalert('Save Changes', 'white', 'green');
            $("#POPUP-AT-PAYMENT").popup("close");
            load_at_payment();
        });
    }
}

var br = localStorage.br;
var Permission_Set_Restaurant = localStorage.Set_Restaurant;
$(document).ready(function () {
    infras();
    load_section_raw_mat();

    $(".group-color").click(function () {
        var new_color = $(this).attr('id').replace("group-color-", "");
        $(".group-color").html("");
        $("#group-color-" + new_color).html("<img src='../../img/action/checked.png'>");
        $("#INNER-GROUP-COLOR").val(new_color);
    });
    $(".group-icon").click(function () {
        var new_icon = $(this).attr('id').replace("group-icon-", "");
        $(".group-icon").css("background-color", "#888");
        $("#group-icon-" + new_icon).css("background-color", "#000");
        $("#INNER-GROUP-ICON").val(new_icon);
    });
});
function infras() {
    $("#POPUP-EDIT,#DESC").css("height", window.innerHeight - 80);
    $("#INNER-EDIT,#INNER-DESC").css("height", window.innerHeight - 200).css("overflow-x", "hidden");

}
function load_section_raw_mat() {
    $("#SECTION-RAWMAT").load("section_raw_mat.php?br=" + br);
}


function add_product_form() {
    $("#EDIT-TITLE").text('Add New Raw-Material');
    $("#POPUP-EDIT").popup("open");
    $("#edit-save-button").attr("editID", '').attr("onclick", 'add_product()').text("Add Raw-Mat");
    $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br, function () {
        $(".prevent-add").hide();
        $(this).trigger("create");
    });
}
function edit_product_form(productid) {
    var productName = $("#DISPLAY-NAME-" + productid).text();
    $("#EDIT-TITLE").text(productName);
    $("#edit-save-button").attr("editID", productid).attr("onclick", 'edit_product()').text("Save Changes");

    $("#POPUP-EDIT").popup("open");
    $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br + "&productid=" + productid, function () {

        $(this).trigger("create");
    });
}

function desc(productid, opr) {
    if (productid === 'check') {
        productid = $("#LAST-DISPLAYID").val();
    }
    var productName = $("#DISPLAY-NAME-" + productid).text();
    $("#LAST-DISPLAYID").val(productid);
    $("#DESC-TITLE").text(productName);

    var stockin = $("#DISPLAY-DATA-" + productid).attr("stockin");
    var stockout = $("#DISPLAY-DATA-" + productid).attr("stockout");
    var stockpos = $("#DISPLAY-DATA-" + productid).attr("stockpos");

    $("#val-in").html(stockin);
    $("#val-out").html(stockout);
    $("#val-pos").html(stockpos);


    $("#DESC").popup("open");
    $("#INNER-DESC").html("<img src=../../img/ajaxloader.gif>").load("popup_desc.php?br=" + br + "&opr=" + opr + "&productid=" + productid, function () {
        $(this).trigger("create");
    });
}

function add_product() {
    var inputid;
    var input_type;
    var input_value;
    var menu_node_bg;
    var additional = '';
    $(".edit").each(function (index) {
        inputid = $(this).attr('id');
        input_type = $(this).attr('type');
        if (input_type === 'checkbox') {
            if ($(this).is(':checked') === true) {
                input_value = '1';
                menu_node_bg = '#ffffff';
            } else {
                input_value = '0';
                menu_node_bg = '#bbbbbb';
            }
        } else {
            input_value = $(this).val();
        }
        if (input_value) {
            additional += "___19___" + inputid + "___38___" + input_value;
        }

    });
    additional = additional.replace(/ /g, '%20');
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_add',
            br: br,
            val: additional
        },
        success: function (data) {
            amsalert("New Product Added", "white", "green");
            $("#spare").html(data);
        }
    });



    $("#POPUP-EDIT").popup("close");
    //Set Desc on main page
    $("#DISPLAY-NAME-" + productid).text($('#NameEN').val());
    $("#DISPLAY-PRICE-" + productid).text($('#PriceTHB').val() + '.-');
    $("#DISPLAY-MENU-CODE-" + productid).text('#' + $('#Menu_Code').val());

    $("#MENU-NODE-" + productid).css('background-color', menu_node_bg);
    if ($('#Barcode').val()) {
        $("#DISPLAY-BARCODE-" + productid).text('*' + $('#Barcode').val() + '*');
    }

}
function edit_product() {
    var productid = $("#edit-save-button").attr("editID");
    var inputid;
    var input_type;
    var input_value;
    var menu_node_bg;
    var additional = '';
    $(".edit").each(function (index) {
        inputid = $(this).attr('id');
        input_type = $(this).attr('type');
        if (input_type === 'checkbox') {
            if ($(this).is(':checked') === true) {
                input_value = '1';
                menu_node_bg = '#ffffff';
            } else {
                input_value = '0';
                menu_node_bg = '#bbbbbb';
            }
        } else {
            input_value = $(this).val();
        }
        if (input_value) {
            additional += "___19___" + inputid + "___38___" + input_value;
        }

    });
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_edit',
            br: br,
            productid: productid,
            val: additional
        },
        success: function (data) {
            amsalert("Saved", "white", "green");
            $("#spare").html(data);
        }
    });

    $("#POPUP-EDIT").popup("close");
    //Set Desc on main page
    $("#DISPLAY-NAME-" + productid).text($('#Name').val());
    $("#DISPLAY-MENU-CODE-" + productid).text('#' + $('#Raw_Material_Code').val());
    if ($('#Unit').val() && $('#Unit_Convert').val() && $('#Buying_Unit').val()) {
        $("#DISPLAY-PRICE-" + productid).text($('#Unit_Convert').val() + "" + $('#Unit').val() + " / " + $('#Buying_Unit').val());
    } else {
        $("#DISPLAY-PRICE-" + productid).text($('#Unit').val());
    }
    if ($('#Barcode').val() > 0) {
        $("#DISPLAY-BARCODE-" + productid).text('*' + $('#Barcode').val() + '*');
    }
    $("#MENU-NODE-" + productid).css('background-color', menu_node_bg);


}
function refresh_type_stock(typeNo) {
    var totalMenu = '';
    $(".typeNo-" + typeNo).each(function () {
        var menuID = $(this).attr("raw_materialID");
        totalMenu += "," + menuID;
        $("#Stock-" + menuID).html("<span style='font-size:10px'>loading...</span>");
    });
    totalMenu = totalMenu.substring(1, totalMenu.length);
    refresh_stock(totalMenu, 0);
}
function refresh_stock(raw_pID, method) {
    //##method->0 : all product in branch
    //##method->1 : by product
    var pID_split = raw_pID.split(",");
    for (var pLoop = 0; pLoop < pID_split.length; pLoop++) {
        var pID = pID_split[pLoop];
        $("#refresh_stock_btn").html("<img src='../../img/ajaxloader.gif'>");
        $.ajax({
            type: "POST",
            url: "refresh_stock.php",
            enctype: 'multipart/form-data',
            data: {
                br: br,
                pID: pID,
                method: method
            },
            success: function (data) {
                $("#spare").html(data);
                var data_split = data.split("___");
                var totalIn = data_split[0];
                var total_out = data_split[1];
                var total_pos = data_split[2];
                var total_stock = data_split[3];
                var finish_time = data_split[4];
                var productID = data_split[5];
                if (total_stock==='') {
                    total_stock = "x0.00";
                }
                $("#Stock-" + productID).html(total_stock).css("color", "blue");

                $("#refresh_stock_btn").html("Refresh Finished : " + finish_time);
                var unit_convert = parseFloat($("#DISPLAY-DATA-" + productID).attr("unit_convert"));
                total_stock = parseFloat(total_stock.replace(/,/g, ""));
                var buying_unit_remain = Math.floor(total_stock / unit_convert);
                var selling_unit_remain = Math.floor(total_stock % unit_convert);
                $("#Buying_Unit-" + productID).html(buying_unit_remain).css("color", "blue");
                $("#Selling_Unit-" + productID).html(selling_unit_remain).css("color", "blue");

                $("#DISPLAY-DATA-" + productID).attr("stockin", totalIn);
                $("#DISPLAY-DATA-" + productID).attr("stockout", total_out);
                $("#DISPLAY-DATA-" + productID).attr("stockpos", total_pos);
                $("#val-in").html(totalIn);
                $("#val-out").html(total_out);
                $("#val-pos").html(total_pos);
            }
        });
    }
    amsalert("Refresh Completed!", "white", "green");

}
  
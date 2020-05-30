var br = localStorage.br;
var usrID = localStorage.usrID;
var permission = localStorage.permission;
var Set_Restaurant = localStorage.Set_Restaurant;
var permission_kitchen = permission.substring(1, 2);
var permission_menu = permission.substring(4, 5);
if (Set_Restaurant) {
    permission_kitchen = 1;
}
$(document).ready(function () {
    infras();
    load_section_product();

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
    trying_weird();
});
function infras() {
    $("#POPUP-EDIT").css("height", window.innerHeight - 80);
    $("#INNER-EDIT").css("height", window.innerHeight - 200).css("overflow-x", "hidden");

    $("#POPUP-TOPUP").css("height", window.innerHeight - 80);
    $("#INNER-TOPUP").css("height", window.innerHeight - 200).css("overflow-x", "hidden");

    $("#POPUP-STOCK").css("height", window.innerHeight - 80);
    $("#INNER-STOCK").css("height", window.innerHeight - 200).css("overflow-x", "hidden");

}
function load_section_product() {
    $("#SECTION-PRODUCT").load("section_product.php?br=" + br);
}

function drag(id) {
    $("#spare_event").text(id);
}
function drop(drop_to) {
    $("#product_type_save_button").slideDown(300);
    var drop_from = $("#spare_event").text();
    var old_data = $("#GROUP-NODE-" + drop_from).html();
    var old_bg = $("#GROUP-NODE-" + drop_from).attr('style');
    $("#GROUP-NODE-" + drop_from).animate({width: 0, height: 80}, 300, function () {
        $("#GROUP-NODE-" + drop_from).remove();
        $("#GROUP-NODE-" + drop_to).show(500).before("<div ondrop='drop(" + drop_from + ")'  ondragover='allowDrop(event)' class='GROUP-NODE' id='GROUP-NODE-" + drop_from + "' style='" + old_bg + "'>" + old_data + "</div>");
    });


    $("#spare").load(drop_from + "->" + drop_to);
}
function allowDrop(ev) {
    ev.preventDefault();
}
function save_type_sequence() {
    $("#product_type_save_button").hide();
    var sequence = 1;
    var total_changes = '';
    $(".GROUP-NODE").each(function () {
        var gID = $(this).attr("id").replace('GROUP-NODE-', '');
        $("#GROUP-NODE-NUMBER-" + gID).text(sequence);
        total_changes += sequence + "_" + gID + ",";
        sequence++;
    });
    $("#spare").load("operation.php?opr=save_sequence&total_changes=" + total_changes + "&br=" + br, function () {
        amsalert("Saved", "white", "green");
    });
}
function save_inner_type() {
    var groupID = $("#INNER-GROUPID").val();
    var title = $("#INNER-GROUP-TITLE").val();
    var title_old = $("#INNER-GROUP-TITLE-OLD").val();
    var color = $("#INNER-GROUP-COLOR").val();
    var icon = $("#INNER-GROUP-ICON").val();
    var style = $("#INNER-GROUP-STYLE").val();
    var addition = "&groupID=" + groupID;
    addition += "&title=" + title;
    addition += "&title_old=" + title_old;
    addition += "&color=" + color;
    addition += "&icon=" + icon;
    addition += "&style=" + style;
    addition += "&br=" + br;
    addition = addition.replace(/ /g, "%20");
    $("#spare").load("operation.php?opr=change_inner_type&addition=" + addition, function (data) {
        if (data === 'Existed') {
            amsalert("Product Type Name Existed", "white", "red");

        } else {
            $("#GROUP-NODE-TEXT-" + groupID).text(title);
            $("#GROUP-PROPERTY-" + groupID).attr("g_title", title);
            $("#GROUP-NODE-" + groupID).css("background-color", "#" + color);
            $("#GROUP-PROPERTY-" + groupID).attr("g_color", color);

            $("#GROUP-NODE-ICON-" + groupID).html("<img src='../../img/menu_icon/" + icon + ".png'>");
            $("#GROUP-PROPERTY-" + groupID).attr("g_icon", icon);
            $("#GROUP-PROPERTY-" + groupID).attr("g_style", style);
            $("#POPUP-EDIT-GROUP").popup("close");
            amsalert("Saved", "white", "green");

        }
    });
}
function change_group_style(to) {
    $(".INNER-GROUP-STYLE").css("background-color", '#fff');
    $("#INNER-GROUP-STYLE-" + to).css("background-color", '#000');
    $("#INNER-GROUP-STYLE").val(to);
}

function edit_group_form(groupID) {
    var G_Title = $("#GROUP-PROPERTY-" + groupID).attr("g_title");
    var G_Color = $("#GROUP-PROPERTY-" + groupID).attr("g_color");
    var G_Icon = $("#GROUP-PROPERTY-" + groupID).attr("g_icon");
    var G_style = $("#GROUP-PROPERTY-" + groupID).attr("g_style");
    $(".group-color").html("");
    $("#group-color-" + G_Color).html("<img src='../../img/action/checked.png'>");
    $(".group-icon").css("background-color", "#888");
    $("#group-icon-" + G_Icon).css("background-color", "#000");
    $("#INNER-GROUPID").val(groupID);
    $("#INNER-GROUP-TITLE-OLD").val(G_Title);
    $("#INNER-GROUP-TITLE").val(G_Title);
    $("#INNER-GROUP-COLOR").val(G_Color);
    $("#INNER-GROUP-ICON").val(G_Icon);
    change_group_style(G_style);
    $("#INNER-GROUP-STYLE").val(G_style);

    $("#POPUP-EDIT-GROUP").popup("open");
}
function add_product_form() {
    $("#EDIT-TITLE").text('Add New Product');
    $("#POPUP-EDIT").popup("open");
    $("#edit-save-button").attr("editID", '').attr("onclick", 'add_product()').text("Add Product");
    $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br + "&Set_Restaurant=" + Set_Restaurant + "&set_kitchen=" + permission_kitchen, function () {
        $(".prevent-add").hide();
        selection_type();
        $(this).trigger("create");
    }
    );
}

function selection_type() {
    var currentID = $("#edit-save-button").attr("editID");
    var currentType = $("#DISPLAY-DATA-" + currentID).attr("menuType");
    var totalType = $("#totalType").val();
    var totalType_split = totalType.split("_____");
    var selectType = "<select id='Type' class='edit' onChange='check_new_select(this)'><option value='no-group'></option>";
    for (var spl = 0; spl < totalType_split.length; spl++) {
        if (totalType_split[spl]) {
            selectType += "<option>" + totalType_split[spl] + "</option>";
        }
    }
    selectType += "<option value='new---19'>+New Group</option></select>";
    $("#selectType_Area").html(selectType);
    $("#Type").val(currentType);
}
function edit_product_form(productid) {
    var productName = $("#DISPLAY-NAME-" + productid).text();
    $("#EDIT-TITLE").text(productName);
    $("#edit-save-button").attr("editID", productid).attr("onclick", 'edit_product()').text("Save Changes");
    $("#POPUP-EDIT").popup("open");
    $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br + "&Set_Restaurant=" + Set_Restaurant + "&productid=" + productid + "&set_kitchen=" + permission_kitchen, function () {

        selection_type();
        $(this).trigger("create");
    });
}
function topup_product_form(productid) {
    var productName = $("#DISPLAY-NAME-" + productid).text();
    $("#topup-save-button").attr("topupID", productid);
    $("#TOPUP-TITLE").text(productName);

    $("#POPUP-TOPUP").popup("open");
    $("#INNER-TOPUP").html("<img src=../../img/ajaxloader.gif>").load("popup_topup.php?br=" + br + "&productid=" + productid);
}
function stock_product_form(productid) {
    var productName = $("#DISPLAY-NAME-" + productid).text();
    $("#stock-save-button").attr("stockID", productid);
    $("#STOCK-TITLE").text(productName);

    $("#POPUP-STOCK").popup("open");
    $("#INNER-STOCK").html("<img src=../../img/ajaxloader.gif>").load("popup_stock.php?br=" + br + "&productid=" + productid);
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
            additional += "___|19|___" + inputid + "___|38|___" + input_value;
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


//$("#DISPLAY-PIC-" + productid).html("<img src='../../../pos/picture/" + productid + ".jpg'>");

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
    var Product_Picture = $("#Product_Picture").val();
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
            additional += "___|19|___" + inputid + "___|38|___" + input_value;
        }

    });
    additional = additional.replace(/ /g, '%20');
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_edit',
            br: br,
            productid: productid,
            Product_Picture: Product_Picture,
            val: additional
        },
        success: function (data) {
            amsalert("Saved", "white", "green");
            $("#spare").html(data);
        }
    });

//###reload image
    $.ajax({
        url: '../../picture/' + br + '/' + productid + '.jpg',
        type: 'HEAD',
        error: function ()
        {
            $("#DISPLAY-PIC-" + productid).html("<img src='../../picture/"+br+"/" + productid + ".png'>");
        },
        success: function ()
        {
            $("#DISPLAY-PIC-" + productid).html("<img src='../../picture/"+br+"/" + productid + ".jpg'>");
        }
    });


    if ($("#POS").is(':checked') === true) {
        menu_node_bg = '#ffffff';
    } else {
        menu_node_bg = '#bbbbbb';
    }

    $("#MENU-NODE-" + productid).css('background-color', menu_node_bg);
    $("#POPUP-EDIT").popup("close");
//Set Desc on main page
    $("#DISPLAY-NAME-" + productid).text($('#NameEN').val());
    $("#DISPLAY-PRICE-" + productid).text($('#PriceTHB').val() + '.-');
    $("#DISPLAY-MENU-CODE-" + productid).text('#' + $('#Menu_Code').val());
    if ($('#Barcode').val() > 0) {
        $("#DISPLAY-BARCODE-" + productid).text('*' + $('#Barcode').val() + '*');
    }


}
function topup_product() {

    var productid = $("#topup-save-button").attr("topupID");
    var additional = $("#current_topup").val();
    additional = additional.replace(/--/g, '-');
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_topup',
            br: br,
            productid: productid,
            val: additional
        },
        success: function (data) {

            $("#POPUP-TOPUP").popup("close");
            amsalert("Topup Saved", "white", "green");
            $("#spare").html(data);
        }
    });
}

function stock_product() {

    var productid = $("#stock-save-button").attr("stockID");
    var currentStock = $("#current_stock").val();
    var currentQty = '';
    $(".stockQty").each(function () {
        currentQty += '-' + $(this).val();
    });
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_stock',
            br: br,
            productid: productid,
            currentStock: currentStock,
            currentQty: currentQty
        },
        success: function (data) {
            $("#POPUP-STOCK").popup("close");
            amsalert("Raw Material Saved", "white", "green");
            $("#spare").html(data);
        }
    });
}
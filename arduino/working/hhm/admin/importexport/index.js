var br = localStorage.br;
var usrID = localStorage.usrID;
var usr = localStorage.usr;
$(document).ready(function () {

    $("#list_form").scroll(function () {
        var area_height = parseInt($("#list_form").css("height"));
        var line_height = 30;
        var page_line = 50;

        var scroll = $("#list_form").scrollTop();
        var page_now = parseInt($("#page_now").text());
        $("#scroll_position").text(scroll);
        if (scroll > ((line_height * page_line) * page_now) - area_height) {
            $("#page_now").text(page_now + 1);
            load_list_section('', page_line * (page_now));
        }
    });
    $('#CMD-BOX').keyup(function (e) {
        if (e.keyCode === 13) {
            submit_command();
        }
    });
    load_list_section('', 0);
    load_infras();
});
function load_list_section(display, start) {
    $("#spare").load("list_form.php?br=" + br + "&display=" + display + "&start=" + start, function (data) {
        $("#list_form").append(data);
    });

}
function load_infras() {
    $("#list_form").css("height", window.innerHeight - 100);
    $("#table-title").css("bottom", window.innerHeight - 100);
    $("#POPUP-DESC").css("height", window.innerHeight - 220);
    $("#POPUP-DESC2").css("height", window.innerHeight - 220);
    $("#POPUP-ADDITION").css("height", window.innerHeight - 35);
}
function load_desc(id, method) {
    var serviceNo = $(".row-" + id).attr("tserviceno");
    var date_time = $(".row-" + id).attr("tdate");
    var cancel = $(".row-" + id).attr("tcancel");
    $("#DESC-VOIDED").hide();
    var form_method = 0;

    if (serviceNo && serviceNo !== 'Edit') {
        //View only
        $("#pfrom_location_edit").hide();
        $("#pto_location_edit").hide();
        $("#POPUP-FOOTER,#pdocumentno1_edit,#pdocumentno2_edit").hide();
        $("#POPUP-FOOTER2").show();
        $("#pdf-serviceID").val(id);
        $("#pdf-br").val(br);
        $("#pdocumentno1_view,#pdocumentno2_view").show();

    } else {
        serviceNo = 'auto';
        form_method = 1;//able to edit
        $("#pfrom_location_edit").show();
        $("#pto_location_edit").show();
        $("#POPUP-FOOTER,#pdocumentno1_edit,#pdocumentno2_edit").show();
        $("#POPUP-FOOTER2").hide();
        $("#pdocumentno1_view,#pdocumentno2_view").hide();
    }


    $("#pserviceno").text('auto').text(serviceNo);
    $("#pdate").text('').text(date_time);
    $("#pfrom_location").text('').text($(".row-" + id).attr("tfrom_location"));
    $("#pto_location").text('').text($(".row-" + id).attr("tto_location"));
    $("#pdocumentno1").text('').val($(".row-" + id).attr("tdocumentno1"));
    $("#pdocumentno2").text('').val($(".row-" + id).attr("tdocumentno2"));
    $("#pdocumentno1_view").text('').text($(".row-" + id).attr("tdocumentno1"));
    $("#pdocumentno2_view").text('').text($(".row-" + id).attr("tdocumentno2"));
    if (method === 1) {
        $("#POPUP-TITLE").text("Import Document");
        $("#pto_location").text(br);
        $("#pto_location_edit").hide();
    } else if (method === 2) {
        $("#POPUP-TITLE").text("Export Document");
        $("#pfrom_location").text(br);
        $("#pfrom_location_edit").hide();
    } else if (method === 4) {
        $("#POPUP-TITLE").text("Purchase Order (PO)");
        $("#pfrom_location").text(br);
        $("#pfrom_location_edit").hide();
    } else if (method === 5) {
        $("#POPUP-TITLE").text("Delivery Order");
        $("#pto_location").text(br);
        $("#pto_location_edit").hide();
    }
    $("#paper_method").val(method);

    $("#POPUP-ADDITION").hide();
    $("#POPUP-EDIT").popup('open');
    $("#CMD-BOX").val('').focus().css("background-color", "white");

    $("#paperid").val(id);
    $("#POPUP-DESC").load("desc_form.php?id=" + id + "&br=" + br + "&form_method=" + form_method, function () {

        $("#DESC-DESC").css("height", window.innerHeight - 220);
        if (cancel === '1') {
            $("#VOID-BTN").hide();
            $("#DESC-VOIDED").show();
        } else if (usr === 'admin') {
            $("#VOID-BTN").show();
            $("#DESC-VOIDED").hide();
        } else {
            $("#VOID-BTN").hide();
            $("#DESC-VOIDED").hide();
        }
    });
}
function submit_command() {
    var cmd = $("#CMD-BOX").val();
    var cmd_length = cmd.length;
    if (cmd_length > 0) {
        var paperid = $("#paperid").val();
        if (cmd_length === 18 && cmd.substr(0, 2) >= 15 && cmd.substr(0, 2) <= 25 && paperid === '0') {
            import_refer(cmd);
        } else {
            add_product(cmd, 1);
        }
    }
}
function add_product(cmd, type) {
    var cmd_qty = cmd.split("*");
    if (type === 1) {
        //#####From Command bar
        if (cmd_qty[1]) {
            cmd = cmd_qty[1];
            qty = parseFloat(cmd_qty[0]);
        } else {
            qty = 1;
        }
    } else if (type === 2) {
        //#####From Searching Product
        qty = 1;
    }
    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'add_product',
            br: br,
            type: type,
            search: cmd
        },
        success: function (data) {
            var data_col = data.replace("|||||", "");
            data_col = data.split("__|__");
            var menuID = data_col[0];
            var menuID_exists = $("#c1-" + menuID).length;
            if (menuID && menuID_exists === 0) {
                $("#CMD-BOX").val('').css({backgroundColor: 'white', color: 'black'});
                var next_line = $('.in-node').length;
                $(".child1").append("<div class='in-node collect-data' id='c1-" + menuID + "' pid='" + menuID + "' pqty='" + qty + "' pprice='" + data_col[4] + "' punit='" + data_col[5] + "' punitconv='" + data_col[6] + "'>" + ((next_line / 7) + 1) + "</div>");
                $(".child2").append("<div class='in-node' id='c2-" + menuID + "'>" + data_col[1] + "</div>"); //Code
                $(".child3").append("<div class='in-node' id='c3-" + menuID + "'>" + data_col[2] + "</div>"); //Name
                $(".child4").append("<div class='in-node' id='c4-" + menuID + "'>" + data_col[3] + "</div>"); //Unit
                $(".child5").append("<div class='in-node' id='c5-cover-" + menuID + "'><input type='number' id='c5-" + menuID + "' value='" + qty + "' style='width:100%' onchange='qty_change(this)'></div>"); //Quantity
                $(".child6").append("<div class='in-node' id='c6-" + menuID + "'>" + data_col[4] + "</div>"); //Unit Price
                $(".child7").append("<div class='in-node' id='c7-" + menuID + "'>" + qty * data_col[4] + "</div>"); //Total Price
                $("#CMD-BOX").val('').css({backgroundColor: 'white', color: 'black'});
            } else if (menuID_exists > 0) {
                var currentQty = parseFloat($("#c5-" + menuID).val());
                var currentPrice = parseFloat($("#c6-" + menuID).text());
                $("#c1-" + menuID).attr("pqty", currentQty + qty);
                $("#c5-" + menuID).val(currentQty + qty);
                $("#c7-" + menuID).text((currentQty + qty) * currentPrice);
                $("#CMD-BOX").val('').css({backgroundColor: 'white', color: 'black'});
            } else {
                $("#CMD-BOX").css({backgroundColor: 'red', color: 'white'});
            }
        }
    });
}
function import_refer(cmd) {
    var method = parseInt($("#paper_method").val());
    $("#spare1").load("operation.php?opr=import_refer&br=" + br + "&method=" + method + "&serviceNo=" + cmd, function (data) {
        if (data === 'notfound') {
            alert('Document #' + cmd + '\n not found!');
        } else {
            var data_split = data.split("_____");
            var id = data_split[0];
            var from = data_split[1];
            var to = data_split[2];
            var remark = data_split[3];
            var refer = data_split[4];


            $("#list_form").html('');
            load_list_section('', 0);
            load_desc(id, method);

            $("#pfrom_location").text(from);
            $("#pto_location").text(to);
            $("#pdocumentno1").val(remark);
            $("#pdocumentno2").val(refer);
        }
    });


}
function qty_change(ths) {
    var menuID = ths.id;
    menuID = menuID.replace('c5-', '');
    var currentQty = parseFloat(ths.value);
    if (currentQty >= 1) {
        var unitPrice = parseFloat($("#c1-" + menuID).attr("pprice"));
        $("#c7-" + menuID).text((currentQty * unitPrice).toFixed(2));
        $("#c1-" + menuID).attr("pqty", currentQty);
    } else {
        $("#c1-" + menuID).remove();
        $("#c2-" + menuID).remove();
        $("#c3-" + menuID).remove();
        $("#c4-" + menuID).remove();
        $("#c5-" + menuID).hide();
        $("#c5-cover-" + menuID).remove();
        $("#c6-" + menuID).remove();
        $("#c7-" + menuID).remove();
    }
}
function save_paper() {
    var total_line = '';
    var totalid = '';
    var lineid, lineqty, lineprice, lineunit, lineunitconv;
    $(".collect-data").each(function () {
        lineid = $(this).attr('pid');
        lineqty = $(this).attr('pqty');
        lineprice = $(this).attr('pprice');
        lineunit = $(this).attr('punit');
        lineunitconv = $(this).attr('punitconv');

        total_line += lineid + "__|__'" + lineqty * lineunitconv + "'__|__'" + lineprice + "'__|__'" + lineunit + "'__|__'" + lineqty + "'|||||";
        totalid += lineid + ",";
    });

    var paperid = $("#paperid").val();
    var method = $("#paper_method").val();
    var from_location = $("#pfrom_location").text();
    var to_location = $("#pto_location").text();
    var doc1 = $("#pdocumentno1").val();
    var doc2 = $("#pdocumentno2").val();




    $.ajax({
        type: "POST",
        url: "operation.php",
        enctype: 'multipart/form-data',
        data: {
            opr: 'save_paper',
            paperid: paperid,
            br: br,
            method: method,
            from_location: from_location,
            to_location: to_location,
            doc1: doc1,
            doc2: doc2,
            total_line: total_line,
            totalid: totalid,
            usrID: usrID
        },
        success: function (data) {
            if (!paperid || paperid === '0') {
                $("#paperid").val(data);
                $("#list_form").html('');
                load_list_section('', 0);
            } else {
                $(".row-" + paperid).attr("tfrom_location", from_location);
                $(".row-" + paperid).attr("tto_location", to_location);
                $(".row-" + paperid).attr("tdocumentno1", doc1);
                $(".row-" + paperid).attr("tdocumentno2", doc2);

                $(".lfrom_location-" + paperid).text(from_location);
                $(".lto_location-" + paperid).text(to_location);
                $(".ldocumentno1-" + paperid).text(doc1);

                $(".ldocumentno2-" + paperid).text(doc2);
            }
            $("#spare1").text(data);
        }
    });
}
function finish_paper() {
    save_paper();
    var cfm = confirm('Confirm finish this Paper?');
    if (cfm !== false) {
        var paperid = $("#paperid").val();
        var method = $("#paper_method").val();
        $.ajax({
            type: "POST",
            url: "operation.php",
            enctype: 'multipart/form-data',
            data: {
                opr: 'finish_paper',
                paperid: paperid,
                br: br,
                method: method,
                usrID: usrID
            },
            success: function (data) {
                $("#POPUP-EDIT").popup("close");

                $("#spare1").text(data);
                $("#list_form").html('');
                load_list_section('', 0);
            }
        });
    }
}
function delete_paper() {
    var cfm = confirm("Confirm Delete this Paper?");
    if (cfm !== false) {
        $("#POPUP-EDIT").popup("close");
        var paperid = $("#paperid").val();
        $("#spare1").load("operation.php?opr=delete_paper&br=" + br + "&paperid=" + paperid, function () {

            $("#list_form").html('');
            load_list_section('', 0);
        });
    }
}
function addition_from_address() {
    $("#POPUP-ADDITION").show(100);
    $("#ADDITION-HEADER").html("<h2 style='margin:0'>From-Location</h2>");
    $("#ADDITION-DESC").html("<img src='../../img/ajaxloader.gif'>").load("operation.php?opr=addition_from_address&br=" + br, function () {
        $(this).trigger("create");
    });
}
function choose_from_location(from_location) {
    if (from_location === 'new---19') {
        from_location = $("#from_location_input").val();
    }

    $("#pfrom_location").text(from_location);
    $("#POPUP-ADDITION").hide(100);

}
function addition_to_address() {
    $("#POPUP-ADDITION").show(100);
    $("#ADDITION-HEADER").html("<h2 style='margin:0'>To-Location</h2>");
    $("#ADDITION-DESC").html("<img src='../../img/ajaxloader.gif'>").load("operation.php?opr=addition_to_address&br=" + br, function () {
        $(this).trigger("create");
    });
}
function choose_to_location(to_location) {
    if (to_location === 'new---19') {
        to_location = $("#to_location_input").val();
    }
    $("#pto_location").text(to_location);
    $("#POPUP-ADDITION").hide(100);

}
function find_product() {
    $("#POPUP-ADDITION").show(100);
    $("#ADDITION-HEADER").load("operation.php?opr=finding_header&br=" + br, function () {
        $(this).trigger("create");
    });
    $("#ADDITION-DESC").text('');

}
function find_product_list(ths) {
    var product_type0 = ths.value;
    var product_type = product_type0.replace(/ /g, '%20');
    $("#ADDITION-DESC").load("operation.php?opr=find_product_list&br=" + br + "&product_type=" + product_type, function () {
    });

}
function search_product_list() {
    var product_search = $("#SEARCH-PRODUCT").val();
    if (product_search.length > 0) {
        $("#SEARCH-PRODUCT-GROUP").val('').change();
        $("#ADDITION-DESC").load("operation.php?opr=search_product_list&br=" + br + "&product_search=" + product_search, function () {
        });
    }

}
function void_docs() {

    var cfm = confirm("Confirm Void this Paper?");
    if (cfm !== false) {
        var paperid = $("#paperid").val();
        var method = $("#paper_method").val();
        $.ajax({
            type: "POST",
            url: "operation.php",
            enctype: 'multipart/form-data',
            data: {
                opr: 'void_paper',
                paperid: paperid,
                br: br,
                method: method,
                usrID: usrID
            },
            success: function (data) {
                $("#POPUP-EDIT").popup("close");
                $("#spare1").text(data);
                $("#list_form").html('');
                load_list_section('', 0);
            }
        });
    }
}
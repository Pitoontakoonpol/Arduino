var br = localStorage.BMbr;
var usrID = localStorage.usrID;
var Set_Restaurant = localStorage.Set_Restaurant;

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
});
function infras() {
  $("#POPUP-EDIT").css("height", window.innerHeight - 80);
  $("#POPUP-EXTRA-EDIT").css("height", window.innerHeight - 180);
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
  var br = localStorage.BMbr;
  $("#product_type_save_button").hide();
  var sequence = 1;
  var total_changes = '';
  $(".GROUP-NODE").each(function () {
    var gID = $(this).attr("id").replace('GROUP-NODE-', '');
    var gName = $(this).attr("type_name");
    $("#GROUP-NODE-NUMBER-" + gID).text(sequence);
    total_changes += sequence + "___" + gName + ",";
    sequence++;
  });

  $.ajax({
    type: "POST",
    url: "operation.php",
    enctype: 'multipart/form-data',
    data: {
      opr: 'save_sequence',
      br: br,
      total_changes: total_changes
    },
    success: function (data) {
      amsalert("Saved", "white", "green");
      $("#spare").html(data);
    }
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
  br = localStorage.BMbr;
  $("#EDIT-TITLE").text('Add New Product');
  $("#POPUP-EDIT").popup("open");
  $("#edit-save-button").attr("editID", '').attr("onclick", 'add_product()').text("Add Product");
  $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br, function () {
    $(".prevent-add").hide();
    $(this).trigger("create");
  });
}
function edit_product_form(productid) {
  br = localStorage.BMbr;
  var productCode = $("#DISPLAY-DATA-" + productid).attr("menuCode");
  var productName = $("#DISPLAY-DATA-" + productid).attr("menuName");
  var productPrice = $("#DISPLAY-PRICE-" + productid).text();
  var productPoint = $("#POINT-DATA-" + productid).attr('data');
  var productActive = $("#ACTIVE-DATA-" + productid).attr('data');
  $("#EDIT-TITLE").text(productName);
  $("#edit-save-button").attr("editID", productid).attr("onclick", 'edit_product()').text("Save Changes");
  $("#POPUP-EDIT").popup("open");
  productCode = productCode.replace(/ /g, '___');
  productName = productName.replace(/ /g, '___');
  $("#INNER-EDIT").html("<img src=../../img/ajaxloader.gif>").load("popup_edit.php?br=" + br + "&productCode=" + productCode + "&productName=" + productName, function () {
    $(this).trigger("create");
    $("#price-range").text(productPrice);
    $("#point-range").text(productPoint);
    $("#active-range").text(productActive);
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
      additional += "___19___" + inputid + "___38___" + input_value;
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
    url: '../../../pos/picture/' + productid + '.jpg',
    type: 'HEAD',
    error: function ()
    {
      $("#DISPLAY-PIC-" + productid).html("<img src='../../../pos/picture/" + productid + ".png'>");
    },
    success: function ()
    {
      $("#DISPLAY-PIC-" + productid).html("<img src='../../../pos/picture/" + productid + ".jpg'>");
    }
  });


  $("#POPUP-EDIT").popup("close");
  //Set Desc on main page
  $("#DISPLAY-NAME-" + productid).text($('#NameEN').val());
  // $("#DISPLAY-PRICE-" + productid).text($('#PriceTHB').val() + '.-');
  $("#DISPLAY-MENU-CODE-" + productid).text('#' + $('#Menu_Code').val());
  $("#DISPLAY-DATA-" + productid).attr("menuName", $('#NameEN').val());
  $("#DISPLAY-DATA-" + productid).attr("menuCode", $('#Menu_Code').val());
  if ($('#Barcode').val() > 0) {
    $("#DISPLAY-BARCODE-" + productid).text('*' + $('#Barcode').val() + '*');
  }
  $("#MENU-NODE-" + productid).css('background-color', menu_node_bg);
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

function branch_mod(topic) {
  $(".branch_edit").hide();
  $("#branch_mod_" + topic).show();
  $("#edit-save-button").attr("onclick", "edit_" + topic + "()").text("Save " + topic).css("text-transform", "capitalize");
}
function edit_price() {
  var TotalID = $("#TotalID").val();
  var inputid;
  var input_value;
  var additional = '';
  $(".edit-price").each(function (index) {
    inputid = $(this).attr('id');
    input_value = $(this).val();
    if (input_value) {
      additional += "___19___" + inputid + "___38___" + input_value;
    }
  });
  $.ajax({
    type: "POST",
    url: "operation.php",
    enctype: 'multipart/form-data',
    data: {
      opr: 'edit_price',
      val: additional,
      productid: TotalID
    },
    success: function (data) {
      amsalert("Price Saved", "white", "green");
      $("#spare").html(data);
    }
  });

}
function edit_point() {
  var TotalID = $("#TotalID").val();
  var inputid;
  var input_value;
  var additional = '';
  $(".edit-point").each(function (index) {
    inputid = $(this).attr('id');
    input_value = $(this).val();
    if (input_value) {
      additional += "___19___" + inputid + "___38___" + input_value;
    }
  });
  
  $.ajax({
    type: "POST",
    url: "operation.php",
    enctype: 'multipart/form-data',
    data: {
      opr: 'edit_point',
      val: additional,
      productid: TotalID
    },
    success: function (data) {
      amsalert("Point Saved", "white", "green");
      $("#spare").html(data);
    }
  });


}
function edit_active() {
  var TotalID = $("#TotalID").val();
  var inputid;
  var input_value;
  var additional = '';
  $(".edit-active").each(function (index) {
    inputid = $(this).attr('id');
    if ($(this).is(':checked') === true) {
      input_value = '1';
    } else {
      input_value = '0';
    }
    if (input_value) {
      additional += "___19___" + inputid + "___38___" + input_value;
    }
  });

  $(".edit-scan").each(function (index) {
    inputid = $(this).attr('id');
    if ($(this).is(':checked') === true) {
      input_value = '1';
    } else {
      input_value = '0';
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
      opr: 'edit_active',
      val: additional,
      productid: TotalID
    },
    success: function (data) {
      amsalert("Activation Saved", "white", "green");
      $("#spare").html(data);
    }
  });
}
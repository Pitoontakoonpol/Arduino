var br = localStorage.br;
var screen_height = window.innerHeight;
var screen_width = window.innerWidth;
$(document).ready(function () {

  $("#main-center").css("height", screen_height - 80);
  $("#main-center-edit").css("height", screen_height - 120);

  $("#main-center").scroll(function () {
    var td_height = parseInt($("#td_height").val());
    var page_size = parseInt($("#page_size").val());
    var page_now = parseInt($("#page_now").val());
    var scroll = $("#main-center").scrollTop();
    var reload_on = (td_height * page_now * page_size) + td_height;
    $("#scroll_position").val(scroll);
    if (scroll >= reload_on) {
      load_main_center(page_now + 1);
      $("#page_now").val(page_now + 1);
    }
  });
});
function change_center_mode(mode) {
  $(".form_mode").hide();
  $("." + mode).show();
  $(".select_new").hide();

  $("#form").val(mode);
  initMap();
}
function load_add() {
  $("#main-center-edit").html("<img src='../../img/ajaxloader.gif'>").load("main_center_detail.php?form=add", function () {
    initMap();
  });
  $('#main-center-edit').popup('open');
}
function load_inside(form, id) {

  $(".front-row").css("border", "solid 5px #fff");
  $("#front-row" + id).css("border-left", "solid 5px #ff8800");
  $("#main-center-edit").html("<img src='../../img/ajaxloader.gif'>").load('main_center_detail.php?form=' + form + '&id=' + id + "&br=" + br, function () {
    initMap();
  });
  $('#main-center-edit').popup('open');
}
function load_main_center(page) {
  var inputid;
  var input_value;
  var additional = '';
  var page_size = $("#page_size").val();
  var order_by = $("#order_by").val();
  $(".search").each(function (index) {
    inputid = $(this).attr('id');
    input_value = $(this).val();
    if (input_value) {
      additional += "_19_" + inputid + "_38_" + input_value;
    }
  });
  if (additional) {
    additional = additional.replace(/ /g, '%20');
    additional = "&search=1&val=" + additional
  }
  $("#page" + page).show().html("<img src='../../img/ajaxloader.gif'>").load("main_center.php?br=" + br + "&page=" + page + "&order_by=" + order_by + "&page_size=" + page_size + additional, function () {

  });
  $("#edit-back-button").hide();
}
function selection_change(ths) {
  var select_value = ths.value;
  var selectID = ths.id;
  if (select_value === 'new___selection') {
    $("#" + selectID + "_New").show().focus();
  } else {
    $("#" + selectID + "_New").hide();
  }
}
function check_add() {
  var Name = $("#Name").val();
  var Mobile = $("#Mobile").val();
  if (Name && Mobile) {
    do_add();
  } else {
      amsalert("Please input Name and Mobile Number!", 'white', 'red');
  }
}
function do_add() {
  var inputid;
  var input_value;
  var additional = '';
  $("input.add,select.add,textarea.add").each(function (index) {
    inputid = $(this).attr('id');
    input_value = $(this).val();
    if (input_value) {
      additional += "_19_" + inputid + "_38_" + input_value;
    }
  });
  additional = additional.replace(/ /g, '%20');
  $("#spare").load("operation.php?opr=do_add&br=" + br + "&val=" + additional, function (return_val) {
    if (return_val.indexOf("Error") > 0) {
    amsalert("Some Error followed,\n" + return_val, 'white', 'red');
    } else {
      $("#main-center-edit").popup("close");
      amsalert("New Member added Completed!", 'white', 'green');
    }
  });
}


function check_edit() {
  var Name = $("#Name").val();
  var Mobile = $("#Mobile").val();
  if (Name && Mobile) {
    do_edit();
  } else {
      amsalert("Please input Name and Mobile Number!", 'white', 'red');
  }
}
function do_edit() {
  var inputid;
  var input_value;
  var additional = '';
  $(".edit").each(function (index) {
    inputid = $(this).attr('id');
    input_value = $(this).val();
    if (input_value) {
      additional += "_19_" + inputid + "_38_" + input_value;
    }
  });
  additional = additional.replace(/ /g, '%20');
  $("#spare").load("operation.php?opr=do_edit&br=" + br + "&val=" + additional, function (return_val) {
    if (return_val.indexOf("Error") > 0) {
      amsalert("Some Error followed,\n" + return_val, 'white', 'red');
    } else {
      $("#main-center-edit").popup("close");
      amsalert("Modified Completed!", 'white', 'green');
    }
  });
}

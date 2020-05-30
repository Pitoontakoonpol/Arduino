var screenW = window.innerWidth;
var screenH = window.innerHeight;
var username = localStorage.username;
var usernameID = localStorage.userID;

$(document).ready(function() {
  $("#ADD-FORM").css("height", screenH);
  $("#POPUP-DATA-ADD").css("height", screenH);
  $(".PDATA-MAIN").css("height", screenH - 100);
  list_result();

  $(window).resize(function() {
    infras();
  });

  $("#target").submit(function(event) {
    alert("Handler for .submit() called.");
    //  event.preventDefault();
  });

  $("#other").click(function() {
    $("#target").submit();
  });
});

function infras() {
  screenW = window.innerWidth;
  $("#spare").text(screenW);
  var sub_cell=6;
  var area_width=window.innerWidth-75;
if (screenW > 800) {
    $(".LN-DESC").css("width", (area_width/3)-40);
    $(".LN-PIC img").css({ width:60,height:60 });
  } else if (screenW < 600) {
    $(".LN-DESC").css("width", area_width);
    $(".LN-PIC img").css({ width:180,height:180 });
  }
}

function list_result() {
  $("#list-result").load("list_result.php", function() {
    infras();
    $(".LIST-NODE").show();
  });
}

function show_add_form() {
  $("#POPUP-DATA-ADD").fadeIn(200);
  $("#PICTURE-AREA").text('');
  $("#editID").val('');
  $(".add_form_input").val('');

}
function edit_data(id) {
  $("#POPUP-DATA-ADD").fadeIn(200);
  $("#editID").val(id);
  $("#PICTURE-AREA").text('');

  $(".add_form_input").each(function(){
    var inputID=$(this).attr("id");
    var dataToInput=$("#LN-"+inputID+"-"+id).text();

    $("#"+inputID).val(dataToInput).change();
  });
  for(var pic=1;pic<=6;pic++) {
    var file_name="../../machine_pic/"+id+"/"+id+"_File"+pic+".jpg";
    var file_number=pic-1;
  $("#PICTURE-AREA").append("<img src='"+file_name+"' title='Picture"+file_number+"'>");

  }
}
function close_form() {
  $("#POPUP-DATA-ADD").fadeOut(200);
}


function submit_form() {
  var editID=$("#editID").val();
  var MachineID=$("#MachineID").attr('id');
  if (MachineID==='') {
    alert('Machine ID cannot leave Blank!');

  } else {
    var variable_list="";
    var value_send="";
    $(".add_form_input").each(function(){
      value_send+=$(this).val()+"__19__";
      variable_list+=$(this).attr('id')+",";
    });

if(editID){
  var opr='submit_edit';
} else {
  var opr='submit_insert';
}

    $.ajax({
      type: "POST",
      url: "operation.php",
      enctype: "multipart/form-data",
      data: {
              opr: opr,
              id: editID,
              variable_list:variable_list,
              value_send:value_send
            },
      success: function(JobID) {
        $("#spare").text(JobID);
            $(".file_upload").each(function() {
              var inputID = $(this).attr("id");
              var file_path = $(this).val();
              if (JobID && file_path && inputID) {
                upload_picture_now(JobID,inputID);
              } else {
              }
            });

            if(opr==='submit_insert') {
              $("#spare2").load("list_result.php?opr=submit_prepend&ID=" + JobID,
              function(return_data) {
                $("#list-result").prepend(return_data);
                $(".LIST-NODE").slideDown(500);
                infras();
              });
        }
        close_form();
      }
    });
  }
}

function upload_picture_now(DATAID,inputID) {
  var fd = new FormData();
  var file_upload = $("#" + inputID)[0].files[0];
  fd.append("file", file_upload);

  $.ajax({
    url: "upload_picture.php?ID=" + DATAID + "&inputID=" + inputID,
    type: "post",
    data: fd,
    contentType: false,
    processData: false,
    success: function(response) {
      if (response != 0) {
      //    alert('file uploaded'+response);
      } else {
        alert("picture not uploaded");
      }
    }
  });
  $("#"+inputID).val('');
}

<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
include("../fn/fn_today.php");
include("fn_img_checkin.php");
$Page_Name = 'Check In';
$area_height = 250;


if ($_GET['opr'] == 'do_add') {
    $Picture = $_FILES["Picture"]["tmp_name"];
    $Picture_Name = $_FILES["Picture"]["name"];
    $Picture_Name_Explode = explode("___", $Picture_Name);
    $Picture_UserName = $Picture_Name_Explode[1];
    $Picture_UserID = $Picture_Name_Explode[2];
    $Picture_UserBranch = $Picture_Name_Explode[3];
    $Picture_UserMethod = $Picture_Name_Explode[4];
    if ($Picture_UserMethod == 'in') {
        $Method = 1;
        $Method_Text = 'Check In';
    } else if ($Picture_UserMethod == 'out') {
        $Method = 2;
        $Method_Text = 'Check Out';
    }
    //echo $Picture_UserName."/".$Picture_UserID."/".$Picture_UserBranch."/".$Method;
    $File_Name = "u" . $Picture_UserID . "b" . $Picture_UserBranch . "d" . $Today00 . "t" . $DateU . "m" . $Picture_UserMethod;
    $Text_Anotate .= "$Method_Text : " . $Picture_UserName . "@" . $Picture_UserBranch;
    $Text_Anotate .= " \n";
    $Text_Anotate .= "Date : " . date("d/m/Y", $DateU) . " " . date("H:i:s", $DateU);

    $sql_MK_img = "INSERT INTO checkin (BranchID,Date_Time,Method,UserID) VALUES ('$Picture_UserBranch','$DateU','$Method','$Picture_UserID')";
    $result_MK_img = $conn_db->query($sql_MK_img);
    $dir = "picture/$Picture_UserBranch";
    if (!FILE_EXISTS($dir)) {
        exec("mkdir $dir");
    }
//####Start Upload
    $filenameS = $Picture;
    $filenameD = "$dir/$File_Name.jpg";

    $data = getimagesize($filenameS);
    $width = $data[0];
    $height = $data[1];
    $type = $data[2];

    if (move_uploaded_file($filenameS, $filenameD) AND $type == 2) {
        echo "Success : " . date("H:i:s", $DateU) . "\n";
        echo "Name: $Picture_UserName\n\n\n\n\n\n\n\n\n\n";
        exec("convert $filenameD -resize 600 $filenameD");
       exec("composite -geometry +10+1  bg.png $filenameD $filenameD");
        exec("convert $filenameD -fill '#0008' -draw 'rectangle 0,400,1000,430' -fill white -annotate +10+415 '$Text_Anotate' $filenameD");
    } else {
        echo "Upload Error!\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
    }
}
?>
<html>
    <head>
        <title><?= $Page_Name ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <link href="index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            var br = localStorage.br;
            var usr = localStorage.usr;
            var usrID = localStorage.usrID;
            var upload_url = "http://pos.ambient.co.th/admin/checkin/index.php?opr=do_add";

         //   upload_url = "http://192.168.1.95/pos_play/admin/checkin/index.php?opr=do_add";

            $(document).ready(function () {
                $("#user-list").load("user_list.php?br=" + br, function () {
                    $(this).trigger("create");
                });
                load_history();
                if (/Android/i.test(navigator.userAgent)) {
                    $("#capture_check").show();
                } else {
                    $("#topic-title").css("padding-left", "60px");
                }
            });
            function load_history() {
                var dfr = $("#dfr").val();
                var dto = $("#dto").val();
                var dorder = $("#dorder").val();

                var inF = $("#inF").val();
                var inT = $("#inT").val();

                var outF = $("#outF").val();
                var outT = $("#outT").val();

                var workF = $("#workF").val();
                var workT = $("#workT").val();
                $("#history").load("history.php?br=" + br + "&dfr=" + dfr + "&dto=" + dto + "&dorder=" + dorder + "&inF=" + inF + "&inT=" + inT + "&outF=" + outF + "&outT=" + outT + "&workF=" + workF + "&workT=" + workT + "&usr=" + usr + "&usrID=" + usrID);
            }
            function turn_camera_in() {
                var photoid = $("#check_in_user").val();
                if (!photoid) {
                    alert('กรุณาเลือกผู้ใช้งาน');
                } else if (photoid) {
                    $("#spare1").load("http://127.0.0.1:8080/take_photo?photo_id=" + photoid + "in__&upload_url=" + upload_url);
                }

            }
            function turn_camera_out() {
                var photoid = $("#check_in_user").val();
                if (!photoid) {
                    alert('กรุณาเลือกผู้ใช้งาน');
                } else if (photoid) {
                    $("#spare1").load("http://127.0.0.1:8080/take_photo?photo_id=" + photoid + "out__&upload_url=" + upload_url);
                }
            }
            function show_pic() {

                var picfile = $(this).attr("picfile");
                show_checkin_photo(picfile);
            }
            function show_checkin_photo(ths) {
                var slotid = ths;
                var picture_name = $("#" + slotid).attr("picfile");
                var pic_local = "<img style='width:100%' src='picture/" + br + "/" + picture_name + "'>";
                $("#picture_area").popup("open").html("<img src='../../img/ajaxloader.gif'>" + picture_name)
                        .html(pic_local);
            }
            function hide_checkin_photo() {
                $("#picture_area").fadeOut(300);
            }
            function advance_adjust() {
                var inF = $("#inF").val();
                var inT = $("#inT").val();
                var outF = $("#outF").val();
                var outT = $("#outT").val();
                var workF = $("#workF").val();
                var workT = $("#workT").val();
                var dStyle = $("#dStyle").prop("checked");

                $(".in-val,.out-val,.period-val").show();
                $(".in-val,.out-val,.period-val").css("color", "#000");

                if (dStyle === true) {
                    $(".in-val").filter(function () {
                        return parseInt($(this).attr("tinH")) < inF;
                    }).hide();
                    $(".in-val").filter(function () {
                        return parseInt($(this).attr("tinH")) > inT - 1;
                    }).hide();

                    $(".out-val").filter(function () {
                        return parseInt($(this).attr("toutH")) < outF;
                    }).hide();
                    $(".out-val").filter(function () {
                        return parseInt($(this).attr("toutH")) > outT - 1;
                    }).hide();

                    $(".period-val").filter(function () {
                        return parseInt($(this).attr("periodh")) < workF;
                    }).hide();
                    $(".period-val").filter(function () {
                        return parseInt($(this).attr("periodh")) > workT - 1;
                    }).hide();
                } else if (dStyle === false) {
                    $(".in-val").filter(function () {
                        return parseInt($(this).attr("tinH")) < inF;
                    }).css("color", "#ccc");
                    $(".in-val").filter(function () {
                        return parseInt($(this).attr("tinH")) > inT - 1;
                    }).css("color", "#ccc");

                    $(".out-val").filter(function () {
                        return parseInt($(this).attr("toutH")) < outF;
                    }).css("color", "#ccc");
                    $(".out-val").filter(function () {
                        return parseInt($(this).attr("toutH")) > outT - 1;
                    }).css("color", "#ccc");

                    $(".period-val").filter(function () {
                        return parseInt($(this).attr("periodh")) < workF;
                    }).css("color", "#ccc");
                    $(".period-val").filter(function () {
                        return parseInt($(this).attr("periodh")) > workT - 1;
                    }).css("color", "#ccc");
                }
            }
        </script>
    </head>
    <body>
        <?php
        include("../header/header.php");
        ?>
        <div style="text-align:center;padding:10px;background-color:#444;color:#fff;display:none" id="capture_check">
            <div id="user-list"></div>
            <div style="margin-top:30px;text-align:center;">
                <div style="float:left;width:49%"><button class="ui-btn ui-icon-plus ui-btn-icon-top ui-corner-all" onclick="turn_camera_in()">เข้างาน</button></div>
                <div style="float:right;width:49%"><button class="ui-btn ui-icon-plus ui-btn-icon-top ui-corner-all" onclick="turn_camera_out()">ออกงาน</button></div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <div>
            <form name='main_form' enctype="multipart/form-data" method="post" action="<?= $PHP_SELF ?>?opr=do_add" style="display:none">
                <input  id="Picture" name="Picture" value="<?= $Picture ?>" type="file" /><br/>
                <input type="submit" name="submit_add" class="fs18"  value="Submit" id="submit-add"/>
            </form>
        </div>
        <div style="padding:10px;">  
            <h2 style="color:#555;font-size:25px;text-align:left" id="topic-title">Check In / Out history</h2>
            <div style="color:#555;font-size:15px;text-align:left">
                <?php
                $fr_input = date("Y-m-d", $Today00 - (86400 * 30));
                $to_input = date("Y-m-d", $Today00 + 86399);
                ?>
                <div style="float:left;width:150px;">
                    <input type="date" id="dfr" value='<?= $fr_input ?>'>
                </div>
                <div style="float:left;padding:15px 5px">-</div>
                <div style="float:left;width:150px;">
                    <input type="date" id="dto" value='<?= $to_input ?>'>
                </div>
                <div style="float:left;width:140px;padding-top:3px">
                    <select id='dorder' data-mini='true'>
                        <option value='DESC'>Order ↑</option>
                        <option value='ASC'>Order ↓</option>
                    </select>
                </div>
                <div style="float:left;width:100px;margin-left:10px;padding-top:3px;">
                    <button class="ui-btn ui-icon-search ui-btn-icon-left ui-corner-all ui-mini" onclick="load_history()" id="normal-search">Search</button>
                </div>
                <div style="clear:both;width:200px;margin-left:20px;" onclick="$('#advance-section').slideToggle(300);$(this).hide(300)">Advance Search</div>
                <div style="clear:both;float:left;display:none;border:solid 1px #ccc;padding:10px;" id="advance-section">
                    <div style="float:left;">
                        <div data-role="rangeslider" style="width:400px;">
                            <label for="range-1a" style="font-size:13px;">Check-in Time : </label>
                            <input type="range" name="inF" id="inF" min="0" max="24" value="0" onchange="advance_adjust()">
                            <input type="range" name="inT" id="inT" min="0" max="24" value="24" onchange="advance_adjust()">
                        </div>
                    </div>
                    <div style="float:left;margin-left:30px;">
                        <div data-role="rangeslider" style="width:400px;">
                            <label for="range-1a" style="font-size:13px;">Check-out Time : </label>
                            <input type="range" name="outF" id="outF" min="0" max="24" value="0" onchange="advance_adjust()">
                            <input type="range" name="outT" id="outT" min="0" max="24" value="24" onchange="advance_adjust()">
                        </div>
                    </div>
                    <div style="clear:both;float:left;">
                        <div data-role="rangeslider" style="width:400px;">
                            <label for="range-1a" style="font-size:13px;">Working Hour : </label>
                            <input type="range" name="workF" id="workF" min="0" max="24" value="0" onchange="advance_adjust()">
                            <input type="range" name="workT" id="workT" min="0" max="24" value="24" onchange="advance_adjust()">
                        </div>
                    </div>
                    <div style="float:left;margin-left:30px;">
                        <label for="range-1a" style="font-size:13px;">Style : </label>
                        <input type="checkbox" data-role="flipswitch" name="flip-checkbox-3" id="dStyle" data-on-text="hide" data-off-text="fade" data-wrapper-class="custom-size-flipswitch" onchange="advance_adjust()">
                    </div>
                </div>
            </div>
        </div>
        <div id="history" style="clear:both;width:100%;overflow:auto;padding:10px;"></div>
        <div data-role="popup" id="picture_area" data-overlay-theme="b"  style="max-width:650px;position:fixed;top:20px;right:20px;"></div>
        <div id="spare1"></div>
        <style type="text/css">
            .check-time-in{
                font:normal 15px sans-serif;
                padding:10px;
                border-radius:3px;
                cursor:pointer;
            }
            .check-time-out{
                font:normal 15px sans-serif;
                padding:10px;
                border-radius:3px;
                cursor:pointer;
            }
        </style>
    </body>
</html>

$(document).ready(function () {
    $("#usr").focus();
    var width = window.innerWidth ||
            document.documentElement.clientWidth ||
            document.body.clientWidth;
    var height = window.innerHeight ||
            document.documentElement.clientHeight ||
            document.body.clientHeight;
    $("#screenWidth").val(width);
    $("#screenHeight").val(height);
    $("#screenSize").text(width + 'x' + height);
    $('textarea').bind("enterKey", function (e) {
//do stuff here
    });
    $('#usr,#pwd').keyup(function (e) {
        if (e.keyCode === 13)
        {
            signIn();
        }
    });
});
function signIn() {
    var device = '';
    if (/Android/i.test(navigator.userAgent)) {
        device = 'AND';
    } else if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
        device = 'APL';
    } else if (/window/i.test(navigator.userAgent)) {
        device = 'WIN';
    } else if (/linux/i.test(navigator.userAgent)) {
        device = 'LIN';
    } else {
        device = navigator.userAgent;
    }
    $("#BUTTON-AREA").hide();
    $("#LOADING-AREA").show();
    var usr = $("#usr").val();
    var pwd = $("#pwd").val();
    var time_diff = 0;
    $.post("login.php", {usr: usr, pwd: pwd, device: device}, function (data) {
        $("#spare").text(data);
        if (data === '0') {
            $("#NOTIFY-AREA").text('Login Failed!');
            $("#BUTTON-AREA").show();
            $("#LOADING-AREA").hide();
        } else if (data !== '') {
            var passData = data.split("___");
            var d = new Date();
            var seconds = Math.round(d.getTime() / 1000);
            time_diff = parseInt(passData[16]) - seconds;
            localStorage.usrID = passData[0];
            localStorage.usr = passData[1];
            localStorage.br = passData[2];
            localStorage.ServiceNo_Last = passData[3];
            localStorage.Set_System_View = passData[4];
            localStorage.Set_Bill_Title = passData[5];
            localStorage.Set_Address_Title = passData[6];
            localStorage.Set_TAXID = passData[7];
            localStorage.Set_POSID = passData[8];
            localStorage.Set_Queue = passData[9];
            localStorage.Set_Footer = passData[10];
            localStorage.Set_Lang_POS = passData[11];
            localStorage.Set_Lang_Bill = passData[12];
            localStorage.permission = passData[13];
            localStorage.Set_Currency = passData[14];
            localStorage.Set_Footer_Option = passData[15];
            localStorage.Set_Brief_Report = passData[16];
            localStorage.Set_Screen2 = passData[17];
            localStorage.Set_Restaurant = passData[18];
            localStorage.Set_Member_Name = passData[19];
            localStorage.Set_Member_Point = passData[20];
            localStorage.Set_Payment_Option = passData[21];
            localStorage.Type = passData[22];
            localStorage.Set_Multi_Station = passData[23];
            localStorage.Set_Ticket = passData[24];
            localStorage.Set_Scan_Member = passData[25];
            localStorage.time_diff = time_diff;
            localStorage.login_time = seconds;
            if (localStorage.usrID && localStorage.br > 0) {

                    window.location.replace('status/');
            } else {
                $("#NOTIFY-AREA").text('Login Failed!');
                $("#BUTTON-AREA").show();
                $("#LOADING-AREA").hide();
            }
        }
    });
}


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
    $("#BUTTON-AREA").hide();
    $("#LOADING-AREA").show();
    var usr = $("#usr").val();
    var pwd = $("#pwd").val();

    $.post("login.php", {usr: usr, pwd: pwd}, function (data) {
        if (data === '0') {
            $("#NOTIFY-AREA").text('Login Failed!');
            $("#BUTTON-AREA").show();
            $("#LOADING-AREA").hide();
        } else {
            var passData = data.split("___");
            localStorage.BMusrID = passData[0];
            localStorage.BMusr = passData[1];
            localStorage.BMbr = passData[2];
            if (localStorage.BMusrID && localStorage.BMbr) {
                window.location.replace('status/');
            } else {
                $("#NOTIFY-AREA").text('Login Failed!');
                $("#BUTTON-AREA").show();
                $("#LOADING-AREA").hide();

            }
        }
    });
}

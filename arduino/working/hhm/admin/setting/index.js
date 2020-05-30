var br = localStorage.br;
var usr = localStorage.usr;
var usrID = localStorage.usrID;
var Set_Restaurant = localStorage.Set_Restaurant;
var ww = window.innerWidth;
var wh = window.innerHeight;

$(document).ready(function () {
    if (usr === 'admin') {
        $("#SECTION-ADMIN-ACCOUNT").html("<img src='../../img/ajaxloader.gif'>").load("section_admin_account.php?usr=" + usr + "&br=" + br, function () {
            $(this).trigger("create");
        });
        if (Set_Restaurant) {
            $("#SECTION-ADMIN-RESTAURANT").html("<img src='../../img/ajaxloader.gif'>").load("section_admin_restaurant.php?usr=" + usr + "&br=" + br, function () {
                $(this).trigger("create");
            });
        } else {
            $("#SECTION-ADMIN-POS").html("<img src='../../img/ajaxloader.gif'>").load("section_admin_pos.php?usr=" + usr + "&br=" + br, function () {
                $(this).trigger("create");
            });
        }
    }
    $("#SECTION-STAFF").load("section_staff.php?usr=" + usr + "&br=" + br);

});
function infra() {

    $("#Add-Form").css({
        position: 'fixed',
        maxHeight: wh - 200,
        overflowY: 'auto',
        left: (ww / 2) - 200,
        top: 100,
        width: 400
    });
}
function change_password() {
    $(".notify-icon").hide();
    var current_password = $("#current_password").val();
    var new_password = $("#new_password").val();
    var confirm_password = $("#confirm_password").val();
    if (current_password === '' || new_password === '' || confirm_password === '') {
        amsalert('กรุณากรอกข้อมูลให้ครบถ้วน', 'white', 'red');
        $(".notify-icon").show();
    } else if (new_password !== confirm_password) {
        amsalert('New Password และ Confirm Password ไม่ตรงกัน!', 'white', 'red');
        $("#notify-confirm-password").show();
    } else {
        $("#spare").load("operation.php?opr=change_password&br=" + br + "&current_password=" + current_password + "&new_password=" + new_password + "&usr=" + usr, function (data) {
            if (data === 'success') {
                amsalert('รหัสผ่านของท่านได้ถูกเปลี่ยนเรียบร้อยแล้ว\nกรุณาเข้าสู่ระบบอีกครั้งด้วยรหัสผ่านใหม่!', 'white', 'green');
                window.location.replace("../logout.php");
            } else if (data === 'fail') {
                amsalert('รหัสผ่านปัจจุบันของท่านไม่ตรง กรุณาตรวจสอบอีกครั้ง!', 'white', 'red');
                $("#notify-current-password").show();
            }
        });
    }
}

function show_remark_mod() {
    $("#mod-remark").slideToggle(300);
}

function open_add_form() {
    infra();
    $('#form-title').text('Add New Staff');
    $('.Add_Section').show();
    $('#form-submit').text('Add Staff');
    $('#form-submit').attr('onclick', 'check_add()');
    $('#Add-Form').popup('open');
    $('#Add-Form input').val('');

}
function open_edit_form(id) {
    infra();
    $('#form-title').text('Modify Staff');
    $('#form-submit').text('Modify Staff');
    $('#Add-Form input').val('');
    $("#staffid").val(id);
    $('#form-submit').attr('onclick', 'check_edit()');
    $('.Add_Section').hide();
    $('#Add-Form').popup('open');
    $("#regis-name").val($("#Firstname-" + id).text());
    $("#regis-position").val($("#Position-" + id).text());
    $("#regis-telephone").val($("#Telephone-" + id).text());
    $("#regis-salary").val($("#Salary-" + id).text());
    $("#regis-salaryfood").val($("#Salaryfood-" + id).text());
    var remark = $("#Remark-" + id).html();
    remark = remark.replace(/<br>/g, '\n');
    $("#regis-remark").val(remark);
}

function check_add() {
    var username = $("#regis-username").val();
    var password = $("#regis-password").val();
    var name = $("#regis-name").val();
    var position = $("#regis-position").val();
    var telephone = $("#regis-telephone").val();
    var salary = $("#regis-salary").val();
    var salaryfood = $("#regis-salaryfood").val();
    var remark = $("#regis-remark").val();
    if (!username || !password || !name) {
        alert("กรุณากรอกข้อมูลให้ครบถ้วน!");
    } else if (password.length < 4) {
        alert("รหัสผ่านจะต้องมีความยาว 4 ตัวขึ้นไป!");
    } else if (username && password && name) {
        name = name.replace(/ /g, "%20");
        position = position.replace(/ /g, "%20");

        var additional = "&username=" + username;
        additional += "&password=" + password;
        additional += "&name=" + name;
        additional += "&position=" + position;
        additional += "&telephone=" + telephone;
        additional += "&salary=" + salary;
        additional += "&salaryfood=" + salaryfood;
        additional += "&remark=" + remark;
        $("#spare").load("operation.php?opr=do_add&br=" + br + additional, function (data) {
            if (data === 'success') {
                $('#Add-Form').popup('close');
                location.reload();
            } else if (data === 'exist') {
                alert(username + " ได้ถูกมีอยู่แล้ว!");
            } else {
                alert("Error, Something wrong!");
            }
        });
    }
}
function check_edit() {
    var password = $("#regis-password").val();
    var id = $("#staffid").val();
    var name = $("#regis-name").val();
    var position = $("#regis-position").val();
    var telephone = $("#regis-telephone").val();
    var salary = $("#regis-salary").val();
    var salaryfood = $("#regis-salaryfood").val();
    var remark = $("#regis-remark").val();
    remark = remark.replace(/\n/g, '<br>');
    if (!name) {
        alert("กรุณากรอกข้อมูลให้ครบถ้วน!");
    } else if (password && password.length < 4) {
        alert("รหัสผ่านจะต้องมีความยาว 4 ตัวขึ้นไป!");
    } else if (name) {

        $("#Firstname-" + id).text(name);
        $("#Position-" + id).text(position);
        $("#Telephone-" + id).text(telephone);
        $("#Salary-" + id).text(salary);
        $("#Salaryfood-" + id).text(salaryfood);
        $("#Remark-" + id).html(remark);
        name = name.replace(/ /g, "%20");
        position = position.replace(/ /g, "%20");

        remark = remark.replace(/ /g, '%20');

        var additional = "&id=" + id;
        additional += "&password=" + password;
        additional += "&name=" + name;
        additional += "&position=" + position;
        additional += "&telephone=" + telephone;
        additional += "&salary=" + salary;
        additional += "&salaryfood=" + salaryfood;
        additional += "&remark=" + remark;

        $("#spare").load("operation.php?opr=do_edit&br=" + br + additional, function (data) {
            $('#Add-Form').popup('close');
            amsalert('Staff Modified', 'white', 'green');
        });
    }
}
function check_submit_remark() {
    var remark = $("#remark_remark").val();
    remark = remark.replace(/ /g, "%20");
    if (!remark) {
        alert("กรุณากรอกข้อมูลให้ครบถ้วน!");
    } else if (remark.length < 4) {
        alert("remark จะต้องมีความยาว 4 ตัวขึ้นไป!");
    } else {
        var additional = "&remark=" + remark;
        $("#spare").load("operation.php?opr=do_add_remark&br=" + br + additional, function (data) {
            if (data === 'success') {
                window.location.replace("./");
            } else if (data === 'exist') {
                alert(remark + " ได้ถูกมีอยู่แล้ว!");
            } else {
                alert("Error, Something wrong!");
            }
        });
    }
}
function status_to(id, status_to) {

    $("#spare").load("operation.php?opr=do_status&br=" + br + "&id=" + id + "&status_to=" + status_to, function (data) {
        if (status_to === 1) {
            $("#gray-" + id).hide(300);
            $("#yellow-" + id).show(300);
        } else {
            $("#yellow-" + id).hide(300);
            $("#gray-" + id).show(300);
        }
        amsalert('Save Changes', 'white', 'green');
    });
}
function remark_status_to(id, status_to) {

    $("#spare").load("operation.php?opr=do_status_remark&br=" + br + "&id=" + id + "&status_to=" + status_to, function () {
        if (status_to === 1) {
            $("#remark_gray-" + id).hide(300);
            $("#remark_yellow-" + id).show(300);
        } else {
            $("#remark_yellow-" + id).hide(300);
            $("#remark_gray-" + id).show(300);
        }
        amsalert('Save Changes', 'white', 'green');
    });
}
function change_permission(ths) {
    var ths_val = ths.id;
    var ths_split = ths_val.split("-");
    var topic = ths_split[0];
    var id = ths_split[1];
    var current_status = $("#" + ths_val).is(":checked");
    var next_status = 0;
    if (current_status === true) {
        next_status = 1;
    }
    $("#spare").load("operation.php?opr=change_permission&br=" + br + "&id=" + id + "&topic=" + topic + "&next_status=" + next_status);
    amsalert('Save Changes', 'white', 'green');
}
function save_edit(id) {
    var Firstname = $("#Firstname_" + id).val();
    var Firstname_Old = $("#Firstname_Old_" + id).val();
    var Position = $("#Position_" + id).val();
    var Telephone = $("#Telephone_" + id).val();
    var Salary = $("#Salary_" + id).val();
    var Salaryfood = $("#Salaryfood_" + id).val();
    var Remark = $("#Remark_" + id).val();

    if (Firstname === '') {
        Firstname = Firstname_Old;
    }
    var Firstname_Show = Firstname;
    var Position_Show = Position;
    var Remark_Show = Remark;
    Firstname = Firstname.replace(/ /g, '%20');
    Position = Position.replace(/ /g, '%20');
    Remark = Remark.replace(/ /g, '%20');

    var additional = "&firstname=" + Firstname;
    additional += "&position=" + Position;
    additional += "&telephone=" + Telephone;
    additional += "&Salary=" + Salary;
    additional += "&Salaryfood=" + Salaryfood;
    additional += "&remark=" + Remark;
    additional += "&id=" + id;

    $("#spare").load("operation.php?opr=do_edit&br=" + br + additional, function (data) {
        $(".edit_form").hide(300);
        $("#Firstname_Show_" + id).text(Firstname_Show);
        $("#Position_Show_" + id).text(Position_Show);
        $("#Telephone_Show_" + id).text(Telephone);
        $("#Salary-" + id).text(Salary);
        $("#Salaryfood-" + id).text(Salaryfood);
        $("#Remark_Show_" + id).text(Remark_Show);
    });
    amsalert('Save Changes', 'white', 'green');
}
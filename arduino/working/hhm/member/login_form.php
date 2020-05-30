<script type="text/javascript">
    $(document).ready(function () {
        $("#phone").focus();
        $("#login_pincode").keypress(function (e) {
            if (e.which === 13) {
                login('<?= $b ?>');
            }
        });
    });
    function login(b) {
        var phone = $("#phone").val();
        var pin = $("#login_pincode").val();
        if (phone && pin) {
            $("#spare").load("../member/login_operation.php?operation=19&phone=" + phone + "&pin=" + pin + "&branchid=" + b, function (data) {
                if (data === 'error') {
                    alert("Mobile number or Pincode not Match!");
                } else {
                    var data_split = data.split("_____");
                    var memberID = data_split[1];
                    var memberName = data_split[2];
                    after_login(b, memberID, memberName);
                }
            });
        }
    }
    function checkMobile(current) {
        var current_val = current.value;
        var current_id = current.id;
        var first_digit = current_val.substring(0, 1);
        var last_digit = current_val.substring(current_val.length - 1);
        var old_digit = current_val.substring(0, current_val.length - 1);

        if (first_digit !== '0') {
            $("#" + current_id).val('');
        } else if ((current_val.length === 4 || current_val.length === 8) && last_digit !== '-') {
            $("#" + current_id).val(old_digit + "-" + last_digit);
        } else if ((last_digit < 48 || last_digit > 57) && current_val.length <= 12) {
        } else {
            $("#" + current_id).val(old_digit);
        }
    }
</script>
<div id="login-session">
    <div class="login-title">Mobile Number</div>
    <div><input type="text" id="phone" class="fs20" style="height:30px;width:100%;border:solid 1px;padding-left:5px;" onkeyup="checkMobile(this);"></div>
    <div class="login-title" style="padding-top:20px;">Pincode</div>
    <div style="width:50%;float:left;"><input type="password" id="login_pincode" class="fs20" maxlength="4" style="width:100%;height:30px;padding:0;border:solid 1px;padding-left:5px;"></div>
    <div style="float:right;width:40%;"><button style="width:100%;height:30px;padding:0;" class="fs20" id="button-login" onclick="login('<?= $b ?>')">Log in</button></div>
</div>
<?php
$sql_setting = "SELECT Member_Register_Point FROM shop_setting WHERE BranchID='$b' LIMIT 1";
$result_setting = $conn_db->query($sql_setting);
while ($db_setting = $result_setting->fetch_array()) {
  foreach ($db_setting as $key_setting => $value_setting) {
    $$key_setting = $value_setting;
  }
}
?>
<script type="text/javascript">
  $(document).ready(function () {
    $("#button-do-register").click(function () {
      var b = $("#b").val();
      var regis_phone = $("#regis_phone").val();
      var regis_pincode = $("#regis_pincode").val();
      var regis_cpincode = $("#regis_cpincode").val();
      var regis_name = $("#regis_name").val();
      var regis_email = $("#regis_email").val();
      var regis_d = $("#regis_d").val();
      var regis_m = $("#regis_m").val();
      var regis_y = $("#regis_y").val();
      var regis_gender = $("#regis_gender").val();
      var Member_Register_Point = '<?=$Member_Register_Point?>';
      var duplicate = 0;
      if (regis_phone) {
        if (!regis_phone || !regis_pincode || !regis_cpincode || !regis_name) {
          alert('Please complete the register form!');
        } else if (regis_phone.length < 9) {
          alert('Phone number must be longer than 8 digits');
        } else if (regis_pincode !== regis_cpincode) {
          alert('Pincode and Confirm Pincode not Match');
        } else if (regis_pincode.length !== 4) {
          alert('Pincode must be 4 digits');
        } else {
          regis_phone = regis_phone.replace(/-/, '');
          var additional = "&b=" + b +
                  "&regis_phone=" + regis_phone +
                  "&regis_pincode=" + regis_pincode +
                  "&regis_name=" + regis_name +
                  "&regis_email=" + regis_email +
                  "&regis_d=" + regis_d +
                  "&regis_m=" + regis_m +
                  "&regis_y=" + regis_y +
                  "&regis_gender=" + regis_gender+
                  "&Member_Register_Point=" + Member_Register_Point;
          $("#spare_register").load("../member/register_operation.php?opr=do_register" + additional, function (data) {
            if (data === 'duplicated') {
              alert("Your Mobile number already Existed!");
            } else if (data === 'success') {
              alert("Registeration Success, Enjoy!");
              after_register();
            }
          });
        }
      }
    });
  });
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
<input type='hidden' id='b' name='b' value='<?= $b ?>'>

<div class="login-title">Mobile Number</div>
<div class="field"><input style="width:60%" type="text" id="regis_phone" onkeyup="checkMobile(this);"></div>
<div style="float:left;width:45%">
  <div class="login-title">Pincode (4digits)</div>
  <div class="field"><input maxlength="4" type="password" id="regis_pincode" style="width:90%;"></div>
</div>
<div style="float:left;width:45%">
  <div class="login-title">Confirm Pincode</div>
  <div class="field"><input maxlength="4" type="password" id="regis_cpincode" style="width:90%;"></div>
</div>
<div  style="clear:both;"></div>
<hr/>
<div class="login-title">Your Name</div>
<div class="field"><input style="width:60%" type="text" id="regis_name"></div>
<div class="login-title" style="clear:both;">Your Email <span class="italic fs14">(optional)</span></div>
<div class="field"><input style="width:80%" type="text" id="regis_email"></div>
<div class="none">
  <div class="login-title">Date of Birth</div>
  <div class="field">
    <select id="regis_d">
      <option value="0"></option>
      <?php for ($d = 1; $d <= 31; $d++) {
        ?>
        <option value="<?= $d ?>"><?= $d ?></option>
      <?php } ?>
    </select>
    <select id="regis_m">
      <option value="0"></option>
      <?php for ($m = 1; $m <= 12; $m++) {
        ?>
        <option value="<?= $m ?>"><?= date("F", mktime(0, 0, 0, $m, 10)) ?></option>
      <?php } ?>
    </select>
    <select id="regis_y">
      <option value="0"></option>
      <?php
      for ($y = date("Y") - 10; $y >= date("Y") - 80; $y--) {
        $textY = $y + 543;
        ?>
        <option value="<?= $y ?>"><?= $textY ?></option>
      <?php } ?>
    </select>
  </div>
  <div class="login-title">Gender</div>
  <div class="field">
    <select id="regis_gender">
      <option value="0"></option>
      <option value="1">Male</option>
      <option value="2">Female</option>
    </select>
  </div>
</div>
<div style="float:right;">
  <button class="fs20" id="button-do-register">Register</button>
</div>
<div id='spare_register' class="none">spare_register</div>
<style type="text/css">
  .login-title {
    font:normal 16px sans-serif;
    text-align:left;
  }
  #login-session div{
    text-align:left;
  }
  .field{
    margin-bottom:20px;
    text-align:left;
  }
  .field input {
    font:normal 20px sans-serif;
  }
  .field select {
    font:normal 20px sans-serif;
  }
</style>
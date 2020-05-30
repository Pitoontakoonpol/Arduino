<?php
$Page = 'Home';
$br = $_GET['b'];
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AmbientPOS | Member Registration</title>
    <link rel="alternate" href="https://www.posambient.com" hreflang="th-TH" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"/>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script>
      function check_reg() {
        var firstName = $("#First_Name").val();
        var lastName = $("#Last_Name").val();
        var mobile = $("#Mobile").val();
        var reg_password = $("#reg_password").val();
        var confirm_password = $("#confirm_password").val();
        var DOB = $("#DOB").val();
        var gender0 = $("#gender-0").val();
        var gender1 = $("#gender-1").val();
        var gender2 = $("#gender-2").val();
        var email = $("#Email").val();
        var agreement = $("#Agreement").val();
        var br = $("#br").val();

        if (!firstName || !mobile || !reg_password || !confirm_password || !email) {
          alert("please complete the form");
        } else if (mobile.length < 9) {
          alert('Mobile Number must contain at least 10 characters!');
        } else if (reg_password !== confirm_password) {
          alert('Password not Match!');
        } else if (reg_password.length < 6) {
          alert('Password must contain at least 6 characters!');
        } else if (agreement === '1') {
          alert('Please check Agree with our policy!');
        } else {

          var gender = 0;
          if ($("#gender-1").is(":checked")) {
            gender = 1;
          } else if ($("#gender-2").is(":checked")) {
            gender = 2;
          }
          $.ajax({
            type: "POST",
            url: "member_reg_opr.php",
            enctype: 'multipart/form-data',
            data: {
              opr: 'reg_member',
              br: br,
              firstName: firstName,
              lastName: lastName,
              mobile: mobile,
              reg_password: reg_password,
              DOB: DOB,
              gender: gender,
              email: email
            },
            success: function (data) {

              alert("Registration Completed!, Please enjoy with our Promotion!");
              window.location.replace("https://www.hahama.co.th");

            }
          });
        }
      }
    </script>
  </head>
  <div style="width:100%;max-width:800px;margin:0 auto;padding:5px;background-color:#fff;">
    <div style="float:left;">
      <img src="../img/logo.jpg" style="width:150px;margin:5px;">
    </div>
    <div style="float:right;">
      <div style="font:bold 25px sans-serif;padding-right:10px">Register</div>
    </div>
    <div style="clear:both;border-bottom:solid 1px #ddd;width:90%;margin:0 auto;"></div>
    <div style="width:100%;max-width:400px;margin:0 auto;margin-top:20px;">
      <div class="label">Your Name</div>
      <div class="label-answer">
        <div  style="width:120px;float:left;margin-right:10px;">
          <input type="hidden" id="br" value="<?= $_GET['b'] ?>">
          <input type="text" id="First_Name" placeholder="First Name">
        </div>
        <div style="width:220px;float:left;">
          <input type="text" id="Last_Name" placeholder="Last Name">
        </div>
        <div style="clear:both;"></div>
      </div>
      <div class="label">Mobile</div>
      <div class="label-answer" style="width:300px;"><input type="number" id="Mobile" ></div>
      <div class="label">Password <span style="font-size:10px;">(Must contain at least 6 characters.)</span></div>
      <div class="label-answer">
        <div  style="width:160px;float:left;margin-right:10px;">
          <input type="password" placeholder="Password" id="reg_password">
        </div>
        <div style="width:160px;float:left;">
          <input type="password" placeholder="Confirm" id="confirm_password">
        </div>
        <div style="clear:both;"></div>
      </div>
      <div class="label">Date of Birth</div>
      <div class="label-answer" style="width:350px;"><input type="date" id="DOB"></div>
      <div class="label">Gender</div>
      <div class="label-answer">
        <fieldset data-role="controlgroup" data-type="horizontal">
          <input type="radio" name="radio-choice-h-2" id="gender-1" value="1">
          <label for="gender-1">Male</label>
          <input type="radio" name="radio-choice-h-2" id="gender-2" value="2">
          <label for="gender-2">Female</label>
          <input type="radio" name="radio-choice-h-2" id="gender-0" value="0" checked="checked">
          <label for="gender-0">Undefined</label>
        </fieldset>
      </div>
      <div class="label">Email</div>
      <div class="label-answer" style="width:350px;"><input type="text" id="Email" ></div>
      <div class="label"></div>
      <div data-role="fieldcontain" style="float:left;margin:0 10px;">
        <select name="flip-2" id="Agreement" data-role="slider">
          <option value="1">No</option>
          <option value="0">Yes</option>
        </select>
      </div>
      <div style="float:left;font-size:13px;width:200px;">I have read and agree to the <a href="#">Terms & Conditions</a>. Terms of Use and the <a href="#">Privacy Policy</a></div>
      <div class="label-answer" style="clear:both;padding:25px;width:90%;"><button onclick="check_reg()">Register</button></div>
    </div>
  </div>
</div>
<style type="text/css">
  .label{
    clear:both;
    margin-top:25px;
    font:normal 12px sans-serif;
  }
</style>
</body>
</html>

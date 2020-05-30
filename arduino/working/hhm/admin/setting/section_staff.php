<div class="setting-box">
  <h2  class="setting-title">Change Password <span style="background-color:yellow;font-size:35px;">&nbsp;&nbsp;<?= $_GET['usr']; ?>&nbsp;&nbsp;</span> </h2>
  <table class="main-table">
    <tr>
      <th>Current Password</th>
      <td><div style="width:300px;float:left;"><input type="password" id="current_password"></div><div class="notify-icon" id="notify-current-password"><img src="../../img/caution.svg"></div></td>
    </tr>
    <tr>
      <th>New Password</th>
      <td><div style="width:300px;float:left;"><input type="password" id="new_password"></div><div class="notify-icon" id="notify-new-password"><img src="../../img/caution.svg"></div></td>
    </tr><tr>
      <th>Confirm Password</th>
      <td><div style="width:300px;float:left;"><input type="password" id="confirm_password"></div><div class="notify-icon" id="notify-confirm-password"><img src="../../img/caution.svg"></div></td>
    </tr><tr>
      <td colspan='2'><button  class="ui-btn ui-btn-icon-left ui-icon-save ui-btn-corner-all ui-btn-inline ui-btn-b" onclick="change_password()">Change Password</button></td>
    </tr>
  </table>
</div>
<style type="text/css">
  .notify-icon{
    float:left;
    margin-top:20px;
    padding-left:5px;
    display:none;
  }
  .notify-icon img{
    height:30px;

  }
</style>
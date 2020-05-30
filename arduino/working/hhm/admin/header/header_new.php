<?php
session_start();
$SESSION_POS = $_SESSION['SESSION_POS'];
$SESSION_POS_ID = $_SESSION['SESSION_POS_ID'];
$SESSION_POS_BRANCH = $_SESSION['SESSION_POS_BRANCH'];
$tab = $_GET['tab'];
?>
<div id='header-left' style="position:fixed;z-index:50;top:5px;left:5px;background-color:#333;padding:10px;border-radius:25px;width:30px;height:30px;z-index:200;" onclick="$('#header-link').slideToggle(150);"><img src='../../img/action/detail_header.png' style="height:30px;" align='absmiddle'></div>
<div id='header-link'  class='bg3' style='z-index:19;position:fixed'>
  <?php
  if ($Permission_POS) {
    ?>
    <div class='header-link' id='link-pos' onclick="document.location = '../pos/'">POS</div>
    <?php
  }
  if ($SESSION_POS_TYPE == 2 OR $Set_Payment_Step == 1) {
    ?>
    <div class='header-link' id='link-table' onclick="document.location = '../table/'">Table</div>
    <?php
  }
  if ($Permission_Status) {
    ?>
    <div class='header-link' id='link-status' onclick="document.location = '../status/'">Status</div>
    <?php
  }
  if ($Permission_Menu) {
    ?>
    <div class='header-link' id='link-menu' onclick="document.location = '../menu/'">Menu</div>
    <?php
  }
  if ($SESSION_POS_PACKAGE == 2 OR $SESSION_POS_PACKAGE == 4) {
    if ($Permission_Kitchen) {
      ?>
      <div class='header-link' id='link-kitchen' onclick="document.location = '../kitchen/'">Kitchen</div>
      <?php
    }
  }
  if ($SESSION_POS_PACKAGE == 3 OR $SESSION_POS_PACKAGE == 4) {
    if ($Permission_Stock) {
      ?>
      <div class='header-link' id='link-stock' onclick="document.location = '../importexport/'">Stock</div>
      <?php
    }
  }
  ?>
  <?php
  if ($SESSION_POS_PACKAGE == 3 OR $SESSION_POS_PACKAGE == 4) {
    ?>
    <div class='header-link' id='link-cash_drawer' onclick="document.location = '../cashdrawer/'">Cash&nbsp;Drawer</div>
    <?php
    if ($Permission_Raw_Material) {
      ?>
      <div class='header-link' id='link-raw_material' onclick="document.location = '../raw_material/'">Raw&nbsp;Material</div>
      <?php
    }
  }
  if ($SESSION_POS_PACKAGE > 1) {
    if ($Permission_Member) {
      ?>
      <div class='header-link' id='link-member' onclick="document.location = '../member/'">Member</div>
      <?php
    }
    if ($Permission_Promotion) {
      ?>
      <div class='header-link' id='link-promotion' onclick="document.location = '../promotion/'">Promotion</div>
      <?php
    }
    ?>
    <div class='header-link' id='link-checkin' onclick="document.location = '../checkin/'">Check-In</div>
    <?php
  }
  if ($Permission_Report) {
    ?>
    <div class='header-link' id='link-report' onclick="document.location = '../report/'">Report</div>
  <?php } ?>
  <div class='header-link' id='link-manual' onclick="document.location = '../manual/'">User&nbsp;Manual</div>

  <?php
  if ($SESSION_POS_BRANCH == 1) {
    ?>
    <div class='header-link' id='link-customized' onclick="document.location = '../<?= $SESSION_POS_BRANCH ?>/'">Customized</div>
  <?php } ?>
  <div class='header-link' id='link-setting' onclick="document.location = '../setting/'">Setting</div>
  <div class='header-link' id='link-logout' onclick="document.location = '../logout.php'">Logout</div>

</div>
<style type='text/css'>
  @media print{
    #header{
      display:none;
    }
  }
  #header{
    color:white;
    padding:5px;
    height:50px;
    position:fixed;
    top:0;
    width:100%;
    z-index:10;
  }
  #header-left{
    height:50px;
    border-right:solid 1px #a78c6e;
    padding-right:7px;
    float:left;
    cursor:hand;
  }
  #header-right{
    border-left:solid 3px #000;
    float:right;
    cursor:hand;
  }
  #header-link{
    position:absolute;
    left:0;
    top:50px;
    width:150px;
    display:none; 
    cursor:hand;
  }
  .header-link{
    padding:5px 5px 5px 10px;
    font:bold 20px sans-serif;
    border-top:solid 2px #a78c6e;
    color:#fff;
    height:25px;
    z-index:9;
    cursor:hand;
  }
  #link-<?= strtolower($Page_Name); ?> {
    background-color:#a78c6e;
    cursor:hand;
  }
</style>

<script type="text/javascript">
  function logout() {
    var r = confirm("Logout?");
    if (r == true) {
      window.location.assign("../logout.php");
    }

  }
</script>

<script type="text/javascript">
  var screenW = window.innerWidth;
  var screenH = window.innerHeight;
  var BMbr = localStorage.BMbr;
  $(document).ready(function () {
    var check_session = localStorage.BMusrID;
    if (!check_session) {
      logout();
    }
    manage_infras();
    get_permission();
  });
  function manage_infras() {
    $("#HEADER-USERNAME").text(localStorage.BMusr);
    $("#HEADER-SECTION").css({
      maxWidth: screenW - 200,
      maxHeight: screenH - 200
    });
  }
  function logout() {
    localStorage.removeItem("BMbr");
    localStorage.removeItem("BMusr");
    localStorage.removeItem("BMusrID");
    window.location.href = "../?lgt=1";

  }
</script>

<div id="HEADER-MAIN-BUTTON" style="position:fixed;z-index:150;top:0;left:0;" data-rel="popup" onclick="$('#HEADER-SECTION').popup('open');">
  <a style="width:55px;height:55px;margin:0;"  class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-bars ui-btn-icon-notext ui-btn-b"></a>
</div>
<div id="FOOTER-MAIN" style="position:fixed;z-index:150;bottom:10px;right:10px;text-align:right;" data-rel="popup" onclick="$('#HEADER-SECTION').popup('open');">
  <img src="../../img/logo.png" style="width:80px;"><br/>
  BRANCH MANAGER</div>

<div data-role="popup" id="HEADER-SECTION" data-transition="fade" data-overlay-theme="b">
  <div id="HEADER-USERNAME"></div>
  <div class="HEADER-NODE PSTA"><img onclick="window.location.replace('../status/')"  src="../../img/header/header_status.png"><br/>Status</div>
  <div class="HEADER-NODE PREP"><img onclick="window.location.replace('../report/')" src="../../img/header/header_report.png"><br/>Report</div>
  <div class="HEADER-NODE PMEM"><img onclick="window.location.replace('../member/')"  src="../../img/header/header_member.png"><br/>Member</div>
  <div class="HEADER-NODE PMEN"><img onclick="window.location.replace('../menu/')"  src="../../img/header/header_product.png"><br/>Product</div>
  <!--
  <div class="HEADER-NODE PRAW"><img onclick="window.location.replace('../raw_material/')"  src="../../img/header/header_raw_material.png"><br/>Raw-Material</div>
  <div class="HEADER-NODE PSTO"><img onclick="window.location.replace('../importexport/')"  src="../../img/header/header_stock.png"><br/>Stock</div>
  <div class="HEADER-NODE PSET"><img onclick="window.location.replace('../setting/')"  src="../../img/header/header_settings.png"><br/>Settings</div>
  -->
  <div class="HEADER-NODE PLOG"><img onclick="logout()" src="../../img/header/header_logout.png"><br/>Logout</div>
  <div style="clear:both"><img src="../../img/logo_white.png"></div>
</div>
<style type="text/css">
  #HEADER-SECTION{
    border:solid 1px #000;
    background-color:rgba(80,20,20,0.8);
    padding:10px;
    overflow-y:auto;
    overflow-x:hidden;
    text-align:center;
  }
  #HEADER-USERNAME{
    font:bold 20px sans-serif;
    color:#fff;
    padding:20px;

  }
  .HEADER-NODE{
    cursor:pointer;
    float:left;
    width:180px;
    height:200px;
    text-align:center;
    color:#fff;
    margin:15px;

  }
  .HEADER-NODE img{
    height:150px;
  }
  bk {
    clear:both;
  }
</style>
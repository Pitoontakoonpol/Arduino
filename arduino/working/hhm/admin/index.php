<?php
session_start();
include("php-config.conf");
include("php-db-config.conf");
?>
<html>
  <head>
    <title>AmbientPOS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
    <link rel="stylesheet" href="../class/jquery.mobile-1.4.5.min.css">
    <script src="../class/jquery-1.11.3.min.js"></script>
    <script src="../class/jquery.mobile-1.4.5.min.js"></script>
    <link href="../class/css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="login.js?180133"></script>
  </head>
  <body>
    <div style='clear:both'></div> 
    <input type="hidden" id="screenWidth" name="screenWidth"/>
    <input type="hidden" id="screenHeight" name="screenHeight"/>
    <div style="background:url('../img/Bar/secondary-release.png');">
      <div>
          <img src="../img/Ambient-Soft-Logo-200w.png" border="0" style='max-width:35%;margin:5px;cursor:pointer;' onclick="location.reload(true);alert('Updated Finished! You are now Up-to-Date.')" title="Update System"/>
      </div>
      <div style='position:absolute;top:10px;right:10px;font:bold 25px sans-serif'>HAHAMA</div>
    </div>
    <div style="background-image:url('../img/Bar/head-tile.png');height:300px;">
      <div style='clear:both;float:right;width:calc(100% - 40px);max-width:400px;padding:50px 20px;'>
        <div id="NOTIFY-AREA" style="font:bold 16px sans-serif;color:red;"></div>
        <input placeholder="Username" type="text" id="usr" style="font-size:20px;"/>
        <input placeholder="Password" type="password" id="pwd" style="font-size:20px;"/>
        <div style="text-align:center;" id="BUTTON-AREA">
          <button onclick="signIn()" class="ui-btn ui-btn-icon-left ui-icon-lock ui-corner-all ui-btn-inline" style="font-size:20px;width:60%">Log In</button>
        </div>
        <div style="text-align:center;display:none;" id="LOADING-AREA">
          <img src="../img/ajaxloader.gif">
        </div>
      </div>
      <div style='clear:both;display:none' id="spare">SPARE</div>
    </div>
    <div style='background-color:#ccc;padding:3px' class='fs14'>
      &copy; 2008 - <?php echo date("Y"); ?> : <a href="http://www.ambient-soft.com" target="_blank">Ambient Soft Co., Ltd.</a>
    </div>
  </body>
</html>

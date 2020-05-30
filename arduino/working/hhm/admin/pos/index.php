
<!DOCTYPE HTML>
<html class="htmlClass">
  <head>  
    <title>POS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
    <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
    <script src="../../class/jquery-1.11.3.min.js"></script>
    <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
    <link href="index.css?1001" rel="stylesheet" type="text/css" />
    <link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../class/fn/comma.js"></script>
    <script type="text/javascript" src="fn_ambient.js?1015"></script>
    <script type="text/javascript" src="index.js?<?=date('U')?>"></script>
  </head>
  <body>
    <?php
    include("../header/header.php");
    ?>

    <div id="SECTION-TOP">loading ...</div>
    <div id="DIALOG-ORDER-LIST-SUPERMARKET">
      <div id="ORDER-LIST-DEL-"></div>
      <div id="ORDER-LIST-QTY-" style="text-align:center;">Qty.</div>
      <div id="ORDER-LIST-NAME-" style="text-align:center;">Name</div>
      <div id="ORDER-LIST-PRICE-" style="text-align:center;">Unit Price</div>
      <div id="ORDER-LIST-TOTAL-PRICE-" style="text-align:center;">Total Price</div>
      <div style="clear:both;"></div>
    </div>
    <div id="SECTION-MENU">loading ...</div>
    <div id="SECTION-RIGHT"><?php include("section_right.php") ?></div>
    <div id="SECTION-BOTTOM"></div>
    <div id="SECTION-DEVELOPER" style='visibility:hidden;'><?php include("section_developer.php") ?></div>
    <div id="INVOICE-PAPER" style="position:fixed;top:0;left:0;z-index:199;width:240px;background-color:#fff;padding:10px;overflow-x:hidden;display:none;"><?php include("invoice.php") ?></div>
    <div id="spare"></div>
    <div id="customer_display" style="display:none;"></div>
  </body>
</html>

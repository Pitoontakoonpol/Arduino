<?php
$br = $_GET['br'];
$ServiceNo = $_GET['serviceNo'];
include("../php-config.conf");
include("../php-db-config.conf");
?>
<link href="../../class/jicon.css" rel="stylesheet" type="text/css" />
<div class="area1">
    <div style="padding:10px 20px;">
        <button class='ui-btn ui-btn-icon-left ui-icon-print ui-corner-all' style='background-color:black;color:#fff;'>Re-Print Invoice</button>
    </div>
    <div style="padding:10px 20px;">
        <button class='ui-btn ui-btn-icon-left ui-icon-void ui-corner-all' style='background-color:gray;color:#fff;'>Void without Stock</button>
    </div>
    <div style="padding:10px 20px;">
        <button class='ui-btn ui-btn-icon-left ui-icon-void ui-corner-all' style='background-color:red;color:#fff;'>Void with Stock</button>
    </div>
</div>
<div style="padding:10px 20px;">
    <button class='ui-btn ui-btn-icon-left ui-icon-tax ui-corner-all' style='background-color:#53b700;color:#fff;' onclick="$('.area1').slideUp(200);$('.tax-customer-search').slideDown(500);">Issue Tax Invoice</button>
</div>
<div class="tax-customer-search" style="padding:5px 20px;display:none">
    <div style="font:bold 20px sans-serif">Customer Search</div>
    <input type="text" id="Tax-Name" placeholder="Name/Code/Type">
    <div style="width:250px;float:left">
        <input type="number" id="Tax-Mobile"  placeholder="Mobile/Tel">
        <input type="text" id="TaxNo"  placeholder="Tax#">
    </div>
    <div style="float:right">
        <button class="ui-btn ui-btn-icon-top ui-icon-search ui-corner-all" onclick="tax_customer_search()">Search Member</button>
    </div>
</div>
<div id="total-customer-search" style="clear:both;text-align:right;padding:5px 20px;display:block;"></div>
<div id="tax-customer-search-result" style="clear:both;padding:5px;display:block;overflow:auto;"></div>
<script type="text/javascript">
var innerHeight = window.innerHeight;
$('#tax-customer-search-result').css("height",innerHeight-220);
</script>

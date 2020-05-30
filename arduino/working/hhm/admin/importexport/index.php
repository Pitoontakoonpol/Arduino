<?php
session_start();

include("../php-config.conf");
include("../php-db-config.conf");
$Page_Name = 'Stock';
$tab = $_GET['tab'];
$Page = "ImportExport";
$Add_Mode = $_GET['Add_Mode'];
$sID = $_GET['sID'];
$eID = $_GET['eID'];
$display = $_GET['display'];
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Stock</title>
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <link rel="stylesheet" href="../../class/jicon.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" src="index.js"></script>

    </head>
    <body>
        <div id="spare" style="clear:both;display:none;"></div>
        <div id="spare1" style="clear:both;display:block;">spare1</div>
        <div id="scroll_position" style="display:none;padding-left:80px;">0</div>
        <div id="page_now" style="display:none;padding-left:80px;">1</div>
        <div id="header-bar"><?php include("../header/header.php"); ?></div>
        <div style="float:left; padding:2px 0 0 80px;">
            <select onchange='window.location.replace(this.value)'>
                <option value='./' <?php if (!$display) echo "selected='selected'"; ?>>All</option>
                <option value='./?display=1' <?php if ($display == 1) echo "selected='selected'"; ?>>Import</option>
                <option value='./?display=2' <?php if ($display == 2) echo "selected='selected'"; ?>>Export</option>
            </select>
        </div>
        <div style="position:fixed;top:0;right:0;">
            <div style="float:left; padding:2px;"><button class='ui-btn ui-icon-stock_po ui-btn-icon-left ui-corner-all ui-btn-inline' onclick="load_desc(0, 4)">Purchase</button></div>
            <div style="float:left; padding:2px;"><button class='ui-btn ui-icon-stock_do ui-btn-icon-left ui-corner-all ui-btn-inline' onclick="load_desc(0, 5)">Delivery</button></div>
            <div style="float:left; padding:2px;"><button class='ui-btn ui-icon-stock_import ui-btn-icon-left ui-corner-all ui-btn-inline' onclick="load_desc(0, 1)">Import</button></div>
            <div style="float:left; padding:2px;"><button class='ui-btn ui-icon-stock_export ui-btn-icon-left ui-corner-all ui-btn-inline' onclick="load_desc(0, 2)">Export</button></div>

        </div>
        <div id="table-title" style="position:fixed;">
            <table class="sub-table" style="margin-left:10px;" cellpadding="0" cellspacing="1">
                <tr>
                    <th><div class="node node1" style="padding:3px 0 0 0;height:20px;">Service#</div></th>
                    <th><div class="node node2">Method</div></th>
                    <th><div class="node node3">Date</div></th>
                    <th><div class="node node4">From Location</div></th>
                    <th><div class="node node5">To Location</div></th>
                    <th><div class="node node6">Remark</div></th>
                    <th><div class="node node7">Reference</div></th>
                    <th><div class="node node8">Product</div></th>
                    <th><div class="node node9" style="text-align:center;">Qty.</div></th>
                    <th><div class="node node10" style="text-align:center;">Price</div></th>
                    <th><div class="node node11">Staff</div></th>
                </tr>
            </table>
        </div>
        <div id="list_form" style="position:fixed;width:100%;bottom:0;overflow:auto;"></div>
        <div data-role="popup" id="POPUP-EDIT"data-transition="fade" data-overlay-theme="b" style="z-index:1;position:fixed;top:0;right:0;overflow-y:auto;background:none;">
            <div style="float:left;display:none;margin-right:5px;background-color:#ccc;overflow-y:auto;min-width:250px;" id="POPUP-ADDITION" class="ui-content">

                <div id="ADDITION-HEADER"></div>
                <div id="ADDITION-DESC"></div>
            </div>
            <div style="float:right;background-color:white;" class="ui-content">
                <div id="POPUP-TITLE" style="font:bold 25px sans-serif;padding:0 50px;text-align:center;"></div>
                <input type="hidden" id="paperid">
                <input type="hidden" id="paper_method">
                <div id="POPUP-HEADER">
                    <table style="font:normal 12px sans-serif;" cellpadding="3" cellspacing="0">
                        <tr>
                            <th>No.</th>
                            <td id="pserviceno"></td>
                            <th>Date/Time</th>
                            <td id="pdate"></td>
                        </tr>
                        <tr>
                            <th>From</th>
                            <td>
                                <div style="float:left;padding:3px;" id="pfrom_location"></div>
                                <div style="float:left;" id="pfrom_location_edit"><button class="ui-btn ui-corner-all" style="padding:2px 5px;margin:0;font:bold 12px sans-serif;" onclick="addition_from_address()">+</button></div>
                            </td>
                            <th>To</th>
                            <td>
                                <div style="float:left;padding:3px;" id="pto_location"></div>
                                <div style="float:left;" id="pto_location_edit"><button class="ui-btn ui-corner-all" style="padding:2px 5px;margin:0;font:bold 12px sans-serif;" onclick="addition_to_address()">+</button></div>
                            </td>
                        </tr>
                        <tr>
                            <th>Remark</th>
                            <td>

                                <div id="pdocumentno1_edit"><input type="text" id="pdocumentno1"></div>
                                <div id="pdocumentno1_view"></div>
                            </td>
                            <th>Reference</th>
                            <td>

                                <div id="pdocumentno2_edit"><input type="text" id="pdocumentno2"></div>
                                <div id="pdocumentno2_view"></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="POPUP-DESC"></div>
                <div id="POPUP-FOOTER">
                    <div style="float:left;width:90px;padding-top:8px"><button class="ui-btn ui-mini ui-icon-finish ui-btn-icon-left" onclick="finish_paper()">Finish</button></div>
                    <div style="float:left;width:90px;padding-top:8px"><button class="ui-btn ui-mini ui-icon-save ui-btn-icon-left" onclick="save_paper()">Save</button></div>
                    <div style="float:left;width:90px;padding-top:8px"><button class="ui-btn ui-mini ui-icon-delete ui-btn-icon-left" onclick="delete_paper()">Delete</button></div>
                    <div style="float:right;width:30px;padding-top:8px"><button class="ui-btn ui-mini ui-corner-all ui-icon-search ui-btn-icon-notext" onclick="find_product()">Find</button></div>
                    <div style="float:right;width:30px;padding-top:8px"><button class="ui-btn ui-mini ui-corner-all ui-icon-enter ui-btn-icon-notext" onclick="submit_command()">Add item</button></div>
                    <div style="float:right;width:calc(75% - 210px);padding-top:5px;"><input type="text" id="CMD-BOX" placeholder="Command"></div>
                </div>
                <div id="POPUP-FOOTER2">
                    <div style="float:left;">
                        <form action='pdf_paper.php' id='pdf_paper' target='_blank' method="POST">
                            <div style="float:left;width:90px;">
                                <input type='hidden' id="pdf-br" name="pdf_br"/>
                                <input type='hidden' id="pdf-serviceID" name="pdf_serviceID"/>
                                <button class="ui-btn ui-mini ui-btn-icon-left ui-icon-bars">PDF</button>
                            </div>
                        </form>
                    </div>
                    <div style="float:left;"><button class="ui-btn ui-mini ui-btn-icon-left ui-icon-delete" onclick="void_docs()" id="VOID-BTN">Void</button></div>
                </div>
            </div>
        </div>
    </body>
</html>

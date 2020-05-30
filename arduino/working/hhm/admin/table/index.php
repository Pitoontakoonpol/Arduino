<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
include("../fn/fn_today.php");
$Page_Name = 'Table';
$area_height = 250;
?>
<html>
    <head>
        <title><?= $Page_Name ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../class/jquery.js"></script>
        <script type="text/javascript" src="index.js"></script>
    </head>
    <body>
        <?php
        include("../header/header.php");
        ?>
        <div id="table_area" style="margin-top:50px;float:left;">
            <div style="width:100%"><?php include("../../branch_asset/".$SESSION_POS_BRANCH.".svg"); ?></div>
            <?php
            $sql = "SELECT Table_Name FROM table_desk WHERE BranchID='$SESSION_POS_BRANCH' AND Table_Status=1";
            $result = $conn_db->query($sql);
            while ($db = $result->fetch_array()) {
                $Table_Name = $db['Table_Name'];
                $Table_Open.=$Table_Name . ",";
            }
            ?>
        </div>
        <div id="order_now"></div>
        <div id="option_bar">
            <div class="button_area developer_note none">
                <input type="text" id="current_table" ><br/>
                <input type="text" id="Table_Open" value="<?= $Table_Open ?>"><br/>
                <input type="text" id="total_vat"><br/>
                <input type="text" id="total_service_charge">
            </div>
            <div class="button_area none" id="open_table"><button onclick="do_open()">Open Table</button></div>
            <div class="button_area none" id="make_order"><button onclick="make_order()">Make Order</button></div>
            <div class="button_area none" id="add_discount">
                <div><select id="dc_percent" class="fs20" style="width:100%;height:40px">
                        <option value="0">0%</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15" >15%</option>
                        <option value="20">20%</option>
                        <option value="25">25%</option>
                        <option value="30">30%</option>
                        <option value="40">40%</option>
                        <option value="50">50%</option>
                        <option value="60">60%</option>
                        <option value="70">70%</option>
                        <option value="80">80%</option>
                        <option value="90">90%</option>
                        <option value="100">100%</option>
                    </select>
                </div>
                <button  onclick="add_discount()" style="height:40px;">Add Discount</button>
            </div>
            <div class="button_area none" id="check_out"><button onclick="check_out1()">Check Out</button></div>
            <div style="clear:both;"></div>
        </div>
        <div id="Payment_Form" class="mask" style="position:fixed;width:100%;height:100%;top:30px;background-color:rgba(0,0,0,0.8);display:none">
            <div style="margin-left:150px;margin-top:100px;border:solid 5px #ccc;float:left;border-radius:5px;background-color:rgba(0,0,0,0.4)">
                <div  style="text-align:center;float:left;width:400px;border-right:solid 3px #999">
                    <div class="white" style="background-color:#ccc;margin-bottom:10px;">Credit Card</div>
                    <div class="white">Note: <input id="cdc_remark" style="background:none;border:solid 2px #ccc;font-size:16px;color:#fff;padding:5px;"></div>
                    <div onclick="check_out2('Visa')"><img src="../../img/bank_note/card_visa.png"></div>
                    <div onclick="check_out2('Master')"><img src="../../img/bank_note/card_master.png"></div>
                    <div onclick="check_out2('JCB')"><img src="../../img/bank_note/card_jcb.png"></div>
                </div>
                <div  style="text-align:center;float:left;width:400px;">
                    <div class="white" style="background-color:#ccc;margin-bottom:10px;">Cash</div>
                    <div class="white"><br/><br/>Get Cash<br/><input type="number" id="cash_remark" style="background:none;border:solid 2px #ccc;font-size:30px;color:#fff;padding:5px;width:200px;text-align:center;"></div>
                    <div style="padding:30px"><button onclick="check_out2('Cash');" style="font-size:40px;padding:10px 40px;">Cash</button></div>
                </div>
            </div>
        </div>
    <div id="spare" style="clear:both;"></div>
</body>
</html>
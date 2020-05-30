<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
include("../menu/fn_menu.php");
$SESSION_POS_BRANCH = $_SESSION["SESSION_POS_BRANCH"];
$SESSION_POS_ID = $_SESSION["SESSION_POS_ID"];
$operation = $_GET['operation'];
$operation_report = $_GET['operation_report'];


////Get Setting from the DB
$sql = "SELECT * FROM shop_setting WHERE BranchID='$SESSION_POS_BRANCH' LIMIT 1";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    $Set_VAT = $db['VAT'];
    $Set_Service_Charge = $db['Service_Charge'];

    $Invoice_Title = $db['Invoice_Title'];
    $Receipt_Title = $db['Receipt_Title'];
    $Address_Title = $db['Address_Title'];
    $TAXID = $db['TAXID'];
    $POSID = $db['POSID'];
}


if ($operation == 'open_table') {

    $Table_Name = $_GET['table_name'];
    $sql = "UPDATE table_desk SET Table_Status=1 WHERE Table_Name='$Table_Name' AND BranchID='$SESSION_POS_BRANCH'";
    $result = $conn_db->query($sql);
}

if ($operation == 'add_discount') {
    $Table_Name = $_GET['table_name'];
    $Discount = $_GET['discount'];


    $sql = "DELETE FROM service_order WHERE BranchID='$SESSION_POS_BRANCH' AND Remark LIKE 'dc%' AND Table_Desk='$Table_Name' AND Order_Open=1";
    $result = $conn_db->query($sql);
    if ($Discount > 0) {
        $sql = "INSERT INTO service_order (BranchID, Time_Order, Table_Desk, Quantity, Price, Order_Open, Remark)";
        $sql.="VALUES ('$SESSION_POS_BRANCH', '$DateU', '$Table_Name', '1', '$Discount_Price', '1', 'dc$Discount')";
        $result = $conn_db->query($sql);
    }
}

if ($operation_report == 'order_list_from_report') {
    $ServiceNo = $_GET['serviceNo'];
    $DateU = $_GET['timeDeliver'];

    $sql = "SELECT * FROM service_order WHERE ServiceNo='$ServiceNo' AND BranchID='$SESSION_POS_BRANCH'";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $payment_type = $db['Payment_By'];
        $payment_remark = $db['Payment_Remark'];
        $Remark = $db['Remark'];
        $Price = $db['Price'];
        $Quantity = $db['Quantity'];
        $TaxNo = $db['TaxNo'];
        $Tax_Name = $db['Tax_Name'];
        $Tax_Address = $db['Tax_Address'];
        $Tax_CompanyNo = $db['Tax_CompanyNo'];
        $Line_Price = $Price * $Quantity;
        $Grand_Total = $Grand_Total + $Line_Price;
        if (strpos($Remark, 'vat') !== false) {
            $Total_VAT = $Line_Price;
        } else if (strpos($Remark, 'sc') !== false) {
            $Total_SC = $Line_Price;
        } else if (strpos($Remark, 'sc') !== false) {
            $Total_SC = $Line_Price;
        } else if (strpos($Remark, 'dc') !== false) {
            $Total_DC = $Line_Price;
        }

        if (!$payment_remark) {
            $payment_remark = $Grand_Total;
        }
    }
    if ($TaxNo) {
        $operation = 'order_list_tax';
    } else {

        $operation = 'order_list';
    }
}

if ($operation == 'order_list') {

    $Table_Name = $_GET['table_name'];
    $Type = $_GET['type'];
    $Payment_Type = $_GET['payment_type'];
    ?>
    <div style="text-align:center;">
        <?php if ($operation_report == 'order_list_from_report') { ?>
            <button class="print-button" style="font-size:30px;" onclick="do_print_slip_history()"><img style='height:30px;'src='../../img/action/print.png'>Print</button>
        <?php } else { ?>
            <button class="print-button" style="font-size:30px;" onclick="do_print('<?= $APK_print ?>', '<?= date("d/m/Y%20H:i:s", $DateU) ?>', '<?= $SESSION_POS_BRANCH ?>', '0', '<?= $Table_Name ?>');"><img style='height:30px;'src='../../img/action/print.png'>Print</button>
        <?php } ?>
    </div>
    <!--Header started-->
    <div class='invoice-header' style="padding-left:8px">
        <div style = 'clear:both;text-align:center'><img style='width:95%' src="../../branch_asset/<?= $SESSION_POS_BRANCH ?>-settlement.png "></div>
        <div class='bill_title'><?= $Invoice_Title ?></div>
    </div>

    <div class='receipt-header none'>
        <div style = 'clear:both;text-align:center'><img style='width:95%' src="../../branch_asset/<?= $SESSION_POS_BRANCH ?>-invoice.png "></div>
    </div>
    <div style = 'clear:both;float:left;font:bold 15px sans-serif;text-align:center'><?= $Receipt_Title ?></div>
    <div style = 'clear:both;font:normal 12px sans-serif;text-align:center'><?= $Address_Title ?></div>
    <div style="clear:both;padding-left:8px">
        <div style="font:normal 11px sans-serif;float:left;">POSID:<?= $POSID ?></div>
    </div>
    <div style="clear:both;padding-left:8px;">
        <div class='bill_time' style="float:left;margin-right:10px;"><?= date("d/m/Y H:i:s", $DateU) ?></div>
        <div style = 'float:left;margin-right:10px;'>TBL.<?= $Table_Name ?></div>
        <div style ="float:left;" class='serviceNo'></div>
    </div>
    <div class = 'menu_line' style="padding:5px 0 20px 0;border:solid 1px #000;border-width:1px 0;margin-top:10px;">
        <div class='menu_del'>&nbsp;</div>
        <div class = 'menu_quantity' style="text-align:center;padding:0;">&nbsp;&nbsp;Qty</div>
        <div class = 'menu_name' style="text-align:center;padding:0;">Desc.</div>
        <div class = 'menu_price' style="text-align:center;padding:0;">Amount</div>
    </div>
    <!--Header ended-->
    <?php
    if ($operation_report == 'order_list_from_report') {
        $sql = "SELECT Remark FROM service_order WHERE ServiceNo='$ServiceNo' AND BranchID='$SESSION_POS_BRANCH' AND Remark LIKE'dc%' GROUP BY Remark";
    } else {
        $sql = "SELECT Remark FROM service_order WHERE Table_Desk = '$Table_Name' AND BranchID = '$SESSION_POS_BRANCH' AND Order_Open = 1 AND Remark LIKE'dc%' GROUP BY Remark";
    }
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $Bill_Remark = $db['Remark'];
        $Payment_By = $db['Payment_By'];
        $Discount_Percentage = substr($Bill_Remark, 2);
    }

    $sql = "SELECT ID,Time_Order,PromotionID,MenuID,QueueNo,SUM(Quantity) AS Quantity, SUM(Price*Quantity)+Total_Topup_Price AS Price, Total_Topup_Price,Cash,Remark";
    $sql .= " FROM service_order WHERE ";
    if ($operation_report == 'order_list_from_report') {
        $sql.="ServiceNo='$ServiceNo' ";
    } else {
        $sql.="Table_Desk = '$Table_Name' AND Order_Open = 1 ";
    }
    $sql.="AND MenuID<>'' AND BranchID = '$SESSION_POS_BRANCH' GROUP BY MenuID";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $OrderID = $db['ID'];
        $Time_Order = $db['Time_Order'];
        $Time_Order = date("d/m/Y%20H:i:s", $Time_Order);
        $PromotionID = $db['PromotionID'];
        $MenuID = $db['MenuID'];
        $QueueNo = $db['QueueNo'];
        $Quantity = $db['Quantity'];
        $Price = $db['Price'];
        $Total_Topup_Price = $db['Total_Topup_Price'];
        $Cash = $db['Cash'];
        $Bill_Remark = $db['Remark'];

        $Line_Price = $Price;
        $menuName_DESC = menu_desc($MenuID);
        $menuName = $menuName_DESC[4];
        $Discount_Menu = $menuName_DESC[43];
        if ($Total_Topup_Price) {
            $menuName = $menuName . '*';
        }
        if ($Discount_Percentage AND $Discount_Menu) {
            $Line_Discount = $Line_Price * ($Discount_Percentage / 100);
        } else {
            $Line_Discount = 0;
        }
        $Total_Discount = $Total_Discount + $Line_Discount;
        ?>
        <div class = 'menu_line'>
            <div class='menu_del' onclick="del_order('<?= $OrderID ?>', '<?= $menuName ?>', '<?= $Table_Name ?>')">
                <img src='../../img/action/trash-20.png'>
            </div>
            <div class = 'menu_quantity'><?= $Quantity ?></div>
            <div class = 'menu_name'><?= $menuName ?></div>
            <div class = 'menu_price'><?= number_format($Line_Price, 2) ?></div>
        </div>
        <?php
        $menuName = str_replace(" ", "%20", $menuName);
        $Bill_Remark = str_replace(" ", "%20", $Bill_Remark);
        $APK_print .= "%0A_bm_v1_500_30_22_0_25_" . $Quantity . "x%20" . $menuName . "_____" . number_format($Line_Price, 2);

        $Total_Price = $Total_Price + $Line_Price;
    }
    $APK_print .= "%0A_bm_v1_500_30_22_0_25_%20";

    echo "<script type='text/javascript'>$('#total_price_$Table_Name').val('$Total_Price');</script>";
    echo "<div class = 'menu_line' style='border-top:solid 1px;></div>";


    //echo "<div class = 'menu_line'><div class = 'menu_quantity'> </div><div class = 'menu_name' style = 'text-align:left;padding-top:5px;'>SubTotal</div><div class = 'menu_price'>" . number_format($Total_Price, 2) . "</div></div>";
    //$APK_print .= "%0A_bm_v1_500_30_22_0_25_%20SubTotal_____" . number_format($Total_Price, 2);

    if ($Set_Service_Charge) {
        $Add_Service_Charge = $Total_Price * ($Set_Service_Charge / 100);
        echo "<div class = 'menu_line'><div class = 'menu_quantity'> </div><div class = 'menu_name' style = 'text-align:left;padding-top:5px;'>Service Charge $Set_Service_Charge%</div><div style='' class = 'menu_price'>" . number_format($Add_Service_Charge, 2) . "</div></div>";
        $APK_print .= "%0A_bm_v1_500_30_22_0_25_%20Service%20Charge_____" . number_format($Add_Service_Charge, 2);
        echo"<script type='text/javascript'>$('#total_service_charge').val('$Add_Service_Charge')</script>";
    }

    if ($Discount_Percentage) {
      if($Total_DC) {
        $Discount_Price=$Total_DC;
      } else {
        $Discount_Price = $Total_Discount * (-1);
      }
        $Amount_Price = $Total_Price + $Discount_Price;
        echo "<div class = 'menu_line'><div class = 'menu_quantity'> </div><div class = 'menu_name' style = 'text-align:left;padding-top:5px;'>Discount $Discount_Percentage%</div><div class = 'menu_price'>" . number_format($Discount_Price, 2) . "</div></div>";
        $APK_print .= "%0A_bm_v1_500_30_22_0_25_%20Discount" . $Discount_Percentage . "Percent_____" . number_format($Discount_Percentage, 2);
        $sql2 = "UPDATE service_order SET Quantity='1',Price = '$Discount_Price',UsernameID='$SESSION_POS_ID' WHERE Remark LIKE 'dc%' AND Table_Desk = '$Table_Name' AND Order_Open = 1 AND BranchID = '$SESSION_POS_BRANCH'";
        $result2 = $conn_db->query($sql2);
        echo"<script type='text/javascript'>$('#dc_percent').val('$Discount_Percentage')</script>";
    }
    $Total_Price = $Total_Price + $Discount_Price + $Add_Service_Charge;
    echo "<div class = 'menu_line'><div class = 'menu_quantity'> </div><div class = 'menu_name' style = 'text-align:left;padding-top:5px;'>Total</div><div class = 'menu_price'>" . number_format($Total_Price, 2) . "</div></div>";
    $APK_print .= "%0A_bm_v1_500_30_22_0_25_%20Total_____" . number_format($Total_Price, 2);

    if ($Set_VAT) {
        $Add_VAT = $Total_Price * ($Set_VAT / 100);
        echo "<div class = 'menu_line'><div class = 'menu_quantity'> </div><div class = 'menu_name' style = 'text-align:left;padding-top:5px;'>VAT $Set_VAT%</div><div class = 'menu_price'>" . number_format($Add_VAT, 2) . "</div></div>";
        $APK_print .= "%0A_bm_v1_500_30_22_0_25_%20VAT_____" . number_format($Add_VAT, 2);
        echo"<script type='text/javascript'>$('#total_vat').val('$Add_VAT')</script>";
    }

    $Amount_Price = $Total_Price + $Add_VAT;
    $APK_print .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0AGrand%20Total:" . number_format($Amount_Price, 2);
    echo "<div style = 'clear:both;float:right;font:bold 20px sans-serif;padding:8px 0'>Grand Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; " . number_format($Amount_Price, 2);
    echo "<input type='hidden' id='grand_total' value='$Amount_Price'>";
    echo "</div>";
    ?>
    <div style='clear:both;text-align:center;font:normal 16px sans-serif;'>********* T H A N K &nbsp; Y O U *********</div>

    <div style = 'clear:both;margin-top:20px;padding-left:5px;' class="receipt-header none">
        <div style = 'float:left;margin-right:20px;'>TBL.<?= $Table_Name ?></div>
        <div style ="float:left;" class='serviceNo'></div>
        <div style ="float:right;">AmbientPOS</div>
        <div class='bill_time2' style="clear:both;"></div>
        <div style = 'clear:both;' id='paid-by1'></div>  
        <div style ="" class='serviceNo2'></div>
        <div>Vatable : <?= number_format($Amount_Price - $Add_VAT, 2) ?>&nbsp;&nbsp;Vat : <?= number_format($Add_VAT, 2) ?></div>
        <div style = 'clear:both;' id='paid-by2'></div>  
    </div>  


    <?php
    if ($operation_report == 'order_list_from_report') {
        $operation = 'check_out';
        $New_Service_Order = $ServiceNo;
    }
}


if ($operation == 'check_out') {
    if ($operation_report != 'order_list_from_report') {
        $Table_Name = $_GET['table_name'];
        $payment_type = $_GET['payment_type'];
        $payment_remark = $_GET['payment_remark'];
        $Grand_Total = $_GET['grand_total'];
        $Total_VAT = $_GET['total_vat'];
        $Total_SC = $_GET['total_service_charge'];
        //Calculate next ServiceNumber


        if ($Set_VAT OR $Set_Service_Charge) {
            $sql = "INSERT INTO service_order (BranchID,Table_Desk,Remark,Order_Open,Quantity,Price,UsernameID) VALUES ";
            if ($Set_VAT) {
                $sql .="('$SESSION_POS_BRANCH','$Table_Name','vat$Set_VAT','1','1','$Total_VAT','$SESSION_POS_ID')";
            }
            if ($Set_VAT AND $Set_Service_Charge) {
                $sql.=",";
            }
            if ($Set_Service_Charge) {
                $sql.="('$SESSION_POS_BRANCH','$Table_Name','sc$Set_Service_Charge','1','1','$Total_SC','$SESSION_POS_ID')";
            }
            $result = $conn_db->query($sql);
        }

        $Time_Order = $DateU;
        $CheckMax = date('ymd', $Time_Order) . str_pad($SESSION_POS_BRANCH, 5, '0', STR_PAD_LEFT);
        $sqlMAX = "SELECT MAX(ServiceNo),MAX(QueueNo) FROM service_order WHERE ServiceNo LIKE '$CheckMax%'  AND BranchID='$SESSION_POS_BRANCH' AND Order_Open=0";
        $resultMAX = $conn_db->query($sqlMAX);
        $listMAX = $resultMAX->fetch_row();
        $Max_Order = substr($listMAX[0], 11);
        $Max_Order = intval($Max_Order);
        $New_Service_Order = date("ymd", $Time_Order) . str_pad($SESSION_POS_BRANCH, 5, '0', STR_PAD_LEFT) . str_pad($Max_Order + 1, 5, '0', STR_PAD_LEFT);
    }

    if ($payment_type == 'Cash') {
        $Payment_Type = "Paid By : เงินสด -> " . number_format($Grand_Total, 2);
        $Payment_Type2 = "Received : " . number_format($payment_remark) . "&nbsp;&nbsp; Change :  " . number_format($payment_remark - $Grand_Total, 2);
    } else {
        $Payment_Type = "Paid By : " . $payment_type;
        if ($payment_remark) {
            $Payment_Type.="-" . $payment_remark;
        }
        $Payment_Type.=" -> " . number_format($Grand_Total, 2);
    }
    echo"<script type='text/javascript'>";
    echo"$('.serviceNo').text('Bill #" . str_pad($Max_Order + 1, 2, '0', STR_PAD_LEFT) . "');";
    echo"$('.serviceNo2').text('TaxInvoice(ABB)#$New_Service_Order');";
    echo"$('.bill_time').text('" . date("d/m/Y H:i:s", $DateU) . "');";
    echo"$('.bill_time2').text('Close Time : " . date("H:i:s d/m/Y", $DateU) . "');";
    echo"$('#paid-by1').html('$Payment_Type');";
    echo"$('#paid-by2').html('$Payment_Type2');";
    echo"$('.receipt-header').show();";
    echo"$('.invoice-header,.menu_del').hide();";
    echo"</script>";


    $sql = "UPDATE table_desk SET Table_Status = 0 WHERE Table_Name = '$Table_Name' AND BranchID = '$SESSION_POS_BRANCH'";
    $result = $conn_db->query($sql);

    $sql = "UPDATE service_order SET ServiceNo='$New_Service_Order', Order_Open = 0,Check_Out_Time='$DateU',Payment_By = '$payment_type' WHERE Table_Desk = '$Table_Name' AND Order_Open = 1 AND BranchID = '$SESSION_POS_BRANCH'";
    $result = $conn_db->query($sql);
}

if ($operation == 'order_list_tax') {
    ?>
    <div style="text-align:center;">
        <button class="print-button" style="font-size:30px;" onclick="do_print_slip_history()"><img style='height:30px;'src='../../img/action/print.png'>Print</button>
    </div>
    <div><img style='width:95%' src="../../branch_asset/<?= $SESSION_POS_BRANCH ?>-tax.png"></div>
    <div style="text-align:center;">POS ID <?= $POSID ?></div>
    <div style="text-align:center;width:70%;margin:10px auto;font:normal 16px sans-serif;border:solid 1px #000000;padding:3px;">ใบเสร็จรับเงิน / ใบกำกับภาษี</div>
    <div style="width:80%;margin:0 auto;">
        <div style="clear:both;">
            <div style="float:left;width:90px;">Tax-ID</div>
            <div style="float:left;"><?= $TAXID ?></div>
        </div>
        <div style="clear:both;">
            <div style="float:left;width:90px;">Tax-Inv.</div>
            <div style="float:left;"><?= $TaxNo ?></div>
        </div>
        <div style="clear:both;">
            <div style="float:left;width:90px;">Date</div>
            <div style="float:left;"><?= date("d/m/Y H:i:s", $DateU) ?></div>
        </div>
    </div>
    <div style="clear:both;width:80%;margin:0 auto;text-align:center;border:solid 1px #000000;border-width:1px 0;font-size:13px;">เป็นการยกเลิกใบกำกับภาษีอย่างย่อ<br/>เลขที่ <?= $ServiceNo ?><br/>และขอออกใบกำกับภาษีแบบเต็มรูปแทน</div>
    <div style="clear:both;width:80%;margin:0 auto;padding:5px;">
        <div style="font:normal 16px sans-serif"><?= $Tax_Name ?></div>
        <div><?= $Tax_Address ?></div>
        <div>Tax-ID <?= $Tax_CompanyNo ?></div>

    </div>
    <div style="clear:both;width:80%;margin:10px auto;padding:5px;border:solid 1px #000000;border-width: 1px 0;">

        <div style="float:left;width:160px;font-size:16px;padding-left:5px;">อาหารและเครื่องดื่ม</div>
        <div style="float:right;font-size:16px;padding-top:5px;"><?= number_format($Grand_Total, 2) ?></div>
        <div style="clear:both;"></div>
    </div>
    <div style="clear:both;width:80%;margin:10px auto;padding:5px;">

        <div style="clear:both;padding:5px 0;">
            <div style="float:left;width:120px;font-size:14px;">Sub Total</div>
            <div style="float:right;font-size:16px;"><?= number_format($Grand_Total, 2) ?></div>
        </div>
        <div style="clear:both;padding:5px 0;">
            <div style="float:left;width:120px;font-size:14px;">Service <?= $Set_Service_Charge ?>%</div>
            <div style="float:right;font-size:14px;"><?= number_format($Total_SC, 2) ?></div>
        </div>
        <div style="clear:both;padding:5px 0;">
            <div style="float:left;width:120px;font-size:14px;">VAT <?= $Set_VAT ?>%</div>
            <div style="float:right;font-size:14px;"><?= number_format($Total_VAT, 2) ?></div>
        </div>
        <div style="clear:both;padding:5px 0;">
            <div style="float:left;width:120px;font-size:16px;padding:5px 0;">Grand Total</div>
            <div style="float:right;font-size:16px;width:90px;text-align:right;padding:5px 0;border:solid 1px #000000;border-width: 1px 0 3px 0;"><?= number_format($Grand_Total, 2) ?></div>
        </div>
        <div style="clear:both;padding:5px 0;">
            <div style="float:left;width:120px;font-size:14px;">Before VAT</div>
            <div style="float:right;font-size:14px;"><?= number_format($Grand_Total / (1 + ($Set_VAT / 100)), 2) ?></div>
        </div>
    </div>
    <div style="text-align:center;padding:5px;font-size:12px;">** Thank You, Please come again **</div>

    <div style="clear:both;width:80%;margin:10px auto;padding:5px;">
        <div>ชำระโดย : <?= $payment_type ?></div>
        <div style="font-size:12px;">ผู้ออกใบกำกับ</div>
        <div style="border:solid 1px #000000;">
            <div style="width:70%;margin:80px auto 0 auto;border-top:solid 1px #000000;text-align:center;">พนักงานรับเงิน</div>
        </div>
    </div>
    <?php
}

if ($operation == 'order_del') {
    $delID = $_GET['delID'];
    $sql = "DELETE FROM service_order WHERE BranchID = '$SESSION_POS_BRANCH' AND ID='$delID'";
    $result = $conn_db->query($sql);
}
?>
<style type="text/css">

    .bill_title{
        clear:both;
        float:left;
        font:bold 30px sans-serif;
        text-align:left;
        padding-left:5px;
    }

    .menu_line{
        clear:both;
    }
    .menu_del{
        width:30px;
        float:left;
        padding-right:10px;
    }
    .menu_quantity{
        text-align:center;
        font-size:12px;
        padding-top:5px;
        padding-left:5px;
        float:left;
        width:20px;
    }
    .menu_name{
        margin-left:5px;
        font-size:12px;
        float:left;
        width:180px;

    }
    .menu_price{
        font-size:12px;
        padding-top:5px;
        float:right;
        text-align:right;
        width:60px;
    }
</style>

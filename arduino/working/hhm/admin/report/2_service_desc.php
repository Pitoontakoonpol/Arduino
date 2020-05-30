<?php
$br = $_GET['br'];
$ServiceNo = $_GET['serviceNo'];
include("../php-config.conf");
include("../php-db-config.conf");
include("../setting/fn_username.php");
include("../promotion/fn_promotion.php");
include("../fn/convert_payment_by.php");
include("../fn/fn_serviceno.php");
?>
<table class="desc-table-title" celpadding="0" cellspacing="0">
    <tr>
        <th>Service/ABB No.</th>
        <td id="title-ABB"></td>
        <th>Tax Invoice No.</th>
        <td id="title-Full_Tax"></td>
    </tr>
    <tr>
        <th>Order Time</th>
        <td id="title-Order_Time">asdf</td>
        <th>Order Status</th>
        <td id="title-Order_Status"></td>
    </tr>
    <tr>
        <th>Paid by</th>
        <td id="title-PaidBy"></td>
        <th>Cashier</th>
        <td id="title-Cashier"></td>
    </tr>
    <tr>
        <th>Member</th>
        <td id="title-Member_Name"></td>
        <th>Point Get</th>
        <td id="title-Total_Point"></td>
    </tr>
</table>
<table cellpadding='0' cellspacing='0' class="desc-table">
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Unit Price</th>
        <th>Quantity</th>
        <th>Total Price</th>
    </tr>
    <?php
    $sql = "SELECT MenuID,PromotionID,Table_Desk,service_order.ServiceNo AS SO_ServiceNo,QueueNo,Time_Order,Time_Order_YMD,Time_Cook,Time_Serve,Time_Deliver,Check_Out_Time,Quantity,Topup_Number,Total_Topup_Price,Price,service_order.Discount AS Discount,Payment_By,Payment_Refund, service_order.Remark AS Remark,service_order.Cancel AS SO_Cancel,service_order.Point AS SO_Point,service_order_tax.TaxNo AS Tax_TaxNo,service_order_tax.Date_Issue AS Tax_Date_Issue, ";
    $sql .= "menu.NameEN AS Menu_Name,menu.Menu_Code AS Menu_Code,menu.Type AS Menu_Type,username.Username AS Username,promotion.Name AS PromotionName, ";
    $sql .= "member.Name AS Member_Name ";
    $sql .= "FROM service_order ";
    $sql .= "LEFT JOIN service_order_tax ON service_order.QueueNo=service_order_tax.ServiceNo AND service_order.Time_Order_YMD=service_order_tax.SNYMD  ";
    $sql .= "LEFT JOIN menu ON service_order.MenuID=menu.ID ";
    $sql .= "LEFT JOIN username ON service_order.UsernameID=username.ID ";
    $sql .= "LEFT JOIN promotion ON service_order.PromotionID=promotion.ID ";
    $sql .= "LEFT JOIN member ON service_order.MemberID=member.ID ";
    $sql .= "WHERE service_order.ServiceNo='$ServiceNo' AND service_order.BranchID='$br'";
   // echo"<textarea>$sql</textarea>";
//update service_order SET QueueNo=substring(ServiceNo,12,5) WHERE QueueNo=0 AND ServiceNo<>0;
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }

        if ($MenuID) {
            $Menu_Name_Text = $Menu_Name;
            $Menu_Code = $Menu_Code;
            $Type = $Menu_Type;
            $Unit_Price = $Price;
            $Line_Discount = $Discount * $Quantity;
            $Line_Price = ($Quantity * $Price) + $Total_Topup_Price - $Line_Discount;
            if ($Total_Topup_Price) {
                $Menu_Name_Text .= "<sup style='font-size:10px' title='This line has Topup'>T</sup>";
                $Menu_Name_CSV .= " (T)";
            }
            if ($Line_Discount) {
                $Menu_Name_Text .= "<sup style='font-size:10px' title='This line has Discount'>D</sup>";
                $Menu_Name_CSV .= " (D)";
            }
        } else if ($PromotionID) {
            $MenuName = promotion_desc($PromotionID);
            $Menu_Name_Text = $MenuName[2];
            $Type = 'Promotion';
            $Unit_Price = $Price;
            $Line_Price = $Unit_Price * $Quantity;
        } else if ($Remark) {
            $Menu_Name_Text = $Remark;
            $Unit_Price = $Price;
            $Line_Price = $Unit_Price;
            $Queue_Stage = '';
            $Cook_Stage = '';
            $Delivery_Stage = '';

            $Quantity = '';
        } else {

            $Unit_Price = 0;
            $Line_Price = 0;
        }

        if ($Cancel == 1) {
            $cancel_css = 'cancel1';
            $cancel_text = "Canceled with Stock";
        } else if ($Cancel == 2) {
            $cancel_css = 'cancel2';
            $cancel_text = "Canceled without Stock";
        } else {
            $cancel_css = '';
            $cancel_text = "Normal";
        }
        if ($Discount) {
            $Discount = "-" . $Discount;
        }
        ?>
        <tr>
            <td style="text-align:center;"><?= $Menu_Code ?></td>
            <td style="text-align:left;"><?= $Menu_Name_Text ?></td>
            <td><?= number_format($Unit_Price, 2) ?></td>
            <td><?= $Quantity ?></td>
            <td><?= number_format($Line_Price, 2) ?></td>
        </tr>
        <?php
        if ($Topup_Number) {
            ?>

            <tr>
                <td></td>
                <td style="text-align:left;"><?= $Topup_Number ?></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php
        }
        $Total_Point += $SO_Point;
        $Total_Quantity = $Total_Quantity + $Quantity;
        $Total_Disc = $Total_Disc + $Line_Discount;
        $Total_VAT = $Total_VAT + $Unit_VAT;
        $Total_SC = $Total_SC + $Unit_SC;
        $Total_Topup = $Total_Topup + $Total_Topup_Price;
        $Total_Price = $Total_Price + $Line_Price;
    }
    $FullSN = full_serviceNo($Time_Order, $br, $QueueNo);
    if($Tax_TaxNo){
    $FullTaxNo = full_taxNo($br, $Tax_Date_Issue, $Tax_TaxNo);
    } else {
        $FullTaxNo='-';
    }

    if ($SO_Cancel == 1) {
        $cancel_text = "Canceled with Stock";
    } else if ($SO_Cancel == 2) {
        $cancel_text = "Canceled without Stock";
    } else {
        $cancel_text = "Normal";
    }
    if (!$Member_Name) {
        $Member_Name = '-';
    }
    if ($Payment_By == 0 AND $Payment_Refund > 0) {
        $Payment_Get=$Total_Price + $Payment_Refund;
        $Payment_Text = "Cash  " . $Payment_Get . " / Change $Payment_Refund";
    } elseif($Payment_By == 0) {
        $Payment_Text = "Cash  " . $Total_Price;
    } else {
        $Payment_Text = convert_payment_by($Payment_By);
    }
    ?>
</table>
<script type="text/javascript">
    $("#title-ABB").text('<?= $FullSN ?>');
    $("#title-Full_Tax").text('<?= $FullTaxNo ?>');
    $("#title-Order_Time").text("<?= date('Y-m-d H:i:s', $Time_Order) ?>");
    $("#title-Order_Status").text("<?= $cancel_text ?>");
    $("#title-Order_Status").text("<?= $cancel_text ?>");
    $("#title-PaidBy").text("<?= $Payment_Text ?>");
    $("#title-Cashier").text("<?= $Username ?>");
    $("#title-Member_Name").text("<?= $Member_Name ?>");
    $("#title-Total_Point").text("<?= $Total_Point ?>");

</script>
<style type="text/css">
    .desc-table{
        width:100%;
        border-right:solid 1px #444;
        margin-top:10px;
    }
    .desc-table th{
        font:bold 12px sans-serif;
        background-color:#ddd;
        border:solid 1px #444;
        border-width:1px 0 1px 1px;
        padding:4px;
        text-align:center;
    }
    .desc-table td{
        padding:3px;
        text-align:right;
        font:normal 13px sans-serif;
        background-color:#fff;
        border:solid 1px #444;
        border-width:0 0 1px 1px;
    }
    .desc-table-title{
        width:100%;
        margin-top:10px;
    }
    .desc-table-title th{
        padding:3px;
        text-align:center;
        font:normal 13px sans-serif;
        background-color:#ddd;
        border:solid 1px #fff;
        border-width:0 0 1px 1px;
        color:#666;
    }
    .desc-table-title td{
        padding:3px;
        text-align:center;
        font:bold 13px sans-serif;
        background-color:#ddd;
        border:solid 1px #fff;
        border-width:0 0 1px 1px;
    }

</style>
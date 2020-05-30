<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
$usrID = $_GET['usrID'];
$Set_Restaurant = $_GET['Set_Restaurant'];
if($Set_Restaurant) {
    $MainDB='service_order_restaurant';
} else {
    $MainDB='service_order';
}
include("../php-config.conf");

include("../php-db-config.conf");
include("../setting/fn_username.php");
include("../promotion/fn_promotion.php");
include("convert_date.php");
include("../fn/convert_payment_by.php");
$user_select = $_GET['user_select'];
if ($user_select) {
    $user_select_sql = " AND UsernameID IN (" . substr(str_replace("___", ",", $user_select), 0, -1) . ")";
}
?>

<script type="text/javascript">
    function do_print_summary() {
        var userAgent = navigator.userAgent;
        if (userAgent.match(/Android/i)) {
            var printAPK_summary = $("#apkPrint_summary").val();
            var print_detail_summary = "http://localhost:8080?type=e&please_print=" + printAPK_summary;
            $("#spare1").load(print_detail_summary);
        } else {
            window.print();
        }
    }
</script>
<div class="header2" style="padding:5px;">
    <div><button class="print-button-summary print-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-print ui-btn-inline" onclick="do_print_summary()">Print</button></div>
</div>
<div class="slip" style="color:#000;float:left;">
    <?php
    $text = '';
    $text .= "<br>Branch $br";
    echo "<div class='fs20 bold' style='text-align:center;'>Summary</div>";
    echo "<div class='fs14 bold' style='text-align:center;'>Branch $br</div>";
    $sql0 = "SELECT SUM(Price*Quantity),SUM(Total_Topup_Price),SUM(Discount*Quantity) FROM $MainDB ";
    $sql0 .= "WHERE MenuID<>0  ";
    $sql0 .= "AND Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to AND BranchID IN($br) $user_select_sql";
    $result0 = $conn_db->query($sql0);
    $list0 = $result0->fetch_row();
    $today_record = $list0[0];
    $today_topup = $list0[1];
    $today_discount = $list0[2];

    /*
      $sql1 = "SELECT SUM(Price*Quantity) FROM service_order ";
      $sql1 .= "WHERE PromotionID<>0 ";
      $sql1 .= "AND Cancel=0 AND Time_Order>=$fr AND Time_Order<$to AND BranchID IN($br) $user_select_sql";
      $result1 = $conn_db->query($sql1);
      $list1 = $result1->fetch_row();
      $today_promotion = $list1[0];
     */
    $sql2 = "SELECT ServiceNo FROM $MainDB ";
    $sql2 .= "WHERE Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to AND BranchID IN($br) $user_select_sql GROUP BY ServiceNo";
    $result2 = $conn_db->query($sql2);
    $total_bill = $result2->num_rows;

    $sql3 = "SELECT COUNT(1) FROM $MainDB ";
    $sql3 .= "WHERE Cancel>0 AND Time_Order_YMD BETWEEN $fr AND $to AND BranchID IN($br) $user_select_sql GROUP BY ServiceNo";
    $result3 = $conn_db->query($sql3);
    $total_cancel = $result3->num_rows;

    $sql4 = "SELECT SUM(CASE WHEN Method='1' AND Cancel='0' THEN Price ELSE 0 END),SUM(CASE WHEN Method='2' AND Cancel='0' THEN Price ELSE 0 END) FROM cash_drawer WHERE Date_Time>=".strtotime('20'.$fr) ." AND BranchID IN($br) $user_select_sql";
    $result4 = $conn_db->query($sql4);
    $list4 = $result4->fetch_row();
    $today_cash_in = $list4[0];
    $today_cash_out = $list4[1];


    $Total_Sell = $today_record;
    $Total_Topup = $today_topup;
    $Total_Bill = $total_bill;
    $Total_Cancel = $total_cancel;
    $Total_Cash_In = $today_cash_in;
    $Total_Cash_Out = $today_cash_out;
    $Amount_Sell = $Total_Sell + $Total_Topup + $today_promotion - $today_discount;
    $arrNo = 0;
    ?>
    <div>ระยะเวลาเริ่มต้น : <?= substr($fr,4,2)."-".substr($fr,2,2)."-20".substr($fr,0,2)." 00:00:00" ?></div>
    <div style="border-bottom:solid 2px">ระยะเวลาสิ้นสุด :  <?= substr($to,4,2)."-".substr($to,2,2)."-20".substr($to,0,2)." 23:59:59" ?></div>
    <div style="clear:both;">
        <div class="left_section">สรุปยอดขายสินค้า</div>
        <div class="right_section"><?= number_format($Total_Sell, 2) ?></div>
    </div>
    <div style="clear:both;">
        <div class="left_section">สรุปยอดขาย Topup</div>
        <div class="right_section"><?= number_format($Total_Topup, 2) ?></div>
    </div>
    <div style="clear:both;display:none;">
        <div class="left_section">สรุปส่วนลดจาก Promotion</div>
        <div class="right_section"><?= number_format($today_promotion, 2) ?></div>
    </div>
    <?php if ($today_discount) {
        ?>
        <div style="clear:both;">
            <div class="left_section">สรุปส่วนลด อื่นๆ</div>
            <div class="right_section">-<?= number_format($today_discount, 2) ?></div>
        </div>
    <?php } ?>        
    <div style="clear:both;border:solid 1px;">
        <div class="left_section">สรุปยอดขายรวมสุทธิ</div>
        <div class="right_section"><?= number_format($Amount_Sell, 2) ?></div>
        <div style="clear:both;"></div>
    </div>
    <?php if ($today_sc) {
        ?>
        <div style="clear:both;">
            <div class="left_section">สรุปยอด Service Charge</div>
            <div class="right_section"><?= number_format($today_sc, 2) ?></div>
        </div>
    <?php } ?>
    <?php if ($today_vat) {
        ?>
        <div style="clear:both;">
            <div class="left_section">สรุปยอด VAT</div>
            <div class="right_section"><?= number_format($today_vat, 2) ?></div>
        </div>
        <?php
    }

    $sql2 = "SELECT Payment_By,SUM(((Price-Discount)*Quantity)+Total_Topup_Price) AS Total_Payment FROM $MainDB ";
    $sql2 .= "WHERE Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to AND BranchID IN($br) $user_select_sql GROUP BY Payment_By";
    $result2 = $conn_db->query($sql2);
    while ($db2 = $result2->fetch_array()) {
        $Payment_By = $db2['Payment_By'];
        $Total_Payment = $db2['Total_Payment'];
        ?>
        <div style="clear:both;">
            <div class="left_section">ชำระโดย <?= convert_payment_by($Payment_By) ?></div>
            <div class="right_section"><?= number_format($Total_Payment, 2) ?></div>
        </div>
        <?php
        $apkPrint_Payment_Type .= "%0A_bm_v1_500_40_25_0_25_%20ชำระโดย%20" . convert_payment_by($Payment_By) . "_____" . number_format($Total_Payment, 2);
    }
    ?>
    <div style="clear:both;">
        <div class="left_section">จำนวนบิล</div>
        <div class="right_section"><?= number_format($Total_Bill) ?></div>
    </div>
    <div style="clear:both;">
        <div class="left_section">Void บิล (ครั้ง)</div>
        <div class="right_section"><?= number_format($Total_Cancel) ?></div>
    </div>
    <div style="clear:both;">
        <div class="left_section">นำเงินเข้า</div>
        <div class="right_section"><?= number_format($Total_Cash_In, 2) ?></div>
    </div>
    <div style="clear:both;">
        <div class="left_section">นำเงินออก</div>
        <div class="right_section"><?= number_format($Total_Cash_Out, 2) ?></div>
    </div>
    <div style="clear:both;"></div>
</div>
<div id="account-search" style="float:left;padding:20px;">
    <div style="font:bold 16px sans-serif;">Summary by User</div>
<?php
$sql = "SELECT DISTINCT Username,username.ID AS UserID FROM $MainDB INNER JOIN username ON ".$MainDB.".UsernameID=username.ID ";
$sql .= "WHERE Time_Order_YMD BETWEEN $fr AND $to AND ".$MainDB.".BranchID IN($br) ORDER BY 2";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    $Username = $db['Username'];
    $UserID = $db['UserID'];
    ?>
        <div><label><input type="checkbox" class="summary-user"  id="<?= $UserID ?>"> <?= $Username ?></label></div>
        <?php
    }
    if ($UserID) {
        if ($user_select) {
            $expld_user = explode("___", $user_select);
            foreach ($expld_user AS $val_usr) {
                echo "<script>$('#$val_usr').prop('checked', true);</script>";
            }
        } else {
            echo "<script>$('.summary-user').prop('checked', true);</script>";
        }
        ?>
        <div style="padding:10px;" ><button onclick="change_page()">Search</button></div>
        <?php
    } else {
        echo "<div style='color:#bbb'>Sales order empty</div>";
    }
    ?>
</div>
    <?php
    $apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0ASummary%20Slip";
    $apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . $fr_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . $to_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปยอดขายสินค้า_____" . number_format($Total_Sell, 2);
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปยอดขาย%20Topup_____" . number_format($Total_Topup, 2);
//$apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปส่วนลด%20Promotion_____" . number_format($today_promotion, 2);
    if ($today_discount) {
        $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปส่วนลด%20อื่นๆ_____" . number_format($today_discount, 2);
    }
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปยอดขายรวมสุทธิ_____" . number_format($Amount_Sell, 2);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";

    if ($today_sc) {
        $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปยอด%20Service%20Charge_____" . number_format($today_sc, 2);
    }
    if ($today_vat) {
        $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20สรุปยอด%20VAT_____" . number_format($today_vat, 2);
    }
    $apkPrint .= $apkPrint_Payment_Type;
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20จำนวนบิล_____" . $Total_Bill;
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20Void%20บิล%20(ครั้ง)_____" . $Total_Cancel;
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20นำเงินเข้า_____" . number_format($Total_Cash_In, 2);
    $apkPrint .= "%0A_bm_v1_500_40_25_0_25_%20นำเงินออก_____" . number_format($Total_Cash_Out, 2);

    $apkPrint .= "%0A<cut>%0A";
    ?>
<input type="hidden" id="apkPrint_summary" value="<?= $apkPrint ?>">
<style type='text/css'>
    .cancel1 {
        color:red;
        text-decoration: line-through;
    }   
    .cancel2 {
        color:#666;
        text-decoration: line-through;
    }
    .left_section{
        float:left;
        height:25px;
    }
    .right_section{
        float:right;
        padding-top:3px;
        height:22px;
    }
    .slip{
        padding:15px;
        width:300px;
        border:solid 1px;
    }
    @media print{
        .header2,#sub-bar,#account-search,#HEADER-MAIN-BUTTON{
            display:none;
        }
        .slip{
            border:none;
            width:200px;
            font-size:12px;
        }

    }
</style>

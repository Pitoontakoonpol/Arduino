<?php

include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$Total_Reload = $_GET['Total_Reload'];
$res = $_GET['res'];

if ($res == 0) {
    $pos_db = 'service_order';
} else if ($res == 1) {
    $pos_db = 'service_order_restaurant';
}

$sql_In1 = "SELECT SUM(Total),'Sell_History',MAX(Date) FROM daily_summary WHERE  BranchID='$br' AND Tender=0 AND Date<" . date("Ymd", $Today00);
$sql_In1 .= " UNION ALL ";
$sql_In1 .= "SELECT SUM((Price*Quantity)+Total_Topup_Price-(Discount*Quantity)),'Sell','0' FROM $pos_db WHERE Payment_By=0 AND Cancel=0 AND BranchID='$br' AND Time_Order_YMD=" . date("ymd", $Today00);
$sql_In1 .= " UNION ALL ";
$sql_In1 .= "SELECT SUM(Price),Method,'0' FROM cash_drawer WHERE Cancel=0 AND BranchID='$br' AND Method=1 ";
$sql_In1 .= " UNION ALL ";
$sql_In1 .= "SELECT SUM(Price),Method,'0' FROM cash_drawer WHERE Cancel=0 AND BranchID='$br' AND Method=2 ";
$sql_In1 .= " UNION ALL ";
$sql_In1 .= "SELECT SUM(Price),Method,'0' FROM cash_drawer WHERE Cancel=0 AND BranchID='$br' AND Method=0 ";
//echo $sql_In1;

$result_In1 = $conn_db->query($sql_In1);
while ($db_result = $result_In1->fetch_array()) {

    $Price = $db_result[0];
    $Method = $db_result[1];
    $Max_Update = $db_result[2];
    if ($Method == 'Sell_History') {
        $total_pos_hist = $total_remain + $Price;
        $Max_Update_Hist = $Max_Update;
    } else if ($Method == 'Sell') {
        $total_pos = $total_remain + $Price;
    } else if ($Method == 1) {
        $total_in = $total_remain + $Price;
    } else if ($Method == 2) {
        $total_out = $total_remain - $Price;
    } else if ($Method == 0) {
        $total_send = $total_remain - $Price;
    }
    unset($Price);
}
//echo "$total_pos + $total_in + $total_out + $total_send >>>> ";
echo number_format($total_pos_hist + $total_pos + $total_in + $total_out + $total_send, 2);

if ($Max_Update_Hist < date('Ymd', $Yesterday00) AND $Total_Reload == 0) {
    if ($Max_Update_Hist == 0) {
        $Update_Hist_Since = 0;
    } else {
        $Update_Hist_Since = date("ymd", strtotime($Max_Update_Hist) + 86400);
    }
    $Update_Hist_To = date("ymd");
    echo " ...";
    $sqlUD = "INSERT IGNORE INTO daily_summary (Date,Total,Tender,BranchID) SELECT FROM_UNIXTIME(Time_Order, '%Y%m%d'),SUM((Price*Quantity)+Total_Topup_Price-(Discount*Quantity)),Payment_By,BranchID FROM $pos_db WHERE BranchID='$br' AND Cancel=0 AND Time_Order_YMD>=$Update_Hist_Since AND Time_Order_YMD<$Update_Hist_To GROUP BY 4,1,3";
    $resultUD = $conn_db->query($sqlUD);
    echo"<script type='text/javascript'>load_total(1);</script>";
}
?>

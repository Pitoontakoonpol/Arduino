<?php

include("../php-config.conf");
include("../php-db-config.conf");
$new_system = $_POST['new_system'];
$sqlString = $_POST['sqlString'];
$br = $_POST['br'];
$purchasePoint = $_POST['purchasePoint'];
$totalPrice = $_POST['totalPrice'];
$memberID = $_POST['memberID'];
$paymentType = $_POST['paymentType'];
$paymentTypeRemark = $_POST['paymentTypeRemark'];
if($new_system=='1'){
$sql = "INSERT IGNORE INTO service_order (BranchID,ServiceNo,QueueNo,UsernameID,MemberID,Payment_By,Payment_Refund,Payment_Remark,Point,Time_Order_YMD,Time_Order,Time_Session,PromotionID,MenuID,Quantity,Price,Discount,Total_Topup_Price,Topup_Number) VALUES ";
} else {
$sql = "INSERT IGNORE INTO service_order (BranchID,ServiceNo,UsernameID,MemberID,Payment_By,Payment_Refund,Payment_Remark,Point,Time_Order_YMD,Time_Order,Time_Session,PromotionID,MenuID,Quantity,Price,Discount,Total_Topup_Price,Topup_Number) VALUES ";    
}
$sql .= substr($sqlString, 0, -1) . ';';
$result = $conn_db->query($sql);
echo $sql."<br/>";
if ($purchasePoint AND $memberID) {
  $sql = "UPDATE member SET Point_Remain=Point_Remain+$purchasePoint WHERE ID='$memberID'";
  $result = $conn_db->query($sql);
//echo $sql;
}
if ($paymentType=='Voucher') {
  $sql = "UPDATE voucher SET Remain_Value=Remain_Value-$totalPrice WHERE BranchID='$br' AND Voucher_Code='$paymentTypeRemark'";
  $result = $conn_db->query($sql);
//echo $sql;
}
?>
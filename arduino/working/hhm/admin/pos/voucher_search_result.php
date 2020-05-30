<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$voucherNo = $_GET['voucherNo'];

$sql = "SELECT ID,Value,Remain_Value FROM voucher WHERE BranchID='$br' AND Voucher_Code='$voucherNo'";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
  $ID = $db['ID'];
  $Value = $db['Value'];
  $Remain_Value = $db['Remain_Value'];
}
if ($ID) {
  echo "Value :" . $Value . "<br/>";
  echo "Usage : " . number_format($Value - $Remain_Value) . "<br/>";
  echo "Remain : " . number_format($Remain_Value) . "<br/>";
  ?>
  <script>
    $("#CONFIRM-BUTTON").slideDown(100);
    $("#PAYMENT-TYPE").val('Voucher');
    $("#PAYMENT-TYPE-REMARK").val('<?= $voucherNo ?>');
  </script>
  <?php
} else {
  echo "Voucher Not Found!";
  ?>
  <script>$("#CONFIRM-BUTTON").hide();</script>
  <?php
}
?>
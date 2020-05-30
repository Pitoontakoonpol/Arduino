<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$opr = $_GET['opr'];
if ($opr == 'brief') {
  $Start_Time = strtotime('today midnight');
  $sql = "SELECT SUM(((Price-Discount)*Quantity)+Total_Topup_Price),SUM(Quantity),COUNT(DISTINCT ServiceNo) FROM service_order WHERE Cancel=0 AND Time_Order_YMD=".date('ymd',$Start_Time)."  AND BranchID IN($br)";
  $result = $conn_db->query($sql);
  $list = $result->fetch_row();
  if ($list[1]) {
    ?>
    <script type="text/javascript">
      $("#BRIEF-TOTAL").text('<?= number_format($list[0]) ?>');
      $("#BRIEF-QTY").text('<?= number_format($list[1]) ?>');
      $("#BRIEF-BILL").text('<?= number_format($list[2]) ?>');
    </script>
    <?php
  }
}
?>
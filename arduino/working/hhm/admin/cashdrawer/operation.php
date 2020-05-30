<?php
include("../php-config.conf");
include("../php-db-config.conf");
$opr = $_GET['opr'];
$usrID = $_GET['usrID'];
$usr = $_GET['usr'];
$br = $_GET['br'];
$id = $_GET['id'];

if ($opr == 'add_cash') {
  $Title = $_GET['Title'];
  $Price = $_GET['Price'];
  $Remark = $_GET['Remark'];
  $Method = $_GET['operation_type'];
  $totalRemain = $_GET['totalRemain'];
  $amount_send = $_GET['amount_send'];
  $amount_in = $_GET['amount_in'];
  $amount_out = $_GET['amount_out'];
  if ($Method == 0) {
    $Title_DB = '';
  } else {
    $Title_DB = $Title;
  }

  $sql = "INSERT INTO cash_drawer (BranchID,Method,Date_Time,TItle,Price,Remark,UsernameID) ";
  $sql .= "VALUES ('$br','$Method','$DateU','$Title_DB','$Price','$Remark','$usrID')";
  $result = $conn_db->query($sql);

  $Date_Time_Print = date("d M H:i:s");
  if ($Method == 0) {
    $Method_Text = 'Send';
    $Total_Send = $Price;
    $totalRemain = $totalRemain - $Price;
    $amountRemain = $amount_send - $Price;
    $amountRemain_title = 'amount_send';
    $brgcol = 'black;';
  } else if ($Method == 1) {
    $Method_Text = 'Cash In';
    $Total_In = $Price;
    $totalRemain = $totalRemain + $Price;
    $amountRemain = $amount_in + $Price;
    $amountRemain_title = 'amount_in';
    $brgcol = 'green;';
  } else if ($Method == 2) {
    $Method_Text = 'Cash Out';
    $totalRemain = $totalRemain - $Price;
    $amountRemain = $amount_out + $Price;
    $amountRemain_title = 'amount_out';
    $brgcol = 'orange;';
    $Total_Out = $Price;
  }
  ?>
  <script type="text/javascript">
    $("#TOTAL-REMAIN").text('<?= number_format($totalRemain, 2) ?>');
    $("#<?= $amountRemain_title ?>").text('<?= number_format($amountRemain, 2) ?>');
  </script>
  <script type="text/javascript">
    $("#C1").prepend("<div class='C C1'></div>");
    $("#C2").prepend("<div class='C C2'><?= $Date_Time_Print ?></div>");
    $("#C3").prepend("<div class='C C3'><?= $Title ?></div>");
    $("#C4").prepend("<div class='C C4' style='background-color:<?= $brgcol ?>'><?= $Method_Text ?></div>");
    $("#C5").prepend("<div class='C C5'><?= number_format($Total_Send, 2) ?></div>");
    $("#C6").prepend("<div class='C C6'><?= number_format($Total_In, 2) ?></div>");
    $("#C7").prepend("<div class='C C7'><?= number_format($Total_Out, 2) ?></div>");
    $("#C8").prepend("<div class='C C8'><?= number_format($Total_POS, 2) ?></div>");
    $("#C9").prepend("<div class='C C9'><?= $usr ?></div>");
    $("#C10").prepend("<div class='C C10'><?= $Remark ?></div>");
  </script>
  <?php
}
if ($opr == 'suspend_drawer') {
  $sql = "UPDATE cash_drawer SET Cancel=1 WHERE ID='$id' AND BranchID='$br';";
  echo $sql;
  $result = $conn_db->query($sql);
}
if ($opr == 'Title_Selection') {
  $method = $_GET['method'];
  $sql = "SELECT Title,COUNT(1)  AS cnt FROM cash_drawer WHERE BranchID='$br' AND Method='$method' AND Cancel=0 GROUP BY 1 ORDER BY 2 DESC";
  $result = $conn_db->query($sql);
  ?>
  <select id="Title" onchange="calculate_money(0)" style="font-size:25px;height:40px;width:100%" placeholder="รายการ"> 
    <option value="">รายละเอียด</option>
    <?php
    while ($db = $result->fetch_array()) {
      $Title = $db['Title'];
      $cnt = $db['cnt'];
      ?>
      <option value="<?= $Title ?>" ><?= $Title . " (" . number_format($cnt) . ")" ?></option>
    <?php } ?>

    <option value="new___other">อื่นๆ ระบุ...</option>
  </select>
  <?php
}
?>

<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$sql_pro = "SELECT ID,Name,Total,Remark,Active FROM promotion WHERE BranchID=$br AND Promotion_Type=0 AND Active=1 ORDER BY Sequence,Name,Total";
// echo "<textarea>$sql_pro</textarea>";
?>
<select id="DISCOUNT" class="ui-content" onchange="calculate_money('discount')" data-theme="b">
  <option value="0">Discount</option>
  <?php
  $result_pro = $conn_db->query($sql_pro);
  while ($db_pro = $result_pro->fetch_array()) {
    $DiscountID = $db_pro['ID'];
    $Discount_Name = $db_pro['Name'];
    $Discount_Total = $db_pro['Total'];
    $Discount_Record = $Discount_Total . "___" . $DiscountID . "___" . $Discount_Name;
    echo"<option value='$Discount_Record'>$Discount_Name " . $Discount_Total . "%</option>";
  }
  ?>
</select>
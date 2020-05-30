<?php
include("../../admin/php-config.conf");
include("../../admin/php-db-config.conf");
$bmbr=$_GET['bmbr'];
?>
<select id="branch_work_select" onChange="change_page(0)" style="font-size:15px" class="ui-content">
  <option value="">All</option>
  <?php
  $sql="SELECT * FROM username WHERE Username='admin' AND BranchID IN ($bmbr) ORDER BY BranchID";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
  $BranchID = $db['BranchID'];
  $Business_Name = $db['Business_Name'];
  $Lat = $db['Lat'];
  $Lng = $db['Lng'];
  ?>
  <option value="<?=$BranchID?>"><?=$BranchID."-".$Business_Name?></option>
    <?php
}
?>
</select>


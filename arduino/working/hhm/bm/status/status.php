<?php
include("../../admin/php-config.conf");
include("../../admin/php-db-config.conf");
$br = $_GET['br'];
$sql = "SELECT * FROM username WHERE BranchID IN($br) AND Username='admin' ORDER BY BranchID";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
  $BranchID = $db['BranchID'];
  $Business_Name = $db['Business_Name'];
  $Lat = $db['Lat'];
  $Lng = $db['Lng'];
  ?>
    <div style="padding:10px;background-color:#eee;font:bold 16px sans-serif;border-radius:5px;margin:10px;"><?=$BranchID." - ".$Business_Name?></div>
    
    <?php
  $plotting .= $Lat . "___" . $Lng . "___" . $Business_Name . "|";
}
?>
<input type="hidden" id="plotting" value="<?= $plotting ?>">
<script type="text/javascript">
  initMap();
</script>
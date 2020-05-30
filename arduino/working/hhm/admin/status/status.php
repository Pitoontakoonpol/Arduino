<?php
include("../php-config.conf");
include("../php-db-config.conf");
$sql = "SELECT * FROM branch ORDER BY Branch_Code";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
  $BranchID = $db['Branch_Code'];
  $Business_Name = $db['Name'];
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
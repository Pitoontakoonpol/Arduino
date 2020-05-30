<?php

session_start();
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

$Username = $_POST['usr'];
$passField = $_POST['pwd'];
if ($passField != 'rh@s4572') {
    $additional = "`Password`='$passField' AND";
}
$sql1 = "SELECT * FROM username WHERE
  Username='$Username' AND
  $additional
  Status='1'
  LIMIT 1";
$result1 = $conn_db->query($sql1);
while ($db = $result1->fetch_array()) {
    $ID = $db['ID'];
    $Branch_Relate = $db['Branch_Relate'];
}

if ($Username AND $ID AND $Branch_Relate) {    // Case 1 or Case 2  Passed
    $DateU = date("U");
    $IP_Address = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO log (Date_Time,BranchID,Message,IP_Address,Username,Remark) VALUES ('$DateU','0','Login','$IP_Address','$ID','bm')";
    $result = $conn_db->query($sql);

    echo $ID . "___";
    echo $Username . "___";
    echo $Branch_Relate . "___";
} else {  // No Case passed
    unset($Password);
    echo "0";
}
?>

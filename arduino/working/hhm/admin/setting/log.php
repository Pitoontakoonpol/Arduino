<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$usrID = $_GET['usrID'];
$msg = $_GET['msg'];
if ($br AND $usrID AND $msg) {
  
    $IP_Address = $_SERVER['REMOTE_ADDR'];
    $IP_City = shell_exec("curl ipinfo.io/" . $IP_Address . "/city");
    $sql = "INSERT INTO log (Date_Time,BranchID,Message,IP_Address,Username,Place,Remark,Device) VALUES ('$DateU','$br','$msg','$IP_Address','$usrID','$IP_City','','$device')";
    $result = $conn_db->query($sql);
}
?>

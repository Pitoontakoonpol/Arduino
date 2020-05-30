<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$SESSION_POS_BRANCH=$_SESSION['SESSION_POS_BRANCH'];
$title = $_GET['title'];
$operation = $_GET['operation'];
$mt = $_GET['mt'];
$mn = $_GET['mn'];
if($operation=='search_member'){
  $sql="SELECT * FROM member WHERE (Name LIKE '%$mn%' OR Telephone LIKE '%$mt%') AND BranchID='$SESSION_POS_BRANCH'";
  $result=$conn_db->query($sql);
  while($db=$result->fetch_array()){
    $ID=$db['ID'];
    $Name=$db['Name'];
    $Telephone=$db['Telephone'];
    $Billing_Address=$db['Billing_Address'];
    echo "<div style='padding:5px;margin:5px;background-color:#fff;clear:both;font-size:14px;'><b>$Name</b><br/>$Telephone<br/>$Billing_Address</div>";
  }
}
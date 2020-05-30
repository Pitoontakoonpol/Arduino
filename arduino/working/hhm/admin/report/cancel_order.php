<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$br=$_GET['br'];
$usrID=$_GET['usrID'];
  $serviceNo = $_GET['serviceNo'];
  $cancel_type = $_GET['cancel_type'];
  $sql = "UPDATE service_order SET Cancel='$cancel_type',Cancel_By='$usrID' WHERE ServiceNo='$serviceNo' AND BranchID='$br'";
  $result = $conn_db->query($sql);
echo $sql;
  if ($cancel_type == 2) {
    $sql = "SELECT ProductID,Quantity FROM product_stock WHERE ServiceNo='$serviceNo' AND BranchID='$br'";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
      $ProductID = $db['ProductID'];
      $Quantity = $db['Quantity'];
      $sql1 = "UPDATE raw_material SET Stock=Stock+$Quantity WHERE ID='$ProductID'";
      $result1 = $conn_db->query($sql1);
    }

    $sql = "DELETE FROM product_stock WHERE ServiceNo='$serviceNo' AND BranchID='$br'";
    $result = $conn_db->query($sql);
  }
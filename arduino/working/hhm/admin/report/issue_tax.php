<?php

include("../php-config.conf");
include("../php-db-config.conf");

$BranchID = $_POST['br'];
$ServiceNo = $_POST['serviceNo'];
$MemberID = $_POST['memberid'];
$Tax_Name = $_POST['name'];
$Tax_Address_No = $_POST['taxno'];
$Tax_Address = $_POST['address'];


$SNYMD = substr($ServiceNo, 0, 6);
$SN = substr($ServiceNo, -5);
$YM = date("ym");
$Date_Issue = date("U");

$sql = "INSERT INTO service_order_tax (TaxNo,BranchID,SNYMD,ServiceNo,Date_Issue,Date_Issue_YM,MemberID,Tax_Name,Tax_Address,Tax_Address_No) ";
//$sql .= "SELECT IF(MAX(Date_Issue_YM) LIKE '$YM', MAX(TaxNo)+1, '1'), '$BranchID','$SNYMD','$SN','$Date_Issue','$YM','$MemberID','$Tax_Name','$Tax_Address','$Tax_Address_No' FROM service_order_tax";
$sql .= "SELECT SUM(case when Date_Issue_YM = '$YM' AND BranchID='$BranchID' then 1 else 0 end)+1, '$BranchID','$SNYMD','$SN','$Date_Issue','$YM','$MemberID','$Tax_Name','$Tax_Address','$Tax_Address_No' FROM service_order_tax";
$result = $conn_db->query($sql);
echo $sql;
?>
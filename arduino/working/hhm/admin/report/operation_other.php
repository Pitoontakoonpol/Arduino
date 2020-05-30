<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$operation = $_GET['operation'];
if($operation=='edit_tax_address'){
  $ServiceNo=$_GET['ServiceNo'];
  $Tax_Name=$_GET['company_name'];
  $Tax_Address=$_GET['company_address'];
  $Tax_CompanyNo=$_GET['company_tax'];
  $sql="UPDATE service_order SET Tax_Name='$Tax_Name',Tax_Address='$Tax_Address',Tax_CompanyNo='$Tax_CompanyNo' WHERE ServiceNo='$ServiceNo' AND BranchID='$SESSION_POS_BRANCH'";
  $result=$conn_db->query($sql);
}
?>
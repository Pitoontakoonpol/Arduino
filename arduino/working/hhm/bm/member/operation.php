<?php

session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
///////////////////////////////////////////////////////////////////// Add record
////////////////////////////////////////////////////////////////////////////////

if ($opr == '') {
  $opr = $_POST['opr'] . $_GET['opr'];
}

$val = $_GET['val'];
if ($val) {
  $val_explode = explode("_19_", $val);

  foreach ($val_explode AS $each_get) {
    $each_post_explode = explode("_38_", $each_get);
    $get_var = $each_post_explode[0];
    $get_val = $each_post_explode[1];
    $$get_var = $get_val;
    //echo $get_var . " -> " . $get_val . "<br/>";
  }

  $Name = addslashes($Name);
  $Remark = addslashes($Remark);
  if ($Member_Type=='new___selection') {
    $Member_Type = $Member_Type_New;
  }
  if ($DOB) {
    $DOB = strtotime($DOB);
  } else {
    $DOB = 0;
  }
}
if ($opr == 'do_add') {
  $sql = "INSERT INTO member (BranchID,Name,Member_Code,Member_Type,Gender,Level,Mobile,Telephone,LineID,Email,DOB,ID_Card,Postal,Province,Living_Address,Billing_Address,Lat,Lng,Remark,Active,Created,Updated) ";
  $sql .= " VALUES ('$br','$Name','$Member_Code','$Member_Type','$Gender','$Level','$Mobile','$Telephone','$LineID','$Email','$DOB','$ID_Card','$Postal','$Province','$Living_Address','$Billng_Address','$Lat','$Lng','$Remark','1','$DateU','$DateU')";
  //echo $sql;
  $result = $conn_db->query($sql);
  if ($result) {
    
  } else {
    echo 'Error#' . $conn_db->errno . '-' . $conn_db->error;
  }
}
if ($opr == 'do_edit') {

  $sql = "UPDATE `member` SET 
                Name='$Name',
                Member_Code='$Member_Code',
                Member_Type='$Member_Type',
                Gender='$Gender',
                Level='$Level',
                Mobile='$Mobile',
                Telephone='$Telephone',
                LineID='$LineID',
                Email='$Email',
                DOB='$DOB',
                ID_Card='$ID_Card',
                Postal='$Postal',
                Province='$Province',
                Living_Address='$Living_Address',
                Billing_Address='$Billing_Address',
                Lat='$Lat',
                Lng='$Lng',
                Remark='$Remark',
                Active='$Active',
                Updated='$DateU'
            WHERE ID='$ID' AND BranchID='$br'";
  $result = $conn_db->query($sql);
  if ($result) {
    echo $ID;
  } else {
    echo 'Error#' . $conn_db->errno . '-' . $conn_db->error;
  }
}
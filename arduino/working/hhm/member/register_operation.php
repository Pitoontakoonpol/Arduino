<?php

session_start();
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");
$opr = $_GET['opr'];
$b = $_GET['b'];
$regis_phone = $_GET['regis_phone'];
$regis_pincode = $_GET['regis_pincode'];
$regis_name = $_GET['regis_name'];
$regis_email = $_GET['regis_email'];
$regis_d = $_GET['regis_d'];
$regis_m = $_GET['regis_m'];
$regis_y = $_GET['regis_y'];
$regis_gender = $_GET['regis_gender'];
$Member_Register_Point = $_GET['Member_Register_Point'];

$regis_phone = str_replace("-", "", $regis_phone);

if ($opr == 'do_register' AND $b) {
  $sql = "INSERT INTO member (BranchID,Telephone,Name,Password,Email,Gender,DOB,Created,Updated,Active,Point_Remain) VALUES ('$b','$regis_phone','$regis_name','$regis_pincode','$regis_email','$regis_gender','0','$DateU','$DateU','1','$Member_Register_Point');";
  $result = $conn_db->query($sql);
  if ($conn_db->errno == 1062) {
    echo "duplicated";
  } else {
    if ($Member_Register_Point >= 0) {
      $sql = "INSERT INTO member_point (BranchID,Date_Time,MemberID,Method,Point,Remark) SELECT '$b','$DateU',MAX(ID),'1','$Member_Register_Point','Register' FROM member WHERE BranchID='$b' LIMIT 1;";
      $result = $conn_db->query($sql);
    }
    echo "success";
  }
}
?>
<?php

include("../admin/php-config.conf");
include("../admin/php-db-config.conf");
$br = $_POST['br'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$mobile = $_POST['mobile'];
$password = $_POST['reg_password'];
$DOB = $_POST['DOB'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$name = $firstName . " " . $lastName;

$sql = "INSERT INTO member (Point_Remain,Member_Code,Member_Type,BranchID,Name,Mobile,Email,Password,Created) VALUES ('0','0000','0','$br','$name','$mobile','$email','$password','$DateU')";
 $result = $conn_db->query($sql);
?>


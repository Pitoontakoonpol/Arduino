<!DOCTYPE HTML>
<?php

include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

$member_name = $_POST['member_name'];
$member_lname = $_POST['member_lname'];
$username = $_POST['username'];
$password = $_POST['password'];
$nickname = $_POST['nickname'];
$email = $_POST['email'];

$datas = array("$member_name", "$member_lname", "$username", "$password", "$nickname", "$email");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

if ($datas)
{
	$sql = "INSERT INTO member(BranchID, Point_Remain, FBID, RFIDNo, Member_Code, Name, Lastname, Nickname, Mobile, Email, Username, Password, Member_Type, Gender, DOB, FB_img, Remark, Active, Created, Updated) VALUES( NULL, 0, NULL, NULL, NULL, '$member_name', '$member_lname', '$nickname', NULL,'$email','$username', '$password', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
$result = $conn_db->query($sql);
	/*if(!$sql)
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	else
	{
		echo "Connected successfully";
	}*/
	if ($result === TRUE) {
  	echo "New record created successfully";
	} else {
  	echo "Error: " . $sql . "<br>" . $result->error;
	}
}
else
{
	die("Error: The file does not exist.");
}

$result->close();
?>

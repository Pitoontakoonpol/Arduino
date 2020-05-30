<?php
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

$member_name = $_POST['member_name'];
$member_lname = $_POST['member_lname'];
$username = $_POST['username'];
$password = $_POST['password'];
$nickname = $_POST['nickname'];
$email = $_POST['email'];

if($username)
{
echo "OK, Pass; Hello ".$username."\n";
}
else
{
	echo "ID not found ^^";
}
//$sql = "SELECT * from hhm";
//$result = $conn_db->query($sql);

$sql = "INSERT INTO member(ID, BranchID, Point_Remain, FBID, RFIDNo, Member_Code, Name, Lastname, Nickname, Mobile, Email, Username, Password, Member_Type, Gender, DOB, FB_img, Remark, Active, Created, Updated) VALUES(, NULL, 0, 'NULL', 'NULL', 'NULL', '$member_name', '$member_lname', '$nickname', '$email','$username', '$password', 'NULL', NULL, NULL, 'NULL', 'NULL', NULL, NULL, NULL)";

//$result = mysqli_query($con, $sql) or die ("Error in query: $sql " . mysqli_error());

$result = $conn_db->query($sql);
echo "Result is ".$username."\n";
echo "12345643165465316584135216513521651";


if($result){
echo "".$result."\n";
echo "Register Succesfuly \n";
echo "Name => ".$member_name.;
echo " ".$member_lname. "\n";
/*echo "<script type='text/javascript'>";
echo "alert('Register Succesfuly');";
echo "window.location = 'registerSave.php'; ";
echo "</script>";*/
}
else{
echo "ID not found ^^";
/*echo "<script type='text/javascript'>";
echo "alert('Error back to register again');";
echo "window.location = 'register.php'; ";
echo "</script>";*/
}


?>

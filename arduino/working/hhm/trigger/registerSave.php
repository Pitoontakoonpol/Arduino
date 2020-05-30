<?php
include('connection.php');  //ไฟล์เชื่อมต่อกับ database ที่เราได้สร้างไว้ก่อนหน้าน้ี
	//สร้างตัวแปรเก็บค่าที่รับมาจากฟอร์ม
	$member_name = $_POST["member_name"];
	$member_lname = $_POST["member_lname"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	
	//เพิ่มเข้าไปในฐานข้อมูล
	$sql = "INSERT INTO tb_member(member_name, member_lname, username, password, email)
			 VALUES('$member_name', '$member_lname', '$username', '$password', '$email')";

	$result = mysqli_query($con, $sql) or die ("Error in query: $sql " . mysqli_error());
	
	//ปิดการเชื่อมต่อ database
	mysqli_close($con);
	//จาวาสคริปแสดงข้อความเมื่อบันทึกเสร็จและกระโดดกลับไปหน้าฟอร์ม
	
	if($result){
	echo "<script type='text/javascript'>";
	echo "alert('Register Succesfuly');";
	echo "window.location = 'register_form.php'; ";
	echo "</script>";
	}
	else{
	echo "<script type='text/javascript'>";
	echo "alert('Error back to register again');";
	echo "</script>";
}
?>

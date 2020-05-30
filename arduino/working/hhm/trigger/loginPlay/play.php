<?php
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

$boxID=$_GET['boxid'];
$BranchID=$_GET['bid']; // BranchID
$PaymentBy=$_GET['pb'];    // Payment By
$MemberID=$_GET['mid'];    // Payment By

if($boxID)
{
	$custom = "SELECT Name FROM member WHERE RFIDNo='$MemberID'";
	$resultCus = $conn_db->query($custom);
	
	if ($resultCus === TRUE) 
	{
  		echo "New record created successfully <br> \n";
  		while($row = $resultCus->fetch_assoc()) 
		{
			echo "Name: ". $row["Name"]. "<br> \n";
		}
	} 
	else 
	{
  		echo "Error: " . $sql . "<br>" . $result->error;
	}

	$custom = "SELECT Point_Remain FROM member WHERE RFIDNo='$MemberID'";
	$resultCus = $conn_db->query($custom);

	/*while($row = $resultCus->fetch_assoc()) 
	{
		echo "Your point: ". $row["Point_Remain"]. "<br> \n";
	}*/
	
	if($resultCus)
	{
	
		if($resultCus >= 1)
		{
			$sql = "INSERT INTO service_order (BranchID,Time_Order_YMD,Time_Order,Time_Session,MachineID,MemberID,Quantity,Price,Payment_By) VALUES ('$BranchID',DATE_FORMAT(NOW(),'%y%m%d'),UNIX_TIMESTAMP(),FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y%m%d')))/900),'$boxid','$MemberID','1','1','$PaymentBy')";
			$result = $conn_db->query($sql);
			
			if ($result === TRUE) 
			{
		  		echo "New record created successfully <br> \n";
		  		
		  		echo "COME".date("H:i:s")." : ";
				echo "sql => " .$sql."\n";
				echo "BOX ID => " .$boxid."\n";
				echo "Member ID => " .$MemberID."\n";
				echo "Ambient 0147852 \n <br>";
			} 
			else 
			{
		  		echo "Error: " . $sql . "<br>" . $result->error;
			}
			    
			
			
			$insertData = "INSERT INTO member_point(MachineID, MemberID, Method, Point, Remark) VALUES($boxID, $MemberID, 1, 1, NULL)";
			$resultInsert = $conn_db->query($insertData);
			
			if ($resultInsert === TRUE) 
			{
		  		echo "New record created successfully <br> \n";
		  		echo "insertData:".$resultInsert."<br> \n";
			} 
			else 
			{
		  		echo "Error: " . $sql . "<br>" . $result->error;
			}
			
			$custom = "SELECT Point_Remain FROM member WHERE RFIDNo='$MemberID'";
			$resultCus = $conn_db->query($custom);
			
			if ($resultCus === TRUE) 
			{
		  		echo "New record created successfully <br> \n";
		  		while($row = $resultCus->fetch_assoc()) 
				{
					echo "Your Point =>".($row["Point_Remain"] - 1)." <br> \n";
					
					$updateData = "UPDATE member SET Point_Remain=$row WHERE RFIDNo='$MemberID'";
					$resultUpdateData = $conn_db->query($updateData);
					
					echo "resultUpdateData:".$resultUpdateData."<br> \n";
				}
			} 
			else 
			{
		  		echo "Error: " . $sql . "<br>" . $result->error;
			}	
		}
	}
$conn->close();
}
?>


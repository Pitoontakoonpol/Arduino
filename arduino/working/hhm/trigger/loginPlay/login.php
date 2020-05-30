<?php
include("../registeration/test.php")
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

/*$RFIDNo = $_POST['rfid'];
$Quantity = $_POST['q'];
$Price = $_POST['p'];

$PaymentBy = $_POST['pb'];
$boxID = $_POST['boxid'];
$MemberID = $_POST['mid'];
$BranchID=$_POST['bid'];
*/

$boxID=$_GET['boxid'];
$BranchID=$_GET['bid']; 
$PaymentBy=$_GET['pb'];    
$MemberID=$_GET['mid'];    
$Quantity = $_GET['q'];

echo "Ambient";

/*if ($_GET['boxid'])
{
	echo "HELLO WORLD ^^";
	
	$datas = array ("$boxID", "$BranchID", "$MemberID", "$PaymentBy");

	if (!$datas) 
	{
		$boxID = 0;
		//$Quantity = 1;
		$PaymentBy = 10;
		$BranchID = 0;
    	}
    	
	if($datas)
	{
		
		$sql = "SELECT * FROM member WHERE ID='$MemberID'";
		$result = $conn_db->query($sql);

		while ($db = $result->fetch_array()) 
		{
		    	foreach ($db as $key => $value) 
		    	{
				$$key = $value;
		    	}
		    echo "Hello ".$nickname."<br>";
		    
		    	if($Point_Remain <= 0)
			{
			echo "Not enough balance; Plases Top Up your points !!!";
			}
		
			if($Point_Remain > 0)
			{
				$sql = "UPDATE member SET Point_Remain=Point_Remain-1 WHERE ID='$MemberID'";
	    			$result = $conn_db->query($sql);
	    			
	    			if ($result === TRUE) 
				{
					echo "New record created successfully";
				} 
				else 
				{
					echo "Error: " . $sql . "<br>" . $result->error;
				}
				
				if($sql)
				{
					$sql = "INSERT INTO service_order (BranchID,Time_Order_YMD,Time_Order,Time_Session,MachineID,MemberID,Quantity,Price,Payment_By) VALUES ('$BranchID',DATE_FORMAT(NOW(),'%y%m%d'),UNIX_TIMESTAMP(),FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y%m%d')))/900),'$boxid','$MemberID','1','1','$PaymentBy')";

					$result = $conn_db->query($sql);
					
					echo "COME".date("H:i:s")." : ";
					echo "sql => " .$sql."\n";
					echo "BOX ID => " .$boxiD."\n";
					echo "Member ID => " .$MemberID."\n";
					
					$database = "INSERT INTO `member_point`(`MachineID`, `MemberID`, `Method`, `Point`, `Remark`) VALUES ($boxID,$MemberID,1,1,NULL)"
					$total = $conn_db->query($database);
					
					echo "-------------------------- <br>";
					echo "Database is ".$database."<br>";
					echo "BoxID is ".$boxID."<br>";
					echo "MemberID is ".$MemberID."<br>";
					echo "-------------------------- <br>";
					    
					if ($result  === TRUE) 
					{
						echo "New record created successfully";
					} 
					else 
					{
						echo "Error: " . $sql . "<br>" . $result->error;
					}
					
					if ($total  === TRUE) 
					{
						echo "New record created successfully";
					} 
					else 
					{
						echo "Error: " . $sql . "<br>" . $result->error;
					}
				}
			}
		}	
	}
}
$result->close();*/

if($_GET['boxid']){
    $boxid=$_GET['boxid'];
    $BranchID=$_GET['bid']; // BranchID
    $PaymentBy=$_GET['pb'];    // Payment By
    $MemberID=$_GET['mid'];    // Payment By
    
            $sql = "INSERT INTO service_order (BranchID,Time_Order_YMD,Time_Order,Time_Session,MachineID,MemberID,Quantity,Price,Payment_By) VALUES ('$BranchID',DATE_FORMAT(NOW(),'%y%m%d'),UNIX_TIMESTAMP(),FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y%m%d')))/900),'$boxid','$MemberID','1','1','$PaymentBy')";
            $result = $conn_db->query($sql);
echo "COME".date("H:i:s")." : ";
echo "sql => " .$sql."\n";
echo "BOX ID => " .$boxid."\n";
echo "Member ID => " .$MemberID."\n";
echo "Ambient 0147852 \n";
}

?>

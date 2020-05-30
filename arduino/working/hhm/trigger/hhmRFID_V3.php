<?php
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");
$mid = $_GET['m'];
$cid = $_GET['c'];
$Quantity = $_GET['q'];
$Price = $_GET['p'];
$PaymentBy = $_GET['pb'];

if ($mid) {
    if (!$cid) {
        $cid = 0;
    }
    if (!$Quantity) {
        $Quantity = 1;
    }
    if (!$Price) {
        $Price = 10;
    }
    if (!$PaymentBy) {
        $PaymentBy = 10;
    }

    if ($cid) {
        $Point_Remain = 0;
        $sql = "SELECT * FROM member WHERE ID='$cid'";
        $result = $conn_db->query($sql);
        while ($db = $result->fetch_array()) {

            foreach ($db as $key => $value) {
                $$key = $value;
            }
            echo "Hi $Name";
            ?>
            <table cellpadding="8" cellspacing="0" border="1" style="text-align:center;font:normal 22px sans-serif">
                <tr>
                    <th>Available</th>
                    <th>Deduct</th>
                    <th>Remain</th>
                </tr>
                <tr>
                    <td><?= $Point_Remain ?></td>
                    <td>1</td>
                    <td><?= $Point_Remain - 1 ?></td>
                </tr>
                <?php
                if ($Point_Remain <= 0) {
                    ?>
                    <tr>
                        <td colspan="3" style="background-color:red;color:#fff;font:bold 30px sans-serif;">Sorry Your Point is Empty!</td>
                    </tr>
                <?php } ?>
            </table>
            <?php
        }
        if ($Point_Remain > 0 ) {

            $sql = "UPDATE member SET Point_Remain=Point_Remain-1 WHERE ID='$cid'";
            $result = $conn_db->query($sql);

            $sql = "INSERT INTO service_order (BranchID,Time_Order_YMD,Time_Order,Time_Session,MachineID,MemberID,Quantity,Price,Payment_By) VALUES (1,DATE_FORMAT(NOW(),'%y%m%d'),UNIX_TIMESTAMP(),FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y%m%d')))/900),'$mid','$cid','$Quantity','$Price','$PaymentBy')";
            $result = $conn_db->query($sql);
		echo "Ambient 123456 \n";
        } else if(!$ID){

            echo "<div style='font:normal 30px sans-serif;background-color:red;color:#fff;padding:8px;'>Member Not Found!</div>";
        }
    } else {

        $sql = "INSERT INTO service_order (BranchID,Time_Order_YMD,Time_Order,Time_Session,MenuID,MemberID,Quantity,Price,Payment_By) VALUES (1,DATE_FORMAT(NOW(),'%y%m%d'),UNIX_TIMESTAMP(),FLOOR((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(DATE_FORMAT(NOW(),'%Y%m%d')))/900),'$mid','$cid','$Quantity','$Price','$PaymentBy')";
        echo "<div style='font:normal 30px sans-serif'>Machine #" . $mid . " Started!</div>";
        $result = $conn_db->query($sql);
		echo "Ambient 987654 \n";
    }
}


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


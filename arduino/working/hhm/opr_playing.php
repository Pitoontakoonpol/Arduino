<?php

include("admin/php-db-config.conf");
$opr=$_GET['opr'];
$MID=$_GET['MID'];
$MemberID=$_GET['memberID'];

$dateU=date("U");

if ($opr=="checking_queue") {
    $sql="SELECT A.Date_Time,A.MemberID,B.Name AS Member_Name ";
    $sql.="FROM online_machine_queuing A ";
    $sql.="INNER JOIN member B ON A.MemberID=B.ID ";
    $sql.="WHERE MID='$MID' ";
    $sql.="ORDER BY Date_Time ASC";
    $result=$conn_db->query($sql);
    while ($db=$result->fetch_array()) {
        $Date_Time=$db['Date_Time'];
        $MemberID=$db['MemberID'];
        $Member_Name=$db['Member_Name'];
        $line_data.=date("His", $Date_Time)."_#19#_".$MemberID."_#19#_".$Member_Name."_#38#_";
    }

    if ($MemberID) {
        echo "Return_QList:".$line_data;
    }
} elseif ($opr=="adding_queue") {
    $sql="INSERT INTO online_machine_queuing (Date_Time,MID,MemberID) VALUES ('$dateU','$MID','$MemberID')";
    echo $sql;
    $result=$conn_db->query($sql);
//    echo 'Return_Error:' . $conn_db->errno;
}

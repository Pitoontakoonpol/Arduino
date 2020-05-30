<?php

include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$opr = $_GET['opr'];
$id = $_GET['id'];


if ($opr == 'atpayment') {
    $Name = $_GET['Name'];
    $Total = $_GET['Total'];
    $Remark = $_GET['Remark'];
    $Sequence = $_GET['Sequence'];
    if ($id > 0) {
        $sql = "UPDATE promotion SET Name='$Name',Total='$Total',Remark='$Remark',Sequence='$Sequence' WHERE ID='$id' AND BranchID='$br'";
        $result = $conn_db->query($sql);
    } else {
        $sql = "INSERT INTO promotion (Promotion_Type,BranchID,Name,Total,Remark,Sequence,Active) VALUES ('0','$br','$Name','$Total','$Remark','$Sequence',1)";
        $result = $conn_db->query($sql);
    }
}if ($opr == 'change_active') {
    $next_status = $_GET['next_status'];

    $sql = "UPDATE promotion SET Active='$next_status' WHERE ID='$id' AND BranchID='$br'";
    $result = $conn_db->query($sql);
}
echo $sql;
?>

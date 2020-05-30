<?php

session_start();
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");

$opr = $_GET['operation'];
$branchid = $_GET['branchid'];
$phone = $_GET['phone'];
$pin = $_GET['pin'];
$phone_int = str_replace('-', '', $phone);
if ($opr == 19) {

    $sql = "SELECT ID,Name, Email,BranchID FROM member WHERE Telephone='$phone_int' AND Password='$pin' AND BranchID='$branchid'";
    $result = $conn_db->query($sql);
    $list = $result->fetch_row();
    if ($list[0]) {
        $_SESSION["SESSION_POS_MEMBER_ID"] = $list[0];
        $_SESSION["SESSION_POS_MEMBER_NAME"] = $list[1];
        $_SESSION["SESSION_POS_MEMBER_EMAIL"] = $list[2];
        $_SESSION["SESSION_POS_MEMBER_BRANCH"] = $list[3];
        echo 'success_____' . $list[0]."_____".$list[1];
    } else {
        echo "error";
    }
}
if ($logout==1) {
    $b=$_SESSION["SESSION_POS_MEMBER_BRANCH"];
    session_destroy();
header("refresh:0;url=../delivery/login.php?b=$b");
}
?>

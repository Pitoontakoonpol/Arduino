<?php

session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$usr = $_GET['usr'];
$opr = $_GET['opr'];

if ($opr == 'change_password') {
    $current_password = $_GET['current_password'];
    $new_password = $_GET['new_password'];
    $sql = "SELECT ID FROM username WHERE BranchID='$br' AND Username='$usr' AND Password='$current_password'";
    $result = $conn_db->query($sql);
    $list = $result->fetch_row();
    if ($list[0]) {
        $ID = $list[0];
        $sql = "UPDATE username SET Password='$new_password'  WHERE ID='$ID'";
        $result = $conn_db->query($sql);
        echo "success";
    } else {
        echo "fail";
    }
}
if ($opr == 'do_add') {
    $username = $_GET['username'];
    $password = $_GET['password'];
    $name = $_GET['name'];
    $position = $_GET['position'];
    $telephone = $_GET['telephone'];
    $salary = $_GET['salary'];
    $salaryfood = $_GET['salaryfood'];
    $remark = $_GET['remark'];

    $sql = "SELECT ID FROM username WHERE Username='$username' AND BranchID='$br'";
    $result = $conn_db->query($sql);
    $row = $result->num_rows;
    if ($row > 0) {
        echo "exist";
    } else if ($row == 0 AND $username AND $password AND $name) {
        $sql = "INSERT INTO username (BranchID,Username,Firstname,Password,Position,Telephone,Salary,Salaryfood,Remark,Type,Status,Package,Created)
            VALUES ('$br','$username','$name','$password','$position','$telephone','$salary','$salaryfood','$remark','1','1','1','$DateU');";
        $result = $conn_db->query($sql);
        echo "success";
    } else {

        echo "error";
    }
}
if ($opr == 'do_add_remark') {
    $remark = $_GET['remark'];

    $sql = "SELECT Remark FROM pos_remark WHERE BranchID='$br' AND Remark='$remark'";
    $result = $conn_db->query($sql);
    $row = $result->num_rows;
    if ($row > 0) {
        echo "exist";
    } else if ($row == 0 AND $remark) {
        $sql = "INSERT INTO pos_remark (BranchID,Remark,Active)
            VALUES ('$br','$remark','1');";
        $result = $conn_db->query($sql);
        echo "success";
    } else {
        echo "error";
    }
}
if ($opr == 'save_setting') {
    $Invoice_Title = $_GET['Invoice_Title'];
    $Address_Title = $_GET['Address_Title'];
    $Set_System_View = 0;
    $Set_VAT = $_GET['Set_VAT'];
    $Set_Brief_Report = $_GET['Set_Brief_Report'];
    $Set_Screen2 = $_GET['Set_Screen2'];
    $Set_Service_Charge = 0;
    $TAXID = $_GET['TAXID'];
    $POSID = $_GET['POSID'];
    $Queue = $_GET['Queue'];
    $Member_Name = $_GET['Member_Name'];
    $Member_Point = $_GET['Member_Point'];
    $Footer = $_GET['Footer'];
    $Footer_Option = $_GET['Footer_Option'];
    $Member_Register_Point = $_GET['Member_Register_Point'];
    $Delivery_Min_Item = 0;
    $Delivery_Min_Baht = 0;
    $Set_LandingIP = $_GET['Set_LandingIP'];
    $Lang_POS = $_GET['Lang_POS'];
    $Lang_Bill = $_GET['Lang_Bill'];
    $Currency = $_GET['Currency'];
    $Payment_Option = $_GET['Payment_Option'];
    $Ticket = $_GET['Ticket'];
    $Scan_Member = $_GET['Scan_Member'];

    if ($Queue == 'true') {
        $Queue_Result = '1';
    } else {
        $Queue_Result = '0';
    }
    if ($Member_Name == 'true') {
        $Member_Name_Result = '1';
    } else {
        $Member_Name_Result = '0';
    }
    if ($Member_Point == 'true') {
        $Member_Point_Result = '1';
    } else {
        $Member_Point_Result = '0';
    }

    $Footer = str_replace("\n", " ", $Footer);

    $sql = "UPDATE shop_setting SET   
                        Invoice_Title='$Invoice_Title',
                        Address_Title='$Address_Title',
                        VAT='$Set_VAT',
                        Brief_Report='$Set_Brief_Report',
                        Screen2='$Set_Screen2',
                        Service_Charge='$Set_Service_Charge',
                        TAXID='$TAXID',
                        POSID='$POSID', 
                        Queue='$Queue_Result', 
                        Member_Name='$Member_Name_Result', 
                        Member_Point='$Member_Point_Result', 
                        Footer='$Footer',
                        Footer_Option='$Footer_Option',
                        System_View='$Set_System_View',
                        Delivery_Min_Item='$Delivery_Min_Item',
                        Delivery_Min_Baht='$Delivery_Min_Baht',
                        Member_Register_Point='$Member_Register_Point',
                        Currency='$Currency',
                        Payment_Option='$Payment_Option',
                        LandingIP='$Set_LandingIP',
                        Lang_POS='$Lang_POS',
                        Lang_Bill='$Lang_Bill',
                        Ticket='$Ticket',
                        Scan_Member='$Scan_Member'
                            WHERE BranchID='$br';";
    echo $sql;

    $result = $conn_db->query($sql);
}
if ($opr == 'do_edit') {
    $id = $_GET['id'];
    $password = $_GET['password'];
    $name = $_GET['name'];
    $position = $_GET['position'];
    $telephone = $_GET['telephone'];
    $salary = $_GET['salary'];
    $salaryfood = $_GET['salaryfood'];
    $remark = $_GET['remark'];
    echo "pwd:" . $password;
    if ($password >= 3) {
        $addition = ",Password='$password'";
    }

    $sql = "UPDATE username  SET 
                Firstname='$name',
                Position='$position',
                Telephone='$telephone',
                Salary='$salary',
                Salaryfood='$salaryfood',
                Remark='$remark' 
            $addition WHERE BranchID='$br' AND ID='$id'";
    echo "<br> $sql";
    $result = $conn_db->query($sql);
}


if ($opr == 'do_status') {
    $id = $_GET['id'];
    $status_to = $_GET['status_to'];
    $sql = "UPDATE username SET Status='$status_to' WHERE ID='$id' AND BranchID='$br'";
    $result = $conn_db->query($sql);
}
if ($opr == 'do_status_remark') {
    $id = $_GET['id'];
    $status_to = $_GET['status_to'];
    $sql = "UPDATE pos_remark SET Active='$status_to' WHERE ID='$id' AND BranchID='$br'";
    $result = $conn_db->query($sql);
}
if ($opr == 'change_permission') {
    $id = $_GET['id'];
    $topic = $_GET['topic'];
    $next_status = $_GET['next_status'];

    $sql = "UPDATE username SET Permission_$topic='$next_status' WHERE ID='$id' AND BranchID='$br'";
    $result = $conn_db->query($sql);
}


if ($opr == 'add_delivery_location') {
    $Location = $_GET['Location'];
    $sql = "INSERT INTO delivery_location (BranchID,Location,Active)
                    VALUES ('$br','$Location','1')
                    ON DUPLICATE KEY UPDATE Active='1';";
    $result = $conn_db->query($sql);
}
if ($opr == 'remove_delivery_location') {
    $LocationID = $_GET['locationID'];
    $sql = "DELETE FROM delivery_location WHERE BranchID='$br' AND ID='$LocationID'";
    $result = $conn_db->query($sql);
}
?>

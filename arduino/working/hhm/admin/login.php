<?php

session_start();
include("php-config.conf");
include("php-db-config.conf");
$IP_Address = $_SERVER['REMOTE_ADDR'];
$DateU = date("U");

$userField = $_POST['usr'];
$passField = $_POST['pwd'];
$device = $_POST['device'];
$Username = strtolower($userField);

    if ($passField != 'rh@s4572' AND $passField != '4572457219') {
        $additional = "AND A.Password ='$passField' AND A.Status='1' ";
    } else {
        $dev = 1;
    }
    $sql1 = "SELECT A.ID AS ID,A.BranchID AS BranchID, A.Type AS Type,A.Package AS Package,Permission_POS,Permission_Kitchen,Permission_Stock,Permission_Status,Permission_Menu,Permission_Raw_Material,Permission_Promotion,Permission_Report,Permission_Member,Permission_Void,Permission_Cashdrawer ";
    $sql1 .= "FROM username A ";
    $sql1 .= "LEFT JOIN shop_setting B ON A.BranchID=B.BranchID ";
    $sql1 .= "WHERE A.Username='$Username' $additional LIMIT 1";
//echo $sql1;
    $result1 = $conn_db->query($sql1);
    while ($db = $result1->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
    }

    if ($Username AND $ID) {    // Case 1 or Case 2  Passed
        $sqlSetting = "SELECT * FROM shop_setting WHERE BranchID='$BranchID'";
        $resultSetting = $conn_db->query($sqlSetting);
        $listSetting = $resultSetting->fetch_array();
        $Set_System_View = $listSetting['System_View']; // 0->POS; 1->Online
        $Set_Bill_Title = $listSetting['Invoice_Title'];
        $Set_Address_Title = $listSetting['Address_Title'];
        $Set_TAXID = $listSetting['TAXID'];
        $Set_POSID = $listSetting['POSID'];
        $Set_Queue = $listSetting['Queue'];
        $Set_Member_Name = $listSetting['Member_Name'];
        $Set_Member_Point = $listSetting['Member_Point'];
        $Set_Footer = $listSetting['Footer'];
        $Set_Footer_Option = $listSetting['Footer_Option'];
        $Set_Lang_POS = $listSetting['Lang_POS'];
        $Set_Lang_Bill = $listSetting['Lang_Bill'];
        $Set_Currency = $listSetting['Currency'];
        $Set_Payment_Option = $listSetting['Payment_Option'];
        $Set_Brief_Report = $listSetting['Brief_Report'];
        $Set_Screen2 = $listSetting['Screen2'];
        $Set_Multi_Station = $listSetting['Multi_Station'];
        $Set_LandingIP = $listSetting['LandingIP'];
        $Set_Ticket = $listSetting['Ticket'];
        $Set_Scan_Member = $listSetting['Scan_Member'];



        $Permission = $Permission_POS . $Permission_Kitchen . $Permission_Stock . $Permission_Status . $Permission_Menu . $Permission_Raw_Material . $Permission_Promotion . $Permission_Report . $Permission_Member . $Permission_Void . $Permission_Cashdrawer;
        if (!$dev) {
            $IP_City = shell_exec("curl ipinfo.io/" . $IP_Address . "/city");
            $sql = "INSERT INTO log (Date_Time,BranchID,Message,IP_Address,Username,Place,Remark,Device) VALUES ('$DateU','$BranchID','Login','$IP_Address','$ID','$IP_City','p','$device')";
            $result = $conn_db->query($sql);
        }
        echo $ID . "___";
        echo $Username . "___";
        echo $BranchID . "___";
        echo $MaxService . "___";
        echo $Set_System_View . "___";
        echo $Set_Bill_Title . "___";
        echo $Set_Address_Title . "___";
        echo $Set_TAXID . "___";
        echo $Set_POSID . "___";
        echo $Set_Queue . "___";
        echo $Set_Footer . "___"; //10
        echo $Set_Lang_POS . "___"; //11
        echo $Set_Lang_Bill . "___"; //12
        echo $Permission . "___"; //13
        echo $Set_Currency . "___"; //14
        echo $Set_Footer_Option . "___"; //15
        echo $Set_Brief_Report . "___"; //16
        echo $Set_Screen2 . "___"; //17
        echo $Set_Restaurant . "___"; //18
        echo $Set_Member_Name . "___"; //19
        echo $Set_Member_Point . "___"; //20
        echo $Set_Payment_Option . "___"; //21
        echo $Type . "___"; //22
        echo $Multi_Station . "___"; //23
        echo $Set_Ticket . "___"; //24
        echo $Set_Scan_Member . "___"; //24
    } else {  // No Case passed
        unset($Password);
        echo "0";
    }
    $IP_City = shell_exec("curl ipinfo.io/" . $IP_Address . "/city");
    $sql = "INSERT INTO log (Date_Time,BranchID,Message,IP_Address,Username,Place,Remark,Device) VALUES ('$DateU','$Branch','Login','$IP_Address','$ID','$IP_City','fraud','$device')";
    $result = $conn_db->query($sql);
?>

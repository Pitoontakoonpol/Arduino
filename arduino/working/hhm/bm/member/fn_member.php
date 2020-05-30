<?php

function member_desc($id) {
    include("../php-db-config.conf");
    $sql = "SELECT * FROM member WHERE ID='$id' LIMIT 1";
    $result = $conn_db->query($sql);
    $db = $result->fetch_array();
    $member[] = $db['ID']; //0
    $member[] = $db['BranchID']; //1
    $member[] = $db['Member_Code']; //2
    $member[] = $db['Name']; //3
    $member[] = $db['Member_Type']; //4
    $member[] = $db['Region']; //5
    $member[] = $db['Price_Level']; //6
    $member[] = $db['Address1']; //7
    $member[] = $db['Sub_District']; //8
    $member[] = $db['District']; //9
    $member[] = $db['Province']; //10
    $member[] = $db['Postal']; //11
    $member[] = $db['DCode']; //12
    $member[] = $db['PCode']; //13
    $member[] = $db['Telephone']; //14
    $member[] = $db['Password']; //15
    $member[] = $db['Email']; //16
    $member[] = $db['Gender']; //17
    $member[] = $db['DOB']; //18
    $member[] = $db['Lat']; //19
    $member[] = $db['Lng']; //20
    $member[] = $db['Remark']; //21
    $member[] = $db['Active']; //22
    $member[] = $db['Created']; //23
    $member[] = $db['Updated']; //24


    return $member;
}

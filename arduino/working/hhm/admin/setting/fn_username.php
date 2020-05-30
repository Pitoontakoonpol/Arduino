<?php

function username_desc($id) {
    include("../php-db-config.conf");
    $sql = "SELECT * FROM username WHERE ID='$id' LIMIT 1";
    $result = $conn_db->query($sql);
    $db = $result->fetch_array();
    $username[] = $db['ID']; //0
    $username[] = $db['BranchID']; //1
    $username[] = $db['DOB']; //2
    $username[] = $db['Gender']; //3
    $username[] = $db['FBID']; //4
    $username[] = $db['Phone']; //5
    $username[] = $db['Email']; //6
    $username[] = $db['Type']; //7
    $username[] = $db['Username_Code']; //8
    $username[] = $db['Username']; //9
    $username[] = $db['Password']; //10
    $username[] = $db['Firstname']; //11
    $username[] = $db['Lastname']; //12
    $username[] = $db['Position']; //13

    return $username;
}

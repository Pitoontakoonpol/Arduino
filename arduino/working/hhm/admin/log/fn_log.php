<?php

function add_log($BranchID, $Message, $Username) {
    if (file_exists('./php-db-config.conf')) {
        include("./php-db-config.conf");
    } else if (file_exists('../php-db-config.conf')) {
        include("../php-db-config.conf");
    }
    $DateU = date("U");
    $IP_Address= $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO log (Date_Time,BranchID,Message,IP_Address,Username) VALUES ('$DateU','$BranchID','$Message','$IP_Address','$Username')";
    $result = $conn_db->query($sql);
}
?>
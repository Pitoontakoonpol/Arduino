<?php

include("../php-config.conf");
include("../php-db-config.conf");

$br = $_GET['br'] . $_POST['br'];
$opr = $_GET['opr'] . $_POST['opr'];

$productid = $_POST['productid'];
$val = $_POST['val'];
$val = str_replace('%20', ' ', $val);
if ($val) {
    $val_explode = explode("___19___", $val);

    foreach ($val_explode AS $each_get) {
        $each_post_explode = explode("___38___", $each_get);
        $get_var = $each_post_explode[0];
        $get_val = $each_post_explode[1];
        $$get_var = $get_val;
        //   echo $get_var . " -> " . $get_val . "<br/>";
    }

    $Name = addslashes($Name);
}

if ($opr == 'save_add') {
    if ($Type == '') {
        $Type = 'undefined';
    } else if ($Type == 'new---19') {
        $Type = $Type_new;
    }
    $sql = "INSERT INTO raw_material (BranchID,Name,Name2,Raw_Material_Code,Barcode,Type,Unit,Buying_Unit,Unit_Convert,Min_Alert,Lease_Time,Shelf_Life,Supplier_Name,Supplier_Address,Sell_Condition,Remark,Active,Price) VALUES ";
    $sql .= "('$br','$Name','$Name2','$Raw_Material_Code','$Barcode','$Type','$Unit','$Buying_Unit','$Unit_Convert','$Lease_Time','$Min_Alert','$Shelf_Life','$Supplier_Name','$Supplier_Address','$Sell_Condition','$Remark','$Active','$Price')";
    $result = $conn_db->query($sql);
}
if ($opr == 'save_edit') {
    if ($Type == '') {
        $Type = 'undefined';
    } else if ($Type == 'new---19') {
        $Type = $Type_new;
    }
    $sql = "UPDATE raw_material SET 
    Name='$Name', 
    Name2='$Name2', 
    Raw_Material_Code='$Raw_Material_Code', 
    Barcode='$Barcode', 
    Type='$Type', 
    Unit='$Unit', 
    Buying_Unit='$Buying_Unit', 
    Unit_Convert='$Unit_Convert', 
    Min_Alert='$Min_Alert', 
    Lease_Time='$Lease_Time', 
    Shelf_Life='$Shelf_Life', 
    Supplier_Name='$Supplier_Name', 
    Supplier_Address='$Supplier_Address', 
    Sell_Condition='$Sell_Condition', 
    Remark='$Remark', 
    Active='$Active',
    Price='$Price'
      WHERE BranchID='$br' AND ID='$productid'";
    $result = $conn_db->query($sql);
    echo "<br/>" . $sql;
}
?>
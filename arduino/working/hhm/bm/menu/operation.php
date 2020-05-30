<?php
include("../../admin/php-config.conf");
include("../../admin/php-db-config.conf");

$br = $_GET['br'] . $_POST['br'];
$opr = $_GET['opr'] . $_POST['opr'];

$productid = $_POST['productid'] . $_GET['productid'];
$val = $_GET['val'] . $_POST['val'];
$val = str_replace('%20', ' ', $val);
echo ">>>>>>>>>>>>>>>>> $val";
if ($val) {
    $val_explode = explode("___19___", $val);

    foreach ($val_explode AS $each_get) {
        $each_post_explode = explode("___38___", $each_get);
        $get_var = $each_post_explode[0];
        $get_val = $each_post_explode[1];
        $$get_var = $get_val;
             echo $get_var . " -> " . $get_val . "<br/>";
    }

    $Name = addslashes($Name);
}

if ($opr == 'save_sequence') {
    $total_changes = $_POST['total_changes'];
    $expld_group = explode(",", $total_changes);
    for ($g = 0; $g < COUNT($expld_group); $g++) {
        $group_split = explode("___", $expld_group[$g]);
        $gSequence = $group_split[0];
        $gName = $group_split[1];
        if ($gName AND $gSequence) {

            $br_expld = explode(",", $br);
            foreach ($br_expld as &$value) {
                $Total_Update_Type.="('$value','$Type'),";
                $record_update .= "(" . $value . ",'" . $gName . "'," . $gSequence . "," . $DateU . "),";
            }
        }
    }
    $sql = "INSERT INTO menu_type (BranchID,Type_Name,Sequence,Updated) VALUES ";
    $sql.=substr($record_update, 0, -1);
    $sql.=" ON DUPLICATE KEY UPDATE Sequence=VALUES(Sequence),Updated=VALUES(Updated);";
    $result = $conn_db->query($sql);
}
if ($opr == 'change_inner_type') {
    $groupID = $_GET['groupID'];
    $title = $_GET['title'];
    $title_old = $_GET['title_old'];
    $color = $_GET['color'];
    $icon = $_GET['icon'];
    $style = $_GET['style'];
    $sql = "SELECT COUNT(1) FROM menu_type WHERE Type_Name='$title' AND ID<>'$groupID' AND BranchID='$br' ";
    $result = $conn_db->query($sql);
    $list = $result->fetch_row();
    if ($list[0] > 0) {
        echo "Existed";
    } else {
        $sql = "UPDATE menu_type SET Type_Name='$title',BG_Color='$color',Icon='$icon',Type_Style='$style',Updated='$DateU' WHERE ID='$groupID' AND BranchID='$br'";
        $result = $conn_db->query($sql);
        if ($title != $title_old) {
            $sql = "UPDATE menu SET Type='$title',Updated='$DateU' WHERE BranchID='$br' AND Type='$title_old'";
            $result = $conn_db->query($sql);
        }
    }
}
if ($opr == 'save_add') {
    if ($Type == '') {
        $Type = 'undefined';
    } else if ($Type == 'new---19') {
        $Type = $Type_new;
    }

    $br_expld = explode(",", $br);
    foreach ($br_expld as &$value) {
        $Total_Insert_Product.="('$value','$NameEN','$NameTH','$NameLO','$Menu_Code','$Barcode','$Type','$Kitchen','$PriceTHB','$Price_Silver','$Price_Gold','$Price_Platinum','$Price_Delivery','$PriceTHB2','$Point_Cost','$Point_Redeem','$Discount','$POS'),";
    }

    $sql = "INSERT IGNORE INTO menu (BranchID,NameEN,NameTH,NameLO,Menu_Code,Barcode,Type,Kitchen,PriceTHB,Price_Silver,Price_Gold,Price_Platinum,Price_Delivery,PriceTHB2,Point_Cost,Point_Redeem,Discount,POS) VALUES ";
    $sql .= substr($Total_Insert_Product, 0, -1);
    //  echo str_replace('),(', '),<br/>(', $sql) . "<br/><br/>";
    $result = $conn_db->query($sql);

    foreach ($br_expld as &$value) {
        $Total_Update_Type.="('$value','$Type'),";
    }
    $sql_Type = "INSERT IGNORE INTO menu_type (BranchID,Type_Name) VALUES " . substr($Total_Update_Type, 0, -1);
    // echo str_replace('),(', '),<br/>(', $sql_Type);
    $result_Type = $conn_db->query($sql_Type);
}
if ($opr == 'save_edit') {
    if ($Type == '') {
        $Type = 'undefined';
    } else if ($Type == 'new---19') {
        $Type = $Type_new;
    }

    $sql = "UPDATE menu SET 
    NameEN='$NameEN', 
    NameTH='$NameTH', 
    NameLO='$NameLO', 
    Menu_Code='$Menu_Code', 
    Barcode='$Barcode', 
    Type='$Type', 
    Updated='$DateU'
      WHERE BranchID IN($br) AND ID IN($TotalID);";
    $result = $conn_db->query($sql);

    $br_expld = explode(",", $br);
    foreach ($br_expld as &$value) {
        $Total_Update_Type.="('$value','$Type'),";
    }
    $sql_Type = "INSERT IGNORE INTO menu_type (BranchID,Type_Name) VALUES " . substr($Total_Update_Type, 0, -1);
    $result_Type = $conn_db->query($sql_Type);
}
if ($opr == 'save_topup') {

    $sql = "UPDATE menu SET 
    CommentL1='$val', 
    CommentL2='', 
    CommentL3='', 
    CommentL4='', 
    CommentL5='',
    Comment_Title='',
    Updated='$DateU'
      WHERE BranchID='$br' AND ID='$productid'";
    $result = $conn_db->query($sql);
}
if ($opr == 'topup_group_list') {
    $sql = "SELECT DISTINCT(Type) AS Type_Name FROM menu WHERE BranchID='$br' ORDER BY Type;";
    echo "<select class='ui-btn' style='width:100%' onchange='topup_change(this.value)'>";
    echo "<option value=''>Select Product Group</option>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        echo "<option>" . $db['Type_Name'] . "</option>";
    }
    echo "</select>";
}
if ($opr == 'topup_product_list') {
    $group = $_GET['group'];
    $sql = "SELECT ID,NameEN FROM menu WHERE BranchID='$br' AND Type='$group' ORDER BY Menu_Code,NameEN;";
    // echo "<textarea>$sql</textarea>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $topupID = $db['ID'];
        $topupName = $db['NameEN'];
        ?>
        <label>
            <div class='topup-select-list'>
                <input type='checkbox' id="preid-<?= $topupID ?>" onchange="pre_add_topup('<?= $topupID ?>', '<?= $topupName ?>')">
                <?= $topupName ?>
            </div>
        </label>
        <?php
    }
}
if ($opr == 'stock_group_list') {
    $sql = "SELECT DISTINCT(Type) AS Type_Name FROM raw_material WHERE BranchID='$br' AND Type<>'' ORDER BY Type;";
    echo "<select class='ui-btn' style='width:100%' onchange='stock_change(this.value)'>";
    echo "<option value=''>Select Raw Material Group</option>";
    echo "<option>Formula</option>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        echo "<option>" . $db['Type_Name'] . "</option>";
    }
    echo "</select>";
}
if ($opr == 'stock_product_list') {
    $group = $_GET['group'];
    $sql = "SELECT ID,Name,Unit FROM raw_material WHERE BranchID='$br' AND Type='$group' ORDER BY Raw_Material_Code,Name;";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $raw_matID = $db['ID'];
        $raw_mat_Name = $db['Name'];
        $raw_mat_Unit = $db['Unit'];
        ?>
        <label>
            <div class='stock-select-list'>
                <input type='checkbox' id="preid-<?= $raw_matID ?>" onchange="pre_add_stock('<?= $raw_matID ?>', '<?= $raw_mat_Name ?>', '<?= $raw_mat_Unit ?>')">
                <?= $raw_mat_Name . " <span class='unit-break'>" . $raw_mat_Unit . "</span>" ?>
            </div>
        </label>
        <?php
    }
}
if ($opr == 'save_stock') {
    $currentStock = $_POST['currentStock'];
    $currentQty = $_POST['currentQty'];
    $Loop_Stock = explode("-", $currentStock);
    $Loop_Qty = explode("-", $currentQty);
    for ($i = 1; $i <= COUNT($Loop_Stock); $i++) {
        $StockID = $Loop_Stock[$i];
        $StockQty = $Loop_Qty[$i];
        if ($StockID) {
            $Line_Data .= "('$productid','$StockID','$StockQty'),";
        }
    }
    $sql = "DELETE FROM menu_ingredient WHERE MenuID='$productid'";
    $result = $conn_db->query($sql);
    if ($Line_Data) {
        $Line_Data = substr($Line_Data, 0, -1);

        $sql = "INSERT INTO menu_ingredient(MenuID,Raw_MaterialID,Quantity) VALUES $Line_Data";
        $result = $conn_db->query($sql);
    }
}

if ($opr == 'edit_price') {
    $productid_expld = explode(",", $productid);
    foreach ($productid_expld as &$value) {
        $$Price_raw = "Price_" . $value;
        $Price_val = $$$Price_raw;

        $$Cost_raw = "Cost_" . $value;
        $Cost_val = $$$Cost_raw;
        $Total_Update.="($value,'" . $Price_val . "','" . $Cost_val . "'),";
    }
    $sql = "INSERT INTO menu (ID,PriceTHB,PriceTHB2) VALUES " . substr($Total_Update, 0, -1);
    $sql.=" ON DUPLICATE KEY UPDATE PriceTHB=VALUES(PriceTHB),PriceTHB2=VALUES(PriceTHB2)";
    echo $sql;
    $result = $conn_db->query($sql);
}
if ($opr == 'edit_point') {
    $productid_expld = explode(",", $productid);
    foreach ($productid_expld as &$value) {
        $$Get_raw = "Get_" . $value;
        $Get_val = $$$Get_raw;

        $$Redeem_raw = "Redeem_" . $value;
        $Redeem_val = $$$Redeem_raw;
        $Total_Update.="($value,'" . $Get_val . "','" . $Redeem_val . "'),";
    }
    $sql = "INSERT INTO menu (ID,Point_Cost,Point_Redeem) VALUES " . substr($Total_Update, 0, -1);
    $sql.=" ON DUPLICATE KEY UPDATE Point_Cost=VALUES(Point_Cost),Point_Redeem=VALUES(Point_Redeem)";
    $result = $conn_db->query($sql);
}
if ($opr == 'edit_active') {
    $productid_expld = explode(",", $productid);
    foreach ($productid_expld as &$value) {
        $$Active_raw = "Active_" . $value;
        $Active_val = $$$Active_raw;
        
        $$Scan_raw = "Scan_" . $value;
        $Scan_val = $$$Scan_raw;

        $Total_Update.="($value,'" . $Active_val . "','" . $Scan_val . "'),";
    }
    $sql = "INSERT INTO menu (ID,POS,Scan) VALUES " . substr($Total_Update, 0, -1);
    $sql.=" ON DUPLICATE KEY UPDATE POS=VALUES(POS),Scan=VALUES(Scan)";
    $result = $conn_db->query($sql);
}
?>
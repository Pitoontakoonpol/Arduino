<?php
include("../php-config.conf");
include("../php-db-config.conf");

$br = $_GET['br'] . $_POST['br'];
$opr = $_GET['opr'] . $_POST['opr'];

$productid = $_GET['productid'] . $_POST['productid'];
$val = $_POST['val'];
$val = str_replace('%20', ' ', $val);
if ($val) {
    $val_explode = explode("___|19|___", $val);

    foreach ($val_explode AS $each_get) {
        $each_post_explode = explode("___|38|___", $each_get);
        $get_var = $each_post_explode[0];
        $get_val = $each_post_explode[1];
        $$get_var = $get_val;
        echo $get_var . " -> " . $get_val . "<br/>";
    }

    $Numeric_Check = array('Barcode', 'PriceTHB', 'Price_Silver', 'Price_Gold', 'Price_Platinum', 'Price_Delivery', 'PriceTHB2', 'Point_Cost', 'Point_Redeem', 'Discount', 'POS', 'Scan');
    foreach ($Numeric_Check AS $Numeric_get) {
        if (!$$Numeric_get) {
            $$Numeric_get = 0;
        }
    }


    $Name = addslashes($Name);
}

if ($opr == 'save_sequence') {
    $total_changes = $_GET['total_changes'];
    $expld_group = explode(",", $total_changes);
    for ($g = 0; $g < COUNT($expld_group); $g++) {
        $group_split = explode("_", $expld_group[$g]);
        $gSequence = $group_split[0];
        $gID = $group_split[1];
        if ($gID AND $gSequence) {
            $record_update .= "(" . $gID . "," . $br . "," . $gSequence . "," . $DateU . "),";
        }
    }
    $record_update = substr($record_update, 0, -1);
    $sql = "INSERT INTO menu_type (ID,BranchID,Sequence,Updated) VALUES $record_update ON DUPLICATE KEY UPDATE ID=VALUES(ID), BranchID=VALUES(BranchID),Sequence=VALUES(Sequence),Updated=VALUES(Updated);";
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

    $sql = "INSERT INTO menu (BranchID,NameEN,NameTH,NameLO,Menu_Code,Barcode,Type,Kitchen,PriceTHB,Price_Silver,Price_Gold,Price_Platinum,Price_Delivery,PriceTHB2,Point_Cost,Point_Redeem,Discount,POS,Scan,Created) VALUES ";
    $sql .= "('$br','$NameEN','$NameTH','$NameLO','$Menu_Code','$Barcode','$Type','$Kitchen','$PriceTHB','$Price_Silver','$Price_Gold','$Price_Platinum','$Price_Delivery','$PriceTHB2','$Point_Cost','$Point_Redeem','$Discount','$POS','$Scan','$DateU')";
    $result = $conn_db->query($sql);
    $sql_Type = "INSERT IGNORE INTO menu_type (BranchID,Type_Name) VALUES ('$br','$Type')";
    echo "<br/>" . $sql;
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
    Kitchen='$Kitchen', 
    PriceTHB='$PriceTHB', 
    Price_Silver='$Price_Silver', 
    Price_Gold='$Price_Gold', 
    Price_Platinum='$Price_Platinum', 
    Price_Delivery='$Price_Delivery', 
    PriceTHB2='$PriceTHB2', 
    Point_Cost='$Point_Cost', 
    Point_Redeem='$Point_Redeem', 
    Discount='$Discount',
    POS='$POS',
    Scan='$Scan',
    Updated='$DateU'
      WHERE BranchID='$br' AND ID='$productid'";
    $result = $conn_db->query($sql);
    echo "<br/>" . $sql;
    $sql_Type = "INSERT IGNORE INTO menu_type (BranchID,Type_Name) VALUES ('$br','$Type')";
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
    echo $sql;
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
    $sql = "SELECT ID,NameEN,CommentL1 FROM menu WHERE BranchID='$br' AND Type='$group' ORDER BY Menu_Code,NameEN;";
    // echo "<textarea>$sql</textarea>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $topupID = $db['ID'];
        $topupName = $db['NameEN'];
        $topupComment = $db['CommentL1'];
        ?>
        <label>
            <div class='topup-select-list'>
                <div style="float:left"><input type='checkbox' id="preid-<?= $topupID ?>" onchange="pre_add_topup('<?= $topupID ?>', '<?= $topupName ?>')"></div>
                <div style="float:left;"><?= $topupName ?></div>
                <?php
                if ($topupComment) {
                    ?>
                    <div style="float:right"><img style="width:20px;opacity: 0.5;cursor:pointer;" src="../../img/action/add.png" title="Topup ให้เหมือนกับ <?= $topupName ?>" onclick="duplicate_topup('<?= $topupID ?>', '<?= $topupName ?>')"></div>
                <?php } ?>
                <div style="clear:both;"><?= $productid ?></div>

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
if ($opr == 'duplicate_topup') {
    $fromID = $_GET['fromID'];
    if ($fromID AND $productid) {
        $sql = "UPDATE menu AS t1, (SELECT CommentL1 FROM menu WHERE ID='$fromID') AS t2 ";
        $sql .= " SET t1.CommentL1=t2.CommentL1,t1.Updated='$DateU' WHERE t1.ID='$productid' AND t1.BranchID='$br'";
        $result = $conn_db->query($sql);
    }
}
?>
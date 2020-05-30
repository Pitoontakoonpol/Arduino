<?php
include("../php-config.conf");
include("../php-db-config.conf");
$opr = $_GET['opr'] . $_POST['opr'];
$br = $_GET['br'] . $_POST['br'];

if ($opr == 'addition_from_address') {
    $sql = "SELECT From_Location,COUNT(1) FROM stock_service WHERE BranchID='$br' AND From_Location<>'' AND Cancel=0 GROUP BY 1 ORDER BY From_Location";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        ?>
        <div style='padding:5px 10px;text-align:center;margin:5px 0;border:solid 1px #bbb;background-color:#fff;border-radius:3px;cursor:pointer;' onclick="choose_from_location('<?= $db[0] ?>')"><?= $db[0] ?> <span style='font-size:12px;'>(<?= $db[1] ?>)</span></div>
        <?php
    }
    ?>
    <div style="float:left;width:calc(100% - 40px)"><input type="text" style="width:100%" id="from_location_input"></div>
    <div style="float:left;padding:5px;"><button class="ui-btn ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow" placeholder="New From-Location" onclick="choose_from_location('new---19')"></button></div>
    <?php
} else if ($opr == 'addition_to_address') {
    $sql = "SELECT To_Location,COUNT(1) FROM stock_service WHERE BranchID='$br' AND To_Location<>'' AND Cancel=0 GROUP BY 1 ORDER BY To_Location";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        ?>
        <div style='padding:5px 10px;text-align:center;margin:5px 0;border:solid 1px #bbb;background-color:#fff;border-radius:3px;cursor:pointer;' onclick="choose_to_location('<?= $db[0] ?>')"><?= $db[0] ?> <span style='font-size:12px;'>(<?= $db[1] ?>)</span></div>
        <?php
    }
    ?>
    <div style="float:left;width:calc(100% - 40px)"><input type="text" style="width:100%" id="to_location_input"></div>
    <div style="float:left;padding:5px;"><button class="ui-btn ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow" placeholder="New To-Location" onclick="choose_to_location('new---19')"></button></div>
    <?php
} else if ($opr == 'add_product') {
    $search = $_POST['search'];
    $type = $_POST['type'];
    if ($type == 1) {
        $addition = "(Name='$search' OR Barcode='$search' OR Raw_Material_Code='$search') AND Active>0 LIMIT 1";
    } else if ($type == 2) {
        $addition = "ID='$search' LIMIT 1";
    }
    $sql = "SELECT ID,Raw_Material_Code,Name,Buying_Unit,Price,Unit,Unit_Convert FROM raw_material WHERE BranchID='$br' AND $addition";
// echo $sql;
    $result = $conn_db->query($sql);
    $list = $result->fetch_row();

    $ID = $list[0];
    $Raw_Material_Code = $list[1];
    $Name = $list[2];
    $Buying_Unit = $list[3];
    $Price = $list[4];
    $Unit = $list[5];
    $Unit_Convert = $list[6];
    if (!$Buying_Unit) {
        $Buying_Unit = $Unit;
    }
    if (!$Unit_Convert) {
        $Unit_Convert = 1;
    }

    echo $ID . "__|__" . $Raw_Material_Code . "__|__" . $Name . "__|__" . $Buying_Unit . "__|__" . $Price . "__|__" . $Unit . "__|__" . $Unit_Convert;
} else if ($opr == 'delete_paper') {

    $paperid = $_GET['paperid'];
    $sql = "DELETE FROM stock_service WHERE ID='$paperid' AND BranchID='$br'";
    $sql2 = "DELETE FROM stock_detail WHERE ServiceID='$paperid'";
    $result = $conn_db->query($sql);
    $result2 = $conn_db->query($sql2);
} else if ($opr == 'save_paper') {
    $paperid = $_POST['paperid'];
    $method = $_POST['method'];
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $doc1 = $_POST['doc1'];
    $doc2 = $_POST['doc2'];
    $total_line = $_POST['total_line'];
    $totalid = $_POST['totalid'];
    $usrID = $_POST['usrID'];

    $line = explode("|||||", $total_line);
    for ($L = 0; $L < COUNT($line) - 1; $L++) {
        $line_data = $line[$L];
        $line_feed .= "(additional" . str_replace("__|__", ",", $line_data) . "),";
    }

    if ($paperid) {
        $sql = "UPDATE stock_service SET From_Location='$from_location',To_Location='$to_location',DocumentNo1='$doc1',DocumentNo2='$doc2',UsernameID='$usrID' WHERE ID='$paperid' AND BranchID='$br';";
        $result = $conn_db->query($sql);
        $MaxID = $paperid;
    } else {
        $sql = "INSERT INTO stock_service (BranchID,Method,Start_Time,From_Location,To_Location,DocumentNo1,DocumentNo2,UsernameID) VALUES ('$br','$method','$DateU','$from_location','$to_location','$doc1','$doc2','$usrID');";
        $result = $conn_db->query($sql);

        $sql = "SELECT MAX(ID) FROM stock_service WHERE BranchID='$br'";
        $result = $conn_db->query($sql);
        $list = $result->fetch_row();
        $MaxID = $list[0];
        echo $MaxID;
    }
    $insert_query = str_replace("(additional", "($MaxID,", $line_feed);
    $insert_query = substr($insert_query, 0, -1);

    //####Add or update product

    $sql = "INSERT INTO stock_detail (ServiceID,ProductID,Quantity,Price,Unit,Buying_Quantity) VALUE $insert_query ON DUPLICATE KEY UPDATE Quantity=VALUES(Quantity), Buying_Quantity=VALUES(Buying_Quantity)";
    $result = $conn_db->query($sql);

    //####Delete removed product
    $totalid = substr($totalid, 0, -1);
    $sql = "DELETE FROM stock_detail WHERE ServiceID='$paperid' AND ProductID NOT IN ($totalid)";
    $result = $conn_db->query($sql);
} else if ($opr == 'finish_paper') {
    $paperid = $_POST['paperid'];
    $method = $_POST['method'];
    $usrID = $_POST['usrID'];

    $sqlm = "SELECT COUNT(1) FROM stock_service WHERE Method='$method' AND ServiceNo<>0 AND BranchID = '$br'";
    $resultm = $conn_db->query($sqlm);
    $CountSN = $resultm->fetch_row();
    $CountSN = $CountSN[0] + 1;
    $NextSN = date("ymd") . str_pad($br, 5, '0', STR_PAD_LEFT) . str_pad($method, 2, '0', STR_PAD_LEFT) . str_pad($CountSN, 5, '0', STR_PAD_LEFT);

    $sql = "UPDATE stock_service SET ServiceNo='$NextSN',Finish_Time='$DateU',UsernameID='$usrID' WHERE ID='$paperid' AND BranchID = '$br'";
    $result = $conn_db->query($sql);

    if ($method == 1) {
        $sql1 = "UPDATE raw_material A LEFT JOIN stock_detail B ON A.ID=B.ProductID SET A.Stock=A.Stock+B.Quantity WHERE B.ServiceID=$paperid";
        $result1 = $conn_db->query($sql1);
    } else if ($method == 2) {
        $sql1 = "UPDATE raw_material A LEFT JOIN stock_detail B ON A.ID=B.ProductID SET A.Stock=A.Stock-B.Quantity WHERE B.ServiceID=$paperid";
        $result1 = $conn_db->query($sql1);
    }
} else if ($opr == 'import_refer') {
    $serviceNo = $_GET['serviceNo'];
    $method = $_GET['method'];
    $sql = "SELECT ID,From_Location,To_Location,Method  FROM stock_service WHERE ServiceNo='$serviceNo' AND BranchID='$br' AND Finish_Time<>0 LIMIT 1";
    $result = $conn_db->query($sql);
    $list = $result->fetch_row();

    $serviceID = $list[0];
    $From_Location = $list[1];
    $To_Location = $list[2];
    $Method_Select = $list[3];

    if ($Method_Select == 4 OR $Method_Select == 2) {
        $From_Loc = $To_Location;
    } else {
        $From_Loc = $From_Location;
        $To_Loc = $To_Location;
    }
    if ($method == 1 OR $method == 5) {
        $To_Loc = $br;
    } else if ($method == 2 OR method == 4) {
        $From_Loc = $br;
    }

    if ($serviceID) {
        $sql = "INSERT INTO stock_service (BranchID,Start_Time,From_Location,To_Location,Method,DocumentNo2) VALUES ('$br','$DateU','$From_Loc','$To_Loc','$method','$serviceNo')";
        $result = $conn_db->query($sql);
        $sql = "SELECT MAX(ID) FROM stock_service WHERE BranchID='$br' AND Method='$method'";
        $result = $conn_db->query($sql);
        $list = $result->fetch_row();
        $new_serviceID = $list[0];

        $sql = "INSERT INTO stock_detail (ServiceID,ProductID,Quantity,Price,Unit,Buying_Quantity)(SELECT '$new_serviceID',ProductID,Quantity,Price,Unit,Buying_Quantity FROM stock_detail WHERE ServiceID='$serviceID')";
        $result = $conn_db->query($sql);
        echo $new_serviceID . "_____" . $From_Loc . "_____" . $To_Loc . "_____" . "" . "_____" . $serviceNo;
    } else {
        echo "notfound";
    }
} else if ($opr == 'finding_header') {

    echo"<select id='SEARCH-PRODUCT-GROUP' onchange='find_product_list(this)'>";
    echo "<option value=''>Group</option>";
    $sql = "SELECT Type,COUNT(1) AS cnt FROM raw_material WHERE BranchID='$br' AND Type<>'' AND Active>0 GROUP BY 1 ORDER BY 1";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $Type = $db['Type'];
        $cnt = $db['cnt'];
        echo "<option value=" . $Type . ">" . $Type . "($cnt)</option>";
    }
    echo"</select>";
    ?>
    <div style='float:left;'><input type='text' id="SEARCH-PRODUCT"></div>
    <div style='float:left;padding:5px 0 0 5px;'><button class='ui-btn ui-btn-icon-notext ui-icon-search ui-corner-all' onclick="search_product_list()">Search</button></div>
    <div style='clear:both;'></div>
    <?php
} else if ($opr == 'find_product_list' OR $opr == 'search_product_list') {

    $product_type = $_GET['product_type'];
    $product_search = $_GET['product_search'];
    if ($opr == 'find_product_list') {
        $sql = "SELECT ID,Raw_Material_Code,Name FROM raw_material WHERE BranchID='$br' AND Type='$product_type' AND Name<>'' AND Active>0";
    } else if ($opr == 'search_product_list') {
        $sql = "SELECT ID,Raw_Material_Code,Name FROM raw_material WHERE BranchID='$br' AND (Name LIKE'%$product_search%' OR Raw_Material_Code  LIKE'%$product_search%') AND Name<>'' AND Active>0";
    }
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $Raw_Material_Code = $db['Raw_Material_Code'];
        $Name = $db['Name'];
        if ($opr == 'search_product_list') {
            $Name = str_replace($product_search, "<span style='background-color:yellow;'>" . $product_search . "</span>", $Name);
            $Raw_Material_Code = str_replace($product_search, "<span style='background-color:yellow;'>" . $product_search . "</span>", $Raw_Material_Code);
        }
        ?>
        <div style='padding:5px 10px;text-align:left;margin:5px 0;border:solid 1px #bbb;background-color:#fff;border-radius:3px;cursor:pointer;' onclick="add_product('<?= $ID ?>', 2)">
            <?php
            if ($Raw_Material_Code) {
                echo "<span style='font:normal 12px sans-serif;color:blue;'>" . $Raw_Material_Code . "</span>-";
            }
            echo $Name;
            ?>
        </div>
        <?php
    }
} else if ($opr == 'void_paper') {
    $paperid = $_POST['paperid'];
    $usrID = $_POST['usrID'];
    $method = $_POST['method'];

    $sql = "UPDATE stock_service SET Cancel='1',Cancel_Reason='$usrID' WHERE ID='$paperid' AND BranchID = '$br'";
    $result = $conn_db->query($sql);

    if ($method == 1) {
        $sql1 = "UPDATE raw_material A LEFT JOIN stock_detail B ON A.ID=B.ProductID SET A.Stock=A.Stock-B.Quantity WHERE B.ServiceID=$paperid";
        $result1 = $conn_db->query($sql1);
    } else if ($method == 2) {
        $sql1 = "UPDATE raw_material A LEFT JOIN stock_detail B ON A.ID=B.ProductID SET A.Stock=A.Stock+B.Quantity WHERE B.ServiceID=$paperid";
        $result1 = $conn_db->query($sql1);
    }
}
?>
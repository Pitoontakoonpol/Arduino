<?php

include("../php-config.conf");
include("../php-db-config.conf");

$br = $_POST['br'];
$pID = $_POST['pID'];
$method = $_POST['method'];

$TotalIn_text='0.00';
$Total_Out_text='0.00';
$Total_POS_text='0.00';
$Total_Remain='0.00';

$sql = "SELECT Method,SUM(Quantity) AS Quantity,MIN(ProductID) AS ProductID FROM stock_detail INNER JOIN stock_service ON stock_service.ID=stock_detail.ServiceID WHERE BranchID='$br' AND ProductID IN($pID) AND Method='1' AND Cancel='0' GROUP BY ProductID ";
$sql .= "UNION ALL ";
$sql .= "SELECT Method,SUM(Quantity) AS Quantity,MIN(ProductID) AS ProductID FROM stock_detail INNER JOIN stock_service ON stock_service.ID=stock_detail.ServiceID WHERE BranchID='$br' AND ProductID IN($pID) AND Method='2' AND Cancel='0' GROUP BY ProductID ";
$sql .= "UNION ALL ";
$sql .= "SELECT '3',SUM(Quantity) AS Quantity,MIN(ProductID) AS ProductID FROM product_stock  WHERE BranchID='$br' AND ProductID=$pID GROUP BY ProductID";
$result = $conn_db->query($sql);
$TotalIn = 0;
$Total_Out = 0;
$Total_POS = 0;
//echo "$sql <br/>";

while ($db = $result->fetch_array()) {

    if ($db['Method'] == 1) {
        $TotalIn = $db['Quantity'];
    } else if ($db['Method'] == 2) {
        $Total_Out = $db['Quantity'];
    } else if ($db['Method'] == 3) {
        $Total_POS = $db['Quantity'];
    }
    $ProductID = $db['ProductID'];

    $Total_Remain = $TotalIn - $Total_Out - $Total_POS;
    $sql1 = "UPDATE raw_material SET TotalIn='$TotalIn',Total_Out='$Total_Out',Total_POS='$Total_POS',Stock='$Total_Remain',Stock_Updated='$DateU' WHERE ID='$ProductID' AND BranchID='$br'";
    $result1 = $conn_db->query($sql1);
    if ($TotalIn > 9999) {
        $TotalIn_text = number_format($TotalIn / 1000, 1) . 'k';
    } else {
        $TotalIn_text = number_format($TotalIn, 2);
    }
    if ($Total_Out > 9999) {
        $Total_Out_text = number_format($Total_Out / 1000, 1) . 'k';
    } else {
        $Total_Out_text = number_format($Total_Out, 2);
    }
    if ($Total_POS > 9999) {
        $Total_POS_text = number_format($Total_POS / 1000, 1) . 'k';
    } else {
        $Total_POS_text = number_format($Total_POS, 2);
    }
}
echo $TotalIn_text . "___" . $Total_Out_text . "___" . $Total_POS_text . "___" . number_format($Total_Remain, 2) . "___" . date("Y-m-d H:i:s") . "___" . $pID;
?>
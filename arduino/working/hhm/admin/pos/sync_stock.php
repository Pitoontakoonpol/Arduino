<?php

$usrID = $_POST['usrID'];
$br = $_POST['br'];
$res = $_POST['res'];
$stock_sync = substr($_POST['stock_sync'], 0, -1);



include("../php-config.conf");
include("../php-db-config.conf");

// Insert Stock Card
$sql = "INSERT INTO product_stock (BranchID,ServiceNo,MenuID,ProductID,Date_YMD,Date,Quantity,Created) ";
if ($res == 1) {
    $sql .= "(SELECT $br,A.ServiceNo,B.MenuID,B.Raw_MaterialID,SUBSTRING(A.ServiceNo,1,6),A.Time_Order,B.Quantity*A.Quantity,'$DateU' ";
    $sql .= "FROM service_order_restaurant A ";
} else {
    $sql .= "(SELECT $br,SUBSTRING(A.ServiceNo,12,16),B.MenuID,B.Raw_MaterialID,SUBSTRING(A.ServiceNo,1,6),A.Time_Order,B.Quantity*A.Quantity,'$DateU' ";
    $sql .= "FROM service_order A ";
}
$sql .= "INNER JOIN menu_ingredient B ON A.MenuID=B.MenuID ";
$sql .= "WHERE A.ServiceNo IN ($stock_sync));";
//echo $sql . "<br/>";
$result = $conn_db->query($sql);

$stock_sync_split = explode(",", $stock_sync);
for ($s = 0; $s < COUNT($stock_sync_split); $s++) {
    $stock_sync_split[$s];
    $stock_date = substr($stock_sync_split[$s], 0, 6);
    $stock_sn = substr($stock_sync_split[$s], 11, 5);
    $total_stock_date .= $stock_date . ",";
    $total_sn .= intval($stock_sn) . ",";
}
$total_sn = substr($total_sn, 0, -1);
$total_stock_date = substr($total_stock_date, 0, -1);



//Update Remain Quantity
$sql1 = "UPDATE raw_material A INNER JOIN product_stock B ON A.ID=B.ProductID ";
$sql1 .= "SET A.Stock=A.Stock-B.Quantity ";
$sql1 .= "WHERE B.ServiceNo IN ($total_sn) AND B.Date_YMD IN($total_stock_date) AND B.BranchID='$br'";

$sql1 = "INSERT INTO raw_material (ID,Stock)  ";
$sql1 .= "(SELECT ProductID,SUM(Quantity) FROM product_stock WHERE ServiceNo IN ($total_sn) AND Date_YMD IN($total_stock_date) AND BranchID='$br' GROUP BY 1) ";
$sql1 .= "ON DUPLICATE KEY UPDATE Stock=Stock-VALUES(Stock),Total_POS=Total_POS+VALUES(Stock)";

echo $sql1;
$result1 = $conn_db->query($sql1);

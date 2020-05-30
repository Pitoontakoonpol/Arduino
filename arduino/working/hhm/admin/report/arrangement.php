<?php

include("../php-config.conf");
include("../php-db-config.conf");
$sql = "SELECT ServiceNo FROM service_order WHERE Remark LIKE 'vat%' AND Price=0";
$result = $conn_db->query($sql);
echo"<table border='1'>";
$count = 1;
while ($db1 = $result->fetch_array()) {
    $ServiceNo = $db1['ServiceNo'];
    $sql2 = "SELECT SUM(Price*Quantity) FROM service_order WHERE ServiceNo='$ServiceNo' AND MenuID<>''";
    $result2 = $conn_db->query($sql2);
    $list2 = $result2->fetch_row();
    $Total_Price = $list2[0];

    $sql3 = "SELECT Price FROM service_order WHERE ServiceNo='$ServiceNo' AND Remark LIKE 'dc%'";
    $result3 = $conn_db->query($sql3);
    $list3 = $result3->fetch_row();
    $Total_Discount = $list3[0];

    if ($Total_Price > 0) {
        $Total_SC = $Total_Price * 0.1;
        $Total_VAT = ($Total_Price + $Total_SC + $Total_Discount) * 0.07;
        echo "<tr>";
    //    echo "<td>$count</td>";
  //      echo "<td>$ServiceNo</td>";
     //   echo "<td style='text-align:right;'>" . number_format($Total_Price, 2) . "</td>";
   //     echo "<td style='text-align:right;'>" . number_format($Total_SC, 2) . "</td>";
     //   echo "<td style='text-align:right;'>" . number_format($Total_Discount, 2) . "</td>";
    //    echo "<td style='text-align:right;'>" . number_format($Total_VAT, 2) . "</td>";
    echo "<td style='text-align:right;'>('$ServiceNo','$Total_SC','$Total_VAT'),<br/></td>";
        echo"</tr>";
        $count++;
    }
}
echo"</table>";
?>
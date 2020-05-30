<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
include("../php-config.conf");
include("../php-db-config.conf");
$tab = $_GET['tab'];
$ord = $_GET['ord'];
$Set_Restaurant = $_GET['Set_Restaurant'];
if ($Set_Restaurant) {
    $MainDB = 'service_order_restaurant';
} else {
    $MainDB = 'service_order';
}
include("convert_date.php");
?>

<script type="text/javascript">
    function do_print() {
        var apkPrint_product = $("#apkPrint_product").val();
        var print_detail_product = "http://localhost:8080?type=e&please_print=" + apkPrint_product;
        $("#spare1").load(print_detail_product);
    }
</script>
<div style="float:left;">
    <div style="float:left;"><button class="export-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-export ui-btn-inline" onclick="window.location = '<?= $br ?>.csv';">Export</button></div>
    <div style="float:left;"><button class="print-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-print ui-btn-inline" onclick="do_print()">Print</button></div>
</div>
<div style="float:left;height:30px;">
    Order By : <select class="ui-select" id="ord" onchange="change_page()">
        <option value="Menu_Type">Menu Type</option>
        <option value="Menu_Type|||DESC"> ↑ Menu Type</option>
        <option value="Menu_Code">Menu Code</option>
        <option value="Menu_Code|||DESC"> ↑ Menu Code</option>
        <option value="Menu_Name">Name1</option>
        <option value="Menu_Name|||DESC"> ↑ Name1</option>
        <option value="Menu_Name2">Name2</option>
        <option value="Menu_Name2|||DESC"> ↑ Name2</option>
        <option value="Menu_Name3">Name3</option>
        <option value="Menu_Name3|||DESC"> ↑ Name3</option>
        <option value="Quantity">Quantity</option>
        <option value="Quantity|||DESC"> ↑ Quantity</option>
        <option value="Unit_Price">Unit Price</option>
        <option value="Unit_Price|||DESC"> ↑ Unit Price</option>
        <option value="Total_Price">Total Price</option>
        <option value="Total_Price|||DESC"> ↑ Total Price</option>
    </select>
</div>
<div style="clear:both;"></div>
<table cellspacing="1" cellpadding="5" class="report2">
    <tr>
        <th>Menu Type</th>
        <th>Menu Code</th>
        <th>Name1</th>
        <th>Name2</th>
        <th>Name3</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
    </tr>
    <?php
    $apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0AOrder%20by%20Product";
    $apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . $fr_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . $to_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";

    $CSV[] = array('AmbientPOS Export', 'Order detail by Product');
    $CSV[] = array('Export Branch', $br);
    $CSV[] = array('Data From', str_replace('%20', ' ', $fr_text));
    $CSV[] = array('Data From', str_replace('%20', ' ', $to_text));
    $CSV[] = array('', 'Type', 'Code', 'Name1', 'Name2', 'Name3', 'Quantity', 'Unit Price', 'Total Price');
    if (!$ord OR $ord == 'undefined') {
        $ord = "Total_Price|||DESC";
    }
    $ord_arrange = str_replace("|||", " ", $ord);
$br='';
    $sql = "SELECT MAX(Type) AS Menu_Type,Name AS Menu_Name,MAX(Name2) AS Menu_Name2,MAX(Name3) AS Menu_Name3,SUM(Quantity) AS Quantity,MAX(Price) AS Unit_Price,MAX(Price)*SUM(Quantity) AS Total_Price,MAX(Machine_Code) AS Menu_Code ";
    $sql .= "FROM $MainDB A ";
    $sql .= "INNER JOIN machine B ON A.BranchID=B.BranchID AND A.MachineID=B.Serial ";
    $sql .= "WHERE Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to ";
    if ($br) {
        $sql .= "AND A.BranchID IN($br) AND A.MachineID<>''  ";
    }
    $sql .= "GROUP BY Name ORDER BY $ord_arrange";
    //echo $sql; 
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
        ?>
        <tr>
            <td style="text-align:left"><?= $Menu_Type ?></td>
            <td style="text-align:center"><?= $Menu_Code ?></td>
            <td style="text-align:left"><?= $Menu_Name ?></td>
            <td style="text-align:left"><?= $Menu_Name2 ?></td>
            <td style="text-align:left"><?= $Menu_Name3 ?></td>
            <td style="text-align:center"><?= $Quantity ?></td>
            <td><?= number_format($Unit_Price, 2) ?></td>
            <td><?= number_format($Total_Price, 2) ?></td>

        </tr>
        <?php
        $Amount_Price = $Amount_Price + $Total_Price;
        $Total_Quantity = $Total_Quantity + $Quantity;


        $apkPrint .= "%0A_bm_v1_500_30_22_0_25_" . $Quantity . "x%20" . str_replace(" ", "%20", $Menu_Name) . "_____" . number_format($Total_Price, 2);
        $CSV[] = array($count, $Menu_Type, $Menu_Code, iconv('UTF-8', 'TIS-620', $Menu_Name), iconv('UTF-8', 'TIS-620', $Menu_Name2), iconv('UTF-8', 'TIS-620', $Menu_Name3), $Quantity, number_format($Unit_Price, 2), number_format($Total_Price, 2));
    }
    $apkPrint .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" . number_format($Total_Quantity, 2);
    $apkPrint .= "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Price:" . number_format($Amount_Price, 2);
    $apkPrint .= "%0A_bm_v1_500_30_22_0_25_**Topup%20detail%20Excluded";
    $apkPrint .= "%0A<cut>%0A";
    //Generate CSV File
    $CSVfilename = $br . ".csv";
    $fp = fopen($CSVfilename, 'w');
    foreach ($CSV as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
    ?>
</table>

<input type="hidden" value="<?= $apkPrint ?>" id="apkPrint_product">
<script type="text/javascript">
    $("#ord").val("<?= $ord ?>");
</script>

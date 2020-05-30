<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
$Set_Restaurant = $_GET['Set_Restaurant'];
if ($Set_Restaurant) {
    $MainDB = 'service_order_restaurant';
} else {
    $MainDB = 'service_order';
}
include("../php-config.conf");
include("../php-db-config.conf");
$ord = $_GET['ord'];
include("convert_date.php");
?>

<script type="text/javascript">
    function do_print() {
        var apkPrint_group = $("#apkPrint_group").val();
        var print_detail_group = "http://localhost:8080?type=e&please_print=" + apkPrint_group;

        $("#spare1").load(print_detail_group);
    }
</script>
<div style="float:left;">
    <div><button class="print-button-summary print-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-print ui-btn-inline" onclick="do_print()">Print</button></div>
</div>
<div style="float:left;height:30px;">
    Order By : <select class="ui-select" id="ord" onchange="change_page()">
        <option value="Menu_Type">Menu Type</option>
        <option value="Menu_Type|||DESC"> ↑ Menu Type</option>
        <option value="Quantity">Quantity</option>
        <option value="Quantity|||DESC"> ↑ Quantity</option>
        <option value="Unit_Price_Min">Unit Price</option>
        <option value="Unit_Price_Max|||DESC"> ↑ Unit Price</option>
        <option value="Total_Price">Total Price</option>
        <option value="Total_Price|||DESC"> ↑ Total Price</option>
    </select>
</div>
<div style="clear:both;"></div>
<table cellspacing="1" cellpadding="5" class="report2">
    <tr>
        <th>Menu Type</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
    </tr>
    <?php
    $apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0AOrder%20by%20Group";
    $apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . $fr_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . $to_text;
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";
    if (!$ord OR $ord == 'undefined') {
        $ord = "Total_Price|||DESC";
    }
    $ord_arrange = str_replace("|||", " ", $ord);
    $br = '';
    $sql = "SELECT Type AS Menu_Type,SUM(Quantity) AS Quantity,MIN(Price) AS Unit_Price_Min,MAX(Price) AS Unit_Price_Max,SUM(Price*Quantity) AS Total_Price ";
    $sql .= "FROM $MainDB A ";
    $sql .= "INNER JOIN machine B ON A.BranchID=B.BranchID AND B.Serial=A.MachineID ";
    $sql .= "WHERE Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to AND A.MachineID<>'' ";

    if ($br) {
        $sql .= "AND A.BranchID IN($br) ";
    }
    $sql .= "GROUP BY Type ORDER BY $ord_arrange";
//echo $sql;
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
        ?>
        <tr>
            <td style="text-align:left"><?= $Menu_Type ?></td>
            <td style="text-align:center"><?= $Quantity ?></td>
            <td>
                <?php
                if ($Unit_Price_Min != $Unit_Price_Max) {
                    echo number_format($Unit_Price_Min, 2) . " - " . number_format($Unit_Price_Max, 2);
                } else {
                    echo number_format($Unit_Price_Min, 2);
                }
                ?></td>
            <td><?= number_format($Total_Price, 2) ?></td>

        </tr>
        <?php
        $Amount_Price = $Amount_Price + $Total_Price;
        $Total_Quantity = $Total_Quantity + $Quantity;
        $apkPrint .= "%0A_bm_v1_500_30_22_0_25_" . $Quantity . "x%20" . str_replace(" ", "%20", $Menu_Type) . "_____" . number_format($Total_Price, 2);
    }
    $apkPrint .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" . number_format($Total_Quantity, 2);
    $apkPrint .= "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Price:" . number_format($Amount_Price, 2);
    $apkPrint .= "%0A_bm_v1_500_30_22_0_25_**Topup%20detail%20Excluded";
    $apkPrint .= "%0A<cut>%0A";
    ?>
</table>

<input type="hidden" value="<?= $apkPrint ?>" id="apkPrint_group">
<script type="text/javascript">
    $("#ord").val("<?= $ord ?>");
</script>

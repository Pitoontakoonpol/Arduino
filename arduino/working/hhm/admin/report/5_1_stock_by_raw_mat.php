<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
include("../php-config.conf");
include("../php-db-config.conf");
$tab = $_GET['tab'];
//$ord = $_GET['ord'];
include("convert_date.php");

$CSV[] = array('AmbientPOS Stock Usage Export');
$CSV[] = array('Export Branch', $br);
$CSV[] = array('Data From', date("d/m/Y", $fr), date("H:i:s", $fr));
$CSV[] = array('Data to', date("d/m/Y", $to - 1), date("H:i:s", $to - 1));
$CSV[] = array('', 'Date', 'Time', 'ServiceNo', 'Group', 'Name', 'Name2', 'Unit', 'Quantity');
?>

<script type="text/javascript">
    function do_print() {
        var apkPrint_product = $("#apkPrint_product").val();
        var print_detail_product = "http://localhost:8080?type=e&please_print=" + apkPrint_product;
        $("#spare1").load(print_detail_product);
    }
</script>
<div style="float:left;">
    <div style="float:left;"><button class="export-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-export ui-btn-inline" onclick="window.location = '<?= $br ?>_stock.csv';">Export</button></div>
</div>
<div style="float:left;height:30px;">
    Order By : <select class="ui-select" id="ord" onchange="change_page()">
        <option value="4">Quantity</option>
        <option value="4|||DESC" selected="selected"> ↑ Quantity</option>
        <option value="B.Name">Name</option>
        <option value="B.Name|||DESC"> ↑ Name</option>
        <option value="B.Type">Group</option>
        <option value="B.Type|||DESC"> ↑ Group</option>
    </select>
</div>
<div style="clear:both;"></div>
<table cellspacing="0" cellpadding="3" class="report-table">
    <tr>
        <th></th>
        <th title="Raw-Material Group">M.Group</th>
        <th>Material Name</th>
        <th>Unit</th>
        <th>Quantity</th>
    </tr>
    <?php
    $apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0AStock%20by%20Raw-Material";
    $apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . date("d%20M%20Y%20H:i:s", $fr);
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . date("d%20M%20Y%20H:i:s", $to);
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";
    if (!$ord OR $ord == 'null' OR $ord == 'undefined') {
        $ord = "4|||DESC";
    }

    $ord_arrange = str_replace("|||", " ", $ord);
    //  $ord_arrange = "B.Name";
    $sql = "SELECT MAX(B.Type) AS Raw_Mat_Type,MAX(B.Name) AS Raw_Mat_Name,MAX(Unit) AS Unit,SUM(Quantity) AS Quantity  ";
    $sql .= "FROM product_stock A ";
    $sql .= "INNER  JOIN raw_material B ON A.ProductID=B.ID ";
    $sql .= "WHERE Date_YMD BETWEEN $fr AND $to AND A.BranchID IN($br) ";
    $sql .= "GROUP BY A.ProductID ";
    $sql .= "ORDER BY $ord_arrange";
    $result = $conn_db->query($sql);
    $count = 1;
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
        if ($Date != $Date_Old) {
            $date_text = date("d/m/Y H:i:s", $Date);
        } else {
            $date_text = '';
        }
        if ($ServiceNo != $ServiceNo_Old) {
            $ServiceNo_text = "x" . str_pad(substr($ServiceNo, -5), 4, "0", STR_PAD_LEFT);
        } else {
            $ServiceNo_text = '';
        }
        ?>
        <tr>
            <td style="text-align:center"><?= $count ?></td>
            <td style="text-align:center"><?= $Raw_Mat_Type ?></td>
            <td style="text-align:left"><?= $Raw_Mat_Name ?></td>
            <td style="text-align:center"><?= $Unit ?></td>
            <td style="text-align:right"><?= number_format($Quantity, 2) ?></td>

        </tr>
        <?php
        $Amount_Price = $Amount_Price + $Total_Price;
        $Total_Quantity = $Total_Quantity + $Quantity;


        $CSV[] = array($count, date("Y-m-d", $Date), date("H:i:s", $Date), $ServiceNo, $Type, $Name, $Name2, $Unit, $Quantity);
        $apkPrint .= "%0A_bm_v1_500_30_22_0_25_%20" . str_replace(" ", "%20", $Raw_Mat_Name) . "_____" . number_format($Quantity, 2);
        $count++;
        $Date_Old = $Date;
        $ServiceNo_Old = $ServiceNo;
    }

    $apkPrint .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" . number_format($Total_Quantity, 2);
    $apkPrint .= "%0A<cut>%0A";

    //Generate CSV File
    $CSVfilename = $br . "_stock.csv";
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
<style type="text/css">
    .report-table{
        font:normal 13px sans-serif;
    }
    .report-table th{
        border:solid 1px #000;
        border-width:1px 1px 1px 0;
    }
    .report-table td{
        border:solid 1px #000;
        border-width:0 1px 1px 0;
    }
</style>

<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
include("../php-config.conf");
include("../php-db-config.conf");
$tab = $_GET['tab'];
$ord = $_GET['ord'];
include("convert_date.php");

$CSV[] = array('AmbientPOS Stock Usage Export');
$CSV[] = array('Export Branch', $br);
$CSV[] = array('Data From', date("d/m/Y", $fr), date("H:i:s", $fr));
$CSV[] = array('Data to', date("d/m/Y", $to - 1), date("H:i:s", $to - 1));
$CSV[] = array('', 'Date', 'Time', 'ServiceNo', 'P.Group', 'Product Name', 'M.Group', 'Material Name', 'Unit', 'Quantity');
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
        <option value="Date,C.Menu_Code,C.NameEN,Raw_Material_Code,Name">Date</option>
        <option value="Date|||DESC"> ↑ Date</option>
        <option value="Quantity">Quantity</option>
        <option value="Quantity|||DESC"> ↑ Quantity</option>
        <option value="Name">Name</option>
        <option value="Name|||DESC"> ↑ Name</option>
        <option value="Type">Group</option>
        <option value="Type|||DESC"> ↑ Group</option>
    </select>
</div>
<div style="clear:both;"></div>
<table cellspacing="0" cellpadding="3" class="report-table">
    <tr>
        <th></th>
        <th>Date-Time</th>
        <th>Service#</th>
        <th title="Product Group">P.Group</th>
        <th>Product Name</th>
        <th title="Raw-Material Group">M.Group</th>
        <th>Material Name</th>
        <th>Unit</th>
        <th>Quantity</th>
    </tr>
    <?php
    $apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0AStock%20by%20Order";
    $apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . date("d%20M%20Y%20H:i:s", $fr);
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . date("d%20M%20Y%20H:i:s", $to);
    $apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
    $apkPrint .= "%0A<align_center>%0A__________________________________________%0A";

    if (!$ord OR $ord == 'undefined') {
        $ord = "Date,C.Menu_Code,C.NameEN,Raw_Material_Code,Name";
    }
    $ord_arrange = str_replace("|||", " ", $ord);
    $sql = "SELECT ServiceNo,Date,B.Type AS Raw_Mat_Type,C.Type AS Product_Type,C.NameEN AS Product_Name,B.Name AS Raw_Mat_Name,Unit,Quantity  ";
    $sql .= "FROM product_stock A ";
    $sql .= "INNER JOIN raw_material B ON A.ProductID=B.ID ";
    $sql .= "INNER JOIN menu C ON A.MenuID=C.ID ";
    $sql .= "WHERE Date_YMD BETWEEN $fr AND $to AND A.BranchID IN($br) ";
    $sql .= "ORDER BY $ord_arrange";
    $result = $conn_db->query($sql);
    $count = 1;
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }

        $Product_Type_Text = $Product_Type;
        $Product_Name_Text = $Product_Name;

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
        if ($Product_Name != $Product_Name_Prev OR $ServiceNo != $ServiceNo_Old) {
            $add_style = "border-top:solid 2px;";
            $add_style_Product_Name = "border-top:solid 2px;font-weight:bold;";
        } else {
            $add_style = "";
            $add_style_Product_Name = "";
            unset($Product_Type_Text);
            unset($Product_Name_Text);
        }
        ?>
        <tr>
            <td style="text-align:center;"><?= $count ?></td>
            <td style="text-align:center;"><?= $date_text ?></td>
            <td style="text-align:center;"><?= $ServiceNo_text ?></td>
            <td style="text-align:center;<?= $add_style ?>"><?= $Product_Type_Text ?></td>
            <td style="text-align:left;<?= $add_style_Product_Name ?>"><?= $Product_Name_Text ?></td>
            <td style="text-align:center;<?= $add_style ?>"><?= $Raw_Mat_Type ?></td>
            <td style="text-align:left;<?= $add_style ?>"><?= $Raw_Mat_Name ?></td>
            <td style="text-align:center;<?= $add_style ?>"><?= $Unit ?></td>
            <td style="text-align:right;<?= $add_style ?>"><?= number_format($Quantity, 2) ?></td>
        </tr>
        <?php
        $Amount_Price = $Amount_Price + $Total_Price;
        $Total_Quantity = $Total_Quantity + $Quantity;


        $CSV[] = array($count, date("Y-m-d", $Date), date("H:i:s", $Date), $ServiceNo_text, $Product_Type, $Product_Name, $Raw_Mat_Type, $Raw_Mat_Name, $Unit, $Quantity);
        $apkPrint .= "%0A_bm_v1_500_30_22_0_25_%20" . str_replace(" ", "%20", $Raw_Mat_Name) . "_____" . number_format($Quantity, 2);
        $count++;
        $Date_Old = $Date;
        $ServiceNo_Old = $ServiceNo;
        $Product_Name_Prev = $Product_Name;
    }

    $apkPrint .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" . number_format($Total_Quantity, 2);
    $apkPrint .= "%0A_bm_v1_500_30_22_0_25_**Topup%20detail%20Excluded";
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

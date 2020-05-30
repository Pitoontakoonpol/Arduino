<?php
session_start();
$br = $_GET['br'];
include("../php-config.conf");
include("../php-db-config.conf");
include("../setting/fn_username.php");
include("../promotion/fn_promotion.php");
include("../raw_material/fn_raw_material.php");
$tab = $_GET['tab'];
$ord = $_GET['ord'];

include("convert_date.php");
?>
<script type="text/javascript">
    function do_print() {
        var printAPK = $("#apkPrint").val();
        var apkPrint_Total_Quantity = $("#apkPrint_Total_Quantity").val();
        var apkPrint_Total_Price = $("#apkPrint_Total_Price").val();
        var print_detail = "http://localhost:8080?type=e&please_print=" + printAPK;

        print_detail += "%0A%0A<align_center>%0A________________________________________";
        print_detail += "%0A<align_center>%0A________________________________________";
        print_detail += "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" + apkPrint_Total_Quantity;
        print_detail += "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Price:" + apkPrint_Total_Price;
        print_detail += "%0A<cut>%0A";
        $("#spare1").load(print_detail);
    }
</script>
<div style="clear:both;height:30px;"></div>

<?php
$count = 1;
$sql = "SELECT * FROM service_order WHERE Time_Order_YMD BETWEEN $fr AND $to ORDER BY Time_Order";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    $Time_Order = $db['Time_Order'];
    $MenuID = $db['MenuID'];
    $Quantity = $db['Quantity'];
    $Price = $db['Price'];
    $line_text.=str_pad($count, 4, "0", STR_PAD_LEFT) . "|" . date("dmY", $Time_Order) . "|0|0|" . $MenuID . "|$Quantity|" . "|$Price|" . "|" . $Price * $Quantity . "|0\n";
    $count++;
}
?>
<textarea style="width:80%;height:300px;"><?= $line_text ?></textarea>
<div id="spare1"></div>
<style type='text/css'>
    .cancel1 {
        color:red;
        text-decoration: line-through;
    }   
    .cancel2 {
        color:#666;
        text-decoration: line-through;
    }
</style>
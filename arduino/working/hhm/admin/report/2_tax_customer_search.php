<?php
include("../php-config.conf");
include("../php-db-config.conf");

$BranchID = $_GET['br'];
$taxName = $_GET['taxName'];
$taxMobile = $_GET['taxMobile'];
$taxNo = $_GET['taxNo'];

$count = 0;
$sql = "SELECT * FROM member WHERE BranchID='$BranchID' AND ((Name LIKE '%$taxName%' OR Company_Name LIKE '%$taxName%') AND (Mobile LIKE'%$taxMobile%' OR Telephone LIKE'%$taxMobile%') AND TaxNo LIKE'%$taxNo%')";
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    $ID = $db['ID'];
    $Name = $db['Name'];
    $Company_Name = $db['Company_Name'];
    $TaxNo = $db['TaxNo'];
    $Telephone = $db['Telephone'];
    $Mobile = $db['Mobile'];
    $Billing_Address = $db['Billing_Address'];
    if ($Company_Name) {
        $Name = $Company_Name;
    }
    ?>
    <div class="customer-line" id="area-<?= $ID ?>" >
        <div class="customer-area1" onclick="active_line2('<?= $ID ?>')">
            <div class="customer-line-name"  id="customer-name-<?= $ID ?>"><?= $Name ?></div>
        </div>
        <div class="customer-area2" id="area2-<?= $ID ?>">
            <table>
                <tr>
                    <th>Tax#</th>
                </tr>
                <tr>
                    <td id="customer-taxno-<?= $ID ?>"><?= $TaxNo ?></td>
                </tr>
                <tr>
                    <th>Address</th>
                </tr>
                <tr>
                    <td id="customer-address-<?= $ID ?>"><?= $Billing_Address ?></td>
                </tr>
                <tr>
                    <th>Telephone/Mobile</th>
                </tr>
                <tr>
                    <td><?= $Telephone . " " . $Mobile ?></td>
                </tr>
            </table>
            <div style="padding:0 20px;"><button onclick="issue_tax('<?= $ID ?>')">Confirm</button></div>
        </div>

    </div>
    <?php
    $count++;
}
?>
<script type='text/javascript'>
    $("#total-customer-search").text('<?= $count ?> results');
    function active_line2(id) {
        $(".customer-area2").slideUp(200);
        $(".customer-line").css('background-color', '#eee');

        $("#area2-" + id).slideDown(200);
        $("#area-" + id).css('background-color', '#53b700');

    }
</script>
<style type="text/css">
    .customer-line{
        margin-bottom:5px;
        clear:both;
        background-color:#eee;
        border-bottom:solid 1px #bbb;
        padding:10px 5px;
    }
    .customer-line-name{
        float:left;
    }
    .customer-line-taxno{
        float:left;
    }
    .customer-area1{
        height:25px;
    }
    .customer-area2{
        display:none;
    }
    .customer-area2 th{
        text-align:left;
        font:bold 11px sans-serif;
        color:#fff;
    }
    .customer-area2 td {
        text-align:left;
        font:normal 16px sans-serif;
        color:#000;
    }
</style>
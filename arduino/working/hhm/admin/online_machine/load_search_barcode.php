<?php
include("../php-db-config.conf");
$search_key = $_GET['search_key'];
$search_key_id = $_GET['search_key_id'];
if ($search_key_id) {
    $sql = "SELECT * FROM product WHERE ID='$search_key_id' LIMIT 1";
} else {
    $sql = "SELECT * FROM product WHERE Barcode='$search_key' LIMIT 1";
}
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    foreach ($db as $key => $value) {
        $$key = $value;
    }
    ?>
    <div>
        <?php
        if (file_exists("../../product_picture/$ID.jpg")) {
            ?><img src="../../product_picture/<?= $ID ?>.jpg" style='height:300px;'>
        <?php } ?>
    </div>
    <table class='product-desc' style='width:100%'>
        <tr>
            <th>Thai</th>
            <td colspan='2' title="<?= $ID ?>"><?= $TH_Description ?></td>
        </tr>
        <tr>
            <th>English</th>
            <td colspan='2'><?= $Description ?></td>
        </tr>
        <tr>
            <th>Unit</th>
            <td><?= $Denom . " " . $AUn ?></td>
        </tr>
        <tr>
            <th>Gross/Net</th>
            <td><?= $Gross_Weight . "/" . $Net_Weight . " " . $Un ?>.</td>
        </tr>
        <tr>
            <th>Dimension</th>
            <td><?= "L" . $Length . " x W" . $Width . " x H" . $Height . " " . $Uni ?>.</td>
        </tr>
    </table>
    <?php
}
?>
<input type="hidden" id="ProductID" value="<?= $ID ?>">
<style type='text/css'>
    .product-desc td{
        padding:3px;
        background-color:#eee;
        font:normal 14px sans-serif;
    }
    .product-desc th{
        padding:3px;
        background-color:#ddd;
        font:normal 14px sans-serif;
    }
</style>
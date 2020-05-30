<?php
include("../php-db-config.conf");
$val = $_GET['val'];
?>

<select onchange="search_barcode(this.value)">
    <option value="">Please select...</option>
    <?php
    $sql = "SELECT ID,Barcode,Description,TH_Description,Denom,AUn FROM product WHERE Brand='$val' ORDER BY 2;";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $Barcode = $db['Barcode'];
        $Description = $db['Description'];
        $TH_Description = $db['TH_Description'];
        $Denom = $db['Denom'];
        $AUn = $db['AUn'];

        if (!$TH_Description) {
            $TH_Description = $Description;
        }

        $Name = $Barcode . " " . $TH_Description . " " . $Denom . "/" . $AUn
        ?>
        <option value="<?= $ID ?>"><?= $Name ?></option>
    <?php } ?>
</select>
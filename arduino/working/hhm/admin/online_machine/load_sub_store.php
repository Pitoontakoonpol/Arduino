<?php
include("../php-db-config.conf");
$val = $_GET['val'];
?>

<select id="StoreID">
    <option></option>
    <?php
    $sql = "SELECT ID,Name FROM store WHERE Store_Type='$val' ORDER BY 2;";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $Name = $db['Name'];
        ?>
        <option value="<?= $ID ?>"><?= $Name ?></option>
    <?php } ?>
</select>
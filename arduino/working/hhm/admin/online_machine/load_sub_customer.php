<?php
include("../php-db-config.conf");
$val = $_GET['val'];
$sql = "SELECT ID,Customer_Code,Name FROM customer WHERE Customer_Code LIKE'%$val%' OR Name LIKE'%$val%' ORDER BY 1 LIMIT 100;";
//echo $sql;
$result = $conn_db->query($sql);
?>
<select id="CustomerID">
    <option value="">Search from '<?=$val?>'</option>
    <?php
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $Customer_Code = $db['Customer_Code'];
        $Name = $db['Name'];
        ?>
        <option value="<?= $ID ?>"><?=$Customer_Code." ". $Name ?></option>
    <?php } ?>
</select>

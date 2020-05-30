<?php
include("../php-db-config.conf");
$val = $_GET['val'];
?>

<select id="IssueID">
    <option></option>
    <?php
    $sql = "SELECT ID,T2,T3,T4 FROM issue WHERE T1='$val' ORDER BY 2;";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $T2 = $db['T2'];
        $T3 = $db['T3'];
        $T4 = $db['T4'];
        ?>
        <option value="<?= $ID ?>"><?= $T2.">".$T3.">".$T4?></option>
    <?php } ?>
</select>
<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
?>

<table bgcolor="#ccc" cellpadding="5" cellspacing="1" class="atpayment-table">
    <tr>
        <th colspan="2"></th>
        <th>Sequence</th>
        <th>Promo. Code</th>
        <th>Discount</th>
        <th>Remark</th>
    </tr>
    <?php
    $count = 1;
    $sql = "SELECT ID,Sequence,Name,Total,Remark,Active FROM promotion WHERE BranchID=$br AND Promotion_Type=0 ORDER BY Sequence,Name,Total";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db AS $key => $value) {
            $$key = $value;
        }
        ?>
        <tr>
            <td><?= $count ?></td>
            <td>
                <div style="float:left;padding:0 5px">
                    <button class="ui-btn ui-btn-inline ui-icon-edit ui-btn-icon-notext ui-corner-all" onclick="popup_at_payment(<?= $ID ?>);"></button>
                </div>
                <div style="float:left;padding:5px">
                    <input type="checkbox" onchange="change_active(this)" id="<?= $ID ?>" <?php if ($Active) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true">
                </div>
            </td>
            <td style="text-align:center;" id="SEQUENCE-<?= $ID ?>"><?= $Sequence ?></td>
            <td align="center" style="font-weight:bold" id="NAME-<?= $ID ?>"><?= $Name ?></td>
            <td align="center" id="TOTAL-<?= $ID ?>"><?= $Total ?>%</td>
            <td id="REMARK-<?= $ID ?>" style="font-size:12px;"><?= $Remark ?></td>
        </tr>
        <?php
        $count++;
    }
    ?>
</table>
<style type="text/css">
    .atpayment-table th{
        font:bold 13px sans-serif;
        background-color:#444;
        color:#fff;
        padding:5px 10px;
    }
    .atpayment-table td{
        font:normal 15px sans-serif;
        background-color:#fff;
    }
</style>
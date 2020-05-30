<?php
include("../php-db-config.conf");
$searchKey = $_GET['searchKey'];
$opr = $_GET['opr'];
if ($opr == 'search_choose') {
    $sql = "SELECT ID,T1,T2,T3,T4,Remark FROM issue WHERE ID ='$searchKey' LIMIT 1;";
    //echo $sql;
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $T1 = $db['T1'];
        $T2 = $db['T2'];
        $T3 = $db['T3'];
        $T4 = $db['T4'];
        $Remark = $db['Remark'];
        ?>

        <div class="issue-table">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="4"><div class="issue-table-ID" style="font:normal 30px courier;background-image:linear-gradient(#000,#222);color:#fff;"><?= $ID ?></div></td>
                </tr>
                <tr>
                    <td colspan="4"><div class="issue-table-T1" style="clear:both;font:normal 20px sans-serif;"><?= $T1 ?></div></td>
                </tr>
                <tr>
                    <td class="spare-block"></td>
                    <td colspan="3"><div class="issue-table-T2" style="clear:both;font:normal 20px sans-serif;"><?= $T2 ?></div></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="spare-block"></td>
                    <td colspan="2"><div class="issue-table-T3" style="clear:both;font:normal 20px sans-serif;"><?= $T3 ?></div></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="spare-block"></td>
                    <td><div class="issue-table-T4" style="clear:both;font:normal 20px sans-serif;"><?= $T4 ?></div></td>
                </tr>
            </table>
        </div>
        <div style="text-align:center;padding:10px;background-color:#ddd"><?= $Remark ?></div>
        <?php
    }
} else {
    $sql = "SELECT ID,T1,T2,T3,T4 FROM issue WHERE T1 LIKE'%$searchKey%' OR T2 LIKE'%$searchKey%' OR T3 LIKE'%$searchKey%' OR T4 LIKE'%$searchKey%' OR T1_Tag LIKE'%$searchKey%' OR T2_Tag LIKE'%$searchKey%' OR T3_Tag LIKE'%$searchKey%' OR T4_Tag LIKE'%$searchKey%' ORDER BY 2,3,4,5 LIMIT 100;";
//echo $sql;
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $ID = $db['ID'];
        $T1 = $db['T1'];
        $T2 = $db['T2'];
        $T3 = $db['T3'];
        $T4 = $db['T4'];

        $T1 = str_ireplace($searchKey, "<span class='search-highlight'>" . strtoupper($searchKey) . "</span>", $T1);
        $T2 = str_ireplace($searchKey, "<span class='search-highlight'>" . strtoupper($searchKey) . "</span>", $T2);
        $T3 = str_ireplace($searchKey, "<span class='search-highlight'>" . strtoupper($searchKey) . "</span>", $T3);
        $T4 = str_ireplace($searchKey, "<span class='search-highlight'>" . strtoupper($searchKey) . "</span>", $T4);
        ?>
        <div class="issue-table">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:30px;" valign="top">
                        <div class="issue-table-ID"><?= $ID ?></div>
                    </td>
                    <td onclick="choosing_issue('<?= $ID ?>')">
                        <div class="issue-table-T1"><?= $T1 ?></div>
                        <div class="issue-table-T2"><?= $T2 ?></div>
                        <div class="issue-table-T3"><?= $T3 ?></div>
                        <div class="issue-table-T4"><?= $T4 ?></div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }
}
?>
<style type="text/css">
    .issue-table{
        clear:both;
        border-top:solid 1px #aaa;
        padding:5px 0;
        background-image: linear-gradient(#eee, #fff);
    }
    .issue-table div{
        float:left;
        padding:3px;
        margin:2px;
        border-radius:5px;
    }
    .issue-table-ID{
        padding:0;
        background-image: linear-gradient(#fff, #fff);
        font-size:10px;
        color:#000;
    }
    .issue-table-T1{
        background-image: linear-gradient(#ffaf4b, #ff920a);
        color:#fff;
    }
    .issue-table-T2{
        background-image: linear-gradient(#888888, #aaaaaa);
        color:#fff;
    }
    .issue-table-T3{
        background-image: linear-gradient(#008800, #00bb00);
        color:#fff;
    }
    .issue-table-T4{
        background-image: linear-gradient(#499bea, #207ce5);
        color:#fff;
    }
    .search-highlight{
        border-bottom:dotted 2px #fff;
    }
    .spare-block{
        width:50px;
        background-image: url('../../img/tier_arrow.svg');
        background-repeat: no-repeat;
    }
</style>
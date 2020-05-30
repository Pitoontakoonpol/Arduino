
<script type="text/javascript">
    $(document).ready(function () {
        $(".faw").text('new Faw');
    });
</script>
<?php
$br = $_GET['br'];
$display = $_GET['display'];
$start = $_GET['start'];
include("../php-config.conf");
include("../php-db-config.conf");
?>
<table class="sub-table" style="margin-left:10px;" cellpadding="0" cellspacing="1">
    <?php
    $sql = "SELECT stock_service.ID,ServiceNo,Method,Start_Time,Finish_Time,From_Location,To_Location,DocumentNo1,DocumentNo2,Remark,Cancel,Cancel_Reason,VAT,Discount,COUNT(1) AS Total_Product,SUM(Buying_Quantity) AS Total_Qty,SUM(Buying_Quantity*Price) AS Total_Price ";
    $sql.="FROM stock_service LEFT JOIN stock_detail ON stock_service.ID=stock_detail.ServiceID ";
    $sql.="WHERE Method LIKE '%$display' AND `BranchID`='$br' ";
    $sql.="GROUP BY stock_service.ID ORDER BY stock_service.Finish_Time=0 DESC,stock_service.Finish_Time DESC,stock_service.Start_Time DESC,Method LIMIT $start,50";
    // echo $sql;
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db AS $key => $value) {
            $$key = $value;
        }
        if (!$Finish_Time) {
            $Date_Time = $Start_Time;
            $bgCol = "background-color:#fff;";
        } else {
            $Date_Time = $Finish_Time;
            unset($bgCol);
        }
        if($Cancel){
            $bgCol = "background-color:#ff8888;text-decoration: line-through;";
            
        }
        if ($Method == 1) {
            $Method_Title = 'Import';
            $methodCol = "background-color:green;color:white;";
        } else if ($Method == 2) {
            $Method_Title = 'Export';
            $methodCol = "background-color:orange;color:white;";
        } else if ($Method == 4) {
            $Method_Title = 'PO';
            $methodCol = "background-color:skyblue;color:black;";
        } else if ($Method == 5) {
            $Method_Title = 'Delivery';
            $methodCol = "background-color:brown;color:white;";
        }
        if ($ServiceNo) {
            $ServiceNo = "#$ServiceNo";
        } else {
            $ServiceNo = "Edit";
        }
        ?>
        <tr>
            <td style="<?= $bgCol ?>">
                <div class="node node1"><button class="ui-btn ui-mini" style="margin:2px;padding:4px;" onclick="load_desc('<?= $ID ?>',<?= $Method ?>)"><?= $ServiceNo ?></button></div>
                <div style="display:none" class="row-<?= $ID ?>" tserviceno="<?= $ServiceNo ?>" tmethod="<?= $Method_Title ?>" tdate="<?= date('d/m/Y H:i:s', $Date_Time) ?>" tfrom_location="<?= $From_Location ?>" tto_location="<?= $To_Location ?>" tdocumentno1="<?= $DocumentNo1 ?>"tdocumentno2="<?= $DocumentNo2 ?>"tcancel="<?= $Cancel ?>"></div>
            </td>
            <td style="<?= $bgCol ?>"><div class="node node2" style="<?= $methodCol ?>"><?= $Method_Title ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node3">
                    <?php
                    if ($Date_Time > $Today00) {
                        echo date('H:i', $Date_Time);
                    } else {
                        echo date("d/m/y", $Date_Time);
                    }
                    ?>
                </div></td>
            <td style="<?= $bgCol ?>"><div class="node node4 lfrom_location-<?= $ID ?>"><?= $From_Location ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node5 lto_location-<?= $ID ?>"><?= $To_Location ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node6 ldocumentno1-<?= $ID ?>"><?= $DocumentNo1 ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node7 ldocumentno2-<?= $ID ?>"><?= $DocumentNo2 ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node8 ltotal_product-<?= $ID ?>"><?= $Total_Product ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node9 ltotal_qty-<?= $ID ?>"><?= number_format($Total_Qty, 2) ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node10 ltotal_price-<?= $ID ?>"><?= number_format($Total_Price, 2) ?></div></td>
            <td style="<?= $bgCol ?>"><div class="node node11 lstaff-<?= $ID ?>"><?= $BranchID ?></div></td>
        </tr>
        <?php
    }
    ?>
</table>
<style type="text/css">
    .sub-table{
        background-color:#bbb;
    }
    .sub-table th {
        background-color:#444;
        font:bold 14px sans-serif;
        text-align:center;
        color:white;

    }
    .sub-table td {
        background-color:#ddd;

    }
    .node{
        overflow:hidden;
        font:normal 14px sans-serif;
        padding:5px 5px;
        height:20px;
    }
    .node1 { 
        text-align:center;
        padding:0;
        height:30px;
        width:165px;
    }
    .node2 { 
        text-align:center;
        width:50px;
    }
    .node3 { 
        text-align:center;
        width:55px;
    }
    .node4 { 
        text-align:center;
        width:100px;
    }
    .node5 { 
        text-align:center;
        width:100px;
    }
    .node6 { 
        font-size:10px;
        text-align:center;
        width:100px;
    }
    .node7 { 
        font-size:10px;
        text-align:center;
        width:100px;
    }
    .node8 { 
        text-align:center;
        width:50px;
    }
    .node9 { 
        text-align:right;
        width:80px;
    }
    .node10 { 
        text-align:right;
        width:100px;
    }
    .node11 { 
        text-align:center;
        width:55px;
    }
</style>
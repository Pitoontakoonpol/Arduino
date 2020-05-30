<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$id = $_GET['id'];
$Name = $_GET['Name'];
echo "<div style='clear:both;padding-left:10px;font:bold 25px sans-serif'>$Name</div>";
?>
<?php
$page = $_GET['page'];
$order_by = $_GET['order_by'];
$page_size = $_GET['page_size'];
$order_by = 'Time_Order DESC';

$start = $page * $page_size;
$sql = "SELECT A.ServiceNo,A.Time_Order,B.NameEN,A.Quantity,A.Price,A.Total_Topup_Price ";
$sql .= "FROM service_order A ";
$sql .= "LEFT JOIN menu B ON A.MenuID=B.ID ";
$sql .= "WHERE A.BranchID='$br' AND A.MemberID='$id' ";
$sql2 .= "ORDER BY $order_by ";
//$sql2 .= "LIMIT $start,$page_size";
//echo "<textarea>" . $sql . $sql2 . "</textarea>";
$result = $conn_db->query($sql);
$Total_Records = $result->num_rows;
$result = $conn_db->query($sql . $sql2);
$count = $Total_Records;
$Total_Records = number_format($Total_Records) . " members";
?>

<div class="node nodehead">
    <div class="node1"style="border-left:solid 1px gray;"></div>
    <div class="node2">Time Order</div>
    <div class="node3">SN</div>
    <div class="node4">Product Title</div>
    <div class="node5">Qty.</div>
    <div class="node6">Topup</div>
    <div class="node7">Discount</div>
    <div class="node8">Price</div>
</div>

<?php
while ($db = $result->fetch_array()) {
    foreach ($db as $key => $value) {
        $$key = $value;
    }

    $Line_Discount = $Discount * $Quantity;
    $Line_Price = ($Quantity * $Price) + $Total_Topup_Price - $Line_Discount;
    $Time_Order_Text = date("Y-m-d H:i:s", $Time_Order);
    $ServiceNo_Text = substr($ServiceNo, -5);
    if ($Old_ServiceNo == $ServiceNo) {
        unset($Time_Order_Text);
        unset($ServiceNo_Text);
    }
    ?>
    <div class="node">
        <div class="node1" style="border-left:solid 1px gray;"><?= $count ?></div>
        <div class="node2"><?= $Time_Order_Text ?></div>
        <div class="node3"><?= $ServiceNo_Text ?></div>
        <div class="node4"><?= $NameEN ?></div>
        <div class="node5"><?= $Quantity ?></div>
        <div class="node6"><?= $Total_Topup_Price ?></div>
        <div class="node7"><?= $Discount ?></div>
        <div class="node8"><?= number_format($Line_Price, 2) ?></div>
    </div>
    <?php
    $Old_ServiceNo = $ServiceNo;
    $count--;
}
?>

<div id="page<?= $page + 1 ?>"></div>
<style type='text/css'>
    .node{
        clear:both;
        margin-left:20px;
    }
    .node div{
        float:left;
        font:normal 13px sans-serif;
        padding:3px;
        border:solid 1px gray;
        border-width:0 1px 1px 0;
        height:18px;
    }
    .node1{
        width:20px;
        text-align:center;
    }
    .node2{
        width:125px;
        text-align:center;
    }
    .node3{
        width:50px;
        text-align:center;
    }
    .node4{
        width:200px;
        text-align:left;
    }
    .node5{
        width:50px;
        text-align:right;
    }
    .node6{
        width:50px;
        text-align:right;
    }
    .node7{
        width:50px;
        text-align:right;
    }
    .node8{
        width:60px;
        text-align:right;
    }

    .nodehead div{
        background-color:#666;
        font:bold 12px sans-serif;
        border-width:1px 1px 3px 0;
        text-align:center;
        height:14px;
        color:#fff;
    }
</style>

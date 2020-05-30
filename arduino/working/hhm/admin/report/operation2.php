<?php
include("../php-config.conf");
include("../php-db-config.conf");
$title = $_GET['title'];
$operation = $_GET['operation'];
?>           
<div class="fs30 bold"><?= str_replace("-", " ", $title) ?></div>
<table cellspacing="1" cellpadding="5" class="report2">
    <tr>
        <th></th>
        <th>Registered</th>
        <th>Name</th>
        <th>Mobile Phone</th>
        <th>Email</th>
        <?php if ($operation == 5) { ?>
            <th>Last Order</th>
            <th>Quantity</th>
            <th>Amount</th>
        <?php } ?>
    </tr>
    <?php
    $count = 0;
    if($operation==5) {
    $sql = "SELECT Phone,Email,Firstname,Status,Created,SUM(service_order.Quantity) AS Quantity,SUM(service_order.Quantity*service_order.Price) AS Total_Price, Max(Time_Order) AS Last_Order, Delivery_Location AS Place FROM username INNER JOIN service_order ON username.Phone=service_order.FBID WHERE Phone<>0 GROUP BY 1 ORDER BY 7 DESC";
    }
    elseif($operation==6) {
    $sql = "SELECT * FROM username WHERE Phone<>0 ORDER BY Created DESC";
    }
    $result = $conn_db->query($sql);
    $row = $result->num_rows;
    while ($db = $result->fetch_array()) {
        $Phone = $db['Phone'];
        $Email = $db['Email'];
        $Firstname = $db['Firstname'];
        $Status = $db['Status'];
        $Created = $db['Created'];
        $Quantity = $db['Quantity'];
        $Tota_Price = $db['Total_Price'];
        $Last_Order = $db['Last_Order'];
        $Place = $db['Place'];
        ?>
        <tr>
            <td><?= $row-$count ?></td>
            <td><?= date("Y-m-d H:i", $Created) ?></td>
            <td style="text-align:left;"><?= $Firstname ?></td>
            <td style="text-align:center;"><?= $Phone ?></td>
            <td style="text-align:center;"><?= $Email ?></td>
            <?php if ($operation == 5) { ?>
                <td><?= date("Y-m-d H:i", $Last_Order)  . " " . $Place?></td>
                <td><?= $Quantity ?></td>
                <td><?= number_format($Tota_Price, 2) ?></td>
            <?php } ?>
        </tr>
        <?php
        $count++;
    }
    ?>
</table>
<?php
include("../php-config.conf");
include("../php-db-config.conf");
$operation = $_GET['operation'];
$title = $_GET['title'];
?>           

<div class="fs30 bold"><?= str_replace("-", " ", $title) ?></div>
<table cellspacing="1" cellpadding="5" class="report2">
    <tr>
        <th></th>
        <?php if ($operation != 4) { ?>
            <th>ServiceNo</th>
            <th>Date/Time</th>
            <th>Menu Code</th>
        <?php } ?>
        <th>Menu Type</th>
        <?php if ($operation != 4) { ?>
            <th>Menu Name</th>
        <?php } ?>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
    </tr>
    <?php
    $count = 1;
    $fr = $Today00;
    $to = $fr + 86400;
    if ($operation == 2) {
        $sql = "SELECT * FROM service_order INNER JOIN menu ON menu.ID=service_order.MenuID WHERE Time_Order>=$fr AND Time_Order<$to ORDER BY Time_Order";
    } else if ($operation == 3) {
        $sql = "SELECT Type,NameEN,SUM(Quantity) AS Quantity,PriceTHB,Menu_Code FROM service_order INNER JOIN menu ON menu.ID=service_order.MenuID WHERE Time_Order>=$fr AND Time_Order<$to GROUP BY NameEN ORDER BY 3 DESC,PriceTHB DESC";
    } else if ($operation == 4) {
        $sql = "SELECT ServiceNo,Time_Order,Type,NameEN,SUM(Quantity) AS Quantity,PriceTHB,Menu_Code FROM service_order INNER JOIN menu ON menu.ID=service_order.MenuID WHERE Time_Order>=$fr AND Time_Order<$to GROUP BY Type ORDER BY 3 DESC,PriceTHB DESC";
    }
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $Menu_Code = $db['Menu_Code'];
        $Menu_Name = $db['NameEN'];
        $ServiceNo = $db['ServiceNo'];
        $Time_Order = $db['Time_Order'];
        $Type = $db['Type'];
        $Quantity = $db['Quantity'];
        $PriceTHB = $db['PriceTHB'];
        
        if ($Time_Order) {
            $Time_Text=date("d/m/y H:i:s",$Time_Order);
        }
        ?>
        <tr>
            <td><?= $count ?></td>
            <?php if ($operation != 4) { ?>
                <td style="text-align:center;"><?= $ServiceNo ?></td>
                <td style="text-align:center;"><?= $Time_Text ?></td>
                <td style="text-align:center;"><?= $Menu_Code ?></td>
            <?php } ?>
            <td style="text-align:center;"><?= $Type ?></td>
            <?php if ($operation != 4) { ?>
                <td style="text-align:left;"><?= $Menu_Name ?></td>
            <?php } ?>
            <td><?= $Quantity ?></td>
            <td><?= number_format($PriceTHB, 2) ?></td>
            <td><?= number_format($PriceTHB * $Quantity, 2) ?></td>
        </tr>
        <?php
        $Total_Price = $Total_Price + ($PriceTHB * $Quantity);
        $Total_Quantity = $Total_Quantity + $Quantity;
        $count++;
    }

    if ($operation != 4) {
        ?>
        <tr>
            <td colspan='6'></td>
            <td><?= number_format($Total_Quantity, 2) ?></td>
            <td></td>
            <td><?= number_format($Total_Price, 2) ?></td>
        </tr>
    <?php } ?>
</table>
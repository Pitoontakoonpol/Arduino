<?php
$br = $_GET['br'];
$opr = $_GET['opr'];
include("../php-config.conf");
include("../php-db-config.conf");
if ($opr == 'list') {
    $sql = "SELECT Time_Order,ServiceNo,COUNT(1) AS Qty,SUM((Quantity*Price)+Total_Topup_Price-Discount) AS Bill_Total,Cancel FROM service_order WHERE BranchID='$br' GROUP BY ServiceNo ORDER BY Time_Order DESC LIMIT 50";
//echo "<textarea>" . $sql . "</textarea>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {

        foreach ($db as $key => $value) {
            $$key = $value;
        }
        if ($Cancel == 1) {
            $bg_col = 'red;color:white;';
        } else if ($Cancel == 2) {
            $bg_col = '#666;color:white;';
        } else {
            $bg_col = '#eee;color:black;';
        }
        ?>
        <div style="padding:10px;background-color:<?= $bg_col ?>;border-radius:5px;margin-top:10px;" class="h<?= $ServiceNo ?>" void_status="<?= $Cancel ?>" onclick="history_desc('<?= $ServiceNo ?>')">  
            <div style="float:left;width:80px;"><?= date("H:i:s", $Time_Order) ?></div>
            <div style="float:left;width:180px;font-size:25px;"><?= $ServiceNo ?></div>
            <div style="width:80px;clear:both;float:left;">x<?= $Qty ?></div>
            <div style="width:120px;float:left;"><?= number_format($Bill_Total, 2) ?>.-</div>
            <div style="clear:both;"></div>
        </div>
        <?php
    }
} else if ($opr == 'desc') {
    $serviceNo = $_GET['serviceNo'];
    ?>
    <div style="padding:10px;background-color:#fff;border-radius:3px;margin-top:10px;float:left;">
        <?php
        $sql = "SELECT Time_Order,ServiceNo,Quantity,Price,Total_Topup_Price,service_order.Discount,MenuID,NameEN,NameTH,NameLO,Payment_Remark FROM service_order ";
        $sql.=" INNER JOIN menu ON menu.ID=service_order.MenuID ";
        $sql.=" WHERE ServiceNo='$serviceNo'";
        //  echo "<textarea>" . $sql . "</textarea>";
        $result = $conn_db->query($sql);
        while ($db = $result->fetch_array()) {
            foreach ($db as $key => $value) {
                $$key = $value;
            }
            if ($cnt == 0) {
                ?>
                <div style="height:30px;border-bottom:solid 2px;">
                    <div style="float:left;width:250px;font-size:18px"><?= date("d/m/Y H:i:s", $Time_Order) ?></div>
                    <div style="float:right;width:200px;font-size:18px;text-align:right;">#<?= $serviceNo ?></div>
                </div>
                <?php
                $apkPrint_History .="%0A_bm_v1_500_30_22_0_25_No.%20" . $serviceNo;
                $apkPrint_History .="%0A_bm_v1_500_30_22_0_25_Order%20Time:%20" . date("d/m/Y H:i:s", $Time_Order);
                $apkPrint_History .="%0A_bm_v1_500_30_22_0_25_Reprinted%20on:%20" . date("d/m/Y H:i:s");
                $apkPrint_History .= "%0A<align_center>%0A__________________________________________%0A";
            }
            ?>
            <div style="min-height:30px;clear:both;">
                <div style="float:left;width:50px;font-size:18px;margin-left:10px;">x<?= $Quantity ?></div>
                <div style="float:left;width:220px;font-size:18px"><?= $NameEN ?></div>
                <div style="float:right;width:180px;font-size:18px;text-align:right;margin-right:10px;"><?= number_format($Price * $Quantity, 2) ?></div>
                <div style="clear:both;"></div>
            </div>


            <?php
            $apkPrint_History .="%0A_bm_v1_500_30_22_0_25_" . $Quantity . "x%20" . $NameEN . "_____" . number_format($Price * $Quantity, 2);
            if ($Total_Topup_Price) {
                ?>
                <div style="min-height:30px;clear:both;">
                    <div style="float:left;width:50px;font-size:18px;margin-left:10px;">&nbsp;</div>
                    <div style="float:left;width:220px;font-size:18px">&nbsp;&nbsp;&nbsp;+ Topup</div>
                    <div style="float:right;width:180px;font-size:18px;text-align:right;margin-right:10px;"><?= number_format($Total_Topup_Price, 2) ?></div>
                    <div style="clear:both;"></div>
                </div>
                <?php
                $apkPrint_History .="%0A_bm_v1_500_30_22_0_25_%20%20%20%20%20%2bTopup_____" . number_format($Total_Topup_Price, 2);
            }

            $cnt++;
            $Total_Price+=($Price * $Quantity) + $Total_Topup_Price;
            $Total_Discount+=$Discount * $Quantity;
        }
        if ($Total_Discount) {
            ?>

            <div style="height:30px;border-top:solid 2px;">
                <div style="float:right;font-size:18px;text-align:right;"><?= number_format($Total_Discount, 2) ?></div>
                <div style="float:right;font-size:18px;text-align:right;padding:0 20px 0 0">Discount</div>
            </div>
            <?php
        }
        $Total_Amount = $Total_Price - $Total_Discount;

        $apkPrint_History .="%0A%0A<align_right>%0A<text_double%201%201>%0ATotal:" . number_format($Total_Amount, 2) . "%0A";
        $apkPrint_History .="%0A<align_left>%0A_bm_v1_500_30_22_0_25_Paid%20by:%20" . $Payment_Remark . "%0A";
        ?>

        <div style="height:30px;border-top:solid 2px;">
            <div style="float:right;font-size:32px;text-align:right;"><?= number_format($Total_Amount, 2) ?></div>
            <div style="float:right;font-size:18px;text-align:right;padding:10px 20px 0 0">Total Amount</div>
        </div>
    </div>
    <input type="hidden" id="apkPrint_History" value="<?= $apkPrint_History ?>">
    <?php
}

<?php
session_start();
$br = $_GET['br'];

include("../php-db-config.conf");
include("../php-config.conf");
$tab = $_GET['tab'];
$ord = $_GET['ord'];
include("convert_date.php");


$sql = "INSERT INTO daily_summary (BranchID,Date_YMD,Total,Tender) ";
$sql .= "SELECT BranchID,Time_Order_YMD,SUM((Price-Discount)*Quantity),Payment_By FROM service_order GROUP BY BranchID,Time_Order_YMD,Payment_By";
$result = $conn_db->query($sql);
?>
<div id='section4'>
    <div id="peak-session" style="float:left;width:28%;clear:both;min-width:320px">
        <div class="fs20 bold" style="margin-top:30px;height:28px;">Golden Time Golden Item</div>
        <div style="overflow-y:auto;height:760px;background-color:#555;color:#fff;">
            <table id="peak-session-table" cellspacing="0" cellspacing="3">
                <?php
                $cnt_peak = 1;
                $sqlPeak = "SELECT SUM(Quantity) AS Peak_Quantity,Time_Session FROM service_order  WHERE Cancel=0 AND Time_Order_YMD>=$fr AND Time_Order_YMD<=$to AND BranchID IN($br) GROUP BY Time_Session ORDER BY 1 DESC LIMIT 10";
                $resultPeak = $conn_db->query($sqlPeak);
                while ($dbPeak = $resultPeak->fetch_array()) {
                    $Time_Session = $dbPeak['Time_Session'];
                    $Peak_Quantity = $dbPeak['Peak_Quantity'];
                    $TimeFromU = $Today00 + floor($Time_Session * 900);
                    $TimeFrom = date("H:i", $TimeFromU);
                    $TimeTo = date("H:i", $TimeFromU + 900);
                    if ($cnt_peak == 1) {
                        echo"<script type='text/javascript'>$('#GTGI').text('$TimeFrom-$TimeTo')</script>";
                    }
                    ?>
                    <tr>
                        <td valign="top" style="float:left;padding-top:5px;border-top:solid 2px #777;color:yellow" class="bold right"><?= $TimeFrom . "-" . $TimeTo ?></td>
                        <td style="border-top:solid 2px #777;"><div class="fs14 bold" style="text-align:right;"><?= number_format($Peak_Quantity) ?></div></td>
                        <td style="text-align:left;border-top:solid 2px #777;">
                            <div style="float:left;width:<?= $Peak_Quantity * 2 ?>px;background:url('../../img/Bar/yellow-bg-vertical.png') repeat-x left top;height:25px;border:solid 1px gray;"></div>
                        </td>
                    </tr>
                    <?php
                    $sqlPeakItem = "SELECT SUM(Quantity) AS PeakItem_Quantity,NameEN FROM service_order INNER JOIN menu ON service_order.MenuID=menu.ID WHERE Cancel=0 AND menu.PriceTHB>0 AND Time_Session=$Time_Session AND  Time_Order_YMD>=$fr AND Time_Order_YMD<=$to  AND service_order.BranchID IN($br) GROUP BY MenuID ORDER BY 1 DESC LIMIT 10";
                    $resultPeakItem = $conn_db->query($sqlPeakItem);
                    while ($dbPeakItem = $resultPeakItem->fetch_array()) {

                        $PeakItem_Quantity = $dbPeakItem['PeakItem_Quantity'];
                        $NameEN = $dbPeakItem['NameEN'];
                        ?>
                        <tr>
                            <td valign="top" style="padding-top:5px;width:30%;min-width:120px;" class="fs14 right"><?= $NameEN ?></td>
                            <td><div class="fs12" style="padding-top:1px;text-align:right;"><?= number_format($PeakItem_Quantity) ?></div></td>
                            <td style="text-align:left;">
                                <div style="float:left;width:<?= $PeakItem_Quantity * 2 ?>px;background:url('../../img/Bar/orange-bg-vertical.png') repeat-x left top;height:17px;"></div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <?php
                    $cnt_peak++;
                }
                ?>
            </table>
        </div>
    </div>
    <div id="peak-item" style="float:left;width:28%;margin-left:5px;min-width:320px">
        <div style="margin-top:30px;height:28px;">
            <div style="float:left" class="fs20 bold">Top Menu & Relatives</div>
            <div style="float:right" class="fs14">
                <a onclick="$('#peak-item-all').show();
                        $('#peak-item-morning').hide();
                        $('#peak-item-afternoon').hide();
                        $('.small-link').css('font-weight', 'normal');
                        $(this).css('font-weight', 'bold');" class="small-link bold cursor"><img src="../../img/time-all.png" style="width:25px;"></a> 
                <a onclick="$('#peak-item-all').hide();
                        $('#peak-item-morning').show();
                        $('#peak-item-afternoon').hide();
                        $('.small-link').css('font-weight', 'normal');
                        $(this).css('font-weight', 'bold');" class="small-link cursor"><img src="../../img/time-morning.png" style="width:25px;"></a> 
                <a onclick="$('#peak-item-all').hide();
                        $('#peak-item-morning').hide();
                        $('#peak-item-afternoon').show();
                        $('.small-link').css('font-weight', 'normal');
                        $(this).css('font-weight', 'bold');" class="small-link cursor"><img src="../../img/time-afternoon.png" style="width:25px;"></a>
            </div>
        </div>
        <div style="overflow-y:auto;height:760px;background-color:#555;clear:both;">
            <div id="peak-item-all">
                <div class="fs16 bold white right">All Day</div>
                <table id="peak-session-table" width="100%">
                    <?php
                    $to = $fr + 86400;
                    $noon = $fr + 43200;

                    $sqlPeak = "SELECT SUM(Quantity) AS Quantity,MenuID, menu.NameEN AS Name FROM service_order INNER JOIN menu ON service_order.MenuID=menu.ID WHERE  Cancel=0 AND Time_Order_YMD>=$fr AND Time_Order_YMD<=$to  AND PriceTHB>0 AND POS=1 AND service_order.BranchID IN($br) GROUP BY MenuID ORDER BY 1 DESC LIMIT 20";
                    $resultPeak = $conn_db->query($sqlPeak);
                    while ($dbPeak = $resultPeak->fetch_array()) {
                        $MenuID = $dbPeak['MenuID'];
                        $MenuName = $dbPeak['Name'];
                        $Quantity = $dbPeak['Quantity'];
                        ?>
                        <tr>
                            <td valign="top" style="padding-top:5px;width:30%;min-width:120px;" class="fs14 right"><?= $MenuName ?></td>
                            <td valign="top"><div class="fs12 bold" style="text-align:right;"><?= number_format($Quantity) ?></div></td>
                            <td style="text-align:left; ">
                                <div style="float:left;width:<?= $Quantity * 5 ?>px;background:url('../../img/Bar/orange-bg-vertical.png') repeat-x left top;height:18px;"></div>
                                <?php
                                $sqlIN = "SELECT ServiceNo FROM service_order WHERE Cancel=0 AND MenuID='$MenuID'  AND Time_Order_YMD>=$fr AND Time_Order_YMD<=$to AND BranchID IN($br)";
                                $resultIN = $conn_db->query($sqlIN);
                                while ($dbIN = $resultIN->fetch_array()) {
                                    $ServiceNo = $dbIN['ServiceNo'];
                                    $ServiceNo_Total = $ServiceNo_Total . "ServiceNo='" . $ServiceNo . "' OR ";
                                }
                                $ServiceNo_Total = "(" . substr($ServiceNo_Total, 0, -3) . ")";
                                $Cntt = 1;
                                $sqlIN2 = "SELECT MenuID,COUNT(1) AS Cnt,NameEN FROM service_order INNER JOIN menu ON service_order.MenuID=menu.ID WHERE Cancel=0 AND $ServiceNo_Total AND MenuID<>'$MenuID' AND PriceTHB>0 AND POS=1 AND service_order.BranchID IN($br) GROUP BY 1 ORDER BY 2 DESC LIMIT 3";
                                $resultIN2 = $conn_db->query($sqlIN2);

                                while ($dbIN2 = $resultIN2->fetch_array()) {
                                    $MenuIDMax = $dbIN2['MenuID'];
                                    $NameEN = $dbIN2['NameEN'];
                                    $Cnt = $dbIN2['Cnt'];
                                    echo "<div style='clear:both;font:normal 12px sans-serif;'><img src='../../img/Bar/yellow-bg.png' style='width:10px;height:10px;'>" . number_format((100 / $Quantity) * $Cnt, 2) . "% (" . $Cnt . ") ++" . $NameEN . "</div>";
                                    $Cntt++;
                                }
                                unset($ServiceNo_Total);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

        </div>
    </div>
</div>
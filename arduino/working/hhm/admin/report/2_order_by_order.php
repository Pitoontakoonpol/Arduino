<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
$SESSION_POS = $_SESSION['SESSION_POS'];
$SESSION_POS_ID = $_SESSION['SESSION_POS_ID'];
$SESSION_POS_TYPE = $_SESSION['SESSION_POS_TYPE'];
$SESSION_POS_PACKAGE = $_SESSION['SESSION_POS_PACKAGE'];
$SESSION_POS_PERMISSION = $_SESSION['SESSION_POS_PERMISSION'];
include("../php-config.conf");
include("../php-db-config.conf");
include("../setting/fn_username.php");
include("../promotion/fn_promotion.php");
include("../fn/convert_payment_by.php");
$tab = $_GET['tab'];
$ord = $_GET['ord'];
$additional = $_GET['additional'];
$Permission_Void = $_GET['Permission_Void'];
include("convert_date.php");
$apkPrint .= "%0A<text_double%201%201>%0A<align_center>%0AOrder%20Lists";
$apkPrint .= "%0A<text_double%200%200>%0A<align_center>%0A%20AmbientPOS%20:%20Branch%20$br";
$apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาเริ่มต้น%20:%20" . $fr_text;
$apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_ระยะเวลาสิ้นสุด%20:%20" . $to_text;
$apkPrint .= "%0A<align_left>%0A_bm_v1_500_35_25_0_30_เวลาที่พิมพ์%20:%20" . date("d%20M%20Y%20H:i:s", $DateU);
$apkPrint .= "%0A<align_center>%0A__________________________________________%0A";
?>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>

<script type="text/javascript">
    function do_print() {
        var apkPrint_order = $("#apkPrint_order").val();
        var print_detail = "http://localhost:8080?type=e&please_print=" + apkPrint_order;
        $("#spare1").load(print_detail);
    }
</script>

<div>
    <div style="float:left;"><button class="export-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-export ui-btn-inline" onclick="window.location = '<?= $br ?>.csv';">Export</button></div>
    <div style="float:left;"><button class="print-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-print ui-btn-inline" onclick="do_print()">Print</button></div>
    <div style="clear:both;"></div>
</div>
<table cellspacing="1" cellpadding="5" class="report2 fs14">
    <tr>
        <th></th>
        <th>ServiceNo</th>
        <th>Status</th>
        <th>Order Time</th>
        <th>Branch</th>
        <th>Machine Code</th>
        <th>Machine Type</th>
        <th>Machine Name</th>
        <th>Serial</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total Price</th>
        <th>Member ID</th>
        <th>Member Name</th>
        <th>Paid By</th>
    </tr>
    <?php
    $count = 1;
    if (!$ord) {
        $Order_By = 'NameEN DESC,PriceTHB DESC';
    }


    $CSV[] = array('AmbientPOS Export');
    $CSV[] = array('Export Branch', $br);
    $CSV[] = array('Data From', str_replace('%20', ' ', $fr_text));
    $CSV[] = array('Data From', str_replace('%20', ' ', $to_text));
    $CSV[] = array('', 'ServiceNo', 'Status', 'Date/Time Order', 'Menu Code', 'Type', 'Menu Name', 'Quantity', 'Unit Price', 'Topup', 'Discount', 'Total Price', 'Line Amount', 'VAT', 'PaidBy', 'Cashier');
    $br = 0;
    $sql = "SELECT A.ID AS ServiceID,A.BranchID AS BranchID,C.Name AS Branch_Name,MachineID,Time_Order,Time_Order_YMD,Quantity,Price,A.MemberID,Payment_By,Cancel, ";
    $sql .= "B.Name AS Menu_Name,B.Machine_Code AS Menu_Code,B.Type AS Menu_Type,D.Name AS Member_Name ";
    $sql .= "FROM service_order A ";
    $sql .= "LEFT JOIN machine B ON A.MachineID=B.Serial AND A.BranchID=B.BranchID ";
    $sql .= "LEFT JOIN branch C ON A.BranchID=C.Branch_Code ";
    $sql .= "LEFT JOIN member D ON A.MemberID=D.RFIDNo ";
    $sql .= "WHERE Time_Order_YMD BETWEEN $fr AND $to  ";
    if ($br) {

        $sql .= " A.BranchID IN($br) AND ";
    }
    $sql .= "ORDER BY Time_Order,B.Name;";
    //      echo $sql . "<br/>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }

        if ($Time_Order) {
            $Time = date("d/m/Y H:i:s", $Time_Order);
            $Time_Text = date("d/m/Y%20H:i:s", $Time_Order);
        }
        $ServiceNo = $ServiceID;

        $Queue_Min = floor($Queue_Stage / 60);
        $Queue_Sec = $Queue_Stage % 60;
        $Queue_Stage = str_pad($Queue_Min, 2, "0", STR_PAD_LEFT) . "." . str_pad($Queue_Sec, 2, "0", STR_PAD_LEFT) . "<span style='font-size:10px'>m</span>";

        $Cook_Min = floor($Cook_Stage / 60);
        $Cook_Sec = $Cook_Stage % 60;
        $Cook_Stage = str_pad($Cook_Min, 2, "0", STR_PAD_LEFT) . "." . str_pad($Cook_Sec, 2, "0", STR_PAD_LEFT) . "<span style='font-size:10px'>m</span>";

        $Delivery_Min = floor($Delivery_Stage / 60);
        $Delivery_Sec = $Delivery_Stage % 60;
        $Delivery_Stage = str_pad($Delivery_Min, 2, "0", STR_PAD_LEFT) . "." . str_pad($Delivery_Sec, 2, "0", STR_PAD_LEFT) . "<span style='font-size:10px'>m</span>";


        if ($MenuID) {
            $Menu_Name_Text = $Menu_Name;
            $Menu_Code = $Menu_Code;
            $Type = $Menu_Type;
            $Unit_Price = $Price;
            $Line_Discount = $Discount * $Quantity;
            $Line_Price = ($Quantity * $Price) + $Total_Topup_Price - $Line_Discount;
            if ($Total_Topup_Price) {
                $Menu_Name_Text .= "<sup style='font-size:10px' title='This line has Topup'>T</sup>";
                $Menu_Name_CSV .= " (T)";
            }
            if ($Line_Discount) {
                $Menu_Name_Text .= "<sup style='font-size:10px' title='This line has Discount'>D</sup>";
                $Menu_Name_CSV .= " (D)";
            }
        } else if ($PromotionID) {
            $MenuName = promotion_desc($PromotionID);
            $Menu_Name_Text = $MenuName[2];
            $Type = 'Promotion';
            $Unit_Price = $Price;
            $Line_Price = $Unit_Price * $Quantity;
        } else if ($Remark) {
            $Menu_Name_Text = $Remark;
            $Unit_Price = $Price;
            $Line_Price = $Unit_Price;
            $Queue_Stage = '';
            $Cook_Stage = '';
            $Delivery_Stage = '';

            $Quantity = '';
        } else {

            $Unit_Price = 0;
            $Line_Price = 0;
        }

        if ($Cancel == 1) {
            $cancel_css = 'cancel1';
            $cancel_text = "Canceled with Stock";
        } else if ($Cancel == 2) {
            $cancel_css = 'cancel2';
            $cancel_text = "Canceled without Stock";
        } else {
            $cancel_css = '';
            $cancel_text = "Normal";
        }
        if ($Discount) {
            $Discount = "-" . $Discount;
        }
        $Payment_By_Text = convert_payment_by($Payment_By);
        ?>
        <tr>
            <td><?= $count ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' style="text-align:left;">
                <?php
                if ($Prev_ServiceNo != $ServiceNo) {
                    if (($usr == 'admin' OR $Permission_Void == 1) AND ! $Cancel) {
                        ?>
                        <div class="cancel_bar" id="cancel_bar_<?= $ServiceNo ?>" style="float:left;">
                            <img src='../../img/action/suspend.png' style='width:20px;' align='absmiddle' onclick="cancel_order('<?= $ServiceNo ?>', '1')">
                            <img src='../../img/action/suspend_gray.png' style='width:20px;margin-left:2px' align='absmiddle' onclick="cancel_order('<?= $ServiceNo ?>', '2')">
                            <?php
                            if ($br == 205) {
                                ?>
                                <img src = '../../img/action/tag-20.png' style = 'width:20px;margin:2px 0' align = 'absmiddle' onclick = "full_tax1('<?= $ServiceNo ?>')">
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div style="float:left;" onclick="service_desc('<?= $ServiceNo ?>')"><?= $ServiceNo ?></div>
                <?php } ?>
            </td>
            <td style="text-align:center;">
                <?php
                if ($Prev_ServiceNo != $ServiceNo) {
                    echo $cancel_text;
                }
                ?>
            </td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:center;">
                <?php
                if ($Prev_ServiceNo != $ServiceNo) {
                    echo $Time;
                    unset($Line_Amount);
                    ?>
                    <script type='text/javascript'>
                        $('#Line-Amount-<?= $count - 1 ?>')
                                .css("border-bottom", 'double 3px #000')
                                .css("background-color", "#eee")
                                .css("font-weight", "bold");
                    </script>
                    <?php
                }
                $Line_Amount += $Line_Price;
                //          echo "($ServiceID)";
                ?>
            </td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:center;"><?= $Branch_Name ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:center;"><?= $Menu_Code ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:center;"><?= $Menu_Type ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:left;"><?= $Menu_Name ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>'  style="text-align:left;"><?= $MachineID ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' ><?= $Quantity ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' ><?= number_format($Price, 2) ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' ><?= number_format($Price * $Quantity, 2) ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' style="text-align:left;"><?= $MemberID ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' style="text-align:left;" ><?= $Member_Name ?></td>
            <td class='<?= $cancel_css ?> h<?= $ServiceNo ?>' style="text-align:center;"> <?= $Payment_By_Text ?></td>
        </tr>
        <?php
        if (!$Cancel) {
            $Total_Quantity = $Total_Quantity + $Quantity;
            $Total_Disc = $Total_Disc + $Line_Discount;
            $Total_VAT = $Total_VAT + $Unit_VAT;
            $Total_SC = $Total_SC + $Unit_SC;
            $Total_Topup = $Total_Topup + $Total_Topup_Price;
            $Total_Price = $Total_Price + ($Price * $Quantity);
        }
        if ($Prev_ServiceNo != $ServiceNo) {
            $apkPrint .= "%0A%0A<align_center>%0A--------------------%0A";
            $apkPrint .= "%0A<align_left>%0A%20" . $Time_Text . "%20-%20" . $ServiceNo;
        }

        $apkPrint .= "%0A_bm_v1_500_30_22_0_25_" . $Quantity . "x%20" . str_replace(" ", "%20", $Menu_Name) . "_____" . number_format($Price * $Quantity, 2);
        $Prev_ServiceNo = $ServiceNo;


        if ($Cancel) {
            $Cancel_Text = "---CANCELED---";
        } else {
            $Cancel_Text = '';
        }
        $CSV[] = array($count, $ServiceNo, $cancel_text, $Time, $Menu_Code, iconv('UTF-8', 'TIS-620', $Type), iconv('UTF-8', 'TIS-620', $Menu_Name), $Quantity, $Unit_Price, $Total_Topup_Price, $Discount, $Line_Price, $Line_Amount, '0', $Payment_By_Text, $Username);
        $line_asset = $count . "__||19||__" . $ServiceNo . "__||19||__" . $cancel_text . "__||19||__" . $Time . "__||19||__" . $Menu_Code . "__||19||__" . $Type . "__||19||__" . $Menu_Name . "__||19||__" . $Quantity . "__||19||__" . number_format($Unit_Price, 2) . "__||19||__" . number_format($Total_Topup_Price, 2) . "__||19||__" . number_format($Discount, 2) . "__||19||__" . number_format($Line_Price, 2) . "__||19||__0.00__||19||__0.00__||19||_" . $Payment_By . "__||19||__" . $Username . "__||38||__";
        unset($Unit_Disc);
        unset($Unit_VAT);
        unset($Unit_SC);
        unset($Line_Discount);
        $count++;
    }

    $apkPrint .= "%0A%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Quantity:" . number_format($Total_Quantity, 2);
    $apkPrint .= "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20Price:" . number_format($Total_Price, 2);
    $apkPrint .= "%0A%0A<align_right>%0A<text_double%201%201>%0ATotal%20VAT:" . number_format($Total_VAT, 2);
    $apkPrint .= "%0A_bm_v1_500_30_22_0_25_**Topup%20combined%20in%20selling%20item";
    $apkPrint .= "%0A<cut>%0A";
    ?>
    <script type='text/javascript'>
        $('#Line-Amount-<?= $count - 1 ?>')
                .css("border-bottom", 'solid 2px #000')
                .css("background-color", "#eee")
                .css("font-weight", "bold");
    </script>
    <tr>
        <td colspan='9'></td>
        <td class="bold" style="border-top:solid 3px;"><?= number_format($Total_Quantity, 2) ?></td>
        <td style="border-top:solid 3px;"></td>
        <td class="bold" style="border-top:solid 3px;"><?= number_format($Total_Price, 2) ?></td>
        <td class="bold" style="border-top:solid 3px;"><?= number_format($Total_VAT, 2) ?></td>
        <td colspan='2' class="bold" style="border-top:solid 3px;"></td>
    </tr>
    <?php
    $CSV[] = array('', '', '', '', '', '', '', $Total_Quantity, '', $Total_Topup, $Total_Disc, $Total_Price, '0.00', '0.00', '', '');
//Generate CSV File
    $CSVfilename = $br . ".csv";
    $fp = fopen($CSVfilename, 'w');
    foreach ($CSV as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
    ?>
</table>
<input type="hidden" value="<?= $apkPrint ?>" id="apkPrint_order">
<div id="invoice_view">invoice_view</div>

<div id="spare1"></div>
<style type='text/css'>
    .cancel1 {
        color:red;
        text-decoration: line-through;
    }   
    .cancel2 {
        color:#666;
        text-decoration: line-through;
    }
    #invoice_view{
        display:none;
        position:fixed;
        z-index:10;
        top:90px;
        right:20px; 
        box-shadow: -0 0  10px #888888;
        width:350px;
        padding:10px;
        background-color:#fff;
        font:normal 14px sans-serif;

    }
    @media print{
        #invoice_view{
            float:left;
            border:none;
            margin-top:0;
            top:0;
            left:5px;
            width:350px;
        }
        .report2{
            font-size:12px;
        }
    }
</style>

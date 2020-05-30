<?php
include("../php-config.conf");
include("../php-db-config.conf");
include("../fn/fn_today.php");
$area_height = 250;
$br = $_GET['br'];
$res = $_GET['res'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="index.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">

</script>
<?php
$df = $_GET['df'];
$dt = $_GET['dt'];
$ci = $_GET['ci'];
$ce = $_GET['ce'];
$cp = $_GET['cp'];
if ($df AND $dt) {
    $df_expld = explode("-", $df);
    $dt_expld = explode("-", $dt);
    $df = date("U", mktime(0, 0, 0, $df_expld[1], $df_expld[2], $df_expld[0]));
    $dt = date("U", mktime(0, 0, -1, $dt_expld[1], $dt_expld[2] + 1, $dt_expld[0]));
}
if (!$df OR $df == 0) {
    $df = $Today00;
}
if (!$dt OR $dt == 0) {
    $dt = $Today00 + 86399;
}
if (!$ci AND ! $ce AND ! $cp) {
    $ci = 1;
    $ce = 1;
    $cp = 1;
}
?>
<div style="width:100%;max-width:900px;overflow-x:auto;">
    <div style="clear:both;">
        <table cellspacing="0" cellpadding="0">
            <tr>
                <td class="C_Top">&nbsp;</td>
                <td class="C_Top">Date/Time</td>
                <td class="C_Top">Title</td>
                <td class="C_Top">Method</td>
                <td class="C_Top">Send</td>
                <td class="C_Top">Cash-In</td>
                <td class="C_Top">Cash-Out</td>
                <td class="C_Top">POS</td>
                <td class="C_Top">Staff</td>
                <td class="C_Top">Remark</td>
            </tr>
            <tr>
                <td valign="top" colspan="4"><div class="C" style="text-align:center;background-color:#555;color:#fff;font:normal 16px sans-serif;">T o t a l</div></td>
                <td valign="top"><div class='C C5 C5-Total' id='amount_send'></div></td>
                <td valign="top"><div class='C C6 C6-Total' id='amount_in'></div></td>
                <td valign="top"><div class='C C7 C7-Total' id='amount_out'></div></td>
                <td valign="top"><div class='C C8 C8-Total' id='amount_pos'></div></td>
                <td valign="top" colspan="2"><div class="C"  style="background-color:#555;"></div></td>
            </tr>
            <tr>
                <td valign="top"><div id="C1"></div></td>
                <td valign="top"><div id="C2"></div></td>
                <td valign="top"><div id="C3"></div></td>
                <td valign="top"><div id="C4"></div></td>
                <td valign="top"><div id="C5"></div></td>
                <td valign="top"><div id="C6"></div></td>
                <td valign="top"><div id="C7"></div></td>
                <td valign="top"><div id="C8"></div></td>
                <td valign="top"><div id="C9"></div></td>
                <td valign="top"><div id="C10"></div></td>
            </tr>
        </table>
        <?php
        $CSV[] = array('AmbientPOS Export (Cash Drawer)');
        $CSV[] = array('Export Branch', $br);
        $CSV[] = array('Data From', date("d/m/Y", $df), date("H:i:s", $df));
        $CSV[] = array('Data to', date("d/m/Y", $dt - 1), date("H:i:s", $dt - 1));
        $CSV[] = array('');
        $CSV[] = array('');
        $CSV[] = array('', 'Date', 'Time', 'Title', 'Method', 'Send', 'Cash-In', 'Cash-Out', 'POS', 'Staff', 'Remark');
        
        $Max_Line_Price = 100;
        if ($cp) {
            if ($res == 0) {
                $sql_drawer .= "SELECT Time_Order,ServiceNo,'3',Price,' ',Quantity,Total_Topup_Price,MenuID,Cancel,'',Username,Discount FROM service_order INNER JOIN username ON service_order.UsernameID=username.ID  WHERE Time_Order_YMD BETWEEN " . date('ymd', $df) . " AND " . date('ymd', $dt) . "  AND service_order.BranchID='$br'  AND service_order.Payment_By=0 AND Cancel=0 ";
            } else if ($res == 1) {
                $sql_drawer .= "SELECT Time_Order,CONCAT(Time_Order_YMD,LPAD(a.BranchID,5,'0'),LPAD(ServiceNo,5,'0')),'3',Price,' ',Quantity,Total_Topup_Price,MenuID,Cancel,'',Username,Discount FROM service_order_restaurant a LEFT JOIN username b ON a.UsernameID=b.ID WHERE  Time_Order_YMD BETWEEN " . date('ymd', $df) . " AND " . date('ymd', $dt) . "  AND a.BranchID='$br'  AND a.Payment_By=0 AND Cancel=0 ";
            }
        }
        if ($cp AND ( $ci OR $ce)) {
            $sql_drawer .= " UNION ALL ";
        }
        if ($ci OR $ce) {
            if ($ci AND ! $ce) {
                $additional = ' AND Method=1 ';
            } else if (!$ci AND $ce) {
                $additional = ' AND Method=2 ';
            }
            $sql_drawer .= "SELECT Date_Time,Title,Method,Price,cash_drawer.Remark,'','','',Cancel,cash_drawer.ID,Username,'' FROM cash_drawer INNER JOIN username ON cash_drawer.UsernameID=username.ID WHERE Date_Time BETWEEN $df AND $dt  $additional  AND cash_drawer.BranchID='$br' ";
        }
        if ($cp) {
            $sql_drawer .= "ORDER BY Time_Order DESC";
        } else {
            $sql_drawer .= "ORDER BY Date_Time DESC";
        }
    //    echo "<textarea>$sql_drawer </textarea>";
        $result_drawer = $conn_db->query($sql_drawer);
        while ($db_drawer = $result_drawer->fetch_array()) {
            $Date_Time = $db_drawer[0];
            $Title = $db_drawer[1];
            $Method = $db_drawer[2];
            $Price = $db_drawer[3];
            $Remark = $db_drawer[4];
            $Quantity = $db_drawer[5];
            $Total_Topup_Price = $db_drawer[6];
            $MenuID = $db_drawer[7];
            $Cancel = $db_drawer[8];
            $ID = $db_drawer[9];
            $Staff_Name = $db_drawer[10];
            $Discount = $db_drawer[11];
            $Date_Time_Print = date("d M H:i:s", $Date_Time);
            $Total_Send = 0;
            $Total_In = 0;
            $Total_Out = 0;
            $Total_POS = 0;
            if ($Method == 0) {
                $Title = 'Send Money';
                $Method_Text = 'Send';
                $Total_Send = $Price;
                $brgcol = 'black;';
            } else if ($Method == 1) {
                $Method_Text = 'Cash In';
                $Total_In = $Price;
                $brgcol = 'green;';
            } else if ($Method == 2) {
                $Method_Text = 'Cash Out';
                $brgcol = 'orange;';

                $Total_Out = $Price;
            } else if ($Method == 3) {
                $Method_Text = 'POS';
                $brgcol = 'blue;';
                if ($MenuID) {
                    $Total_POS = ($Price * $Quantity) + $Total_Topup_Price - ($Discount * $Quantity);
                } else {
                    $Total_POS = $Price;
                }
            } else {
                
            }
            if ($Method == 0 AND $Cancel == 0) {
                $Amount_Send = $Amount_Send + $Total_Send;
                $Line_Price = $Total_Send;
            } else if ($Method == 1 AND $Cancel == 0) {
                $Amount_In = $Amount_In + $Total_In;
                $Line_Price = $Total_In;
            } else if ($Method == 2 AND $Cancel == 0) {
                $Amount_Out = $Amount_Out + $Total_Out;
                $Line_Price = $Total_Out;
            } else if ($Method == 3 AND $Cancel == 0) {
                $Amount_POS = $Amount_POS + $Total_POS;
                $Line_Price = $Total_POS;
            }
            if ($Line_Price > $Max_Line_Price) {
                $Max_Line_Price = $Line_Price;
            }
            ?>
            <script type="text/javascript">
                var usr = localStorage.usr;
                var method = '<?= $Method ?>';
                var cancel = '<?= $Cancel ?>';
                var more_class = ' ';
                if (cancel === '1') {
                    more_class = ' strike ';
                }
                if (usr === 'admin' && method !== '3' && cancel !== '1') {
                    $("#C1").append("<div class='C CNODE C1 m<?= $Method ?>' cost='<?= $Line_Price ?>'><img src='../../img/action/suspend_gray.png' id='cancel-btn-<?= $ID ?>' onclick=\"suspend_drawer(<?= $ID ?>,<?= $Method ?>,'<?= $Line_Price ?>')\"></div>");
                } else {
                    $("#C1").append("<div class='C CNODE C1 m<?= $Method ?>' cost='<?= $Line_Price ?>'></div>");
                }

                $("#C2").append("<div class='C CNODE C2 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= $Date_Time_Print ?></div>");
                $("#C3").append("<div class='C CNODE C3  m<?= $Method ?> " + more_class + "' cost='<?= $Line_Price ?>' id='title-<?= $ID ?>'><?= $Title ?></div>");
                $("#C4").append("<div class='C CNODE C4 m<?= $Method ?>' cost='<?= $Line_Price ?>' style='background-color:<?= $brgcol ?>'><?= $Method_Text ?></div>");
                $("#C5").append("<div class='C CNODE C5 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= number_format($Total_Send, 2) ?></div>");
                $("#C6").append("<div class='C CNODE C6 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= number_format($Total_In, 2) ?></div>");
                $("#C7").append("<div class='C CNODE C7 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= number_format($Total_Out, 2) ?></div>");
                $("#C8").append("<div class='C CNODE C8 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= number_format($Total_POS, 2) ?></div>");
                $("#C9").append("<div class='C CNODE C9 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= $Staff_Name ?></div>");
                $("#C10").append("<div class='C CNODE C10 m<?= $Method ?>' cost='<?= $Line_Price ?>'><?= $Remark ?></div>");
            </script>
            <?php
            $CSV[] = array('', date('Y-m-d', $Date_Time), date('H:i:s', $Date_Time), $Title, $Method_Text, number_format($Total_Send, 2), number_format($Total_In, 2), number_format($Total_Out, 2), number_format($Total_POS, 2), $Staff_Name, $Remark);
            unset($Date_Time_Print);
            unset($Title);
            unset($Method_Text);
            unset($Total_Send);
            unset($Total_In);
            unset($Total_Out);
            unset($Total_POS);
        }
        $CSV[] = array('', '', '', 'Total', '', number_format($Amount_Send, 2), number_format($Amount_In, 2), number_format($Amount_Out, 2), number_format($Amount_POS, 2), '', '');
        //Generate CSV File
        $br_code = $br * 19;
        $CSVfilename = $br . "_drawer" . $br_code . ".csv";
        $fp = fopen($CSVfilename, 'w');
        foreach ($CSV as $fields) {
            fputcsv($fp, $fields);
        }
        fclose($fp);
        ?>
        <button class="export-button ui-btn-b ui-corner-all ui-btn ui-btn-icon-left ui-icon-export ui-btn-inline" onclick="window.location = '<?= $br ?>_drawer<?= $br_code ?>.csv';">Export</button>
        <script type="text/javascript">
            $("#amount_send").text("<?= number_format($Amount_Send, 2) ?>");
            $("#amount_in").text("<?= number_format($Amount_In, 2) ?>");
            $("#amount_out").text("<?= number_format($Amount_Out, 2) ?>");
            $("#amount_pos").text("<?= number_format($Amount_POS, 2) ?>");
            $("#range-1").attr("max", "<?= $Max_Line_Price ?>");
            $("#range-2").attr("max", "<?= $Max_Line_Price ?>").val("<?= $Max_Line_Price ?>");
        </script>
    </div>
</div>
</body>
<style type="text/css">
    .cash-opr{
        margin:3px;
        font-size:16px;
        width:120px;
        text-align:left;
    }
    .cash_type_search{
        float:left;
        width:33%;
    }
    .cash_type_search div{
        font-weight:bold;
        color:white;
        padding:10px 5px;
    }
    .PAYMENT-NOTE{
        float:left;
        width:150px;
        height:150px;
        margin:5px;
        border-radius:75px;
        border:solid 2px #000;
        box-shadow:0 0 20px #000;
    }
    .C{
        height:22px;
        font-size:13px;
        border-bottom:solid 1px #000;
        border-right:solid 1px #000;
        padding:5px;
    }
    .C1{
        text-align:center;
        width:30px
    }
    .C1 img{
        width:20px;
        cursor:pointer;
    }
    .C2{
        width:100px
    }
    .C3{
        width:160px
    }
    .C4{
        width:60px;
        text-align:center;
        color:#fff;
    }
    .C5{
        width:60px;
        text-align:right;
    }
    .C6{
        width:60px;
        text-align:right;
    }
    .C7{
        width:60px;
        text-align:right;
    }
    .C8{
        width:60px;
        text-align:right;
    }
    .C9{
        width:80px;
        text-align:center;
    }
    .C10{
        width:100px
    }
    .title{
        text-align:center;
        font:bold 12px sans-serif;
    }
    .C5-Total{
        background-color:black;
        color:#fff;
    }
    .C6-Total{
        background-color:green;
        color:#fff;
    }
    .C7-Total{
        background-color:orange;
        color:#fff;
    }
    .C8-Total{
        background-color:blue;
        color:#fff;
    }
    .C_Top{
        font:bold 14px sans-serif;
        text-align:center;
        border:solid 1px ;
        border-left:none;
        background-color:#ddd;
    }
    .strike{
        text-decoration: line-through;
    }
</style>
<?php
session_start();
$br = $_GET['br'];
$ww = $_GET['ww'];

include("../php-db-config.conf");
include("../php-config.conf");
$tab = $_GET['tab'];
$ord = $_GET['ord'];
$Set_Restaurant = $_GET['Set_Restaurant'];
include("convert_date.php");

if ($Set_Restaurant) {
    $MainDB = 'service_order_restaurant';
} else {
    $MainDB = 'service_order';
}

$Area_Height = 300;
$Area2_Height = 150;
$Area_Height = $Area_Height - 15;
$Area2_Height = $Area2_Height - 15;

/*
  $sql = "SELECT MAX(B.Username) AS Username,A.UsernameID AS UsernameID FROM $MainDB A ";
  $sql .= "INNER JOIN username B ON A.UsernameID=B.ID ";
  $sql .= "WHERE A.Cancel=0 AND A.Time_Order_YMD BETWEEN $fr AND $to AND A.BranchID IN($br) ";
  $sql .= "GROUP BY A.UsernameID ";
  $sql .= "ORDER BY B.Username;";
  $result = $conn_db->query($sql);
  $UserSelection = "<option value='%%'>All</option>";
  while ($db = $result->fetch_array()) {
  $UserSelection .= "<option value=" . $db['UsernameID'] . ">" . $db['Username'] . "</option>";
  }
  $UserSelection = "<select>$UserSelection</select>";
 */
?>

<div id="STAFF-SECTION" style="display:none;position:fixed;top:10px;right:10px;"><?= $UserSelection ?></div>
<div style="width:100%;overflow-x:auto;">
    <?php
//echo "<br/> $Min_Session($Min_Session_Buffer) to $Max_Session($Max_Session_Buffer) = $Total_Value_Session <br/>";

    $Month6_Start_yMD = date("ymd", mktime(0, 0, 0, substr($fr, 2, 2) - 3, substr($fr, 4, 2), substr($fr, 0, 2)));
    $Month6_Start_YMD = date("Ymd", mktime(0, 0, 0, substr($fr, 2, 2) - 3, substr($fr, 4, 2), substr($fr, 0, 2)));
    $LG_Total_Day = (strtotime("20" . $to) - strtotime($Month6_Start_YMD)) / 86400;
    


    $sql = "SELECT SUM(Total) AS Daily,CONCAT('20',Date_YMD) AS YMD FROM daily_summary ";
    $sql .= "WHERE Date_YMD BETWEEN $Month6_Start_yMD AND 20$to ";
    $sql .= "GROUP BY Date_YMD ";
    $sql .= "ORDER BY Date_YMD;";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        $Daily = $db[0];
        $YMD = $db[1];
        // echo "<br/>" . $Daily . " -> " . $YMD;
        $LG_Daily[$YMD] = $Daily;
    }
    //print_r($LG_Daily);
    $LG_Max = MAX($LG_Daily);
    $LG_Count = COUNT($LG_Daily);
    $LG_Width = ($ww - 10) / $LG_Total_Day;
    $Svg_X = 0;
    $real_to = "20" . $to;
    for ($i = $Month6_Start_YMD; $i <= $real_to; $i += 0) {
        $today_record = $LG_Daily[$i];
        $Today_Text = date("d/m/Y (D) ", mktime(0, 0, 0, substr($i, 4, 2), substr($i, 6, 2), substr($i, 0, 4)));

        $graph_height = (100 / $LG_Max) * $today_record;
        //echo $i . " -> " . $today_record . "<br/>";
        //#####Draw Line
        $Svg_Y = 100 - $graph_height;
        $SVG_Draw .= "L$Svg_X $Svg_Y ";
        $Svg_X += $LG_Width;

        //####Draw Circle
        $Svg_Circle_X = $Svg_X - $LG_Width;

        $Svg_Circle .= "<circle cx='$Svg_Circle_X' cy='$Svg_Y' r='5' stroke='black' stroke-width='1' fill='#aaaaaa' class='report-circle' title='" . $Today_Text . ": " . number_format($today_record) . "' />";
        if (substr($i, 6, 2) == '01') {

            $Svg_Line .= "<line x1='$Svg_Circle_X' y1='0' x2='$Svg_Circle_X' y2='100' style='stroke:#aaaaaa;stroke-width:0.5' />";
        }

        $Next = date("Ymd", mktime(0, 0, 0, substr($i, 4, 2), substr($i, 6, 2) + 1, substr($i, 0, 4)));
        $i = $Next;
    }
    $Svg_LastX = $Svg_X - $LG_Width;
    $SVG_Draw .= "L$Svg_LastX 100 ";
    ?>
    <div id="month6" style="height:100px;background-color:#111;overflow:hidden">
        <svg height="100" width="<?= $ww ?>">
        <path style="fill:url(#grad1);fill-rule:evenodd;stroke:#aaaaaa;stroke-width:2px;"
              d="M0 100 <?= $SVG_Draw ?>  " />
              <?= $Svg_Circle ?>
              <?= $Svg_Line ?>
        <defs>
        <linearGradient id="grad1" x1="0%" y1="0%" x2="0%" y2="100%">
        <stop offset="0%" style="stop-color:#cccccc;stop-opacity:1" />
        <stop offset="100%" style="stop-color:#444444;stop-opacity:1" />
        </linearGradient>
        </defs>
        </svg>
    </div>
    <?php
    $sql = "SELECT SUM((Price-Discount)*Quantity),Time_Session,SUM(IF(MachineID<>0,Quantity,0)) FROM $MainDB ";
    $sql .= "WHERE Cancel=0 AND Time_Order_YMD BETWEEN $fr AND $to ";
//$sql.="AND Time_Session BETWEEN $Min_Session_Buffer AND $Max_Session_Buffer ";
    $sql .= "GROUP BY Time_Session ";
    $sql .= "ORDER BY Time_Session;";
    $result = $conn_db->query($sql);


    while ($db = $result->fetch_array()) {
        $Income = $db[0];
        $Time_Session = $db[1];
        $Quantity = $db[2];
        $Arr_Time_Session[] = $Time_Session;
        $Arr_Income[$Time_Session] = $Income;
        $Arr_Quantity[$Time_Session] = $Quantity;
    }

    $Min_Session = MIN($Arr_Time_Session);
    $Max_Session = MAX($Arr_Time_Session);
    $Min_Session_Buffer = floor($Min_Session / 4) * 4;
    $Max_Session_Buffer = (floor($Max_Session / 4) * 4) + 3;
    $Total_Value_Session = $Max_Session_Buffer - $Min_Session_Buffer;
    if ($Arr_Income) {
        $Highest_Value = MAX($Arr_Income);
        $Total_Income = array_sum($Arr_Income);
        $Total_Quantity = array_sum($Arr_Quantity);
        $draw_table = "<table style='width:100%' callpadding='0' cellspacing='1' bgcolor='#000'>";
        $draw_table .= "<tr>";

        for ($ts = $Min_Session_Buffer; $ts <= $Max_Session_Buffer; $ts++) {
            $Bar_Quantity = $Arr_Income[$ts];
            $Bar_Height = floor(($Area_Height / $Highest_Value) * $Bar_Quantity);
            if (!$Bar_Height) {
                $Bar_Height = 1;
            }
            $Top_Padding = $Area_Height - $Bar_Height;

            $Total_TH = $Total_TH + $Bar_Quantity;
            if ($ts % 4 == 3) {
                $th_Quantity[] = $Total_TH;
                unset($Total_TH);
            }


            if ($Bar_Quantity >= 1000) {
                $Bar_Quantity_Print = number_format($Bar_Quantity / 1000, 1) . "k";
            } else if ($Bar_Quantity > 0) {
                $Bar_Quantity_Print = number_format($Bar_Quantity);
            } else {
                $Bar_Quantity_Print = '';
            }

            $draw_table .= "<td>";
            $draw_table .= "<div class='bar-title' style='margin-top:" . $Top_Padding . "px;'>" . $Bar_Quantity_Print . "</div>";

            if ($Bar_Quantity == $Highest_Value) {
                $Golden_Session = $ts;
                $draw_table .= "<div class='bar' style='height:" . $Bar_Height . "px;'><div class='star_session'><img src='../../img/star.png?xxx1'></div></div>";
            } else {
                $draw_table .= "<div class='bar' style='height:" . $Bar_Height . "px;'><div class='star_session'></div></div>";
            }
            $draw_table .= "</td>";
        }

        $draw_table .= "</tr>";
        $draw_table .= "<tr>";

        for ($ts = $Min_Session_Buffer; $ts < $Max_Session_Buffer + 1; $ts += 4) {
            $draw_table .= "<td class='time_telling' colspan='4'>" . floor($ts / 4) . ":00</td>";
        }
        $draw_table .= "</tr>";
        $draw_table .= "<tr>";
        $Highest_Hour = MAX($th_Quantity);
        $sp = 0;

        for ($ts = $Min_Session_Buffer; $ts < $Max_Session_Buffer + 1; $ts += 4) {
            $Hour_Quantity = $th_Quantity[$sp];
            $sp++;
            $Hour_Height = floor(($Area2_Height / $Highest_Hour) * $Hour_Quantity);
            if ($Hour_Quantity) {
                $Hour_Quantity_Print = number_format($Hour_Quantity);
            } else if (!$Hour_Quantity) {
                $Hour_Quantity_Print = '';
            }
            if (!$Hour_Height) {
                $Hour_Height = 1;
            }
            $Bottom_Padding = $Area2_Height - $Hour_Height;


            $draw_table .= "<td colspan='4'>";

            if ($Hour_Quantity == $Highest_Hour) {
                $Golden_Hour = $ts;
                $draw_table .= "<div class='bar2' style='height:" . $Hour_Height . "px;'><div class='star_hour'><img src='../../img/star.png?xxx1'></div></div>";
            } else {
                $draw_table .= "<div class='bar2' style='height:" . $Hour_Height . "px;'><div class='star_hour'></div></div>";
            }

            $draw_table .= "<div class='bar-title2' style='margin-bottom:" . $Bottom_Padding . "px;'>$Hour_Quantity_Print</div>";
            $draw_table .= "</td>";
        }
        $draw_table .= "</tr>";
        $draw_table .= "</table>";
    }
    echo $draw_table;
    ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".report-circle").mouseenter(function (event) {
            var this_val = $(this).attr("title");
            var this_X = event.pageX;
            var this_Y = event.pageY;
            show_LG(this_val, this_X, this_Y);
        });
        $(".report-circle").mouseout(function () {
            $("#LG_Explain").hide(200);
        });
    });
    function show_LG(val, x, y) {

        var innerWidth = window.innerWidth;
        if (x > innerWidth - 200) {
            x = x - 200;
        }
        $("#LG_Explain").show(200).css({
            top: y - 20,
            left: x + 10
        }).text(val);
    }

    $(".star_session img").fadeIn(1000, function () {
        $(".star_session img").animate({width: 30, right: 3}, 1000, function () {

            $(".star_hour img").fadeIn(1000, function () {
                $(".star_hour img").animate({width: 30, right: 3}, 1000);
            });
        });
    });
    $(".star_hour").fadeIn(1000);
</script>
<div style="position:absolute;background-color:white;display:none;font:13px sans-serif;padding:3px;" id="LG_Explain"></div>
<div style="clear:both;height:50px;"></div>
<div class="report-box2">
    <div class="box-title">Today Income</div>
    <div id="today_record"><?= number_format($Total_Income, 2) ?></div>
</div>
<div class="report-box2">
    <div class="box-title">Total Quantity</div>
    <div><?= number_format($Total_Quantity, 2) ?></div>
</div>
<div class="report-box2">
    <div class="box-title">Golden Time</div>
    <div>
        <?php
        $Golden_Session_Start = floor($Golden_Session / 4) . ":" . str_pad(($Golden_Session % 4) * 15, 2, '0', STR_PAD_LEFT);
        $Golden_Session_End = floor(($Golden_Session + 1) / 4) . ":" . str_pad((($Golden_Session + 1) % 4) * 15, 2, '0', STR_PAD_LEFT);
        echo $Golden_Session_Start;
        echo"-";
        echo $Golden_Session_End;
        ?>
    </div>
</div>
<div class="report-box2">
    <div class="box-title">Golden Hour</div>
    <div>
        <?php
        echo floor($Golden_Hour / 4) . ":00";
        ?>
    </div>
</div>
<div style="clear:both;border-top:solid 3px #888;padding:10px;display:none;" id="load_section2_button">
    <button style="padding:10px 30px;" onclick="load_section2()">Show More</button>
</div>
<div id="section2" style=""></div>
<div id="section4" style="clear:both;"></div>
<style type="text/css">
    .bar-cover{
        float:left;
        border-right:solid 1px black;
    }
    .bar-title{
        width:20px;
        font-size:12px;
        text-align:center;
        height:15px;
        color:#fff;
        margin-left:auto;
        margin-right:auto;
    }
    .bar-title2{
        font-size:14px;
        text-align:center;
        height:15px;
        color:#fff;
    }
    .bar{
        position:relative;
        background:url('../../img/Bar/yellow-bg.png');
        background-size: 100%;
        border-radius: 3px 3px 0 0;
        z-index:50;
    }
    .bar2{
        position:relative;
        background:url('../../img/Bar/sky-bg.png');
        background-size: 100%;
        border-radius: 0 0 5px 5px;
        z-index:50;
    }
    .time_telling{
        background-color:#ccc;
        text-align:center;
        font:bold 13px sans-serif;
    }
    .star_session{
        position:absolute;
        right:10px;
        top:3px;
        width:30px;
    }
    .star_hour{
        position:absolute;
        right:70px;
        bottom:3px;
        width:30px;
    }
    .star_session img,.star_hour img{
        width:100px;
        display:none;
        z-index:100;
    }
</style>


<?php
include("../php-db-config.conf");
include("convert_date.php");
$today_record = $_GET['today_record'];
$br = $_GET['br'];
$Set_Restaurant = $_GET['Set_Restaurant'];
if ($Set_Restaurant) {
    $MainDB = 'service_order_restaurant';
} else {
    $MainDB = 'service_order';
}
$Query_Start = microtime();


if ($fr == $to) {
    for ($i = 1; $i <= 4; $i++) {
        ?>
        <div class="report-box2">
            <?php
            //########################### Section 1
            if ($i == 1) {
                $text = 'VS Yesterday';
                echo "<div class='box-title'>$text</div>";
                $val1 = $fr - 1;
                $val2 = $to;


                $Service_Yesterday_Start = date("ymd", $val1);
                $sql1 = "SELECT SUM((Price-Discount)*Quantity) FROM $MainDB WHERE Cancel=0 AND Time_Order_YMD=$val1";
                $result1 = $conn_db->query($sql1);


                $list1 = $result1->fetch_row();
                $yesterday_record = $list1[0];

                if ($today_record > $yesterday_record) {
                    $section2 = $today_record - $yesterday_record;
                    if ($yesterday_record > 0) {
                        $section2_percent = (100 - (((100 / $today_record) * $yesterday_record)));
                    } else {
                        $section2_percent = 100;
                    }
                    $indication_img = 'arrow_green.png';
                } else {
                    $section2 = $yesterday_record - $today_record;

                    if ($today_record > 0) {
                        $section2_percent = (100 - (((100 / $yesterday_record) * $today_record))) * (-1);
                    } else {
                        $section2_percent = -100;
                    }
                    $indication_img = 'arrow_red.png';
                }
                $today_amount = $section2;

                echo "<div>" . number_format($section2, 2) . "</div>";
                echo "<div style='font:normal 14px sans-serif'>Yesterday : " . number_format($yesterday_record, 2) . "</div>";
                echo "<div style='position:absolute;left:10px;top:30px;width:30px'><img src='../../img/" . $indication_img . "'></div>";
                echo "<div style='position:absolute;left:10px;top:60px;font:bold 12px sans-serif;width:30px'>" . floor($section2_percent) . "%</div>";
            }

            //########################### Section 3
            else if ($i == 2) {
                $date_name = date("ld", strtotime('20' . $fr - 7));
                $text = "VS last $date_name" . "<span style='font-size:12px'>" . date("S", strtotime('20' . $fr - 7)) . "</span>";
                echo "<div class='box-title'>$text</div>";
                $val1 = $fr - 7;
                $val2 = $to - 7;
                $sql1 = "SELECT SUM((Price-Discount)*Quantity) FROM $MainDB WHERE Cancel=0 AND Time_Order_YMD ='$val1' ";
                $result1 = $conn_db->query($sql1);
                $list1 = $result1->fetch_row();
                $yesterday_record = $list1[0];

                if ($today_record > $yesterday_record) {
                    $section2 = $today_record - $yesterday_record;
                    if ($yesterday_record > 0) {
                        $section2_percent = (100 - (((100 / $today_record) * $yesterday_record)));
                    } else {
                        $section2_percent = 100;
                    }
                    $indication_img = 'arrow_green.png';
                } else {
                    $section2 = $yesterday_record - $today_record;

                    if ($today_record > 0) {
                        $section2_percent = (100 - (((100 / $yesterday_record) * $today_record))) * (-1);
                    } else {
                        $section2_percent = -100;
                    }
                    $indication_img = 'arrow_red.png';
                }

                echo "<div>" . number_format($section2, 2) . "</div>";
                echo "<div style='font:normal 14px sans-serif'>Last $date_name" . "<span style='font-size:12px'>" . date("S", strtotime('20' . $fr - 7)) . "</span> : " . number_format($yesterday_record, 2) . "</div>";
                echo "<div style='position:absolute;left:10px;top:30px;width:30px'><img src='../../img/" . $indication_img . "'></div>";
                echo "<div style='position:absolute;left:10px;top:60px;font:bold 12px sans-serif;width:30px'>" . floor($section2_percent) . "%</div>";
            }


            //########################### Section 3
            else if ($i == 3) {

                $text = "Week Accumulate";
                echo "<div class='box-title'>$text</div>";
                $fr_dayoftheweek = date("N", strtotime('20' . $fr));
                $fr_U = strtotime('20' . $fr);
                $to_U = strtotime('20' . $to);
                $val1 = $fr_U - (86400 * 13);
                for ($wtd = $val1; $wtd <= $to_U; $wtd += 86400) {
                    $total_date[] = $wtd;
                    $total_in .= date("ymd", $wtd) . ",";
                }
                $sql1 = "SELECT Time_Order_YMD,SUM((Price-Discount)*Quantity) FROM $MainDB WHERE Cancel=0 AND Time_Order_YMD IN(" . substr($total_in, 0, -1) . ") GROUP BY Time_Order_YMD ORDER BY Time_Order_YMD";
                $result1 = $conn_db->query($sql1);
                while ($db1 = $result1->fetch_array()) {
                    $daily_date = $db1[0];
                    $daily_record = $db1[1];
                    $wtd_record = 'wtd' . $daily_date;
                    $$wtd_record = $daily_record;
                }
                $count = 1;
                foreach ($total_date as &$total_date_each) {
                    $each_result = 'wtd' . date("ymd", $total_date_each);
                    $day_of_week = date("N", $total_date_each);
                    $daily_result = $$each_result;
                    if ($count <= 7 AND $day_of_week <= $fr_dayoftheweek) {
                        $Total_Last_Week = $Total_Last_Week + $daily_result;
                    } else if ($day_of_week <= $fr_dayoftheweek) {
                        $Total_This_Week = $Total_This_Week + $daily_result;
                    }
                    $count++;
                }
                $max_daily = MAX($find_max);
                $last_accumulate_record = $Total_Last_Week;
                $accumulate_record = $Total_This_Week;

                if ($accumulate_record > $last_accumulate_record) {
                    $section2 = $accumulate_record - $last_accumulate_record;
                    if ($last_accumulate_record > 0) {
                        $section3_percent = (100 - (((100 / $accumulate_record) * $last_accumulate_record)));
                    } else {
                        $section3_percent = 100;
                    }
                    $indication_img = 'arrow_green.png';
                } else {
                    $section2 = $last_accumulate_record - $accumulate_record;

                    if ($accumulate_record > 0) {
                        $section3_percent = (100 - (((100 / $last_accumulate_record) * $accumulate_record))) * (-1);
                    } else {
                        $section3_percent = -100;
                    }
                    $indication_img = 'arrow_red.png';
                }
                ?>
                <div><?= number_format($accumulate_record, 2) ?></div>
                <div style="font:normal 14px sans-serif;border-bottom:solid 1px gray;">Last week accum : <?= number_format($last_accumulate_record, 2) ?></div>
                <div style="position:absolute;left:10px;top:30px;width:30px"><img src='../../img/<?= $indication_img ?>'></div>
                <div style="position:absolute;left:10px;top:60px;font:bold 12px sans-serif;width:30px"><?= floor($section3_percent) ?>%</div>
                <div style="position:absolute;bottom:0;height:50px;width:300px;">
                    <?php
                    $count = 1;
                    $count_bar = 0;
                    unset($bar_height);
                    unset($bar_date);
                    foreach ($total_date as &$total_date_each) {
                        $each_result = 'wtd' . date("ymd", $total_date_each);
                        $day_of_week = date("N", $total_date_each);
                        $day_text = date("D", $total_date_each);
                        $daily_result = $$each_result;
                        if ($count > 7 AND $day_of_week <= $fr_dayoftheweek) {
                            $bar_height[] = $daily_result;
                            $bar_date[] = $day_text;
                            $count_bar++;
                        }
                        $count++;
                    }
                    $max_daily = MAX($bar_height);
                    $bar_ratio = $max_daily / 40;
                    $bar_width = 300 / $count_bar;

                    for ($bar = 0; $bar < $count_bar; $bar++) {
                        $each_height = $bar_height[$bar] / $bar_ratio;
                        ?>
                        <div style="float:left;position:absolute;bottom:0;left:<?= ($bar_width * $bar) + $bar ?>px;"> 
                            <div class="bar" id="bar-wtd-<?= $bar ?>" style="font-size:10px;color:#000;background-color:yellow;width:<?= $bar_width ?>;height:0;"><?php
                                if ($bar_height[$bar] > 0) {
                                    echo number_format($bar_height[$bar]);
                                }
                                ?></div>
                            <div style="font-size:8px;background-color:gray;width:<?= $bar_width ?>;height:10px;"><?= $bar_date[$bar] ?></div>
                        </div>
                        <script type="text/javascript">
                            $("#bar-wtd-<?= $bar ?>").animate({height:<?= $each_height ?>}, <?= 300 + ($bar * 200) ?>);
                        </script>
                        <?php
                        unset($each_height);
                    }
                    ?>
                </div>
                <?php
            }
            //########################### Section 4
            else if ($i == 4) {
                unset($total_date);
                unset($total_in);
                $text = "Month Accumulate";
                echo "<div class='box-title'>$text</div>";
                $fr_dayofthemonth = substr($fr, 4, 2);
                $val1 = date("U", mktime(0, 0, 0, substr($fr, 2, 2) - 1, 1, '20' . substr($fr, 0, 2)));
                $val2 = date("U", mktime(0, 0, 0, substr($fr, 2, 2), substr($fr, 4, 2), '20' . substr($fr, 0, 2)));
                for ($mtd = $val1; $mtd <= $val2; $mtd += 86400) {
                    $total_date[] = $mtd;
                    $total_in .= date("ymd", $mtd) . ",";
                }
                $sql1 = "SELECT Time_Order_YMD,SUM((Price-Discount)*Quantity) FROM $MainDB WHERE Cancel=0 AND Time_Order_YMD IN(" . substr($total_in, 0, -1) . ") GROUP BY Time_Order_YMD ORDER BY Time_Order_YMD";
                $result1 = $conn_db->query($sql1);
                while ($db1 = $result1->fetch_array()) {
                    $daily_date = $db1[0];
                    $daily_record = $db1[1];
                    $wtd_record = 'mtd' . $daily_date;
                    $$wtd_record = $daily_record;
                }
                $count = 1;
                foreach ($total_date as &$total_date_each) {
                    $each_result = 'mtd' . date("ymd", $total_date_each);
                    $each_ymd = date("ymd", $total_date_each);
                    $daily_result = $$each_result;

                    $last_month_max = date("ymd", mktime(0, 0, 0, substr($fr, 2, 2) - 1, substr($fr, 4, 2), '20' . substr($fr, 0, 2)));
                    $this_month_start = date("ymd", mktime(0, 0, 0, substr($fr, 2, 2), 1, '20' . substr($fr, 0, 2)));

                    if ($each_ymd <= $last_month_max) {
                        $Total_Last_Month = $Total_Last_Month + $daily_result;
                    } else if ($each_ymd >= $this_month_start) {
                        $Total_This_Month = $Total_This_Month + $daily_result;
                    }
                    $count++;
                }
                $last_accumulate_record = $Total_Last_Month;
                $accumulate_record = $Total_This_Month;

                if ($accumulate_record > $last_accumulate_record) {
                    $section2 = $accumulate_record - $last_accumulate_record;
                    if ($last_accumulate_record > 0) {
                        $section3_percent = (100 - (((100 / $accumulate_record) * $last_accumulate_record)));
                    } else {
                        $section3_percent = 100;
                    }
                    $indication_img = 'arrow_green.png';
                } else {
                    $section2 = $last_accumulate_record - $accumulate_record;

                    if ($accumulate_record > 0) {
                        $section3_percent = (100 - (((100 / $last_accumulate_record) * $accumulate_record))) * (-1);
                    } else {
                        $section3_percent = -100;
                    }
                    $indication_img = 'arrow_red.png';
                }
                ?>
                <div><?= number_format($accumulate_record, 2) ?></div>
                <div style="font:normal 14px sans-serif;border-bottom:solid 1px gray;">Last month accum : <?= number_format($last_accumulate_record, 2) ?></div>
                <div style="position:absolute;left:10px;top:30px;width:30px"><img src='../../img/<?= $indication_img ?>'></div>
                <div style="position:absolute;left:10px;top:60px;font:bold 12px sans-serif;width:30px"><?= floor($section3_percent) ?>%</div>
                <div style="position:absolute;bottom:0;height:50px;width:300px;">
                    <?php
                    $count = 1;
                    $count_bar = 0;
                    unset($bar_height);
                    unset($bar_date);
                    foreach ($total_date as &$total_date_each) {
                        $each_result = 'mtd' . date("ymd", $total_date_each);
                        $each_ymd = date("ymd", $total_date_each);
                        $day_of_month = date("d", $total_date_each);
                        $day_of_month_full = date("d-M-Y", $total_date_each);
                        $daily_result = $$each_result;
                        $this_month_start = date("ymd", mktime(0, 0, 0, substr($fr, 2, 2), 1, '20' . substr($fr, 0, 2)));
                        if ($each_ymd >= $this_month_start) {
                            $bar_height[] = $daily_result;
                            $bar_date[] = $day_of_month;
                            $bar_full_date[] = $day_of_month_full;
                            $count_bar++;
                        }
                        $count++;
                    }
                    $max_daily = MAX($bar_height);
                    $bar_ratio = $max_daily / 40;
                    $bar_width = 290 / $count_bar;

                    for ($bar = 0; $bar < $count_bar; $bar++) {
                        $each_height = floor($bar_height[$bar] / $bar_ratio);
                        ?>
                        <div style="float:left;position:absolute;bottom:0;left:<?= ($bar_width * $bar) + $bar ?>px;"> 
                            <div class="bar" id="bar-mtd-<?= $bar ?>" style="font-size:10px;color:#000;background-color:yellow;width:<?= $bar_width ?>;height:0;" title="<?= $bar_full_date[$bar] . " : " . number_format($bar_height[$bar]) ?>"></div>
                            <div style="font-size:8px;background-color:gray;width:<?= $bar_width ?>;height:10px;"><?= $bar_date[$bar] ?></div>
                        </div>
                        <script type="text/javascript">
                            $("#bar-mtd-<?= $bar ?>").animate({height:<?= $each_height ?>}, <?= 100 + ($bar * 50) ?>);
                        </script>
                        <?php
                        unset($each_height);
                    }
                    ?>
                </div>
                <?php
            }
            $Query_End = microtime();
            $Query_Load = $Query_End - $Query_Start;
            //  echo "<div style='font-size:10px;color:red;'>LOAD>" . $Query_Load . "</div>";
            echo "</div>";
        }
    }

    for ($i = 5; $i <= 5; $i++) {
        ?>
        <div class="report-box2">
            <?php
            //########################### Section 6
            if ($i == 5) {
                $text = "Machine Category";
                echo "<div class='box-title'>$text</div>";
                $val1 = $fr;
                $val2 = $to;
                $sql1 = "SELECT SUM((Price-" . $MainDB . ".Discount)*Quantity) as Total_Price,Type FROM $MainDB INNER JOIN machine B ON " . $MainDB . ".MachineID=B.ID WHERE Cancel=0 AND Time_Order_YMD BETWEEN $val1 AND $val2 GROUP BY Type ORDER BY 1 DESC";
                $result1 = $conn_db->query($sql1);
                $loop = 1;
                while ($db1 = $result1->fetch_array()) {
                    $Total_Price = $db1['Total_Price'];
                    $Type = $db1['Type'];
                    if ($loop <= 4) {
                        $chart_list .= $Total_Price . ",";
                        $Total_Top4 += $Total_Price;
                        $TTL_Price[] = $Total_Price;
                        $TTL_Type[] = $Type;
                    }
                    $Total_Amount += $Total_Price;
                    $loop++;
                }
                $other_type = $Total_Amount - $Total_Top4;
                $chart_list = $chart_list . $other_type;
                ?>
                <div style="float:left;width:35%" id="pie-cat">
                    <?php
                    include("../../img/chart/pie.svg");
                    ?>
                </div>
                <script type="text/javascript">chart_pie('pie-cat', '<?= $chart_list ?>', '#008800,#00ff00,#0000ff,#A52A2A,#999')</script>
                <?php
                for ($k = 0; $k <= 4; $k++) {
                    if ($k == 0) {
                        $bgcol = '#008800';
                        $Total_Value = round((100 / $Total_Amount) * $TTL_Price[$k]) . "% ";
                    } else if ($k == 1) {
                        $bgcol = '#00ff00';
                        $Total_Value = round((100 / $Total_Amount) * $TTL_Price[$k]) . "% ";
                    } else if ($k == 2) {
                        $bgcol = '#0000ff';
                        $Total_Value = round((100 / $Total_Amount) * $TTL_Price[$k]) . "% ";
                    } else if ($k == 3) {
                        $bgcol = '#A52A2A';
                        $Total_Value = round((100 / $Total_Amount) * $TTL_Price[$k]) . "% ";
                    } else if ($k == 4) {
                        $bgcol = '#999';
                        $Type_Name = 'Others';
                        $Total_Value = "";
                    }
                    echo "<div style='float:left;'>";
                    echo"<div style='border:solid 2px #444;clear:both;float:left;width:18px;height:18px;background-color:" . $bgcol . "'></div>";
                    echo "<div style='float:left;width:160px;font:normal 12px sans-serif;text-align:left;'>" . $Total_Value . $TTL_Type[$k] . "$Type_Name</div>";
                    echo "</div>";
                    $Amount_Value = $Amount_Value + $Total_Value;
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
    <div class="report-box2" style="clear:both;width:625px;height:400px">
        <?php
        //########################### Section 6
        $text = "Branch";
        echo "<div class='box-title'>สาขา</div>";
        $val1 = $fr;
        $val2 = $to;
        $sql1 = "SELECT SUM((Price-A.Discount)*Quantity) as Total_Price,Name,A.BranchID FROM $MainDB A LEFT JOIN branch B ON A.BranchID=B.ID WHERE Cancel=0 AND Time_Order_YMD BETWEEN $val1 AND $val2 GROUP BY A.BranchID ORDER BY 1 DESC";
        $result1 = $conn_db->query($sql1);
        $loop = 1;
        while ($db1 = $result1->fetch_array()) {
            $BranchID = $db1['BranchID'];
            $Total_Price1 = $db1['Total_Price'];
            $Type = $db1['Name'];
            if($BranchID==0){
                $Type='Ambient_Test';
            }
            if ($loop <= 10) {
                $chart_list1 .= $Total_Price1 . ",";
                $Total_Top41 += $Total_Price1;
                $TTL_Price1[] = $Total_Price1;
                $TTL_Type1[] = $Type;
            }
            $Total_Amount1 += $Total_Price1;
            $loop++;
        }
        $other_type = $Total_Amount1 - $Total_Top41;
        $chart_list1 = $chart_list1 . $other_type;
        ?>
        <div style="float:left;width:50%" id="pie-cat2">
            <?php
            include("../../img/chart/pie.svg");
            ?>
        </div>
        <div style="float:left;width:45%;padding:40px 0 0 20px;">
            <script type="text/javascript">chart_pie('pie-cat2', '<?= $chart_list1 ?>', '#008800,#00ff00,#0000ff,#A52A2A,#F7DC6F,#CACFD2,#283747,#943126,#2874A6,#AED6F1,#F8C471')</script>
            <?php
            for ($k = 0; $k <= 10; $k++) {
                if ($k == 0) {
                    $bgcol = '#008800';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 1) {
                    $bgcol = '#00ff00';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 2) {
                    $bgcol = '#0000ff';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 3) {
                    $bgcol = '#A52A2A';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 4) {
                    $bgcol = '#F7DC6F';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 5) {
                    $bgcol = '#CACFD2';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 6) {
                    $bgcol = '#283747';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 7) {
                    $bgcol = '#943126';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 8) {
                    $bgcol = '#2874A6';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 9) {
                    $bgcol = '#AED6F1';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                } else if ($k == 10) {
                    $bgcol = '#F8C471';
                    $Total_Value1 = round((100 / $Total_Amount1) * $TTL_Price1[$k]) . "% ";
                }
                echo "<div style='clear:both;'>";
                echo"<div style='border:solid 2px #444;clear:both;float:left;width:18px;height:18px;background-color:" . $bgcol . "'></div>";
                echo "<div style='float:left;font:normal 12px sans-serif;text-align:left;'>";
                echo "<div style='float:left;width:30px;text-align:right;'>" . $Total_Value1 . "</div>";
                echo "<div style='float:left;margin-left:5px;'>" . $TTL_Type1[$k] . "</div>";
                echo "</div>";
                echo "</div>";
                $Amount_Value = $Amount_Value + $Total_Value;
            }
            ?>
        </div>
    </div>
    <div class="report-box2" style="width:625px;height:400px">
        <?php
        //########################### Section 6
        $text = "Machine";
        echo "<div class='box-title'>$text</div>";
        $val1 = $fr;
        $val2 = $to;
        $sql1 = "SELECT SUM((Price-" . $MainDB . ".Discount)*Quantity) as Total_Price,Name FROM $MainDB INNER JOIN machine B ON " . $MainDB . ".MachineID=B.ID WHERE Cancel=0 AND Time_Order_YMD BETWEEN $val1 AND $val2 GROUP BY Name ORDER BY 1 DESC";
        $result1 = $conn_db->query($sql1);
        $loop = 1;
        while ($db1 = $result1->fetch_array()) {
            $Total_Price2 = $db1['Total_Price'];
            $Type = $db1['Name'];
            if ($loop <= 10) {
                $chart_list2 .= $Total_Price2 . ",";
                $Total_Top5 += $Total_Price2;
                $TTL_Price2[] = $Total_Price2;
                $TTL_Type2[] = $Type;
            }
            $Total_Amount2 += $Total_Price2;
            $loop++;
        }
        $other_type2 = $Total_Amount2 - $Total_Top5;
        $chart_list2 = $chart_list2 . $other_type2;
        ?>
        <div style="float:left;width:50%" id="pie-cat3">
            <?php
            include("../../img/chart/pie.svg");
            ?>
        </div>
        <script type="text/javascript">chart_pie('pie-cat3', '<?= $chart_list2 ?>', '#008800,#00ff00,#0000ff,#A52A2A,#F7DC6F,#CACFD2,#283747,#943126,#2874A6,#AED6F1,#F8C471')</script>
        <div style="float:left;width:45%;padding:40px 0 0 20px;">
            <?php
            for ($m = 0; $m <= 10; $m++) {
                if ($m == 0) {
                    $bgcol = '#008800';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 1) {
                    $bgcol = '#00ff00';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 2) {
                    $bgcol = '#0000ff';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 3) {
                    $bgcol = '#A52A2A';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 4) {
                    $bgcol = '#F7DC6F';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 5) {
                    $bgcol = '#CACFD2';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 6) {
                    $bgcol = '#283747';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 7) {
                    $bgcol = '#943126';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 8) {
                    $bgcol = '#2874A6';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 9) {
                    $bgcol = '#AED6F1';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                } else if ($m == 10) {
                    $bgcol = '#F8C471';
                    $Total_Value2 = round((100 / $Total_Amount2) * $TTL_Price2[$m]) . "% ";
                }
                echo "<div style='clear:both;'>";
                echo"<div style='border:solid 2px #444;clear:both;float:left;width:18px;height:18px;background-color:" . $bgcol . "'></div>";
                echo "<div style='float:left;font:normal 12px sans-serif;text-align:left;'>";
                echo "<div style='float:left;width:30px;text-align:right;'>" . $Total_Value2 . "</div>";
                echo "<div style='float:left;margin-left:5px;'>" . $TTL_Type2[$m] . "</div>";
                echo "</div>";
                echo "</div>";
                $Amount_Value2 = $Amount_Value2 + $Total_Value2;
            }
            ?>
        </div>          
    </div>          
    <div style="clear:both;border-top:solid 3px #888;padding:10px;" id="load_section4_button">
        <button style="padding:10px 30px;" onclick="load_section4()">Show More</button>
    </div>
    <div style="clear:both;"></div>
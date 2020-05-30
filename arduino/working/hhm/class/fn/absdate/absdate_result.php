<?php
$Selected_Date = date('U', mktime(0, 0, 0, $selectedmonth, $date, $selectedyear));
$Current_Date = date('U', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
?>
<table width="100%" class="abs-calendar" cellspacing="0">
    <tr>
        <th>&nbsp;</th>
        <th>Sun</th>
        <th>Mon</th>
        <th>Tue</th>
        <th>Wed</th>
        <th>Thu</th>
        <th>Fri</th>
        <th>Sat</th>
    </tr>
    <?php
    for ($Week = 0; $Week <= 5; $Week++) {
        $WeekNo = date("W", mktime(0, 0, 0, $month, ($Week * 7) + 1, $year));
        $Month_Start_Date = date("N", mktime(0, 0, 0, $month, ($Week * 7) + 1, $year));
        $Calendar_Start_Date = date("U", mktime(0, 0, 0, $month, (($Week * 7) + 1) - ($Month_Start_Date), $year));
        ?>
        <tr>
            <td><?php echo $WeekNo; ?></td>
            <?php
            for ($Day = 0; $Day <= 6; $Day++) {
                $U = $Calendar_Start_Date + ($Day * 86400);
                $This_Day = date("j", $U);
                ?>
                <td onclick="choosedate('<?=date("Y",$U)?>','<?=date("n",$U)?>','<?=$This_Day?>')"class="<?php
        if ($month == date('n', $U)) {
            echo 'current-month ';
        } else {
            echo 'other-month ';
        }
        
        if ($U == $Selected_Date) {
            echo ' selected-date ';
        } if ($U == $Current_Date) {
            echo ' current-date';
        }
                ?>">
                        <?php echo $This_Day; ?>
                </td>
            <?php } ?>
        </tr>
        <?php
    }
    ?>
</table>
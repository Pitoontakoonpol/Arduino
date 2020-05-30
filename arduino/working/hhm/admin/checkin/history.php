<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$fr = $_GET['dfr'];
$to = $_GET['dto'];
$dorder = $_GET['dorder'];
$inF = $_GET['inF'];
$inT = $_GET['inT'];
$outF = $_GET['outF'];
$outT = $_GET['outT'];
$workF = $_GET['workF'];
$workT = $_GET['workT'];
$usr = $_GET['usr'];
$usrID = $_GET['usrID'];
$usr = $_GET['usr'];


$fr_expld = explode("-", $fr);
$to_expld = explode("-", $to);
$fr = date("U", mktime(0, 0, 0, $fr_expld[1], $fr_expld[2], $fr_expld[0]));
$to = date("U", mktime(0, 0, 0, $to_expld[1], $to_expld[2] + 1, $to_expld[0]));

if (!$fr OR ! $to) {
    echo "Date Error!";
} else {
//####Draw Table
//##### Count Staff exists
    if ($usr != 'admin' AND $usrID != '375' AND $usrID != '469') {
        $addition = " AND UserID='$usrID' ";
    }
    $sql = "SELECT UserID,Firstname,Username FROM checkin ";
    $sql .= "INNER JOIN username ON checkin.UserID=username.ID ";
    $sql .= "WHERE checkin.BranchID=$br AND Date_Time BETWEEN $fr AND $to $addition ";
    $sql .= "GROUP BY UserID ORDER BY Username";
    $result = $conn_db->query($sql);

    echo "<table cellpadding='0' cellspacing='0'>";
    echo"<tr>";
    echo "<th rowspan='2' colspan='2' class='top-table title date-head'>Date</th>";
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
        echo "<th colspan='3' class='top-table in_time title'>$Username</th>";
        $usr_array .= $UserID . "_";
        $count++;
    }
    echo"</tr>";
    echo"<tr>";
    for ($r = 0; $r < $count; $r++) {
        echo "<th class='in_time title'>in</th>";
        echo "<th class='out_time title'>out</th>";
        echo "<th class='period title'>work</th>";
    }
    echo "</tr>";
    if ($dorder == 'DESC') {
        $d = $to - 86400;
    } else {
        $d = $fr;
    }
    $row = 1;

    for ($ttl = 1; $ttl <= ($to - $fr) / 86400; $ttl++) {
        $did = date("ymd", $d);
        $col = 0;
        switch (date("D", $d)) {
            case 'Mon':$bgcol = 'yellow';
                break;
            case 'Tue':$bgcol = 'pink';
                break;
            case 'Wed':$bgcol = 'green;color:#fff';
                break;
            case 'Thu':$bgcol = 'orange';
                break;
            case 'Fri':$bgcol = 'lightblue';
                break;
            case 'Sat':$bgcol = 'purple;color:#fff';
                break;
            case 'Sun':$bgcol = 'red;color:#fff';
                break;
        }
        ?>
        </tr>  
        <td class="date-head"><?= date("d M ", $d) ?></td>
        <td class="weekdate-head" style="font:normal 13px Courier New;background-color:<?= $bgcol ?>"><?= date("D", $d) ?></td>
        <?php
        $usr_field = explode("_", $usr_array);
        for ($r = 0; $r < $count; $r++) {
            ?>
            <td class="in_time r<?= $row ?> c<?= $col + 1 ?>" onmouseover="$('.r<?= $row ?>').css('background-color', '#cce2f3');$('.c<?= $col + 1 ?>').css('background-color', '#cce2f3');" onmouseout="$('.r<?= $row ?>').css('background-color', '#ffffff');$('.c<?= $col + 1 ?>').css('background-color', '#ffffff');$('.period').css('background-color', '#eee');"><div class="in-val" id="<?= $did . "-" . $usr_field[$r] . "-1" ?>"></div></td>
            <td class="out_time r<?= $row ?> c<?= $col + 2 ?>" onmouseover="$('.r<?= $row ?>').css('background-color', '#cce2f3');$('.c<?= $col + 2 ?>').css('background-color', '#cce2f3');" onmouseout="$('.r<?= $row ?>').css('background-color', '#ffffff');$('.c<?= $col + 2 ?>').css('background-color', '#ffffff');$('.period').css('background-color', '#eee');"><div class="out-val"  id="<?= $did . "-" . $usr_field[$r] . "-2" ?>"></div></td>
            <td class="period r<?= $row ?> c<?= $col + 3 ?>" onmouseover="$('.r<?= $row ?>').css('background-color', '#cce2f3');$('.c<?= $col + 3 ?>').css('background-color', '#cce2f3');" onmouseout="$('.r<?= $row ?>').css('background-color', '#ffffff');$('.c<?= $col + 3 ?>').css('background-color', '#ffffff');$('.period').css('background-color', '#eee');"><div class="period-val"  id="<?= $did . "-" . $usr_field[$r] . "-3" ?>"></div></td>
            <?php
            $col += 3;
        }
        ?>
        </tr>
        <?php
        if ($dorder == 'DESC') {
            $d -= 86400;
        } else {
            $d += 86400;
        }
        $row++;
    }
    echo"</table>";


    $sql = "SELECT Date_Time,UserID,Method ";
    $sql .= "FROM checkin ";
    $sql .= "WHERE BranchID=$br AND Date_Time BETWEEN $fr AND $to ";
    $sql .= "ORDER BY Date_Time ";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        foreach ($db as $key => $value) {
            $$key = $value;
        }
        $photo_name = "u" . $UserID . "b" . $br . "d" . strtotime(date("j M Y", $Date_Time)) . "t" . date("U", $Date_Time);
        if ($Method == 1) {
            $photo_name .= "min.jpg";
        } else {
            $photo_name .= "mout.jpg";
        }

        $did = date("ymd", $Date_Time);
        $dread = '<div onclick="show_checkin_photo(this.id)" picfile="' . $photo_name . '" id="' . $Date_Time . '-' . $UserID . '">' . date("H:i:s", $Date_Time) . "</div>";

        $activeid = $did . "-" . $UserID;
        // echo "<br/>$dread";
        echo"<script type='text/javascript'>$('#" . $activeid . "-$Method').append('" . $dread . "');</script>";
        if ($Method == 1) {
            ?>
            <script type='text/javascript'>
                var currentVal = $('#<?= $activeid ?>-1').attr('tin');
                if (!currentVal) {
                    $('#<?= $activeid ?>-1').attr('tin', '<?= $Date_Time ?>');
                    $('#<?= $activeid ?>-1').attr('tinH', "<?= date('G', $Date_Time) ?>");
                }

                $('#<?= $activeid ?>-3').text('?');
            </script>
            <?php
        } else if ($Method == 2) {
            ?>
            <script type='text/javascript'>
                $('#<?= $activeid ?>-1').attr('tout', '<?= $Date_Time ?>');
                $('#<?= $activeid ?>-2').attr('toutH', "<?= date('G', $Date_Time) ?>");

                var tin = parseInt($('#<?= $activeid ?>-1').attr('tin'));
                var tout = parseInt($('#<?= $activeid ?>-1').attr('tout'));

                if (tout > tin) {
                    var whour = Math.floor((tout - tin) / 3600);
                    var whourH = Math.floor((tout - tin) / 3600);
                    var wminute = Math.floor(((tout - tin) % 3600) / 60);
                    if (whour < 10) {
                        whour = '0' + whour;
                    }
                    if (wminute < 10) {
                        wminute = '0' + wminute;
                    }
                    var time_diff = whour + "." + wminute;
                    $('#<?= $activeid ?>-3').text(time_diff);
                    $('#<?= $activeid ?>-3').attr('tout', time_diff);
                    $('#<?= $activeid ?>-3').attr('periodH', whourH);
                } else {

                    $('#<?= $activeid ?>-3').text('?');
                    $('#<?= $activeid ?>-3').attr('tout', '0');
                    $('#<?= $activeid ?>-3').attr('periodH', '0');
                }
            </script>
            <?php
        }
    }
}
?>
<style type="text/css">
    .in_time{
        font:normal 13px Courier New;
        text-align:center;
        padding:5px;
        width:70px;
        height:25px;
        border:solid 1px black;
        border-width:0 1px 1px 0;
    }
    .out_time{
        font:normal 13px Courier New;
        text-align:center;
        padding:5px;
        width:70px;
        border:solid 1px black;
        border-width:0 1px 1px 0;
    }
    .period{
        font:normal 13px Courier New;
        text-align:center;
        padding:5px;
        width:45px;
        border:solid 1px black;
        background-color:#eee;
        border-width:0 1px 1px 0;
    }
    .date-head{
        font:bold 13px sans-serif;
        text-align:center;
        padding:5px;
        width:45px;
        border:solid 1px black;
        border-width:0 1px 1px 1px;
    }
    .weekdate-head{
        font:normal 13px sans-serif;
        text-align:center;
        padding:5px;
        width:25px;
        border:solid 1px black;
        background-color:#ddd;
        border-width:0 1px 1px 0;
    }
    .top-table {
        border-top:solid 1px black;
    }
    .title{
        font:bold 13px sans-serif;
    }
    
</style>
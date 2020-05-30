<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$searchText = $_GET['searchText'];
if ($searchText == 'Latest Register') {
    $sql = "SELECT * FROM member WHERE BranchID='$br' AND Active>0 ORDER BY Created DESC LIMIT 10";
} else if ($searchText == 'Maximum Point') {
    $sql = "SELECT * FROM member WHERE BranchID='$br' AND Active>0 ORDER BY Point_Remain DESC LIMIT 10";
} else {
    $sql = "SELECT * FROM member WHERE BranchID='$br' AND (Name LIKE '%$searchText%' OR Member_Code LIKE '%$searchText%' OR Mobile LIKE '%$searchText%' OR Telephone LIKE '%$searchText%') AND Active>0";
}
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    $ID = $db['ID'];
    $Name = $db['Name'];
    $Gender = $db['Gender'];
    $Member_Code = $db['Member_Code'];
    $Level = $db['Level'];
    $Mobile = $db['Mobile'];
    $Telephone = $db['Telephone'];
    $Point_Remain = $db['Point_Remain'];
    $Member_Code = str_replace($searchText, "<span style='background-color:yellow;'>" . $searchText . "</span>", $Member_Code);
    $Name = str_replace($searchText, "<span style='background-color:yellow;'>" . $searchText . "</span>", $Name);
    $Mobile = str_replace($searchText, "<span style='background-color:yellow;'>" . $searchText . "</span>", $Mobile);
    $Telephone = str_replace($searchText, "<span style='background-color:yellow;'>" . $searchText . "</span>", $Telephone);
    if ($Gender == '1') {
        $bgcol = 'background-color:tan';
    } else if ($Gender == '2') {
        $bgcol = 'background-color:#ffbad2';
    } else {
        $bgcol = 'background-color:#ddd';
    }
    ?>
    <div class='MEMBER-RESULT' onclick="submit_member('<?= $ID ?>', '<?= $Name ?>', '<?= $Point_Remain ?>');" style="<?= $bgcol ?>">
        <div class='MEMBER-RESULT-NAME'>
            <?php if ($Member_Code AND $Member_Code != 0) { ?>
                <div style="font-size:12px;">#<?= $Member_Code ?></div>
            <?php } ?>
            <div style="font:bold 18px sans-serif;"><?= $Name ?></div>
            <div><?= $Level ?></div>
            <div><?= $Mobile ?></div>
        </div>
        <div class='MEMBER-RESULT-POINT'><?= $Point_Remain ?><span style='font-size:12px'>pt.</span></div>
        <div style="clear:both;"></div>
    </div>
    <?php
}
if (!$ID) {
    echo "<div class='MEMBER-RESULT' style='text-align:center;font:bold 15px sans-serif;'>Member Not Found!</div>";
}
?>
<style type="text/css">
    .MEMBER-RESULT{
        position:relative;
        width:200px;
        height:100px;
        float:left;
        padding:10px;
        border-radius:10px;
        color:#333;
        border:solid 5px #fff;
        cursor:pointer;
        overflow:hidden;
    }
    .MEMBER-RESULT-NAME{
        float:left;
    }
    .MEMBER-RESULT-POINT{
        position:absolute;
        right:0px;
        bottom:0px;
        padding:10px 5px 0 0;
        font:bold 18px sans-serif;
    }
</style>
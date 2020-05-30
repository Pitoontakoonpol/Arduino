<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$sqlType = "SELECT ID AS TypeID,Type_Name,BG_Color,Icon,Type_Style FROM menu_type ";
$sqlType .= "WHERE BranchID='$br' ";
$sqlType .= "ORDER BY Sequence,Type_Name";
$resultType = $conn_db->query($sqlType);
while ($dbType = $resultType->fetch_array()) {
    $TypeID = $dbType['TypeID'];
    $Type_Name = $dbType['Type_Name'];
    $Type_Color = $dbType['BG_Color'];
    $Type_Icon = $dbType['Icon'];
    $Type_Style = $dbType['Type_Style'];

    if ($Type_Color) {
        $bg_col = "background-color:#$Type_Color;";
    }
    ?>
    <div class="TYPE-NODE" id="TYPE-<?= $TypeID ?>" style="display:none;<?= $bg_col ?>" onclick="scroll_to_type('<?= $TypeID ?>')">
        <?php
        if ($Type_Style == 1 AND $Type_Icon) {
            //##########Icon AND Title
            echo "<div style='padding-left:15px;padding-right:15px;'><img src='../../img/menu_icon/" . $Type_Icon . ".png' align='absmiddle' style='height:40px'>$Type_Name</div>";
        } else if ($Type_Style == 2 AND $Type_Icon) {
            //##########Icon Only
            echo "<img src='../../img/menu_icon/" . $Type_Icon . ".png' align='absmiddle' style='height:40px'> ";
        } else {
            //##########Title Only
            echo "<div style='padding-top:5px;height:30px;'>$Type_Name</div>";
        }
        ?>
    </div>
    <?php
}
?>
<div class="TYPE-NODE" style="width:100px;">
</div>
<style type="text/css">
    .TYPE-NODE{
        display: inline-block;
        margin:-2px;
        padding:8px 0;
        color:#fff;
        border-radius:0 0 20px 5px;

    }
</style>
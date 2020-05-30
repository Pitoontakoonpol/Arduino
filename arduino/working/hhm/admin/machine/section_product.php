
<script type="text/javascript">
    var br = localStorage.br;
    $(document).ready(function () {});
</script>
<div>
    <h1 style="float:left;padding-left:10px;">Manage Product (<span id='PRODUCT-COUNT'></span>)</h1>
    <div style="float:right;width:150px;padding-right:3px;">
        <button class="ui-btn ui-corner-all ui-icon-plus ui-btn-icon-top" style="font-size:12px;" onclick="add_product_form()">Add Machine</button>
    </div>
</div>
<?php
$br = $_GET['br'];
include("../php-config.conf");
include("../php-db-config.conf");
$count = 1;
$sqlMenu = "SELECT A.BranchID AS BranchID,A.Name AS Machine_Name,Serial,Machine_Code,Type,A.Updated AS Updated, ";
$sqlMenu .= "B.ID AS TypeID,BG_Color AS Type_Color, Icon AS Type_Icon, Type_Style, ";
$sqlMenu .= "C.Name AS Branch_Name ";
$sqlMenu .= "FROM machine A ";
$sqlMenu .= "INNER JOIN machine_type B ON A.Type=B.Type_Name ";
$sqlMenu .= "LEFT JOIN branch C ON A.BranchID=C.Branch_Code ";
$sqlMenu .= " ORDER BY Sequence,Type,Machine_Code,A.Name";
$resultMenu = $conn_db->query($sqlMenu);
//echo $conn_db->error;
$group_number = 1;
while ($dbMenu = $resultMenu->fetch_array()) {
    foreach ($dbMenu as $key => $value) {
        $$key = $value;
    }
    $Name_Display = $Machine_Name;
    if ($POS) {
        $bg_col = "#ffffff";
    } else {
        $bg_col = "#bbbbbb";
    }
    if (!$Previous_Type OR $Previous_Type != $Type) {
        if (!$Type_Color) {
            $Type_Color = '000';
        }
        if (!$Type_Icon) {
            $Type_Icon = '000';
        }
        $Total_Type .= $Type . "_____";
        ?>
        <script type='text/javascript'>
            $("#SECTION-GROUP").append("<div ondrop='drop(<?= $TypeID ?>)' ondragover='allowDrop(event)' id='GROUP-NODE-<?= $TypeID ?>' class='GROUP-NODE'  style='background-color:#<?= $Type_Color ?>'><div id='GROUP-NODE-NUMBER-<?= $TypeID ?>' class='GROUP-NODE-NUMBER' ondragstart='drag(<?= $TypeID ?>)' draggable='true'><?= $group_number ?></div><div class='GROUP-NODE-ICON' id='GROUP-NODE-ICON-<?= $TypeID ?>' onclick='edit_group_form(<?= $TypeID ?>)'><img src='../../img/menu_icon/<?= $Type_Icon ?>.png'></div><div class='GROUP-NODE-TEXT' id='GROUP-NODE-TEXT-<?= $TypeID ?>'><?= $Type ?></div><div id='GROUP-PROPERTY-<?= $TypeID ?>' g_title='<?= $Type ?>' g_color='<?= $Type_Color ?>' g_icon='<?= $Type_Icon ?>' g_style='<?= $Type_Style ?>'></div></div>");
        </script>
        <div style='page-break-before: always;'></div>
        <div class="MENU-TYPE-BAR" id="MENU-TYPE-BAR-<?= $TypeID ?>" style="background-color:#<?= $Type_Color ?>;"><?= $Type ?></div>
        <?php
        $group_number++;
    }
    ?>
    <div class="MENU-NODE" id="MENU-NODE-<?= $MenuID ?>" style='background-color:<?= $bg_col ?>'>
        <div class="MENU-BUTTON">
            <div>
                <button style="margin:3px;"  onclick="edit_product_form(<?= $MenuID ?>)" class="ui-btn ui-btn-inline ui-icon-edit ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon"></button>
            </div>
            <div>
                <button style="margin:3px;" onclick="topup_product_form(<?= $MenuID ?>)" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon"></button>
            </div>
            <div>
                <button style="margin:3px;" onclick="stock_product_form(<?= $MenuID ?>)" class="ui-btn ui-btn-inline ui-icon-bars ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon"></button>
            </div>
        </div>
        <div class="DISPLAY-PIC" id="DISPLAY-PIC-<?= $MenuID ?>">
            <?php
            $img_path = "../../picture/";
            $img_path_new = "../../picture/$br/";
            $img_file = "../../img/anonymous180.png";
            if (file_exists($img_path . $MenuID . '.jpg')) {
                $img_file = $img_path . $MenuID . '.jpg?' . $Updated;
                $img_file_old = $img_path . $MenuID . '.jpg';
                $img_file_new = $img_path_new . $MenuID . '.jpg';
                if (!file_exists($img_path_new)) {
                    exec("mkdir $img_path_new");
                }
                exec("mv $img_file_old $img_file_new");
            } else if (file_exists($img_path . $MenuID . '.png')) {
                $convert_to = $img_path . $MenuID . ".jpg";
                $convert_delete = $img_path . $MenuID . ".png";
                exec("convert $convert_delete $convert_to ");
                exec("rm $convert_delete");
                $img_file = $img_path . $MenuID . '.png?' . $Updated;
            }

            if (file_exists($img_path_new . $MenuID . '.jpg')) {
                $img_file = $img_path_new . $MenuID . '.jpg?' . $Updated;
            }
            ?>
            <img src="<?= $img_file ?>">
        </div>
        <div class="DISPLAY-DESC">
            <div id="DISPLAY-NAME-<?= $MenuID ?>"><b><?= $Name_Display ?></b></div>
            <div id="DISPLAY-PRICE-<?= $MenuID ?>">@<?=$Branch_Name?></div>
            <div id="DISPLAY-MENU-CODE-<?= $MenuID ?>"># <?= $Machine_Code ?></div>
            <div id="DISPLAY-MENU-CODE-<?= $MenuID ?>">SN <?= $BranchID."-".$Serial ?></div>
            <div id="DISPLAY-BARCODE-<?= $BranchID."-".$MenuID ?>">
                <?php
                if ($Barcode) {
                    echo "*" . $Barcode . "*";
                }
                ?>
            </div>
            <div id="DISPLAY-DATA-<?= $MenuID ?>" menuID="<?= $MenuID ?>" menuCode="<?= $Menu_Code ?>" menuBarcode="<?= $Barcode ?>" menuName="<?= $Name_Display ?>" menuPrice="<?= $Price_Display ?>" menuTopup="<?= $Menu_Topup ?>" menuType="<?= $Type ?>" menuKitchen="<?= $Kitchen ?>"></div>
        </div>
    </div>
    <?php
    $Previous_Type = $Type;
    $Total_Topup .= $Menu_Topup;
    $count++;
}
?>
<input type="hidden" id="totalType">
<script type='text/javascript'>
    $("#totalType").val('<?= $Total_Type ?>');
    $("#PRODUCT-COUNT").text('<?= $count ?>');
</script>
<style type="text/css">
    .MENU-BUTTON{
        position:absolute;
        right:5px;
        top:5px;
        z-index:10;
    }
    .MENU-TYPE-BAR{
        clear:both;
        color:#fff;
        padding:8px;
        font:normal 18px sans-serif;
        background-color:#000;
    }
    .MENU-NODE{
        position:relative;
        float:left;
        text-align:center;
        height:300px;
        width:200px;
        margin:8px;
        font:normal 15px sans-serif;
        border-radius:3px;
        padding:1px;
        box-shadow:0 0 3px #000;
        background-color:#fff;
    }
    .DISPLAY-PIC {
        background:url('../../img/anonymous180.png')  no-repeat;
        position:relative;
    }
    .DISPLAY-PIC img{
        width:200px;
        height:200px;
        border-radius:3px;
    }
    .DISPLAY-DESC div{
        text-align:left;
        padding:1px;
        font:normal 13px sans-serif;
    }
    .GROUP-NODE{
        float:left;
        padding:10px;
        font-size:20px;
        color:#fff;
        margin:2px;
        border-radius:3px;
    }
    .GROUP-NODE-NUMBER{
        min-width:15px;
        font-size:14px;
        padding:3px;
        text-align:center;
        cursor:all-scroll;
        background-color:rgba(0,0,0,0.2);
    }
    .GROUP-NODE-ICON{
        text-align:center;
        padding-top:5px;
    }
    .GROUP-NODE-TEXT{
        padding-top:5px;
    }
    @font-face {
        font-family: barcode;
        src: url(../../class/fonts/3of9.woff);
    }
    .DISPLAY-DESC [id^='DISPLAY-BARCODE']{
        height:25px;
        overflow:hidden;
        font:normal 35px barcode;
        text-align:center;
    }
    @media print {
        .MENU-BUTTON,.ui-btn,#HEADER-MAIN-BUTTON{
            display:none;
        }
    }

</style>
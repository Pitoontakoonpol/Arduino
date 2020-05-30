
<script type="text/javascript">
    var br = localStorage.br;
    $(document).ready(function () {});
</script>
<div>
    <h1 style="float:left;padding-left:10px;">Manage Product (<span id='PRODUCT-COUNT'></span>)</h1>
    <div style="float:right;width:150px;padding-right:3px;">
        <button class="ui-btn ui-corner-all ui-icon-plus ui-btn-icon-top" style="font-size:12px;" onclick="add_product_form()">Add Product</button>
    </div>
</div>
<?php
$br = $_GET['br'];
$br_explode = explode(",", $br);
$FirstBr = $br_explode[0];
include("../../admin/php-config.conf");
include("../../admin/php-db-config.conf");


$sqlMenu = "SELECT ";
$sqlMenu .= "MAX(ID) AS TypeID, Type_Name, MAX(BG_Color) AS Type_Color, MAX(Icon) AS Type_Icon, MAX(Type_Style) ";
$sqlMenu .= "FROM menu_type ";
$sqlMenu .= "WHERE BranchID IN ($br) ";
$sqlMenu .= "GROUP BY Type_Name ";
$sqlMenu .= "ORDER BY MAX(BranchID),MAX(Sequence),Type_Name";
//echo $sqlMenu;
$resultMenu = $conn_db->query($sqlMenu);
$group_number = 1;
while ($dbMenu = $resultMenu->fetch_array()) {
    foreach ($dbMenu as $key => $value) {
        $$key = $value;
    }
    if (!$Type_Color) {
        $Type_Color = '000';
    }
    if (!$Type_Icon) {
        $Type_Icon = '000';
    }
    ?>
    <script type='text/javascript'>
        $("#SECTION-GROUP").append("<div ondrop='drop(<?= $TypeID ?>)' ondragover='allowDrop(event)' id='GROUP-NODE-<?= $TypeID ?>' type_name='<?= $Type_Name ?>' class='GROUP-NODE'  style='background-color:#<?= $Type_Color ?>'><div id='GROUP-NODE-NUMBER-<?= $TypeID ?>' class='GROUP-NODE-NUMBER' ondragstart='drag(<?= $TypeID ?>)' draggable='true'><?= $group_number ?></div><div class='GROUP-NODE-ICON' id='GROUP-NODE-ICON-<?= $TypeID ?>' onclick='edit_group_form(<?= $TypeID ?>)'><img src='../../img/menu_icon/<?= $Type_Icon ?>.png'></div><div class='GROUP-NODE-TEXT' id='GROUP-NODE-TEXT-<?= $TypeID ?>'><?= $Type_Name ?></div><div id='GROUP-PROPERTY-<?= $TypeID ?>' g_title='<?= $Type_Name ?>' g_color='<?= $Type_Color ?>' g_icon='<?= $Type_Icon ?>' g_style='<?= $Type_Style ?>'></div></div>");
    </script>
    <?php
    $group_number++;
}








$count = 1;
$count_menu = 0;
$sqlMenu = "SELECT NameEN,MIN(menu.ID) AS MenuID,Menu_Code,MAX(Barcode) AS Barcode,MAX(menu.Type) AS Type, MAX(PriceTHB),MAX(CommentL1),MAX(CommentL2),MAX(CommentL3),MAX(CommentL4),MAX(CommentL5),MAX(POS),MIN(PriceTHB) AS Min_Price,MAX(PriceTHB) AS Max_Price,MIN(Point_Cost) AS Min_Point_Cost,MAX(Point_Cost) AS Max_Point_Cost,MIN(Point_Redeem) AS Min_Point_Redeem,MAX(Point_Redeem) AS Max_Point_Redeem, COUNT(1) AS Total_Group,SUM(IF(POS=1, 1,0)) AS POS_Active, SUM(IF(POS=0, 1,0)) AS POS_In_Active,SUM(IF(Scan=1, 1,0)) AS Scan_Active  ";
$sqlMenu .= "FROM menu ";
$sqlMenu .= "WHERE menu.BranchID IN ($br) ";
$sqlMenu .= "GROUP BY Menu_Code,NameEN ";
$sqlMenu .= "ORDER BY MAX(Type),Menu_Code,NameEN";
//echo $sqlMenu;
$resultMenu = $conn_db->query($sqlMenu);
//echo $conn_db->error;
while ($dbMenu = $resultMenu->fetch_array()) {
    foreach ($dbMenu as $key => $value) {
        $$key = $value;
    }
    $Menu_Topup = $CommentL1 . $CommentL2 . $CommentL3 . $CommentL4 . $CommentL5;
    $Name_Display = $NameEN;
    if ($Min_Price != $Max_Price) {
        $Price_Display = $Min_Price . " to " . $Max_Price;
    } else {
        $Price_Display = $Max_Price;
    }
    if ($POS_Active > 0) {
        $bg_col = "#ffffff";
    } else {
        $bg_col = "#bbbbbb";
    }
    if (!$Previous_Type OR $Previous_Type != $Type) {
        ?>
        <div style='page-break-before: always;'></div>
        <div class="MENU-TYPE-BAR MENU-TYPE-BAR-<?= str_replace(" ", "_", $Type) ?>" id="MENU-TYPE-BAR-<?= $TypeID ?>" style="background-color:#000;"><?= $Type ?></div>
        <?php
        if ($Previous_Type) {
            ?>
            <script type="text/javascript">
                $(".MENU-TYPE-BAR-<?= str_replace(' ', '_', $Previous_Type) ?>").append(" (<?= $count_menu ?>)");
            </script>
            <?php
        }

        $count_menu = 0;
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
            $img_path = "../../picture/$FirstBr/";
            $img_file = '../../img/anonymous180.png';
            if (file_exists($img_path . $MenuID . '.jpg')) {
                $img_file = $img_path . $MenuID . '.jpg';
            }
            ?>
            <img src="<?= $img_file ?>">
        </div>
        <div class="DISPLAY-DESC">
            <div id="DISPLAY-NAME-<?= $MenuID ?>"><?= $Name_Display ?></div>
            <div id="DISPLAY-PRICE-<?= $MenuID ?>"><?= $Currency_Display . " " . $Price_Display . ".-" ?></div>
            <div id="DISPLAY-MENU-CODE-<?= $MenuID ?>">#<?= $Menu_Code ?></div>
            <?php if ($Barcode) { ?>
                <div id="DISPLAY-BARCODE-<?= $MenuID ?>" >
                    <?php
                    if ($Barcode) {
                        echo "*" . $Barcode . "*";
                    }
                    ?>
                </div>
            <?php } ?>
            <div id="DISPLAY-DATA-<?= $MenuID ?>" menuID="<?= $MenuID ?>" menuCode="<?= $Menu_Code ?>" menuBarcode="<?= $Barcode ?>" menuName="<?= $Name_Display ?>" menuPrice="<?= $Price_Display ?>" menuTopup="<?= $Menu_Topup ?>"></div>
            <div id="POINT-DATA-<?= $MenuID ?>" data="Get:<?= $Min_Point_Cost . '-' . $Max_Point_Cost . ' Claim:' . $Min_Point_Redeem . '-' . $Max_Point_Redeem ?>">
                <?php
                echo "Point Get:" . $Min_Point_Cost . "-";
                echo $Max_Point_Cost;
                echo " Claim:" . $Min_Point_Redeem . "-";
                echo $Max_Point_Redeem;
                ?>
            </div>
            <div id="ACTIVE-DATA-<?= $MenuID ?>" data="<?= $POS_Active . '/' . $Total_Group ?>"style="float:left;"><?php
                echo "Active " . $POS_Active . "/";
                echo $Total_Group;
                ?>
            </div>
            <div id="SCAN-DATA-<?= $MenuID ?>" data="<?= $Scan_Active . '/' . $Total_Group ?>" style="float:left;margin-left:10px;"><?php
                echo "Scan " . $Scan_Active . "/";
                echo $Total_Group;
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <?php
    $Previous_Type = $Type;
    $Total_Topup .= $Menu_Topup;

    unset($Total_Group);
    $count++;
    $count_menu++;
}
?>

<script type="text/javascript">
    $(".MENU-TYPE-BAR-<?= str_replace(' ', '_', $Previous_Type) ?>").append(" (<?= $count_menu ?>)");
</script>
<script type='text/javascript'>$("#PRODUCT-COUNT").text('<?= $count ?>')</script>
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
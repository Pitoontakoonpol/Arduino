
<script type="text/javascript">
    var br = localStorage.br;
    $(document).ready(function () {});
</script>
<div>
    <h1 style="float:left;padding-left:100px;">Manage Raw Material</h1>
    <div style="float:right;width:150px;padding-right:3px;">
        <button class="ui-btn ui-corner-all ui-icon-plus ui-btn-icon-top" style="font-size:12px;" onclick="add_product_form()">Add Raw Material</button>
    </div>
</div>
<?php
$br = $_GET['br'];
include("../php-config.conf");
include("../php-db-config.conf");

$sqlMenu = "SELECT * ";
$sqlMenu .= "FROM raw_material ";
$sqlMenu .= "WHERE BranchID='$br' ";
$sqlMenu .= " ORDER BY Type,Raw_Material_Code,Name";

//echo $sqlMenu . "<br/>";
$resultMenu = $conn_db->query($sqlMenu);
//echo $conn_db->error;
$group_number = 1;
while ($dbMenu = $resultMenu->fetch_array()) {
    foreach ($dbMenu as $key => $value) {
        $$key = $value;
    }
    if ($Active) {
        $bg_col = "#ffffff";
    } else {
        $bg_col = "#bbbbbb";
    }
    if ($Stock <= $Min_Alert) {
        $stock_bg = "background-color:#ff0000;color:#fff;";
    } else {
        $stock_bg = "background-color:#00ff00;color:#000;";
    }
    if ($Unit AND $Buying_Unit AND $Unit_Convert > 1) {
        $Unit_Text = $Unit_Convert . "$Unit / " . $Buying_Unit;
    } else {
        $Unit_Text = $Unit;
    }
    if (!$Previous_Type OR $Previous_Type != $Type) {
        $TypeNo++;
        ?>
        <script type='text/javascript'>
            $("#SECTION-GROUP").append("<div ondrop='drop(<?= $TypeID ?>)' ondragover='allowDrop(event)' id='GROUP-NODE-<?= $TypeID ?>' class='GROUP-NODE'  style='background-color:#<?= $Type_Color ?>'><div id='GROUP-NODE-NUMBER-<?= $TypeID ?>' class='GROUP-NODE-NUMBER' ondragstart='drag(<?= $TypeID ?>)' draggable='true'><?= $group_number ?></div><div class='GROUP-NODE-ICON' id='GROUP-NODE-ICON-<?= $TypeID ?>' onclick='edit_group_form(<?= $TypeID ?>)'><img src='../../img/raw_material_icon/<?= $Type_Icon ?>.png'></div><div class='GROUP-NODE-TEXT' id='GROUP-NODE-TEXT-<?= $TypeID ?>'><?= $Type ?></div><div id='GROUP-PROPERTY-<?= $TypeID ?>' g_title='<?= $Type ?>' g_color='<?= $Type_Color ?>' g_icon='<?= $Type_Icon ?>' g_style='<?= $Type_Style ?>'></div></div>");
        </script>
        <div class="MENU-TYPE-BAR" id="MENU-TYPE-BAR-<?= $TypeID ?>">
            <div style="float:left;"><?= $Type ?></div>
            <div style="float:right;"><button class="ui-btn ui-btn-icon-left ui-icon-refresh ui-mini ui-corner-all" onclick="refresh_type_stock('<?= $TypeNo ?>');$(this).fadeOut(300)">Stock</button></div>
            <div style="clear:both;"></div>
        </div>
        <?php
        $group_number++;
    }
    ?>
    <div class="MENU-NODE" id="MENU-NODE-<?= $ID ?>" style='background-color:<?= $bg_col ?>'>
        <div class="MENU-BUTTON">
            <div>
                <button style="margin:3px;"  onclick="edit_product_form(<?= $ID ?>)" class="ui-btn ui-btn-inline ui-icon-edit ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon"></button>
            </div>
            <div>
                <button style="margin:3px;"  onclick="desc(<?= $ID ?>, 'stockin')" class="ui-btn ui-btn-inline ui-icon-bars ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon"></button>
            </div>
        </div>
        <div class="DISPLAY-DESC">
            <div id="DISPLAY-NAME-<?= $ID ?>"><?= $Name ?></div>
            <div id="DISPLAY-PRICE-<?= $ID ?>"><?= $Unit_Text ?></div>
            <div id="DISPLAY-MENU-CODE-<?= $ID ?>">#<?= $Raw_Material_Code ?></div>
            <div id="DISPLAY-BARCODE-<?= $ID ?>">
                <?php
                if ($Barcode) {
                    echo "*" . $Barcode . "*";
                }
                ?></div>
            <div id="DISPLAY-STOCK-<?= $ID ?>" style="position:absolute;bottom:0;right:0px;padding:3px 5px;width:192px;border-radius:3px;<?= $stock_bg ?>">
                <div style="font:normal 10px sans-serif;text-align:left;float:left;max-width:80px;">
                    <?php
                    if ($Buying_Unit AND $Unit_Convert > 1) {
                        ?>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td valign="center" style="text-align:center;padding-right:2px;" id="Buying_Unit-<?= $ID ?>"><?= floor($Stock / $Unit_Convert) ?></td>
                                <td valign="center"><?= $Buying_Unit ?></td>
                            </tr>
                            <tr>
                                <td valign="center" style="text-align:center;padding-right:2px;" id="Selling_Unit-<?= $ID ?>"><?= $Stock % $Unit_Convert ?></td>
                                <td valign="center"><?= $Unit ?></td>
                            </tr>
                        </table>
                        <?php
                    }

                    if ($TotalIn > 9999) {
                        $TotalIn_text = number_format($TotalIn / 1000, 1) . 'k';
                    } else {
                        $TotalIn_text = number_format($TotalIn, 2);
                    }
                    if ($Total_Out > 9999) {
                        $Total_Out_text = number_format($Total_Out / 1000, 1) . 'k';
                    } else {
                        $Total_Out_text = number_format($Total_Out, 2);
                    }
                    if ($Total_POS > 9999) {
                        $Total_POS_text = number_format($Total_POS / 1000, 1) . 'k';
                    } else {
                        $Total_POS_text = number_format($Total_POS, 2);
                    }
                    ?>
                </div>
                <div style="font:normal 22px sans-serif;text-align:right;float:right" id="Stock-<?= $ID ?>"><?= number_format($Stock, 2) ?></div>
            </div>
            <div class="typeNo-<?=$TypeNo?>" typeID="<?=$TypeNo?>" id="DISPLAY-DATA-<?= $ID ?>" raw_materialID="<?= $ID ?>" raw_materialCode="<?= $Menu_Code ?>" raw_materialBarcode="<?= $Barcode ?>" raw_materialName="<?= $Name_Display ?>" raw_materialPrice="<?= $Price_Display ?>" raw_materialTopup="<?= $Menu_Topup ?>" unit_convert="<?= $Unit_Convert ?>" stockin="<?= $TotalIn_text ?>" stockout="<?= $Total_Out_text ?>" stockpos="<?= $Total_POS_text ?>" stock="<?= $Stock ?>"></div>
        </div>
    </div>
    <?php
    $Previous_Type = $Type;
    $Total_Topup .= $Menu_Topup;
}
?>
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
        height:130px;
        width:200px;
        /*width defined by img*/
        margin:8px;
        font:normal 15px sans-serif;
        border-radius:3px;
        padding:1px;
        box-shadow:0 0 3px #000;
        background-color:#fff;
    }
    .DISPLAY-DESC div{
        text-align:left;
        padding:1px;
        font:normal 13px sans-serif;
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
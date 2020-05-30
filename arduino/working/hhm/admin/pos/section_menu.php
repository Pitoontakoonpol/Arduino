<script type="text/javascript">
    $(document).ready(function () {
        $(".DISPLAY-PIC").bind("tap swipeleft", function (event) {
            var menuID = $(this).attr("id").replace('DISPLAY-PIC-', '');
            if (event.type === 'tap') {
                numbering_menu(menuID, 1);
            } else if (event.type === 'swipeleft') {
                numbering_menu(menuID, -1);
            }
        });
        $(".MENU-ORDER-QTY").bind("taphold", function () {
            var menuID = $(this).attr("id");
            menuID = menuID.replace('MENU-ORDER-QTY-', '');
            numbering_menu(menuID, 0);
        });
        $("div[id^='TOPUP-DISPLAY-PIC-']").bind("tap", function () {
            var topupID = $(this).attr("id");
            topupID = topupID.replace('TOPUP-DISPLAY-PIC-', '');
            topup_menu(topupID, 1);
        });
    });
</script>
<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$Set_Lang_POS = $_GET['Set_Lang_POS'];
$Set_Lang_Bill = $_GET['Set_Lang_Bill'];

$sqlMenu = "SELECT NameEN,NameTH,NameLO,menu.ID AS MenuID,Menu_Code,Barcode,Type,PriceTHB,Point_Cost,Point_Redeem,CommentL1,CommentL2,CommentL3,CommentL4,CommentL5,POS,Scan,menu.Updated AS Updated, ";
$sqlMenu .= "menu_type.ID AS TypeID,BG_Color AS Type_Color ";
$sqlMenu .= "FROM menu ";
$sqlMenu .= "INNER JOIN menu_type ON menu.Type=menu_type.Type_Name ";
$sqlMenu .= "WHERE menu.BranchID='$br' AND  menu_type.BranchID='$br' AND (POS=1 OR Scan=1)";
$sqlMenu .= " ORDER BY Sequence,Type,Menu_Code,NameEN";

//echo $sqlMenu."<br/>";
$resultMenu = $conn_db->query($sqlMenu);
//echo $conn_db->error;
while ($dbMenu = $resultMenu->fetch_array()) {
    foreach ($dbMenu as $key => $value) {
        $$key = $value;
    }
    $Menu_Topup = $CommentL1 . $CommentL2 . $CommentL3 . $CommentL4 . $CommentL5;
    if ($Set_Lang_POS == 2 AND $NameTH) {
        $Name_Display = $NameTH;
    } else if ($Set_Lang_POS == 3 AND $NameLO) {
        $Name_Display = $NameLO;
    } else {
        $Name_Display = $NameEN;
    }
    if ($Set_Lang_POS != $Set_Lang_Bill) {
        if ($Set_Lang_Bill == 2 AND $NameTH) {
            $Name_Bill = $NameTH;
        } else if ($Set_Lang_Bill == 3 AND $NameLO) {

            $Name_Bill = $NameLO;
        } else {
            $Name_Bill = $NameEN;
        }
    }

    $Price_Display = $PriceTHB;
    if (!$Previous_Type OR $Previous_Type != $Type AND ( $POS == 1 OR $Scan == 1)) {
        echo "<script type='text/javascript'>$('#TYPE-$TypeID').show();</script>";
        ?>
        <div class="MENU-TYPE-BAR" id="MENU-TYPE-BAR-<?= $TypeID ?>" style="background-color:#<?= $Type_Color ?>"><?= $Type ?></div>
        <?php
    }
    ?>
    <div class="MENU-NODE" id="MENU-NODE-<?= $MenuID ?>" <?php
    if ($POS == 0) {
        echo" style='display:none;'";
    }
    ?>>
        <div class="DISPLAY-PIC" id="DISPLAY-PIC-<?= $MenuID ?>">
            <div class="MENU-ORDER-QTY" id="MENU-ORDER-QTY-<?= $MenuID ?>">0</div>
            <?php
            $img_path = "../../picture/";
            $img_path_new = "../../picture/$br/";
            $img_file = '../../img/anonymous180.png';
            if (file_exists($img_path . $MenuID . '.jpg')) {
                $img_file = $img_path . $MenuID . '.jpg?' . $Updated;
            } else if (file_exists($img_path . $MenuID . '.png')) {
                $img_file = $img_path . $MenuID . '.png?' . $Updated;
            }

            if (file_exists($img_path_new . $MenuID . '.jpg')) {
                $img_file = $img_path_new . $MenuID . '.jpg?' . $Updated;
            }
            if ($Point_Redeem <= 0) {
                $Point_Redeem = $Price_Display;
            }
            ?>
            <img src="<?= $img_file ?>">
            <div class="DISPLAY-CODE">
                <div id="DISPLAY-MENU-CODE-<?= $MenuID ?>">
                    <?php
                    if ($Menu_Code) {
                        echo"#" . $Menu_Code;
                    }
                    ?></div>
                <div id="DISPLAY-BARCODE-<?= $MenuID ?>">
                    <?php
                    if ($Barcode) {
                        echo"||" . $Barcode . "||";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="DISPLAY-NAME-<?= $MenuID ?>"><?= $Name_Display ?></div>
        <div id="DISPLAY-PRICE-<?= $MenuID ?>" title="<?= $Point_Cost ?> Point"><?= $Currency_Display . " " . $Price_Display . ".-" ?></div>
        <div id="DISPLAY-DATA-<?= $MenuID ?>" menuID="<?= $MenuID ?>" menuCode="<?= $Menu_Code ?>" menuBarcode="<?= $Barcode ?>" menuName="<?= $Name_Display ?>" menuBill="<?= $Name_Bill ?>" menuPrice="<?= $Price_Display ?>" menuPoint="<?= $Point_Cost ?>" menuRedeem="<?= $Point_Redeem ?>" menuTopup="<?= $Menu_Topup ?>"></div>
        <?php if ($Menu_Topup) { ?>
            <div class="SAVING-TOPUP" style='display:none;' id="SAVING-TOPUP-<?= $MenuID ?>"></div>
            <div class="SAVING-TOPUP-PRICE" style='display:none;' id="SAVING-TOPUP-PRICE-<?= $MenuID ?>">0</div>
        <?php } ?>
    </div>
    <?php
    $Previous_Type = $Type;
    $Total_Topup .= $Menu_Topup;
}
?>
<div style="clear:both;height:120px;"></div>
<?php
if ($Total_Topup) {
    ?>    

    <div id="SECTION-TOPUP">
        <?php
        // echo "BEFORE>> ".$Total_Topup;
        $Total_Topup = str_replace("--", "-", $Total_Topup);
        $Topup_Array = explode("-", substr($Total_Topup, 1));
        $Topup_Array = array_unique($Topup_Array);
        $Topup_Array = implode(",", $Topup_Array);
        $Topup_Array = str_replace(",,", ",", $Topup_Array);
        if (substr($Topup_Array, -1) == ',') {
            $Topup_Array = substr($Topup_Array, 0, -1);
        }
        //echo "<br/>AFTER>> ".$Topup_Array;
        $sql_Topup = "SELECT NameEN,NameTH,NameLO,ID AS TopupID, PriceTHB AS Topup_Currency FROM menu WHERE BranchID='$br' AND ID IN ($Topup_Array) ORDER BY Menu_Code,NameEN";
        // echo "<br/>AFTER>> ".$sql_Topup;
        $result_Topup = $conn_db->query($sql_Topup);
        while ($db_Topup = $result_Topup->fetch_array()) {
            $TopupID = $db_Topup['TopupID'];
            $Topup_NameEN = $db_Topup['NameEN'];
            $Topup_NameTH = $db_Topup['NameTH'];
            $Topup_NameLO = $db_Topup['NameLO'];
            $Topup_Currency = $db_Topup['Topup_Currency'];

            $Topup_Title = $Topup_NameEN;
            
            $img_path = "../../picture/";
            $img_path_new = "../../picture/$br/";
            $topup_img_file = '../../img/anonymous_plus.png';
            if (file_exists($img_path . $TopupID . '.jpg')) {
                $topup_img_file = $img_path . $TopupID . '.jpg?' . $Updated;
            } else if (file_exists($img_path . $TopupID . '.png')) {
                $topup_img_file = $img_path . $TopupID . '.png?' . $Updated;
            }

            if (file_exists($img_path_new . $TopupID . '.jpg')) {
                $topup_img_file = $img_path_new . $TopupID . '.jpg?' . $Updated;
            }
            
            
            ?>
            <div class="TOPUP-NODE" id="TOPUP-NODE-<?= $TopupID ?>" >
                <div id="TOPUP-DISPLAY-PIC-<?= $TopupID ?>"><img src="<?= $topup_img_file ?>"></div>
                <div style="white-space: pre-wrap;"><?= $Topup_Title ?></div>
                <div><?= $Topup_Currency ?>.-</div>
                <div id="DISPLAY-DATA-TOPUP-<?= $TopupID ?>" topupname="<?= $Topup_Title ?>" topupprice="<?= $Topup_Currency ?>"></div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
/*
  $sql_Promotion = "SELECT * FROM promotion WHERE BranchID='$br' AND Active=1";
  $result_Promotion = $conn_db->query($sql_Promotion);
  while ($db_Promotion = $result_Promotion->fetch_array()) {
  $PrID = $db_Promotion['ID'];
  $Pr_Type = $db_Promotion['Promotion_Type'];
  $Pr_Name = $db_Promotion['Name'];
  $Pr_ProductID = $db_Promotion['ProductID'];
  $Pr_Member_Range = $db_Promotion['Member_Range'];
  $Pr_Qty = $db_Promotion['Qty'];
  $Pr_Discount_Type = $db_Promotion['Discount_Type'];
  $Pr_Total = $db_Promotion['Total'];
  $Pr_Date = $db_Promotion['Date'];
  $Pr_Time_From = $db_Promotion['Time_From'];
  $Pr_Time_To = $db_Promotion['Time_To'];
  ?>
  <input type="text" class="pr_list" id="PromotionID-<?= $PrID ?>" pr_type="<?= $Pr_Type ?>" pr_name="<?= $Pr_Name ?>" pr_member="<?= $Pr_Member_Range ?>" pr_qty="<?= $Pr_Qty ?>" pr_discount_type="<?= $Pr_Discount_Type ?>" pr_total="<?= $Pr_Total ?>" pr_date="<?= $Pr_Date ?>" pr_time_from="<?= $Pr_Time_From ?>" pr_time_to="<?= $Pr_Time_To ?>">
  <?php
  $Pr_Range .= "___" . $Pr_ProductID;
  }
 */
?>
<!--
<input type="text" id="Pr_Range" value="<?= $Pr_Range ?>">
-->
<style type="text/css">
</style>
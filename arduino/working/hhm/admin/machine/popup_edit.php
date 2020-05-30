
<script type="text/javascript">
    $(document).ready(function () {

    });
    function check_new_select(ths) {

        var selectid = ths.id;
        var select_value = ths.value;
        if (select_value === 'new---19') {
            $("#" + selectid + "_new").show().focus();
        } else {
            $("#" + selectid + "_new").hide().val('');
        }
    }
</script>
</head>
<body>
    <?php
    $br = $_GET['br'];
    $Set_Restaurant = $_GET['Set_Restaurant'];
    $set_kitchen = $_GET['set_kitchen'];
    $productid = $_GET['productid'];
    include("../php-config.conf");
    include("../php-db-config.conf");

    if ($productid AND $br) {
        $sqlMenu = "SELECT * ";
        $sqlMenu .= "FROM menu ";
        $sqlMenu .= "WHERE BranchID='$br' AND  ID='$productid'";

        $resultMenu = $conn_db->query($sqlMenu);
        while ($dbMenu = $resultMenu->fetch_array()) {
            foreach ($dbMenu as $key => $value) {
                $$key = $value;
            }
        }
    }
    ?>
    <div class="ui-field-contain">
        <div style="float:left;">
            <table cellpadding="5">
                <tr>
                    <th>Name 1</th>
                    <td><input type="text" id="NameEN" value="<?= $NameEN ?>" placeholder="Language 1" class="edit"></td>
                </tr>
                <tr>
                    <th>Name 2</th>
                    <td><input type="text" id="NameTH" value="<?= $NameTH ?>" placeholder="Language 2" class="edit"></td>
                </tr>
                <tr>
                    <th>Name 3</th>
                    <td><input type="text" id="NameLO" value="<?= $NameLO ?>" placeholder="Language 3" class="edit"></td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td><input type="text" id="Menu_Code" value="<?= $Menu_Code ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Barcode</th>
                    <td><input type="number" id="Barcode" value="<?= $Barcode ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <div id='selectType_Area'>asdf</div>
                        <input type="text" id="Type_new" style="display:none;" class="edit" placeholder="Define New Group">
                    </td>
                </tr>
                <?php
                if ($set_kitchen) {
                    ?>
                    <tr>
                        <th>Kitchen</th>
                        <td>
                            <select id="Kitchen" class="edit" onChange="check_new_select(this)">
                                <option value=""></option>
                                <?php
                                $sql_Kitchen = "SELECT Kitchen,COUNT(1) AS cnt FROM menu WHERE BranchID='$br' AND Kitchen<>'' GROUP BY Kitchen ORDER BY Kitchen";
                                $result_Kitchen = $conn_db->query($sql_Kitchen);
                                while ($db_Kitchen = $result_Kitchen->fetch_array()) {
                                    echo"<option value=" . $db_Kitchen['Kitchen'] . ">" . $db_Kitchen['Kitchen'] . " (" . $db_Kitchen['cnt'] . ")</option>";
                                }
                                ?>
                                <option value="new---19">+New Kitchen</option>
                            </select>
                            <input type="text" id="Kitchen_new" style="display:none;" class="edit" placeholder="Define New Kitchen">
                        </td>
                    </tr>
                    <tr>
                        <th>Kitchen2</th>
                        <td>
                            <select onChange="check_new_select(this)">
                                <option value=""></option>
                                <?php
                                $sql_Kitchen = "SELECT Kitchen,COUNT(1) AS cnt FROM menu WHERE BranchID='$br' AND Kitchen<>'' GROUP BY Kitchen ORDER BY Kitchen";
                                $result_Kitchen = $conn_db->query($sql_Kitchen);
                                while ($db_Kitchen = $result_Kitchen->fetch_array()) {
                                    echo"<option value=" . $db_Kitchen['Kitchen'] . ">" . $db_Kitchen['Kitchen'] . " (" . $db_Kitchen['cnt'] . ")</option>";
                                }
                                ?>
                                <option value="new---19">+New Kitchen</option>
                            </select>
                            <input type="text" id="Kitchen2_new" style="display:none;" class="edit" placeholder="Define New Kitchen">
                        </td>
                    </tr>
                    <?php
                }
                if ($Set_Restaurant) {
                    ?>
                    <tr>
                        <th>Discount</th>
                        <td><label><input type="checkbox" id="Discount" value="1" name="Discount" <?php if ($Discount) echo "checked"; ?> class="edit"><span style="font-size:14px;color:#555;margin-left:5px"> Item will be activated for Discount</span></label></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th valign="top">
                        <div class="prevent-add">Picture</div></th>
                    <td>
                        <div class="prevent-add">
                            <iframe src="upload_image.php?ProductID=<?= $productid ?>&br=<?= $br ?>" style="padding:0;border:none;height:100px;overflow:hidden;">
                            </iframe>
                        </div>
                    </td>
                </tr>
                <tr><td colspan="2" style="height:30px"></td></tr>
                <tr>
                    <th style="padding-top:20px;" valign="top">Price</th>
                    <td>
                        <label style="font-size:12px;color:#888;margin:0">Regular</label>
                        <input type="number" id="PriceTHB" value="<?= $PriceTHB ?>" placeholder="Regular Price" class="edit">
                        <label style="font-size:12px;color:#888;margin:0">Silver Member</label>
                        <input type="number" id="Price_Silver" value="<?= $Price_Silver ?>" placeholder="Silver Member Price" class="edit">
                        <label style="font-size:12px;color:#888;margin:0">Gold Member</label>
                        <input type="number" id="Price_Gold" value="<?= $Price_Gold ?>" placeholder="Gold Member Price" class="edit">
                        <label style="font-size:12px;color:#888;margin:0">Platinum Member</label>
                        <input type="number" id="Price_Platinum" value="<?= $Price_Platinum ?>" placeholder="Platinum Member Price" class="edit">
                        <label style="padding-top:20px;font-size:12px;color:#888;margin:0">Delivery</label>
                        <input type="number" id="Price_Delivery" value="<?= $Price_Delivery ?>" placeholder="Price on Delivery" class="edit">
                        <label style="padding-top:20px;font-size:12px;color:#888;margin:0">Cost</label>
                        <input type="number" id="PriceTHB2" value="<?= $PriceTHB2 ?>" placeholder="Cost Price" class="edit">
                    </td>
                </tr>
                <tr>
                    <th style="padding-top:20px;" valign="top">Point</th>
                    <td>
                        <label style="font-size:12px;color:#888;margin:0">Purchase</label>
                        <input type="number" id="Point_Cost" value="<?= $Point_Cost ?>" placeholder="Get on Purchase" title='เมื่อซื้อรายการนี้ สมาชิกได้ Point สะสมเท่าไหร่' class="edit">
                        <label style="font-size:12px;color:#888;margin:0">Redeem</label>
                        <input type="number" id="Point_Redeem" value="<?= $Point_Redeem ?>" placeholder="Used for Purchase" title='สมาชิกต้องใช้กี่ Point แลกซื้อรายการนี้' class="edit">
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:15px;">Active</th>
                    <td>
                        <label><input type="checkbox" data-role="flipswitch" id="POS" value="1" name="POS" <?php if ($POS) echo "checked"; ?> class="edit"><span style="font-size:14px;color:#555;margin-left:5px"> Display on POS screen</span></label>
                        <label><input type="checkbox" data-role="flipswitch" id="Scan" value="1" name="Scan" <?php if ($Scan) echo "checked"; ?> class="edit"><span style="font-size:14px;color:#555;margin-left:5px">Scan Barcode/Product Code</span></label>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $("#Type").val('<?= $Type ?>');
        $("#Kitchen").val('<?= $Kitchen ?>');
    </script>

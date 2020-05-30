

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
    $productid = $_GET['productid'];
    include("../php-config.conf");
    include("../php-db-config.conf");

    if ($productid AND $br) {
        $sqlMenu = "SELECT * ";
        $sqlMenu .= "FROM raw_material ";
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
                    <td><input type="text" id="Name" value="<?= $Name ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Name 2</th>
                    <td><input type="text" id="Name2" value="<?= $Name2 ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Code</th>
                    <td><input type="text" id="Raw_Material_Code" value="<?= $Raw_Material_Code ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Barcode</th>
                    <td><input type="number" id="Barcode" value="<?= $Barcode ?>" class="edit"></td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>
                        <select id="Type" class="edit" onChange="check_new_select(this)">
                            <?php
                            $sql_Group = "SELECT DISTINCT(Type) FROM raw_material WHERE BranchID='$br' ORDER BY Type";
                            $result_Group = $conn_db->query($sql_Group);
                            while ($db_Group = $result_Group->fetch_array()) {
                                echo"<option>" . $db_Group['Type'] . "</option>";
                            }
                            ?>
                            <option value="new---19">+New Group</option>
                        </select>
                        <input type="text" id="Type_new" style="display:none;" class="edit" placeholder="Define New Group">
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:35px;">Unit</th>
                    <td>
                        <div style="float:left;width:150px;">
                            <label style="font-size:12px;color:#888;margin:0">Selling Unit</label>
                            <input type="text" id="Unit" value="<?= $Unit ?>" class="edit">
                            <label style="font-size:12px;color:#888;margin:0">Purchasing Unit</label>
                            <input type="text" id="Buying_Unit" value="<?= $Buying_Unit ?>" class="edit">
                            <label style="font-size:12px;color:#888;margin:0">Conversion</label>
                            <input type="number" id="Unit_Convert" value="<?= $Unit_Convert ?>" class="edit">
                        </div>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Alert at</th>
                    <td>
                        <div style="float:left;width:120px;"><input type="number" id="Min_Alert" value="<?= $Min_Alert ?>" class="edit" placeholder="Re-Order Point"></div>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:12px;">Lease Time</th>
                    <td>
                        <div style="float:left;width:120px;"><input type="number" id="Lease_Time" value="<?= $Lease_Time ?>" class="edit" title="Time between purchasing and item arrived"></div> <div style="float:left;font-size:12px;padding-top:20px;">&nbsp;Day(s)</div>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Shelf Life</th>
                    <td>
                        <div style="float:left;width:120px;"><input type="number" id="Shelf_Life" value="<?= $Shelf_Life ?>" class="edit" title="Life on shelf (from item arrived / not from Manufacturer date)"></div><div style="font-size:12px;padding-top:20px;">&nbsp;Day(s)</div>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Price</th>
                    <td>
                        <div style="float:left;width:120px;"><input type="number" id="Price" value="<?= $Price ?>" class="edit" title="Curent Price"></div>
                    </td>
                </tr>
                <tr><td colspan="2" style="height:30px"></td></tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Supplier</th>
                    <td>
                        <input type="text" id="Supplier_Name" value="<?= $Supplier_Name ?>" class="edit" placeholder="Supplier Name">
                        <textarea id="Supplier_Address" class="edit" style="width:300px;height:70px" placeholder="Supplier Address"><?= $Supplier_Address ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Condition</th>
                    <td>
                        <textarea id="Sell_Condition" class="edit" style="width:300px;height:100px"><?= $Sell_Condition ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th valign="top" style="padding-top:18px;">Remark</th>
                    <td>
                        <textarea id="Remark" class="edit" style="width:300px;height:100px"><?= $Remark ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>Active</th>
                    <td>
                        <label><input type="checkbox" data-role="flipswitch" id="Active" value="1" name="Active" <?php if ($Active) echo "checked"; ?> class="edit"><span style="font-size:14px;color:#555;margin-left:5px"> Item is available</span></label>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $("#Type").val('<?= $Type ?>');
    </script>
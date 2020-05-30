<?php
include("../php-db-config.conf");
?>

<table style='width:100%;max-width:350px;'>
    <tr>
        <td colspan='2' style="background-color:#aaa;padding:8px;color:#fff;text-align:center;">DATA Form</td>
    </tr>
    <tr>
        <td valign="top" style="padding-top:20px;">Issue</td>
        <td style="max-width:280px;">
            <select onchange="load_sub_issue(this.value)">
                <option></option>
                <?php
                $sql = "SELECT T1 FROM issue Group BY 1 ORDER BY 1;";
                $result = $conn_db->query($sql);
                while ($db = $result->fetch_array()) {
                    $T1 = $db['T1'];
                    ?>
                    <option><?= $T1 ?></option>
                <?php } ?>
            </select>
            <div id="sub_issue" style="width:100%"></div>
        </td>
    </tr>
    <tr>
        <td valign="top"  style="padding-top:20px;">Product</td>
        <td style="max-width:280px;">
            <select id="Product_Selection" onchange="load_sub_product(this.value, '')">
                <option></option>
                <option value="bc_search">Barcode Search</option>
                <?php
                $sql = "SELECT Brand AS Cat FROM product WHERE Brand<>'' Group BY 1 ORDER BY 1;";
                $result = $conn_db->query($sql);
                while ($db = $result->fetch_array()) {
                    $Cat = $db['Cat'];
                    ?>
                    <option><?= $Cat ?></option>
                <?php } ?>
            </select>
            <div id="bc_search_form" style="display:none;">
                <div style="float:left;"><input type="number" id="bc_search"></div>
                <div style="float:left;"><button class="ui-btn ui-btn-corner-all ui-btn-icon-notext ui-icon-search ui-mini" onclick="search_barcode();">Search</button></div>
            </div>
            <div id="sub_product"></div>
        </td>
    </tr>
    <tr>
        <td valign="top"  style="padding-top:20px;">Location</td>
        <td>
            <select onchange="load_sub_store(this.value)">
                <option></option>
                <?php
                $sql = "SELECT Store_Type FROM store Group BY 1 ORDER BY 1;";
                $result = $conn_db->query($sql);
                while ($db = $result->fetch_array()) {
                    $Store_Type = $db['Store_Type'];
                    ?>
                    <option><?= $Store_Type ?></option>
                <?php } ?>
            </select>
            <div id="sub_store"></div>
        </td>
    </tr>

    <tr>
        <td valign='top'>Picture</td>
        <td>
            <input type='file' id="picture1">
            <input type='file' id="picture2">
            <input type='file' id="picture3">
        </td>
    </tr>
    <tr>
        <td valign="top"  style="padding-top:20px;">Explain</td>
        <td><textarea id="Explain"></textarea></td>
    </tr>
    <tr>
        <td>User</td>
        <td><div class="AREA-USERNAME" style="font:bold 15px sans-serif"></div><input class="AREA-USERNAMEID" type="hidden"></td>
    </tr>
    <tr>
        <td colspan="2"><button onclick="qa_add()">Add new DATA</button></td>
    </tr>
</table>
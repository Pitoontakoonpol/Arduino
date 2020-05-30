<?php
include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
?>        
<div class="setting-box">
    <h2 class="setting-title">Account Management</h2>
    <div style="text-align:left;padding-left:10px;font:normal 15px sans-serif;color:#000;"><button onclick="open_add_form()" class="ui-btn ui-btn-inline ui-mini ui-btn-icon-left ui-icon-plus ui-corner-all">Add Staff</button></div>

    <div id="Add-Form" data-role="popup" data-overlay-theme="b" class="ui-content">
        <div style="text-align:center;padding:5px;font:bold 22px sans-serif;background-color:#ddd" id="form-title">Add New Staff</div>
        <table style="margin-top:10px">
            <tr>
                <th><div class="Add_Section">Username</div></th>
                <td><div style="float:left;width:100px;" class="Add_Section"><input type='text' id='regis-username'></div><div style="float:left;padding-top:15px;" class="Add_Section">&nbsp;@<?= $br ?></div></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><div style="float:left;width:100px;"><input type='text' id='regis-password'></div><div style="float:left;padding-top:15px;"class="Add_Section">&nbsp;&nbsp;*</div></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><div style="float:left;width:250px;"><input type='text' id='regis-name'> </div><div style="float:left;padding-top:15px;">&nbsp;&nbsp;*</div></td>
            </tr>
            <tr>
                <th>Position</th>
                <td><input type='text' id='regis-position'></td>
            </tr>
            <tr>
                <th>Telephone</th>
                <td><input type='number' id='regis-telephone' ></td>
            </tr>
            <tr>
                <th>Salary</th>
                <td><div style="float:left;width:100px;"><input type='number' id='regis-salary' style='text-align:right;' title="if Salary less than 2,000 will considor as hourly payment staff."></div></td>
            </tr>
            <tr>
                <th>ค่าอาหาร</th>
                <td><div style="float:left;width:100px;"><input type='number' id='regis-salaryfood' style='text-align:right;'></div></td>
            </tr>
            <tr>
                <th valign="top">Remark</th>
                <td><div style="width:300px;"><textarea id='regis-remark' style="min-height:100px;font-size:12px;"></textarea></div></td>
            </tr>
            <tr>
                <td colspan='2'><input type="hidden" id="staffid" ><button class="ui-btn ui-btn-b ui-corner-all" id="form-submit" onclick="check_add()">Add Staff</button></td>
            </tr>
        </table>

    </div>
    <div id="account-table" style="overflow-x:auto;border:solid 1px gray;">

        <table class="permission-table" cellspacing="1" style="background-color:#ccc;">
            <tr>
                <th></th>
                <th>Username</th>
                <th>Name</th>
                <th>POS</th>
                <th class="permission_stock">Stock</th>
                <th>Status</th>
                <th>Product</th>
                <th class="permission_stock">Raw-Material</th>
                <th>Member</th>
                <th class="permission_promotion">Promotion</th>
                <th>Cashdrawer</th>
                <th>Report</th>
                <th>Void Bill</th>
            </tr>
            <?php
            $sql = "SELECT * FROM username WHERE BranchID='$br' AND Username<>'admin' AND Status IN(0,1) ORDER BY Username,Firstname";
            $result = $conn_db->query($sql);
            while ($db = $result->fetch_array()) {
                $ID = $db['ID'];
                $Username = $db['Username'];
                $Firstname = $db['Firstname'];
                $Position = $db['Position'];
                $Telephone = $db['Telephone'];
                $Salary = $db['Salary'];
                $Salaryfood = $db['Salaryfood'];
                $Salarydeduct = $db['Salarydeduct'];
                $Remark = $db['Remark'];
                $Status = $db['Status'];
                $Permission_POS = $db['Permission_POS'];
                $Permission_Kitchen = $db['Permission_Kitchen'];
                $Permission_Stock = $db['Permission_Stock'];
                $Permission_Status = $db['Permission_Status'];
                $Permission_Menu = $db['Permission_Menu'];
                $Permission_Raw_Material = $db['Permission_Raw_Material'];
                $Permission_Promotion = $db['Permission_Promotion'];
                $Permission_Cashdrawer = $db['Permission_Cashdrawer'];
                $Permission_Report = $db['Permission_Report'];
                $Permission_Member = $db['Permission_Member'];
                $Permission_Void = $db['Permission_Void'];
                if ($Salaryfood == 0) {
                    $Salaryfood = '';
                }
                if ($Salarydeduct == 0) {
                    $Salarydeduct = '';
                }
                ?>
                <tr>
                    <td style="background-color:#ddd; " valign="top">
                        <div style="float:left;width:25px;height:20px;">
                            <img id="yellow-<?= $ID ?>" src="../../img/action/bulb-yellow-20.png" onclick="status_to('<?= $ID ?>', 0)" />
                            <img id="gray-<?= $ID ?>" src="../../img/action/bulb-gray-20.png" onclick="status_to('<?= $ID ?>', 1)"/>
                            <?php
                            if ($Status == 1) {
                                echo "<script type='text/javascript'>$('#gray-$ID').hide();</script>";
                            } else {
                                echo "<script type='text/javascript'>$('#yellow-$ID').hide();</script>";
                            }
                            ?>
                        </div>
                        <div style="float:left;"><img src="../../img/action/edit-20.png" onclick="open_edit_form('<?= $ID ?>')"/></div>
                    </td>
                    <td style="text-align:left;background-color:#ddd;font:bold 14px sans-serif" valign="top"><?= strtolower($Username) ?></td>
                    <td style="text-align:left;background-color:#ddd" valign="top">
                        <div style="font:bold 14px sans-serif" id="Firstname-<?= $ID ?>"><?= ucfirst($Firstname) ?></div>
                        <div class="fs12"  id="Position-<?= $ID ?>"><?= ucfirst($Position) ?></div>
                        <div class="fs12" id="Telephone-<?= $ID ?>"><?= $Telephone ?></div>
                        <div class="fs12" id="Salary-<?= $ID ?>"><?= $Salary ?></div>
                        <div class="fs12" id="Salaryfood-<?= $ID ?>"><?= $Salaryfood ?></div>
                        <div class="fs12" id="Salarydeduct-<?= $ID ?>"><?= $Salarydeduct ?></div>
                        <div class="fs10 gray" id="Remark-<?= $ID ?>"><?= $Remark ?></div>
                    </td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="POS-<?= $ID ?>" <?php if ($Permission_POS) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top" class="permission_stock"><input type="checkbox" onchange="change_permission(this)" id="Stock-<?= $ID ?>"<?php if ($Permission_Stock) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Status-<?= $ID ?>"<?php if ($Permission_Status) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Menu-<?= $ID ?>"<?php if ($Permission_Menu) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top" class="permission_stock"><input type="checkbox" onchange="change_permission(this)" id="Raw_Material-<?= $ID ?>"<?php if ($Permission_Raw_Material) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top" class="permission_promotion"><input type="checkbox" onchange="change_permission(this)" id="Promotion-<?= $ID ?>"<?php if ($Permission_Promotion) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Member-<?= $ID ?>"<?php if ($Permission_Member) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Cashdrawer-<?= $ID ?>"<?php if ($Permission_Cashdrawer) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Report-<?= $ID ?>"<?php if ($Permission_Report) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                    <td valign="top"><input type="checkbox" onchange="change_permission(this)" id="Void-<?= $ID ?>"<?php if ($Permission_Void) echo "checked='checked'"; ?>  data-role="flipswitch"data-mini="true"></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>

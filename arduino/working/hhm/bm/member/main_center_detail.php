<?php
session_start();

include("../php-config.conf");
include("../php-db-config.conf");
$br = $_GET['br'];
$form = $_GET['form'];
$id = $_GET['id'];
?>
<script type="text/javascript">
    function check_new_select(selectid, val) {
        if (val === 'addNewSelect19') {
            $("#" + selectid + "_New").show().focus().css("margin-left", "20px");
        } else {
            $("#" + selectid + "_New").hide().val('');
        }
    }
</script>
<?php
if ($form == 'view' OR $form == 'edit') {
    $sql = "SELECT * FROM `member` WHERE ID='$id' AND BranchID='$br'";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
        include("db.php");
    }
}
if ($form != 'add') {
    ?>
    <div style="padding:0 10px;float:left;">
        <button class="ui-btn ui-icon-bars ui-btn-icon-left ui-btn-inline" onclick="change_center_mode('view')">View</button>
        <button class="ui-btn ui-icon-edit ui-btn-icon-left ui-btn-inline" onclick="change_center_mode('edit');">Edit</button>
        <button class="ui-btn ui-icon-clock ui-btn-icon-left ui-btn-inline">History</button>

    </div>
<?php } ?>
<div style="float:right">
    <a href="#" data-rel="back" class="ui-btn ui-shadow ui-btn-b ui-icon-delete ui-btn-icon-left ui-btn-inline" style="margin-left:25px;">Close</a>
</div>
<?php
if ($form == 'add' OR $form == 'view' OR $form == 'edit') {
    ?>
    <input type="hidden" id="ID" value="<?= $ID ?>" class='edit'/>
    <input type="hidden" id="form" value="<?= $form ?>"/>
    <div id="map"></div>
    <div style='background-color:#ddd;height:18px;'>
        <input type="text" id="Lat" class="form_mode add edit" value="<?= $Lat ?>" disabled="disabled" style="float:left;background-color:#ddd;border:none;font-size:12px;width:120px;color:gray">
        <input type="text" id="Lng" class="form_mode add edit" value="<?= $Lng ?>" disabled="disabled" style="float:left;background-color:#ddd;margin-left:10px;border:none;font-size:12px;width:120px;color:gray">
    </div>
    <div style="text-align:left; font:bold 25px sans-serif"><span class="form_mode view"><?= $Name ?></span></div>
    <table cellspacing="1" cellpadding="5" class="main-form">
        <tr>
            <th><span style='text-align:right;' class="form_mode add edit">Name</span></th>
            <td>
                <input type="text" id="Name" value="<?= $Name ?>"placeholder="**Required Filled" class="fs16 form_mode add edit" style="height:30px;width:420px;border:solid 1px gray;"/> 
            </td>
        </tr>
        <tr>
            <th>Code</th>
            <td>
                <input type="text" name="Member_Code" id="Member_Code" value="<?= $Member_Code ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $Member_Code ?></div>
            </td>
        </tr>
        <tr>
            <th>Group</th>
            <td>
                <div class='form_mode add edit'>
                    <select id='Member_Type' name="Member_Type" onchange="selection_change(this)" class='form_mode add edit'>
                        <option></option>
                        <?php
                        $sql_Select = "SELECT Member_Type,COUNT(Member_Type) AS cnt FROM member WHERE Member_Type<>'' AND BranchID='$br' GROUP BY 1 ORDER BY 2 DESC;";
                        $result_Select = $conn_db->query($sql_Select);
                        while ($db_Select = $result_Select->fetch_array()) {
                            $Type_Select = $db_Select['Member_Type'];
                            $cnt_Select = $db_Select['cnt'];
                            ?>
                            <option value="<?= $Type_Select ?>"><?= $Type_Select ?> (<?= number_format($cnt_Select) ?>)</option>
                        <?php }
                        ?>
                        <option style="background-color:#0000ff;color:#fff" value='new___selection'>+new Group</option>
                    </select>
                    <input type='text' id='Member_Type_New' name="Member_Type_New" style="width:200px;display:none;" class='form_mode select_new add edit'>
                </div>
                <div class='form_mode view'><?= $Member_Type ?></div>
            </td>
        </tr>
        <tr>
            <th valign="top" style="width:100px;">Gender</th>
            <td>
                <div style="float:left;">
                    <select id="Gender" class="form_mode add edit">
                        <option value='0'>Undefined</option>
                        <option value='1'>Male</option>
                        <option value='2'>Female</option>
                    </select>
                </div>
                <div class="form_mode view">
                    <?php
                    if (!$Gender OR $Gender == 0) {
                        $Gender_Title = 'Undefined';
                    } else if ($Gender == '1') {
                        $Gender_Title = 'Male';
                    } else if ($Gender == '2') {
                        $Gender_Title = 'Female';
                    }
                    echo $Gender_Title;
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <th valign="top" style="width:100px;">Level</th>
            <td>
                <div style="float:left;">
                    <select id="Level" class="form_mode add edit">
                        <option value='0'>Regular</option>
                        <option value='1'>Platinum</option>
                        <option value='2'>Gold</option>
                        <option value='3'>Silver</option>
                    </select>
                </div>
                <div class="form_mode view">
                    <?php
                    if (!$Level OR $Level == 0) {
                        $Level_Title = 'Regular';
                    } else if ($Level == '1') {
                        $Level_Title = 'Platinum';
                    } else if ($Level == '2') {
                        $Level_Title = 'Gold';
                    } else if ($Level == '3') {
                        $Level_Title = 'Silver';
                    }
                    echo $Level_Title;
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <th>Mobile</th>
            <td>
                <input type="number"  id="Mobile" value="<?= $Mobile ?>" class='form_mode add edit' placeholder="**Required Filled"/>
                <div class="form_mode view"><?= $Mobile ?></div>
            </td>
        </tr>
        <tr>
            <th>Telephone</th>
            <td>
                <input type="number" id="Telephone" value="<?= $Telephone ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $Telephone ?></div>
            </td>
        </tr>
        <tr>
            <th>Line ID</th>
            <td>
                <input type="text" name="LineID" id="LineID" value="<?= $LineID ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $LineID ?></div>
            </td>
        </tr>
        <tr>
            <th>Email</th>
            <td>
                <input type="email" name="Email" id="Email" value="<?= $Email ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $Email ?></div>
            </td>
        </tr>
        <tr>
            <th>DOB</th>
            <td>
                <input type="date" name="DOB" id="DOB" value="<?= $DOB ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $DOB ?></div>
            </td>
        </tr><tr>
            <th>ID Card#</th>
            <td>
                <input type="text" name="TaxNo" id="TaxNo" value="<?= $TaxNo ?>" class='form_mode add edit'/>
                <div class="form_mode view"><?= $TaxNo ?></div>
            </td>
        </tr>
        <tr>
            <th>Postal Code</th>
            <td>
                <input type="number" id="Postal" value="<?= $Postal ?>" class="form_mode add edit" style="width:100px;">
                <div class="form_mode view"><?= $Postal ?></div>
            </td>
        </tr>
        <tr>
            <th valign="top">Address</th>
            <td>
                <label style="color:gray;font-size:13px;">Living</label>
                <textarea class="form_mode add edit" id="Living_Address" style="width:400px;height:80px;font:normal 14px sans-serif"><?= $Living_Address ?></textarea>
                <span class="form_mode view"><?= $Living_Address ?></span>
                <label style="color:gray;font-size:13px;">Billing</label>
                <textarea class="form_mode add edit" id="Billing_Address" style="width:400px;height:80px;font:normal 14px sans-serif"><?= $Billing_Address ?></textarea>
                <span class="form_mode view"><?= $Billing_Address ?></span>
            </td>
        </tr>
        <tr>
            <th valign="top">Remark</th>
            <td>
                <textarea class="form_mode add edit" id="Remark" style="width:400px;height:100px;font:normal 14px sans-serif"><?= $Remark ?></textarea>
                <span class="form_mode view"><?= $Remark ?></span>
            </td>
        </tr>
        <tr>
            <th valign="top">Active</th>
            <td>
                <?php
                if ($Active == 1) {
                    $Active_Title = 'Yes';
                } else {
                    $Active_Title = 'No';
                }
                ?>
                <select id='Active' class='form_mode add edit'>
                    <option value='1'>Yes</option>
                    <option value='0'>No</option>
                </select>
                <span class="form_mode view"><?= $Active_Title ?></span>
            </td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td>
                <button class="form_mode add" style="font-size:22px;" onclick="check_add()">Add Member</button>
                <button class="form_mode edit"  style="font-size:22px;" onclick="check_edit()">Modify</button>
            </td>
        </tr>
    </table>
    <script type="text/javascript">
        $("#Member_Type").val('<?= $Member_Type ?>');
        $("#Gender").val(<?= $Gender ?>);
        $("#Level").val('<?= $Level ?>');
        $("#Active").val('<?= $Active ?>');
    </script>
    <?php
}
?>

<style type="text/css">
    .add,.view,.edit{
        display:none;
    }
    #mapCanvas {
        width: 600px;
        height: 400px;
    }
</style>
<?php
if ($form == 'add') {
    echo "<style type='text/css'>.add{display:block;}</style>";
} else if ($form == 'view') {
    echo "<style type='text/css'>.view{display:block;}</style>";
} else if ($form == 'edit') {
    echo "<style type='text/css'>.edit{display:block;}</style>";
}
?>
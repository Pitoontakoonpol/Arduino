<?php
include("../php-db-config.conf");
$opr = $_GET['opr'];
$ID = $_GET['ID'];
?>
<?php
$sql = "SELECT ";
$sql .= "A.ID, A.MachineID, A.CameraID1, A.CameraID2, A.CameraID3, A.Play_Mode, A.Grab_Weight, A.Max_Second, A.Category, A.Title, A.Price, A.Location, A.Tag, A.Active ";
$sql .= "FROM online_machine A ";

if ($opr == 'submit_prepend') {
    $sql .= " WHERE A.ID='$ID' ";
}
$sql .= "ORDER BY A.Category DESC;";
//echo $sql;
$result = $conn_db->query($sql);
while ($db = $result->fetch_array()) {
    foreach ($db as $key => $value) {
        $$key = $value;
    }

if($CameraID1==0) $CameraID1="<span class='na'>n/a</span>";
if($CameraID2==0) $CameraID2="<span class='na'>n/a</span>";
if($CameraID3==0) $CameraID3="<span class='na'>n/a</span>";
    include("LIST_NODE.php");
}
?>
<style type="text/css">
    .LIST-NODE{
        position:relative;
        padding:3px;
        background-color:#f4f4f4;
        border-top:solid 2px #e3e3e3;
        clear:both;
        overflow: hidden;
    }
    .LN-DESC{
        float:left;
        overflow:hidden;
        white-space: nowrap;
        margin-right:5px;
        padding:3px;
        height:20px;
        font:normal 14px sans-serif;


    }
    .LN-MID{
        width:75px;
        float:left;
        font:bold 15px courier;
        color:blue;
        margin-right:5px;
        padding:1px;
        height:24px;
    }
    .LN-PIC{
      position:absolute;
      overflow: hidden;
      right:0;
      top:0;
    }

    .LN-CAMERA div{
      float:left;
    }
    .LN-ICON{
      height:20px;
      margin:0 10px;
    }
    .na{
      color:gray;
      font-style: italic;
    }
</style>

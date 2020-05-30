<?php

include("../php-db-config.conf");
$opr = $_GET['opr'] . $_POST['opr'];

$variable_list=$_POST['variable_list'];
$value_send=$_POST['value_send'];

$expld_VL=explode(",",$variable_list);
$expld_VS=explode("__19__",$value_send);
for($v=0;$v<COUNT($expld_VL);$v++){
  $VL_Data=$expld_VL[$v];
  $VS_Data=$expld_VS[$v];
  $$VL_Data=$VS_Data;
  //  echo $VL_Data."($v) -> ".$VS_Data."\n";
}

if ($opr == 'submit_insert') {

  if(!$Max_Second)$Max_Second=30;
  if(!$Grab_Weight)$Grab_Weight=888888;
  if(!$Price)$Price=10;
  if($Active!='1')$Active=0;

    $sql = "INSERT INTO online_machine (MachineID,CameraID1,CameraID2,CameraID3,Play_Mode,Grab_Weight,Max_Second,Category,Title,Price,Location,Tag,Active) VALUES ";
    $sql .= "( '$MachineID', '$CameraID1', '$CameraID2', '$CameraID3', '$Play_Mode', '$Grab_Weight', '$Max_Second', '$Category', '$Title', '$Price', '$Location', '$Tag', '$Active')";

    if ($conn_db->query($sql) === true) {
        $last_id = $conn_db->insert_id;
        echo $last_id;
    }
}
else if ($opr == 'submit_edit') {
  $id=$_POST['id'];

  if(!$Max_Second)$Max_Second=30;
  if(!$Grab_Weight)$Grab_Weight=888888;
  if(!$Price)$Price=10;
  if(!$Active)$Active=0;

    $sql = "UPDATE online_machine SET
              MachineID='$MachineID',
              CameraID1='$CameraID1',
              CameraID2='$CameraID2',
              CameraID3='$CameraID3',
              Play_Mode='$Play_Mode',
              Grab_Weight='$Grab_Weight',
              Max_Second='$Max_Second',
              Category='$Category',
              Title='$Title',
              Price='$Price',
              Location='$Location',
              Tag='$Tag',
              Active='$Active'
            WHERE ID='$id';
            ";
    $result=$conn_db->query($sql);
    echo $id;
}

<?php

$d = $_GET['d'];
$expld_d=explode("-",$d);
$fr=date("ymd",mktime(0,0,0,$expld_d[1],$expld_d[2],$expld_d[0]));
$to=date("ymd",mktime(0,0,-1,$expld_d[4],$expld_d[5]+1,$expld_d[3]));

$fr_text=date("Y-m-d%20H:i:s",mktime(0,0,0,$expld_d[1],$expld_d[2],$expld_d[0]));
$to_text=date("Y-m-d%20H:i:s",mktime(0,0,-1,$expld_d[4],$expld_d[5]+1,$expld_d[3]));

$SN_Start=date("ymd",$fr)."0000000000";
$SN_End=date("ymd",$to)."9999999999";


$SN_Start_Yesterday=date("ymd",$fr-86400);

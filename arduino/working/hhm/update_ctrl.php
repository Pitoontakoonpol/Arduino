<?php

$id = $_GET['id'];
$val = $_GET['val'];
$file_name="ctrl/".$id;
if(!FILE_EXISTS($file_name)){
    
}
$myfile = fopen($file_name, "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $val);
fclose($myfile);
?>
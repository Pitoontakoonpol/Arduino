<?php

foreach ($db as $key => $value) {
    $$key = $value;
}
if ($Lat AND $Lng) {
    $Lat_Lng = $Lat . "," . $Lng;
}
$Member_Code=STR_PAD($Member_Code,4,0,STR_PAD_LEFT);
?>

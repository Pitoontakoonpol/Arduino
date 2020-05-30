<?php

function today($Date, $Language) {
//$Date format is date('u')
//$Language can be EN or TH
    global $Date_Now;
    global $Month_Now;
    global $Year_Now;
    global $DateU;
    $Date_Check = date("d", $Date);  // 01.02.03
    $Month_Check = date("m", $Date); // 01.02.03
    $Year_Check = date("Y", $Date);  // 2008
    $Sec_Diff = $DateU - $Date;
    $Gap = ceil($Sec_Diff/86400);
    $Time=date("H:i:s",$Date);
        if ($Sec_Diff>=0 AND $Sec_Diff<60) {
            $result = $Sec_Diff ." second ago";
            $resultTH = $Sec_Diff ." วินาที ที่ผ่านมา";
        }
        else if ($Sec_Diff>=60 AND $Sec_Diff<3600) {
            $result = number_format($Sec_Diff/60,0) ." minutes ago";
            $resultTH = number_format($Sec_Diff/60,0) ." นาที ที่ผ่านมา";
        }
        //echo $Date ."<>" .$Gap . " >>";
        else if ($Sec_Diff>=3600 AND $Sec_Diff<86400) {
            $result = number_format($Sec_Diff/3600,0) ." hours ago";
            $resultTH = number_format($Sec_Diff/3600,0) ." ชั่วโมง ที่แล้ว";
        } else if ($Gap == 1) {
            $result = "Yesterday";
            $resultTH = "เมื่อวานนี้";
        } else if ($Gap == 2) {
            $result = "2 days ago";
            $resultTH = "2 วันที่แล้ว";
        } else if ($Gap == 3) {
            $result = "3 days ago";
            $resultTH = "3 วันที่แล้ว";
        } else if ($Gap == 4) {
            $result = "4 days ago";
            $resultTH = "4 วันที่แล้ว";
        } else if ($Gap == 5) {
            $result = "5 days ago";
            $resultTH = "5 วันที่แล้ว";
        } else if ($Gap == 6) {
            $result = "6 days ago";
            $resultTH = "6 วันที่แล้ว";
        } else if ($Gap == 7) {
            $result = "7 days ago";
            $resultTH = "7 วันที่แล้ว";
        } else if ($Gap >7) {
            $result = date("d/m/y", $Date);
            $resultTH = date("d/m/y", $Date);
        } else if ($Gap == 1) {
            $result = "Tomorrow";
            $resultTH = "พรุ่งนั้";
        } else if ($Gap == 2) {
            $result = "Next 2 days";
            $resultTH = "อีก 2 วัน";
        }
        if ($Language=='TH') {
            $result=$resultTH . "<br/>".$Time;
        }
    return $result;
}

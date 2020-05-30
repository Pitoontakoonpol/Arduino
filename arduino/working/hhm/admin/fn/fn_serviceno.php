<?php

function full_serviceNo($datetime, $branchid, $runningno) {

    $ServiceNo_Full = date("ymd", $datetime) . str_pad($branchid, 5, "0", STR_PAD_LEFT) . str_pad($runningno, 5, "0", STR_PAD_LEFT);
    return $ServiceNo_Full;
}

function full_taxNo($branchid, $datetime, $runningno) {

    $Tax_Full = "T" . date("ym", $datetime) ."".$branchid."-" . str_pad($runningno, 5, "0", STR_PAD_LEFT);
    return $Tax_Full;
}

function full_voidNo($datetime, $runningno) {

    $Void_Full = "CN" . date("ym", $datetime) . "-" . str_pad($runningno, 5, "0", STR_PAD_LEFT);
    return $Void_Full;
}

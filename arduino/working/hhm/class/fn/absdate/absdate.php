<script type="text/javascript" src="<?= $path ?>absdate.js"></script>
<div id="abs-navigate"></div>
<div id="abs-result"></div>

<style type="text/css">
    #absmonth {
        height:30px;
        font:normal 14px sans-serif;
        border:none;
    }
    #absyear {
        height:30px;
        font:normal 14px sans-serif;
        width:60px;
        border:none;
        text-align:center;
        vertical-align:middle;

    }
    .absprev {
        height:20px;
        width:20px;
        vertical-align:middle;
        padding:0px;
        border:none;
        background:url('<?= $path ?>arr-top.png') no-repeat center;
    }
    .absnext {
        height:20px;
        width:20px;
        vertical-align:middle;
        padding:0px;
        border:none;
        background-color:#fff;
        background:url('<?= $path ?>arr-down.png') no-repeat center;
    }
    .absprev:hover {
        background:url('<?= $path ?>arr-top-hv.png') no-repeat center;
    }
    .absnext:hover {
        background:url('<?= $path ?>arr-down-hv.png') no-repeat center;
    }


    .abs-calendar th {
        text-align:center;
        font:normal 12px sans-serif;
        color:#ddd;
        border-bottom:solid 1px gray;
        background-color:#555;
        padding-top:10px;
        cursor:default;
    }
    .abs-calendar td:first-child {
        text-align:center;
        font:normal 12px sans-serif;
        color:#bbb;
        background-color:#555;
        cursor:default;
    }
    .abs-calendar td:nth-child(n+2) {
        width:13%;
        height:25px;
        text-align:center;
        font:normal 13px sans-serif;
        cursor:crosshair;
    }
    .selected-date{
        border:solid 2px #fff;
        border-radius:5px;
    }
    .current-date {
        border:solid 2px #555;
        border-radius:3px;
    }
    .current-month {
        background-color:#000;
        border-radius:3px;
        color:#fff;
    }
    .other-month {
        background-color:#222;
        color:#666;
        
    }
</style>
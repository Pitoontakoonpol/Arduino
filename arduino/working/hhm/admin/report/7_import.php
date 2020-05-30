<?php
session_start();
$br = $_GET['br'];
$usr = $_GET['usr'];
?>
<div>
    <form>
        <input type="file" name="import_file">
        <button>Import</button>
    </form>
    
</div>
<style type='text/css'>
    .cancel1 {
        color:red;
        text-decoration: line-through;
    }   
    .cancel2 {
        color:#666;
        text-decoration: line-through;
    }
    #invoice_view{
        display:none;
        position:fixed;
        z-index:10;
        top:90px;
        right:20px; 
        box-shadow: -0 0  10px #888888;
        width:350px;
        padding:10px;
        background-color:#fff;
        font:normal 14px sans-serif;

    }
    @media print{
        #invoice_view{
            float:left;
            border:none;
            margin-top:0;
            top:0;
            left:5px;
            width:350px;
        }
        .report2{
            font-size:12px;
        }
    }
</style>
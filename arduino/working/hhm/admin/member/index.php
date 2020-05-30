<?php
session_start();

include("../php-config.conf");
include("../php-db-config.conf");
$Map_Api_Key = 'AIzaSyDqDt8Q3hoURxHyy81DNmlGgAQRGS_WE2U';
$Page_Name = "Member";
?>
<html>
  <head>  
    <title>Membership</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
    <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
    <script src="../../class/jquery-1.11.3.min.js"></script>
    <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
    <link href="index.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="index.js?2"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqDt8Q3hoURxHyy81DNmlGgAQRGS_WE2U"></script>
    <script type="text/javascript">
      $(document).ready(function () {
        load_main_center(0);
      });

      var map;
      function initMap() {
        var lat = $("#Lat").val();
        var lng = $("#Lng").val();
        var form = $("#form").val();
        if (!lat || !lng || lat === '0' || lng === '0') {
          lat = '13.789956';
          lng = '100.703425';

        }

        var dragMarker = '';
        if (form === 'add' || form === 'edit') {
          dragMarker = true;
        } else {
          dragMarker = false;
        }
        var mapDiv = document.getElementById('map');
        var latLng = new google.maps.LatLng(lat, lng);
        map = new google.maps.Map(mapDiv, {
          center: latLng,
          zoom: 15
        });
        var marker = new google.maps.Marker({
          position: latLng,
          map: map,
          draggable: dragMarker,
          icon: '../../img/member_marker.png',
          title: 'Your Member Location!'
        });
        google.maps.event.addListener(marker, 'drag', function () {
          var new_lat=marker.getPosition().lat();
          var new_lng=marker.getPosition().lng();
          $("#Lat").val(new_lat);
          $("#Lng").val(new_lng);
        });
      }
    </script>
  </head>
  <body >
    <?php 
    include("../header/header.php"); 
    include("../../class/fn/amsalert.php"); 
    ?>
    <div style="position:fixed;z-index:150;top:0;right:80px;" data-rel="popup" onclick="load_add()">
      <a style="width:55px;height:55px;margin:0;"  class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-plus ui-btn-icon-notext ui-btn-b"></a>
    </div>
    <div style="position:fixed;z-index:150;top:0;right:20px;" data-rel="popup" onclick="$('#main-left').popup('open');">
      <a style="width:55px;height:55px;margin:0;"  class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-search ui-btn-icon-notext ui-btn-b"></a>
    </div>
    <input type='hidden' id='td_height' value="70">
    <input type='hidden' id='page_size' value="100">
    <input type='hidden' id='page_now' value="0">

    <div id="main-top" style="text-align:center;background-color:#444;height:60px;">
      <div style="padding:20px 0;font:normal 14px sans-serif;color:#fff" id='Total_Records'></div>
      <div style="font:normal 13px sans-serif;padding:5px 120px 0 0;width:300px;"></div>
    </div>

    <div id="spare" style='display:none;'>--spare here--</div>
    <div id="main-center"><div id="page0"></div></div>
    <div id="main-center-edit" data-role="popup" data-overlay-theme="b" data-dismissible="false"></div>
    <div id="main-search" data-role="popup" data-overlay-theme="b"><?php include("main_search.php"); ?></div>
  </body>
</html>

<?php
$Page_Name = 'Status';

$area_height = 250;
?>
<html>
  <head>
    <title><?= $Page_Name ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="index.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
    <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
    <script src="../../class/jquery-1.11.3.min.js"></script>
    <script src="../../class/jquery.mobile-1.4.5.min.js"></script>

    <script type="text/javascript">
      var br = localStorage.BMbr;
      $(document).ready(function () {
        $("#INTERNAL").load("status.php?br=" + br);

      });
      function initMap() {
        var plotting = $("#plotting").val().split("|");
        for (var m = 0; m < plotting.length-1; m++) {
          var locat = plotting[m].split("___");
          var lat = locat[0];
          var lng = locat[1];
          var title = locat[2];
          var plot = {lat: parseFloat(lat), lng: parseFloat(lng)};
          if (m === 0) {
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 12,
              center: plot
            });
          }
          var marker = new google.maps.Marker({
            title: title,
            icon:'../../img/hhm_pin.png',
            position: plot,
            map: map
          });
        }
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTB0XoZpj5BmPpKSFpb2WZ_40Gw7lGt4E"></script>
  </head>
  <body>
    <?php
    include("../header/header.php");
    ?>
    <div id="map"></div>
    <div id="INTERNAL"></div>

    <style type="text/css">
      #map {
        width: 100%;
        height: 600px;
        background-color: grey;
      }
    </style>
  </body>
</html>
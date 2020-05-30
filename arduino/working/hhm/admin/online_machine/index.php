<?php
$Page_Name = 'Status';
include("../php-db-config.conf");
?>
<html>
    <head>
        <title><?= $Page_Name ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="index.css" rel="stylesheet" type="text/css" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../../class/jquery.mobile-1.4.5.min.css">
        <script src="../../class/jquery-1.11.3.min.js"></script>
        <script src="../../class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" src="index.js"></script>
    </head>
    <body>
        <?php
          include("../header/header.php");
        ?>
        <div id="spare" style="position:fixed;width:80%;left:100px;top:0px;display:block;z-index:800">--Spare here--</div>
        <div id="spare2" style="display:none;position:fixed;width:80%;left:100px;top:30px;z-index:800">--Spare here--</div>
        <div id="list-result" style="padding-top:90px;">--List--</div>
        <div id="POPUP-DATA-ADD">
          <div style="position:absolute;"><button onclick="close_form()" class="ui-btn ui-corner-all ui-shadow ui-btn-icon-left ui-icon-delete ui-mini ui-btn-b">Close</button></div>
            <div class="PDATA-HEADER">
                <div style="padding:10px;text-align:center;font:normal 24px sans-serif;">Add new DATA</div>
            </div>
            <div class="step step1 PDATA-MAIN" style="padding:10px;">
                <input type="hidden" id="editID" value="">
              <table style="width:100%;" class="add-form">
                <tr>
                  <td>MachineID<br><input type="number" id="MachineID" placeholder="MachineID" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Title<br><input type="text" id="Title" placeholder="Title" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Category<br><input type="text" id="Category" placeholder="Category" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Play Mode<br>
                      <select id="Play_Mode" class="add_form_input">
                        <option value="0"></option>
                        <option value="1">Mode 1</option>
                        <option value="2">Mode 2</option>
                        <option value="3">Mode 3</option>
                      </select>
                  </td>
                </tr>
                <tr>
                  <td>Grab Weight (6 Steps)<br><input type="number" id="Grab_Weight" placeholder="Grab Weight" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>HP<br><input type="number" id="Price" placeholder="Price" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Location<br><input type="text" id="Location" placeholder="Machine Located" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Camera ID 1<br><input type="number" id="CameraID1" placeholder="CameraID 1" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Camera ID 2<br><input type="number" id="CameraID2" placeholder="CameraID 2" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Camera ID 3<br><input type="number" id="CameraID3" placeholder="CameraID 3" class="add_form_input"></td>
                </tr>
                <tr>
                  <td>Tag<br><input type="text" id="Tag" placeholder="Search Tag" class="add_form_input"></td>
                </tr>
                <tr>
                  <td><div id="PICTURE-AREA"></div></td>
                </tr>
                <tr>
                  <td>Main Picture<br><input type="file" id="File1" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Reward Picture 1<br><input type="file" id="File2" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Reward Picture 2<br><input type="file" id="File3" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Reward Picture 3<br><input type="file" id="File4" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Reward Picture 4<br><input type="file" id="File5" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Reward Picture 5<br><input type="file" id="File6" class="file_upload"></td>
                </tr>
                <tr>
                  <td>Active<br>

                    <select id="Active" data-role="slider" class="add_form_input">
                    	<option value="0">Off</option>
                    	<option value="1">On</option>
                    </select>
                </tr>
                <tr>
                  <td><div style="padding:5px 100px;"><button onclick="submit_form()">Submit</button></div></td>
                </tr>
              </table>
            </div>
        </div>


        <div class="SUB-HEADER-BAR">
            <div style='float:right;padding:20px 0 0 0;margin-right:-8px'>
                <button class='ui-btn ui-btn-icon-left ui-icon-plus ui-corner-all' onclick='show_add_form()'>Add DATA</button>
            </div>
        </div>
        <div data-role="popup" id="DATA-Pic-Area" style="position:fixed;bottom:0;right:0"></div>

        <style type='text/css'>
        {
          max-width:100%;
        }
            #POPUP-DATA-ADD {
                display:none;
                position:fixed;
                top:0;
                right:0;
                z-index:151;
                width:100%;
                max-width:600px;
                box-shadow:0px 0px 10px #000;
            }
            .PDATA-HEADER{
                height:50px;
                background-image: linear-gradient(#000, #666);
                color:#fff;

            }
            .PDATA-MAIN{
                background-color:#fff;
                overflow-y:auto;
            }
            .PDATA-FOOTER{
                height:120px;
                background-image: linear-gradient(#000, #666);
                color:#fff;
            }
            .SUB-HEADER-BAR{
                position:fixed;
                top:0;
                width:calc(100% - 20px);
                height:50px;
                margin-left:20px;
                background-color:#888;
            }
            .ADD-FORM{
              font:bold 12px sans-serif;
            }
            .ADD-FORM input{
              font:normal 14px sans-serif;
            }
            #PICTURE-AREA img {
              width:90px;
              height:90px;
            }

        </style>
    </body>
</html>

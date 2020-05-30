<html class="htmlClass">
    <head>  
        <title>HHM</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="class/jquery.mobile-1.4.5.min.css">
        <script src="class/jquery-1.11.3.min.js"></script>
        <script src="class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var ww = window.innerWidth;
                var wh = window.innerHeight;
            });
            function update_ctrl(id, val) {
                $("#spare").load("update_ctrl.php?id=" + id + "&val=" + val, function (data) {

                });
            }
            function go_view(id, i) {
                var current_power = $("#ctrl-" + i).val()*19;
                window.location.replace("wsmcu/wsclient.php?id="+id+"&p="+current_power);

                
                
            }
        </script>
    </head>
    <body>
        <table class="main-table" cellpadding="5" cellspacing="3">
            <?php
            for ($i = 1; $i <= 10; $i++) {
                $file_name = "ctrl/" . $i;
                $id = 20200000 + $i;
                if (FILE_EXISTS($file_name)) {
                    $file_read = fopen($file_name, "r") or die("Unable to open file!");
                    $ctrl = fgets($file_read);
                    fclose($file_read);
                }
                ?>
                <tr>
                    <td><img src="machine_pic/<?= $i ?>.png" style="width:200px;height:200px;"></td>
                    <td>
                        <button onclick="go_view(<?= $id ?>,<?= $i ?>)"> View </button>
                        <button onclick="go_view(<?= $id ?>,<?= $i ?>)"> Play </button>
                        <div style="width:75px;">
                            <input type="text" id="ctrl-<?= $i ?>" onblur="update_ctrl('<?= $i ?>', this.value);" value="<?= $ctrl ?>">
                        </div>
                    </td>
                </tr>
                <?php
                unset($ctrl);
            }
            ?>
        </table>
        <div id="spare"></div>
        <style type="text/css">
            .main-table td{
                background-color:#ddd;
            }
        </style>
    </body>
</html>
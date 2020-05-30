<!DOCTYPE HTML>
<html class="htmlClass">
    <head>  
        <title>Ambient:NodeMCU</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="class/jquery.mobile-1.4.5.min.css">
        <script src="class/jquery-1.11.3.min.js"></script>
        <script src="class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                var ww = window.innerWidth;
                var wh = window.innerHeight;
                /*
                 $("button").mousedown(function () {
                 var cmd = $(this).attr("cmd");
                 var command = cmd.replace("TX", "TX=");
                 send_command(command);
                 $("#command_note").text(command + ' mouse down ');
                 
                 });
                 $("button").mouseup(function () {
                 send_command('TX=0');
                 $("#command_note").text('TX=0 mouse up ');
                 });
                 
                 */


                $(".command-btn").bind('touchstart', function () {
                    var cmd = $(this).attr("cmd");
                    var command = cmd.replace("TX", "TX=");
                    send_command(command);
                    $("#command_note").text(command + ' mouse down ');
                    $(this).css("fill", "#00FF00");

                });
                $(".command-btn").bind('touchend', function () {
                    $(this).css("fill", "#CCCCCC");
                    send_command('TX=0');
                    $("#command_note").text('TX=0 mouse up ');
                });
                infras();


                function infras() {
                    if (ww > 200) {
                        left_buffer=(ww-200)/2;
                        $("#BTN-CONTROL").css("margin-left", left_buffer);
                    }

                }
            });



            function send_command(command) {

                var ip_address = $("#ip_address").val();

                if (command === 0) {
                    command = $("#command_send").val();
                }

                command = command + "///";
                if (!ip_address) {
                    alert("Please input IP ADDRESS!");
                } else {
                    $("#command_send").val(command);
                    $("#spare").load("http://" + ip_address + "?" + command, function (data) {
                        $("#spare").html(data);
                    });
                }

            }
        </script>
    </head>
    <body>
        <iframe src="http://192.168.1.43:81/stream" style="width:100%"></iframe>
        <iframe src="http://192.168.1.51:81/stream" style="width:100%"></iframe></br>
        <div style="width:100%;max-width:350px;padding:10px;">
            <div style='display:none'>
                <input type="text" id="ip_address" placeholder="ip address" value="192.168.1.207">
                <input type="text" id="command_send" placeholder="command">
                <button onclick="send_command(0)">Send</button>
            </div>
            <table style="display:none;">
                <tr>
                    <td></td>
                    <td><button class='command-btn' cmd="TX1" style='font:bold 27px sans-serif'>↑</button></td>
                    <td></td>
                    <td rowspan="3" style="center:7px;"></td>
                </tr>
                <tr>
                    <td><button class='command-btn' cmd="TX3" style='font:bold 27px sans-serif'>←</button></td>
                    <td></td>
                    <td><button class='command-btn' cmd="TX4" style='font:bold 27px sans-serif'>→</button></td>
                    <td><button class='command-btn' cmd="TX5" style='font:bold 27px sans-serif'>Grab!</button></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button class='command-btn' cmd="TX2" style='font:bold 27px sans-serif'>↓</button></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div style='display:none'>
            <div id="command_note">-noted-</div>
            <div id="spare">-return-</div>
        </div>
        <div style="position:fixed;bottom:5px;width:100%;max-width:200px;" id="BTN-CONTROL">
            <?php
            include("img/control_btn.svg");
            ?>
        </div>
    </body>
</html>

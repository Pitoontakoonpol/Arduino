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

                });
                $(".command-btn").bind('touchend', function () {
                    send_command('TX=0');
                    $("#command_note").text('TX=0 mouse up ');
                });
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
	<iframe src="http://35.240.185.52/client1" seamless></iframe>
        <iframe src="http://34.87.71.163/client" seamless></iframe></br>
        <div style="width:100%;max-width:350px;padding:10px;">
            <div style='display:none'>
                <input type="text" id="ip_address" placeholder="ip address" value="192.168.1.207">
                <input type="text" id="command_send" placeholder="command">
                <button onclick="send_command(0)">Send</button>
            </div>
            <table>
                <tr>
                    <td></td>
                    <td><button class='command-btn' cmd="TX1" style='font:bold 30px sans-serif'>↑</button></td>
                    <td></td>
                    <td rowspan="3" style="padding-left:20px;"></td>
                </tr>
                <tr>
                    <td><button class='command-btn' cmd="TX3" style='font:bold 30px sans-serif'>←</button></td>
                    <td></td>
                    <td><button class='command-btn' cmd="TX4" style='font:bold 30px sans-serif'>→</button></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button class='command-btn' cmd="TX2" style='font:bold 30px sans-serif'>↓</button></td>
                    <td></td>
                </tr>
            </table><button class='command-btn' cmd="TX5" style='font:bold 30px sans-serif'>Grab!</button>
        </div>

        <div style='display:none'>
            <div id="command_note">-noted-</div>
            <div id="spare">-return-</div>
        </div>
    </body>
</html>

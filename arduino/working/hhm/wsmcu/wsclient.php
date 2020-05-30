<?php
session_start();
include("../admin/php-config.conf");
include("../admin/php-db-config.conf");
$ID = $_GET['ID'];
?>
<html>
    <head>
        <title>Online Crane : Ambient</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0"/>
        <link rel="stylesheet" href="../class/jquery.mobile-1.4.5.min.css">
        <link rel="stylesheet" href="wsclient.css">
        <script src="../class/jquery-1.11.3.min.js"></script>
        <script src="../class/jquery.mobile-1.4.5.min.js"></script>
        <script type="text/javascript" src="wsclient.js?<?=date('U')?>"></script>
    </head>
    <body>
    <?php
        include("../header.php");
        $sql = "SELECT * FROM online_machine WHERE ID='$ID' AND Active=1";
        //echo $sql;
        $result = $conn_db->query($sql);
        while ($db = $result->fetch_array()) {
            foreach ($db as $key => $value) {
                $$key = $value;
            }
        }
        ?>
        <div style="top:0;width:100%;background-color:#222;padding:3px;">
            <button class="ui-btn ui-btn-icon-left ui-icon-back ui-btn-inline ui-corner-all" onclick="location.href = '../'">Back</button>
            <div style="clear:both;"></div>
        </div>
        <div style="background-color:#111;padding:5px;">
            <div id="cam1" style="position:relative;background-color:#000;max-width:400px;margin:0 auto;">
		<!--img id='ws1'style="height:250px;border:solid 1px;"></img-->
		<iframe src="http://hhm.ambient.co.th:81/client_2020000<?=$ID*2?>"></iframe>  
                <div class="cam-caption">CAM 1</div>
            </div>

            <div id="cam2" style="display:none;position:relative;background-color:#000;max-width:400px;margin:0 auto;">
		            <!--img id='ws2'style="height:250px;border:solid 1px #fff;"></img-->
                <div class="cam-caption">CAM 2</div>
            </div>
        </div>

        <!--script>
		var img = [];
	          img.push(document.querySelector('#ws1'));
	          img.push(document.querySelector('#ws2'));
		//const WS_URL = 'ws:///35.240.185.52:65070';
	    	const WS_URL = ['ws://hhm.ambient.co.th:'+<?=65070+$ID*2?>,'ws://103.76.180.24:'+<?=65070+$ID*2+1?>];
            	
		const ws = [];
            	WS_URL.forEach((item,i)=>{
               	  ws[i]= new WebSocket(item);
            	});

		let urlObject;
            	ws.forEach((item,i)=>{
            	 item.onopen = () => {
		 console.log(`Connected to ${WS_URL[i]}`);

		 }

            	 item.onmessage = message => {
               	  let arrayBuffer = message.data;
		  if(urlObject){
                   URL.revokeObjectURL(urlObject);
                  }

		  urlObject = URL.createObjectURL(new Blob([arrayBuffer]));
             	  img[i].src = urlObject;

		  } // it's now just connecting but doing nothing.
          	 });
        </script-->




        <div id="PLAYER-DISPLAY" style="">
          <table cellpadding="3" cellspacing="0" border="0" style="font:normal 13px sans-serif;">
            <tr>
              <td>Waiting:<span id="cust_waiting">0</span></td>
              <td>Watching:<span id="cust_watching">1</span></td>
            </tr>
            <tr>
              <td><div id="cust_waiting_list"></div></td>
              <td><div id="cust_watching_list"></div></td>
            </tr>
          </table>
        </div>
        <div>
          <div id="REWARD-DISPLAY" style="float:left;font:normal 30px sans-serif;"></div>
          <div style="float:right;padding-right:10px;margin-top:25px;">
            <div id="btn-all"style="max-width:200px;display:none;">
                <?php
                include("../img/button/control_btn.svg");
                ?>
            </div>
            <div class="option-btn btn-play" onclick="adding_queue()" cmd="" bname="" ><img class="command-btn" src="../img/button/online-play.png"></div>
            <div class="option-btn btn-start" onclick="btn_start()" cmd="" bname=""><img class="command-btn" src="../img/button/online-start.png"></div>
            <div class="option-btn btn-up" onmousedown="send_press('TX1')" onmouseup="send_release('btn_up')" cmd="TX1" bname="btn_up"><img class="command-btn" src="../img/button/online-up.png"></div>
            <div class="option-btn btn-right" onmousedown="send_press('TX4')" onmouseup="send_release('btn_right')" cmd="TX4" bname="btn_right"><img class="command-btn" src="../img/button/online-right.png"></div>
            <div style="position:absolute;top:75px;right:20px;width:75px;" onclick="switch_camera(0)">
                <img src="../img/cam-view.svg" style="width:100%">
            </div>
          </div>
        </div>
        <div id="REWARD-LIST" style="clear:both;">
            <div style="font:normal 18px sans-serif;padding:5px;">รายการของรางวัลในตู้:</div>
            <?php
            for ($rw=2;$rw<=6;$rw++) {
                $RW_Filename="../machine_pic/".$ID."/".$ID."_File".$rw.".jpg";
                if (file_exists($RW_Filename)) {
                    ?>
              <div class="reward-node">
                  <div><img src="<?=$RW_Filename?>"></div>
                  <div class="reward-node-title"><?= $Title ?></div>
              </div>
              <?php
                }
            }
            ?>
        </div>
        <div style="position:absolute;bottom:10px;width:150px;left:1200px;padding:10px;background-color:orangered;" id="AREA-DEV">
          <div>
            <table>
                <tr>
                    <td></td>
                    <td><button class="control command-btn" onmousedown="send_command('TX1')" onmouseup="send_command('TX0')">↑</button></td>
                    <td></td>
                </tr>
                <tr>
                    <td><button class="control command-btn" onmousedown="send_command('TX3')" onmouseup="send_command('TX0')">←</button></td>
                    <td><button class="control command-btn" onclick="send_command('TX5')">G</button></td>
                    <td><button class="control command-btn" onmousedown="send_command('TX4')" onmouseup="send_command('TX0')">→</button></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button class="control command-btn" onmousedown="send_command('TX2')" onmouseup="send_command('TX0')">↓</button></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan='3'>
                        <button onclick="check_reward()">C-Reward</button>
                        <button onclick="send_command('TX8')">C-Reverse</button>
                        <button onclick="send_command('TX9')">Reset</button>
                    </td>
                </tr>
            </table>
          </div>
            <input type="text" id="id" placeholder="id" value="<?= $MachineID ?>">
            <input type="text" id="play_mode" placeholder="play_mode" value="<?= $Play_Mode ?>">
            <input type="text" id="command_note" placeholder="command">
            <input type="text" id="TY" placeholder="TY" value="<?= $Grab_Weight ?>" >
            <input typ="text" id="T" placeholder="Timg"  value="<?= $Max_Second ?>" full_time="<?= $Max_Second ?>" db_time="<?= $Max_Second ?>">
            <div id="TIME-COUNTDOWN"><?= $Max_Second ?></div>
        </div>
        <div style='display:none'>
            <div id="spare">-return-</div>
        </div>
        <div id="SocketMsg" style="color:#fff;position:fixed;left:10px;bottom:10px;background-color:#555;padding:10px;"></div>
        <script type="text/javascript">
            $("#BTN-CONTROL").show();
            $("#AREA-DEV").show();
        </script>
    </body>
</html>

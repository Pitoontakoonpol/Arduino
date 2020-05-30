        var memberID = localStorage.memberID;
        var memberName = localStorage.memberName;
        var sock, display, id, TY, play_mode, Timeout1, interval;

        $(document).ready(function() {
          id = $("#id").val();
          login_status();
          socket_init();
          infras();
          header_infra();

          //Smartphone Event
          $(".option-btn").bind('touchstart', function() {
            var cmd = $(this).attr("cmd");
            if (cmd) {
              send_press(cmd);
            }
          });
          $(".option-btn").bind('touchend', function() {
            var bname = $(this).attr("bname");
            if (bname) {
              send_release(bname);
            }
          });
        });

        function socket_init() {

          sock = new WebSocket("wss://hhmcrane.com:1919"); //replace this address with the one the node.js server prints out. keep port 1919
          //sock = new WebSocket("ws://192.168.1.95:1919"); //replace this address with the one the node.js server prints out. keep port 1919

          display = $("#display").val();
          alert("Stage 0");
          sock.onopen = function(event) {
            alert("Stage 1");
            sock.send("SCO" + id + 'Registered');

            alert("Stage 2");
            checking_queue();

            alert("Stage 3");
          };
          sock.onmessage = function(event) {
            alert(event.data);
            console.log(event); //show received from server data in console of browser
            $("#SocketMsg").append(event.data + "<br />"); //add incoming message from server to the log screen previous string + new string(message)
            var prefix = event.data.substring(0, 3);
            var bcMessage = event.data.substring(3, event.data.LENGTH);
            if (prefix == 'SCQ') {
              display_queue(bcMessage)
            }
          };
        }

        function infras() {
          var ww = window.innerWidth;
          var wh = window.innerHeight;
          play_mode = $("#play_mode").val();

          if (play_mode === '1') {
            $(".btn-play").show();
          } else {
            alert('else mode');
          }
        }

        function reset_game() {
          var db_time = $("#T").attr("db_time");
          $("#T").val(db_time).attr("full_time", db_time);
          $("#TIME-COUNTDOWN").text(db_time).css("background-color", "#ddd");
          clearInterval(interval);
          $("#REWARD-DISPLAY").text('');
          switch_camera(1);
        }

        function btn_play() {
          $(".btn-play").hide();
          $(".btn-start").show();
          $("#cust_waiting").text('1');
          sock.send(id + "_play"); //send string to server
        }

        function btn_start() {
          $(".btn-start").hide();
          $(".btn-up").show();
          reset_game();
        }

        function btn_up_release() {
          $(".btn-up").hide();
          $(".btn-right").show();
          switch_camera(2);
        }

        function btn_right_release() {
          $(".btn-right").hide();
          send_command('TX5');
        }

        function send_press(cmd) {
          send_command(cmd);
        }

        function send_release(bname) {
          send_command("TX0");
          if (bname === 'btn_up') {
            btn_up_release();
          } else if (bname === 'btn_right') {
            btn_right_release();
          }
        }

        function send_command(command) {
          id = $("#id").val();
          TY = $("#TY").val();

          if (command === 'TX5') {
            $("#TIME-COUNTDOWN").text('-');
            $(".command-btn").removeAttr("cmd");
            $(".control").removeAttr("onmousedown");
            $(".control").removeAttr("onmouseup");

            var rwd_counter = 15;
            var rwd_interval = setInterval(function() {
              rwd_counter--;
              if (rwd_counter > 0) {
                $("#REWARD-DISPLAY").text('รอลุ้น... ' + rwd_counter);
              } else {
                $("#REWARD-DISPLAY").text('');
                clearInterval(rwd_interval);
                $(".btn-start").show();
                $('#T').val('30').attr("full_time", '30');
                count_time('retain_play');
              }
            }, 1000);
            $(".btn-right").hide();
          }

          if (command !== "") {
            count_time(0);
            sock.send("SCM" + id + "_" + command + "TY" + TY); //send string to server
          } else {
            console.log("empty string not sent");
            alert('no command');
          }
          $("#command_note").val(id + "_" + command + "TY" + TY);
        }

        function check_reward() {
          var cmd_send = "SCM" + id + "_TX7TY888888";
          sock.send(cmd_send); //send string to server
          sock.onmessage = function(msgevent) {
            console.log('in :', msgevent.data);
            var reward_respond = msgevent.data;
            var reward_result = reward_respond.split(":");
            reward_result = reward_result[2];
            if (reward_result === '1') {
              $("#REWARD-DISPLAY").text('ยินดีด้วย!!!!!!');
              alert("ยินดีด้วยค่ะ คุณได้รับของรางวัล !!!!!\n กรุณาเข้าที่หน้ารายการรางวัล เพื่อเลือกวิธีการจัดส่ง \nหรือขายคืนค่ะ");
            } else {
              $("#REWARD-DISPLAY").text('เสียใจด้วยค่ะ :(');
              alert("เสียใจด้วยค่ะ :(\nลองใหม่นะคะ...");
            }
          };
        }

        function switch_camera(cam) {
          if (cam === 0) {
            if ($("#cam1").is(":visible")) {
              $("#cam1").slideUp(300);
              $("#cam2").slideDown(300);
            } else {
              $("#cam1").slideDown(300);
              $("#cam2").slideUp(300);
            }
          } else if (cam === 1) {
            $("#cam1").slideDown(300);
            $("#cam2").slideUp(300);
            $("#cam3").slideUp(300);
          } else if (cam === 2) {
            $("#cam1").slideUp(300);
            $("#cam2").slideDown(300);
            $("#cam3").slideUp(300);
          } else if (cam === 3) {
            $("#cam1").slideUp(300);
            $("#cam2").slideUp(300);
            $("#cam3").slideDown(300);
          }
        }

        function count_time(mode) {
          var counter = parseInt($('#T').val());
          var full_time = parseInt($('#T').attr("full_time"));
          if (full_time === counter) {
            $('#T').val('0');
            interval = setInterval(function() {
              counter--;
              if ($("#TIME-COUNTDOWN").text() === '-') {
                counter = 0;
                clearInterval(interval);
              } else if (counter <= 0 && mode === 0) {
                clearInterval(interval);
                send_command('TX5');
              } else if (counter <= 0 && mode === 'retain_play') {
                clearInterval(interval);
                $(".btn-play").show();
                $(".btn-start").hide();
                $("#cust_waiting").text('0');
              }
              if (counter < 10) {
                var counter_text = '0' + counter;
                $("#TIME-COUNTDOWN").text(counter_text).css({
                  backgroundColor: 'red',
                  color: '#fff'
                });
              } else {
                var counter_text = '' + counter;
                $("#TIME-COUNTDOWN").text(counter_text).css({
                  backgroundColor: 'yellow',
                  color: '#000'
                });
              }
            }, 1000);
          }
        }

        function checking_queue() {
          alert("checking_queue in");
          //  alert('checking');
          $("#spare").load("../opr_playing.php?opr=checking_queue&MID=" + id, function(return_data) {
            $("#MAIN-FOOTER-RIGHT").text(return_data);
            var check_return = return_data.split("Return_QList:")[1];
            if (check_return) {
              sock.send("SCQ" + id + check_return);
              display_queue(check_return);
            } else {
              $("#cust_waiting").text('0');
            }
          });
          alert("checking_queue out");
        }

        function display_queue(queue_data) {
          alert("Display Queue in");
          queue_list = queue_data.substr(3, queue_data.LENGTH);
          var return_split = queue_data.split("_#38#_");
          var countQ = 0;
          for (var i = 0; i <= return_split.length; i++) {
            if (return_split[i]) {
              countQ++;
              var waiting_time = return_split[i].split("_#19#_")[0];
              var waiting_time_display = waiting_time.substring(0, 2) + ":" + waiting_time.substring(2, 4) + ":" + waiting_time.substring(4, 6);
              var list_text = "<div class='cwl-line'>";
              list_text += "<div class='cwl-number'>" + countQ + ".</div>";
              list_text += "<div class='cwl-name'>" + return_split[i].split("_#19#_")[2] + "</div>";
              list_text += "<div class='cwl-time'>" + waiting_time_display + "</div>";
              list_text += "</div>";
              $("#cust_waiting_list").append(list_text);
            }
          }
          $("#cust_waiting").text(countQ);
          alert("Display Queue out");
        }

        function adding_queue() {
          alert("adding_queue");
          $("#spare").load("../opr_playing.php?opr=adding_queue&MID=" + id + "&memberID=" + memberID, function(return_data) {
            alert("adding_queue" + return_data);
            /*
            $("#MAIN-FOOTER-RIGHT").text(return_data);
            var check_return=return_data.split("Return_Error:")[1];
            if(check_return){
            } else {
            }
            */
          });
        }
<script type="text/javascript">
    var screenW = window.innerWidth;
    var screenH = window.innerHeight;
    var br = localStorage.br;
    var permission = localStorage.permission;
    var usrID = localStorage.usrID;
    var Set_System_View = localStorage.Set_System_View;
    $(document).ready(function () {
        var check_session = localStorage.usrID;
        if (!check_session) {
            logout();
        }
        manage_infras();
        get_permission();
        trying_weird();
    });
    function manage_infras() {
        $("#HEADER-USERNAME").text(localStorage.usr + '@' + localStorage.br);
        $("#HEADER-SECTION").css({
            maxWidth: screenW - 200,
            maxHeight: screenH - 200
        });
    }
    function change_header() {
        if (br === '20') {

//alert('กรุณาชำระค่าบริการรายปีเพื่อการใช้งานที่ต่อเนื่อง \n ภายในวันนี้ก่อนเที่ยง\nขออภัยหากชำระแล้ว\n@ambientpos');
        }
        $('#HEADER-SECTION').css("top", "-500");
        var currentPage = $("title").text();
        $.post("../header/test_connection.php").done(function (data) {
            if (data === 'y') {
                var d = new Date();
                var dateU = Math.round(d.getTime() / 1000);
                if (!localStorage.login_time || dateU - localStorage.login_time > 57600) {
                    alert("You've log in the system for more than 16Hours now, Please login again!");
                    logout();
                }
                if (currentPage === 'POS') {
                    sync_server();
                }
                $('#HEADER-SECTION').popup('open').animate({top: 75}, 500);
            }
        }).fail(function (xhr, status, error) {
            $('#HEADER-SECTION-ERROR').popup('open');
        });
    }
    function change_module(target) {
        $(".HEADER-NODE").hide();
        $(".HEADER-LOAD").show();
        window.location.replace("../" + target + "/");
    }
    function logout() {
        localStorage.removeItem("br");
        localStorage.removeItem("usr");
        localStorage.removeItem("usrID");
        localStorage.removeItem("ServiceNo_Last");
        localStorage.removeItem("System_View");
        window.location.href = "../?lgt=1";
    }
    function get_permission() {
        //########### 0POS,1Kitchen,2Stock,3Status,4Menu,5Raw_Material,6Promotion,7Report,8Member,9Void,10Cashdrawer
        var ps = permission.split("");
        for (var p = 0; p <= 9; p++) {
            if (ps[0] === '1') {
                if (Set_System_View === '0') {
                    $(".PPOS").show();
                } else if (Set_System_View === '1') {
                    $(".PPOS_ONLINE").show();
                }
            }
            if (ps[1] === '1') {
                $(".PKIT").show();
            }
            if (ps[2] === '1') {
                $(".PSTO").show();
            }
            if (ps[3] === '1') {
                $(".PSTA").show();
            }
            if (ps[4] === '1') {
                if (Set_System_View === '0') {
                    $(".PMEN").show();
                } else if (Set_System_View === '1') {
                    $(".PMEN_ONLINE").show();
                }
            }
            if (ps[5] === '1') {
                $(".PRAW").show();
            }
            if (ps[6] === '1') {
                $(".PPRO").show();
            }
            if (ps[7] === '1') {
                $(".PREP").show();
            }
            if (ps[8] === '1') {
                $(".PMEM").show();
            }
            if (ps[10] === '1') {
                $(".PCAS").show();
            }
        }
        $(".PCHE,.PUSE,.PSET,.PLOG").show();
        if ((br === '1' || br === '8' || br === '19' || br === '60') && usr === 'admin') {

            $(".PCHEA").show();
        }
    }

    function trying_weird() {
        var ps = permission.split("");
        var page_title = $("title").text();
        var weird = '';
        if (page_title === 'POS' && ps[0] === '0') {
            weird = 'try%20pos';
        } else if (page_title === 'Product Management' && ps[4] === '0') {
            weird = 'try%20product';
        } else if (page_title === 'Stock' && ps[2] === '0') {
            weird = 'try%20stock';
        } else if (page_title === 'Raw Material Management' && ps[5] === '0') {
            weird = 'try%raw-mat';
        } else if (page_title === 'Report' && ps[7] === '0') {
            weird = 'try%20report';
        }
        if (weird !== '') {
            $("#spare").text('loading').load("../setting/log.php?br=" + br + "&usrID=" + usrID + "&msg=" + weird, function () {
                logout();
                alert('ดูเหมือนว่าคุณกำลังพยายาม\nทำอะไรที่ Account ของคุณไม่มีสิทธิ?');
            });
        }
    }
</script>

<div id="HEADER-MAIN-BUTTON" style="position:fixed;z-index:150;top:0;left:0;" data-rel="popup" onclick="change_header()">
    <a style="width:55px;height:55px;margin:0;"  class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-icon-bars ui-btn-icon-notext ui-btn-b"></a>
</div>

<div data-role="popup" id="HEADER-SECTION" data-transition="fade" data-overlay-theme="b">
    <div id="HEADER-USERNAME"></div>
    <div class="HEADER-NODE PPOS"><img onclick="change_module('status');" src="../../img/header/header_pos.svg"><br/>Status</div>
    <div class="HEADER-NODE PPOS_ONLINE"><img onclick="change_module('pos_online');" src="../../img/header/header_pos_online.svg"><br/>POS</div>
    <div class="HEADER-NODE PMEM"><img onclick="change_module('member');"  src="../../img/header/header_member.png"><br/>Member</div>
    <div class="HEADER-NODE PKIT"><img onclick="change_module('kitchen');"  src="../../img/header/header_kitchen.png"><br/>Kitchen</div>
    <div class="HEADER-NODE PCAS"><img onclick="change_module('cashdrawer');"  src="../../img/header/header_cash_drawer.png"><br/>Cash Drawer</div>
    <div class="HEADER-NODE PCHE"><img onclick="change_module('checkin');"  src="../../img/header/header_checkin.png"><br/>Check-In</div>
    <div class="HEADER-NODE PCHEA"><img onclick="change_module('checkin_59');"  src="../../img/header/header_checkin.png"><br/>Check-In (Beta)</div>
    <div class="HEADER-NODE PREP"><img onclick="change_module('report');" src="../../img/header/header_report.png"><br/>Report</div>
    <div class="HEADER-NODE PSTA"><img onclick="change_module('status')"  src="../../img/header/header_status.png"><br/>Status</div>
    <div class="HEADER-NODE PMEN"><img onclick="change_module('machine');"  src="../../img/header/header_product.png"><br/>Machine</div>
    <div class="HEADER-NODE PMEN"><img onclick="change_module('online_machine');"  src="../../img/header/header_product.png"><br/>Online-Machine</div>
    <div class="HEADER-NODE PRAW"><img onclick="change_module('raw_material')"  src="../../img/header/header_raw_material.png"><br/>Raw-Material</div>
    <div class="HEADER-NODE PSTO"><img onclick="change_module('importexport')"  src="../../img/header/header_stock.png"><br/>Stock</div>
    <div class="HEADER-NODE PPRO"><img onclick="change_module('promotion')"  src="../../img/header/header_promotion.png"><br/>Promotion</div>
    <div class="HEADER-NODE PVOU" style="display:none;"><img onclick="change_module('voucher')"  src="../../img/header/header_promotion.png"><br/>Voucher</div>
    <div class="HEADER-NODE PUSE"><img onclick="change_module('manual')"  src="../../img/header/header_user_manual.png"><br/>User Manual</div>
    <div class="HEADER-NODE PSET"><img onclick="change_module('setting')"  src="../../img/header/header_settings.png"><br/>Settings</div>
    <div class="HEADER-NODE PLOG"><img onclick="logout()" src="../../img/header/header_logout.png"><br/>Logout</div>
    <div class="HEADER-LOAD" style="padding:100px;display:none;">
        <div style="border-radius:10px;"><img src="../../img/bar180.gif"></div>
        <div style="color:#fff;">Changing Page...</div>
    </div>
    <div style="clear:both"><img src="../../img/logo_white.png"></div>
</div>

<div data-role="popup" id="HEADER-SECTION-ERROR" data-transition="fade" data-overlay-theme="b" class="ui-content">
    <div style="font:bold 20px sans-serif;">Connection to Server Failed!</div>
    <div>but you can continue your selling as normally</div>
    <div>Please try again in 5 minutes... </div>
    <hr/>
    <div style="font:bold 20px sans-serif;">การเชื่อมต่อกับเซฟเวอร์ไม่สำเร็จ</div>
    <div>แต่คุณยังสามารถดำเนินการขายต่อไปได้อย่างปรกติ</div>
    <div>กรุณาลองใหม่อีกครั้งใน 5 นาที... </div>
</div>
<style type="text/css">
    #HEADER-SECTION{
        position:fixed;
        top:-500px;
        left:90px;
        margin:0 auto;
        border:solid 1px #000;
        background-color:rgba(0,0,0,0.8);
        padding:10px;
        overflow-y:auto;
        overflow-x:hidden;
        text-align:center;
    }
    #HEADER-USERNAME{
        font:bold 20px sans-serif;
        color:#fff;
        padding:20px;

    }
    .HEADER-NODE{
        display:none;
        cursor:pointer;
        float:left;
        width:180px;
        height:200px;
        text-align:center;
        color:#fff;
        margin:15px;

    }
    .HEADER-NODE img{
        height:150px;
    }
    bk {
        clear:both;
    }
</style>

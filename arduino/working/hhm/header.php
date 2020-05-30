
<script>

function header_infra(){
    ww = window.innerWidth;
    wh = window.innerHeight;
  if(ww>1250) {
    $("#MAIN-HEADER").css({
      marginLeft:(ww-1250)/2,
      width:1250
    });
  } else {
    $("#MAIN-HEADER").css({
      marginLeft:0,
      width:ww
    });
  }
}

function facebook_login_check(){
  $("#spare-login").load("opr_login.php?opr=select_id_fb&fbid="+fbid,function(rData){
    var memberDesc=rData.split('Return_MemberDesc:')[1];
    if(memberDesc && memberDesc!==undefined){
      do_login(memberDesc);

      login_status();
    } else {
      do_fb_register();
    }
  });
}

function do_fb_register(){
    $("#spare-login").load("opr_login.php?opr=fb_register&fb_msg_header="+fb_msg_header+"&fb_msg="+fb_msg,function(rData){
      var rStatus=rData.split('rStatus:')[1];
      if(rStatus==='0'){
        facebook_login_check();
      } else {
        alert("Unknown Error! : Facebook Login");
      }
    });
}

function do_login(memberDesc){
  mdSplit=memberDesc.split("__19__");
  localStorage.memberID=mdSplit[0];
  localStorage.memberName=mdSplit[1];
  $("#MAIN-FOOTER-LEFT").text("Hi "+mdSplit[1]);
}

function login_status(){
  var memberID=localStorage.memberID;
  var memberName=localStorage.memberName;
  if(memberID && memberName) {
    $("#MAIN-HEADER-RIGHT").html("<div style='float:left'>"+memberName+" </div><div style='float:left;cursor:pointer;margin-left:10px;' onclick='logout()'> [Logout]</div>");
  } else {
    $("#MAIN-HEADER-RIGHT").html("<div onclick='login_window();' style='cursor:pointer;'>Login</div>");
  }
    $("#POPUP-LOGIN").popup("close");
}

function login_window(){
  $("#POPUP-LOGIN").popup("open").load("header_login.php");
}

function logout(){
  alert('You are logged Out!');
    localStorage.memberID='';
    localStorage.memberName='';

    localStorage.removeItem("memberID");
    localStorage.removeItem("memberName");

    login_status();
}

</script>
<div id="status" style="display:none;"></div>

<div id="MAIN-HEADER">
  <div id="MAIN-HEADER-LEFT">HHM Crane</div>
  <div id="MAIN-HEADER-RIGHT">&nbsp;</div>
</div>
<div id="MAIN-FOOTER">
<div id="MAIN-FOOTER-RIGHT">FR</div>
  <div id="MAIN-FOOTER-LEFT">FL</div>
</div>

<div class="ui-content" data-role="popup" id="POPUP-LOGIN" style="width:350px;height:350px;" data-overlay-theme="b" data-theme="b" ></div>
<div id="spare-login" style="display:none;">SPARE LOGIN</div>
<style type="text/css">

#MAIN-HEADER{
  position:fixed;
  top:0;
  width:100%;
  max-width:1250px;
  height:30px;
  border-bottom:solid 1px gray;
}
#MAIN-HEADER-LEFT{
  float:left;
}
#MAIN-HEADER-RIGHT{
  float:right;
}

#MAIN-FOOTER{
  z-index:100;
  background-color:#000088;
  font:normal 10px courier;
  position:fixed;
  bottom:0;
  width:100%;
  color:#fff;
  border-bottom:solid 1px gray;

}
#MAIN-FOOTER-LEFT{
  padding:2px 20px;
  float:right;
}
#MAIN-FOOTER-RIGHT{
  padding:2px 20px;
  float:right;
}
</style>

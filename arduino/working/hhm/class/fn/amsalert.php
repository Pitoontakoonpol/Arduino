<script type="text/javascript" src="../jquery-1.11.3.min.js"></script>
<script type="text/javascript">
  var myVar;
  function amsalert(text, color, bgcolor) {
    clearTimeout(myVar);
    $("#amsalert").animate({top: -30, opacity: 0}, 200);
    $("#amsalert").css("color", color);
    $("#amsalert").css("background-color", bgcolor);
    $("#amsalert").html(text);
    $("#amsalert").animate({
      top: 20, 
      opacity: 1
    }, 300, function () {
      myVar = setTimeout(function () {
        alerthide();
      }, 5000);
    });
  }
  function alerthide() {
    $("#amsalert").animate({top: -30, opacity: 0}, 300);
  }
</script>
<div id="amsalert" style="position:fixed;top:-30px;padding:8px 80px;right:20px;opacity: 0;z-index:500;border-radius:5px;"></div>

//USAGE
// 1. put this code at the top of the page 
// <script type="text/javascript" src=" path to file /absdate.js"></script>
// 
// 2. paste this div some where in the page 
// <div id='absdate'></div>
// 
// 3. create textbox similar to the sample 
// <input type="text" name="DateofBirth" id="DateofBirth" class="absdate" onclick="absdate(this,'../../class/fn/absdate/')"/>
//
// 4. use come class like sample for decloration 
// 
//      .absdate {
//          cursor: hand;
//          cursor: pointer;
//          background:url('../img/Calendar30-20.png') no-repeat right;
//          background-color:#fff;
//      }
//



function absdate(ths,path){
    var value=ths.value;
    var id=ths.id;
    
    var ScreenW=window.innerWidth;
        
    $('#absdate')
    .show()
    .css({
        position:'fixed',
        top:30,
        left:ScreenW,
        width:300,
        "background":"#333"
    })
    .animate({
        left:ScreenW-300
    },500);
    
    absloaddate(value,id,path);
}

function absloaddate(value,id,path){
    var datedetect=value.split('-');
    var year=datedetect[2];
    var month=datedetect[1];
    var date=datedetect[0];
    
    var currentTime = new Date()
    if (!year) year=currentTime.getFullYear();
    if (!month) month=currentTime.getMonth()+1;
    if (!date) date=currentTime.getDate();
    $('#absdate').load(path+"absdate.php?path="+path,function(){
        $('#abs-navigate').load(path+"absdate_navigate.php",function(){
            $('#absdate').val(date);
            $('#absyear').val(year);
            $("select#absmonth").val(month);
            $('#absid').val(id);
            $('#abspath').val(path);
            $('#absselectedyear').val(year);
            $('#absselectedmonth').val(month);
            $('#abs-result').load(path+"absdate_result.php?selectedyear="+year+"&selectedmonth="+month+"&year="+year+"&month="+month+"&date="+date);
        });
    });
}

function changeyear(direction){
    var selectedyear=$('#absselectedyear').val();
    var selectedmonth=$('#absselectedmonth').val();
    var year=$('#absyear').val();
    var month=$('select#absmonth').val();
    var date=$('#absdate').val();
    var path=$('#abspath').val();
    year=parseInt(year);
    if (direction=='prev'){
        var year=year-1;
    }
    else if (direction=='next'){
        var year=year+1;
    } else {
        var year=year;
    }
    
    if (year) {
        $('#abs-result').load(path+"absdate_result.php?selectedyear="+selectedyear+"&year="+year+"&selectedmonth="+selectedmonth+"&month="+month+"&date="+date,function(){
            $('#absyear').val(year);
        });
    }
}

function changemonth(direction){
    var selectedyear=$('#absselectedyear').val();
    var selectedmonth=$('#absselectedmonth').val();
    var year=$('#absyear').val();
    var month=$('select#absmonth').val();
    var date=$('#absdate').val();
    var path=$('#abspath').val();
    year=parseInt(year);
    month=parseInt(month);
    if (direction=='prev'){
        var month=month-1;
        if (month==0) {
            month=12;
            year=year-1;
        }
    }
    else if (direction=='next'){
        var month=month+1;
        if (month==13) {
            month=1;
            year=year+1;
        }
    } else {
        var month=month;
    }
    
    if (month) {
        $('#abs-result').load(path+"absdate_result.php?selectedyear="+selectedyear+"&year="+year+"&selectedmonth="+selectedmonth+"&month="+month+"&date="+date,function(){
            $('#absyear').val(year);
            $('select#absmonth').val(month);
        });
    }
}
function choosedate(year,month,date){
    var id=$('#absid').val();
    $('#'+id).val(date+'-'+month+'-'+year);
    $('#absdate').fadeOut(500);
    
}
function absdate_close(){
    var ScreenW=window.innerWidth;
        
    $('#absdate')
    .animate({
        left:ScreenW
    },500);
}
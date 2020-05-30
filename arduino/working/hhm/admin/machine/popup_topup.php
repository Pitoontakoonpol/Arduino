

<script type="text/javascript">
    var br = localStorage.br;
    $(document).ready(function () {

    });
    function del_topup(id) {
        $("#topup-" + id).slideUp(100);
        $("#current_topup").val($("#current_topup").val().replace('-' + id, ''));
    }
    function add_topup_check() {
        $("#topup-add-btn,#topup-list").hide();
        $("#topup-back-btn,#topup-group-list,#topup-product-list").show();
        $("#topup-group-list").load("operation.php?opr=topup_group_list&br=" + br);
        $("#topup-product-list").text('');
    }
    function back_topup() {
        $("#topup-add-btn,#topup-list").show();
        $("#topup-back-btn,#topup-group-list,#topup-product-list").hide();
    }

    function topup_change(select_val) {
        select_val = select_val.replace(/ /g, '%20');
        var current_topup = $("#current_topup").val().split('-');
        $("#topup-product-list").load("operation.php?opr=topup_product_list&br=" + br + "&group=" + select_val, function () {
            current_topup.forEach(function (topupid) {
                $("#preid-" + topupid).prop('checked', true);
            });
        });
    }
    function pre_add_topup(topupid, topup_name) {
        var current_topup = $("#current_topup").val();
        var re = new RegExp('-' + topupid, "g");
        var duplicate_topup = current_topup.replace(re, '');
        if ($("#preid-" + topupid).is(":checked") === true) {
            duplicate_topup += '-' + topupid;
            $("#current_topup").val(duplicate_topup);

            $("#topup-list").append("<div class='topup-list' id='topup-" + topupid + "'><div style='float:left;'><img src='../../img/anonymous.png' align='absmiddle'> " + topup_name + "</div><button style='float:right;'  onclick='del_topup(" + topupid + ")' class='ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon'></button></div>");
        } else {
            $("#topup-" + topupid).remove();
            $("#current_topup").val(duplicate_topup);
        }
    }
    function duplicate_topup(fromID, fromName) {
        var toID = $("#topup-save-button").attr("topupID");
        var cfm = confirm('คัดลอก topup ทั้งหมดให้เหมือนกับ' + fromName + "\n ยืนยัน?");
        if (cfm !== false) {
            $("#spare").load("operation.php?opr=duplicate_topup&br=" + br + "&fromID=" + fromID + "&productid=" + toID, function () {
                topup_product_form(toID);
            });
        }
//        $("#preid-" + fromID).prop('checked', false);
    }


</script>
</head>
<body>
    <div style="padding:5px;" id='topup-add-btn'><button class="ui-btn ui-icon-plus ui-btn-icon-left ui-corner-all" onclick='add_topup_check();'>Add Topup</button></div>
    <div style="padding:5px;display:none;" id='topup-back-btn'><button class="ui-btn ui-icon-back ui-btn-icon-left ui-corner-all ui-btn-b" onclick='back_topup();'>Back</button></div>
    <div id='topup-list'>
        <?php
        $br = $_GET['br'];
        $productid = $_GET['productid'];
        include("../php-config.conf");
        include("../php-db-config.conf");


        if ($productid AND $br) {
            $count = 1;
            $sql_Pre = "SELECT REPLACE(SUBSTR(CONCAT(CommentL1,CommentL2,CommentL3,CommentL4,CommentL5),2),'-',',') FROM menu WHERE BranchID='$br' AND  ID='$productid'";
            $result_Pre = $conn_db->query($sql_Pre);
            $list_Pre = $result_Pre->fetch_row();
            $Pre_Val = $list_Pre[0];
            if ($Pre_Val) {
                if (substr($Pre_Val, -1) == ',') {
                    $Pre_Val = substr($Pre_Val,0, -1);
              //      echo "OLD!";
                }
                $sqlMenu = "SELECT ID,NameEN FROM menu  WHERE ID IN ($Pre_Val) ORDER BY Menu_Code,NameEN";
               // echo "<textarea>$sqlMenu</textarea>";
                $resultMenu = $conn_db->query($sqlMenu);
                while ($dbMenu = $resultMenu->fetch_array()) {
                    foreach ($dbMenu as $key => $value) {
                        $$key = $value;
                    }
                    if (FILE_EXISTS("../../../pos/picture/$ID.jpg")) {
                        $img_name = "../../../pos/picture/$ID.jpg";
                    } else if (FILE_EXISTS("../../../pos/picture/$ID.png")) {
                        $img_name = "../../../pos/picture/$ID.png";
                    } else {
                        $img_name = "../../img/anonymous.png";
                    }
                    echo "<div class='topup-list' id='topup-$ID'>";
                    echo "<div style='float:left;'><img src='$img_name' align='absmiddle'> " . $NameEN . "</div>";
                    echo "<button style='float:right;'  onclick='del_topup($ID)' class='ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon'></button>";
                    echo "</div>";
                    $count++;
                }
            }
        }
        ?>
    </div>
    <div id='topup-group-list'></div>
    <div id='topup-product-list'></div>
    <input style="width:100%" type="hidden" id="current_topup" value="-<?= str_replace(',', '-', $Pre_Val) ?>">
    <style type="text/css">
        .topup-list{
            border-radius:3px;
            height:50px;
            clear:both;
            padding:5px;
            margin:5px;
            background-color:orange;
            color:white;
        }
        .topup-list img{
            height:50px;
        }
        .topup-select-list{
            background-color:#eee;
            padding:8px;
            margin:5px;
            border-radius:3px;
        }
    </style>
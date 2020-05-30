

<script type="text/javascript">
  var br = localStorage.br;
  $(document).ready(function () {

  });
  function del_stock(id) {
    $("#stock-" + id).slideUp(100,function(){$(this).remove();});
    $("#current_stock").val($("#current_stock").val().replace('-' + id, ''));
  }
  function add_stock_check() {
    $("#stock-add-btn,#stock-list").hide();
    $("#stock-back-btn,#stock-group-list,#stock-product-list").show();
    $("#stock-group-list").load("operation.php?opr=stock_group_list&br=" + br);
    $("#stock-product-list").text('');
  }
  function back_stock() {
    $("#stock-add-btn,#stock-list").show();
    $("#stock-back-btn,#stock-group-list,#stock-product-list").hide();
  }

  function stock_change(select_val) {
    select_val = select_val.replace(/ /g, '%20');
    var current_stock = $("#current_stock").val().split('-');
    $("#stock-product-list").load("operation.php?opr=stock_product_list&br=" + br + "&group=" + select_val, function () {
      current_stock.forEach(function (stockid) {
        $("#preid-" + stockid).prop('checked', true);
      });
    });
  }
  function pre_add_stock(stockid, stock_name,stock_unit) {
    var current_stock = $("#current_stock").val();
    var re = new RegExp('-' + stockid, "g");
    var duplicate_stock = current_stock.replace(re, '');
    if ($("#preid-" + stockid).is(":checked") === true) {
      duplicate_stock += '-' + stockid;
      $("#current_stock").val(duplicate_stock);

      $("#stock-list").append("<div class='stock-list' id='stock-" + stockid + "'><div style='float:left;'>" + stock_name + "<span class='unit-break'>" + stock_unit + "</span></div><button onclick='del_stock(" + stockid + ")' class='ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon'></button><input class='stockQty' id='Qty-" + stockid + "' value='0' type='number'></div>");
    } else {
      $("#stock-" + stockid).remove();
      $("#current_stock").val(duplicate_stock);
    }
  }


</script>
</head>
<body>
  <div style="padding:5px;" id='stock-add-btn'><button class="ui-btn ui-icon-plus ui-btn-icon-left ui-corner-all" onclick='add_stock_check();'>Add Raw Material</button></div>
  <div style="padding:5px 0;display:none;" id='stock-back-btn'><button class="ui-btn ui-icon-back ui-btn-icon-left ui-corner-all ui-btn-b" onclick='back_stock();'>Back</button></div>
  <div id='stock-list'>
    <?php
    $br = $_GET['br'];
    $productid = $_GET['productid'];
    include("../php-config.conf");
    include("../php-db-config.conf");


    if ($productid AND $br) {
      $count = 1;
      $sqlMenu = "SELECT Raw_MaterialID,Name,Unit,Quantity FROM menu_ingredient ";
      $sqlMenu .= "INNER JOIN raw_material ON  menu_ingredient.Raw_MaterialID=raw_material.ID ";
      $sqlMenu .= "WHERE MenuID ='$productid'  ";
    //  echo "<textarea>" . $sqlMenu . "</textarea>";
      $resultMenu = $conn_db->query($sqlMenu);
      while ($dbMenu = $resultMenu->fetch_array()) {
        foreach ($dbMenu as $key => $value) {
          $$key = $value;
        }
        echo "<div class='stock-list' id='stock-$Raw_MaterialID'>";
        echo "<div style='float:left;'>$Name<span class='unit-break'>$Unit</span></div>";
        echo "<button onclick='del_stock($Raw_MaterialID)' class='ui-btn ui-btn-inline ui-icon-delete ui-btn-icon-notext ui-corner-all ui-shadow ui-nodisc-icon ui-alt-icon'></button>";
        echo "<input class='stockQty' id='Qty-$Raw_MaterialID' value='$Quantity' type='number'>";
        echo "</div>";
        $count++;
        $Total_Raw.="-".$Raw_MaterialID;
      }
    }
    ?>
  </div>
  <div id='stock-group-list'></div>
  <div id='stock-product-list'></div>
  <input style="width:100%" type="hidden" id="current_stock" value="<?=$Total_Raw?>">
  <style type="text/css">
    .stock-list{
      font:normal 15px sans-serif;
      border-radius:3px;
      height:50px;
      clear:both;
      padding:3px;
      margin:5px 0;
      background-color:#eee;
    }
    .stock-list div{
width:300px;
    }
    .stock-list input{
      float:right;
      text-align:right;
      width:60px;
      margin:12px 3px 0 0;
    }
    .stock-list button{
      float:right;
      margin-right:-3px;
    }
    .stock-select-list{
      background-color:#eee;
      padding:8px;
      margin:5px;
      border-radius:3px;
    }
    .unit-break{
      font:normal 12px sans-serif;
      color:#fff;
      background-color:#666;
      padding:2px 4px;
      border-radius:2px;
      margin-left:5px;
    }
  </style>
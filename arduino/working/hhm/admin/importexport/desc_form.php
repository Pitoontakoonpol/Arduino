<?php
$br = $_GET['br'];
$id = $_GET['id'];
$form_method = $_GET['form_method'];
include("../php-config.conf");
include("../php-db-config.conf");
?>
  <div id="DESC-DESC" style="overflow-y:auto">
<table class="fsub-table" style="" cellpadding="0" cellspacing="1">
  <tr>
    <th><div class="fnode fnode1 fhead"></div></th>
    <th><div class="fnode fnode2 fhead">Code</div></th>
    <th><div class="fnode fnode3 fhead" style="text-align:center;">Name</div></th>
    <th><div class="fnode fnode4 fhead">Unit</div></th>
    <th><div class="fnode fnode5 fhead" style="text-align:center;">Qty.</div></th>
    <th><div class="fnode fnode6 fhead" style="text-align:center;">Unit Price</div></th>
    <th><div class="fnode fnode7 fhead" style="text-align:center;">Total Price</div></th>
  </tr>
    <tr>
      <td><div class="fnode1 child child1"></div></td>
      <td><div class="fnode2 child child2"></div></td>
      <td><div class="fnode3 child child3"></div></td>
      <td><div class="fnode4 child child4"></div></td>
      <td><div class="fnode5 child child5"></div></td>
      <td><div class="fnode6 child child6"></div></td>
      <td><div class="fnode7 child child7"></div></td>
    </tr>
  <?php
  if ($id) {
    $count = 1;
    $sql = "SELECT stock_detail.ProductID AS MenuID,Name,Raw_Material_Code,stock_detail.Unit,Buying_Quantity,stock_detail.Price,Unit_Convert FROM stock_detail ";
    $sql.="INNER JOIN raw_material ON stock_detail.ProductID=raw_material.ID ";
    $sql.="WHERE ServiceID='$id'";
    // echo "<textarea>$sql</textarea>";
    $result = $conn_db->query($sql);
    while ($db = $result->fetch_array()) {
      foreach ($db AS $key => $value) {
        $$key = $value;
      }
      ?>
      <script type="text/javascript">

        $(".child1").append("<div class='in-node collect-data' id='c1-<?= $MenuID ?>' pid='<?= $MenuID ?>' pqty='<?= $Buying_Quantity ?>' pprice='<?= $Price ?>' punit='<?= $Buying_Unit ?>' punitconv='<?= $Unit_Convert ?>'><?= $count ?></div>");
        $(".child2").append("<div class='in-node' id='c2-<?= $MenuID ?>'><?= $Raw_Material_Code ?></div>"); //Code
        $(".child3").append("<div class='in-node' id='c3-<?= $MenuID ?>'><?= $Name ?></div>"); //Name
        $(".child4").append("<div class='in-node' id='c4-<?= $MenuID ?>'><?= $Unit ?></div>"); //Unit
        $(".child6").append("<div class='in-node' id='c6-<?= $MenuID ?>'><?= $Price ?></div>"); //Unit Price
        $(".child7").append("<div class='in-node' id='c7-<?= $MenuID ?>'><?= $Price * $Buying_Quantity ?></div>"); //Total Price


      </script>
      <?php
      if ($form_method == 1) {
        ?>
        <script type="text/javascript">
          $(".child5").append("<div class='in-node' id='c5-cover-<?= $MenuID ?>'><input type='number' id='c5-<?= $MenuID ?>' value='<?= $Buying_Quantity ?>' style='width:100%' onchange='qty_change(this)'></div>"); //Quantity
        </script>
        <?php
      } else {
        ?>
        <script type="text/javascript">
          $(".child5").append("<div class='in-node' id='c5-<?= $MenuID ?>'><?= $Buying_Quantity ?></div>"); //Quantity
        </script>
        <?php
      }
      $count++;
    }
  }
  ?>
</table>
  </div>

<?php if ($form_method == 1) { ?>
  <script type='text/javascript'>$(".edit").show();</script>
  <script type='text/javascript'>$(".view").hide();</script>
  <?php
} else {
  ?>
  <script type='text/javascript'>$(".edit").hide();</script>
  <script type='text/javascript'>$(".view").show();</script>
<?php }
?>

<style type="text/css">
  .fsub-table{
    background-color:#333;
  }
  .fsub-table th {
    padding-top:5px;
    font:bold 13px sans-serif;
    text-align:center;
    background-color:#ddd;
    border-bottom:solid 1px;
  }
  .fsub-table th div{
    font:bold 13px sans-serif;
  }

  .fsub-table td {
    font:normal 12px sans-serif;
    background-color:#eee;
  }
  .fnode{
    overflow:hidden;
    font:normal 12px sans-serif;
    padding:2px 2px;
    height:22px;
  }
  .fnode1 { 
    text-align:center;
    width:15px;
  }
  .fnode2 { 
    text-align:center;
    width:80px;
  }
  .fnode3 { 
    text-align:left;
    width:250px;
  }
  .fnode4 { 
    text-align:center;
    width:50px;
  }
  .fnode5 { 
    text-align:right;
    width:50px;
  }
  .fnode6 { 
    text-align:right;
    width:80px;
  }
  .fnode7 { 
    text-align:right;
    width:80px;
  }
  .fhead {
    padding:0;
  }
  .in-node {
    height:20px;
    border-bottom:solid 1px;
    padding:0 2px;
  }
  .in-node input{
    width:100%;
    padding:0px;
    text-align:right;
  }
</style>
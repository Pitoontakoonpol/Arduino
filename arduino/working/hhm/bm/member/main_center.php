<?php
include("../../admin/php-config.conf");
include("../../admin/php-db-config.conf");
$br = $_GET['br'];
?>
<table cellspacing="0" cellpadding="0" width="100%" class="main-table">
  <?php
  $page = $_GET['page'];
  $order_by = $_GET['order_by'];
  $page_size = $_GET['page_size'];
  $search = $_GET['search'];

  $order_by = str_replace("DESC", " DESC", $order_by);

  if ($search == 1) {

    $val = $_GET['val'];
    $val_explode = explode("_19_", $val);

    foreach ($val_explode AS $each_get) {
      $each_post_explode = explode("_38_", $each_get);
      $get_var = $each_post_explode[0];
      $get_val = $each_post_explode[1];
      $$get_var = $get_val;
    }

    $addition = "AND Name LIKE'%$search_Name%' ";
    $addition .= "AND Telephone LIKE'%$search_Telephone%' ";
    $addition .= "AND Member_Code LIKE'%$search_Member_Code%' ";
  }

  $start = $page * $page_size;
  $count = $start + 1;
  $sql = "SELECT * ";
  $sql .= "FROM member ";
  $sql .= "WHERE BranchID='$br' ";
  $sql .= "$addition ";
  $sql2 .= "ORDER BY $order_by ";
  $sql2 .= "LIMIT $start,$page_size";
  $result = $conn_db->query($sql);
  $Total_Records = $result->num_rows;
  $result = $conn_db->query($sql . $sql2);
  //echo $sql . $sql2;
  if ($addition) {
    $Total_Records = "Search result: " . number_format($Total_Records) . " records";
  } else {
    $Total_Records = number_format($Total_Records) . " members";
  }
  echo "<script type='text/javascript'>$('#Total_Records').text('$Total_Records');</script>";


  while ($db = $result->fetch_array()) {
    include("db.php");
    if ($search) {
      $Name = str_replace($search_Name, "<span style='background-color:yellow'>$search_Name</span>", $Name);
    }
    ?>
    <tr>
      <td valign="top"  class="front-row" id="front-row<?= $ID ?>"><?= $count ?></td>
      <td valign="top"  onclick="load_inside('view', '<?= $ID ?>')" class="td-picture cursor"></td>
      <td valign="top"  onclick="load_inside('view', '<?= $ID ?>')" class="td-name cursor" style="font-weight:bold;color:blue;">
        <div><?= $Name ?></div>
      </td>
      <td valign="top">
        <?php
        if ($Level == 1) {
          echo 'Platinum';
        } else if ($Level == '2') {
          echo 'Gold';
        } else if ($Level == '3') {
          echo 'Silver';
        } else {
          echo "Regular";
        }
        ?></td>
      <td valign="top">
        <div><?= $Mobile ?></div>
        <div><?= $Telephone ?></div>
        <div><?= $Email ?></div>
      </td>
      <td valign="top" style="text-align:center;">
  <?php echo date("d/m/Y", $Created) . "<br/>(" . floor((date("U") - $Created) / 86400) . "d)"; ?> 
      </td>
      <td valign="top"><?= number_format($Point_Remain)." pt." ?></td>
    </tr>
    <?php
    $count++;
  }
  ?>
</table>

<div id="page<?= $page + 1 ?>"></div>
<style type='text/css'>
  .td-picture{
    width:68px;
    padding:1px;
    background:url('../../img/anonymous.png');
    background-size:68px;
  }
  .td-picture img{
    width:68px
  }
  .td-name{
    width:400px;
    color:blue;
    font:bold 15px sans-serif;

  }
  .td-department,.td-section{
    width:150px
  }
  .td-productid{
    color:#666;
    font:normal 13px sans-serif;

  }
</style>

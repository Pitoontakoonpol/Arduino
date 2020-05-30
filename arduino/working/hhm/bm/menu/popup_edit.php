
<script type="text/javascript">
  $(document).ready(function () {

  });
  function check_new_select(ths) {

    var selectid = ths.id;
    var select_value = ths.value;
    if (select_value === 'new---19') {
      $("#" + selectid + "_new").show().focus();
    } else {
      $("#" + selectid + "_new").hide().val('');
    }
  }
</script>
</head>
<body>
  <div id="branch_mod_product" class="branch_edit">
    <?php
    $br = $_GET['br'];
    $br_split = explode(",", $br);
    $productCode = $_GET['productCode'];
    $productName = $_GET['productName'];
    $productName = str_replace("___", " ", $productName);
    include("../../admin/php-config.conf");
    include("../../admin/php-db-config.conf");

    if ($productName) {
      $sqlMenu = "SELECT * ";
      $sqlMenu .= "FROM menu ";
      $sqlMenu .= "WHERE Menu_Code='$productCode' AND NameEN='$productName' AND BranchID IN ($br) ORDER BY BranchID ASC";
      //  echo $sqlMenu;
      $resultMenu = $conn_db->query($sqlMenu);
      while ($dbMenu = $resultMenu->fetch_array()) {
        foreach ($dbMenu as $key => $value) {
          $$key = $value;
        }
        $TotalID .="," . $ID;
        $Total_Price .="," . $BranchID . '___' . $ID . '___' . $PriceTHB . '___' . $PriceTHB2;
        $Total_Point .="," . $BranchID . '___' . $ID . '___' . $Point_Cost . '___' . $Point_Redeem;
        $Total_Active .="," . $BranchID . '___' . $ID . '___' . $POS;
        $Total_Scan .="," . $BranchID . '___' . $ID . '___' . $Scan;
      }
    }
    ?>
    <div class="ui-field-contain">
      <div style="float:left;">
        <table cellpadding="5">
          <tr>
            <th>Name 1</th>
            <td><input type="text" id="NameEN" value="<?= $NameEN ?>" placeholder="Language 1" class="edit"></td>
          </tr>
          <tr>
            <th>Name 2</th>
            <td><input type="text" id="NameTH" value="<?= $NameTH ?>" placeholder="Language 2" class="edit"></td>
          </tr>
          <tr>
            <th>Name 3</th>
            <td><input type="text" id="NameLO" value="<?= $NameLO ?>" placeholder="Language 3" class="edit"></td>
          </tr>
          <tr>
            <th>Code</th>
            <td><input type="text" id="Menu_Code" value="<?= $Menu_Code ?>" class="edit"></td>
          </tr>
          <tr>
            <th>Barcode</th>
            <td><input type="number" id="Barcode" value="<?= $Barcode ?>" class="edit"></td>
          </tr>
          <tr>
            <th>Type</th>
            <td>
              <select id="Type" class="edit" onChange="check_new_select(this)">
                <?php
                $sql_Group = "SELECT Type_Name FROM menu_type WHERE BranchID IN($br) GROUP BY Type_Name ORDER BY Type_Name";
                $result_Group = $conn_db->query($sql_Group);
                while ($db_Group = $result_Group->fetch_array()) {
                  echo"<option>" . $db_Group['Type_Name'] . "</option>";
                }
                ?>
                <option value="new---19">+New Group</option>
              </select>
              <input type="text" id="Type_new" style="display:none;" class="edit" placeholder="Define New Group">
            </td>
          </tr>
          <?php
          if ($productName) {
            //##### Edit Form
            ?>
            <tr>
              <th valign="top">
                <div class="prevent-add">Picture</div></th>
              <td>
                <div class="prevent-add">
                  <iframe src="upload_image.php?ProductID=<?= $productid ?>&TotalID=<?= substr($TotalID, 1) ?>&br=<?=$br ?>" style="padding:0;border:none;height:100px;overflow:hidden;">
                  </iframe>
                </div>
              </td>
            </tr>
            <tr><td colspan="2" style="height:30px"></td></tr>
            <tr>
              <th style="padding-top:20px;" valign="top">Price</th>
              <td>
                <div id="price-range" style="float:left;padding-top:15px;width:200px"></div>
                <div style="float:left"><button class="ui-btn ui-mini ui-corner-all" onclick="branch_mod('price')">Modify</button></div>
              </td>
            </tr>
            <tr>
              <th style="padding-top:20px;" valign="top">Point</th>
              <td>
                <div id="point-range" style="float:left;padding-top:15px;width:200px"></div>
                <div style="float:left"><button class="ui-btn ui-mini ui-corner-all" onclick="branch_mod('point')">Modify</button></div>
              </td>
            </tr>
            <tr>
              <th>Active</th>
              <td>
                <div id="active-range" style="float:left;padding-top:15px;width:200px"></div>
                <div style="float:left"><button class="ui-btn ui-mini ui-corner-all" onclick="branch_mod('active')">Modify</button></div>
              </td>
            </tr>
            <?php
          } else {
            //##### Add Form
            ?>
            <tr>
              <th style="padding-top:20px;" valign="top">Price</th>
              <td>
                <label style="font-size:12px;color:#888;margin:0">Regular</label>
                <input type="number" id="PriceTHB" value="<?= $PriceTHB ?>" placeholder="Regular Price" class="edit">
                <label style="font-size:12px;color:#888;margin:0">Silver Member</label>
                <input type="number" id="Price_Silver" value="<?= $Price_Silver ?>" placeholder="Silver Member Price" class="edit">
                <label style="font-size:12px;color:#888;margin:0">Gold Member</label>
                <input type="number" id="Price_Gold" value="<?= $Price_Gold ?>" placeholder="Gold Member Price" class="edit">
                <label style="font-size:12px;color:#888;margin:0">Platinum Member</label>
                <input type="number" id="Price_Platinum" value="<?= $Price_Platinum ?>" placeholder="Platinum Member Price" class="edit">
                <label style="padding-top:20px;font-size:12px;color:#888;margin:0">Delivery</label>
                <input type="number" id="Price_Delivery" value="<?= $Price_Delivery ?>" placeholder="Price on Delivery" class="edit">
                <label style="padding-top:20px;font-size:12px;color:#888;margin:0">Cost</label>
                <input type="number" id="PriceTHB2" value="<?= $PriceTHB2 ?>" placeholder="Cost Price" class="edit">
              </td>
            </tr>
            <tr>
              <th style="padding-top:20px;" valign="top">Point</th>
              <td>
                <label style="font-size:12px;color:#888;margin:0">Purchase</label>
                <input type="number" id="Point_Cost" value="<?= $Point_Cost ?>" placeholder="Get on Purchase" title='เมื่อซื้อรายการนี้ สมาชิกได้ Point สะสมเท่าไหร่' class="edit">
                <label style="font-size:12px;color:#888;margin:0">Redeem</label>
                <input type="number" id="Point_Redeem" value="<?= $Point_Redeem ?>" placeholder="Used for Purchase" title='สมาชิกต้องใช้กี่ Point แลกซื้อรายการนี้' class="edit">
              </td>
            </tr>
            <tr>
              <th>Active</th>
              <td>Off</td>
            </tr>
            <?php
          }
          ?>
        </table>
      </div>
    </div>
  </div>
  <?php
  if ($productName) {
    ?>
    <div id="branch_mod_price" class="branch_edit" style="display:none;">
      <h2>Branch Price</h2>
      <table class="branch-table">
        <tr>
          <th>Branch</th>
          <th>Price</th>
          <th>Cost</th>
        </tr>
        <?php
        $Total_Price = explode(",", substr($Total_Price, 1));
        foreach ($Total_Price AS &$split) {

          $expld = explode("___", $split);
          echo "<tr>";
          echo "<td class='branch-list'>";
          echo $expld[0];
          echo "</td>";
          echo "<td>";
          ?>
          <input type="number" class="edit-price" id="Price_<?= $expld[1] ?>" value="<?= $expld[2] ?>">
          <?php
          echo "</td>";
          echo "<td>";
          ?>
          <input type="number" class="edit-price" id="Cost_<?= $expld[1] ?>" value="<?= $expld[3] ?>">
          <?php
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
    <div id="branch_mod_point" class="branch_edit" style="display:none;">
      <h2>Branch Point</h2>
      <table class="branch-table">
        <tr>
          <th>Branch</th>
          <th>Get</th>
          <th>Redeem</th>
        </tr>
        <?php
        $Total_Point = explode(",", substr($Total_Point, 1));
        foreach ($Total_Point AS &$split) {

          $expld = explode("___", $split);
          echo "<tr>";
          echo "<td class='branch-list'>";
          echo $expld[0];
          echo "</td>";
          echo "<td>";
          ?>
          <input type="number" class="edit-point" id="Get_<?= $expld[1] ?>" value="<?= $expld[2] ?>">
          <?php
          echo "</td>";
          echo "<td>";
          ?>
          <input type="number" class="edit-point" id="Redeem_<?= $expld[1] ?>" value="<?= $expld[3] ?>">
          <?php
          echo "</td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
    <div id="branch_mod_active" class="branch_edit" style="display:none;">
      <h2>Branch Active</h2>
      <table class="branch-table">
        <tr>
          <th>Branch</th>
          <th>Display</th>
          <th>Code/Scan</th>
        </tr>
        <?php
        $Total_Active_expld = explode(",", substr($Total_Active, 1));
        $Total_Scan_expld = explode(",", substr($Total_Scan, 1));
        $cnt = 0;
        for ($A = 0; $A <= COUNT($Total_Active_expld) - 1; $A++) {

          $expld = explode("___", $Total_Active_expld[$A]);
          $expld2 = explode("___", $Total_Scan_expld[$A]);

          if ($expld[0]) {
            if ($expld[2] === '1') {
              $checking = "checked='checked'";
            }
            if ($expld2[2] === '1') {
              $checking2 = "checked='checked'";
            }
            echo "<tr>";
            echo "<td class='branch-list'>";
            echo $expld[0];
            echo "</td>";
            echo "<td>";
            ?>
            <input type="checkbox" class="edit-active" data-role="flipswitch" id="Active_<?= $expld[1] ?>" <?= $checking ?> >
            <?php
            echo "</td>";
            echo "<td>";
            ?>
            <input type="checkbox" class="edit-scan" data-role="flipswitch" id="Scan_<?= $expld2[1] ?>" <?= $checking2 ?> >
            <?php
            echo "</td>";
            echo "</tr>";
            unset($checking);
            unset($checking2);
          }
        }
        ?>
      </table>
    </div>
  <?php } ?>
  <input type="hidden" id="TotalID" value="<?= substr($TotalID, 1) ?>" class="edit">
  <input type="hidden" id="BranchID" value="<?= $br ?>" class="edit">
  <script type="text/javascript">
    $("#Type").val('<?= $Type ?>');
  </script>

  <style type="text/css">
    .branch-table th{
      font-size:14px;
      background-color:#666;
      color:#fff;
      text-align:center;
      padding:5px;
    }
    .branch-table td{
      font-size:14px;
      background-color:#ddd;
      text-align:center;
      padding:3px 10px;
    }
    .branch-table td input{
      text-align:right;
    }
  </style>
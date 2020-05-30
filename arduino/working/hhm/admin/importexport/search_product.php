<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$SESSION_POS_BRANCH = $_SESSION['SESSION_POS_BRANCH'];
include("../raw_material/fn_stock_remain.php");
?>
    <div style='background-color:#aaa;padding:5px;text-align:right'><button onclick='$("#search-result").slideUp(300);'>Close</button></div>
<div style='position:relative;padding:10px;font:normal 13px sans-serif;max-height:450px;width:660px;overflow:auto'>
    <?php
    $keyWord = $_GET['keyWord'];
    $serviceID = $_GET['serviceID'];

    $keyWord = str_replace(" ", '%', $keyWord);
    $count = 1;
    $sql = "SELECT ID,Name,Unit,Type,Raw_Material_Code,Stock FROM raw_material WHERE (Raw_Material_Code LIKE '%$keyWord%' OR  Name LIKE '%$keyWord%' OR Type LIKE '%$keyWord%') AND BranchID='$SESSION_POS_BRANCH'";
    $result = $conn_db->query($sql);
    $row = $result->num_rows;
    echo "<h3>Search Results ( $row )</h3>";
    while ($db = $result->fetch_array()) {

        $productID = $db[0];
        $Name = $db[1];
        $Unit = $db[2];
        $Type = $db[3];
        $Product_Code = $db[4];
        $Stock = $db[5];
        ?>
        <div style='clear:both;padding:4px 0;border-top:dashed 1px #aaa;' class="cursor" onclick="add_product('1', '<?= $productID ?>');">
            <div style='width:15px;float:left;border:solid 1px #fff;padding:3px;font-weight:bold;text-align:center;'>
                <?= $count ?><input type='hidden' id='search-line-<?= $count ?>' value='<?= $productID ?>'>
            </div>
            <div style="float:left;width:400px;text-align:left;padding:3px 0 0 3px;font:normal 14px sans-serif;">
                <?php
                if ($Product_Code) {
                    echo $Product_Code . " - ";
                }
                echo $Name;
                if ($Unit) {
                    echo " (" . $Unit . ")";
                }
                ?>
            </div>
            <div style="float:right;width:80px;">
                <?php
                echo " <img src='../../img/action/import-white-20.png'> " . number_format($Stock,4);
                ?>
            </div>
        </div>
        <?php
        $count++;
    }
    ?>
</div>
<div style='text-align:left;'>
    <input type='text' id='choose_product' style='background-color:transparent;color:#fff'>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('input#choose_product').keyup(function (event) {
            if (event.keyCode == 27 || event.keyCode == 109) {
                $("input#choose_product").val('');
                $("#search-result").slideUp(500);
                $("#search_product").focus();
            }
        });
        $('input#choose_product').keypress(function (event) {
            if (event.keyCode == 13) {
                var inputCode = $("#choose_product").val();
                var input = inputCode.split("*");
                var Q = input[0];
                var pGetID = input[1];
                if (!pGetID) {
                    Q = 1;
                    pGetID = input[0];
                }
                var pID = $("#search-line-" + pGetID).val();
                add_product(Q, pID);
            }
        });
    });
</script>



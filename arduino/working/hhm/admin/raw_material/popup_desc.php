<script type="text/javascript">
    $(document).ready(function () {

    });
</script>
</head>
<body>
    <?php
    $br = $_GET['br'];
    $opr = $_GET['opr'];
    $productid = $_GET['productid'];
    include("../php-config.conf");
    include("../php-db-config.conf");
    if ($opr == 'stockin' OR $opr == 'stockout') {
        if ($opr == 'stockin') {
            $Method = 1;
        } else if ($opr == 'stockout') {
            $Method = 2;
        }
        ?>
        <table cellpadding="3" cellspacing="0" border="1" style="font-size:12px;width:100%">
            <tr>
                <th style='text-align:center'>Date/Time</th>
                <th style='text-align:center'>Service #</th>
                <th style='text-align:center'>Quantity</th>
                <th style='text-align:center'>Price</th>
                <th style='text-align:center'>Amount</th>
            </tr>
            <?php
            $cnt = 1;
            $sqlMenu = "SELECT Finish_Time,ServiceNo,Quantity,Price ";
            $sqlMenu .= "FROM stock_detail ";
            $sqlMenu .= "INNER JOIN stock_service ON stock_detail.ServiceID=stock_service.ID ";
            $sqlMenu .= "WHERE BranchID='$br' AND  ProductID='$productid' AND Finish_Time>0 AND Method=$Method ORDER BY Finish_Time DESC ";
            $resultMenu = $conn_db->query($sqlMenu);
            while ($dbMenu = $resultMenu->fetch_array()) {
                foreach ($dbMenu as $key => $value) {
                    $$key = $value;
                }
                echo "<tr>";
                echo "<td style='text-align:center'>" . date("Y-M-d", $Finish_Time) . "<br/>" . date("H:i:s", $Finish_Time) . "</td>";
                echo "<td style='text-align:center'>#" . $ServiceNo . "</td>";
                echo "<td style='text-align:right'>" . number_format($Quantity, 2) . "</td>";
                echo "<td style='text-align:right'>" . number_format($Price, 2) . "</td>";
                echo "<td style='text-align:right'>" . number_format($Price * $Quantity, 2) . "</td>";
                echo "</tr>";
                $cnt++;
            }
            if ($cnt == 1) {
                echo "0 Record(s)";
            }
            echo "</table>";
        } else if ($opr == 'pos') {
            if (!$start) {
                $start = 0;
            }
            ?>

            <table cellpadding="3" cellspacing="0" border="1" style="font-size:12px;width:100%">
                <tr>
                    <th style='text-align:center'>Date/Time</th>
                    <th style='text-align:center'>Service #</th>
                    <th style='text-align:center'>Quantity</th>
                </tr>
                <?php
                $cnt = 1;
                $sqlMenu = "SELECT Date,ServiceNo,SUM(Quantity) AS sum_qty ";
                $sqlMenu .= "FROM product_stock ";
                $sqlMenu .= "WHERE BranchID='$br' AND  ProductID='$productid' GROUP BY 1 ORDER BY Date DESC LIMIT 500";
                $resultMenu = $conn_db->query($sqlMenu);
                while ($dbMenu = $resultMenu->fetch_array()) {
                    foreach ($dbMenu as $key => $value) {
                        $$key = $value;
                    }
                    echo "<tr>";
                    echo "<td style='text-align:center'>" . date("Y-M-d", $Date) . "<br/>" . date("H:i:s", $Date) . "</td>";
                    echo "<td style='text-align:center'>#" . str_pad($ServiceNo,5,'0',STR_PAD_LEFT) . "</td>";
                    echo "<td style='text-align:right'>" . number_format($sum_qty, 2) . "</td>";
                    echo "</tr>";
                    $cnt++;
                }
                if ($cnt == 1) {
                    echo "0 Record(s)";
                }
            } else if ($opr == 'operate') {
                ?>
                <div class="operate-menu"><button class="ui-btn ui-icon-plus ui-btn-icon-left ui-corner-all" style="background-color:green;color:#fff">Import Stock</button></div>
                <div class="operate-menu"><button class="ui-btn ui-icon-plus ui-btn-icon-left ui-corner-all" style="background-color:orange;color:#fff">Export Stock</button></div>
                <div class="operate-menu" id="refresh_stock_btn"><button class="ui-btn ui-icon-refresh ui-btn-icon-left ui-corner-all" style="background-color:#555;color:#fff" onclick="refresh_stock('<?= $productid ?>', 1)">Refresh Stock</button></div>

                <?php
            }
            ?>
            <style type="text/css">
                .operate-menu{
                    padding:10px;
                    text-align:center;
                    font:normal 14px sans-serif;
                }
            </style>
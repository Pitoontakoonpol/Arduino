<div style='clear:both;'>
    <div style='float:left;width:526px; background-color:#444;border:solid 3px #fff;padding:10px;'>
        <?php
        $sql = "SELECT Count(MenuID) AS data1, MenuID AS data2 FROM service_order  WHERE Cancel=0 AND Price>0 AND Time_Order>=$fr AND Time_Order<$to AND BranchID='$SESSION_POS_BRANCH' GROUP BY MenuID ORDER BY 1 DESC";
        echo $sql;
        $result = $conn_db->query($sql);
        while ($db = $result->fetch_array()) {
            $data1 = $db['data1'];
            $data2 = $db['data2'];
            echo $data1 . " - " . $data2 . "<br/>";
        }
        ?>
    </div>
</div>
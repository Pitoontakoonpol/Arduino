<?php
session_start();
include("../php-config.conf");
include("../php-db-config.conf");
$opr = $_GET['opr'];
$branchID = $_POST['branchID'];
?>
<html>
    <head>
        <title>Arrangement</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../../class/css.css" rel="stylesheet" type="text/css" />
        <link href="index.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="../../class/jquery.js"></script>
    </head>
    <body>
        <h2>GET MenuID From MenuName</h2>
        <?php
        if ($opr == 1) {
            $text_field1 = $_POST['text_field1'];
            $text_field1 = str_replace("'", "\'", $text_field1);
            $expld = explode(",", $text_field1);
            echo"<table border='1' cellspacing='0' cellpadding='5' class='fs14'>";
            $count = 1;
            for ($i = 0; $i <= COUNT($expld); $i++) {
                $menuName = $expld[$i];
                if ($menuName) {
                    $sql = "SELECT ID,NameEN FROM menu WHERE NameEN='$menuName' AND BranchID='$branchID' LIMIT 1";
                    $result = $conn_db->query($sql);
                    while ($db = $result->fetch_array()) {
                        $dbmenuID = $db[0];
                        $dbmenuName = $db[1];
                        echo"<tr>";
                        echo"<td>";
                        echo $count;
                        echo"</td>";
                        echo"<td>";
                        echo $dbmenuID;
                        echo"</td>";
                        echo"<td>";
                        echo $dbmenuName;
                        echo"</td>";
                        echo"</tr>";
                        $count++;
                    }
                }
            }
            echo "</table>";
        }
        ?>
        <form method="post" name="gield" style="padding:10px;" action="<?= $PHP_SELF ?>?opr=1">
            BranchID: <input type="text"name="branchID" value="<?= $branchID ?>"><br/><br/>
            <textarea name="text_field1" style="width:800px;height:300px;"><?= $text_field1 ?></textarea><br/><br/>
            <input type="submit" name="submit1" class="fs20">
        </form>
        <h2>GET Raw_Mat ID From Raw-Mat Name</h2>
        <?php
        if ($opr == 2) {
            echo $text_field2;
            $text_field2 = $_POST['text_field2'];
            $text_field2 = str_replace("'", "\'", $text_field2);
            $expld = explode(",", $text_field2);
            echo"<table border='1' cellspacing='0' cellpadding='5' class='fs14'>";
            $count = 1;
            for ($i = 0; $i <= COUNT($expld); $i++) {
                $rawName = $expld[$i];
                $expldRaw = explode("-----", $rawName);
                $rawName = $expldRaw[0];
                $rawUnit = $expldRaw[1];
                $sql = "SELECT ID,Name,Unit FROM raw_material WHERE Name='$rawName' AND Unit='$rawUnit' AND BranchID='$branchID' LIMIT 1";
                $result = $conn_db->query($sql);
                while ($db = $result->fetch_array()) {
                    $dbrawID = $db[0];
                    $dbrawName = $db[1];
                    $dbrawUnit = $db[2];
                }
                    echo"<tr>";
                    echo"<td>";
                    echo $count;
                    echo"</td>";
                    echo"<td>";
                    echo $dbrawID;
                    echo"</td>";
                    echo"<td>";
                    echo $dbrawName;
                    echo"</td>";
                    echo"<td>";
                    echo $dbrawUnit;
                    echo"</td>";
                    echo"</tr>";
                    $count++;
            }
            echo "</table>";
        }
        ?>
        <form method="post" name="gield2" style="padding:10px;" action="<?= $PHP_SELF ?>?opr=2">
            BranchID: <input type="text"name="branchID" value="<?= $branchID ?>"><br/><br/>
            <textarea name="text_field2" style="width:800px;height:300px;"><?= $text_field2 ?></textarea><br/><br/>
            <input type="submit" name="submit2" class="fs20">
        </form>
    </body>
</html>
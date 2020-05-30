<?php
$Page_Name = 'Import';
include("../php-db-config.conf");
?>
<html>
    <head>
        <title><?= $Page_Name ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <script type="text/javascript">
        </script>
    </head>
    <body>
        <div style="padding:100px 0 0 30px;max-width:350px;">


            <?php
            if ($_GET['do_upload'] == 1) {
                $pID = $_POST['pID'];
                $br = $_POST['br'];
                $Source = $_FILES["Picture"]["tmp_name"];
                $Picture_Name = $_FILES["Picture"]["name"];
                echo ">" . $Picture_Name;
                if ($Picture_Name) {
                    $Picture_Name = str_replace("M ", "", $Picture_Name);
                    $Picture_Name = str_replace(".xls", "", $Picture_Name);
                    $Play_Explode = explode("-", $Picture_Name);
                    $Play_Date = str_pad($Play_Explode[0], 2, '0', STR_PAD_LEFT);
                    $Play_Month = str_pad($Play_Explode[1], 2, '0', STR_PAD_LEFT);
                    $Dir = "uploads/";
                    //Check Exist Directory
                    if (!FILE_EXISTS($Dir)) {
                        exec("mkdir $Dir");
                    }
                    $filenameS = $Source;
                    $filenameD = $Dir . $pID . ".xls";
                    $filenameC = $Dir . $pID . ".csv";

                    if (move_uploaded_file($Source, $filenameD)) {
                        echo " has been uploaded.<br/>";
                        exec("ssconvert $filenameD $filenameC");
                        exec("rm $filenameD");
                        $uploadok = 1;
                    } else {
                        echo "Upload Error!";
                    }

                    if ($uploadok == 1) {
                        echo "UPLOAD OK!<br/>";
                        ?>
                        <table border="1" style="display:none;">
                            <?php
                            $row = 1;
                            $row_value = 1;
                            if (($handle = fopen($filenameC, "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    $num = count($data);
                                    // echo "<p> $num fields in line $row: <br /></p>\n";
                                    for ($c = 0; $c < $num; $c++) {
                                        //echo ">$row|$c|" . $data[$c] . "<br />\n";
                                        if ($data[0]) {
                                            break;
                                        } else if (!$data[2]) {
                                            break;
                                        } else if ($c == 2) {
                                            $Machine_Name = $data[2];
                                            $line_data .= $Machine_Name . "|||";
                                        } else if ($c == 3) {
                                            $Machine_Cat = $data[3];
                                            $line_data .= $Machine_Cat . "|||";
                                        } else if ($c == 4) {
                                            $Machine_Serial = $data[4];
                                            $line_data .= $Machine_Serial . "|||";
                                        } else if ($c == 5) {
                                            $Machine_Name2 = $data[5];
                                            $line_data .= $Machine_Name2 . "|||";
                                        } else if ($c == 6) {
                                            $line_data .= $data[6] . "|||";
                                        } else if ($c == 7) {
                                            $Line_Price = number_format(str_replace("$", "", $data[7]), 2);
                                            $line_data .= $Line_Price . "|||";
                                        } else if ($c == 36) {
                                            $Play_Time = $data[36];
                                            $line_data .= $Play_Time . "|||";
                                        }
                                    }
                                    if ($line_data) {
                                        echo "<tr>";
                                        $split_col = explode("|||", $line_data);
                                        echo "<td>$row_value</td>";
                                        for ($i = 0; $i <= COUNT($split_col); $i++) {
                                            echo "<td>$split_col[$i]</td>";
                                        }
                                        $Machine_Name = $split_col[0];
                                        $Machine_Cat = $split_col[1];
                                        $Machine_Serial = $split_col[2];
                                        $Price = $split_col[5];
                                        $Quantity = $split_col[6];
                                        $Unit_Price = $Price / $Quantity;
                                        $Time_Order_YMD = date("y") . $Play_Month . $Play_Date;
                                        $Time_Order = strtotime("20" . date("y") . "-" . $Play_Month . "-" . $Play_Date);

                                        $sql_all_machine .= "(";
                                        $sql_all_machine .= $pID . ",";
                                        $sql_all_machine .= "'$Machine_Name',";
                                        $sql_all_machine .= "'$Machine_Cat',";
                                        $sql_all_machine .= "'$Machine_Serial'";
                                        $sql_all_machine .= "),";

                                        $sql_all_type .= "(";
                                        $sql_all_type .= "'$Machine_Cat'";
                                        $sql_all_type .= "),";


                                        $sql_all_transaction .= "(";
                                        $sql_all_transaction .= $pID . ",";
                                        $sql_all_transaction .= "'$Time_Order_YMD',";
                                        $sql_all_transaction .= "'$Time_Order',";
                                        $sql_all_transaction .= "'$Machine_Serial',";
                                        $sql_all_transaction .= "'$Quantity',";
                                        $sql_all_transaction .= "'$Unit_Price',";
                                        $sql_all_transaction .= "'7'";
                                        $sql_all_transaction .= "),";
                                        $Data_Rows++;
                                    }
                                    echo "</tr>";
                                    unset($line_data);
                                    unset($Line_Price);
                                    $row_value++;
                                }
                                $row++;
                            }
                            fclose($handle);
                            if ($sql_all_machine) {
                                echo $Data_Rows . " Records Added!<br/>";
                                $sql_import_machine = "INSERT IGNORE INTO machine (BranchID,Name,Type,Serial) VALUES " . substr($sql_all_machine, 0, -1) . ";";
                                $result_import_machine = $conn_db->query($sql_import_machine);
                                //  echo $sql_import_machine . "<br/><br/>";

                                $sql_import_type = "INSERT IGNORE INTO machine_type (Type_Name) VALUES " . substr($sql_all_type, 0, -1) . ";";
                                $result_import_type = $conn_db->query($sql_import_type);
                                //echo $sql_import_type . "<br/><br/>";

                                $sql_import_transaction = "INSERT IGNORE INTO service_order (BranchID,Time_Order_YMD,Time_Order,MachineID,Quantity,Price,Payment_By) VALUES " . substr($sql_all_transaction, 0, -1) . ";";
                                $result_all_transaction = $conn_db->query($sql_import_transaction);
                                //  echo $sql_import_transaction . "<br/><br/>";
                            }
                        }
                        ?>
                    </table>

                    <?php
                }
                ?>

                <a href="./">Upload Another file</a>
                <?php
            } else {
                ?>
                <form action="<?= $PHP_SELF ?>?do_upload=1" id="uploadForm" method="post" enctype="multipart/form-data">  

                    <select name="pID" id="pID" >
                        <?php
                        $sql = "SELECT * FROM branch ORDER BY Branch_Code";
                        $result = $conn_db->query($sql);
                        while ($db = $result->fetch_array()) {
                            $Branch_Code = $db['Branch_Code'];
                            $Name = $db['Name'];
                            echo "<option value='" . $Branch_Code . "'>" . $Branch_Code . " - " . $Name . "</option>";
                        }
                        ?>
                    </select>
                    <input type="file" name="Picture" id="Picture" accept=".xls" onchange="$(this).submit()">
                    <input type="submit" value="Upload Image" name="submit" style='font-size:16px;'>
                </form>
            <?php } ?>

        </div>
    </body>
</html>

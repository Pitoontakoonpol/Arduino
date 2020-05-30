<?php
//##############POS INVOICE
    $cID = $_GET['cID'];
    $sql2 = "SELECT Name,Address1,Address2,Sub_District,District,Province,Postal_Code,Country  FROM customer WHERE ID='$cID'";
    $result2 = $conn_db->query($sql2);
    $list2 = $result2->fetch_row();
    if ($list2[6] == 0)
        $list2[6] = '';
    $Customer = $list2[0] . "<br/><span class='fs12'> $list2[1] $list2[2] $list2[3] $list2[4] $list2[5] $list2[6] $list2[7] </span>";
    ?>
    <div id='slip-paper'>
        <div  id='slip-logo' style="text-align:center;"><img style="width:200px;" src="../../img/logo.png"></div>
        <div class="customer_text" id="customer_text" style='padding:10px 0'><?= $Customer ?></div>
        <div>
            <?php
            $serviceID = $sID;
            include("../importexport/list_product.php");
            ?>
        </div>
        <div id="footer_bill" style="clear:both;border-top:solid 1px;">
            <div>
                <div class="total_product_text" style="float:right;font-weight:bold;padding:5px;width:80px;text-align:right"><?= number_format($total_product, 2) ?></div>
                <div style="float:right;">รวมรายการ </div>
            </div>
            <div style="clear:both;">
                <div class="total_unit_text" style="float:right;font-weight:bold;padding:5px;width:80px;text-align:right"><?= number_format($Total_Quantity, 2) ?></div>
                <div style="float:right;">รวมชิ้น </div>
            </div>
            <div style="clear:both;">
                <div class="total_price_text" style="border:solid 1px;float:right;font-weight:bold;padding:5px;width:80px;text-align:right"><?= number_format($Total_Price, 2) ?></div>
                <div style="float:right;font:normal 14px sans-serif;">รวมเงิน </div>
            </div>
        </div>
        <div style="clear:both;">
            <div class="remark_text" style="font:normal 12px sans-serif;padding:5px;width:300px;text-align:left">
                <?php
                if ($list[2]) {
                    echo "หมายเหตุ : $list[2] ";
                }
                ?></div>
        </div>
    </div>
    <style type='text/css'>


        #slip-paper {
            width:910px;
        }
        #footer_bill{
            font-size:14px;
        }
        @media print{
            #header-bar{
                display:none;
            }
            #total{
                display:none;
            }
            #customer_text{
                display:block;
                font:normal 12px sans-serif;
                border-bottom:solid 1px;
            }
            .w00,.w1,.w2,.w4,.w6{
                display:none;
            }
            .w0 div{
                height:15px;
                border:none;
                padding:0;
            }
            .w3,.w5,.w7 {
                font:normal 10px sans-serif;
                overflow:hidden;
            }
            .w0{
                border:none;
            }
            .w3 {
                width:150px;
                border:none;
            }
            .w5 {
                width:50px;
            }
            .w7 {
                width:50px
            }

            #footer_bill{
                font-size:10px;
            }
            #slip-paper {
                width:250px;
            }
        }
    </style>
    
    
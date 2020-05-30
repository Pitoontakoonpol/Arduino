<?php

session_start();
include("../php-config.conf");
include("../php-db-config.conf");
require('../../class/fpdf/fpdf.php');
require('code39.php');
define('FPDF_FONTPATH', '../../class/fpdf/font/');
$serviceID = $_POST['pdf_serviceID'];
$br = $_POST['pdf_br'];
echo $serviceID;

$pdf = new FPDF('P', 'mm', array(210, 297));
$pdf = new PDF_Code39();
$pdf->SetMargins(10, 10, 0);
$pdf->AddFont('tahoma', '', 'tahoma.php');
/*
  $pdf->Cell(80, 12, 'Order No.:' . $ID, 0, 1, "C");
  $pdf->SetFont('tahoma', '', 12);
  $pdf->Cell(40, 5, iconv('UTF-8', 'TIS-620', $Kitchen), 0, 0);
  $pdf->SetFont('tahoma', '', 15);
  $pdf->Cell(30, 5, iconv('UTF-8', 'TIS-620', 'โต๊ะ : ' . $table), 0, 1, 'R');

 */


if ($serviceID) {
    $count = 1;
    $sql = "SELECT Method,stock_service.ServiceNo,stock_service.Finish_Time,From_Location,To_Location,DocumentNo1,DocumentNo2,Cancel,stock_detail.ProductID AS MenuID,Name,Raw_Material_Code,stock_detail.Unit,Buying_Quantity,stock_detail.Price,Unit_Convert FROM stock_detail ";
    $sql.="INNER JOIN stock_service ON stock_detail.ServiceID=stock_service.ID ";
    $sql.="INNER JOIN raw_material ON stock_detail.ProductID=raw_material.ID ";
    $sql.="WHERE ServiceID='$serviceID'";
    echo "<textarea>$sql</textarea>";
    $result = $conn_db->query($sql);
    $rows = $result->num_rows;
    while ($db = $result->fetch_array()) {
        foreach ($db AS $key => $value) {
            $$key = $value;
        }
        if ($count % 30 == 1 OR $count == 1) {
            $pdf->AddPage();
            $pdf->SetFont('tahoma', '', 20);
            if ($Method == 1) {
                $Method_Text = "Import Document";
            } else if ($Method == 2) {
                $Method_Text = "Export Document";
            } else if ($Method == 4) {
                $Method_Text = "Purchase Order (PO)";
            } else if ($Method == 5) {
                $Method_Text = "Delivery Order";
            }
            $pdf->Cell(190, 10, $Method_Text, 0, 1, "C");

            $pdf->SetFont('tahoma', '', 10);
            $pdf->Cell(20, 6, "No.", 0, 0, "C");
            $pdf->Cell(60, 6, "#" . $ServiceNo, 0, 0, "L");
            $pdf->Cell(20, 6, "Date/Time.", 0, 0, "C");
            $pdf->Cell(60, 6, date("d/m/Y H:i:s", $Finish_Time), 0, 1, "L");
            $pdf->Cell(20, 6, "From", 0, 0, "C");
            $pdf->Cell(60, 6, iconv('UTF-8', 'TIS-620', $From_Location), 0, 0, "L");
            $pdf->Cell(20, 6, "To", 0, 0, "C");
            $pdf->Cell(60, 6, iconv('UTF-8', 'TIS-620', $To_Location), 0, 1, "L");
            $pdf->Cell(20, 6, "Remark", 0, 0, "C");
            $pdf->Cell(60, 6, iconv('UTF-8', 'TIS-620', $DocumentNo1), 0, 0, "L");
            $pdf->Cell(20, 6, "Reference", 0, 0, "C");
            $pdf->Cell(60, 6, iconv('UTF-8', 'TIS-620', $DocumentNo2), 0, 1, "L");

            $pdf->SetFont('tahoma', '', 10);
            $pdf->Cell(5, 7, "", 1, 0, "C");
            $pdf->Cell(30, 7, "Code", 1, 0, "C");
            $pdf->Cell(80, 7, "Name", 1, 0, "C");
            $pdf->Cell(20, 7, "Unit", 1, 0, "C");
            $pdf->Cell(15, 7, "Qty", 1, 0, "C");
            $pdf->Cell(20, 7, "Unit Price", 1, 0, "C");
            $pdf->Cell(20, 7, "Total Price", 1, 1, "C");
        }
        $pdf->Cell(5, 7, $count, 1, 0, "C");
        $pdf->Cell(30, 7, iconv('UTF-8', 'TIS-620', $Raw_Material_Code), 1, 0, "C");
        $pdf->Cell(80, 7, iconv('UTF-8', 'TIS-620', $Name), 1, 0, "L");
        $pdf->Cell(20, 7, iconv('UTF-8', 'TIS-620', $Unit), 1, 0, "C");
        $pdf->Cell(15, 7, number_format($Buying_Quantity), 1, 0, "R");
        $pdf->Cell(20, 7, number_format($Price, 2), 1, 0, "R");
        $pdf->Cell(20, 7, number_format($Buying_Quantity * $Price, 2), 1, 1, "R");
        if ($count % 30 == 1 OR $count == 1) {
            $page++;
            $page_print="Page: " . $page . " of " . ceil($rows/30);
            $pdf->Text(175, 290, $page_print);
            $pdf->Code39(10, 280, $ServiceNo, 1, 10);
            if($Cancel){
                
            $pdf->Image('../../img/voided.png',60,40);
            }
        }
        $count++;
    }
}
$pdf->Output($serviceID . '.pdf', "F");
header("Location:" . $serviceID . '.pdf');
?>
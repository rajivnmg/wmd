<?php 
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include('../../Model/DBModel/fpdf.php');
$d=date('d_m_Y');

class PDF extends FPDF
{

    function Header()
    {
        $this->SetFont('Helvetica','',25);
        $this->SetFontSize(40);
        //Move to the right
        $this->Cell(80);
        //Line break
        $this->Ln();
    }

    //Page footer
    function Footer()
    {
        
    }

    //Load data
    function LoadData($file)
    {
        //Read file lines
        $lines=file($file);
        $data=array();
        foreach($lines as $line)
            $data[]=explode(';',chop($line));
        return $data;
    }

    //Simple table
    function BasicTable($data)
    { 

        $this->SetFillColor(255,255,255);
        //$this->SetDrawColor(255, 0, 0);
        
        //Data
        $this->SetFont('Arial','',10);
        foreach ($data as $eachResult) 
        { //width
			
			
			$bname='';
					
			if (strlen($eachResult["BuyerName"]) > 20){
				$bname = substr($eachResult["BuyerName"], 0, 20) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			$pname='';
					
			if (strlen($eachResult["Principal_Supplier_Name"]) > 15){
				$pname = substr($eachResult["Principal_Supplier_Name"], 0, 15) . '...';
			}else{
				$pname = $eachResult["Principal_Supplier_Name"];
			}
			
            $this->Cell(1);
            $this->Cell(8,6,$eachResult["SN"],0);
            $this->Cell(23,6,$eachResult["oinvoice_No"],0);
            $this->Cell(23,6,$eachResult["oinv_date"],0);
            $this->Cell(40,6,$pname,0);
            $this->Cell(40,6,$bname,0);
            $this->Cell(18,6,$eachResult["Item_Code_Partno"],0);
            $this->Cell(15,6,$eachResult["issued_qty"],0, 0, 'R');
            $this->Cell(25,6,number_format((float)$eachResult["Salling"], 2, '.', ''),0, 0, 'R');
            $this->Cell(25,6,number_format((float)$eachResult["landing_price"], 2, '.', ''),0, 0, 'R');
            $this->Cell(25,6,number_format((float)$eachResult["Margin"], 2, '.', ''),0, 0, 'R');
            $this->Cell(25,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0, 0, 'R');
            $this->Cell(25,6,$eachResult["ms"],0);
            $this->Ln();
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Principal Name','Buyer Name','CodePart','Qty','Selling Price','Landing Price','Margin','Bill Amount','Market Seg');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/MarginReportModel.php';

//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'Margin Report');
$pdf->Ln();

$pdf->SetFillColor(255,255,255);
$w=array(10,23,23,40,40,20,15,25,25,25,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();
//$objQuery = MarginReportModel::GetMarginReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value']);
$objQuery = MarginReportModel::GetMarginReportNonExcisePDF($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['buyerid'],$_REQUEST['txtinvoicenumber'],$_REQUEST['finyear'],$_REQUEST['marketsegment']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

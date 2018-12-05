<?php 
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
            $this->Cell(1);
            $this->Cell(10,6,$eachResult["SN"],0);
            $this->Cell(23,6,$eachResult["invno"],0);
            $this->Cell(23,6,$eachResult["invdate"],0);
            $this->Cell(40,6,$eachResult["groupdesc"],0);
            $this->Cell(40,6,$eachResult["tarrifheading"],0);
            $this->Cell(18,6,$eachResult["unitname"],0);
            $this->Cell(25,6,number_format((float)$eachResult["quantity"], 2, '.', ''),0);
            $this->Cell(25,6,number_format((float)$eachResult["duty"], 2, '.', ''),0);
         
            $this->Ln();
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Description of Goods','CETSH No','Qty Code','Quantity','Amount of Duty Involved(Rs)');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/ReportModel/MarginReportModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");

//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'STOCK TRANSFER EXCISE REPORT'.',  From  :- '.$_REQUEST['todate'].'   To  :- '.$_REQUEST['fromdate']);
$pdf->Ln();

$pdf->SetFillColor(255,255,255);
$w=array(10,23,23,40,40,18,15,25,25,25,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],8,$header[$i],0,0,'L',true);
$pdf->Ln();
$objQuery = StockStatementModel::GetStockTransferExciseReturn("STOCKTRANSFER",$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

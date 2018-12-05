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
        //$this->SetFillColor(255,255,255);
        //$this->SetDrawColor(255, 0, 0);
        
        //Data
        $this->SetFont('Arial','',10);
        foreach ($data as $eachResult) 
        { //width 
			$lines = (strlen($eachResult["Item_Desc"]))/40;
			$row_height = 5;
			if($lines > 1) {
				$row_height = $row_height * ceil($lines);
			}
			
			
			if (strlen($eachResult["Item_Desc"]) > 15){
				$Item_Desc = substr($eachResult["Item_Desc"], 0, 15) . '...';
			}else{
				$Item_Desc = $eachResult["Item_Desc"];
			}
			
			if (strlen($eachResult["BuyerName"]) > 10){
				$bname = substr($eachResult["BuyerName"], 0, 10) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			if (strlen($eachResult["PRINCIPAL_NAME"]) > 10){
				$pname = substr($eachResult["PRINCIPAL_NAME"], 0, 10) . '...';
			}else{
				$pname = $eachResult["PRINCIPAL_NAME"];
			}
			
			if (strlen($eachResult["Item_Code_Partno"]) > 4){
				$code_part = substr($eachResult["Item_Code_Partno"], 0, 4) . '...';
			}else{
				$code_part = $eachResult["Item_Code_Partno"];
			}
			
			
			$marketsegment = '';
				if($eachResult["msid"] == 1){
					$marketsegment = 'AUTO';
				}else if($eachResult["msid"] == 2){
					$marketsegment = 'GEN';
				}else if($eachResult["msid"] == 3){
					$marketsegment = 'MRO';
				}else if($eachResult["msid"] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
			if($eachResult['po_price'] !== "Gross Total")
			{
				$this->Cell(1);
				$this->Cell(6,$row_height,$eachResult["SN"],0);
				$this->Cell(24,$row_height,$eachResult["oinvoice_No"],0);
				$this->Cell(22,$row_height,$eachResult["oinv_date"],0,0);
				$this->Cell(25,$row_height,$eachResult["BuyerCode"],0);
				$this->Cell(27,$row_height,$bname,0);
				$this->Cell(27,$row_height,$pname,0);
				$this->Cell(18,$row_height,$code_part,0);
				$this->Cell(50,$row_height,$Item_Desc,0);
				$this->Cell(12,$row_height,$eachResult["issued_qty"],0);
				$this->Cell(12,$row_height,$eachResult["UNITNAME"],0);
				$this->Cell(18,$row_height,$eachResult["po_price"],0);
				$this->Cell(20,$row_height,number_format((float)$eachResult["totalprice"], 2, '.', ''),0);
				$this->Cell(15,$row_height,$eachResult["marketsegment"],0);
				$this->Ln();
			}
			else
			{
				$this->Cell(1);
				$this->Cell(6,$row_height,'',0);
				$this->Cell(24,$row_height,'',0);
				$this->Cell(22,$row_height,'',0,0);
				$this->Cell(25,$row_height,'',0);
				$this->Cell(27,$row_height,'',0);
				$this->Cell(27,$row_height,'',0);
				$this->Cell(18,$row_height,'',0);
				$this->Cell(50,$row_height,'',0);
				$this->Cell(12,$row_height,'',0);
				$this->Cell(8,$row_height,'',0);
				$this->Cell(22,$row_height,$eachResult["po_price"],0);
				$this->Cell(20,$row_height,number_format((float)$eachResult["totalprice"], 2, '.', ''),0);
				$this->Cell(15,$row_height,'',0);
				$this->Ln();
			}
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','InvoiceNo','InvoiceDate','BuyerCode','BuyerName','Principal','PartNo','Item Desc','Qty','Unit','Price','TotalPrice','Market Segment');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//print_r($CompanyInfo); exit;
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Secondary Sales Statement Report : From  '.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(6,24,22,25,27,27,18,50,12,12,18,20,15);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery = StockStatementModel::GetExciseSecondarySalesStatement($_REQUEST['Principal'],$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketsegment'],$_REQUEST['finyear'],$_REQUEST['buyerid']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

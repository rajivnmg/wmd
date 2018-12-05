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
		//echo '<pre>'; print_r($eachResult); echo '</pre>';
		
			if (strlen($eachResult["BuyerName"]) > 6){
					$bname = substr($eachResult["BuyerName"], 0, 6) . '...';
			}else{
					$bname = $eachResult["BuyerName"];
			}
			
			if (strlen($eachResult["PRINCIPAL_NAME"]) > 6){
					$pname = substr($eachResult["PRINCIPAL_NAME"], 0, 6) . '...';
			}else{
					$pname = $eachResult["PRINCIPAL_NAME"];
			}
			
			if (strlen($eachResult["Item_Desc"]) > 5){
					$item_Desc = substr($eachResult["Item_Desc"], 0, 5) . '...';
			}else{
					$item_Desc = $eachResult["Item_Desc"];
			}
			
			if (strlen($eachResult["BuyerCode"]) > 5){
					$BuyerCode = substr($eachResult["BuyerCode"], 0, 5) . '...';
			}else{
					$BuyerCode = $eachResult["BuyerCode"];
			}
			
			if (strlen($eachResult["Item_Code_Partno"]) > 5){
					$Item_Code_Partno = substr($eachResult["Item_Code_Partno"], 0, 5) . '...';
			}else{
					$Item_Code_Partno = $eachResult["Item_Code_Partno"];
			}
			
			if($eachResult['IGST_AMOUNT'] !== "Gross Total")
			{
				$this->Cell(1);
				$this->Cell(6,5,$eachResult["SN"],0);
				$this->Cell(26,5,$eachResult["oinvoice_No"],0);
				$this->Cell(22,5,$eachResult["oinv_date"],0,0);
				$this->Cell(15,5,$BuyerCode,0);
				$this->Cell(20,5,$bname,0);
				$this->Cell(20,5,$pname,0);
				$this->Cell(15,5,$Item_Code_Partno,0);
				$this->Cell(15,5,$item_Desc,0);
				$this->Cell(15,5,$eachResult["issued_qty"],0);
				$this->Cell(8,5,$eachResult["UNITNAME"],0);
				$this->Cell(15,5,$eachResult["po_price"],0);
				$this->Cell(15,5,$eachResult["DISCOUNT"],0);
				$this->Cell(15,5,$eachResult["TAXABLE_AMOUNT"],0);
				$this->Cell(15,5,$eachResult["CGST_AMOUNT"],0);
				$this->Cell(15,5,$eachResult["SGST_AMOUNT"],0);
				$this->Cell(15,5,$eachResult["IGST_AMOUNT"],0);
				$this->Cell(15,5,number_format((float)$eachResult["totalprice"], 2, '.', ''),0);
				$this->Cell(10,5,$eachResult["marketsegment"],0);
				$this->Ln();
			}
			else 
			{
				$this->Cell(1);
				$this->Cell(6,5,'',0);
				$this->Cell(26,5,'',0);
				$this->Cell(22,5,'',0,0);
				$this->Cell(15,5,'',0);
				$this->Cell(20,5,'',0);
				$this->Cell(20,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(8,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,'',0);
				$this->Cell(15,5,$eachResult["IGST_AMOUNT"],0);
				$this->Cell(15,5,number_format((float)$eachResult["totalprice"], 2, '.', ''),0);
				$this->Cell(10,5,'',0);
				$this->Ln();
			}
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SN','InvoiceNo','InvoiceDate','BuyerCode','BuyerName','Principal','PartNo','Item_Desc','Qty','Unit','Price','DISCOUNT','TAXABLE','CGST','SGST','IGST','TotalPrice','Market Segment');
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

$pdf->Write(5, 'Sales Statement Report : From  '.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(6,26,22,15,20,20,15,15,15,8,15,15,15,15,15,15,15,10);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery = StockStatementModel::GetExciseSecondarySales($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['salseuser'],$_REQUEST['marketsegment'],$_REQUEST['buyerid']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

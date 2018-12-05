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
		//$total =0;
        foreach ($data as $eachResult) 
        { //width
			
			$Item_desc='';
			
			if (strlen($eachResult['Item_desc']) > 45){
				$Item_desc = substr($eachResult['Item_desc'], 0, 45) . '...';
			}else{
				$Item_desc = $eachResult['Item_desc'];
			}
			
				$this->Cell(1);
				$this->Cell(10,6,$eachResult['SN'],0);
				$this->Cell(33,6,$eachResult['Item_Code_Partno'],0);				
				$this->Cell(90,6,$Item_desc,0);
				$this->Cell(30,6,$eachResult['tot_exciseQty'],0);
				$this->Cell(35,6,$eachResult['tot_nonExciseQty'],0);
				$this->Cell(40,6,$eachResult['issue_qty'],0);
				$this->Cell(30,6,$eachResult['stock_qty'],0);
				
				$this->Ln();
			
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','CodePart No.','Item Description','Excise Stock','Non-Excise Stock','Issue Qty (In Challan)','Stock');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//print_r($CompanyInfo); exit;
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Excise NonExcise Chalan Stock Statement : COdePart No -'.$_REQUEST['txtsearchkey']);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,33,90,30,35,40,30);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery = StockStatementModel::GetItemExciseNonExciseChallanIssuedQty($_REQUEST['txtsearchkey']);

$pdf->BasicTable($objQuery);
$pdf->Output();

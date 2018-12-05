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
			if($eachResult['basic_value'] != "Total : "){
				
				
				if (strlen($eachResult["Principal_Supplier_Name"]) > 35){
					$pname = substr($eachResult["Principal_Supplier_Name"], 0, 35) . '...';
				}else{
					$pname = $eachResult["Principal_Supplier_Name"];
				}
			
				if (strlen($eachResult["Item_Desc"]) > 25){
					$item_Desc = substr($eachResult["Item_Desc"], 0, 25) . '...';
				}else{
					$item_Desc = $eachResult["Item_Desc"];
				}
				
				$this->Cell(1);
				$this->Cell(10,6,$eachResult["SN"],0);
				$this->Cell(23,6,$eachResult["invoice_No"],0);
				$this->Cell(23,6,$eachResult["inv_date"],0);
				$this->Cell(60,6,$pname,0);
				$this->Cell(24,6,$eachResult["Item_Code_Partno"],0);
				$this->Cell(60,6,$item_Desc,0);
				$this->Cell(15,6,$eachResult["qty"],0);
				$this->Cell(25,6,number_format((float)$eachResult["unitrate"], 2, '.', ''),0);
				$this->Cell(25,6,number_format((float)$eachResult["basic_value"], 2, '.', ''),0);
				$this->Cell(25,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
				
			}else{
				$this->Cell(1);
				$this->Cell(10,6,'',0);
				$this->Cell(23,6,'',0);
				$this->Cell(23,6,'',0);
				$this->Cell(60,6,'',0);
				$this->Cell(24,6,'',0);
				$this->Cell(60,6,'',0);
				$this->Cell(15,6,'',0);
				$this->Cell(25,6,'',0);
				$this->Cell(25,6,$eachResult["basic_value"],'',0);
				$this->Cell(25,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
			
			}
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Principal Name','CodePart','CodePart Desc','Qty','Basic Price','Basic Value','Invoice Value');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//*** Table 1 ***//
$pdf->AddPage('L');
$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'Purchase Report : From  '.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,23,23,60,24,60,15,25,25,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery =  SalseReportModel::GetPurchaseReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketseg'],$_REQUEST['invoicenumber'],$_REQUEST['pid'],$_REQUEST['itemid']);
$pdf->BasicTable($objQuery);
$pdf->Output();


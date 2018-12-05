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
			$pname='';
			$bname='';
			if (strlen($eachResult["Principal_Supplier_Name"]) > 35){
				$pname = substr($eachResult["Principal_Supplier_Name"], 0, 35) . '...';
			}else{
				$pname = $eachResult["Principal_Supplier_Name"];
			}
			
			if (strlen($eachResult["BuyerName"]) > 25){
				$bname = substr($eachResult["BuyerName"], 0, 25) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			if($eachResult['tax_amt'] != "Total : "){
				$this->Cell(1);
				$this->Cell(10,6,$eachResult["SN"],0);
				$this->Cell(33,6,$eachResult["oinvoice_No"],0);				
				$this->Cell(33,6,date("d-m-Y", strtotime($eachResult["oinv_date"])),0);
				$this->Cell(68,6,$pname,0);
				$this->Cell(63,6,$bname,0);
				$this->Cell(24,6,number_format((float)$eachResult["taxable_amt"], 2, '.', ''),0);
				$this->Cell(24,6,number_format((float)$eachResult["tax_amt"], 2, '.', ''),0);
				$this->Cell(24,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
				//$total =$total + $eachResult["bill_value"] ;
			}else{
				$this->Cell(1);
				$this->Cell(10,6,'',0);
				$this->Cell(33,6,'',0);
				$this->Cell(33,6,'',0);
				$this->Cell(63,6,'',0);
				$this->Cell(63,6,'',0);			
				$this->Cell(24,6,'',0);					
				$this->Cell(24,6,$eachResult["tax_amt"],0);
				$this->Cell(24,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
			}
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Principal/SupplierName','BuyerName','Taxable Amount','Tax Amount','Total Amount');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//print_r($CompanyInfo); exit;
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Daily Sales Report : From -'.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,33,33,63,63,24,24,24);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

 $objQuery = SalseReportModel::getDailySalesReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value'],$_REQUEST['pid'],$_REQUEST['bid']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

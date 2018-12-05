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
			if (strlen($eachResult["Principal_Supplier_Name"]) > 15){
				$pname = substr($eachResult["Principal_Supplier_Name"], 0, 12) . '...';
			}else{
				$pname = $eachResult["Principal_Supplier_Name"];
			}
			
			if (strlen($eachResult["BuyerName"]) > 15){
				$bname = substr($eachResult["BuyerName"], 0, 12) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			if($eachResult['other_amt'] !== "Total : "){
				$this->Cell(1);
				$this->Cell(6,6,$eachResult["SN"],0);
				$this->Cell(26,6,$eachResult["oinvoice_No"],0);				
				$this->Cell(20,6,date("d-m-Y", strtotime($eachResult["oinv_date"])),0);
				$this->Cell(25,6,$pname,0);
				$this->Cell(25,6,$bname,0);
				$this->Cell(20,6,number_format((float)$eachResult["taxable_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["cgst_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["sgst_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["igst_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["freight_amount"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["p_f_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["ins_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["inc_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["other_amt"], 2, '.', ''),0);
				$this->Cell(17,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
				//$total =$total + $eachResult["bill_value"] ;
			}else{
				$this->Cell(1);
				$this->Cell(6,6,'',0);
				$this->Cell(26,6,'',0);
				$this->Cell(20,6,'',0);
				$this->Cell(25,6,'',0);
				$this->Cell(25,6,'',0);			
				$this->Cell(20,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);			
				$this->Cell(17,6,'',0);				
				$this->Cell(17,6,$eachResult["other_amt"],0);
				$this->Cell(17,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0);
				$this->Ln();
			}
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SN','Invoice No','Invoice Date','Principal Name','BuyerName','Taxable Amt','CGST Amt','SGST Amt','IGST Amt','Fre. Amt','P&F Amt','Ins. Amt','Inc. Amt','Oth. Amt','Total Amt');
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

$pdf->Write(5, 'Daily Sales Report : From '.date("d-m-Y", strtotime($_REQUEST['todate'])).' To '.date("d-m-Y", strtotime($_REQUEST['fromdate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(6,26,20,25,25,20,17,17,17,17,17,17,17,17,17,17);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery = SalseReportModel::getDailySalesReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value'],$_REQUEST['pid'],$_REQUEST['bid']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

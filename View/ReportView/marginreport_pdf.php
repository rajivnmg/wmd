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
			if (strlen($eachResult["Principal_Supplier_Name"]) > 20){
				$pname = substr($eachResult["Principal_Supplier_Name"], 0, 20) . '...';
			}else{
				$pname = $eachResult["Principal_Supplier_Name"];
			}
			
			if (strlen($eachResult["BuyerName"]) > 15){
				$bname = substr($eachResult["BuyerName"], 0, 15) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			if($eachResult['Margin'] !== "Grand Total"){
				$this->Cell(1);
				$this->Cell(8,6,$eachResult["SN"],0);
				$this->Cell(25,6,$eachResult["oinvoice_No_excel"],0);
				$this->Cell(23,6,$eachResult["oinv_date"],0);
				$this->Cell(40,6,$pname,0);
				$this->Cell(40,6,$bname,0);
				$this->Cell(22,6,$eachResult["Item_Code_Partno"],0);
				$this->Cell(15,6,$eachResult["issued_qty"],0, 0, 'R');
				$this->Cell(25,6,number_format((float)$eachResult["Salling"], 2, '.', ''),0, 0, 'R');
				$this->Cell(25,6,number_format((float)$eachResult["landing_price"], 2, '.', ''),0, 0, 'R');
				$this->Cell(23,6,number_format((float)$eachResult["Margin"], 2, '.', ''),0, 0, 'R');
				$this->Cell(25,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0, 0, 'R');
				$this->Cell(15,6,$eachResult['ms'],0);
				$this->Ln();
				//$total =$total + $eachResult["bill_value"] ;
			}else{
				$this->Cell(1);
				$this->Cell(8,6,'',0);
				$this->Cell(25,6,'',0);
				$this->Cell(23,6,'',0);
				$this->Cell(40,6,'',0);
				$this->Cell(40,6,'',0);
				$this->Cell(22,6,'',0);
				$this->Cell(15,6,'',0, 0, 'R');
				$this->Cell(25,6,'',0, 0, 'R');
				$this->Cell(25,6,'',0, 0, 'R');
				$this->Cell(23,6,$eachResult["Margin"],0, 0, 'R');
				$this->Cell(25,6,number_format((float)$eachResult["bill_value"], 2, '.', ''),0, 0, 'R');
				$this->Cell(15,6,'',0);
				$this->Ln();
			}
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Principal Name','Buyer Name','CodePart','Qty','Salling Price','Landing Price','Margin','Bill Amount','Market Seg');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/MarginReportModel.php");
//print_r($CompanyInfo); exit;
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Margin Report');

$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(8,25,23,40,40,22,15,25,25,23,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$objQuery = MarginReportModel::GetMarginReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['buyerid'],$_REQUEST['txtinvoicenumber'],$_REQUEST['finyear'],$_REQUEST['marketsegment']);
$pdf->BasicTable($objQuery);
$pdf->Output();
?>
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
            $this->Cell(20,6,$eachResult->_quotation_id,0);
            $this->Cell(33,6,$eachResult->_quotation_no,0);
            $this->Cell(33,6,$eachResult->_quotation_date,0);
            $this->Cell(60,6,$eachResult->_principal_name,0);
            $this->Cell(60,6,$eachResult->_coustomer_name,0);
            $this->Cell(18,6,$eachResult->_contact_persone,0);          
            $this->Ln();
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('Quation ID','Quation Number','Quotation Date','Principal Name','Customer Name','Contact Person');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include '../../Model/Business_Action_Model/Quation_Model.php';

//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'Quotation');
$pdf->Ln();

$pdf->SetFillColor(255,255,255);
$w=array(20,33,33,60,60,18,15,25,25,25,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();
$objQuery = Quotation_Model::downloadQuotation($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['buyerId'],$_REQUEST['principalId'],$_REQUEST['quotno']);

$pdf->BasicTable($objQuery);
$pdf->Output();
?>

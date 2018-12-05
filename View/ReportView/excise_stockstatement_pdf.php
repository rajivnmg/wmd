<?php 
ini_set("session.auto_start", 0); 
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
            $this->Cell(1);
            $this->Cell(10,5,$eachResult["SN"],0);
            $this->Cell(20,5,$eachResult["Item_Code_Partno"],0);
            $this->Cell(120,5,$eachResult["Item_Desc"],0,0);
            $this->Cell(25,5,$eachResult["Tarrif_Heading"],0);
            $this->Cell(15,5,$eachResult["tot_Qty"],0);
            $this->Cell(15,5,$eachResult["UNITNAME"],0);
            $this->Cell(22,5,$eachResult["Cost_Price"],0);
            $this->Cell(28,5,number_format((float)$eachResult["total_price"], 2, '.', ''),0);
            $this->Ln();
        }
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','PartNo','Product Description','HSN Code','Quantity','Unit','Price','TotalPrice');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
include '../../Model/Masters/GroupMaster_Model.php';

//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'Stock Statement With Value');
$pdf->Ln();

$pdf->SetFillColor(255,255,255);
$w=array(10,20,120,25,15,15,22,28);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],7,$header[$i],0,0,'L',true);
$pdf->Ln();
$GrandTotal = 0.00;
$groupData = GroupMasterModel::GetGroupList();
while ($grouprow = mysql_fetch_array($groupData,MYSQL_ASSOC))
{
    $objQuery = StockStatementModel::GetExciseStock($grouprow["GroupId"]);
    if(mysql_num_rows($objQuery) > 0)
    {
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0);
        $pdf->Write(5, "Group : ". $grouprow["GroupDesc"]);
        $pdf->Ln(); 
        $resultData = array();
        $GrossTotal = 0.00;
        for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
            $result = mysql_fetch_array($objQuery);
            $GrossTotal = $GrossTotal + $result[7];
            array_push($resultData,$result); 
        }
        $pdf->BasicTable($resultData);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(200);
        $pdf->Write(5, "Gross Total : ". $GrossTotal);
        $pdf->Ln();
        $GrandTotal = $GrandTotal + $GrossTotal;
    }
}
$pdf->SetFont('Arial','',12);
$pdf->Cell(200);
$pdf->Write(5, "Grand Total : ". $GrandTotal);
$pdf->Ln();
$pdf->Output();


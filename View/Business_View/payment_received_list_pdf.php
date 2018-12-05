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
         
        $this->SetFont('Arial','',10);
		$i=1;
		
        foreach ($data as $eachResult) 
        { 
			$bname='';
					
			if (strlen($eachResult["BuyerName"]) > 25){
				$bname = substr($eachResult["BuyerName"], 0, 25) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			$status ='';
			if($eachResult['status']=='created'){
				$status ='Active';
			}else if($eachResult['status']=='cancelled'){
					$status ='Cancelled';
			}else{
				$status =$eachResult['status'];
			}
			
			
				$this->Cell(1);
				$this->Cell(10,6,$i++,0);
				$this->Cell(10,6,$eachResult["trxnId"],0);				
				$this->Cell(25,6,$eachResult["trnx_no"],0);
				$this->Cell(25,6,date("d-m-Y", strtotime($eachResult["trxn_date"])),0);
				$this->Cell(45,6,$eachResult["bpono"],0);
				$this->Cell(60,6,$bname,0);
				
				$this->Cell(30,6,$eachResult["trxn_type"],0);
				$this->Cell(20,6,$status,0);
				$this->Cell(40,6,number_format((float)$eachResult["received_amt"], 2, '.', ''),0);
				$this->Cell(25,6,$eachResult["UserId"],0);
				$this->Ln();
				//$total =$total + $eachResult["bill_value"] ;
			
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Payment ID','Payment Referrence Number','Payment Date','PO Number','BuyerName','Payment Type','Status','Received Amount','User');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/Business_Action_Model/pay_model.php");
include_once("../../Model/Param/param_model.php");

$CompanyInfo = ParamModel::GetCompanyInfo();
//print_r($CompanyInfo); exit;
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Payment Received list From -: '.date("d-m-Y", strtotime($_REQUEST['Fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['Todate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,10,25,25,45,60,30,20,40,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();

$Buyerid= $_REQUEST['Buyerid'];
$trxnNo= $_REQUEST['trxnNo'];
$Fromdate= $_REQUEST['Fromdate'];
$Todate= $_REQUEST['Todate'];
$pono= $_REQUEST['pono'];
$finyear= $_REQUEST['finyear'];

 $objQuery=Payment_Model::SearchPaymentTransaction($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear,0,1000000);		

$pdf->BasicTable($objQuery);
$pdf->Output();
?>

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
		$i = 1;
        foreach ($data as $eachResult) 
        { //width
		
			$bname='';						
			if (strlen($eachResult["BuyerName"]) > 25){
				$bname = substr($eachResult["BuyerName"], 0, 25) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			$pname='';						
			if (strlen($eachResult["principalName"]) > 25){
				$pname = substr($eachResult["principalName"], 0, 25) . '...';
			}else{
				$pname = $eachResult["principalName"];
			}
				$this->Cell(1);
				$this->Cell(10,6,$i,0);
				$this->Cell(50,6,$pname,0);		
				$this->Cell(50,6,$bname,0);		
				$this->Cell(30,6,$eachResult["locationName"],0);				
				$this->Cell(20,6,$eachResult["no_of_po"],0);
				$this->Cell(30,6,$eachResult["bpoType"],0);
				$this->Cell(30,6,$eachResult["executiveId"],0);	
				$this->Cell(30,6,$eachResult["finyear"],0);				
				$this->Cell(30,6,number_format((float)$eachResult["po_val"], 2, '.', ''),0);
				$this->Ln();
					
		$i++;
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','BuyerName','No of PO','PoType','Executive','Financial Year','Revenue(Amount)');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/Business_Action_Model/PO_Reports_Model.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//print_r($CompanyInfo); exit;

//*** Table 1 ***//


$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Year Wise Revenue Deatils : Financial Year : '.$_REQUEST['finyear']);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,100,20,30,30,30,30);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();		
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 100000;
		$start=(($page-1)*$rp);      
          
$objQuery =  PO_Reports_Model::GetFinYearWiseRevenue($_REQUEST['usertype'],$_REQUEST['finyear'],$_REQUEST['bid'],$_REQUEST['potype'],$_REQUEST['principalid'],$_REQUEST['FromDate'],$_REQUEST['ToDate'],$_REQUEST['locationid'],$start,$rp);

//print_r($objQuery); exit;
 
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

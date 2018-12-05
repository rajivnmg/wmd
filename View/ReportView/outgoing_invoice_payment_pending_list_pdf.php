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
			if (strlen($eachResult["BuyerName"]) > 35){
				$bname = substr($eachResult["BuyerName"], 0, 35) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			$now = time(); // or your date as well
			$your_date = strtotime($eachResult['invoiceDate']);
			$datediff = $now - $your_date;
			$d = floor($datediff/(60*60*24));			
				$this->Cell(1);
				$this->Cell(10,6,$i,0);
				$this->Cell(30,6,$eachResult["invoiceNo"],0);				
				$this->Cell(30,6,date("d-m-Y", strtotime($eachResult["invoiceDate"])),0);
				$this->Cell(30,6,date("d-m-Y", strtotime($eachResult["dueDate"])),0);
				//$this->Cell(12,6,$eachResult["credit_period"],0);	
				$this->Cell(12,6,$d,0);	
				
				$this->Cell(30,6,number_format((float)$eachResult["invoiceAmount"], 2, '.', ''),0);				
				$this->Cell(70,6,$bname,0);
				$this->Cell(30,6,$eachResult["executiveId"],0);	
				$this->Cell(30,6,number_format((float)$eachResult["balanceAmount"], 2, '.', ''),0);
				$this->Ln();
					
		$i++;
        }
		
			
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','Invoice No','Invoice Date','Due Date','Days','Invoice Amount','BuyerName','Executive','Due Amount');
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

$pdf->Write(5, 'Outgoing Invoice Pending Payment List : From -'.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(10,30,30,30,12,30,70,30,30);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],11,$header[$i],0,0,'L',true);
$pdf->Ln();
		
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 100000;
		$start=(($page-1)*$rp);      
        $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : "s.invoiceDate";
		$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : "DESC";
      
 $objQuery= PO_Reports_Model::paymentPendingInvoice($_REQUEST['usertype'],$_REQUEST['fromdate'],$_REQUEST['todate'],$_REQUEST['bid'],$_REQUEST['InvoiceType'],$_REQUEST['invoice_no'],$_REQUEST['finyear'],$start,$rp,$sortname,$sortorder);


 
$pdf->BasicTable($objQuery);
$pdf->Output();
?>

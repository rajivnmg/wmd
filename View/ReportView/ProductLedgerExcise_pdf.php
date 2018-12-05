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

        //$this->SetFillColor(255,255,255);
        //$this->SetDrawColor(255, 0, 0);
        
        //Data
        $this->SetFont('Arial','',10);
        $i = 1;
        foreach ($data as $eachResult) 
        { //width  
            $this->Cell(1);
            $this->Cell(10,5,$i,0);
            $this->Cell(25,5,$eachResult["invno"],0);
            $this->Cell(25,5,$eachResult["invdate"],0,0);
            $this->Cell(120,5,$eachResult["name"],0);
            $this->Cell(25,5,$eachResult["incomingqty"],0);
            $this->Cell(25,5,$eachResult["outgoingqty"],0);
            $this->Cell(25,5,$eachResult["balanceqty"],0);
            $this->Ln();
            $i++;
        }
    }
    //Better table
}

$pdf=new PDF();

$header=array('SNo','InvoiceNo','InvoiceDate','Principal/BuyerName','IncomingQty','OutgoingQty','BalanceQty');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';

//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);
$pdf->Write(5, 'Product Ledger Excise Report');
$pdf->Ln();

$pdf->SetFillColor(255,255,255);
$w=array(10,25,25,120,25,25,25);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],7,$header[$i],0,0,'L',true);
$pdf->Ln();
$GrandTotal = 0.00;
$Query = "";
/*         switch ($_REQUEST['type'])
        {
            case "Principal":
                $Query = "select iiex.principal_inv_no as invno , iiex.principal_inv_date as invdate,  
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiexd.p_qty as incomingqty, null as outgoingqty
,txndtl.balance_qty as balanceqty 
from incominginvoice_excise_detail as iiexd
inner join incominginvoice_excise as iiex on iiexd.entryId = iiex.entryId 
left join `transaction` as txn on iiex.entryId = txn.refId and txn.transactionType = 'IE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiex.principalId = pm.Principal_Supplier_Id 
where iiex.principalId = ".$_REQUEST['value']." AND iiex.principal_inv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
UNION select oiex.oinvoice_No as invno , oiex.oinv_date as invdate, 
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oiexd.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty   
from outgoinginvoice_excise_detail as oiexd
inner join outgoinginvoice_excise as oiex on oiexd.oinvoice_exciseID = oiex.oinvoice_exciseID
left join `transaction` as txn on oiex.oinvoice_exciseID = txn.refId and txn.transactionType = 'OE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oiex.principalID = pm.Principal_Supplier_Id 
inner join buyer_master as bm on oiex.BuyerID = bm.BuyerId 
where oiex.principalID = ".$_REQUEST['value']." AND oiex.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
order by invdate";
                break;
            case "Codepart";
                $Query = "select iiex.principal_inv_no as invno , iiex.principal_inv_date as invdate,  
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiexd.p_qty as incomingqty, null as outgoingqty
,txndtl.balance_qty as balanceqty 
from incominginvoice_excise_detail as iiexd
inner join incominginvoice_excise as iiex on iiexd.entryId = iiex.entryId 
left join `transaction` as txn on iiex.entryId = txn.refId and txn.transactionType = 'IE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiex.principalId = pm.Principal_Supplier_Id 
where iiexd.itemID_code_partNo = ".$_REQUEST['value']." AND iiex.principal_inv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
UNION select oiex.oinvoice_No as invno , oiex.oinv_date as invdate, 
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oiexd.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty   
from outgoinginvoice_excise_detail as oiexd
inner join outgoinginvoice_excise as oiex on oiexd.oinvoice_exciseID = oiex.oinvoice_exciseID
left join `transaction` as txn on oiex.oinvoice_exciseID = txn.refId and txn.transactionType = 'OE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oiex.principalID = pm.Principal_Supplier_Id 
inner join buyer_master as bm on oiex.BuyerID = bm.BuyerId 
where oiexd.codePartNo_desc = ".$_REQUEST['value']." AND oiex.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
order by invdate";
            default :
                break;
        } */
        //echo $Query;
       // $objQuery = DBConnection::SelectQuery($Query);
	   $objQuery = StockStatementModel::GetExciseProductLedger($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['finyear']);
    /* if(mysql_num_rows($objQuery) > 0)
    {
        $resultData = array();
        for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
            $result = mysql_fetch_array($objQuery);
            //$GrandTotal = $GrandTotal + $result[10];
            array_push($resultData,$result);
        }
        $pdf->BasicTable($resultData);
    } */
	$pdf->BasicTable($objQuery);
$pdf->Output();

?>

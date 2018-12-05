<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=outgoinginvoicepaymentpending.xls");
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/Business_Action_Model/PO_Reports_Model.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
echo 'Outgoing Invoice Pending Payment List ' . "\t".'' . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['fromdate'])) - date("d-m-Y", strtotime($_REQUEST['todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";
		
echo '' . "\t".$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";
	
echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Due Date' . "\t" .'Days' . "\t" .'Invoice Amount'."\t" . 'BuyerName'."\t" . 'Executive'."\t".'Due Amount'."\t"."\n";


$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 100000;
$start=(($page-1)*$rp);      
$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : "s.invoiceDate";
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : "DESC";
//$total =0;	
 $i = 0;	
 $objQuery= PO_Reports_Model::paymentPendingInvoice($_REQUEST['usertype'],$_REQUEST['fromdate'],$_REQUEST['todate'],$_REQUEST['bid'],$_REQUEST['InvoiceType'],$_REQUEST['invoice_no'],$_REQUEST['finyear'],$start,$rp,$sortname,$sortorder);
if(empty($objQuery)){
 exit;
}

foreach ($objQuery as $eachResult) 
{
	
	$now = time(); // or your date as well
			$your_date = strtotime($eachResult['invoiceDate']);
			$datediff = $now - $your_date;
			$d = floor($datediff/(60*60*24));			
			
			
			echo  ++$i."\t".$eachResult['invoiceNo'] ."\t".date("d-m-Y", strtotime($eachResult['invoiceDate']))."\t".date("d-m-Y", strtotime($eachResult['dueDate'])). "\t".$d."\t" . number_format((float)$eachResult["invoiceAmount"], 2, '.', '')
					 ."\t".$eachResult['BuyerName']. "\t".$eachResult['executiveId']."\t" . number_format((float)$eachResult["balanceAmount"], 2, '.', '')."\t". "\n";
	
	
	
}
 

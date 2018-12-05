<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=DailySalesReport.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
echo 'Daily Sales Report ' . "\t".'' . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['todate'])) - date("d-m-Y", strtotime($_REQUEST['fromdate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";
		
echo '' . "\t".$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";
	
echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Principal_Name' . "\t" .'BuyerName' . "\t" .'Taxable Amount'."\t" . 'CGST Amt'."\t" .'SGST Amt'."\t" .'IGST Amt'."\t" .'Fre. Amt'."\t" .'P&F Amt'."\t" .'Ins. Amt'."\t" .'Inc. Amt'."\t" .'Oth. Amt'."\t" . 'Total Amount'."\t"."\n";

//$total =0;		
$objQuery = SalseReportModel::getDailySalesReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value'],$_REQUEST['pid'],$_REQUEST['bid']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{	
	if($eachResult['other_amt'] !== "Total : ")
	{
		echo    $eachResult['SN'] ."\t".
				$eachResult['oinvoice_No'] ."\t".
				date("d-m-Y", strtotime($eachResult['oinv_date'])). "\t".
				$eachResult['Principal_Supplier_Name'] ."\t".
				$eachResult['BuyerName'] ."\t" .
				number_format((float)$eachResult["taxable_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["cgst_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["sgst_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["igst_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["freight_amount"], 2, '.', '')."\t" .
				number_format((float)$eachResult["p_f_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["ins_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["inc_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["other_amt"], 2, '.', '')."\t" .
				number_format((float)$eachResult["bill_value"], 2, '.', '')."\t". "\n";
	}else{
		echo   ''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".'' ."\t".$eachResult['other_amt']. "\t" . number_format((float)$eachResult["bill_value"], 2, '.', '')."\t". "\n";
	}
			//$total =$total + $eachResult["bill_value"] ;
}
 // echo  ''."\t".''."\t". 'Total'. "\t" . number_format((float)$total, 2, '.', '')."\t". "\n";

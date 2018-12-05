<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=purchaseReport.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();

echo ' Purchase Order Report '. "\t" . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['fromdate'])) - date("d-m-Y", strtotime($_REQUEST['todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";

		//echo ''. "\t" .$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";

		echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Principal Name'."\t". 'CodePart'. "\t" .'CodePart Desc'. "\t". 'Qty'. "\t" . 'Basic Price'. "\t" . 'Basic Value'. "\t" . 'Invoice Value'."\t"."\n";



$objQuery = SalseReportModel::GetPurchaseReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketseg'],$_REQUEST['invoicenumber'],$_REQUEST['pid'],$_REQUEST['itemid']);
//print_r($objQuery); exit;
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult){
	if($eachResult['basic_value'] != "Total : "){
    echo    $eachResult['SN'] ."\t".$eachResult['invoice_No'] ."\t".$eachResult['inv_date']
            . "\t" . $eachResult['Principal_Supplier_Name'] ."\t" . $eachResult['Item_Code_Partno']."\t". str_replace(",", " ", $eachResult['Item_Desc']) 
            . "\t" . $eachResult['qty'] . "\t" . number_format((float)$eachResult["unitrate"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["basic_value"], 2, '.', '') 
            . "\t" . number_format((float)$eachResult["bill_value"], 2, '.', '')."\t". "\n";			
			
	}else{
		echo    ''."\t".''."\t".''. "\t" . '' ."\t".''. "\t" . ''."\t". '' . "\t" .'' . "\t" .$eachResult['basic_value']. "\t" . number_format((float)$eachResult['bill_value'], 2, '.', '')."\t". "\n";
	}
}

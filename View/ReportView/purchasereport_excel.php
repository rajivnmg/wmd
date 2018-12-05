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

		echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Principal Name'."\t". 'CodePart'. "\t" .'CodePart Desc'. "\t". 'Qty'. "\t" . 'Basic Price'. "\t" . 'Discount'. "\t" . 'Basic Value'. "\t" . 'CGST Amount'. "\t" . 'SGST Amount'. "\t" . 'IGST Amount'. "\t" . 'PACKING AMOUNT'. "\t" . 'INSURANCE AMOUNT'. "\t" . 'FREIGHT AMOUNT'. "\t" . 'OTHER AMOUNT'. "\t" . 'Total Amount'. "\t" . 'Invoice Value'."\t"."\n";



$objQuery = SalseReportModel::GetPurchaseReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketseg'],$_REQUEST['invoicenumber'],$_REQUEST['pid'],$_REQUEST['itemid']);
//print_r($objQuery); exit;
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult){
	if($eachResult['total_value'] != "Total : "){
    echo    $eachResult['SN'] ."\t".$eachResult['invoice_No'] ."\t".$eachResult['inv_date']
            . "\t" . $eachResult['Principal_Supplier_Name'] ."\t" . $eachResult['Item_Code_Partno']."\t". str_replace(",", " ", $eachResult['Item_Desc']) 
            . "\t" . $eachResult['qty'] . "\t" . number_format((float)$eachResult["unitrate"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["DISCOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["basic_value"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["CGST_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["SGST_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["IGST_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["PACKING_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["INSURANCE_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["FREIGHT_AMOUNT"], 2, '.', '')
			. "\t" . number_format((float)$eachResult["OTHER_AMOUNT"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["total_value"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["bill_value"], 2, '.', '')."\t". "\n";			
			
	}else{
		echo    ''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''."\t".''. "\t" . '' ."\t".''."\t".''. "\t" . ''."\t". '' . "\t" .'' . "\t" .$eachResult['total_value']. "\t" . number_format((float)$eachResult['bill_value'], 2, '.', '')."\t". "\n";
	}
}

<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=ExciseNonExciseChalanStockStatement.xls");
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
echo '' . "\t".'' . "\t".'Excise NonExcise Chalan Stock Statement ' . 'CodePart No : '.$_REQUEST['txtsearchkey']. "\t"."\n";
		
echo '' . "\t".$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";
	
echo 'SNo' . "\t" . 'CodePart No'."\t".'Item Description' . "\t" . 'Excise Stock' . "\t" .'Non-Excise Stock' . "\t" .'Issue Qty (In Challan)'."\t" . 'Stock'."\t"."\n";

//$total =0;		
$objQuery = StockStatementModel::GetItemExciseNonExciseChallanIssuedQty($_REQUEST['txtsearchkey']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) {
		
			echo    $eachResult['SN'] ."\t".$eachResult['Item_Code_Partno'] ."\t".$eachResult['Item_desc']
			. "\t".$eachResult['tot_exciseQty'] ."\t".$eachResult['tot_nonExciseQty'] ."\t" .$eachResult["issue_qty"]."\t" . $eachResult["stock_qty"]."\t". "\n";
	
			
}

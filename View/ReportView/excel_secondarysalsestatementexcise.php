<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=SecondarySalesStatement.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();

echo 'Secondary Sales Statement Report '. "\t" . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['fromdate'])) - date("d-m-Y", strtotime($_REQUEST['todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate'])). "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";

echo ''. "\t" .$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";

echo '' . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'BuyerCode'."\t".'BuyerName' ."\t".'Principal' ."\t".'PartNo'. "\t" . 'PartNo'. "\t" .'Qty'."\t". 'Unit'."\t".'Price' . "\t" . 'TotalPrice'."\t".'Market Segmet'."\t"."\n";

//$total =0;		
$objQuery = StockStatementModel::GetExciseSecondarySalesStatement($_REQUEST['Principal'],$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketsegment'],$_REQUEST['finyear'],$_REQUEST['buyerid']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{
	if($eachResult['po_price'] !== "Gross Total")
	{
		echo ''. $eachResult['oinvoice_No'] ."\t".$eachResult['oinv_date'] . "\t" . $eachResult['BuyerCode'] 
                ."\t".str_replace(',',' ',$eachResult['BuyerName'])."\t".str_replace(',',' ',$eachResult['PRINCIPAL_NAME']) . "\t" . $eachResult['Item_Code_Partno'] ."\t". str_replace(',',' ',$eachResult['Item_Desc']) ."\t".$eachResult['issued_qty'] 
                ."\t".$eachResult['UNITNAME']."\t".$eachResult['po_price']
                . "\t" . number_format((float)$eachResult["totalprice"], 2, '.', '')."\t".$eachResult['marketsegment']
                . "\t" ."\n";
	}else{
		echo ''. '' ."\t".'' . "\t" .''."\t".''. "\t" .''."\t".''."\t".''."\t".''."\t".''."\t".$eachResult['po_price']
                . "\t" . number_format((float)$eachResult["totalprice"], 2, '.', '')."\t".''. "\t" ."\n";
	}
}

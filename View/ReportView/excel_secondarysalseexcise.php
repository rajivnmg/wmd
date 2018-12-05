<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=SalesStatement.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();

echo 'Sales Statement '. "\t" . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['fromdate'])) - date("d-m-Y", strtotime($_REQUEST['todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";
		
echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'BuyerCode'."\t".'BuyerName' ."\t".'Principal' ."\t".'PartNo'. "\t".'CodePart Desc'. "\t" . 'Qty'."\t". 'Unit'."\t".'Price'. "\t" .'DISCOUNT'. "\t" .'TAXABLE AMOUNT'. "\t" .'CGST_AMOUNT'. "\t" .'SGST_AMOUNT'. "\t" .'IGST_AMOUNT'. "\t" . 'TotalPrice'."\t". 'FREIGHT AMOUNT'."\t". 'P_F AMOUNT'."\t". 'INCIDENTAL AMOUNT'."\t". 'INSURANCE AMOUNT'."\t". 'OTHER AMOUNT'."\t". 'INVOICE VALUE'."\t". 'Executive'."\t".'Market Segment'."\t"."\n";

//$total =0;		
$objQuery = StockStatementModel::GetExciseSecondarySales($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['salseuser'],$_REQUEST['marketsegment'],$_REQUEST['buyerid']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{	
	echo $eachResult['SN'] . "\t" . $eachResult['oinvoice_No'] ."\t".$eachResult['oinv_date'] . "\t" . $eachResult['BuyerCode'] ."\t".$eachResult['BuyerName'] . "\t" .$eachResult['PRINCIPAL_NAME'] . "\t" . $eachResult['Item_Code_Partno'] ."\t". $eachResult['Item_Desc'] ."\t".$eachResult['issued_qty'] ."\t".$eachResult['UNITNAME']."\t".$eachResult['po_price'] ."\t".$eachResult['DISCOUNT'] ."\t".$eachResult['TAXABLE_AMOUNT'] ."\t".$eachResult['CGST_AMOUNT'] ."\t".$eachResult['SGST_AMOUNT'] ."\t".$eachResult['IGST_AMOUNT'] ."\t". number_format((float)$eachResult["totalprice"], 2, '.', '') ."\t". number_format((float)$eachResult["FREIGHT_AMOUNT"], 2, '.', '') ."\t". number_format((float)$eachResult["P_F_AMOUNT"], 2, '.', '') ."\t". number_format((float)$eachResult["INCIDENTAL_AMOUNT"], 2, '.', '') ."\t". number_format((float)$eachResult["INSURANCE_AMOUNT"], 2, '.', '') ."\t". number_format((float)$eachResult["OTHER_AMOUNT"], 2, '.', '') ."\t". number_format((float)$eachResult["INVOICE_VALUE"], 2, '.', '') ."\t". $eachResult['salesExecutive'] ."\t". $eachResult['marketsegment'] ."\t" ."\n";
}

<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=StockTransferExciseReport.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/ReportModel/MarginReportModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");

echo '' . "\t" . 'STOCK TRANSFER EXCISE REPORT' . "\t" ."\t".'FROM : '.$_REQUEST['todate'] 
        . "\t" . 'TO : '.$_REQUEST['fromdate']. "\t" . ''. "\t" . ''. "\t" . ''
        . "\t" . ''. "\t" . ''."\t"."\n";
echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Description of Goods'."\t".'CETSH No'. "\t" . 'Qty Code'. "\t" .'Quantity'. "\t". 'Amount of Duty Involved(Rs)'."\t"."\n";

$objQuery = StockStatementModel::GetStockTransferExciseReturn("STOCKTRANSFER",$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear']);
if(empty($objQuery)){
exit;
}
foreach ($objQuery as $eachResult) 
{
    echo  $eachResult['SN'] ."\t".$eachResult['invno'] ."\t".$eachResult['invdate']
             . "\t" . $eachResult['groupdesc'] ."\t".$eachResult['tarrifheading']. "\t" . $eachResult['unitname']."\t" . number_format((float)$eachResult["quantity"], 2, '.', '')
		     . "\t" .number_format((float)$eachResult["duty"], 2, '.', '')."\t". "\n";
}

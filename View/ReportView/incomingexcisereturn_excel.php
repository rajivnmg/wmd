<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=IncomingExciseReport.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/ReportModel/MarginReportModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
echo '' . "\t" . ''."\t".'' . "\t" . 'Duration : '.($_REQUEST['fromdate'] - $_REQUEST['todate'])."\t".'FROM : '.$_REQUEST['todate'] 
        . "\t" . 'TO : '.$_REQUEST['fromdate']. "\t" . 'INCOMING EXCISE REPORT'. "\t" . ''. "\t" . ''
        . "\t" . ''. "\t" . ''."\t"."\n";
echo 'SNo' . "\t" . 'Invoice/Bill Of Entry No.'."\t".'Invoice Date' . "\t" . 'Issued By'. "\t" . 'Registration No'. "\t" .'Name'. "\t". 'address'. "\t" . 'Description of Goods'. "\t" . 'CETSH No'. "\t" . 'Qty Code'. "\t" . 'Quantity'."\t".'Amount of Duty Involved(Rs)'."\t"."\n";
$objQuery =StockStatementModel::GetExciseReturn("INCOMING",$_REQUEST['todate'],$_REQUEST['fromdate']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{
    echo    $eachResult['SN'] ."\t".$eachResult['invno'] ."\t".$eachResult['invdate']
            . "\t" . $eachResult['emp'] ."\t".str_replace(",", " ", $eachResult['ecccode']) . "\t" . $eachResult['princiname']."\t". str_replace(",", " ", $eachResult['princiadd']) 
            . "\t" . $eachResult['groupdesc'] . "\t" . $eachResult["tarrifheading"]. "\t" . $eachResult["unitname"]. "\t" . number_format((float)$eachResult["quantity"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["duty"], 2, '.', '')."\t". "\n";
}
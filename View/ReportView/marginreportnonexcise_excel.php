<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=MarginReport.xls");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/MarginReportModel.php';
echo '' . "\t" . ''."\t".'' . "\t" . 'Duration : '.($_REQUEST['todate'] - $_REQUEST['fromdate'])."\t".'FROM : '.$_REQUEST['fromdate'] 
        . "\t" . 'TO : '.$_REQUEST['todate']. "\t" . ''. "\t" . ''. "\t" . ''
        . "\t" . ''. "\t" . ''."\t"."\n";
echo 'SNo' . "\t" . 'Invoice No'."\t".'Invoice Date' . "\t" . 'Principal Name'."\t".'Buyer Name' 
        . "\t" . 'CodePart'. "\t" .'CodePart Desc'. "\t". 'Qty'. "\t" . 'Selling Price'. "\t" . 'Landing Price'
        . "\t" . 'Margin'. "\t" . 'Bill Amount'."\t".'Industry Segment'."\t"."\n";

$objQuery = MarginReportModel::GetMarginReportNonExcisePDF($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['buyerid'],$_REQUEST['txtinvoicenumber'],$_REQUEST['finyear'],$_REQUEST['marketsegment']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{
    echo    $eachResult['SN'] ."\t".$eachResult['oinvoice_No'] ."\t".$eachResult['oinv_date']
            . "\t" . $eachResult['Principal_Supplier_Name'] ."\t".str_replace(",", " ", $eachResult['BuyerName']) . "\t" . $eachResult['Item_Code_Partno']."\t". str_replace(",", " ", $eachResult['codePartNo_desc']) 
            . "\t" . $eachResult['issued_qty'] . "\t" . number_format((float)$eachResult["Salling"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["landing_price"], 2, '.', '') 
            . "\t" . number_format((float)$eachResult["Margin"], 2, '.', '')
            . "\t" . number_format((float)$eachResult["bill_value"], 2, '.', '')."\t".$eachResult['ms']."\t". "\n";
}

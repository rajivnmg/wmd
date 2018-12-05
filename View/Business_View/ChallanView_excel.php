<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=Quotation.xls");
include '../../Model/DBModel/DbModel.php';
include '../../Model/Business_Action_Model/Quation_Model.php';
echo '' . "\t" . ''."\t".'' . "\t" . 'Duration : '.($_REQUEST['todate'] - $_REQUEST['fromdate'])."\t".'FROM : '.$_REQUEST['fromdate'] 
        . "\t" . 'TO : '.$_REQUEST['todate']. "\t" . ''. "\t" . ''. "\t" . ''
        . "\t" . ''. "\t" . ''."\t"."\n";
echo 'Quation ID' . "\t" . 'Quation Number'."\t".'Quotation Date' . "\t" . 'Principal Name'."\t".'Customer Name' 
        . "\t" . 'Contact Person'. "\t" ."\n";

$objQuery = Quotation_Model::downloadQuotation($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['buyerId'],$_REQUEST['principalId'],$_REQUEST['quotno']);
if(empty($objQuery)){
 exit;
}

foreach ($objQuery as $eachResult) 
{
	//print_r($eachResult->_quotation_id);
    //echo $eachResult->_quotation_id ."\t".$eachResult->_quotation_no. "\t" . $eachResult->_quotation_date ."\t".$eachResult->_principal_name. "\t" . $$eachResult->_coustomer_name."\t". $eachResult->_contact_persone. "\t"."\n";
}
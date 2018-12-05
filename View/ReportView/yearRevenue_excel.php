<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=yearwiseRevenue.xls");
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/Business_Action_Model/PO_Reports_Model.php");
include_once("../../Model/Param/param_model.php");


$CompanyInfo = ParamModel::GetCompanyInfo();
echo 'Rrvenue details ' . "\t".'' . 'Financial Year : '.($_REQUEST['finyear'])."\t".' :  ';
		
echo '' . "\t".$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";
	
echo 'SNo' . "\t" . 'PrincipalName'. "\t" . 'LocationName'. "\t" . 'BuyerName'."\t".'No of PO' . "\t" . 'PoType' . "\t" .'Executive' . "\t" .'Financial Year'."\t" . 'Revenue(Amount)'."\t" ."\t"."\n";


$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 100000;
$start=(($page-1)*$rp);      
//$total =0;	
$i = 0;	
 
$objQuery =  PO_Reports_Model::GetFinYearWiseRevenue($_REQUEST['usertype'],$_REQUEST['finyear'],$_REQUEST['bid'],$_REQUEST['potype'],$_REQUEST['principalid'],$_REQUEST['FromDate'],$_REQUEST['ToDate'],$_REQUEST['locationid'],$start,$rp);

//print_r($objQuery); exit;
if(empty($objQuery)){
 exit;
}

foreach ($objQuery as $eachResult) 
{
			echo  ++$i."\t".str_replace(',', ' ', $eachResult['principalName'])."\t".str_replace(',', ' ', $eachResult['locationName'])."\t".str_replace(',', ' ', $eachResult['BuyerName'])."\t".$eachResult['no_of_po']."\t".$eachResult['bpoType']. "\t".$eachResult['executiveId'] ."\t" .$eachResult['finyear']."\t".number_format((float)$eachResult['po_val'], 2, '.', '')."\t"."\n";
	
	
	
}
 

<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=paymentReceivedList.xls");
include '../../Model/DBModel/DbModel.php';
include_once( "../../Model/Business_Action_Model/pay_model.php");
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
echo 'Payment Received list' . "\t".'' . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['Fromdate'])) - date("d-m-Y", strtotime($_REQUEST['Todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['Fromdate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['Todate'])). "\t"."\n";
		
echo '' . "\t".$CompanyInfo["Name"]. "\t" .str_replace(","," ",$CompanyInfo["Address"])."\t"."\n";
	
echo 'SNo' . "\t".'trxnId'."\t" . 'Trxn No'."\t".'Trxn Date' . "\t" . 'PO Number' . "\t" .'BuyerName' . "\t" .'Payment type'."\t" . 'Status'."\t" . 'Received Amount'."\t".'User'."\t"."\n";

//$total =0;
     
$Buyerid= $_REQUEST['Buyerid'];
$trxnNo= $_REQUEST['trxnNo'];
$Fromdate= $_REQUEST['Fromdate'];
$Todate= $_REQUEST['Todate'];
$pono= $_REQUEST['pono'];
$finyear= $_REQUEST['finyear'];
$i = 1;
 $objQuery=Payment_Model::SearchPaymentTransaction($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear,0,1000000);		

if(empty($objQuery)){
 exit;
}

foreach ($objQuery as $eachResult) {
    $bname='';					
    if (strlen($eachResult["BuyerName"]) > 25){
    $bname = substr($eachResult["BuyerName"], 0, 25) . '...';
    }else{
        $bname = $eachResult["BuyerName"];
	}	
    if($eachResult['status']=='created'){
        $status ='Active';
    }else if($eachResult['status']=='cancelled'){
        $status ='Cancelled';
    }else{
        $status =$eachResult['status'];
    }
    echo    $i ."\t".$eachResult['trxnId'] ."\t".$eachResult['trnx_no'] ."\t".date("d-m-Y", strtotime($eachResult['trxn_date']))
					. "\t".$eachResult['bpono'] ."\t".$eachResult["BuyerName"] ."\t" . $eachResult["trxn_type"]."\t" . $status."\t" . number_format((float)$eachResult["received_amt"], 2, '.', '')."\t".$eachResult['UserId']."\t". "\n";
	
    //$total =$total + $eachResult["bill_value"] ;
    $i =$i+1;
}
 // echo  ''."\t".''."\t". 'Total'. "\t" . number_format((float)$total, 2, '.', '')."\t". "\n";

<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
header("Content-disposition: attachment; filename=ProductLedgerExcise.xls");
echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'Principal/BuyerName'."\t".'IncomingQty'
        ."\t".'OutgoingQty'. "\t" . 'BalanceQty'."\t"."\n";
$GrandTotal = 0.00;
$Quantity = 0;
$Query = "";       
		$objQuery = StockStatementModel::GetExciseProductLedger($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['finyear']);
		
		
		 foreach ($objQuery as $row)
		{
			echo $sn . "\t" . $row['invno'] ."\t".$row['invdate'] . "\t" . $row['name'] 
                ."\t".$row['incomingqty'] . "\t" . $row['outgoingqty'] ."\t".$row['balanceqty'] 
                ."\t"."\n";
			$sn++;
}

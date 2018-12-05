<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
header("Content-disposition: attachment; filename=ProductLedgerNon-Excise.xls");
echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'Principal/BuyerName'."\t".'IncomingQty'
        ."\t".'OutgoingQty'. "\t" . 'BalanceQty'."\t"."\n";
$GrandTotal = 0.00;
$Quantity = 0;
$Query = "";
        switch ($_REQUEST['type'])
        {
            case "Principal":
                $Query = "select iiwe.incoming_inv_no_p as invno , iiwe.principal_inv_date as invdate,  
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiwed.qty as incomingqty, null as outgoingqty,
txndtl.balance_qty as balanceqty 
from incominginvoice_without_excise_detail as iiwed
inner join incominginvoice_without_excise as iiwe on iiwed.incominginvoice_we = iiwe.incominginvoice_we 
left join `transaction` as txn on iiwe.incominginvoice_we = txn.refId and txn.transactionType = 'InNonEx'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id 
where iiwe.principalID = ".$_REQUEST['value']." AND iiwe.principal_inv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
UNION select oine.oinvoice_No as invno , oine.oinv_date as invdate, 
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oined.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty   
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
left join `transaction` as txn on oine.oinvoice_nexciseID = txn.refId and txn.transactionType = 'ONE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id 
inner join buyer_master as bm on oine.BuyerID = bm.BuyerId 
where oine.principalID = ".$_REQUEST['value']." AND oine.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
order by invdate";
                break;
            case "Codepart";
                $Query = "select iiwe.incoming_inv_no_p as invno , iiwe.principal_inv_date as invdate,  
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiwed.qty as incomingqty, null as outgoingqty,
txndtl.balance_qty as balanceqty 
from incominginvoice_without_excise_detail as iiwed
inner join incominginvoice_without_excise as iiwe on iiwed.incominginvoice_we = iiwe.incominginvoice_we 
left join `transaction` as txn on iiwe.incominginvoice_we = txn.refId and txn.transactionType = 'InNonEx'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id 
where iiwed.itemID_code_partNo = ".$_REQUEST['value']." AND iiwe.principal_inv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
UNION select oine.oinvoice_No as invno , oine.oinv_date as invdate, 
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oined.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty   
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
left join `transaction` as txn on oine.oinvoice_nexciseID = txn.refId and txn.transactionType = 'ONE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id 
inner join buyer_master as bm on oine.BuyerID = bm.BuyerId 
where oined.codePartNo_desc = ".$_REQUEST['value']." AND oine.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' 
order by invdate";
                break;
            default :
                break;
        }
        //$objQuery = DBConnection::SelectQuery($Query);
		
		 $objQuery = StockStatementModel::GetNonExciseProductLedger($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['finyear']);
		  foreach ($objQuery as $row)
		{
			echo $sn . "\t" . $row['invno'] ."\t".$row['invdate'] . "\t" . $row['name'] 
                ."\t".$row['incomingqty'] . "\t" . $row['outgoingqty'] ."\t".$row['balanceqty'] 
                ."\t"."\n";
			$sn++;
}
/* if(mysql_num_rows($objQuery) > 0)
{
    $sn = 1;
    while ($row = mysql_fetch_array($objQuery,MYSQL_ASSOC))
    {
        echo $sn . "\t" . $row['invno'] ."\t".$row['invdate'] . "\t" . $row['PrincipalBuyerName'] 
                ."\t".$row['incomingqty'] . "\t" . $row['outgoingqty'] ."\t".$row['balanceqty'] 
                ."\t"."\n";
        $sn++;
    }
} */

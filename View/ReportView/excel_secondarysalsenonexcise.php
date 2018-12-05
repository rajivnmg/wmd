<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=ExciseSecondarySales.xls");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'BuyerCode'."\t".'BuyerName' ."\t".'PartNo'
        ."\t".'CodePart Desc'. "\t" . 'Qty'."\t". 'Unit'."\t".'Price'."\t" .'TotalPrice'."\t".'Industry Segment'."\t"."\n";
$GrandTotal = 0.00;
$Quantity = 0;
$Query = "";
      /*  switch ($_REQUEST['type'])
        {
            case "Principal":
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice 
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy 
where oine.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' AND oine.principalID = ".$_REQUEST['value']."  order by SN ASC";
                break;
            case "Codepart";
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice 
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy 
where oine.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' AND oied.codePartNo_desc = ".$_REQUEST['value']."  order by SN ASC";
                break;
            case "Salse";
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice 
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy 
where oine.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' AND oine.userId = '".$_REQUEST['value']."'  order by SN ASC";
                break;
            default :
                break;
        } */
       
       
$todate = $_REQUEST['todate'];
$fromdate = $_REQUEST['fromdate'];
$finyear = $_REQUEST['finyear'];
$principalid = $_REQUEST['principalid'];
$itemid = $_REQUEST['itemid'];
$salseuser = $_REQUEST['salseuser'];
$marketsegment = $_REQUEST['marketsegment'];
$buyerid	= $_REQUEST['buyerid'];

 
   $cond1 ='';
        if(!empty($buyerid))
        {
            $cond = $cond."AND oine.BuyerID = '".$buyerid."'";
          
        }
        if(!empty($principalid))
        {
            $cond = $cond."AND oine.principalID = '".$principalid."'";
          
        }
        if(!empty($itemid))
        {
            $cond = $cond."AND oied.codePartNo_desc = '".$itemid."'";
          
        }
               
        if(!empty($salseuser)) {
            $cond = $cond."AND po.executiveId LIKE '".$salseuser."%'";
        }
        if(!empty($marketsegment)) {
            $cond = $cond."AND oine.msid = '".$marketsegment."'";
        }        
        
    
         $finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} 
        
	
             $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) as totalprice,po.executiveId,oine.msid
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join outgoinginvoice_nonexcise_mapping AS oim ON oine.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by SN ASC";
        
        
$objQuery = DBConnection::SelectQuery($Query);
if(mysql_num_rows($objQuery) > 0)
{
    while ($row = mysql_fetch_array($objQuery,MYSQL_ASSOC))
    {
		
		$marketsegment = '';
				if($row['msid'] == 1){
					$marketsegment = 'AUTO';
				}else if($row['msid'] == 2){
					$marketsegment = 'GEN';
				}else if($row['msid'] == 3){
					$marketsegment = 'MRO';
				}else if($row['msid'] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
				
        echo $row['SN'] . "\t" . $row['oinvoice_No'] ."\t".$row['oinv_date'] . "\t" . $row['BuyerCode'] 
                ."\t".$row['BuyerName'] . "\t" . $row['Item_Code_Partno'] ."\t".$row['Item_Desc'] ."\t".$row['issued_qty'] 
                ."\t".$row['UNITNAME']."\t".$row['po_price']."\t". number_format((float)$row["totalprice"], 2, '.', '')."\t".$marketsegment."\t"."\n";
        $GrandTotal = $GrandTotal + $row['totalprice'];
        $Quantity = $Quantity + $row['issued_qty'];
    }
}
echo "Grand Total : "."\t"."\t"."\t"."\t"."\t"."\t".$Quantity."\t"."\t".$GrandTotal."\n";

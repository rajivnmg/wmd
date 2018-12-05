<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=Non_ExciseSecondarySalesStatement.xls");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'BuyerCode'."\t".'BuyerName' ."\t".'PartNo'
        . "\t" . 'Qty'."\t". 'Unit'."\t".'Price' . "\t" . 'TotalPrice'."\t".'Market Segmet'."\t"."\n";
$GrandTotal = 0.00;
$todate = $_REQUEST['todate'];
		$fromdate = $_REQUEST['fromdate'];
		$finyear = $_REQUEST['finyear'];
		$principalid = $_REQUEST['Principal'];
		$itemid = $_REQUEST['itemid'];
		$salseuser = $_REQUEST['salseuser'];
		$marketsegment = $_REQUEST['marketsegment'];
		$buyerid	= $_REQUEST['buyerid'];

         $cond ='';
         $cond1 ='';
        if(!empty($principalid))
        {
            $cond = $cond."AND oine.principalID = '".$principalid."'";
          
        }
        if(!empty($buyerid))
        {
            $cond = $cond."AND oine.BuyerID = '".$buyerid."'";
          
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
(oined.issued_qty * pod.po_price) as totalprice,oine.msid 
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join outgoinginvoice_nonexcise_mapping AS oim ON oine.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 

inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by SN ASC";
$objQuery = DBConnection::SelectQuery($Query);
if(mysql_num_rows($objQuery) > 0)
{
    while ($row = mysql_fetch_array($objQuery,MYSQL_ASSOC))
    {
		$marketsegment = '';
				if($row["msid"] == 1){
					$marketsegment = 'AUTO';
				}else if($row["msid"] == 2){
					$marketsegment = 'GEN';
				}else if($row["msid"] == 3){
					$marketsegment = 'MRO';
				}else if($row["msid"] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
        echo $row['SN'] . "\t" . $row['oinvoice_No'] ."\t".$row['oinv_date'] . "\t" . $row['BuyerCode'] 
                ."\t".str_replace(',',' ',$row['BuyerName']) . "\t" . $row['Item_Code_Partno'] ."\t".$row['issued_qty'] 
                ."\t".$row['UNITNAME']."\t".$row['po_price']
                . "\t" . number_format((float)$row["totalprice"], 2, '.', '')."\t".$marketsegment
                . "\t" ."\n";
        $GrandTotal = $GrandTotal + $row['totalprice'];
    }
}
echo "\t"."\t"."\t"."\t"."\t"."\t"."Grand Total : ". $GrandTotal."\t"."\n";

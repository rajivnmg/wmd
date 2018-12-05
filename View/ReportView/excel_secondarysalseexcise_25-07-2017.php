<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=ExciseSalesStatement.xls");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();

echo 'Excise Sales Statement '. "\t" . 'Duration : '.(date("d-m-Y", strtotime($_REQUEST['fromdate'])) - date("d-m-Y", strtotime($_REQUEST['todate'])))."\t".'FROM : '.date("d-m-Y", strtotime($_REQUEST['todate']))
        . "\t" . 'TO : '.date("d-m-Y", strtotime($_REQUEST['fromdate'])). "\t"."\n";

echo 'SNo' . "\t" . 'InvoiceNo'."\t".'InvoiceDate' . "\t" . 'BuyerCode'."\t".'BuyerName' ."\t".'PartNo'
        . "\t".'CodePart Desc'. "\t" . 'Qty'."\t". 'Unit'."\t".'Price'. "\t" . 'TotalPrice'."\t".'Industary Segment'."\t"."\n";
$GrandTotal = 0.00;
$Quantity = 0;
$Query = "";

$todate = $_REQUEST['todate'];
$fromdate = $_REQUEST['fromdate'];
$finyear = $_REQUEST['finyear'];
$principalid = $_REQUEST['principalid'];
$itemid = $_REQUEST['itemid'];
$salseuser = $_REQUEST['salseuser'];
$marketsegment = $_REQUEST['marketsegment'];
$buyerid	= $_REQUEST['buyerid'];

		$cond ='';
       
        if(!empty($principalid))
        {
            $cond = $cond."AND oie.principalID = '".$principalid."'";
          
        }
        if(!empty($buyerid))
        {
            $cond = $cond."AND oie.BuyerID = '".$buyerid."'";
          
        }
        if(!empty($itemid))
        {
            $cond = $cond."AND oied.codePartNo_desc = '".$itemid."'";
          
        }
               
        if(!empty($salseuser)) {
            $cond = $cond."AND po.executiveId LIKE '".$salseuser."%'";
        }
        if(!empty($marketsegment)) {
            $cond = $cond."AND oie.msid = '".$marketsegment."'";
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
		
		  
          $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) as totalprice,po.executiveId,oie.msid  from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by SN ASC";
        
     
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
                ."\t".$row['BuyerName'] . "\t" . $row['Item_Code_Partno'] ."\t". $row['Item_Desc'] ."\t".$row['issued_qty'] 
                ."\t".$row['UNITNAME']."\t".$row['po_price']."\t". number_format((float)$row["totalprice"], 2, '.', '')."\t". $marketsegment."\t"."\n";
        $GrandTotal = $GrandTotal + $row['totalprice'];
        $Quantity = $Quantity + $row['issued_qty'];
    }
}
echo "Grand Total : "."\t"."\t"."\t"."\t"."\t"."\t"."\t".$Quantity."\t"."\t"."\t".$GrandTotal."\n";

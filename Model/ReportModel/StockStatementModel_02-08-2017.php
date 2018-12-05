<?php
class StockStatementModel {
	public static function GetItemExciseNonExciseChallanIssuedQty($SearchKey)
	{
		$opt="AND itm.Item_Code_Partno LIKE '$SearchKey%'";
		$Query ="SELECT (@cnt := @cnt + 1) AS SN,temp.itemid,temp.issue_qty,iv.tot_exciseQty,iv.tot_nonExciseQty,itm.Item_desc,itm.Item_Code_Partno,(iv.tot_exciseQty+iv.tot_nonExciseQty-temp.issue_qty) AS stock_qty  FROM (SELECT SUM(qty) AS issue_qty,chd.code_part_no AS itemid FROM challan_detail AS chd INNER JOIN challan AS ch ON chd.ChallanId=ch.ChallanId WHERE ch.challan_status IN ('1','6') GROUP BY  chd.code_part_no ) AS temp INNER JOIN inventory AS iv ON temp.itemid=iv.code_partNo ,item_master AS itm CROSS JOIN (SELECT @cnt := 0) AS dummy WHERE temp.itemid=itm.ItemId $opt ORDER BY SN ASC";
		
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
         $i = 0;
     
         while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         	$newArray=array();
         	$newArray['SN']=$Row['SN'];
         	$newArray['Item_Code_Partno']=$Row['Item_Code_Partno'];
         	$newArray['Item_desc']=$Row['Item_desc'];
         	$newArray['tot_exciseQty']=$Row['tot_exciseQty'];
         	$newArray['tot_nonExciseQty']=$Row['tot_nonExciseQty'];
         	$newArray['issue_qty']=$Row['issue_qty'];
            $newArray['stock_qty']=$Row['stock_qty'];
            $newArray['itemid']=$Row['itemid'];
     	    $objArray[$i] =$newArray;
            $i++;
        }	
        return $objArray;
	}
	
	
	// function added on 6 APRIL 2015 to show challan details on chalan issue stock
	
	public static function GetItemExciseNonExciseChallanIssuedQtyDetails($itemid,$Code_Partno)
	{
		$Query ="SELECT ch.ChallanNo,ch.ChallanDate,bm.BuyerName,ch.ExecutiveId,chd.qty,pm.Principal_Supplier_Name FROM challan AS ch INNER JOIN challan_detail AS chd ON chd.ChallanId=ch.ChallanId 
		inner join principal_supplier_master as pm on chd.principalId = pm.Principal_Supplier_Id
		inner join buyer_master as bm on ch.BuyerID = bm.BuyerId		
		WHERE ch.challan_status IN ('1','6') AND chd.code_part_no =".$itemid;
	
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
         $i = 1;
     
         while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         	$newArray=array();
         	$newArray['SN']=$i;
         	$newArray['ChallanNo']=$Row['ChallanNo'];
         	$newArray['ChallanDate']=$Row['ChallanDate'];
         	$newArray['BuyerName']=$Row['BuyerName'];
         	$newArray['Principal_Supplier_Name']=$Row['Principal_Supplier_Name'];
         	$newArray['ExecutiveId']=$Row['ExecutiveId'];
            $newArray['qty']=$Row['qty'];
            $objArray[$i] =$newArray;
            $i++;
        }	
        return $objArray;
	}
	
	public static function countRec($SearchKey)
	{
		$opt="AND itm.Item_Code_Partno LIKE '$SearchKey%'";
		$Query ="SELECT count(*) as tot FROM (SELECT SUM(qty) AS issue_qty,chd.code_part_no AS itemid FROM challan_detail AS chd INNER JOIN challan AS ch ON chd.ChallanId=ch.ChallanId WHERE ch.challan_status IN ('1','2') GROUP BY  chd.code_part_no ) AS temp INNER JOIN inventory AS iv ON temp.itemid=iv.code_partNo ,item_master AS itm CROSS JOIN (SELECT @cnt := 0) AS dummy WHERE temp.itemid=itm.ItemId $opt";
		
		$result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($result, MYSQL_ASSOC);
    	 $tot=$Row['tot'];
    	return $tot;
 
        return $result;
	}
    public static function GetExciseStockWithValue()
    {
        //~ $Query = "select (@cnt := @cnt + 1) AS SN,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading,
//~ inv.tot_exciseQty,um.UNITNAME,im.Cost_Price
//~ ,inv.tot_exciseQty * im.Cost_Price as total_price, gm.GroupId, gm.GroupDesc ,im.ItemId
//~ from item_master as im
//~ inner join unit_master as um ON im.UnitId = um.UnitId
//~ inner join inventory as inv on im.ItemId = inv.code_partNo
//~ left join group_master as gm on im.GroupId = gm.GroupId
//~ CROSS JOIN (SELECT @cnt := 0) AS dummy
//~ where inv.tot_exciseQty  > 0 order by SN ASC;";
 $Query = "select (@cnt := @cnt + 1) AS SN,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading,
inv.tot_Qty,um.UNITNAME,im.Cost_Price
,inv.tot_Qty * im.Cost_Price as total_price, gm.GroupId, gm.GroupDesc ,im.ItemId
from item_master as im
inner join unit_master as um ON im.UnitId = um.UnitId
inner join inventory as inv on im.ItemId = inv.code_partNo
left join group_master as gm on im.GroupId = gm.GroupId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where inv.tot_Qty  > 0 order by SN ASC;";
        $result = DBConnection::SelectQuery($Query);
        return $result;
    }

    public static function GetNonExciseStockWithValue()
    {
        $Query = "select (@cnt := @cnt + 1) AS SN,im.ItemId,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading,";
        $Query =$Query."inv.tot_nonExciseQty,um.UNITNAME,im.Cost_Price, gm.GroupId, gm.GroupDesc ";
        $Query =$Query.",inv.tot_nonExciseQty * im.Cost_Price as total_price,im.ItemId ";
        $Query =$Query."from item_master as im ";
        $Query =$Query."inner join unit_master as um ON im.UnitId = um.UnitId ";
        $Query =$Query."inner join inventory as inv on im.ItemId = inv.code_partNo ";
        $Query =$Query."left join group_master as gm on im.GroupId = gm.GroupId ";
        $Query =$Query."CROSS JOIN (SELECT @cnt := 0) AS dummy ";
        $Query =$Query."where inv.tot_nonExciseQty  > 0 order by SN ASC ";
       
        $result = DBConnection::SelectQuery($Query);
    
        return $result;
    }






    public static function GetExciseSecondarySales($todate,$fromdate,$finyear,$principalid,$itemid,$salseuser,$marketsegment,$buyerid)
    {		
		
        $Query = "";
      
    // commented on 16 March 2016  due to remove auto filter
      /*  switch ($type)
        {
            case "Principal":
             /*   $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) - oie.discount as totalprice from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oie.principalID = ".$value."  order by SN ASC";  */
			/*		 $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) as totalprice,po.executiveId,ms.msname from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join market_segment as ms on ms.msid = oie.msid 
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
inner join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oie.principalID = ".$value."  order by SN ASC";
		
                break;
            case "Codepart";
                $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) as totalprice ,po.executiveId,ms.msname from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join market_segment as ms on ms.msid = oie.msid 
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
inner join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oied.codePartNo_desc = ".$value."  order by SN ASC";
                break;
            case "Salse";
                $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) as totalprice,po.executiveId,ms.msname  from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join market_segment as ms on ms.msid = oie.msid 
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND po.executiveId LIKE '%".$value."%'  order by SN ASC";
                break;
            default :
                break;
        }
        
        */
        
         $cond ='';
         $cond1 ='';
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
        
       /*
         if(!empty($finyear)) {
           $finyears = explode(',',$finyear);
            
            if(count($finyears) == 1){
				$cond1 = $cond1."oim.finyear ='".$finyears[0]."'";
			}else if(count($finyears) == 2){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ="."'".$finyears[1]."'";
			}else if(count($finyears) == 3){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."'";
			}else if(count($finyears) == 4){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."' OR oim.finyear ="."'".$finyears[3]."'";
			}
                   
        }*/
         
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
        
        
        
         /* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
oie.discount,(oied.issued_qty * pod.po_price) as totalprice,po.executiveId,oie.msid,po.bpoId,oied.codePartNo_desc,oie.principalID  from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
INNER join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by SN ASC";
        
        */
        
            
          $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,oied.oinv_price ,
((oied.oinv_price * oied.issued_qty) - oied.taxable_amt) AS DISCOUNT, oied.cgst_amt, oied.sgst_amt, oied.igst_amt, oied.total, po.executiveId,oie.msid,oie.pono,oied.codePartNo_desc,oie.principalID, psm.Principal_Supplier_Name
from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
INNER join purchaseorder as po ON oie.pono = po.bpoId
inner join purchaseorder_detail as pod ON pod.bpoId = po.bpoId AND oied.oinv_price = pod.po_price
INNER JOIN principal_supplier_master psm ON psm.Principal_Supplier_Id = oie.principalID
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oie.oinvoice_No ASC";
        
            
		//echo $Query; exit;
        $result = DBConnection::SelectQuery($Query);
        $counter = 0;
        $total = 0 ;
        $data = array();
        $i=0;
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_assoc($result))
            {
				//$po_price = 0;
				// get po_price added on 19 may 2016 to correct data integrity issue in report in case of recurring po
				//$po_price = self::getPoItemPriceForReport($row[14],$row[15],$row[16]);
				
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
				
                $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row['oinvoice_No'], 'oinv_date'=>$row['oinv_date'],'BuyerCode'=>$row['BuyerCode'], 'BuyerName'=>$row['BuyerName'],'Item_Code_Partno'=>$row['Item_Code_Partno'], 'Item_Desc'=>$row['Item_Desc'],
                    'issued_qty'=>$row['issued_qty'], 'UNITNAME'=>$row['UNITNAME'], 'po_price'=>$row['oinv_price'],'salesExecutive'=>$row['executiveId'],'totalprice'=>$row['total'],'marketsegment'=>$marketsegment,'PRINCIPAL_NAME'=>$row['Principal_Supplier_Name'], 'DISCOUNT'=>$row['DISCOUNT'], 'CGST_AMOUNT'=>$row['cgst_amt'], 'SGST_AMOUNT'=>$row['sgst_amt'], 'IGST_AMOUNT'=>$row['igst_amt'], 'DISCOUNT'=>$row['DISCOUNT']);
                $counter++;
                $total = $total + ($row[7] * $po_price);
            }
            
            $data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'',
                    'BuyerCode'=>'','BuyerName'=>'','Item_Code_Partno'=>'', 'Item_Desc'=>'',
                    'issued_qty'=>'','UNITNAME'=>'','po_price'=>'Gross Total','salesExecutive'=>'','totalprice'=>$total,'marketsegment'=>'','PRINCIPAL_NAME'=>'');
            $counter++;
	}

        return $data;
    }
    
    
    //************* function return the po price on the bsaic of poid and principal id     
      public static function getPoItemPriceForReport($poid,$itemid,$principal){		
		$Query = '';
		
		$Query = "SELECT pod.po_price FROM  purchaseorder_detail as pod  WHERE  pod.`po_codePartNo` ='".$itemid."' AND pod.po_principalId = '".$principal."' AND pod.bpoId = '".$poid."' LIMIT 1"; 
		
		//echo $Query; echo '<br>';
		$result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{ $row = mysql_fetch_row($result);
		   return $row[0]; exit;
		}else{
			return 0; exit;
		}
		
	}
    
    

    public static function GetNonExciseSecondarySales($todate,$fromdate,$finyear,$principalid,$itemid,$salseuser,$marketsegment,$buyerid)
    {
        $Query = "";
       /*
        *  switch ($type)
        {
            case "Principal":
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice,po.executiveId,ms.msname
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join market_segment as ms on ms.msid = oine.msid 
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oine.principalID = ".$value."  order by SN ASC";
                break;
            case "Codepart";
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,po.executiveId,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice,ms.msname
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join market_segment as ms on ms.msid = oine.msid 
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oied.codePartNo_desc = ".$value."  order by SN ASC";
                break;
            case "Salse";
                $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
oine.discount,(oined.issued_qty * pod.po_price) - oine.discount as totalprice,po.executiveId,ms.msname
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join market_segment as ms on ms.msid = oine.msid 
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oine.userId = '".$value."'  order by SN ASC";
                break;
            default :
                break;
        }  */
        
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
oine.discount,(oined.issued_qty * pod.po_price) as totalprice,po.executiveId,oine.msid,oine.pono,oined.codePartNo_desc,oine.principalID
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join outgoinginvoice_nonexcise_mapping AS oim ON oine.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 
inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
LEFT join purchaseorder as po ON pod.bpod_Id = po.bpoId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oine.oinvoice_No ASC";
        
        //echo $Query; exit; 
        $result = DBConnection::SelectQuery($Query);
        $counter = 0;
        $total = 0 ;
        $data = array();
        $i = 0;
	if(mysql_num_rows($result) > 0){
            while($row = mysql_fetch_row($result)){
				$po_price = 0;
				// get po_price added on 19 may 2016 to correct data integrity issue in report in case of recurring po
				$po_price = self::getPoItemPriceForReport($row[14],$row[15],$row[16]);
				
				$marketsegment = '';
				if($row[13] == 1){
					$marketsegment = 'AUTO';
				}else if($row[13] == 2){
					$marketsegment = 'GEN';
				}else if($row[13] == 3){
					$marketsegment = 'MRO';
				}else if($row[13] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
				
                $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row[1],'oinv_date'=>$row[2],
                    'BuyerCode'=>$row[3],'BuyerName'=>$row[4],'Item_Code_Partno'=>$row[5], 'Item_Desc'=>$row[6],
                    'issued_qty'=>$row[7],'UNITNAME'=>$row[8],'po_price'=>$po_price ,'discount'=>$row[10],'totalprice'=>($row[7] * $po_price ),'salesExecutive'=>$row[12],'marketsegment'=>$marketsegment);
                $counter++;
                 $total = $total + ($row[7] * $po_price);
            }
            
             $data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'',
                    'BuyerCode'=>'','BuyerName'=>'','Item_Code_Partno'=>'', 'Item_Desc'=>'',
                    'issued_qty'=>'','UNITNAME'=>'','po_price'=>'Gross Total','salesExecutive'=>'','totalprice'=>$total,'marketsegment'=>'');
            $counter++;
	}
        return $data;
    }

    public static function GetExciseProductLedger($todate,$fromdate,$principalid,$itemid,$finyear)
    {
				
        $Query = "";
       
       /* switch ($type)
        {
            case "Principal":
                $Query = "select iiex.principal_inv_no as invno , iiex.principal_inv_date as invdate,
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiexd.p_qty as incomingqty, null as outgoingqty
,txndtl.balance_qty as balanceqty
from incominginvoice_excise_detail as iiexd
inner join incominginvoice_excise as iiex on iiexd.entryId = iiex.entryId
left join `transaction` as txn on iiex.entryId = txn.refId and txn.transactionType = 'IE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiex.principalId = pm.Principal_Supplier_Id
where iiex.principalId = $value AND iiex.principal_inv_date BETWEEN '$todate' AND '$fromdate'
UNION select oiex.oinvoice_No as invno , oiex.oinv_date as invdate,
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oiexd.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty
from outgoinginvoice_excise_detail as oiexd
inner join outgoinginvoice_excise as oiex on oiexd.oinvoice_exciseID = oiex.oinvoice_exciseID
left join `transaction` as txn on oiex.oinvoice_exciseID = txn.refId and txn.transactionType = 'OE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oiex.principalID = pm.Principal_Supplier_Id
inner join buyer_master as bm on oiex.BuyerID = bm.BuyerId
where oiex.principalID = $value AND oiex.oinv_date BETWEEN '$todate' AND '$fromdate'
order by invdate";
                break;
            case "Codepart";
                /* $Query = "select iiex.principal_inv_no as invno , iiex.principal_inv_date as invdate,
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiexd.p_qty as incomingqty, null as outgoingqty
,txndtl.balance_qty as balanceqty
from incominginvoice_excise_detail as iiexd
inner join incominginvoice_excise as iiex on iiexd.entryId = iiex.entryId
left join `transaction` as txn on iiex.entryId = txn.refId and txn.transactionType = 'IE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiex.principalId = pm.Principal_Supplier_Id
where iiexd.itemID_code_partNo = $value AND iiex.principal_inv_date BETWEEN '$todate' AND '$fromdate'
UNION select oiex.oinvoice_No as invno , oiex.oinv_date as invdate,
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oiexd.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty
from outgoinginvoice_excise_detail as oiexd
inner join outgoinginvoice_excise as oiex on oiexd.oinvoice_exciseID = oiex.oinvoice_exciseID
left join `transaction` as txn on oiex.oinvoice_exciseID = txn.refId and (txn.transactionType = 'OE' OR txn.transactionType = 'Stock')
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oiex.principalID = pm.Principal_Supplier_Id
inner join buyer_master as bm on oiex.BuyerID = bm.BuyerId
where oiexd.codePartNo_desc = $value AND oiex.oinv_date BETWEEN '$todate' AND '$fromdate'
order by invdate"; */
					
	/*	$Query = "SELECT *  FROM transaction 
 WHERE transaction.transactionId IN (SELECT MAX(transaction.transactionId) FROM transaction 
LEFT JOIN transaction_detail ON transaction.transactionId = transaction_detail.transactionId
WHERE (`transactionType`='IE' OR `transactionType`='OE' OR  `transactionType`='Stock') AND transaction_detail.code_partNo=$value AND `transactionDate` BETWEEN '$todate' AND '$fromdate'  GROUP BY refId)";
            break;
            default :
                break;
        }
        
         */
        
$Query = "SELECT *  FROM transaction 
WHERE transaction.transactionId IN (SELECT MAX(transaction.transactionId) FROM transaction 
LEFT JOIN transaction_detail ON transaction.transactionId = transaction_detail.transactionId
WHERE (`transactionType`='IE' OR `transactionType`='OE' OR  `transactionType`='InNonEx' OR `transactionType`='ONE' OR  `transactionType`='Stock') AND transaction_detail.code_partNo='".$itemid."' AND `transactionDate` BETWEEN '$todate' AND '$fromdate'  GROUP BY refId)";
      
        $result = DBConnection::SelectQuery($Query);
        $counter = 1;
		// Get Stock before to date
		
		$stock = StockStatementModel::getStock($todate,$itemid,"EXcise");	
		if($stock == 0){
			$stock = StockStatementModel::getOpeningBalance($todate,$itemid,"EXcise");
		}
		$data[0] = array('SN'=>'','invno'=>'','invdate'=>'',
                    'name'=>'Opening Stock','incomingqty'=>'','outgoingqty'=>'', 'balanceqty'=>$stock,'history'=>'');
		if(mysql_num_rows($result) > 0){
			
            while($row = mysql_fetch_row($result))
            {
				$resultinvoice = self::getInvoiceDetails($row[1],$row[2],$itemid);
				 //print_r($resultinvoice); 
				 if($row[2] == 'Stock'){
					$type = 'OST';
				 }else if($row[2] == 'OE'){
				 	$type = 'OE';
				 }else if($row[2] == 'ONE'){
				 	$type = 'ON';
				 }else if($row[2] == 'InNonEx'){
				 	$type = 'INEx';
				 }else if($row[2] == 'IE'){
					$type = 'EX';
				 }
				 $history = '';
				 if($row[7] == 'UPDATE'){
					$history ='History';
				 }
				 $balance = self::getBalanceStock($row[0],$itemid,$type);
				 $data[$counter] = array('SN'=>$counter,'invno'=>$resultinvoice[0],'invdate'=>$resultinvoice[1],
                    'name'=>$resultinvoice[2],'incomingqty'=>$resultinvoice[3],'outgoingqty'=>$resultinvoice[4], 'balanceqty'=>$balance[0],'history'=>'<a href="javascript:showHistory('.$row[1].',\''.$row[2].'\','.$itemid.',\''.$resultinvoice[0].'\','.$balance[0].',\'Excise\');">'.$history.'</a>');
                $counter++; 				
            } 
		}else{
            $data[$counter] = array('SN'=>"",'invno'=>"",'invdate'=>"",'name'=>"",'incomingqty'=>"",'outgoingqty'=>"", 'balanceqty'=>"",'history'=>'');
            $counter++;
		}
        return $data;
    }
	
	//incomming invoice excise details
	public static function getInvoiceDetails($id,$type,$value){		
		$Query = '';		
		if($type=="IE"){
			$Query = "SELECT iiex.principal_inv_no AS invno, iiex.principal_inv_date AS invdate, pm.Principal_Supplier_Name AS PrincipalBuyerName, SUM( iiexd.p_qty ) AS incomingqty, NULL AS outgoingqty
			FROM incominginvoice_excise_detail AS iiexd
			INNER JOIN incominginvoice_excise AS iiex ON iiexd.entryId = iiex.entryId
			LEFT JOIN principal_supplier_master AS pm ON iiex.principalId = pm.Principal_Supplier_Id
			WHERE iiexd.entryId =$id
			AND itemID_code_partNo =$value LIMIT 1"; 
		}else if($type=="OE"){
			$Query = "SELECT oiex.oinvoice_No AS invno, oiex.oinv_date AS invdate, bm.BuyerName AS PrincipalBuyerName, NULL AS incomingqty, SUM( oiexd.issued_qty ) AS outgoingqty			
			FROM outgoinginvoice_excise_detail AS oiexd
			INNER JOIN outgoinginvoice_excise AS oiex ON oiexd.oinvoice_exciseID = oiex.oinvoice_exciseID
			INNER JOIN principal_supplier_master AS pm ON oiex.principalID = pm.Principal_Supplier_Id
			INNER JOIN buyer_master AS bm ON oiex.BuyerID = bm.BuyerId
			WHERE oiexd.oinvoice_exciseID =$id
			AND `codePartNo_desc` =$value LIMIT 1"; 
		}else if($type=="Stock"){
			$Query = "SELECT st.`stInvNo` AS invno, st.`stInvDate` AS invdate,  'Stock Transfer' AS PrincipalBuyerName, NULL AS incomingqty, SUM( std.issued_qty ) AS outgoingqty FROM stocktransfer_detail AS std
			INNER JOIN stocktransfer AS st ON std.`stId` = st.`stId` 
			WHERE std.stId =$id
			AND `st_codePartNo` =$value LIMIT 1"; 
		}		
		//echo $Query; exit;
		 $result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{
           $row = mysql_fetch_row($result);
		   return $row;
		}else{
			return 0;
		}
	
	}
	
	public static function getBalanceStock($id,$value,$type){			
		$Query = "SELECT balance_qty, code_partNo, transactiondId FROM transaction_detail
			WHERE code_partNo =  $value
			AND transactionId =$id
			AND excise_nonexcise_TAG =  '$type'
			ORDER BY transactiondId DESC LIMIT 1"; 
	 
		 $result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{
           $row = mysql_fetch_row($result);
		   return $row;
		}else{
			return 0;
		}
	
	}
	
	public static function getInvoiceHistory($id,$type,$codepart,$transactionType){
	
	/*  if($type=== "Stock" && $transactionType === "NonEx"){
		$Query = "SELECT tr.transactionDate, trd.qty, trd.balance_qty, trd.update_qty
		FROM transaction AS tr INNER JOIN transaction_detail AS trd ON tr.transactionId = trd.transactionId WHERE tr.refId =$id AND trd.code_partNo =$codepart AND debit_credit_flag ='c'";
	}else if($type == 'Stock' && $transactionType == 'Excise'){
		$Query = "SELECT tr.transactionDate, trd.qty, trd.balance_qty, trd.update_qty
		FROM transaction AS tr INNER JOIN transaction_detail AS trd ON tr.transactionId = trd.transactionId WHERE tr.refId =$id AND trd.code_partNo =$codepart AND debit_credit_flag ='d' ";
	}else{ */
		$Query = "SELECT tr.transactionDate, trd.qty, trd.balance_qty, trd.update_qty
		FROM transaction AS tr INNER JOIN transaction_detail AS trd ON tr.transactionId = trd.transactionId WHERE tr.refId =$id AND trd.code_partNo =$codepart ";
	 //} 
	// echo $Query; exit;
	 $result = DBConnection::SelectQuery($Query);  
	    $objArray = array();
		$i = 0;		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['date'] = $Row['transactionDate'];
			  $objArray[$i]['qty'] = $Row['qty'];
			  $objArray[$i]['balance_qty'] = $Row['balance_qty'];
			  $objArray[$i]['update_qty'] = $Row['update_qty'];
			
			  $i++;
		}		
		return $objArray;
	  
	}
	public static function getStock($todate,$value,$type){
		
		$Query = '';
		/* if($type=="EXcise"){
			$Query = "select tds.balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 inner join transaction AS td ON td.transactionId = tds.transactionId 
		 WHERE tds.code_partNo ='$value' AND (tds.excise_nonexcise_TAG ='OE' OR tds.excise_nonexcise_TAG = 'EX' OR tds.excise_nonexcise_TAG = 'OST' ) AND  td.transactionDate < '$todate' order by tds.transactiondId DESC LIMIT 1"; 
		}else if($type=="NONEXcise"){
			$Query = "select tds.balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 inner join transaction AS td ON td.transactionId = tds.transactionId 
		 WHERE tds.code_partNo ='$value' AND (tds.excise_nonexcise_TAG = 'INEx' OR tds.excise_nonexcise_TAG = 'ON' OR tds.excise_nonexcise_TAG = 'IST') AND  td.transactionDate < '$todate' order by tds.transactiondId DESC LIMIT 1"; 
		} */
		$Query = "select tds.balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 inner join transaction AS td ON td.transactionId = tds.transactionId 
		 WHERE tds.code_partNo ='$value' AND (tds.excise_nonexcise_TAG ='OE' OR tds.excise_nonexcise_TAG = 'EX' OR tds.excise_nonexcise_TAG = 'OST' OR tds.excise_nonexcise_TAG = 'INEx' OR tds.excise_nonexcise_TAG = 'ON' OR tds.excise_nonexcise_TAG = 'IST' ) AND  td.transactionDate < '$todate' order by tds.transactiondId DESC LIMIT 1"; 
		//echo $Query; echo '<br>';
		$result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{
           $row = mysql_fetch_row($result);
		   return $row[0];
		}else{
			return 0;
		}
	
	}
	
	public static function getOpeningBalance($todate,$value,$type){
		
		$Query = "SELECT openingQty FROM  `openinginventory` WHERE  `code_partNo` =$value LIMIT 1"; 
		//~ if($type=="EXcise"){
			//~ $Query = "SELECT openingExciseQty FROM  `openinginventory` WHERE  `code_partNo` =$value LIMIT 1"; 
		//~ }else if($type=="NONEXcise"){
				//~ $Query = "SELECT openingNonExciseQty FROM  `openinginventory` WHERE  `code_partNo` =$value LIMIT 1";  
		//~ }
		//echo $Query; echo '<br>';
		$result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{
           $row = mysql_fetch_row($result);
		   return $row[0];
		}else{
			return 0;
		}
	
	}
	
	
    public static function GetNonExciseProductLedger($todate,$fromdate,$principalid,$itemid,$finyear)
    {
        $Query = "";
     /*   switch ($type)
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
				where iiwe.principalID = $value AND iiwe.principal_inv_date BETWEEN '$todate' AND '$fromdate'
				UNION select oine.oinvoice_No as invno , oine.oinv_date as invdate,
				bm.BuyerName as PrincipalBuyerName, null as incomingqty, oined.issued_qty as outgoingqty,
				txndtl.balance_qty as balanceqty
				from outgoinginvoice_nonexcise_detail as oined
				inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
				left join `transaction` as txn on oine.oinvoice_nexciseID = txn.refId and txn.transactionType = 'ONE'
				left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
				inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id
				inner join buyer_master as bm on oine.BuyerID = bm.BuyerId
				where oine.principalID = $value AND oine.oinv_date BETWEEN '$todate' AND '$fromdate'
				order by invdate";
                break;
            case "Codepart";
               /*  $Query = "select iiwe.incoming_inv_no_p as invno , iiwe.principal_inv_date as invdate,
pm.Principal_Supplier_Name as PrincipalBuyerName ,iiwed.qty as incomingqty, null as outgoingqty,
txndtl.balance_qty as balanceqty
from incominginvoice_without_excise_detail as iiwed
inner join incominginvoice_without_excise as iiwe on iiwed.incominginvoice_we = iiwe.incominginvoice_we
left join `transaction` as txn on iiwe.incominginvoice_we = txn.refId and txn.transactionType = 'InNonEx'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id
where iiwed.itemID_code_partNo = $value AND iiwe.principal_inv_date BETWEEN '$todate' AND '$fromdate'
UNION select oine.oinvoice_No as invno , oine.oinv_date as invdate,
bm.BuyerName as PrincipalBuyerName, null as incomingqty, oined.issued_qty as outgoingqty,
txndtl.balance_qty as balanceqty
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
left join `transaction` as txn on oine.oinvoice_nexciseID = txn.refId and txn.transactionType = 'ONE'
left join transaction_detail as txndtl on txn.transactionId = txndtl.transactionId
inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id
inner join buyer_master as bm on oine.BuyerID = bm.BuyerId
where oined.codePartNo_desc = $value AND oine.oinv_date BETWEEN '$todate' AND '$fromdate'
order by invdate"; */
		/*	$Query = "SELECT *  FROM transaction 
				WHERE transaction.transactionId IN (SELECT MAX(transaction.transactionId) FROM transaction 
				LEFT JOIN transaction_detail ON transaction.transactionId = transaction_detail.transactionId
				WHERE (`transactionType`='InNonEx' OR `transactionType`='ONE' OR  `transactionType`='Stock') AND transaction_detail.code_partNo=$value AND `transactionDate` BETWEEN '$todate' AND '$fromdate'  GROUP BY refId)";
                break;
            default :
                break;
        }
        
        */
        
        
        	$Query = "SELECT *  FROM transaction 
				WHERE transaction.transactionId IN (SELECT MAX(transaction.transactionId) FROM transaction 
				LEFT JOIN transaction_detail ON transaction.transactionId = transaction_detail.transactionId
				WHERE (`transactionType`='InNonEx' OR `transactionType`='ONE' OR  `transactionType`='Stock') AND transaction_detail.code_partNo='".$itemid."' AND `transactionDate` BETWEEN '$todate' AND '$fromdate'  GROUP BY refId)";
		
		// echo $Query; exit;
        $result = DBConnection::SelectQuery($Query);
        $counter = 1;
		// Get Stock before to date		
		$stock = StockStatementModel::getStock($todate,$itemid,"NONEXcise");	
		if($stock == 0){
			$stock = StockStatementModel::getOpeningBalance($todate,$itemid,"NONEXcise");
		}
		//echo $stock; exit;
		$data[0] = array('SN'=>'','invno'=>'','invdate'=>'',
                    'name'=>'Opening Stock','incomingqty'=>'','outgoingqty'=>'', 'balanceqty'=>$stock,'history'=>'');
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_row($result))
            {
				//print_r($row); exit;
				$resultinvoice = self::getInvoiceNonExciseDetails($row[1],$row[2],$itemid);
				// print_r($resultinvoice); 
				 if($row[2] == 'Stock'){
					$type = 'IST';
				 }else if($row[2] == 'OE'){
				 	$type = 'OE';
				 }else if($row[2] == 'ONE'){
				 	$type = 'ON';
				 }else if($row[2] == 'InNonEx'){
				 	$type = 'INEx';
				 }else if($row[2] == 'IE'){
					$type = 'EX';
				 }
				 $history = '';
				 if($row[7] == 'UPDATE'){
					$history ='History';
				 }
				 $balance = self::getBalanceStock($row[0],$itemid,$type);
				// print_r($balance);
                $data[$counter] = array('SN'=>$counter,'invno'=>$resultinvoice[0],'invdate'=>$resultinvoice[1],
                    'name'=>$resultinvoice[2],'incomingqty'=>$resultinvoice[3],'outgoingqty'=>$resultinvoice[4], 'balanceqty'=>$balance[0],'history'=>'<a href="javascript:showHistory('.$row[1].',\''.$row[2].'\','.$itemid.',\''.$resultinvoice[0].'\','.$balance[0].',\'NonEx\');">'.$history.'</a>');
                $counter++; 
				
            } 
	}else{
            $data[$counter] = array('SN'=>"",'invno'=>"",'invdate'=>"",'name'=>"",'incomingqty'=>"",'outgoingqty'=>"", 'balanceqty'=>"",'history'=>'');
            $counter++;
	}

        return $data;		
        
    }
	
	public static function getInvoiceNonExciseDetails($id,$type,$value){
		
		$Query = '';
		
		if($type=="InNonEx"){
			$Query = "SELECT iiwe.incoming_inv_no_p AS invno, iiwe.principal_inv_date AS invdate, pm.Principal_Supplier_Name AS PrincipalBuyerName, SUM(iiwed.qty) AS incomingqty, NULL AS outgoingqty
			FROM incominginvoice_without_excise_detail AS iiwed
			INNER JOIN incominginvoice_without_excise AS iiwe ON iiwed.incominginvoice_we = iiwe.incominginvoice_we
			INNER JOIN principal_supplier_master AS pm ON iiwe.principalID = pm.Principal_Supplier_Id
			WHERE iiwed.incominginvoice_we = $id
			AND iiwed.itemID_code_partNo =$value LIMIT 1"; 
		}else if($type=="ONE"){
			$Query = "SELECT oine.oinvoice_No AS invno, oine.oinv_date AS invdate, bm.BuyerName AS PrincipalBuyerName, NULL AS incomingqty, SUM( oined.issued_qty ) AS outgoingqty
			FROM outgoinginvoice_nonexcise_detail AS oined
			INNER JOIN outgoinginvoice_nonexcise AS oine ON oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
			INNER JOIN principal_supplier_master AS pm ON oine.principalID = pm.Principal_Supplier_Id
			INNER JOIN buyer_master AS bm ON oine.BuyerID = bm.BuyerId
			WHERE oined.oinvoice_nexciseID =$id
			AND oined.`codePartNo_desc` =$value LIMIT 1"; 
		}else if($type=="Stock"){
			$Query = "SELECT st.`stInvNo` AS invno, st.`stInvDate` AS invdate,  'Stock Transfer' AS PrincipalBuyerName, SUM( std.issued_qty ) AS incomingqty, NULL AS outgoingqty FROM stocktransfer_detail AS std
			INNER JOIN stocktransfer AS st ON std.`stId` = st.`stId` 
			WHERE std.stId =$id
			AND `st_codePartNo` =$value LIMIT 1"; 
		}		
		
		 $result = DBConnection::SelectQuery($Query);     
		if(mysql_num_rows($result) > 0)
		{
           $row = mysql_fetch_row($result);
		   return $row;
		}else{
			return 0;
		}
	
	}
	
    public static function GetSalesTaxReturnExcise($todate,$fromdate,$buyerid,$invoiceno)
    {
        $Query = "select (@cnt := @cnt + 1) AS SN, oiex.oinvoice_No, oiex.oinv_date ,bm.BuyerName, bm.TIN,
(oiex.bill_value - oiex.freight_amount - oiex.saleTax ) as taxable_amount,oiex.saleTax
from outgoinginvoice_excise as oiex
inner join buyer_master as bm ON oiex.BuyerID = bm.BuyerId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where ";

        if($buyerid != "" && $buyerid > 0)
        {
            $Query = $Query."oiex.BuyerID = ".$buyerid." AND ";
        }
        else if($invoiceno != "") {
            $Query = $Query." oiex.oinvoice_No = '".$invoiceno."' AND ";
        }
        $Query = $Query." oiex.insertDate BETWEEN '".$todate."' AND '".$fromdate."' order by SN ASC";

        //echo $Query;
        $result = DBConnection::SelectQuery($Query);
        $counter = 0;
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_row($result))
            {
                $data[$counter] = array('SN'=>$row[0],'invno'=>$row[1],'invdate'=>$row[2],
                    'buyername'=>$row[3],'tin_vat_no'=>$row[4],'taxable_amount'=>$row[5], 'tax_amount'=>$row[6]);
                $counter++;
            }
	}
	else
	{
            $data[$counter] = array('SN'=>"",'invno'=>"",'invdate'=>"",
                    'buyername'=>"",'tin_vat_no'=>"",'taxable_amount'=>"", 'tax_amount'=>"");
            $counter++;
	}

        return $data;
    }
    public static function GetSalesTaxReturnNonExcise($todate,$fromdate,$buyerid,$invoiceno)
    {
        $Query = "select (@cnt := @cnt + 1) AS SN, oinex.oinvoice_No, oinex.oinv_date ,bm.BuyerName, bm.TIN,
(oinex.bill_value - oinex.freight_amount - oinex.po_saleTax ) as taxable_amount,oinex.po_saleTax
from outgoinginvoice_nonexcise as oinex
inner join buyer_master as bm ON oinex.BuyerID = bm.BuyerId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where ";
        if($buyerid != "")
        {
            $Query = $Query."oinex.BuyerID = ".$value." AND ";
        }
        else if($invoiceno != "") {
            $Query = $Query." oiex.oinvoice_No = '".$value."' AND ";
        }
        $Query = $Query." oinex.insertDate BETWEEN '".$todate."' AND '".$fromdate."' order by SN ASC";

        //echo $Query;
        $result = DBConnection::SelectQuery($Query);
        $counter = 0;
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_row($result))
            {
                $data[$counter] = array('SN'=>$row[0],'invno'=>$row[1],'invdate'=>$row[2],
                    'buyername'=>$row[3],'tin_vat_no'=>$row[4],'taxable_amount'=>$row[5], 'tax_amount'=>$row[6]);
                $counter++;
            }
	}
	else
	{
            $data[$counter] = array('SN'=>"",'invno'=>"",'invdate'=>"",
                    'buyername'=>"",'tin_vat_no'=>"",'taxable_amount'=>"", 'tax_amount'=>"");
            $counter++;
	}

        return $data;
    }

/*     public static function GetExciseReturn($type,$todate,$fromdate)
    {
        $Query = "";
        switch ($type)
        {
            case "INCOMING":
                $Query = "select iem.display_EntryNo,ie.insertDate,ie.userId ,pm.ECC_CODENO, pm.Principal_Supplier_Name,
CONCAT(pm.ADD1,' ' , pm.ADD2) as address,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,ied.p_qty,
(ied.ed_amt + ied.edu_amt + ied.hedu_amount + ied.cvd_amt ) as duty
from incominginvoice_excise_detail as ied
inner join incominginvoice_excise as ie on ied.entryId = ie.entryId
inner join incominginvoice_entryno_mapping as iem on iem.inner_EntryNo = ie.entryId
inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = ie.principalId
inner join item_master as im on im.ItemId = ied.itemID_code_partNo
inner join group_master as gm on gm.GroupId = im.GroupId
inner join unit_master as um on um.UnitId = im.UnitId
WHERE ie.insertDate >= '$todate' AND ie.insertDate <= '$fromdate'
ORDER BY iem.display_EntryNo ASC;";
                //echo $Query;
                $result = DBConnection::SelectQuery($Query);
                $counter = 0;
                if(mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $data[$counter] = array('invno'=>$row[0],'invdate'=>$row[1],'emp'=>$row[2],
                            'ecccode'=>$row[3],'princiname'=>$row[4],'princiadd'=>$row[5],
                            'groupdesc'=>$row[6],'tarrifheading'=>$row[7],'unitname'=>$row[8],
                            'quantity'=>$row[9],'duty'=>$row[10]);
                        $counter++;
                    }
                }
                else
                {
                    $data[$counter] = array('invno'=>"",'invdate'=>"",'emp'=>"",'ecccode'=>"",
                        'princiname'=>"",'princiadd'=>"",'groupdesc'=>"",'tarrifheading'=>"",
                        'unitname'=>"",'quantity'=>"",'duty'=>"");
                    $counter++;
                }

                return $data;
                break;
            case "OUTGOING":
                $Query = "select oe.oinvoice_No ,oe.insertDate,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,oed.issued_qty ,
( oed.ed_amt + oed.edu_amt + oed.hedu_amount + oed.cvd_amt ) as duty
from outgoinginvoice_excise_detail as oed
inner join outgoinginvoice_excise as oe on oe.oinvoice_exciseID = oed.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oem on oe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx
inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = oe.principalID
inner join item_master as im on im.ItemId = oed.codePartNo_desc
inner join group_master as gm on gm.GroupId = im.GroupId
inner join unit_master as um on um.UnitId = im.UnitId
WHERE oe.insertDate >= '$todate' AND oe.insertDate <= '$fromdate'
ORDER BY oe.oinvoice_No ASC;";
                $result = DBConnection::SelectQuery($Query);
                $counter = 0;
                if(mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $data[$counter] = array('invno'=>$row[0],'invdate'=>$row[1],
                            'groupdesc'=>$row[2],'tarrifheading'=>$row[3],
                        'unitname'=>$row[4],'quantity'=>$row[5],'duty'=>$row[6]);
                        $counter++;
                    }
                }
                else
                {
                    $data[$counter] = array('invno'=>"",'invdate'=>"",
                            'groupdesc'=>"",'tarrifheading'=>"",
                        'unitname'=>"",'quantity'=>"",'duty'=>"");
                    $counter++;
                }

                return $data;
                break;
            default :
                break;
        }
    } */
	
	  public static function GetExciseReturn($type,$todate,$fromdate)
    {
        $Query = "";
        switch ($type)
        {
            case "INCOMING":
			//commented by codefire 14-7-15
               /*  $Query = "select iem.display_EntryNo,ie.insertDate,ie.userId ,pm.ECC_CODENO, pm.Principal_Supplier_Name,
				CONCAT(pm.ADD1,' ' , pm.ADD2) as address,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,ied.p_qty,
				(ied.ed_amt + ied.edu_amt + ied.hedu_amount + ied.cvd_amt ) as duty
				from incominginvoice_excise_detail as ied
				inner join incominginvoice_excise as ie on ied.entryId = ie.entryId
				inner join incominginvoice_entryno_mapping as iem on iem.inner_EntryNo = ie.entryId
				inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = ie.principalId
				inner join item_master as im on im.ItemId = ied.itemID_code_partNo
				inner join group_master as gm on gm.GroupId = im.GroupId
				inner join unit_master as um on um.UnitId = im.UnitId
				WHERE ie.insertDate >= '$todate' AND ie.insertDate <= '$fromdate'
				ORDER BY iem.display_EntryNo ASC;"; */
				//Added by codefire 14-7-15
				 $Query = "select iem.display_EntryNo,ie.principal_inv_date,ie.userId ,pm.ECC_CODENO, pm.Principal_Supplier_Name,
				CONCAT(pm.ADD1,'', pm.ADD2) as address,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,ied.p_qty,
				(ied.ed_amt + ied.edu_amt + ied.hedu_amount + ied.cvd_amt) as duty,ie.supplierId,ie.principal_inv_no
				from incominginvoice_excise_detail as ied
				inner join incominginvoice_excise as ie on ied.entryId = ie.entryId
				inner join incominginvoice_entryno_mapping as iem on iem.inner_EntryNo = ie.entryId
				inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = ie.principalId
				inner join item_master as im on im.ItemId = ied.itemID_code_partNo
				inner join group_master as gm on gm.GroupId = im.GroupId
				inner join unit_master as um on um.UnitId = im.UnitId
				WHERE ie.insertDate >= '$todate' AND ie.insertDate <= '$fromdate'
				ORDER BY iem.display_EntryNo ASC;";
                $result = DBConnection::SelectQuery($Query);
                $counter = 0;
				$i=1;
                if(mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_row($result))
                    {	
							$issuer='';
							if($row[11] == NULL){
								$issuer='Manufacturer';
							}else{
							 $issuer='Supplier';
							}

                        $data[$counter] = array('SN'=>$i,'invno'=>$row[12],'invdate'=>$row[1],'emp'=>"$issuer",
                            'ecccode'=>$row[3],'princiname'=>$row[4],'princiadd'=>$row[5],
                            'groupdesc'=>$row[6],'tarrifheading'=>$row[7],'unitname'=>$row[8],
                            'quantity'=>$row[9],'duty'=>$row[10]);
                        $counter++;
						$i++;
                    }
                }
                else
                {
                    $data[$counter] = array('SN','invno'=>"",'invdate'=>"",'emp'=>"",'ecccode'=>"",
                        'princiname'=>"",'princiadd'=>"",'groupdesc'=>"",'tarrifheading'=>"",
                        'unitname'=>"",'quantity'=>"",'duty'=>"");
                    $counter++;
                }

                return $data;
                break;
            case "OUTGOING":
			
			//commented by codefire 14-7-15
            /* $Query = "select oe.oinvoice_No ,oe.insertDate,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,oed.issued_qty ,
				( oed.ed_amt + oed.edu_amt + oed.hedu_amount + oed.cvd_amt ) as duty
				from outgoinginvoice_excise_detail as oed
				inner join outgoinginvoice_excise as oe on oe.oinvoice_exciseID = oed.oinvoice_exciseID
				inner join outgoinginvoice_excise_mapping as oem on oe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx
				inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = oe.principalID
				inner join item_master as im on im.ItemId = oed.codePartNo_desc
				inner join group_master as gm on gm.GroupId = im.GroupId
				inner join unit_master as um on um.UnitId = im.UnitId
				WHERE oe.insertDate >= '$todate' AND oe.insertDate <= '$fromdate'
				ORDER BY oe.oinvoice_No ASC;"; */
				//Added by codefire 14-7-15
				$Query = "select oe.oinvoice_No ,oe.insertDate,gm.GroupDesc,im.Tarrif_Heading,um.UNITNAME,oed.issued_qty ,
				( oed.ed_amt + oed.edu_amt + oed.hedu_amount + oed.cvd_amt) as duty
				from outgoinginvoice_excise_detail as oed
				inner join outgoinginvoice_excise as oe on oe.oinvoice_exciseID = oed.oinvoice_exciseID
				inner join outgoinginvoice_excise_mapping as oem on oe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx
				inner join principal_supplier_master as pm on pm.Principal_Supplier_Id = oe.principalID
				inner join item_master as im on im.ItemId = oed.codePartNo_desc
				inner join group_master as gm on gm.GroupId = im.GroupId
				inner join unit_master as um on um.UnitId = im.UnitId
				WHERE oe.insertDate >= '$todate' AND oe.insertDate <= '$fromdate'
				ORDER BY oe.oinvoice_No ASC;";
				//echo $Query; exit;
                $result = DBConnection::SelectQuery($Query);
                $counter = 0;
				$i=1;
                if(mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $data[$counter] = array('SN'=>$i,'invno'=>$row[0],'invdate'=>$row[1],
                            'groupdesc'=>$row[2],'tarrifheading'=>$row[3],
                        'unitname'=>$row[4],'quantity'=>$row[5],'duty'=>$row[6]);
                        $counter++;
						$i++;
                    }
                }
                else
                {
                    $data[$counter] = array('SN','invno'=>"",'invdate'=>"",
                            'groupdesc'=>"",'tarrifheading'=>"",
                        'unitname'=>"",'quantity'=>"",'duty'=>"");
                    $counter++;
                }

                return $data;
                break;
			
			 case "STOCKTRANSFER":
			
			//Created by codefire 1-10-15
				$Query ="SELECT st.stId,st.stInvNo,st.stInvDate,stmd.issued_qty,(stmd.ed_amt +stmd.cvd_amt+(((stmd.ed_amt)*(stmd.edu_percent))/100)) as duty,um.UNITNAME,im.Tarrif_Heading,gm.GroupDesc 
				FROM stocktransfer as st 
				inner join stocktransfer_detail as stmd ON stmd.stId = st.stId
				inner join item_master as im on im.ItemId = stmd.st_codePartNo
				inner join group_master as gm on gm.GroupId = im.GroupId
				inner join unit_master as um on um.UnitId = im.UnitId
				inner join stocktransfer_mapping AS stm ON stm.inner_stId=st.stId
				WHERE stm.finyear='".MultiweldParameter::GetFinancialYear_fromTXT()."' AND st.stInvDate >= '$todate' AND st.stInvDate <= '$fromdate' ORDER BY stm.display_stId ASC;";	
				
			
                $result = DBConnection::SelectQuery($Query);
                $counter = 0;
				$i=1;
                if(mysql_num_rows($result) > 0)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $data[$counter] = array('SN'=>$i,'invno'=>$row[1],'invdate'=>$row[2],
                         'groupdesc'=>$row[7],'tarrifheading'=>$row[6],
                        'unitname'=>$row[5],'quantity'=>$row[3],'duty'=>number_format($row[4]));
                        $counter++;
						$i++;
                    }
                }
                else
                {
                    $data[$counter] = array('SN','invno'=>"",'invdate'=>"",
                            'groupdesc'=>"",'tarrifheading'=>"",
                        'unitname'=>"",'quantity'=>"",'duty'=>"");
                    $counter++;
                }

                return $data;
                break;
			
            default :
                break;
        }
    }
	
	public static function GetStockTransferExciseReturn($type,$todate,$fromdate,$finyear)
    {
			$Query = "";			
			$finyrs ='';
			if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'stm.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR stm.finyear = "'.$year.'"';
					}					 
				}				
			} 
			
       		//Created by codefire 1-10-15
			$Query ="SELECT st.stId,st.stInvNo,st.stInvDate,stmd.issued_qty,(stmd.ed_amt +stmd.cvd_amt+(((stmd.ed_amt)*(stmd.edu_percent))/100)) as duty,um.UNITNAME,im.Tarrif_Heading,gm.GroupDesc 
			FROM stocktransfer as st 
			inner join stocktransfer_detail as stmd ON stmd.stId = st.stId
			inner join item_master as im on im.ItemId = stmd.st_codePartNo
			inner join group_master as gm on gm.GroupId = im.GroupId
			inner join unit_master as um on um.UnitId = im.UnitId
			inner join stocktransfer_mapping AS stm ON stm.inner_stId=st.stId
			WHERE ($finyrs) AND st.stInvDate >= '$todate' AND st.stInvDate <= '$fromdate' ORDER BY stm.display_stId ASC;";	
			$result = DBConnection::SelectQuery($Query);
			$counter = 0;
			$total = 0;
			$total_qty = 0;
			$i=1;
			if(mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_row($result))
				{
					$data[$counter] = array('SN'=>$i,'invno'=>$row[1],'invdate'=>$row[2],
					 'groupdesc'=>$row[7],'tarrifheading'=>$row[6],
					'unitname'=>$row[5],'quantity'=>$row[3],'duty'=>number_format((float)$row[4], 2, '.', ''));
					$counter++;
					$i++;
					$total = $total + $row[4];
					$total_qty = $total_qty + $row[3];
				}
			}else{
				$data[$counter] = array('SN','invno'=>"",'invdate'=>"",
						'groupdesc'=>"",'tarrifheading'=>"",
					'unitname'=>"",'quantity'=>"",'duty'=>"");
				$counter++;
			}
			
			$data[$counter] = array('SN'=>"",'invno'=>"Gross Total",'invdate'=>"",
						'groupdesc'=>"",'tarrifheading'=>"",
					'unitname'=>"",'quantity'=>number_format((float)$total_qty, 2, '.', ''),'duty'=>number_format((float)$total, 2, '.', ''));
			
			return $data;
			break;		
    }



    public static function GetExciseStock($Groupid)
    {
        /* $Query = "select (@cnt := @cnt + 1) AS SN,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading,
inv.tot_exciseQty,um.UNITNAME,im.Cost_Price
,inv.tot_exciseQty * im.Cost_Price as total_price, gm.GroupId, gm.GroupDesc,im.ItemId
from item_master as im
inner join unit_master as um ON im.UnitId = um.UnitId
inner join inventory as inv on im.ItemId = inv.code_partNo
left join group_master as gm on im.GroupId = gm.GroupId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where inv.tot_exciseQty  > 0 AND im.GroupId = $Groupid order by SN ASC;"; */
		$Query = "select (@cnt := @cnt + 1) AS SN,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading, inv.tot_Qty, um.UNITNAME, im.Cost_Price, inv.tot_Qty * im.Cost_Price as total_price, gm.GroupId, gm.GroupDesc, im.ItemId from item_master as im inner join unit_master as um ON im.UnitId = um.UnitId inner join inventory as inv on im.ItemId = inv.code_partNo left join group_master as gm on im.GroupId = gm.GroupId CROSS JOIN (SELECT @cnt := 0) AS dummy where inv.tot_Qty  > 0 AND im.GroupId = $Groupid order by SN ASC;";
        $result = DBConnection::SelectQuery($Query);
        return $result;
    }

    public static function GetNonExciseStock($Groupid)
    {
        $Query = "select (@cnt := @cnt + 1) AS SN,im.Item_Code_Partno, im.Item_Desc, im.Tarrif_Heading,
inv.tot_nonExciseQty ,um.UNITNAME,im.Cost_Price
,inv.tot_nonExciseQty * im.Cost_Price as total_price, gm.GroupId, gm.GroupDesc,im.ItemId
from item_master as im
inner join unit_master as um ON im.UnitId = um.UnitId
inner join inventory as inv on im.ItemId = inv.code_partNo
left join group_master as gm on im.GroupId = gm.GroupId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where inv.tot_nonExciseQty  > 0 AND im.GroupId = $Groupid order by SN ASC;";
        $result = DBConnection::SelectQuery($Query);
        return $result;
    }

     public static function GetExciseSecondarySalesStatement($principalid,$todate,$fromdate,$marketsegment,$finyear,$buyerid)
    {
		
		// commented on 17-12-2015 due to price taking from purchase order in plase of related incomming invoice
        /* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
(oied.issued_qty * pod.po_price) as totalprice from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' AND oie.principalID = ".$principalid."  order by SN ASC;"; */
        
        $cond ='';
         $cond1 ='';
        if(!empty($principalid))
        {
            $cond = $cond."AND oie.principalID = '".$principalid."'";
          
        }
        if(!empty($buyerid))
        {
            $cond = $cond."AND oie.BuyerID = '".$buyerid."'";
          
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
        
		/* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,iod.basic_purchase_price ,
(oied.issued_qty * iod.basic_purchase_price) as totalprice,oie.msid,oim.finyear,oie.pono,oied.codePartNo_desc,oie.principalID from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oie.oinvoice_No ASC"; */

		/* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,iod.basic_purchase_price ,
(oied.issued_qty * iod.basic_purchase_price) as totalprice,oie.msid,oim.finyear,oie.pono,oied.codePartNo_desc,oie.principalID, psm.Principal_Supplier_Name from outgoinginvoice_excise_detail as oied
LEFT join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
LEFT join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
LEFT join buyer_master as bm ON oie.BuyerID = bm.BuyerId
LEFT join item_master as im on im.ItemId = oied.codePartNo_desc
LEFT join unit_master as um ON im.UnitId = um.UnitId
LEFT join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
LEFT JOIN principal_supplier_master psm ON psm.Principal_Supplier_Id = oie.principalID
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oie.oinvoice_No ASC"; */
		/* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,IF(iod.basic_purchase_price IS NULL,IF((SELECT COUNT(iiedo.landing_price) FROM incominginvoice_excise_detail_old iiedo WHERE iiedo.entryDId = oied.iinv_no) = 0,(SELECT iinedo.landing_price FROM incominginvoice_without_excise_detail_old AS iinedo INNER JOIN incominginvoice_without_excise_old AS iineo ON iinedo.incominginvoice_we = iineo.incominginvoice_we WHERE iineo.principalID = oie.principalID AND iinedo.itemID_code_partNo = oied.codePartNo_desc ORDER BY iinedo.incominginvoice_we DESC LIMIT 1),(SELECT iiedo.landing_price FROM incominginvoice_excise_detail_old iiedo WHERE iiedo.entryDId = oied.iinv_no)), iod.landing_price) AS BASIC_PRICE ,oie.msid,oim.finyear,oie.pono,oied.codePartNo_desc,oie.principalID, psm.Principal_Supplier_Name from outgoinginvoice_excise_detail as oied
LEFT join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
LEFT join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
LEFT join buyer_master as bm ON oie.BuyerID = bm.BuyerId
LEFT join item_master as im on im.ItemId = oied.codePartNo_desc
LEFT join unit_master as um ON im.UnitId = um.UnitId
LEFT join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
LEFT JOIN principal_supplier_master psm ON psm.Principal_Supplier_Id = oie.principalID
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oie.oinvoice_No ASC"; */

$Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,iod.basic_purchase_price ,
(oied.issued_qty * iod.basic_purchase_price) as totalprice,oie.msid,oim.finyear,oie.pono,oied.codePartNo_desc,oie.principalID, psm.Principal_Supplier_Name from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
INNER JOIN principal_supplier_master psm ON psm.Principal_Supplier_Id = oie.principalID
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oie.oinvoice_No ASC";
		
		//echo $Query; exit;
		
		$result = DBConnection::SelectQuery($Query);
        $counter = 0;
        $total = 0;
        $data = array();
        $i = 0;
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_assoc($result))
            {
				//$po_price = 0;
				// get po_price added on 19 may 2016 to correct data integrity issue in report in case of recurring po
				//$po_price = self::getPoItemPriceForReport($row[13],$row[14],$row[15]);
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
                $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row['oinvoice_No'],'oinv_date'=>$row['oinv_date'], 'BuyerCode'=>$row['BuyerCode'],'BuyerName'=>$row['BuyerName'],'Item_Code_Partno'=>$row['Item_Code_Partno'], 'Item_Desc'=>$row['Item_Desc'],
                    'issued_qty'=>$row['issued_qty'],'UNITNAME'=>$row['UNITNAME'],'po_price'=>$row['BASIC_PRICE'],'totalprice'=>($row['BASIC_PRICE'] * $row['issued_qty']),'marketsegment'=>$marketsegment, 'PRINCIPAL_NAME'=>$row['Principal_Supplier_Name']);
                $counter++;
                $total = $total + ($row['BASIC_PRICE'] * $row['issued_qty']);
            }
            
             $data[$counter] = array('SN'=>$row[0],'oinvoice_No'=>'','oinv_date'=>'',
                    'BuyerCode'=>'','BuyerName'=>'','Item_Code_Partno'=>'', 'Item_Desc'=>'',
                    'issued_qty'=>'','UNITNAME'=>'','po_price'=>'Gross Total','totalprice'=>$total,'marketsegment'=>'', 'PRINCIPAL_NAME'=>'');
              $counter++;
	}
        return $data;
    }
    
    
    public static function GetNonExciseSecondarySalesStatement($principalid,$todate,$fromdate,$marketsegment,$finyear,$buyerid)
    {
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
        
        if(!empty($marketsegment)) {
            $cond = $cond."AND oine.msid = '".$marketsegment."'";
        }
        
        if(!empty($finyear)) {
           $finyears = explode(',',$finyear);
            
            if(count($finyears) == 1){
				$cond1 = $cond1."oim.finyear ='".$finyears[0]."'";
			}else if(count($finyears) == 2){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ="."'".$finyears[1]."'";
			}else if(count($finyears) == 3){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."'";
			}else if(count($finyears) == 4){
				$cond1 = $cond1."oim.finyear='".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."' OR oim.finyear ="."'".$finyears[3]."'";
			}
                   
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
         
        //print_r($finyrs);exit;
          
          
         
        
        $Query = "select (@cnt := @cnt + 1) AS SN, oine.oinvoice_No , oine.oinv_date , bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oined.issued_qty ,um.UNITNAME ,pod.po_price ,
(oined.issued_qty * pod.po_price) as totalprice,oine.msid,oine.pono,oined.codePartNo_desc,oine.principalID
from outgoinginvoice_nonexcise_detail as oined
inner join outgoinginvoice_nonexcise as oine on oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
inner join outgoinginvoice_nonexcise_mapping AS oim ON oine.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 

inner join buyer_master as bm ON oine.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oined.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oined.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oine.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by oine.oinvoice_No ASC";
  
        
        $result = DBConnection::SelectQuery($Query);
        $counter = 0;
        $total = 0;
        $data = array();
        $i = 0 ;
	if(mysql_num_rows($result) > 0)
	{
            while($row = mysql_fetch_row($result))
            {
				
				$po_price = 0;
				// get po_price added on 19 may 2016 to correct data integrity issue in report in case of recurring po
				$po_price = self::getPoItemPriceForReport($row[12],$row[13],$row[14]);
				
				$marketsegment = '';
				if($row[11] == 1){
					$marketsegment = 'AUTO';
				}else if($row[11] == 2){
					$marketsegment = 'GEN';
				}else if($row[11] == 3){
					$marketsegment = 'MRO';
				}else if($row[11] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
                $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row[1],'oinv_date'=>$row[2],
                    'BuyerCode'=>$row[3],'BuyerName'=>$row[4],'Item_Code_Partno'=>$row[5], 'Item_Desc'=>$row[6],
                    'issued_qty'=>$row[7],'UNITNAME'=>$row[8],'po_price'=>$po_price,'totalprice'=>($row[7] * $po_price),'marketsegment'=>$marketsegment);
                $counter++;
                 $total = $total + ($row[7] * $po_price);
            }
            
            $data[$counter] = array('SN'=>$row[0],'oinvoice_No'=>'','oinv_date'=>'',
                    'BuyerCode'=>'','BuyerName'=>'','Item_Code_Partno'=>'', 'Item_Desc'=>'',
                    'issued_qty'=>'','UNITNAME'=>'','po_price'=>'Gross Total','totalprice'=>$total,'marketsegment'=>'');
                $counter++;
	}
        return $data;
    }
    
    
    
    
    // Function add to get the excise none-excise quentiry of the basis of date for report to check the all item stock on the basis of date ON 11-May-2016
	 public static function  checkInventoryByDate($Type,$code_partNo,$curentFinYear,$date){
	
	     $Query = "";
         if($Type == "N")
         {
                $Query = "SELECT ((openQty+inQty+stQty)-outQty) as tot_nonExciseQty from ";
		        $Query =$Query."(select IFNULL(SUM(openingNonExciseQty),0) AS openQty FROM  openinginventory ";
		        $Query =$Query."where openinginventory.code_partNo=$code_partNo) as t1, ";
				$Query =$Query."(select IFNULL(SUM(qty),0) AS inQty,iwe.principal_inv_date  FROM ";
				$Query =$Query."incominginvoice_without_excise as iwe , incominginvoice_without_excise_detail as iwed,incominginvoice_we_mapping  as iwm ";
				$Query =$Query."where iwed.incominginvoice_we=iwm.inner_incomingInvoiceWe AND iwe.incominginvoice_we = iwed.incominginvoice_we ";
				$Query =$Query."and itemID_code_partNo=$code_partNo and iwm.finyear='$curentFinYear' AND iwe.principal_inv_date <= '".$date."') AS t2, ";
                $Query =$Query."(select IFNULL(SUM(issued_qty),0) AS stQty FROM stocktransfer as st ,stocktransfer_detail as std,stocktransfer_mapping as stm ";
                //$Query =$Query."where std.stdId = stm.inner_stId and std.st_codePartNo=$code_partNo and stm.finyear='$curentFinYear') as t3,";
				 $Query =$Query."where std.stId = stm.inner_stId AND st.stId = std.stId and std.st_codePartNo=$code_partNo and stm.finyear='$curentFinYear' AND st.stInvDate <= '".$date."') as t3,";
				$Query =$Query."(select IFNULL(SUM(issued_qty),0) AS outQty,on.oinv_date FROM outgoinginvoice_nonexcise as on ,outgoinginvoice_nonexcise_detail AS ond,outgoinginvoice_nonexcise_mapping as onm ";
				$Query =$Query."where ond.oinvoice_nexciseID=onm.inner_outgoingInvoiceNonEx AND on.oinvoice_nexciseID = ond.oinvoice_nexciseID";
			    $Query =$Query."and codePartNo_desc=$code_partNo and onm.finyear='$curentFinYear' AND on.oinv_date  <= '".$date."' ) as t4";

          }else{
				
             // $Query = "select IFNULL(SUM(ExciseQty),0) AS tot_exciseQty FROM incominginventory WHERE  code_PartNo=$code_partNo ";
				$Query ="SELECT ((openQty+inQty)-(outQty+stQty)) as tot_exciseQty from ";
				$Query =$Query."(select IFNULL(SUM(openingExciseQty),0) AS openQty ";
				$Query =$Query."FROM  openinginventory where code_partNo=$code_partNo) as t1,";
				$Query =$Query."(select IFNULL(SUM(IF(s_qty>0,s_qty,p_qty)),0) AS inQty , ie.principal_inv_date  FROM ";
				$Query =$Query."incominginvoice_excise as ie ,incominginvoice_excise_detail as ied,";
				$Query =$Query."incominginvoice_entryno_mapping  as iem ";
				$Query =$Query."where ied.entryId=iem.inner_EntryNo AND ie.entryId = ied.entryId ";
				$Query =$Query."and itemID_code_partNo=$code_partNo and iem.finyear='$curentFinYear' AND ie.principal_inv_date <= '".$date."' ) AS t2,";
				$Query =$Query."(select IFNULL(SUM(issued_qty),0) AS outQty ,oe.oinv_date ";
				$Query =$Query."FROM outgoinginvoice_excise as oe ,outgoinginvoice_excise_detail AS oed,";
				$Query =$Query."outgoinginvoice_excise_mapping as oem ";
				$Query =$Query."where oed.oinvoice_exciseID=oem.inner_outgoingInvoiceEx AND oe.oinvoice_exciseID = oed.oinvoice_exciseID  ";
				$Query =$Query."and codePartNo_desc=$code_partNo and oem.finyear='$curentFinYear' AND oe.oinv_date <= '".$date."') as t3,";
				$Query =$Query."(select IFNULL(SUM(issued_qty),0) AS stQty,st.stInvDate ";
				$Query =$Query."FROM stocktransfer as st , stocktransfer_detail as std,stocktransfer_mapping as stm ";
				//$Query =$Query."where std.stdId = stm.inner_stId and std.st_codePartNo=$code_partNo ";
				$Query =$Query."where std.stId = stm.inner_stId and std.st_codePartNo=$code_partNo AND st.stId = std.stId  ";
				$Query =$Query."and stm.finyear='$curentFinYear' AND st.stInvDate <= '".$date."') as t4";
          }
		//echo $Query; exit;
			
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		$Quantity = 0;
         if($Type == "E")
         {			 
             $Quantity = $Row['tot_exciseQty'];
         }
         else if($Type == "N")
         {			
             $Quantity = $Row['tot_nonExciseQty'];
         }    
		 
         return $Quantity;
    }
    
    // Function add to get the excise none-excise quentiry of the basis of date for report to check the all item stock on the basis of date ON 12-May-2016 from Transaction Table
	 public static function  checkInventoryByDateFromTransaction($Type,$code_partNo,$curentFinYear,$date){
	
	     $Query = "select IFNULL(tds.balance_qty,0) as balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 inner join transaction AS td ON td.transactionId = tds.transactionId 
		 WHERE tds.code_partNo ='$code_partNo' AND (tds.excise_nonexcise_TAG = 'INEx' OR tds.excise_nonexcise_TAG = 'ON' OR tds.excise_nonexcise_TAG = 'IST') AND  td.transactionDate < '$date' order by tds.transactiondId DESC LIMIT 1"; 
		 
	     $openStocktype = 'EXcise';
	     
	     
         //~ if($Type == "N")
         //~ {
			 //~ $openStocktype = 'NONEXcise';
			 //~ 
             //~ $Query = "select IFNULL(tds.balance_qty,0) as balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 //~ inner join transaction AS td ON td.transactionId = tds.transactionId 
		 //~ WHERE tds.code_partNo ='$code_partNo' AND (tds.excise_nonexcise_TAG = 'INEx' OR tds.excise_nonexcise_TAG = 'ON' OR tds.excise_nonexcise_TAG = 'IST') AND  td.transactionDate < '$date' order by tds.transactiondId DESC LIMIT 1"; 
		//~ 
//~ 
         //~ }else{
			 //~ 
			//~ $openStocktype = 'EXcise';
			//~ 
			//~ $Query = "select IFNULL(tds.balance_qty,0) as balance_qty,tds.code_partNo,td.transactionDate,tds.excise_nonexcise_TAG FROM transaction_detail AS tds
		 //~ inner join transaction AS td ON td.transactionId = tds.transactionId 
		 //~ WHERE tds.code_partNo ='$code_partNo' AND (tds.excise_nonexcise_TAG ='OE' OR tds.excise_nonexcise_TAG = 'EX' OR tds.excise_nonexcise_TAG = 'OST' ) AND  td.transactionDate < '$date' order by tds.transactiondId DESC LIMIT 1"; 
       //~ 
      	//~ }
		
        $Result = DBConnection::SelectQuery($Query);       
		$Quantity = 0;
       if(mysql_num_rows($Result) > 0)
		{
           $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
           
          // print_r($Row); exit;
		   $Quantity = $Row['balance_qty'];
		}else{
			$Quantity= self::getOpeningBalance($date,$code_partNo,$openStocktype);
		}
        	 
         return $Quantity;
    }
	
    
}


<?php
class Managementapproval_Model
{
	    public $approvalId;
	    public $poid;
	    public $mrem;
	    public $arem;
	    public $maTag;
	    public $_items;
	public function __construct($approvalId,$aRemarks,$mRemarks,$ma,$_items)
	    {
	        $this->approvalId=$approvalId;
	        $this->poid=$poid;
            $this->arem=$aRemarks;
	        $this->mrem=$mRemarks;
	        $this->maTag=$ma;
	        $this->_items = $_items;
        }
	public static function  LoadMAByID($poid){
		$Query = "SELECT ma.approvalId,ma.admin_comments, ma.management_comments, po.management_approval,po.approval_status
		FROM management_approval AS ma RIGHT JOIN purchaseorder AS po ON ma.poid = po.bpoId WHERE po.bpoId =$poid";
          //echo 	$Query;
			$Result = DBConnection::SelectQuery($Query);
			$objArray = array();
			$i = 0;
			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)){
	            $approvalId= $Row['approvalId'];
	            $management_approval = $Row['management_approval'];
	            $approval_status = $Row['approval_status'];
	            $aRemarks = $Row['admin_comments'];
	            $mRemarks = $Row['management_comments'];
			    $_itmes = Managementapproval_Details_Model::LoadGrid($poid);
	            $newObj = new Managementapproval_Model($approvalId,$aRemarks,$mRemarks,$management_approval,$_itmes);
	            $objArray[$i] = $newObj;
	            $i++;
			}
			return $objArray;
	}


	public static function InsertMA($poid,$aRemarks,$userId){
	  $date = date("Y-m-d");
	  	//added on 02-JUNE-2016 due to Handle Special Character
        $aRemarks = mysql_escape_string($aRemarks);
      
	  $Query = "INSERT INTO management_approval(poId,admin_comments,UserId ,InsertDate) VALUES ($poid,'$aRemarks','$userId','$date')";
      //echo $Query;
      $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
		//return $Result;
	}

	public static function UpdatePO_BYMA($poid,$management_approval){
		if($management_approval==true){
			$management_approval="R";
		}else{
			$management_approval="N";
		}
		$Query="UPDATE purchaseorder SET management_approval='$management_approval' WHERE bpoId=$poid";
			//echo $Query;
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}
	
	// function to send the PO for management approval if credit period exceed - 03-12-2015 added by codefire for outgoing invoice excise/Non-excise	
	public static function UpdatePO_BYMA_NEW($poid,$management_approval){
		if($management_approval==true){
			$management_approval="R";
			$approval_status = "X";
		}else{
			$management_approval="N";
			$approval_status = "A";
		}
		
		$Query="UPDATE purchaseorder SET management_approval='$management_approval',approval_status = '$approval_status' WHERE bpoId=$poid";
			
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}
	
	public static function UPDATEMA($poid,$approval){
		
		//added on 02-JUNE-2016 due to Handle Special Character
        $approval = mysql_escape_string($approval);
		$Query="UPDATE management_approval SET management_comments='$approval' WHERE poId=$poid";
		
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}


	public static function APPPO_BYMA($poid,$approval){
		$Query="";
		if($approval =="A"){
			$Query="UPDATE purchaseorder SET approval_status='$approval',management_approval='N' WHERE bpoId=$poid";
		}else if($approval =="R"){
			$Query="UPDATE purchaseorder SET approval_status='$approval',management_approval='R' WHERE bpoId=$poid";
		}else{
		
		}
			//echo $Query;
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}
	public static function checkBuyerNewExist_UsingInv($BUYERID){
		$Query = "SELECT  SUM(dum.CNT) AS total FROM (SELECT COUNT(*) AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.pono=PO.bpoId AND PO.BuyerId=$BUYERID AND (OE.PAYMENT_STATUS='O' OR OE.PAYMENT_STATUS='P')
UNION  ALL SELECT COUNT(*) AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.pono=PO.bpoId AND PO.BuyerId=$BUYERID AND (ONE.PAYMENT_STATUS='O' OR ONE.PAYMENT_STATUS='P')
) AS dum";
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
	public static function checkBuyerNewExist_UsingInvNew($BUYERID){
		$Query = "SELECT  SUM(dum.CNT) AS total FROM (
SELECT COUNT(*) AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND OE.PAYMENT_STATUS='O'
UNION  ALL
SELECT COUNT(*) AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND ONE.PAYMENT_STATUS='O'
) AS dum";
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

	public static function getQuotCreditPeriod($BUYERID){
		$Query = "select distinct(credit_period) as q_cp from quotation
where buyerid=$BUYERID or
cust_id=(select cust_id from buyer_cust_master where buyerid=$BUYERID)";
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    /*public static function unBilledPOVal($BUYERID){
         $Query = "SELECT totalPOVal-totalBillVal AS unBilledPOVal FROM
(SELECT IFNULL(SUM(PO_VAL),0) AS totalPOVal FROM purchaseorder WHERE BUYERID=$BUYERID AND PO_STATUS='O')t1,
(SELECT  IFNULL(SUM(dum.CNT),0) AS totalBillVal FROM (
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND OE.PAYMENT_STATUS='O'
UNION  ALL
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND ONE.PAYMENT_STATUS='O'
) AS dum)t2";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
}*/
//#####################
public static function unBilledPOVal($BUYERID){
  $unBilledPoVal=0;
  $pendingPOVal=0;
  $partialPOVal=0;
  $partialSchPOVal=0;	
  $pendingPOVal=self::unBilledPendingPOVal($BUYERID);
 
  $partialPOVal=self::unBilledPartialPOVal($BUYERID);
  $partialSchPOVal=self::unBilledPartialSchPOVal($BUYERID);
  $unBilledPoVal=$pendingPOVal+$partialPOVal+$partialSchPOVal;
  return $unBilledPoVal;
  	
}	
public static function unBilledPendingPOVal($BUYERID)
{
	$Query="SELECT SUM(ppo.po_val) AS po_val FROM (SELECT SUM(t1.po_val) AS po_val FROM (SELECT po.po_val FROM purchaseorder_detail AS pod LEFT JOIN purchaseorder AS po ON pod.bpoId=po.bpoId  WHERE po.bpoType='N' AND pod.po_item_stage IN ('YDE') AND po.buyerId='$BUYERID'  GROUP BY pod.bpoId ) AS t1 UNION ALL SELECT SUM(po_val) FROM  (SELECT ((spod.sch_delbydateqty*pod.po_price)-(((spod.sch_delbydateqty*pod.po_price)*pod.po_discount)/100)) AS po_val FROM purchaseorder_schedule_detail AS spod LEFT JOIN purchaseorder_detail AS pod ON spod.bpoId=pod.bpoId AND spod.bpod_Id=pod.bpod_Id LEFT JOIN purchaseorder AS po ON pod.bpoId=po.bpoId  WHERE spod.pos_item_stage IN ('YDE')  AND po.buyerId='$BUYERID') AS t2) AS ppo";
	$Result = DBConnection::SelectQuery($Query);

	while ($Row = mysql_fetch_array($Result,MYSQL_ASSOC)) {
	   $poval=$Row['po_val'];	
	}	
	//$Row=mysql_fetch_row($Result);
	return round($poval,2);
}

 public static function unBilledPartialPOVal($BUYERID)
{
	$Query="SELECT pod.bpoId,pod.bpod_Id,pod.po_codePartNo,pod.po_qty,pod.po_price,pod.po_discount,pod.po_ed_applicability FROM purchaseorder_detail AS pod LEFT JOIN purchaseorder AS po ON pod.bpoId=po.bpoId  WHERE po.bpoType='N' AND pod.po_item_stage IN ('POIG') AND po.buyerId='$BUYERID'";
	//echo $Query;
	$Result = DBConnection::SelectQuery($Query);
	$bal_qty=0;
	$issue_qty=0;
	$total_poval=0;
	while ($Row = mysql_fetch_array($Result,MYSQL_ASSOC)) {
		
		$bal_qty=0;
	  	$issue_qty=self::GetPoIssueQty($Row['bpod_Id'],$Row['po_codePartNo'],'N',$Row['po_ed_applicability']);
	  	
	  	$bal_qty=$Row['po_qty']-$issue_qty;
	  	$po_val=(($Row['po_price']*$bal_qty)-(($Row['po_price']*$bal_qty)*$Row['po_discount']/100));
	  	$total_poval=$total_poval+$po_val;
	}
	
	return round($total_poval,2);
}

public static function unBilledPartialSchPOVal($BUYERID)
{
	$Query="SELECT po.buyerId,pod.po_ed_applicability ,pod.po_price,pod.po_discount,pod.bpoId,spod.bposd_Id,spod.bpod_Id,sch_codePartNo,spod.sch_delbydateqty FROM purchaseorder_schedule_detail AS spod LEFT JOIN purchaseorder_detail AS pod ON spod.bpoId=pod.bpoId AND spod.bpod_Id=pod.bpod_Id LEFT JOIN purchaseorder AS po ON pod.bpoId=po.bpoId  WHERE po.bpoType='R' AND pos_item_stage='POIG' AND po.buyerId='$BUYERID'";
	
	$Result = DBConnection::SelectQuery($Query);
	$bal_qty=0;
	$issue_qty=0;
	$total_poval=0;
	while ($Row = mysql_fetch_array($Result,MYSQL_ASSOC)) {
		$bal_qty=0;
	  	$issue_qty=self::GetPoIssueQty($Row['bposd_Id'],$Row['sch_codePartNo'],'R',$Row['po_ed_applicability']);
	  	$bal_qty=$Row['sch_delbydateqty']-$issue_qty;
	  	$po_val=(($Row['po_price']*$bal_qty)-(($Row['po_price']*$bal_qty)*$Row['po_discount']/100));
	  	$total_poval=$total_poval+$po_val;
	}
	return round($total_poval,2);
} 

public static function GetPoIssueQty($bpod_Id,$itemId,$bpoType,$potype)
   {
	    $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";
   	   $tot_issue_qty=0;
   	   //~ if($potype=="N")
       //~ {
	      //~ $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_nonexcise_detail AS ivd ,outgoinginvoice_nonexcise AS iv,purchaseorder AS po WHERE ivd.oinvoice_nexciseID=iv.oinvoice_nexciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_nexcisedID DESC";
	     //~ 
       //~ }
       //~ else if($potype=="E" ||$potype=="I")
       //~ {
       	 //~ $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";	
	    		//~ 
       //~ }
       
       //echo $Query;
       $Result = DBConnection::SelectQuery($Query);
       if(mysql_num_rows($Result))
       {
	   	  while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_issue_qty=$Row['issued_qty'];
          }
	   }
        
      return  $tot_issue_qty;  
       
   }
//######################  

    public static function outstandingAmount($BUYERID){
    	$Query = "SELECT totalBillVal-ReceivedPayment AS outstandingPayment FROM
(SELECT  IFNULL(SUM(dum.CNT),0) AS totalBillVal FROM (
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.pono=PO.bpoId AND PO.BuyerId=$BUYERID
UNION  ALL
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.pono=PO.bpoId AND PO.BuyerId=$BUYERID
) AS dum)t1,(SELECT IFNULL(SUM(receivedAmount),0) AS ReceivedPayment FROM trxn as tx,trxn_detail as txd WHERE tx.trxnId=txd.trxnId and BUYERID=$BUYERID)t2";
    	$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
	
	   public static function outstandingAmountNew($BUYERID){
    	$Query = "SELECT totalBillVal-ReceivedPayment AS outstandingPayment FROM
(SELECT  IFNULL(SUM(dum.CNT),0) AS totalBillVal FROM (
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID
UNION  ALL
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID
) AS dum)t1,(SELECT IFNULL(SUM(receivedAmount),0) AS ReceivedPayment FROM trxn as tx,trxn_detail as txd WHERE tx.trxnId=txd.trxnId and BUYERID=$BUYERID)t2";
    	$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
	
	
	// function to get the item landing price to check the miminum margin cut-off for the po 
	public static function getItemLandingPrice($itemId,$principalId,$type){
		$Query='';
		/* if($type =="E"){
			$Query = "SELECT MAX(ied.landing_price) as price,ied.entryDId FROM incominginvoice_excise_detail as ied 
			INNER JOIN incominginvoice_excise as ie on ie.entryId = ied.entryId WHERE ied.itemID_code_partNo=$itemId AND ie.principalId = $principalId ORDER BY ied.entryDId DESC LIMIT 1";
		}else if($type =="N"){
			$Query = "SELECT MAX(iwed.landing_price) as price,iwed.incominginvoice_we FROM incominginvoice_without_excise_detail as iwed 
			INNER JOIN incominginvoice_without_excise as iwe on iwe.incominginvoice_we = iwed.incominginvoice_we WHERE iwed.itemID_code_partNo=$itemId AND iwe.principalID = $principalId ORDER BY iwed.incominginvoice_we DESC LIMIT 1";
		}else{
			$Query = "SELECT im.Cost_Price as price FROM item_master as im WHERE ItemId=$itemId AND PrincipalId = $principalId LIMIT 1";
		} */
		
		$Query1='';
		$Query2 = '';
		if($type =="E"){
				$Query1 = "SELECT iwed.incominginvoice_we as id,IFNULL(MAX(iwed.landing_price),0) as price,IFNULL(iwe.pf_chrg,0) as pf_chrg ,IFNULL(iwe.insurance,0) as insurance,IFNULL(iwe.freight,0) as freight,iwed.ed_percent,iwed.edu_percent,iwed.hedu_percent,iwed.cvd_percent FROM incominginvoice_without_excise_detail as iwed INNER JOIN incominginvoice_without_excise as iwe on iwe.incominginvoice_we = iwed.incominginvoice_we WHERE iwed.itemID_code_partNo=$itemId AND iwe.principalID = $principalId ";
		
				$Query2 = "SELECT ied.entryDId as id,IFNULL(MAX(ied.landing_price),0) as price,IFNULL(ie.pf_chrg,0) as pf_charge,IFNULL(ie.insurance,0) as insurance,IFNULL(ie.freight,0) as freight,ied.basic_purchase_price,ied.ed_percent,ied.edu_percent,ied.hedu_percent,ied.cvd_percent FROM incominginvoice_excise_detail as ied 
				INNER JOIN incominginvoice_excise as ie on ie.entryId = ied.entryId WHERE ied.itemID_code_partNo=$itemId AND ie.principalId = $principalId ";
				//$Query="SELECT MAX(price) as price FROM  (".$Query1."  UNION ALL ".$Query2." )as s ";	
				$Query="SELECT * FROM  (".$Query2.")as s ORDER BY s.price DESC LIMIT 1";	
				
				$Result = DBConnection::SelectQuery($Query);
				if(mysql_num_rows($Result) > 0){
					$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
					$bsp = $Row['price'];
					$pf_charge = $Row['pf_charge'];
					$insurance = $Row['insurance'];
					$freight = $Row['freight'];
					$ed_percent = $Row['ed_percent'];
					$edu_percent = $Row['edu_percent'];
					$hedu_percent = $Row['hedu_percent'];
					$cvd_percent = $Row['cvd_percent'];
					$total_bill_val = $Row['basic_purchase_price'];	
					
					$ed_val = (($bsp * $ed_percent)/100);
					$edu_val = (($bsp * $edu_percent)/100);
					$hedu_val = (($bsp * $hedu_percent)/100);
					$cvd_val = (($bsp * $cvd_percent)/100);					
					$pf_val =  $total_bill_val/$pf_charge;
					$inc_val =  $total_bill_val/$insurance;
					$frt_val =  $total_bill_val/$freight;					
					$actual_land = ($bsp - ($ed_val + $edu_val + $hedu_val + $cvd_val + $pf_val + $inc_val + $frt_val));					
					return $actual_land;
					exit;
				}else{	
					$p = 0;
					return $p;
					exit;
				}	
				
		}else{	
				$Query1 = "SELECT iwed.incominginvoice_we as id,IFNULL(MAX(iwed.landing_price),0) as price,IFNULL(iwe.pf_chrg,0) as pf_chrg ,IFNULL(iwe.insurance,0) as insurance,IFNULL(iwe.freight,0) as freight,iwe.tot_bill_val as total_vill FROM incominginvoice_without_excise_detail as iwed INNER JOIN incominginvoice_without_excise as iwe on iwe.incominginvoice_we = iwed.incominginvoice_we WHERE iwed.itemID_code_partNo=$itemId AND iwe.principalID = $principalId ";
		
				$Query2 = "SELECT ied.entryDId as id,IFNULL(MAX(ied.landing_price),0) as price,IFNULL(ie.pf_chrg,0) as pf_charge,IFNULL(ie.insurance,0) as insurance,IFNULL(ie.freight,0) as freight,ie.total_bill_val as total_vill FROM incominginvoice_excise_detail as ied 
				INNER JOIN incominginvoice_excise as ie on ie.entryId = ied.entryId WHERE ied.itemID_code_partNo=$itemId AND ie.principalId = $principalId ";
				//$Query="SELECT MAX(price) as price FROM  (".$Query1."  UNION ALL ".$Query2." )as s ";	
				$Query="SELECT * FROM  (".$Query1." UNION ALL ".$Query2." )as s ORDER BY s.price DESC LIMIT 1";	
				
				$Result = DBConnection::SelectQuery($Query);
				if(mysql_num_rows($Result) > 0){
					$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
					$bsp = $Row['price'];
					$pf_charge = $Row['pf_charge'];
					$insurance = $Row['insurance'];
					$freight = $Row['freight'];
					$total_bill_val = $Row['total_vill'];	
					$pf_val =  $total_bill_val/$pf_charge;
					$inc_val =  $total_bill_val/$insurance;
					$frt_val =  $total_bill_val/$freight;
					
					$actual_land = ($bsp - ($pf_val + $inc_val + $frt_val));
					return $actual_land;				
					
					exit;
				}else{	
					$p = 0;
					return $p; exit;
				}	
		}
		
		exit;
	
		
	}

	//function to get PO credit period add on 1-12-2015 by codefire
	public static function getPoCreditPeriod($poid){		
		$Query = "SELECT * FROM purchaseorder WHERE bpoId = $poid LIMIT 1";		
    	$Result = DBConnection::SelectQuery($Query);
		 if(mysql_num_rows($Result) > 0){
			$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
			return $Row;
		 }else{
			$Row['bpoId'] = 0;
			return $Row;
		 }		
	}
	
	public static function getItemQuatationPrice($itemId,$principalId,$quotNumber){
		$Query='';
			$Query = "SELECT qd.* FROM quotation_detail as qd 
			INNER JOIN quotation as q ON q.quotId = qd.quotId WHERE qd.code_part_no=$itemId AND q.principalId = $principalId AND q.quotNo= '$quotNumber' LIMIT 1";
		
    	$Result = DBConnection::SelectQuery($Query);
		 if(mysql_num_rows($Result) > 0){
			$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
			return $Row['rate_perUnit'];
		 }else{
			return 0;
		 }		
	}
	
	public static function checkPoApprovalStatus($poid){
		$Query = "SELECT * FROM management_approval WHERE poId = $poid LIMIT 1";
		$Result = DBConnection::SelectQuery($Query);
		 if(mysql_num_rows($Result) > 0){
			$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
			return $Row['approvalId'];
		 }else{
			return 0;
		 }		
	}

}
class Managementapproval_Details_Model{
	    public $bpod_Id;
	    public $po_principalId;
        public $po_principalName;
        public $po_codePartNo;
        public $product;
        public $po_qty;
        public $po_cate;
        public $po_price;
        public $buyer_discount;
        public $po_discount;

public function __construct($bpod_Id,$po_principalId,$po_principalName,$po_codePartNo,$product,$po_qty,$po_cate,$po_price,$buyer_discount,$po_discount){
        $this->bpod_Id = $bpod_Id;
        $this->po_principalId  =$po_principalId;
        $this->po_principalName  =$po_principalName;
        $this->po_codePartNo = $po_codePartNo;
        $this->product = $product;
        $this->po_qty = $po_qty;
        $this->po_cate = $po_cate;
        $this->po_price = $po_price;
        $this->buyer_discount=$buyer_discount;
        $this->po_discount=$po_discount;
 }
public static function UPDATEPO_CATEGORY($bpod_Id,$po_price_category){
			$Query="UPDATE purchaseorder_detail SET po_price_category='$po_price_category' WHERE bpod_Id=$bpod_Id";
			//echo $Query;
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}else{
				return QueryResponse::NO;
			}
}
public static function LoadGrid($poid){
	$Query="select po.BUYERID,bpod_Id,po_quotNo,po_principalId,Principal_Supplier_Name,po_codePartNo,CONCAT(`Item_Code_Partno`, '-', `Item_desc`)Product,po_qty,po_price_category,po_price, po_discount
from purchaseorder_detail as pd,purchaseorder as po,buyer_master as bm,item_master as im,principal_supplier_master as psm
where po.bpoId=pd.bpoId and po.buyerid=bm.buyerId and pd.po_principalId=psm.Principal_Supplier_Id and pd.po_codePartNo=im.ItemId
and pd.bpoId=$poid group by po.BUYERID,bpod_Id,po_principalId,Principal_Supplier_Name,po_codePartNo,po_qty,po_price_category,po_price, po_discount ";
        //echo $Query;
        $result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $buyer_discount="";
            $buyerID = $Row['BUYERID'];
            $bpod_Id = $Row['bpod_Id'];
            $po_quotNo= $Row['po_quotNo'];
            $po_principalId= $Row['po_principalId'];
            $po_principalName= $Row['Principal_Supplier_Name'];
            $po_codePartNo= $Row['po_codePartNo'];
            $product = $Row['Product'];
            $po_qty = $Row['po_qty'];
            $po_cate = $Row['po_price_category'];
            $po_price = $Row['po_price'];
            $po_discount = $Row['po_discount'];
            //$buyer_discount=self::getBuyerDiscount($buyerID,$principalId);
            $buyer_discount=self::getProposedDiscount($po_quotNo);
            $newObj = new Managementapproval_Details_Model($bpod_Id,$po_principalId,$po_principalName,$po_codePartNo,$product,$po_qty,$po_cate,$po_price,$buyer_discount,$po_discount);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function getProposedDiscount($po_quotNo){
	  $propDiscount="";
	  $sql="select discount from quotation where quotNo='$po_quotNo'";
	  $res = DBConnection::SelectQuery($sql);
	  while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	  	$propDiscount=$row['discount'];
	  }
	  return $propDiscount;
    }

    public static function getBuyerDiscount($buyerID,$principalId){
	  $buyerDiscount="";
	  $sql="select discount from buyer_discount_info where BUYERID=$buyerID AND PRINCIPAL_SUPPLIER_ID=$principalId";
	  $res = DBConnection::SelectQuery($sql);
	  while ($row = mysql_fetch_array($res, MYSQL_ASSOC)) {
	  	$buyerDiscount=$row['discount'];
	  }
	  return $buyerDiscount;
    }
}

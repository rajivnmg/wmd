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
		FROM management_approval AS ma RIGHT JOIN purchaseorder AS po ON ma.poid = po.bpoId
WHERE po.bpoId =$poid";
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
	  
		//added on 01-JUNE-2016 due to Handle Special Character
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
	public static function UPDATEMA($poid,$approval){

		$Query="UPDATE management_approval SET management_comments='$approval' WHERE poId=$poid";
			//echo $Query;
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}


	public static function APPPO_BYMA($poid,$approval){

		$Query="UPDATE purchaseorder SET approval_status='$approval' WHERE bpoId=$poid";
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
    public static function unBilledPOVal($BUYERID){
         $Query = "SELECT totalPOVal-totalBillVal AS unBilledPOVal FROM
(SELECT IFNULL(SUM(PO_VAL),0) AS totalPOVal FROM purchaseorder WHERE BUYERID=$BUYERID AND PO_STATUS='O')t1,
(SELECT  IFNULL(SUM(dum.CNT),0) AS totalBillVal FROM (
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND OE.PAYMENT_STATUS='O'
UNION  ALL
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID AND ONE.PAYMENT_STATUS='O'
) AS dum)t2";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
}

    public static function outstandingAmount($BUYERID){
    	$Query = "SELECT totalBillVal-ReceivedPayment AS outstandingPayment FROM
(SELECT  IFNULL(SUM(dum.CNT),0) AS totalBillVal FROM (
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_excise AS OE,purchaseorder AS PO WHERE OE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID
UNION  ALL
SELECT BILL_VALUE AS CNT FROM outgoinginvoice_nonexcise AS ONE,purchaseorder AS PO WHERE ONE.BUYERID=PO.BPOID AND PO.BUYERID=$BUYERID
) AS dum)t1,(SELECT IFNULL(SUM(receivedAmount),0) AS ReceivedPayment FROM trxn as tx,trxn_detail as txd WHERE tx.trxnId=txd.trxnId and BUYERID=$BUYERID)t2";
    	$Result = DBConnection::SelectQuery($Query);
		return $Result;
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

	// function return the buyer discount on the basis of principal
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


?>

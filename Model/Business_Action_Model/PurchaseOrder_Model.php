<?php
class PurchaseOrder_Model
{
    public $_bpoId;
    public $_bpono;
    public $_bpoDate;
    public $_bpoVDate;
    public $_bpoType;
    public $_mode_delivery;
    public $_credit_period;
    public $_executiveId;
    public $_cash_discount_tag;
    public $_cash_discount_value;
    public $_bill_ship_address_same_tag;
    public $_po_shipadd1;
    public $_po_shipadd2;
    public $_po_shipStateId;
    public $_po_shipCityId;
    public $_po_shipCountryId;
    public $_po_shipLocationId;
    public $_po_shipPincode;
    public $_pf_chrg;
    public $_incidental_chrg;
    public $_freight_tag;
    public $_freight_percent;
    public $_freight_amount;
    public $_management_approval;
    public $_approval_status;
    public $_po_status;
    public $_Remarks;
    public $_UserId;
    public $_InsertDate;
    public function __construct($bpoId,$bpono,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId,$cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$pf_chrg,$incidental_chrg,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$Remarks,$UserId,$InsertDate)
    {
        $this->_bpoId=$bpoId;
        $this->_bpono=$bpono;
        $this->_bpoDate=$bpoDate;
        $this->_bpoVDate=$bpoVDate;
        $this->_bpoType=$bpoType;
        $this->_mode_delivery=$mode_delivery;
        $this->_credit_period=$credit_period;
        $this->_executiveId=$executiveId;
        $this->_cash_discount_tag=$cash_discount_tag;
        $this->_cash_discount_value=$cash_discount_value;
        $this->_bill_ship_address_same_tag=$bill_ship_address_same_tag;
        $this->_po_shipadd1=$po_shipadd1;
        $this->_po_shipadd2=$po_shipadd2;
        $this->_po_shipStateId=$po_shipStateId;
        $this->_po_shipCityId=$po_shipCityId;
        $this->_po_shipCountryId=$po_shipCountryId;
        $this->_po_shipLocationId=$po_shipLocationId;
        $this->_po_shipPincode=$po_shipPincode;
        $this->_pf_chrg=$pf_chrg;
        $this->_incidental_chrg=$incidental_chrg;
        $this->_freight_tag=$freight_tag;
        $this->_freight_percent=$freight_percent;
        $this->_freight_amount=$freight_amount;
        $this->_management_approval=$management_approval;
        $this->_approval_status=$approval_status;
        $this->_po_status=$po_status;
        $this->_Remarks=$Remarks;
        $this->_UserId=$UserId;
        $this->_InsertDate=$InsertDate;
    }
    public static function  LoadPurchase($bpono)
	{
       
        if($bpono > 0)
        {
            $result = self::GetPurchaseDetails($bpono);
        }
        else
        {
            $result = self::GetPurchaseList();
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $bpoId= $Row['bpoId'];
            $bpono = $Row['bpono'];
            $bpoDate = $Row['bpoDate'];
            $bpoVDate= $Row['bpoVDate'];
            $bpoType = $Row['bpoType'];
            $mode_delivery= $Row['mode_delivery'];
            $credit_period= $Row['credit_period'];
            $executiveId = $Row['executiveId'];
            $cash_discount_tag = $Row['cash_discount_tag'];
            $cash_discount_value = $Row['cash_discount_value'];
            $po_shipadd1 = $Row['po_shipadd1'];
            $po_shipadd2 = $Row['po_shipadd2'];
            $po_shipStateId = $Row['po_shipStateId'];
            $po_shipCityId = $Row['po_shipCityId'];
            $po_shipCountryId = $Row['po_shipCountryId'];
            $po_shipLocationId = $Row['po_shipLocationId'];
            $po_shipPincode = $Row['po_shipPincode'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $freight_tag = $Row['freight_tag'];
            $freight_percent = $Row['freight_percent'];
            $freight_amount = $Row['freight_amount'];
            $management_approval = $Row['management_approval'];
            $approval_status = $Row['approval_status'];
            $po_status = $Row['po_status'];
            $Remarks = $Row['Remarks'];
            $UserId = $Row['UserId'];
            $InsertDate = $Row['InsertDate'];
            $newObj = new PurchaseOrder_Model($bpoId,$bpono,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId,$cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$pf_chrg,$incidental_chrg,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$Remarks,$UserId,$InsertDate);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function InsertPurchase($bpono,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId,
        $cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId
        ,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$pf_chrg,$incidental_chrg,$freight_tag,$freight_percent
        ,$freight_amount,$management_approval,$approval_status,$po_status,$Remarks,$UserId,$InsertDate)
    {
		
		//added on 03-JUNE-2016 due to Handle Special Character
		$po_shipadd1 = mysql_escape_string($po_shipadd1);
		$po_shipadd2 = mysql_escape_string($po_shipadd2);
		$po_shipPincode = mysql_escape_string($po_shipPincode);
		$Remarks = mysql_escape_string($Remarks);
		
        $Query="INSERT INTO purchaseorder(bpono, bpoDate, bpoVDate, bpoType,mode_delivery,credit_period,executiveId, cash_discount_tag,cash_discount_value,bill_ship_address_same_tag, po_shipadd1, po_shipadd2, po_shipStateId, po_shipCityId,        po_shipCountryId, po_shipLocationId, po_shipPincode, pf_chrg,incidental_chrg, freight_tag, freight_percent,freight_amount, management_approval, approval_status, po_status, Remarks, UserId, InsertDate) VALUES ('$bpono','$bpoDate','$bpoVDate','$bpoType',$mode_delivery,$credit_period,$executiveId,'$cash_discount_tag',$cash_discount_value,'$bill_ship_address_same_tag','$po_shipadd1','$po_shipadd2',$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$pf_chrg,$incidental_chrg,'$freight_tag',$freight_percent,$freight_amount,'$management_approval','$approval_status','$po_status','$Remarks','$UserId','$InsertDate')";
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
        
    }
    public static function UpdatePurchase($bpoId,$bpono,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId, $cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$pf_chrg,$incidental_chrg,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$Remarks,$UserId,$InsertDate)
    {
		
		//added on 03-JUNE-2016 due to Handle Special Character
		$po_shipadd1 = mysql_escape_string($po_shipadd1);
		$po_shipadd2 = mysql_escape_string($po_shipadd2);
		$po_shipPincode = mysql_escape_string($po_shipPincode);
		$Remarks = mysql_escape_string($Remarks);
		
        $Query="UPDATE purchaseorder SET bpono='$bpono',bpoDate='$bpoDate',bpoVDate='$bpoVDate',bpoType='$bpoType',mode_delivery=$mode_delivery,credit_period=$credit_period,executiveId=$executiveId,cash_discount_tag='$cash_discount_tag',cash_discount_value=$cash_discount_value,bill_ship_address_same_tag='$bill_ship_address_same_tag', po_shipadd1='$po_shipadd1', po_shipadd2='$po_shipadd2',po_shipStateId=$po_shipStateId,po_shipCityId=$po_shipCityId,po_shipCountryId=$po_shipCountryId,po_shipLocationId=$po_shipLocationId,po_shipPincode=$po_shipPincode,pf_chrg=$pf_chrg,incidental_chrg=$incidental_chrg,freight_tag='$freight_tag',freight_percent=$freight_percent,freight_amount=$freight_amount,management_approval='$management_approval',approval_status='$approval_status',po_status='$po_status',Remarks='$Remarks',UserId='$UserId',InsertDate='$InsertDate' WHERE bpoId=$bpoId";
        $Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
    }
    public static function GetPurchaseList()
    {
        $Query = "SELECT * FROM purchaseorder"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function GetPurchaseDetails($bpono)
    {
        $Query = "SELECT * FROM purchaseorder where bpono=$bpono"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}
class PurchaseOrderDetails_Model
{
    public $_po_detail_id;
    public $_bpoId;
    public $_po_pquotNo;
    public $_po_quotNo;
    public $_po_principalId;
    public $_po_codePartNo;
    public $_po_buyeritemcode;
    public $_po_qty;
    public $_po_unit;
    public $_po_price;
    public $_po_discount;
    public $_po_ed_applicability;
    public $_po_saleTax;
    public $_po_deliverybydate;
    public function __construct($po_detail_id,$bpoId,$po_pquotNo,$po_quotNo,$po_principalId,$po_codePartNo,$po_buyeritemcode,$po_qty,$po_unit,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate)
    {
        $this->_po_detail_id=$po_detail_id;
        $this->_bpoId=$bpoId;
        $this->_po_pquotNo=$po_pquotNo;
        $this->_po_principalId=$po_principalId;
        $this->_po_codePartNo=$po_codePartNo;
        $this->_po_buyeritemcode=$po_buyeritemcode;
        $this->_po_qty=$po_qty;
        $this->_po_unit=$po_unit;
        $this->_po_price=$po_price;
        $this->_po_discount=$po_discount;
        $this->_po_ed_applicability=$po_ed_applicability;
        $this->_po_saleTax=$po_saleTax;
        $this->_po_deliverybydate=$po_deliverybydate;
    }
    public static function  LoadPurchaseDetails($po_detail_id)
	{
        
        if($po_detail_id > 0)
        {
            $result = self::GetPurchaseDetails($po_detail_id);
        }
        else
        {
            $result = self::GetPurchaseList();
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $po_detail_id= $Row['po_detail_id'];
            $bpoId = $Row['bpoId'];
            $po_pquotNo = $Row['po_pquotNo'];
            $po_quotNo= $Row['po_quotNo'];
            $po_principalId = $Row['po_principalId'];
            $po_codePartNo= $Row['po_codePartNo'];
            $po_buyeritemcode= $Row['po_buyeritemcode'];
            $po_qty = $Row['po_qty'];
            $po_unit = $Row['po_unit'];
            $po_price = $Row['po_price'];
            $po_discount = $Row['po_discount'];
            $po_ed_applicability = $Row['po_ed_applicability'];
            $po_saleTax = $Row['po_saleTax'];
            $po_deliverybydate = $Row['po_deliverybydate'];
            
            $newObj = new PurchaseOrderDetails_Model($po_detail_id,$bpoId,$po_pquotNo,$po_quotNo,$po_principalId,$po_codePartNo,$po_buyeritemcode,$po_qty,$po_unit,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function InsertPurchaseDetails($bpoId,$po_pquotNo,$po_quotNo,$po_principalId,$po_codePartNo,$po_buyeritemcode,$po_qty,$po_unit,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate)
    {
		//added on 03-JUNE-2016 due to Handle Special Character
		$po_codePartNo = mysql_escape_string($po_codePartNo);
		$po_buyeritemcode = mysql_escape_string($po_buyeritemcode);
		
        $Query="INSERT INTO purchaseorder_detail(bpoId, po_pquotNo, po_quotNo, po_principalId, po_codePartNo, po_buyeritemcode, po_qty, po_unit, po_price, po_discount, po_ed_applicability, po_saleTax, po_deliverybydate) VALUES ($bpoId,'$po_pquotNo','$po_quotNo',$po_principalId,'$po_codePartNo','$po_buyeritemcode',$po_qty,$po_unit,$po_price,$po_discount,'$po_ed_applicability',$po_saleTax,'$po_deliverybydate')";
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function UpdatePurchaseDetails($po_detail_id,$bpoId,$po_pquotNo,$po_quotNo,$po_principalId,$po_codePartNo,$po_buyeritemcode,$po_qty,$po_unit,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate)
    {
		//added on 03-JUNE-2016 due to Handle Special Character
		$po_codePartNo = mysql_escape_string($po_codePartNo);
		$po_buyeritemcode = mysql_escape_string($po_buyeritemcode);
		
        $Query="UPDATE purchaseorder_detail SET bpoId=$bpoId,po_pquotNo='$po_pquotNo',po_quotNo='$po_quotNo',po_principalId=$po_principalId,po_codePartNo='$po_codePartNo',po_buyeritemcode='$po_buyeritemcode',po_qty=$po_qty,po_unit=$po_unit,po_price=$po_price,po_discount=$po_discount,po_ed_applicability='$po_ed_applicability',po_saleTax=$po_saleTax,po_deliverybydate='$po_deliverybydate' WHERE po_detail_id=$po_detail_id";
        $Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
    }
    public static function GetPurchaseDetails($po_detail_id)
    {
        $Query = "SELECT * FROM purchaseorder where po_detail_id=$po_detail_id"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function GetPurchaseList()
    {
        $Query = "SELECT * FROM purchaseorder_detail"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}

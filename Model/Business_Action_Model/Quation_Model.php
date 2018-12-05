<?php
class Quotation_Model
{
    public $_quotation_id;
    public $_quotation_no;
    public $_parent_quotation_no;
    public $_coustomer_ref_no;
    public $_coustomer_ref_date;
    public $_quotation_date;
    public $_buyer_id;
    public $_cust_id;
    public $_coustomer_name;
    public $_coustomer_add;
    public $_contact_persone;
    public $_principal_id;
    public $_discount;
    public $_delivery;
    public $_sales_tax;
    public $_incidental_chrg;
    public $frgt;
    public $frgp;
    public $frga;
    public $_credit_period;
    public $_ed_edu_tag;
    public $_ed;
    public $_edu;
    public $_hedu;
    public $_cvd;
    public $_remarks;
    public $_principal_name;
    public $_itmes  = array();
    public $sale_tax_text;
    public $edu_text;
    public $Delivery_text;
	public $cuserId;
	public $_pnf;
	public $_ins;
	public $_othc;
    
    //public function __construct($quotationid,$quotationno,$parentquotationno,$coustomerrefno,$cust_ref_date,$quotationdate,$buyerid,$custid,$coustomername,$coustomeradd,$contactpersone,$principalid,$discount,$delivery,$salestax,$incidentalchrg,$frgt,$frgp,$frga,$creditperiod,$ededutag,$_cvd,$remarks,$principalname,$itmes,$sale_tax_text,$edu_text,$Delivery_text,$cuserId=null){
	public function __construct($quotationid,$quotationno,$parentquotationno,$coustomerrefno,$cust_ref_date,$quotationdate,$buyerid,$custid,$coustomername,$coustomeradd,$contactpersone,$principalid,$discount,$delivery,$salestax,$incidentalchrg,$frgt,$frgp,$frga,$creditperiod,$ededutag,$_cvd,$remarks,$principalname,$itmes,$sale_tax_text,$edu_text,$Delivery_text,$pnf,$ins, $othc, $cuserId=null){
        
		 $this->_quotation_id = $quotationid;
         $this->_quotation_no = $quotationno;
         $this->_parent_quotation_no = $parentquotationno;
         $this->_coustomer_ref_no = $coustomerrefno;
         $this->_coustomer_ref_date = $cust_ref_date;
         $this->_quotation_date = $quotationdate;
         $this->_buyer_id = $buyerid;
         $this->_cust_id = $custid;
         $this->_coustomer_name = $coustomername;
         $this->_coustomer_add = $coustomeradd;
         $this->_contact_persone = $contactpersone;
         $this->_principal_id = $principalid;
         $this->_discount = $discount;
         $this->_delivery = $delivery;
         $this->_sales_tax = $salestax;
         $this->_incidental_chrg = $incidentalchrg;
         $this->frgt = $frgt;
         $this->frgp = $frgp;
         $this->frga = $frga;
         $this->_credit_period = $creditperiod;
         $this->_ed_edu_tag = $ededutag;
         $this->_cvd = $_cvd;
         $this->_remarks = $remarks;
         $this->_principal_name = $principalname;
         $this->_itmes = $itmes;
         $this->sale_tax_text = $sale_tax_text;
         $this->edu_text = $edu_text;
         $this->Delivery_text = $Delivery_text;
		 $this->cuserId = $cuserId;
		 $this->_pnf = $pnf;
		 $this->_ins = $ins;
		 $this->_othc = $othc;
	}
    
    public static function AutoGenerateQuotationNumber(){
        $num_code = MultiweldParameter::QuotationAutogentateKey.date('y');
        $lastNum = self::GetLastQuotationNumber();
        $num_digit_in_last_record = self::count_digit($lastNum);
        $lastNum = $lastNum + 1;
        $s = sprintf("%06d", $lastNum);
        $lastNum = $num_code.$s;
        return $lastNum;
    }
    public static function GetLastQuotationNumber(){
        $Query = "SELECT quotId FROM quotation ORDER BY quotId DESC LIMIT 1"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["quotId"] > 0)
            return $Row["quotId"];
        else
            return null;
    }
     
    public static function count_digit($number) {
        return strlen((string) $number);
    }
    public static function  LoadQuotation($Quotation_Number,$page,$rp,$sortname,$sortorder)
	{
        //$Quotation_Number = 19;
        $result;
        if($Quotation_Number > 0)
        {
            $result = self::GetQuotationDetails($Quotation_Number);
        }
        else
        {
            $result = self::GetQuotationList($page,$rp,$sortname,$sortorder);
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		 $_quotation_id = $Row['quotId'];
             $_quotation_no = $Row['quotNo'];
             $_parent_quotation_no = $Row['cust_ref_no'];
             $_coustomer_ref_no = $Row['cust_ref_no'];
             $_coustomer_ref_date= date("d/m/Y", strtotime($Row['cust_ref_date']));
             $_quotation_date=date("d/m/Y", strtotime($Row['quotDate']));
             $_buyer_id = $Row['BuyerId'];
             $_cust_id = $Row['cust_id'];
             if($_buyer_id > 0)
             {
                 $BuyerQuery = "select buyer_master.BuyerId, buyer_master.BuyerName, buyer_master.Bill_Add1, buyer_master.Bill_Add2, city_master.CityName, location_master.LocationName, state_master.StateName from buyer_master inner join city_master ON city_master.CityId = buyer_master.CityId inner join location_master ON location_master.LocationId = buyer_master.LocationId inner join state_master ON state_master.StateId = buyer_master.StateId WHERE buyer_master.BuyerId = $_buyer_id";
                 $BuyerResult = DBConnection::SelectQuery($BuyerQuery);
                 $BuyerRow = mysql_fetch_array($BuyerResult, MYSQL_ASSOC);
                 $_coustomer_name = $BuyerRow['BuyerName'];
                 $_coustomer_add = $BuyerRow['Bill_Add1'] . " ". $BuyerRow['Bill_Add2']." , ".$BuyerRow['LocationName']." , ".$BuyerRow['CityName'];
             }
             else if($_cust_id > 0)
             {
                 $CustomerQuery = "SELECT  cust_name, cust_add FROM cust_master WHERE cust_id = $_cust_id";
                 $CustomerResult = DBConnection::SelectQuery($CustomerQuery);
                 $CustomerRow = mysql_fetch_array($CustomerResult, MYSQL_ASSOC);
                 $_coustomer_name = $CustomerRow['cust_name'];
                 $_coustomer_add = $CustomerRow['cust_add'];
             }
             $_contact_persone = $Row['contact_person'];
             $_principal_id = $Row['principalId'];
             $_discount = $Row['discount'];
             $_delivery = $Row['deliveryId'];
             //$_sales_tax = $Row['salesTax'];
             $_incidental_chrg = $Row['incidental_chrg'];
             $frgt = $Row['freight_tag'];
             $frgp = $Row['freight_percent'];
             $frga = $Row['freight_amount'];
             $_credit_period = $Row['credit_period'];
             //$_ed_edu_tag = $Row['ed_edu_tag'];
             //$_cvd = $Row['cvd_percent'];
             $_remarks = $Row['remarks'];
			 $_pnf = $Row['packing_forwarding_charge'];
			 $_ins = $Row['insurance_charge'];
			 $_othc = $Row['other_charge'];
             $_principal_name = $Row['Principal_Supplier_Name'];
             $_itmes = Quotation_Details_Model::LoadQuotationDetails($Row['quotId']);
             $sale_tax_text= $Row['SALESTAX_DESC'];
             $edu_text = $Row['ed'];
             $Delivery_text = $Row['del'];
			 $cuserId = $Row['userId'];
             //$newObj = new Quotation_Model($_quotation_id,$_quotation_no,$_parent_quotation_no,$_coustomer_ref_no,$_coustomer_ref_date, $_quotation_date,$_buyer_id,$_cust_id,$_coustomer_name,$_coustomer_add,$_contact_persone,$_principal_id,$_discount,$_delivery,$_sales_tax,$_incidental_chrg,$frgt,$frgp,$frga,$_credit_period,$_ed_edu_tag,$_cvd,$_remarks,$_principal_name,$_itmes,$sale_tax_text,$edu_text,$Delivery_text,$cuserId);
			 $newObj = new Quotation_Model($_quotation_id, $_quotation_no, $_parent_quotation_no, $_coustomer_ref_no, $_coustomer_ref_date, $_quotation_date, $_buyer_id, $_cust_id, $_coustomer_name, $_coustomer_add, $_contact_persone,$_principal_id, $_discount, $_delivery, $_sales_tax, $_incidental_chrg, $frgt, $frgp, $frga, $_credit_period,$_ed_edu_tag, $_cvd, $_remarks, $_principal_name, $_itmes, $sale_tax_text, $edu_text, $Delivery_text, $_pnf, $_ins, $_othc, $cuserId);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function getSalesTax($buyerId){
		$result = purchaseorder_Details_Model::showSalesTax($buyerId);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $_sales_tax= $Row['salestax_id'];
               $sale_tax_text= $Row['salestax_desc'];
              //$newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice);
              $newObj = new Quotation_Model($_quotation_id=null,$_quotation_no=null,$_parent_quotation_no=null,$_coustomer_ref_no=null,$_coustomer_ref_date=null, $_quotation_date=null,$_buyer_id=null,$_cust_id=null,$_coustomer_name=null,$_coustomer_add=null,$_contact_persone=null,$_principal_id=null,$_discount=null,$_delivery=null,$_sales_tax,$_incidental_chrg=null,$frgt=null,$frgp=null,$frga=null,$_credit_period=null,$_ed_edu_tag=null,$_cvd=null,$_remarks=null,$_principal_name=null,$_itmes=null,$sale_tax_text,$edu_text=null,$Delivery_text=null, $_pnf=null, $_ins=null, $_othc=null );
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    //public static function InsertQuotation($quotNo, $cust_ref_no,$cust_ref_date, $BuyerId, $cust_id,$contact_person,$principalId,$discount,$deliveryId,$salesTax,$incidental_chrg,$frgt,$frgp,$frga,$credit_period,$ed_edu_tag,$_cvd,$remarks,$userId){
	public static function InsertQuotation($quotNo, $cust_ref_no,$cust_ref_date, $BuyerId, $cust_id, $contact_person, $principalId,  $discount, $deliveryId, $incidental_chrg, $frgt, $frgp, $frga, $credit_period, $remarks, $pnf_charge, $ins_charge, $othc_charge,$userId){
	if($BuyerId==NULL){
			$BuyerId='NULL';
		}
        if($cust_id==NULL){
			$cust_id='NULL';
		}
         if($discount==NULL){
			$discount=0.00;
		}
        if($cust_ref_date==NULL){
			$cust_ref_date='NULL';
		}
        if($deliveryId==NULL){
			$deliveryId=0;
		}
        /* if($salesTax==NULL){
			$salesTax='NULL';
		} */
        if($freight==NULL){
			$freight='NULL';
		}
        if($incidental_chrg==NULL){
			$incidental_chrg=0.00;
		}
        if($frgt == "A"){
		   $frgp = 'NULL';
		}else if($frgt == "P"){
			$frga = 'NULL';
		}else{
			$frgp = 'NULL';
			$frga = 'NULL';
		}
		/* BOF for adding GST by Ayush Giri on 20-06-2017 */
        /* if($_cvd==NULL){
			$_cvd='NULL';
		} */
		if($pnf_charge ==NULL){
			$pnf_charge = 'NULL';
		}
		if($ins_charge ==NULL){
			$ins_charge = 'NULL';
		}
		if($othc_charge ==NULL){
			$othc_charge = 'NULL';
		}
		/* EOF for adding GST by Ayush Giri on 20-06-2017 */
		$date = date("Y-m-d");
		$quotNo = self::AutoGenerateQuotationNumber();
        $_coustomer_ref_date11= MultiweldParameter::xFormatDate($cust_ref_date);
        
        //added on 03-JUNE-2016 due to Handle Special Character
        $cust_ref_no = mysql_escape_string($cust_ref_no);
        $contact_person = mysql_escape_string($contact_person);
		$credit_period = mysql_escape_string($credit_period);
		$remarks = mysql_escape_string($remarks);
        
        //$Query = "INSERT INTO quotation (quotNo, cust_ref_no,cust_ref_date, quotDate, BuyerId, cust_id, contact_person, principalId, discount, deliveryId, salesTax, incidental_chrg, freight_tag, freight_percent, freight_amount, credit_period, ed_edu_tag, cvd_percent, remarks, userId, insertDate) VALUES ('$quotNo','".$cust_ref_no."','".$_coustomer_ref_date11."', '".$date."', $BuyerId, $cust_id, '".$contact_person."', '$principalId', '$discount', '$deliveryId', '$salesTax', '$incidental_chrg','$frgt',$frgp,$frga, '".$credit_period."', '$ed_edu_tag', '$_cvd', '".$remarks."', '$userId', '$date')"; 
		
		$Query = "INSERT INTO quotation (quotNo, cust_ref_no,cust_ref_date, quotDate, BuyerId, cust_id, contact_person, principalId, discount, deliveryId, incidental_chrg, freight_tag, freight_percent, freight_amount, credit_period, remarks, packing_forwarding_charge, insurance_charge, other_charge, userId, insertDate) VALUES ('$quotNo','".$cust_ref_no."','".$_coustomer_ref_date11."', '".$date."', $BuyerId, $cust_id, '".$contact_person."', '$principalId', '$discount', '$deliveryId', '$incidental_chrg','$frgt',$frgp,$frga, '".$credit_period."', '".$remarks."', $pnf_charge, $ins_charge, $othc_charge, '$userId', '$date')";
     
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function GetQuotationDetails($Quotation_Number){
			
		$Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed , prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm on qut.salesTax = vcm.SALESTAX_ID left join param as prm on prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId WHERE qut.quotId = $Quotation_Number"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    
    public static function GetQuotationList($page,$rp,$sortname,$sortorder){
		$d = date("Y-m-d");
		$quotDate = date('Y-m-d', strtotime($d. ' - 30 days'));
		if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
			$Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed ,
prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm  
ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm 
on qut.salesTax = vcm.SALESTAX_ID left join param as prm on  
prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag  
left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId WHERE qut.userId='".$_SESSION['USER']."' AND qut.quotDate >= '$quotDate' ORDER BY $sortname $sortorder LIMIT $page , $rp"; 
			
		}else{
			$Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed ,
prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm  
ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm 
on qut.salesTax = vcm.SALESTAX_ID left join param as prm on  
prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag  
left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId WHERE qut.quotDate >= '$quotDate' ORDER BY $sortname $sortorder LIMIT $page , $rp"; 
		}
		
		//echo $Query ;
		
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
     public static function CountRecordQuot($tableName,$Type){
		$d = date("Y-m-d");
		$quotDate = date('Y-m-d', strtotime($d. ' - 30 days'));
		if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
			$Query = "SELECT COUNT(*) as total FROM ".$tableName." WHERE userId = '".$_SESSION['USER']."' AND quotDate >= '$quotDate'";
		 }else{
			$Query = "SELECT COUNT(*) as total FROM ".$tableName." WHERE quotDate >= '$quotDate'";
		 }

         $Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         return $Row["total"];
    }
    
    //public static function GetQuotationList(){
    //    $Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed , prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm on qut.salesTax = vcm.SALESTAX_ID left join param as prm on prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId"; 
    //    $Result = DBConnection::SelectQuery($Query);;
    //    return $Result;
    //}
    function loadname($quotationno)
    {
        $this->_quotation_no = $quotationno;
    }
    public static function GetQuotationNumberList(){
		$Query = "SELECT quotNo FROM quotation"; 
		$Result = DBConnection::SelectQuery($Query);
        $objArray = array();
        $i = 0;                                     
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_quotation_no = $Row['quotNo'];
            $newObj = new Quotation_Model($_quotation_id,$_quotation_no,$_parent_quotation_no,$_coustomer_ref_no,$cust_ref_date,$_quotation_date,$_buyer_id,$_coustomer_name,$_coustomer_add,$_contact_persone,$_principal_id,$_discount,$_delivery,$_sales_tax,$_incidental_chrg,$frgt,$frgp,$frga,$_credit_period,$_ed_edu_tag,$_remarks,$_principal_name);
            $objArray[$i] = $newObj;
            $i++;
        }
        return $objArray;
	}
	 public static function CountRecord($tableName,$Type){
		if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
			$Query = "SELECT COUNT(*) as total FROM ".$tableName." WHERE userId = '".$_SESSION['USER']."'";
		 }else{
			$Query = "SELECT COUNT(*) as total FROM ".$tableName;
		 }

         $Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         return $Row["total"];
    }


	
    public static function SearchQuotation($CoulamName,$value1,$value2,$value3,$value4,$count,$start,$rp){
        $Query = "";
        $CountQuery = "";
	
        switch($CoulamName)
        {
            case "Principal":
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
					$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value1 AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp "; 
					$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value1 AND qut.userId='".$_SESSION['USER']."'"; 
				}else{
					 $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value1 LIMIT $start , $rp "; 
					$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value1"; 
				}
                break;
            case "Buyer":
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.userId='".$_SESSION['USER']."'";
				}else{
				$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1";
				}
                break;
            case "Date":
				
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."'";
				
				}else{
				 $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$value1' AND '$value2' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$value1' AND '$value2'";
				}
                break;
            case "Principal_WITH_DATE":
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."'";
				}else{
				 $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.principalId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2'";
				}
                break;
            case "Buyer_WITH_DATE":
			if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' AND qut.userId='".$_SESSION['USER']."'";
				}else{
				$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value3 AND qut.quotDate BETWEEN '$value1' AND '$value2'";
				}
                break;
            case "Principal_WITH_Buyer_WITH_DATE":
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4' AND qut.userId='".$_SESSION['USER']."'";
				}else{
				 $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4'";
				}
                break;
            case "Principal_WITH_Buyer":
			if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
                $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.userId='".$_SESSION['USER']."'";
				}else{
				 $Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4' LIMIT $start , $rp ";
                $CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.BuyerId = $value1 AND qut.principalId = $value2 AND qut.quotDate BETWEEN '$value3' AND '$value4'";
				}
                break;
			case "QuationNo":
				if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
					$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$value1' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp "; 
					$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$value1' AND qut.userId='".$_SESSION['USER']."'"; 
				}else{
					$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$value1'"; 
					$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$value1'"; 
				}
			 break;
            default:
                return;
                break;
        }
	
        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];
     
		$Result = DBConnection::SelectQuery($Query);
        $objArray = array();
        $i = 0;                                     
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_quotation_id = $Row['quotId'];
             $_quotation_no = $Row['quotNo'];
             //$_parent_quotation_no = $Row['pquotNo'];
			 $_parent_quotation_no = $Row['quotNo'];
             $_coustomer_ref_no = $Row['cust_ref_no'];
             $_coustomer_ref_date= date("d/m/Y", strtotime($Row['cust_ref_date']));
             $_quotation_date=date("d/m/Y", strtotime($Row['quotDate']));
             $_buyer_id = $Row['BuyerId'];
             $_cust_id = $Row['cust_id'];
             if($_buyer_id > 0)
             {
                 $BuyerQuery = "select buyer_master.BuyerId, buyer_master.BuyerName, buyer_master.Bill_Add1, buyer_master.Bill_Add2, city_master.CityName, location_master.LocationName, state_master.StateName from buyer_master inner join city_master ON city_master.CityId = buyer_master.CityId inner join location_master ON location_master.LocationId = buyer_master.LocationId inner join state_master ON state_master.StateId = buyer_master.StateId WHERE buyer_master.BuyerId = $_buyer_id";
                 $BuyerResult = DBConnection::SelectQuery($BuyerQuery);
                 $BuyerRow = mysql_fetch_array($BuyerResult, MYSQL_ASSOC);
                 $_coustomer_name = $BuyerRow['BuyerName'];
                 $_coustomer_add = $BuyerRow['Bill_Add1'] . " ". $BuyerRow['Bill_Add2']." , ".$BuyerRow['LocationName']." , ".$BuyerRow['CityName'];
             }
             else if($_cust_id > 0)
             {
                 $CustomerQuery = "SELECT  cust_name, cust_add FROM cust_master WHERE cust_id = $_cust_id";
                 $CustomerResult = DBConnection::SelectQuery($CustomerQuery);
                 $CustomerRow = mysql_fetch_array($CustomerResult, MYSQL_ASSOC);
                 $_coustomer_name = $CustomerRow['cust_name'];
                 $_coustomer_add = $CustomerRow['cust_add'];
             }
             $_contact_persone = $Row['contact_person'];
             $_principal_id = $Row['principalId'];
             $_discount = $Row['discount'];
             $_delivery = $Row['deliveryId'];
             $_sales_tax = $Row['salesTax'];
             $_incidental_chrg = $Row['incidental_chrg'];
             $frgt = $Row['freight_tag'];
             $frgp = $Row['freight_percent'];
             $frga = $Row['freight_amount'];
             $_credit_period = $Row['credit_period'];
             $_ed_edu_tag = $Row['ed_edu_tag'];
             $_cvd = $Row['cvd_percent'];
             $_remarks = $Row['remarks'];
             $_principal_name = $Row['Principal_Supplier_Name'];
             $_itmes = Quotation_Details_Model::LoadQuotationDetails($Row['quotId']);
             $sale_tax_text=''; //$Row['SALESTAX_DESC'];
             $edu_text =''; //$Row['ed'];
             $Delivery_text ='';// $Row['del'];
            $newObj = new Quotation_Model($_quotation_id,$_quotation_no,$_parent_quotation_no,$_coustomer_ref_no,$_coustomer_ref_date, $_quotation_date,$_buyer_id,$_cust_id,$_coustomer_name,$_coustomer_add,$_contact_persone,$_principal_id,$_discount,$_delivery,$_sales_tax,$_incidental_chrg,$frgt,$frgp,$frga,$_credit_period,$_ed_edu_tag,$_cvd,$_remarks,$_principal_name,$_itmes,$sale_tax_text,$edu_text,$Delivery_text);
            $objArray[$i] = $newObj;
            $i++;
        }
        return $objArray;
	}
	
	
	
	// function added due to search improvement 23-12-2015
	  public static function SearchQuotations($Fromdate,$Todate,$buyerid,$principalid,$quotno,$quotStatus,$executive,$count,$start,$rp){
        $Query = "";
       	$opt1="";
	
				
				if($quotno!=""){				
					
					 if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
							$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$quotno' AND qut.userId='".$_SESSION['USER']."' LIMIT $start , $rp "; 
							
						}else{
							
							$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$quotno' LIMIT $start , $rp "; 
							
						}
				}else{
						if($buyerid!=""){				
						  $opt1=$opt1."AND qut.BuyerId='$buyerid' ";
						 
						}
						if($quotStatus!=""){
								if($quotStatus == "All"){
									$opt1=$opt1."AND qut.quotNo NOT IN (SELECT  q.`quotNo` FROM quotation as q INNER JOIN quotation_detail as qd ON qd.quotId = q.quotId WHERE NOT EXISTS (SELECT * FROM purchaseorder_detail pd WHERE pd.po_quotNo = q.quotNo AND pd.po_codePartNo = qd.code_part_no AND pd.po_quotNo is not null AND pd.po_quotNo != '0' AND pd.po_quotNo != ''))";
									
								}else if($quotStatus == "Partially"){
									$opt1=$opt1."AND qut.quotNo NOT IN (SELECT * FROM ((SELECT qut.quotNo FROM quotation as qut WHERE qut.quotNo NOT IN (SELECT po_quotNo FROM purchaseorder_detail where po_quotNo  is not null and po_quotNo != '0' AND po_quotNo != '')) UNION ALL (SELECT qut.quotNo FROM quotation as qut WHERE  qut.quotDate BETWEEN '$Fromdate' AND '$Todate' AND qut.quotNo NOT IN (SELECT  q.`quotNo` FROM quotation as q INNER JOIN quotation_detail as qd ON qd.quotId = q.quotId WHERE NOT EXISTS (SELECT pd.po_quotNo FROM purchaseorder_detail pd WHERE pd.po_quotNo = q.quotNo AND pd.po_codePartNo = qd.code_part_no AND pd.po_quotNo is not null AND pd.po_quotNo != '0' AND pd.po_quotNo != '')))) as s )";
								
								}else if($quotStatus == "NotReceived"){
									$opt1=$opt1."AND qut.quotNo NOT IN (SELECT po_quotNo FROM purchaseorder_detail where po_quotNo is not null and po_quotNo != '0' and po_quotNo != '')";
									
								}
											 
						} 
						if($principalid!=""){			
							$opt1=$opt1."AND qut.principalId=$principalid";
									
						}							
									
						if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
							$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$Fromdate' AND '$Todate' AND qut.userId='".$_SESSION['USER']."' $opt1 LIMIT $start , $rp "; 
						
						}else{
							if($executive!=""){			
								$opt1=$opt1."AND qut.userId='$executive'";	
								
							}
							$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE  qut.quotDate BETWEEN '$Fromdate' AND '$Todate' $opt1 LIMIT $start , $rp "; 
							
						}				
				}
				
		//echo $Query; exit;
       
		$Result = DBConnection::SelectQuery($Query);
        $objArray = array();
        $i = 0;                                     
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_quotation_id = $Row['quotId'];
             $_quotation_no = $Row['quotNo'];
             //$_parent_quotation_no = $Row['pquotNo'];
			 $_parent_quotation_no = $Row['quotNo'];
             $_coustomer_ref_no = $Row['cust_ref_no'];
             $_coustomer_ref_date= date("d/m/Y", strtotime($Row['cust_ref_date']));
             $_quotation_date=date("d/m/Y", strtotime($Row['quotDate']));
             $_buyer_id = $Row['BuyerId'];
             $_cust_id = $Row['cust_id'];
             if($_buyer_id > 0)
             {
                 $BuyerQuery = "select buyer_master.BuyerId, buyer_master.BuyerName, buyer_master.Bill_Add1, buyer_master.Bill_Add2, city_master.CityName, location_master.LocationName, state_master.StateName from buyer_master inner join city_master ON city_master.CityId = buyer_master.CityId inner join location_master ON location_master.LocationId = buyer_master.LocationId inner join state_master ON state_master.StateId = buyer_master.StateId WHERE buyer_master.BuyerId = $_buyer_id";
                 $BuyerResult = DBConnection::SelectQuery($BuyerQuery);
                 $BuyerRow = mysql_fetch_array($BuyerResult, MYSQL_ASSOC);
                 $_coustomer_name = $BuyerRow['BuyerName'];
                 $_coustomer_add = $BuyerRow['Bill_Add1'] . " ". $BuyerRow['Bill_Add2']." , ".$BuyerRow['LocationName']." , ".$BuyerRow['CityName'];
             }
             else if($_cust_id > 0)
             {
                 $CustomerQuery = "SELECT  cust_name, cust_add FROM cust_master WHERE cust_id = $_cust_id";
                 $CustomerResult = DBConnection::SelectQuery($CustomerQuery);
                 $CustomerRow = mysql_fetch_array($CustomerResult, MYSQL_ASSOC);
                 $_coustomer_name = $CustomerRow['cust_name'];
                 $_coustomer_add = $CustomerRow['cust_add'];
             }
             $_contact_persone = $Row['contact_person'];
             $_principal_id = $Row['principalId'];
             $_discount = $Row['discount'];
             $_delivery = $Row['deliveryId'];
             $_sales_tax = $Row['salesTax'];
             $_incidental_chrg = $Row['incidental_chrg'];
             $frgt = $Row['freight_tag'];
             $frgp = $Row['freight_percent'];
             $frga = $Row['freight_amount'];
             $_credit_period = $Row['credit_period'];
             $_ed_edu_tag = $Row['ed_edu_tag'];
             $_cvd = $Row['cvd_percent'];
             $_remarks = $Row['remarks'];
             $_principal_name = $Row['Principal_Supplier_Name'];
             $_itmes = Quotation_Details_Model::LoadQuotationDetails($Row['quotId']);
             $sale_tax_text=''; //$Row['SALESTAX_DESC'];
             $edu_text =''; //$Row['ed'];
             $Delivery_text ='';// $Row['del'];
            $newObj = new Quotation_Model($_quotation_id,$_quotation_no,$_parent_quotation_no,$_coustomer_ref_no,$_coustomer_ref_date, $_quotation_date,$_buyer_id,$_cust_id,$_coustomer_name,$_coustomer_add,$_contact_persone,$_principal_id,$_discount,$_delivery,$_sales_tax,$_incidental_chrg,$frgt,$frgp,$frga,$_credit_period,$_ed_edu_tag,$_cvd,$_remarks,$_principal_name,$_itmes,$sale_tax_text,$edu_text,$Delivery_text);
            $objArray[$i] = $newObj;
            $i++;
        }
        return $objArray;
	}
	
	
	// function added due to search improvement 23-12-2015
	  public static function QuotCountRecords($Fromdate,$Todate,$buyerid,$principalid,$quotno,$quotStatus,$executive,$count,$start,$rp){
     
        $CountQuery = "";
		
		$opt2="";
				
				if($quotno!=""){				
					
					 if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
							
							$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$quotno' AND qut.userId='".$_SESSION['USER']."'"; 
						}else{
							
							
							$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotNo = '$quotno'"; 
						}
				}else{
						if($buyerid!=""){				
						
						  $opt2=$opt2."AND qut.BuyerId='$buyerid' ";
						}
						if($quotStatus!=""){
								if($quotStatus == "All"){
									
									$opt2=$opt2."AND qut.quotNo NOT IN (SELECT  q.`quotNo` FROM quotation as q INNER JOIN quotation_detail as qd ON qd.quotId = q.quotId WHERE NOT EXISTS (SELECT * FROM purchaseorder_detail pd WHERE pd.po_quotNo = q.quotNo AND pd.po_codePartNo = qd.code_part_no AND pd.po_quotNo is not null AND pd.po_quotNo != '0' AND pd.po_quotNo != ''))";	
								}else if($quotStatus == "Partially"){
									
									$opt2=$opt2."AND qut.quotNo NOT IN (SELECT * FROM ((SELECT qut.quotNo FROM quotation as qut WHERE qut.quotNo NOT IN (SELECT po_quotNo FROM purchaseorder_detail where po_quotNo  is not null and po_quotNo != '0' and po_quotNo != '')) UNION ALL (SELECT qut.quotNo FROM quotation as qut WHERE  qut.quotDate BETWEEN '2015-08-01' AND '2015-12-24' AND qut.quotNo NOT IN (SELECT  q.`quotNo` FROM quotation as q INNER JOIN quotation_detail as qd ON qd.quotId = q.quotId WHERE NOT EXISTS (SELECT pd.po_quotNo FROM purchaseorder_detail pd WHERE pd.po_quotNo = q.quotNo AND pd.po_codePartNo = qd.code_part_no AND pd.po_quotNo is not null AND pd.po_quotNo != '0' AND pd.po_quotNo != '')))) as s )";
								}else if($quotStatus == "NotReceived"){
									
									$opt2=$opt2."AND qut.quotNo NOT IN (SELECT po_quotNo FROM purchaseorder_detail where po_quotNo is not null and po_quotNo != '0' and po_quotNo != '')";	
								}
											 
						} 
						if($principalid!=""){			
							
							$opt2=$opt2."AND qut.principalId=$principalid";					
						}							
									
						if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
							
							$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$Fromdate' AND '$Todate' AND qut.userId='".$_SESSION['USER']."' $opt2"; 
						}else{
							if($executive!=""){			
								$opt2=$opt2."AND qut.userId='$executive'";					
							}
							
							$CountQuery = "SELECT count(*) as total FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id WHERE qut.quotDate BETWEEN '$Fromdate' AND '$Todate' $opt2"; 
						}				
				}
				
		//echo $CountQuery; exit;
        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];		
        return $count;
	}
	
	
	// Download search Quotation As PDF OR EXCEL
	 public static function downloadQuotation($todate,$fromdate,$buyerId,$principalId,$quotno){
        $Query = "";
        $CountQuery = "";
		
		$opt='';
   	    $opt1='';
		session_start();
		$TYPE = $_SESSION["USER_TYPE"];
   	    if(($fromdate!="") && ($todate!=""))
		{
			 $opt=$opt." WHERE qut.quotDate >= '$fromdate' AND qut.quotDate<='$todate' ";
			 $opt1=$opt1." WHERE qut.quotDate>='$fromdate' AND qut.quotDate<='$todate' ";	
		} 
		if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE'] =="E"){
			$opt=$opt."AND qut.userId='".$_SESSION['USER']."'";
			$opt1=$opt1."AND qut.userId='".$_SESSION['USER']."'";
			
		}
		
		if($quotno!=NULL ||$quotno!="")
		{
			$opt=$opt." AND qut.quotNo='$quotno'";
			$opt1=$opt1." AND qut.quotNo='$quotno'";	
		}
		
		if($buyerId!=NULL ||$buyerId!="")
		{
			$opt=$opt."AND qut.BuyerId='$buyerid'";
		    $opt1=$opt1."AND qut.BuyerId='$buyerid'";	
		}
		if($principalId!=NULL ||$principalId!="")
		{
			$opt=$opt."AND qut.principalId='$principalId'";
		    $opt1=$opt1."AND qut.principalId='$principalId'";	
		}  
		$Query = "SELECT qut.*,psm.Principal_Supplier_Name FROM quotation as qut INNER JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id $opt"; 
		
             
		$Result = DBConnection::SelectQuery($Query);
        $objArray = array();
        $i = 0;                                     
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_quotation_id = $Row['quotId'];
             $_quotation_no = $Row['quotNo'];
             //$_parent_quotation_no = $Row['pquotNo'];
			 $_parent_quotation_no = $Row['quotNo'];
             $_coustomer_ref_no = $Row['cust_ref_no'];
             $_coustomer_ref_date= date("d/m/Y", strtotime($Row['cust_ref_date']));
             $_quotation_date=date("d/m/Y", strtotime($Row['quotDate']));
             $_buyer_id = $Row['BuyerId'];
             $_cust_id = $Row['cust_id'];
             if($_buyer_id > 0)
             {
                 $BuyerQuery = "select buyer_master.BuyerId, buyer_master.BuyerName, buyer_master.Bill_Add1, buyer_master.Bill_Add2, city_master.CityName, location_master.LocationName, state_master.StateName from buyer_master inner join city_master ON city_master.CityId = buyer_master.CityId inner join location_master ON location_master.LocationId = buyer_master.LocationId inner join state_master ON state_master.StateId = buyer_master.StateId WHERE buyer_master.BuyerId = $_buyer_id";
                 $BuyerResult = DBConnection::SelectQuery($BuyerQuery);
                 $BuyerRow = mysql_fetch_array($BuyerResult, MYSQL_ASSOC);
                 $_coustomer_name = $BuyerRow['BuyerName'];
                 $_coustomer_add = $BuyerRow['Bill_Add1'] . " ". $BuyerRow['Bill_Add2']." , ".$BuyerRow['LocationName']." , ".$BuyerRow['CityName'];
             }
             else if($_cust_id > 0)
             {
                 $CustomerQuery = "SELECT  cust_name, cust_add FROM cust_master WHERE cust_id = $_cust_id";
                 $CustomerResult = DBConnection::SelectQuery($CustomerQuery);
                 $CustomerRow = mysql_fetch_array($CustomerResult, MYSQL_ASSOC);
                 $_coustomer_name = $CustomerRow['cust_name'];
                 $_coustomer_add = $CustomerRow['cust_add'];
             }
             $_contact_persone = $Row['contact_person'];
             $_principal_id = $Row['principalId'];
             $_discount = $Row['discount'];
             $_delivery = $Row['deliveryId'];
             $_sales_tax = $Row['salesTax'];
             $_incidental_chrg = $Row['incidental_chrg'];
             $frgt = $Row['freight_tag'];
             $frgp = $Row['freight_percent'];
             $frga = $Row['freight_amount'];
             $_credit_period = $Row['credit_period'];
             $_ed_edu_tag = $Row['ed_edu_tag'];
             $_cvd = $Row['cvd_percent'];
             $_remarks = $Row['remarks'];
             $_principal_name = $Row['Principal_Supplier_Name'];
             $_itmes = Quotation_Details_Model::LoadQuotationDetails($Row['quotId']);
            
             $sale_tax_text=''; //$Row['SALESTAX_DESC'];
             $edu_text =''; //$Row['ed'];
             $Delivery_text ='';// $Row['del'];
            $newObj = new Quotation_Model($_quotation_id,$_quotation_no,$_parent_quotation_no,$_coustomer_ref_no,$_coustomer_ref_date, $_quotation_date,$_buyer_id,$_cust_id,$_coustomer_name,$_coustomer_add,$_contact_persone,$_principal_id,$_discount,$_delivery,$_sales_tax,$_incidental_chrg,$frgt,$frgp,$frga,$_credit_period,$_ed_edu_tag,$_cvd,$_remarks,$_principal_name,$_itmes,$sale_tax_text,$edu_text,$Delivery_text);
            $objArray[$i] = $newObj;
            $i++;
        }
        return $objArray;
	}
	
    public static function UpdateQuotation($Quotation_ID, $pquotNo, $cust_ref_no,$cust_ref_date, $BuyerId, $cust_id,$contact_person,$principalId,$discount,$deliveryId,$salesTax,$freight,$incidental_chrg,$credit_period,$ed_edu_tag,$_cvd,$remarks){
		
		//added on 03-JUNE-2016 due to Handle Special Character
        $cust_ref_no = mysql_escape_string($cust_ref_no);
        $contact_person = mysql_escape_string($contact_person);
		$credit_period = mysql_escape_string($credit_period);
		$remarks = mysql_escape_string($remarks);
		
		$Query = "UPDATE quotation SET pquotNo = '$pquotNo', cust_ref_no = '".$cust_ref_no."',cust_ref_date = '$cust_ref_date', BuyerId = $BuyerId, cust_id = $cust_id, contact_person = '".$contact_person."', principalId = '$principalId', discount = '$discount', deliveryId = '$deliveryId', salesTax = '$salesTax', freight = '$freight', incidental_chrg = '$incidental_chrg', credit_period = '$credit_period', ed_edu_tag = '$ed_edu_tag', cvd_percent = '$_cvd', remarks = '".$remarks."' WHERE quotId = '$Quotation_ID'";
		//echo($Query); 
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
    
    public static function DeleteQuotation($quotationId){
        $Query = "DELETE FROM quotation WHERE quotId = $quotationId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    
    public static function FIND_QNO_IN_PO($Quotation_Number){
        $Query = "select count(*) as num from purchaseorder_detail where po_quotNo = '$Quotation_Number'"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["num"];
    }
	

		// function to get all PO related to Quotation.
	public static function quotationPoList($quotId,$quotNo)
    {		
		$DATA = "";	
		$Query = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,pod.po_qty,pod.po_codePartNo,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po
		INNER JOIN purchaseorder_detail as pod ON pod.bpoId = po.bpoId
		INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
		WHERE pod.po_quotNo  ='$quotNo' "; 		
		$result = DBConnection::SelectQuery($Query);
		//$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		 $objArray = array();
		 $i = 0;		
			while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				  $objArray[$i]['bpoId'] = $Row['bpoId'];
				  $objArray[$i]['bpono'] = $Row['bpono'];
				  $objArray[$i]['po_val'] = $Row['po_val'];
				  $objArray[$i]['bpoDate'] = $Row['bpoDate'];
				  $objArray[$i]['po_qty'] = $Row['po_qty'];
				  $objArray[$i]['bpoType'] = $Row['bpoType'];
				  $objArray[$i]['po_codePartNo'] = $Row['po_codePartNo'];
				  $objArray[$i]['Item_Code_Partno'] = $Row['Item_Code_Partno'];			  
				  $objArray[$i]['Item_Desc'] = $Row['Item_Desc'];
				   
				  $i++;
			}
			
			return $objArray;
		
    }
}

class Quotation_Details_Model
{
    public $_quotation_id;
    public $_item_code_part_no;
    public $_unit_id;
    public $_quantity;
    public $_price_per_unit;
    public $_unit_name;
    public $_item_descp;
    public $itemid;
	public $_hsn_code;
	public $_cgst_rate;
	public $_sgst_rate;
	public $_igst_rate;
	
    //public function __construct($quotationid,$itemcodepartno,$unitid,$quantity,$priceperunit,$unitname,$itemdescp,$itemid){
    public function __construct($quotationid, $itemcodepartno, $unitid, $quantity, $priceperunit, $unitname, $item_discount, $hsn_code,  $cgst_rate, $sgst_rate, $igst_rate,$itemdescp,$itemid){
        $this->_quotation_id = $quotationid;
        $this->_item_code_part_no = $itemcodepartno;
        $this->_unit_id = $unitid;
        $this->_quantity = $quantity;
        $this->_price_per_unit = $priceperunit;
        $this->_unit_name = $unitname;
		$this->_item_discount = $item_discount;
		$this->_hsn_code = $hsn_code;
		$this->_cgst_rate = $cgst_rate;
		$this->_sgst_rate = $sgst_rate;
		$this->_igst_rate = $igst_rate;
        $this->_item_descp = $itemdescp;
        $this->itemid = $itemid;
	}
    //public static function InsertQuotationDetails($quotId,$code_part_no,$unitId,$qty,$rate_perUnit){
	public static function InsertQuotationDetails($quotId, $code_part_no, $unitId, $item_discount, $hsn_code, $cgst_rate, $sgst_rate, $igst_rate, $qty, $rate_perUnit){
		//added on 03-JUNE-2016 due to Handle Special Character
        $code_part_no = mysql_escape_string($code_part_no);
		$item_discount = empty($item_discount)?'NULL' :$item_discount;
		$rate_perUnit = empty($rate_perUnit)?'NULL' :$rate_perUnit;
		        //$Query = "INSERT INTO quotation_detail (quotId, code_part_no, unitId, qty, rate_perUnit) VALUES ($quotId, '".$code_part_no."',$unitId,$qty,$rate_perUnit)"; 
		$Query = "INSERT INTO quotation_detail (quotId, code_part_no, unitId, item_discount, hsn_code, cgst_rate, sgst_rate, igst_rate, qty, rate_perUnit) VALUES ($quotId, '".$code_part_no."', $unitId, $item_discount, '".$hsn_code."', $cgst_rate, $sgst_rate, $igst_rate, $qty, $rate_perUnit)";
		
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function DeleteItem($quotId){
        $Query = "DELETE FROM quotation_detail WHERE quotId = $quotId"; 
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function GetItemList($Quotation_Id){
		$Query = "SELECT qd.*,um.UNITNAME,im.Item_Desc,im.Item_Code_Partno FROM quotation_detail as qd INNER JOIN unit_master um ON um.UNITID = qd.unitId INNER JOIN item_master as im ON im.ItemId = qd.code_part_no WHERE qd.quotId = $Quotation_Id"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function  LoadQuotationDetails($Quotation_Id)
	{
        if($Quotation_Id > 0){
            $result = self::GetItemList($Quotation_Id);
            $objArray = array();
            $i = 0;
            while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $_quotation_id = $Row['quotId'];
                $_item_code_part_no = $Row['Item_Code_Partno'];
                $_unit_id = $Row['unitId'];
                $_quantity = $Row['qty'];
                $_price_per_unit = $Row['rate_perUnit'];
                $_unit_name =  $Row['UNITNAME'];
				$_item_discount =  $Row['item_discount'];
				$_hsn_code =  $Row['hsn_code'];
				$_cgst_rate =  $Row['cgst_rate'];
				$_sgst_rate =  $Row['sgst_rate'];
				$_igst_rate =  $Row['igst_rate'];
                $_item_descp =  $Row['Item_Desc']; 
                $itemid = $Row['code_part_no'];;
               // $newObj = new Quotation_Details_Model($_quotation_id,$_item_code_part_no,$_unit_id,$_quantity,$_price_per_unit,$_unit_name,$_item_descp,$itemid);
				$newObj = new Quotation_Details_Model($_quotation_id,$_item_code_part_no,$_unit_id,$_quantity,$_price_per_unit,$_unit_name, $_item_discount, $_hsn_code, $_cgst_rate, $_sgst_rate, $_igst_rate,$_item_descp,$itemid);
                $objArray[$i] = $newObj;
                $i++;
            }
            return $objArray;   
        }
        
	}
}

class Cusotmer{
    
    public $_cust_id;
    public $_cust_name;
    public $_cust_add;
    public $_buyer_tag;
    public function __construct($custid,$custname,$custadd,$buyertag){
        $this->_cust_id = $custid;
        $this->_cust_name = $custname;
        $this->_cust_add = $custadd;
        $this->_buyer_tag = $buyertag;
	}
    public static function InsertCustomer($_cust_name,$_cust_add){
		
		//added on 03-JUNE-2016 due to Handle Special Character
        $cust_name = mysql_escape_string($cust_name);
        $_cust_add = mysql_escape_string($_cust_add);
		
        $Query = "INSERT INTO cust_master (cust_name, cust_add, buyer_tag) VALUES ('$_cust_name','$_cust_add','N')"; 
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function  LoadCustomerDetails($Customer_Id)
	{
        if($Customer_Id > 0){
            $result = self::GetCustomer($Customer_Id);
            $objArray = array();
            $i = 0;
            while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $_cust_id = $Row['cust_id'];
                $_cust_name = $Row['cust_name'];
                $_cust_add = $Row['cust_add'];
                $_buyer_tag = $Row['buyer_tag'];
                $newObj = new Cusotmer($_cust_id,$_cust_name,$_cust_add,$_buyer_tag);
                $objArray[$i] = $newObj;
                $i++;
            }
            return $objArray;   
        }
        
	}
    public static function GetCustomer($Customer_Id){
		$Query = "SELECT * FROM cust_master WHERE cust_id = $Customer_Id"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function UpdateCustomer($_cust_id,$_cust_name,$_cust_add,$_buyer_tag){
		
		//added on 03-JUNE-2016 due to Handle Special Character
        $_cust_name = mysql_escape_string($_cust_name);
        $_cust_add = mysql_escape_string($_cust_add); 
        $_buyer_tag = mysql_escape_string($_buyer_tag);
		
        $Query = "UPDATE cust_master SET cust_name = '".$_cust_name."', cust_add = '".$_cust_add."', buyer_tag = '".$_buyer_tag."' WHERE cust_id = $_cust_id"; 
        $Result = DBConnection::UpdateQuery($Query);
        if($Result == QueryResponse::SUCCESS){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
}

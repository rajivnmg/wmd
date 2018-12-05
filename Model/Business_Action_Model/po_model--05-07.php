<?php
require_once('root.php');
Logger::configure($root_path."config.xml");
$logger = Logger::getLogger('Query result');
class Purchaseorder_Model
{
        public $_bpoId;
        public $buyerid;
	    public $pon;
	    public $bn;
	    public $pod;
	    public $ipovd;
	    public $pot;
	    public $mode_delivery;
	    public $cp;
	    public $executiveId;
	    public $cd;
	    public $cdt;
	    public $addTag;
	    public $sadd1;
	    public $sadd2;
	    public $sstate1;
	    public $scity1;
	    public $scountry1;
	    public $slocation1;
	    public $spincode;
	    public $sphno;
	    public $smobno;
	    public $sfax;
	    public $semail;
	    public $poVal;
	    public $pf_chrg;
	    public $inci_chrg;
	    public $frgt1;
	    public $frgt;
	    public $frgtp;
	    public $frgta;
	    public $_management_approval;
	    public $_approval_status;
	    public $_po_status;
	    public $rem;
	    public $UserId;
	    public $InsertDate;
	    public $_items = array();
	    public $buyerName;
	    public $oig_status;		
		public $ms;
		public $bemailId;
		public $po_hold_state;
	    public $po_hold_reason;	
	    public $ins_charge;	
	    public $othc_charge;	
	    
	    
	    public function __construct($bpoId,$buyerid,$pon,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId,
	    $cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$po_shiphno,$po_shipMobno,$po_shipFax,$po_shipMail,$po_val,
	    $pf_chrg,$incidental_chrg,$freight_tag1,$freight_tag,$freight_percent,$freight_amount,$management_approval,
	    $approval_status,$po_status,$ms,$rem,$UserId,
	    $InsertDate,$_items,$buyerName,$oig_status,$bemailId=null,$po_hold_state=null,$po_hold_reason=null,$ins_charge=null,$othc_charge=null)
	    {
	        $this->_bpoId=$bpoId;
	        $this->bn=$buyerid;
	        $this->buyerid=$buyerid;
	        $this->pon=$pon;
	        $this->pod=$bpoDate;
	        $this->ipovd=$bpoVDate;
	        $this->pot=$bpoType;
	        $this->mode_de=$mode_delivery;
	        $this->cp=$credit_period;
	        $this->exec=$executiveId;
	        $this->cd=$cash_discount_tag;
	        $this->cdt=$cash_discount_value;
	        $this->addTag=$bill_ship_address_same_tag;
	        $this->sadd1=$po_shipadd1;
	        $this->sadd2=$po_shipadd2;
	        $this->sstate1=$po_shipStateId;
	        $this->scity1=$po_shipCityId;
	        $this->scountry1=$po_shipCountryId;
	        $this->slocation1=$po_shipLocationId;
	        $this->spincode=$po_shipPincode;
	        $this->sphno=$po_shiphno;
	        $this->smobno=$po_shipMobno;
	        $this->sfax=$po_shipFax;
	        $this->semail=$po_shipMail;
	        $this->poVal=$po_val;
	        $this->pf_chrg=$pf_chrg;
	        $this->inci_chrg=$incidental_chrg;
	        $this->frgt1=$freight_tag1;
	        $this->frgt=$freight_tag;
	        $this->frgtp=$freight_percent;
	        $this->frgta=$freight_amount;
	        $this->_management_approval=$management_approval;
	        $this->_approval_status=$approval_status;
	        $this->_po_status=$po_status;
	        $this->rem=$rem;
	        $this->_items = $_items;
            $this->bn_name=$buyerName;
            $this->oig_status=$oig_status;
			$this->ms=$ms;
			$this->bemailId = $bemailId;
			$this->po_hold_state = $po_hold_state;
			$this->po_hold_reason = $po_hold_reason;
			$this->ins_charge = $ins_charge;
			$this->othc_charge = $othc_charge;
    }
    public static function GetPurchaseDetails($bpono){
		$Query = "select * from purchaseorder where bpono = $bpono";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetPurchaseList(){
		$Query = "select * from purchaseorder";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
        public static function GetNewPoByDisplayPO($DISPLAYID,$year){
        $Query = "select new_bpoId from po_excise_nonexcise_mapping where old_bpoId = $DISPLAYID  and finyear = '".$year."'"; 
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["new_bpoId"] > 0)
        {
            return $Row["new_bpoId"];
        }
        else 
            return 0;
    }
    public static function GetDisplayPOByNewPo($INNERPO){
        $Query = "select display_pono from po_excise_nonexcise_mapping where inner_pono = '".$INNERPO."';";
        //echo $Query;
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["display_pono"] == "")
            return $INNERPO;
        else
            return $Row["display_pono"];
    }
public static function  LoadPurchaseByID($bpono,$po_ed_applicability)
		{
	        $Query = "select po.*,bm.BuyerName from purchaseorder as po,buyer_master as bm where po.BuyerId=bm.BuyerId and bpoId = $bpono";
            //echo($Query); exit;
		    $Result = DBConnection::SelectQuery($Query);
			$objArray = array();
			$i = 0;
			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
	            $bpoId= $Row['bpoId'];
                 
	            $buyerid=$Row['BuyerId'];
                $buyerName=$Row['BuyerName'];
	            $bpono =  self::GetDisplayPOByNewPo($Row['bpono']);//$Row['bpono']; //
                
	            $bpoDate = $Row['bpoDate'];
	            $bpoVDate= $Row['bpoVDate'];
				
				$ms = $Row['msid'];			
	            $bpoDate1 = MultiweldParameter::xFormatDate1($bpoDate);
	            $bpoVDate1 = MultiweldParameter::xFormatDate1($bpoVDate);
	            $bpoType = $Row['bpoType'];
	            $mode_delivery= $Row['mode_delivery'];
	            $credit_period= $Row['credit_period'];
	            $executiveId = $Row['executiveId'];
	            $cash_discount_tag = $Row['cash_discount_tag'];
	            $cash_discount_value = $Row['cash_discount_value'];
	            $bill_ship_address_same_tag=$Row['bill_ship_address_same_tag'];
	            $po_shipadd1 = $Row['po_shipadd1'];
	            $po_shipadd2 = $Row['po_shipadd2'];
	            $po_shipStateId = $Row['po_shipStateId'];
	            $po_shipCityId = $Row['po_shipCityId'];
	            $po_shipCountryId = $Row['po_shipCountryId'];
	            $po_shipLocationId = $Row['po_shipLocationId'];
	            $po_shipPincode = $Row['po_shipPincode'];
	            $po_shiphno=$Row['po_phoneno'];
	            $po_shipMobno=$Row['po_mobno'];
	            $po_shipFax=$Row['po_fax'];
	            $po_shipMail=$Row['po_email'];
	            $po_val=$Row['po_val'];
	            $pf_chrg = $Row['pf_chrg'];
	            $incidental_chrg = $Row['incidental_chrg'];
	            $freight_tag = $Row['freight_tag'];				
	            $freight_tag1=$freight_tag;
	            $freight_percent = $Row['freight_percent'];
	            $freight_amount = $Row['freight_amount'];
	            if($freight_tag=="a" ||$freight_tag=="A")
	            {
				  $rev_freight_amount=self::invoiceRevFreightAmountNew($bpoId);
				  if($rev_freight_amount>0)
				  {
				  	$freight_amount=0.00;
				  }
				}
	            $management_approval = $Row['management_approval'];
	            $approval_status = $Row['approval_status'];
	            $po_status = $Row['po_status'];
	            $Remarks = $Row['Remarks'];
	            $Remarks1 =html_entity_decode($Remarks);
	            $UserId = $Row['UserId'];
	            $InsertDate = $Row['InsertDate'];
	            $bemailId = $Row['po_ack_email'];
	            $po_hold_state = $Row['po_state'];
	            $po_hold_reason = $Row['po_hold_reason'];
	            $ins_charge = $Row['insurance_charge'];
	            $othc_charge = $Row['other_charge'];
	           
	            $_itmes = purchaseorder_Details_Model::LoadPO($Row['bpoId'],$po_ed_applicability,$bpoType);      
				//print_r($_itmes);exit;
				$oig_status= self::invoiceGeneratedNew($Row['bpoId']);
	            $newObj = new Purchaseorder_Model($bpoId,$buyerid,$bpono,$bpoDate1,$bpoVDate1,$bpoType,$mode_delivery,$credit_period,$executiveId,$cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$po_shiphno,$po_shipMobno,$po_shipFax,$po_shipMail,$po_val,$pf_chrg,$incidental_chrg,$freight_tag1,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$ms,$Remarks1,$UserId,$InsertDate,$_itmes,$buyerName,$oig_status,$bemailId,$po_hold_state,$ins_charge,$ins_charge,$othc_charge);
	            $objArray[$i] = $newObj;
	            $i++;
			}
			//print_r($objArray);exit;
			return $objArray;
	}
	
	public function invoiceRevFreightAmount($bpoId)
	{
	   $freight_amount=0; 	
	   $sql_sel="SELECT SUM(freight_amount) AS freight_amount FROM `outgoinginvoice_excise` WHERE pono='$bpoId' UNION ALL SELECT SUM(freight_amount) AS freight_amount FROM `outgoinginvoice_nonexcise` WHERE pono='$bpoId'";
	   $Result = DBConnection::SelectQuery($sql_sel);
	   while($row=mysql_fetch_row($Result))
	   {
	   	$freight_amount=$freight_amount+$row[0];
	   }
	   return $freight_amount;	
	}
	
	public static function invoiceRevFreightAmountNew($bpoId)
	{ // fiunction created  by Rajiv 0n 13-8-15 due to call of non-static method in static method.
	   $freight_amount=0; 	
	   $sql_sel="SELECT SUM(freight_amount) AS freight_amount FROM `outgoinginvoice_excise` WHERE pono='$bpoId' UNION ALL SELECT SUM(freight_amount) AS freight_amount FROM `outgoinginvoice_nonexcise` WHERE pono='$bpoId'";
	   $Result = DBConnection::SelectQuery($sql_sel);
	   while($row=mysql_fetch_row($Result))
	   {
	   	$freight_amount=$freight_amount+$row[0];
	   }
	   return $freight_amount;	
	}
	
	public static function invoiceGeneratedNew($bpono)
	{ // fiunction created  by Rajiv 0n 13-8-15 due to call of non-static method in static method.
	   $sql_sel="SELECT COUNT(*) AS cnt FROM purchaseorder_detail WHERE po_item_stage='OIG' AND  bpoId='$bpono'";
	   $Result = DBConnection::SelectQuery($sql_sel);
	   $row=mysql_fetch_row($Result);
	   return $row[0];	
	}
	
	public function invoiceGenerated($bpono)
	{
	   $sql_sel="SELECT COUNT(*) AS cnt FROM purchaseorder_detail WHERE po_item_stage='OIG' AND  bpoId='$bpono'";
	   $Result = DBConnection::SelectQuery($sql_sel);
	   $row=mysql_fetch_row($Result);
	   return $row[0];	
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
	            $buyerid=$Row['BuyerId'];

	            $bpono = $Row['bpono'];
	            $bpoDate = $Row['bpoDate'];
	            $bpoVDate= $Row['bpoVDate'];
	            $bpoType = $Row['bpoType'];
	            $mode_delivery= $Row['mode_delivery'];
	            $credit_period= $Row['credit_period'];
	            $executiveId = $Row['executiveId'];
	            $cash_discount_tag = $Row['cash_discount_tag'];
	            $cash_discount_value = $Row['cash_discount_value'];
	            $bill_ship_address_same_tag=$Row['bill_ship_address_same_tag'];
	            $po_shipadd1 = $Row['po_shipadd1'];
	            $po_shipadd2 = $Row['po_shipadd2'];
	            $po_shipStateId = $Row['po_shipStateId'];
	            $po_shipCityId = $Row['po_shipCityId'];
	            $po_shipCountryId = $Row['po_shipCountryId'];
	            $po_shipLocationId = $Row['po_shipLocationId'];
	            $po_shipPincode = $Row['po_shipPincode'];
	            $po_shiphno=$Row['po_phoneno'];
	            $po_shipMobno=$Row['po_mobno'];
	            $po_shipFax=$Row['po_fax'];
	            $po_shipMail=$Row['po_email'];
	            $po_val=$Row['$po_val'];
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
	            $bemailId = $Row['po_ack_email'];
	            $po_hold_state = $Row['po_state'];
	            $po_hold_reason = $Row['po_hold_reason'];
	            $_itmes = purchaseorder_Details_Model::LoadPO($Row['bpoId'],$po_ed_applicability,$bpoType);
	            $newObj = new Purchaseorder_Model($bpoId,$buyerid,$bpono,$bpoDate,$bpoVDate,$bpoType,$mode_delivery,$credit_period,$executiveId,$cash_discount_tag,$cash_discount_value,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipStateId,$po_shipCityId,$po_shipCountryId,$po_shipLocationId,$po_shipPincode,$po_shiphno,$po_shipMobno,$po_shipFax,$po_shipMail,$po_val,$pf_chrg,$incidental_chrg,$freight_tag1,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$Remarks,$UserId,$InsertDate,$_itmes,$bemailId,$po_hold_state,$po_hold_reason);
	            $objArray[$i] = $newObj;
	            $i++;
			}
			return $objArray;
	}
     public static function insertDateTime(){
		$dt = new DateTime();
        return $dt->format('Y-m-d H:i:s');
	}


    //public static function InsertPO($bn,$mode_delivery, $pon, $cp, $pod,$exec,$ipovd,$cd,$cdt,$pot,$addTag,$sadd1,$sadd2,$scountry1,$sstate1,$scity1,$slocation1,$spincode,$sphno,$smobno,$sfax,$semail,$po_val,$pf_chrg,$inci_chrg,$frgt,$frgp,$frga,$management_approval,$approval_status,$po_status,$msid,$rem,$userId,$bemailId,$po_hold_state,$po_hold_reason){
	public static function InsertPO($bn,$mode_delivery, $pon, $cp, $pod, $exec, $ipovd, $cd, $cdt, $pot, $addTag, $sadd1, $sadd2,$scountry1, $sstate1, $scity1, $slocation1, $spincode, $sphno, $smobno, $sfax, $semail, $po_val, $pf_chrg, $inci_chrg, $frgt,$frgp, $frga, $management_approval, $approval_status, $po_status, $msid, $rem, $userId, $bemailId, $po_hold_state, $po_hold_reason, $ins_charge, $othc_charge){
		if($cdt == NULL){
			$cdt = 'NULL';
		}
		if($sstate1 == NULL){
			$sstate1 = 'NULL';
		}
		if($po_val == NULL){
			$po_val = 'NULL';
		}
    	if($pf_chrg==NULL){
			$pf_chrg=0.00;
		}
		if($inci_chrg==NULL){
			$inci_chrg=0.00;
		}
		if($ins_charge == NULL){
			$ins_charge = 0.00;
		}
		if($othc_charge == NULL){
			$othc_charge = 0.00;
		}
    	if($frgt == "A"){
		   $frgp = 0.00;
		}else if($frgt == "P"){
			$frga = 0.00;
		}else{
			$frgp =0.00;
			$frga =0.00;
		}
		if($cd==true){
			$cd="Y";
		}else{
			$cd="N";
			$cdt='NULL';
		}
		if($addTag==true){
			$addTag="Y";
		}else{
			$addTag="N";
		}
		if($po_hold_state==true){
			$po_hold_state="H";
		}else{
			$po_hold_state="U";
		}
		//echo $pod;

		$pod1= MultiweldParameter::xFormatDate($pod);
		$ipovd1=MultiweldParameter::xFormatDate($ipovd);
		$date=  date("Y-m-d");  // self::insertDateTime();
		$remrks = htmlentities($rem);
        $currentyear = MultiweldParameter::GetFinancialYear_fromTXT();
        $QueryMap = "INSERT INTO po_excise_nonexcise_mapping (old_bpoId,finyear, inner_pono, display_pono, tag, dataFlag) VALUES (0,'$currentyear', '$pon', '', '', 'N');";
        $ResultMap = DBConnection::InsertQuery($QueryMap);
       /*  $Query = "INSERT INTO purchaseorder(bpoId,BuyerId,bpono,bpoDate,bpoVDate,bpoType,mode_delivery,credit_period,executiveId,cash_discount_tag,cash_discount_value,bill_ship_address_same_tag,po_shipadd1,po_shipadd2,po_shipStateId,po_shipCityId,po_shipCountryId,po_shipLocationId,po_shipPincode,po_phoneno,po_mobno,po_fax,po_email,po_val,pf_chrg,incidental_chrg,freight_tag,freight_percent,freight_amount,management_approval,approval_status,po_status,msid,Remarks,UserId ,InsertDate) VALUES ($ResultMap,$bn, '$pon', '$pod1', '$ipovd1', '$pot', '$mode_delivery', $cp,'$exec','$cd',$cdt,'$addTag','$sadd1','$sadd2','$sstate1','$scity1','$scountry1','$slocation1','$spincode','$sphno','$smobno','$sfax','$semail','$po_val','$pf_chrg','$inci_chrg','$frgt','$frgp','$frga','$management_approval','$approval_status','$po_status','$msid','$remrks','$userId','$date')"; */
	    
	    //added on 03-JUNE-2016 due to Handle Special Character
        $sadd1 = mysql_escape_string($sadd1);
        $sadd2 = mysql_escape_string($sadd2);
        $spincode = mysql_escape_string($spincode);
        $smobno = mysql_escape_string($smobno);
        $sfax = mysql_escape_string($sfax);
        $semail = mysql_escape_string($semail);
        $bemailId = mysql_escape_string($bemailId);
        $po_hold_reason = mysql_escape_string($po_hold_reason);	    
	    
	    //$Query = "INSERT INTO purchaseorder(bpoId,BuyerId,bpono,bpoDate,bpoVDate,bpoType,mode_delivery,credit_period,executiveId,cash_discount_tag,cash_discount_value,bill_ship_address_same_tag,po_shipadd1,po_shipadd2,po_shipStateId,po_shipCityId,po_shipCountryId,po_shipLocationId,po_shipPincode,po_phoneno,po_mobno,po_fax,po_email,po_val,pf_chrg,incidental_chrg,freight_tag,freight_percent,freight_amount,management_approval,approval_status,po_status,msid,Remarks,UserId ,InsertDate,po_ack_email,po_state,po_hold_reason) VALUES ($ResultMap,$bn, '$pon', '$pod1', '$ipovd1', '$pot', '$mode_delivery', $cp,'$exec','$cd',$cdt,'$addTag','$sadd1','$sadd2','$sstate1','$scity1','$scountry1','$slocation1','$spincode','$sphno','$smobno','$sfax','$semail','$po_val','$pf_chrg','$inci_chrg','$frgt','$frgp','$frga','$management_approval','$approval_status','$po_status','$msid','$remrks','$userId','$date','$bemailId','$po_hold_state','$po_hold_reason')";
		
		$Query = "INSERT INTO purchaseorder( bpoId, BuyerId, bpono, bpoDate, bpoVDate, bpoType, mode_delivery, credit_period, executiveId, cash_discount_tag, cash_discount_value, bill_ship_address_same_tag, po_shipadd1, po_shipadd2, po_shipStateId, po_shipCityId, po_shipCountryId, po_shipLocationId, po_shipPincode, po_phoneno, po_mobno, po_fax, po_email, po_val, pf_chrg, incidental_chrg, freight_tag, freight_percent, freight_amount, management_approval, approval_status, po_status, msid, Remarks, UserId , InsertDate, po_ack_email, po_state, po_hold_reason, insurance_charge, other_charge) VALUES ( $ResultMap, $bn, '$pon', '$pod1', '$ipovd1', '$pot', '$mode_delivery', $cp, '$exec', '$cd', $cdt, '$addTag', '$sadd1', '$sadd2', $sstate1, '$scity1', '$scountry1', '$slocation1', '$spincode', '$sphno', '$smobno', '$sfax', '$semail', $po_val, '$pf_chrg', '$inci_chrg', '$frgt', '$frgp', '$frga', '$management_approval', '$approval_status', '$po_status', '$msid', '$remrks','$userId', '$date', '$bemailId', '$po_hold_state', '$po_hold_reason', '$ins_charge', '$othc_charge')";
       
	   //echo $Query

        $Result = DBConnection::InsertQuery($Query);
		
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }

	}
    public static function UpdatePO($bpoId,$buyerId,$mode_delivery,$bpono,$credit_period,$bpoDate,$executiveId,$bpoVDate,$cash_discount_tag,$cash_discount_value,$bpoType,$bill_ship_address_same_tag,$po_shipadd1,$po_shipadd2,$po_shipCountryId,$po_shipStateId,$po_shipCityId,$po_shipLocationId,$po_shipPincode,$po_phoneno,$po_mobno,$po_fax,$po_email,$po_val,$pf_chrg,$incidental_chrg,$freight_tag,$freight_percent,$freight_amount,$management_approval,$approval_status,$po_status,$msid,$Remarks,$userId, $bemailId, $po_hold_state, $po_hold_reason, $ins_charge, $othc_charge)
    {

		    $bpoDate1= MultiweldParameter::xFormatDate($bpoDate);
		    $bpoVDate1=MultiweldParameter::xFormatDate($bpoVDate);
		    if($cash_discount_value==""||$cash_discount_value==null)
		    {
				$cash_discount_value=0.00;
			}
			 if($incidental_chrg==""||$incidental_chrg==null)
		    {
				$incidental_chrg=0.00;
			}
			 if($freight_percent==""||$freight_percent==null)
		    {
				$freight_percent=0.00;
			}
			 if($freight_amount==""||$freight_amount==null)
		    {
				$freight_amount=0.00;
			} 
			if($pf_chrg==""||$pf_chrg==null)
		    {
				$pf_chrg=0.00;
			}
			if($othc_charge == NULL){
				$othc_charge = 0.00;
			}
			 
			//added on 03-JUNE-2016 due to Handle Special Character
			$po_shipadd1 = mysql_escape_string($po_shipadd1);
			$po_shipadd2 = mysql_escape_string($po_shipadd2);
			$po_phoneno = mysql_escape_string($po_phoneno);
			$po_mobno = mysql_escape_string($po_mobno);
			$po_email = mysql_escape_string($po_email);
			$Remarks = mysql_escape_string($Remarks);
			$bemailId = mysql_escape_string($bemailId);
			$po_hold_reason = mysql_escape_string($po_hold_reason);	
						
	    	$Query = "UPDATE purchaseorder SET ";
	        //~ $Query.="bpono='$bpono',BuyerId='$buyerId', bpoDate='$bpoDate1', bpoVDate='$bpoVDate1', bpoType='$bpoType', mode_delivery ='$mode_delivery', credit_period='$credit_period',executiveId='$executiveId', cash_discount_tag = '$cash_discount_tag' , cash_discount_value='$cash_discount_value', bill_ship_address_same_tag='$bill_ship_address_same_tag', po_shipadd1='$po_shipadd1',po_shipadd2='$po_shipadd2',po_shipStateId='$po_shipStateId',po_shipCityId='$po_shipCityId' , po_shipCountryId = '$po_shipCountryId', po_shipLocationId='$po_shipLocationId',po_shipPincode='$po_shipPincode', po_phoneno ='$po_phoneno' , po_mobno='$po_mobno',po_fax='$po_fax',po_email='$po_email',po_val='$po_val',pf_chrg='$pf_chrg',incidental_chrg='$incidental_chrg',freight_tag='$freight_tag',freight_percent='$freight_percent',freight_amount='$freight_amount' , Remarks ='$Remarks',management_approval='$management_approval',approval_status='$approval_status',po_status='$po_status',msid='$msid',UserId='$userId' WHERE bpoId=$bpoId";
	        
	        //added by gajendra
	        $Query.="bpono='$bpono',BuyerId='$buyerId', bpoDate='$bpoDate1', bpoVDate='$bpoVDate1', bpoType='$bpoType', mode_delivery ='$mode_delivery', credit_period='$credit_period',executiveId='$executiveId', cash_discount_tag = '$cash_discount_tag' , cash_discount_value='$cash_discount_value', bill_ship_address_same_tag='$bill_ship_address_same_tag', po_shipadd1='$po_shipadd1',po_shipadd2='$po_shipadd2',po_shipStateId='$po_shipStateId',po_shipCityId='$po_shipCityId' , po_shipCountryId = '$po_shipCountryId', po_shipLocationId='$po_shipLocationId',po_shipPincode='$po_shipPincode', po_phoneno ='$po_phoneno' , po_mobno='$po_mobno',po_fax='$po_fax',po_email='$po_email',po_val='$po_val',pf_chrg='$pf_chrg',incidental_chrg='$incidental_chrg',freight_tag='$freight_tag',freight_percent='$freight_percent',freight_amount='$freight_amount' , Remarks ='$Remarks',management_approval='$management_approval',approval_status='$approval_status',po_status='$po_status',msid='$msid',UserId='$userId', po_ack_email = '$bemailId', po_state = '$po_hold_state', po_hold_reason = '$po_hold_reason', insurance_charge = '$ins_charge', other_charge = '$othc_charge' WHERE bpoId=$bpoId";
	        //end
	     
	        $Result = DBConnection::UpdateQuery($Query);
						
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
    }

    public static function GetPODetails($poId){
		$Query="SELECT po.* from purchaseorder as po where bpoId=$poId and po_status='O'";
	    //echo $Query;
	    $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
      public static function validatePO($buyerId,$pono,$bpoId){
      	$opt="";
      	if($bpoId!="")
      	{
		  $opt=" and bpoId!='$bpoId'";	
		}
		$Query="SELECT COUNT(*)c FROM purchaseorder as po WHERE po.buyerId=$buyerId and po.bpono='$pono' $opt";
	    $Result = DBConnection::SelectQuery($Query);
		$row1=mysql_fetch_array($Result, MYSQL_ASSOC);
	    $total=$row1['c'];
		return $total;
	}

}

class purchaseorder_Details_Model
{
    public $bpod_Id;
    public $po_quotNo;
    public $po_principalId;
    public $po_principalName;
    public $po_itemId;
    public $po_codePartNo;
    public $po_buyeritemcode;
    public $unit_id;
    public $po_unit;
    public $po_qty;
    public $po_price;
    public $po_discount;
    public $po_hsn_code;
    public $po_cgst_rate;
    public $po_cgst_amt;
    public $po_sgst_rate;
    public $po_sgst_amt;
    public $po_igst_rate;
    public $po_igst_amt;
    public $taxable_amt;
    public $po_finVal;
    public $eda1;
    public $po_ed_applicability;
    public $sTax;
    public $po_saleTax;
    public $po_deliverybydate;
    public $po_item_stage;

    public $itemdescp;
    public $iden_mark;
    public $po_totVal;
    public $po_oprice;
    public $po_odiscount;
    public $po_balance_qty;


    public function __construct($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$po_hsn_code,$po_cgst_rate,$po_cgst_amt,$po_sgst_rate,$po_sgst_amt,$po_igst_rate,$po_igst_amt,$taxable_amt,$po_finVal,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount, $po_oprice,$po_balance_qty){
        $this->bpod_Id = $bpod_Id;
        $this->po_quotNo = $po_quotNo;
        $this->po_principalId  =$po_principalId;
        $this->po_principalName  =$po_principalName;
        $this->pname  =$po_principalName;
        $this->cPartNo = $po_itemId;
        $this->po_itemId = $po_itemId;
        $this->po_codePartNo = $po_codePartNo;
        $this->item_desc = $itemdescp;
        $this->po_buyeritemcode=$po_buyeritemcode;
        $this->unit_id=$unit_id;
        $this->po_unit = $po_unit;
        $this->po_qty = $po_qty;
        $this->po_price = $po_price;
        $this->po_discount=$po_discount;
        $this->po_hsn_code=$po_hsn_code;
        $this->po_cgst_rate=$po_cgst_rate;
        $this->po_cgst_amt=$po_cgst_amt;
        $this->po_sgst_rate=$po_sgst_rate;
        $this->po_sgst_amt=$po_sgst_amt;
        $this->po_igst_rate=$po_igst_rate;
        $this->po_igst_amt=$po_igst_amt;
        $this->po_finVal=$po_finVal;
        $this->po_taxable_amt=$taxable_amt;
        $this->eda1=$eda1;
        $this->po_ed_applicability=$po_ed_applicability;
        $this->sTax=$sTax;
        $this->po_saleTax=$po_saleTax;
        $this->po_deliverybydate=$po_deliverybydate;
        $this->po_totVal=$po_totVal;
        $this->itemdescp = $itemdescp;
        $this->iden_mark= $Item_Identification_Mark;
        $this->Item_Identification_Mark= $Item_Identification_Mark;
        $this->po_odiscount=$po_odiscount;
        $this->po_oprice=$po_oprice;
        $this->po_balance_qty=$po_balance_qty;
       

	}
    
    //*########################################################
      public static function DeleteItem($bpoId){
        $Query = "DELETE FROM  purchaseorder_detail WHERE  bpoId='$bpoId'";
        $Result = DBConnection::UpdateQuery($Query);
        return $Result;
    }
    
    //#####################################################
     
    public static function showPOQuotation($buyerId){
		$Query = "SELECT quotNo QUOTNO FROM quotation WHERE BuyerId='$buyerId'";
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function getPOQuotation($buyerId){
		$result = self::showPOQuotation($buyerId);
		$objArray = array();
		$i = 0;
		
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $po_quotNo= $Row['QUOTNO'];
              $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

    public static function showSalesTax($buyerId){
      $Query = "select salestax_id,salestax_desc from vat_cst_master where TYPE='B' OR TYPE=(SELECT Tax_Type FROM buyer_master WHERE BuyerId='$buyerId')";
       
	   $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function getSalesTax($buyerId){
		$result = self::showSalesTax($buyerId);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $sTax= $Row['salestax_id'];
               $po_saleTax= $Row['salestax_desc'];
			   /*$newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty); */
			    $newObj = new purchaseorder_Details_Model($bpod_Id=null,$po_quotNo=null,$po_principalId=null,$po_principalName=null,$po_itemId=null,$po_codePartNo=null,$po_buyeritemcode=null,$unit_id=null,$po_unit=null,$po_qty=null,$po_price=null,$po_discount=null,$eda1=null,$po_ed_applicability=null,$sTax,$po_saleTax,$po_deliverybydate=null,$po_item_stage=null,$po_totVal=null,$itemdescp=null,$Item_Identification_Mark=null,$po_odiscount=null,$po_oprice=null,$po_balance_qty=null);
                                                       
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	 public static function getSalesTaxNew(){
		$Query = "select salestax_id,salestax_desc from vat_cst_master ORDER BY salestax_id ASC";       
	    $result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $sTax= $Row['salestax_id'];
               $po_saleTax= $Row['salestax_desc'];
			   /*$newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty); */
			    $newObj = new purchaseorder_Details_Model($bpod_Id=null,$po_quotNo=null,$po_principalId=null,$po_principalName=null,$po_itemId=null,$po_codePartNo=null,$po_buyeritemcode=null,$unit_id=null,$po_unit=null,$po_qty=null,$po_price=null,$po_discount=null,$eda1=null,$po_ed_applicability=null,$sTax,$po_saleTax,$po_deliverybydate=null,$po_item_stage=null,$po_totVal=null,$itemdescp=null,$Item_Identification_Mark=null,$po_odiscount=null,$po_oprice=null,$po_balance_qty=null);
                                                       
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
    public static function getPOPrincipalSupplier($tbName,$quotNo,$type){
		$result = self::showPOPrincipalSupplier($tbName,$quotNo,$type);
		$objArray = array();
		$i = 0;
        while($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $po_principalId= $Row['PID'];
               $po_principalName= $Row['PNAME'];
              $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function showPOPrincipalSupplier($tbName,$quotNo,$type){
		//echo $tbName;
		if($quotNo=="0" || $quotNo==""){

		$Query = "SELECT PRINCIPAL_SUPPLIER_ID as PID,PRINCIPAL_SUPPLIER_NAME as PNAME FROM principal_supplier_master WHERE type='P'";

		}else if($quotNo!="0" && $tbName=="QUOTATION"){
		$Query = "SELECT Q.PRINCIPALID as PID,PRINCIPAL_SUPPLIER_NAME as PNAME  FROM quotation AS Q,principal_supplier_master  AS PSM WHERE Q.PRINCIPALID=PSM.PRINCIPAL_SUPPLIER_ID AND TYPE='P' AND QUOTNO='$quotNo'";
		}
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function msec() 
    {
     list($usec, $sec) = explode(' ',microtime());
     return intval(($usec+$sec)*1000.0);
    }
    public static function getCodePartNo($qno,$id){
		
		$result = self::showCodePartNo($qno,$id);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $po_itemId= $Row['ItemId'];
               $po_codePartNo= $Row['Item_Code_Partno'];
               $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
                                                        
            $objArray[$i] = $newObj;
            $i++;
		}

		return $objArray;
	
	}
	
	 public static function getCodePartNoForAutoComplete($qno,$id){
		
		$result = self::showCodePartNo($qno,$id);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $po_itemId= $Row['ItemId'];
               $po_codePartNo= $Row['Item_Code_Partno'];
               $objArray[$po_itemId] = $po_codePartNo;
            $i++;
		}
		return $objArray;
	
	}
	
    public static function showCodePartNo($qno,$id){
 	if($qno=="0" || $qno==""){
		$Query = "select ItemId,Item_Code_Partno from item_master WHERE PrincipalId=$id";
	}else{
	    $Query = "SELECT ItemId,Item_Code_Partno FROM quotation as q,quotation_detail as qd,item_master as im WHERE qd.quotId=q.quotId  and qd.code_part_no=im.itemId and q.quotno='$qno'";
	}
    //echo $Query;
    $Result = DBConnection::SelectQuery($Query);
    return $Result;
    }
    public static function getDiscount($id){
		$result = self::showDiscount($id);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $po_discount= $Row['DISCOUNT'];

               $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
                                                                                               
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function showDiscount($id){
 	  $Query = "SELECT DISCOUNT FROM quotation as q WHERE q.quotno='$id'";
	  $Result = DBConnection::SelectQuery($Query);
    return $Result;
    }
	/* BOF for adding GST by Ayush Giri on 19-06-2017 */
    //public static function InsertPODetails($bpoId,$po_quotNo,$po_principalId,$po_codePartNo,$po_buyeritemcode,$po_unit,$po_qty,$po_price,$po_price_category,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate,$po_item_stage){
	public static function InsertPODetails($bpoId, $po_quotNo, $po_principalId, $po_codePartNo, $po_buyeritemcode, $po_unit, $po_qty,$po_price, $po_price_category, $po_discount, $po_hsn_code, $po_cgst_rate, $po_cgst_amt, $po_sgst_rate, $po_sgst_amt, $po_igst_rate, $po_igst_amt, $po_deliverybydate, $po_taxable_amt, $po_finVal, $po_item_stage){
	/* EOF for adding GST by Ayush Giri on 19-06-2017 */
		//echo "Hi"+$po_deliverybydate;
		if($po_deliverybydate!="" && $po_deliverybydate!=undefined && $po_deliverybydate!=null){
		   $po_deliverybydate1=MultiweldParameter::xFormatDate($po_deliverybydate);
		   //$po_deliverybydate1= date("Y-m-d",strtotime($po_deliverybydate));
		}else{
			$po_deliverybydate1="0000-00-00";
		}
		if($po_discount==NULL){
			$po_discount=0.00;
		}
		if($po_price_category!="S"){
		   $po_price_category="N";
		}
		
		/*if($po_saleTax=="")
		{
			$po_saleTax=0.00;
		}*/
		
		//added on 03-JUNE-2016 due to Handle Special Character
		$po_codePartNo = mysql_escape_string($po_codePartNo);
		$po_buyeritemcode = mysql_escape_string($po_buyeritemcode);
		if($po_codePartNo==NULL){
			$po_codePartNo='NULL';
		}
		if($po_unit==NULL){
			$po_unit='NULL';
		}
		if($po_price==NULL){
			$po_price='NULL';
		}
		if($po_cgst_rate==NULL){
			$po_cgst_rate='NULL';
		}
		if($po_qty==NULL){
			$po_qty='NULL';
		}
		if($po_sgst_rate==NULL){
			$po_sgst_rate='NULL';
		}
		if($po_cgst_amt==NULL){
			$po_cgst_amt='NULL';
		}
		if($po_sgst_amt==NULL){
			$po_sgst_amt='NULL';
		}
		if($po_igst_rate==NULL){
			$po_igst_rate='NULL';
		}
		if($po_igst_amt==NULL){
			$po_igst_amt='NULL';
		}
		if($po_taxable_amt==NULL){
			$po_taxable_amt='NULL';
		}
		if($po_finVal==NULL){
			$po_finVal='NULL';
		}
		
		$date = date("d-m-Y");
		
		/* BOF for adding GST by Ayush Giri on 19-06-2017 */
        //$Query = "INSERT INTO purchaseorder_detail (bpoId,po_quotNo,po_principalId,po_codePartNo,po_buyeritemcode,po_qty,po_unit,po_price,po_price_category,po_discount,po_ed_applicability,po_saleTax,po_deliverybydate,po_item_stage) VALUES ('$bpoId','$po_quotNo','$po_principalId','$po_codePartNo','$po_buyeritemcode','$po_qty','$po_unit','$po_price','$po_price_category','$po_discount','$po_ed_applicability','$po_saleTax','$po_deliverybydate1','$po_item_stage')";
		
		$Query = "INSERT INTO purchaseorder_detail (bpoId, po_quotNo, po_principalId, po_codePartNo, po_buyeritemcode, po_qty, po_unit, po_price, po_price_category, po_discount, po_hsn_code, po_cgst_rate, po_cgst_amt, po_sgst_rate, po_sgst_amt, po_igst_rate, po_igst_amt, po_deliverybydate, taxable_amt, total, po_item_stage) VALUES ('$bpoId', '$po_quotNo', '$po_principalId', $po_codePartNo, '$po_buyeritemcode', $po_qty, $po_unit, $po_price, '$po_price_category', '$po_discount','$po_hsn_code',$po_cgst_rate,$po_cgst_amt,$po_sgst_rate,$po_sgst_amt,$po_igst_rate,$po_igst_amt,'$po_deliverybydate1',$po_taxable_amt,$po_finVal,'$po_item_stage')";
		/* EOF for adding GST by Ayush Giri on 19-06-2017 */
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function GetItemList($poId,$po_ed_applicability,$bpoType){
	$opt="";
    	if($po_ed_applicability!='')
    	{
			$opt=" and pd.po_ed_applicability='$po_ed_applicability'";
		}
		if($bpoType=="R")
		{
			 //$Query ="SELECT pd.*,pm.Principal_Supplier_Name,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark,um.unitname,t1.param_value1 eda1,vcm.salestax_desc sTax FROM (SELECT param_value1,param1 FROM param WHERE param_type='EXCISEDUTY' AND param_code='APPLICABLE')t1, purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id,principal_supplier_master AS pm,item_master AS im,unit_master AS um,vat_cst_master AS vcm WHERE pm.Principal_Supplier_Id=pd.po_principalId AND im.ItemId = pd.po_codePartNo AND um.unitId = pd.po_unit AND pd.po_ed_applicability=t1.param1 AND pd.po_saletax=vcm.salestax_id AND pd.bpoId ='$poId' $opt";
			 $Query ="SELECT pd.*,pm.Principal_Supplier_Name,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark,um.unitname FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id,principal_supplier_master AS pm,item_master AS im,unit_master AS um WHERE pm.Principal_Supplier_Id=pd.po_principalId AND im.ItemId = pd.po_codePartNo AND um.unitId = pd.po_unit AND pd.bpoId ='$poId' $opt";
		}
		else
		{
		  //$Query = "select pd.*,pm.Principal_Supplier_Name,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark,um.unitname,t1.param_value1 eda1,vcm.salestax_desc sTax FROM (select param_value1,param1 from param where param_type='EXCISEDUTY' and param_code='APPLICABLE')t1,purchaseorder_detail as pd,principal_supplier_master as pm,item_master as im,unit_master as um,vat_cst_master as vcm where pm.Principal_Supplier_Id=pd.po_principalId and im.ItemId = pd.po_codePartNo and um.unitId = pd.po_unit and pd.po_ed_applicability=t1.param1 and pd.po_saletax=vcm.salestax_id and pd.bpoId ='$poId' $opt";	
		  $Query = "select pd.*,pm.Principal_Supplier_Name,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark,um.unitname FROM purchaseorder_detail as pd,principal_supplier_master as pm,item_master as im,unit_master as um where pm.Principal_Supplier_Id=pd.po_principalId and im.ItemId = pd.po_codePartNo and um.unitId = pd.po_unit and pd.bpoId ='$poId' $opt";	
		}
	//echo $Query;exit;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function  LoadPO($poId,$po_ed_applicability,$bpoType)
	{
        $result = self::GetItemList($poId,$po_ed_applicability,$bpoType);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $bpod_Id = $Row['bpod_Id'];
            $po_quotNo= $Row['po_quotNo'];
            $po_principalId= $Row['po_principalId'];
            $po_principalName = $Row['Principal_Supplier_Name'];
            $po_itemId= $Row['Item_Code_Partno'];
            $po_codePartNo= $Row['po_codePartNo'];
            //$po_itemId= $Row['ItemId'];
            //$po_codePartNo= $Row['Item_Code_Partno'];
            $po_buyeritemcode= $Row['po_buyeritemcode'];
			$itemdescp = $Row['Item_Desc'];
			
            $Item_Identification_Mark=$Row['Item_Identification_Mark'];
            $po_qty= $Row['po_qty'];
            $unit_id= $Row['po_unit'];
            $po_unit= $Row['unitname'];
            $po_price= $Row['po_price'];
            $po_discount= $Row['po_discount'];
            $po_hsn_code= $Row['po_hsn_code'];
            $po_cgst_rate= $Row['po_cgst_rate'];
            $po_cgst_amt= $Row['po_cgst_amt'];
            $po_sgst_rate= $Row['po_sgst_rate'];
            $po_sgst_amt= $Row['po_sgst_amt'];
            $po_igst_rate= $Row['po_igst_rate'];
            $po_igst_amt= $Row['po_igst_amt'];
            $po_finVal= $Row['total'];
            $po_taxable_amt= $Row['taxable_amt'];
            $po_ed_applicability= $Row['po_ed_applicability'];
            $eda1= $Row['eda1'];
            $po_saleTax= $Row['po_saleTax'];
            $sTax= $Row['sTax'];
            $po_deliverybydate= $Row['po_deliverybydate'];
         
            if($po_deliverybydate=="0000-00-00"){
				$po_deliverybydate1="";
			}else{
				$po_deliverybydate1=MultiweldParameter::xFormatDate1($po_deliverybydate);// date("d/m/Y",strtotime($po_deliverybydate));
			}

            $po_totVal = self::getRowAmount($po_qty,$po_price,$po_discount);

            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$po_hsn_code,$po_cgst_rate,$po_cgst_amt,$po_sgst_rate,$po_sgst_amt,$po_igst_rate,$po_igst_amt,$po_taxable_amt,$po_finVal,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate1,$po_item_stage=null,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_discount,$po_price,$po_balance_qty=null);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function getRowAmount($qty,$price,$discount)
    {
        return ((($qty * $price)*(100-$discount))/100);
    }

   public static function GetItemDesc($buyerId,$quotId,$principalId,$itemId){
   	 $result = self::showItemDesc($buyerId,$quotId,$principalId,$itemId);
 		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {

               $itemdescp= $Row['Item_Desc'];
               $Item_Identification_Mark= $Row['Item_Identification_Mark'];
               $unit_id= $Row['unitId'];
               $po_unit= $Row['unitname'];
               $po_price= $Row['rate_perUnit'];
               $po_oprice=$po_price;
			   $po_odiscount=0;
			   $po_discount=0;
               if($quotId!="0"){
                  $po_discount= $Row['discount'];
                  $po_odiscount=$po_discount;
               }else{
			   	  $po_price="";
			   	  $po_oprice="0";
			   }
               $newObj = new purchaseorder_Details_Model($bpod_Id=null,$po_quotNo=null,$po_principalId=null,$po_principalName=null,$po_itemId=null,$po_codePartNo=null,$po_buyeritemcode=null,$unit_id,$po_unit,$po_qty=null,$po_price,$po_discount,$eda1=null,$po_ed_applicability=null,$sTax=null,$po_saleTax=null,$po_deliverybydate=null,$po_item_stage=null,$po_totVal=null,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty=null);
                                                        
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
   }
   public static function showItemDesc($buyerId,$quotId,$principalId,$itemId){
      if($quotId!="0"){
	  $Query = "select discount,rate_perUnit,Item_Code_Partno,Item_Identification_Mark,Item_Desc,qd.unitId,unitname
      from quotation as q,quotation_detail as qd,item_master as im,buyer_master as bm,unit_master as um
      where q.quotId=qd.quotId
      and im.itemId=qd.code_part_no
      and bm.BuyerId=q.BuyerId
      and qd.unitId=um.UnitId
      and q.BuyerId=$buyerId
      and qd.code_part_no=$itemId
 	  and q.quotNo='$quotId'";
	  }else{
	  	$Query = "select Item_Identification_Mark,Item_Desc,Cost_Price rate_perUnit, im.unitId,unitname
from item_master as im,unit_master as um where um.unitId=im.unitId and im.itemID=$itemId  and principalId=$principalId ";
	  }


 	  //echo $Query;
	  $result = DBConnection::SelectQuery($Query);
      return $result;
   }
   //##############################
   	public static function GetPoItemIssueQty($bpod_Id,$itemId,$bpoType,$potype)
   {	
   	   $tot_issue_qty=0;
   	   $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";	
   	   
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
    
       $Result = DBConnection::SelectQuery($Query);
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_issue_qty=$Row['issued_qty'];
       }
      return  $tot_issue_qty;  
       
   }
   public static function GetPoIssueQty($bpod_Id,$potype)
   {
   	   if($potype=="N")
       {
	      $chkIsuue="SELECT SUM(issued_qty) AS tot_issued_qty FROM outgoinginvoice_nonexcise_detail WHERE buyer_item_code='$bpod_Id' GROUP BY buyer_item_code";	
       }
       else if($potype=="E")
       {
	     $chkIsuue="SELECT SUM(issued_qty) AS tot_issued_qty FROM outgoinginvoice_excise_detail WHERE buyer_item_code='$bpod_Id' GROUP BY buyer_item_code";		
       }
       $Result1 = DBConnection::SelectQuery($chkIsuue);
       $row1=mysql_fetch_row($Result1);
       return $row1[0];
   }
   
   public static function GetPOItemDetail($bpod_Id,$bpoType)
   {	
	 if($bpoType=="R")
	 {
	 	 $Query="SELECT itm.Item_Desc,itm.Item_Code_Partno,psd.bposd_Id AS bpod_Id,pd.po_ed_applicability ,psd.sch_delbydateqty  AS po_qty,po_codePartNo,pd.po_buyeritemcode,pd.po_price,pd.po_unit,po_discount,taxable_amt,po_hsn_code,po_cgst_rate,po_cgst_amt,po_sgst_rate,po_sgst_amt,po_igst_rate,po_igst_amt,total,pd.po_saleTax   FROM  purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id AND psd.sch_codePartNo=pd.po_codePartNo,item_master AS itm  WHERE psd.bposd_Id='$bpod_Id'  AND psd.sch_codePartNo=itm.ItemId";
	 }
	 else
	 {
	 	 $Query="SELECT itm.Item_Desc,itm.Item_Code_Partno,pd.po_codePartNo,pd.bpod_Id,pd.po_ed_applicability ,pd.po_qty,pd.po_buyeritemcode,pd.po_price,po_unit,po_discount,taxable_amt,po_hsn_code,po_cgst_rate,po_cgst_amt,po_sgst_rate,po_sgst_amt,po_igst_rate,po_igst_amt,total,po_saleTax  FROM  purchaseorder_detail AS pd,item_master AS itm  WHERE pd.bpod_id='$bpod_Id' AND pd.po_codePartNo=itm.ItemId";
	 }
	 //echo $Query;exit;
	 $Result = DBConnection::SelectQuery($Query);
	 $objArray = array();
	 $i = 0;
	 while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		
	  	$newObj= array();
	  	$newObj=$Row;
	  	$bpod_Id=$Row['bpod_Id'];
	  	$po_codePartNo=$Row['po_codePartNo'];
	  	$tag=$Row['po_ed_applicability'];
	  	$order_qty=$Row['po_qty'];
	  	
	  	$tot_isuue_qty=self:: GetPoItemIssueQty($bpod_Id,$po_codePartNo,$bpoType,$tag);
	  	$stock_qty=self::GetItemStockQty($po_codePartNo,$tag);
	  	
	  	$balance_qty=$order_qty-$tot_isuue_qty;
	  	$newObj['po_balance_qty']=$balance_qty;
	  	$newObj['stock_qty']=$stock_qty;
	  	$objArray[$i]=$newObj;
	  	$i++;
	 }
	//print_r($objArray);exit;
	 return $objArray;		
   }
   
    //#################### added function for searching speed issue resolve #### aksoni 24/04/2015 
   public static function GetItemDescription($buyerId,$quotId,$principalId,$itemId)
   {
   	  if($quotId!="0"){
	  $Query = "select discount,rate_perUnit as po_price,Item_Code_Partno,Item_Identification_Mark as Item_Identification_Mark,Item_Desc as item_desc,qd.unitId as unit_id,unitname as po_unit
      from quotation as q,quotation_detail as qd,item_master as im,buyer_master as bm,unit_master as um
      where q.quotId=qd.quotId
      and im.itemId=qd.code_part_no
      and bm.BuyerId=q.BuyerId
      and qd.unitId=um.UnitId
      and q.BuyerId=$buyerId
      and qd.code_part_no=$itemId
 	  and q.quotNo='$quotId'";
	  }else{
	  	$Query = "select Item_Identification_Mark as Item_Identification_Mark,Item_Desc as item_desc,im.unitId as unit_id,unitname as po_unit
         from item_master as im,unit_master as um where um.unitId=im.unitId and im.itemID=$itemId  and principalId=$principalId ";
	  }
	   $Result = DBConnection::SelectQuery($Query);
	
		$objArray = array();
	     $i = 0;
	    while ($Row=mysql_fetch_array($Result, MYSQL_ASSOC)) {
	 	    $objArray[$i]=$Row;
            $i++;
	 	}	
	 	return $objArray;

   }
   //########################### end 
   /* BOF to show GST rates by Ayush Giri on 19-06-2017 */
   public static function GetItemGST($itemId,$buyer_id,$principal_supplier_id)
   {
	   $Query = "SELECT Tax_Type FROM buyer_master WHERE BuyerId = '".$buyer_id."'";
	   $Result = DBConnection::SelectQuery($Query);
	   $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	   
		if($Row['Tax_Type'] == 'S')
		{
			$Query = "SELECT hm.hsn_code AS HSN_CODE, tm.tax_rate AS GST_RATE, (tm.tax_rate/2) AS CGST_RATE, (tm.tax_rate/2) AS SGST_RATE, '0' AS IGST_RATE  FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '".mysql_escape_string(trim($itemId))."' AND im.PrincipalId = '".$principal_supplier_id."'";
		}
		else
		{
			$Query = "SELECT hm.hsn_code AS HSN_CODE, tm.tax_rate AS GST_RATE, '0' AS CGST_RATE, '0' AS SGST_RATE, tm.tax_rate AS IGST_RATE FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '".mysql_escape_string(trim($itemId))."' AND im.PrincipalId = '".$principal_supplier_id."'";
		}
	    $Result = DBConnection::SelectQuery($Query);
	
		$objArray = array();
	     $i = 0;
	    while ($Row=mysql_fetch_array($Result, MYSQL_ASSOC)) {
	 	    $objArray[$i]=$Row;
            $i++;
	 	}
	 	return $objArray;
   }
   /* EOF to show GST rates by Ayush Giri on 19-06-2017 */
   //########################### end 
   
   
    public static function  GetItemStockQty($code_partNo,$tag)
	{
		$itemStockQTY=0; 
        $Query = "SELECT * FROM inventory WHERE code_partNo = $code_partNo";
     
		$Result = DBConnection::SelectQuery($Query);
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			$itemStockQTY= $Row['tot_Qty'];
		}
		return $itemStockQTY;
	}
   //#################################################
   public static function GetPoDetails($poId,$po_principalId,$po_codePartNo,$tag,$bpoType){

       if($bpoType=="R")
       {
	   	  if($tag == "E")
        {
           $Query = "SELECT psd.sch_req_qty AS req_qty,psd.bposd_Id,pd.* FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.sch_codePartNo=pd.po_codePartNo AND (pd.po_ed_applicability = 'E' OR pd.po_ed_applicability = 'I'),principal_supplier_master AS pm  WHERE pm.Principal_Supplier_Id = psd.sch_principalId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND psd.sch_principalId='$po_principalId' AND  psd.sch_codePartNo='$po_codePartNo' AND  psd.bpoId='$poId'  ORDER BY psd.bpoId LIMIT 1;";
        }
        else if($tag == "N")
        {
            $Query = "SELECT psd.sch_req_qty AS req_qty,psd.bposd_Id,pd.* FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.sch_codePartNo=pd.po_codePartNo AND pd.po_ed_applicability = 'N',principal_supplier_master AS pm  WHERE pm.Principal_Supplier_Id = psd.sch_principalId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND psd.sch_principalId='$po_principalId' AND  psd.sch_codePartNo='$po_codePartNo' AND  psd.bpoId='$poId'  ORDER BY psd.bpoId LIMIT 1;";
        }
	   }
	   else
	   {
	   	 if($tag == "E")
        {
        $Query = "SELECT pod.*,pm.Principal_Supplier_Name FROM purchaseorder_detail as pod INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = pod.po_principalId WHERE pod.bpoId = $poId AND pod.po_principalId = $po_principalId AND pod.po_codePartNo = $po_codePartNo AND (po_ed_applicability = 'E' OR po_ed_applicability = 'I')";
        }
        else if($tag == "N")
        {
        $Query = "SELECT pod.*,pm.Principal_Supplier_Name FROM purchaseorder_detail as pod INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = pod.po_principalId WHERE pod.bpoId = $poId AND pod.po_principalId = $po_principalId AND pod.po_codePartNo = $po_codePartNo AND po_ed_applicability = 'N'";
        }
	   }
    
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			 $po_balance_qty1=0;
			 $tot_isuue_qty=0;
             
            //$po_pquotNo= $Row['po_pquotNo'];
            $po_quotNo= $Row['po_quotNo'];
            $po_principalId= $Row['po_principalId'];
            $po_principalName = $Row['Principal_Supplier_Name'];
            $po_codePartNo= $Row['po_codePartNo'];
            $po_buyeritemcode= $Row['po_buyeritemcode'];
            if($bpoType=="R")
            {
				$po_qty= $Row['req_qty'];
				$bpod_Id = $Row['bposd_Id'];
			}
			else
			{
				$po_qty= $Row['po_qty'];
				$bpod_Id = $Row['bpod_Id'];
			}
            
            
            $po_unit= $Row['po_unit'];
            $po_price= $Row['po_price'];
            $po_discount= $Row['po_discount'];
            $po_ed_applicability= $Row['po_ed_applicability'];
            $po_saleTax = $Row['po_saleTax'];
            $po_deliverybydate= $Row['po_deliverybydate'];
        
            $tot_isuue_qty=self:: GetPoItemIssueQty($bpod_Id,$po_codePartNo,$bpoType,$tag);
            $po_balance_qty1=($po_qty-$tot_isuue_qty);
            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty1);
                                                     
            //$bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,0,$po_unit,$po_qty,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
    public static function  LoadPODetailsByPrincipal($podId,$tag,$Principalid,$bpoType)
	{	
    $Query = "";
      if($bpoType=="R")
      {
      	 if($tag=="E")
         {
			//Comment/add by Gajendra 
         	//~ $Query ="SELECT itm.Item_Code_Partno,itm.ItemId,psd.bposd_Id as bpod_Id  FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id  AND psd.sch_codePartNo=pd.po_codePartNo  AND (pd.po_ed_applicability = 'E' OR pd.po_ed_applicability = 'I'),  item_master AS itm WHERE psd.sch_codePartNo=itm.ItemId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND sch_principalId='$Principalid' AND  psd.bpoId='$podId' ORDER BY psd.sch_scheduledate,psd.bposd_Id";
         	$Query ="SELECT itm.Item_Code_Partno,itm.ItemId,psd.bposd_Id as bpod_Id  FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id  AND psd.sch_codePartNo=pd.po_codePartNo ,  item_master AS itm WHERE psd.sch_codePartNo=itm.ItemId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND sch_principalId='$Principalid' AND  psd.bpoId='$podId' ORDER BY psd.sch_scheduledate,psd.bposd_Id";
         }
         else if($tag=="N")
        {
			//Comment/add by Gajendra 
        	//~ $Query ="SELECT itm.Item_Code_Partno,itm.ItemId,psd.bposd_Id as bpod_Id  FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id AND psd.sch_codePartNo=pd.po_codePartNo  AND pd.po_ed_applicability = 'N',  item_master AS itm WHERE psd.sch_codePartNo=itm.ItemId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND sch_principalId='$Principalid' AND  psd.bpoId='$podId' ORDER BY psd.sch_scheduledate,psd.bposd_Id";
        	$Query ="SELECT itm.Item_Code_Partno,itm.ItemId,psd.bposd_Id as bpod_Id  FROM purchaseorder_schedule_detail AS psd JOIN purchaseorder_detail AS pd ON psd.bpoId=pd.bpoId AND psd.bpod_Id=pd.bpod_Id AND psd.sch_codePartNo=pd.po_codePartNo  AND pd.po_ed_applicability = 'N',  item_master AS itm WHERE psd.sch_codePartNo=itm.ItemId AND (pos_item_stage='YDE' OR pos_item_stage='POIG') AND sch_principalId='$Principalid' AND  psd.bpoId='$podId' ORDER BY psd.sch_scheduledate,psd.bposd_Id";
        }	
      	
	  }
	  else
	  {
	  	 if($tag == "E")
         {
			 //Comment/add by Gajendra 
             //~ $Query = "SELECT im.Item_Code_Partno,im.ItemId,bpod_Id FROM purchaseorder_detail as pod INNER JOIN item_master as im ON pod.po_codePartNo = im.ItemId WHERE pod.bpoId = $podId AND po_principalId = $Principalid AND (po_ed_applicability = 'E' OR po_ed_applicability = 'I') AND (po_item_stage = 'SCH' OR po_item_stage = 'STR' OR po_item_stage = 'YDE' OR po_item_stage = 'POIG' OR po_item_stage = 'IIG')";
             $Query = "SELECT im.Item_Code_Partno,im.ItemId,bpod_Id FROM purchaseorder_detail as pod INNER JOIN item_master as im ON pod.po_codePartNo = im.ItemId WHERE pod.bpoId = $podId AND po_principalId = $Principalid  AND (po_item_stage = 'SCH' OR po_item_stage = 'STR' OR po_item_stage = 'YDE' OR po_item_stage = 'POIG' OR po_item_stage = 'IIG')";
             
         }
         else if($tag == "N")
        {	//Comment/add by Gajendra 
            //~ $Query = "SELECT im.Item_Code_Partno,im.ItemId,bpod_Id FROM purchaseorder_detail as pod INNER JOIN item_master as im ON pod.po_codePartNo = im.ItemId WHERE pod.bpoId = $podId AND po_principalId = $Principalid AND po_ed_applicability = 'N' AND (po_item_stage = 'SCH' OR po_item_stage = 'STR' OR po_item_stage = 'YDE' OR po_item_stage = 'IIG' OR po_item_stage = 'POIG')";
            $Query = "SELECT im.Item_Code_Partno,im.ItemId,bpod_Id FROM purchaseorder_detail as pod INNER JOIN item_master as im ON pod.po_codePartNo = im.ItemId WHERE pod.bpoId = $podId AND po_principalId = $Principalid AND (po_item_stage = 'SCH' OR po_item_stage = 'STR' OR po_item_stage = 'YDE' OR po_item_stage = 'IIG' OR po_item_stage = 'POIG')";
        }
	  }
	
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
             //$bpod_Id = $Row['bpod_Id'];
            $po_pquotNo= $Row['po_pquotNo'];
            $po_quotNo= $Row['po_quotNo'];
            $po_principalId= $Row['po_principalId'];
            $po_principalName = $Row['Principal_Supplier_Name'];
            $po_itemId = $Row['ItemId'];
            $po_codePartNo= $Row['Item_Code_Partno'];
            $po_buyeritemcode= $Row['po_buyeritemcode'];
            $po_qty= $Row['po_qty'];
            $po_unit= $Row['po_unit'];
            $po_price= $Row['po_price'];
            $po_discount= $Row['po_discount'];
            $po_ed_applicability= $Row['po_ed_applicability'];
            $po_saleTax= $Row['po_saleTax'];
            $po_deliverybydate = $Row['po_deliverybydate'];
            $bpod_Id=$Row['bpod_Id'];
            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
                                                     
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function  LoadPrincipal($poId,$po_ed_applicability)
	{
          $opt='';
    	    if($po_ed_applicability!="")
    	    {
				$opt=" AND pod.po_ed_applicability='$po_ed_applicability'";
			}
		$Query = "SELECT distinct pm.Principal_Supplier_Id, pm.Principal_Supplier_Name FROM purchaseorder_detail as pod INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = pod.po_principalId WHERE pod.bpoId ='$poId' $opt";
        //$Query = "SELECT bpod_Id, psm.principal_supplier_name, item_code_partno,po_ed_applicability FROM purchaseorder_detail AS pod, purchaseorder AS po,principal_supplier_master AS psm, item_master AS im WHERE po.bpoid = pod.bpoid AND bpono =  '$poId' AND psm.principal_supplier_id = pod.po_principalId AND pod.po_codePartNo = im.itemId";
       // echo $Query;
       
		$result = DBConnection::SelectQuery($Query);
	   
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		// change by Rajiv for undefine index rerrors on 14-AUG-15
            $bpod_Id = $Row['bpod_Id'];
			$po_quotNo = $Row['po_quotNo'];
			$po_itemId = $Row['po_principalId'];
			$po_buyeritemcode = $Row['po_buyeritemcode'];
			$unit_id = $Row['unit_id'];
			$po_unit = $Row['po_unit'];
			$po_qty = $Row['po_qty'];
			$po_price = $Row['po_price'];
			$po_discount = $Row['po_discount'];
			$eda1 = $Row['eda1'];
			$sTax = $Row['sTax'];
			$po_saleTax = $Row['po_saleTax'];
			$po_deliverybydate = $Row['po_deliverybydate'];
            $po_codePartNo= $Row['item_code_partno'];
            $po_principalId = $Row['Principal_Supplier_Id'];
            $po_principalName = $Row['Principal_Supplier_Name'];
            $po_ed_applicability = $Row['po_ed_applicability'];
            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark=null,$po_odiscount=null,$po_oprice=null,$po_balance_qty=null);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

public static function  getPrincipalBuyerDiscount($Buyer_id ,$PrincipalId){
       	$Query="select DISCOUNT FROM buyer_discount_info  
RIGHT JOIN buyer_master ON buyer_discount_info.BUYERID=buyer_master.BuyerId 
WHERE buyer_discount_info.BUYERID = $Buyer_id 
AND buyer_discount_info.PRINCIPAL_SUPPLIER_ID = $PrincipalId 
";	
       	$result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		if ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
             $po_discount=$Row['DISCOUNT'];
            $po_odiscount=$po_discount;
            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice,$po_balance_qty);
          
            $objArray[$i] = $newObj;
            $i++;
		}else{
			$newObj = new purchaseorder_Details_Model(Null,Null,Null,Null,Null,Null,Null,Null,Null,Null,Null,0,Null,Null,Null,Null,Null,Null,Null,Null,Null,0,Null,null);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;

}
}


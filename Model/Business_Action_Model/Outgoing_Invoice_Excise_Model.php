<?php
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 3600);
//require_once('root.php');
//require_once($root_path."Config.php");
class Outgoing_Invoice_Excise_Model
{
    public $oinvoice_exciseID;
    public $oinvoice_No;
    public $_vehcle_no;
    public $_dnt_supply;
    public $_supply_place;
    public $_reverse_charge_payable;
    public $ins_charge;
    public $othc_charge;
    public $_docket_no;
    public $pono;
    public $poid;
    public $BuyerID;
    public $principalID;
    public $supplierID;
    public $mode_delivery;
    public $oinv_date;
    public $oinv_time;
    public $dispatch_time;
    public $po_date;
    public $Supplier_stage;
    public $discount;
    public $total_ed;
    public $inclusive_ed_tag;
    public $freight_percent;
    public $freight_amount;
    public $saleTax;
    public $pf_chrg;
    public $incidental_chrg;
    public $bill_value;
    public $payment_status;
    public $remarks;
    public $userId;
    public $insertDate;
    public $Buyer_Name;
    public $Principal_Name;
    public $Supplier_Name;
    public $_itmes  = array();
    public $_PO_Details  = array();
    public $mod_delivery_text;
	public $ms;
	public $bemailId;
	public $bname;
	public $freight_gst_rate; 
	public $freight_gst_amount; 
	public $p_f_gst_rate; 
	public $p_f_gst_amount; 
	public $inc_gst_rate; 
	public $inc_gst_amount; 
	public $ins_gst_rate; 
	public $ins_gst_amount; 
	public $othc_gst_rate; 
	public $othc_gst_amount;
	
	public static function ValidateOutgoingInvoiceExciseDataCalculation($data){
		$Data = json_decode($data,true);
		$PayAmount = 0.00;
		$finalTaxableAmount = 0.0;
		foreach($Data['_items'] as $item){
			$basic_amount = $item['issued_qty'] * $item['oinv_price'];
			$Discount_Amount = ($basic_amount * $item['discount']) / 100;
			$ed_amt = $item['ed_amt'];
			$edu_amt = $item['edu_amt'];
            $hedu_amt = $item['hedu_amount'];
            $cvd_amt =  $item['cvd_amt'];
			if($Data['inclusive_ed_tag'] == "I" || $Data['inclusive_ed_tag'] == true)
			{
				$ExciseAmount = $basic_amount - $Discount_Amount;
			}
			else
			{
				$ExciseAmount = $basic_amount - $Discount_Amount + $ed_amt + $edu_amt + $hedu_amt + $cvd_amt;
			}
			$pf_charge_amount = ($Data['pf_chrg_percent'] * $ExciseAmount) / 100;
			$incidental_amount = (($ExciseAmount + $pf_charge_amount) * $Data['incidental_chrg_percent']) / 100;
			$TaxableAmount = $ExciseAmount + $pf_charge_amount + $incidental_amount;
			$SaletaxPurcent = $SurchargePercent = $TaxAmount = $SurchargeAmount = 0.00;
			$taxid = $item['saleTax'];
			$Print = SalseTaxModel::LoadSalseTax($taxid);
			if (!empty($Print[0]->SALESTAX_CHRG)) {
				$SaletaxPurcent = $Print[0]->SALESTAX_CHRG;
			}
			else {
				$SaletaxPurcent = 0;
			}
			if (!empty($Print[0]->SURCHARGE)) {
				$SurchargePercent = $Print[0]->SURCHARGE;
			}
			else {
				$SurchargePercent = 0;
			}
			$TaxAmount = $TaxableAmount * $SaletaxPurcent;
			$TaxAmount = $TaxAmount / 100;
			$SurchargeAmount = $SurchargePercent * $TaxAmount;
			$SurchargeAmount = $SurchargeAmount / 100;
			$total_saleTax = $TaxAmount + $SurchargeAmount;
			$PayAmount = $PayAmount + $TaxableAmount + $total_saleTax;
			$finalTaxableAmount = $finalTaxableAmount + $TaxableAmount;
		}
		$Print = Purchaseorder_Model::LoadPurchaseByID($Data['poid'],$Data['pot']);
		$F_tag = $Print[0]->frgt;
		$F_amt = 0.00;
		if ($F_tag=='P') { 
			$F_amt = (($finalTaxableAmount) / 100) * $Print[0]->frgtp;
		}else if ($F_tag=='A') {
			 if (empty($Print[0]->frgta) || $Print[0]->frgta == "null") {
				 $freight_amount = 0;
			 }
			 else {
				 $freight_amount = $Print[0]->frgta;
			 }
			$F_amt = $freight_amount;
		}
		//$F_amt = $Data['freight_amount'];
		$PayAmount = $PayAmount + $F_amt;
		$bill_value = $PayAmount;
		$diff = $bill_value - $Data['bill_value'];
		if(abs($diff) > 5)
		{
			return 0;
		}
		return 1;
	}

    public static function GET_OINV_NUM_BY_DISPLAY($DISPLAYID,$YEAR){
        $Query = "select inner_outgoingInvoiceEx from outgoinginvoice_excise_mapping where display_outgoingInvoiceEx = $DISPLAYID AND finyear = '".$YEAR."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["inner_outgoingInvoiceEx"] > 0)
        {
            return $Row["inner_outgoingInvoiceEx"];
        }
        else
            return 0;
    }
    public static function Create_OIVNDISPLAYID(){
        $Query = "select (IFNULL(MAX(display_outgoingInvoiceEx),0)+1)display_outgoingInvoiceEx FROM outgoinginvoice_excise_mapping WHERE finyear='".MultiweldParameter::GetFinancialYear_fromTXT()."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["display_outgoingInvoiceEx"] > 0)
        {
            return $Row["display_outgoingInvoiceEx"];
        }
    }
    public static function INSERT_OIV_DISPLAY_ENTRY_MAPING($OEID,$oinvoice_No){
        $currentyear = MultiweldParameter::GetFinancialYear_fromTXT();
        //$Query = "insert into outgoinginvoice_excise_mapping (inner_outgoingInvoiceEx,display_outgoingInvoiceEx,finyear,outgoingInvoiceExNo) values (".$OEID.",".self::Create_OIVNDISPLAYID().",'".$currentyear."','".$oinvoice_No."')";
        $Query = "insert into outgoinginvoice_excise_mapping (inner_outgoingInvoiceEx,display_outgoingInvoiceEx,finyear,outgoingInvoiceExNo) values (".$OEID.",".self::Create_OIVNDISPLAYID().",'".$currentyear."','".$oinvoice_No."')";
        $Result = DBConnection::InsertQuery($Query);
    }
    public static function DELETE_OIV_DISPLAY_ENTRY_MAPING($OEID){
        $Query = "delete from outgoinginvoice_excise_mapping where inner_outgoingInvoiceEx = $OEID";
        $Result = DBConnection::InsertQuery($Query);
    }

    public static function createNewExciseInvoiceNumber($prefix, $exciseStr){
        //$Query = "SELECT oinvoice_exciseID FROM outgoinginvoice_excise ORDER BY oinvoice_exciseID DESC LIMIT 1";
         if(MANESAR){
			if($prefix == 'ST')
			{
				$Query = "SELECT CONCAT('ST02-',((SUBSTR(oinvoice_No,6,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
			else
			{
				$Query = "SELECT CONCAT('M02-',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
		}else if(GURGAON){
			if($prefix == 'ST')
			{
				$Query = "SELECT CONCAT('ST01-',((SUBSTR(oinvoice_No,6,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
			else
			{
				$Query = "SELECT CONCAT('M01-',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
		
		}else if(RUDRAPUR){
			if($prefix == 'ST')
			{
				$Query = "SELECT CONCAT('ST03-',((SUBSTR(oinvoice_No,6,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
			else
			{
				$Query = "SELECT CONCAT('M03-',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
		
		} else if(HARIDWAR){
			if($prefix == 'ST')
			{
				$Query = "SELECT CONCAT('ST04-',((SUBSTR(oinvoice_No,6,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
			else
			{
				$Query = "SELECT CONCAT('M04-',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
			}
		} else{
			$Query = "SELECT CONCAT('E-',((SUBSTR(oinvoice_No,3,8))+1)) AS new_outgoingInvoice FROM outgoinginvoice_excise WHERE oinvoice_No LIKE ('$exciseStr') ORDER BY oinvoice_exciseID DESC LIMIT 1";
		}
		//echo $Query;exit;
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["new_outgoingInvoice"];
    }

   public static function checkExciseInvoiceNumber($exciseStr){
     	        $Query = "SELECT COUNT(*)c FROM outgoinginvoice_excise AS oie where oie.oinvoice_No LIKE ('$exciseStr')";
				//echo '<br>CHECK POINT EXCISE QUERY --> '.$Query;
	            $Result = DBConnection::SelectQuery($Query);
	 		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
	 	        $total=$row['c'];
		        return $total;
   }
   
   public static function IsStockTransfer($buyer_id)
   {
		$CompanyInfo = ParamModel::GetCompanyInfo();
		
	   if(!empty($buyer_id)){
		   $Query = "SELECT  bgd.gst_no AS GSTIN FROM buyer_master bm JOIN buyer_gst_details bgd ON bgd.buyer_id = bm.BuyerId AND bm.StateId = bgd.gst_state_id WHERE bm.BuyerId = '".$buyer_id."'";
	   }else{
		   echo 'error';
		   exit;
	   }
	   
	   $Result = DBConnection::SelectQuery($Query);
	   $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	   $gstin = isset($Row['GSTIN'])?$Row['GSTIN']:0;
	   //echo 'CompanyInfo--> '.$CompanyInfo['gstin_number'].' GSTIN --> '.$gstin;//exit;
	   if(trim($CompanyInfo['gstin_number']) == trim($gstin))
	   {
		   return true;
	   }
	   else
	   {
		   return false;
	   }
   }
   
   public static function GetLastInvoiceNumber($buyer_id){
	        $FinancialYear = MultiweldParameter::GetFinancialYear();
			
			/* BOF to Calculate Prefix for Outgoing Invoice Number by Ayush Giri on 06-09-2017 */
			if(self::IsStockTransfer($buyer_id))
			{
				$prefix = 'ST';
			}
			else
			{
				$prefix = 'M';
			}
			/* EOF to Calculate Prefix for Outgoing Invoice Number by Ayush Giri on 06-09-2017 */
			
	        if(MANESAR){
				 $exciseStr= $prefix."02-".$FinancialYear."%";
			}else if (GURGAON){
				$exciseStr= $prefix."01-".$FinancialYear."%";
			} else if (RUDRAPUR){
				$exciseStr= $prefix."03-".$FinancialYear."%";
			} else if (HARIDWAR){
				$exciseStr= $prefix."04-".$FinancialYear."%";
			} else{
				 $exciseStr="E-".$FinancialYear."%";
			}
			
            $totExciseInvoiceNumber=self::checkExciseInvoiceNumber($exciseStr);
			$totExciseInvoiceNumber;
	        if($totExciseInvoiceNumber>0){
				
				//$getNewExciseInvoiceNo=self::createNewExciseInvoiceNumber($exciseStr);
				$getNewExciseInvoiceNo=self::createNewExciseInvoiceNumber($prefix,$exciseStr);
				
	           return $getNewExciseInvoiceNo;
	        }else{
				$firstExciseInvoiceNumber =$totExciseInvoiceNumber+1;
				$s = sprintf("%06d", $firstExciseInvoiceNumber);
				if(MANESAR){
					$exciseStr= $prefix."02-".$FinancialYear.$s;
				}else if (GURGAON){
					$exciseStr= $prefix."01-".$FinancialYear.$s;
				} else if (RUDRAPUR){
					$exciseStr= $prefix."03-".$FinancialYear.$s;
				} else if (HARIDWAR){
					$exciseStr= $prefix."04-".$FinancialYear.$s;
				} else{
					 $exciseStr="E-".$FinancialYear.$s;
				}
				return $exciseStr;
			}
   }


    //public static function InsertOutgoingInvoiceExcise($oinvoice_No,$pono, $BuyerID, $principalID,  $supplierID,  $mode_delivery,  $oinv_date, $oinv_time, $po_date,  $discount,  $inclusive_ed_tag, $freight_percent,  $freight_amount,  $pf_chrg,  $incidental_chrg,  $bill_value,  $payment_status, $remarks,  $userId,$ms,$ins_charge,$othc_charge,$_supply_place,$_reverse_charge_payable,$_dnt_supply,$_docket_no){
	public static function InsertOutgoingInvoiceExcise($oinvoice_No,$pono, $BuyerID, $principalID,  $supplierID,  $mode_delivery,  $oinv_date, $oinv_time, $po_date,  $discount,  $inclusive_ed_tag, $freight_percent,  $freight_amount,  $pf_chrg,  $incidental_chrg,  $bill_value,  $payment_status, $remarks,  $userId,$ms,$ins_charge,$othc_charge,$_supply_place,$_reverse_charge_payable,$_dnt_supply,$_docket_no, $p_f_gst_rate, $p_f_gst_amount, $inc_gst_rate, $inc_gst_amount, $ins_gst_rate, $ins_gst_amount, $othc_gst_rate, $othc_gst_amount, $freight_gst_rate, $freight_gst_amount){
		
        $date = date("Y-m-d");
		
		$oinvoice_No = self::GetLastInvoiceNumber($BuyerID);
		//$oinvoice_No = self::GetLastInvoiceNumber();
		//echo 'INVOICE NUMBER --> '.$oinvoice_No; exit;
        if($supplierID==NULL){
			$supplierID='NULL';
		}
        if($discount==NULL){
			$discount='NULL';
		}
        if($freight_percent==NULL){
			$freight_percent='NULL';
		}
        if($freight_amount==NULL){
			$freight_amount='NULL';
		}
        if($pf_chrg==NULL){
			$pf_chrg='NULL';
		}
        if($incidental_chrg==NULL){
			$incidental_chrg='NULL';
		}
		if($ins_charge==NULL){
			$ins_charge='NULL';
		}
		if($othc_charge==NULL){
			$othc_charge='NULL';
		}
		if($_dnt_supply==NULL){
			$_dnt_supply='0000-00-00 00:00:00';
		}
		//echo 'oinvoice_No --> '.$oinvoice_No;exit;
        $CheckQuery = "select count(*) as inv from outgoinginvoice_excise where oinvoice_No = '$oinvoice_No'";
		//echo '<br/>CheckQuery --> '.$CheckQuery;
        $FindInv = DBConnection::SelectQuery($CheckQuery);
        $FindRow = mysql_fetch_array($FindInv, MYSQL_ASSOC);
		//echo '<br/>FindRow<pre>';print_r($FindRow);echo '</pre>';exit;
        if($FindRow["inv"] > 0)
        {
            return 0;
        }
        else
        {
			//added on 01-JUNE-2016 due to Handle Special Character			
			$remarks = mysql_escape_string($remarks);
			
			
            //$Query = "INSERT INTO outgoinginvoice_excise (oinvoice_No,pono, BuyerID, principalID, supplierID, mode_delivery, oinv_date, oinv_time, po_date, discount, inclusive_ed_tag, freight_percent, freight_amount, pf_chrg, incidental_chrg, bill_value, balanceAmount, payment_status, msid ,remarks, userId, insertDate,insurance_charge,other_charge,place_of_supply,reverse_charge_payable,datetime_of_supply,docket_no) VALUES ('$oinvoice_No',$pono,  $BuyerID,  $principalID,  $supplierID,  '$mode_delivery',  '$date','$oinv_time', $pono,  $discount, '$inclusive_ed_tag', $freight_percent,  $freight_amount,  $pf_chrg,  $incidental_chrg,  $bill_value, $bill_value, '$payment_status','$ms','$remarks', '$userId', '$date', $ins_charge, $othc_charge,'$_supply_place',$_reverse_charge_payable,'$_dnt_supply','$_docket_no')";
			$Query = "INSERT INTO outgoinginvoice_excise (oinvoice_No,pono, BuyerID, principalID, supplierID, mode_delivery, oinv_date, oinv_time, po_date, discount, inclusive_ed_tag, freight_percent, freight_amount, pf_chrg, incidental_chrg, bill_value, balanceAmount, payment_status, msid ,remarks, userId, insertDate, insurance_charge, other_charge, place_of_supply, reverse_charge_payable, datetime_of_supply, docket_no, p_f_gst_rate, p_f_gst_amount, inc_gst_rate, inc_gst_amount, ins_gst_rate, ins_gst_amount, othc_gst_rate, othc_gst_amount, freight_gst_rate, freight_gst_amount) VALUES ('$oinvoice_No', $pono, $BuyerID, $principalID, $supplierID, '$mode_delivery', '$date', '$oinv_time', $pono, $discount, '$inclusive_ed_tag', $freight_percent,  $freight_amount, $pf_chrg, $incidental_chrg, $bill_value, $bill_value, '$payment_status', '$ms', '$remarks', '$userId', '$date', $ins_charge, $othc_charge, '$_supply_place', $_reverse_charge_payable,'$_dnt_supply', '$_docket_no', $p_f_gst_rate, $p_f_gst_amount, $inc_gst_rate, $inc_gst_amount, $ins_gst_rate, $ins_gst_amount, $othc_gst_rate, $othc_gst_amount, $freight_gst_rate, $freight_gst_amount)";
            $Result = DBConnection::InsertQuery($Query);

            if($Result > 0){
                return $Result;
            }
            else{
                return -1;
            }
        }

    }

    public static function UpdateOutgoingInvoiceExcise($oinvoice_exciseID,$oinvoice_No,$pono,$BuyerID,$principalID,  $supplierID,  $mode_delivery,  $oinv_date, $oinv_time, $po_date, $discount, $inclusive_ed_tag, $freight_percent,  $freight_amount, $pf_chrg,  $incidental_chrg,  $bill_value,  $payment_status, $remarks,  $userId,$ms,$ins_charge,$othc_charge,$_supply_place,$_reverse_charge_payable,$_dnt_supply,$_docket_no){
        if($supplierID==NULL){
			$supplierID='NULL';
		}
        if($discount==NULL){
			$discount='NULL';
		}
        if($freight_percent==NULL){
			$freight_percent='NULL';
		}
        if($freight_amount==NULL){
			$freight_amount='NULL';
		}
        if($pf_chrg==NULL){
			$pf_chrg='NULL';
		}
        if($incidental_chrg==NULL){
			$incidental_chrg='NULL';
		}
		if($ins_charge==NULL){
			$ins_charge='NULL';
		}
		if($othc_charge==NULL){
			$othc_charge='NULL';
		}
		if($_dnt_supply==NULL){
			$_dnt_supply='0000-00-00 00:00:00';
		}
		$oinv_date1= MultiweldParameter::xFormatDate($oinv_date);
		
		//added on 02-JUNE-2016 due to Handle Special Character			
		$remarks = mysql_escape_string($remarks);
	
		$Query = "UPDATE outgoinginvoice_excise ";
		$Query = $Query."SET oinvoice_No = '$oinvoice_No' , pono = $pono, BuyerID = $BuyerID, principalID = $principalID, supplierID = $supplierID,";
		$Query = $Query."mode_delivery = '$mode_delivery', oinv_date = '$oinv_date1', oinv_time = '$oinv_time',";
		$Query = $Query."po_date = $po_date, discount = $discount,";
		$Query = $Query."inclusive_ed_tag = '$inclusive_ed_tag', freight_percent = $freight_percent, freight_amount = $freight_amount,";
		$Query = $Query."pf_chrg = $pf_chrg, incidental_chrg = $incidental_chrg, bill_value = $bill_value,balanceAmount = $bill_value, ";
		$Query = $Query."payment_status = '$payment_status',msid='$ms', remarks = '$remarks', userId = '$userId', insurance_charge = $ins_charge, other_charge = $othc_charge, place_of_supply = '$_supply_place', reverse_charge_payable =  $_reverse_charge_payable, datetime_of_supply = '$_dnt_supply', docket_no = '$_docket_no'";
		$Query = $Query."WHERE oinvoice_exciseID=$oinvoice_exciseID";
	   
		$Result = DBConnection::UpdateQuery($Query);
   
		if($Result =="SUCCESS"){
			return $Result;
		}else{
			return -1;
		}
   }

    public function __construct($oinvoice_exciseID,$oinvoice_No,$pono,$poid,$BuyerID,$principalID,$supplierID, $mode_delivery,$oinv_date,$oinv_time,$dispatch_time,$po_date,$Supplier_stage,$discount,$total_ed,$inclusive_ed_tag,$freight_percent,$freight_amount,$saleTax,$pf_chrg,$incidental_chrg,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name, $Supplier_Name,$_itmes,$_PO_Details,$mod_delivery_text,$ms,$poinc_charge=0,$popf_charge=0,$_vehcle_no,$_dnt_supply,$_supply_place,$_reverse_charge_payable,$ins_charge,$othc_charge,$_docket_no,$bemailId,$bname, $freight_gst_rate, $freight_gst_amount, $p_f_gst_rate, $p_f_gst_amount, $inc_gst_rate, $inc_gst_amount, $ins_gst_rate, $ins_gst_amount, $othc_gst_rate, $othc_gst_amount){
        $this->oinvoice_exciseID = $oinvoice_exciseID;
        $this->oinvoice_No = $oinvoice_No;
        $this->_vehcle_no = $_vehcle_no;
        $this->_dnt_supply = $_dnt_supply;
        $this->_supply_place = $_supply_place;
        $this->_reverse_charge_payable = $_reverse_charge_payable;
        $this->ins_charge = $ins_charge;
        $this->othc_charge = $othc_charge;
        $this->_docket_no = $_docket_no;
        $this->pono = $pono;
        $this->poid = $poid;
        $this->BuyerID = $BuyerID;
        $this->principalID = $principalID;
        $this->supplierID = $supplierID;
        $this->mode_delivery = $mode_delivery;
        $this->oinv_date = $oinv_date;
        $this->oinv_time = $oinv_time;
        $this->dispatch_time = $dispatch_time;
        $this->po_date = $po_date;
        $this->Supplier_stage = $Supplier_stage;
        $this->discount = $discount;
        $this->total_ed = $total_ed;
        $this->inclusive_ed_tag = $inclusive_ed_tag;
        $this->freight_percent = $freight_percent;
        $this->freight_amount = $freight_amount;
        $this->saleTax = $saleTax;
        $this->pf_chrg = $pf_chrg;
        $this->incidental_chrg = $incidental_chrg;
        $this->bill_value = $bill_value;
        $this->payment_status = $payment_status;
        $this->remarks = $remarks;
        $this->userId = $userId;
        $this->insertDate = $insertDate;
        $this->Buyer_Name = $Buyer_Name;
        $this->Principal_Name = $Principal_Name;
        $this->Supplier_Name = $Supplier_Name;
        $this->_itmes = $_itmes;
        $this->_PO_Details = $_PO_Details;
        $this->mod_delivery_text = $mod_delivery_text;
		$this->ms = $ms;
		$this->_poinc_charge = $poinc_charge;
		$this->_popf_charge = $popf_charge;	
		$this->bemailId = $bemailId;	
		$this->bname = $bname;	
		$this->freight_gst_rate = $freight_gst_rate; 
		$this->freight_gst_amount = $freight_gst_amount; 
		$this->p_f_gst_rate = $p_f_gst_rate; 
		$this->p_f_gst_amount = $p_f_gst_amount;
		$this->inc_gst_rate = $inc_gst_rate; 
		$this->inc_gst_amount = $inc_gst_amount; 
		$this->ins_gst_rate = $ins_gst_rate; 
		$this->ins_gst_amount = $ins_gst_amount; 
		$this->othc_gst_rate = $othc_gst_rate; 
		$this->othc_gst_amount = $othc_gst_amount;
	}

    public static function LoadPrincipal($poId,$po_ed_applicability){
    	    $opt='';
    	    //Comment by gajendra
    	     //~ if($po_ed_applicability!="")
    	    //~ {   if($po_ed_applicability=='E')
    	        //~ {
					//~ $opt=" AND (pod.po_ed_applicability='E' OR pod.po_ed_applicability='I' )";
				//~ }
				//~ else
				//~ {
					//~ $opt=" AND pod.po_ed_applicability='$po_ed_applicability'";
				//~ }
				//~ 
			//~ }
			//end
            $Query = "";
       		$Query = "SELECT distinct pm.Principal_Supplier_Id, pm.Principal_Supplier_Name FROM purchaseorder_detail as pod INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = pod.po_principalId WHERE pod.bpoId ='$poId' $opt";
			$result = DBConnection::SelectQuery($Query);
	   		$objArray = array();
	   		$i = 0;
	   		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	               $principalID = $Row['Principal_Supplier_Id'];
	               $Principal_Name = $Row['Principal_Supplier_Name'];
                   $newObj = new Outgoing_Invoice_Excise_Model(null,null,null,null,null,$principalID,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,$Principal_Name,null,null,null,null,null);
	               //$newObj = new Outgoing_Invoice_Excise_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,0,$po_codePartNo,$po_buyeritemcode,$po_unit,$po_qty,$po_price,$po_discount,$po_ed_applicability,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal,$itemdescp,$Item_Identification_Mark,$po_odiscount,$po_oprice);
	               $objArray[$i] = $newObj;
	               $i++;
	   		}
		return $objArray;

    }

        public static function GetDeliveryModeText($type)
        {
            $Query = "SELECT  PARAM_VALUE1  FROM  param  WHERE  PARAM_TYPE = 'DELIVERY' AND PARAM_CODE = 'MODE' AND PARAM1 = '$type';";
            $Result = DBConnection::SelectQuery($Query);
            $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
            return $Row["PARAM_VALUE1"];
        }

    public static function  LoadOutgoingInvoiceExcise($oinvoice_exciseID,$start=null,$rp=null,$year)
	{
        $result;
        if($oinvoice_exciseID > 0)
        {
            $result = self::GetOutgoingInvoiceExciseDetails($oinvoice_exciseID);
        }
        else
        {
            $result = self::GetOutgoingInvoiceExciseList($start,$rp);
        }

		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
            $oinvoice_exciseID = $Row['oinvoice_exciseID'];
            $oinvoice_No = $Row['oinvoice_No'];
            $_vehcle_no = $Row['vehcle_no'];
            $_dnt_supply = $Row['datetime_of_supply'];
            $_docket_no = $Row['docket_no'];
            $_supply_place = $Row['place_of_supply'];
            $_reverse_charge_payable = $Row['reverse_charge_payable'];
            $ins_charge = $Row['insurance_charge'];
            $othc_charge = $Row['other_charge'];
            $pono = $Row['pono'];
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
            $supplierID = empty($Row['supplierID'])?'':$Row['supplierID'];
            $mode_delivery = $Row['mode_delivery'];
            $mod_delivery_text = self::GetDeliveryModeText($mode_delivery);
            $oinv_date = MultiweldParameter::xFormatDate1($Row['oinv_date']);
            $oinv_time = $Row['oinv_time'];
            $dispatch_time = $Row['dispatch_time'];
            $po_date = $Row['po_date'];
            $Supplier_stage = $Row['Supplier_stage'];
            $discount = $Row['discount'];
            $total_ed = $Row['total_ed'];
            $inclusive_ed_tag = $Row['inclusive_ed_tag'];
            $freight_percent = $Row['freight_percent'];
            $freight_amount = $Row['freight_amount'];
            $saleTax = $Row['saleTax'];
            $pf_chrg = $Row['pf_chrg'];
            $pincidental_chrg = $Row['poinc_charge'];
            $bemailId = $Row['po_ack_email'];
            $bname = $Row['po_ack_name'];
			$ppf_chrg = $Row['popf_charge'];
            $incidental_chrg = $Row['incidental_chrg'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = $Row['insertDate'];
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['PRINCIPALNAME'];
            $Supplier_Name = $Row['SUPPLIERNAME'];
            $bpoType = $Row['bpoType'];
			$ms = $Row['msid'];
			$freight_gst_rate = $Row['freight_gst_rate'];
			$freight_gst_amount = $Row['freight_gst_amount'];
			$p_f_gst_rate = $Row['p_f_gst_rate'];
			$p_f_gst_amount = $Row['p_f_gst_amount'];
			$inc_gst_rate = $Row['inc_gst_rate'];
			$inc_gst_amount = $Row['inc_gst_amount'];
			$ins_gst_rate = $Row['ins_gst_rate'];
			$ins_gst_amount = $Row['ins_gst_amount'];
			$othc_gst_rate = $Row['othc_gst_rate'];
			$othc_gst_amount = $Row['othc_gst_amount'];
			if($ms == 0){
			$ms = '';
			}
						
            if($oinvoice_exciseID > 0)
            {
                $_itmes = Outgoing_Invoice_Excise_Model_Details::LoadOutgoingInvoiceExciseDetails($Row['oinvoice_exciseID'],$bpoType);
            }
            else
            {
                $_itmes = null;
            }
           
            //$_PO_Details = Outgoing_Invoice_Excise_Model_Payment_Details::LoadOutgoingInvoiceExcisePaymentDetails($pono);
            //$newObj = new Outgoing_Invoice_Excise_Model($oinvoice_exciseID,$oinvoice_No,$pono,null,$BuyerID,$principalID,$supplierID,$mode_delivery,$oinv_date,$oinv_time,$dispatch_time,$po_date,$Supplier_stage,$discount,$total_ed,$inclusive_ed_tag,$freight_percent,$freight_amount,$saleTax,$pf_chrg,$incidental_chrg,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name,$Principal_Name,$Supplier_Name,$_itmes,null,$mod_delivery_text,$ms,$pincidental_chrg,$ppf_chrg,$_vehcle_no,$_dnt_supply,$_supply_place,$_reverse_charge_payable,$ins_charge,$othc_charge,$_docket_no,$bemailId,$bname);
			$newObj = new Outgoing_Invoice_Excise_Model( $oinvoice_exciseID, $oinvoice_No, $pono, null, $BuyerID, $principalID, $supplierID, $mode_delivery, $oinv_date, $oinv_time, $dispatch_time, $po_date, $Supplier_stage, $discount, $total_ed, $inclusive_ed_tag, $freight_percent, $freight_amount, $saleTax, $pf_chrg, $incidental_chrg, $bill_value, $payment_status, $remarks, $userId, $insertDate, $Buyer_Name, $Principal_Name, $Supplier_Name, $_itmes, null, $mod_delivery_text, $ms, $pincidental_chrg, $ppf_chrg, $_vehcle_no, $_dnt_supply, $_supply_place, $_reverse_charge_payable,$ins_charge, $othc_charge, $_docket_no, $bemailId, $bname, $freight_gst_rate, $freight_gst_amount, $p_f_gst_rate, $p_f_gst_amount, $inc_gst_rate, $inc_gst_amount, $ins_gst_rate, $ins_gst_amount, $othc_gst_rate, $othc_gst_amount);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function SearchOutgoingInvoiceExcise($year,$oinv,$Fromdate,$Todate,$Principalid,$Buyerid,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";

        $Query = "SELECT oie.*,oem.display_outgoingInvoiceEx,bm.BuyerName,pm.Principal_Supplier_Name as principalname,
		sm.Principal_Supplier_Name as suppliername ,
		IFNULL(pom.display_pono,po.bpono ) as bpono,po.bpoDate,po.bpono as ponum
		FROM outgoinginvoice_excise AS oie
		INNER JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId
		LEFT JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id
		LEFT JOIN principal_supplier_master as sm ON oie.supplierID = sm.Principal_Supplier_Id
		INNER JOIN outgoinginvoice_excise_mapping AS oem ON
		oem.inner_outgoingInvoiceEx=oie.oinvoice_exciseID
		LEFT join po_excise_nonexcise_mapping as pom on pom.new_bpoId = oie.pono
		LEFT join purchaseorder as po on po.bpoId = oie.pono
		WHERE  oem.finyear='".$year."' ";

        if($oinv!=""){
           $Query =$Query. " and oie.oinvoice_No  = '$oinv' ";
        }
        if($Fromdate!=""){
           $Query =$Query. " and oie.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $Query =$Query. " and oie.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $Query =$Query. " and oie.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $Query =$Query. " and oie.BuyerID = $Buyerid";
        }
        $Query =$Query." ORDER BY oem.display_outgoingInvoiceEx DESC LIMIT $start , $rp ";

      

        $CountQuery = "SELECT count(*) as total FROM outgoinginvoice_excise AS oie ";
        $CountQuery =$CountQuery. " INNER JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id LEFT JOIN principal_supplier_master as sm ON oie.supplierID = sm.Principal_Supplier_Id ";
        $CountQuery =$CountQuery. " INNER JOIN outgoinginvoice_excise_mapping AS oem ON oem.inner_outgoingInvoiceEx=oie.oinvoice_exciseID ";
        $CountQuery =$CountQuery. " WHERE  oem.finyear='".$year."'";

        if($oinv!=""){
           $CountQuery =$CountQuery. " and oie.oinvoice_No  = '$oinv' ";
        }
        if($Fromdate!=""){
           $CountQuery =$CountQuery. " and oie.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $CountQuery =$CountQuery. " and oie.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $CountQuery =$CountQuery. " and oie.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $CountQuery =$CountQuery. " and oie.BuyerID = $Buyerid";
        }

        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];
		$result = DBConnection::SelectQuery($Query);
        $objArray = array();
		$i = 0;

	while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_exciseID = $Row['display_outgoingInvoiceEx'];
            $oinvoice_No = $Row['oinvoice_No'];
            $pono = $Row['ponum'];
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
            $supplierID = $Row['supplierID'];
            $mode_delivery = $Row['mode_delivery'];
            $oinv_date = $Row['oinv_date'];
            $oinv_time = $Row['oinv_time'];
            $dispatch_time = $Row['dispatch_time'];
            $po_date = $Row['bpoDate'];
            $bpoid = $Row['pono'];
            $Supplier_stage = $Row['Supplier_stage'];
            $discount = $Row['discount'];
            $total_ed = $Row['total_ed'];
            $inclusive_ed_tag = $Row['inclusive_ed_tag'];
            $freight_percent = $Row['freight_percent'];
            $freight_amount = $Row['freight_amount'];
            $saleTax = $Row['saleTax'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = $Row['insertDate'];
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['principalname'];
            $Supplier_Name = $Row['suppliername'];
            $_itmes = null;
            $mod_delivery_text = "";
			$ms = $Row['msid'];
            $newObj = new Outgoing_Invoice_Excise_Model($oinvoice_exciseID,$oinvoice_No,$pono,null,$BuyerID,$principalID,$supplierID,$mode_delivery,$oinv_date,$oinv_time,$dispatch_time,$po_date,$Supplier_stage,$discount,$total_ed,$inclusive_ed_tag,$freight_percent,$freight_amount,$saleTax,$pf_chrg,$incidental_chrg,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name,$Principal_Name,$Supplier_Name,$_itmes,null,$mod_delivery_text,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
	    return $objArray;
	}
    public static function GetTaxByPOID($POID,$PrincId,$itemId){
        $Query = "select po_saleTax from purchaseorder_detail where bpoId = $POID  AND po_principalId = $PrincId AND po_codePartNo = $itemId";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        $Value = $Row['po_saleTax'];
        return $Value;
	}
	
//################## 

public static function 	UpdatePOItemStage($changeStage,$Bpod_id,$itemId,$potype,$bpoType)
{
	 if($potype=="N")
       {
	      $chkIsuue="SELECT SUM(ivd.issued_qty) AS issued_qty,ivd.ordered_qty FROM outgoinginvoice_nonexcise_detail AS ivd ,outgoinginvoice_nonexcise AS iv,purchaseorder AS po WHERE ivd.oinvoice_nexciseID=iv.oinvoice_nexciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$Bpod_id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_nexcisedID DESC";
	     
       }
       else if($potype=="E")
       {
       	 $chkIsuue="SELECT SUM(ivd.issued_qty) AS issued_qty,ivd.ordered_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$Bpod_id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";
       }
      
       $Result1 = DBConnection::SelectQuery($chkIsuue);
       $row1=mysql_fetch_row($Result1);
        if($row1[0]==$row1[1])
       {
		//added on 01-JUNE-2016 due to Handle Special Character
        $changeStage = mysql_escape_string($changeStage);
     
       	 if($bpoType=="R")
       	 {
			 
		 	$Query = "update purchaseorder_schedule_detail SET pos_item_stage='$changeStage'  WHERE bposd_Id = $Bpod_id";
		 }
		 else
		 {
		   $Query = "update purchaseorder_detail SET po_item_stage='$changeStage' WHERE bpod_Id=$Bpod_id";	
		 } 
	     
	   
         $Result = DBConnection::UpdateQuery($Query);
      }
   else
   {
   	   if($bpoType=="R")
       {
		  $Query = "update purchaseorder_schedule_detail SET pos_item_stage='POIG' WHERE bposd_Id=$Bpod_id";
	   }
	   else
	   {
	   	   $Query = "update purchaseorder_detail SET po_item_stage = 'POIG' WHERE bpod_Id=$Bpod_id";
	   }
   	   
   	  
        $Result = DBConnection::UpdateQuery($Query);

   }

		return $Result;
}

//#######################	
 public static function UpdateItemStage($changeStage,$Bpod_id,$potype)
 {
   if($potype=="N")
   {
	$chkIsuue="SELECT SUM(issued_qty) AS tot_issued_qty,ordered_qty FROM outgoinginvoice_nonexcise_detail WHERE buyer_item_code='$Bpod_id' GROUP BY buyer_item_code";
   }
   else if($potype=="E")
   {
	  $chkIsuue="SELECT SUM(issued_qty) AS tot_issued_qty,ordered_qty FROM outgoinginvoice_excise_detail WHERE buyer_item_code='$Bpod_id' GROUP BY buyer_item_code";
   }

   $Result1 = DBConnection::SelectQuery($chkIsuue);

   $row1=mysql_fetch_row($Result1);
 
   if($row1[0]==$row1[1])
   {
	  $Query = "update purchaseorder_detail SET po_item_stage = '$changeStage' WHERE bpod_Id = $Bpod_id";
	
      $Result = DBConnection::UpdateQuery($Query);
   }
   else
   {
   	    $Query = "update purchaseorder_detail SET po_item_stage = 'POIG' WHERE bpod_Id = $Bpod_id";
   	   
        $Result = DBConnection::UpdateQuery($Query);

   }

		return $Result;
}
    public static function GetPurchaseOrderForBilling($po=null){
	
		if($po != null){
			$Query = "SELECT bpoId,bpono FROM purchaseorder WHERE PO_STATUS='O' AND bpono LIKE '$po%' AND po_state != 'H' AND bpoType != 'B' AND (management_approval='N' OR (management_approval='R' AND APPROVAL_STATUS='A') )";
		}else{
			$Query = "SELECT bpoId,bpono FROM purchaseorder WHERE PO_STATUS='O' AND bpoType != 'B' AND (management_approval='N' OR (management_approval='R' AND APPROVAL_STATUS='A') )";
		}
		//echo $Query; exit; 
        $Result = DBConnection::SelectQuery($Query);
      
        $objArray = array();
        //$i = 0;
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $pono = $Row['bpono'];
            $poid = $Row['bpoId'];
            $objArray[$poid] = $pono;
            //$i++;
         }
//        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
//            $pono = $Row['bpono'];
//            $poid = $Row['bpoId'];
//            $newObj = new Outgoing_Invoice_Excise_Model($oinvoice_exciseID,$oinvoice_No,$pono,$poid,$BuyerID,$principalID,$supplierID,$mode_delivery,$oinv_date,$oinv_time,$dispatch_time,    $po_date,$Supplier_stage,$discount,$total_ed,$inclusive_ed_tag,$freight_percent,$freight_amount,$saleTax,$pf_chrg,$incidental_chrg,$bill_value,$payment_status,$remarks,    $userId,$insertDate,$Buyer_Name,$Principal_Name,$Supplier_Name,$_itmes,$_Payment,$mod_delivery_text);
//            $objArray[$i] = $newObj;
//            $i++;
//         }
        return $objArray;
    }
    
     public static function GetBundlePurchaseOrderForBilling($po=null){
	
		if($po != null){
			$Query = "SELECT bpoId,bpono FROM purchaseorder WHERE PO_STATUS='O' AND bpono LIKE '$po%' AND po_state != 'H' AND bpoType = 'B' AND (management_approval='N' OR (management_approval='R' AND APPROVAL_STATUS='A') )";
		}else{
			$Query = "SELECT bpoId,bpono FROM purchaseorder WHERE PO_STATUS='O' AND bpoType = 'B' AND (management_approval='N' OR (management_approval='R' AND APPROVAL_STATUS='A') )";
		}		
        $Result = DBConnection::SelectQuery($Query);      
        $objArray = array();
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $pono = $Row['bpono'];
            $poid = $Row['bpoId'];
            $objArray[$poid] = $pono;
           
         }
        return $objArray;
    }
    
    
    
    public static function GetOutgoingInvoiceExciseDetails($oinvoice_exciseID){
        $Query = "SELECT oie.*,bm.BuyerName,pm.Principal_Supplier_Name as PRINCIPALNAME,sm.Principal_Supplier_Name as SUPPLIERNAME,pod.bpoType,pod.pf_chrg AS popf_charge,pod.incidental_chrg AS poinc_charge, pod.po_ack_email, pod.po_ack_name
		FROM outgoinginvoice_excise as oie LEFT JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id LEFT JOIN principal_supplier_master as sm ON oie.supplierID = sm.Principal_Supplier_Id,purchaseorder AS pod WHERE pod.bpoId=oie.pono AND oie.oinvoice_exciseID = $oinvoice_exciseID";
		
        $Result = DBConnection::SelectQuery($Query);
      
        return $Result;
    }
    public static function GetOutgoingInvoiceExciseList($start,$rp,$year){
        $Query = "SELECT oie.*,bm.BuyerName,pm.Principal_Supplier_Name as PRINCIPALNAME,sm.Principal_Supplier_Name as SUPPLIERNAME FROM outgoinginvoice_excise as oie LEFT JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id LEFT JOIN principal_supplier_master as sm ON oie.supplierID = sm.Principal_Supplier_Name 
		INNER JOIN outgoinginvoice_excise_mapping AS oem ON oem.inner_outgoingInvoiceEx=oie.oinvoice_exciseID WHERE  oem.finyear='".$year."' LIMIT $start , $rp";
        $Result = DBConnection::SelectQuery($Query);
        return $Result;
		
    }
    public static function DeleteOutgoingExcise($InvoiceId){
        $Query = "delete from outgoinginvoice_excise where oinvoice_exciseID = $InvoiceId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
	
	public static function DeleteOutgoingInvoiceExcise($InvoiceId, $tranId, $event){
		
		$Query ="SELECT oied.iinv_no, oied.codePartNo_desc, issued_qty, oie.pono, po.bpoType FROM outgoinginvoice_excise_detail oied JOIN  outgoinginvoice_excise oie ON oie.oinvoice_exciseID = oied.oinvoice_exciseID LEFT JOIN purchaseorder po ON po.bpoId = oie.pono WHERE oied.oinvoice_exciseID = '".$InvoiceId."' ";
		
		$Result = DBConnection::SelectQuery($Query);
		if(mysql_num_rows($Result) > 0)
		{
			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) 
			{
				$iinv_no = $Row['iinv_no'];
				$issued_qty = $Row['issued_qty'];
				$code_partNo = $Row['codePartNo_desc'];
				$pono = $Row['pono'];
				$bpoType = $Row['bpoType'];
				
				if($tranId>0)
				{
					$Update_incoming = "UPDATE incominginventory SET qty = qty + $issued_qty WHERE incoming_inventory_id = $iinv_no";
					
					$Result_Update_incoming = DBConnection::UpdateQuery($Update_incoming);
					
					$Delete_transaction_details = "DELETE FROM transaction_detail WHERE transactionId = '".$tranId."' AND excise_nonexcise_TAG = 'OE' AND code_partNo = '".$code_partNo."'";
					$Result_Delete_transaction_details = DBConnection::UpdateQuery($Delete_transaction_details);
					
					$Update_inventory = "UPDATE inventory SET tot_Qty = tot_Qty + $issued_qty WHERE code_partNo = $code_partNo";
					$Result_Update_inventory = DBConnection::UpdateQuery($Update_inventory);
				}
				
				if($bpoType = 'R')
				{
					$Update_Purchase_details = "UPDATE purchaseorder_schedule_detail SET pos_item_stage= 'YDE'  WHERE bpoId = '".$pono."' AND sch_codePartNo = '".$code_partNo."'";
					
				}
				else
				{
					$Update_Purchase_details = "UPDATE purchaseorder_detail SET po_item_stage = 'YDE' WHERE bpoId = '".$pono."' AND po_codePartNo = '".$code_partNo."'";
				}
				
				$Result_Update_Purchase_details = DBConnection::UpdateQuery($Update_Purchase_details);
			}
			$Delete_outgoing_details = "DELETE FROM outgoinginvoice_excise_detail WHERE oinvoice_exciseID = '".$InvoiceId."' ";
			$Result_Delete_outgoing_details = DBConnection::UpdateQuery($Delete_outgoing_details);
		}
		
		$Delete_transaction = "DELETE FROM transaction WHERE refId = '".$InvoiceId."' AND transactionId = '".$tranId."' ";
		$Result_Delete_transaction = DBConnection::UpdateQuery($Delete_transaction);
		
		$Delete_outgoinginvoice_excise_mapping = "DELETE FROM outgoinginvoice_excise_mapping WHERE 	inner_outgoingInvoiceEx = '".$InvoiceId."' ";
		$Result_Delete_outgoinginvoice_excise_mapping = DBConnection::UpdateQuery($Delete_outgoinginvoice_excise_mapping);
		
		$Delete_event = "DELETE FROM events WHERE po_inv_id = '".$InvoiceId."' AND id = '".$event."' ";
		$Result_Delete_event = DBConnection::UpdateQuery($Delete_event);
		
		$Delete_outgoing = "DELETE FROM outgoinginvoice_excise WHERE oinvoice_exciseID = '".$InvoiceId."'";
		$Result_Delete_outgoing = DBConnection::UpdateQuery($Delete_outgoing);
		
		return;
	}
}

class Outgoing_Invoice_Excise_Model_Details
{
    public $oinvoice_exciseID;
    public $_item_id;
    public $buyer_item_code;
    public $oinv_codePartNo;
    public $codePartNo_desc;
    public $ordered_qty;
    public $item_discount;
    public $item_taxable_total;
    public $iinv_no;
    public $issued_qty;
    public $oinv_price;
    public $tot_price;
    public $ed_percent;
    public $ed_amt;
    public $ed_perUnit;
    public $entryId;
    public $edu_percent;
    public $edu_amt;
    public $hedu_percent;
    public $hedu_amount;
    public $cvd_percent;
    public $cvd_amt;
    public $bpod_Id;
    public $mappingid;
    public $entryDId;
    public $stock_qty;
    public $balance_qty;	
	public $principal_inv_date;
	public $UnitId;
	public $Item_Identification_Mark;
	public $hsn_code ;
	public $cgst_rate ;
	public $cgst_amt ;
	public $sgst_rate ;
	public $sgst_amt ;
	public $igst_rate ;
	public $igst_amt ;
    public static function InsertOutgoingInvoiceExciseDetails($oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$iinv_no,$issued_qty,$item_discount,$item_taxable_total,$hsn_code,$entryId,$cgst_rate,$sgst_rate,$igst_rate,$cgst_amt,$sgst_amt,$igst_amt,$tot_price,$oinv_price){
        if($cvd_amt==null || $cvd_amt==NULL){
        $cvd_amt="0.00";
        }
        if($entryId == NULL){
			$entryId = 0;
		}
       	// added on 02-JUNE-2016 due to Handle Special Character
        $buyer_item_code = mysql_escape_string($buyer_item_code);
        $codePartNo_desc = mysql_escape_string($codePartNo_desc);
       
        //comment by gajendra
        //~ $Query = "INSERT INTO outgoinginvoice_excise_detail (oinvoice_exciseID, buyer_item_code, oinv_codePartNo, codePartNo_desc, ordered_qty, iinv_no, issued_qty, oinv_price, ed_percent, ed_amt, ed_perUnit, entryId, edu_percent, edu_amt,hedu_percent, hedu_amount, cvd_percent, cvd_amt) VALUES ($oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$iinv_no,$issued_qty,$oinv_price,$ed_percent,$ed_amt,$ed_perUnit,$entryId,$edu_percent,$edu_amt,$hedu_percent,$hedu_amount,$cvd_percent,$cvd_amt)";
        
        $Query = "INSERT INTO outgoinginvoice_excise_detail (oinvoice_exciseID, buyer_item_code, oinv_codePartNo, codePartNo_desc, ordered_qty, iinv_no, issued_qty, oinv_price, discount_percent, taxable_amt, hsn_code, entryId, cgst_rate, sgst_rate,igst_rate, cgst_amt, sgst_amt, igst_amt, total) VALUES ($oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$iinv_no,$issued_qty,$oinv_price,$item_discount,$item_taxable_total,'$hsn_code',$entryId,$cgst_rate,$sgst_rate,$igst_rate,$cgst_amt,$sgst_amt,$igst_amt,$tot_price)";

    
        $Result = DBConnection::InsertQuery($Query);
        /* if($Result == QueryResponse::SUCCESS){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        } */
		if($Result > 0){
            return $Result;
        }
		else if($Result == QueryResponse::ERROR)
		{
			return $Result;
		}
        else{
            return QueryResponse::NO;
        }
    }

	public static function GetOutgoingInvoiceExciseInfo($oinvoice_exciseID)
	{
		$Query = "SELECT po.bpoType,oivd.codePartNo_desc,oivd.issued_qty,oivd.iinv_no,oivd.buyer_item_code,oivd.oinvoice_exciseDID FROM outgoinginvoice_excise_detail AS oivd,outgoinginvoice_excise AS oiv,purchaseorder AS po WHERE oivd.oinvoice_exciseID=oiv.oinvoice_exciseID AND oiv.pono=po.bpoId AND oivd.oinvoice_exciseID=$oinvoice_exciseID";
		
		$RESULT = DBCONNECTION::SELECTQUERY($Query);
		$OBJARRAY = ARRAY();
		$I = 0;
		WHILE ($ROW = MYSQL_FETCH_ARRAY($RESULT, MYSQL_ASSOC)) {
		 $OBJARRAY[$I]['codePartNo']=$ROW['codePartNo_desc'];
		 $OBJARRAY[$I]['issued_qty']=$ROW['issued_qty'];
		 $OBJARRAY[$I]['iinv_no']=$ROW['iinv_no'];
		 $OBJARRAY[$I]['bpoDId']=$ROW['buyer_item_code'];
		 $OBJARRAY[$I]['oinvoice_exciseDID']=$ROW['oinvoice_exciseDID'];
		  $OBJARRAY[$I]['bpoType']=$ROW['bpoType'];
		 $I++;
		}
		RETURN $OBJARRAY;

    }


    public static function noOfRowsBeforeUpdate($id){
	    $Query = "select count(*) as num from outgoinginvoice_excise_detail where oinvoice_exciseID=$id";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row['num'];

    }

    public function __construct($oinvoice_exciseID,$itemid,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$iinv_no,$issued_qty,$oinv_price,$total_price,$ed_percent,$ed_amt,$ed_perUnit,$entryId,$edu_percent,$edu_amt,$hedu_percent,$hedu_amount,$cvd_percent , $cvd_amt,$bpod_Id,$mappingid,$entryDId,$stock_qty,$balance_qty,$principal_inv_date=null,$UnitId=null,$Item_Identification_Mark,$hsn_code,$item_discount,$item_taxable_total,$cgst_rate,$cgst_amt,$sgst_rate,$sgst_amt,$igst_rate,$igst_amt){
        $this->oinvoice_exciseID = $oinvoice_exciseID;
        $this->_item_id = $itemid;
        $this->buyer_item_code = $buyer_item_code;
        $this->oinv_codePartNo = $oinv_codePartNo;
        $this->codePartNo_desc = $codePartNo_desc;
        $this->ordered_qty = $ordered_qty;
        $this->item_taxable_total = $item_taxable_total;
        $this->item_discount = $item_discount;
        $this->iinv_no = $iinv_no;
        $this->issued_qty = $issued_qty;
        $this->oinv_price = $oinv_price;
        $this->tot_price = $total_price;
        $this->ed_percent = $ed_percent;
        $this->ed_amt = $ed_amt;
        $this->ed_perUnit = $ed_perUnit;
        $this->entryId = $entryId;
        $this->edu_percent = $edu_percent;
        $this->edu_amt = $edu_amt;
        $this->hedu_percent = $hedu_percent;
        $this->hedu_amount = $hedu_amount;
        $this->cvd_percent = $cvd_percent;
        $this->cvd_amt = $cvd_amt;
        $this->bpod_Id = $bpod_Id;
        $this->mappingid = $mappingid;
        $this->entryDId = $entryDId;
        $this->stock_qty=$stock_qty;
        $this->balance_qty=$balance_qty;
		$this->principal_inv_date = $principal_inv_date;
		$this->UnitId = $UnitId;
		$this->Item_Identification_Mark = $Item_Identification_Mark;
		$this->hsn_code = $hsn_code;
		$this->cgst_rate = $cgst_rate;
		$this->cgst_amt = $cgst_amt;
		$this->sgst_rate = $sgst_rate;
		$this->sgst_amt = $sgst_amt;
		$this->igst_rate = $igst_rate;
		$this->igst_amt = $igst_amt;
	}

    public static function  GetRateFromPO_Details($PODID,$bpoType,$item_codePartNo)
	{
		if($bpoType=="R")
		{
			 $Query ="SELECT pd.po_price FROM purchaseorder_schedule_detail AS pds,purchaseorder_detail AS pd  WHERE pds.bpoId=pd.bpoId AND pds.sch_codePartNo=pd.po_codePartNo AND bposd_Id='$PODID' AND pd.po_codePartNo='$item_codePartNo'";
		}
		else
		{
			 $Query = "SELECT po_price FROM purchaseorder_detail WHERE bpod_Id = $PODID";
		}
       
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row['po_price'];
    }
    public static function  GetEntryID($IncomingInvID)
	{
        $Query = "SELECT display_EntryNo FROM incominginvoice_entryno_mapping WHERE inner_EntryNo = $IncomingInvID";
	$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row['display_EntryNo'];
    }
    public static function  LoadOutgoingInvoiceExciseDetails($oinvoice_exciseID,$bpoType)
	{	
        $result = self::GetOutgoingInvoiceExciseDetailsList($oinvoice_exciseID,$bpoType);
		$objArray = array();
		$i = 0;
	
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
            $oinvoice_exciseID = $Row['oinvoice_exciseID'];
            $_item_id = $Row['codePartNo_desc'];
			$principal_inv_date = $Row['principal_inv_date'];
			$UnitId = $Row['UnitId'];
			$Item_Identification_Mark = $Row['Item_Identification_Mark'];
			if($Item_Identification_Mark == 'OKS'){
				$Item_Identification_Mark = 'Klueber';
			}
			//~ $Tarrif_Heading = $Row['Tarrif_Heading'];
			$Tarrif_Heading = $Row['hsn_code'];
			$cgst_rate = $Row['cgst_rate'];
			$cgst_amt = $Row['cgst_amt'];
			$sgst_rate = $Row['sgst_rate'];
			$sgst_amt = $Row['sgst_amt'];
			$igst_rate = $Row['igst_rate'];
			$igst_amt = $Row['igst_amt'];
            //~ $buyer_item_code = $Row['buyer_item_code'];
            $buyer_item_code = $Row['po_buyeritemcode'];
            $oinv_codePartNo = $Row['Item_Code_Partno'];
            $codePartNo_desc = $Row['Item_Desc'];
            $ordered_qty = $Row['ordered_qty'];
            $item_discount = $Row['discount_percent'];
            $item_taxable_total = $Row['taxable_amt'];
            $iinv_no = $Row['principal_inv_no'];
            $issued_qty = $Row['issued_qty'];
            //comment by gajendra
            //$oinv_price = self::GetRateFromPO_Details($Row['oinv_price'],$bpoType,$_item_id);
			$oinv_price = $Row['oinv_price'];
            //~ $tot_price = $issued_qty * $oinv_price;
            $tot_price = $Row['total'];
            $ed_percent = $Row['ed_percent'];
            $ed_amt = $Row['ed_amt'];
            $ed_perUnit = $Row['ed_perUnit'];
            $entryId = $Row['entryId'] ; //
            $mappingid = self::GetEntryID($Row['entryId']);
            $edu_percent = $Row['edu_percent'];
            $edu_amt = $Row['edu_amt'];
            $hedu_percent = $Row['hedu_percent'];
            $hedu_amount = $Row['hedu_amount'];
            $cvd_percent = $Row['cvd_percent'];
            $cvd_amt = $Row['cvd_amt'];
            //~ $bpod_Id = $Row['oinv_price'];
            $bpod_Id = $Row['oinv_codePartNo'];
            $entryDId =  $Row['iinv_no'];
           $stock_qty=self::GetItemInvoiceStock($entryDId);
           $tot_issue_qty=self::GetPoIssueQty($bpod_Id,$_item_id,$bpoType);
           $balance_qty=$ordered_qty-$tot_issue_qty;
            
            $newObj = new Outgoing_Invoice_Excise_Model_Details($oinvoice_exciseID,$_item_id,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$iinv_no,$issued_qty,$oinv_price,$tot_price,$ed_percent,$ed_amt,$ed_perUnit,$entryId,$edu_percent,$edu_amt,$hedu_percent,$hedu_amount,$cvd_percent,$cvd_amt,$bpod_Id,$mappingid,$entryDId,$stock_qty,$balance_qty,$principal_inv_date,$UnitId ,$Item_Identification_Mark,$Tarrif_Heading,$item_discount,$item_taxable_total,$cgst_rate, $cgst_amt,$sgst_rate,$sgst_amt,$igst_rate, $igst_amt );
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
   //public static function GetPoIssueQty($bpod_Id,$itemId,$bpoType,$potype)
   public static function GetPoIssueQty($bpod_Id,$itemId,$bpoType)
   {
   	   $tot_issue_qty=0;
   	   /* if($potype=="N")
       {
	      $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_nexcise_detail AS ivd ,outgoinginvoice_nonexcise AS iv,purchaseorder AS po WHERE ivd.oinvoice_nexciseID=iv.oinvoice_nexciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_nexcisedID DESC";
	     
       }
       else if($potype=="E")
       {
       	 $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";	
	    		
       } */
	   $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";
	   
       $Result = DBConnection::SelectQuery($Query);
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_issue_qty=$Row['issued_qty'];
       }
      return  $tot_issue_qty;  
       
   }
	public static function GetItemInvoiceStock($iinv_no)
	{
	  $tot_ExciseQty=0;	
	  $Query = "SELECT qty FROM incominginventory   WHERE entryDId='$iinv_no'";
	  $Result = DBConnection::SelectQuery($Query); 
	  while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_ExciseQty=$Row['qty'];
       }
      return $tot_ExciseQty;     	
	}

    public static function DeleteItem($oinvoice_exciseDID){
        $Query = "DELETE FROM outgoinginvoice_excise_detail WHERE oinvoice_exciseDID = $oinvoice_exciseDID";       
        $Result = DBConnection::UpdateQuery($Query);      
        if($Result =="SUCCESS"){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }

    public static function GetOutgoingInvoiceExciseDetailsList($oinvoice_exciseID,$bpoType){
		//~ $Query = "SELECT oied.*,pd.po_buyeritemcode,im.Item_Code_Partno,pd.po_saleTax,pd.po_discount,im.Item_Desc,ie.principal_inv_no,ie.principal_inv_date,im.UnitId,im.Item_Identification_Mark,im.Tarrif_Heading FROM outgoinginvoice_excise_detail as oied left join purchaseorder_detail as pd ON pd.bpod_Id = oied.buyer_item_code left join item_master as im ON oied.codePartNo_desc = im.ItemId left join incominginvoice_excise as ie ON ie.entryId = oied.entryId WHERE oied.oinvoice_exciseID = $oinvoice_exciseID";
    	 if($bpoType=='R'){
			 $Query="SELECT oied.*,pd.po_buyeritemcode,pd.po_codePartNo,pd.po_saleTax,pd.po_discount ,im.Item_Code_Partno, im.Item_Desc,ie.principal_inv_no,ie.principal_inv_date,im.UnitId,im.Item_Identification_Mark,im.Tarrif_Heading FROM outgoinginvoice_excise_detail AS oied left join incominginvoice_excise as ie ON ie.entryId = oied.entryId,purchaseorder_schedule_detail AS psd, item_master AS im,purchaseorder_detail AS pd WHERE  pd.po_codePartNo=psd.sch_codePartNo AND pd.bpoId=psd.bpoId AND  psd.bposd_Id = oied.buyer_item_code AND  oied.codePartNo_desc = im.ItemId AND oied.oinvoice_exciseID=$oinvoice_exciseID";
			//~ $Query="SELECT oied.*,pd.po_buyeritemcode,pd.po_codePartNo,pd.po_saleTax,pd.po_discount ,im.Item_Code_Partno, im.Item_Desc,ie.principal_inv_no,ie.principal_inv_date,im.UnitId,im.Item_Identification_Mark,im.Tarrif_Heading FROM outgoinginvoice_excise_detail AS oied,purchaseorder_schedule_detail AS psd, item_master AS im,incominginvoice_excise AS ie,purchaseorder_detail AS pd WHERE ie.entryId = oied.entryId AND pd.po_codePartNo=psd.sch_codePartNo AND pd.bpoId=psd.bpoId AND  psd.bposd_Id = oied.buyer_item_code AND  oied.codePartNo_desc = im.ItemId AND oied.oinvoice_exciseID=$oinvoice_exciseID";
		}else{
			$Query = "SELECT oied.*,pd.po_buyeritemcode,im.Item_Code_Partno,pd.po_saleTax,pd.po_discount,im.Item_Desc,ie.principal_inv_no,ie.principal_inv_date,im.UnitId,im.Item_Identification_Mark,im.Tarrif_Heading FROM outgoinginvoice_excise_detail as oied left join purchaseorder_detail as pd ON pd.bpod_Id = oied.buyer_item_code left join item_master as im ON oied.codePartNo_desc = im.ItemId left join incominginvoice_excise as ie ON ie.entryId = oied.entryId WHERE oied.oinvoice_exciseID = $oinvoice_exciseID";
		} 	
		//echo $Query;exit;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

}

class Outgoing_Invoice_Excise_Model_Payment_Details
{
    public $oinvoice_exciseID;
    public $received_amount_value;
    public $received_amount_date;
    public $received_amount_status;

    public static function InsertOutgoingInvoiceExcisePaymentDetails($oinvoice_exciseID,$received_amount_value,$received_amount_date,$received_amount_status){
        
        //added on 02-JUNE-2016 due to Handle Special Character        
        $received_amount_date = mysql_escape_string($received_amount_date);
        $received_amount_status = mysql_escape_string($received_amount_status);
        
        $Query = "INSERT INTO outgoinginvoice_payment (oinvoice_exciseID, received_amount_value, received_amount_date, received_amount_status) VALUES ($oinvoice_exciseID,$received_amount_value,'$received_amount_date', '$received_amount_status')";
        echo($Query);
        $Result = DBConnection::InsertQuery($Query);
        if($Result == QueryResponse::SUCCESS){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }

    public function __construct($oinvoice_exciseID,$received_amount_value,$received_amount_date,$received_amount_status){
        $this->oinvoice_exciseID = $oinvoice_exciseID;
        $this->received_amount_value = $received_amount_value;
        $this->received_amount_date = $received_amount_date;
        $this->received_amount_status = $received_amount_status;
	}

    public static function  LoadOutgoingInvoiceExcisePaymentDetails($oinvoice_exciseID)
	{
        $result = self::GetOutgoingInvoiceExcisePaymentDetailsList($oinvoice_exciseID);
		$objArray = array();
		$i = 0;
		while($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_exciseID = $Row['oinvoice_exciseID'];
            $received_amount_value = $Row['received_amount_value'];
            $received_amount_date = $Row['received_amount_date'];
            $received_amount_status = $Row['received_amount_status'];
            $newObj = new Outgoing_Invoice_Excise_Model_Payment_Details($oinvoice_exciseID,$received_amount_value,$received_amount_date,$received_amount_status);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

    public static function DeleteItem($oinvoice_exciseID){
        $Query = "DELETE FROM outgoinginvoice_payment WHERE oinvoice_exciseID = $oinvoice_exciseID";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }

    public static function GetOutgoingInvoiceExcisePaymentDetailsList($oinvoice_exciseID){
		$Query = "SELECT * FROM outgoinginvoice_payment WHERE oinvoice_exciseID = $oinvoice_exciseID";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

}

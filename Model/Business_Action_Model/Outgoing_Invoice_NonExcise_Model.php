<?php
//include("../DBModel/Enum_Model.php");
//include("../DBModel/DbModel.php");
//require_once('root.php');
//require_once($root_path."Config.php");
class Outgoing_Invoice_NonExcise_Model
{

    public $oinvoice_nexciseID;
    public $oinvoice_type;
    public $oinvoice_No;
    public $oinv_date;
    public $pono;
    public $po_date;
    public $BuyerID;
    public $principalID;
    public $mode_delivery;
    public $discount;
    public $pf_chrg;
    public $incidental_chrg;
    public $freight_amount;
    public $po_saleTax;
    public $bill_value;
    public $payment_status;
    public $remarks;
    public $userId;
    public $insertDate;
    public $Buyer_Name;
    public $Principal_Name;
    public $_itmes  = array();	
	public $bundles  = array();
	
    public $mod_delivery_text;
	public $ms;
	
	public static function ValidateOutgoingInvoiceNonExciseDataCalculation($data){
		$Data = json_decode($data,true);
		$PayAmount = 0.00;
		$finalTaxableAmount = 0.0;
		foreach($Data['_items'] as $item){
			$taxid = $item['saletaxID'];
			$Discount_percent = $item['discount'];
			$basic_amount = $item['issued_qty'] * $item['oinv_price'];
			$Discount_Amount = ($basic_amount * $Discount_percent) / 100;
			$CalculateAmount = $basic_amount - $Discount_Amount;
			$pf_charge_amount = $incidental_amount = 0.00;
			if(!empty($Data['pf_chrg_percent'])){
				$pf_charge_amount = ($Data['pf_chrg_percent'] * $CalculateAmount) / 100;
			}
			if(!empty($Data['incidental_chrg_percent'])){
				$incidental_amount = ($Data['incidental_chrg_percent'] * $CalculateAmount) / 100;
			}
			$TaxableAmount = $CalculateAmount + $pf_charge_amount + $incidental_amount;
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
			$salesTaxAmount = $TaxAmount + $SurchargeAmount;
			$PayAmount = $PayAmount + $TaxableAmount + $salesTaxAmount;
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
		$PayAmount = $PayAmount + $F_amt;
		$bill_value = $PayAmount;
		echo $bill_value;
		echo "</br>";
		echo $Data['bill_value'];exit;
		$diff = $bill_value - $Data['bill_value'];
		if(abs($diff) > 5)
		{
			return 0;
		}
		return 1;
	}
		
		//Function to gennerate and neturn new invoice number.
       public static function createNewNonExciseInvoiceNumber($nexciseStr){
		 $Query = "";
			if(GURGAON){
				 $Query = "SELECT CONCAT('T-',((SUBSTR(oinvoice_No,3,8))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(HARIDWAR){
				 $Query = "SELECT CONCAT('HW/',((SUBSTR(oinvoice_No,4,9))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(RUDRAPUR){
				 $Query = "SELECT CONCAT('RUD/',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(MANESAR){
				 $Query = "SELECT CONCAT(' M/T-',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else{
				 $Query = "";
			}
	       // $Query = "SELECT CONCAT('T-',((SUBSTR(oinvoice_No,3,8))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
	     
	        $Result = DBConnection::SelectQuery($Query);
	        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	        return $Row["new_outgoingInvoice"];
	    }
		
		// function to return the total invoice number 	
	   public static function checkNonExciseInvoiceNumber($nexciseStr){
	     	        $Query = "SELECT COUNT(*)c FROM outgoinginvoice_nonexcise AS oine where oine.oinvoice_No LIKE ('$nexciseStr')";
		            $Result = DBConnection::SelectQuery($Query);
		 		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
		 	        $total=$row['c'];
			        return $total;
	   }
	   
	   // Function to get the last incoice number to generate the new next invoice number.
	   public static function GetLastInvoiceNumber(){
		        $FinancialYear = MultiweldParameter::GetFinancialYear();
				if(GURGAON){
					$nexciseStr="T-".$FinancialYear."%";
				}else if(HARIDWAR){
					$nexciseStr="HW/".$FinancialYear."%";
				}else if(RUDRAPUR){
				   $nexciseStr="RUD/".$FinancialYear."%";
				}else if(MANESAR){
				   $nexciseStr="M/T-".$FinancialYear."%";
				}
				else{
					break; exit;
				}		     
	            $totNonExciseInvoiceNumber=self::checkNonExciseInvoiceNumber($nexciseStr);

		        if($totNonExciseInvoiceNumber>0){
	               $getNewNonExciseInvoiceNo=self::createNewNonExciseInvoiceNumber($nexciseStr);
		           return $getNewNonExciseInvoiceNo;
		        }else{
		         $firstNonExciseInvoiceNumber =$totNonExciseInvoiceNumber+1;
		         $s = sprintf("%06d", $firstNonExciseInvoiceNumber);
					if(GURGAON){
						 return "T-".$FinancialYear.$s;
					}else if(HARIDWAR){
						 return "HW/".$FinancialYear.$s;
					}else if(RUDRAPUR){
						return "RUD/".$FinancialYear.$s;
					}else if(MANESAR){
						return "M/T-".$FinancialYear.$s;
					}else{
						break; exit;
					}		
		        
		        }
   }


    public static function GET_OINV_NUM_BY_DISPLAY($DISPLAYID,$YEAR){
        $Query = "select inner_outgoingInvoiceNonEx from outgoinginvoice_nonexcise_mapping where display_outgoingInvoiceNonEx = $DISPLAYID AND finyear = '".$YEAR."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["inner_outgoingInvoiceNonEx"] > 0)
        {
            return $Row["inner_outgoingInvoiceNonEx"];
        }
        else
            return 0;
    }
    public static function Create_OIVNDISPLAYID(){
        $Query = "select (IFNULL(MAX(display_outgoingInvoiceNonEx),0)+1)display_outgoingInvoiceNonEx  FROM outgoinginvoice_nonexcise_mapping WHERE finyear='".MultiweldParameter::GetFinancialYear_fromTXT()."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["display_outgoingInvoiceNonEx"] > 0)
        {
            return $Row["display_outgoingInvoiceNonEx"];
        }
    }
    public static function INSERT_OIV_DISPLAY_ENTRY_MAPING($ONEID,$oinvoice_No){
        $currentyear = MultiweldParameter::GetFinancialYear_fromTXT();
        $Query = "insert into outgoinginvoice_nonexcise_mapping (inner_outgoingInvoiceNonEx,display_outgoingInvoiceNonEx, finyear,outgoingInvoiceNonExNo) values (".$ONEID.",".self::Create_OIVNDISPLAYID().",'".$currentyear."','".$oinvoice_No."')";
        $Result = DBConnection::InsertQuery($Query);
    }
    
    public static function DELETE_OIV_DISPLAY_ENTRY_MAPING($ONEID){
        $Query = "delete from outgoinginvoice_nonexcise_mapping where inner_outgoingInvoiceNonEx = $ONEID";
        $Result = DBConnection::InsertQuery($Query);
    }
    
    //  function call's when new outgoing incoive non-excise is created to save(insert) data in database table.
    public static function InsertOutgoingInvoiceNonExcise($oinvoice_type,$oinvoice_No,$oinv_date,$pono, $BuyerID,  $principalID, $mode_delivery, $discount,$pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$ms){
	
		$oinvoice_No = self::GetLastInvoiceNumber();
        $CheckQuery = "select count(*) as inv from outgoinginvoice_nonexcise where oinvoice_No = '$oinvoice_No'";
		
        $FindInv = DBConnection::SelectQuery($CheckQuery);
        $FindRow = mysql_fetch_array($FindInv, MYSQL_ASSOC);
        if($FindRow["inv"] > 0)
        {
            return 0;
        }
        else {
			
			// added on 02-JUNE-2016 due to Handle Special Character
			$pono = mysql_escape_string($pono);
			$remarks = mysql_escape_string($remarks);
			
            $date = date("Y-m-d");
            $Query = "insert into outgoinginvoice_nonexcise (oinvoice_type, oinvoice_No, oinv_date, pono, po_date, BuyerID, principalID, mode_delivery, discount, pf_chrg, incidental_chrg, freight_amount, po_saleTax, bill_value, balanceAmount, payment_status,msid, remarks, userId, insertDate) VALUES ('$oinvoice_type','$oinvoice_No','$date',$pono,$pono,  $BuyerID,  $principalID,  '$mode_delivery', '$discount', $pf_chrg,$incidental_chrg,$freight_amount, $po_saleTax,$bill_value,$bill_value,'$payment_status','$ms', '$remarks', '$userId', '$date' )";
             mysql_query('SET foreign_key_checks = 0');
            $Result = DBConnection::InsertQuery($Query);
            if($Result > 0){
                return $Result;
            } 
            else{
                return QueryResponse::NO;
            }
        }

    }


	// Function call when OutgoingInvoiceNonExcise is updating
   public static function UpdateOutgoingInvoiceNonExcise($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono, $BuyerID,  $principalID, $mode_delivery, $discount,$pf_chrg,$incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$ms){
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
       
	    $oinv_date1= MultiweldParameter::xFormatDate($oinv_date);
        $po_date1= MultiweldParameter::xFormatDate($po_date);
        
        // added on 02-JUNE-2016 due to Handle Special Character
		$remarks = mysql_escape_string($remarks);
        
		$Query = "UPDATE outgoinginvoice_nonexcise SET ";
		$Query = $Query."oinvoice_type = '$oinvoice_type' , oinvoice_No = '$oinvoice_No', oinv_date = '$oinv_date1', pono = $pono,";
		$Query = $Query."po_date = $pono, BuyerID = $BuyerID, principalID = $principalID, mode_delivery = '$mode_delivery', ";
		$Query = $Query."discount = $discount, pf_chrg = $pf_chrg, incidental_chrg = $incidental_chrg, freight_amount = $freight_amount, ";
		$Query = $Query."po_saleTax = $po_saleTax, bill_value = $bill_value, balanceAmount = $bill_value, payment_status = '$payment_status',msid='$ms', ";
		$Query = $Query."remarks = '$remarks', userId = '$userId' ";
		$Query = $Query."WHERE oinvoice_nexciseID=$oinvoice_nexciseID";		
	    $Result = DBConnection::UpdateQuery($Query);		
	    if($Result =="SUCCESS"){
			return $Result;
		}
		else{
			return -1;
		}
   }

    public function __construct($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,$_itmes,$mod_delivery_text,$ms,$bundles=null){
        $this->oinvoice_nexciseID = $oinvoice_nexciseID;
        $this->oinvoice_type = $oinvoice_type;
        $this->oinvoice_No = $oinvoice_No;
        $this->oinv_date = $oinv_date;
        $this->pono = $pono;
        $this->po_date = $po_date;
        $this->BuyerID = $BuyerID;
        $this->principalID = $principalID;
        $this->mode_delivery = $mode_delivery;
        $this->discount = $discount;
        $this->pf_chrg = $pf_chrg;
        $this->incidental_chrg = $incidental_chrg;
        $this->freight_amount = $freight_amount;
        $this->po_saleTax = $po_saleTax;
        $this->bill_value = $bill_value;
        $this->payment_status = $payment_status;
        $this->remarks = $remarks;
        $this->userId = $userId;
        $this->insertDate = $insertDate;
        $this->Buyer_Name = $Buyer_Name;
        $this->Principal_Name = $Principal_Name;
        $this->_itmes = $_itmes;
		$this->bundles = $bundles;
        $this->mod_delivery_text = $mod_delivery_text;
		$this->ms = $ms;
	}
        public static function GetDeliveryModeText($type)
        {
            $Query = "SELECT  PARAM_VALUE1  FROM  param  WHERE  PARAM_TYPE = 'DELIVERY' AND PARAM_CODE = 'MODE' AND PARAM1 = '$type';";
            
            $Result = DBConnection::SelectQuery($Query);
            $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
            return $Row["PARAM_VALUE1"];
        }
        
      // Function call when outgoingInvoice NonExcise is edited OR updaTED  
    public static function  LoadOutgoingInvoiceNonExcise($oinvoice_nexciseID,$start=null,$rp=null,$year)
	{
        $result;
        if($oinvoice_nexciseID > 0)
        {
            $result = self::GetOutgoingInvoiceNonExciseDetails($oinvoice_nexciseID);
        }
        else
        {
            $result = self::GetOutgoingInvoiceNonExciseList($start,$rp,$year);
        }

		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexciseID = $Row['oinvoice_nexciseID'];
            $oinvoice_type = $Row['oinvoice_type'];
            $oinvoice_No = $Row['oinvoice_No'];
            $oinv_date = MultiweldParameter::xFormatDate1($Row['oinv_date']);
            $pono = $Row['pono'];
            $po_date = $Row['po_date'];
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
            $mode_delivery = $Row['mode_delivery'];
            $mod_delivery_text = self::GetDeliveryModeText($mode_delivery);
            $discount = $Row['discount'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $freight_amount = $Row['freight_amount'];
            $po_saleTax = $Row['po_saleTax'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = $Row['insertDate'];
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['principalname'];
            $bpoType = $Row['bpoType'];
			$ms = $Row['msid'];
			if($ms == 0){
			$ms = '';
			}
            if($oinvoice_nexciseID > 0)
            {
                $_itmes = Outgoing_Invoice_NonExcise_Model_Details::LoadOutgoingInvoiceNonExciseDetails($Row['oinvoice_nexciseID'],$bpoType);
            }
            else
            {
                $_itmes = null;
            }
            $newObj = new Outgoing_Invoice_NonExcise_Model($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,$_itmes,$mod_delivery_text,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	// function call for bundle Invoice select case
	public static function  LoadOutgoingBundleInvoiceNonExcise($oinvoice_nexciseID,$start=null,$rp=null,$year)
	{
        $result;
        if($oinvoice_nexciseID > 0)
        {
            $result = self::GetOutgoingInvoiceNonExciseDetails($oinvoice_nexciseID);
        }
        else
        {
            $result = self::GetOutgoingInvoiceNonExciseList($start,$rp,$year);
        }

		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexciseID = $Row['oinvoice_nexciseID'];
            $oinvoice_type = $Row['oinvoice_type'];
            $oinvoice_No = $Row['oinvoice_No'];
            $oinv_date = MultiweldParameter::xFormatDate1($Row['oinv_date']);
            $pono = $Row['pono'];
            $po_date = $Row['po_date'];
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
			
            $mode_delivery = $Row['mode_delivery'];
            $mod_delivery_text = self::GetDeliveryModeText($mode_delivery);
            $discount = $Row['discount'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $freight_amount = $Row['freight_amount'];
            $po_saleTax = $Row['po_saleTax'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = $Row['insertDate'];
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['principalname'];
            $bpoType = $Row['bpoType'];
			$ms = $Row['msid'];
		
			$_items = array();
			if($ms == 0){
			$ms = '';
			}
            if($oinvoice_nexciseID > 0)
            {
                $bundles = BundleInvoice_Details_M::LoadBundleByOutGoingInvoice($Row['oinvoice_nexciseID'],$bpoType,$Row['principalID']);
				//print_r($bundles); exit;
            }
            else
            {
                $bundles = null;
            }
            $newObj = new Outgoing_Invoice_NonExcise_Model($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,$principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,$_items,$mod_delivery_text,$ms,$bundles);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	// function call when OutgoingInvoiceNonExcise has been searched.
    public static function SearchOutgoingInvoiceNonExcise($year,$oinv,$Todate,$Fromdate,$Principalid,$Buyerid,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";

        $Query = "SELECT oine.*,owem.display_outgoingInvoiceNonEx,bm.BuyerName,pm.Principal_Supplier_Name as principalname,po.bpono,po.bpoDate FROM outgoinginvoice_nonexcise AS oine ";
        $Query =$Query. " LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id";
        $Query =$Query. " INNER JOIN outgoinginvoice_nonexcise_mapping AS owem "
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono ";
        $Query =$Query. " WHERE  owem.finyear='".$year."' AND po.bpoType !='B' ";

        if($oinv!=""){
           $Query =$Query. " and owem.outgoingInvoiceNonExNo  = '$oinv' ";
        }
        if($Fromdate!=""){
           $Query =$Query. " and oine.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $Query =$Query. " and oine.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $Query =$Query. " and oine.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $Query =$Query. " and oine.BuyerID = $Buyerid";
        }
        $Query =$Query." LIMIT $start , $rp";

        $CountQuery = "SELECT count(*) as total  FROM outgoinginvoice_nonexcise AS oine ";
        $CountQuery =$CountQuery. " LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id";
        $CountQuery =$CountQuery. " INNER JOIN outgoinginvoice_nonexcise_mapping AS owem "
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono  ";
        $CountQuery =$CountQuery. " WHERE  owem.finyear='".$year."' AND po.bpoType !='B'";

        if($oinv!=""){
           $CountQuery =$CountQuery. " and oine.oinvoice_nexciseID  = '$oinv' ";
        }
        if($Fromdate!=""){
           $CountQuery =$CountQuery. " and oine.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $CountQuery =$CountQuery. " and oine.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $CountQuery =$CountQuery. " and oine.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $CountQuery =$CountQuery. " and oine.BuyerID = $Buyerid";
        }

        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];

        $result = DBConnection::SelectQuery($Query);
        $objArray = array();
		$i = 0;

	while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexciseID = $Row['display_outgoingInvoiceNonEx'];
            $oinvoice_type = $Row['oinvoice_type'];
            $oinvoice_No = $Row['oinvoice_No'];
            $oinv_date = date("d-m-Y", strtotime($Row['oinv_date']));
            $pono = $Row['bpono'];
            $po_date = date("d-m-Y", strtotime($Row['bpoDate']));
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
            $mode_delivery = $Row['mode_delivery'];
            $mod_delivery_text = self::GetDeliveryModeText($mode_delivery);
            $discount = $Row['discount'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $freight_amount = $Row['freight_amount'];
            $po_saleTax = $Row['po_saleTax'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = date("d-m-Y", strtotime($Row['insertDate']));
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['principalname'];
			$ms = $Row['msid'];
            $newObj = new Outgoing_Invoice_NonExcise_Model($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,null,$mod_delivery_text,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	// call function to search bundle invoice -15-12-2015
	 public static function SearchOutgoingBundleInvoiceNonExcise($year,$oinv,$Todate,$Fromdate,$Principalid,$Buyerid,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";

        $Query = "SELECT oine.*,owem.display_outgoingInvoiceNonEx,bm.BuyerName,pm.Principal_Supplier_Name as principalname,po.bpono,po.bpoDate FROM outgoinginvoice_nonexcise AS oine ";
        $Query =$Query. " LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id";
        $Query =$Query. " INNER JOIN outgoinginvoice_nonexcise_mapping AS owem "
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono";
        $Query =$Query. " WHERE po.bpoType ='B' AND owem.finyear='".$year."'";

        if($oinv!=""){
           $Query =$Query. " and owem.outgoingInvoiceNonExNo  = '$oinv' ";
        }
        if($Fromdate!=""){
           $Query =$Query. " and oine.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $Query =$Query. " and oine.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $Query =$Query. " and oine.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $Query =$Query. " and oine.BuyerID = $Buyerid";
        }
        $Query =$Query." LIMIT $start , $rp";



        $CountQuery = "SELECT count(*) as total  FROM outgoinginvoice_nonexcise AS oine ";
        $CountQuery =$CountQuery. " LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id";
        $CountQuery =$CountQuery. " INNER JOIN outgoinginvoice_nonexcise_mapping AS owem "
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID  ";
        $CountQuery =$CountQuery. " WHERE  owem.finyear='".$year."'";

        if($oinv!=""){
           $CountQuery =$CountQuery. " and oine.oinvoice_nexciseID  = '$oinv' ";
        }
        if($Fromdate!=""){
           $CountQuery =$CountQuery. " and oine.insertDate >= '$Fromdate'";
        }
        if($Todate!=""){
           $CountQuery =$CountQuery. " and oine.insertDate <= '$Todate'";
        }
        if($Principalid!=""){
           $CountQuery =$CountQuery. " and oine.principalID = $Principalid";
        }
        if($Buyerid!=""){
           $CountQuery =$CountQuery. " and oine.BuyerID = $Buyerid";
        }

        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];

        $result = DBConnection::SelectQuery($Query);
        $objArray = array();
		$i = 0;

	while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexciseID = $Row['display_outgoingInvoiceNonEx'];
            $oinvoice_type = $Row['oinvoice_type'];
            $oinvoice_No = $Row['oinvoice_No'];
            $oinv_date = date("d-m-Y", strtotime($Row['oinv_date']));
            $pono = $Row['bpono'];
            $po_date = date("d-m-Y", strtotime($Row['bpoDate']));
            $BuyerID = $Row['BuyerID'];
            $principalID = $Row['principalID'];
            $mode_delivery = $Row['mode_delivery'];
            $mod_delivery_text = self::GetDeliveryModeText($mode_delivery);
            $discount = $Row['discount'];
            $pf_chrg = $Row['pf_chrg'];
            $incidental_chrg = $Row['incidental_chrg'];
            $freight_amount = $Row['freight_amount'];
            $po_saleTax = $Row['po_saleTax'];
            $bill_value = $Row['bill_value'];
            $payment_status = $Row['payment_status'];
            $remarks = $Row['remarks'];
            $userId = $Row['userId'];
            $insertDate = date("d-m-Y", strtotime($Row['insertDate']));
            $Buyer_Name = $Row['BuyerName'];
            $Principal_Name = $Row['principalname'];
			$ms = $Row['msid'];
            $newObj = new Outgoing_Invoice_NonExcise_Model($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,null,$mod_delivery_text,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	
	
	
   /*
    public static function UpdateOutgoingInvoiceNonExcise($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks){
		$Query = "UPDATE outgoinginvoice_nonexcise SET oinvoice_type = '$oinvoice_type',oinvoice_No = '$oinvoice_No',oinv_date = '$oinv_date', pono = $pono,po_date = '$po_date', BuyerID = $BuyerID, principalID = $principalID, mode_delivery = $mode_delivery,  discount = $discount,pf_chrg = $pf_chrg, incidental_chrg = $incidental_chrg, freight_amount = $freight_amount, po_saleTax = $po_saleTax,  bill_value = $bill_value, payment_status = '$payment_status', remarks = '$remarks' WHERE oinvoice_nexciseID = $oinvoice_nexciseID";
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
    */
    public static function GetOutgoingInvoiceNonExciseDetails($oinvoice_nexciseID){
		$Query = "SELECT oine.*,bm.BuyerName,pm.Principal_Supplier_Name as principalname,pod.bpoType
FROM outgoinginvoice_nonexcise AS oine
LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId
LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id,purchaseorder AS pod WHERE pod.bpoId=oine.pono AND  oine.oinvoice_nexciseID = $oinvoice_nexciseID";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetOutgoingInvoiceNonExciseList($start,$rp,$year){
			$Query = "SELECT oine.*,po.freight_tag,po.freight_percent,bm.BuyerName,pm.Principal_Supplier_Name as principalname";
			$Query .= " FROM outgoinginvoice_nonexcise AS oine INNER JOIN outgoinginvoice_nonexcise_mapping AS owem ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID ,buyer_master as bm,principal_supplier_master as pm,purchaseorder as po ";
			$Query .= " WHERE owem.finyear='".$year."' AND oine.BuyerID = bm.BuyerId";
			$Query .= " and oine.principalID = pm.Principal_Supplier_Id";
		    $Query .= " and po.bpoId=oine.pono LIMIT $start , $rp";
			$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function DeleteOutgoingNonExcise($InvoiceId){
        $Query = "delete from outgoinginvoice_nonexcise where oinvoice_nexciseID = $InvoiceId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    //public static function GetOutgoingInvoiceNonExciseDetails($oinvoice_nexciseID){
    //    $Query = "SELECT oie.*,bm.BuyerName,pm.Principal_Supplier_Name as PRINCIPALNAME FROM outgoinginvoice_nonexcise as oie INNER JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId INNER JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id WHERE oie.oinvoice_nexciseID = $oinvoice_nexciseID";
    //    $Result = DBConnection::SelectQuery($Query);
    //    return $Result;
    //}
    //public static function GetOutgoingInvoiceNonExciseList(){
    //    $Query = "SELECT oie.*,bm.BuyerName,pm.Principal_Supplier_Name as PRINCIPALNAME FROM outgoinginvoice_nonexcise as oie INNER JOIN buyer_master as bm ON oie.BuyerID = bm.BuyerId INNER JOIN principal_supplier_master as pm ON oie.principalID = pm.Principal_Supplier_Id";
    //    $Result = DBConnection::SelectQuery($Query);
    //    return $Result;
    //}
}

class Outgoing_Invoice_NonExcise_Model_Details
{

    public $oinvoice_nexcisedID;
    public $itemid;
    public $oinvoice_nexciseID;
    public $buyer_item_code;
    public $oinv_codePartNo;
    public $codePartNo_desc;
    public $ordered_qty;
    public $issued_qty;
    public $oinv_price;
    public $stock_qty;
    public $item_amount;
    public $bpod_Id;
    public $po_saleTax;
    public $po_discount;
    public $balance_qty;
    public $_item_id;
    public $_unitname;
	public $po_qty;
	public $cPartNo;
	public $iden_mark;
	public $item_desc;
	public $po_unit;
	public $po_price;
    public static function InsertOutgoingInvoiceNonExciseDetails($oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$bundle_id=null){
	 if($bundle_id == null){
		$bundle_id = 0;
	 }
	 
	//added on 02-JUNE-2016 due to Handle Special Character
    $codePartNo_desc = mysql_escape_string($codePartNo_desc);
   
    $Query = "INSERT INTO outgoinginvoice_nonexcise_detail (oinvoice_nexciseID, buyer_item_code, oinv_codePartNo, codePartNo_desc, ordered_qty, issued_qty, oinv_price,bundle_invoice_id) VALUES ($oinvoice_exciseID,$oinv_price,$oinv_price,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$bundle_id)";
       	
        $Result = DBConnection::InsertQuery($Query);		
        return $Result;
    }

    public function __construct($oinvoice_nexcisedID,$oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$itemid,$stock_qty,$item_amount,$bpod_Id,$po_saleTax,$po_discount,$balance_qty,$unitname,$po_qty=null,$cPartNo=null,$iden_mark=null,$po_price=null){
        $this->oinvoice_nexcisedID = $oinvoice_nexcisedID;
        $this->oinvoice_exciseID = $oinvoice_exciseID;
        $this->buyer_item_code = $buyer_item_code;
        $this->oinv_codePartNo = $oinv_codePartNo;
        $this->codePartNo_desc = $codePartNo_desc;
        $this->ordered_qty = $ordered_qty;
        $this->issued_qty = $issued_qty;
        $this->oinv_price = $oinv_price;
        $this->itemid = $itemid;
        $this->_item_id = $itemid;
        $this->stock_qty = $stock_qty;
        $this->item_amount = $item_amount;
        $this->bpod_Id = $bpod_Id;
        $this->saletaxID = $po_saleTax;
	    $this->discount=$po_discount;
	    $this->balance_qty=$balance_qty;
	    $this->_unitname=$unitname;
		$this->po_qty = $po_qty;
		$this->cPartNo = $cPartNo;
		$this->iden_mark = $iden_mark;
		$this->item_desc = $codePartNo_desc;
		$this->po_unit = $unitname ;
		$this->po_price = $po_price;
	}
   public static function  GetRateFromPO_Details($PODID,$bpoType)
	{   if($bpoType=="R")
	    {
			  $Query = "SELECT pd.po_price FROM purchaseorder_schedule_detail AS psd,purchaseorder_detail AS pd WHERE pd.bpoId=psd.bpoId  AND pd.bpod_Id=psd.bpod_Id AND  psd.bposd_Id=$PODID";
		}
		else
		{
			  $Query = "SELECT po_price FROM purchaseorder_detail WHERE bpod_Id = $PODID";
		}
      
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row['po_price'];
    }
    public static function  GetCodepartFromPO_Details($PODID)
	{
        $Query = "SELECT po_codePartNo FROM purchaseorder_detail WHERE bpod_Id = $PODID";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row['po_codePartNo'];
    }
    public static function  LoadOutgoingInvoiceNonExciseDetails($oinvoice_nexcisedID,$bpoType)
	{
        $result = self::GetOutgoingInvoiceNonExciseDetailsList($oinvoice_nexcisedID,$bpoType);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexcisedID = $Row['oinvoice_nexcisedID'];
            $oinvoice_exciseID = $Row['oinvoice_nexciseID'];
            $buyer_item_code = $Row['po_buyeritemcode'];
            $oinv_codePartNo = $Row['Item_Code_Partno'];
            $codePartNo_desc = $Row['Item_Desc'];
            $ordered_qty = $Row['ordered_qty'];
			if($Row['issued_qty'] == null || $Row['issued_qty'] ==''){
				$issued_qty=0;
			}else{
				$issued_qty = $Row['issued_qty'];
			}
            $oinv_price = self::GetRateFromPO_Details($Row['oinv_price'],$bpoType);
            $item_amount = $issued_qty*$oinv_price;
            $itemid = $Row['codePartNo_desc'];
            $unitname = $Row['UNITNAME'];
            $bpod_Id = $Row['oinv_price'];
            $po_saleTax = $Row['po_saleTax'];
            $po_discount=$Row['po_discount'];
            $stock_qty=self::GetItemStock($itemid);
            $total_issued_qty1=self::GetPoIssueQty($bpod_Id,$itemid,$bpoType,'N');
            $balance_qty=$ordered_qty-$total_issued_qty1;
            $newObj = new Outgoing_Invoice_NonExcise_Model_Details($oinvoice_nexcisedID,$oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$itemid,$stock_qty,$item_amount,$bpod_Id,$po_saleTax,$po_discount,$balance_qty,$unitname);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	// outgoing bundle invoice details 14-12-15
	 public static function  LoadOutgoingBundleInvoiceNonExciseDetails($oinvoice_nexcisedID,$bpoType,$bpoId,$bundle_invoice_id,$principalID)
	{
      $Query = "SELECT oned.* ,pd.po_buyeritemcode,pd.po_codePartNo,pd.po_saleTax,pd.po_discount,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark,um.UNITNAME FROM outgoinginvoice_nonexcise_detail as oned left join purchaseorder_detail as pd ON pd.bpod_Id = oned.buyer_item_code inner join item_master as im ON oned.codePartNo_desc = im.ItemId ,unit_master AS um WHERE im.UnitId=um.UnitId AND oned.bundle_invoice_id = $bundle_invoice_id AND oned.oinvoice_nexciseID =$oinvoice_nexcisedID";		
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $oinvoice_nexcisedID = $Row['oinvoice_nexcisedID'];
            $oinvoice_exciseID = $Row['oinvoice_nexciseID'];
            $buyer_item_code = $Row['po_buyeritemcode'];
            $oinv_codePartNo = $Row['Item_Code_Partno'];
			$cPartNo = $Row['Item_Code_Partno'];
            $codePartNo_desc = $Row['Item_Desc'];
			$item_desc = $Row['Item_Desc'];
			
            $ordered_qty = $Row['ordered_qty'];
			$po_qty = $Row['ordered_qty'];			
            $issued_qty = $Row['issued_qty'];
            $oinv_price = self::GetRateFromPO_Details($Row['oinv_price'],$bpoType);
            $item_amount = $issued_qty*$oinv_price;
            $itemid = $Row['codePartNo_desc'];
			
            $unitname = $Row['UNITNAME'];
            $bpod_Id = $Row['oinv_price'];
			$po_price = BundleInvoice_Details_M::getBundleItemLandingCost($itemid,$principalID);
            $po_saleTax = $Row['po_saleTax'];
            $po_discount=$Row['po_discount'];
			$iden_mark= $Row['Item_Identification_Mark'];
            $stock_qty=self::GetItemStock($itemid);
            $total_issued_qty1=self::GetPoIssueQty($bpod_Id,$itemid,$bpoType,'N');
            $balance_qty=$ordered_qty-$total_issued_qty1;
            $newObj = new Outgoing_Invoice_NonExcise_Model_Details($oinvoice_nexcisedID,$oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$itemid,$stock_qty,$item_amount,$bpod_Id,$po_saleTax,$po_discount,$balance_qty,$unitname,$po_qty,$cPartNo,$iden_mark,$po_price);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	
	public static function GetPoIssueQty($bpod_Id,$itemId,$bpoType,$potype)
   {
   	   $tot_issue_qty=0;
   	   if($potype=="N")
       {
	      $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_nonexcise_detail AS ivd ,outgoinginvoice_nonexcise AS iv,purchaseorder AS po WHERE ivd.oinvoice_nexciseID=iv.oinvoice_nexciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_nexcisedID DESC";
	     
       }
       else if($potype=="E")
       {
       	 $Query="SELECT SUM(ivd.issued_qty) AS issued_qty FROM outgoinginvoice_excise_detail AS ivd ,outgoinginvoice_excise AS iv,purchaseorder AS po WHERE ivd.oinvoice_exciseID=iv.oinvoice_exciseID  AND iv.pono=po.bpoId AND ivd.oinv_codePartNo='$bpod_Id' AND ivd.codePartNo_desc='$itemId' AND po.bpoType='$bpoType' GROUP BY ivd.codePartNo_desc  ORDER BY ivd.oinvoice_excisedID DESC";	
	    		
       }
       $Result = DBConnection::SelectQuery($Query);
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_issue_qty=$Row['issued_qty'];
       }
      return  $tot_issue_qty;  
       
   }
	public static function GetItemStock($code_partNo)
	{
	  $tot_nonExciseQty=0;	
	  $Query = "SELECT * FROM inventory WHERE code_partNo='$code_partNo'";
	  $Result = DBConnection::SelectQuery($Query); 
	  while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
          
            $tot_nonExciseQty=$Row['tot_nonExciseQty'];
       }
      return $tot_nonExciseQty;     	
	}

 	public static function GetOutgoingInvoiceNonExciseInfo($oinvoice_nexciseID)
 	{
 		$Query = "SELECT po.bpoType,oinvd.codePartNo_desc,oinvd.issued_qty,oinvd.oinvoice_nexcisedID,oinvd.oinv_codePartNo FROM outgoinginvoice_nonexcise_detail AS oinvd,outgoinginvoice_nonexcise AS oinv,purchaseorder AS po WHERE oinvd.oinvoice_nexciseID=oinv.oinvoice_nexciseID  AND oinv.pono=po.bpoId  AND oinvd.oinvoice_nexciseID=$oinvoice_nexciseID";
 		
 		$RESULT = DBCONNECTION::SELECTQUERY($Query);
 		$OBJARRAY = ARRAY();
 		$I = 0;
 		WHILE ($ROW = MYSQL_FETCH_ARRAY($RESULT, MYSQL_ASSOC)) {
 		 $OBJARRAY[$I]['codePartNo']=$ROW['codePartNo_desc'];
 		 $OBJARRAY[$I]['issued_qty']=$ROW['issued_qty'];
         $OBJARRAY[$I]['oinvoice_nexcisedID']=$ROW['oinvoice_nexcisedID'];
         $OBJARRAY[$I]['oinv_codePartNo']=$ROW['oinv_codePartNo'];
         $OBJARRAY[$I]['bpoType']=$ROW['bpoType'];
 		 $I++;
 		}
 		RETURN $OBJARRAY;

    }

    public static function DeleteItem($oinvoice_nexcisedID){
        $Query = "DELETE FROM outgoinginvoice_nonexcise_detail WHERE oinvoice_nexcisedID=$oinvoice_nexcisedID";
       
        $Result = DBConnection::UpdateQuery($Query);
       	
        return $Result;
    }

    public static function GetOutgoingInvoiceNonExciseDetailsList($oinvoice_nexcisedID,$bpoType){
    	if($bpoType=="R")
    	{
			 $Query ="SELECT oned.*,pd.po_buyeritemcode,pd.po_codePartNo,pd.po_saleTax,pd.po_discount,im.Item_Code_Partno, im.Item_Desc,um.UNITNAME FROM outgoinginvoice_nonexcise_detail AS oned,purchaseorder_schedule_detail AS psd, item_master AS im,purchaseorder_detail AS pd,unit_master AS um WHERE pd.po_codePartNo=psd.sch_codePartNo AND pd.bpoId=psd.bpoId  AND pd.bpod_Id=psd.bpod_Id AND  psd.bposd_Id = oned.buyer_item_code AND  oned.codePartNo_desc = im.ItemId and im.UnitId=um.UnitId AND oned.oinvoice_nexciseID=$oinvoice_nexcisedID";
		}
		else
		{
		  $Query = "SELECT oned.* ,pd.po_buyeritemcode,pd.po_codePartNo,pd.po_saleTax,pd.po_discount,im.Item_Code_Partno, im.Item_Desc,um.UNITNAME FROM outgoinginvoice_nonexcise_detail as oned left join purchaseorder_detail as pd ON pd.bpod_Id = oned.buyer_item_code inner join item_master as im ON oned.codePartNo_desc = im.ItemId ,unit_master AS um WHERE im.UnitId=um.UnitId AND oned.oinvoice_nexciseID =$oinvoice_nexcisedID";
		}
		
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
	
	//insert bundle of po's into bundleInvoice table 14-12-2013
	public static function InsertBundleInvoiceDetails($outgoingInvId,$bpoId,$bundle_id,$ibglAcc,$ibitem_desc,$ibpo_qty,$ibpo_qty,$ibpo_unit,$ibpo_price,$ibpo_discount,$ibpo_saleTax,$ibpo_totVal,$user){
			
		if($ibpo_discount==NULL){
			$ibpo_discount=0.00;
		}
		
		//added on 02-JUNE-2016 due to Handle Special Character
		$ibitem_desc = mysql_escape_string($ibitem_desc);
		
		$Query = "INSERT INTO bundle_outgoing_invoice (oinvoice_nexciseID,bpoId,	bundle_id,glacc,bundle_desc,order_qty,issue_qty,unit_id,bpo_saleTax,bpo_discount,unitRate,netAmt,UserId) VALUES
        ($outgoingInvId,'$bpoId','$bundle_id','$ibglAcc','$ibitem_desc','$ibpo_qty','$ibpo_qty',$ibpo_unit,'$ibpo_saleTax','$ibpo_discount','$ibpo_price',
        '$ibpo_totVal','$user')";
    
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
           return $Result;
        }
        else{
            return QueryResponse::NO;
        }
	}
	

}


class BundleInvoice_Details_M
{
	public $bundle_invoice_id;
	public $oinvoice_nexciseID;
    public $bpoId;
    public $bundle_id;
    public $ibglAcc;
    public $ibitem_desc;
    public $ibpo_qty;
    public $issue_qty;
	public $ibpo_unit;
	public $unit_name;
    public $ibpo_discount;
    public $ibpo_saleTax;
    public $ibpo_price;
    public $ibpo_totVal;
	public $saletax_chrg;
	public $saletax_desc;
	public $surchrg_desc;
	public $surchrg;
    public $items = array();
	public $po_price;
	
    public function __construct($bundle_invoice_id,$oinvoice_nexciseID,$bpoId,$bundle_id,$ibglAcc,$ibitem_desc,$order_qty,$issue_qty,$ibpo_unit,$unit_name,$ibpo_discount,$ibpo_saleTax,$ibpo_price,$ibpo_totVal,$saletax_chrg,$saletax_desc,$surchrg_desc,$surchrg,$items,$po_price=null){
		$this->bundle_invoice_id = $bundle_invoice_id;
		$this->oinvoice_nexciseID = $oinvoice_nexciseID;
        $this->bpoId = $bpoId;
        $this->bundle_id = $bundle_id;
        $this->ibglAcc  = $ibglAcc;
        $this->ibitem_desc  =$ibitem_desc;
        $this->ibpo_qty  =$order_qty;
        $this->issue_qty = $issue_qty;
		$this->ibpo_unit = $ibpo_unit;
		$this->unit_name = $unit_name;
		$this->ibpo_discount = $ibpo_discount;
        $this->ibpo_saleTax = $ibpo_saleTax;
        $this->ibpo_price = $ibpo_price;
        $this->ibpo_totVal = $ibpo_totVal;    
		$this->items = $items;  
		$this->saletax_chrg = $saletax_chrg; 		
		$this->saletax_desc = $saletax_desc;
		$this->surchrg_desc = $surchrg_desc;
		$this->surchrg = $surchrg;
		$this->po_price = $po_price;

	}
    
    //*########################################################
    
    // function returns the bundle invoice 
      public static function LoadBundleByOutGoingInvoice($oinvoice_nexciseID,$bpoType,$principalID){
	 
	  $Query = "select boi.*,vm.SALESTAX_CHRG,vm.SALESTAX_DESC,vm.SURCHARGE_DESC,vm.SURCHARGE,um.UNITNAME FROM bundle_outgoing_invoice as boi,vat_cst_master as vm ,unit_master as um WHERE vm.SALESTAX_ID = boi.bpo_saleTax AND um.UnitId = boi.unit_id AND boi.oinvoice_nexciseID = $oinvoice_nexciseID";	
	  
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$bundle_invoice_id = $Row['bundle_invoice_id'];
			$oinvoice_nexciseID = $Row['oinvoice_nexciseID'];			
            $bpoId = $Row['bpoId'];
            $bundle_id= $Row['bundle_id'];			
            $glacc= $Row['glacc'];
            $bundle_desc = $Row['bundle_desc'];
            $ibpo_qty= $Row['order_qty'];
			$issue_qty= $Row['issue_qty']; 
			$ibpo_unit = $Row['unit_id']; 
			$unit_name = $Row['UNITNAME']; 				
			$bpo_discount= $Row['bpo_discount'];			
            $bpo_saleTax= $Row['bpo_saleTax'];
            $unitRate= $Row['unitRate'];        
		    $netAmt= $Row['netAmt'];
			$saletax_chrg = $Row['SALESTAX_CHRG'];
			$saletax_desc = $Row['SALESTAX_DESC'];
			$surchrg_desc = $Row['SURCHARGE_DESC'];
			$surchrg = $Row['SURCHARGE'];
            $items = Outgoing_Invoice_NonExcise_Model_Details::LoadOutgoingBundleInvoiceNonExciseDetails($Row['oinvoice_nexciseID'],"B",$bpoId,$bundle_invoice_id,$principalID); 
            //$po_totVal = Bundle_Details_Model::getRowAmount($po_qty,$po_price,$po_discount);            
            $newObj = new BundleInvoice_Details_M($bundle_invoice_id,$oinvoice_nexciseID,$bpoId,$bundle_id,$glacc,$bundle_desc,$ibpo_qty,$issue_qty,$ibpo_unit,$unit_name,$bpo_discount,$bpo_saleTax,$unitRate,$netAmt,$saletax_chrg,$saletax_desc,$surchrg_desc,$surchrg,$items);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;		
    }	
	
   //########################### end 
   
   // function returns the bundleitem landing cost 
   public static function getBundleItemLandingCost($itemId,$principalId){
		$Query1 = "SELECT IFNULL(MAX(iwed.landing_price),0) as price,iwed.incominginvoice_we as id FROM incominginvoice_without_excise_detail as iwed INNER JOIN incominginvoice_without_excise as iwe on iwe.incominginvoice_we = iwed.incominginvoice_we WHERE iwed.itemID_code_partNo=$itemId AND iwe.principalID = $principalId ";
		
		$Query2 = "SELECT IFNULL(MAX(ied.landing_price),0) as price,ied.entryDId as id FROM incominginvoice_excise_detail as ied 
			INNER JOIN incominginvoice_excise as ie on ie.entryId = ied.entryId WHERE ied.itemID_code_partNo=$itemId AND ie.principalId = $principalId ";
			
		$Query="SELECT MAX(price) as price FROM  (".$Query1."  UNION ALL ".$Query2." )as s ";	
    	$Result = DBConnection::SelectQuery($Query);
		 if(mysql_num_rows($Result) > 0){
			$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
			return $Row['price'];
		 }else{	
			$p = 0;
			return $p;
		}
	}
   
 }

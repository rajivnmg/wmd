<?php
//include("../DBModel/Enum_Model.php");
//include("../DBModel/DbModel.php");
require_once('root.php');
require_once($root_path."Config.php");
class BundleInvoice_Model
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
    public $mod_delivery_text;
	public $ms;

       public static function createNewNonExciseInvoiceNumber($nexciseStr){
		 $Query = "";
			if(GURGAON){
				 $Query = "SELECT CONCAT('T-',((SUBSTR(oinvoice_No,3,8))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(HARIDWAR){
				 $Query = "SELECT CONCAT('HW/',((SUBSTR(oinvoice_No,4,9))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(MANESAR){
				 $Query = "SELECT CONCAT('MW/',((SUBSTR(oinvoice_No,4,9))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else if(RUDRAPUR){
				 $Query = "SELECT CONCAT('RUD/',((SUBSTR(oinvoice_No,5,10))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
			}else{
				 $Query = "";
			}
	       // $Query = "SELECT CONCAT('T-',((SUBSTR(oinvoice_No,3,8))+1)) AS new_outgoingInvoice FROM outgoinginvoice_nonexcise WHERE oinvoice_No LIKE ('$nexciseStr') ORDER BY oinvoice_nexciseID DESC LIMIT 1";
	     
	        $Result = DBConnection::SelectQuery($Query);
	        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	        return $Row["new_outgoingInvoice"];
	    }

	   public static function checkNonExciseInvoiceNumber($nexciseStr){
	     	        $Query = "SELECT COUNT(*)c FROM outgoinginvoice_nonexcise AS oine where oine.oinvoice_No LIKE ('$nexciseStr')";
		            $Result = DBConnection::SelectQuery($Query);
		 		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
		 	        $total=$row['c'];
			        return $total;
	   }
	   public static function GetLastInvoiceNumber(){
		        $FinancialYear = MultiweldParameter::GetFinancialYear();
				if(GURGAON){
					$nexciseStr="T-".$FinancialYear."%";
				}else if(MANESAR){
					$nexciseStr="MW/".$FinancialYear."%";
				}else if(HARIDWAR){
					$nexciseStr="HW/".$FinancialYear."%";
				}else if(RUDRAPUR){
				   $nexciseStr="RUD/".$FinancialYear."%";
				}else{
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
					}else if(MANESAR){
						 return "MW/".$FinancialYear.$s;
					}else if(RUDRAPUR){
						return "RUD/".$FinancialYear.$s;
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
    public static function InsertOutgoingInvoiceNonExcise($oinvoice_type,$oinvoice_No,$oinv_date,$pono, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$ms){
	
		$oinvoice_No = self::GetLastInvoiceNumber();
        $CheckQuery = "select count(*) as inv from outgoinginvoice_nonexcise where oinvoice_No = '$oinvoice_No'";
		
		 // added on 02-JUNE-2016 due to Handle Special Character
		$remarks = mysql_escape_string($remarks);
        $FindInv = DBConnection::SelectQuery($CheckQuery);
        $FindRow = mysql_fetch_array($FindInv, MYSQL_ASSOC);
        if($FindRow["inv"] > 0)
        {
            return 0;
        }
        else {
            $date = date("Y-m-d");
            $Query = "insert into outgoinginvoice_nonexcise (oinvoice_type, oinvoice_No, oinv_date, pono, po_date, BuyerID, principalID, mode_delivery, discount, pf_chrg, incidental_chrg, freight_amount, po_saleTax, bill_value, balanceAmount, payment_status,msid, remarks, userId, insertDate) VALUES ('$oinvoice_type','$oinvoice_No','$date',$pono,$pono,  $BuyerID,  $principalID,  '$mode_delivery', $discount, $pf_chrg,$incidental_chrg,$freight_amount, $po_saleTax,$bill_value,$bill_value,'$payment_status','$ms', '$remarks', '$userId', '$date' )";
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
        //added on 02-JUNE-2016 due to Handle Special Character
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

    public function __construct($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,$_itmes,$mod_delivery_text,$ms){
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
        $this->mod_delivery_text = $mod_delivery_text;
		$this->ms = $ms;
	}
        public static function GetDeliveryModeText($type)
        {
            $Query = "SELECT  PARAM_VALUE1  FROM  param  WHERE  PARAM_TYPE = 'DELIVERY' AND PARAM_CODE = 'MODE' AND PARAM1 = '$type';";
            //echo $Query;
            $Result = DBConnection::SelectQuery($Query);
            $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
            return $Row["PARAM_VALUE1"];
        }
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
    public static function SearchOutgoingInvoiceNonExcise($year,$oinv,$Todate,$Fromdate,$Principalid,$Buyerid,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";

        $Query = "SELECT oine.*,owem.display_outgoingInvoiceNonEx,bm.BuyerName,pm.Principal_Supplier_Name as principalname,po.bpono,po.bpoDate FROM outgoinginvoice_nonexcise AS oine ";
        $Query =$Query. " LEFT JOIN buyer_master as bm ON oine.BuyerID = bm.BuyerId LEFT JOIN principal_supplier_master as pm ON oine.principalID = pm.Principal_Supplier_Id";
        $Query =$Query. " INNER JOIN outgoinginvoice_nonexcise_mapping AS owem "
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono ";
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
                . "ON owem.inner_outgoingInvoiceNonEx=oine.oinvoice_nexciseID INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono ";
        $CountQuery =$CountQuery. " WHERE po.bpoType ='B' AND owem.finyear='".$year."'";

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
            $newObj = new BundleInvoice_Model($oinvoice_nexciseID,$oinvoice_type,$oinvoice_No,$oinv_date,$pono,$po_date, $BuyerID,  $principalID, $mode_delivery, $discount,  $pf_chrg, $incidental_chrg,$freight_amount,$po_saleTax,$bill_value,$payment_status,$remarks,$userId,$insertDate,$Buyer_Name, $Principal_Name,null,$mod_delivery_text,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

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
   
}

class BundleInvoice_Model_Details
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
    public static function InsertOutgoingInvoiceNonExciseDetails($oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price){
	// added on 01-JUNE-2016 due to Handle Special Character
	$codePartNo_desc = mysql_escape_string($codePartNo_desc);
	
    $Query = "INSERT INTO outgoinginvoice_nonexcise_detail (oinvoice_nexciseID, buyer_item_code, oinv_codePartNo, codePartNo_desc, ordered_qty, issued_qty, oinv_price) VALUES ($oinvoice_exciseID,$oinv_price,$oinv_price,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price)";
       	
        $Result = DBConnection::InsertQuery($Query);		
        return $Result;
    }

    public function __construct($oinvoice_nexcisedID,$oinvoice_exciseID,$buyer_item_code,$oinv_codePartNo,$codePartNo_desc,$ordered_qty,$issued_qty,$oinv_price,$itemid,$stock_qty,$item_amount,$bpod_Id,$po_saleTax,$po_discount,$balance_qty,$unitname){
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
            $issued_qty = $Row['issued_qty'];
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
	
	
	 public static function  LoadPrincipalByBundlePo($poId,$po_ed_applicability)
	{
          $opt='';
    	    if($po_ed_applicability!="")
    	    {
				$opt=" AND pod.po_ed_applicability='$po_ed_applicability'";
			}
		$Query = "SELECT distinct pm.Principal_Supplier_Id, pm.Principal_Supplier_Name,pod.po_quotNo,pod.po_principalId, pod.po_buyeritemcode,pod.po_unit,pod.po_qty,pod.po_price,pod.po_discount,pod.po_ed_applicability,pod.po_saleTax,pod.po_deliverybydate,pod.po_codePartNo,pod.po_item_stage FROM purchaseorder_detail as pod INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = pod.po_principalId WHERE pod.bpoId ='$poId' $opt";
        //$Query = "SELECT bpod_Id, psm.principal_supplier_name, item_code_partno,po_ed_applicability FROM purchaseorder_detail AS pod, purchaseorder AS po,principal_supplier_master AS psm, item_master AS im WHERE po.bpoid = pod.bpoid AND bpono =  '$poId' AND psm.principal_supplier_id = pod.po_principalId AND pod.po_codePartNo = im.itemId";
      //  echo $Query;
       
		$result = DBConnection::SelectQuery($Query);
	   
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		// change by Rajiv for undefine index rerrors on 14-AUG-15
            $bpod_Id = $poId;
			$po_quotNo = $Row['po_quotNo'];
			$po_itemId = $Row['po_principalId'];
			$po_buyeritemcode = $Row['po_buyeritemcode'];
			$unit_id = $Row['po_unit'];
			$po_unit = $Row['po_unit'];
			$po_qty = $Row['po_qty'];
			$po_price = $Row['po_price'];
			$po_discount = $Row['po_discount'];
			$eda1 = $Row['po_ed_applicability'];
			$sTax = "";//$Row['sTax'];
			$po_saleTax = $Row['po_saleTax'];
			$po_deliverybydate = $Row['po_deliverybydate'];
            $po_codePartNo= $Row['po_codePartNo'];
            $po_principalId = $Row['Principal_Supplier_Id'];
            $po_principalName = $Row['Principal_Supplier_Name'];
			$po_item_stage = $Row['po_item_stage'];
            $po_ed_applicability = $po_ed_applicability;
            $newObj = new purchaseorder_Details_Model($bpod_Id,$po_quotNo,$po_principalId,$po_principalName,$po_itemId,$po_codePartNo,$po_buyeritemcode,$unit_id,$po_unit,$po_qty,$po_price,$po_discount,$eda1,$po_ed_applicability,$sTax,$po_saleTax,$po_deliverybydate,$po_item_stage,$po_totVal=null,$itemdescp=null,$Item_Identification_Mark=null,$po_odiscount=null,$po_oprice=null,$po_balance_qty=null);

            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

}

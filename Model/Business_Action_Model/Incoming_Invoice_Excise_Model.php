<?php
//include("../DBModel/Enum_Model.php");
//include("../DBModel/DbModel.php");
class Incoming_Invoice_Excise_Model
{
    public $_entry_Id;
    public $_vehcle_no;
    public $_mode_delivery;
    public $_datetime_of_supply;
    public $_dnt_supply;
    public $_place_of_supply;
    public $_supply_place;
    public $_reverse_charge_payable;
    public $_principal_inv_no;
    public $_principal_inv_date;
    public $_principal_Id;
    public $_principal_gstin;
    public $_supplier_inv_no;
    public $_supplier_inv_date;
    public $_supplier_Id;
    public $_supplier_gstin;
    public $_pf_chrg;
    public $_insurance;
    public $_freight;
    public $_total_bill_val;
    public $_rece_date;
    public $ms;
    public $_remarks;
    public $_user_Id;
    public $_insert_Date;
    public $_itmes  = array();
    public $_mapping_Id;
    public $_principal_name;
    public $_supplier_name;

    public static function GetLastInvoiceNumber(){
        $Query = "SELECT entryId FROM incominginvoice_excise ORDER BY entryId DESC LIMIT 1";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        //$Row["entryId"] = 5;

        if($Row["entryId"] == null)
        {
            $myid = array("_entry_Id"=>"000001","_mapping_Id"=>"1");
            return $myid;
        }
        if($Row["entryId"] > 0)
        {
            $id = $Row["entryId"] + 1;
            $s = sprintf("%06d", $id);
            $myid = array("_entry_Id"=>$s,"_mapping_Id"=>self::Create_DISPLAYID());
            return $myid;
        }
        else
            return null;
    }
    
    //###################3
    public static function validateInvoice($principalID,$invNo)
    {
	   $Query="SELECT COUNT(*) AS cnt FROM incominginvoice_excise WHERE principal_inv_no='$invNo' AND principalId='$principalID'";
	   $Result = DBConnection::SelectQuery($Query);
	   $row1=mysql_fetch_array($Result, MYSQL_ASSOC);
	   $total=$row1['cnt'];
	   return $total;
	}
	
   
   //####################
    public static function GET_INV_NUM_BY_DISPLAY($DISPLAYID,$YEAR){
        $Query = "select inner_EntryNo from incominginvoice_entryno_mapping where display_EntryNo = $DISPLAYID AND finyear = '".$YEAR."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["inner_EntryNo"] > 0)
        {
            return $Row["inner_EntryNo"];
        }
        else
            return 0;
    }

    public static function Create_DISPLAYID(){
        $Query = "select (IFNULL(MAX(display_EntryNo),0)+1)display_EntryNo  FROM incominginvoice_entryno_mapping WHERE finyear='".MultiweldParameter::GetFinancialYear_fromTXT()."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["display_EntryNo"] > 0)
        {
            return $Row["display_EntryNo"];
        }
    }
    
    public static function INSERT_DISPLAY_ENTRY_MAPING($IEID){
        $currentyear = MultiweldParameter::GetFinancialYear_fromTXT();
        $Query = "insert into incominginvoice_entryno_mapping (inner_EntryNo,display_EntryNo, finyear) values (".$IEID.",".self::Create_DISPLAYID().",'".$currentyear."')";
		
        $Result = DBConnection::InsertQuery($Query);
    }
    
    public static function DELETE_DISPLAY_ENTRY_MAPING($IEID){
        $Query = "delete from incominginvoice_entryno_mapping where inner_EntryNo = $IEID";
        $Result = DBConnection::InsertQuery($Query);
    }
    
	public static function Insert_Incoming_Invoice_Excise($mode_delivery, $vehcle_no, $_datetime_of_supply, $_place_of_supply, $_reverse_charge_payable, $_principal_inv_no, $_principal_inv_date, $_principal_Id, $_principal_gstin, $_supplier_inv_no, $_supplier_inv_date, $_supplier_Id, $_supplier_gstin, $_pf_chrg, $_insurance, $_freight, $_total_bill_val, $_rece_date, $ms, $_remarks, $_user_Id){

        $date = date("Y-m-d");
        $mode_delivery = !empty($mode_delivery)?$mode_delivery:'';
        $vehcle_no = !empty($vehcle_no)?$vehcle_no:'';
        $_datetime_of_supply = !empty($_datetime_of_supply)?date('Y-m-d h:i:s',strtotime($_datetime_of_supply)):'0000-00-00 00:00:00';
        $_place_of_supply = !empty($_place_of_supply)?$_place_of_supply:'';
        $reverse_charge_payable = !empty($reverse_charge_payable)?$reverse_charge_payable:'';
        $_principal_Id = !empty($_principal_Id)?$_principal_Id:0;
        $_supplier_Id = !empty($_supplier_Id)?$_supplier_Id:'null';
        $_supplier_inv_date = !empty($_supplier_inv_date)?date('Y-m-d',strtotime($_supplier_inv_date)):'0000-00-00';
        $_principal_inv_date = !empty($_principal_inv_date)?date('Y-m-d',strtotime($_principal_inv_date)):'0000-00-00';
        $_pf_chrg = !empty($_pf_chrg)?round($_pf_chrg, 2):0;
        $_insurance = !empty($_insurance)?round($_insurance,2):0;
        $_freight = !empty($_freight)?round($_freight,2):0;
        $_total_bill_val = !empty($_total_bill_val)?$_total_bill_val:0;
        $_rece_date = !empty($_rece_date)?date('Y-m-d',strtotime($_rece_date)):'0000-00-00';
        $remarks = mysql_escape_string($remarks);
		
		$Query = "INSERT INTO incominginvoice_excise (`mode_delivery`, `vehcle_no`, `datetime_of_supply`, `place_of_supply`, `reverse_charge_payable`, `principal_inv_no`, `principal_inv_date`, `principalId`, `principal_gstin`, `supplier_inv_no`, `supplier_inv_date`, `supplierId`, `supplier_gstin`, `pf_chrg`, `insurance`, `freight`, `total_bill_val`, `rece_date`, `msid`, `remarks`, `userId`, `insertDate`) VALUES ('$mode_delivery', '$vehcle_no', '$_datetime_of_supply', '$_place_of_supply', '$_reverse_charge_payable', '$_principal_inv_no', '$_principal_inv_date', $_principal_Id, '$_principal_gstin', '$_supplier_inv_no', '$_supplier_inv_date', $_supplier_Id, '$_supplier_gstin', $_pf_chrg, $_insurance, $_freight, $_total_bill_val, '$_rece_date', '$ms', '$_remarks', '$_user_Id', '$date')"; 
		
        $Result = DBConnection::InsertQuery($Query);
        
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }

    /* public static function update_Incoming_Invoice_Excise($entryId,$vehcle_no, $mode_delivery, $principal_inv_no, $principal_inv_date, $principalId,$supplier_inv_no, $supplier_inv_date, $supplierId, $pf_chrg, $insurance, $freight, $saleTax,$saleTaxAmt, $total_bill_val, $rece_date, $remarks,$userId,$ms){
            if($supplierId==NULL){
				$supplierId='NULL';
			}
	        if($pf_chrg==NULL){
				$pf_chrg='NULL';
			}
	        if($insurance==NULL){
				$insurance='NULL';
			}
	        if($freight==NULL){
				$freight='NULL';
			}
	        if($saleTax==NULL){
				$saleTax=0;
			}
			if($saleTaxAmt=="" ||$saleTaxAmt==NULL || $saleTaxAmt==null)
			{
			  $saleTaxAmt=0.00;
			}
			if($total_bill_val=="")
			{
				$total_bill_val=0.00;
			}
	        //echo($principal_inv_date);
	   		$principal_inv_date1= MultiweldParameter::xFormatDate($principal_inv_date);

			if($supplier_inv_date==NULL){
				$supplier_inv_date1='0000-00-00';
			}else{
				$supplier_inv_date1= MultiweldParameter::xFormatDate($supplier_inv_date);
			}
	   		$rece_date1 = MultiweldParameter::xFormatDate($rece_date);
            $date = date("Y-m-d");
            
           //added on 02-JUNE-2016 due to Handle Special Character
			$remarks = mysql_escape_string($remarks);
            
			$Query = "UPDATE incominginvoice_excise ";
			$Query = $Query."SET vehcle_no = '$vehcle_no' , mode_delivery = '$mode_delivery', principal_inv_no = '$principal_inv_no',";
			$Query = $Query."principal_inv_date = '$principal_inv_date1', principalId = $principalId, supplier_inv_no = '$supplier_inv_no',";
			$Query = $Query."supplier_inv_date = '$supplier_inv_date1', supplierId = $supplierId, pf_chrg = $pf_chrg,";
			$Query = $Query."insurance = $insurance, freight = $freight, saleTax = $saleTax, SaleTaxAmt = $saleTaxAmt,";
			$Query = $Query."total_bill_val = $total_bill_val, rece_date = '$rece_date1', remarks = '".$remarks."',";
			$Query = $Query."userId = '$userId',msid='$ms' WHERE entryId=$entryId";
	  
			$Result = DBConnection::UpdateQuery($Query);
 
			if($Result =="SUCCESS"){
				return $Result;
			}else{
				return -1;
			}
	} */
	
	public static function update_Incoming_Invoice_Excise($entryId, $mode_delivery, $vehcle_no, $_datetime_of_supply, $_place_of_supply, $_reverse_charge_payable, $_principal_inv_no, $_principal_inv_date, $_principal_Id, $_principal_gstin, $_supplier_inv_no, $_supplier_inv_date, $_supplier_Id, $_supplier_gstin, $_pf_chrg, $_insurance, $_freight, $_total_bill_val, $_rece_date, $ms, $_remarks, $_user_Id){
        
        $mode_delivery = !empty($mode_delivery)?$mode_delivery:'';
        $vehcle_no = !empty($vehcle_no)?$vehcle_no:'';
        $_datetime_of_supply = !empty($_datetime_of_supply)?date('Y-m-d h:i:s',strtotime($_datetime_of_supply)):'0000-00-00 00:00:00';
        $_place_of_supply = !empty($_place_of_supply)?$_place_of_supply:'';
        $reverse_charge_payable = !empty($reverse_charge_payable)?$reverse_charge_payable:'';
        $_principal_Id = !empty($_principal_Id)?$_principal_Id:0;
        $_supplier_Id = !empty($_supplier_Id)?$_supplier_Id:'null';
        $_supplier_inv_date = !empty($_supplier_inv_date)?date('Y-m-d',strtotime($_supplier_inv_date)):'0000-00-00';
        $_principal_inv_date = !empty($_principal_inv_date)?date('Y-m-d',strtotime($_principal_inv_date)):'0000-00-00';
        $_pf_chrg = !empty($_pf_chrg)?round($_pf_chrg, 2):0;
        $_insurance = !empty($_insurance)?round($_insurance,2):0;
        $_freight = !empty($_freight)?round($_freight,2):0;
        $_total_bill_val = !empty($_total_bill_val)?$_total_bill_val:0;
        $_rece_date = !empty($_rece_date)?date('Y-m-d',strtotime($_rece_date)):'0000-00-00';
        $remarks = mysql_escape_string($remarks);
		
		$Query = "UPDATE incominginvoice_excise SET `mode_delivery` = '$mode_delivery', `vehcle_no` = '$vehcle_no', `datetime_of_supply` = '$_datetime_of_supply', `place_of_supply` = '$_place_of_supply', `reverse_charge_payable` = '$_reverse_charge_payable', `principal_inv_no` = '$_principal_inv_no', `principal_inv_date` = '$_principal_inv_date', `principalId` = $_principal_Id, `principal_gstin` = '$_principal_gstin', `supplier_inv_no` = '$_supplier_inv_no', `supplier_inv_date` = '$_supplier_inv_date', `supplierId` = $_supplier_Id, `supplier_gstin` = '$_supplier_gstin', `pf_chrg` = $_pf_chrg, `insurance` = $_insurance, `freight` = $_freight, `total_bill_val` = $_total_bill_val, `rece_date` = '$_rece_date', `msid` = '$ms', `remarks` = '$_remarks', `userId` = '$_user_Id' WHERE entryId=$entryId"; 
		
		$Result = DBConnection::UpdateQuery($Query);
 
			if($Result =="SUCCESS"){
				return $Result;
			}else{
				return -1;
			}
		//return $Query;
	}
	
    public function __construct($_entry_Id,$_vehcle_no,$_mode_delivery,$_dnt_supply, $_supply_place, $_principal_inv_no,$_principal_inv_date,$_principal_Id,$_principal_name, $_supplier_inv_no,$_supplier_inv_date,$_supplier_Id,$_supplier_name,$_pf_chrg,$_insurance,$_supplier_gstin, $_freight,$_sale_Tax,$saleTaxAmt,$_total_bill_val, $_rece_date,$_remarks,$_user_Id,$_insert_Date,$_itmes,$_mapping_Id,$ms=null,$_reverse_charge_payable = null){
        $this->_entry_Id = $_entry_Id;
        $this->_vehcle_no = $_vehcle_no;
        $this->_mode_delivery = $_mode_delivery;
        $this->_principal_inv_no = $_principal_inv_no;
        $this->_principal_inv_date = $_principal_inv_date;
        $this->_principal_Id = $_principal_Id;
        $this->_principal_name = $_principal_name;
        $this->_supplier_inv_no = $_supplier_inv_no;
        $this->_supplier_inv_date = $_supplier_inv_date;
        $this->_supplier_Id = $_supplier_Id;
        $this->_supplier_name = $_supplier_name;
        $this->_pf_chrg = round($_pf_chrg,2);
        $this->_insurance = round($_insurance,2);
        $this->_freight = round($_freight,2);
        $this->_sale_Tax = $_sale_Tax;
        $this->SaleTaxAmount =$saleTaxAmt;
        $this->_total_bill_val = $_total_bill_val;
        $this->_rece_date = $_rece_date;
        $this->_remarks = $_remarks;
        $this->_user_Id = $_user_Id;
        $this->_insert_Date = $_insert_Date;
        $this->_itmes = $_itmes;
        $this->_mapping_Id = $_mapping_Id;
		$this->ms=$ms;
		$this->_dnt_supply = $_dnt_supply;
		$this->_supply_place = $_supply_place;
		$this->_supplier_gstin = $_supplier_gstin;
		$this->_reverse_charge_payable = $_reverse_charge_payable;
	}
	
    public static function  LoadIncomingInvoiceExcise($Insert_Incoming_Invoice_Number,$start = null,$rp = null)
	{	
        $result;
        if($Insert_Incoming_Invoice_Number > 0)
        {
            $result = self::GetIncomingInvoiceExciseDetails($Insert_Incoming_Invoice_Number);
            
        }
        else
        {
            $result = self::GetIncomingInvoiceExciseList($start,$rp);
        }

        $objArray = array();
        $i = 0;

        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_entry_Id = $Row['entryId'];
            $_mapping_Id = $Row['display_EntryNo'];
            $_vehcle_no = $Row['vehcle_no'];
            $_mode_delivery = $Row['mode_delivery'];
            $_dnt_supply = $Row['datetime_of_supply'];
            $_supply_place = $Row['place_of_supply'];
            $_principal_inv_no = $Row['principal_inv_no'];
            $_principal_inv_date = $Row['principal_inv_date'];
            $_principal_Id = $Row['principalId'];
            $_principal_name = $Row['princname'];
            $_supplier_inv_no = $Row['supplier_inv_no'];
            $_supplier_inv_date = $Row['supplier_inv_date'];
            $_supplier_Id = $Row['supplierId'];
            $_supplier_name = $Row['suppname'];
            $_pf_chrg = round($Row['pf_chrg'],2);
            $_insurance = round($Row['insurance'],2);
            $_freight = round($Row['freight'],2);
            $_sale_Tax = $Row['saleTax'];
            $saleTaxAmt=$Row['SaleTaxAmt'];
            $_total_bill_val = $Row['total_bill_val'];
            $_rece_date = $Row['rece_date'];
            $_remarks = $Row['remarks'];
            $_user_Id = $Row['userId'];
            $_insert_Date = $Row['insertDate'];
            $_supplier_gstin = $Row['supplier_gstin'];
            $_reverse_charge_payable = $Row['reverse_charge_payable'];
			$ms = $Row['msid'];
			//$_principal_inv_date1=MultiweldParameter::xFormatDate1($_principal_inv_date);
			$_principal_inv_date1= $_principal_inv_date;
            if($_supplier_inv_date=='0000-00-00'){
                $_supplier_inv_date1='';
			}else{
			  //$_supplier_inv_date1=MultiweldParameter::xFormatDate1($_supplier_inv_date);
			  $_supplier_inv_date1= $_supplier_inv_date;
			}
			//$_rece_date1=MultiweldParameter::xFormatDate1($_rece_date);
			$_rece_date1= $_rece_date;

            if($Insert_Incoming_Invoice_Number > 0)
            {	
                $_itmes = Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetails($Row['entryId'],$Row['pf_chrg'],$Row['insurance'],$Row['freight'],$Row['saleTax']);
            }
            else
            {
                $_itmes = null;
            }


            $newObj = new Incoming_Invoice_Excise_Model($_entry_Id,$_vehcle_no,$_mode_delivery,$_dnt_supply, $_supply_place, $_principal_inv_no,$_principal_inv_date1,$_principal_Id,$_principal_name,$_supplier_inv_no,$_supplier_inv_date1,$_supplier_Id,$_supplier_name,$_pf_chrg,$_insurance,$_supplier_gstin, $_freight,$_sale_Tax,$saleTaxAmt,$_total_bill_val,$_rece_date1,$_remarks,$_user_Id,$_insert_Date,$_itmes,$_mapping_Id,$ms, $_reverse_charge_payable);
            $objArray[$i] = $newObj;
            //echo "<pre>"; print_r($newObj); exit;
            $i++;
		}
		return $objArray;
	}
	
	//~ public static function SearchIncomingInvoiceExcise($year,$value1,$value2,$value3,$value4,$start,$rp,&$count){
        //~ $Query = "";
        //~ $CountQuery = "";
//~ 
        //~ $Query = "SELECT iie.*,iem.display_EntryNo,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppname FROM incominginvoice_excise as iie ";
        //~ $Query =$Query. " LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId ";
        //~ $Query =$Query. " INNER JOIN incominginvoice_entryno_mapping AS iem ON iie.entryId=iem.inner_EntryNo ";
        //~ $Query =$Query. " WHERE  iem.finyear='".$year."'";
//~ 
        //~ if($value1!=""){
                   //~ $Query =$Query. " and (iie.principal_inv_no = '$value1' OR iie.supplier_inv_no = '$value1')";
        //~ }
        //~ if($value2!=""){
           //~ $Query =$Query. " and iie.principal_inv_date >= '$value2'";
        //~ }
        //~ if($value3!=""){
                   //~ $Query =$Query. " and iie.principal_inv_date <= '$value3'";
        //~ }
        //~ if($value4!=""){
                  //~ $Query =$Query. " and psm1.Principal_Supplier_Id = $value4";
//~ 
        //~ }
        //~ $Query =$Query." order by rece_date desc LIMIT $start , $rp";
        //~ //echo $Query;
        //~ $CountQuery = "SELECT count(*) as total FROM incominginvoice_excise as iie ";
        //~ $CountQuery =$CountQuery. " LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId ";
        //~ $CountQuery =$CountQuery. " INNER JOIN incominginvoice_entryno_mapping AS iem ON iie.entryId=iem.inner_EntryNo ";
        //~ $CountQuery =$CountQuery. " WHERE  iem.finyear='".$year."'";
        //~ if($value1!=""){
                   //~ $CountQuery =$CountQuery. " and (iie.principal_inv_no = '$value1' OR iie.supplier_inv_no = '$value1')";
        //~ }
        //~ if($value2!=""){
           //~ $CountQuery =$CountQuery. " and iie.principal_inv_date >= '$value2'";
        //~ }
        //~ if($value3!=""){
                   //~ $CountQuery =$CountQuery. " and iie.principal_inv_date <= '$value3'";
        //~ }
        //~ if($value4!=""){
                  //~ $CountQuery =$CountQuery. " and psm1.Principal_Supplier_Id = $value4";
//~ 
        //~ }
//~ 
        //~ $CountResult = DBConnection::SelectQuery($CountQuery);
        //~ $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        //~ $count = $RowCount['total'];
//~ 
//~ 
//~ 
		//~ $result = DBConnection::SelectQuery($Query);
        //~ $objArray = array();
		//~ $i = 0;
//~ 
		//~ while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//~ 
//~ 
            //~ $_entry_Id = $Row['display_EntryNo'];
            //~ //$_vehcle_no = $Row['vehcle_no'];
            //~ //$_mode_delivery = $Row['mode_delivery'];
            //~ $_principal_inv_no = $Row['principal_inv_no'];
            //~ //$_principal_inv_date = $Row['principal_inv_date'];
            //~ //$_principal_Id = $Row['principalId'];
            //~ $_principal_name = $Row['princname'];
            //~ $_supplier_inv_no = $Row['supplier_inv_no'];
            //~ //$_supplier_inv_date = $Row['supplier_inv_date'];
            //~ //$_supplier_Id = $Row['supplierId'];
            //~ $_supplier_name = $Row['suppname'];
            //~ //$_pf_chrg = $Row['pf_chrg'];
            //~ //$_insurance = $Row['insurance'];
            //~ //$_freight = $Row['freight'];
            //~ //$_sale_Tax = $Row['saleTax'];
            //~ //$saleTaxAmt=$Row['SaleTaxAmt'];
            //~ $_total_bill_val = $Row['total_bill_val'];
            //~ //$_rece_date = $Row['rece_date'];
            //~ //$_remarks = $Row['remarks'];
            //~ //$_user_Id = $Row['userId'];
            //~ $_insert_Date = $Row['insertDate'];
			//~ //$_principal_inv_date1=MultiweldParameter::xFormatDate1($_principal_inv_date);
            //~ //if($_supplier_inv_date=='0000-00-00'){
            //~ //    $_supplier_inv_date1='';
            //~ //}else{
            //~ //  $_supplier_inv_date1=MultiweldParameter::xFormatDate1($_supplier_inv_date);
            //~ //}
            //~ //$_rece_date1=MultiweldParameter::xFormatDate1($_rece_date);
            //~ $_itmes = null;// Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetails($Row['entryId'],$Row['pf_chrg'],$Row['insurance'],$Row['freight'],$Row['saleTax']);
//~ 
            //~ $newObj = new Incoming_Invoice_Excise_Model($_entry_Id,$_vehcle_no,$_mode_delivery, $_dnt_supply = null, $_supply_place = null,$_principal_inv_no,$_principal_inv_date1,$_principal_Id,$_principal_name,$_supplier_inv_no,$_supplier_inv_date1,$_supplier_Id,$_supplier_name,$_pf_chrg,$_insurance, $_supplier_gstin, $_freight,$_sale_Tax,$saleTaxAmt,$_total_bill_val,$_rece_date1,$_remarks,$_user_Id,$_insert_Date,$_itmes,$_mapping_Id);
            //~ $objArray[$i] = $newObj;
            //~ $i++;
		//~ }
		//~ return $objArray;
	//~ }
	
	public static function SearchIncomingInvoiceExciseNew($year,$value1,$value2,$value3,$value4,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";	

        $Query = "SELECT iie.*,iem.display_EntryNo,iem.inner_EntryNo AS _mapping_Id,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppname FROM incominginvoice_excise as iie ";
        $Query =$Query. " LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId ";
        $Query =$Query. " INNER JOIN incominginvoice_entryno_mapping AS iem ON iie.entryId=iem.inner_EntryNo ";
        $Query =$Query. " WHERE  iem.finyear='".$year."'";

        if($value1!=""){
                   $Query =$Query. " and (iie.principal_inv_no = '$value1' OR iie.supplier_inv_no = '$value1')";
        }
        if($value2!=""){
           $Query =$Query. " and iie.principal_inv_date >= '$value2'";
        }
        if($value3!=""){
                   $Query =$Query. " and iie.principal_inv_date <= '$value3'";
        }
        if($value4!=""){
                  $Query =$Query. " and psm1.Principal_Supplier_Id = $value4";

        }
        $Query =$Query." order by rece_date desc LIMIT $start , $rp";
       // echo $Query; exit;
        $CountQuery = "SELECT count(*) as total FROM incominginvoice_excise as iie ";
        $CountQuery =$CountQuery. " LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId ";
        $CountQuery =$CountQuery. " INNER JOIN incominginvoice_entryno_mapping AS iem ON iie.entryId=iem.inner_EntryNo ";
        $CountQuery =$CountQuery. " WHERE  iem.finyear='".$year."'";
        if($value1!=""){
                   $CountQuery =$CountQuery. " and (iie.principal_inv_no = '$value1' OR iie.supplier_inv_no = '$value1')";
        }
        if($value2!=""){
           $CountQuery =$CountQuery. " and iie.principal_inv_date >= '$value2'";
        }
        if($value3!=""){
                   $CountQuery =$CountQuery. " and iie.principal_inv_date <= '$value3'";
        }
        if($value4!=""){
                  $CountQuery =$CountQuery. " and psm1.Principal_Supplier_Id = $value4";

        }

        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];

		$result = DBConnection::SelectQuery($Query);
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {


            $_entry_Id = $Row['display_EntryNo'];
            $_vehcle_no = $Row['vehcle_no'];
			$_mapping_Id = $Row['_mapping_Id'];
            $_mode_delivery = $Row['mode_delivery'];
            $_principal_inv_no = $Row['principal_inv_no'];
            $_principal_inv_date = $Row['principal_inv_date'];
            $_principal_Id = $Row['principalId'];
            $_principal_name = $Row['princname'];
            $_supplier_inv_no = $Row['supplier_inv_no'];
            $_supplier_inv_date = $Row['supplier_inv_date'];
            $_supplier_Id = $Row['supplierId'];
            $_supplier_name = $Row['suppname'];
            $_pf_chrg = $Row['pf_chrg'];
            $_insurance = $Row['insurance'];
            $_freight = $Row['freight'];
            $_sale_Tax = $Row['saleTax'];
            $saleTaxAmt=$Row['SaleTaxAmt'];
            $_total_bill_val = $Row['total_bill_val'];
            $_rece_date = $Row['rece_date'];
            $_remarks = $Row['remarks'];
            $_user_Id = $Row['userId'];
            $_insert_Date = $Row['insertDate'];
			$_principal_inv_date1=MultiweldParameter::xFormatDate1($_principal_inv_date);
            if($_supplier_inv_date=='0000-00-00'){
                $_supplier_inv_date1='';
            }else{
              $_supplier_inv_date1=MultiweldParameter::xFormatDate1($_supplier_inv_date);
            }
           // $_rece_date1=MultiweldParameter::xFormatDate1($_rece_date);
            $_itmes = null;

			//Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetails($Row['entryId'],$Row['pf_chrg'],$Row['insurance'],$Row['freight'],$Row['saleTax']);

            $newObj = new Incoming_Invoice_Excise_Model($_entry_Id,$_vehcle_no,$_mode_delivery, $_dnt_supply = null, $_supply_place = null,$_principal_inv_no,$_principal_inv_date,$_principal_Id,$_principal_name,$_supplier_inv_no,$_supplier_inv_date1,$_supplier_Id,$_supplier_name,$_pf_chrg,$_insurance,$_supplier_gstin,$_freight,$_sale_Tax,$saleTaxAmt,$_total_bill_val,$_rece_date,$_remarks,$_user_Id,$_insert_Date,$_itmes,$_mapping_Id);
            $objArray[$i] = $newObj;
			//echo "<pre>"; print_r($newObj); exit;
            $i++;
		}
		return $objArray;
	}
	
    public static function UpdateIncomingInvoiceExcise($_entry_Id, $vehcle_no, $mode_delivery, $principal_inv_no, $principal_inv_date, $principalId, $supplier_inv_no, $supplier_inv_date, $supplierId, $pf_chrg, $insurance, $freight, $saleTax, $total_bill_val, $rece_date, $remarks){
		
		//added on 01-JUNE-2016 due to Handle Special Character
        $remarks = mysql_escape_string($remarks);
       
       	$Query = "UPDATE incominginvoice_excise SET vehcle_no = '$vehcle_no', mode_delivery = '$mode_delivery', principal_inv_no = '$principal_inv_no', principal_inv_date = '$principal_inv_date', principalId = $principalId, supplier_inv_no = '$supplier_inv_no', supplier_inv_date = '$supplier_inv_date', supplierId = $supplierId, pf_chrg = $pf_chrg, insurance = $insurance, freight = $freight, saleTax = $saleTax, total_bill_val = $total_bill_val, rece_date = '$rece_date', remarks = '$remarks' WHERE entryId = $_entry_Id";
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}

    public static function GetIncomingInvoiceExciseDetails($Insert_Incoming_Invoice_Number){
		$Query = "SELECT iie.*, iemp.display_EntryNo, iemp.finyear,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppname FROM incominginvoice_excise as iie LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId inner join incominginvoice_entryno_mapping as iemp ON iie.entryId = iemp.inner_EntryNo WHERE iie.entryId = $Insert_Incoming_Invoice_Number";
		//print_r($Query);exit;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetIncomingInvoiceExciseList($start,$rp){
		$Query = "SELECT iie.*, iemp.display_EntryNo, iemp.finyear,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppname FROM incominginvoice_excise as iie LEFT JOIN principal_supplier_master as psm1 ON psm1.Principal_Supplier_Id = iie.principalId LEFT JOIN principal_supplier_master as psm2 ON psm2.Principal_Supplier_Id = iie.supplierId inner join incominginvoice_entryno_mapping as iemp ON iie.entryId = iemp.inner_EntryNo LIMIT $start , $rp";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function FIND_INVOIC_IN_OGINV_ST($INVOICENO){

		$Query = "SELECT (outCount+stCount) as num from ";
		$Query = $Query."(select count(*) as outCount FROM outgoinginvoice_excise_detail where iinv_no IN (select entryDId FROM incominginvoice_excise_detail where entryId='$INVOICENO')) as t1, ";
        $Query = $Query."(select count(*) as stCount FROM stocktransfer_detail where iinv_no IN (select entryDId FROM incominginvoice_excise_detail where entryId='$INVOICENO')) as t2 ";

	
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["num"];
    }
    public static function DeleteInvoiceExcise($EntryId){
        $Query = "delete from incominginvoice_excise where entryId = $EntryId";
        //echo($Query);
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }

}

class Incoming_Invoice_Excise_Model_Details
{
    public $_entryDId;
    public $_entry_Id;
    public $_itemID_terrif_heading;
    public $_item_id;
    public $_item_code_part_no;
    public $_itemID_descp;
    public $_principal_qty;
    public $_itemID_unitid;
    public $_itemID_unitname;
    public $_supplier_qty;
    public $_unit_name;
    public $_expire_date;
    public $_batch_number;
    public $_basic_purchase_price;
    public $_total;
    public $_discount;
	public $_discounted_amt;
	public $_packing_percent;
	public $_packing_amt;
	public $_insurance_percent;
	public $_insurance_amt;
	public $_freight_percent;
	public $_freight_amt;
	public $_other_percent;
	public $_other_amt;
    public $_taxable_total;
    public $_cgst_rate;
    public $_cgst_amt;
    public $_sgst_rate;
    public $_sgst_amt;
    public $_igst_rate;
    public $_igst_amt;
    public $_landing_price;
    public $_total_landing_price;
    public $iinvno;
    public $iinvdate;
    public $mappingid;
	public $ms;
	public $principal_inv_date;
	public $supplier_inv_no;
	public $supplier_inv_date;
	public $_hsn_code;

    public function __construct($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_itemID_code_partNo,$_p_qty,$_unitId_p,$_unit_name_p,$_s_qty,$_unitId_s,$_tot_ass_val,$_ed_percent,$_ed_amt,$_edu_percent,$_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amt,$_expiry_date,$_batch_no,$_supplr_RG23D,$_basic_purchase_price,$_ed_per_Unit,$_item_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price,$_total_landing_price,$iinvno,$_total,$_discount=null, $_discounted_amt, $_packing_per, $_packing_amt, $_insurance_per, $_insurance_amt, $_freight_per, $_freight_amt, $_other_per, $_other_amt,$_taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt, $iinvdate=null,$mappingid=null, $principal_inv_date=null){
        $this->_entryDId = $_entryDId;
        $this->_entry_Id = $_entry_Id;
        $this->_itemID_terrif_heading = $_itemID_terrif_heading;
        $this->_hsn_code = $_itemID_terrif_heading;
        $this->_item_id=$_item_id;
        $this->_item_code_part_no = $_itemID_code_partNo;
        $this->_principal_qty = $_p_qty;
        $this->_itemID_unitid = $_unitId_p;
        $this->_itemID_unitname = $_unit_name_p;
        $this->_supplier_qty = $_s_qty;
        $this->_unit_name = $_unitId_s;
        $this->_total_ass_value = $_tot_ass_val;
        $this->_ed_percent = $_ed_percent;
        $this->_ed_amount = $_ed_amt;
        $this->_edu_cess_percent = $_edu_percent;
        $this->_edu_amt = $_edu_amt;
        $this->_hedu_percent = $_hedu_percent;
        $this->_hedu_amount = $_hedu_amount;
        $this->_cvd_percent = $_cvd_percent;
        $this->_cvd_amount = $_cvd_amt;
        $this->_expire_date = $_expiry_date;
        $this->_batch_number = $_batch_no;
        $this->_supplier_rg23d = $_supplr_RG23D;
        $this->_basic_purchase_price = $_basic_purchase_price;
        $this->_ed_per_Unit = $_ed_per_Unit;
        $this->_itemID_descp = $_item_descp;
        $this->_unit_ass_value = $_unit_ass_value;
        $this->_ed_unit = $_ed_unit;
        $this->_edu_cess_amount = $_edu_cess_amount;
        $this->_landing_price = $_landing_price;
        $this->_total_landing_price = $_total_landing_price;
        $this->iinvno = $iinvno;
        $this->iinvdate = $iinvdate;
        $this->mappingid = $mappingid;
		$this->principal_inv_date = $principal_inv_date;
		$this->_discount = $_discount;
		$this->_discounted_amt = $_discounted_amt;
		$this->_packing_percent = $_packing_per;
		$this->_packing_amt = $_packing_amt;
		$this->_insurance_percent = $_insurance_per;
		$this->_insurance_amt = $_insurance_amt;
		$this->_freight_percent = $_freight_per;
		$this->_freight_amt = $_freight_amt;
		$this->_other_percent = $_other_per;
		$this->_other_amt = $_other_amt;
		$this->_total = $_total;
		$this->_taxable_total = $_taxable_total;
		$this->_cgst_rate = $_cgst_rate;
		$this->_cgst_amt = $_cgst_amt;
		$this->_sgst_rate = $_sgst_rate;
		$this->_sgst_amt = $_sgst_amt;
		$this->_igst_rate = $_igst_rate;
		$this->_igst_amt = $_igst_amt;
	}


    public static function GetIncomingInvoiceExciseInfo($entryId)
	{
			$Query = "SELECT itemID_code_partNo,IF(s_qty>0,s_qty,p_qty) as qty,entryDId AS iinv_no FROM incominginvoice_excise_detail WHERE  entryId=$entryId";
			$RESULT = DBCONNECTION::SELECTQUERY($Query);
			$OBJARRAY = ARRAY();
			$I = 0;
			WHILE ($ROW = MYSQL_FETCH_ARRAY($RESULT, MYSQL_ASSOC)) {
			 $OBJARRAY[$I]['codePartNo']=$ROW['itemID_code_partNo'];
			 $OBJARRAY[$I]['qty']=$ROW['qty'];
			 $OBJARRAY[$I]['iinv_no']=$ROW['iinv_no'];
			 $I++;
			}
			RETURN $OBJARRAY;

	}

    public static function  LoadIncomingInvoiceExciseDetails($Insert_Incoming_Invoice_Number,$_pf_chrg,$_insurance,$_freight,$_sale_Tax)
	{
        $result = self::GetIncomingInvoiceExciseDetailsList($Insert_Incoming_Invoice_Number);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			//print_r($Row);exit;
             $_entryDId = $Row['entryDId'];
             $_entry_Id = $Row['entryId'];
             $_itemID_terrif_heading = $Row['Tarrif_Heading'];
             $_item_id=$Row['itemID_code_partNo'];
             $_item_code_part_no = $Row['Item_Code_Partno'];
             $_principal_qty = $Row['p_qty'];
             $_itemID_unitid = $Row['unitId_p'];
             $_itemID_unitname = $Row['UNITNAME'];
             $_supplier_qty = $Row['s_qty'];
             $_unit_name = $Row['UNITNAME'];
             $_total_ass_value = $Row['tot_ass_val'];
             $_ed_percent = $Row['ed_percent'];
             $_ed_amount = $Row['ed_amt'];
             $_edu_cess_percent = $Row['edu_percent'];
             $_edu_amt = $Row['edu_amt'];
             $_hedu_percent = $Row['hedu_percent'];
             $_hedu_amount = $Row['hedu_amount'];
             $_cvd_percent = $Row['cvd_percent'];
             $_cvd_amount = $Row['cvd_amt'];
             $_discount = $Row['discount_percent'];
			 $_discount_amt = $Row['discounted_amt'];
			 $_packing_per = $Row['packing_percent'];
			 $_packing_amt = $Row['packing_amt'];
			 $_insurance_per = $Row['insurance_percent'];
			 $_insurance_amt = $Row['insurance_amt'];
			 $_freight_per = $Row['freight_percent'];
			 $_freight_amt = $Row['freight_amt'];
			 $_other_per = $Row['other_percent'];
			 $_other_amt = $Row['other_amt'];
             $_total = $Row['total'];
             $_taxable_total = $Row['taxable_amt'];
             $_cgst_rate = $Row['cgst_rate'];
             $_cgst_amt = $Row['cgst_amt'];
             $_sgst_rate = $Row['sgst_rate'];
             $_sgst_amt = $Row['sgst_amt'];
             $_igst_rate = $Row['igst_rate'];
             $_igst_amt = $Row['igst_amt'];
             //echo $_discount; exit;
			 $_expire_date1 = "0000-00-00";
             if($Row['expiry_date'] == "0000-00-00")
             {
			 	$_expire_date = "";
			 }
			 else
			 {
			 	$_expire_date = $Row['expiry_date'];
			 	//$_expire_date1=MultiweldParameter::xFormatDate1($_expire_date);
				$_expire_date1= $_expire_date;
			 }
             if($Row['batch_no'] == 'NULL')
             {
                $_batch_number = "";
             }
             else
             {
                 $_batch_number = $Row['batch_no'];
             }
             if($Row['supplr_RG23D'] == 'NULL')
             {
                $_supplier_rg23d = "";
             }
             else
             {
                 $_supplier_rg23d = $Row['supplr_RG23D'];
             }
             $_basic_purchase_price = $Row['basic_purchase_price'];
             $_ed_per_Unit = $Row['ed_perUnit'];
             $_itemID_descp = $Row['Item_Desc'];
             $_unit_ass_value = self::GetUnitAssValue($Row['p_qty'],$Row['s_qty'],$Row['tot_ass_val']);
             $_ed_unit = self::CaseA_Get_ED_Unit($Row['p_qty'],$Row['s_qty'],$Row['ed_amt']);
             $_edu_cess_amount = $Row['edu_amt'];
             $_landing_price = $Row['landing_price'];
             $_total_landing_price = $Row['total_landing_price'];
            // $mappingid = $Row['display_EntryNo'];
			$mappingid = $_entry_Id;
             //$newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty,$_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent, $_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d, $_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price, $_total_landing_price,$mappingid,$_total, $_discount, $_taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt);
			 
			$newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty,$_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent, $_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d, $_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price, $_total_landing_price,$mappingid,$_total, $_discount, $_discount_amt, $_packing_per, $_packing_amt, $_insurance_per, $_insurance_amt, $_freight_per, $_freight_amt, $_other_per, $_other_amt, $_taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

     public static function  LoadIncomingInvoiceExciseDetailsbycodepart($INVOICEDID=null,$codepart=null)
	{
        $Query = "SELECT iied.*,im1.Item_Code_Partno,im2.Tarrif_Heading,im1.Item_Desc,
um.UNITNAME ,iie.principal_inv_no, iie.principal_inv_date ,iiem.display_EntryNo
FROM incominginvoice_excise_detail as iied
INNER JOIN item_master as im1 ON im1.ItemId = iied.itemID_code_partNo
INNER JOIN item_master as im2 ON im2.ItemId = iied.itemID_terrif_heading
INNER JOIN unit_master as um ON um.UnitId = im1.unitId
INNER JOIN incominginvoice_excise as iie ON iie.entryId = iied.entryId
inner join incominginvoice_entryno_mapping as iiem on iie.entryId = iiem.inner_EntryNo
WHERE iied.entryDId = $INVOICEDID";
	
	
        $Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
             $_entryDId = $Row['entryDId'];
             $_entry_Id = $Row['entryId'];
             $_itemID_terrif_heading = $Row['Tarrif_Heading'];
             $_item_id=$Row['itemID_code_partNo'];
             $_item_code_part_no = $Row['Item_Code_Partno'];
              $iinvno = $Row['principal_inv_no'];
            // $_principal_qty = self::GetInvItemStock($iinvno,$_item_id);
             $_principal_qty=$Row['p_qty'];
             $_itemID_unitid = $Row['unitId_p'];
             $_itemID_unitname = $Row['UNITNAME'];
             $_supplier_qty = $Row['s_qty'];
             $_unit_name = $Row['UNITNAME'];
             $_total_ass_value = $Row['tot_ass_val'];
             $_ed_percent = $Row['ed_percent'];
             $_ed_amount = $Row['ed_amt'];
             $_edu_cess_percent = $Row['edu_percent'];
             $_edu_amt = $Row['edu_amt'];
             $_hedu_percent = $Row['hedu_percent'];
             $_hedu_amount = $Row['hedu_amount'];
             $_cvd_percent = $Row['cvd_percent'];
             $_cvd_amount = $Row['cvd_amt'];
             if($Row['expiry_date'] == "0000-00-00")
             {
                    $_expire_date1 = "";
             }
             else
             {
                    $_expire_date = $Row['expiry_date'];
                    $_expire_date1=MultiweldParameter::xFormatDate1($_expire_date);
             }
             if($Row['batch_no'] == 'NULL')
             {
                $_batch_number = "";
             }
             else
             {
                 $_batch_number = $Row['batch_no'];
             }
             if($Row['supplr_RG23D'] == 'NULL')
             {
                $_supplier_rg23d = "";
             }
             else
             {
                 $_supplier_rg23d = $Row['supplr_RG23D'];
             }
             $_basic_purchase_price = $Row['basic_purchase_price'];
             $_ed_per_Unit = $Row['ed_perUnit'];
             $_itemID_descp = $Row['Item_Desc'];
             $_unit_ass_value = self::GetUnitAssValue($Row['p_qty'],$Row['s_qty'],$Row['tot_ass_val']);
             $_ed_unit = self::CaseA_Get_ED_Unit($Row['p_qty'],$Row['s_qty'],$Row['ed_amt']);
             $_edu_cess_amount = $Row['edu_amt'];
             $_landing_price = $Row['landing_price'];
             $_total_landing_price = $Row['total_landing_price'];
            // $iinvno = $Row['principal_inv_no'];
             $iinvdate = MultiweldParameter::xFormatDate1($Row['principal_inv_date']);
             $mappingid = $Row['display_EntryNo'];
             $newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty,$_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent, $_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d, $_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price, $_total_landing_price,$iinvno,$iinvdate,$mappingid);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	
	 public static function  LoadIncomingInvoiceExciseDetailsbyIncomingInventoryId($incoming_inventory_id=null,$codepart=null)
	{
		$Query = "SELECT entryDId FROM incominginventory WHERE incoming_inventory_id = $incoming_inventory_id AND principal_inv_no IS NULL";
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if(isset($Row['entryDId']) && !empty($Row['entryDId'])){
			$INVOICEDID = $Row['entryDId'];
			$Query = "SELECT iied.*,im1.Item_Code_Partno,im2.Tarrif_Heading,im1.Item_Desc,
	um.UNITNAME ,iie.principal_inv_no, iie.principal_inv_date ,iiem.display_EntryNo
	FROM incominginvoice_excise_detail as iied
	INNER JOIN item_master as im1 ON im1.ItemId = iied.itemID_code_partNo
	INNER JOIN item_master as im2 ON im2.ItemId = iied.itemID_terrif_heading
	INNER JOIN unit_master as um ON um.UnitId = im1.unitId
	INNER JOIN incominginvoice_excise as iie ON iie.entryId = iied.entryId
	inner join incominginvoice_entryno_mapping as iiem on iie.entryId = iiem.inner_EntryNo
	WHERE iied.entryDId = $INVOICEDID";
		//echo $Query;exit;
		
			$Result = DBConnection::SelectQuery($Query);
			$objArray = array();
			$i = 0;
			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
				 $_entryDId = $Row['entryDId'];
				 $_entry_Id = $Row['entryId'];
				 $_itemID_terrif_heading = $Row['Tarrif_Heading'];
				 $_item_id=$Row['itemID_code_partNo'];
				 $_item_code_part_no = $Row['Item_Code_Partno'];
				  $iinvno = $Row['principal_inv_no'];
				// $_principal_qty = self::GetInvItemStock($iinvno,$_item_id);
				 $_principal_qty=$Row['p_qty'];
				 $_itemID_unitid = $Row['unitId_p'];
				 $_itemID_unitname = $Row['UNITNAME'];
				 $_supplier_qty = $Row['s_qty'];
				 $_unit_name = $Row['UNITNAME'];
				 $_total_ass_value = $Row['tot_ass_val'];
				 $_ed_percent = $Row['ed_percent'];
				 $_ed_amount = $Row['ed_amt'];
				 $_edu_cess_percent = $Row['edu_percent'];
				 $_edu_amt = $Row['edu_amt'];
				 $_hedu_percent = $Row['hedu_percent'];
				 $_hedu_amount = $Row['hedu_amount'];
				 $_cvd_percent = $Row['cvd_percent'];
				 $_cvd_amount = $Row['cvd_amt'];
				 if($Row['expiry_date'] == "0000-00-00")
				 {
						$_expire_date1 = "";
				 }
				 else
				 {
						$_expire_date = $Row['expiry_date'];
						$_expire_date1=MultiweldParameter::xFormatDate1($_expire_date);
				 }
				 if($Row['batch_no'] == 'NULL')
				 {
					$_batch_number = "";
				 }
				 else
				 {
					 $_batch_number = $Row['batch_no'];
				 }
				 if($Row['supplr_RG23D'] == 'NULL')
				 {
					$_supplier_rg23d = "";
				 }
				 else
				 {
					 $_supplier_rg23d = $Row['supplr_RG23D'];
				 }
				 $_basic_purchase_price = $Row['basic_purchase_price'];
				 $_ed_per_Unit = $Row['ed_perUnit'];
				 $_itemID_descp = $Row['Item_Desc'];
				 $_unit_ass_value = self::GetUnitAssValue($Row['p_qty'],$Row['s_qty'],$Row['tot_ass_val']);
				 $_ed_unit = self::CaseA_Get_ED_Unit($Row['p_qty'],$Row['s_qty'],$Row['ed_amt']);
				 $_edu_cess_amount = $Row['edu_amt'];
				 $_landing_price = $Row['landing_price'];
				 $_total_landing_price = $Row['total_landing_price'];
				// $iinvno = $Row['principal_inv_no'];
				 $iinvdate = MultiweldParameter::xFormatDate1($Row['principal_inv_date']);
				 $mappingid = $Row['display_EntryNo'];
				 $newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty,$_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent, $_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d, $_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price, $_total_landing_price,$iinvno,$iinvdate,$mappingid);
				$objArray[$i] = $newObj;
				$i++;
			}
			return $objArray;
		}
	}
	
	//**#### function for geting initial  principal quantity of incoming invoice Item
	public static function GetInvItemStock($principal_invNo,$ItemId){
		$Query = "SELECT SUM(invd.p_qty) AS p_qty FROM incominginvoice_excise AS inv,incominginvoice_excise_detail AS invd  WHERE inv.entryId= invd.entryId AND   inv.principal_inv_no='$principal_invNo' AND invd.itemID_code_partNo='$ItemId' GROUP BY invd.entryId ORDER BY inv.entryId LIMIT 1";
			
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		
		return $Row['p_qty'];
   }
   //#########
    public static function getCodePartNo($pid){
		$result = self::showCodePartNo($pid);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $_item_id= $Row['ITEMID'];
               $_itemID_code_partNo= $Row['ITEM_CODE_PARTNO'];
               $newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_itemID_code_partNo,$_p_qty,$_itemID_unitid,$_unit_name_p,$_s_qty,$_unitId_s,$_tot_ass_val,$_ed_percent,$_ed_amt,$_edu_percent,$_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amt,$_expiry_date,$_batch_no,$_supplr_RG23D,$_basic_purchase_price,$_ed_per_Unit,$_item_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price,$_total_landing_price);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
   public static function showCodePartNo($id){
 	$Query = "select ITEMID,ITEM_CODE_PARTNO from item_master WHERE PRINCIPALID=$id AND Tarrif_Heading is NOT NULL";
	$Result = DBConnection::SelectQuery($Query);
    return $Result;
    }
    public static function DeleteItem($EntryId){
        $Query = "DELETE FROM incominginvoice_excise_detail WHERE entryId = $EntryId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function GetIncomingInvoiceExciseDetailsList($Insert_Incoming_Invoice_Number){
		$Query = "SELECT iied.*,im1.Item_Code_Partno,im2.Tarrif_Heading,im1.Item_Desc,um.UNITNAME FROM incominginvoice_excise_detail as iied INNER JOIN item_master as im1 ON im1.ItemId = iied.itemID_code_partNo INNER JOIN item_master as im2 ON im2.ItemId = iied.itemID_terrif_heading INNER JOIN unit_master as um ON um.UnitId = im1.unitId WHERE iied.entryId = $Insert_Incoming_Invoice_Number";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetUnitAssValue($principal_Qty,$Supplier_Qty,$total_ass_value)
    {
        if($Supplier_Qty > 0){
            return $total_ass_value/$Supplier_Qty;
        }
        else if($principal_Qty > 0){
            return $total_ass_value/$principal_Qty;
        }
        else{
            return 0;
        }
    }
    public static function CaseA_Get_ED_Unit($principal_Qty,$Supplier_Qty,$ED_Amt)
    {
        if($Supplier_Qty > 0){
            return $ED_Amt/$Supplier_Qty;
        }
        else if($principal_Qty > 0){
            return $ED_Amt/$principal_Qty;
        }
        else{
            return 0;
        }
    }
    public static function CaseC_EDU_Cess_Amount($ED_Amt,$EDU_Cess_Percent)
    {
        return $ED_Amt * $EDU_Cess_Percent;
    }
    public static function CaseD_Get_Landing_Price($_basic_price,$_ed_per_Unit,$_edu_amt,$_cvd_amount,$_hedu_amount,$_pf_chrg,$_insurance,$_freight,$_sale_Tax,$principal_Qty,$Supplier_Qty)
    {
        $cess_amt = 0.00;
        $cvd_amt = 0.00;
        $hedu_amt = 0.00;
        if($Supplier_Qty > 0){
            $cess_amt = $_edu_amt/$Supplier_Qty;
            $cvd_amt = $_cvd_amount/$Supplier_Qty;
            $hedu_amt = $_hedu_amount/$Supplier_Qty;
        }
        else if($principal_Qty > 0){
            $cess_amt = $_edu_amt/$principal_Qty;
            $cvd_amt = $_cvd_amount/$principal_Qty;
            $hedu_amt = $_hedu_amount/$principal_Qty;
        }
        $TaxableAmount = $_basic_price + $_ed_per_Unit +  $cess_amt + $cvd_amt + $hedu_amt;
        return $_landing_price + $_ed_amount + $_edu_amt + $_cvd_amount + $_pf_chrg + $_insurance + $_freight + $_sale_Tax;
    }
    public static function CaseE_Get_Total_Landing_Price($principal_Qty,$Supplier_Qty,$_landing_price)
    {
        if($Supplier_Qty > 0){
            return $_landing_price*$Supplier_Qty;
        }
        else if($principal_Qty > 0){
            return $_landing_price*$principal_Qty;
        }
        else{
            return 0;
        }
    }
    
    //public static function Insert_Incoming_Invoice_Excise_Details($entryId,$itemID_terrif_heading,$itemID_code_partNo,$p_qty,$unitId_p,$s_qty,$unitId_s,$expiry_date, $batch_no, $basic_purchase_price, $_total, $__discount, $__taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt, $_landing_price, $_total_landing_price){
	public static function Insert_Incoming_Invoice_Excise_Details($entryId, $itemID_terrif_heading, $itemID_code_partNo,$p_qty, $unitId_p ,$s_qty, $unitId_s, $expiry_date, $batch_no, $basic_purchase_price, $_total, $__discount, $_discounted_amt, $_packing_percent, $_packing_amt, $_insurance_percent, $_insurance_amt, $_freight_percent, $_freight_amt, $_other_percent, $_other_amt, $__taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt, $_landing_price, $_total_landing_price){
        
        $date = date("Y-m-d");
        $expiry_date = !empty($expiry_date)?date("Y-m-d",strtotime($expiry_date)):'0000-00-00';
        
		// added on 02-JUNE-2016 due to Handle Special Character
        $itemID_terrif_heading = mysql_escape_string($itemID_terrif_heading);
        $itemID_code_partNo = mysql_escape_string($itemID_code_partNo);
        

        //$Query = "INSERT INTO incominginvoice_excise_detail (`entryId`, `itemID_terrif_heading`, `itemID_code_partNo`, `p_qty`, `unitId_p`, `s_qty`, `unitId_s`, `expiry_date`, `batch_no`, `basic_purchase_price`, `total`, `discount_percent`, `taxable_amt`, `cgst_rate`, `cgst_amt`, `sgst_rate`, `sgst_amt`, `igst_rate`, `igst_amt`, `landing_price`, `total_landing_price`) VALUES ($entryId, $itemID_terrif_heading,$itemID_code_partNo, $p_qty, $unitId_p,$s_qty,$unitId_s,'$expiry_date','$batch_no',$basic_purchase_price, $_total, $__discount, $__taxable_total, $_cgst_rate, $_cgst_amt, $_sgst_rate, $_sgst_amt, $_igst_rate, $_igst_amt, $_landing_price,$_total_landing_price)";
		
		$Query = "INSERT INTO incominginvoice_excise_detail (`entryId`, `itemID_terrif_heading`, `itemID_code_partNo`, `p_qty`, `unitId_p`, `s_qty`, `unitId_s`, `expiry_date`, `batch_no`, `basic_purchase_price`, `total`, `discount_percent`, `discounted_amt`, `packing_percent`, `packing_amt`, `insurance_percent`, `insurance_amt`, `freight_percent`, `freight_amt`, `other_percent`, `other_amt`, `taxable_amt`, `cgst_rate`, `cgst_amt`, `sgst_rate`, `sgst_amt`, `igst_rate`, `igst_amt`, `landing_price`, `total_landing_price`) VALUES ('$entryId', 
		'$itemID_terrif_heading', '$itemID_code_partNo', '$p_qty', '$unitId_p', '$s_qty', '$unitId_s', '$expiry_date' ,     '$batch_no', '$basic_purchase_price', '$_total', '$__discount', '$_discounted_amt', '$_packing_percent',            '$_packing_amt', '$_insurance_percent', '$_insurance_amt', '$_freight_percent','$_freight_amt','$_other_percent',   '$_other_amt',  '$__taxable_total', '$_cgst_rate', '$_cgst_amt', '$_sgst_rate', '$_sgst_amt',  '$_igst_rate',       '$_igst_amt', '$_landing_price', '$_total_landing_price')";
       		
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    
     public static function Get_Incoming_Excise_invoice($codepart,$invno)
    {
        //$invno1=11;
        $Query = "select invd.* from incominginvoice_excise_detail as invd inner join incominginvoice_excise as inv on invd.entryId=inv.entryId where invd.entryId=$invno and invd.itemID_code_partNo=$codepart";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_entryDId = $Row['entryDId'];
            $_entry_Id = $Row['entryId'];
            $_itemID_terrif_heading = $Row['Tarrif_Heading'];
            $_item_code_part_no = $Row['Item_Code_Partno'];
            $_principal_qty = $Row['p_qty'];
            $_itemID_unitid = $Row['unitId_p'];
            $_itemID_unitname = $Row['UNITNAME'];
            $_supplier_qty = $Row['s_qty'];
            $_unit_name = $Row['unitId_s'];
            $_total_ass_value = $Row['tot_ass_val'];
            $_ed_percent = $Row['ed_percent'];
            $_ed_amount = $Row['ed_amt'];
            $_edu_cess_percent = $Row['edu_percent'];
            $_edu_amt = $Row['edu_amt'];
            $_hedu_percent = $Row['hedu_percent'];
            $_hedu_amount = $Row['hedu_amount'];
            $_cvd_percent = $Row['cvd_percent'];
            $_cvd_amount = $Row['cvd_amt'];
            if($Row['expiry_date'] == "0000-00-00")
             {
			 	$_expire_date1 = "";
			 }
			 else
			 {
			 	$_expire_date = $Row['expiry_date'];
			 	$_expire_date1=MultiweldParameter::xFormatDate1($_expire_date);
			 }
            $_batch_number = $Row['batch_no'];
            $_supplier_rg23d = $Row['supplr_RG23D'];
            $_basic_purchase_price = $Row['basic_purchase_price'];
            $_ed_per_Unit = $Row['ed_perUnit'];
            $_itemID_descp = $Row['Item_Desc'];
            $_unit_ass_value = self::GetUnitAssValue($Row['p_qty'],$Row['s_qty'],$Row['tot_ass_val']);
            $_ed_unit = self::CaseA_Get_ED_Unit($Row['p_qty'],$Row['s_qty'],$Row['ed_amt']);
            $_edu_cess_amount = self::CaseC_EDU_Cess_Amount($Row['ed_amt'],$Row['edu_percent']);

            $_landing_price = $Row['landing_price'];
            $_total_landing_price = $Row['total_landing_price'];

            $newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty, $_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent,$_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d,$_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price,$_total_landing_price,$mappingid);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
    }
	
	 public static function getIncomingInvoiceDetailsById($entryDid)
    {       
        $Query = "select invd.*,inv.principal_inv_date,inv.principal_inv_no, inv.supplier_inv_no,inv.supplier_inv_date,un.UNITNAME from incominginvoice_excise_detail as invd inner join incominginvoice_excise as inv on invd.entryId=inv.entryId INNER JOIN unit_master as un ON un.UnitId = invd.unitId_p where invd.entryDId=$entryDid";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_entryDId = $Row['entryDId'];
            $_entry_Id = $Row['entryId'];
			$_item_id = $Row['itemID_code_partNo'];
            $_itemID_terrif_heading = $Row['Tarrif_Heading'];
            $_item_code_part_no = $Row['Item_Code_Partno'];
            $_principal_qty = $Row['p_qty'];
            $_itemID_unitid = $Row['unitId_p'];
            $_itemID_unitname = $Row['UNITNAME'];
            $_supplier_qty = $Row['s_qty'];
            $_unit_name = $Row['unitId_s'];
            $_total_ass_value = $Row['tot_ass_val'];
            $_ed_percent = $Row['ed_percent'];
            $_ed_amount = $Row['ed_amt'];
            $_edu_cess_percent = $Row['edu_percent'];
            $_edu_amt = $Row['edu_amt'];
            $_hedu_percent = $Row['hedu_percent'];
            $_hedu_amount = $Row['hedu_amount'];
            $_cvd_percent = $Row['cvd_percent'];
            $_cvd_amount = $Row['cvd_amt'];
			$principal_inv_date = $Row['principal_inv_date'];
			
            if($Row['expiry_date'] == "0000-00-00")
             {
			 	$_expire_date1 = "";
			 }
			 else
			 {
			 	$_expire_date = $Row['expiry_date'];
			 	$_expire_date1=MultiweldParameter::xFormatDate1($_expire_date);
			 }
            $_batch_number = $Row['batch_no'];
            $_supplier_rg23d = $Row['supplr_RG23D'];
            $_basic_purchase_price = $Row['basic_purchase_price'];
            $_ed_per_Unit = $Row['ed_perUnit'];
            $_itemID_descp = $Row['Item_Desc'];
            $_unit_ass_value = self::GetUnitAssValue($Row['p_qty'],$Row['s_qty'],$Row['tot_ass_val']);
            $_ed_unit = self::CaseA_Get_ED_Unit($Row['p_qty'],$Row['s_qty'],$Row['ed_amt']);
            $_edu_cess_amount = self::CaseC_EDU_Cess_Amount($Row['ed_amt'],$Row['edu_percent']);

            $_landing_price = $Row['landing_price'];
            $_total_landing_price = $Row['total_landing_price'];
            $supplier_inv_no = $Row['supplier_inv_no'];
            $supplier_inv_date = $Row['supplier_inv_date'];
            $principal_inv_no = $Row['principal_inv_no'];

            $newObj = new Incoming_Invoice_Excise_Model_Details($_entryDId,$_entry_Id,$_itemID_terrif_heading,$_item_id,$_item_code_part_no,$_principal_qty, $_itemID_unitid,$_itemID_unitname,$_supplier_qty,$_unit_name,$_total_ass_value,$_ed_percent,$_ed_amount,$_edu_cess_percent,$_edu_amt,$_hedu_percent,$_hedu_amount,$_cvd_percent,$_cvd_amount,$_expire_date1,$_batch_number,$_supplier_rg23d,$_basic_purchase_price,$_ed_per_Unit,$_itemID_descp,$_unit_ass_value,$_ed_unit,$_edu_cess_amount,$_landing_price,$_total_landing_price,$mappingid,$principal_inv_date);
            $newObj->supplier_inv_no = $supplier_inv_no;
            $newObj->supplier_inv_date = $supplier_inv_date;
            $newObj->principal_inv_no = $principal_inv_no;
           
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
    }
}

/* BOF to show GST rates by Ayush Giri on 21-06-2017 */
class Incoming_Invoice_GST_Model
{
	public $_principal_supplier_id;
    public $_principal_supplier_name;
    public $_address;
    public $_city;
    public $_state;
    public $_city_id;
    public $_state_id;
	public $_gstin;
    
	
	public function __construct($principal_supplier_id, $principal_supplier_name, $address, $city, $state, $city_id, $state_id, $gstin)
	{
		$this->_principal_supplier_id = $principal_supplier_id;
		$this->_principal_supplier_name = $principal_supplier_name;
        $this->_address = $address;
        $this->_city = $city;
        $this->_state = $state;
		$this->_city_id = $city_id;
        $this->_state_id = $state_id;
		$this->_gstin = $gstin;
	}
	
	public static function  Load_Principal_Supplier_Incoming_GST($Principal_supplier_id,$Principal_supplier_type)
	{
		$result = '';
		if($Principal_supplier_type == "P")
		{
			$result = self::Get_Principal_List($Principal_supplier_id);
		}
		else if($Principal_supplier_type == "S")
		{
			$result = self::Get_Supplier_List($Principal_supplier_id);
		}
		
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) 
		{
			$_principal_supplier_id = $Row['Principal_Supplier_Id'];
            $_principal_supplier_name = $Row['Principal_Supplier_Name'];
			$_address = $Row['ADDRESS'];
            $_city = $Row['CITY'];
            $_state = $Row['STATE'];
            $_city_id = $Row['CITY_ID'];
            $_state_id = $Row['STATE_ID'];
			$_gstin = $Row['GSTIN'];
			
			$newObj = new Incoming_Invoice_GST_Model($_principal_supplier_id, $_principal_supplier_name, $_address, $_city, $_state, $_city_id, $_state_id, $_gstin);
			
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	public static function Get_Supplier_List($Principal_supplier_id)
	{
		$Query = "SELECT psm.Principal_Supplier_Id, psm.Principal_Supplier_Name, CONCAT(psm.ADD1,' ',psm.ADD2) AS ADDRESS, cm.CityName AS CITY, cm.CityId AS CITY_ID, sm.StateId AS STATE_ID, sm.StateName AS STATE, psgd.gst_no AS GSTIN FROM principal_supplier_master psm INNER JOIN city_master cm ON psm.CITYID = cm.CityId INNER JOIN state_master sm ON psm.STATEID = sm.StateId INNER JOIN principal_supplier_gst_details psgd ON psgd.principal_supplier_id = psm.Principal_Supplier_Id WHERE psm.TYPE = 'S' AND psm.Principal_Supplier_Id = ".$Principal_supplier_id;
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
	public static function Get_Principal_List($Principal_supplier_id)
	{
		$Query = "SELECT psm.Principal_Supplier_Id, psm.Principal_Supplier_Name, CONCAT(psm.ADD1,' ',psm.ADD2) AS ADDRESS, cm.CityName AS CITY, cm.CityId AS CITY_ID, sm.StateId AS STATE_ID, sm.StateName AS STATE, psgd.gst_no AS GSTIN FROM principal_supplier_master psm INNER JOIN city_master cm ON psm.CITYID = cm.CityId INNER JOIN state_master sm ON psm.STATEID = sm.StateId INNER JOIN principal_supplier_gst_details psgd ON psgd.principal_supplier_id = psm.Principal_Supplier_Id WHERE psm.TYPE = 'P' AND psm.Principal_Supplier_Id = ".$Principal_supplier_id;
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
   
   public static function GetItemGST($itemId, $principal_id = null , $supplier_ID = null, $buyer_id = null)
   {	
		$CompanyInfo = ParamModel::GetCompanyInfo();
		
	   if(!empty($buyer_id)){
		   //$Query = "SELECT StateId as state_id FROM buyer_master WHERE BuyerId = '".$buyer_id."'";
		   $Query = "SELECT bm.StateId as state_id, bgd.gst_no AS GSTIN FROM buyer_master bm JOIN buyer_gst_details bgd ON bgd.buyer_id = bm.BuyerId AND bm.StateId = bgd.gst_state_id WHERE bm.BuyerId = '".$buyer_id."'";
	   }elseif(!empty($supplier_ID)){
		   //$Query = "SELECT STATEID as state_id FROM principal_supplier_master WHERE Principal_Supplier_Id = '".$supplier_ID."'";
		   $Query = "SELECT psm.STATEID as state_id, psgd.gst_no AS GSTIN FROM principal_supplier_master psm  JOIN principal_supplier_gst_details psgd ON psm.Principal_Supplier_Id = psgd.principal_supplier_id AND psm.STATEID = psgd.gst_state_id WHERE psm.Principal_Supplier_Id = '".$supplier_ID."'";
	   }elseif(!empty($principal_id)){
		   //$Query = "SELECT STATEID as state_id FROM principal_supplier_master WHERE Principal_Supplier_Id = '".$principal_id."'";
		   $Query = "SELECT psm.STATEID as state_id, psgd.gst_no AS GSTIN FROM principal_supplier_master psm  JOIN principal_supplier_gst_details psgd ON psm.Principal_Supplier_Id = psgd.principal_supplier_id AND psm.STATEID = psgd.gst_state_id WHERE psm.Principal_Supplier_Id = '".$principal_id."'";
	   }else{
		   echo 'error';
		   exit;
	   }
	   
	   $Result = DBConnection::SelectQuery($Query);
	   $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	   $state_id = isset($Row['state_id'])?$Row['state_id']:0;
	   $gstin = isset($Row['GSTIN'])?$Row['GSTIN']:0;
	   
	   if($CompanyInfo['gstin_number'] == $gstin)
	   {
		   $Query = "SELECT hm.hsn_code AS HSN_CODE, '0' AS GST_RATE, '0' AS CGST_RATE, '0' AS SGST_RATE, '0' AS IGST_RATE  FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.ItemId = '".mysql_escape_string(trim($itemId))."'";
	   }
	   else
	   {
		   if($state_id == CURRENT_BRANCH_STATE_ID){
			   $tax_Type = 'S';
		   }else{
			   $tax_Type = 'I';
		   }
		   if($tax_Type == 'S')
			{
				$Query = "SELECT hm.hsn_code AS HSN_CODE, tm.tax_rate AS GST_RATE, (tm.tax_rate/2) AS CGST_RATE, (tm.tax_rate/2) AS SGST_RATE, '0' AS IGST_RATE  FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.ItemId = '".mysql_escape_string(trim($itemId))."'";
			}
			else
			{
				$Query = "SELECT hm.hsn_code AS HSN_CODE, tm.tax_rate AS GST_RATE, '0' AS CGST_RATE, '0' AS SGST_RATE, tm.tax_rate AS IGST_RATE FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.ItemId = '".mysql_escape_string(trim($itemId))."'";
			}
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
}
/* EOF to show GST rates by Ayush Giri on 21-06-2017 */

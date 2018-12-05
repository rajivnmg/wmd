<?php

class IncomingInvoiceNonExcise_Model
{
    public $_incominginvoiceid;
    public $_incoming_inv_no_p;
    public $_principalID;
    public $_principal_inv_date;
   // public $_principal_inv_date;
    public $_incoming_inv_no_s;
    public $_supplrId;
    public $_supplr_inv_date;
    public $_pf_chrg;
    public $_insurance;
    public $_freight;
    public $_saletax;
    public $SaleTaxAmount;
    public $_tot_bill_val;
    public $_rece_date;
    public $_remarks;
    public $_principalname;
    public $_suppliername;
    public $_items=array();
	public $ms;
    public function __construct($incominginvoiceid,$incoming_inv_no_p,$principalID,$principal_inv_date,$incoming_inv_no_s,$supplrId,$supplr_inv_date,$pf_chrg,$insurance,$freight,$saletax,$tot_bill_val,$rece_date,$remarks,$principalname,$suppliername, $items,$SaleTaxAmount,$ms)
    {
   $this->_incominginvoiceid=$incominginvoiceid;
   $this->_incoming_inv_no_p=$incoming_inv_no_p;
   $this->_principalID=$principalID;
   $this->_principal_inv_date=$principal_inv_date;
   $this->_incoming_inv_no_s=$incoming_inv_no_s;
   $this->_supplrId=$supplrId;
   $this->_supplr_inv_date=$supplr_inv_date;
   $this->_pf_chrg=$pf_chrg;
   $this->_insurance=$insurance;
   $this->_freight=$freight;
   $this->_saletax=$saletax;
   $this->SaleTaxAmount=$SaleTaxAmount;
   $this->_tot_bill_val=$tot_bill_val;
   $this->_rece_date=$rece_date;
   $this->_remarks=$remarks;
   $this->_principalname=$principalname;
   $this->_suppliername=$suppliername;
   $this->_items=$items;
   $this->ms = $ms;

    }

    public static function GET_WIN_NUM_BY_DISPLAY($DISPLAYID,$YEAR){
        $Query = "select inner_incomingInvoiceWe from incominginvoice_we_mapping where display_incomingInvoiceWe = $DISPLAYID AND finyear = '".$YEAR."'";
		
		
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["inner_incomingInvoiceWe"] > 0)
        {
            return $Row["inner_incomingInvoiceWe"];
        }
        else
            return 0;
    }
    public static function Create_DISPLAYID(){
        $Query = "select (IFNULL(MAX(display_incomingInvoiceWe),0)+1)display_incomingInvoiceWe  FROM incominginvoice_we_mapping WHERE finyear='".MultiweldParameter::GetFinancialYear_fromTXT()."'";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["display_incomingInvoiceWe"] > 0)
        {
            return $Row["display_incomingInvoiceWe"];
        }
    }
    public static function INSERT_DISPLAY_ENTRY_MAPING($INEID){
        $currentyear = MultiweldParameter::GetFinancialYear_fromTXT();
        $Query = "insert into incominginvoice_we_mapping (inner_incomingInvoiceWe,display_incomingInvoiceWe, finyear) values (".$INEID.",".self::Create_DISPLAYID().",'".$currentyear."')";
		$Result = DBConnection::InsertQuery($Query);
    }
    public static function DELETE_DISPLAY_ENTRY_MAPING($INEID){
        $Query = "delete from incominginvoice_we_mapping where inner_incomingInvoiceWe = $INEID";
        $Result = DBConnection::InsertQuery($Query);
    }
    public static function LoadAll($IncomingInvoiceNonExciseId,$start=null,$rp=null)
    {
        if($IncomingInvoiceNonExciseId==0)
        {
           $result= self::getAllIncomingInvoice($start,$rp);
        }
        else if($IncomingInvoiceNonExciseId>0)
        {
            $result=self::getIncomingInvoice($IncomingInvoiceNonExciseId);
        }
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		$_incominginvoiceid=$Row['incominginvoice_we'];
            $_incoming_inv_no_p=$Row['incoming_inv_no_p'];
            $_principalID=$Row['principalID'];
            $_principal_inv_date=$Row['principal_inv_date'];
            $principal_inv_date1 = MultiweldParameter::xFormatDate1($_principal_inv_date);
            $_incoming_inv_no_s=$Row['incoming_inv_no_s'];
            if($_incoming_inv_no_s=='NULL'){
                $_incoming_inv_no_s="";
            }
            $_supplrId=$Row['supplrId'];
            $_supplr_inv_date=$Row['supplr_inv_date'];
            if($_supplr_inv_date=='0000-00-00'){
                $supplr_inv_date1="";
            }else{
             $supplr_inv_date1 = MultiweldParameter::xFormatDate1($_supplr_inv_date);
            }
            $_pf_chrg = $Row['pf_chrg'];
            $_insurance = $Row['insurance'];
            $_freight = $Row['freight'];
            $_saletax = $Row['saletax'];
            $SaleTaxAmount = $Row['SaleTaxAmt'];
            $_tot_bill_val = $Row['tot_bill_val'];
            $_rece_date = $Row['rece_date'];
            $_rece_date1 = MultiweldParameter::xFormatDate1($_rece_date);
            $_remarks = $Row['remarks'];
            $_principalname = $Row['principalname'];
            $_suppliername = $Row['suppliername'];
			$ms = $Row['msid']; 
            if($IncomingInvoiceNonExciseId > 0)
            {
               $items=IncomingInvoiceNonExciseDetails_Model::pageLoad($IncomingInvoiceNonExciseId);
            }
            else
            {
               $items = null;
            }
            $newObj = new IncomingInvoiceNonExcise_Model
($_incominginvoiceid,$_incoming_inv_no_p,$_principalID,$principal_inv_date1,$_incoming_inv_no_s,$_supplrId,$supplr_inv_date1,$_pf_chrg,$_insurance,$_freight,$_saletax,$_tot_bill_val,$_rece_date1,$_remarks,$_principalname,$_suppliername,$items,$SaleTaxAmount,$ms);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
    }
    public static function SearchIncomingInvoiceNonExcise($year,$value1,$value2,$value3,$value4,$start,$rp,&$count){
        $Query = "";
        $CountQuery = "";
        /*
        switch($CoulamName)
        {
            case "Principal":
                $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principlename,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE psw.Principal_Supplier_Id = $value1 LIMIT $start , $rp";
                $CountQuery = "SELECT count(*) as total FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE psw.Principal_Supplier_Id = $value1";
                break;
            case "PrincipalDate":
                $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principlename,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE iwe.principal_inv_date BETWEEN '$value1' AND '$value2' LIMIT $start , $rp";
                $CountQuery = "SELECT count(*) as total FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE iwe.principal_inv_date BETWEEN '$value1' AND '$value2'";
                break;
            case "Principal_WITH_PrincipalDATE":
                $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principlename,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE psw.Principal_Supplier_Id = $value3 AND iwe.principal_inv_date BETWEEN '$value1' AND '$value2' LIMIT $start , $rp";
                $CountQuery = "SELECT count(*) as total FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE psw.Principal_Supplier_Id = $value3 AND iwe.principal_inv_date BETWEEN '$value1' AND '$value2'";
                break;
            case "InvoiceNumber":
                $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principlename,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id WHERE iwe.incoming_inv_no_p = '$value1' OR iwe.incoming_inv_no_s = '$value1'";
                $CountQuery = "SELECT count(*) as total FROM incominginvoice_without_excise WHERE  incoming_inv_no_p = '$value1' OR incoming_inv_no_s = '$value1'";
                break;
            default:
                return;
                break;
        }
        */
        $Query = "SELECT iwe.*,iwem.display_incomingInvoiceWe,psw.Principal_Supplier_Name as principlename,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe ";
        $Query =$Query. " LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id ";
        $Query =$Query. " INNER JOIN incominginvoice_we_mapping AS iwem ON iwe.incominginvoice_we=iwem.inner_incomingInvoiceWe ";
        $Query =$Query. " WHERE  iwem.finyear='".$year."'";

        if($value1!=""){
           $Query =$Query. " and (iwe.incoming_inv_no_p = '$value1' OR iwe.incoming_inv_no_s = '$value1')";
        }
        if($value2!=""){
           $Query =$Query. " and iwe.principal_inv_date >= '$value2'";
        }
        if($value3!=""){
           $Query =$Query. " and iwe.principal_inv_date <= '$value3'";
        }
        if($value4!=""){
           $Query =$Query. " and iwe.principalID = $value4";
        }
        $Query =$Query. " and iwe.remarks NOT LIKE '@Delete By%'";
        $Query =$Query." order by rece_date desc LIMIT $start , $rp";
        
        
         
        $CountQuery = "SELECT count(*) as total FROM incominginvoice_without_excise as iwe ";
        $CountQuery =$CountQuery. " LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id ";
        $CountQuery =$CountQuery. " INNER JOIN incominginvoice_we_mapping AS iwem ON iwe.incominginvoice_we=iwem.inner_incomingInvoiceWe ";
        $CountQuery =$CountQuery. " WHERE  iwem.finyear='".$year."'";

        if($value1!=""){
           $CountQuery =$CountQuery. " and (iwe.incoming_inv_no_p = '$value1' OR iwe.incoming_inv_no_s = '$value1')";
        }
        if($value2!=""){
           $CountQuery =$CountQuery. " and iwe.principal_inv_date >= '$value2'";
        }
        if($value3!=""){
           $CountQuery =$CountQuery. " and iwe.principal_inv_date <= '$value3'";
        }
        if($value4!=""){
           $CountQuery =$CountQuery. " and iwe.principalID = $value4";
        }
        $CountQuery =$CountQuery. " and iwe.remarks NOT LIKE '@Delete By%'";
      
        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];
		//print_r($Query); exit;
        $result = DBConnection::SelectQuery($Query);
        $objArray = array();
	$i = 0;

            while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_incominginvoiceid=$Row['display_incomingInvoiceWe'];
            $_incoming_inv_no_p=$Row['incoming_inv_no_p'];
            //$_principalID=$Row['principalID'];
            $_principal_inv_date=$Row['principal_inv_date'];
            $principal_inv_date1 = MultiweldParameter::xFormatDate1($_principal_inv_date);
            /* $_incoming_inv_no_s=$Row['incoming_inv_no_s'];
            if($_incoming_inv_no_s=='NULL'){
                $_incoming_inv_no_s="";
            }
            $_supplrId=$Row['supplrId'];
            $_supplr_inv_date=$Row['supplr_inv_date'];
            if($supplr_inv_date=='0000-00-00'){
                $supplr_inv_date1="";
            }else{
             $supplr_inv_date1 = MultiweldParameter::xFormatDate1($_supplr_inv_date);
            }
            $_pf_chrg=$Row['pf_chrg'];
            $_insurance=$Row['insurance'];
            $_freight=$Row['freight'];
            $_saletax=$Row['saletax'];*/
            $SaleTaxAmount = $Row['SaleTaxAmt'];
            $_tot_bill_val=$Row['tot_bill_val'];
            $_rece_date=$Row['rece_date'];
            $_rece_date1 = MultiweldParameter::xFormatDate1($_rece_date);
            $_remarks=$Row['remarks'];
            $_principalname=$Row['principlename'];
            $_suppliername=$Row['suppliername'];
            $items = null;
            $newObj = new IncomingInvoiceNonExcise_Model
($_incominginvoiceid,$_incoming_inv_no_p,$_principalID,$principal_inv_date1,$_incoming_inv_no_s,$_supplrId,$supplr_inv_date1,$_pf_chrg,$_insurance,$_freight,$_saletax,$_tot_bill_val,$_rece_date1,$_remarks,$_principalname,$_suppliername,$items,$SaleTaxAmount,$ms);
            $objArray[$i] = $newObj;
            $i++;

		}
		return $objArray;
	}
    public static function InsertIncomingInvoiceNonExcise($incoming_inv_no_p,$principalID,$principal_inv_date,$incoming_inv_no_s,$supplrId,$supplr_inv_date,$pf_chrg,$insurance,$freight,$saletax,$SaleTaxAmt,$tot_bill_val,$rece_date,$remarks,$ms)
   {

        session_start();
        $USERID=$_SESSION["USER"];
        $date = date("y-m-d");
        if($incoming_inv_no_s==NULL){
			$incoming_inv_no_s='NULL';
		}
        if($supplrId==NULL){
			$supplrId='NULL';
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
        if($saletax==NULL ||$saletax==""){
			$saletax=0;
		}
		if($SaleTaxAmt=='' ||$SaleTaxAmt==NULL)
		{
			$SaleTaxAmt=0.00;
		}
        $principal_inv_date1= MultiweldParameter::xFormatDate($principal_inv_date);
	    if($supplr_inv_date==NULL){
			$supplr_inv_date1='0000-00-00';
		}else{
			$supplr_inv_date1= MultiweldParameter::xFormatDate($supplr_inv_date);
		}
        //echo $rece_date."##";
        $rece_date1= MultiweldParameter::xFormatDate($rece_date);
        //echo $rece_date1;
        
        // added on 02-JUNE-2016 due to Handle Special Character
        $incoming_inv_no_p = mysql_escape_string($incoming_inv_no_p);
        $incoming_inv_no_s = mysql_escape_string($incoming_inv_no_s);
        $remarks = mysql_escape_string($remarks);
                        
       $Query = "INSERT INTO incominginvoice_without_excise (incoming_inv_no_p, principalID, principal_inv_date, incoming_inv_no_s, supplrId, supplr_inv_date, pf_chrg, insurance, freight, saletax,SaleTaxAmt, tot_bill_val, rece_date, msid, remarks, userId, insertDate) VALUES ('".$incoming_inv_no_p."',$principalID,'".$principal_inv_date1."','".$incoming_inv_no_s."',$supplrId,'".$supplr_inv_date1."',$pf_chrg,$insurance,$freight,$saletax,$SaleTaxAmt,$tot_bill_val, '$rece_date1','$ms', '$remarks', '$USERID','$date')";
       $Result = DBConnection::InsertQuery($Query);

       if($Result > 0){
           return $Result;
       }
       else{
           return QueryResponse::NO;
       }
   }
   //###################3
     public static function validateInvoice($principalID,$invNo)
    {
	   $Query="SELECT COUNT(*) AS cnt FROM incominginvoice_without_excise WHERE incoming_inv_no_p='$invNo' AND principalID='$principalID'";
	   $Result = DBConnection::SelectQuery($Query);
	   $row1=mysql_fetch_array($Result, MYSQL_ASSOC);
	    $total=$row1['cnt'];
		return $total;
	}
	   //#######################################
    public static function CancelIncomingInvoiceNonExcise($invoiceid)
    {
  	  $Query="UPDATE incominginvoice_without_excise SET cancelled_status= '1'";
  	  $Query.="WHERE incominginvoice_we=$invoiceid";
      $Result = DBConnection::UpdateQuery($Query);
      if($Result =="SUCCESS"){
            return TRUE;
      }
      else{
            return FALSE;
      }
    }
   
   //###################################
   
   //####################
    public static function UpdateIncomingInvoiceNonExcise($invoiceid,$incoming_inv_no_p,$principalID,$principal_inv_date,$incoming_inv_no_s,$supplrId,$supplr_inv_date,$pf_chrg,$insurance,$freight,$saletax,$SaleTaxAmt,$tot_bill_val,$rece_date,$remarks,$ms)
    {
        session_start();
        $USERID=$_SESSION["USER"];
        //$date = date("d-m-Y");
        if($incoming_inv_no_s==NULL){
			$incoming_inv_no_s='NULL';
		}
        if($supplrId==NULL){
			$supplrId='NULL';
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
        if($saletax==NULL ||$saletax==""){
			$saletax=0;
		}
		if($SaleTaxAmt=='' ||$SaleTaxAmt==NULL)
		{
			$SaleTaxAmt=0.00;
		}
        $principal_inv_date1= MultiweldParameter::xFormatDate($principal_inv_date);
	    if($supplr_inv_date==NULL){
			$supplr_inv_date1='0000-00-00';
		}else{
			$supplr_inv_date1= MultiweldParameter::xFormatDate($supplr_inv_date);
		}
        //echo $rece_date."##";
        $rece_date1= MultiweldParameter::xFormatDate($rece_date);
		
		//added on 02-JUNE-2016 due to Handle Special Character
        $incoming_inv_no_p = mysql_escape_string($incoming_inv_no_p);
        $incoming_inv_no_s = mysql_escape_string($incoming_inv_no_s);
        $remarks = mysql_escape_string($remarks);
		
		$Query="UPDATE incominginvoice_without_excise SET incoming_inv_no_p = '$incoming_inv_no_p' ,";
		$Query.="principalID = $principalID,principal_inv_date = '$principal_inv_date1', incoming_inv_no_s = '$incoming_inv_no_s',";
		$Query.="supplrId = $supplrId,supplr_inv_date = '$supplr_inv_date1', pf_chrg = $pf_chrg, insurance = $insurance,";
		$Query.="freight = $freight,saletax = $saletax, SaleTaxAmt = $SaleTaxAmt, tot_bill_val = $tot_bill_val, ";
		$Query.="rece_date = '$rece_date1', msid='$ms', remarks = '$remarks',userId = '$USERID'";
		$Query.="WHERE incominginvoice_we=$invoiceid";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result =="SUCCESS"){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function getIncomingInvoice($IncomingInvoiceNonExciseId)
    {
        $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principalname,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id where incominginvoice_we=$IncomingInvoiceNonExciseId";
		//echo $Query;
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
    }
    public static function getAllIncomingInvoice($start,$rp)
    {
        $Query = "SELECT iwe.*,psw.Principal_Supplier_Name as principalname,psws.Principal_Supplier_Name as suppliername FROM incominginvoice_without_excise as iwe LEFT join principal_supplier_master as psw on iwe.principalID=psw.Principal_Supplier_Id left join principal_supplier_master as psws on iwe.supplrId=psws.Principal_Supplier_Id LIMIT $start , $rp";
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
    }
     public static function FIND_INVOIC_IN_OGINV($INVOICENO){
        $Query = "select count(*) as num from purchaseorder_detail where po_quotNo = '$INVOICENO'";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["num"];
    }
    public static function DeleteInvoiceNonExcise($InvoiceId){
        $Query = "delete from incominginvoice_without_excise where incominginvoice_we = $InvoiceId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
}

class IncomingInvoiceNonExciseDetails_Model
{
    public $_entryDId;
    public $_incominginvoice_we;
    public $_item_id;
    public $_qty;
    public $_rate;
    public $_exp_date;
    public $_batch_no;
    public $_item_descp;
    public $_landing_price;
    public $_total_landing_price;
    public $_partno_1;
    public $_amount;

    public function __construct($_entryDId,$incominginvoice_we,$itemID_code_partNo,$qty,$rate,$expiry_date,$batch_no,$item_descp,$_landing_price,
    $_total_landing_price,$_partno_1,$_amount)
    {
        $this->_entryDId = $_entryDId;
        $this->_incominginvoice_we=$incominginvoice_we;
        $this->_item_id=$itemID_code_partNo;
        $this->_qty=$qty;
        $this->_rate=$rate;
        $this->_exp_date=$expiry_date;
        $this->_batch_no=$batch_no;
        $this->_item_descp=$item_descp;
        $this->_landing_price = $_landing_price;
        $this->_total_landing_price = $_total_landing_price;
        $this->_partno_1 = $_partno_1;
        $this->_amount = $_amount;
    }

    	public static function GetIncomingInvoiceNonExciseInfo($ID)
		{
			$Query = "SELECT itemID_code_partNo,qty FROM incominginvoice_without_excise_detail WHERE  incominginvoice_we=$ID";
			
			$RESULT = DBCONNECTION::SELECTQUERY($Query);
			$OBJARRAY = ARRAY();
			$I = 0;
			WHILE ($ROW = MYSQL_FETCH_ARRAY($RESULT, MYSQL_ASSOC)) {
			 $OBJARRAY[$I]['codePartNo']=$ROW['itemID_code_partNo'];
			 $OBJARRAY[$I]['qty']=$ROW['qty'];
			 $I++;
			}
			RETURN $OBJARRAY;

        }


    public static function InsertIncomingInvoiceNonExciseDetails($incominginvoice_we,$itemID_code_partNo,$qty,$rate,$expiry_date,$batch_no,$_landing_price,$_total_landing_price)
    {
        if($batch_no==NULL){
			$batch_no='';
		}
	    if($expiry_date==NULL){
			$expiry_date1='0000-00-00';
		}else{
			$expiry_date1=MultiweldParameter::xFormatDate($expiry_date);
		}
		
		//added on 02-JUNE-2016 due to Handle Special Character
        $itemID_code_partNo = mysql_escape_string($itemID_code_partNo);
        $batch_no = mysql_escape_string($batch_no);      
		
        $Query = "INSERT INTO incominginvoice_without_excise_detail(incominginvoice_we, itemID_code_partNo, qty, rate, expiry_date, batch_no,landing_price,total_landing_price) VALUES ($incominginvoice_we,$itemID_code_partNo,$qty,$rate,'$expiry_date1','$batch_no',$_landing_price,$_total_landing_price)";
		     
       $Result = DBConnection::InsertQuery($Query);
       if($Result > 0){
           return $Result;
       }
       else{
           return QueryResponse::NO;
       }

    }

    public static function pageLoad($incominginvoice_we)
    {
        if($incominginvoice_we>0)
        {
           $result =self::getInvoiceDetais($incominginvoice_we);
        }
        else
        {
            $result =self::getAllInvoiceDetais();
        }
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
           
    		$incominginvoice_we=$Row['incominginvoice_we'];
            $_item_id=$Row['itemID_code_partNo'];
            $qty=$Row['qty'];
            $rate=$Row['rate'];
            $expiry_date=$Row['expiry_date'];
            //echo $expiry_date;
            if($expiry_date=='0000-00-00'){
				$expiry_date1="";
			}else{
			 $expiry_date1 = MultiweldParameter::xFormatDate1($expiry_date);
			}
             //echo $expiry_date1;
            if($Row['batch_no'] == 'NULL')
            {
				$batch_no = "";
			}
            else
            {
				$batch_no=$Row['batch_no'];
			}
            $item_descp=$Row['item_descp'];
            $_landing_price = $Row['landing_price'];
            $_total_landing_price = $Row['total_landing_price'];
            $_partno_1 = $Row['codepart'];
            $_amount = $qty*$rate;
            $newObj = new IncomingInvoiceNonExciseDetails_Model($_entryDId=null,$incominginvoice_we,$_item_id,$qty,$rate,$expiry_date1,$batch_no,$item_descp,$_landing_price, $_total_landing_price,$_partno_1,$_amount);
            $objArray[$i] = $newObj;
            $i++;

		}
		return $objArray;
     }
     public static function DeleteInvoiceNonExciseDetails($InvoiceId){
        $Query = "delete from incominginvoice_without_excise_detail where incominginvoice_we = $InvoiceId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
public static function getInvoiceDetais($incominginvoice_we)
{
    $Query = "SELECT inc.*,it.Item_Desc as item_descp,it.Item_Code_Partno as codepart FROM incominginvoice_without_excise_detail as inc left join item_master as it on inc.itemID_code_partNo=it.ITEMID WHERE incominginvoice_we=$incominginvoice_we";
    $Result = DBConnection::SelectQuery($Query);;
    return $Result;

}
public static function getAllInvoiceDetais()
{
    $Query = "SELECT * FROM incominginvoice_without_excise_detail";
    $Result = DBConnection::SelectQuery($Query);;
    return $Result;
}
public static function DeleteIncomingInvoiceNonExcise($invoiceid)
{
    $Query = "DELETE FROM incominginvoice_without_excise_detail where incominginvoice_we=$invoiceid";
    $Result = DBConnection::DeleteQuery($Query);;
    return $Result;
}
	
public static function FIND_INVOIC_IN_OGINV($INVOICENO){
        $Query = "select count(*) as num from purchaseorder_detail where po_quotNo = '$INVOICENO'";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["num"];
    }

}

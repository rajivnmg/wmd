<?php 
date_default_timezone_set('Asia/Kolkata');
class Stocktransfer_Model
{
 public $_stId;
 public $_stInvNo;
 public $_stInvDate;
 public $_stBuyerId;
 public $_stPrincipalId;
 public $_stSupplrId;
 public $_mode_delivery;
 public $_st_time;
 public $_dispatch_time;
 public $_Supplier_stage;
 public $_discount;
 public $_Inclusive_Tag;
 public $_total_ed;
 public $_saleTax;
 public $_total_amt;
 public $_printType;
 public $_remarks;
 public $_userId;
 public $_insertDate;
 public $_items=array();

 public $_BuyerName;
 public $_PrincipalName;
 public $_SupplierName;

 public function __construct( $stId,$stInvNo,$stInvDate,$stBuyerId,$stPrincipalId,$stSupplrId,$modedelivery,$sttime, $dispatchtime,$Supplierstage,$discount,$Inclusive_Tag,$totaled,$saleTax,$totalamt,$printType, $remarks,$userId,$insertDate,$items,$_BuyerName,$_PrincipalName,$_SupplierName)
 {
 $this->_stId=$stId;
 $this->_stInvNo=$stInvNo;
 $this->_stInvDate=$stInvDate;
 $this->_stBuyerId=$stBuyerId;
 $this->_stPrincipalId=$stPrincipalId;
 $this->_stSupplrId=$stSupplrId;
 $this->_mode_delivery=$modedelivery;
 $this->_st_time=$sttime;
 $this->_dispatch_time=$dispatchtime;
 $this->_Supplier_stage=$Supplierstage;
 $this->_discount=$discount;
 $this->_Inclusive_Tag=$Inclusive_Tag;
 $this->_total_ed=$totaled;
 $this->_saleTax=$saleTax;
 $this->_total_amt=$totalamt;
 $this->_printType=$printType;
 $this->_remarks=$remarks;
 $this->_userId=$userId;
 $this->_insertDate=$insertDate;
 $this->_items=$items;

 $this->_BuyerName=$_BuyerName;
 $this->_PrincipalName=$_PrincipalName;
 $this->_SupplierName=$_SupplierName;
 }


    public static function getSystemDateTime(){
	        $Query ="SELECT DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i') sdatetime";
		    $Result = DBConnection::SelectQuery($Query);
		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
		 	$sysDateTime=$row['sdatetime'];
			return $sysDateTime;
    }
 	 public static function createNewStockTransferNumber($stocktransferStr){
    	  $Query = "SELECT stInvNo FROM stocktransfer where stInvNo like ('$stocktransferStr') ORDER BY stId DESC LIMIT 1";
    	 
    	  $Result = DBConnection::SelectQuery($Query);
    	  $row=mysql_fetch_array($Result, MYSQL_ASSOC);
    	  $stInvNo=$row['stInvNo'];
          $stPos = strrpos($stInvNo,"/");
    	  $str=(substr($stInvNo,3,($stPos-3)))+1;
    	  $newStockTransfer="ST-".$str."/".substr($stocktransferStr,1,5);
    	  return $newStockTransfer;
	 }
     public static function checkStockTransferNumber($stocktransferStr){
		    $Query = "SELECT COUNT(*)c FROM  stocktransfer AS st where st.stInvNo LIKE ('$stocktransferStr')";
			$Result = DBConnection::SelectQuery($Query);
			$row=mysql_fetch_array($Result, MYSQL_ASSOC);
		    $total=$row['c'];
		    return $total;
	 }
	 public static function GetLastTransferNumber(){
			$file = MultiweldParameter::GetFinancialYear_fromTXT();
			$data =trim($file);
			$vYear=strlen($data);
			$StockTransferStr=substr($data,2,2)."-".substr($data,7,2);
				$stocktransferStr="%".$StockTransferStr;
			$totStockTransferNumber=self::checkStockTransferNumber($stocktransferStr);
			if($totStockTransferNumber>0){
				   $getNewStockTransferNo=self::createNewStockTransferNumber($stocktransferStr);
			       return $getNewStockTransferNo;
			}else{
			 $firstStockTransferNumber =1;
			 return "ST-".$firstStockTransferNumber."/".$StockTransferStr;
			}
	  }


      public static function GET_ST_NUM_BY_DISPLAY($DISPLAYID,$YEAR){
          $Query = "SELECT inner_stId FROM stocktransfer_mapping WHERE display_stId=$DISPLAYID and finyear='$YEAR'";
        
          $Result = DBConnection::SelectQuery($Query);
          $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
          if($Row["inner_stId"] > 0)
          {
              return $Row["inner_stId"];
          }
          else
              return 0;
    }

  public static function GetLastInvoiceNumber(){
        $Query = "SELECT stId,stInvNo FROM stocktransfer ORDER BY insertDate DESC LIMIT 1";
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        $stId=$Row["stId"];
        $stInvNo=$Row["stInvNo"];
        if($stInvNo!=null){
			$stPos = strrpos($stInvNo,"/");
            $exit_finyear = substr($stInvNo,$stPos+1);
		}
        //echo "p-->".$exit_finyear."ankur";
        $FinancialYear = MultiweldParameter::GetFinancialYear_fromTXT();
        //echo "f***>".$FinancialYear."raj";
        if($stId == null  || $FinancialYear!=$exit_finyear){
			return "ST-1/".$FinancialYear;
		}else if($stId > 0 && $FinancialYear==$exit_finyear)
        {
            $id = $stId + 1;
            return "ST-".$id."/".$FinancialYear;
        }
        else {
			return null;
		}

    }
     public static function xFormatDate($dte){
		$dt   = new DateTime();
        $date = $dt->createFromFormat('d/m/Y', $dte);
	    return $date->format('Y-m-d');
	}
    public static function xFormatDate1($dte){
		$dt   = new DateTime();
        $date = $dt->createFromFormat('Y-m-d', $dte);
	    return $date->format('d/m/Y');
	}

 public static function InsertStockTransferMapping($display_stId,$stInvNo,$finyear){
	 
   $iSql="INSERT INTO stocktransfer_mapping(display_stId,stInvNo,finyear) VALUES ($display_stId,'$stInvNo','$finyear')";
   
   $Result = DBConnection::InsertQuery($iSql);
   
   if($Result > 0){
       return $Result;
    }
     else{
            return QueryResponse::NO;
     }
 }

 public static function InsertStockTransfer($stInvNo,$stInvDate,$stBuyerId,$stPrincipalId,$stSupplrId,$modedelivery,$sttime,$dispatchtime,$Supplierstage,$discount,$Inclusive_Tag,$totaled,$saleTax,$totalamt,$printType, $remarks)
 {		
     session_start();	 
	 $stInvNo = Stocktransfer_Model::GetLastTransferNumber();
     $finyear = MultiweldParameter::GetFinancialYear_fromTXT();
	 $finyear =trim($finyear);
     $finIntervalStr=substr($finyear,2,2)."-".substr($finyear,7,2);
     $checkStr="%".$finIntervalStr;
     $mappSql="SELECT (IFNULL(max(display_stId),0)+1) display_stId from stocktransfer_mapping where stInvNo like ('$checkStr')";
     $result = DBConnection::SelectQuery($mappSql);
    
     $row = mysql_fetch_array($result, MYSQL_ASSOC);
     $display_stId=$row["display_stId"];

     $innerStId=self::InsertStockTransferMapping($display_stId,$stInvNo,$finyear);

     if($stSupplrId==NULL){
		$stSupplrId='NULL';
   	 }
      if($Inclusive_Tag==NULL){
	 	 $Inclusive_Tag='N';
   	 }
   	 if($discount==NULL){
	 	 $discount=0.00;
   	 }
     $userId=$_SESSION["USER"];
     $insertDate=date("Y-m-d");
     $stInvDate1=self::xFormatDate($stInvDate);
	
	//added on 03-JUNE-2016 due to Handle Special Character
     $remarks = mysql_escape_string($remarks);
         
     $Query="INSERT INTO stocktransfer(stId,stInvNo, stInvDate, stBuyerId, stPrincipalId, stSupplrId, mode_delivery, st_time, dispatch_time, Supplier_stage, discount, inclusiveED,total_ed, saleTax, total_amt, printType, remarks, userId, insertDate) VALUES ($innerStId,'$stInvNo','$stInvDate1',99999999,$stPrincipalId,$stSupplrId,'$modedelivery','$sttime','$dispatchtime','$Supplierstage',$discount,'$Inclusive_Tag',$totaled,$saleTax,$totalamt,'$printType','$remarks','$userId','$insertDate')";

     $Result = DBConnection::InsertQuery($Query);
    
     if($Result > 0){
         return $Result;
     }
     else{
         return QueryResponse::NO;
     }
 }
 public static function LoadAll($StockTransferId)
 {
     if($StockTransferId==0)
     {
         $result= self::getAllStockTransfer();
     }
        else if($StockTransferId>0)
     {
         $result=self::getStockTransfer($StockTransferId);
     }


     $objArray = array();
     $i = 0;

     while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {

         $stId=$Row['stId'];
         $stInvNo=$Row['stInvNo'];
         $stInvDate=$Row['stInvDate'];
         $stInvDate1=self::xFormatDate1($stInvDate);
         $stBuyerId=$Row['stBuyerId'];
         $stPrincipalId=$Row['stPrincipalId'];
         $stSupplrId=$Row['stSupplrId'];
         $modedelivery=$Row['mode_delivery'];
         $sttime=$Row['st_time'];
         $dispatchtime=$Row['dispatch_time'];
         $Supplierstage=$Row['Supplier_stage'];
         $discount=$Row['discount'];
         $Inclusive_Tag=$Row['inclusiveED'];
         $totaled=$Row['total_ed'];

         $saleTax=$Row['saleTax'];
         $totalamt=$Row['total_amt'];
         $printType=$Row['printType'];
         $remarks=$Row['remarks'];
         $userId=$Row['userId'];
         $insertDate=$Row['insertDate'];

         $_BuyerName=$Row['BuyerName'];
         $_PrincipalName=$Row['princname'];
         $_SupplierName=$Row['suppliername'];

         $items=StocktransferDetails_Model::LoadAll($stId);
         $newObj = new Stocktransfer_Model
($stId,$stInvNo,$stInvDate1,$stBuyerId,$stPrincipalId,$stSupplrId,$modedelivery,$sttime,
 $dispatchtime,$Supplierstage,$discount,$Inclusive_Tag,$totaled,$saleTax,$totalamt,$printType, $remarks,$userId,$insertDate,$items,$_BuyerName,$_PrincipalName,$_SupplierName);
         $objArray[$i] = $newObj;
         $i++;

     }
     return $objArray;

 }
 public static function getAllStockTransfer()
 {
     $Query = "SELECT st.*,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppliername,bm.BuyerName FROM stocktransfer as st INNER JOIN principal_supplier_master as psm1 ON st.stPrincipalId = psm1.Principal_Supplier_Id LEFT JOIN principal_supplier_master as psm2 ON st.stSupplrId = psm2.Principal_Supplier_Id LEFT JOIN buyer_master as bm ON st.stBuyerId = bm.BuyerId";
     //echo $Query;
     $Result = DBConnection::SelectQuery($Query);;
     return $Result;
 }
 public static function getStockTransfer($StockTransferId)
 {
     $Query = "SELECT st.*,psm1.Principal_Supplier_Name as princname,psm2.Principal_Supplier_Name as suppliername,bm.BuyerName  FROM stocktransfer  as st INNER JOIN principal_supplier_master as psm1 ON st.stPrincipalId = psm1.Principal_Supplier_Id LEFT JOIN principal_supplier_master as psm2 ON st.stSupplrId = psm2.Principal_Supplier_Id  LEFT JOIN buyer_master as bm ON st.stBuyerId = bm.BuyerId where stId=$StockTransferId";
     $Result = DBConnection::SelectQuery($Query);
     return $Result;
 }

public static function countRec($year,$value1,$value2,$value3,$value4)
 {
          $CountQuery = "";

          $CountQuery = "SELECT count(*) as total FROM stocktransfer AS st ";
          $CountQuery =$CountQuery. "INNER JOIN principal_supplier_master as psm1 ON st.stPrincipalId = psm1.Principal_Supplier_Id  ";
          $CountQuery =$CountQuery. "LEFT JOIN principal_supplier_master as psm2 ON st.stSupplrId = psm2.Principal_Supplier_Id ";
          $CountQuery =$CountQuery. "INNER JOIN stocktransfer_mapping AS stm ON stm.inner_stId=st.stId  ";
          $CountQuery =$CountQuery. "WHERE  stm.finyear='$year'";

          if($value1!=""){
             $CountQuery =$CountQuery. " and stm.stInvNo  = '$value1' ";
          }
          if($value2!=""){
             $stFromDate=self::xFormatDate($value2);
             $CountQuery =$CountQuery. " and st.stInvDate >= '$stFromDate'";
          }
          if($value3!=""){
             $stToDate=self::xFormatDate($value3);
             $CountQuery =$CountQuery. " and st.stInvDate <= '$stToDate'";
          }
          if($value4!=""){
             $CountQuery =$CountQuery. " and st.stPrincipalId = $value4";
          }
//echo $CountQuery;
          $CountResult = DBConnection::SelectQuery($CountQuery);
          $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
          return $RowCount['total'];
 }



public static function SearchStockTransfer($year,$value1,$value2,$value3,$value4,$start,$rp,&$count){
          $Query = "";
          $CountQuery = "";

          $Query ="SELECT stm.inner_stId,stm.display_stId,st.*,psm1.Principal_Supplier_Name as princname,";
          $Query =$Query. "psm2.Principal_Supplier_Name as suppliername FROM stocktransfer as st ";
          $Query =$Query. "INNER JOIN principal_supplier_master as psm1 ON st.stPrincipalId = psm1.Principal_Supplier_Id  ";
          $Query =$Query. "LEFT JOIN principal_supplier_master as psm2 ON st.stSupplrId = psm2.Principal_Supplier_Id ";
          $Query =$Query. "INNER JOIN stocktransfer_mapping AS stm ON stm.inner_stId=st.stId  ";
          $Query =$Query. "WHERE  stm.finyear='$year'";
          if($value1!=""){
             $Query =$Query. " and st.stInvNo  = '$value1' ";
          }
          if($value2!=""){
             $stFromDate=self::xFormatDate($value2);
             $Query =$Query. " and st.stInvDate >= '$stFromDate'";
          }
          if($value3!=""){
             $stToDate=self::xFormatDate($value3);
             $Query =$Query. " and st.stInvDate <= '$stToDate'";
          }
          if($value4!=""){
             $Query =$Query. " and st.stPrincipalId = $value4";
          }

          $Query =$Query." ORDER BY stm.display_stId ASC LIMIT $start , $rp ";
          
          $result = DBConnection::SelectQuery($Query);
          $objArray = array();
  	$i = 0;

  	while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      
         $stId=$Row['display_stId'];
         $stInvNo=$Row['stInvNo'];
         $stInvDate=$Row['stInvDate'];
         $stInvDate1=self::xFormatDate1($stInvDate);
         $stBuyerId=$Row['stBuyerId'];
         $stPrincipalId=$Row['stPrincipalId'];
         $stSupplrId=$Row['stSupplrId'];
         $modedelivery=$Row['mode_delivery'];
         $sttime=$Row['st_time'];
         $dispatchtime=$Row['dispatch_time'];
         $Supplierstage=$Row['Supplier_stage'];
         $discount=$Row['discount'];
         $totaled=$Row['total_ed'];
         $Inclusive_Tag=$Row['inclusiveED'];
         $saleTax=$Row['saleTax'];
         $totalamt=$Row['total_amt'];
         $printType=$Row['printType'];
         $remarks=$Row['remarks'];
         $userId=$Row['userId'];
         $insertDate=$Row['insertDate'];
         $insertDate= null;

         //$_BuyerName=$Row['BuyerName'];
         $_BuyerName=null;
         $_PrincipalName=$Row['princname'];
         $_SupplierName=$Row['suppliername'];

         //$items=StocktransferDetails_Model::LoadAll($stId);
         $items=NULL;
         $newObj = new Stocktransfer_Model
($stId,$stInvNo,$stInvDate1,$stBuyerId,$stPrincipalId,$stSupplrId,$modedelivery,$sttime,
 $dispatchtime,$Supplierstage,$discount,$Inclusive_Tag,$totaled,$saleTax,$totalamt,$printType, $remarks,$userId,$insertDate,$items,$_BuyerName,$_PrincipalName,$_SupplierName);
         $objArray[$i] = $newObj;
         $i++;
  		}
  	    return $objArray;
	}


 public static function updateStockTransfer($stId,$stInvNo,$stInvDate,$stPrincipalId,$stSupplrId,$modedelivery,$sttime,
 $dispatchtime,$Supplierstage,$discount,$Inclusive_Tag,$totaled,$saleTax,$totalamt,$printType, $remarks)
 {
     if($stSupplrId==NULL){
		$stSupplrId='NULL';
   	 }
      if($Inclusive_Tag==NULL){
	 	 $Inclusive_Tag='N';
   	 }
   	 if($discount==NULL){
	 	 $discount=0.00;
   	 }
     $userId=$_SESSION["USER"];
     $insertDate=date("Y-m-d");
     $stInvDate1=self::xFormatDate($stInvDate);
     
     //added on 03-JUNE-2016 due to Handle Special Character
     $remarks = mysql_escape_string($remarks);
     
     $Query = " UPDATE stocktransfer SET stInvNo='$stInvNo',stInvDate='$stInvDate1',stBuyerId=99999999,stPrincipalId=$stPrincipalId,stSupplrId=$stSupplrId,mode_delivery='$modedelivery',st_time='$sttime',dispatch_time='$dispatchtime',Supplier_stage='$Supplierstage',discount=$discount,total_ed=$totaled,inclusiveED='$Inclusive_Tag',saleTax=$saleTax,total_amt=$totalamt,printType='$printType',remarks='$remarks' where stId=$stId";
     
        $Result = DBConnection::UpdateQuery($Query);
     
        if($Result=="SUCCESS"){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
 }

}
class StocktransferDetails_Model
{
    public $_stdId;
    public $_stId;
    public $itemid;
    public $_st_codePartNo;
    public $_codePartNo_desc;
    public $_iinv_no;
    public $_iinv_no_add;
    public $_bal_qty;
    public $_issued_qty;
    public $_price;
    public $_amt;
    public $_ed_percent;
    public $_ed_perUnit;
    public $_ed_amt;
    public $_entryId;
    public $_edu_percent;
    public $edu_amt;
    public $_cvd_percent;
    public $_cvd_amt;

    public function __construct( $stdId,$stId,$itemid,$st_codePartNo,$codePartNo_desc,$iinv_no,$iinv_no_add,$bal_qty,
    $issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$edu_amt,$cvd_percent,$cvd_amt,$_price_add=null)
    {
    $this->_stdId=$stdId;
    $this->_stId=$stId;
    $this->itemid=$itemid;
    $this->_st_codePartNo=$st_codePartNo;
    $this->_codePartNo_desc=$codePartNo_desc;
    $this->_iinv_no=$iinv_no;
    $this->_iinv_no_add=$iinv_no_add;
    $this->_bal_qty=$bal_qty;
    $this->_issued_qty=$issued_qty;
    $this->_price=$price;
    $this->_amt=$amt;
    $this->_ed_percent=$ed_percent;
    $this->_ed_perUnit=$ed_perUnit;
    $this->_ed_amt=$ed_amt;
    $this->_entryId=$entryId;
    $this->_edu_percent=$edu_percent;
    $this->edu_amt=$edu_amt;
    $this->_cvd_percent=$cvd_percent;
    $this->_cvd_amt=$cvd_amt;
	$this->_price_add = $_price_add;
    }


       public static function GetStockTransferInfo($stId)
		{
			$Query = "SELECT st_codePartNo,issued_qty as qty,iinv_no FROM stocktransfer_detail WHERE  stId=$stId";
	
			$RESULT = DBCONNECTION::SELECTQUERY($Query);
			$OBJARRAY = ARRAY();
			$I = 0;
			WHILE ($ROW = MYSQL_FETCH_ARRAY($RESULT, MYSQL_ASSOC)) {
			 $OBJARRAY[$I]['codePartNo']=$ROW['st_codePartNo'];
			 $OBJARRAY[$I]['qty']=$ROW['qty'];
			 $OBJARRAY[$I]['iinv_no']=$ROW['iinv_no'];
			 $I++;
			}
			RETURN $OBJARRAY;

	    }

    public static function showIncomingExciseDetail($itemId,$iinvNo)
	{
     $result=self::getIncomingExciseDetail($itemId,$iinvNo);
	
     $objArray = array();
     $i = 0;
     while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
             $itemid=$Row['ItemId'];
             $iinv_no_add=$Row['iinv_no_add'];
             $bal_qty=$Row['bal_qty'];
             $ed_percent=$Row['ed_percent'];
			 $ed_perUnit=$Row['ed_perUnit'];
			 $ed_amt=$Row['ed_amt'];
             $entryId=$Row['entryId'];
             $edu_percent=$Row['edu_percent']+$Row['hedu_percent'];
             $edu_amt=(($ed_amt*$edu_percent)/100);
             $cvd_percent=$Row['cvd_percent'];
		     $cvd_amt=$Row['cvd_amt'];
			 $tot_ass_val=$Row['tot_ass_val'];
			 $p_qty=$Row['p_qty'];
			 $s_qty=$Row['s_qty'];
			 $_price_add = $tot_ass_val/$p_qty;
             $newObj = new StocktransferDetails_Model ( $stdId,$stId,$itemid,$st_codePartNo,$codePartNo_desc,$iinv_no,$iinv_no_add,$bal_qty,$issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$edu_amt,$cvd_percent,$cvd_amt,$_price_add);
             $objArray[$i] = $newObj;
             $i++;
     }
      return $objArray;
    }
    public static function LoadAll($StockTransferId)
    {
        if($StockTransferId==0)
        {
            $result= self::getAllStockTransferDetails();
        }
        else if($StockTransferId>0)
        {
            $result=self::getStockTransferDetails($StockTransferId);
        }
        $objArray = array();
        $i = 0;

        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $stdId=$Row['stdId'];
            $stId=$Row['stId'];
            $itemid=$Row['ItemId'];
            $st_codePartNo=$Row['st_codePartNo'];
            $codePartNo_desc=$Row['codePartNo_desc'];
            $iinv_no=$Row['iinv_no'];
            $iinv_no_add=$Row['iinv_no_add'];
            $bal_qty=$Row['bal_qty'];
            $issued_qty=$Row['issued_qty'];
            $price=$Row['price'];
            $amt=$Row['amt'];
            $ed_percent=$Row['ed_percent'];
            $ed_perUnit=$Row['ed_perUnit'];
            $ed_amt=$Row['ed_amt'];
            $entryId=$Row['entryId'];
            $edu_percent=$Row['edu_percent'];
            $edu_amt=(($ed_amt*$edu_percent)/100);
            $cvd_percent=$Row['cvd_percent'];
            $cvd_amt=$Row['cvd_amt'];



            // $items=IncomingInvoiceNonExciseDetails_Model::pageLoad($incominginvoiceid);
            $newObj = new StocktransferDetails_Model
   ( $stdId,$stId,$itemid,$st_codePartNo,$codePartNo_desc,$iinv_no,$iinv_no_add,$bal_qty,
    $issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$edu_amt,$cvd_percent,$cvd_amt);
            $objArray[$i] = $newObj;
            $i++;

        }
        return $objArray;

    }
    public static function InsertStockTransferDetails($stId,$st_codePartNo,$codePartNo_desc,$iinv_no,$bal_qty,
    $issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$cvd_percent,$cvd_amt)
    {
		 //added on 03-JUNE-2016 due to Handle Special Character
		$codePartNo_desc = mysql_escape_string($codePartNo_desc);
		$iinv_no = mysql_escape_string($iinv_no);
		
		
        $Query="INSERT INTO stocktransfer_detail(stId, st_codePartNo, codePartNo_desc, iinv_no, bal_qty, issued_qty, price, amt, ed_percent, ed_perUnit, ed_amt, entryId, edu_percent, cvd_percent, cvd_amt) VALUES ($stId,$st_codePartNo,$codePartNo_desc,$iinv_no,$bal_qty,$issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$cvd_percent,$cvd_amt)";
      
        $Result = DBConnection::InsertQuery($Query);
        
        if($Result > 0){
           $remainQty=($bal_qty-$issued_qty);
           $updateIncomingInventorySql="UPDATE incominginventory set ExciseQty=$remainQty where entryDId=$iinv_no and incomingInvTyp='E'";
           $result = DBConnection::UpdateQuery($updateIncomingInventorySql);
          
           return $result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function getAllStockTransferDetails()
    {
        $Query = "SELECT * FROM stocktransfer_detail";
        $Result = DBConnection::SelectQuery($Query);;
        return $Result;
    }

    public static function  LoadIncomingExciseList($code_PartNo,$incomingInvTyp)
	 {
	    $Query = "SELECT Item_Desc,ii.entryDId,ExciseQty,principal_inv_no,edu_percent FROM incominginventory as ii,incominginvoice_excise as iie,";
	    $Query .= "incominginvoice_excise_detail as iied,item_master as im ";
	    $Query .= "WHERE ii.entryDId=iied.entryDId and iie.entryId=iied.entryId and ii.incomingInvNo=iie.entryId ";
	    $Query .= "and im.itemId=ii.code_PartNo and ExciseQty>0 AND ii.code_PartNo=$code_PartNo AND incomingInvTyp='$incomingInvTyp'";
		
			$Result = DBConnection::SelectQuery($Query);
			$objArray = array();
			$i = 0;

			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			   $codePartNo_desc= $Row['Item_Desc'];

			   $_entryDId = $Row['entryDId'];
			   $principal_inv_no = $Row['principal_inv_no'];
			   $iinv_no=($_entryDId."|".$principal_inv_no);

			   $bal_qty = $Row['ExciseQty'];
               $edu_percent=$Row['edu_percent'];
			   $newObj = new StocktransferDetails_Model($stdId,$stId,$itemid,$code_PartNo,$codePartNo_desc,$iinv_no,$iinv_no_add,$bal_qty,$issued_qty,$price,$amt,$ed_percent,$ed_perUnit,$ed_amt,$entryId,$edu_percent,$edu_amt,$cvd_percent,$cvd_amt);
	                       $objArray[$i] = $newObj;
	                       $i++;
			}
			return $objArray;
	}


    public static function getStockTransferDetails($StockTransferId)
    {
        //$Query = "SELECT * FROM stocktransfer_detail where stId=$StockTransferId";
        $Query = "SELECT stdId, stId,im.ItemId, im.Item_Code_Partno as st_codePartNo,im.Item_Desc as codePartNo_desc, ie.principal_inv_no as iinv_no,ad.iinv_no AS iinv_no_add, bal_qty, issued_qty,";
		$Query =$Query ."price,amt,ad.ed_percent,ad.ed_perUnit,ad.ed_amt,ad.entryId,ad.edu_percent,ad.cvd_percent,ad.cvd_amt ";
		$Query =$Query ."FROM stocktransfer_detail as ad,item_master AS im,incominginvoice_excise AS ie,incominginvoice_excise_detail AS ied ";
		$Query =$Query ."where im.itemId=ad.st_codePartNo ";
		$Query =$Query ."and ie.entryId=ied.entryId ";
		$Query =$Query ."and ied.entryDId=ad.iinv_no ";
        $Query =$Query ."and ad.stId=$StockTransferId ";
     
        $Result = DBConnection::SelectQuery($Query);;
        return $Result;
    }
    public static function DeleteStockTransferDetail($StockTransferId)
    {
        $Query = "Delete from stocktransfer_detail where stId=$StockTransferId";
        $Result = DBConnection::DeleteQuery($Query);;
        return $Result;
    }
    public static function getIncomingExciseDetail($itemId,$iinvNo)
	{
	        $Query = "select im.ItemId,ied.entryDId AS iinv_no_add,Item_Desc,ii.ExciseQty as bal_qty,ied.ed_percent,ied.ed_perUnit,ied.ed_amt,iem.display_EntryNo as entryId,ied.edu_percent,ied.hedu_percent ,ied.cvd_percent,ied.cvd_amt,ied.tot_ass_val,ied.p_qty,ied.s_qty ";
            $Query =$Query ."from item_master as im,incominginventory AS ii,incominginvoice_excise AS ie,incominginvoice_excise_detail AS ied,incominginvoice_entryno_mapping AS iem ";
            $Query =$Query ."where im.itemId=ied.itemID_code_partNo ";
            $Query =$Query ."and ie.entryId=ied.entryId ";
            $Query =$Query ."and ii.entryDId=ied.entryDId ";
	        $Query =$Query ."and iem.inner_EntryNo=ie.entryId ";
	        $Query =$Query ."and ied.itemID_code_partNo=$itemId ";
	        $Query =$Query ."and ied.entryDId=$iinvNo";
	     
	        $Result = DBConnection::SelectQuery($Query);
	        return $Result;
    }
}

class StocktransferView_Model
{
 public $_stId;
 public $_stInvNo;
 public $_stInvDate;
 public $_discount;
 public $_total_ed;
 public $_saleTax;
 public $_total_amt;
 public $_PrincipalName;
 public $_SupplierName;

public function __construct( $stId,$stInvNo,$stInvDate,$discount,$totaled,$saleTax,$totalamt,$_PrincipalName,$_SupplierName)
 {
 $this->_stId=$stId;
 $this->_stInvNo=$stInvNo;
 $this->_stInvDate=$stInvDate;
 $this->_discount=$discount;
 $this->_total_ed=$totaled;
 $this->_saleTax=$saleTax;
 $this->_total_amt=$totalamt;

  $this->_PrincipalName=$_PrincipalName;
 $this->_SupplierName=$_SupplierName;
 }


    public static function LoadAll($StockTransferId)
	 {

	     $result= self::getAllStockTransfer();
         $objArray = array();
	     $i = 0;

	     while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {

	         $stId=$Row['stId'];
	         $stInvNo=$Row['stInvNo'];
	         $stInvDate=$Row['stInvDate'];
	         $stInvDate1=self::xFormatDate1($stInvDate);
	         $discount=$Row['discount'];
	         $totaled=$Row['total_ed'];
	         $saleTax=$Row['saleTax'];
	         $totalamt=$Row['total_amt'];
	         $_PrincipalName=$Row['princname'];
	         $_SupplierName=$Row['suppliername'];

	         $newObj = new StocktransferView_Model($stId,$stInvNo,$stInvDate1,$discount,$totaled,$saleTax,$totalamt,$_PrincipalName,$_SupplierName);
	         $objArray[$i] = $newObj;
	         $i++;
	     }
	     return $objArray;

	 }
	 public static function getAllStockTransfer()
	 {
	     $Query = "SELECT st.stId,st.stInvNo,stInvDate,psm1.Principal_Supplier_Name as princname,";
         $Query =$Query."psm2.Principal_Supplier_Name as suppliername,st.total_amt as Amount,st.discount,";
         $Query =$Query."st.total_ed,st.saleTax FROM stocktransfer as st ";
         $Query =$Query."INNER JOIN principal_supplier_master as psm1 ON st.stPrincipalId = psm1.Principal_Supplier_Id ";
         $Query =$Query."LEFT JOIN principal_supplier_master as psm2 ON st.stSupplrId = psm2.Principal_Supplier_Id ";
	     //echo $Query;
	     $Result = DBConnection::SelectQuery($Query);
	     return $Result;
	 }
}

?>

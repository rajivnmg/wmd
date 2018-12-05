<?php
class Challan_Model
{
    public $_ChallanId;
    public $_ChallanDate;
    public $_ChallanNo;
    public $_BuyerId;
    public $_gc_note;
    public $_gc_note_date;
    public $_ExecutiveId;
    public $_Challan_Purpose;
    public $_OrderNo;
    public $_OrderDate;
    public $_cust_contact_name;
    public $_CustId;
    public $_UserId;
    public $_InsertDate;
    public $_CustName;
    public $_CustAddress;
    public $_items=array();
    public $_BuyerName;
    public $_Challan_Status;
    public $invType;
    public $invoiceNo;
	public $OutgoingInvoiceNo1;
	public $OutgoingInvoiceNo2;
	public $OutgoingInvoiceNo3;
    public $_total_Qty;
	public $loanNo;
	public $remarks;
	
    public function __construct($ChallanId,$ChallanDate,$ChallanNo,$BuyerId,$gc_note,$gc_note_date,$ExecutiveId,$Challan_Purpose,$OrderNo,$OrderDate,$cust_contact_name,$CustId,$UserId,$InsertDate,$CustAddress,$CustName,$items,$_BuyerName,$challan_status,$invType,$invoiceNo,$total_qty,$loanNo,$remarks,$OutgoingInvoiceNo2=null,$OutgoingInvoiceNo3=null)
    {
        $this->_ChallanId=$ChallanId;
        $this->_ChallanDate=$ChallanDate;
        $this->_ChallanNo=$ChallanNo;
        $this->_BuyerId=$BuyerId;
        $this->_gc_note=$gc_note;
        $this->_gc_note_date=$gc_note_date;
        $this->_ExecutiveId=$ExecutiveId;
        $this->_Challan_Purpose=$Challan_Purpose;
        $this->_OrderNo=$OrderNo;
        $this->_OrderDate=$OrderDate;
        $this->_cust_contact_name=$cust_contact_name;
        $this->_CustId=$CustId;
        $this->_UserId=$UserId;
        $this->_InsertDate=$InsertDate;
        $this->_CustAddress=$CustAddress;
        $this->_CustName=$CustName;
        $this->_items=$items;
        $this->_BuyerName=$_BuyerName;
        $this->_Challan_Status=$challan_status;
        $this->invType=$invType;
        $this->invoiceNo=$invoiceNo;
        $this->_total_Qty=$total_qty;
		$this->loanNo = $loanNo;
		$this->_remarks = $remarks;
		$this->_OutgoingInvoiceNo1 = $invoiceNo;
		$this->_OutgoingInvoiceNo2 = $OutgoingInvoiceNo2;
		$this->_OutgoingInvoiceNo3 = $OutgoingInvoiceNo3;
    }
    public static function  LoadChallan($ChallanId)
	{
        
        if($ChallanId > 0)
        {
            $result = self::GetChallanDetails($ChallanId);
        }
        else
        {
            $result = self::GetChallanList();
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $ChallanId= $Row['ChallanId'];
            $ChallanDate = $Row['ChallanDate'];
            $ChallanNo = $Row['ChallanNo'];
            $BuyerId= $Row['BuyerId'];
            $gc_note = $Row['gc_note'];
            $gc_note_date= $Row['gc_note_date'];
            $ExecutiveId= $Row['ExecutiveId'];
            $Challan_Purpose = $Row['Challan_Purpose'];
            if($Row['OrderNo']=="NULL")
            {
			  $OrderNo="";	
			}
			else
			{
				$OrderNo = $Row['OrderNo'];
			}
            if($Row['OrderDate']=="0000-00-00")
            {
				$OrderDate="";
			}
			else
			{
				 $OrderDate = $Row['OrderDate'];
			}           
            $cust_contact_name = $Row['cust_contact_name'];
            $CustId = $Row['CustId'];
            $CustName=$Row['CustName'];
            $CustAddress=$Row['CustAddress'];
            $UserId = $Row['UserId'];
            $InsertDate = $Row['InsertDate'];
            $_BuyerName = $Row['CustName'];
            $challan_status = $Row['challan_status'];
            $invType = $Row['InvType'];
            $invoiceNo = $Row['InvoiceNo'];
            $total_qty=$Row['Total_qty'];
			$loanNo = $Row['loanNo'];
			$remarks = $Row['remarks'];
			$OutgoingInvoiceNo2 = $Row['InvoiceNo1'];
			$OutgoingInvoiceNo3 = $Row['InvoiceNo2'];
			
            $items=ChallanDetails_Model::LoadChallan($ChallanId);
            
            $newObj = new Challan_Model($ChallanId,$ChallanDate,$ChallanNo,$BuyerId,$gc_note,$gc_note_date,$ExecutiveId,$Challan_Purpose,$OrderNo,$OrderDate,$cust_contact_name,$CustId,$UserId,$InsertDate,$CustAddress,$CustName,$items,$_BuyerName,$challan_status,$invType,$invoiceNo,$total_qty,$loanNo,$remarks,$OutgoingInvoiceNo2,$OutgoingInvoiceNo3);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function InsertChallan($ChallanDate,$ChallanNo,$BuyerId,$gc_note,$gc_note_date,$ExecutiveId,$Challan_Purpose,$OrderNo,$OrderDate,$cust_contact_name,$CustId,$challan_status,$invType,$invoiceNo,$total_qty,$loanNo,$remarks,$OutgoingInvoiceNo2,$OutgoingInvoiceNo3)
    {
        $InsertDate=date("Y-m-d");
        session_start();
        $UserId=$_SESSION["USER"];
        if($OrderNo==NULL||$OrderNo==""){
        	$OrderNo='NULL';
        }
        
        if($OrderDate==NULL||$OrderDate==""){
        	$OrderDate='0000-00-00';
        }
        
        if($CustId==NULL||$CustId==""){
        	$CustId='NULL';
        }
        
        if($chalan_status==NULL||$chalan_status==""){
        	$chalan_status='1';
        }
        
        if($invType==NULL||$invType==""){
        	$invType='NULL';
        }
        
        if($invoiceNo==NULL||$invoiceNo==""){
        	$invoiceNo='NULL';
        }
		
		if($OutgoingInvoiceNo2==NULL||$OutgoingInvoiceNo2==""){
        	$OutgoingInvoiceNo2='NULL';
        }
		
		if($OutgoingInvoiceNo3==NULL||$OutgoingInvoiceNo3==""){
        	$OutgoingInvoiceNo3='NULL';
        }
		
        if($cust_contact_name==NULL||$cust_contact_name==""){
        	$cust_contact_name='';
        }	
		
		//$date = new DateTime($ChallanDate);
		//$cDate = $date->format('Y-d-m');
		$ChallanNo = self::GetLastChallanNumber();		
		
		// added on 02-JUNE-2016 due to Handle Special Character
        $cust_contact_name = mysql_escape_string($cust_contact_name); 
        $gc_note = mysql_escape_string($gc_note);
        $remarks = mysql_escape_string($remarks); 
        $OutgoingInvoiceNo2 = mysql_escape_string($OutgoingInvoiceNo2); 
        $OutgoingInvoiceNo3 = mysql_escape_string($OutgoingInvoiceNo3);
		
		
		
        $Query="INSERT INTO challan(`ChallanDate`, ChallanNo, BuyerId, gc_note, gc_note_date, ExecutiveId, Challan_Purpose, OrderNo, OrderDate, cust_contact_name, CustId, UserId, InsertDate,challan_status,InvType,InvoiceNo,Total_qty,create_id,loanNo,remarks,InvoiceNo1,InvoiceNo2) VALUES('$ChallanDate','$ChallanNo','$BuyerId','$gc_note','$gc_note_date','$ExecutiveId','$Challan_Purpose','$OrderNo','$OrderDate','$cust_contact_name',$CustId,'$UserId','$InsertDate','$challan_status','$invType','$invoiceNo','$total_qty','$UserId','$loanNo','$remarks','$OutgoingInvoiceNo2','$OutgoingInvoiceNo3')";
        //echo '<br/>INSERT QUERY --> '. $Query; exit;
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function UpdateChallan($ChallanId,$ChallanDate,$ChallanNo,$BuyerId,$gc_note,$gc_note_date,$ExecutiveId,$Challan_Purpose,$OrderNo,$OrderDate,$cust_contact_name,$CustId,$chalan_status,$invType,$invoiceNo,$total_qty,$loanNo,$remarks,$OutgoingInvoiceNo2,$OutgoingInvoiceNo3)
    {
    	 if($OrderNo==NULL||$OrderNo==""){
        	$OrderNo='NULL';
        }
        if($OrderDate== NULL || $OrderDate == ""){
        	$OrderDate='0000-00-00';
        }
        if($CustId==NULL||$CustId==""){
        	$CustId='NULL';
        }
         if($chalan_status==NULL||$chalan_status==""){
        	$chalan_status='1';
        }
         if($invType==NULL||$invType==""){
        	$invType='NULL';
        }
        
        if($invoiceNo==NULL||$invoiceNo==""){
        	$invoiceNo='NULL';
        }
		if($OutgoingInvoiceNo2==NULL||$OutgoingInvoiceNo2==""){
        	$OutgoingInvoiceNo2='NULL';
        }
		if($OutgoingInvoiceNo3==NULL||$OutgoingInvoiceNo3==""){
        	$OutgoingInvoiceNo3='NULL';
        }
        if($cust_contact_name==NULL||$cust_contact_name==""){
        	$cust_contact_name='';
        }
		 $modified_time=date('Y-m-d H:i:s');
		 $create_Id = $_SESSION["USER"];
		//$date = new DateTime($ChallanDate);
		//$cDate = $date->format('Y-d-m');
		
		//added on 02-JUNE-2016 due to Handle Special Character
        $cust_contact_name = mysql_escape_string($cust_contact_name); 
        $gc_note = mysql_escape_string($gc_note);
        $remarks = mysql_escape_string($remarks); 
        $OutgoingInvoiceNo2 = mysql_escape_string($OutgoingInvoiceNo2); 
        $OutgoingInvoiceNo3 = mysql_escape_string($OutgoingInvoiceNo3);
		
        $Query="UPDATE challan SET `ChallanDate`='$ChallanDate',ChallanNo='$ChallanNo',BuyerId=$BuyerId,gc_note='$gc_note',gc_note_date='$gc_note_date',ExecutiveId='$ExecutiveId',Challan_Purpose=$Challan_Purpose,OrderNo=$OrderNo,OrderDate='$OrderDate',cust_contact_name='$cust_contact_name',CustId=$CustId,challan_status='$chalan_status',InvType='$invType',InvoiceNo='$invoiceNo',Total_qty='$total_qty',modify_time = '$modified_time',modify_Id='$create_Id',loanNo='$loanNo' ,remarks='".$remarks."',InvoiceNo1='$OutgoingInvoiceNo2',InvoiceNo2='$OutgoingInvoiceNo3' WHERE ChallanId=$ChallanId";
		
     
        $Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
    }
    
    public static function GetChallanDetails($ChallanId)
    {
        $Query = "SELECT BuyerId FROM challan where ChallanId=$ChallanId";
        $result = DBConnection::SelectQuery($Query);
        $i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $BuyerId= $Row['BuyerId'];
            if($BuyerId>0)
            {
                $Query = "SELECT ch.*,buy.Bill_Add1 as CustAddress,buy.BuyerName as CustName FROM challan as ch inner join buyer_master as buy on ch.BuyerId =buy.BuyerId where ChallanId=$ChallanId"; 
                $Result = DBConnection::SelectQuery($Query);
                
                return $Result;
                break;
            }
            else if($BuyerId==0)
            {
                $Query = "SELECT ch.*,cs.cust_add as CustAddress,cs.cust_name as CustName FROM challan as ch inner join cust_master as cs on ch.CustId =cs.cust_id where ChallanId=$ChallanId"; 
                $Result = DBConnection::SelectQuery($Query);
                return $Result;
                break;
            }
            break;
            $i++;
        }
        //$Query = "SELECT * FROM challan where ChallanId=$ChallanId"; 
        //$Result = DBConnection::SelectQuery($Query);
        //return $Result;
    }
    public static function GetChallanList()
    {
        $Query = "SELECT ch.*,buy.BuyerName as CustName,buy.Bill_Add1 as CustAddress FROM challan as ch left join buyer_master as buy on ch.BuyerId =buy.BuyerId"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    
    public static function checkChallanNumber($ChallanNoStr){
     	        $Query = "SELECT COUNT(*)c FROM challan where ChallanNo LIKE ('$ChallanNoStr')";
	            $Result = DBConnection::SelectQuery($Query);
	 		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
	 	        $total=$row['c'];
		        return $total;
   }
   public static function createNewChallanNumber($ChallanNoStr){
       
        $Query = "SELECT CONCAT('C-',((SUBSTR(ChallanNo,3,8))+1)) AS new_ChallanNo FROM challan WHERE ChallanNo LIKE ('$ChallanNoStr') ORDER BY ChallanId DESC LIMIT 1";
        
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["new_ChallanNo"];
    }
	public static function GetLastChallanNumber(){
	        $FinancialYear = MultiweldParameter::GetFinancialYear();
	        $trxnStr="C-".$FinancialYear."%";
            $totChallanNumber=self::checkChallanNumber($trxnStr);

	        if($totChallanNumber>0){
               $getNewChallanNo=self::createNewChallanNumber($trxnStr);
	           return $getNewChallanNo;
	        }else{
	         $firstChallanNumber =$totChallanNumber+1;
	         $s = sprintf("%06d", $firstChallanNumber);
	         return "C-".$FinancialYear.$s;
	        }
   }

    public static function SearchChallan($Fromdate,$Todate,$challanNo,$Buyerid,$status,$purpose,$executive,$contactName,$start,$rp)
	{
		$opt="";
		//$opt=" where ch.challan_status=1 ";
	
		if($Buyerid!=""){
		
		  $opt=$opt."AND ch.BuyerId='$Buyerid' ";	
		  
		}
		if($challanNo!=""){
		
			 $opt=$opt."AND ch.ChallanNo LIKE '$challanNo%' ";
			 
		}
		if(($Fromdate!="") && ($Todate!="")){
		
			 $opt=$opt."AND ch.ChallanDate>='$Fromdate' AND ch.ChallanDate<='$Todate' ";	
			 
		}
		if($status!=""){
		
			$opt=$opt."AND ch.challan_status=$status ";	
			 
		}
		if($purpose!=""){
	
			$opt=$opt."AND ch.Challan_Purpose=$purpose";	
			
		}
		if($executive!=""){
	
			$opt=$opt."AND ch.ExecutiveId='$executive'";	
			
		}	
		if($contactName!=""){
	
			$opt=$opt."AND ch.cust_contact_name LIKE '$contactName%'";	
			
		}			
		
		//$Query=" SELECT ch.*,buy.BuyerName,buy.Bill_Add1 as CustAddress FROM challan as ch left join buyer_master as buy on ch.BuyerId = buy.BuyerId $opt ";
		$Query=" SELECT ch.*,buy.BuyerName,buy.Bill_Add1 as CustAddress FROM challan as ch, buyer_master as buy WHERE ch.BuyerId = buy.BuyerId $opt ";
		$Query =$Query." ORDER BY  `ch`.`ChallanId` ASC LIMIT $start , $rp ";

		//echo $Query; 
		
		
        
        $result = DBConnection::SelectQuery($Query);
        $objArray = array();
	    $i = 0;

	    while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	    	$objArray[$i]=$Row;
	    	$i++;
	    }	
		
		return $objArray;
	}
	public static function countRec($Fromdate,$Todate,$challanNo,$Buyerid,$status,$purpose,$executive,$contactName)
	{
		$opt="";
		//$opt=" where ch.challan_status=1 ";
		
		if(($Fromdate!="") && ($Todate!="")){
		
			$opt=$opt."AND ch.ChallanDate>='$Fromdate' AND ch.ChallanDate<='$Todate' ";		
		  
		}
		if($challanNo!=""){
		
			 $opt=$opt."AND ch.ChallanNo LIKE '$challanNo%' ";
			 
		}
		if($Buyerid!=""){
		
			 $opt=$opt."AND ch.BuyerId='$Buyerid' ";
			 
		}
		if($status!=""){
		
			$opt=$opt."AND ch.challan_status=$status ";	
			 
		}
		if($purpose!=""){
	
			$opt=$opt."AND ch.Challan_Purpose=$purpose ";	
			
		}
		if($executive!=""){
	
			$opt=$opt."AND ch.ExecutiveId='$executive'";	
			
		}	
		if($contactName!=""){
	
			$opt=$opt."AND ch.cust_contact_name LIKE '$contactName%'";	
			
		}		
	//	$CountQuery=" SELECT ch.*,buy.BuyerName ,buy.Bill_Add1 as CustAddress,count(ch.ChallanId) AS total FROM challan as ch left join buyer_master as buy on ch.BuyerId =buy.BuyerId $opt ";
	$CountQuery=" SELECT ch.*,buy.BuyerName ,buy.Bill_Add1 as CustAddress,count(ch.ChallanId) AS total FROM challan as ch, buyer_master as buy WHERE ch.BuyerId =buy.BuyerId $opt ";
		
	

	    $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];
	    return $count;
	}
}
class ChallanDetails_Model
{
   // public $_challan_detail_id;
    public $_ChallanId;
    public $_code_part_no;
    public $_qty;
    public $item_desc;
    public $_principalname;
    public $_principalID;
    public function __construct($ChallanId,$code_part_no,$qty,$item_desc,$principalId,$_principalname,$itemid,$_SrNo)
    {
    //$this->_challan_detail_id=$challan_detail_id;
    $this->_ChallanId=$ChallanId;
    $this->_code_part_no=$code_part_no;
    $this->_qty=$qty;
    $this->item_desc=$item_desc;
    $this->_principalID=$principalId;
    $this->_principalname=$_principalname;
    $this->itemid=$itemid;
    $this->_SrNo=$_SrNo;
    }
    
    public static function  LoadChallan($ChallanId)
	{
        
        if($ChallanId > 0)
        {
            $result = self::GetChallanDetails($ChallanId);
        }
        else
        {
            $result = self::GetChallanList();
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
           // $challan_detail_id= $Row['challan_detail_id'];
            $ChallanId = $Row['ChallanId'];
            $code_part_no = $Row['code_part_no'];
            $itemid = $Row['itemid'];
            $qty= $Row['qty'];
            $item_desc=$Row['item_desc'];
            $principalId=$Row['principalId'];
            $_principalname=$Row['Principal_Supplier_Name'];
            $_SrNo=$i+1;
            $newObj = new ChallanDetails_Model($ChallanId,$code_part_no,$qty,$item_desc,$principalId,$_principalname,$itemid,$_SrNo);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
public static function LoadChallanDetails($ChallanId)
	{
        
        if($ChallanId > 0)
        {
            $result = self::GetChallanDetails($ChallanId);
        }
        else
        {
            $result = self::GetChallanList();
        }
		
		$objArray = array();
		$i = 0;		
		while($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['code_partNo'] = $Row['itemid'];
			  $objArray[$i]['item_codepart'] = $Row['code_part_no'];	  
			  $objArray[$i]['item_desc'] = $Row['item_desc'];		  
			  $objArray[$i]['principalname'] = $Row['Principal_Supplier_Name'];
			  $objArray[$i]['tot_exciseQty'] = $Row['qty'];   
			  $objArray[$i]['create_time'] = $Row['create_time'];
			  $objArray[$i]['create_id'] = $Row['create_id']; 		 
			  $objArray[$i]['modify_time'] = $Row['modify_time'];
			  $objArray[$i]['modify_id'] = $Row['modify_id'];
		
			  $i++;
		  }
		
		return $objArray;		
		
		 /*  $Item_Obj = new Inventory_Model(0,0,0,"","","",0,0,0);
        $objArray = array();
		$i = 0;
		$j=0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		
			 $Item_Obj->code_partNo = $Row['itemid'];
             $Item_Obj->item_codepart = $Row['code_part_no'];
             $Item_Obj->item_desc = $Row['item_desc'];           
             $Item_Obj->principalname = $Row['Principal_Supplier_Name'];
             $Item_Obj->tot_exciseQty = $Row['qty'];   
			 $Item_Obj->create_time = $Row['create_time'];
             $Item_Obj->create_id = $Row['create_id'];   
			 $Item_Obj->modify_time = $Row['modify_time'];
             $Item_Obj->modify_id = $Row['modify_id'];   
             $newObj = new Inventory_Model($Item_Obj->code_partNo,$Item_Obj->tot_exciseQty,0,$Item_Obj->principalname,$Item_Obj->item_codepart,$Item_Obj->item_desc,null,null,null);
             $objArray[$i] = $newObj;
             $i++;
		}
		return $objArray;	 */		
		
	}
	
    public static function InsertChallanDetails($ChallanId,$code_part_no,$qty,$principalId,$create_Id,$create_time)
    {
        $Query="INSERT INTO challan_detail(ChallanId,code_part_no,qty,principalId,status,create_id,create_time) VALUES ('$ChallanId','$code_part_no',$qty,$principalId,'active','$create_Id','$create_time')";
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            //return QueryResponse::YES;
			return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function UpdateChallanDetails($ChallanId,$code_part_no,$qty)
    {
        $Query="UPDATE challan_detail SET code_part_no='$code_part_no',qty=$qty WHERE ChallanId=$ChallanId";
        $Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
    }
    public static function UpdateStatus($ChallanId,$create_Id,$create_time)
    {
		 $Query = "update challan_detail set status='inactive',modify_Id='$create_Id',modify_time='$create_time' where ChallanId='$ChallanId' and status='active'";
		
		 $Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	public static function BuyerWiseChallanDetail($BUYERID)
	{
		 $Query="SELECT CASE ch.Challan_Purpose WHEN ch.Challan_Purpose='1' THEN 'Free Sample' WHEN ch.Challan_Purpose='2' THEN 'Returnable Sample' WHEN ch.Challan_Purpose='3' THEN 'Loan Basis' WHEN ch.Challan_Purpose='4' THEN 'Replacement' WHEN ch.Challan_Purpose='5' THEN 'To Be Billed' END AS Challan_Purpose ,ch.ChallanNo,DATE_FORMAT(ch.ChallanDate,'%d/%m/%Y') AS Challan_Date,chd.qty,itm.Item_Desc,p.Principal_Supplier_Name FROM challan_detail AS chd INNER JOIN challan AS ch ON chd.ChallanId=ch.ChallanId,item_master AS itm,principal_supplier_master AS p  WHERE chd.status='active' AND chd.code_part_no=itm.ItemId AND chd.principalId=p.Principal_Supplier_Id AND ch.challan_status='1' AND  ch.BuyerId='$BUYERID'  ORDER BY ch.ChallanDate,ch.ChallanNo desc";
		
		 $Result = DBConnection::SelectQuery($Query);
		 $objArray = array();
		 $i = 0;
		
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			
		  $Row['sno']=$i+1;	
		  $objArray[$i]=$Row;
		  $i++;	
		}
		
		return $objArray;	
	}
    public static function GetChallanDetails($ChallanId)
    {
        $Query="SELECT ch.ChallanId,ch.code_part_no AS itemid,ch.qty,it.Item_Code_Partno AS code_part_no,ch.principalId,it.Item_Desc AS item_desc,ch.create_id,ch.create_time,ch.modify_time,ch.modify_id,p.Principal_Supplier_Name FROM challan_detail as ch inner join item_master as it on ch.code_part_no=it.ItemId,principal_supplier_master as p where status='active' and ch.principalId=p.Principal_Supplier_Id and ChallanId=$ChallanId ";
       
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function GetChallanList()
    {
        $Query="SELECT ch.*,it.Item_Desc as item_descp FROM challan_detail as ch inner join item_master as it on ch.code_part_no=it.ItemId where ChallanId=$ChallanId";
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function DeleteChallanDetails($ChallanId)
    {
        $Query="DELETE FROM challan_detail where ChallanId= $ChallanId";
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    
}

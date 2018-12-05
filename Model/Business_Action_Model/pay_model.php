<?php

class Payment_Model
{
	public $trxnId;
	public $trnx_no;
	public $buyerId;
	public $BuyerName;
	public $trxn_date;
	public $trxn_type;
	public $bank_name;
	public $branch_name;
	public $bank_add;
	public $cheque_no;
	public $cheque_date;
	public $cheque_account_no;
	public $UserId;
	public $received_amt;
	public $status;
	public $insertDate;
	public $_items;
	public $cancel_reason;
	
	public function __construct($trxnId,$trnx_no,$buyerId,$trxn_date,$trxn_type,$bank_name,$branch_name,$bank_add,$cheque_no,$cheque_date,$cheque_account_no,$received_amt,$UserId,$status,$insertDate,$BuyerName,$_items,$cancel_reason)
	    {
			$this->trxnId=$trxnId;
			$this->trnx_no=$trnx_no;
			$this->buyerId=$buyerId;
			$this->trxn_date=$trxn_date;
			$this->trxn_type=$trxn_type;
			$this->bank_name=$bank_name;
			$this->branch_name=$branch_name;
			$this->cheque_no=$cheque_no;
			$this->cheque_date=$cheque_date;
			$this->cheque_account_no=$cheque_account_no;
			$this->bank_add=$bank_add;
			$this->UserId=$UserId;
			$this->received_amt=$received_amt;
	        $this->status=$status;
	        $this->insertDate=$insertDate;
	        $this->BuyerName=$BuyerName;
	        $this->_items = $_items;
			$this->cancel_reason=$cancel_reason;
	        
        }
	public static function  LoadPAYByID($trxnid){
		$Query = "SELECT t.*,bm.BuyerName FROM trxn AS t INNER JOIN buyer_master AS bm ON t.buyerId = bm.BuyerId  WHERE t.trxnId =$trxnid";
		
			$Result = DBConnection::SelectQuery($Query);
			$objArray = array();
			$i = 0;
			while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)){
	            
	            $trxnId= $Row['trxnId'];
	            $trnx_no= $Row['trnx_no'];
	            $trxn_date = $Row['trxn_date'];
	            $trxn_type = $Row['trxn_type'];
	            $bank_name = $Row['bank_name'];			    
	            $bank_add  = $Row['bank_add'];
	            $branch_name=$Row['branch_name'];
	            $cheque_no= $Row['cheque_no'];
	            $cheque_date= $Row['cheque_date'];
	            $cheque_account_no=$Row['cheque_account_no'];
	            $buyerId= $Row['buyerId'];
	            $BuyerName= $Row['BuyerName'];	           	
	            $received_amt=$Row['received_amt'];
	            $UserId=$Row['UserId'];	
	            $status=$Row['status'];	
				$cancel_reason=$Row['cancel_reason'];					
	           	$insertDate=$Row['insertDate'];    
			    $_itmes = Payment_Details_Model::LoadGrid($trxnid);
	            $newObj = new Payment_Model($trxnId,$trnx_no,$buyerId,$trxn_date,$trxn_type,$bank_name,$branch_name,$bank_add,$cheque_no,$cheque_date,$cheque_account_no,$received_amt,$UserId,$status,$insertDate,$BuyerName,$_itmes,$cancel_reason);
	            $objArray[$i] = $newObj;
	            $i++;
			}
			return $objArray;	
	}
	
	public static function  LoadPAYMENTDETAILSByID($trxnid){
		  $Query="SELECT td.*,p.PARAM_VALUE1 AS payment_status_value FROM trxn_detail AS td ,param AS p WHERE td.trxnId = ".$trxnid." AND td.payment_status=p.PARAM1 AND PARAM_TYPE='PAYMENT' AND p.PARAM_CODE='STATUS'";
	
	   	   $result = DBConnection::SelectQuery($Query);
		   $objArray = array();
		   $i = 0;
		   while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		   	 $objArray[$i]['trxnId'] = $Row['trxnId'];
		   	 $objArray[$i]['trxndId'] = $Row['trxndId'];
		   	 $objArray[$i]['invoiceNo'] = $Row['invoiceNo'];
		   	 $objArray[$i]['invoiceDate'] = $Row['invoiceDate'];
		   	 $objArray[$i]['dueDate'] = $Row['dueDate'];
		   	 $objArray[$i]['invoiceAmount'] = $Row['invoiceAmount']; 
		   	 $objArray[$i]['payabledAmount'] = $Row['payabledAmount']; 
		   	 $objArray[$i]['receivedAmount'] = $Row['receivedAmount']; 
		   	 $objArray[$i]['balanceAmount'] =$Row['balanceAmount']; 
		   	 $objArray[$i]['shortAmount']=$Row['shortAmount']; 
		   	 $objArray[$i]['excessAmount']=$Row['excessAmount']; 
		   	 $objArray[$i]['debitFlag']=$Row['debitFlag']; 
		   	 $objArray[$i]['debitId']=$Row['debitId'];
		   	 $objArray[$i]['debitAmt']=$Row['debitAmt']; 
		   	 $objArray[$i]['cash_discount_value']=$Row['cash_discount_value']; 
		   	 $objArray[$i]['payment_status_value']=$Row['payment_status_value']; 
		   	 $objArray[$i]['payment_status']=$Row['payment_status']; 
		   	 $objArray[$i]['status']=$Row['status']; 
		     $objArray[$i]['Remarks']=$Row['Remarks']; 
		     $i++;
		   }
		  return $objArray;	
		  
		
		  
	}
	
	
	public static function PaymentTransactionCancel($trxnId,$cancel_reason)
	{
		$cancel_reason=addslashes($cancel_reason);
		$Query ="update trxn set status='cancelled',cancel_reason='$cancel_reason' where trxnId='$trxnId'";

		
		$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
		
    public static function InsertPayment($trnx_no,$bn,$trxn_date,$trn_type,$bank_name,$branch_name,$cheque_no,$cheque_date,$cheque_acount_no,$utr_no,$bank_add,$received_amt,$USERID)
	{
		$trnx_no = self::GetLastTransactionNumber();
		$insertDate=date("Y-m-d H:i:s");
		//$trxn_date1= MultiweldParameter::xFormatDate($trxn_date);		
		//$cheque_date1= MultiweldParameter::xFormatDate($cheque_date);
		if($cheque_date==''||$cheque_date==NULL)
		{
			$cheque_date='0000-00-00';
		}
		
		//added on 02-JUNE-2016 due to Handle Special Character
		$bank_name = mysql_escape_string($bank_name);
		$branch_name = mysql_escape_string($branch_name);
		$bank_add = mysql_escape_string($bank_add);
		$cheque_no = mysql_escape_string($cheque_no);
		$cheque_acount_no = mysql_escape_string($cheque_acount_no);
		$utr_no = mysql_escape_string($utr_no);
		$USERID = mysql_escape_string($USERID);
				
		$Query ="INSERT INTO trxn (trnx_no,buyerId,trxn_date,trxn_type,bank_name,branch_name,bank_add,cheque_no,cheque_date,cheque_account_no,utr_no,received_amt,UserId,insertDate,status)  
		         VALUES('$trnx_no','$bn','$trxn_date','$trn_type','$bank_name','$branch_name','$bank_add','$cheque_no','$cheque_date','$cheque_acount_no','$utr_no','$received_amt','$USERID','$insertDate','created')";
		
		$Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
		
	}
	
	public static function UpdatePayment($trxnId,$trnx_no,$bn,$trxn_date,$trn_type,$bank_name,$branch_name,$cheque_no,$cheque_date,$cheque_acount_no,$utr_no,$bank_add,$received_amt,$USERID)
	{
		$modify_time=date("Y-m-d H:i:s");
		//$trxn_date1= MultiweldParameter::xFormatDate($trxn_date);		
		//$cheque_date1= MultiweldParameter::xFormatDate($cheque_date);
		if($cheque_date==''||$cheque_date==NULL)
		{
			$cheque_date='0000-00-00';
		}
		
		//added on 02-JUNE-2016 due to Handle Special Character
		$bank_name = mysql_escape_string($bank_name);
		$branch_name = mysql_escape_string($branch_name);
		$bank_add = mysql_escape_string($bank_add);
		$cheque_no = mysql_escape_string($cheque_no);
		$cheque_acount_no = mysql_escape_string($cheque_acount_no);
		$utr_no = mysql_escape_string($utr_no);
		$USERID = mysql_escape_string($USERID);
		
		$Query ="UPDATE trxn SET trnx_no='$trnx_no',buyerId='$bn',trxn_date='$trxn_date',trxn_type='$trn_type',bank_name='$bank_name',branch_name='$branch_name',bank_add='$bank_add',cheque_no='$cheque_no',cheque_date='$cheque_date',cheque_account_no='$cheque_acount_no',utr_no='$utr_no',received_amt='$received_amt',modify_id='$USERID',modify_time='$modify_time',status='created' WHERE trxnId = $trxnId"; 
		      
		
		$Result = DBConnection::UpdateQuery($Query);
       if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
		}else{
				return QueryResponse::NO;
		}
		
	}
	public static function InsertTRXN($buyerId,$trxn_type,$bank_name,$bank_add,$received_amt,$trxn_status,$userId){
	  $date = date("Y-m-d");
	  
	  //added on 02-JUNE-2016 due to Handle Special Character
		$bank_name = mysql_escape_string($bank_name);
		$bank_add = mysql_escape_string($bank_add);
	  
	  $Query = "INSERT INTO trxn(buyerId,trxn_date,trxn_type,bank_name,bank_add,received_amt,trxn_status,UserId ,InsertDate) VALUES ($buyerId,'$date','$trxn_type','$bank_name','$bank_add',$received_amt,'$trxn_status','$userId','$date')";
      //echo $Query;
      $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
	}
	
	public static function UpdatePAY($trxnId,$buyerId,$trxn_date,$trxn_type,$bank_name,$bank_add,$received_amt){
		
		//added on 02-JUNE-2016 due to Handle Special Character
		$bank_name = mysql_escape_string($bank_name);
		$bank_add = mysql_escape_string($bank_add);
		
		$Query="UPDATE trxn SET trxnId=$trxnId,buyerId=$buyerId,trxn_date='$trxn_date',trxn_type='$trxn_type',bank_name='$bank_name',bank_add='$bank_add',received_amt=$received_amt,trxn_status='$trxn_status' WHERE trxnId=$trxnId";
		
			$Result = DBConnection::UpdateQuery($Query);
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
	}
	public static function SearchPaymentTransaction($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear,$start,$rp)
	{
		$opt="";
		if($Buyerid!="")
		{
		  $opt=$opt." AND t.buyerId='$Buyerid' ";	
		}
		if($trxnNo!="")
		{
			 $opt=$opt."  AND t.trnx_no like '$trxnNo%' ";	
		}
		if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND t.trxn_date>='$Fromdate' AND t.trxn_date<='$Todate' ";	
		}
		if($pono != "")
		{
			 $opt=$opt." AND po.bpono='$pono' ";
		}	
		
		/*
		$finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} */
		
		$opt = $opt."GROUP BY trnx_no";
		$Query=" SELECT txd.invoiceNo,t.trxnId,t.trnx_no,t.trxn_date,t.received_amt,t.UserId,t.status,tmp.pono,po.bpono,bm.BuyerName,pr.PARAM_VALUE1 AS trxn_type FROM trxn_detail AS txd INNER JOIN trxn AS t ON t.trxnId=txd.trxnId INNER JOIN (SELECT oinvoice_No,pono FROM outgoinginvoice_excise UNION ALL SELECT oinvoice_No,pono FROM outgoinginvoice_nonexcise) AS tmp ON txd.invoiceNo=tmp.oinvoice_No INNER JOIN purchaseorder AS po ON tmp.pono=po.bpoId INNER JOIN buyer_master AS bm ON po.buyerId = bm.BuyerId ,param AS pr WHERE t.trxn_type=pr.PARAM1 AND pr.PARAM_TYPE='TRXN' AND pr.PARAM_CODE='MODE' $opt ORDER BY  `t`.`trxn_date` ASC LIMIT $start,$rp";
		        
		        
		// echo $Query; exit;
        $result = DBConnection::SelectQuery($Query);
        $objArray = array();
	    $i = 0;

	    while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	    	$objArray[$i]=$Row;
	    	$i++;
	    }	
		
		return $objArray;
	}
	public static function countRec($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear)
	{
		$opt="";
		if($Buyerid!="")
		{
		  $opt=$opt." AND t.buyerId='$Buyerid' ";	
		}
		if($trxnNo!="")
		{
			 $opt=$opt."  AND t.trnx_no like '$trxnNo %' ";	
		}
		if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND t.trxn_date>='$Fromdate' AND t.trxn_date<='$Todate' ";	
		}
		
		if($pono != "")
		{
			 $opt=$opt." AND po.bpono='$pono' ";
		}
		
		/*
		$finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} */
		
		$opt = $opt."GROUP BY trnx_no";
		
		$CountQuery=" SELECT  COUNT(*) AS total1 ,txd.invoiceNo,t.trxnId,t.trnx_no,t.trxn_date,t.received_amt,t.UserId,tmp.pono,po.bpono,bm.BuyerName,pr.PARAM_VALUE1 AS trxn_type FROM trxn_detail AS txd INNER JOIN trxn AS t ON t.trxnId=txd.trxnId INNER JOIN (SELECT oinvoice_No,pono FROM outgoinginvoice_excise UNION ALL SELECT oinvoice_No,pono FROM outgoinginvoice_nonexcise) AS tmp ON txd.invoiceNo=tmp.oinvoice_No INNER JOIN purchaseorder AS po ON tmp.pono=po.bpoId INNER JOIN buyer_master AS bm ON po.buyerId = bm.BuyerId ,param AS pr WHERE t.trxn_type=pr.PARAM1 AND pr.PARAM_TYPE='TRXN' AND pr.PARAM_CODE='MODE' $opt ";
		
		$Query="SELECT COUNT(*)AS total FROM  (".$CountQuery.") as s ";	
	
	    $CountResult = DBConnection::SelectQuery($Query);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];
	    return $count;
	}
	public static function checkTransactionNumber($trxnStr){
     	        $Query = "SELECT COUNT(*)c FROM trxn  where trnx_no LIKE ('$trxnStr')";
	            $Result = DBConnection::SelectQuery($Query);
	 		    $row=mysql_fetch_array($Result, MYSQL_ASSOC);
	 	        $total=$row['c'];
		        return $total;
   }
   public static function createNewTransactionNumber($trxnStr){
       
        $Query = "SELECT CONCAT('P-',((SUBSTR(trnx_no,3,8))+1)) AS new_transactionNo FROM trxn WHERE trnx_no LIKE ('$trxnStr') ORDER BY trxnId DESC LIMIT 1";
        
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        return $Row["new_transactionNo"];
    }
	public static function GetLastTransactionNumber(){
	        $FinancialYear = MultiweldParameter::GetFinancialYear();
	        $trxnStr="P-".$FinancialYear."%";
            $totTransactionNumber=self::checkTransactionNumber($trxnStr);

	        if($totTransactionNumber>0){
               $getNewTransactionNo=self::createNewTransactionNumber($trxnStr);
	           return $getNewTransactionNo;
	        }else{
	         $firstTransactionNumber =$totTransactionNumber+1;
	         $s = sprintf("%06d", $firstTransactionNumber);
	         return "P-".$FinancialYear.$s;
	        }
   }

}

class Payment_Details_Model{
		public $trxnId;	
		public $trxndId	;
        public $invoiceNo;
        public $invoiceDate;
        public $dueDate;
        public $invoiceAmount;
        public $payabledAmount;
        public $receivedAmount ;
        public $balanceAmount ;
        public $shortAmount;
        public $excessAmount;        
        public $debitFlag;
        public $debitId;
        public $debitAmt;
        public $cash_discount_value;       
        public $payment_status;
        public $payment_status_value;
        public $createId;
        public $createTime;
        public $status;
        public $Remarks;
        

       public function __construct($trxnId,$trxndId,$invoiceNo,$invoiceDate,$dueDate,$invoiceAmount,$payabledAmount,$receivedAmount,$balanceAmount ,$shortAmount,$excessAmount,$debitFlag,$debitId,$debitAmt,$cash_discount_value,$payment_status,$payment_status_value,$status,$Remarks){
			$this->trxnId =$trxnId;
			$this->trxndId =$trxndId;
			$this->invoiceNo=$invoiceNo;
			$this->invoiceDate=$invoiceDate;
			$this->dueDate=$dueDate;
			$this->invoiceAmount=$invoiceAmount;
			$this->payabledAmount=$payabledAmount;
			$this->receivedAmount=$receivedAmount ;
			$this->balanceAmount=$balanceAmount;
			$this->shortAmount=$shortAmount ;
			$this->excessAmount=$excessAmount;
			$this->debitFlag=$debitFlag;
			$this->debitId=$debitId;
			$this->debitAmt=$debitAmt;
			$this->cash_discount_value=$cash_discount_value;			
			$this->payment_status=$payment_status;
			$this->payment_status_value=$payment_status_value;
			$this->status=$status;
			$this->Remarks=$Remarks;
       }
        public static function LoadGrid($trxnId)
       {
	   	  $Query="SELECT td.*,p.PARAM_VALUE1 AS payment_status_value FROM trxn_detail AS td ,param AS p WHERE td.trxnId =$trxnId AND td.payment_status=p.PARAM1 AND PARAM_TYPE='PAYMENT' AND p.PARAM_CODE='STATUS'";
	  
	   	   $result = DBConnection::SelectQuery($Query);
		   $objArray = array();
		   $i = 0;
		   while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		   	 $trxnId=$Row['trxnId'];
		   	 $trxndId=$Row['trxndId'];
		   	 $invoiceNo=$Row['invoiceNo'];
		   	 $invoiceDate=$Row['invoiceDate'];
		   	 $dueDate=$Row['dueDate'];
		   	 $invoiceAmount=$Row['invoiceAmount']; 
		   	 $payabledAmount=$Row['payabledAmount']; 
		   	 $receivedAmount=$Row['receivedAmount']; 
		   	 $balanceAmount=$Row['balanceAmount']; 
		   	 $shortAmount=$Row['shortAmount']; 
		   	 $excessAmount=$Row['excessAmount']; 
		   	 $debitFlag=$Row['debitFlag']; 
		   	 $debitId=$Row['debitId'];
		   	 $debitAmt=$Row['debitAmt']; 
		   	 $cash_discount_value=$Row['cash_discount_value']; 
		   	 $payment_status_value=$Row['payment_status_value']; 
		   	 $payment_status=$Row['payment_status']; 
		   	 $status=$Row['status']; 
		   	 $Remarks=$Row['Remarks']; 
		   
		   	 
		   	 $newObj = new Payment_Details_Model($trxnId,$trxndId,$invoiceNo,$invoiceDate,$dueDate,$invoiceAmount,$payabledAmount,$receivedAmount,$balanceAmount ,$shortAmount,$excessAmount,$debitFlag,$debitId,$debitAmt,$cash_discount_value,$payment_status,$payment_status_value,$status,$Remarks);
			
		   	  $objArray[$i]=$newObj;
               $i++;
		   }
		   return $objArray;	
	   }
       
       public static function InsertPaymentDetails($trxnId,$invoice_no,$invoice_date,$due_date,$invoice_amt,$short_amt,$excess_amt,$discount_amt,$debitFlag,$debitId,$debit_amt,$invoice_status,$payabled_amt,$balance_amt,$received_amt,$remark,$USERID)
       {
   	      $insertDate=date("Y-m-d H:i:s");
   	      //$invoice_date1= MultiweldParameter::xFormatDate($invoice_date);
   	     // $due_date1= MultiweldParameter::xFormatDate($due_date);
   	     
		//added on 02-JUNE-2016 due to Handle Special Character		
		$remark = mysql_escape_string($remark);
   	     
   	      $Query = "INSERT INTO trxn_detail (trxnId,invoiceNo,invoiceDate,dueDate,invoiceAmount,payabledAmount,receivedAmount,balanceAmount,shortAmount,excessAmount,cash_discount_value,debitId,debitFlag,debitAmt,payment_status,createId,createTime,status,Remarks)
   	                VALUES('$trxnId','$invoice_no','$invoice_date','$due_date','$invoice_amt','$payabled_amt','$received_amt','$balance_amt','$short_amt','$excess_amt','$discount_amt','$debitId','$debitFlag','$debit_amt','$invoice_status','$USERID','$insertDate','created','$remark')";
   	          
   	                $Result = DBConnection::InsertQuery($Query);
          if($Result > 0){
            return QueryResponse::YES;
           }
           else{
            return QueryResponse::NO;
          }
       }
    public static function InsertTRXNDetails($trxnId,$invoiceNo,$invoiceDate,$dueDate,$invoiceAmount,$receivedAmount ,$short_access ,$debitFlag,$cash_discount_value,$debitAmt,$payment_status,$Remarks){
		//$po_deliverybydate1=Purchaseorder_Model::xFormatDate($po_deliverybydate);
		if($short_access==""){
			$short_access="NULL";
		}
		if($debitFlag==""){
			$debitFlag="N";
		}
		if($debitFlag=="N"){
			$debitAmt="NULL";
		}		
		if($cash_discount_value==""){
			$cash_discount_value="NULL";
		}
		
		//added on 02-JUNE-2016 due to Handle Special Character		
		$Remarks = mysql_escape_string($Remarks);
		
		$Query = "INSERT INTO trxn_detail (trxnId,invoiceNo,invoiceDate,dueDate,invoiceAmount,receivedAmount ,short_access ,debitFlag,cash_discount_value,debitAmt,payment_status,Remarks) VALUES ($trxnId,'$invoiceNo','$invoiceDate','$dueDate',$invoiceAmount,$receivedAmount ,$short_access ,'$debitFlag',$cash_discount_value,$debitAmt,'$payment_status','$Remarks')";
        echo $Query;
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function loadInvoices($BUYERID,$INVOICENO){
    	    $opt=" AND noe.payment_status='P' ";
    	    if($INVOICENO!='')
    	    {
			 $opt=" AND noe.oinvoice_No='$INVOICENO'";	
			}
			$Query="SELECT oinvoice_No as invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,noe.oinv_date,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)as dueDate,po.bpoId FROM outgoinginvoice_excise  AS noe,purchaseorder AS po WHERE noe.pono=po.bpoId  AND noe.BuyerId='$BUYERID ' $opt
			
			UNION ALL SELECT oinvoice_No as  invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,noe.oinv_date,po.credit_period,DATE_FORMAT(DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY),'%d/%m/%Y')dueDate,po.bpoId FROM outgoinginvoice_nonexcise AS noe,purchaseorder AS po WHERE noe.pono=po.bpoId  AND noe.BuyerId='$BUYERID' $opt ";
      //  echo $Query;
     
        $result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $trxnId = $Row['bpoId'];
            $invoiceNo= $Row['invoiceNo'];
            $invoiceDate= $Row['invoiceDate'];            
            $dueDate= $Row['dueDate'];
            $invoiceAmount=$Row['invoiceAmount'];
            $balanceAmount=$Row['balanceAmount'];
            $newObj = new Payment_Details_Model($trxnId,$trxndId,$invoiceNo,$invoiceDate,$dueDate,$invoiceAmount,$payabledAmount,$receivedAmount,$balanceAmount ,$shortAmount,$excessAmount,$debitFlag,$debitId,$debitAmt,$cash_discount_value,$payment_status,$payment_status_value,$status,$Remarks);
            $objArray[$i] = $newObj;
            $i++;
		}
		
		
		
		return $objArray;

	}
		
	  public static function loadInvoicesNew($BUYERID,$INVOICENO,$finyears){
    	   
    	    $opt=" AND noe.payment_status='P' ";
    	    	   
    	    $financialyear = "";
			 if(count($finyears) == 1){
				$financialyear = "'".$finyears[0]."'";
			}else if(count($finyears) == 2){
				$financialyear = "'".$finyears[0]."'"." OR oim.finyear ="."'".$finyears[1]."'";
			}else if(count($finyears) == 3){
				$financialyear =  "'".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."'";
			}else if(count($finyears) == 4){
				$financialyear =  "'".$finyears[0]."'"." OR oim.finyear ='".$finyears[1]."' OR oim.finyear ="."'".$finyears[2]."' OR oim.finyear ="."'".$finyears[3]."'";
			}
					
			
    	    if($INVOICENO!='')
    	    {
			 $opt=" AND noe.oinvoice_No='$INVOICENO'";	
			}
			
			
			$Query="SELECT oinvoice_No as invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,noe.oinv_date,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)as dueDate,po.bpoId FROM outgoinginvoice_excise  AS noe,purchaseorder AS po,outgoinginvoice_excise_mapping AS oim WHERE noe.pono=po.bpoId AND noe.oinvoice_exciseID=oim.inner_outgoingInvoiceEx AND noe.BuyerId='$BUYERID ' AND (oim.finyear = $financialyear) $opt
			
			UNION ALL SELECT oinvoice_No as  invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,noe.oinv_date,po.credit_period,DATE_FORMAT(DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY),'%d/%m/%Y')dueDate,po.bpoId FROM outgoinginvoice_nonexcise AS noe,purchaseorder AS po,outgoinginvoice_nonexcise_mapping AS oim WHERE noe.pono=po.bpoId AND noe.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx AND noe.BuyerId='$BUYERID' AND (oim.finyear = $financialyear) $opt ";
      //  echo $Query; exit;
     
        $result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $trxnId = $Row['bpoId'];
            $invoiceNo= $Row['invoiceNo'];
            $invoiceDate= $Row['invoiceDate'];            
            $dueDate= $Row['dueDate'];
            $invoiceAmount=$Row['invoiceAmount'];
            $balanceAmount=$Row['balanceAmount'];
            $newObj = new Payment_Details_Model($trxnId,$trxndId,$invoiceNo,$invoiceDate,$dueDate,$invoiceAmount,$payabledAmount,$receivedAmount,$balanceAmount ,$shortAmount,$excessAmount,$debitFlag,$debitId,$debitAmt,$cash_discount_value,$payment_status,$payment_status_value,$status,$Remarks);
            $objArray[$i] = $newObj;
            $i++;
		}
		
		
		
		return $objArray;

	}
	
	public static function updatePaymentStatus($INVOICENO,$BALANCE_AMOUNT,$PAYMENT_STATUS)
	{
		$SELQueryExcise=" select count(*) as cn from outgoinginvoice_excise WHERE oinvoice_No='$INVOICENO'";
		
		$SELQueryNonExcise=" select count(*) as cn from outgoinginvoice_nonexcise  WHERE oinvoice_No='$INVOICENO'";
		$result = DBConnection::SelectQuery($SELQueryExcise);
		$Row=mysql_fetch_row($result);
		if($Row[0]>0)
		{
		  $Query="UPDATE outgoinginvoice_excise  SET balanceAmount='$BALANCE_AMOUNT',payment_status='$PAYMENT_STATUS' WHERE oinvoice_No='$INVOICENO' ";	
		  $Result=DBConnection::UpdateQuery($Query);
		  if($Result==QueryResponse::SUCCESS){
				return QueryResponse::YES;
		  }
		  else{
				return QueryResponse::NO;
		  }
		}
		else
		{
			$result = DBConnection::SelectQuery($SELQueryNonExcise);
		    $Row=mysql_fetch_row($result);
		    if($Row[0]>0)
		    {
		       $Query="UPDATE outgoinginvoice_nonexcise  SET balanceAmount='$BALANCE_AMOUNT',payment_status='$PAYMENT_STATUS' WHERE oinvoice_No='$INVOICENO' ";	
		       $Result=DBConnection::UpdateQuery($Query);
		       if($Result==QueryResponse::SUCCESS){
				return QueryResponse::YES;
		       }
		       else{
				return QueryResponse::NO;
		       }
		    }
		}
		return QueryResponse::NO;
		
	}
	
	public static function updatePaymentDetailStatus($trxndId,$status)
	{
		$Query="update trxn_detail set status='$status' where trxndId='$trxndId'";
		
		$Result= DBConnection::UpdateQuery($Query);
		if($Result==QueryResponse::SUCCESS){
				return QueryResponse::YES;
		}
		else{
				return QueryResponse::NO;
		}
	}
	
	public static function DeletePaymentDetails($trxnId)
	{
		
        $Query = "DELETE FROM trxn_detail WHERE trxnId = $trxnId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
   
	}

}
?>

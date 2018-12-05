<?php
class Transaction_Model
{
    public $_transactionId;
    public $_refId;
    public $_transactionType;
    public $_transactionDate;
    public $_ftransaction;
    public $_ttransaction;
    public function __construct($transactionId,$refId,$transactionType,$transactionDate,$ftransaction,$ttransaction)
    {
        $this->_transactionId=$transactionId;
        $this->_refId=$refId;
        $this->_transactionType=$transactionType;
        $this->_transactionDate=$transactionDate;
        $this->_ftransaction=$ftransaction;
        $this->_ttransaction=$ttransaction;
    }
    public static function LoadTransaction($transactionId)
    {
        if($transactionId>0)
        {
            $result=GetTransaction($transactionId);
        }
        else{
            $result=GetAllTransaction($transactionId);
        }
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		$transactionId=$Row['transactionId'];
            $refId=$Row['refId'];
            $transactionType=$Row['transactionType'];
            $transactionDate=$Row['transactionDate'];
            $ftransaction=$Row['ftransaction'];
            $ttransaction=$Row['ttransaction'];
            $newObj = new Transaction_Model($transactionId,$refId,$transactionType,$transactionDate,$ftransaction);
            $objArray[$i] = $newObj;
            $i++;

		}
		return $objArray;
    }
    public static function LoadTransactionByRefID($refid,$type)
    {
        $Query="SELECT * FROM transaction WHERE refId = $refid AND transactionType ='$type'";
        $Result = DBConnection::SelectQuery($Query);
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    		$transactionId=$Row['transactionId'];
            $refId=$Row['refId'];
            $transactionType=$Row['transactionType'];
            $transactionDate=$Row['transactionDate'];
            $ftransaction=$Row['ftransaction'];
            $ttransaction=$Row['ttransaction'];
            $newObj = new Transaction_Model($transactionId,$refId,$transactionType,$transactionDate,$ftransaction);
            $objArray[$i] = $newObj;
            $i++;

		}
		return $objArray;
    }
    public static function InsertTransaction($refId,$transactionType,$transactionDate,$ftransaction,$kye = null)
    {
			
        session_start();
        $UserId=$_SESSION["USER"];
        $ttransaction=$_SESSION["MULTIWELD"];
        $Query="INSERT INTO transaction(refId, transactionType, transactionDate, ftransaction, ttransaction, UserId,transactionKey) VALUES ($refId,'$transactionType', '$transactionDate',$ftransaction,$ttransaction,'$UserId','".$kye."')";
		
        //echo($Query);
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function GetTransaction($transactionId)
    {
        $Query="SELECT * FROM transaction WHERE transactionId=$transactionId";
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function GetAllTransaction()
    {
        $Query="SELECT * FROM transaction";
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
    }

    public static function DeleteTransaction($txnId){
        $Query = "delete from transaction where transactionId = $txnId";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }

}
class TransactionDetails_Model
{
    public $_transactiondId;
    public $_transactionId;
    public $_code_partNo;
    public $_excise_nonexcise_TAG;
    public $_qty;
    public $_debit_credit_flag;
    public function __construct($transactiondId,$transactionId,$code_partNo,$excise_nonexcise_TAG,$qty,$debit_credit_flag)
    {
        $this->_transactiondId=$transactiondId;
        $this->_transactionId=$transactionId;
        $this->_code_partNo=$code_partNo;
        $this->_excise_nonexcise_TAG=$excise_nonexcise_TAG;
        $this->_qty=$qty;
        $this->_debit_credit_flag=$debit_credit_flag;
    }
    public static function LoadTransactionDetails($transactionId)
    {
        if($transactionId>0)
        {
            $result=GetTransactionDetails($transactionId);
        }
        else{
            $result=GetAllTransactionDetails($transactionId);
        }
        $objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		$transactiondId=$Row['transactiondId'];
            $transactionId=$Row['transactionId'];
            $code_partNo=$Row['code_partNo'];
            $excise_nonexcise_TAG=$Row['excise_nonexcise_TAG'];
            $qty=$Row['qty'];
            $debit_credit_flag=$Row['debit_credit_flag'];



            $newObj = new TransactionDetails_Model($transactiondId,$transactionId,$code_partNo,$excise_nonexcise_TAG,$qty,$debit_credit_flag);
            $objArray[$i] = $newObj;
            $i++;

		}
		return $objArray;
    }
     public static function InsertTransactionDetails($transactionId,$code_partNo,$excise_nonexcise_TAG,$qty,$debit_credit_flag,$balance_qty,$query_type=null)
    {
		$new_qty = 0;
		if($query_type == "UPDATE") {
			$pre_qty = self::GetTotalPreviousQuentity($transactionId,$code_partNo,$excise_nonexcise_TAG,$debit_credit_flag);
			
			if($pre_qty['qty'] == 0){
					$new_qty = 0;
			 } else if($pre_qty['balance_qty'] > $balance_qty){		 
					$new_qty = '-'. ($pre_qty['balance_qty'] - $balance_qty);			
			 }else{
					$new_qty = '+'. ($balance_qty - $pre_qty['balance_qty']);	
			 }
		 }		
			$Query="INSERT INTO transaction_detail(transactionId, code_partNo, excise_nonexcise_TAG, qty, debit_credit_flag,balance_qty,update_qty) VALUES ($transactionId,$code_partNo,'$excise_nonexcise_TAG',$qty,'$debit_credit_flag',$balance_qty,'$new_qty')";
			
			$Result = DBConnection::InsertQuery($Query);
			if($Result > 0){
				return $Result;
			}
			else{
				return QueryResponse::NO;
			}
		
     }
	
     public static function GetTransaction($transactionId)
     {
         $Query="SELECT * FROM transaction_detail WHERE transactionId=$transactionId";
         $Result = DBConnection::SelectQuery($Query);
		  if( $Result > 0){
			$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
			return $Row;
		 }else{
			return 0;
		 }
     }
     public static function GetAllTransaction()
     {
         $Query="SELECT * FROM transaction_detail";
         $Result = DBConnection::SelectQuery($Query);;
         return $Result;
     }
     public static function DeleteTransactionDetails($txnId){
        $Query = "delete from transaction_detail where transactionId = $txnId";
     
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
	
	 public static function GetTotalPreviousQuentity($transactionId,$codepartNo,$type,$debit_credit_flag)
     {
		$qty = 0;
         $Query="SELECT qty,balance_qty FROM transaction_detail WHERE  excise_nonexcise_TAG='".$type."' AND code_partNo='".$codepartNo."' AND debit_credit_flag='".$debit_credit_flag."' Order By transactionId DESC limit 1";
		//echo  $Query;
		$auentiry = array();
         $qty = DBConnection::SelectQuery($Query);		
		 if( $qty > 0){
			 while ($Row = mysql_fetch_array($qty, MYSQL_ASSOC)) {
				return $Row;
				break;
           	}
		 }else{	
			$Row['qrt'] = 0;
			return $Row['qrt'];       
		}
	}
}

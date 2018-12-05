<?php
class Outgoing_Inventory_Model
{
  public $code_PartNo; 
  public $incomingInvNo;
  public $outgoingInvNo;
  public $outgoingInvDt; 
  public $outgoingInvTyp;
  public $BuyerId; 
  public $ExciseQty; 
  public $NonExciseQty;

    public static function Insert_Outgoing_Inventory($code_PartNo, $incomingInvNo,$outgoingInvNo, $outgoingInvDt, $outgoingInvTyp, $BuyerId,$ExciseQty, $NonExciseQty){                         
        $date = date("Y-m-d");    
        
        //added on 01-JUNE-2016 due to Handle Special Character
		$incomingInvNo = mysql_escape_string($incomingInvNo);
                                                                                                                          
        $Query = "INSERT INTO outgoinginventory (code_PartNo, incomingInvNo, outgoingInvNo, outgoingInvDt, outgoingInvTyp, BuyerId, ExciseQty, NonExciseQty) VALUES ($code_PartNo,$incomingInvNo,$outgoingInvNo,'$outgoingInvDt','$outgoingInvTyp',$BuyerId,$ExciseQty, $NonExciseQty)";
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public function __construct($code_PartNo, $incomingInvNo,$outgoingInvNo, $outgoingInvDt, $outgoingInvTyp, $BuyerId,$ExciseQty, $NonExciseQty){
    
        $this->code_PartNo = $code_PartNo;
        $this->incomingInvNo = $incomingInvNo;
        $this->outgoingInvNo = $outgoingInvNo; 
        $this->outgoingInvDt = $outgoingInvDt; 
        $this->outgoingInvTyp = $outgoingInvTyp; 
        $this->BuyerId = $BuyerId;  
        $this->ExciseQty = $ExciseQty; 
        $this->NonExciseQty = $NonExciseQty;
	}
    public static function  LoadOutgoing_Inventory($code_PartNo,$outgoingInvTyp)
	{
        $Query = "SELECT * from outgoinginventory WHERE code_PartNo = $code_PartNo AND outgoingInvTyp = '$outgoingInvTyp'"; 
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $code_PartNo = $Row['code_PartNo']; 
            $incomingInvNo = $Row['incomingInvNo'];
            $outgoingInvNo = $Row['outgoingInvNo'];
            $outgoingInvDt = $Row['outgoingInvDt'];
            $outgoingInvTyp = $Row['outgoingInvTyp']; 
            $BuyerId = $Row['BuyerId'];
            $ExciseQty = $Row['ExciseQty'];
            $NonExciseQty = $Row['NonExciseQty'];
            $newObj = new Incoming_Inventory_Model($code_PartNo, $incomingInvNo, $outgoingInvNo, $outgoingInvDt, $outgoingInvTyp,$BuyerId, $ExciseQty, $NonExciseQty);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
}

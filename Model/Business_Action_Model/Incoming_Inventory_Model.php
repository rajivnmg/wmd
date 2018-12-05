<?php
class Incoming_Inventory_Model
{
   public $_entryDId;
   public $code_PartNo;
   public $incomingInvNo;
   public $incomingInvDt;
   public $incomingInvTyp;
   public $principalId;
   public $supplrId;
   public $ExciseQty;
   public $NonExciseQty;
   public $principal_inv_no;

   public static function Insert_Incoming_Inventory($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty){
        $date = date("Y-m-d");
		if($principalId==NULL){
			$principalId='NULL';
		}
        if($supplrId==NULL){
			$supplrId='NULL';
		}
         $Query = "INSERT INTO incominginventory (entryDId,code_PartNo, incomingInvNo, incomingInvDt, incomingInvTyp, principalId, supplrId, qty) VALUES ($_entryDId,$code_PartNo,'$incomingInvNo','$incomingInvDt','$incomingInvTyp',$principalId,$supplrId, $ExciseQty)"; 
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
     public function __construct($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty,$principal_inv_no){
        $this->_entryDId = $_entryDId;
        $this->code_PartNo = $code_PartNo;
        $this->incomingInvNo = $incomingInvNo;
        $this->incomingInvDt = $incomingInvDt;
        $this->incomingInvTyp = $incomingInvTyp;
        $this->principalId = $principalId;
        $this->principal_inv_no=$principal_inv_no;
        $this->supplrId = $supplrId;
        $this->ExciseQty = $ExciseQty;
        $this->NonExciseQty = $NonExciseQty;
	}
	public static function GetIncomingInventoryList($code_PartNo,$incomingInvTyp)
	{
		$Query="";
	    $Query="SELECT ici.entryDId as _entryDId,ici.ExciseQty,ince.principal_inv_no from incominginventory as ici inner join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  inner join incominginvoice_excise_detail as ined on ined.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.incomingInvTyp = '$incomingInvTyp' AND ici.ExciseQty > 0";
       
        $Result=DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;	
		while ($Row=mysql_fetch_array($Result,MYSQL_ASSOC)) {
			$objArray[$i]=$Row;
			$i++;
	    }
	    return $objArray;		
	}
    public static function  LoadIncoming_InventoryForBilling($code_PartNo,$incomingInvTyp)
	{
		/* BOF to show Invoice Number in Outgoing Invoice by Ayush Giri on 20-07-2017 */ 
        //$Query = "SELECT ici.*,ince.principal_inv_no from incominginventory as ici left join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  left join incominginvoice_excise_detail as ined on ined.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.qty > 0"; 
		
		//$Query = "SELECT ici.incoming_inventory_id, ici.entryDId, ici.code_PartNo, ici.	incomingInvNo, ici.incomingInvDt, ici.incomingInvTyp, ici.principalId, ici.supplrId, ici.qty, if(ince.principal_inv_no is NULL, ici.principal_inv_no, ince.principal_inv_no) AS PRINCIPAL_INVOICE_NUMBER  from incominginventory as ici left join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  left join incominginvoice_excise_detail as ined on ined.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.qty > 0";
		
		$Query = "SELECT ici.incoming_inventory_id, ici.entryDId, ici.code_PartNo, ici.	incomingInvNo, ici.incomingInvDt, ici.incomingInvTyp, ici.principalId, ici.supplrId, ici.qty, if(ici.principal_inv_no is NULL, ince.principal_inv_no, ici.principal_inv_no) AS PRINCIPAL_INVOICE_NUMBER  from incominginventory as ici left join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  left join incominginvoice_excise_detail as ined on ined.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.qty > 0";
		/* EOF to show Invoice Number in Outgoing Invoice by Ayush Giri on 20-07-2017 */ 
        
        //~ if($incomingInvTyp=="E")
        //~ {
           //~ $Query = "SELECT ici.*,ince.principal_inv_no from incominginventory as ici inner join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  inner join incominginvoice_excise_detail as ined on ined.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.incomingInvTyp = '$incomingInvTyp' AND ici.ExciseQty > 0";
        //~ }
        //~ else if($incomingInvTyp=="I")
        //~ {
           //~ $Query = "SELECT ici.*,ince.principal_inv_no from incominginventory as ici inner join incominginvoice_excise as ince on ici.incomingInvNo=ince.entryId  inner join incominginvoice_without_excise_detail as iwed on iwed.entryDId = ici.entryDId  WHERE ici.code_PartNo = $code_PartNo AND ici.incomingInvTyp = '$incomingInvTyp' AND ici.NonExciseQty > 0";
        //~ }
        
        //echo $Query; exit;
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
			//print_r($Row);exit;
            //$_entryDId = $Row['entryDId'];
            //$_entryDId = $Row['incomingInvNo'];
			$_entryDId = $Row['incoming_inventory_id'];
            $code_PartNo = $Row['code_PartNo'];
            $incomingInvNo = $Row['incomingInvNo'];
            $incomingInvDt = $Row['incomingInvDt'];
            $incomingInvTyp = $Row['incomingInvTyp'];
            $principalId = $Row['principalId'];
            $supplrId = $Row['supplrId'];
            $ExciseQty = $Row['qty'];
            $NonExciseQty = $Row['qty'];
            $principal_inv_no=$Row['PRINCIPAL_INVOICE_NUMBER'];
            $newObj = new Incoming_Inventory_Model($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty,$principal_inv_no);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
  public static function  LoadIncoming_Inventory($code_PartNo,$incomingInvTyp)
	{
        $Query = "SELECT * from incominginventory WHERE code_PartNo = $code_PartNo AND incomingInvTyp = '$incomingInvTyp'";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_entryDId = $Row['entryDId'];
            $code_PartNo = $Row['code_PartNo'];
            $incomingInvNo = $Row['incomingInvNo'];
            $incomingInvDt = $Row['incomingInvDt'];
            $incomingInvTyp = $Row['incomingInvTyp'];
            $principalId = $Row['principalId'];
            $supplrId = $Row['supplrId'];
            $ExciseQty = $Row['ExciseQty'];
            $NonExciseQty = $Row['NonExciseQty'];
            $principal_inv_no="";
            $newObj = new Incoming_Inventory_Model($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty,$principal_inv_no);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function  LoadIncoming_InventoryWithPrincipal($code_PartNo,$incomingInvTyp)
	{
        $Query = "SELECT inc.*,ince.principal_inv_no from incominginventory as inc inner join incominginvoice_excise as ince on inc.incomingInvNo=ince.entryId WHERE inc.code_PartNo = $code_PartNo AND inc.incomingInvTyp = '$incomingInvTyp'";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_entryDId = $Row['entryDId'];
            $code_PartNo = $Row['code_PartNo'];
            $incomingInvNo = $Row['incomingInvNo'];
            $principal_inv_no = $Row['principal_inv_no'];
            $incomingInvDt = $Row['incomingInvDt'];
            $incomingInvTyp = $Row['incomingInvTyp'];
            $principalId = $Row['principalId'];
            $supplrId = $Row['supplrId'];
            $ExciseQty = $Row['ExciseQty'];
            $NonExciseQty = $Row['NonExciseQty'];
            $newObj = new Incoming_Inventory_Model($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty,$principal_inv_no);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
     public static function LoadIncoming_Inventory_qty($invno,$incomingInvTyp)
    {
        $Query = "SELECT * from incominginventory WHERE incomingInvNo = $invno AND incomingInvTyp = '$incomingInvTyp'";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $_entryDId = $Row['entryDId'];
            $code_PartNo = $Row['code_PartNo'];
            $incomingInvNo = $Row['incomingInvNo'];
            $principal_inv_no = $Row['principal_inv_no'];
            $incomingInvDt = $Row['incomingInvDt'];
            $incomingInvTyp = $Row['incomingInvTyp'];
            $principalId = $Row['principalId'];
            $supplrId = $Row['supplrId'];
            $ExciseQty = $Row['ExciseQty'];
            $NonExciseQty = $Row['NonExciseQty'];
            $newObj = new Incoming_Inventory_Model($_entryDId,$code_PartNo, $incomingInvNo, $incomingInvDt, $incomingInvTyp, $principalId,$supplrId, $ExciseQty, $NonExciseQty,$principal_inv_no);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
    }
    public static function  GetQuantity($INVOICEDID,$incomingInvTyp)
	{	
		//$Query = "SELECT qty FROM incominginventory WHERE entryDId = $INVOICEDID";
		$Query = "SELECT qty FROM incominginventory WHERE incoming_inventory_id = $INVOICEDID";
        
        //comment by gajendra
        //~ if($incomingInvTyp == "E")
         //~ {
             //~ $Query = "SELECT ExciseQty FROM incominginventory WHERE entryDId = $INVOICEDID";
         //~ }
         //~ else if($incomingInvTyp == "N")
         //~ {
             //~ $Query = "SELECT NonExciseQty FROM incominginventory WHERE entryDId = $INVOICEDID";
         //~ }
         //echo $Query;
         //end
         $Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $Qty = empty($Row['qty'])?'0':$Row['qty'];
          //comment by gajendra
         //~ if($incomingInvTyp == "E")
         //~ {
             //~ $Qty = $Row['ExciseQty'];
         //~ }
         //~ else if($incomingInvTyp == "N")
         //~ {
             //~ $Qty = $Row['NonExciseQty'];
         //~ }
         //end
         return $Qty;
	}
    public static function Update_Incoming_Inventory($entryDId,$Type,$Qty,$EditTag){
		//$Query = "SELECT qty FROM incominginventory WHERE entryDId = $entryDId";
		$Query = "SELECT qty FROM incominginventory WHERE incoming_inventory_id = $entryDId";
        //~ $Query = "";
         //~ if($Type == "E")
         //~ {
             //~ $Query = "SELECT ExciseQty FROM incominginventory WHERE entryDId = $entryDId";
         //~ }
         //~ else if($Type == "N")
         //~ {
             //~ $Query = "SELECT NonExciseQty FROM incominginventory WHERE entryDId = $entryDId";
         //~ }
         //echo $Query;
         $Result = DBConnection::SelectQuery($Query);

         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         
         $Prev_Qty = empty($Row['qty'])?0:$Row['qty'];
         //~ if($Type == "E")
         //~ {
             //~ $Prev_Qty = $Row['ExciseQty'];
         //~ }
         //~ else if($Type == "N")
         //~ {
             //~ $Prev_Qty = $Row['NonExciseQty'];
         //~ }
         $NewQty = 0;
         if($EditTag == "A")
         {
             $NewQty = $Prev_Qty + $Qty;
         }
         else if($EditTag == "S")
         {
             if($Prev_Qty > $Qty || $Prev_Qty == $Qty)
             {
                 $NewQty = $Prev_Qty - $Qty;
             }
         }
         //$Query = "UPDATE incominginventory SET qty = $NewQty WHERE  entryDId = $entryDId";
		 $Query = "UPDATE incominginventory SET qty = $NewQty WHERE  incoming_inventory_id = $entryDId";
         //~ if($Type=="E"){
			//~ $Query = "UPDATE incominginventory SET ExciseQty = $NewQty WHERE  entryDId = $entryDId";
		//~ }
        //~ if($Type=="N"){
            //~ $Query = "UPDATE incominginventory SET NonExciseQty = $NewQty WHERE  entryDId = $entryDId";
		//~ }
		
		
        $Result = DBConnection::UpdateQuery($Query);
	
        return $Result;
    }

    public static function DeleteIncomingInventoryByEntryDId($entryDId){
        $Query = "delete from incominginventory where entryDId = $entryDId";
		
     
        //echo($Query);
        $Result = DBConnection::UpdateQuery($Query);
		//echo $entryDId; exit;
        return $Result;
    }


    public static function DeleteIncomingInventory($incomingInvNo, $incomingInvTyp){
        $Query = "delete from incominginventory where incomingInvNo = $incomingInvNo AND incomingInvTyp = '$incomingInvTyp'";
        //echo($Query);
        $Result = DBConnection::UpdateQuery($Query);
    }

    public static function Delete_Incoming_Inventory($code_PartNo, $incomingInvNo, $incomingInvTyp){
        $Query = "delete from incominginventory where code_PartNo = $code_PartNo AND incomingInvNo = $incomingInvNo AND incomingInvTyp = '$incomingInvTyp'";
        //echo($Query);
        $Result = DBConnection::UpdateQuery($Query);
    }
}

<?php
class TaxMasterModel  {
	
   public $_TaxId;
   public $_TAXrate;
   public $_TAXdesc;
    
	public function __construct($taxId,$TAXrate, $TAXdesc){
		$this->_TaxId = $taxId;
		$this->_TAXrate = $TAXrate;
		$this->_TAXdesc = $TAXdesc;
	}
	public static function  LoadAll($TAXID)
	{
	if($TAXID>0)
	{
		$result = self::GetTaxDetails($TAXID);
	}
	else{
		$result = self::GetTaxList();
	}
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_TaxId = $Row['tax_id'];
		 	$_TAXrate = $Row['tax_rate'];
			$_TAXdesc = $Row['tax_description'];
		 	$newObj = new TaxMasterModel($_TaxId,$_TAXrate, $_TAXdesc);
		 	$objArray[$i] = $newObj;
		 	$i++;
		}
		return $objArray;
	}
	public static function InsertTax($TAXrate, $TAXdesc){
		$Resp = self::CheckTaxName('-1',$TAXrate);
		if($Resp == QueryResponse::NO){
			$date = date("Y-m-d");
			$TAXrate = mysql_escape_string($TAXrate);
			$TAXdesc = mysql_escape_string($TAXdesc);
			$Query = "INSERT INTO tax_master (tax_rate, tax_description) VALUES ('".$TAXrate."', '".$TAXdesc."')"; 
			$Result = DBConnection::InsertQuery($Query);
			//if($Result == QueryResponse::SUCCESS){
			if($Result != QueryResponse::ERROR){
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
		}
	    else{
			return QueryResponse::NO;
		}
	}
	private static function CheckTaxName($TAXID, $TAXrate){
		$Query = "SELECT COUNT(tax_rate) AS TAX_COUNT FROM tax_master WHERE tax_rate = '$TAXrate' AND tax_id != '".$TAXID."'";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['TAX_COUNT'] > 0){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateTax($TAXID, $TAXrate, $TAXdesc){
		$TAXrate = mysql_escape_string($TAXrate);
		$TAXdesc = mysql_escape_string($TAXdesc);
		$Query = "UPDATE tax_master SET tax_rate = '".$TAXrate."',tax_description = '".$TAXdesc."' WHERE tax_id = '".$TAXID."'"; 
        $Resp = self::CheckTaxName($TAXID, $TAXrate);
        if($Resp == QueryResponse::NO){
			$Result = DBConnection::UpdateQuery($Query);
            if($Result == QueryResponse::SUCCESS){
                return QueryResponse::YES;
            }
            else{
                return QueryResponse::NO;
            }
		}
	    else{
			return QueryResponse::NO;
		}
	}
	public static function GetTaxList(){
		$Query = "SELECT tax_id, tax_rate, tax_description FROM tax_master"; 
        
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	public static function GetTaxDetails($TAXID)
	{
		$Query = "SELECT tax_id, tax_rate, tax_description FROM tax_master WHERE tax_id = $TAXID";  
        
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
}
?>

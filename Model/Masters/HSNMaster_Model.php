<?php
class HSNMasterModel  {
	
    public $_hsn_id;
    public $_hsn_code;
    public $_hsn_desc;
    public $_remark;
	public $_tax_id;
	public $_tax_rate;
    
	public function __construct($HSNId,$HSNcode,$HSNdesc,$remark,$TaxId,$TaxRate){
		$this->_hsn_id = $HSNId;
		$this->_hsn_code = $HSNcode;
        $this->_hsn_desc = $HSNdesc;
        $this->_remark = $remark;
		$this->_tax_id = $TaxId;
		$this->_tax_rate = $TaxRate;
	}
	
    public static function  LoadHSN($HSNID, $TAG=null,$ID=null, $tax=null, $hsn_code=null, $hsn_desc=null)
	{
		$result = null;
		if($HSNID > 0)
		{	
			$result = self::GetHSNDetails($HSNID, $TAG,$ID, $tax, $hsn_code,$hsn_desc);
		}
        else
        {
            $result = self::GetHSNList($TAG,$ID,$tax,$hsn_code,$hsn_desc);
        }
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_hsn_id = $Row['hsn_id'];
            $_hsn_code = $Row['hsn_code'];
            $_hsn_desc = $Row['hsn_description'];
            $_remark = $Row['hsn_remarks'];
			$_tax_id = $Row['tax_id'];
			$_tax_rate = $Row['TAX_RATE'];
            $newObj = new HSNMasterModel($_hsn_id,$_hsn_code,$_hsn_desc,$_remark,$_tax_id,$_tax_rate);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	/*public static function  LoadHSN($HSNID)
	{
        $result = null;
		if($HSNID > 0)
		{	
			$result = self::GetHSNDetails($HSNID);
		}
        else
        {
            $result = self::GetHSNList();
        }
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_hsn_id = $Row['hsn_id'];
            $_hsn_code = $Row['hsn_code'];
            $_hsn_desc = $Row['hsn_description'];
            $_remark = $Row['hsn_remarks'];
			$_tax_id = $Row['tax_id'];
			$_tax_rate = $Row['TAX_RATE'];
            $newObj = new HSNMasterModel($_hsn_id,$_hsn_code,$_hsn_desc,$_remark,$_tax_id,$_tax_rate);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}*/
	public static function InsertHSN($HSNCode, $HSNDESC, $Remark, $TaxId){
		$Resp = self::CheckHSNCode('-1',$HSNCode);
		if($Resp == QueryResponse::NO){
			$HSNCode = mysql_escape_string($HSNCode);
			$HSNDESC = mysql_escape_string($HSNDESC);
			$Remark = mysql_escape_string($Remark);	
			
			$date = date("Y-m-d");
			$Query = "INSERT INTO hsn_master (hsn_code, hsn_description, hsn_remarks, tax_id) VALUES ('".$HSNCode."','".$HSNDESC."','".$Remark."','".$TaxId."')";
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
	private static function CheckHSNCode($HSNID,$HSNCode){
		$Query = "SELECT COUNT(hsn_code) AS HSN_COUNT FROM hsn_master WHERE hsn_code = '".$HSNCode."' AND hsn_id != '".$HSNID."'";
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['HSN_COUNT'] > 0){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateHSNDiscription($HSNID,$HSNCode ,$HSNDESC,$Remark, $TaxId){
		// added on 01-JUNE-2016 due to Handle Special Character
		$HSNCode = mysql_escape_string($HSNCode);
		$HSNDESC = mysql_escape_string($HSNDESC);
		$Remark = mysql_escape_string($Remark);	
		$TaxId = mysql_escape_string($TaxId);	
		
		$Query = "UPDATE hsn_master SET hsn_description = '".$HSNDESC."',hsn_code = '".$HSNCode."', hsn_remarks = '".$Remark."', tax_id = '".$TaxId."' WHERE hsn_id = ".$HSNID;
		$Resp = self::CheckHSNCode($HSNID,$HSNCode);
		if($Resp == QueryResponse::NO){
			$Result = DBConnection::UpdateQuery($Query);
			
			if($Result == QueryResponse::SUCCESS){
				return QueryResponse::YES;
			}else{
				return QueryResponse::NO;
			}
		}
		else{
			return QueryResponse::NO;
		}
	}
	/* public static function GetHSNList(){
		//$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id ORDER BY hm.hsn_code"; 
		$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	} */
	
	public static function GetHSNList($TAG=null, $ID=null, $tax=null,$hsn_code=null, $hsn_desc=null){
		
		switch($TAG)
		{
			case "T":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE tm.tax_id = $tax";
			break;
			
			case "C":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_code LIKE '%$hsn_code%'";
			break;
			
			case "D":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_description LIKE '%$hsn_desc%'";
			break;
			
			default:
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id";
		}
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
		//return $Query;
	}
	
	public static function GetHSNDetails($HSNID, $TAG=null, $ID=null, $tax=null,$hsn_code=null, $hsn_desc=null)
	{
		switch($TAG)
		{
			case "T":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."' AND tm.tax_id = $tax";
			break;
			
			case "C":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."' AND hm.hsn_code LIKE '%$hsn_code%'";
			break;
			
			case "D":
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."' AND hm.hsn_description LIKE '%$hsn_desc%'";
			break;
			
			default:
			$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."'";
		}
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
		//return $Query;
	}
	
	/*public static function GetHSNDetails($HSNID)
	{
		//$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."' ORDER BY hm.hsn_code";
		$Query = "SELECT hm.hsn_id, hm.hsn_code, hm.hsn_description, hm.tax_id, hm.hsn_remarks, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_id = '".$HSNID."'";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}*/
	
	public static function GetTaxRateByHSNCode($hsn_code)
	{
		$Query = "SELECT CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM hsn_master hm JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE hm.hsn_code = '".$hsn_code."'";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
		               $objArray[] = $Row['TAX_RATE'];
		            $i++;
				}
		return $objArray;
	}
	
}

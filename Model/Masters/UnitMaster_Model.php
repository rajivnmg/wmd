<?php
   //include("../DBModel/Enum_Model.php");
   //include("../DBModel/DbModel.php");   
class UnitMasterModel  {
    
   public $_uniId;
   public $_unitName;
   private $_createdId;
   private $_createDate;
    
	public function __construct($uniId,$unitName,$createdId,$createDate){
		$this->_uniId = $uniId;
		$this->_unitName = $unitName;
		$this->_createdId = $createdId;
		$this->_createDate = $createDate;
	}
	public static function  LoadAll($unitid)
	{
	if($unitid>0)
	{
			$result = self::GetUnitDetatils($unitid);
			
	}
	else{
		$result = self::GetUnitList();
	}
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_uniId = $Row['UnitId'];
		 	$_unitName = $Row['UNITNAME'];
		 	$_createdId = $Row['USERID'];
		 	$_createDate = $Row['INSERTDATE'];
		 	$newObj = new UnitMasterModel($_uniId,$_unitName,$_createdId,$_createDate);
		 	$objArray[$i] = $newObj;
		 	$i++;
    		
		}
		return $objArray;
	}
	public static function InsertUnit($UnitName, $CreatorID){
		$Resp = self::CheckUnitName($UnitName);
		if($Resp == QueryResponse::NO){
			$date = date("Y-m-d");
			// added on 01-JUNE-2016 due to Handle Special Character
			$UnitName = mysql_escape_string($UnitName);
			$Query = "INSERT INTO unit_master (UNITNAME,USERID,INSERTDATE) VALUES ('$UnitName','$CreatorID','$date')"; 
			$Result = DBConnection::InsertQuery($Query);
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
	public static function CheckUnitName($UnitName){
		$Query = "SELECT UNITNAME FROM unit_master WHERE UNITNAME = '$UnitName'"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['UNITNAME'] == $UnitName){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateUnit($UnitID, $UnitName){
		// added on 01-JUNE-2016 due to Handle Special Character
		$UnitName = mysql_escape_string($UnitName);
		$Query = "UPDATE unit_master SET UNITNAME = '$UnitName' WHERE UNITID = '$UnitID'"; 
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetUnitList(){
		$Query = "SELECT * FROM unit_master"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
	public static function GetUnitDetatils($unitid)
	{
	$Query = "SELECT * FROM unit_master where UNITID='$unitid'"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
}
?>

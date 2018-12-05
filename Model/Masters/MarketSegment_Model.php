<?php
//include("../DBModel/Enum_Model.php");
//include("../DBModel/DbModel.php");

class MarketSegmentModel {
	
   public $_msId;
   public $_msName;
    
	public function __construct($msid,$msname){
		$this->_msId = $msid;
		$this->_msName = $msname;
		
	}
	public static function  LoadAll($msid)
	{
	if($msid>0)
	{
		$result = self::GetMsDetails($msid);
	}
	else{
		$result = self::GetMsList();
	}
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_msId = $Row['msid'];
		 	$_msName = $Row['msname'];
		 	 	
		 	$newObj = new MarketSegmentModel($_msId,$_msName);
		 	$objArray[$i] = $newObj;
		 	$i++;
    		
		}
		return $objArray;
	}
	public static function InsertMarketSegment($msName){
		$Resp = self::CheckMarketSegment($msName);
		if($Resp == QueryResponse::NO){
			// added on 01-JUNE-2016 due to Handle Special Character
			$msName = mysql_escape_string($msName);
			$Query = "INSERT INTO market_segment (msname) VALUES ('$msName')"; 
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
	private static function CheckMarketSegment($msName){
		$Query = "SELECT msname FROM market_segment WHERE msname = '$msName'"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['msname'] == $msName){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateMarketSegment($msId, $msName){
		// added on 01-JUNE-2016 due to Handle Special Character
		$msName = mysql_escape_string($msName);
		$Query = "UPDATE market_segment SET msname = '$msName' WHERE msid = '$msId'"; 
        $Resp = self::CheckStateName($msName);
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
	public static function GetMsList(){
			$Query = "SELECT * FROM market_segment"; 
       
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
	public static function GetMsDetails($msId)
	{
		$Query = "SELECT * FROM market_segment WHERE msid = $msId";  
        
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	public static function GetPoMsId($poid)
	{
		$Query = "SELECT * FROM purchaseorder WHERE bpoId = $poid";
        $msid=0;
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		$msid = $Row['msid']; 
		return $msid;
	}
}
?>

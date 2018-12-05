<?php
class LocationMasterModel  {
	
    public $_location_id;
    public $_city_id;
    public $_state_id;
    public $_locationName;
    public $_city_name;
    public $_state_name;
    private $_createdId;
    private $_createDate;
    
	public function __construct($locationid,$cityname,$statename,$locationName,$createdId,$createDate,$cityid,$stateid){
		$this->_location_id = $locationid;
        $this->_city_name = $cityname;
        $this->_state_name = $statename;
		$this->_locationName = $locationName;
		$this->_createdId = $createdId;
		$this->_createDate = $createDate;
        $this->_city_id = $cityid;
		$this->_state_id = $stateid;
	}
    public static function  SelectLocation($locationID)
	{
        $result = self::GetLocationDetails($locationID);
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_location_id = $Row['LocationId'];
            $_city_name = $Row['CityName'];
            $_state_name = $Row['StateName'];
            $_city_id = $Row['CityId'];
            $_state_id = $Row['StateId'];
            $_locationName = $Row['LocationName'];
            $newObj = new LocationMasterModel($_location_id,$_city_name,$_state_name,$_locationName,$_createdId,$_createDate,$_city_id,$_state_id);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function  LoadLocation($CityID,$start,$rp)
	{
        $result;
        if($CityID > 0)
        {
            $result = self::GetLocationListByCity($CityID);
        }
        else
        {
            $result = self::GetLocationList($start,$rp);
        }
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_location_id = $Row['LocationId'];
            $_city_name = $Row['CityName'];
            $_state_name = $Row['StateName'];
            $_city_id = $Row['CityId'];
            $_state_id = $Row['StateId'];
            $_locationName = $Row['LocationName'];
            $_createdId = "";
            $_createDate = "";
            $newObj = new LocationMasterModel($_location_id,$_city_name,$_state_name,$_locationName,$_createdId,$_createDate,$_city_id,$_state_id);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function InsertLocation($CityID, $StateID, $LocationName, $UserID){
		$Resp = self::CheckLocationName($LocationName,$CityID);
		if($Resp == QueryResponse::NO){
			// added on 01-JUNE-2016 due to Handle Special Character		
			$LocationName = mysql_escape_string($LocationName);
			$date = date("Y-m-d");
			$Query = "INSERT INTO location_master (CityId,StateId,LocationName,UserId,InsertDate) VALUES ('$CityID','$StateID','$LocationName','$UserID','$date')"; 
			$Result = DBConnection::InsertQuery($Query);
			if($Result > 0){
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
	private static function CheckLocationName($LocationName,$Cityid){
		$LocationName = mysql_escape_string($LocationName);
		$Query = "SELECT LocationName FROM location_master WHERE LocationName = '$LocationName' AND CityId = $Cityid"; 
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['LocationName'] == $LocationName){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateLocation($LocationID, $LocationName){
		$LocationName = mysql_escape_string($LocationName);
		$Query = "UPDATE location_master SET LocationName = '$LocationName' WHERE LocationId = $LocationID"; 
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetLocationList($start,$rp){
		$Query = "SELECT  lm.LocationId,lm.LocationName,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM  location_master as lm INNER JOIN  city_master as cm ON lm.CityId = cm.CityId INNER JOIN state_master as sm ON lm.StateId = sm.StateId LIMIT $start , $rp"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	public static function GetLocationListByCity($CityID){
		$Query = "SELECT  lm.LocationId,lm.LocationName,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM  location_master as lm INNER JOIN  city_master as cm ON lm.CityId = cm.CityId INNER JOIN state_master as sm ON lm.StateId = sm.StateId WHERE lm.CityId = $CityID"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    
    public static function GetLocationDetails($locationID){
		$Query = "SELECT  lm.LocationId,lm.LocationName,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM  location_master as lm INNER JOIN  city_master as cm ON lm.CityId = cm.CityId INNER JOIN state_master as sm ON lm.StateId = sm.StateId WHERE lm.LocationId = $locationID"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
	 public static function GetAllLocations(){
		$Query = "SELECT * FROM  location_master "; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    
}
?>

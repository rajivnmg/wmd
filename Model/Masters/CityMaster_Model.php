<?php
class CityMasterModel  {
	
    public $_city_id;
    public $_city_nameame;
    public $_state_id;
    public $_state_name;
    private $_createdId;
    private $_createDate;
    
	public function __construct($cityId,$cityName,$statename,$createdId,$createDate,$stateid){
		$this->_city_id = $cityId;
		$this->_city_nameame = $cityName;
        $this->_state_name = $statename;
		$this->_createdId = $createdId;
		$this->_createDate = $createDate;
        $this->_state_id = $stateid;
	}
	public static function  LoadCity($CITYID,$tag,$STATEID)
	{	
        $result;
        if($tag == "CITY")
        {
            if($CITYID > 0)
            {
                $result = self::GetCityDetails($CITYID);
             
            }
            else
            {
                $result = self::GetCityList();
            }
        }
        else if($tag == "STATE")
        {
            $result = self::GetCityListByState($STATEID);
        }
        
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_city_id = $Row['CityId'];
            $_city_nameame = $Row['CityName'];
            $_state_name = $Row['StateName'];
            $_state_id = $Row['StateId'];
            $_createdId = "";
            $_createDate = "";
            $newObj = new CityMasterModel($_city_id,$_city_nameame,$_state_name,$_createdId,$_createDate,$_state_id);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function InsertCity($CityName, $StateID, $CreatorID){
		$Resp = self::CheckCityByState($CityName,$StateID);
		if($Resp == QueryResponse::NO){
			$date = date("Y-m-d");
			// added on 01-JUNE-2016 due to Handle Special Character
			$CityName = mysql_escape_string($CityName);
			$Query = "INSERT INTO city_master (STATEID,CITYNAME,USERID,INSERTDATE) VALUES ('$StateID','".$CityName."','$CreatorID','$date')"; 
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
	private static function CheckCityByState($CityName, $StateID){
		// added on 01-JUNE-2016 due to Handle Special Character
		$UnitName = mysql_escape_string($UnitName);
		$Query = "SELECT CityName FROM city_master WHERE CityName = '".$UnitName."' AND StateId = $StateID"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['CityName'] == $CityName){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateCityName($CityID, $CityName){
		// added on 01-JUNE-2016 due to Handle Special Character
		$CityName = mysql_escape_string($CityName);
		$Query = "UPDATE city_master SET CITYNAME = '".$CityName."' WHERE CITYID = $CityID"; 
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetCityList(){
		$Query = "SELECT cm.CityId, cm.CityName ,sm.StateId, sm.StateName FROM city_master as cm INNER JOIN state_master AS sm ON cm.StateId = sm.StateId"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function GetCityDetails($CITYID){
		$Query = "SELECT cm.CityId, cm.CityName ,sm.StateId, sm.StateName FROM city_master as cm INNER JOIN state_master AS sm ON cm.StateId = sm.StateId WHERE cm.CityId = $CITYID"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
	public static function GetCityListByState($STATEID){
		$Query = "SELECT cm.CityId, cm.CityName ,sm.StateId, sm.StateName FROM city_master as cm INNER JOIN state_master AS sm ON cm.StateId = sm.StateId WHERE cm.StateId = $STATEID"; 
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    
}
?>

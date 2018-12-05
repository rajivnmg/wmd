<?php
class UserTypeModel{
   public $_Id;
   public $_groupName;
   private $_groupType;
  public function __construct($Id,$groupName,$groupType){
		$this->_Id=$Id;
		$this->_groupName=$groupName;
		$this->_groupType=$groupType;
		print_r($_REQUEST); exit;
		
	}
	public static function LoadAllUserType()
{
    $Query = "SELECT * FROM param WHERE PARAM_TYPE='USERTYPE'"; 
   
    $Result = DBConnection::SelectQuery($Query);
    $objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    		
    		$newObj = new UserTypeModel($Row['PARAM_ID'],$Row['PARAM_VALUE1'],$Row['PARAM_VALUE2']);
		 	$objArray[$i] = $newObj;
		 	$i++;
    		
		}
		  
		return $objArray;
   
}	
}

class GroupPermissionModel
{
  public $_Id;
  public $_GroupId;
  public $_Permission;
  public $_InsertDate;
  public $_UserId;
  public function __construct($Id,$GroupId,$Permission,$InsertDate,$UserId){
		$this->_Id=$Id;
		$this->$_GroupId=$groupName;
		$this->$_Permission=$groupType;
		$this->$_InsertDate=$InsertDate;
		$this->$_UserId=$UserId;
		
  }	
  
  public function getGroupPermission($GROUPD)
  {
  	 $Query="select * from group_permission where GROUPD='$GROUPD'";
  	 $Result=DBConnection::SelectQuery($Query);
  	 if(mysql_num_rows($Result)>0)
  	 {
	 	$Row = mysql_fetch_array($Result,MYSQL_ASSOC);
  	    return $Row;
	 }
	 else
	 {
	 	return FALSE;
	 }
  	 
  }
}
?>
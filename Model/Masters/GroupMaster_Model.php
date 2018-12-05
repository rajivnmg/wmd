<?php
class GroupMasterModel  {
	
    public $_group_id;
    public $_group_coad;
    public $_group_desc;
    private $_createdId;
    private $_createDate;
    public $_remark;
    
	public function __construct($groupId,$groupcoad,$groupdesc,$createdId,$createDate,$remark){
		$this->_group_id = $groupId;
		$this->_group_coad = $groupcoad;
        $this->_group_desc = $groupdesc;
		$this->_createdId = $createdId;
		$this->_createDate = $createDate;
        $this->_remark = $remark;
	}
    public static function GetLastGroupCode(){
        $Query = "SELECT GroupId FROM group_master ORDER BY GroupId DESC LIMIT 1"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        if($Row["GroupId"] == null)
            return "GC001";
        if($Row["GroupId"] > 0)
        {
            $id = $Row["GroupId"] + 1;
            $s = sprintf("%03d", $id);
            $s = "GC".$s;
            return $s;
        }else {
            return null;
        }
    }
	public static function  LoadGroup($GROUPID)
	{
        $result = null;
		if($GROUPID>0)
		{	
			$result = self::GetGroupDetails($GROUPID);
		}
        else
        {
            $result = self::GetGroupList();
        }
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
    		$_group_id = $Row['GroupId'];
            $_group_coad = $Row['GroupCode'];
            $_group_desc = $Row['GroupDesc'];
            $_createdId = $Row['UserId'];
            $_createDate = $Row['InsertDate'];
            $_remark = $Row['Remarks'];
            $newObj = new GroupMasterModel($_group_id,$_group_coad,$_group_desc,$_createdId,$_createDate,$_remark);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function InsertGroup($GroupCoad, $GroupDESC, $CreatorID, $Remark){
		$Resp = self::CheckGroupCoad($GroupCoad);
		if($Resp == QueryResponse::NO){
			// added on 01-JUNE-2016 due to Handle Special Character
			$GroupCoad = mysql_escape_string($GroupCoad);
			$GroupDESC = mysql_escape_string($GroupDESC);
			$Remark = mysql_escape_string($Remark);	
			
			$date = date("Y-m-d");
			$Query = "INSERT INTO group_master (GroupCode,GroupDesc,UserId,InsertDate,Remarks) VALUES ('$GroupCoad','$GroupDESC','$CreatorID','$date','".$Remark."')"; 
			//echo $Query;
			//exit();
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
	private static function CheckGroupCoad($GroupCoad){
		$Query = "SELECT GroupCode FROM group_master WHERE GroupCode = '$GroupCoad'"; 
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['GroupCode'] == $GroupCoad){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function UpdateGroupDiscription($GroupID,$GroupCode ,$GroupDESC,$Remark){
		// added on 01-JUNE-2016 due to Handle Special Character
		$GroupCode = mysql_escape_string($GroupCode);
		$GroupDESC = mysql_escape_string($GroupDESC);
		$Remark = mysql_escape_string($Remark);	
		
		$Query = "UPDATE group_master SET GroupDesc = '$GroupDESC',GroupCode = '".$GroupCode."', Remarks = '".$Remark."' WHERE GroupId = $GroupID"; 
		$Result = DBConnection::UpdateQuery($Query);
		
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}else{
			return QueryResponse::NO;
		}
	}
	public static function GetGroupList(){
		$Query = "SELECT * FROM group_master"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	public static function GetGroupDetails($GROUPID)
	{
		$Query = "SELECT * FROM group_master WHERE GroupId = '$GROUPID'";  
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
}

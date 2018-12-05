<?php
//include("../DBModel/Enum_Model.php");

class UserMenuModel
{
    public $_MenuId;
    public function __construct($MenuId)
    {
        $this->_MenuId=$MenuId;
    }
    public static function insertuserMenu($MenuId,$USERID)
    {
        //session_start();
       // $USERID= $_SESSION["USER"];
        $Query = "INSERT INTO menu_privilege(USERID, MENU_ID) VALUES('".$USERID."',".$MenuId.")"; 
        $Query=str_replace('\\','',$Query);
        $Result = DBConnection::InsertQuery($Query);
        if($Result == QueryResponse::SUCCESS){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function DeleteUserMenu($menuid,$USERID)
    {
       // session_start();
       // $USERID= $_SESSION["USER"];
        $Query = "DELETE FROM menu_privilege WHERE USERID='$USERID' and MENU_ID=$menuid"; 
        $Result = DBConnection::DeleteQuery($Query);
        if($Result == QueryResponse::SUCCESS){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function SelectUserMenu($userid)
    {
        $Query = "SELECT * FROM menu_privilege WHERE USERID='$userid'"; 
		$result = DBConnection::SelectQuery($Query);;
		//return $Result;
        $objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		
            $MenuId=$Row['MENU_ID'];
     
            $newObj = new UserMenuModel($MenuId);
            $objArray[$i] = $newObj;
            $i++;
    		
		}
		return $objArray; 
    }
    
}
?>
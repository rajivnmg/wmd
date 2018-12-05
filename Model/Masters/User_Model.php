<?php
//include("../DBModel/Enum_Model.php");
//include("../DBModel/DbModel.php");
Logger::configure($root_path."config.xml");
$logger = Logger::getLogger('User Module');
class UserModel
{
	public $_USERID;public $_PASSWD;public $_USER_NAME;public $_USER_TYPE;public $_CREATED_DATE;public $_PHONE;public $_MOBILE;
	public $_email; public $_ACTIVE;
	public function __construct($USERID,$PASSWD,$USER_NAME,$USER_TYPE,$CREATED_DATE,$PHONE,$MOBILE,$email,$ACTIVE)
	{
	
		$this->_USERID=$USERID;
		$this->_PASSWD=$PASSWD;
		$this->_USER_NAME=$USER_NAME;
		$this->_USER_TYPE=$USER_TYPE;
		$this->_CREATED_DATE=$CREATED_DATE;
		$this->_PHONE=$PHONE;
		$this->_MOBILE=$MOBILE;
		$this->_email=$email;
		$this->_ACTIVE=$ACTIVE;
	}
  public static function  LoadAll($USERID){
 
	if($USERID!=NULL){ 
			$result = self::LoadUserDetails($USERID);			
	}else{
		$result = self::LoadUser();
	}
		
		$objArray = array();
		$i = 0;
		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	
		 	$USERID=$Row['USERID'];
		 	//$PASSWD=$Row['PASSWD'];
             $PASSWD="";
			 $UserType="";
		 	$USER_NAME=$Row['USER_NAME'];
			if($Row['USER_TYPE']=='M'){
				$UserType = "Management";
			}else if($Row['USER_TYPE']=='A'){
				$UserType = "Administrator";
			}else if($Row['USER_TYPE']=='E'){
				$UserType = "Executive";
			}else if($Row['USER_TYPE']=='S'){
				$UserType = "Super User";
			}else if($Row['USER_TYPE']=='B'){
				$UserType = "Back Office";
			}
		 	$USER_TYPE=$Row['USER_TYPE'];
		 	$CREATED_DATE=$Row['CREATED_DATE'];
		 	$PHONE=$Row['PHONE'];
		 	$MOBILE=$Row['MOBILE'];
		 	$email=$Row['email'];
		 	$ACTIVE=$Row['ACTIVE'];
		 	$newObj = new UserModel($USERID,$PASSWD,$USER_NAME,$UserType,$CREATED_DATE,$PHONE,$MOBILE,$email,$ACTIVE);
		 	$objArray[$i] = $newObj;
		 	$i++;
    		
		}
		return $objArray;
	}
  public static function CheckUserName($USERID){
	  $logger = Logger::getLogger('User Module');
      $Query = "SELECT USERID FROM user_mast WHERE USERID = '$USERID'"; 
	  $logger->debug($Query); // function to write log file
      $Result = DBConnection::SelectQuery($Query);
      $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
	 
      if($Row['USERID'] == $USERID){
          return QueryResponse::YES;
      }
      else{
		  $logger->error($Query); // function to write log file
          return QueryResponse::NO;
      }
  }
public static function insertuser($USERID,$PASSWD,$USER_NAME,$USER_TYPE,$PHONE,$MOBILE,$email,$ACTIVE)
	{
		$logger = Logger::getLogger('User Module');
		if($ACTIVE)
		{
			$ACTIVE='Y';
		}
		else
		{
			$ACTIVE='N';
		}
        $Resp = self::CheckUserName($USERID);
		if($Resp == QueryResponse::NO){
			$CREATED_DATE=date("Y-m-d");
			// added on 01-JUNE-2016 due to Handle Special Character			
			$USERID = mysql_escape_string($USERID);
			$USER_NAME = mysql_escape_string($USER_NAME);
			$USER_TYPE = mysql_escape_string($USER_TYPE);
			$PHONE = mysql_escape_string($PHONE);
			$MOBILE = mysql_escape_string($MOBILE);
			$email = mysql_escape_string($email);    
			
			$Query="INSERT INTO user_mast(USERID,PASSWD,USER_NAME,USER_TYPE,CREATED_DATE,PHONE,MOBILE,email,ACTIVE) VALUES('$USERID','".md5($PASSWD)."','$USER_NAME','$USER_TYPE','$CREATED_DATE','$PHONE','$MOBILE','$email','$ACTIVE')";
			 $logger->debug($Query); // function to write log file
			$Result = DBConnection::InsertQuery($Query);
			 $logger->debug($Result); // function to write log file
			if($Result > 0){
				return $Result;
			}
			else{
				 $logger->error($Query); // function to write log file
				return QueryResponse::NO;
			}
	    }
	    else{
			return QueryResponse::NO;
		}

	
}
public static function UpdateUser($USERID,$USER_NAME,$USER_TYPE,$PHONE,$MOBILE,$email,$ACTIVE)
{
	$logger = Logger::getLogger('User Module');
    //if($ACTIVE)
    //{
    //    $ACTIVE="Y";
    //}
    //else
    //{
    //    $ACTIVE="N";
    //}
     	// added on 01-JUNE-2016 due to Handle Special Character	
		$USER_NAME = mysql_escape_string($USER_NAME);
		$USER_TYPE = mysql_escape_string($USER_TYPE);
		$PHONE = mysql_escape_string($PHONE);
		$MOBILE = mysql_escape_string($MOBILE);
		$email = mysql_escape_string($email);    
    
    
    $Query="UPDATE user_mast SET USER_NAME='$USER_NAME',USER_TYPE='$USER_TYPE',PHONE='$PHONE',MOBILE='$MOBILE',email='$email',ACTIVE='$ACTIVE' WHERE USERID='$USERID'";
	 $logger->debug($Query); // function to write log file
	$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			$logger->error($Query); // function to write log file
			return QueryResponse::NO;
		}
}
public static function LoadExecutive()
{
	$logger = Logger::getLogger('User Module');
    $Query = "SELECT * FROM user_mast where USER_TYPE='E'"; 
	 $logger->debug($Query); // function to write log file
    $Result = DBConnection::SelectQuery($Query);
    return $Result;
}
public static function LoadUser()
{
		$logger = Logger::getLogger('User Module');
	    $Query = "SELECT * FROM user_mast"; 
		$logger->debug($Query); // function to write log file
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
}
public static function LoadUserDetails($USERID)
{
	$logger = Logger::getLogger('User Module');
	$Query = "SELECT * FROM user_mast WHERE USERID='$USERID'"; 
		 $logger->debug($Query); // function to write log file
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
}
public static function updateUserPermission($USERID,$Permission)
{
	$logger = Logger::getLogger('User Module');
	$Permission=json_encode($Permission);
  	 $Query = "UPDATE user_mast set PERMISSION='$Permission' where USERID='$USERID'";
  	 $logger->debug($Query); // function to write log file
  	 // return $Query;
  	  $Result = DBConnection::UpdateQuery($Query);
		if($Result ==QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
		
			return QueryResponse::NO;
		}
}
public static function getUserPermission($USERID)
{
	$logger = Logger::getLogger('User Module');
	$Query="SELECT PERMISSION FROM user_mast where USERID='$USERID'";
	 $logger->debug($Query); // function to write log file
	$Result = DBConnection::SelectQuery($Query);
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
/* public static function UserPasswordChange($USERID)
{
	
	$logger = Logger::getLogger('User Module');
	$query="UPDATE user_mast SET PASSWD='$PASSWD' WHERE USERID='$USERID'";
	$logger->info($Query); // function to write log file
	$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			$logger->error($Query); // function to write log file
			return QueryResponse::NO;
		}
} */
public static function UserLogin($USERID,$PASSWD)
{
	$logger = Logger::getLogger('User Module');
	$Query = "SELECT * FROM user_mast WHERE USERID='$USERID' and PASSWD='".md5($PASSWD)."'"; 
	 $logger->debug($Query); // function to write log file
    $Result = DBConnection::SelectQuery($Query);
    $Row = $Result->fetch_assoc();
    //$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    if($Row['USERID'] == $USERID && $Row['PASSWD']== md5($PASSWD))
    {
        //session_start();
        $_SESSION["USER"]=$USERID;
        $_SESSION["MULTIWELD"]=123;
        $_SESSION["USER_TYPE"]=$Row['USER_TYPE'];
        $_SESSION["USER_NAME"]=$Row['USER_NAME'];
        $USERTYPE=$Row['USER_TYPE'];
        $_SESSION['loggedin_time'] = time();  
        $Query = "select PARAM_VALUE1 from param where PARAM_TYPE = 'SALESTAX' AND PARAM_CODE = 'INCOMING'"; 
		 $logger->debug($Query); // function to write log file
        $Result = DBConnection::SelectQuery($Query);
        $Row = $Result->fetch_array();
        //$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        $_SESSION["SALE_TAX"]= $Row['PARAM_VALUE1'];   
        
        return $USERTYPE;
        //$Result = DBConnection::SelectQuery($Query);;
        ////return $Result;
        //$objArray = array();
        //$i = 0;		
        //while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {    		
        //     $USERID=$Row['USERID'];
        //     $PASSWD=$Row['PASSWD'];
        //     $USER_NAME=$Row['USER_NAME'];
        //     $USER_TYPE=$Row['USER_TYPE'];
        //     $CREATED_DATE=$Row['CREATED_DATE'];
        //     $PHONE=$Row['PHONE'];
        //     $MOBILE=$Row['MOBILE'];
        //     $email=$Row['email'];
        //     $ACTIVE=$Row['ACTIVE'];
        //     $newObj = new UserModel($USERID,$PASSWD,$USER_NAME,$USER_TYPE,$CREATED_DATE,$PHONE,$MOBILE,$email,$ACTIVE);
        //     $objArray[$i] = $newObj;
        //     $i++;
    		
        //}
        //return $objArray;
    }
    else
    {
        return QueryResponse::NO;
    }
}
public static function UpdateUserPass($oldpass,$newpass)
{
	$logger = Logger::getLogger('User Module');
    session_start();
    $USERID=$_SESSION["USER"];
    $Query = "SELECT * FROM user_mast WHERE USERID='$USERID' and PASSWD='".md5($oldpass)."'"; 
	
    $Result = DBConnection::SelectQuery($Query);
    $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    if($Row['USERID'] == $USERID && $Row['PASSWD']==md5($oldpass))
    {
        $Query="UPDATE user_mast SET PASSWD='".md5($newpass)."' WHERE USERID='$USERID'";    
		$logger->debug($Query); // function to write log file
		$Result = DBConnection::UpdateQuery($Query);
		
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}else{
		
			return QueryResponse::NO;
		}
    }else{
		
        return QueryResponse::NO;
    }
}
	public static function GetUserType()	{  // function returns all type of users
		
		$Query = "SELECT * FROM param WHERE PARAM_TYPE='USERTYPE'";
		
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
	public static function GetUserId(){  // function returns all user 
		
		$Query = "SELECT * FROM user_mast"; 
	
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

	public static function getUserByType($uType){ // function returns all user of same type
		
		$Query = "SELECT USERID,USER_NAME FROM user_mast WHERE USER_TYPE='$uType' OR USER_TYPE='B'";
	
        $Result = DBConnection::SelectQuery($Query);
        //echo $Result;
		return $Result;
	}
}

?>

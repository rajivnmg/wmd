<?php
class CreateUser
{
	public static function insertuser($USERID,$PASSWD,$USER_NAME,$USER_TYPE,$PHONE,$MOBILE,$email,$ACTIVE)
	{
        $CREATED_DATE=date('y-m-d');
       	//added on 01-JUNE-2016 due to Handle Special Character
        $USERID = mysql_escape_string($USERID);
        $USER_NAME = mysql_escape_string($USER_NAME);
        $USER_TYPE = mysql_escape_string($USER_TYPE);
        $PHONE = mysql_escape_string($PHONE);
        $MOBILE = mysql_escape_string($MOBILE);
        $email = mysql_escape_string($email);    
        
		$query="INSERT INTO user_mast(USERID,PASSWD,USER_NAME,USER_TYPE,CREATED_DATE,PHONE,MOBILE,email,ACTIVE) VALUES ('$USERID','$PASSWD','".$USER_NAME."','$USER_TYPE','','$PHONE','$MOBILE','$email','$ACTIVE')";
		$Result = DBConnection::InsertQuery($Query);
			if($Result > 0){
				return $Result;
			}
			else{
				return QueryResponse::NO;
			}
	}
	
}

?>

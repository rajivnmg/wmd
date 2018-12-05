<?php
if ( !isset($_SESSION) ) {session_start(); }
require_once('root.php');
require_once($root_path."log4php/Logger.php");  
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){ //log file settion for gurgaon due to run all instance from single set of code
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){ //log file settion for rudrapur due to run all instance from single set of code
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){ //log file settion for manesar due to run all instance from single set of code
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){ //log file settion for haridwar due to run all instance from single set of code
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}

//Logger::configure($root_path."config.xml");
include_once("Enum_Model.php");
class DBConnection {
	
	public static function InsertQuery($Query){
		  $logger = Logger::getLogger('DBmodel INSERT');
		  $logger->info($Query);
		  $Result = "" ;
          try{	
      	  $My_Sql = mysqli_connect(DB_DETAILS::PC_NAME,DB_DETAILS::USER_NAME,DB_DETAILS::PASSWORD) or die("Unable to connect to MySQL");
		  $Db_Name = mysqli_select_db(DB_DETAILS::DATABASE_NAME,$My_Sql) or die("Could not select examples");
		 //echo $Query ." =====<br/>=== ";	
			mysql_set_charset("UTF8", $My_Sql);		
			$Result = mysqli_query($Query,$My_Sql);
            $insert_id = mysqli_insert_id($My_Sql);
			//mysql_close($My_Sql);
			if(!$Result)
		    {
			  $logger->error('Could not enter data: '.$Query.'  : '. mysql_error());
			  die('Could not enter data: '.$Query.'  : '.mysql_error());
           	  return QueryResponse::ERROR;
			}
			else
			{
                return $insert_id;
			}
		  } catch (Exception $ex) {
		    $logger->error($ex); // write error/exception in log file.
          } 
			mysql_close($My_Sql);
	}
	public static function SelectQuery($Query){
		$Result = "" ;
		$logger = Logger::getLogger('DBmodel Select');
		$logger->debug($Query);
		
          try{		  
			// $My_Sql = mysql_connect('localhost','root','codefire') or die(" Unable to connect toMySQL");   
			//$Db_Name = mysql_select_db('multiuse_gurgaon',$My_Sql) or die("Could not select examples");
			//echo $Query ." =====<br/>=== ";		      
		   $My_Sql = mysqli_connect(DB_DETAILS::PC_NAME,DB_DETAILS::USER_NAME,DB_DETAILS::PASSWORD,DB_DETAILS::DATABASE_NAME) or die(" Unable to connect toMySQL");        
           $dname = DB_DETAILS::DATABASE_NAME;
           //$Db_Name = mysqli_select_db(DB_DETAILS::DATABASE_NAME,$My_Sql) or die("Could not select examples");			
			//mysqli_set_charset("UTF8", $My_Sql);
			
			
		   $Result = $My_Sql->query($Query);
					
			if(!$Result)
		    {					
				//$logger->error('Could not select data : '.$Query.'  : '.  mysqli_error());
				//die('Could not select data: '.$Query.'  : '. mysql_error());
				return QueryResponse::ERROR;
			}
			else
			{ 				
			  //$Data = mysql_fetch_array($Result, MYSQL_ASSOC);			  
			  return $Result;
			}
		  } catch (Exception $ex) {
           $logger->error($ex);
		  	mysql_close($My_Sql);
		  }
		   	mysql_close($My_Sql);
	}
    public static function UpdateQuery($Query){
		 $logger = Logger::getLogger('DBmodel Update');
		 $logger->info($Query);
		  $Result = "" ;
		  $My_Sql = mysqli_connect(DB_DETAILS::PC_NAME,DB_DETAILS::USER_NAME,DB_DETAILS::PASSWORD) or die("Unable to connect to MySQL");
		  $Db_Name = mysqli_select_db(DB_DETAILS::DATABASE_NAME,$My_Sql) or die("Could not select examples");
		
		  try{	
			 mysql_set_charset("UTF8", $My_Sql);
			$Result = mysqli_query($Query,$My_Sql);
		
			if(!$Result){
			  $logger->info('Could not update data : '.$Query.'  : '. mysql_error()); // function to write in log file.
	      	  die('Could not update data: '.$Query.'  : '. mysql_error());
		  	  return QueryResponse::ERROR;
		    }else{
		  	  return QueryResponse::SUCCESS;
		    }
		  } 
		  catch (Exception $ex) {
		  	mysql_close($My_Sql);
		  }
		  	mysql_close($My_Sql);
	}
    public static function DeleteQuery($Query){
		 $logger = Logger::getLogger('DBmodel Delete');
		 $logger->warn($Query);
        $Result = "" ;
        $My_Sql = mysqli_connect(DB_DETAILS::PC_NAME,DB_DETAILS::USER_NAME,DB_DETAILS::PASSWORD) or die("Unable to connect to MySQL");
        $Db_Name = mysqli_select_db(DB_DETAILS::DATABASE_NAME,$My_Sql) or die("Could not select examples");
        try{	
			$Result = mysqli_query($Query,$My_Sql);
			
			if(!$Result){
			   $logger->error('Could not delete data '.$Query.'  : '.  mysql_error());
	      	  die('Could not update data: '.$Query.'  : '.mysql_error());
		  	  return QueryResponse::ERROR;
		    }
		    else
		    {
		  	  return QueryResponse::SUCCESS;
		    }
        } 
        catch (Exception $ex) {
            mysql_close($My_Sql);
        }
		mysql_close($My_Sql);
	}
}


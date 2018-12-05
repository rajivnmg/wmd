<?php
if ( !isset($_SESSION) ) {session_start(); }
require_once('root.php');
include($root_path."Config.php");
ini_set("date.timezone", "Asia/Kolkata");
abstract class MultiweldParameter  {

    const QuotationAutogentateKey = "MW";

    public static function GetFinancialYear(){
        /*
        $FinancialYear = "2014-2015";
        $S = array();
        $S = str_split($FinancialYear);
        return $S[2].$S[3];*/
        $file = file_get_contents('../../finyear.txt',NULL,NULL,2,2);
        return $file;
    }
    public static function GetFinancialYear_fromTXT(){
        $file = file_get_contents('../../finyear.txt',NULL,NULL,0,9);
        return $file;
    }
    public static function xFormatDate($dte){
		  $dateInput = explode('/',$dte);
          $ukDate = $dateInput[2].'-'.$dateInput[1].'-'.$dateInput[0];
          return $ukDate;
	}
    public static function xFormatDate1($dte){
          $dateInput = explode('-',$dte);
		//  echo "<pre>";
		//  print_r($dte);
		  $ukDate = $dateInput[2].'/'.$dateInput[1].'/'.$dateInput[0];
          return $ukDate;
	}
}
 abstract class QueryModel  {

 	const INSERT = "INSERT";
    const UPDATE = "UPDATE";
    const SELECT = "SELECT";
    const DELETE = "DELETE";
    const PAGELOAD = "PAGELOAD";
    const AUTO_COMPLEAT = "AUTO_COMPLEAT";
    const SEARCH = "SEARCH";
}
abstract class QueryResponse  {

 	const SUCCESS = "SUCCESS";
    const ERROR = "ERROR";
    const YES = "YES";
    const NO = "NO";
}

abstract class DB_DETAILS  {
    const PC_NAME = DB_HOST;
    const USER_NAME = DB_USER;
    const PASSWORD = DB_PASS; 
    const DATABASE_NAME = DB_DATA;  
}

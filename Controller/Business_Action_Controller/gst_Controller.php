<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/gst_model.php");
//Logger::configure("../../config.xml");
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
$logger = Logger::getLogger('GST_Controller');
session_start();
$UID=$_SESSION["USER"];
$Type  = isset($_REQUEST['TYP'])?$_REQUEST['TYP']:null;
$logger->info($Type); // function to write in log file
if($Type == null)
    $Type = 'GETTAX';
	switch($Type){
    case 'GETTAX':
		$shipped_state_id = $_REQUEST['SHIPPED_STATE_ID'];
        $item_code = $_REQUEST['ITEM_ID'];
		$Print = gst_Model::getGSTRates($shipped_state_id,$item_code);
		//$result = array('a','b');
		echo json_encode($Print);
		//echo $result;
        return;
        break;
    default:
        break;
}
?>

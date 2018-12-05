<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/CityMaster_Model.php");
//Logger::configure("../../config.xml");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('city_Controller');

$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
    session_start();
      $USERID=$_SESSION["USER"];
	  
        $Print = CityMasterModel::InsertCity($_REQUEST['CITYNAME'],$_REQUEST['STATEID'],$USERID);
		$logger->debug($Print);// to create info type log file
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $result = CityMasterModel::LoadCity($_REQUEST['CITYID'],$_REQUEST['TAG'],$_REQUEST['STATEID']);
        echo json_encode($result);
        return;
        break;
    case QueryModel::UPDATE:
        $Print = CityMasterModel::UpdateCityName($_REQUEST['CITYID'],$_REQUEST['CITYNAME']);
		$logger->debug($Print);// to create info type log file
        echo json_encode($Print);
        return;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
        break;
    default:
        break;
}
function Pageload(){
    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = CityMasterModel::LoadCity(0,"CITY",0);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'CITYID'       => $row->_city_id,
                'CITYNAME'     => $row->_city_nameame,
                'STATENAME'     => $row->_state_name,
                'StateId'   => $row->_state_id
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);    
}

?>

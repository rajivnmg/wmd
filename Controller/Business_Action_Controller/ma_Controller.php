<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
session_start();
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
include_once( "../../Model/Masters/BuyerMaster_Model.php");
include_once( "../../Model/Business_Action_Model/ma_model.php");
include_once("../../log4php/Logger.php");
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
$logger = Logger::getLogger('MA_controller');
$UID=$_SESSION["USER"];
$Type = $_REQUEST['TYP'];
	
if($Type == null)
    $Type = QueryModel::PAGELOAD;
   // echo $Type;
   // exit;
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['MADATA'];
		$logger->debug($Data);	// to create info type log file		
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);		
        $Print = Managementapproval_Model::InsertMA($obj->{'poid'},$obj->{'arem'},$UID);
		$logger->debug($Print);	// to create debug type log file		
        if($Print>0){
		  $Print = Managementapproval_Model::UpdatePO_BYMA($obj->{'poid'},$obj->{'maTag'});
		}
        $logger->debug($Print);	// to create debug type log file		
        if($Print=="YES"){
		$i = 0;
        while($i < sizeof($obj->{'_items'}))
        {
            Managementapproval_Details_Model::UPDATEPO_CATEGORY($obj->_items[$i]->bpod_Id,$obj->_items[$i]->po_cate);
            $i++;
        }

		}
        echo json_encode($Print);
        return;
        break;
    case "MA_FILL":
        $POID= $_REQUEST['POID'];
        $Print = Managementapproval_Model::LoadMAByID($POID);
		$logger->debug(json_encode($Print));	// to create debug type log file		
        echo json_encode($Print);
        return;
        break;

    case "APPROVAL":
        $Data = $_REQUEST['MADATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data); 
        $Print = Managementapproval_Model::UpdateMA($obj->{'poid'},$obj->{'mrem'});
		$logger->debug(json_encode($Print));	// to create debug type log file		
        $Print = Managementapproval_Model::APPPO_BYMA($obj->{'poid'},$obj->{'asTag'});
        $logger->debug(json_encode($Print));	// to create debug type log file		
        echo json_encode($Print);
        return;
        break;
    case "BILLINGADD":
        $buyerId = $_REQUEST['BUYERID'];
        $Print = BuyerMaster_Model::LoadBuyerDetails($buyerId);
		 $logger->debug(json_encode($Print));	// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
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
    $rows = NULL;//Purchaseorder_Model::LoadPO(0);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'quotId'       => $row->_quotation_id,
                'quotNo'     => $row->_quotation_no,
                'quotdate'     => $row->_quotation_date,
                'principalname'       => $row->_principal_name,
                'customername'     => $row->_coustomer_name,
                'contactpersone'     => $row->_contact_persone
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}
?>

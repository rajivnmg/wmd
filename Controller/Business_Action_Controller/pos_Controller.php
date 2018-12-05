<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/pos_model.php");
include_once("root.php");
include_once($root_path."log4php/Logger.php");
//Logger::configure($root_path."config.xml");
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
$logger = Logger::getLogger('POS_controller');

$Type = $_REQUEST['TYP'];
$logger->info($Type);// to create info type log file
if($Type == null)
    $Type = QueryModel::PAGELOAD;   
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['PODATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
		$logger->debug($Data);// to create debug type log file
        $i=0;
        while($i < sizeof($obj->{'_items'}))
        {
            $Print=purchaseorder_Schedule_Model::InsertPODetails($obj->{'bpoId'},$obj->_items[$i]->bpod_Id,$obj->_items[$i]->sch_principalId,$obj->_items[$i]->sch_codePartNo,$obj->_items[$i]->bic,$obj->_items[$i]->sch_rqty,$obj->_items[$i]->schDate,$obj->_items[$i]->sch_dqty);
           	$logger->debug($Print);// to create debug type log file
			$i++;
        }
        echo json_encode($Print);
        return;
        break;
    case "SCHPRINCIPAL":
        $pid= $_REQUEST['POID'];
        $Print = purchaseorder_Schedule_Model::getSCHPrincipal($pid);
		$logger->debug(json_encode($Print));// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "SCHCODEPARTNO":
        $pid= $_REQUEST['PRINCIPALID'];
        $poid= $_REQUEST['POID'];
        
        $Print = purchaseorder_Schedule_Model::getCodePartNo($poid,$pid);
		$logger->debug(json_encode($Print));// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "LOADPOITEM":
        $bpod_Id= $_REQUEST['BPODID'];
        $Print = purchaseorder_Schedule_Model::getBPODItemDetail($bpod_Id);
		$logger->debug(json_encode($Print));// to create debug type log file
        echo json_encode($Print);
        return;
        break;   
    case "BUYERITEMCODE":
        $cpn= $_REQUEST['CPN'];
        $poid= $_REQUEST['POID'];
        $Print = purchaseorder_Schedule_Model::getBuyerItemCode($poid,$cpn);
		$logger->debug(json_encode($Print));// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "AUTOLIST":
        $Print = purchaseorder_Schedule_Auto_Model::getAutoList();
        echo json_encode($Print);
        return;
        break;
    case "SCH_FILL":
        $PO_num= $_REQUEST['PO_NUMBER'];
        $Print = Purchaseorder_Model::LoadPurchaseByID($PO_num);
		$logger->debug(json_encode($Print));// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "LOADPODETAILS":
        $Print = purchaseorder_Schedule_Model::GetPoDetails($_REQUEST['POID'],$_REQUEST['PRINID'],$_REQUEST['ITEMID']);
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

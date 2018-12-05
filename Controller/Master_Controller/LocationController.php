
<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/LocationMaster_Model.php");
include_once( "../../Model/Param/param_model.php");
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
    session_start();
      $USERID=$_SESSION["USER"];
        $Print = LocationMasterModel::InsertLocation($_REQUEST['CITYID'],$_REQUEST['STATEID'],$_REQUEST['LOCATIONNAME'],$USERID);
        echo json_encode($Print);return;
        break;
    case QueryModel::SELECT:
        $result = LocationMasterModel::LoadLocation($_REQUEST['CITYID'],0,0);
        echo json_encode($result);return;
        break;
    case QueryModel::UPDATE:
        $jsonData = LocationMasterModel::UpdateLocation($_REQUEST['LOCATIONID'], $_REQUEST['LOCATIONNAME']);
        echo json_encode($jsonData);return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "SELECT_LOCATION":
        $result = LocationMasterModel::SelectLocation($_REQUEST['LOCATIONID']);
        echo json_encode($result);return;
        break;
    default:
        break;
}
function Pageload(){
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = LocationMasterModel::LoadLocation(0,$start,$rp);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'LOCATIONID'       => $row->_location_id,
                'LOCATIONNAME'     => $row->_locationName,
                'CITYNAME'     => $row->_city_name,
                'STATENAME'     => $row->_state_name,
                'CITYID'     => $row->_city_id,
                'STATEID'     => $row->_state_id
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("location_master","");
    echo json_encode($jsonData);    
}

?>
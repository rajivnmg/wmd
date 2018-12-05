
<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/GroupMaster_Model.php");
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $GroupCode = $_REQUEST['GROUPCODE'];
        $GroupDESC = $_REQUEST['GROUPDESC'];
        $Remark = $_REQUEST['REMARKS'];
        session_start();
      $USERID=$_SESSION["USER"];
        $Print = GroupMasterModel::InsertGroup($GroupCode, $GroupDESC, $USERID, $Remark);
        echo json_encode($Print);
        break;
    case QueryModel::SELECT:
       $print= GroupMasterModel::LoadGroup($_REQUEST['GROUPID']);
	   echo json_encode($print);
	   return;
        break;
    case QueryModel::UPDATE:
        $GroupID = $_REQUEST['GROUPID'];
        $GroupCode = $_REQUEST['GROUPCODE'];
        $GroupDESC = $_REQUEST['GROUPDESC'];
        $Remark = $_REQUEST['REMARKS'];
        $Print = GroupMasterModel::UpdateGroupDiscription($GroupID,$GroupCode, $GroupDESC,$Remark.trim());
        echo json_encode($Print);
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "AUTOCODE":
        $Print = GroupMasterModel::GetLastGroupCode();
        echo json_encode($Print);
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

    $rows = GroupMasterModel::LoadGroup(0);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'GROUPID'     => $row->_group_id,
                'GROUPCODE'   => $row->_group_coad,
                'GROUPDESC'   => $row->_group_desc,
                'REMARKS'     => $row->_remark
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}

?>
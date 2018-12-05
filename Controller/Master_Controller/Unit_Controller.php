
<?php
  include_once("../../Model/DBModel/DbModel.php");
  include_once("../../Model/DBModel/Enum_Model.php");
  include_once( "../../Model/Masters/UnitMaster_Model.php");
  $Type = $_REQUEST['TYP'];
  if($Type == null)
  {
  $Type = QueryModel::PAGELOAD;
  }
      
  switch($Type){
  	case QueryModel::INSERT:
     session_start();
      $USERID=$_SESSION["USER"];
  	       $Print = UnitMasterModel::InsertUnit($_REQUEST['UNITNAME'],$USERID);
             echo json_encode($Print);
  		break;
  	case QueryModel::SELECT:
  	       $Print = UnitMasterModel::LoadAll($_REQUEST['UNITID']);
             echo json_encode($Print);
		   return;
  		break;
  	case QueryModel::UPDATE:
  	      UnitMasterModel::UpdateUnit($_REQUEST['UNITID'],$_REQUEST['UNITNAME']);
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
      $unitid=0;
      $rows = UnitMasterModel::LoadAll($unitid);
	 //$rows=UnitMasterModel::LoadAll1();
      header("Content-type: application/json");
      $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
      $i = 0;
      foreach($rows AS $row){
          
          //If cell's elements have named keys, they must match column names
          //Only cell's with named keys and matching columns are order independent.
          $entry = array('id' => $i,
              'cell'=>array(
                  'UNITID'       => $row->_uniId,
                  'UNITNAME'     => $row->_unitName
              )
          );
          $i++;
          
          $jsonData['rows'][] = $entry;
      }
      $jsonData['total'] = count($rows);
      echo json_encode($jsonData);
  }
  
?>
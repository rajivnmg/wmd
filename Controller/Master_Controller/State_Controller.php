<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/StateMaster_Model.php");
  $Type = $_REQUEST['TYP'];
  if($Type == null)
  {
      $Type = QueryModel::PAGELOAD;
  }
  
  switch($Type){
  	case QueryModel::INSERT:
       session_start();
      $USERID=$_SESSION["USER"];
  	       //$Print = StateMasterModel::InsertState($_REQUEST['STATENAME'],$USERID);
		   $Print = StateMasterModel::InsertState($_REQUEST['STATENAME'],$_REQUEST['STATECODE'],$_REQUEST['TINNUMBER'],$USERID);
  	       echo json_encode($Print);
  		break;
  	case QueryModel::SELECT:
  	      $Print= StateMasterModel::LoadAll($_REQUEST['STATEID']);
		  echo json_encode($Print);
		  return;
  		break;
  	case QueryModel::UPDATE:
          //$Print = StateMasterModel::UpdateState($_REQUEST['STATEID'],$_REQUEST['STATENAME']);
		  $Print = StateMasterModel::UpdateState($_REQUEST['STATEID'],$_REQUEST['STATENAME'],$_REQUEST['STATECODE'],$_REQUEST['TINNUMBER']);
          echo json_encode($Print);
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
      
      $rows = StateMasterModel::LoadAll(0);
      header("Content-type: application/json");
      $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
      $i = 0;
      foreach($rows AS $row){
          
          //If cell's elements have named keys, they must match column names
          //Only cell's with named keys and matching columns are order independent.
          $entry = array('id' => $i,
              'cell'=>array(
                  'STATEID'       => $row->_stateId,
                  'STATENAME'     => $row->_stateName,
                  'STATECODE'     => $row->_stateCode,
                  'TINNUMBER'     => $row->tin_no
              )
          );
          $i++;
          
          $jsonData['rows'][] = $entry;
      }
      $jsonData['total'] = count($rows);
      echo json_encode($jsonData);
  }
  
?>
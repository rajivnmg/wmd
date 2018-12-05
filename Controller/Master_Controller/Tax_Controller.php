<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/TaxMaster_Model.php");
  $Type = $_REQUEST['TYP'];
  if($Type == null)
  {
      $Type = QueryModel::PAGELOAD;
  }
  
  switch($Type){
  	case QueryModel::INSERT:
       session_start();
      $USERID=$_SESSION["USER"];
  	       $Print = TaxMasterModel::InsertTax($_REQUEST['TAXRATE'], $_REQUEST['TAXDESC']);
  	       echo json_encode($Print);
  		break;
  	case QueryModel::SELECT:
  	      $Print= TaxMasterModel::LoadAll($_REQUEST['TAXID']);
		  echo json_encode($Print);
		  return;
  		break;
  	case QueryModel::UPDATE:
          $Print = TaxMasterModel::UpdateTax($_REQUEST['TAXID'],$_REQUEST['TAXRATE'], $_REQUEST['TAXDESC']);
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
      $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'TAXRATE';
      $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
      $query = isset($_POST['query']) ? $_POST['query'] : false;
      $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
      
      $rows = TaxMasterModel::LoadAll(0);
      header("Content-type: application/json");
      $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
      $i = 0;
      foreach($rows AS $row){
          
          //If cell's elements have named keys, they must match column names
          //Only cell's with named keys and matching columns are order independent.
          $entry = array('id' => $i,
              'cell'=>array(
                  'TAXID'       => $row->_TaxId,
                  'TAXRATE'     => $row->_TAXrate,
				  'TAXDESC'     => $row->_TAXdesc
              )
          );
          $i++;
          
          $jsonData['rows'][] = $entry;
      }
      $jsonData['total'] = count($rows);
      echo json_encode($jsonData);
  }
  
?>
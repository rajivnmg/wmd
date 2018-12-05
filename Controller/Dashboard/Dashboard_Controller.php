<?php

include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/Dashboard/DashboardModel.php");
include_once("../../Model/Business_Action_Model/Inventory_Model.php"); 
include_once("../../Model/DBModel/SalseTaxModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_NonExcise_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
include_once( "../../Model/Business_Action_Model/pos_model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Invoice_Excise_Model.php");
include_once( "../../Model/Param/param_model.php");
include_once("../Param/Param.php");
include("../../Model/ReportModel/Report1Model.php");

  $Type = $_REQUEST['TYP']; 
  if(isset($_REQUEST['POID']) && isset($_REQUEST['BPONO'])){
	 $POID = $_REQUEST['POID']; 
	 $BPONO = $_REQUEST['BPONO'];
  }
  if(isset($_REQUEST['invid'])){
	$invid = $_REQUEST['invid'];
  }
  if(isset($_REQUEST['invno'])){
	$invno = $_REQUEST['invno'];
  }
  if(isset($_REQUEST['bpoType'])){
	$bpoType = $_REQUEST['bpoType'];
  }
 
  switch($Type){
  	case "LISTDATA":
  	    $Print = DashboardModel::LoadListData();
        echo json_encode($Print);
		return;
  		break;
    case "LOADLSCITEMLIST":
  	    $Print = DashboardModel::LoadLSCItemData();
        echo json_encode($Print);
	    return;
  		break;
    case "LOADITEMLIST":
  	    $Print = DashboardModel::LoadItemData();
        echo json_encode($Print);
	    return;
  		break;
	case "LISTEXECUTIVEDATA": // function to get all executive details
		$USERID=$_REQUEST['ExecutiveId']; 
		$Print = DashboardModel::LoadExecutiveData($USERID);
        echo json_encode($Print);
		return;
  		break;
	default:
  		break;
  }
  
?>

<?php
  include_once("../../Model/DBModel/DbModel.php");
  include_once("../../Model/DBModel/Enum_Model.php");
  include( "../../Model/Masters/UserType_Model.php");
  include_once("../../log4php/Logger.php"); 
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
  $logger = Logger::getLogger('user_controller');
  $Type = $_REQUEST['TYP'];
  if($Type == null)
  {
  $Type = QueryModel::PAGELOAD;
  }
 
  switch($Type){
  	case QueryModel::INSERT:
  	         $Data = $_REQUEST['PERMISSIONDATA'];
  	         $Data = str_replace('\\','', $Data);
  	         $groupId= $_REQUEST['GROUPID'];
  	         session_start();
             $USERID=$_SESSION["USER"];
  	        $logger->debug($Data);// to create debug type log file
  	         $Print =GroupPermissionModel::savePermission($groupId,$Data,$USERID);
			 $logger->debug($Print);// to create debug type log file
  	         echo json_encode($Print);
		   return ;
  		break;
    case QueryModel::UPDATE:
  	         $Data = $_REQUEST['PERMISSIONDATA'];
  	         $Data = str_replace('\\','', $Data);
  	         $Id= $_REQUEST['ID'];
  	         session_start();
             $USERID=$_SESSION["USER"];
			 $logger->debug($Data);// to create debug type log file
  	         $Print =GroupPermissionModel::updatePermission($Id,$Data,$USERID);
			 $logger->debug($Print);// to create debug type log file
  	         echo json_encode($Print);
		   return ;
  		break;		
  	case QueryModel::SELECT:
  	        $Print = GroupPermissionModel::getGroupPermission($_REQUEST['GROUPID']);
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
      $query = isset($_POST['query']) ? $_POST['query'] : false;
      $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
      $unitid=0;
      $rows =UserTypeModel::LoadAllUserType();

      header("Content-type: application/json");
      $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
      $i = 0;
     // $obj=json_encode($rows);
      // error_log(json_encode($rows), 3, "c:\dd-errors1.log"); 
      foreach($rows AS $row){
     
          $entry = array('id' => $i,
              'cell'=>array(
                  'SN'=>$row->_Id,
                  'UserType'=>$row->_groupName,
                  //'OPRATION'=> '<a href="add_group_permission.php?GROUP_ID='.$row->_Id.'&GROUP_NAME='.$row->_groupName.'">View</a>'
				  'OPRATION'=> '<a href="#">View</a>'
              )
          );
          $i++;
          
          $jsonData['rows'][] = $entry;
      }
      $jsonData['total'] = count($rows);
      echo json_encode($jsonData);
  }
  
  
?>

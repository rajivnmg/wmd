<?php
  include_once("../../Model/DBModel/DbModel.php");
  include_once("../../Model/DBModel/Enum_Model.php");
  include( "../../Model/Masters/UserType_Model.php");
  include_once( "../../Model/Masters/User_Model.php");
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
  $logger = Logger::getLogger('userPriv_controller');
  
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
  	         $USERID= $_REQUEST['USERID'];
  	         session_start(); 	  
			 $logger->debug($Data);// to create debug type log file 
  	         $Print =UserModel::updateUserPermission($USERID,$Data);
			 $logger->debug($Print);// to create debug type log file 
  	         echo json_encode($Print);
		   return ;
  		break;		
  	case QueryModel::SELECT:
  	        $USERID= $_REQUEST['USERID'];
  	        $Print = UserModel::getUserPermission($USERID);
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
   
    $unitid=0;
    $rows = UserModel::LoadAll($unitid);
    //$rows=UnitMasterModel::LoadAll1();
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
       
    foreach($rows AS $row){
   
        $entry = array('id' => $i,
            'cell'=>array(
            'SNO'=>($i+1),
            'USERID'=> $row->_USERID,
            'NAME'=>$row->_USER_NAME,
            'USER_TYPE_NAME'=>$row->_USER_TYPE,
            'ACTION'=> '<a href="addUserPrivilege.php?USERID='.$row->_USERID.'">Add Privilege</a>',       
            )
        );
        $i++;        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}
?>

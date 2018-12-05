<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/Masters/User_Model.php");
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
$logger = Logger::getLogger('user_controller');
$Type = $_REQUEST['TYP'];
//$logger->info($Type);// to create info type log file
$page='';
if(isset($_REQUEST['ACTION'])){
$page=$_REQUEST['ACTION'];
}
if($page==null)
{

    if($Type == null)
        $Type = QueryModel::PAGELOAD;
    switch($Type)
    {
		
        case QueryModel::INSERT:
            $Data = $_REQUEST['CREATEUSERDATA'];
			$logger = Logger::getLogger('user_controller');
			$logger->debug($Data); // Function to write data in log file
            if($Data!=null)
            {			
                $obj = json_decode($Data);
                $Print = UserModel::insertuser($obj->{'USERID'}, $obj->{'PASSWD'}, $obj->{'NAME'},$obj->{'USER_TYPE'}, $obj->{'PHONE'},$obj->{'MOBILE'}, $obj->{'email'}, $obj->{'ACTIVE'});
				$logger->debug($Data); // Function to write data in log file
                echo json_encode($Print);
                return;
            }
            else{
                return;
            }
            break;
        case QueryModel::UPDATE:
		$logger = Logger::getLogger('user_controller');
             $Data = $_REQUEST['UPDATEUSERDATA'];
			$logger->debug($Data); // Function to write data in log file
            $obj = json_decode($Data);
            $Print = UserModel::UpdateUser( $_REQUEST['USERID'], $_REQUEST['NAME'], $_REQUEST['USER_TYPE'], $_REQUEST['PHONE'],$_REQUEST['MOBILE'], $_REQUEST['email'],  $_REQUEST['ACTIVE']);
            echo json_encode($Print);
            return;            
            break;
        case "CHANGEPASSWORD":
                  $Data = $_REQUEST['UPDATEUSERDATA'];
                  $obj = json_decode($Data);
                  $print=UserModel::UserPasswordChange($_SESSION['USERID'],$obj->{'oldpass'},$obj->{'newpass'}); 
                  echo json_encode($Print);
                  return;
                  break;   
        case QueryModel::PAGELOAD:
            Pageload();
            return;
            break;
        case QueryModel::SELECT:
            $Print = UserModel::LoadAll($_REQUEST['USERID']);
            echo json_encode($Print);
            return;
            break;
        default:
            break;
    }
}
else if($page=="CreateUser")
{

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type)
{
    case QueryModel::INSERT:
	
        $Data = $_REQUEST['CREATEUSERDATA'];
        if($Data!=null)
        {
			$logger = Logger::getLogger('user_controller');
            $obj = json_decode($Data);
            $Print = UserModel::insertuser($obj->{'USERID'}, $obj->{'PASSWD'}, $obj->{'NAME'},$obj->{'USER_TYPE'}, $obj->{'PHONE'},$obj->{'MOBILE'}, $obj->{'email'}, $obj->{'ACTIVE'});
			$logger->debug($Print );	// to create debug type log file
            echo json_encode($Print);
            return;
        }
        else{
            return;
        }
		break;
    case QueryModel::UPDATE:     
				$logger = Logger::getLogger('user_controller');
                $obj = json_decode($Data);
                $Print = UserModel::UpdateUser($_REQUEST['USERID'], $_REQUEST['PASSWD'], $_REQUEST['NAME'],$_REQUEST['USER_TYPE'], $_REQUEST['PHONE'],$_REQUEST['MOBILE'],$_REQUEST['email'], $_REQUEST['ACTIVE']);
				$logger->debug($Print );	// to create debug type log file
                echo json_encode($Print);
                return;
            
		break;
    case QueryModel::SELECT:
        $Print = UserModel::LoadAll($_REQUEST['USERID']);
        echo json_encode($Print);
        return;
        break;
	}
}
else if($page=="Login")
{		
	//print_r($logger);
	//$logger->debug($Print);
	//$logger->warn($Print);
	$logger = Logger::getLogger('user_controller');
	switch($Type)
    {
    case QueryModel::SELECT:
        $Data = $_REQUEST['LOGINCREDENTIAL'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);      
        $Print = UserModel::UserLogin($obj->{'USERID'}, $obj->{'PASSWD'});		
		$logger->debug($Print);	// to create debug type log file
		echo json_encode($Print);
        return;
break;
}
}
else if($page=="ChangePassword")
{
	$logger = Logger::getLogger('user_controller');
    switch($Type)
    {
     case QueryModel::UPDATE:
         $Data = $_REQUEST['ChangePassword'];
                $obj = json_decode($Data);
                $Print = UserModel::UpdateUserPass($obj->{'oldpass'}, $obj->{'newpass'});
				$logger->debug($Print); // to create debug type log file
                echo json_encode($Print);
                return;            
            break;
 }
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
    $rows = UserModel::LoadAll($unitid);
	//print_r($rows);exit;
    //$rows=UnitMasterModel::LoadAll1();
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
	$logger = Logger::getLogger('user_controller');
	$logger->debug($jsonData); // to create debug type log file
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'USERID' => $row->_USERID,
                //'PASSWD'=> $row->_PASSWD,
                'NAME'=>$row->_USER_NAME,
                'USER_TYPE'=>$row->_USER_TYPE,
                'PHONE'=>$row->_PHONE,
                'email'=>$row->_email,
                'MOBILE'=>$row->_MOBILE,
                'ACTIVE'=>$row->_ACTIVE
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}

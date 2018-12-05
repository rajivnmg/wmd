<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/UserMenu_Model.php");
$Type = $_REQUEST['TYP'];
if($Type!=null)
{
    switch($Type)
    {
        case QueryModel::INSERT:
            $menuid = $_REQUEST['menuid'];
            $userid=$_REQUEST['userid'];
            if($menuid!=null)
            {
               // $obj = json_decode($menuid);
                $Print = UserMenuModel::insertuserMenu($menuid,$userid);
                echo json_encode($Print);
                return;
            }
            else{
                return;
            }
            break;
            
              case QueryModel::DELETE:
            $menuid = $_REQUEST['menuid'];
            $userid=$_REQUEST['userid'];
            if($menuid!=null)
            {
                //$obj = json_decode($Data);
                $Print = UserMenuModel::DeleteUserMenu($menuid,$userid);
                echo json_encode($Print);
                return;
            }
            else{
                return;
            }
            break;
              case QueryModel::SELECT:
                 
                  $userid=$_REQUEST['userid'];
                  if($userid!=null)
                  {
                      // $obj = json_decode($menuid);
                      $Print = UserMenuModel::SelectUserMenu($userid);
                      echo json_encode($Print);
                      return;
                  }
                  else{
                      return;
                  }
                  break;
    }
}
?>
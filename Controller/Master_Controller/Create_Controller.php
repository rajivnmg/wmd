<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/Create_Model.php");
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type)
{
    case QueryModel::INSERT:
        $Data = $_REQUEST['CREATEUSERDATA'];
        $obj = json_decode($Data);
        $Print = Create_Model::insertuser($obj->{'USERID'}, $obj->{'PASSWD'}, $obj->{'USER_NAME'},$obj->{'USER_TYPE'}, $obj->{'PHONE'},$obj->{'MOBILE'}, $obj->{'email'}, $obj->{'ACTIVE'});
		 echo json_encode($Print);
        return;
break;
}
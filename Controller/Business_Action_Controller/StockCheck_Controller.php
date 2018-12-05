<?php


include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/StockCheck_Model.php");

$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    
    case QueryModel::SELECT:
       
        $Print =StockCheck_Model::LoadStockCheck($_REQUEST['POID']); 
        echo json_encode($Print);
        return;
        break;
       
}

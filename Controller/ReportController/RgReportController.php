<?php
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 3600);
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/ReportModel/RgReportModel.php");

$Type = $_REQUEST['TYP'];
switch($Type){
    case "RG23DReport": // call to show the sales report from report
        $Print = RgReportModel::getRg23dReport($_REQUEST['fromdate'],$_REQUEST['todate'],$_REQUEST['pid']);
        echo json_encode($Print);
        return;
        break;
				
    default:
        break;
}


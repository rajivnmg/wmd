<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	if(isset($_REQUEST['TYP']) && $_REQUEST['TYP'] == "POSTATEUPDATE"){
		$res = DashboardModel::UpdatePoState($_REQUEST['POID'],$_REQUEST['POSTATE'],$_REQUEST['HOLDREASON']);
		echo $res;
		return;
		exit;
	}else if(isset($_REQUEST['TYP']) && $_REQUEST['TYP'] == "POSTATUSUPDATE"){
		$res = DashboardModel::UpdatePoStatusById($_REQUEST['POID'],$_REQUEST['CLOSEREASON']);
		echo $res;
		return;
		exit;
	}				 

					

<?php 
include("root.php");
include_once($root_path."GlobalConfig.php");  

$redirect = SITE_URL.'View/ReportView/po_pending_report.php';
header('Location: '.$redirect);
exit();
?>

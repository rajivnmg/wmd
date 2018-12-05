<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Incoming Invoice Without Excise Duty</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/menu.css" type="text/css" media="screen" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Business_action_js/Search.js'></script>
</head>
<body ng-app="Search_app">

<form id="Search" name="Search" method="" ng-controller="Search_Controller" class="smart-green">

<div style="width:33.33%; float:left;">
    <label><span>From Po Date:</span><input type="text" id="txtfromdate" name="" ng-model="Search.FromDate"/></label>
</div>
<div style="width:33.33%; float:left;">
    <label><span>To Po Date:</span><input type="text" id="txttodate" name="txttodate" ng-model="Search.ToDate"/></label>
</div>
<div style="width:33.33%; float:left;">
 <label><span>PO Valid Date:</span><input type="text" id="txtpodate" name="" ng-model="Search.PoVD"/></label>
   
</div>
<div class="clr"></div>
<div style="width:25%; float:left;">
    <label><span>Mode:</span>
         <select id="Mode" name="" ng-model="Search.Mode">
        <option value="">Select One</option>
        <option value="NI">New Insert</option>
        <option value="FA">For Approval</option>
        <option value="BG">Bill Generation</option>
		<option value="PSPO">Partially Supplied PO</option>
		<option value="RPO">Rejected PO</option>
        </select>
    </label>
</div>
<div style="width:25%; float:left;">
    <label><span>PO TYPE:</span><select id="PoType" name="" ng-model="Search.PoType">
<option value="">Select Type</option>
<option value="N">Normal</option>
<option value="R">Recurring</option>

</select></label>
</div>
<div style="width:25%; float:left;">
    <label><span>Status:</span>
         <select id="Status" name="" ng-model="Search.Status">
            <option value="">Select One</option>
            <option value="O">Open</option>
            <option value="C">Close</option>
         </select>
    </label>
</div>
<div style="width:25%; float:left;">
    <label><span>Market segment:</span>
         <select id="marketsegment" name="" ng-model="Search.marketsegment" class="form-control">
									<option value="">Select One</option>
									<?php 								
									require("../../Model/Masters/MarketSegment_Model.php");
									//include("../../Model/DBModel/DbModel.php");
										    $result =  MarketSegmentModel::GetMsList();
											while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
												echo "<option value='".$row['msid']."'>".$row['msname']."</option>";
										}?>
									</select>	
       
    </label>
</div>
<div class="clr"></div>
<div style="width:33.33%; float:left;">
 <label><span>Buyer Name:</span>
         <select id="BuyerId" name="" ng-model="Search.BuyerId">
            <option value="">Select One</option>
            <?php include( "../../Model/Masters/BuyerMaster_Model.php"); //include("../../Model/DBModel/DbModel.php");
                  $result =  BuyerMaster_Model::GetBuyerList();
                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                      echo "<option value='".$row['BuyerId']."' title='".$row['BuyerName']."'>".$row['BuyerName']."</option>";
                  }?>
          </select>
    </label>
   
</div>
<div style="width:33.3%; float:left;">
    <label><span>Principal:</span>
		 <select name="" id="principalid" ng-model="Search.principalid">
            <option value="">Select One</option>
            <?php include( "../../Model/Business_Action_Model/po_model.php");
			                  $result = purchaseorder_Details_Model::showPOPrincipalSupplier('PRINCIPAL_SUPPLIER_MASTER','0','P');
			                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                       echo "<option value='".$row['PID']."'>".$row['PNAME']."</option>";
                              }?>
          </select>
    </label>
</div>
<div style="width:33.3%; float:left;">
    <label><span>PO Number:</span>
                                        <input type="text" id="ponumber" style="" placeholder="PO Number" ng-model="Search.ponumber"></input>
                                       
    </label>
</div>
<div class="clr"></div>

<div align="center">
<input type="button" class="button" value="Search" id="bbb" name="bbb" ng-click="SearchPo();" ng-model="Search.bb"/>
</div>
<div>

<div style=" margin-top:30px; margin-left:-50px;"><table class="flexme4" style="width:100%;"></table></div>

</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#txtfromdate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d'
});
$('#txttodate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d'
});
$('#txtpodate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d'
});
</script>
</br></br></br></br></br></br></br></br></br>
</form>
</body>

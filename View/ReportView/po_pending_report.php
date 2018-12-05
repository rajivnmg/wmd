<?php
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php"); 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PURCHASE ORDER PENDING REPORT</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/menu.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
 <script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script> 
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.pack.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Report_js/po_report.js'></script>       


</head>

<body ng-app="PO_Reports_app">
<?php  include("../../header.php"); ?>
<form name="PO_Reports" id="PO_Reports" ng-controller="PO_Reports_Controller" >

<div id="wrapper">
    
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
			   		
		<!-- po details -->
			
			 <div id="myPoDetails" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1200px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO Details : <span id="bname" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="podetails">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!-- po details -->
			
			<div></div>
			<!-- po INvoice details -->
			
			 <div id="myPoInvoiceDetails" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:830px;">
					<span id="wait" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:99999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Invoice Details : </h4>
					  </div>
					  <div class="modal-body" id="poInvoiceDetails">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!-- po invoice details -->
			
			
		<!-- po Stae -->
			
			 <div id="myPoState" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:600px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO No. : <span id="ponum" style="font-size:16px;"></span><br/>BuyerName : <span id="pobname" style="font-size:16px;"></span>,<br/>PO State : <span id="pstate" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="poState">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!--  po Stae  -->
			
			<!-- Show po Stock -->
			
			 <div id="showPoStock" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1000px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO No. : <span id="stockponum" style="font-size:16px;"></span> ,&nbsp;&nbsp;&nbsp;&nbsp;PO Date : <span id="stockpdate" style="font-size:16px;"></span><br/>BuyerName : <span id="stockpobname" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="PoStock">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!--  Show PO stock -->
			
			
			
             <div class="col-lg-8" style="width: 100%;">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Purchase Order Pending Report
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                           	<div class="form-group">
								<div style="width:25%; float:left;">
								<label>
									<span>Financial Year:</span>
									<select id="cfinancialyear" style="width: 250px;" class="form-control">
										<?php
										$data = file_get_contents("../../finyear.txt"); //read the file										
										$convert = explode("\n", $data); //create array separate by new line	
										for ($i=0;$i<count($convert)-1;$i++){
										 echo "<option value='".trim($convert[$i])."'>".$convert[$i]."</option>";
										}
										?>
									</select>
								</label>
								</div>
                                <div style="width:25%; float:left;">
                                    <label>
                                       <span>From PO Date:</span>
                                        <input type="text" style="width: 250px;" id="txtdatefrom_po" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.FromDate"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                     <label>
                                        <span>To PO Date:</span>
                                        <input type="text" id="txtdateto_po" style="width: 250px;" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.ToDate"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>PO Validity Date:</span>
                                        <input type="text" id="txtpovaliditydate" style="width: 250px;" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.PoVD"></input>
                                    </label>
                                </div>
                                <div class="clr"></div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>PO Number:</span>
                                        <input type="text" ng-model="PO_Reports.ponumber" class="form-control ng-pristine ng-valid" placeholder="PO Number" style="width: 250px;" id="ponumber">
                                    </label>
                                </div>
                                
								
								<div style="width:25%; float:left;">
								 <label><span>PO TYPE:</span><select style="width: 250px;" class="form-control ng-pristine ng-valid" ng-model="PO_Reports.PoType" name="" id="potype">
									<option value="">Select Type</option>
									<option value="N">Normal</option>
									<option value="R">Recurring</option>
									</select></label>									
                                   
                                </div>
                                <div style="width:25%; float:left;">
								 <label><span>Status:</span>
									 <select style="width: 250px;" class="form-control ng-pristine ng-valid" ng-model="PO_Reports.Status" name="" id="Status">
										<option value="">Select One</option>
										<option value="O">Open</option>
										<option value="C">Close</option>
									 </select>
								</label>
															   
                                </div>
                                <div style="width:25%; float:left;">
								 <label><span>Mode:</span>
									 <select style="width: 250px;" class="form-control ng-pristine ng-valid" ng-model="PO_Reports.Mode" name="" id="Mode">
									<option value="">Select One</option>
									<option value="NI">New Insert</option>
									<option value="FA">For Approval</option>
									<option value="BG">Pending PO</option>
									<option value="PSPO">Partially Supplied PO</option>
									<option value="R">Rejected PO</option>
									</select>
								</label>
                                   
                                </div>
								 
                              
                                <div class="clr"></div>
                                <div style="width:50%; float:left;">
                                     <label>
                                        <span>Buyer:</span><hidden id="buyerid" ng-model="PO_Reports.BuyerId"></hidden>
                                        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 570px;" ng-model="PO_Reports.BuyerName" placeholder="Search By Buyer Name" class="form-control"  />
                                    </label>
                                </div>
                                <div style="width:49%; float:left;">
                                    <label>
                                        <span>Principal:</span><hidden ng-model="PO_Reports.principalid" id="principalid" class="ng-pristine ng-valid"></hidden>
                                        <input type="text" class="form-control" placeholder="Type Principal Name" style="z-index: 2; background:transparent;width: 570px;" id="autocomplete-ajax-principal" autocomplete="off">
                                    </label>
                                </div>
                                <div class="clr"></div>
								
								<div style="width:25%; float:left;">
								 <label><span>Market segment:</span>
									 <select style="width: 250px;" class="form-control ng-pristine ng-valid" ng-model="PO_Reports.marketsegment" name="" id="marketsegment">
									<option value="">Select One</option>
									
									<option value="1">AUTO</option><option value="2">GEN</option><option value="3">MRO</option><option value="4">OEM</option> 
									</select>									
								</label>
                                   
                                </div>
                                <?php if($_SESSION['USER_TYPE'] == "B") { ?>
                                <div style="width:25%; float:left;">
									<label>
										<span>Sales Executive*:</span>
										<select name="salseuser" id="salseuser" class="form-control">
										<option value="All" selected>All</option>
										<?php //include("../../Model/DBModel/DbModel.php"); 
										//include( "../../Model/Masters/User_Model.php");  
										$result =  UserModel::getUserByType('E');
									/*	while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
											 if($_SESSION['USER_TYPE'] == "B" && $row['USERID']=="jagrati"){
												echo "<option value='".$row['USERID']."' selected>".$row['USER_NAME']."</option>";
											}else{
												echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
											}
										} */

										while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
											 
												echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
										}
										
										?>  
										</select>
									</label>
								</div>
								<?php }	?>    
								<input type="hidden" id="usertype" name="usertype" value="<?php echo $_SESSION['USER_TYPE']; ?>"/>
									<div style="width:10%; float:left;">                                    
										<input type="text" id="executive_id"  ng-model="PO_Reports.executiveId" style="display:none;">
										<input type="text" id="reportId"  ng-model="PO_Reports.repType" style="display:none;">
									<button type="button" class="btn btn-primary" id="bbb" name="bbb" ng-click="SearchPo('<?php echo $_GET['repType']; ?>','<?php echo $_SESSION['USER']; ?>');" ng-model="PO_Reports.bb" style="margin-top:20px;width:100px;">Search</button>
                                       
                                   
                                </div>
                                <div class="clr"></div>
                               
                                <br><br>
                                <div style=" overflow: scroll;">
                                    <table style="width:100%;" class="polist"></table>
                                </div>
                                
                            </div>
							
						
                        </div>
                        <!-- /.panel-body -->
                    </div>
                 </div>
             </div>
           </div>
       </div>
   </div>

<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
    $('#txtfromdate').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    $('#txttodate').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    $('#txtpodate').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    $('#txtdateto_po').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    $('#txtdatefrom_po').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    $('#txtpovaliditydate').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d',
            scrollInput:false
    });
    </script>
<br/><br/><br/><br/>
</form>
<!-- Show PoPickList -->
			
			 <div id="showPoPickList" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1000px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO No. : <span id="picklistponum" style="font-size:16px;"></span> ,&nbsp;&nbsp;&nbsp;&nbsp;PO Date : <span id="picklistpdate" style="font-size:16px;"></span><br/>BuyerName : <span id="picklistpobname" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="PoPickList">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!--  showPoPickList -->
			
			
			<!-- Change Po states  -->			
			 <div id="changePOStatusDiv" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:600px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO No. : <span id="picklistponum1" style="font-size:16px;"></span> ,&nbsp;&nbsp;&nbsp;&nbsp;PO Date : <span id="picklistpdate1" style="font-size:16px;"></span><br/>BuyerName : <span id="picklistpobname1" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="changePOStatus">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
<!--  Change Po states -->
			
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

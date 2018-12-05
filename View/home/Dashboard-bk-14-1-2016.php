<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include("../../Model/ReportModel/Report1Model.php");
?>

<!DOCTYPE html>
<html lang="en">
<?php 
include 'head.php'; 
?>
<body ng-app="dashboard_app">
<form name="form1" id="form1" ng-controller="dashboard_Controller" data-ng-init="init();">
    <div id="wrapper">
        <!-- Navigation -->
        <?php include 'menu.php'; ?>

        <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
		
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
			
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                
                <div class="modal-dialog">
                    <div class="modal-content" style="width:100%; margin-left:-200px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            DataTables Advanced Tables
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>CodePart</th>
                                            <th>Discription</th>
                                            <th>Principal</th>
                                            <th>Price</th>
                                            <th>LSC</th>
                                            <th>USC</th>
                                            <th>Excise Quantity</th>
                                            <th>Non-Excise Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="odd gradeX" ng-repeat="item in dashboard._items">
                                            <td>{{item.code_partNo}}</td>
                                            <td>{{item.item_codepart}}</td>
                                            <td>{{item.item_desc}}</td>
                                            <td>{{item.principalname}}</td>
                                            <td>{{item.price}}</td>
                                            <td>{{item.lsc}}</td>
                                            <td>{{item.usc}}</td>
                                            <td>{{item.tot_exciseQty}}</td>
                                            <td>{{item.tot_nonExciseQty}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{dashboard.total_item}}</div>
                                    <div>Current Stock!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"  data-toggle="modal" data-target="#myModal" ng-click="callAllItem();">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{dashboard.new_outgoing}}</div>
                                    <div>Outgoing Invoice!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{dashboard.new_purchase_order}}</div>
                                    <div>Puchase Orders!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge">{{dashboard.lsc_item}}</div>
                                    <div>LSC Item!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left" data-toggle="modal" data-target="#myModal" ng-click="callLSCItem();">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        
                    </div>
                </div>
            </div>
             <div class="row">
                 <div class="col-lg-8" style="width: 100%;">
                      <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Purchase Order
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>From Date:</span>
                                        <input type="text" style="width: 250px;" id="txtdatefrom_po" placeholder="dd/mm/yyyy" class="form-control" ng-model="dashboard.FromDate"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>To Date:</span>
                                        <input type="text" id="txtdateto_po" style="width: 250px;" placeholder="dd/mm/yyyy" class="form-control" ng-model="dashboard.ToDate"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>PO Validity Date:</span>
                                        <input type="text" id="txtpovaliditydate" style="width: 250px;" placeholder="dd/mm/yyyy" class="form-control" ng-model="dashboard.PoVD"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>PO Number:</span>
                                        <input type="text" id="ponumber" style="width: 250px;" placeholder="PO Number" class="form-control" ng-model="dashboard.ponumber"></input>
                                    </label>
                                </div>
                                <div class="clr"></div>
								
								<div style="width:25%; float:left;">
								 <label><span>PO TYPE:</span><select id="PoType" name="" ng-model="dashboard.PoType" class="form-control" style="width: 250px;">
									 <option value="">Select Type</option>
							<?php
								include( "../../Model/Param/param_model.php");
								$result =  ParamModel::GetParamList('PO','TYPE');
									while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
										   echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
									 }
									  
						    ?>
									
									</select></label>
                                   
                                </div>
                                <div style="width:25%; float:left;">
								 <label><span>Status:</span>
									 <select id="Status" name="" ng-model="dashboard.Status" class="form-control" style="width: 250px;">
										<option value="">Select One</option>
										<option value="O">Open</option>
										<option value="C">Close</option>
									 </select>
								</label>
															   
                                </div>
                                <div style="width:25%; float:left;">
								 <label><span>Mode:</span>
									 <select id="Mode" name="" ng-model="dashboard.Mode" class="form-control" style="width: 250px;">
									<option value="">Select One</option>
									<option value="NI">New Insert</option>
									<option value="FA">For Approval</option>
									<option value="BG">Pending PO</option>
									<option value="PSPO">Partially Supplied PO</option>
									<option value="R">Rejected PO</option>
									</select>
								</label>
                                   
                                </div>
								  <div style="width:25%; float:left;">
								 <label><span>Market segment:</span>
									 <select id="marketsegment" name="" ng-model="dashboard.marketsegment" class="form-control" style="width: 250px;">
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
                                <div style="width:50%; float:left;">
                                    <label>
                                        <span>Buyer:</span><hidden id="buyerid" ng-model="dashboard.BuyerId"></hidden>
                                        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 564px;" placeholder="Search By Buyer Name" class="form-control" onKeyPress="loadBuyerByName(this.value);" />
                                    </label>
                                </div>
                                <div style="width:50%; float:left;">
                                    <label>
                                        <span>Principal:</span><hidden id="principalid" ng-model="dashboard.principalid"></hidden>
                                        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;width: 564px;" placeholder="Type Principal Name" class="form-control"/>
                                    </label>
                                </div>
                                <div class="clr"></div>
                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" ng-click="SearchPoDashboard();" ng-model="Search.bb">Search</button>
                                <br/><br/>
                                <div style=" overflow: scroll;">
                                    <table class="polist" style="width:100%;"></table>
                                </div>
                                
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Purchase Order Pending For Approval
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div style="margin-top:20px; margin-left:20px;" id="polist_approval">
                                <table id="po_approval_list" class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Order Date</th>
                                            <th>Validity Date</th>
                                            <th>Buyer Name</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>PO Value</th>
                                        </tr>
                                    </thead>
                            
                            <?php
                           // include( "../../Model/ReportModel/Report1Model.php");
                           // include("../../Model/DBModel/DbModel.php");

                            if($TYPE =="S"){
                            $result =  Report1Model::GetPOListOfPandingManagmentApproval_SuperAdmin();
                            }
                            else if($TYPE =="M"){
                            $result =  Report1Model::GetPOListOfPandingManagmentApproval_Managment();
                            }
                            while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                           
								  $potype ='';
								  if($row['Type'] == "N"){
									$potype ="Normal";
								  }else if($row['Type'] == "B"){
									$potype ="Bundle";
								  }else{
									$potype ="Recurring";
								  }
								  
                                  $bpoId=$row['bpoId'];
                            echo "<tbody> <tr class='odd gradeX'>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpono']."</td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpoDate']."</a></td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpoVDate']."</a></td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['BuyerName']."</a></td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['LocationName']."</a></td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$potype."</a></td>";
                                echo "<td><a href='../Business_View/management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['po_val']."</a></td>";
                                //echo "<td><a href='management_approval_form.php?POID=".$bpoId."'>Go To</a></td>";
                            echo "</tr> </tbody>";
                            //echo "<option value='".$row['bpoId']."'>".$row['bpono']."</option>";
                            }?>
                            </table>
                            
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                   
                </div>
            </div>
            
           
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <!-- jQuery Version 1.11.0 -->
    <script src="../js/boot_js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../js/boot_js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
    
<!-- DataTables JavaScript -->
    <script src="../js/boot_js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="../js/boot_js/plugins/dataTables/dataTables.bootstrap.js"></script>
    
    <!-- Morris Charts JavaScript -->
<!--    <script src="../js/boot_js/plugins/morris/raphael.min.js"></script>
    <script src="../js/boot_js/plugins/morris/morris.min.js"></script>
    <script src="../js/boot_js/plugins/morris/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript -->
    <script src="../js/boot_js/sb-admin-2.js"></script>
    
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
    </script>
	
    <script>
    $(document).ready(function() {
        $('#po_approval_list').dataTable();
    });
    </script>
	 <script>
    $(document).ready(function() {
        $('#dataTables-podetails').dataTable();
    });
    </script>
    <link rel="stylesheet" type="text/css" href="../css/a/styles.css"/>
    <script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
    <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
    <script type='text/javascript' src='../js/boot_js/home.js'></script>
    <script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
    <script>
 
    $('#txtdateto_po').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d'
    });
    $('#txtdatefrom_po').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d'
    });
    $('#txtpovaliditydate').datetimepicker({
            lang:'en',
            timepicker:false,
            format:'Y-m-d',
            formatDate:'Y-m-d'
    });

    </script>
</form>
</body>

</html>

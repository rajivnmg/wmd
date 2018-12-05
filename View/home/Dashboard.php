<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include("../../Model/ReportModel/Report1Model.php");
	$TYPE = $_SESSION['USER_TYPE'];
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
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/multiweld/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
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
					<span id="wait" style="margin-left:45%;margin-top:100px;display:none;background: url(/multiweld/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:99999;background-size: contain;"></span>
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
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/multiweld/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
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
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/multiweld/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
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
			<div></div>
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
                                            <!--<th>Excise Quantity</th>
                                            <th>Quantity</th>-->
											<th>Quantity</th>
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
                                            <!--<td>{{item.tot_exciseQty}}</td>
                                            <td>{{item.tot_nonExciseQty}}</td>-->
											<td>{{item.tot_Qty}}</td>
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
                   
                  		  <!-- /.panel-body QUOTATAION-->
                    
                     <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Payment Pending
                        </div>
                        <!-- /.panel-heading -->
                      <div class="panel-body">
                            <div class="form-group">                      
                                <div class="clr"></div>
                                <div>
                                    <table id="payment_list" class="table tablesorter table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Invoice Number</th>
                                            <th>Invoice Date</th>
                                            <th>Due Date</th>
                                            <th>Invoice Amt.</th>
                                            <th>Buyer Name</th>
                                            <th>Sales Executive</th>
                                            <th>Due Amt.</th>
                                        </tr>
                                        </tr>
                                    </thead><tbody> 
                                    <?php
									$data = file_get_contents("../../finyear.txt"); //read the file
									$convert = explode("\n", $data); //create array separate by new line
									
                                      $result_payment=Report1Model::GetPendingPaymentListForAdmin($_SESSION["USER"],'P',$TYPE,$convert[0]);
                                   
                                      while($row_pay=mysql_fetch_array($result_payment, MYSQL_ASSOC)){
										$type='buyer';
                                       echo "<tr class='odd gradeX'>";
                                       echo "<td>".$row_pay['invoiceNo']."</td>";
                                       echo "<td>".date("d/m/Y",strtotime($row_pay['invoiceDate']))."</td>";
                                       echo "<td>".date("d/m/Y",strtotime($row_pay['dueDate']))."</td>";
                                       echo "<td>".$row_pay['invoiceAmount']."</td>";
                                       
                                        echo "<td><a href='javascript:buyer_customer_detail(".$row_pay['buyerid'].",\"".$type."\")');' title='Click To View Buyer Detail'>".$row_pay['BuyerName']."</a></td>";
                                       
                                       echo "<td>".$row_pay['executiveId']."</td>";                                       
                                       echo "<td>".$row_pay['balanceAmount']."</td>";
                                       echo "</tr>";	 
                                      }
                                    ?>
                                     </tbody></table>
                                </div>
                                <div class="clr"></div>
                                <br/><br/>
                               <a href="<?php print SITE_URL.OINVOICE_PAYMENT_PENDING_LIST; ?>" ><button type="button" class="btn btn-primary" id="bbb" name="bbb"  ng-model="Search.bb">More Info</button></a> 
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    
				</div> 
                   <!-- /.panel -->
                     <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Quotation's
                        </div>
                        <!-- /.panel-heading -->
                       <div class="panel-body">
                            <div class="form-group">                      
                                <div class="clr"></div>
                                <div>
                                    <table id="quot_list" class="table tablesorter table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Quation Number</th>
                                            <th>Quotation Date</th>
                                            <th>Principal Name</th>
                                            <th>Customer Name/Buyer Name</th>
                                            <th>Contact Person</th>
                                           
                                        </tr>
                                    </thead><tbody>
                                     <?php
                                 
									$k=0;
                                      $result_partial = Report1Model::GetQuotationList($_SESSION["USER"],'open');
                                     
                                        while($row_pa = mysql_fetch_array($result_partial, MYSQL_ASSOC)){
										//var_dump($row_pa); 
										 $_cust_id = 0;
										 $_buyer_id = 0;
										 $_buyer_id = $row_pa['BuyerId'];
										 $_cust_id = $row_pa['cust_id'];
										 $name='';
										 $type='';
										 $id=0;
										if(!empty($_buyer_id) && !is_null($_buyer_id) && $_buyer_id != 'NULL' && $_buyer_id != '' && $_buyer_id != '0'){		
										
												$Buyer = Report1Model::GetQuotationBuyerName($_buyer_id);	
												$name = $Buyer['BuyerName'];
												$type = 'buyer';
												$id = $_buyer_id;
										}else if(!empty($_cust_id)){
												$Customer = Report1Model::GetQuotationCustomerName($_cust_id);		
														
												$name = $Customer['cust_name'];
												$type='cust';
												$id = $_cust_id;
										}
                                       echo " <tr class='odd gradeX'>";
                                       echo "<td>".++$k."</td>";
                                       echo "<td>".$row_pa['quotNo']."</td>";
                                       echo "<td>".date("d/m/Y",strtotime($row_pa['quotDate']))."</td>";
                                       echo "<td>".$row_pa['Principal_Supplier_Name']."</td>";
                                       echo "<td><a href='javascript:buyer_customer_detail(".$id.",\"".$type."\")');' title='Click To View Buyer Detail'>".$name."</a></td>";
                                                                         
                                       echo "<td>".$row_pa['contact_person']."</td>";
									   
                                       echo "</tr>";	} 
                                  
                                    ?>
                                    </tbody> </table>
                                </div>
                                <div class="clr"></div>
                                <br/><br/>
                               <a href="<?php print SITE_URL.VIEWQUATION;  ?>" ><button type="button" class="btn btn-primary" id="bbb" name="bbb"  ng-model="Search.bb">More Info</button></a> 
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
					
			
            
           
            <!-- /.row -->
                </div>
            </div>
            
           
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
        
        
        		<!-- Buye-customer details -->
			
			 <div id="Quataion_buyer_customer_detail" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1200px;">
					<span id="wait" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span> 
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Buyer/Customer Detail: </h4>
					  </div>
					  <div class="modal-body" id="qBCdetail">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div> 
			<!-- Buye-customer details  -->
        
        
        

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
</body>

</html>

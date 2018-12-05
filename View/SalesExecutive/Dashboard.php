<?php
include('root.php');
include($root_path."GlobalConfig.php");
include($root_path."Model/ReportModel/Report1Model.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>
<body ng-app="dashboard_app">
<form name="form1" id="form1" ng-controller="dashboard_Controller" data-ng-init="init('<?php echo $_SESSION["USER"];?>');">
    <div id="wrapper">
        <!-- Navigation -->
        <?php include 'menu.php'; ?>

        <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
		
			<!-- po details -->
			
			 <div id="myPoDetails" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1200px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(../../images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">PO Details : </h4>
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
			
			
		
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                
                <div class="modal-dialog">
                    <div class="modal-content" style="width:1000px; margin-left:-200px;">
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
                                    <div class="huge">{{dashboard.total_pending_po}}</div>
                                    <div>Pending Purchase Order!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left"  data-toggle="modal" data-target="#myModal" ng-click="callAllItem-1();">View Details</span>
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
                                    <div class="huge">{{dashboard.total_deliverd_po}}</div>
                                    <div>PO Delivered!</div>
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
                                    <div class="huge">{{dashboard.total_partial_deliverd_po}}</div>
                                    <div>PO Partialy Delivered!</div>
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
                                    <div class="huge">{{dashboard.total_pending_payment}}</div>
                                    <div>Payment Pending!</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left" data-toggle="modal" data-target="#myModal" ng-click="callLSCItem-1();">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        
                    </div>
                </div>
            </div>
             <div class="row">
                 <div class="col-lg-8" style="width:100%;height:100%;">
                      
                      
                     <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Purchase Order
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">                      
                                <div class="clr"></div>
                                <div>
                                    <table id="po_list" class="table tablesorter table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Order Date</th>
                                            <th>Validity Date</th>
                                            <th>Buyer Name</th>
                                            <th>Sales Executive</th>
                                            <th>Type</th>
                                            <th>PO Value</th>
                                        </tr>
                                    </thead><tbody>
                                    <?php
                                     
                            
                                      //$result_pending=Report1Model::GetPendingPoList($_SESSION["USER"],'pending');
									  $result_pending=Report1Model::GetOpenPoList($_SESSION["USER"],'open');
                                      
                                      while($row_p=mysql_fetch_array($result_pending, MYSQL_ASSOC)){
									 
										$type ='buyer';
                                       echo " <tr class='odd gradeX'>";
                                       echo "<td ><a href='javascript:salesPoDetail(".$row_p['bpoId'].",\"".$row_p['bpono']."\");' title='Click To View Invoices'>".$row_p['bpono']."</a></td>";
                                       echo "<td>".date("d/m/Y",strtotime($row_p['bpoDate']))."</td>";
                                       echo "<td>".date("d/m/Y",strtotime($row_p['bpoVDate']))."</td>";
                                        echo "<td><a href='javascript:buyer_customer_detail(".$row_p['BuyerId'].",\"".$type."\")');' title='Click To View Buyer Detail'>".$row_p['BuyerName']."</a></td>";
                                       echo "<td>".$row_p['executiveId']."</td>";
                                       if($row_p['bpoType']=='N'){
									   	echo "<td>Normal</td>";
									   }else if($row_p['bpoType']=='R'){
									     echo "<td>Recurring</td>";
									   }else if($row_p['bpoType']=='B'){
										 echo "<td>Bundle</td>";
									   }
                                       
                                       echo "<td>".$row_p['po_val']."</td>";
                                       echo "</tr>";	 
                                      }
                                    ?>
                                    </tbody> </table>
                                </div>
                                <div class="clr"></div>
                                <br/><br/>
                                
                               <a href="<?php print SITE_URL.PO_PENDING_STATEMENT.'?repType=pending'; ?>" ><button type="button" class="btn btn-primary" id="bbb" name="bbb"  ng-model="Search.bb">More Info</button></a> 
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                   
                    <!-- /.panel -->
                   <!--  <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Purchase Order Partial Delivered
                        </div> -->
                        <!-- /.panel-heading -->
                      <!-- <div class="panel-body">
                            <div class="form-group">                      
                                <div class="clr"></div>
                                <div>
                                    <table id="po_list" class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Order Date</th>
                                            <th>Validity Date</th>
                                            <th>Buyer Name</th>
                                            <th>Sales Executive</th>
                                            <th>Type</th>
                                            <th>PO Value</th>
                                        </tr>
                                    </thead> -->
                                     <?php
                                     
                            
                                    /*   $result_partial=Report1Model::GetPendingPoList($_SESSION["USER"],'partial');
                                      
                                      while($row_pa=mysql_fetch_array($result_partial, MYSQL_ASSOC)){
                                       echo "<tbody> <tr class='odd gradeX'>";
                                       echo "<td>".$row_pa['bpono']."</td>";
                                       echo "<td>".$row_pa['bpoDate']."</td>";
                                       echo "<td>".$row_pa['bpoVDate']."</td>";
                                        echo "<td>".$row_pa['BuyerName']."</td>";
                                       echo "<td>".$row_pa['executiveId']."</td>";
                                       if($row_pa['bpoType']=='N')
                                       {
									   	echo "<td>Normal</td>";
									   }else if($row_pa['bpoType']=='R')
									   {
									     echo "<td>Recurring</td>";
									   }                                       
                                       echo "<td>".$row_pa['po_val']."</td>";
                                       echo "</tr> </tbody>";	 
                                      } */
                                    ?>
                                    <!--</table>
                                </div>
                                <div class="clr"></div>
                                <br/><br/>
                               <a href="<?php// print SITE_URL.PO_PARTIAL_DELIVERED_STATEMENT.'?repType=partial';  ?>" ><button type="button" class="btn btn-primary" id="bbb" name="bbb"  ng-model="Search.bb">More Info</button></a> 
                            </div>
                        </div> -->
                        <!-- /.panel-body -->
                   <!-- </div> -->
					
					  <!-- /.panel-body QUOTATAION-->
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
										
										 $_buyer_id = $row_pa['BuyerId'];
										 $_cust_id = $row_pa['cust_id'];
										 $name='';
										 $type='';
										 $id=0;
										if($_buyer_id > 0){										
												$Buyer = Report1Model::GetQuotationBuyerName($_buyer_id);	
												$name = $Buyer['BuyerName'];
												$type = 'buyer';
												$id = $_buyer_id;
										}else if($_cust_id > 0){	
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
                                     
                                      $result_payment=Report1Model::GetPendingPaymentList($_SESSION["USER"],'P');
                                   
                                      
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
	
     <script type="text/javascript" src="../js/jquery-latest.js"></script>
	  <!-- jQuery Version 1.11.0 -->
    <script src="../js/boot_js/jquery-1.11.0.js"></script>
	<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
   

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
   
	
	<script type="text/javascript">
	$(function() {
		$("#po_list").tablesorter();
		
	});	
	$(function() {
		$("#payment_list").tablesorter();
		
	});	
	$(function() {
		$("#quot_list").tablesorter();
		
	});	
	</script>
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
    <link rel="stylesheet" type="text/css" href="../css/a/styles.css"/>
    <script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
    <link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
    <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
    <script type='text/javascript' src='../js/boot_js/salesExecutiveHome.js'></script>
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
</form>
</body>

</html>

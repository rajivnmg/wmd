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
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Report_js/po_report.js'></script>       


</head>

<body ng-app="PO_Reports_app">
 <?php 
	//include '../SalesExecutive/header.php';
	include("../../header.php");
?>
<form name="PO_Reports" id="PO_Reports" ng-controller="PO_Reports_Controller" data-ng-init="SearchPendingPayment('<?php echo $_SESSION['USER']; ?>');">

<div id="wrapper">
   
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
             <div class="col-lg-8" style="width: 100%;">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Outgoing Invoice Payment Pending Report
                            <img src="../img/pdf_icon.png" onclick="GetpdfPendingPaymentList('<?php echo $_SESSION['USER']; ?>');" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer"  title="Click To Download As PDF">
                            <img src="../img/excel_icon.png" onclick="GetexcelPendingPaymentList('<?php echo $_SESSION['USER']; ?>');" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer"  title="Click To Download As Excel">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
							 <div style="width:25%; float:left;">
								<div style="width:90%; float:left;">
								<label>
									<span>Financial Year:</span>
									<select id="ddlfinancialyear" style="width: 250px;" class="form-control" ng-model="PO_Reports.finyear">
										<?php
										$data = file_get_contents("../../finyear.txt"); //read the file
										$convert = explode("\n", $data); //create array separate by new line
										for ($i=0;$i<count($convert);$i++){
										 echo "<option value='".trim($convert[$i])."'>".$convert[$i]."</option>";
										}
										?>
									</select>
								</label>
								</div>
							 </div>	
                              <div style="width:25%; float:left;">
                                <div style="width:90%; float:left;">
                                    <label>
                                        <span>From Invoice Date:</span>
                                        <input type="text" style="width: 250px;" id="txtdatefrom_po" placeholder="yyyy-mm-dd" class="form-control" ng-model="PO_Reports.FromDate"></input>
                                    </label>
                                </div>
                                 <div class="clr"></div>
                               </div>  
                              <div style="width:25%; float:left;">  
                                <div style="width:90%; float:left;">
                                    <label>
                                        <span>To Invoice Date:</span>
                                        <input type="text" id="txtdateto_po" style="width: 250px;" placeholder="yyyy-mm-dd" class="form-control" ng-model="PO_Reports.ToDate"></input>
                                    </label>
                                </div>   
                                
                                <div class="clr"></div>
                               </div>                               
                                <div style="width:24%; float:left;">
                                <div style="width:90%; float:left;">
                                   <label><span>Invoice TYPE:</span>
                                   <select id="invoicetype" name="invoicetype" style="width: 250px;" ng-model="PO_Reports.InvoiceType"  class="form-control">
                                      <option value="">Select Type</option>
                                      <option value="E">Excise</option>
                                      <option value="N">Non-Excise</option>
                                   </select></label>
                                </div>
                                 
                              <div class="clr"></div>
                              </div>
                               <div style="width:50%; float:left;">
                                <div style="width:50%; float:left;">
                                    <label>
                                        <span>Buyer:</span><hidden id="buyerid" ng-model="PO_Reports.BuyerId"></hidden>
                                        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 564px;" ng-model="PO_Reports.BuyerName" placeholder="Search By Buyer Name" class="form-control" onkeypress="loadBuyerByName(this.value);" />
                                    </label>
                                </div>
                                 <div class="clr"></div>
                               </div>
                              
                                <div style="width:25%; float:left;">
                                   <label>
                                        <span>Invoice Number:</span>
                                        <input type="text" id="invoiceno" style="width: 250px;" ng-model="PO_Reports.invoice_no" placeholder="Search By Invoice No" class="form-control"  />
                                    </label>
                                </div>
                                <?php  if($_SESSION['USER_TYPE'] == 'A' || $_SESSION['USER_TYPE'] == 'M' || $_SESSION['USER_TYPE'] == 'S' || $_SESSION['USER_TYPE'] == 'B') { ?>
                                  <div style="width:24.5%; float:left;">
                                    <label>
                                        <span>Sales Executive*:</span>
                                        <select name="salseuser" id="salseuser" class="form-control" style="width: 250px;">
                                        <option value="">Select Sales Executive</option>
                                        <?php //include("../../Model/DBModel/DbModel.php"); 
										//include( "../../Model/Masters/User_Model.php");  
                                        $result =  UserModel::getUserByType('E');
                                        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                                             echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
                                        }
                                        ?>    
                                        </select>
                                    </label>
                            </div>
                            <?php  } ?>
                            
                            <input type="hidden" id="usertype" name="usertype" value="<?php echo $_SESSION['USER_TYPE']; ?>" />
                           
                                 <div style="float:left;margin-top: 20px;">
									<button type="button" class="btn btn-primary" id="bbb" name="bbb" ng-click="SearchPendingPayment('<?php echo $_SESSION['USER']; ?>');" ng-model="PO_Reports.bb">Search</button>
									<div class="clr"></div>
                                 </div>
                                 
                               </div>
                               
                              
                               <div class="clr"></div>
                             
                               <div class="clr"></div>
                                 <br/> 
                               
                                <div style=" overflow: scroll;">
                                    <table class="polist" style="width:100%;"></table>
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
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

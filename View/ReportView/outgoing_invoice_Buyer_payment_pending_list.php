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

<body ng-app="PO_Reports_app" bgcolor="#fff">
<?php 
         //include '../SalesExecutive/header.php'; 
		 include("../../header.php");
?>
<form name="PO_Reports" id="PO_Reports" ng-controller="PO_Reports_Controller" data-ng-init="SearchBuyerPendingPayment('<?php echo $_SESSION['USER']; ?>');">

<div id="wrapper">
    
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
             <div class="col-lg-8" style="width: 100%;">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Buyer Payment Pending Report
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                              <div style="width:50%; float:left;">
                                <div style="width:50%; float:left;">
                                    <label>
                                        <span>From Invoice Date:</span>
                                        <input type="text" style="width: 250px;" id="txtdatefrom_po" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.FromDate"></input>
                                    </label>
                                </div>
                                 <div class="clr"></div>
                               </div>  
                              <div style="width:50%; float:left;">  
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>To Invoice Date:</span>
                                        <input type="text" id="txtdateto_po" style="width: 250px;" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.ToDate"></input>
                                    </label>
                                </div>
                                <div class="clr"></div>
                               </div> 
                               <div class="clr"></div>
                               <div style="width:50%; float:left;">
                                <div style="width:50%; float:left;">
                                    <label>
                                        <span>Buyer:</span><hidden id="buyerid" ng-model="PO_Reports.BuyerId"></hidden>
                                         <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 564px;" ng-model="PO_Reports.BuyerName" placeholder="Search By Buyer Name" class="form-control" onKeyPress="loadBuyerByName(this.value);" />
                                    </label>
                                </div>
                                 <div class="clr"></div>
                               </div>
                               <div style="width:50%; float:left;">
                                <div style="width:100%; float:left;">
                                   <label><span>Invoice TYPE:</span>
                                   <select id="invoicetype" name="invoicetype" style="width: 250px;" ng-model="PO_Reports.InvoiceType"  class="form-control">
                                      <option value="">Select Type</option>
                                      <option value="E">Excise</option>
                                      <option value="N">Non-Excise</option>
                                   </select></label>
                                </div>
                                
                                 <div class="clr"></div>
                               </div>
                               <div class="clr"></div>
                               <div style="width:80%; float:left;" align="center">
                                <br/>
                               <input type="text" id="executive_id"  ng-model="PO_Reports.executiveId" style="display:none;">
                                   <input type="text" id="reportId"  ng-model="PO_Reports.repType" style="display:none;">
                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" ng-click="SearchBuyerPendingPayment('<?php echo $_SESSION['USER']; ?>');" ng-model="PO_Reports.bb">Search</button>
                               </div>
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

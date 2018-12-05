<?php
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/Report_js/challan_stock.js'></script>
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
 <script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script> 
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<title>Excise & Non-Excise And Chalan Issue  Statement Report </title>      


</head>

<body ng-app="PO_Reports_app" >
<?php 
 include("../../header.php");
 ?>
<form name="PO_Reports" id="PO_Reports" ng-controller="PO_Reports_Controller" data-ng-init="SearchPo();">

 <div id="wrapper">
       
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
             <div class="col-lg-8" style="width: 100%;">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i> Excise & Non-Excise And Chalan Issue  Statement Report 
                            <img src="../img/pdf_icon.png" onclick="GetpdfChallanReport();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer" class="__web-inspector-hide-shortcut__" title="Click To Download As PDF">
                            <img src="../img/excel_icon.png" onclick="GetexcelChallanReport();" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer" title="Click To Download As Excel">
                        </div>
                        
                        <!-- po Stae -->
			
			 <div id="viewChallanD" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1020px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">CodePartNo. : <span id="codepartno" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="viewChallan">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!--  po Stae  -->
			
			<!-- Show po Stock -->
			

                        
                        
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                                <div style="width:25%; float:left;">
                                    <label>
                                       &nbsp;
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                       &nbsp;
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                    &nbsp;
                                    <br/>
                                    </label>
                                </div>
                               <div style="width:25%; float:left;">
                                    <label>
                                        <span></span>
                                        <input type="text" id="txtsearchkey" style="width: 250px;" placeholder="Search By Code Part No" ng-keyup="SearchPo();" class="form-control" ng-model="PO_Reports.SearchKey"></input>
                                    </label>
                                </div>
                                
                                <div class="clr"></div>
                                 
                              
                                <div style=" overflow: scroll;">
                                    <table class="polist" style="width:100%;">
                                    	
                                  
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                 </div>
             </div>
           </div>
       </div>
  


</form>
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

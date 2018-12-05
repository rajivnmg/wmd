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
<title>Buyer Wise Revenue Detail REPORT</title>
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
     

  <!-- Multiple Select -->
  <link href="../css/multiple-select.css" rel="stylesheet" />


</head>

<body ng-app="PO_Reports_app" bgcolor="#fff">
<?php 
         //include '../SalesExecutive/header.php'; 
		 include("../../header.php");
?>
<form name="PO_Reports" id="PO_Reports" ng-controller="PO_Reports_Controller" data-ng-init="SearchFinancialYearBuyerRevenue('<?php echo $_SESSION['USER']; ?>');">

<div id="wrapper">
    
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
             <div class="col-lg-8" style="width: 100%;">
                   <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Buyer Wise Revenue Detail Report
                            <img src="../img/pdf_icon.png" onclick="GetpdfYearRevenue('<?php echo $_SESSION['USER']; ?>');" style=" width: 30px; height: 30px; margin-top: -7px; margin-right:20px; float: right;cursor:pointer"  title="Click To Download As PDF">
                            <img src="../img/excel_icon.png" onclick="GetexcelYearRevenue('<?php echo $_SESSION['USER']; ?>');" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; margin-right:20px; float: right;cursor:pointer"  title="Click To Download As Excel">
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="form-group">
                              <div style="width:100%; float:left;">
                                <div style="width:33%; float:left;">
                                 <label><span>Financial Year:</span>
                                   <select id="financialyear" name="financialyear" style="width: 250px;" ng-model="PO_Reports.FnYear"  class="form-control">
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
                              
                            <div style="width:33%; float:left;">
                            <label>
                               <span>From Date:</span>
                                <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.FromDate"></input>
                            </label>
							</div>
							<div style="width:33%; float:left;">
								<label>                               
                                  <span>To Date:</span>
                                <input type="text" id="txtdateto" placeholder="dd/mm/yyyy" class="form-control" ng-model="PO_Reports.ToDate"></input>
                            </label>
							</div>
                             <div style="width:33%; float:left;">
                                    <label>
                                        <span>Sales Executive*:</span>
                                        <select name="salseuser" id="salseuser"  class="form-control">
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
                              
                            <div style="width:33%; float:left;">
                                <label><span>PO TYPE:</span>
                                   <select id="potype" name="potype" style="width: 250px;" ng-model="PO_Reports.PoType"  class="form-control">
                                      <option value="">Select Type</option>
                                      <option value="N">Normal</option>
                                      <option value="R">Recurring</option>
                                     </select>
                                    </label>
                                    
                            </div>  
                            
                            <div style="width:33%; float:left;">
								<label>
									<span>Location:</span><select name="location" style="width: 250px;" id="location"  class="form-control" ng-model="PO_Reports.locationid">
										<option value="0">Select Location</option>
										<?php //include("../../Model/DBModel/DbModel.php"); 
										include( "../../Model/Masters/LocationMaster_Model.php");  
                                        $result =  LocationMasterModel::GetAllLocations();
                                        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                                             echo "<option value='".$row['LocationId']."'>".$row['LocationName']."</option>";
                                        }
                                        ?>    
										</select>
								</label>
								</div>
                             
                          </div>  
                                                      
                               
                                <div style="width:100%; float:left;">  
                                <div style="width:49%; float:left;">
                                  <label>
                                        <span>Principal:</span><hidden id="principalid" ng-model="PO_Reports.principalid"></hidden>
                                        <input type="text" id="autocomplete-ajax-principal" style=" z-index: 2; background: transparent;width: 564px;" ng-model="PO_Reports.PrincipalName" placeholder="Search By Principal Name" class="form-control"  />
                                    </label>   
                                </div>
                                <div style="width:49%; float:left;">
                                  <label>
                                        <span>Buyer:</span><hidden id="buyerid" ng-model="PO_Reports.BuyerId"></hidden>
                                        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 564px;" ng-model="PO_Reports.BuyerName" placeholder="Search By Buyer Name" class="form-control" onKeyPress="loadBuyerByName(this.value);"/>
                                    </label>   
                                </div>
                                <div class="clr"></div>
                               </div> 
                              
                               <div class="clr"></div>
                               <div style="width:90%; float:left;" align="center">
                              
                               <input type="text" id="executive_id"  ng-model="PO_Reports.executiveId" style="display:none;">
                                 <input type="hidden" id="usertype" name="usertype" value="<?php echo $_SESSION['USER_TYPE']; ?>" />
                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" ng-click="SearchFinancialYearBuyerRevenue('<?php echo $_SESSION['USER']; ?>');" ng-model="PO_Reports.bb">Search</button>
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
 $('#txtdateto').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#txtdatefrom').datetimepicker({
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

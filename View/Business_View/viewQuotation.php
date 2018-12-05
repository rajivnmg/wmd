<?php 
include("root.php");
include_once($root_path."GlobalConfig.php");  ?>
<?php include("../home/head.php") ?>
<?php 
include("root.php");
include_once($root_path."GlobalConfig.php");  ?>
<!doctype html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/a/styles.css"/>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>

 <!-- Multiple Select -->
  <link href="../css/multiple-select.css" rel="stylesheet" />

<title>Quotation</title>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>
<body>
<form id="form1">
    <div id="wrapper">
        <?php include '../home/menu.php'; ?>
           <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
		   
		   
		   
		   <!-- Quot po details -->
			
			 <div id="myQuotDetails" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:1200px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(../../images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Quotation's PO List : </h4>
					  </div>
					  <div class="modal-body" id="polist">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!-- Quot po details -->
		   
		   
               <div class="row">
                 <div class="col-lg-8" style="width: 100%;">
                     <div class="panel panel-default">
                        <div class="panel-heading">
						
                            <i class="fa fa-bar-chart-o fa-fw"></i>Quotation
                             <!--<button type="button" class="btn btn-info"  style="margin-top:-7px;float: right;" id="downloadAsExcel" onclick="downloadAsExcel();">Download as Excel</button>
	 <button type="button" class="btn btn-default"  style="margin-top:-7px;float: right;" id="downloadAsExcel" onclick="downloadAsExcel();">&nbsp;Download as PDF&nbsp;</button> -->
	  <img src="../img/pdf_icon.png" onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;"/>

                        <img src="../img/excel_icon.png" onclick="Getexcel();"  style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;"/>
                            <a class="btn btn-primary" style=" margin-top: -7px; float: right;margin-right:100px;" href="<?php print SITE_URL.QUATION; ?>">New Quotation</a>
                        </div>
                        <div class="panel-body">
                            <div>
								
								<div style="width:25%; float:left;">
                                <label>
                                    <span>Financial Year:</span>
                                   <select multiple="multiple" id="ddlfinancialyear" style=" width: 220px;">
									 <?php
										$data = file_get_contents("../../finyear.txt"); //read the file
										$convert = explode("\n", $data); //create array separate by new line
										for ($i=0;$i<count($convert);$i++){
											if($i== 0){
												echo "<option value='".trim($convert[$i])."' selected='selected'>".$convert[$i]."</option>";
											}else{
												echo "<option value='".trim($convert[$i])."'>".$convert[$i]."</option>";
											}
											
											
										}
										?>
      
      
								</select>
								<script src="../js/multiple-select.js"></script>
								<script>
									$('select').multipleSelect();
									
								</script>
                                </label>
                            </div>
								
								
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>From Date:</span>
                                        <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy" class="form-control"></input>
                                    </label>
                                </div>
                                <div style="width:25%; float:left;">
                                    <label>
                                        <span>To Date:</span>
                                        <input type="text" id="txtdateto" placeholder="dd/mm/yyyy" class="form-control"></input>
                                    </label>
                                </div>
								 <div style="width:24%; float:left;">
                                    <label>
                                        <span>Quotation Number:</span>
                                        <input type="text" id="quotno" style="width: 300px;" ng-model="QUATION.quotNo" placeholder="Search By Quotation No" class="form-control ng-pristine ng-valid">
                                    </label>
                                </div>
								    <div class="clr"></div>
                                </div>
								<div>
								<div style="width:25%; float:left;">
									<label>
										<span>Sales Executive:</span>
										<select name="salseuser" id="salseuser" class="form-control">
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
								<div style="width:25%; float:left;">
									<label>
										<span>Quotation Status:</span>
										<select name="quotationStatus" id="quotationStatus"  class="form-control">
											<option value="">Select Quotation Status</option>
											<option value="All">All Received</option>
											<option value="Partially">Partially Received</option>
											<option value="NotReceived">Not Received</option>
										</select>
									</label>
								</div>

                                <div style="width:49%; float:left;">
                                    <label>
                                        <span>Buyer Name:</span><input type="hidden" name="buyerid" id="buyerid" value=""/>
                                        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent; width: 520px;" placeholder="Search By Buyer Name" class="form-control" onKeyPress="loadBuyerByName(this.value);"/>
                                    </label>
                                </div>
                                <div style="width:49%; float:left;">
                                    <label>
                                        <span>Principal:</span><input type="hidden" name="principalid" id="principalid" value=""/>
                                        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent; width: 520px;" placeholder="Type Principal Name" class="form-control"/>
                                    </label>
                                </div>
								
								 <div style="width:40%; float:left;margin-top: 14px"> 
									<label>
                                      <button type="button" class="btn btn-primary" onclick="SearchQuotation();" style="width:100px;margin-left: 130px;">Search</button>
									</label>
                                </div>
                                <div class="clr"></div>
                                </div><br/><br/>
                            <div id="ShowData_Div" style=" width: 100%; overflow: scroll;">
                                <table class="quotation_list" style="display:none; width:100%;"></table>
                            </div> 
                        </div>
                     </div>
                 </div>
               </div>
           </div>
    
       </div>

<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Business_action_js/view_quotation.js'></script>
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
</form>
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

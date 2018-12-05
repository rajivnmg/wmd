<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php"); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="../css/a/styles.css"/>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>

 <!-- Multiple Select -->
  <link href="../css/multiple-select.css" rel="stylesheet" />

<title>Product Ledger Non Excise</title>
</head>

<body>
<form id="form1">
<div id="wrapper">
    <?php include '../home/menu.php'; ?>
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
		   
		    <!-- po details -->
			
			 <div id="invoiceHistory" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:600px;">
					<span id="waitPo" style="margin-left:45%;margin-top:200px;display:none;background: url(../../images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Invoice History : </h4>
					  </div>
					 <div id="invoiceHistories"></div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!-- po details -->
             <div class="col-lg-8" style="width: 100%;">
                 <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                        Product Ledger Non Excise Report
                        <input type="hidden" id="rpttype" value="NONEXCISEProductLedger"></input>			
                        <img src="../img/pdf_icon.png" onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer"  title="Click To Download As PDF"/>

                        <img src="../img/excel_icon.png" onclick="Getexcel();"  style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer"  title="Click To Download As Excel"/>
                    </div>
                    <div class="panel-body">
                        <div>							
							<div style="width:30%; float:left;">
                                <label>
                                    <span>Financial Year:</span>
                                   <select multiple="multiple" id="ddlfinancialyear" style=" width: 300px;">
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
							
							
                            <div style="width:33%; float:left;">
                                <label>
									<span>From Date:</span>
                                    
                                    <input type="text" id="txtdateto" placeholder="dd/mm/yyyy" class="form-control"></input>
                                </label>
                            </div>
                            <div style="width:33%; float:left;">
                                <label>
                                    
                                     <span>To Date:</span>
                                    <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy" class="form-control"></input>
                                </label>
                            </div>
                            <div style="width:38%; float:left;">
                                <label>
                                    <span>Principal:</span><hidden id="principalid"></hidden>
                                    <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; width: 430px; background:transparent;" placeholder="Type Principal Name" class="form-control"/>
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div style="width:38%; float:left;">
                                <label>
                                    <span>Code Part:</span><hidden id="itemid"></hidden>
                                    <input type="text" id="autocomplete-ajax-codepart" style="z-index: 2; width: 400px; background:transparent;" placeholder="Search By Code Part" class="form-control"/>
                                </label>
                            </div>
                             <div style="float:left;padding-top:15px;">
                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" onclick="Search('','');">Get Records</button>
                            </div>
                            
                            <div class="clr"></div>
                         </div>
                        <div id="ShowData_Div" style=" margin-top:30px; margin-left:0px;">
                           <table class="outgoing_invoice_excise_list" style="display: none; width:100%;"></table>
                        </div>
                    </div>
                 </div>
             </div>
           </div>
       </div>
   </div>
<script type='text/javascript' src='../js/Report_js/ProductLedgerExcise.js'></script>
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

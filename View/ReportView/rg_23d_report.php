<?php include('root.php');
include($root_path."GlobalConfig.php");
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php 
	include("../home/head.php");
?>
</head>

<body>
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<link rel="stylesheet" type="text/css" href="../css/a/styles.css"/>
<!-- Multiple Select -->
<link href="../css/multiple-select.css" rel="stylesheet" />
<form id="form1">
<div id="wrapper">
    <?php include '../home/menu.php'; ?>
       <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
           <div class="row">
             <div class="col-lg-8" style="width: 100%;">
                 <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-bar-chart-o fa-fw"></i>R.G. 23-D Report                       
                       <!--<img src="../img/pdf_icon.png" onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer"  title="Click To Download As PDF"/>-->
						<img src="../img/excel_icon.png" onclick="Getexcel();"  style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer"  title="Click To Download As Excel"/>
                    </div>
                    <div class="panel-body">
                        <div>
							
							
							<div style="width:20%; float:left;">
                                <label>
                                    <span>Financial Year:</span>
                                   <select multiple="multiple" id="ddlfinancialyear" style=" width: 150px;">
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
                                </label>
                            </div>
							
							
                            <div style="width:20%; float:left;">
                                <label>
                                    <span>From Date:</span>
                                    <input type="text" id="txtdateto" placeholder="dd/mm/yyyy" class="form-control" style="width:95%;"></input>
                                </label>
                            </div>
                            <div style="width:20%; float:left;">
                                <label>
                                    <span>To Date:</span>
                                    <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy" class="form-control" style="width:95%;"></input>
                                </label>
                            </div>
                            <div style="width:30%; float:left;">
                                <label>
                                    <span>Principal:</span><hidden id="principalid"></hidden>
                                    <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; width: 320px; background:transparent;" placeholder="Search By Principal Name" class="form-control" autocomplete="off">
                                </label>
                            </div>
                            <div style="width:10%;float:right;padding-top:15px;">

                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" onclick="Search();" style="padding-top:5px;">Get Records</button>
                            </div>                          
                          </div>
						   <div style="clear: both;"></div>
						   <div id="ShowData_Div" style="margin-top:20px; margin-left:0px;overflow-x: scroll;">
							   <table class="rd-23d-report" style="display: none; width:100%;"></table>
							</div>
                    </div>
                 </div>
             </div>
           </div>
       </div>
   </div>
</form>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script src="../js/multiple-select.js"></script>
<script>
	$('select').multipleSelect();
</script>
<script type='text/javascript' src='../js/Report_js/rg-23d-report.js'></script>
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
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

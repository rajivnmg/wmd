<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../js/datatable/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/datatable/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../js/datatable/jquery.dataTables.min.js"></script>

<!-- Multiple Select -->
<link href="../css/multiple-select.css" rel="stylesheet" />

<title>Stock Statement Report  With Date and Value</title>
</head>

<body>
<form id="form1">
    <div id="wrapper">
        <?php include '../home/menu.php'; ?>
           <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
               <div class="row">
                 <div class="col-lg-8" style="width: 100%;">
                     <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Stock Statement Report  With Date and Value
                            
                            <img src="../img/pdf_icon.png" onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer"  title="Click To Download As PDF"/>
                            
                            
                            <img src="../img/excel_icon.png" onclick="GetExcel();" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer"  title="Click To Download As Excel"/>
                           
                        </div>
                       
                        <div>
                        <div style="width:24%; float:left;">
                                <label>
                                    <span>Financial Year:</span>
                                   <select multiple="multiple" id="ddlfinancialyear" style=" width: 250px;">
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
                                    <input type="text" id="txtdatetill" placeholder="dd/mm/yyyy" class="form-control" style="width:120%;"></input>
                                </label>
                            </div>
							<input type="hidden" id="txt_type"  class="form-control" style="width:120%;" value="E"></input>
                            
                              <div style="width:14%;float:left;padding-top:15px;">

                                <button type="button" class="btn btn-primary" id="bbb" name="bbb" onclick="Search();" style="padding-top:5px;">Get Records</button>
                            </div>  
                           </div>
						
                           
                        <div class="panel-body">
							<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/multiweld/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
                            <div id="ShowData_Div" style=" margin-top:30px; margin-left:0px;">
                                
                            </div>
                        </div>
                     </div>
                 </div>
               </div>
           </div>
       </div>
 
    <script type='text/javascript' src='../js/Report_js/stockstatement_withdatevalue.js'></script>
    <script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#txtdatetill').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
<script>
    function Getpdf()
    {
		var finyear = $("#ddlfinancialyear").val();
		var tilldate = $("#txtdatetill").val();  
		var st_type = $("#txt_type").val(); 
		if (finyear != "" && tilldate != "") { 
			window.open('ondate_excise_stockstatement_pdf.php?finyear='+finyear+'&tilldate='+tilldate+'&st_type='+st_type, '_blank');
		}else{
		alert('Please Select date');
	}
    }
    function GetExcel()
    {
		var finyear = $("#ddlfinancialyear").val();
		var tilldate = $("#txtdatetill").val();  
		var st_type = $("#txt_type").val(); 
		if (finyear != "" && tilldate != "") { 
			window.open('ondate_excise_stockstatement_excel.php?finyear='+finyear+'&tilldate='+tilldate+'&st_type='+st_type, '_blank');
		}else{
		alert('Please Select date');
	}
    }
</script>
<style>
    tr.group,
tr.group:hover {
    background-color: #ddd !important;
}
</style>
<br/><br/><br/><br/>
</form>
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>
</html>

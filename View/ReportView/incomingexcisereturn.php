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
<title>Incoming Excise Return</title>
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
                        <i class="fa fa-bar-chart-o fa-fw"></i>
                        Incoming Excise Return
                        <input type="hidden" id="rpttype" value="INCOMINGEXCISERETURN"></input>
                        <img src="../img/pdf_icon.png"  onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; float: right;cursor:pointer"  title="Click To Download As PDF"/>

                        <img src="../img/excel_icon.png" onclick="Getexcel();" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; float: right;cursor:pointer"  title="Click To Download As Excel"/>
                    </div>
                    <div class="panel-body">
                        <div>
                        <div style="width:50%; float:left;">
                            <label>
                                <span>From Date:</span>
                                <input type="text" id="txtdateto" placeholder="dd/mm/yyyy" class="form-control"></input>
                            </label>
                        </div>
                        <div style="width:50%; float:left;">
                            <label>
                                <span>To Date:</span>
                                <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy" class="form-control"></input>
                            </label>
                        </div>
                        <div style="width:33%;">
                            <button type="button" class="btn btn-primary" id="bbb" name="bbb" onclick="Search();">Search</button>
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
<script type='text/javascript' src='../js/Report_js/incomingexcisereturn.js'></script>
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

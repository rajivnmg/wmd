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
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link href="../js/datatable/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/datatable/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="../js/datatable/jquery.dataTables.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<title>Sales Tax Return Excise</title>
</head>

<body>
<?php include("../../header.php") ?>
<form id="form1" class="smart-green">
<div align="center">
    <h1>Sales Tax Return Excise Report<span></span></h1>
</div>

<div>
    <input type="hidden" id="rpttype" value="EXCISESalesTaxReturn"></input>
<div style="width:22%; float:left;">
    <label>
       <span>From Date:</span>
        <input type="text" id="txtdateto" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div style="width:22%; float:left;">
    <label>
        
         <span>To Date:</span>
        <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div style="width:22%; float:left;">
    <label>
        <span>Invoice Number:</span>
        <input type="text" id="txtinvoicenumber" placeholder="Invoice Number"></input>
    </label>
</div>
<div style="width:30%; float:left;">
    <label>
        <span>Buyer:</span><hidden id="buyerid"></hidden>
        <input type="text" id="autocomplete-ajax-buyer" style="z-index: 2; background:transparent;" placeholder="Type Buyer Name"/>
    </label>
</div>
<div class="clr"></div>
<div align="center" style="margin-top: 10px;">
    <label>
        <a class="button" style="margin-top:20px;" onclick="Search();">Search</a>
    </label>
</div>
<div class="clr"></div>
</div>
<div id="ShowData_Div" style=" margin-top:30px; margin-left:-30px;">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>S. No.</th>
                <th>Invoice No.</th>
                <th>Invoice Date</th>
                <th>Buyer Name</th>
                <th>VAT/TIN No.</th>
                <th>Taxable Amount</th>
                <th>Tax Amount</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody id="datarow"></tbody>
    </table>
</div> 
<script type='text/javascript' src='../js/Report_js/SalesTaxReturn.js'></script>
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
<?php include("../../footer.php") ?>
</body>
</html>

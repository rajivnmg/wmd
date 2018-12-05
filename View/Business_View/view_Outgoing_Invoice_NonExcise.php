<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php");
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
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<title>Outcoming Invoice Without Excise Duty</title>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>

<body>

<form id="form1" class="smart-green">
<div align="center">
    <h1>Outcoming Invoice Without Excise Duty Search/List<span></span></h1>
</div>
<div style="width:25%; float:left;">
<a class="button" href="<?php print SITE_URL.NEW_OUTGOING_INVOICE_NonEXCISE; ?>">New</a>
</div>
<div class="clr"></div>
<div>
<div style="width:33%; float:left;">
<label>
    <span>Financial Year:</span>
    <select id="ddlfinancialyear" >
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
<div style="width:33%; float:left;">
    <label>
        <span>From Date:</span>
        <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>To Date:</span>
        <input type="text" id="txtdateto" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div class="clr"></div>
<div style="width:33%; float:left;">
    <label>
        <span>Invoice Number:</span>
        <input type="text" id="txtinvoicenumber" placeholder="Invoice Number"></input>
    </label>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>Principal:</span><hidden id="principalid"></hidden>
        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;" placeholder="Type Principal Name"/>
    </label>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>Buyer:&nbsp;&nbsp;</span><hidden id="buyerid"></hidden>
        <input type="text" id="autocomplete-ajax-buyer" style="z-index: 2; background:transparent;" placeholder="Type Buyer Name" onKeyPress="loadBuyerByName(this.value);"/>
    </label>
</div>
<div class="clr"></div>
<div align="center" style="margin-top: 10px;">
    <label>
        <a class="button" style="margin-top:20px;" onclick="SearchOutgoingInvoiceExcise();">Search</a>
    </label>
</div>
<div class="clr"></div>
</div>
<div id="ShowData_Div" style=" margin-top:30px; margin-left:-30px;">
    <table class="outgoing_invoice_nonexcise_list" style="display: none; width:100%;"></table>
</div>
<script type='text/javascript' src='../js/Business_action_js/view_Outgoing_Invoice_NonExcise.js'></script>
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

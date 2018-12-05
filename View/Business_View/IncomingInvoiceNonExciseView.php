<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
    <link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
    <script type='text/javascript' src='../js/jquery.min.js'></script>
    <link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
    <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
    <script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
    <title> Incoming Invoice without Excise Duty</title>
    <style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>
<body>

<form name="f1" method="post" class="smart-green">
<div align="center">
    <h1> Incoming Invoice Without Excise Duty Search/List<span></span></h1>
</div>
<div style="width:25%; float:left; margin-bottom: 10px;">
<a class="button" href="<?php print SITE_URL.NEW_INCOMINGINVOICENONEXCISE; ?>">Create New Invoice</a>
</div>
<div class="clr"></div>
<div>
<div style="width:10%; float:left;">
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
<div style="width:20%; float:left;">
    <label>
        <span>Invoice Number:</span>
        <input type="text" id="txtinvoicenumber" placeholder="Invoice Number"></input>
    </label>
</div>
<div style="width:20%; float:left;">
    <label>
        <span>From Date:</span>
        <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div style="width:20%; float:left;">
    <label>
        <span>To Date:</span>
        <input type="text" id="txtdateto" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
<div style="width:20%; float:left;">
    <label>
        <span>Principal:</span><hidden id="principalid"></hidden>
        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;" placeholder="Type Principal Name"/>
    </label>
</div>

<div class="clr"></div>
<div align="center" style=" margin-top: 10px; ">
    <label>
        <a class="button" style="margin-top:20px;" onclick="SearchIncomingInvoiceNonExcise();">Search</a>
    </label>
</div>
<div class="clr"></div>
</div>
<div style=" margin-top:30px; margin-left:-30px;"><table class="flexme4" style="width:100%;"></table></div>
<script type='text/javascript' src='../js/Business_action_js/IncomingInvoiceNonExciseView.js'></script>
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

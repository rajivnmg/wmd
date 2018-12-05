<?php 
include('root.php');
include($root_path."GlobalConfig.php");

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Business_action_js/StockTransferView.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<title>Stock Transfer Search/List</title>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>

</head>
<body>
<?php include("../../header.php") ?>
<form name="f1" method="post" class="smart-green">
<div align="center"><h1>Stock Transfer Search/List</h1></div>
<div style="width:25%; float:left;">
<a class="button" href="<?php print SITE_URL.NEW_STOCK_TRANSFER; ?>">New</a>
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
           <span>Stocktransfer Number:</span>
           <input type="text" id="txtinvoicenumber" placeholder="Stocktransfer Number"></input>
       </label>
   </div>
   <div style="width:32%; float:left;">
       <label>
           <span>Principal:</span><hidden id="principalid"></hidden>
        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;" placeholder="Type Principal Name"/>
       </label>
   </div>
  
   <div style="width:12%; float:left;">
       <label>
           <input type="button" class="button" name="B1" value="Search" style="margin-top:20px;" onclick="Search();">
       </label>
   </div>
   <div class="clr"></div>
   </div>
   <div id="ShowData_Div" style=" margin-top:30px; margin-left:-30px;">
       <table class="outgoing_invoice_excise_list" style="display: none; width:100%;"></table>
   </div>
<!--<div style=" margin-top:30px; margin-left:-50px;"><table class="flexme4" style="width:100%;"></table></div>-->
<script type='text/javascript' src='../js/Business_action_js/StockTransferView.js'></script>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#txtdatefrom').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#txtdateto').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>

</form><br/><br/><br/><br/>
<?php include("../../footer.php") ?>
</body>
</html>

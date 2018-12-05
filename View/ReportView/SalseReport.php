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
<link rel="stylesheet" type="text/css" href="../js/css/flexigrid.css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>

<link href="../css/pop.css" media="all" rel="stylesheet" type="text/css"/>
<script language="javascript" src="../js/jquery.pop.js" type="text/javascript"></script>
<title>Salse Report</title>
</head>

<body>
<?php include("../../header.php") ?>
<form id="form1" class="smart-green">
<div align="center">
    <h1>Salse Report Details<span></span></h1>
</div>
<div class="clr"></div>
<div>
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
<div style="width:33%; float:left;">
    <label>
        <span>Invoice Type:</span><hidden id="principalid"></hidden>
        <select id="invoicetypelist" onchange="ChangeInvoiceType();">
            <option value="0">Select Invoice Type</option>
            <option value="1">Incoming Excise</option>
            <option value="2">Incoming Non-Excise</option>
            <option value="3">Outgoing Excise</option>
            <option value="4">Outgoing Non-Excise</option>
            <option value="5">Balance Sheet Excise</option>
            <option value="6">Balance Sheet Non-Excise</option>
        </select>
    </label>
</div>
<div class="clr"></div>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>Principal:</span><hidden id="principalid"></hidden>
        <input type="text" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;" placeholder="Search By Principal Name"/>
    </label>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>Code Part:</span><hidden id="codepart"></hidden>
        <input type="text" id="autocomplete-ajax-codepart" style="z-index: 2; background:transparent;" placeholder="Search By Principal Name"/>
    </label>
</div>
<div style="width:33%; float:left;display: none;" id="buyerlist">
    <label>
        <span>Buyer Name:</span><hidden id="buyerid"></hidden>
        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" placeholder="Search By Buyer Name"/>
    </label>
</div>
<div style="width:33%; float:left;display: none;" id="supplierlist">
    <label>
        <span>Supplier:</span>
        <input type="text" id="autocomplete-ajax-supplier" style=" z-index: 2; background: transparent;" placeholder="Search By Supplier Name" /><hidden id="supplierid"></hidden>
    </label>
</div>
<div class="clr"></div>
<div align="center" style="width:100%; margin-top:20px;">
    <label>
        <a class="button" style="margin-top:20px;" onclick="SearchSalseReport();">Search</a>
    </label>
</div>
<div class="clr"></div>
<div id="ShowData_Div" style=" margin-top:30px; margin-left:-30px;">
    <table class="salsereport" style="display: none; width:100%;"></table>
    <table id="salsetable" style="width:100%;">
      <thead>
        <tr>
           <td>Sr.</td>
           <td>Date</td>
           <td>Principal</td>
           <td>Buyer</td>
           <td>Total Cost</td>
           <td>Type</td>
           <td>Details</td>
           <td>Show</td>
        </tr>
      </thead>
      <!-- <tfoot>
          <tr>
              <td>Sum</td>
              <td>$180</td>
          </tr>
      </tfoot> -->
      <tbody id="rowdata">
      </tbody>
    </table>
    
</div> 
<script type='text/javascript' src='../js/Report_js/salsereport.js'></script>
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

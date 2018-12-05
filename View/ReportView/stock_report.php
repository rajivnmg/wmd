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
<title>Stock Report</title>
</head>

<body>
<?php include("../../header.php") ?>
<form id="form1" class="smart-green">
<div align="center">
    <h1>Stock Report<span></span></h1>
</div>
<div>
<div style="width:33%; float:left;">
    <label>
        <span>Select Date:</span>
        <input type="text" id="txtdate" placeholder="YYYY-MM-DD"></input>
    </label>
</div>
<div style="width:33%; float:left;">
    <label>
        <span>Select Report Type:</span>
        <select id="reporttype" onchange="getreportpath();">
            <option value="0">Select Report Type</option>
            <option value="1">Excise Stock Statement With Value</option>
            <option value="2">Non-Excise Stock Statement With Value</option>
            <option value="3">Excise Stock</option>
            <option value="4">Non-Excise Stock</option>
        </select>
    </label>
</div>
<div class="clr"></div>
</div>
    <script>
        function getreportpath()
        {
            var r_type = $("#reporttype").val();
            var r_Date = $("#txtdate").val();
            if(r_Date != "")
            {
                if(r_type == 0)
                {
                    alert("Please select any report type for seeing report.");
                }
                else if(r_type == 1)
                {
                     window.open('excise_stockstatement_pdf.php?date='+r_Date, '_blank')
                }
                else if(r_type == 2)
                {
                     window.open('nonexcise_stockstatement_pdf.php?date='+r_Date, '_blank')
                }
                else if(r_type == 3)
                {
                     window.open('excise_stock_pdf.php?date='+r_Date, '_blank')
                }
                else if(r_type == 4)
                {
                     window.open('non_excise_stock_pdf.php?date='+r_Date, '_blank')
                }
            }
        }
    </script>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#txtdate').datetimepicker({
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


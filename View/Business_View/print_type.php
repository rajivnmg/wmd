<?php 
include('root.php');
include $root_path.'GlobalConfig.php';
include( "../../Model/ReportModel/Report1Model.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<title>Print Type</title>
</head>

<body>
<?php include("../../header.php") ?>

<?php 
$actionUrl ='';
if($_REQUEST['TYP'] == "EXCISE"){
	$actionUrl ="../../pdf/print_excise_invoice.php";
}else if($_REQUEST['TYP']=="NonEXCISE"){
	$actionUrl ="../../pdf/print_nonexcise_invoice.php";
}else{
	echo 'URL not Exit'; exit;
}

echo '<form id="form1" class="smart-green" action="'.$actionUrl.'">';

 ?>
<div align="center">
    <h1>Please Select Print Type for Current Invoice</h1>


</div><hr/>
	
 <div align="center"> 
        <select name="printtype" id="printtype" style="width:300px;">
            <option value="">Select Print Type</option>
            <!--<option value="1">ORIGINAL FOR BUYER</option>-->
			<option value="1">ORIGINAL FOR  RECIPIENT</option>
            <option value="2">DUPLICATE FOR TRANSPORTER</option>
            <option value="3">COPY</option>
            <option value="4">QUADUPLICATE FOR ASSESSE</option>
            <option value="5">TRIPLICATE FOR CENTRAL EXCISE</option>
        </select>
		<br/><br/><br/>
		
		<input type="hidden" name="invoiceId" id="invoiceId"  value="<?php echo $_REQUEST['invoiceID']; ?>"/>
		<input class="button" type="submit" id="printInvoice" value="Print" tabindex="2">
 </div>

</form>
<?php include("../../footer.php") ?>
</body>
</html>

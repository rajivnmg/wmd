<?php 
include('root.php');
include $root_path.'GlobalConfig.php';
include( "../../Model/ReportModel/Report1Model.php");
//print_r($perm);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<style>
table {
	font: 11px/24px Verdana, Arial, Helvetica, sans-serif;
	border-collapse: collapse;
	width: 80%;
	}

th {
	padding: 0 0.5em;
	text-align: left;
	}

tr.yellow td {
	border-top: 1px solid #FB7A31;
	border-bottom: 1px solid #FB7A31;
	background: #FFC;
	}

td {
	border-bottom: 1px solid #CCC;
	padding: 0 0.5em;
	}

td.width {
	width: 190px;
	}

td.adjacent {
	border-left: 1px solid #CCC;
	text-align: center;
	}
</style>
<title>Purchase Order</title>
</head>

<body>
<?php include("../../header.php") ?>
<form id="form1" class="smart-green">
<div align="center">
    <h1>Purchase Order List For Approval</h1>
</div>

<div style="margin-top:20px; margin-left:20px;">
<table>
<tr>

<td>Order Number</td>
<td>Order Date</td>
<td>Validity Date</td>
<td>Buyer Name</td>
<td>Location</td>
<td>Type</td>
<td>PO Value</td>

</tr>
<?php
include( "../../Model/ReportModel/Report1Model.php");
include("../../Model/DBModel/DbModel.php");

if($TYPE =="S"){
$result =  Report1Model::GetPOListOfPandingManagmentApproval_SuperAdmin();
}
else if($TYPE =="M"){
$result =  Report1Model::GetPOListOfPandingManagmentApproval_Managment();
}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
      $bpoId=$row['bpoId'];
echo "<tr>";
    
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpono']."</td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpoDate']."</a></td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['bpoVDate']."</a></td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['BuyerName']."</a></td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['LocationName']."</a></td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['Type']."</a></td>";
    echo "<td><a href='management_approval_form.php?fromPage=AR&POID=".$bpoId."'>".$row['po_val']."</a></td>";
    //echo "<td><a href='management_approval_form.php?POID=".$bpoId."'>Go To</a></td>";
echo "</tr>";
//echo "<option value='".$row['bpoId']."'>".$row['bpono']."</option>";
}?>

</table>

</div>
<br/><br/><br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

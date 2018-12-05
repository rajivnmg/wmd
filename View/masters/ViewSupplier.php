<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<title>Supplier Master</title>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>
<body> 
<form name="f1" method="post" class="smart-green">
<div align="center"><h1>Supplier Master Search/List</h1></div>
<div style="width:25%; float:left;">
<a class="button" href="<?php print SITE_URL.SUPPLIERMASTER; ?>">New</a>
</div>
<div class="clr"></div>
<div style=" margin-top:30px; margin-left:-50px;"><table class="SupplierList" style="width:100%;"></table></div>
<script type='text/javascript' src='../js/Masters_js/view_supplier.js'></script>
<br/><br/><br/><br/><br/>
</form>  
</body>
</html>
     

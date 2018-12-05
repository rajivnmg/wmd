<?php
include('root.php');
include($root_path."GlobalConfig.php");

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
<title>Payment Received Search List</title>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
	.ms-drop.bottom ul > li label { overflow: hidden; }
	.ms-drop.bottom input[type="checkbox"] { margin: 3px 3px 0 0; float: left; }
	.ms-drop.bottom span { margin: 0; }
</style>


 <!-- Multiple Select -->
  <link href="../css/multiple-select.css" rel="stylesheet" />


</head>

<body>
<?php include("../../header.php") ?>
 <!-- Bootstrap Core JavaScript -->
    <script src="../js/boot_js/bootstrap.min.js"></script>
<form id="form1" class="smart-green">
<div align="center">
    <h1>Payment Received Against Outcoming Invoice Search/List<span></span></h1>
</div>
<div style="width:25%; float:left;">
<a class="button" href="<?php print SITE_URL.PAYMENT; ?>">Make Payment</a><br/><br/>
</div>

<!-- po details -->
			
			 <div id="paymenthistorybox" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:900px;">
					<span id="waitPo" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span>
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Payment Details : <span id="paumentnumn" style="font-size:16px;"></span><br/>BuyerName : <span id="bname" style="font-size:16px;"></span></h4>
					  </div>
					  <div class="modal-body" id="payment_details">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div>
			<!-- po details -->




<div class="panel-heading">
            <img src="../img/pdf_icon.png" onclick="Getpdf();" style=" width: 30px; height: 30px; margin-top: -7px; margin-right:20px; float: right;cursor:pointer" title="Click To Download As PDF">
			<img src="../img/excel_icon.png" onclick="Getexcel();" style=" width: 30px; height: 32px; margin-top: -7px; margin-right: 5px; margin-right:20px; float: right;cursor:pointer" title="Click To Download As Excel">
</div>

<div class="clr"></div>
<div>
	
					<div style="width:24%; float:left;">
                                <label>
                                    <span>Financial Year:</span>
                                   <select multiple="multiple" id="ddlfinancialyear" style=" width: 280px;">
									 <?php
										$data = file_get_contents("../../finyear.txt"); //read the file
										$convert = explode("\n", $data); //create array separate by new line
										for ($i=0;$i<count($convert);$i++){
											if($i== 0){
												echo "<option value='".trim($convert[$i])."' selected='selected'>".$convert[$i]."</option>";
											}else{
												echo "<option value='".trim($convert[$i])."'>".$convert[$i]."</option>";
											}								
										}
										?>
								</select>
								<script src="../js/multiple-select.js"></script>
								<script>
									$('select').multipleSelect();
									
								</script>
                                </label>
                            </div>
	
	
   <div style="width:25%; float:left;">
    <label>
        <span>From Payment Date:</span>
        <input type="text" id="txtdatefrom" placeholder="dd/mm/yyyy"></input>
    </label>
</div>
        <div style="width:25%; float:left;">
    <label>
        <span>To Payment Date:</span>
			<input type="text" id="txtdateto" placeholder="dd/mm/yyyy"></input>
		</label>
	</div>
	 <div style="width:25%; float:left;">
    <label>
        <span>Payment Reference Number:</span>
        <input type="text" id="txttrxnNo" placeholder="Type Payment Reference Number"></input>
    </label>
    </div>
<div class="clr"></div>
<div style="width:50%; float:left;">
 <label>
    <span>Buyer Name*:</span>
   
    <input  type="text" id="buyerid" ng-model="payment.bn" style="display: none;" />
    <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Buyer Name" ng-model="payment.bn_name" onKeyPress="loadBuyerByName(this.value);" required/>
  </label>
</div>
<div style="width:30%; float:left;">
    <label>
        <span>Purchase Order Number:</span>
        <input type="text" id="txtpono" placeholder="Type Purchase Order Number"></input>
    </label>
 </div>
<div style="width:19%; float:left;margin-top: 13px;"><br>
    <label>
        <a class="button" onclick="Search();">Search</a>
    </label>
</div>
</div>
<div class="clr"></div>

<div class="clr"></div>

<div id="ShowData_Div" style="width:100%; margin-top:30px; margin-left:-30px;">
    <table class="received_payment_list" style="display: none; width:100%;"></table>
</div>
<script type='text/javascript' src='../js/Business_action_js/payment_received_list.js'></script>
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

<?php
include('root.php');
include($root_path."GlobalConfig.php");
?>
<?php include("../../header.php") ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Challan Viow</title>
<!-- Bootstrap Core CSS -->
<link href="../css/boot_css/bootstrap.min.css" rel="stylesheet">   	
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
 <script src="../js/boot_js/bootstrap.min.js"></script>
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
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
<body ng-app="challan_app"> 
<form name="f1" method="post" class="smart-green">
<div align="center"><h1>Challan Search/List</h1></div>
<div style="width:25%; float:left;">
<a class="button" href="<?php print SITE_URL.NEW_CHALLAN; ?>">New</a>
</div>
<div class="clr"></div>

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

            <div style="width:75%; float:left;">
    <label>
        <span>From Challan Date:</span>
        <input type="text" id="txtdatefrom" placeholder="yyyy/mm/dd"></input>
    </label>
</div>

</div>
<div style="width:25%; float:left;">
            <div style="width:75%; float:left;">
    <label>
        <span>To Challan Date:</span>
        <input type="text" id="txtdateto" placeholder="yyyy/mm/dd"></input>
    </label>
</div><div class="clr"></div>
</div>
<div style="width:25%; float:left;">
    <div style="width:95%; float:left;">
    <label>
        <span>Challan Status:</span>       
            <select name="challan_status" id="challan_status" ng-model="Challan.challan_status">
				<option value="">Select Status</option>
                <option value="1">Open</option>
				<option value="6">Free Sample</option>
				<option value="7">Close against Loan settlement</option>
                <option value="2">Close Without Outgoing Invoice</option>
                <option value="3">Close With Outgoing Excise</option>
                <option value="4">Close With Outgoing Non-Excise</option>
                <option value="5">Close With Outgoing Excise & Non-Excise</option>               
             </select>      
    </label>
    </div><div class="clr"></div>
</div>
<div class="clr"></div>
<div style="width:33.33%; float:left;">
       <div style="width:75%; float:left;">
    <label>
        <span>Challan Number:</span>
        <input type="text" id="txtchallanNo" placeholder="Type Challan Number"></input>
    </label>
    </div><div class="clr"></div>
</div>
<div style="width:66.33%; float:left;">
  <div style="width:84%; float:left;">
 <label>
    <span>Buyer Name:</span>
   
    <input  type="text" id="buyerid"  style="display: none;" />
    <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Buyer Name" onKeyPress="loadBuyerByName(this.value);"/>
  </label>
   </div><div class="clr"></div>
</div>

<div style="width:25%; float:left;">
   <div style="width:95%; float:left;">
 <label>
     <span>Purpose:</span>
            <select name="challan_purpose" id="challan_purpose" ng-model="Challan.challan_purpose">
                <option value="">Select Purpose</option>
                <option value="1">Free Sample</option>
                <option value="2">Returnable Sample</option>
                <option value="3">Loan Basis</option>
                <option value="4">Replacement</option>
                <option value="5">To be Billed</option>
             </select>
  </label>
   </div><div class="clr"></div>
</div>
<div style="width:25%; float:left;">
	<label>
		<span>Sales Executive*:</span>
		<select name="salseuser" id="salseuser" class="form-control">
		<option value="">Select Sales Executive</option>
		<?php //include("../../Model/DBModel/DbModel.php"); 
		//include( "../../Model/Masters/User_Model.php");  
		$result =  UserModel::getUserByType('E');
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			 echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
		}
		?>    
		</select>
	</label>
</div>
 <div style="width:30%; float:left;">	
        <label>
            <span>Customer Contact Person Name:</span>
            <input type="text" name="challan_contactName" id="challan_contactName" placeholder="Customer Contact Person Name" ng-model="Challan._cust_contact_name" class="ng-pristine ng-invalid ng-invalid-required">
        </label>  
</div>

<div style="width:18%;float:left;padding-top:15px;"></br>
    <label>
        <a class="button" style="margin-top:10px;" onclick="Search();">Search</a>
    </label>
</div>
<div class="clr"></div>

<div class="clr"></div>



<!-- po details -->
			
			 <div id="challanDetail" class="modal fade pull-left" role="dialog" data-toggle="modal">
				  <div class="modal-dialog" style="width:800px;">
					<span id="wait" style="margin-left:45%;margin-top:100px;display:none;background: url(/gurgaon/images/loading.gif) no-repeat;height: 80px;width:75px;position: absolute;z-index:9999;background-size: contain;"></span> 
					<!-- Modal content-->
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Challan Detail: </h4>
					  </div>
					  <div class="modal-body" id="chlnDtl">
					
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  </div>
					</div>

				  </div>
				</div> 
			<!-- po details -->
			
		

<div class="clr"></div>
<div id="ShowData_Div" style="width:100%; margin-top:30px; margin-left:-60px; margin-right:0pxl">
    <table class="flexme4" style="display: none; width:100%;"></table>
</div>

</div>
<script type='text/javascript' src='../js/Business_action_js/ChallanView.js'></script>
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
</form> 
<?php include($root_path."footer.php") ?>   
</body>
</html>
     

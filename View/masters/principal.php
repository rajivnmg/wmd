<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");
?>
<html>
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Masters_js/principal.js'></script>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
</head>
<body ng-app = "principal_app">
<?php include("../../header.php") ?>
<form name="form1" id="form1" ng-controller="principal_Controller"  ng-submit="submitForm();" class="smart-green" data-ng-init="init('<?php echo  $_GET['ID'];?>')">

<div align="center"><h1>Principal Master Form</h1></div>
<div>
<div id="Form_Div" style=" width:90%;margin-left:30px;">
<div style="width:20%; float:left;"><label><span>Principal Name:</span></label><hidden id="principal_id" ng-model="principal._principal_supplier_id"></hidden></div>
<div style="width:29%; float:left;"><input type="text"  name="mnm" id="princ_name" value="" placeholder="Principal Name" tabindex="1" ng-model="principal._principal_supplier_name" required/> </div>
<div style="width:20%; float:left;"><label><span>State</span></label></div>
<div style="width:29%; float:left;">
<select name='state' id='state' title='Select State' tabindex="4" onChange='showCity(this.value,0);' ng-model="principal._state_id" required>
<option value="">Select State</option>
<?php
    include( "../../Model/Masters/StateMaster_Model.php");
   // include("../../Model/DBModel/DbModel.php");
    $result =  StateMasterModel::GetStateList();
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){ 
        echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
    }
?> </select></div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span> Address Line 1</span></label></div>
<div style="width:29%; float:left;"> <input type="text"  name="address1" id="princ_add1" value="" placeholder="Address Line 1" tabindex="2"  ng-model="principal._add1" required/></div>
<div style="width:20%; float:left;"><label><span>City</span></label> </div>
<div style="width:29%; float:left;"><select name="city" id="city" tabindex="5" ng-model="principal._city_id" required><option value="">Select City</option></select> </div>
<div class="clr"></div>
    
<div style="width:20%; float:left;"><label><span> Address Line 2</span></label></div>
<div style="width:29%; float:left;"> <input type="text"  name="address2" id="princ_add2" value="" placeholder="Address Line 2" tabindex="3"  ng-model="principal._add2" required/></div>
<div style="width:20%; float:left;"><label><span>Pincode</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="pincode" id="princ_pin" placeholder="Pincode" tabindex="6"  ng-model="principal._pincode" valid-number/> </div>
<div class="clr"></div>
    
    
<div><h2>Excise Details</h2></div>

<div style="width:20%; float:left;"><label><span>ECC Code No.</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="ECCCodeNo" id="princ_ecc" value="" placeholder="ECC Code No" tabindex="7" ng-model="principal._ecc_codeno" required/> </div>
<div style="width:20%; float:left;"> <label><span>Range</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="CERegnNO" id="princ_range" value="" placeholder="Range" tabindex="10" ng-model="principal._pc_range" required/> </div>
<div class="clr"></div>

<div style="width:20%; float:left;"><label><span>TIN No.</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="SALESTAXNO" id="princ_tin" value="" placeholder="TIN No" tabindex="8" ng-model="principal._tin_no" required/> </div>
<div style="width:20%; float:left;"><label><span> Division</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="RngDivCollecterate" id="princ_division" value="" placeholder="Division" tabindex="11" ng-model="principal._pc_division" required/> </div>
<div class="clr"></div>

<div style="width:20%; float:left;"><label><span>PAN No. </span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="add2" id="princ_pan" value="" placeholder="PAN No" tabindex="9"  ng-model="principal._pan_no" required/> </div>
<div style="width:20%; float:left;"><label><span>Commission Rate</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="add" id="princ_commission" value="" placeholder="Commission Rate" tabindex="12" ng-model="principal._commission_rate" required/> </div>
<div class="clr"></div>
<div style="width:20%; float:left;">
	<label><span>Tax Type:</span></label>
</div>
<div style="width:29%; float:left;"> 
	<select name="select" id="txntype"  ng-model="principal._tax_type"  tabindex="23" required>
		<option value="">Select Tax Type</option>
		<option value="S">CGST-SGST</option>
		<option value="I">IGST</option>
	</select>
</div>
<div class="clr"></div>
       
<div><h2>Bank Detail</h2></div>
<div style="width:20%; float:left;"><label><span> Bank Name</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc" id="princ_bankname" value="" placeholder="Bank Name" tabindex="13" ng-model="principal._bankname" required/> </div>
<div style="width:20%; float:left;"> <label><span>RTGS Code</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc3" id="princ_rtgs" value="" placeholder="RTGS Code" tabindex="17"  ng-model="principal._rtgs" required/> </div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span>Bank Account No</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="gc2" id="princ_accountnumber" placeholder="Bank Account No" tabindex="14" ng-model="principal._accountnumber" valid-number/> </div>
<div style="width:20%; float:left;"><label><span>NEFT Code </span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc4" id="princ_neft" value="" placeholder="NEFT Code" tabindex="18"  ng-model="principal._neft" required/> </div>
<div class="clr"></div>
    
<div style="width:20%; float:left;"><label><span>Account Type</span></label></div>
<div style="width:29%; float:left;"><select name="title" ng-model="principal._accounttype" tabindex="15" required id="princ_account"><option value="">Select Account Type</option><option value="S">Saving</option><option value="C">Current</option></select><!-- <input type="text"  name="gc5" id="princ_account" value="" placeholder="Account Type" tabindex="15" class="required mytext"/> --> </div>
<div class="clr"></div>
<div style="width:20%; float:left;"> <label><span>Bank Address</span></label></div>
<div style="width:29%; float:left;"><textarea name="bank_add" id="bank_add" placeholder="Bank Address" tabindex="16"  ng-model="principal._bankaddress" required></textarea> </div>
<div class="clr"></div>
</div>
<!-- <form name="form2"  id="form2"> -->
<div align="left" id="ContactInfo_Div" style=" width:90%;margin-left:30px;">
<div><h2>Principal Contact Person Details</h2></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
         <table width="100%" border="1">
            <tr>
              <td width="10%"><label><span>Title</span></label></td>
              <td width="15%"><label><span>First Name</span></label></td>
              <td width="15%"><label><span>Last Name</span></label></td>
              <td width="20%"><label><span>Deptt</span></label></td>
              <td width="20%"><label><span>Phone No</span></label></td>
              <td width="20%"><label><span>Email</span></label></td>
            </tr>
            <tr><hidden id="buyer_id_for_employee"></hidden>
              <td><select name="title" id="emp_title" ng-model="principal._title" tabindex="19"><option value="">Select Title</option>
              <?php
              include( "../../Controller/Param/Param.php");
              $result =  Param::GetTitleList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['titleid']."'>".$row['titlename']."</option>";
              }?>
              <!-- <option value="1">Mr.</option><option value="2">Miss.</option> --></select></td>
              <td><input type="text"  name="gc5" id="emp_fname" value="" ng-model="principal._first_name" tabindex="20"/></td>
              <td><input type="text"  name="gc5" id="emp_lname" value="" ng-model="principal._last_name" tabindex="21"/> </td>
              <td><select name="dept" id="emp_dept" ng-model="principal._dept_id" tabindex="22"><option value="">Select Department</option>
              
              <?php
              $result =  Param::GetDepartmentList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['departmentid']."'>".$row['departmentname']."</option>";
              }?>
              <!-- <option value="1">Account</option><option value="2">Marketing</option><option value="3">HR</option> -->
              </select></td>
              <td><input type="text"  name="gc5" id="emp_phon" value="" ng-model="principal._phone1" tabindex="23"/></td>
              <td><input type="email"  name="gc5" id="emp_email" value="" ng-model="principal._email1" tabindex="24"/></td>
            </tr>
            <tr  ng-repeat="item in principal._EmployeeList">
              <td><input type="text" ng-model="item.titlename"/></td>
              <td><input type="text" ng-model="item._first_name"/></td>
              <td><input type="text" ng-model="item._last_name"/></td>
              <td><input type="text" ng-model="item.deptname"/></td>
              <td><input type="text" ng-model="item._phone"/></td>
              <td><input type="email" ng-model="item._email"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="principal.deleteemp" ng-click="RemoveEmployee(item)" class="button"/></td>
            </tr>
          </table>
    </div>
    <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="empadd" id="empadd" value="Add" ng-model="buyer.AddEmp"  tabindex="25" ng-click="AddEmployee()" />
           <!--  <input type="button" class="button" name="addcon" id="empadd" value="Add" onclick="AddEmployee();" /> -->
            <!-- <input type="button" class="button" name="addcon1" id="empadd" value="Update" onclick="UpdateEmployee();" />
            <input type="submit" class="button" name="addcon2" id="empcancel" value="Cancel" onclick="CancelEmployee();" /> -->
        </label>
    </div><div class="clr"></div>
<!-- <div><h2>Contact Person Information</h2></div><hidden id="principal_id_for_employee"></hidden>
    <div style="width:20%; float:left;"><label><span>Title</span></label></div>
    <div style="width:29%; float:left;">
    <select name="title" id="emp_title"><option value="0">Select Title</option><option value="1">Mr.</option><option value="2">Miss.</option></select></div>
    <div class="clr"></div>
    <div style="width:20%; float:left;"><label><span>First Name</span></label></div>
    <div style="width:29%; float:left;"><input type="text"  name="gc5" id="emp_fname" value="" /> </div>
    <div class="clr"></div>
    <div style="width:20%; float:left;"><label><span>Last Name</span></label></div>
    <div style="width:29%; float:left;"><input type="text"  name="gc5" id="emp_lname" value="" /> </div>
    <div class="clr"></div>
    <div style="width:20%; float:left;"><label><span>Deptt.</span></label></div>
    <div style="width:29%; float:left;"><select name="dept" id="emp_dept"><option value="0">Select Department</option><option value="1">Account</option><option value="2">Marketing</option><option value="3">HR</option></select></div>
    <div class="clr"></div>
    <div style="width:20%; float:left;"><label><span>Phone No</span></label></div>
    <div style="width:29%; float:left;"><input type="text"  name="gc5" id="emp_phon" value="" /> </div>
    <div class="clr"></div>
    <div style="width:20%; float:left;"><label><span>Email</span></label></div>
    <div style="width:29%; float:left;"><input type="text"  name="gc5" id="emp_email" value="" /> </div>
    <div class="clr"></div>
    <div align="center">
            <input type="button" class="button" name="addcon" id="empadd" value="Add" onclick="AddEmployee();" />
            <input type="button" class="button" name="addcon" id="empadd" value="Update" onclick="UpdateEmployee();" />
            <input type="submit" class="button" name="addcon2" id="empcancel" value="Cancel" onclick="CancelEmployee();" />
    </div> -->
</div>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 09-06-2017------------------------------->
<div align="left" id="GstInfo_Div" style=" width:90%;margin-left:30px;">
<div><h2>Principal GST Details</h2></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
         <table width="100%" border="1">
            <tr>
			  <td width="15%"><label><span>State</span></label></td>
              <td width="15%"><label><span>GST Registration Status</span></label></td>
              <td width="15%"><label><span>GST Migration Status</span></label></td>
              <td width="15%"><label><span>GSTIN/UIN</span></label></td>
              <td width="15%"><label><span>GST registration date</span></label></td>
              <td width="15%"><label><span>Arn No</span></label></td>
              <td width="10%"><label><span>Permanent GST</span></label></td>
            </tr>
            <tr><hidden id="principal_id_for_gst"></hidden>
			  <td>
			  <select name="gst_state_id" id="gst_state_id" ng-model="principal._gst_state_id" tabindex="19">
			  <option value="">Select State</option>
              <?php
              include_once( "../../Model/Masters/StateMaster_Model.php");
              $result =  StateMasterModel::GetStateList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['StateId']."'>".$row['StateName']."</option>";
              }?>
              </select>
			  </td>
              <td>
			  <select name="gst_reg_status" id="gst_reg_status" ng-model="principal._gst_reg_status" tabindex="19">
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
			  <td>
			  <select name="gst_mig_status" id="gst_mig_status" ng-model="principal._gst_mig_status" tabindex="19">
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
              <td><input type="text"  name="gst_no" id="gst_no" value="" ng-model="principal._gst_no" tabindex="20"/></td>
              <td><input type="text"  name="gst_reg_date" id="gst_reg_date" value="" ng-model="principal._gst_reg_date" placeholder="yyyy-mm-dd" tabindex="21"/> </td>
              <td><input type="text"  name="arn_no" id="arn_no" value="" ng-model="principal._arn_no" tabindex="23"/></td>
              <td><input type="text"  name="perm_gst" id="perm_gst" value="" ng-model="principal._perm_gst" tabindex="24"/></td>
            </tr>
            <tr  ng-repeat="item in principal._GSTList">
			  <td><input type="text" ng-model="item._gst_state_name"/></td>
              <td><input type="text" ng-model="item._gst_reg_status_name"/></td>
              <td><input type="text" ng-model="item._gst_mig_status_name"/></td>
              <td><input type="text" ng-model="item._gst_no"/></td>
			  <td><input type="text" ng-model="item._gst_reg_date"/></td>
              <td><input type="text" ng-model="item._arn_no"/></td>
              <td><input type="text" ng-model="item._perm_gst"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="principal.deletegst" ng-click="RemoveGST(item)" class="button"/></td>
            </tr>
          </table>
    </div>
 <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="gstadd" id="gstadd" value="Add" ng-model="buyer.AddGST"  tabindex="25" ng-click="AddGST()" />
        </label>
    </div><div class="clr"></div>
</div>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 09-06-2017------------------------------->
<div align="center">
<input type="submit" class="button" name="qdd" id="btnaddprincipal" value="Save" tabindex="26"/>
<span><a  style="text-decoration: none;"  tabindex="27" class="button" href="<?php print SITE_URL.VIEWPRINCIPALMASTER; ?>">Cancle</a></span>
    <!-- <input type="button" class="button" name="qdd" id="btnaddprincipal" value="Save" tabindex="19" onclick="AddPrincipalMaster();"/> -->
    <input type="button" class="button" name="qdd2" id="btnupdateprincipal" value="Update" tabindex="26" ng-click="Update();"/>
    <!-- <input type="submit" class="button" name="B1" value="Cancel" id="btncancle" onclick="Cancle();" tabindex="20"/> -->
</div><br/><br/><br/><br/>
<!-- </form> -->     
</div>    

</form>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<script>
$('#gst_reg_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<?php include("../../footer.php") ?>
</body>
</html>
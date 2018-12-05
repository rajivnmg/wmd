<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../../header.php") 
 ?>
<html>
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<!-----------------------------BOF to add GST details for Supplier by Ayush Giri on 14-06-2017------------------------------->
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<!-----------------------------EOF to add GST details for Supplier by Ayush Giri on 14-06-2017------------------------------->
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Masters_js/supplier.js'></script>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
</head>
<body ng-app = "supplier_app">


<form name="form1" id="form1" ng-controller="supplier_Controller" ng-submit="submitForm();"   class="smart-green" data-ng-init="init('<?php echo  $_GET['ID'];?>')">

<div align="center"><h1>Supplier Master Form</h1></div>
<div align="center">
<div align="left" id="Form_Div" style=" width:90%;margin-left:30px;">
<div style="width:20%; float:left;"><label><span>Supplier Name:</span></label><hidden id="supplier_id" ng-model="supplier._principal_supplier_id"></hidden></div>
<div style="width:29%; float:left;"><input type="text" name="mnm" id="supp_name" value="" placeholder="Supplier Name" tabindex="1"  required ng-model="supplier._principal_supplier_name"/> </div>
<div style="width:20%; float:left;"> <label><span>State</span></label></div>
<div style="width:29%; float:left;">
<select name='state' id='state'  tabindex='4' ng-model='supplier._state_id' onChange='showCity(this.value,0);' required> 
    <option value=""> Select State</option>
<?php
    include( "../../Model/Masters/StateMaster_Model.php");
   // include("../../Model/DBModel/DbModel.php");
    $result =  StateMasterModel::GetStateList();
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
        echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
    }
?></select> </div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span> Address Line 1</span></label></div>
<div style="width:29%; float:left;"> <input type="text"  name="address1" id="supp_add1" value="" placeholder="Address Line 1" tabindex="2"   ng-model="supplier._add1" required/></div>
<div style="width:20%; float:left;"><label><span>City</span></label></div>
<div style="width:29%; float:left;"><select name="city" id="city" tabindex="5"  ng-model="supplier._city_id" required ><option value="">Select City</option></select> </div>
<div class="clr"></div>
    
<div style="width:20%; float:left;"><label><span> Address Line 2</span></label></div>
<div style="width:29%; float:left;"> <input type="text"  name="address2" id="supp_add2" value="" placeholder="Address Line 2" tabindex="3"  ng-model="supplier._add2" required/></div>
<div style="width:20%; float:left;"><label><span>Pincode</span></label></div>
<div style="width:29%; float:left;">
<div ng-class="{ 'has-error' : userForm.txtphone.$invalid && !userForm.txtphone.$pristine }">
<input type="text" name="_pincode" id="_pincode"  placeholder="Pincode" tabindex="6" ng-model="supplier._pincode" valid-number/>
<p ng-show="form1._pincode.$invalid && !form1._pincode.$pristine" class="help-block">Enter only digit.</p>
</div>
</div>
<div class="clr"></div>

    
<div><h2>Excise Details</h2></div>
<div style="width:20%; float:left;"><label><span>ECC Code No.</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="ECCCodeNo" id="supp_ecc" value="" placeholder="ECC Code No" tabindex="7"  ng-model="supplier._ecc_codeno" required/> </div>
<div style="width:20%; float:left;"> <label><span>Range</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="CERegnNO" id="supp_range" value="" placeholder="Range" tabindex="10"  ng-model="supplier._pc_range" required/> </div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span>TIN No.</span></label> </div>
<div style="width:29%; float:left;"><input type="text"  name="SALESTAXNO" id="supp_tin" value="" placeholder="TIN No" tabindex="8"  ng-model="supplier._tin_no" required/> </div>
<div style="width:20%; float:left;"> <label><span> Division</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="RngDivCollecterate" id="supp_division" value="" placeholder="Division" tabindex="11" ng-model="supplier._pc_division" required/> </div>
<div class="clr"></div>

<div style="width:20%; float:left;"><label><span>PAN No. </span></label> </div>
<div style="width:29%; float:left;"><input type="text" placeholder="PAN No" tabindex="9"  name="pan" id="supp_pan" ng-model="supplier._pan_no" required/> </div>
<div style="width:20%; float:left;"><label><span> Commission Rate</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="add" id="supp_commission" value="" placeholder="Commission Rate" tabindex="12" ng-model="supplier._commission_rate" required/> </div>
<div class="clr"></div>
<div style="width:20%; float:left;">
	<label><span>Tax Type:</span></label>
</div>
<div style="width:29%; float:left;"> 
	<select name="select" id="txntype"  ng-model="supplier._tax_type"  tabindex="23" required>
		<option value="">Select Tax Type</option>
		<option value="S">CGST-SGST</option>
		<option value="I">IGST</option>
	</select>
</div>
<div class="clr"></div>
<div><h2>Bank Detail</h2></div>
<div style="width:20%; float:left;"> <label><span> Bank Name</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc" id="supp_bankname" value="" placeholder="Bank Name" tabindex="13" ng-model="supplier._bankname" required/></div>
<div style="width:20%; float:left;"><label><span>RTGS Code</span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc3" id="supp_rtgs" value="" placeholder="RTGS Code" tabindex="17" ng-model="supplier._rtgs" required/></div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span>Bank Account No</span></label></div>
<div style="width:29%; float:left;"><input type="text" id="supp_accountnumber" value="" placeholder="Bank Account No" tabindex="14" ng-model="supplier._accountnumber" valid-number/> </div>
<div style="width:20%; float:left;"><label><span>NEFT Code </span></label></div>
<div style="width:29%; float:left;"><input type="text"  name="gc4" id="supp_neft" value="" placeholder="NEFT Code" tabindex="18" ng-model="supplier._neft" required/></div>
<div class="clr"></div>
    
<div style="width:20%; float:left;"><label><span>Account Type</span></label></div>
<div style="width:29%; float:left;">
<select name="title" ng-model="supplier._accounttype" required id="supp_account" tabindex="15"><option value="">Select Account Type</option><option value="S">Saving</option><option value="C">Current</option></select>
<!-- <input type="text"  name="gc5" id="supp_account" value="" placeholder="Account Type" tabindex="15" class="required mytext"/> --></div>
<div class="clr"></div>
<div style="width:20%; float:left;"><label><span>Bank Address</span></label></div>
<div style="width:29%; float:left;"><textarea name="bank_add" id="bank_add" placeholder="Bank Address" tabindex="16" ng-model="supplier._bankaddress" required/></textarea> </div>
<div class="clr"></div>

<!-- <form name="form2"  id="form2"> -->
<div><h2>Supplier Contact Person Details</h2></div>
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
            <tr>
              <td><select name="title" id="emp_title" ng-model="supplier._title" tabindex="19"><option value="">Select Title</option>
              <?php
              include( "../../Controller/Param/Param.php");
              $result =  Param::GetTitleList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['titleid']."'>".$row['titlename']."</option>";
              }?>
              <!-- <option value="1">Mr.</option><option value="2">Miss.</option> --></select></td>
              <td><input type="text"  name="gc5" id="emp_fname" value="" ng-model="supplier._first_name" tabindex="20"/></td>
              <td><input type="text"  name="gc5" id="emp_lname" value="" ng-model="supplier._last_name" tabindex="21"/> </td>
              <td><select name="dept" id="emp_dept" ng-model="supplier._dept_id"  tabindex="22"><option value="">Select Department</option><?php
              $result =  Param::GetDepartmentList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['departmentid']."'>".$row['departmentname']."</option>";
              }?>
              <!-- <option value="1">Account</option><option value="2">Marketing</option><option value="3">HR</option> --></select></td>
              <td><input type="text"  name="gc5" id="emp_phon" value="" ng-model="supplier._phone1" tabindex="23"/></td>
              <td><input type="email"  name="gc5" id="emp_email" value="" ng-model="supplier._email1" tabindex="24"/></td>
            </tr>
            <tr  ng-repeat="item in supplier._EmployeeList">
              <td><input type="text" ng-model="item.titlename"/></td>
              <td><input type="text" ng-model="item._first_name"/></td>
              <td><input type="text" ng-model="item._last_name"/></td>
              <td><input type="text" ng-model="item.deptname"/></td>
              <td><input type="text" ng-model="item._phone"/></td>
              <td><input type="email" ng-model="item._email"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="supplier.deleteemp" ng-click="RemoveEmployee(item)" class="button"/></td>
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
<!-- <div><h2>Contact Person Information</h2></div><hidden id="supplier_id_for_employee"></hidden>
    <div style="width:20%; float:left;"><label><span>Title</span></label></div>
    <div style="width:29%; float:left;"><select name="title" id="emp_title"><option value="0">Select Title</option><option value="1">Mr.</option><option value="2">Miss.</option></select> </div>
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
            <input type="button" class="button" name="addcon" id="empadd" value="Update" onclick="UPDATEEmployee();" />
            <input type="submit" class="button" name="addcon2" id="empcancel" value="Cancel" onclick="CancelEmployee();" />
    </div> -->
	
<!-----------------------------BOF to add GST details for Supplier by Ayush Giri on 09-06-2017------------------------------->
<div align="left" id="GstInfo_Div" style=" width:90%;margin-left:30px;">
<div><h2>Supplier GST Details</h2></div>
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
			  <select name="gst_state_id" id="gst_state_id" ng-model="supplier._gst_state_id" tabindex="26">
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
			  <select name="gst_reg_status" id="gst_reg_status" ng-model="supplier._gst_reg_status" tabindex="27">
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
			  <td>
			  <select name="gst_mig_status" id="gst_mig_status" ng-model="supplier._gst_mig_status" tabindex="28">
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
              <td><input type="text"  name="gst_no" id="gst_no" value="" ng-model="supplier._gst_no" tabindex="29"/></td>
              <td><input type="text"  name="gst_reg_date" id="gst_reg_date" value="" ng-model="supplier._gst_reg_date" placeholder="yyyy-mm-dd" tabindex="21"/> </td>
              <td><input type="text"  name="arn_no" id="arn_no" value="" ng-model="supplier._arn_no" tabindex="30"/></td>
              <td><input type="text"  name="perm_gst" id="perm_gst" value="" ng-model="supplier._perm_gst" tabindex="31"/></td>
            </tr>
            <tr  ng-repeat="item in supplier._GSTList">
			  <td><input type="text" ng-model="item._gst_state_name"/></td>
              <td><input type="text" ng-model="item._gst_reg_status_name"/></td>
              <td><input type="text" ng-model="item._gst_mig_status_name"/></td>
              <td><input type="text" ng-model="item._gst_no"/></td>
			  <td><input type="text" ng-model="item._gst_reg_date"/></td>
              <td><input type="text" ng-model="item._arn_no"/></td>
              <td><input type="text" ng-model="item._perm_gst"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="supplier.deletegst" ng-click="RemoveGST(item)" class="button"/></td>
            </tr>
          </table>
    </div>
 <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="gstadd" id="gstadd" value="Add" ng-model="buyer.AddGST"  tabindex="32" ng-click="AddGST()" />
        </label>
    </div><div class="clr"></div>
</div>
<!-----------------------------EOF to add GST details for Supplier by Ayush Giri on 09-06-2017------------------------------->

<div align="center">
<input type="submit" class="button" name="qdd" id="btnaddsupplier" tabindex="33" value="Save" />
<span><a  style="text-decoration: none;" class="button"  tabindex="34" href="<?php print SITE_URL.VIEWSUPPLIERMASTER; ?>">Cancle</a></span>   
<!-- <input type="button" class="button" name="qdd" id="btnaddsupplier" tabindex="19" value="Save" onclick="AddSupplierMaster();"> -->
    <input type="button" class="button" name="qdd2" id="btnupdatesupplier" value="Update" tabindex="35" ng-click="Update();">
    <!-- <input type="submit" class="button" name="B1" value="Cancel" id="btncancle" tabindex="20" onclick="Cancle();"/> -->
</div></div><br/><br/><br/><br/><br/>
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

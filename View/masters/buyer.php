<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>New Buyer Entry Form</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<style>
.form-section .custom-error {
    color:#FF0000;
}
</style>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<!-- <script type='text/javascript' src='../js/ang_js/jquery-1.6.4.min.js'></script>
<script type='text/javascript' src='../js/js/ang_js/none.js'></script> -->
<!-- <script type='text/javascript' src='../js/ang_js/angular.min.js'></script> -->

<script type='text/javascript' src='../js/Masters_js/buyer.js'></script>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 14-06-2017------------------------------->
</head>
<body ng-app = "buyer_app">
<?php include("../../header.php") ?>
<form name="form1" id="form1" ng-controller="buyer_Controller" ng-submit="submitForm();"  class="smart-green" data-ng-init="init('<?php echo  $_GET['ID'];?>')">

<div>
<div align="center"><h1>Buyer Entry Form</h1></div>
    <div style="width:50%; float:left;">
        <label>
            <span>Buyer Name*:</span><hidden  ng-model="buyer._buyer_id" id="buyer_id" ></hidden>
            <input type="text" name="buyer_nm" ng-model="buyer._buyer_name" id="buyer_nm" placeholder="Buyer Name" tabindex="1" required/>
        </label>
    </div>
    
	<?php if(isset($_GET['ID']) && ($_GET['ID'] != '')){
        echo'<div style="width:25%; float:left;"><label>
            <span>Buyer Code:</span><!-- auto generate -->
            <input type="text" name="add" id="buyercode" value="" ng-model="buyer._buyer_code" placeholder="Buyer Code" tabindex="2" required readonly/>
        </label> </div>';
		}else{
		echo'<input type="hidden" name="add" id="buyercode" value="" ng-model="buyer._buyer_code" placeholder="Buyer Code" tabindex="2" required readonly/>';
		}
		?>

    <div style="width:25%; float:left;">
        <label>
            <span>Vendor Code*:</span>
            <input type="text" name="add2" id="vendercode" value="" ng-model="buyer._vendor_code" placeholder="Vendor Code" tabindex="3" required/>
        </label>
    </div>
    <div class="clr"></div>
    <div><h2>Buyer Billing Address Details</h2></div>
    <div style="width:50%; float:left;">
        <label>
            <span>Address Line 1:</span>
            <input type="text" name="buyer_nm19" id="buyer_add1" ng-model="buyer._bill_add1" placeholder="Address Line 1" tabindex="4" required/>
        </label>
    </div>
    
    <div style="width:25%;  float:left;">
        <label>
            <span>Pincode:</span><br/>
            <input type="text" name="buyer_nm21" id="pincode"  ng-model="buyer._pincode" placeholder="Pincode" tabindex="10" valid-number/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Mobile No.:</span><br/>
            <input type="number" style="width: 90%;" name="buyer_nm28" id="mobile" ng-model="buyer._mobile" placeholder="Mobile No" tabindex="11" valid-number/>
        </label>
    </div><div class="clr"></div>
    <div style="width:50%; float:left;">
        <label>
            <span>Address Line 2:</span>
            <input type="text" name="buyer_nm20" id="buyer_add2"  ng-model="buyer._bill_add2" placeholder="Address Line 2" tabindex="5" required/>
        </label>
    </div>
    
    <div style="width:25%; float:left;">
        <label>
            <span>Phone No.:</span><br/>
            <input type="number" style="width: 90%;" name="buyer_nm27" id="phone"  ng-model="buyer._phone" placeholder="Phone No" tabindex="12" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Fax No.:</span><br/>
            <input type="text" name="buyer_nm29" id="fax"  ng-model="buyer._fax" placeholder="Fax No" tabindex="13" required/>
        </label>
    </div>
    
    <div class="clr"></div>
    <div style="width:23.6%; float:left;">
        <label>
            <span>Country:</span>
            <select name="buyer_add12" id="buyer_country" ng-model="buyer._country_id" tabindex="6"><option value="">Select Country</option><option value="1">India</option></select>
        </label>
    </div>
    <div style="width:23.6%; float:left;">
        <label>
            <span>State:</span><!-- filled by user -->
            <?php
                   include( "../../Model/Masters/StateMaster_Model.php");
                  // include("../../Model/DBModel/DbModel.php");
                   $result =  StateMasterModel::GetStateList();
                   echo "<select name='state' id='state' onChange='showCity(this.value,0);' ng-model='buyer._state_id' tabindex='7'> 
                   <option value=''> Select State</option>";
                   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                       echo "<option value='".$row['StateId']."'>".$row['StateName']."</option>";
                   }
                   echo("</select>");
                 ?>
        </label>
    </div>
    <div style="width:25%;margin-left:35px; float:left;" class="form-section">
        <label>
            <span>Email Id:</span><br/>
            <input type="email" name="_email" id="email" ng-model="buyer._email" placeholder="Email Id" tabindex="14" required/> 
            <div class="custom-error" ng-show="form1._email.$dirty && form1._email.$invalid">
            <span ng-show="form1._email.$error.email">Invalid:Please, write a valid email address.</span>
            </div>
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:23.6%; float:left;">
        <label>
            <span>City:</span>
            <select name="city" id="city" onChange="showLocation(this.value,0);" class="input1"  ng-model="buyer._city_id" tabindex="8" >
            <option value="">Select City</option></select>
        </label>
    </div>
    <div style="width:23.6%; float:left;">
        <label>
            <span>Location:</span><select name="location" id="location" class="input1"  ng-model="buyer._location_id" tabindex="9"><option value="">Select Location</option></select>
        </label>
    </div>
    
    <div class="clr"></div>
    <div><h2>Buyer Excise Details</h2></div>
    <div style="width:25%; float:left;">
        <label>
            <span>ECC Code No:</span>
            <input type="text"  name="add4" id="ecc" value="" ng-model="buyer._ecc" placeholder="ECC Code No" tabindex="15" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Range:</span>
            <input type="text"  name="RngDivCollecterate" id="range" value="" ng-model="buyer._buyer_range" placeholder="Range" tabindex="18" required/>
        </label>
    </div>
    
    <div style="width:25%; float:left;">
        <label>
            <span>Credit Period*:</span>
            <input type="text" name="buyer_nm31" id="credit" ng-model="buyer._credit_period" placeholder="Credit Period" tabindex="21" valid-number/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Credit Limit*:</span>
            <input type="text" name="buyer_nm32" id="limit" ng-model="buyer.Credit_Limit" placeholder="Credit Limit" tabindex="22" valid-number/>
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>PAN No:</span>
            <input type="text" name="buyer_nm7" id="pan"  ng-model="buyer._pan" placeholder="PAN No" tabindex="16" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Division:</span>
            <input type="text"  name="ECCCodeNo" id="division" value="" ng-model="buyer._division" placeholder="Division" tabindex="19" required/>
        </label>
    </div>
     
    <div style="width:25%; float:left;">
        <label>
            <span>Tax Type:</span>
            <select name="select" id="txntype"  ng-model="buyer._tax_type"  tabindex="23" required>
              <option value="">Select Tax Type</option>
			  <!-- BOF for adding GST by Ayush Giri on 20-06-2017 -->
              <!--<option value="V">VAT</option>
              <option value="C">CST</option>-->
			  <option value="S">CGST-SGST</option>
              <option value="I">IGST</option>
			  <!-- EOF for adding GST by Ayush Giri on 20-06-2017 -->
            </select>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Buyer Level:</span>
            <select name="select" id="byrlvl"  ng-model="buyer.Buyer_Level"  tabindex="24" required>
              <option value="">Select Buyer Level</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="X">Blocked</option>
            </select>
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>TIN No*:</span>
            <input type="text"  name="add3" id="tin" value="" ng-model="buyer._tin" placeholder="TIN No" tabindex="17" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Commission Rate:</span>
            <input type="text" name="commissionrate" id="commissionrate" value="" ng-model="buyer._commission_rate" placeholder="Commission Rate" tabindex="20" required value="" is-number/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Executive*:</span>
            <select name="buyer_add10" id="buyer_executive" ng-model="buyer._executive_id" tabindex="25" required>
            <option value="">Select Executive</option>
              <?php
             // include( "../../Model/Masters/User_Model.php");
          
              $result =  UserModel::LoadExecutive();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
              }?>
            </select>
        </label>
    </div>
    <div class="clr"></div>
    <div><h2>Shipping Address Details</h2></div>
    <div align="center"><strong><input name="checkbox4" type="checkbox" id="checkbox4" value="1" ng-model="buyer._check_add" ng-change="change();" tabindex="26"/>(Checked if Shipping address and biling address are same)</strong></div>
    <div style="width:50%; float:left;">
    <div align="center"><strong>Shipping Address 1</strong></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Address Line 1:</span>
                <input type="text" name="buyer_nm8" id="ship1_add1"  ng-model="buyer._add1" placeholder="Address Line 1" tabindex="27" required/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Address Line 2:</span>
                <input type="text" name="buyer_nm11" id="ship1_add2"  ng-model="buyer._add2" placeholder="Address Line 2" tabindex="28" required/>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Country:</span>
                <select name="city" id="shppingcountry1" class="input1"  ng-model="buyer._shipping_country_id" tabindex="29" required><option value="">Select Country</option><option value="1">INDIA</option></select>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>State:</span>
                <?php
      $result =  StateMasterModel::GetStateList();
      echo "<select name='state' id='shppingstate1' onChange='showCityForShipping1(this.value,0);'  tabindex='30' required  ng-model='buyer._shipping_state_id' > <option value=''> Select State</option>";
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
          echo "<option value='".$row['StateId']."'>".$row['StateName']."</option>";
      }
      echo("</select>");
                 ?>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>City:</span>
                <select name="city" id="shppingcity1" onChange="showLocationForShipping1(this.value,0);"  tabindex="31" required ng-model="buyer._shipping_city_id" > <option value="">Select City</option></select>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Location:</span>
                <select name="city" id="shppinglocation1"  tabindex="32" required ng-model="buyer._shipping_location_id" ><option value="">Select Location</option></select>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Pincode:</span>
                <input type="text" name="ship1_pincode" id="ship1_pincode"  ng-model="buyer._shipping_pincode" placeholder="Pincode" tabindex="33" valid-number/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Phone No:</span>
                <input type="number" style="width: 90%;" name="ship1_phon" id="ship1_phon"  ng-model="buyer._shipping_phone" placeholder="Phone No" tabindex="34" required valid-number/>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Mobile No:</span>
                <input type="number" style="width: 90%;" name="ship1_mobile" id="ship1_mobile" ng-model="buyer._shipping_mobile" placeholder="Mobile No" tabindex="35" valid-number/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Fax No:</span>
                <input type="text" name="ship1_fax" id="ship1_fax" ng-model="buyer._shipping_fax" placeholder="Fax No" tabindex="36" required/>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Email Id:</span>
                <input type="email" name="ship1_email" id="ship1_email" ng-model="buyer._shipping_email" placeholder="Email Id" tabindex="37" required/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
    <div align="center"><strong>Shipping Address 2</strong></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Address Line 1:</span>
                <input type="text" name="buyer_nm17" id="ship2_add1" ng-model="buyer.shipping2add1" placeholder="Address Line 1" tabindex="38"/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Address Line 2:</span>
                <input type="text" name="buyer_nm18" id="ship2_add2"  ng-model="buyer.shipping2add2" placeholder="Address Line 2" tabindex="39"/>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Country:</span>
                <select name="city" id="shppingcountry2" class="input1" ng-model="buyer.shippingcountry2"  tabindex="40" ><option value="">Select Country</option><option value="1">INDIA</option></select>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>State:</span>
                <?php
                $result =  StateMasterModel::GetStateList();
                echo "<select name='state' id='shppingstate2' tabindex='41' onChange='showCityForShipping2(this.value,0);' ng-model='buyer.shippingstate2' >
                <option value=''> Select State</option>";
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['StateId']."'>".$row['StateName']."</option>";
                }
                echo("</select>");
                         ?>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>City:</span>
                <select name="city" id="shppingcity2" onChange="showLocationForShipping2(this.value,0);" tabindex="42" ng-model="buyer.shippingcity2" >     
                <option value="">Select City</option></select>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Location:</span>
                <select name="city" id="shppinglocation2" ng-model="buyer.shippinglocation2"  tabindex="43" >
                <option value="">Select Location</option></select>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Pincode:</span>
                <input type="text" name="ship2_pincode" id="ship2_pincode"  ng-model="buyer.shippingpincode2" placeholder="Pincode" tabindex="44" valid-number/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Phone No:</span>
                <input type="number" style="width: 90%;" name="ship2_phon" id="ship2_phon" ng-model="buyer.shippingphone2" placeholder="Phone No" tabindex="45" valid-number/>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Mobile No:</span>
                <input type="number" style="width: 90%;" name="ship2_mobile" id="ship2_mobile" ng-model="buyer.shippingmobile2" placeholder="Mobile No" tabindex="46" valid-number/>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Fax No:</span>
                <input type="text" name="ship2_fax" id="ship2_fax" ng-model="buyer.shippingfax2" placeholder="Fax No" tabindex="47" />
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Email Id:</span>
                <input type="email" name="ship2_email" id="ship2_email" ng-model="buyer.shippingemail2" placeholder="Email Id" tabindex="48"/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:48%; float:left;">
        <label>
            <span>Remark:</span>
            <textarea name="comm" id="comm" cols="45" rows="5" ng-model="buyer._remarks" placeholder="Maximum 300 char." tabindex="49"/></textarea>
        </label>
    </div>
    <div class="clr"></div>
    
    <div><h2>Buyer Discount Details</h2></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
        <table width="100%" border="1">
            <tr>
              <td width="48%"><strong>Principal</strong></td>
              <td width="52%"><strong>Discount</strong></td>
            </tr>
            <tr>
              <td><div align="center"><hidden id="buyerid_for_discount"></hidden>
                                      <hidden id="principalid_for_discount"></hidden>
                <select name="gd2" id="principalid" style="width:254px" class="input1" tabindex="50" ng-model="buyer._principal_id" >
                     <option value="">Select Principal</option>
                     <?php
                     include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                     $result =  Principal_Supplier_Master_Model::Get_Principal_List();
                     while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                         echo "<option value='".$row['Principal_Supplier_Id']."'>".$row['Principal_Supplier_Name']."</option>";
                     }?>
                   </select><!--  display from principal master --></div></td>
              <td><input name="buyer_nm2" type="text" id="buyer_discount" tabindex="51"  ng-model="buyer._discount"  isNumber/>
                (in %)</td>
                
            </tr>
            <tr  ng-repeat="item in buyer._DiscountList">
              <td width="48%"><input type="text" ng-model="item._principalname"/></td>
              <td width="52%"><input type="text" ng-model="item._discount"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="buyer.deleteitem"  valid-number ng-click="RemoveDiscount(item)" class="button"/></td>
            </tr>
          </table>
          <table class="loaddiscount" style="width:100%;"></table>
    </div>
    <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="addcon" id="discountadd" value="Add" tabindex="52" ng-model="buyer.AddDis" ng-click="AddDiscount()" />
            <!-- <input type="button" class="button" name="addcon" id="discountadd" value="Add" onclick="AddBuyerDiscount();" /> -->
            <!-- <input type="button" class="button" name="addcon" id="discountupdate" value="Update" onclick="UpdateBuyerDiscount();" />
            <input type="button" class="button" name="addcon2" id="discountcancel" value="Cancel" onclick="CancelBuyerDiscount();" /> -->
        </label>
    </div><div class="clr"></div>
    <div><h2>Buyer Contact Person Details</h2></div>
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
              <td><select name="title" class="input1" id="emp_title" ng-model="buyer._title" tabindex="53">
              <option value="">Select Title</option>
              <?php
              include( "../../Controller/Param/Param.php");
              $result =  Param::GetTitleList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['titleid']."'>".$row['titlename']."</option>";
              }?>
              <!-- <option value="1">Mr.</option><option value="2">Miss.</option> -->
              
              </select></td>
              <td><input type="text"  name="gc5" id="emp_fname" value="" ng-model="buyer._first_name" tabindex="54"/></td>
              <td><input type="text"  name="gc5" id="emp_lname" value="" ng-model="buyer._last_name" tabindex="55"/> </td>
              <td><select class="input1" name="dept" id="emp_dept" ng-model="buyer._dept_id" tabindex="56">
              <option value="">Select Department</option>
              <?php
              $result =  Param::GetDepartmentList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['departmentid']."'>".$row['departmentname']."</option>";
              }?>
              <!-- <option value="1">Account</option><option value="2">Marketing</option><option value="3">HR</option> -->
              
              </select></td>
              <td><input type="text"  name="gc5" id="emp_phon" value="" ng-model="buyer._phone1" tabindex="57"/></td>
              <td><input type="email"  name="gc5" id="emp_email" value="" ng-model="buyer._email1" tabindex="58"/></td>
            </tr>
            <tr  ng-repeat="item in buyer._EmployeeList">
              <td><input type="text" ng-model="item.titlename"/></td>
              <td><input type="text" ng-model="item._first_name"/></td>
              <td><input type="text" ng-model="item._last_name"/></td>
              <td><input type="text" ng-model="item.deptname"/></td>
              <td><input type="text" ng-model="item._phone"/></td>
              <td><input type="email" ng-model="item._email"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="buyer.deleteemp" ng-click="RemoveEmployee(item)" class="button"/></td>
            </tr>
          </table>
    </div>
    <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="empadd" id="empadd" value="Add" ng-model="buyer.AddEmp"  tabindex="59" ng-click="AddEmployee()" />
           <!--  <input type="button" class="button" name="addcon" id="empadd" value="Add" onclick="AddEmployee();" /> -->
            <!-- <input type="button" class="button" name="addcon1" id="empadd" value="Update" onclick="UpdateEmployee();" />
            <input type="submit" class="button" name="addcon2" id="empcancel" value="Cancel" onclick="CancelEmployee();" /> -->
        </label>
    </div>
	<div class="clr"></div>
<!-----------------------------BOF to add GST details for Principal by Ayush Giri on 12-06-2017------------------------------->
<div><h2>Buyer GST Details</h2></div>
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
            <tr><hidden id="buyer_id_for_gst"></hidden>
              <td>
			  <select name="gst_state_id" id="gst_state_id" ng-model="buyer._gst_state_id">
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
			  <select name="gst_reg_status" id="gst_reg_status" ng-model="buyer._gst_reg_status">
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
			  <td>
			  <select name="gst_mig_status" id="gst_mig_status" ng-model="buyer._gst_mig_status" >
				<option value="">Select Status</option>
				<option value='0'>Disabled</option>
				<option value='1'>Active</option>
              </select>
			  </td>
              <td><input type="text"  name="gst_no" id="gst_no" value="" ng-model="buyer._gst_no"/></td>
              <td><input type="text"  name="gst_reg_date" id="gst_reg_date" value="" ng-model="buyer._gst_reg_date" placeholder="yyyy-mm-dd" /> </td>
              <td><input type="text"  name="arn_no" id="arn_no" value="" ng-model="buyer._arn_no" /></td>
              <td><input type="text"  name="perm_gst" id="perm_gst" value="" ng-model="buyer._perm_gst" /></td>
            </tr>
            <tr  ng-repeat="item in buyer._GSTList">
			  <td><input type="text" ng-model="item._gst_state_name"/></td>
              <td><input type="text" ng-model="item._gst_reg_status_name"/></td>
              <td><input type="text" ng-model="item._gst_mig_status_name"/></td>
              <td><input type="text" ng-model="item._gst_no"/></td>
			  <td><input type="text" ng-model="item._gst_reg_date"/></td>
              <td><input type="text" ng-model="item._arn_no"/></td>
              <td><input type="text" ng-model="item._perm_gst"/></td>
              <td><input type="button" name="button6" id="button60" value="X" ng-model="buyer.deletegst" ng-click="RemoveGST(item)" class="button"/></td>
            </tr>
          </table>
    </div>
    <div class="clr"></div>
    <div style="width:5%; float:right;">
        <label>
        <input type="button" class="button" name="gstadd" id="gstadd" value="Add" ng-model="buyer.AddGST"  ng-click="AddGST()" />
        </label>
    </div>
	<div class="clr"></div>
<!-----------------------------EOF to add GST details for Principal by Ayush Giri on 12-06-2017------------------------------->
    <div align="center">
         <input type="submit"  class="button" name="B1" value="Save" id="btnaddbuyer" ng-model="buyer.save"  tabindex="60" />
         <input type="button" name="addcon"  class="button" id="btnupdatebuyer" value="Update" ng-click="Update();"  tabindex="60"/>
         <span><a tabindex="61" style="text-decoration: none;" class="button" href="<?php print SITE_URL.VIEWBUYERMASTER; ?>">Cancel</a></span>
         <!-- <input type="submit"  class="button" name="B3"  value="Cancel" onClick="CancelEmployee();"/> -->
    </div>
</div>
<div align="left" id="BuyerDiscount_Div" style=" width:90%;margin-left:30px;"></div>
<div align="left" id="ContactInfo_Div" style=" width:90%;margin-left:30px;"></div>
<br/><br/><br/><br/><br/>
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

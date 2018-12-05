<?php
include('root.php');
include($root_path."GlobalConfig.php");
include($root_path."Model/ReportModel/Report1Model.php");
include("../../header.php") 
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bundle Purchase Order</title>

<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/bundle.js'></script>
<script type="text/javascript">
$(function() {
  $("#autocomplete-ajax-buyer").focus();
});
</script>
<style>
.link {
    transition: all ease 0.2s;
    -moz-transition: all ease 0.2s;
    -webkit-transition: all ease 0.2s;
    -o-transition: all ease 0.2s;
    overflow: hidden;
    max-height: 0;
    background: blue;
    margin: 0 10px;
    padding: 0 10px;

}
.show {
        max-height: 100px;
        margin: 10px;
        padding: 10px;
    }
</style>
</head>
<body ng-app="bundle_app">

<?php

if(isset($_GET['USER'])){
	$UID=$_SESSION["USER"];
}

if(isset($_GET['fromPage'])){
	$FPG=$_GET["fromPage"];
}
if(isset($_GET['POID'])){
	$POID = $_GET['POID'];
}


?>

<div align="left" valign="top" class="fhead">&nbsp;&nbsp;Bundle Purchase Order Form</div>
<form id="form1"  ng-controller="bundle_Controller" ng-submit="AddBundlePO();" data-ng-init="init('<?php echo($_GET['POID']) ?>')" class="smart-green">

<input type="hidden" name="poId" id="ipoId" ng-model="purchaseOrder._bpoId"  value="<?php echo($POID) ?>" >


<div>
 <div style="width:50%; float:left;">
  <div style="width:98%; float:left;">
   <label>
    <span>Buyer Name*:</span><hidden id="buyerid" ng-model="purchaseOrder.bn"></hidden><input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Buyer Name" ng-model="purchaseOrder.bn_name" onKeyPress="loadBuyerById(this.value);"  required/>
  </label>
                       <!-- <select name="buyer_name" id="ibuyer_name" ng-model="purchaseOrder.bn" class="input1"  tabindex="1"  ng-change="showParentQuotation();getBillingAddress();">
                              <option value="" selected=""></option>
                             <?php include( "../../Model/Masters/BuyerMaster_Model.php"); 
							 //include("../../Model/DBModel/DbModel.php");
			                  $result =  BuyerMaster_Model::GetBuyerList();
			                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                            echo "<option value='".$row['BuyerId']."' title='".$row['BuyerName']."'>".$row['BuyerName']."</option>";
                              }?>
                        </select> -->


            </div><div class="clr"></div>
    </div>

    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Mode of Delivery*:</span>
                        <select name="mode_de" id="imode_de" tabindex="6" ng-model="purchaseOrder.mode_de" required>
                        <option value="">Select Mode of Delivery</option>
                                 <?php include( "../../Model/Param/param_model.php");
			                      $result =  ParamModel::GetParamList('DELIVERY','MODE');
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                                  }?>
                         </select>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Buyer Purchase Order No.*:</span>
                        <input name="po_no" type="text" id="ipo_no" ng-model="purchaseOrder.pon" tabindex="2"  ng-blur="validatePO();" required/>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Credit Period*:</span>
                        <input name="credit_period" type="text" id="ic_p" tabindex="7" ng-model="purchaseOrder.cp" valid-number/>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Purchase Order Date*:</span>
                        <input name="pod" type="text" id="ipod" ng-model="purchaseOrder.pod" tabindex="3" value="Filled By User from Buyer's PO" required/>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Sales Executive*:</span>
                        <select name="executive" id="iexe" tabindex="8" ng-model="purchaseOrder.exec" required>
                        <option value="">Select Sales Executive</option>
                              <?php  //include( "../../Model/Masters/User_Model.php");
			                      $result =  UserModel::getUserByType('E');
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
                                  }?>
                        </select>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Purchase Order Validity Date*:</span>
                        <input name="povd" type="text" id="ipovd" ng-model="purchaseOrder.ipovd" tabindex="4" value="Filled By User from Buyer's PO" required/>
                    </label>
            </div><div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
             <label>
              <span>Market segment*:</span>
			 
				
				<?php  include( "../../Model/Masters/MarketSegment_Model.php");
								$msid='';
			                      $result =  MarketSegmentModel::GetMsList();								
										echo ' <select name="marketsegment" id="marketsegment" tabindex="9" ng-model="purchaseOrder.ms" required><option value="">Select One</option>';
										while($row=mysql_fetch_array($result, MYSQL_ASSOC)){										
											echo "<option value='".$row['msid']."'>".$row['msname']."</option>";							
										}								
				?>
				
			  </select>
             </label>
            </div>   

            <div class="clr"></div>
			
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label> <span>Principal*:</span>
                       <select name="principalId" id="iprincipalId" class="input1"  ng-model="purchaseOrder.po_principalId" ng-change="showPOCodePartNo(); getDiscount();" style="width:350px;" tabindex="5">
              <option value="" selected>Select Principal</option>
                           <?php include( "../../Model/Business_Action_Model/po_model.php");
			                  $result = purchaseorder_Details_Model::showPOPrincipalSupplier('PRINCIPAL_SUPPLIER_MASTER','0','P');
			                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                       echo "<option value='".$row['PID']."'>".$row['PNAME']."</option>";
                              }?>
              </select>
			  <input type="hidden" name="bprincipalId" id="bprincipalId"/>
                    </label>
					<input type="hidden" name="pot" id="ipot" ng-model="purchaseOrder.pot" required/>
					
            </div><div class="clr"></div>
    </div>
	<div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
             <label>
              <span>Cash Discount(if any):<input name="cash_discount" type="checkbox" id="icd" ng-model="purchaseOrder.cd" tabindex="9" value="1" ng-click="cashDisc();"/></span>
             </label>
            </div>
             <div class="clr"></div>
            <div id="dcdt" style="width:50%; float:left;">
             <label>
               <input name="cash_discountt" type="text" id="icdt" ng-model="purchaseOrder.cdt" tabindex="10" value="" size="4"/>
             </label>
            </div>

            <div class="clr"></div>
			
    </div>
	
    <div class="clr"></div><!-- Billing Address Comes from buyer master and not editable <br>
      // Shipping Address Comes from buyer master and editable according to PO-->
    <div style="width:100%; float:left;">
            <div align="center" style="width:100%; float:left;">

                    <input name="addressTag" type="checkbox" id="iaddTag" tabindex="11" ng-model="purchaseOrder.addTag" value="1" ng-click="putShippingAdd();"/>
                        <span style="font-weight: bold;">(Checked if Shipping address and biling address are same)</span>

            </div><div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:100%; float:left;">
        <div style="width:50%; float:left;">
             <div align="center" style="width:100%; float:left;">
                 <span style="font-weight: bold;">Billing  Address</span>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="badd1" id="ibadd1"  ng-model="purchaseOrder.badd1"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="badd2" id="ibadd2" ng-model="purchaseOrder.badd2" ng-model="purchaseOrder.badd2"   value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="hidden" name="bcountry" id="billingcountry1" ng-model="purchaseOrder.bcountry1"  value="" class="input1" />
	 	<input type="text" name="bcountryName" id="billingcountryName1" ng-model="purchaseOrder.bcountryName1"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="hidden" name="bstate" id="billingstate1" ng-model="purchaseOrder.bstate1"  value="" class="input1" />
	  <input type="text" name="bstateName" id="billingstateName1" ng-model="purchaseOrder.bstateName1"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="hidden" name="bcity" id="bcity1" class="input1"  ng-model="purchaseOrder.bcity1" />
      	<input type="text" name="bcityName" id="bcityName1" class="input1"  ng-model="purchaseOrder.bcityName1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="hidden" name="bloc" id="blocation1" class="input1"  ng-model="purchaseOrder.blocation1" />
      	<input type="text" name="blocName" id="blocationname1" class="input1"  ng-model="purchaseOrder.blocationName1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="bpincode" id="ibpincode" ng-model="purchaseOrder.bpincode" value=""  size="6" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="bphno" id="iphno" ng-model="purchaseOrder.bphno"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="bmobno" id="imobno" ng-model="purchaseOrder.bmobno"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="bfax" id="ibfax" ng-model="purchaseOrder.bfax"  value="" class="input1" readonly/>
             </div>
             <div align="center" style="width:100%; float:left;">
                <input type="text" name="bemail" id="ibemail" ng-model="purchaseOrder.bemail"  value="" class="input1" readonly/>
             </div>
        </div>
        <div style="width:50%; float:left;">
             <div align="center" style="width:100%; float:left;">
                 <span style="font-weight: bold;">Shipping Address</span>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="sadd1" id="isadd1" tabindex="12" ng-model="purchaseOrder.sadd1"  value="" class="input1" />
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="sadd2" id="isadd2" tabindex="13" ng-model="purchaseOrder.sadd2" value="" class="input1"/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <select name="scountry" id="shppingcountry1" class="input1" tabindex="14"  ng-model="purchaseOrder.scountry1" >
	        <option value="">Select Country</option>
	        <option value="1">INDIA</option>
	       </select>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <select name="sstate" id="shppingstate1" class="input1" tabindex="15" ng-model="purchaseOrder.sstate1" ng-change="showCity(0);">
	                <option value="">Select State</option>
	                <?php include( "../../Model/Masters/StateMaster_Model.php");
		             $result =  StateMasterModel::GetStateList();
		             while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			            echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
                     }?>
	               </select>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <select name="scity" id="shppingcity1" class="input1" tabindex="16" ng-model="purchaseOrder.scity1"  ng-change="showLocation(0);"><option value="">Select City</option></select>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <select name="sloc" id="shppinglocation1" tabindex="17" class="input1"  ng-model="purchaseOrder.slocation1" readonly><option value="">Select Location</option></select>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="spincode" id="ispincode" tabindex="18" ng-model="purchaseOrder.spincode"  value="" size="6" class="input1"/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="sphno" id="isphno" tabindex="19" ng-model="purchaseOrder.sphno"  value="" class="input1"/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="smobno" id="ismobno" tabindex="20" ng-model="purchaseOrder.smobno"  value="" class="input1"/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="sfax" id="isfax" tabindex="21" ng-model="purchaseOrder.sfax"  value="" class="input1"/>
             </div>
             <div align="center" style="width:100%; float:left;">
                 <input type="text" name="semail" id="isemail" tabindex="22" ng-model="purchaseOrder.semail"  value="" class="input1"/>
             </div>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div align="center">
           <label><span>Purchase Order Detail Start {{bundlesn}}</span></label>
    </div><div class="clr"></div>
    <div style="width:100%; float:left; height:350px; overflow:scroll;">
	<!-- Bundle Group start -->
		<div style="border: 2px solid #0000FF;">
	 <!-- Bundle part start -->
		 <table width="100%">
            <tr>             
              <td align="center" style="width:8%;">GL Acc*</td>
              <td align="center" style="width:32%;">Description*</td>
              <td align="center" style="width:8%;">Quantity*</td>
              <td align="center" style="width:12%;">UOM*</td>
              <td align="center" style="width:8%;">Unit Rate INR*</td>
              <td align="center" style="width:8%;">Discount(%)* </td>
              <td align="center" style="width:10%;">Sale Tax (%)*</td>
              <td align="center" style="width:10%;">Net Amt INR)*</td>
              <td align="center" style="width:4%;">&nbsp;</td>
              
          </tr>
          <tr>              
			<td><input name="bglAcc" type="text"  id="bglAcc" ng-model="purchaseOrder.bglAcc"  tabindex="23"/></td>
			<td><input name="bitem_desc" type="text" id="bitem_desc" ng-model="purchaseOrder.bitem_desc" value="" tabindex="24"/></td>
			<td><input name="bqty" type="text" id="biqty" ng-model="purchaseOrder.bpo_qty"  value="filled by user" ng-change="calculateBundleValue();" tabindex="25"/>
			 <input name="bundleqty" type="hidden" id="bundleqty"/></td>
			<td><hidden id="unitId" ng-model="purchaseOrder.bpo_unitid"/>
				<select name="bunit" id="biunit" class="input1"   tabindex="34" ng-model="purchaseOrder.bpo_unit"  ng-change="getText2();"><option value="">Select UOM</option>
				 <?php
					  include_once( "../../Model/Masters/UnitMaster_Model.php");
					  $result = UnitMasterModel::LoadAll(0);
					 foreach ($result as $unit) {
							 echo "<option value='".$unit->_uniId."'>".$unit->_unitName."</option>";
						}?>
				</select>
			</td>
			<td><input name="bprice" type="text" id="biprice" ng-model="purchaseOrder.bpo_price" ng-change="calculateBundleValue();checkPrice();" tabindex="26"/>
			<input name="bundlerpice" type="hidden" id="bundlerpice"/>
			</td>
			<td><input name="bdiscount" type="text" id="bidiscount" ng-model="purchaseOrder.bpo_discount"  tabindex="27" value=""  ng-change="calculateBundleValue(); checkDiscount();" isNumber/>
				<input name="bundlediscount" type="hidden" id="bundlediscount"/>
				</td>
				
				 <td><select name="bsalestax" id="bisalestax" ng-model="purchaseOrder.bpo_saleTax" ng-change="getText3();" tabindex="28">
				 </select></td>
				 <td><input name="btotVa" type="text" id="bitotVal"  ng-model="purchaseOrder.bpo_totVal"  tabindex="29" readOnly/></td>
				 <td></td>            
           
            </tr>           
          </table>
		  <!-- BUndle part end -->
		   <!-- BUndle Codepart Item description start -->
        <table width="100%" border="0">
            <tr>
				 
				  <td align="center" style="width:10%;">Part No*.</td>
				  <td align="center" style="width:10%;">Identification Mark</td>
				  <td align="center" style="width:20%;">Item Description</td>
				  <td align="center" style="width:10%;">Buyer Item Code</td>
				  <td align="center" style="width:10%;">Quantity*</td>
				  <td align="center" style="width:10%;">Unit </td>
				  <td align="center" style="width:10%;">Landing Price</td>
				  <td align="center" style="width:10%;">Total Value</td>
				  <td style="width:10%;">&nbsp;</td>
            </tr>
            <tr>	  
				  <td><input name="codePartNo" type="hidden" id="icodePartNo" ng-model="purchaseOrder.po_codePartNo"></hidden>
					  <input name="cPartNo" type="text"  id="autocomplete-ajax-CodePartNo" ng-model="purchaseOrder.cPartNo" tabindex="30"  ></td>
				  <td><input name="iden_mark" type="text" id="iden_mark" ng-model="purchaseOrder.iden_mark" value="" tabindex="31" readonly /></td>
				  <td><input name="item_desc" type="text" id="item_desc" ng-model="purchaseOrder.item_desc" value=""  tabindex="32" readOnly /></td>
				  <td><input name="bitemcode" type="text" id="ibitemcode" ng-model="purchaseOrder.po_buyeritemcode" tabindex="33"/></td>
				  <td><input name="qty" type="text" id="iqty" ng-model="purchaseOrder.po_qty"  value="filled by user"  ng-change="calculateValue();" tabindex="34"/></td>
				  <td><hidden id="unitId" ng-model="purchaseOrder.po_unitid"/>
				  <input type="text" name="unit" id="iunit" ng-model="purchaseOrder.po_unit" tabindex="3111" readOnly /></td>
				  <td><input name="price" type="text" id="iprice" ng-model="purchaseOrder.po_price" tabindex="35" readOnly /></td>
				  <td><input name="totVa" type="text" id="itotVal"  ng-model="purchaseOrder.po_totVal"  tabindex="36" readonly /></td>
				  <td><label> &nbsp;&nbsp;&nbsp;
            <input class="button" type="button" name="Add" id="iAdd" value="Add" ng-model="purchaseOrder.additem"  tabindex="38" ng-click="addItem()"/>
        </label></td>
            </tr>

             <tr id="poRow" ng-repeat="item in purchaseOrder._items" >
				 <td><input type="text" ng-model="item.cPartNo"  /><input type="hidden" ng-model="item.po_codePartNo" ></td>
				 <td><input type="text" ng-model="item.iden_mark" readOnly /></td>
				 <td><input type="text" ng-model="item.item_desc" readOnly /></td>
				 <td><input type="text" ng-model="item.po_buyeritemcode" /></td>
				 <td><input type="text" ng-model="item.po_qty" ng-change="RowChangeEvent(item);" /></td>
				 <td><input type="text" ng-model="item.po_unit"  readOnly /><input type="hidden" ng-model="item.unit_id" /></td>
				 <td><input type="text" ng-model="item.po_price" readOnly /></td>
				 <td><input type="text" ng-model="item.po_totVal" readOnly /></td>
				 <td><input type="hidden" ng-model="item.po_odiscount"/>
					 <input type="hidden" ng-model="item.po_oprice" />
					 <input type="hidden" ng-model="item.po_price_category"/>
					 <input type="hidden" ng-model="item.po_discount_category"/>
					 <hidden ng-model="item.bpod_Id" />
					 <input type="button" name="DEL" id="idel" value="x" ng-model="purchaseOrder.deleteitem" ng-click="removeItem(item)"/>
				 </td>
            </tr>
          </table>
		  
		</div>
		
		
		
		<div style="border: 2px solid #0000FF;" ng-repeat="bundle in purchaseOrder.bundles">
	 <!-- Bundle part start fiend details-->
		 <table width="100%" border="0">
            <tr>
			<td align="center" style="width:8%;">GL Acc</td>
              <td align="center" style="width:32%;">Description</td>
              <td align="center" style="width:8%;">Quantity</td>
              <td align="center" style="width:12%;">UOM</td>
              <td align="center" style="width:8%;">Unit Rate INR</td>
              <td align="center" style="width:8%;">Discount(%) </td>
              <td align="center" style="width:10%;">Sale Tax (%)</td>
              <td align="center" style="width:10%;">Net Amt INR)</td>
              <td align="center" style="width:4%;"></td>
                        
            </tr>

            <tr>    
			
				  <td><input name="ibglAcc" type="text"  id="ibglAcc" ng-model="bundle.ibglAcc"  tabindex="23"  ></td>
				  <td><input name="ibitem_desc" type="text" id="ibitem_desc" ng-model="bundle.ibitem_desc" value="" tabindex="24"/></td>
				  <td><input name="ibqty" type="text" id="ibiqty" ng-model="bundle.ibpo_qty"  value="filled by user" size="4" ng-change="calculateBundleValue();"   tabindex="25"/></td>
				  <td><hidden id="iunitId" ng-model="bundle.ibpo_unitid"/>
				<select name="ibunit" id="ibiunit" class="input1"   tabindex="31114" ng-model="bundle.ibpo_unit" style="width:200px;" ng-change="getText2();" required><option value="">Select UOM*</option>
					<?php
					  include_once( "../../Model/Masters/UnitMaster_Model.php");
					  $result = UnitMasterModel::LoadAll(0);
					 foreach ($result as $unit) {
							 echo "<option value='".$unit->_uniId."'>".$unit->_unitName."</option>";
						}?>
				  </select>
				 </td>
				  <td><input name="ibprice" type="text" id="ibiprice" ng-model="bundle.ibpo_price" ng-change="calculateBundleValue();checkPrice();" tabindex="26" /></td>
				  <td><input name="ibdiscount" type="text" id="ibidiscount" ng-model="bundle.ibpo_discount"  tabindex="27" value=""  ng-change="calculateBundleValue(); checkDiscount();" /></td>
				
				 <td>
				 <?php 	       
				 $result = purchaseorder_Details_Model::getSalesTaxNew();	 
			 ?>
				<select name="ibsalestax" id="ibisalestax" ng-model="bundle.ibpo_saleTax" ng-change="getText3();" tabindex="28">
					<?php								
					foreach($result as $saletax) {
						echo "<option value=".$saletax->sTax.">" .$saletax->po_saleTax."</option>";
                    }				   
					?>			
				</select>
				<input type="hidden" ng-model="bundle.ibpo_saleTax" />
			</td>
				 <td><input name="ibtotVa" type="text" id="ibitotVal"  ng-model="bundle.ibpo_totVal"  tabindex="29"/></td>
				 <td>
					<input type="button" name="DEL" id="bidel" value="xx" ng-model="purchaseOrder.deletebundle" ng-click="removeBundle(bundle)" style="background-color:red;"/>
					<input name="bundle_id" type="hidden"  id="bundle_id" ng-model="bundle.bundle_id">
					<input name="bpoId" type="hidden"  id="bpoId" ng-model="bundle.bpoId">
				 </td>            
           
            </tr>           
          </table>
		  <!-- BUndle part end -->
		   <!-- BUndle Codepart Item description start -->
        <table width="100%" border="0">
            <tr>
				 
				  <td align="center" style="width:10%;">Part No*.</td>
				  <td align="center" style="width:10%;">Identification Mark</td>
				  <td align="center" style="width:20%;">Item Description</td>
				  <td align="center" style="width:10%;">Buyer Item Code</td>
				  <td align="center" style="width:10%;">Quantity*</td>
				  <td align="center" style="width:10%;">Unit </td>
				  <td align="center" style="width:10%;">Landing Price</td>
				  <td align="center" style="width:10%;">Total Value</td>
				  <td style="width:10%;">&nbsp;</td>
            </tr>
       

             <tr id="poRow" ng-repeat="item in bundle.items" >
				 <td><input type="text" ng-model="item.cPartNo"  /><input type="hidden" ng-model="item.po_codePartNo" ></td>
				 <td><input type="text" ng-model="item.iden_mark" readOnly /></td>
				 <td><input type="text" ng-model="item.item_desc" readOnly /></td>
				 <td><input type="text" ng-model="item.po_buyeritemcode" /></td>
				 <td><input type="text" ng-model="item.po_qty" ng-change="RowChangeEvent(item);" /></td>
				 <td><input type="text" ng-model="item.po_unit" readOnly /><input type="hidden" ng-model="item.unit_id" /></td>
				 <td><input type="text" ng-model="item.po_price" readOnly /></td>
				 <td><input type="text" ng-model="item.po_totVal" readOnly /></td>
				 <td><input type="hidden" ng-model="item.po_odiscount"/>
					 <input type="hidden" ng-model="item.po_oprice" />
					 <input type="hidden" ng-model="item.po_price_category"/>
					 <input type="hidden" ng-model="item.po_discount_category"/>
					 <hidden ng-model="item.bpod_Id" />
					 <input type="button" name="DEL" value="x" ng-model="purchaseOrder.deletebundleItem" ng-click="removeBundleItem(item,$parent.$index,$index);"/>
				 </td>
            </tr>
          </table>
		  
		</div>
		<!-- bundle group end -->
		  
    </div>
      <div class="clr"></div>
      <div style="width:10%; float:right;">
        <label>
            <input class="button" type="button" name="Add" id="iAdd" value="Add Bundle" ng-model="purchaseOrder.addbundle"  tabindex="39" ng-click="addBundle()"/>
        </label>
      </div><div class="clr"></div>

      <div style="width:25%; float:right;">
        <label><span>Grand Total:</span>
            <input name="poVal" type="text" id="ipoVal" value="0" size="7" ng-model="purchaseOrder.poVal"  readOnly/>
        </label>
      </div><div class="clr"></div>


      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
                <label>
                    <span>P&amp;F Charge(%):</span><input type="text" name="pf_chrg"   id="ipf_chrg" is-number  ng-model="purchaseOrder.pf_chrg"  tabindex="39"  />
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Incidental Charges(%):</span><input type="text" name="inci_chrg" id="iinci_chrg" is-number ng-model="purchaseOrder.inci_chrg" value="filled by user"  tabindex="40" />
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:100%; float:left;">
                <label>
                    <span>Comments:</span><textarea name="remarks" id="irem"  tabindex="41" ng-model="purchaseOrder.rem"></textarea>
                </label>
          </div>
          <div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
          <div style="width:100%; float:left;">
                <label>
                    <span>Freight Tag:</span>
                    <select name="frgt" id="ifrgt" ng-model="purchaseOrder.frgt1" ng-change="getFreight();"  tabindex="42">
                    <option value="">Select Freight Tag</option>
                        <?php
			                $result =  ParamModel::GetParamList('CHARGE','FREIGHT');
			                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                      echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                        }?>
                      </select>
                </label>
          </div>
          <div class="clr"><input type="hidden" name="frgt" id="ifrgt"  ng-model="purchaseOrder.frgt" /></div>
          <div id="dfrgp" style="width:50%; float:left;display: none;">
                <label>
                    <span>Freight (%):</span><input type="text" name="frgp" id="ifrgp"  tabindex="43" is-number ng-model="purchaseOrder.frgtp" />
                </label>
          </div>
          <div id="dfrga" style="width:50%; float:left;display:none;">
                <label>
                    <span>Freight Amount:</span><input type="text" name="frga" id="ifrga"  tabindex="43" is-number ng-model="purchaseOrder.frgta" />
                </label>
          </div>
		  <div id="bmail" style="width:50%; float:left;">
                <label>
                    <span>Buyer Email (Order confirmation will be received here):</span>
					   <input type="text" name="bemailId" id="bemailId" ng-model="purchaseOrder.bemailId" tabindex="45" value="" class="input1" required/>
					
                </label>
          </div>
          <div class="clr"></div>

      </div>
      <div class="clr"></div>
	  <?php if(isset($_SESSION['USER_TYPE']) && $_SESSION['USER_TYPE']=='E' ){  ?>
	  
      <div align="center">
       

        <input class="button" type="button" name="button4" id="button4" value="Cancel" ng-model="purchaseOrder.GoForCancel" ng-click="GotoCancel()">
      </div>
	  
	  <?php } else {  ?>
	  <div align="center">
        <input class="button" type="submit" name="b1" id="btnsave" value="Save"  tabindex="44">
        <input class="button" type="button" name="b2" id="btnupdate" value="Update" ng-click="UpdatePO();" tabindex="44">

        <input class="button" type="button" name="b3" id="fbt" value="Management Approval" ng-model="purchaseOrder.GoForApproval" ng-click="Goto(<?php echo($_GET['POID']) ?>);">

        <!--<input class="button" type="button" name="button4" id="button4" value="Cancel"> -->
      </div>
	  <?php }  ?>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#ipod').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#ipovd').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#ideldate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

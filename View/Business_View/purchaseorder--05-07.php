<?php
include('root.php');
include($root_path."GlobalConfig.php");
include($root_path."Model/ReportModel/Report1Model.php");
?>
<?php include("../../header.php") ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Purchase Order</title>
<script> 
//var redirect_url = '<?php print SITE_URL; ?>';

</script>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/po.js'></script>

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
<body ng-app="po_app">

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

    <div align="left" valign="top" class="fhead">&nbsp;&nbsp;Purchase Order Form</div>
<form id="form1"  ng-controller="po_Controller" ng-submit="AddPO();" data-ng-init="init('<?php echo($_GET['POID']) ?>')" class="smart-green">

<input type="hidden" name="poId" id="ipoId" ng-model="purchaseOrder.poid"  value="<?php echo($POID) ?>" >


<div >
 <div style="width:50%; float:left;">
  <div style="width:98%; float:left;">
   <label>
    <span>Buyer Name*:</span><hidden id="buyerid" ng-model="purchaseOrder.bn"></hidden><input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Buyer Name" ng-model="purchaseOrder.bn_name" onKeyPress="loadBuyerById(this.value);" required/>
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
                    <label>
                        <span>Purchase Order Type*:</span>
                        <select name="pot" id="ipot" tabindex="5" ng-model="purchaseOrder.pot" ng-change="showQtyDelivery();" required>
                        <option value="">Select Purchase Order Type</option>
                          <?php
			                      $result =  ParamModel::GetParamList('PO','TYPE');
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
									  if($row['PARAM1'] !="B"){									  
			                           echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
								   }
                                  }?>
                         </select>
                    </label>
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
           <label><span>Purchase Order Detail Start</span></label>
    </div><div class="clr"></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
        <table width="200%" border="0">
            <tr>
              <td width="2%">Sr. No.</td>
              <td width="5%" align="center">Quotation Number</td>
              <td width="14%" align="center">Principal*</td>
              <td width="3%" align="center">Part No*.</td>
              <td width="6%" align="center">Identification Mark</td>
              <td width="15%" align="center">Item Description</td>
              <td width="10%" align="center">Buyer Item Code</td>
              <td width="3%"><div align="center">Quantity*</div></td>
              <td width="2%" align="center">Unit </td>
              <td width="5%" align="center">Price</td>
              <td width="3%" align="center">Discount(%)</td>
			  <!-- BOF for adding GST by Ayush Giri on 16-06-2017 -->
              <!--<td width="8%" align="center">ED Applicable</td>
              <td width="11%" align="center">Sale Tax (%)</td> -->
			  <td width="4%" align="center">HSN Code</td>
			  <td width="4%" align="center">CGST Rate(%)</td>
			  <td width="4%" align="center">CGST Amt</td>
			  <td width="4%" align="center">SGST Rate(%)</td>
			  <td width="4%" align="center">SGST Amt</td>
			  <td width="4%" align="center">IGST Rate(%)</td>
			  <td width="4%" align="center">IGST Amt</td>
			  <!-- EOF for adding GST by Ayush Giri on 16-06-2017 -->
              <td width="4%" align="center">Delivery by Date</td>
              <td width="5%" align="center">Taxable Value</td>
			  <td width="5%" align="center">Total Value</td>
              <td width="6%">&nbsp;</td>
            </tr>

            <tr>
			  <td></td>
              <td><select name="quotNo" id="iquotNo"   ng-model="purchaseOrder.po_quotNo" ng-click="showPOPrincipal();" style="width:150px;" tabindex="24"></select></td>
              <td><select name="principalId" id="iprincipalId" class="input1"  ng-model="purchaseOrder.po_principalId" ng-change="showPOCodePartNo(); getDiscount();" style="width:350px;" tabindex="25">
              <option value="" selected>Select Principal</option>
                           <?php include( "../../Model/Business_Action_Model/po_model.php");
			                  $result = purchaseorder_Details_Model::showPOPrincipalSupplier('PRINCIPAL_SUPPLIER_MASTER','0','P');
			                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                       echo "<option value='".$row['PID']."'>".$row['PNAME']."</option>";
                              }?>
              </select></td>
              <td><input name="codePartNo" type="hidden" id="icodePartNo" ng-model="purchaseOrder.po_codePartNo"></hidden>
                  <input name="cPartNo" type="text"  id="autocomplete-ajax-CodePartNo" ng-model="purchaseOrder.cPartNo" style="width:60px;" tabindex="26"  ></td>
              <td><input name="iden_mark" type="text" id="iden_mark" ng-model="purchaseOrder.iden_mark" value="" style="width:135px;" tabindex="27" readonly/></td>
              <td><input name="item_desc" type="text" id="item_desc" ng-model="purchaseOrder.item_desc" value="" style="width:365px;" tabindex="28"/></td>
              <td><input name="bitemcode" type="text" id="ibitemcode" ng-model="purchaseOrder.po_buyeritemcode" style="width:250px;" tabindex="29"/></td>
              <td><input name="qty" type="text" id="iqty" ng-model="purchaseOrder.po_qty"  value="filled by user" size="4" ng-change="calculateValue();" style="width:65px;"  tabindex="30"/></td>
              <td><hidden id="unitId" ng-model="purchaseOrder.po_unitid"/>
              <input type="text" name="unit" id="iunit" ng-model="purchaseOrder.po_unit" style="width:50px;"  tabindex="3111"/></td>
              <td><input name="price" type="text" id="iprice" ng-model="purchaseOrder.po_price" ng-change="calculateValue();checkPrice();" tabindex="32" style="width:115px;" /></td>
              <td><input name="discount" type="text" id="idiscount" ng-model="purchaseOrder.po_discount" style="width:65px;" tabindex="33" value=""  ng-change="calculateValue(); checkDiscount();" /></td>
			  <!-- BOF for adding GST by Ayush Giri on 16-06-2017 -->
              <!-- <td>
			  <select name="edapp" id="iedapp" class="input1"   tabindex="34" ng-model="purchaseOrder.po_ed_applicability" style="width:200px;" ng-change="getText2();"><option value="">Select Applicability</option>
                <?php
			        /*$result =  ParamModel::GetParamList('EXCISEDUTY','APPLICABLE');
			        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			              echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                    }*/
					?>
              </select>
			  </td>
              <td><select name="salestax" id="isalestax" ng-model="purchaseOrder.po_saleTax" ng-change="getText3();" style="width:275px;" tabindex="35">

              </select>
			  </td>
			   -->
			   <td id="td_hsn">
                <input name="hsn_code" type="text" id="hsn_code"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_hsn_code" readonly=""/>
              </td>
			  <td id="td_cgst">
                <input name="cgst_rate" type="text" id="cgst_rate"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_cgst_rate" readonly=""/>
              </td>
			  <td id="td_cgst_amt">
                <input name="cgst_amt" type="text" id="cgst_amt"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_cgst_amt" readonly=""/>
              </td>
			  <td id="td_sgst">
                <input name="sgst_rate" type="text" id="sgst_rate"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_sgst_rate" readonly=""/>
              </td>
			  <td id="td_sgst_amt">
                <input name="sgst_amt" type="text" id="sgst_amt"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_sgst_amt" readonly=""/>
              </td>
			  <td id="td_igst">
                <input name="igst_rate" type="text" id="igst_rate"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_igst_rate" readonly=""/>
              </td>
			  <td id="td_igst_amt">
                <input name="igst_amt" type="text" id="igst_amt"  tabindex="34" style="width:100px;" ng-model="purchaseOrder.po_igst_amt" readonly=""/>
              </td>
			  <!-- EOF for adding GST by Ayush Giri on 16-06-2017 -->
              <td id="dbd">
                <input name="deldate" type="text" id="ideldate"  tabindex="36" style="width:100px;" ng-model="purchaseOrder.po_deliverybydate"/>
              </td>
              <td id="dbd1" style="display:none">
                <input name="deldate1" type="text" id="ideldate1"  tabindex="36" style="width:100px;" disabled/>
              </td>
              <td><input name="totVa" type="text" id="itotVal"  ng-model="purchaseOrder.po_taxable_amt"  tabindex="37" style="width:105px;" /></td>
			  <!-- BOF for adding GST by Ayush Giri on 19-06-2017 -->
			  <td>
			  <input name="finVa" type="text" id="ifinVal"  ng-model="purchaseOrder.po_finVal"  tabindex="37" style="width:105px;" />
			  </td>
			  <!-- EOF for adding GST by Ayush Giri on 19-06-2017 -->
              <td>
              <input name="bpod_Id" type="hidden" id="ibpod_Id" ng-model="purchaseOrder.bpod_Id"  readonly/>
              <input name="eda1" type="hidden" id="ieda1" ng-model="purchaseOrder.eda1" size="20" readonly/>
              <input name="pname" type="hidden" id="ipname" ng-model="purchaseOrder.pname" size="20" readonly/>
              <input name="sTax" type="hidden" id="sTax" ng-model="purchaseOrder.sTax" size="20" readonly/>
              <input name="odiscount" type="text" id="iodiscount" ng-model="purchaseOrder.po_odiscount" style="display:none;width:65px;" />
              <input name="po_oprice" type="hidden" id="ioprice" ng-model="purchaseOrder.po_oprice" style="width:115px;"  />
              <input name="po_price_category" type="hidden" id="ipo_price_category" ng-model="purchaseOrder.po_price_category"  style="width:40px;"  />
              <input name="po_discount_category" type="hidden" id="ipo_discount_category" ng-model="purchaseOrder.po_discount_category"  style="width:40px;"  />
              </td>
            </tr>

             <tr id="poRow" ng-repeat="item in purchaseOrder._items" >
			 <td>{{$index + 1}}</td>
             <td><input type="text" ng-model="item.po_quotNo" style="width:138px;" /></td>
             <td><input type="text" ng-model="item.pname"  style="width:338px;" /><input type="hidden" ng-model="item.po_principalId" /></td>
             <td><input type="text" ng-model="item.cPartNo" style="width:60px;"  /><input type="hidden" ng-model="item.po_codePartNo"  style="width:50px;"/></td>
             <td><input type="text" ng-model="item.iden_mark" style="width:135px;" /></td>
             <td><input type="text" ng-model="item.item_desc" style="width:365px;" /></td>
             <td><input type="text" ng-model="item.po_buyeritemcode" style="width:250px;" /></td>
             <td><input type="text" ng-model="item.po_qty" ng-change="RowChangeEvent(item);"            style="width:65px;"  /></td>
             <td><input type="text" ng-model="item.po_unit" style="width:50px;"  /><input type="hidden" ng-model="item.unit_id" /></td>
             <td><input type="text" ng-model="item.po_price" ng-change="RowChangeEvent(item);"          style="width:115px;" /></td>
             <td><input type="text" ng-model="item.po_discount" ng-change="RowChangeEvent(item);"       style="width:65px;"  /></td>
             <!--<td>
				<select class="input1" ng-model="item.po_ed_applicability" style="width:200px;" ng-change="getText2();"><option value="">Select Applicability</option>
                <?php
			       /* $result =  ParamModel::GetParamList('EXCISEDUTY','APPLICABLE');
			        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			              echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                    }*/
					?>
              </select>
				 
				<input type="hidden" ng-model="item.po_ed_applicability"/></td>
			 
             <td>
			 <?php 	       
				// $result = purchaseorder_Details_Model::getSalesTaxNew();	 
			 ?>
				<select name="salestax" id="isalestax" ng-model="item.po_saleTax" ng-change="getText3();" style="width:275px;">
					<?php								
					/* foreach($result as $saletax) {
						echo "<option value=".$saletax->sTax.">" .$saletax->po_saleTax."</option>";
                    } */				   
					?>			
				</select>
			
				<input type="hidden" ng-model="item.po_saleTax"  style="width:50px;"/>
			</td>-->
			<td><input type="text" ng-model="item.po_hsn_code"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_cgst_rate"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_cgst_amt"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_sgst_rate"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_sgst_amt"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_igst_rate"  style="width:100px;" /></td>
			<td><input type="text" ng-model="item.po_igst_amt"  style="width:100px;" /></td>
             <td><input type="text" ng-model="item.po_deliverybydate"  style="width:100px;" /></td>
             <td><input type="text" ng-model="item.po_taxable_amt"          style="width:105px;" /></td>
			 <td><input type="text" ng-model="item.po_finVal"          style="width:105px;" /></td>
             <td><input type="hidden" ng-model="item.po_odiscount"       style="width:65px;"  />
                 <input type="hidden" ng-model="item.po_oprice"          style="width:115px;" />
                 <input type="hidden" ng-model="item.po_price_category"   style="width:40px;" />
                 <input type="hidden" ng-model="item.po_discount_category"   style="width:40px;" />
                 <hidden ng-model="item.bpod_Id" />
                 <input type="button" name="DEL" id="idel" value="x" ng-model="purchaseOrder.deleteitem" ng-click="removeItem(item)" style="width:50px;" />
             </td>
            </tr>
          </table>
    </div>
      <div class="clr"></div>
      <div style="width:5%; float:right;">
        <label>
            <input class="button" type="button" name="Add" id="iAdd" value="Add" ng-model="purchaseOrder.additem"  tabindex="38" ng-click="addItem()"/>
        </label>
      </div><div class="clr"></div>

<div style="width:50%; float:left;">         
     
          <div align="center" style="width:100%;">
            <label><span>
            <input type="checkbox" name="checkbox4" id="checkbox4"  ng-model="purchaseOrder.po_hold_state" ng-change="poHoldReason();"></input>ON HOLD (checked if PO Create In Hold State)</span>
            </label>
          </div><div class="clr"></div>
          <div style="width:100%; float:left; display:none;" id="hold_reason">
            <label>
                <span>PO Hold Reason:</span>
                <textarea class="FormElement" style="height: 40px;" textarea name="po_hold_reason" id="po_hold_reason" ng-model="purchaseOrder.po_hold_reason" cols="40" rows="4"></textarea>
            </label>
          </div>
          
          <div class="clr"></div>
      </div>


      <div style="width:25%; float:right;">
        <label><span>Grand Total:</span>
            <input name="poVal" type="text" id="ipoVal" value="" size="7" ng-model="purchaseOrder.poVal"  readOnly/>
        </label>
      </div><div class="clr"></div>


      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
                <label>
                    <span>P&amp;F Charge (%):</span><input type="text" name="pf_chrg"   id="ipf_chrg" is-number  ng-model="purchaseOrder.pf_chrg"  tabindex="39" ng-change="getLanding_Price()"  />
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Incidental Charges (%):</span><input type="text" name="inci_chrg" id="iinci_chrg" is-number ng-model="purchaseOrder.inci_chrg" value="filled by user"  tabindex="40" ng-change="getLanding_Price()" />
                </label>
          </div>
          <div class="clr"></div>
		  <!-- BOF for adding GST by Ayush Giri on 22-06-2017 -->
		  <div style="width:50%; float:left;">
                <label>
                    <span>Insurance Charge (%):</span><input type="text" name="ins_charge"   id="ins_charge" is-number  ng-model="purchaseOrder.ins_charge"  tabindex="39" ng-change="getLanding_Price()"  />
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Other Charges (%):</span><input type="text" name="othc_charge" id="othc_charge" is-number ng-model="purchaseOrder.othc_charge" value="filled by user"  tabindex="40" ng-change="getLanding_Price()" />
                </label>
          </div>
          <div class="clr"></div>
		  <!-- EOF for adding GST by Ayush Giri on 22-06-2017 -->
          <div style="width:100%; float:left;">
                <label>
                    <span>Comments:</span><textarea name="remarks" id="irem" style="height: 60px;" tabindex="41" ng-model="purchaseOrder.rem"></textarea>
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
                    <span>Freight (%):</span><input type="text" name="frgp" id="ifrgp"  tabindex="43" is-number ng-change="getLanding_Price()" ng-model="purchaseOrder.frgtp" />
                </label>
          </div>
          <div id="dfrga" style="width:50%; float:left;display:none;">
                <label>
                    <span>Freight Amount:</span><input type="text" name="frga" id="ifrga"  tabindex="43" ng-change="getLanding_Price()" is-number ng-model="purchaseOrder.frgta" />
                </label>
          </div>
		  <div id="bmail" style="width:50%; float:left;">
                <label>
                    <span>Buyer Email (Order confirmation will be received here):</span>
					   <input type="text" name="bemailId" id="bemailId" ng-model="purchaseOrder.bemailId" tabindex="45" class="input1" required/>
					
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

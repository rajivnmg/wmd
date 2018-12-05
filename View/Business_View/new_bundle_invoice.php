<?php
include('root.php');
include($root_path."GlobalConfig.php");
include($root_path."Model/ReportModel/Report1Model.php");
?>
<?php include("../../header.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Bundle Invoice</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/bundle_invoice.js'></script>

<link href="../css/pop.css" media="all" rel="stylesheet" type="text/css"/>

<script language="javascript" src="../js/jquery.pop.js" type="text/javascript"></script>
<script type='text/javascript'>
    $(document).ready(function(){
    $.pop();
    });
</script>

</head>
<body ng-app="Outgoing_Bundle_Invoice_NonExcise_App">

<form id="form1" ng-controller="Bundle_Controller" ng-submit="AddOutgoingBundleInvoiceExcise();" data-ng-init="init('<?php echo $_GET['bundle'];?>');" class="smart-green">
<div>
<div align="center"><h1>Bundle Invoice Form</h1></div>
      <div style="width:50%; float:left;">
			<div style="width:50%; float:left;">
                    <label>
                        <span>Through:</span>
                        <select name="partno_3" id="partno_3" ng-model="outgoing_bundle_invoice_nonexcise.mode_delivery" required>
                        <option value="">Select Through</option>
                        <?php
                          include( "../../Controller/Param/Param.php");
                          $result =  Param::GetParamList("DELIVERY","MODE");
									while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
									echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                          }?>	
                          </select>
                    </label>
            </div><div class="clr"></div>
      </div>
	   <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label><span>Market segment*:</span>
       
           <select name="marketsegment" id="marketsegment" tabindex="9" ng-model="outgoing_bundle_invoice_nonexcise.ms" style=" z-index: 2; background: transparent;" required>
           <option value="">Select One</option>
               <?php  include( "../../Model/Masters/MarketSegment_Model.php");
			                      $result =  MarketSegmentModel::GetMsList();
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['msid']."'>".$row['msname']."</option>";
                  }?>
            </select> 
            </div><div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
            <div style="width:80%; float:left;">
                    <label>
                        <span>Buyer:</span>
                        <hidden id="buyerid" ng-model="outgoing_bundle_invoice_nonexcise.BuyerID"></hidden>
            <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width: 110%;" tabindex="1" placeholder="Type Buyer Name" ng-model="outgoing_bundle_invoice_nonexcise.bn_name" readonly/>
                      <!--<select name="buyer_no_2" id="buyer_id" ng-model="outgoing_bundle_invoice_nonexcise.BuyerID" required>
                          <option value="">Select Buyer</option>
                          <?php
                              include( "../../Model/Masters/BuyerMaster_Model.php");
                              $result =  BuyerMaster_Model::GetBuyerList();
                              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                                  echo "<option value='".$row['BuyerId']."'>".$row['BuyerName']."</option>";
                              }?>
                          </select> from PO basis on Order No. &amp; DISABLED -->
                    </label>
            </div><div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
	  
	   <?php
	  if(isset($_GET['outgoing_bundle_invoice_nonexcise']) && ($_GET['outgoing_bundle_invoice_nonexcise'] !='')){
           echo'<div style="width:50%; float:left;">
                    <label>
                        <span>Invoice Number:</span>
                        <input type="text" id="txt_outgoing_invoice_num"  ng-model="outgoing_bundle_invoice_nonexcise.oinvoice_No" required readonly/>
                    </label>
            </div>';
		}else{
			echo'<input type="hidden" id="txt_outgoing_invoice_num"  ng-model="outgoing_bundle_invoice_nonexcise.oinvoice_No" required readonly/>';
		}
			?>  
        
            <div style="width:50%; float:left;">
                    <label>
                        <span>Invoice Date:</span>
                        <input name="inv_no_p" type="text" id="inv_date" ng-model="outgoing_bundle_invoice_nonexcise.oinv_date" placeholder="yyyy-mm-dd" readonly required/>
                    </label>
            </div><div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
            <div style="width:70%; float:left;">
                    <label>
                        <span>Principal:</span>
                        <select name="buyer_no_3" id="principal_list" ng-model="outgoing_bundle_invoice_nonexcise.principalID" ng-change="getprincipal();" required><option value="">Select Principal</option></select><!--  List from PO -->
                    </label>
            </div><div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Purchase Order Number:</span>
                         <input name="bpoType" type="text" id="bpoType" ng-model="outgoing_bundle_invoice_nonexcise.pot" style="display: none"/><!-- added by aksoni for store bpoType of PO-->
                         <input name="freightTag" type="text" id="freightTag" ng-model="outgoing_bundle_invoice_nonexcise.frgt" style="display:none"/><!-- added by aksoni for store freightTag of PO 09/04/2015-->
                        <hidden id="PurchaseOrderid" ng-model="outgoing_bundle_invoice_nonexcise.poid"></hidden>
            <input type="text" name="PurchaseOrder_name" id="autocomplete-ajax-PurchaseOrder" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Purchase Order Name" ng-model="outgoing_bundle_invoice_nonexcise.pono" onKeyPress="loadPoByNumber(this.value);" required/>
                        <!-- <select name="select" id="invoice_no" ng-change="GetPurchaseOrderDetails();"  ng-model="outgoing_bundle_invoice_nonexcise.pono" required><option value="">Select Purchase Order Number</option>
                          <?php
                              $result =  Param::GetPurchaseOrderForBilling();
                              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                                  echo "<option value='".$row['bpoId']."'>".$row['bpono']."</option>";
                              }?>
                          </select> -->
                    </label>
            </div>
            <div style="width:50%; float:left;">
                    <label>
                        <span>Purchase Order Date:</span>
                        <input name="inv_no_p7" type="text" id="inv_po_date" ng-model="outgoing_bundle_invoice_nonexcise.po_date" placeholder="yyyy-mm-dd" required/>
                    </label>
            </div><div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div align="center">
           <label><span>Bundle Invoice Detail</span></label>
      </div><div class="clr"></div>
      <div style="width:100%; float:left; height:300px; overflow:scroll;" id="invDetail_temp">
            <table width="100%" border="1">
                <tr>
                
                  <td width="13%"><label><span>Item Code Part No.*</span></label></td>
                  <td width="20%"><div align="center"><label><span>Description</span></label></div></td>
                  <td width="10%"><label><span>Ordered Quantity</span></label></td>
                  <td width="10%"><label><span>Balance Quantity</span></label></td>
				  <td width="10%"><div align="center"><label><span>Bill Quantity*</span></label> </div></td>
                  <td width="10%"><div align="center"><label><span>Rate*</span></label></div></td>
                  <td width="11%"><div align="center"><label><span>Amount</span></label></div></td>
                <td width="6%">&nbsp;</td>
                </tr>
               
                
              </table>
      </div>
	  		<div style="border: 2px solid #0000FF;" ng-repeat="bundle in outgoing_bundle_invoice_nonexcise.bundles">
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
				  <td><input name="ibqty" type="text" id="ibiqty" ng-model="bundle.ibpo_qty"  value="filled by user" size="4" ng-change="calculateBundleValue();" tabindex="25" readOnly /></td>
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
				  <td><input name="ibprice" type="text" id="ibiprice" ng-model="bundle.ibpo_price" ng-change="calculateBundleValue();checkPrice();" tabindex="26" readOnly /></td>
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
				 <td><input name="ibtotVa" type="text" id="ibitotVal"  ng-model="bundle.ibpo_totVal"  tabindex="29" readOnly /></td>
				 <td>
				 <input type="button" name="DEL" id="bidel" value="xx" ng-model="outgoing_bundle_invoice_nonexcise.deletebundle" ng-click="removeBundle(bundle)" style="background-color:red;"/>
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
				  <td align="center" style="width:25%;">Item Description</td>				 
				  <td align="center" style="width:5%;">Order Qty*</td>
				  <td align="center" style="width:5%;">Balance Qty</td>
				  <td align="center" style="width:5%;">Issued Qty</td>
				  <td align="center" style="width:10%;">Unit </td>
				  <td align="center" style="width:10%;">Landing Price</td>
				  <td align="center" style="width:10%;">Total Value</td>
				  <td style="width:10%;">&nbsp;</td>
            </tr>
       
       

             <tr id="poRow" ng-repeat="item in bundle.items" >
				 <td><input type="text" ng-model="item.cPartNo"  /><input type="hidden" ng-model="item.po_codePartNo" ></td>
				 <td><input type="text" ng-model="item.iden_mark" readOnly /></td>
				 <td><input type="text" ng-model="item.item_desc" readOnly /></td>
				 <td><input type="text" ng-model="item.po_qty" ng-change="RowChangeEvent(item);" readOnly /></td>
				 <td><input type="text" ng-model="item.balance_qty" readOnly /></td>
				 <td><input type="text" ng-model="item.issued_qty"></td>
				 <td><input type="text" ng-model="item.po_unit" readOnly /><input type="hidden" ng-model="item.unit_id" /></td>
				 <td><input type="text" ng-model="item.po_price" readOnly /></td>
				 <td><input type="text" ng-model="item.po_totVal" value="{{ item.po_price * item.issued_qty }}" readOnly /></td>
				 <td><input type="hidden" ng-model="item.po_odiscount"/>
					 <input type="hidden" ng-model="item.po_oprice" />
					 <input type="hidden" ng-model="item.po_price_category"/>
					 <input type="hidden" ng-model="item.po_discount_category"/>
					 <hidden ng-model="item.bpod_Id" />
					 <input type="button" name="DEL" value="x" ng-model="outgoing_bundle_invoice_nonexcisedeletebundleItem" ng-click="removeBundleItem(item,$parent.$index,$index);"/>
				 </td>
            </tr>
          </table>
		  
		</div>
		<!-- bundle group end -->
	  
      <div class="clr"></div>
      <div style="width:5%; float:right;">

      </div><div class="clr"></div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
                <label>
                    <span>Discount:</span>
                    <input name="dt_p4" type="text" id="dt_p4" ng-model="outgoing_bundle_invoice_nonexcise.total_discount" placeholder="Discount" required/>
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Tax:</span>
                    <input name="partno_" id="partno_" type="text" ng-model="outgoing_bundle_invoice_nonexcise.total_saleTax" placeholder="Tax Charges" required/>
                    <select name="select2" id="txtsaletax"  ng-model="outgoing_bundle_invoice_nonexcise.po_saleTax"  required>
                        <option value="">Select Tax</option>
                      <?php
                      $result =  Param::GetVATCSTList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                  echo "<option value='".$row['SALESTAX_ID']."'>".$row['SALESTAX_DESC']."</option>";
                      }?>
                    </select>
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:50%; float:left;">
                <label>
                    <span>P &amp; F Charge:</span>
                    <input name="dt_p5" type="text" id="txtpf_charg" ng-model="outgoing_bundle_invoice_nonexcise.pf_chrg" placeholder="P & F  Charges" required/>
                    <input type="hidden" id="txtpf_charg_percent" ng-model="outgoing_bundle_invoice_nonexcise.pf_chrg_percent" style="display:block">
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Incidental Charges:</span>
                    <input name="inv_no_p3" type="text" id="txtincidental_chrg" ng-model="outgoing_bundle_invoice_nonexcise.incidental_chrg" placeholder="Incidental Charges" required/>
                    <input type="hidden" id="txtincidental_chrg_percent" ng-model="outgoing_bundle_invoice_nonexcise.incidental_chrg_percent" style="display:block">
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Freight (%):</span>
                    <input name="dt_p6" type="text" id="txtfreight_percent" ng-model="outgoing_bundle_invoice_nonexcise.fright_percent" placeholder="Freight (%)" required/>
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Freight Amount:</span>
                    <input name="inv_no_p4" type="text" id="txtfreight_amount" ng-model="outgoing_bundle_invoice_nonexcise.freight_amount" placeholder="Freight Amount" required/>
                </label>
          </div>
          <div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:right;">
                <label>
                    <span>Total Amount:</span>
                    <input type="text" name="inv_no_p5" id="inv_no_p5"  ng-model="outgoing_bundle_invoice_nonexcise.bill_value" placeholder="0.00" required/>
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:50%; float:right;">
                <label>
                    <span>Comment's:</span>
                    <textarea name="comm" id="comm" ng-model="outgoing_bundle_invoice_nonexcise.remarks" placeholder="Maximum Char 300." ></textarea>
                </label>
          </div>
		  <div id="bmail" style="width:30%; float:left;">
                <label>
                    <span>Buyer Email (Dispatch Information will be received here):</span>
					   <input type="text" name="bemailId" id="bemailId" ng-model="outgoing_bundle_invoice_nonexcise.bemailId" tabindex="45" value="" class="input1" required/>
					
                </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div align="center">
        <input class="button" type="submit" name="button3" id="btnsave"  value="Save"/>
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_BUNDLE_INVOICE; ?>">Cancel</a></span>
        <!-- <input class="button" type="button" name="button3" id="button3" value="Cancel"/> -->
        <input type="button" class="button" name="button3" id="btnupdate" ng-click="Update();" value="Update"/>
        <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINT_BUNDLE_INVOICE.'?TYP=SELECT&bundle='.$_GET['bundle']; ?>">Print View</a>
        <!-- <input class="button" type="button" name="button3" id="button3" value="Print"/> -->
      </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#inv_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d'
});
$('#inv_po_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d'
});
</script>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

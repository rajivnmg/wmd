<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php");
 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Incoming Invoice With Excise Duty</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/incoming_invoice_excise.js'></script>
</head>
<body  ng-app="Incoming_Invoice_Excise_App">

<form id="form1" ng-controller="Incoming_Invoice_Excise_Controller" ng-submit="AddIncomingInvoiceExcise('<?php echo  $_GET['IncomingInvoiceExciseNum'];?>');" data-ng-init="init('<?php echo  $_GET['IncomingInvoiceExciseNum'];?>');"  class="smart-green">
<div>
   <hidden id="_invoiceid" ng-model="incoming_invoice_excise._invoiceid"></hidden>
   <div align="center"><h1>Incoming Invoice Form</h1></div>
   <?php
   if(isset($_GET['IncomingInvoiceExciseNum']) && ($_GET['IncomingInvoiceExciseNum'] !='')){
  echo' <div style="width:25%; float:left;">
        <label>
            <span>Entry ID:</span>
            <input type="text" id="invoice_intry_id" ng-model="incoming_invoice_excise._entry_Id" placeholder="Entry Number" readonly required></input>
        </label>
   </div>
     <div style="width:25%; float:left;">
        <label>
            <span>Entry Number:</span>
            <input type="text" id="invoice_mapping_id" ng-model="incoming_invoice_excise._mapping_Id" placeholder="Entry Number" readonly required></input>
        </label>
    </div>';
	}else{
		  echo'<div style="width:25%; float:left;">
        <label>
           <input type="hidden" id="invoice_intry_id" ng-model="incoming_invoice_excise._entry_Id" placeholder="Entry Number" readonly required></input>
        </label>
   </div>
     <div style="width:25%; float:left;">
        <label>           
            <input type="hidden" id="invoice_mapping_id" ng-model="incoming_invoice_excise._mapping_Id" placeholder="Entry Number" readonly required></input>
        </label>
    </div>';
	
	}
	?>
   <div class="clr"></div>
	<div style="width:25%; float:left;">
        <label>
            <span>Mode of Transport:</span>
            <select name="select3" id="select3"  ng-model="incoming_invoice_excise._mode_delivery" required>
            <option value="">Select Mode of Transport</option>
              <?php
              include( "../../Controller/Param/Param.php");
              $result =  Param::GetParamList("DELIVERY","MODE");
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                  echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
              }?>
            </select>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Vehicle Number:</span>
            <input type="text" id="vehcle_no"  ng-model="incoming_invoice_excise._vehcle_no" placeholder="Vehicle Number"></input>
        </label>
    </div>
	<div style="width:25%; float:left;">
        <label>
            <span>Date & Time of Supply:</span>
            <input type="text" id="dnt_supply"  ng-model="incoming_invoice_excise._dnt_supply" placeholder="dd/mm/yyyy hh:mm"></input>
        </label>
    </div>
	<div style="width:25%; float:left;">
        <label>
            <span>Place of Supply:</span>
            <input type="text" id="supply_place"  ng-model="incoming_invoice_excise._supply_place" placeholder="Place of Supply"></input>
        </label>
    </div>
    <div class="clr"></div>
	<div style="width:25%; float:left;">
        <label>
            <span>Tax payable on Reverse Charge?:</span>
			<select name="reverse_charge_payable" id="reverse_charge_payable" tabindex="9" ng-model="incoming_invoice_excise._reverse_charge_payable" style=" z-index: 2; background: transparent;" required>
				<option value="">Select One</option>
				<option value="1">Yes</option>
				<option value="0">No</option>
            </select> 
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
        <div style="width:100%; float:left;"><label><span>Manufacturer Detail </span></label></div><div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Invoice Number:</span>
                <input type="text" id="principal_invoice_no"  ng-model="incoming_invoice_excise._principal_inv_no" placeholder="Invoice Number" ng-keyup="validateInvoiceNumber();" required></input>
            </label>
        </div>
        <div style="float:left;">
            <label>
                <span>Date:</span>
                <input type="text" id="principal_invoice_date"  ng-model="incoming_invoice_excise._principal_inv_date" placeholder="dd/mm/yyyy" required></input>
            </label>
        </div><div class="clr"></div>
        <div style="width:69%; float:left;">
            <label>
                <span>Principal:</span>
                <input type="text" name="principal_name" id="autocomplete-ajax-principal" style=" z-index: 2; background: transparent;" placeholder="Type Principal Name" ng-model="incoming_invoice_excise._principal_name" required/>
				<hidden id="principalid" ng-model="incoming_invoice_excise._principal_Id"></hidden>
				<hidden id="principal_address" ng-model="incoming_invoice_excise._principal_address"></hidden>
				<hidden id="principal_city" ng-model="incoming_invoice_excise._principal_city"></hidden>
				<hidden id="principal_state" ng-model="incoming_invoice_excise._principal_state"></hidden>
            </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>GSTIN:</span>
                <input type="text" name="principal_gstin" id="principal_gstin" placeholder="GSTIN" ng-model="incoming_invoice_excise._principal_gstin" readonly="" required/>
            </label>
        </div>
		<div class="clr"></div>
		<div style="float:left;">
            <label><span>Market segment*:</span>
			<select name="marketsegment" id="marketsegment" tabindex="9" ng-model="incoming_invoice_excise.ms" style=" z-index: 2; background: transparent;" required>
           <option value="">Select One</option>
               <?php  include( "../../Model/Masters/MarketSegment_Model.php");
			                      $result =  MarketSegmentModel::GetMsList();
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['msid']."'>".$row['msname']."</option>";
                  }?>
            </select> 
        </div>
		<div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
        <div style="width:100%; float:left;"><label><span>Supplier Detail </span></label></div><div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Invoice Number:</span>
                <input type="text" id="supplier_invoice_no"  ng-model="incoming_invoice_excise._supplier_inv_no" placeholder="Invoice Number"></input>
            </label>
        </div>
        <div style="float:left;">
            <label>
                <span>Date:</span>
                <input type="text" id="supplier_invoice_date"  ng-model="incoming_invoice_excise._supplier_inv_date" placeholder="dd/mm/yyyy"></input>
            </label>
        </div><div class="clr"></div>
        <div style="width:75%;float:left;">
            <label>
                <span>Supplier:</span>
                <input type="text" name="country" id="autocomplete-ajax-supplier" style=" z-index: 2; background: transparent;" placeholder="Type Supplier Name"  ng-model="incoming_invoice_excise._supplier_name"/>
				<hidden id="supplierid" ng-model="incoming_invoice_excise._supplier_Id"></hidden>
                <hidden id="supplier_address" ng-model="incoming_invoice_excise._supplier_address"></hidden>
				<hidden id="supplier_city" ng-model="incoming_invoice_excise._supplier_city"></hidden>
				<hidden id="supplier_state" ng-model="incoming_invoice_excise._supplier_state"></hidden>
            </label>
        </div>
		<div class="clr"></div>
		<div style="width:50%; float:left;">
            <label>
                <span>GSTIN:</span>
                <input type="text" name="supplier_gstin" id="supplier_gstin" placeholder="GSTIN" ng-model="incoming_invoice_excise._supplier_gstin" readonly="" required/>
            </label>
        </div>
		<div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div align="center">
           <label><span>Invoice Detail</span></label>
    </div><div class="clr"></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
        <table width="350%" border="1">
              <tr>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Item Code Part No.*</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Description</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>HSN Code</span></label></td>
					<td width="10%" colspan="2" style="text-align:center;"><label><span>Quantity</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Unit</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Basic Purchase Price<br />(D)</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Total</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Discount(%)</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Taxable Value</span></label></td>
					<td width="10%" colspan="2" style="text-align:center;"><label><span>CGST</span></label></td>
					<td width="10%" colspan="2" style="text-align:center;"><label><span>SGST</span></label></td>
					<td width="10%" colspan="2" style="text-align:center;"><label><span>IGST</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Batch No.</span></label></td>
					<td width="5%"  rowspan="2" style="text-align:center;"><label><span>Expiry Date</span></label></td>
					<td width="5%" 	rowspan="2" style="text-align:center;"><label><span>Landing Price per unit.</span></label></td>
					<td width="5%" 	rowspan="2" style="text-align:center;"><label><span>Total Landing Price</span></label></td>
				</tr>
				<tr>
					<td width="5%" style="text-align:center;"><label><span>Principal</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Supplier</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Rate</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Amt</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Rate</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Amt</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Rate</span></label></td>
					<td width="5%" style="text-align:center;"><label><span>Amt</span></label></td>
				</tr>
				<tr>
					<td>
						<hidden id="itemid" ng-model="incoming_invoice_excise._item_id"></hidden>
						<input type="text" name="item_master" id="item_master" style=" z-index: 2; background: transparent;" placeholder="Type Item Code Part" ng-model="incoming_invoice_excise._item_code_part_no" />
					</td>
					<td>
						<input type="text" id="item_descp" ng-model="incoming_invoice_excise._itemID_descp" readonly/> 
					</td>
					<td>
						<input type="text" id="hsn_code" ng-model="incoming_invoice_excise._hsn_code" readonly/>
						<hidden id="item_id" ng-model="incoming_invoice_excise._item_id"></hidden>
					</td>
					<td>
						<input type="text" id="principal_unitid" ng-change="" ng-model="incoming_invoice_excise._principal_qty" valid-number/>
					</td>
					<td>
						<input type="text" id="supplier_unitid"  ng-change="" ng-model="incoming_invoice_excise._supplier_qty" valid-number/>
					</td>
					<td>
						<hidden id="ddlunitid" ng-model="incoming_invoice_excise._itemID_unitid"></hidden>
						<input type="text" id="item_unitname"   ng-model="incoming_invoice_excise._itemID_unitname" readonly/>
					</td>
					<td>
						<input type="text" id="basic_purchase_price" ng-change="getTotalAmt();" ng-model="incoming_invoice_excise._basic_purchase_price"/>
					</td>
					<td>
						<input type="text" id="item_total" ng-model="incoming_invoice_excise._total" readonly/>
					</td>
					<td>
						<input type="text" id="item_discount" ng-change="getTaxableAmt()" ng-model="incoming_invoice_excise._discount" valid-number/>
					</td>
					<td>
						<input type="text" id="item_taxable_total" ng-model="incoming_invoice_excise._taxable_total" readonly/>
					</td>
					<td>
						<input type="text" id="cgst_rate" ng-model="incoming_invoice_excise._cgst_rate" readonly/>
					</td>
					<td>
						<input type="text" id="cgst_amt" ng-model="incoming_invoice_excise._cgst_amt" readonly/>
					</td>
					<td>
						<input type="text" id="sgst_rate" ng-model="incoming_invoice_excise._sgst_rate" readonly/>
					</td>
					<td>
						<input type="text" id="sgst_amt" ng-model="incoming_invoice_excise._sgst_amt" readonly/>
					</td>
					<td>
						<input type="text" id="igst_rate" ng-model="incoming_invoice_excise._igst_rate" readonly/>
					</td>
					<td>
						<input type="text" id="igst_amt" ng-model="incoming_invoice_excise._igst_amt" readonly/>
					</td>
					<td>
						<input type="text" id="batch_number" ng-model="incoming_invoice_excise._batch_number"/>
					</td>
					<td>
						<input type="text" id="expire_date" ng-model="incoming_invoice_excise._expire_date"/>
					</td>
					<td>
						<input type="text" id="landing_price" ng-model="incoming_invoice_excise._landing_price" readonly/>
					</td>
					<td>
						<input type="text" id="total_landing_price" ng-model="incoming_invoice_excise._total_landing_price" readonly/>
					</td>
					<td>
						<input class="button" type="button" name="button2" id="button2" ng-click="addItem()" value="+" />
					</td>
				</tr>
				<tr  ng-repeat="item in incoming_invoice_excise._items">
					<td><input type="text" ng-model="item._item_code_part_no" /></td>
					<td><input type="text" ng-model="item._itemID_descp" /></td>
					<td><input type="text" ng-model="item._hsn_code" /></td>
					<td><input type="text" ng-model="item._principal_qty" valid-number/></td>
					<td><input type="text" ng-model="item._supplier_qty"  valid-number/></td>
					<td><input type="text" ng-model="item._itemID_unitname" /></td>
					<td><input type="text" ng-model="item._basic_purchase_price" /></td>
					<td><input type="text" ng-model="item._total" /></td>
					<td><input type="text" ng-model="item._discount" /></td>
					<td><input type="text" ng-model="item._taxable_total" /></td>
					<td><input type="text" ng-model="item._cgst_rate" /></td>
					<td><input type="text" ng-model="item._cgst_amt" /></td>
					<td><input type="text" ng-model="item._sgst_rate" /></td>
					<td><input type="text" ng-model="item._sgst_amt" /></td>
					<td><input type="text" ng-model="item._igst_rate" /></td>
					<td><input type="text" ng-model="item._igst_amt" /></td>
					<td><input type="text" ng-model="item._batch_number" /></td>
					<td><input type="text" ng-model="item._expire_date" /></td>
					<td><input type="text" ng-model="item._landing_price"/></td>
					<td><input type="text" ng-model="item._total_landing_price"/></td>
					<td>
						<input type="button" class="button" name="button6" id="button60" value="-" ng-click="removeItem(item)" />
					</td>
				</tr>
            </table>
    </div>
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>Packing (E):</span>
            <input name="textfield3" type="text" id="txtpacking"  ng-change="getLanding_Price()"  ng-model="incoming_invoice_excise._pf_chrg" placeholder="Packing" is-number ></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Insurance (F):</span>
            <input name="textfield" type="text" id="txtinsurance"  ng-change="getLanding_Price()"  ng-model="incoming_invoice_excise._insurance" placeholder="Insurance"  is-number ></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Freight(G):</span>
            <input name="textfield2" type="text" id="txtfreight"  ng-change="getLanding_Price()"  ng-model="incoming_invoice_excise._freight" placeholder="Freight"  is-number></input>
        </label>
    </div>
    <!--<div style="width:25%; float:left;">
        <label>
            <span>Salse Tax(H):</span>
            <select name="select2" id="txtsaletax"  ng-model="incoming_invoice_excise._sale_Tax" ng-change="">
                <option value="">Select Tax</option>
              <?php
              /* $result =  Param::GetVATCSTList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                  echo "<option value='".$row['SALESTAX_ID']."'>".$row['SALESTAX_DESC']."</option>";
              } */?>
            </select>
        </label>
         <label>
            <input  type="text" id="txtsaletax1" ng-model="incoming_invoice_excise.SaleTaxAmount" placeholder="Salse Tax Amount" ng-change="getLanding_Price();" required>
        </label>
    </div> -->
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>Total Bill Value:</span>
            <input name="textfield5" type="text" id="txttotalbillvalue" value="Filled by user"  ng-model="incoming_invoice_excise._total_bill_val" placeholder="Total Bill Value" valid-number readonly></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Received Date:</span>
            <input name="textfield9" type="text" id="txtreceiveddate" value="Default sys date"  ng-model="incoming_invoice_excise._rece_date" placeholder="dd/mm/yyyy" required></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Comment:</span>
            <textarea name="textarea" id="txtcomment"  ng-model="incoming_invoice_excise._remarks" placeholder="Maximum Char 300." ></textarea>
        </label>
    </div>
    <div class="clr"></div>
    <div align="center">
        <input type="submit" class="button" name="button3" id="btnsave"  value="Save">
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_INCOMING_INVOICE_EXCISE; ?>">Cancel</a></span>
        <input type="button" class="button" name="button6" id="btnupdate" value="Update" ng-click="Update();" style="display: none;">
        <!-- <input type="button" class="button" name="button3" id="button3" value="Cancel">
        <input type="button" class="button" name="button3" id="button3" value="Delete">
        <input type="button" class="button" name="button3" id="button3" value="Print"> -->
    </div>
</div>
<!--   Old Design   -->
<!-- <p>Case A:</p>
<p>ED/Unit-&gt;If Mfr Qty is not empty and Supplr. Qty is empty then comes by <strong>ED Amtt /Mfr qty</strong>
  <br>If Mfr Qty is not empty and Supplr. Qty is also not empty then comes by <strong>ED Amtt /Supplr qty</strong></p>
<p>Case B:</p>
<p>If Mfr Qty is not empty and Supplr. Qty is empty then comes by <strong>Mfr qty</strong> <br>
If Mfr Qty is not empty and Supplr. Qty is also not empty then comes by <strong>Supplr qty</strong></p>
<p>Case C:EDU Amt.=(ED Amtt * EDU.cess%)</p>
<p>Case D: Landing Price=(D+A+B+C+E+F)+H+G</p>
<p>Case E:Total Landing Price=D.Qty</p> -->
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#mydate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#principal_invoice_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#supplier_invoice_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#expire_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#txtreceiveddate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});

$('#dnt_supply').datetimepicker({
	lang:'en',
	timepicker:true,
	format:'Y-m-d H:i',
	formatDate:'Y-m-d H:i',
	scrollInput:false
});
</script>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

</form>
<?php include("../../footer.php") ?>
</body>
</html>

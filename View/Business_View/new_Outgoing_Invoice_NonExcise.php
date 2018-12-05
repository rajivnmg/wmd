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
<title>Outgoing Invoice without Excise Duty</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/Outgoing_Invoice_NonExcise.js'></script>

<link href="../css/pop.css" media="all" rel="stylesheet" type="text/css"/>
<!-- <link href="../css/application.css" media="all" rel="stylesheet" type="text/css"/> -->
<script language="javascript" src="../js/jquery.pop.js" type="text/javascript"></script>
<script type='text/javascript'>
    $(document).ready(function(){
    $.pop();
    });
</script>

</head>
<body ng-app="Outgoing_Invoice_NonExcise_App">

<form id="form1" ng-controller="Outgoing_Invoice_NonExcise_Controller" ng-submit="AddOutgoingInvoiceExcise();" data-ng-init="init('<?php echo $_GET['OutgoingInvoiceNonExciseNum'];?>');" class="smart-green">
<div>
<div align="center"><h1>Outgoing Invoice without Excise Duty Form</h1></div>
      <!-- <div style="width:50%; float:left;">
            <div style="width:100%; float:left;">
                    <label><input type="radio" name="INVOICETYPE" value="I" id="INVOICETYPE_0" ng-model="outgoing_invoice_nonexcise.oinvoice_type" /><span>Invoice</span></label>

                    <label><input type="radio" name="INVOICETYPE" value="C" id="INVOICETYPE_1" ng-model="outgoing_invoice_nonexcise.oinvoice_type" /><span>Cash</span></label>
                    <hidden ng-model="outgoing_invoice_nonexcise.oinvoice_nexciseID"></hidden>
            </div><div class="clr"></div>
      </div> -->
      <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Through:</span>
                        <select name="partno_3" id="partno_3" ng-model="outgoing_invoice_nonexcise.mode_delivery" required>
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
       
           <select name="marketsegment" id="marketsegment" tabindex="9" ng-model="outgoing_invoice_nonexcise.ms" style=" z-index: 2; background: transparent;" required>
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
            <div style="width:70%; float:left;">
                    <label>
                        <span>Buyer:</span>
                        <hidden id="buyerid" ng-model="outgoing_invoice_nonexcise.BuyerID"></hidden>
            <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;width:94%" tabindex="1" placeholder="Type Buyer Name" ng-model="outgoing_invoice_nonexcise.bn_name" readonly required/>
                      <!--<select name="buyer_no_2" id="buyer_id" ng-model="outgoing_invoice_nonexcise.BuyerID" required>
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
	  if(isset($_GET['OutgoingInvoiceNonExciseNum']) && ($_GET['OutgoingInvoiceNonExciseNum'] !='')){
           echo'<div style="width:50%; float:left;">
                    <label>
                        <span>Invoice Number:</span>
                        <input type="text" id="txt_outgoing_invoice_num"  ng-model="outgoing_invoice_nonexcise.oinvoice_No" required readonly/>
                    </label>
            </div>';
		}else{
			echo'<input type="hidden" id="txt_outgoing_invoice_num"  ng-model="outgoing_invoice_nonexcise.oinvoice_No" required readonly/>';
		}
			?>  
        
            <div style="width:50%; float:left;">
                    <label>
                        <span>Invoice Date:</span>
                        <input name="inv_no_p" type="text" id="" ng-model="outgoing_invoice_nonexcise.oinv_date" placeholder="yyyy-mm-dd" readonly required/>
                    </label>
            </div><div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
            <div style="width:70%; float:left;">
                    <label>
                        <span>Principal:</span>
                        <select name="buyer_no_3" id="principal_list" ng-model="outgoing_invoice_nonexcise.principalID" ng-change="getprincipal();" required><option value="">Select Principal</option></select><!--  List from PO -->
                    </label>
            </div><div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Purchase Order Number:</span>
                         <input name="bpoType" type="text" id="bpoType" ng-model="outgoing_invoice_nonexcise.pot" style="display: none"/><!-- added by aksoni for store bpoType of PO-->
                         <input name="freightTag" type="text" id="freightTag" ng-model="outgoing_invoice_nonexcise.frgt" style="display:none"/><!-- added by aksoni for store freightTag of PO 09/04/2015-->
                        <hidden id="PurchaseOrderid" ng-model="outgoing_invoice_nonexcise.poid"></hidden>
            <input type="text" name="PurchaseOrder_name" id="autocomplete-ajax-PurchaseOrder" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Purchase Order Name" ng-model="outgoing_invoice_nonexcise.pono" onKeyPress="loadPoByNumber(this.value);" required/>
                        <!-- <select name="select" id="invoice_no" ng-change="GetPurchaseOrderDetails();"  ng-model="outgoing_invoice_nonexcise.pono" required><option value="">Select Purchase Order Number</option>
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
                        <input name="inv_no_p7" type="text" id="inv_po_date" ng-model="outgoing_invoice_nonexcise.po_date" placeholder="yyyy-mm-dd" required/>
                    </label>
            </div><div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div align="center">
           <label><span>Invoice Detail</span></label>
      </div><div class="clr"></div>
      <div style="width:100%; float:left; height:300px; overflow:scroll;">
            <table width="110%" border="1">
                <tr>
                  <td width="10%"><div align="center"><label><span>Buyer Item Code*</span></label></div></td>
                  <td width="13%"><label><span>Item Code Part No.*
                <a  href="#" id="help" class="pop_toggle" style="display: none;">
                  <div  class='pop'>
                      <table style="width:300px;">
                          <tr>
                             <td>Code Part Number</td>
                             <td>Request Quantity</td>
                             <td>Delivery Date</td>
                             <td>Quantity Delivery Date</td>
                          </tr>
                      </table>
                      <table style="width:300px;" id="rowdata"></table>
                   </div>
                  </a>

                  </span></label></td>
                  <td width="20%"><div align="center"><label><span>Description</span></label></div></td>
                  <td width="10%"><label><span>Ordered Quantity</span></label></td>
                  <td width="10%"><label><span>Balance Quantity</span></label></td>
                  <td width="10%"><label><span>Available Quantity</span></label></td>
                  <td width="10%"><div align="center"><label><span>Bill Quantity*</span></label> </div></td>
                  <td width="10%"><div align="center"><label><span>Rate*</span></label></div></td>
                  <td width="11%"><div align="center"><label><span>Amount</span></label></div></td>
                <td width="6%">&nbsp;</td>
                </tr>

                <tr>
                  <td><input type="text"  ng-model="outgoing_invoice_nonexcise.buyer_item_code" readonly/><!-- display from PO&amp; NON EDITABLE --></td>
                  <td><input type="text" name="partno_2" id=" id="partno_2" ng-model="outgoing_invoice_nonexcise.oinv_codePartNo" style="display:none">
                  <select id="bpodid" ng-model="outgoing_invoice_nonexcise.bpod_Id" ng-change="itemdesc();"></select>       
                  <!--  principal item based on PO --></td>
                  <td><input type="text" id="itemid" ng-model="outgoing_invoice_nonexcise._item_id" style="display:none">
                  <input type="text"  ng-model="outgoing_invoice_nonexcise.codePartNo_desc" readonly/><!-- Auto display when click on part no. --></td>
                  <td><input type="text"  ng-model="outgoing_invoice_nonexcise.ordered_qty" readonly/><!-- auto display --></td>
                  <td><input type="text"  ng-model="outgoing_invoice_nonexcise.balance_qty" readonly/><!-- auto display --></td>
                  <td><input type="text"  ng-model="outgoing_invoice_nonexcise.stock_qty" readonly/><!-- auto display --></td>
                  <td><input name="dt_p2" type="text" id="dt_p2" value="filled by user &amp; not more than po" size="5"  ng-model="outgoing_invoice_nonexcise.issued_qty" ng-change="checkStock(); getTotal_Price(); " is-number/></td>
                  <td><input name="dt_p3" type="text" id="dt_p3" value="display from PO"  ng-change="getTotal_Price();" ng-model="outgoing_invoice_nonexcise.oinv_price" readonly/></td>
                  <td><input type="text"  ng-model="outgoing_invoice_nonexcise.item_amount" readonly/><!-- (Qty*Rate) --></td>
                  <td>
                  <input type="text" ng-model="outgoing_invoice_nonexcise.discount" style="display:none" readonly />
                  <input type="text" ng-model="outgoing_invoice_nonexcise.saletaxID" style="display:none" readonly/>
                  <input class="button" type="button" name="button" id="button" ng-click="addItem()" value="+" />
                  </td>
                </tr>
                <tr  ng-repeat="item in outgoing_invoice_nonexcise._items">
                  <td><input type="text"  ng-model="item.buyer_item_code" readonly/></td>
                  <td><input type="text" name="partno_2" ng-model="item.oinv_codePartNo" readonly/></td>
                  <td><input type="text"  ng-model="item.codePartNo_desc" readonly/>
                  <input type="text"  ng-model="item.bpod_Id" style="display:none">
                  <input type="text" ng-model="item._item_id" style="display:none">
                  </td>
                  <td><input type="text"  ng-model="item.ordered_qty" readonly/></td>
                   <td><input type="text"  ng-model="item.balance_qty" readonly/></td>
                  <td><input type="text"  ng-model="item.stock_qty" readonly/></td>
                  <td><input type="text" id="dt_p2" size="5"  ng-model="item.issued_qty" ng-change="UpdateOnRow(item);"/></td>
                  <td><input type="text" id="dt_p3" ng-model="item.oinv_price" readonly/></td>
                  <td><input type="text" ng-model="item.item_amount" readonly/></td>


                  <td  width="2%" >
                  <input type="text" ng-model="item.discount" style="display:none" readonly/>
                  <input type="text" ng-model="item.saletaxID" style="display:none" readonly/>
                  <input class="button" type="button" name="button6" id="button60" value="-" ng-click="removeItem(item)" >
                  </td>
                </tr>
              </table>
      </div>
      <div class="clr"></div>
      <div style="width:5%; float:right;">

      </div><div class="clr"></div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
                <label>
                    <span>Discount:</span>
                    <input name="dt_p4" type="text" id="dt_p4" ng-model="outgoing_invoice_nonexcise.total_discount" placeholder="Discount" required/>
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Tax:</span>
                    <input name="partno_" id="partno_" type="text" ng-model="outgoing_invoice_nonexcise.total_saleTax" placeholder="Tax Charges" required/>
                    <select name="select2" id="txtsaletax"  ng-model="outgoing_invoice_nonexcise.po_saleTax"  required>
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
                    <input name="dt_p5" type="text" id="txtpf_charg" ng-model="outgoing_invoice_nonexcise.pf_chrg" placeholder="P & F  Charges" required/>
                    <input type="hidden" id="txtpf_charg_percent" ng-model="outgoing_invoice_nonexcise.pf_chrg_percent" style="display:block">
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Incidental Charges:</span>
                    <input name="inv_no_p3" type="text" id="txtincidental_chrg" ng-model="outgoing_invoice_nonexcise.incidental_chrg" placeholder="Incidental Charges" required/>
                    <input type="hidden" id="txtincidental_chrg_percent" ng-model="outgoing_invoice_nonexcise.incidental_chrg_percent" style="display:block">
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Freight (%):</span>
                    <input name="dt_p6" type="text" id="txtfreight_percent" ng-model="outgoing_invoice_nonexcise.fright_percent" placeholder="Freight (%)" required/>
                </label>
          </div>
          <div style="width:50%; float:left;">
                <label>
                    <span>Freight Amount:</span>
                    <input name="inv_no_p4" type="text" id="txtfreight_amount" ng-model="outgoing_invoice_nonexcise.freight_amount" placeholder="Freight Amount" required/>
                </label>
          </div>
          <div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:right;">
                <label>
                    <span>Total Amount:</span>
                    <input type="text" name="inv_no_p5" id="inv_no_p5"  ng-model="outgoing_invoice_nonexcise.bill_value" placeholder="0.00" required readonly/>
                </label>
          </div>
          <div class="clr"></div>
          <div style="width:50%; float:right;">
                <label>
                    <span>Comment's:</span>
                    <textarea name="comm" id="comm" ng-model="outgoing_invoice_nonexcise.remarks" placeholder="Maximum Char 300." ></textarea>
                </label>
          </div>
		  <div id="bmail" style="width:30%; float:left;">
                <label>
                    <span>Buyer Email (Dispatch Information will be received here):</span>
					   <input type="text" name="bemailId" id="bemailId" ng-model="outgoing_invoice_nonexcise.bemailId" tabindex="45" value="" class="input1" required/>
					
                </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div align="center">
        <input class="button" type="submit" name="button3" id="btnsave"  value="Save"/>
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_OUTGOING_INVOICE_NonEXCISE; ?>">Cancel</a></span>
        <!-- <input class="button" type="button" name="button3" id="button3" value="Cancel"/> -->
        <input type="button" class="button" name="button3" id="btnupdate" ng-click="Update();" value="Update"/>
        <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINT_OUTGOING_INVOICE_NonEXCISE.'?TYP=SELECT&OutgoingInvoiceNonExciseNum='.$_GET['OutgoingInvoiceNonExciseNum']; ?>">Print View</a>
		 <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINT_INVOICE.'?TYP=NonEXCISE&invoiceID='.$_GET['OutgoingInvoiceNonExciseNum']; ?>">Print New</a>
        <!-- <input class="button" type="button" name="button3" id="button3" value="Print"/> -->
      </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#inv_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#inv_po_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

<?php 
ini_set('memory_limit', '2048M');
//session_start(); 

include('root.php');
include($root_path."GlobalConfig.php");
include($root_path."Model/ReportModel/Report1Model.php");
include("../../header.php");

 ?>
<!doctype html>
<html>
<head>

<meta charset="utf-8">
<title>Outgoing Invoice With Excise Duty</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/Outgoing_Invoice_Excise.js'></script> 

<link href="../css/pop.css" media="all" rel="stylesheet" type="text/css"/>
<!-- <link href="../css/application.css" media="all" rel="stylesheet" type="text/css"/> -->
<script language="javascript" src="../js/jquery.pop.js" type="text/javascript"></script>
<script type='text/javascript'>
    $(document).ready(function(){
    $.pop();
    });
</script>

</head>
<body  ng-app="Outgoing_Invoice_Excise_App">

<form id="form1" ng-controller="Outgoing_Invoice_Excise_Controller" ng-submit="AddOutgoingInvoiceExcise();"  data-ng-init="init('<?php if(isset($_GET['OutgoingInvoiceExciseNum'])) {echo $_GET['OutgoingInvoiceExciseNum'];}?>');" class="smart-green">

<div>
<div align="center"><h1>Outgoing Invoice Form</h1></div>
    <?php
      if(isset($_GET['OutgoingInvoiceExciseNum']) && ($_GET['OutgoingInvoiceExciseNum'] !='')){
     echo'<div style="width:33.3%; float:left;">
        <label>
            <span>Invoice Number*:</span>
            <input name="textfield" type="text" id="txt_outgoing_invoice_num" ng-model="outgoing_invoice_excise.oinvoice_No" placeholder="Invoice Number" readonly required></input>
        </label>
      </div>';
	  }else{
			echo'<input name="textfield" type="hidden" id="txt_outgoing_invoice_num" ng-model="outgoing_invoice_excise.oinvoice_No" placeholder="Invoice Number" readonly required></input>
        ';
	  
	  }
	  
	  ?>
	   <div style="width:33.3%; float:left;">
        <label>
            <span>Vehicle Number:</span>
            <input type="text" id="vehcle_no"  ng-model="outgoing_invoice_excise._vehcle_no" placeholder="Vehicle Number"></input>
            
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Date & Time of Supply:</span>
            <input type="text" id="dnt_supply"  ng-model="outgoing_invoice_excise._dnt_supply" placeholder="dd/mm/yyyy hh:mm"></input>
            
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Place of Supply:</span>
            <input type="text" id="supply_place"  ng-model="outgoing_invoice_excise._supply_place" placeholder="Place of Supply"></input>
            
            
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Tax payable on Reverse Charge?:</span>
            <select name="reverse_charge_payable" id="reverse_charge_payable" tabindex="9" ng-model="outgoing_invoice_excise._reverse_charge_payable" style=" z-index: 2; background: transparent;" required>
				<option value="">Select One</option>
				<option value="1">Yes</option>
				<option value="0">No</option>
            </select> 
           
            
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Invoice Date:</span>
            <input name="date" type="text" id="" ng-model="outgoing_invoice_excise.oinv_date" placeholder="yyyy-mm-dd" tabindex="1" readonly required></input>
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Invoice Time:</span>
            <input name="time" type="text" id="time1" ng-model="outgoing_invoice_excise.oinv_time" placeholder="hh-mm" tabindex="2" readonly required>
        </label>
      </div>
      <div class="clr"></div>
      
      
      
      <div style="width:33.3%; float:left;">
        <label>
            <span>Buyer:</span>
            <hidden id="buyerid" ng-model="outgoing_invoice_excise.BuyerID"></hidden>
          
            <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent; width:93%" tabindex="1" placeholder="Type Buyer Name" ng-model="outgoing_invoice_excise.bn_name" readonly required/>
           <!-- <select name="select" id="buyer_id"  ng-model="outgoing_invoice_excise.BuyerID" required>
            <option value="">Select Buyer</option>
              <?php
                  include( "../../Model/Masters/BuyerMaster_Model.php");
                //  include("../../Model/DBModel/DbModel.php");
                  $result =  BuyerMaster_Model::GetBuyerList();
                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                      echo "<option value='".$row['BuyerId']."'>".$row['BuyerName']."</option>";
                  }?>
              </select> Display on the basis of PO -->
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Purchase Order Number:</span>
             <input name="bpoType" type="text" id="bpoType" ng-model="outgoing_invoice_excise.pot" style="display:none"/><!-- added by aksoni for store bpoType of PO-->
              <input name="freightTag" type="text" id="freightTag" ng-model="outgoing_invoice_excise.frgt" style="display:none"/><!-- added by aksoni for store freightTag of PO 09/04/2015-->
            <hidden id="PurchaseOrderid" ng-model="outgoing_invoice_excise.poid"></hidden>
            <input type="text" name="PurchaseOrder_name" id="autocomplete-ajax-PurchaseOrder" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Purchase Order Name" ng-model="outgoing_invoice_excise.pono" onKeyPress="loadPoByNumber(this.value);" required/>
            
           <!--  <select name="select" id="invoice_no" ng-change="GetPurchaseOrderDetails();" ng-model="outgoing_invoice_excise.pono" required>
            <option value="">Select Purchase Order Number</option>  
           <?php
                  include( "../../Controller/Param/Param.php");
                  $result =  Param::GetPurchaseOrderForBilling();
                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                      echo "<option value='".$row['bpoId']."'>".$row['bpono']."</option>";
                  }?>
              </select> -->
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Purchase Order Date:</span>
            <input name="date2" type="text" id="podate" ng-model="outgoing_invoice_excise.po_date" placeholder="yyyy-mm-dd" tabindex="2" required></input>
        </label>
      </div>
      <div class="clr"></div>
      
      
      
      <div style="width:33.3%; float:left;">
        <label>
            <span>Principal:</span>
            <select name="select2" id="principal_list" ng-model="outgoing_invoice_excise.principalID" required ng-change="getprincipal();"><option value="">Select Principal</option></select><!-- Display on the basis of PO -->
        </label>
      </div>
      <div style="width:33.3%; float:left;">
            <label>
                <span>Principal GSTIN:</span>
                <input type="text" name="principal_gstin" id="principal_gstin" placeholder="GSTIN" ng-model="outgoing_invoice_excise._principal_gstin" readonly="" required/>
            </label>
        </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Mode of Transport:</span>
            <select name="select4" id="select4"  ng-model="outgoing_invoice_excise.mode_delivery" required>
            <option value="">Select Through</option>
              <?php
              //include( "../../Controller/Param/Param.php");
              $result =  Param::GetParamList("DELIVERY","MODE");
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
              }?>
              </select><!-- Display from param -->
        </label>
      </div>
      <div style="width:33.3%; float:left;">
        <label>
            <span>Dispatch Time:</span>
            <input name="time2" type="text" id="dispatchtime" ng-model="outgoing_invoice_excise.dispatch_time" placeholder="hh-mm" tabindex="2" required></input>
        </label>
      </div>
      
      
      <div style="width:33.3%; float:left;">
        <label>
            <span>Supplier:</span>
            <select name="select3" id="select3"  ng-model="outgoing_invoice_excise.supplierID" ng-change="getSupplierGSTN();">
            <option value="">Select Supplier</option>
              <?php
                include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                $result =  Principal_Supplier_Master_Model::Get_Supplier_List();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['Principal_Supplier_Id']."'>".$row['Principal_Supplier_Name']."</option>";
                }?>
              </select><!-- Display on the basis of PO -->
        </label>
      </div>
      <div style="width:33.3%; float:left;">
            <label>
                <span>Supplier GSTIN:</span>
                <input type="text" name="supplier_gstin" id="supplier_gstin" placeholder="GSTIN" ng-model="outgoing_invoice_excise._supplier_gstin" readonly="" required/>
            </label>
        </div>
      <div style="width:33.3%; float:left; padding-top:25px;">
        <label>
            <span>Supplier Stage:</span>
        </label>
        1st Stage
            <input type="checkbox" name="checkbox" id="checkbox"  ng-model="outgoing_invoice_excise.Supplier_stage1"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2nd Stage                         
            <input type="checkbox" name="checkbox2" id="checkbox2"  ng-model="outgoing_invoice_excise.Supplier_stage2"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Free Sample
            <input type="checkbox" name="checkbox3" id="checkbox3"  ng-model="outgoing_invoice_excise.Supplier_stageF"/>
      </div>
     <div class="clr"></div>
	    <div style="width:33.3%; float:left;">
      <label><span>Market segment*:</span>
       
           <select name="marketsegment" id="marketsegment" tabindex="9" ng-model="outgoing_invoice_excise.ms" style=" z-index: 2; background: transparent;" required>
           <option value="">Select One</option>
               <?php  include( "../../Model/Masters/MarketSegment_Model.php");
			                      $result =  MarketSegmentModel::GetMsList();
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['msid']."'>".$row['msname']."</option>";
                  }?>
            </select> 
      </div>
      <div class="clr"></div>
      <div align="center">
           <label><span>Invoice Detail</span></label>
      </div><div class="clr"></div>
      <div style="width:100%; float:left; height:300px; overflow:scroll;">
          <table width="100%" border="1">
            <tr>
              <td width="11%" rowspan="2"><label><span>Buyer Item Code</span></label></td>
              <td width="10%" rowspan="2"><label><span>Item Code Part No.
              <a id="help" href="#" class="pop_toggle" style="display: none;">
               
               <div class='pop'>
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
              <td width="6%" rowspan="2"><label><span>Description</span></label></td>
              <td width="8%" rowspan="2"><label><span>Ordered Quantity</span></label></td>
              <td width="8%" rowspan="2"><label><span>Balance Quantity</span></label></td>
              <td width="9%" rowspan="2"><label><span>Incoming Invoice No.</span></label></td>
              <td colspan="2"><div align="center"><label><span>Quantity</span></label></div></td>
              <td width="5%" rowspan="2"><label><span>Rate</span></label></td>
              <td width="5%" rowspan="2"><label><span>Discount(%)</span></label></td>
              <td width="4%" rowspan="2"><label><span>Taxable Value</span></label></td>
              
              <td width="5%" rowspan="2"><label><span>HSN Code</span></label></td>
              <td colspan="2"><div align="center"><label><span>CGST</span></label></div></td>
              <td colspan="2"><div align="center"><label><span>SGST</span></label></div></td>
              <td colspan="2"><div align="center"><label><span>IGST</span></label></div></td>
              
              
              <td width="6%" rowspan="2"><label><span>Total Price</span></label></td>
              
              <!--<td width="6%" rowspan="2"><label><span>CVD Amount(C)</span></label></td>-->
            </tr>
            <tr>
              <td width="5%"><label><span>Balanced.*</span></label></td>
              <td width="4%"><label><span>Issued</span></label></td>
              <td width="5%"><label><span>Rate(%)</span></label></td>
              <td width="4%"><label><span>Amt</span></label></td>
              <td width="5%"><label><span>Rate(%)</span></label></td>
              <td width="4%"><label><span>Amt</span></label></td>
              <td width="5%"><label><span>Rate(%)</span></label></td>
              <td width="4%"><label><span>Amt</span></label></td>
            </tr>
            <tr>
              <td><!-- display from PO&amp; NON EDITABLE --><input type="text" ng-model="outgoing_invoice_excise.buyer_item_code"/></td>
              <td><hidden id="item_master" ng-model="outgoing_invoice_excise.oinv_codePartNo"></hidden>
                <select id="bpodid" ng-model="outgoing_invoice_excise.bpod_Id" ng-change="itemdesc();"></select> 
               <input type="text" id="itemid" ng-model="outgoing_invoice_excise._item_id" style="display:none"/>
              <!--  display on the basis of Principal --></td>
              <td><input type="text" style="width:50px;" ng-model="outgoing_invoice_excise.codePartNo_desc" readonly/>
             
              </td>
              <td><!-- from PO --><input type="text" style="width:50px;" ng-model="outgoing_invoice_excise.ordered_qty" readonly/></td>
               <td><!-- from PO --><input type="text" style="width:50px;" ng-model="outgoing_invoice_excise.balance_qty" readonly/></td>
              <td><select name="select8" id="invoice_list" ng-model="outgoing_invoice_excise.iinv_no" ng-change="get_Invoice_Details();">
              </select>
      <!-- display from RG23D it means stock --></td>
              <td><!-- it also from stock --><input type="text" style="width:50px;" ng-model="outgoing_invoice_excise.stock_qty" readonly/></td>
              <td><!-- filled by user &amp; not more than po --><input type="text" style="width:50px;" ng-change="checkStock(); getTotal_Price(); " ng-model="outgoing_invoice_excise.issued_qty" is-number/></td>
              <td><!-- from po --><input type="text" style="width:50px;"  ng-change="getTotal_Price();" ng-model="outgoing_invoice_excise.oinv_price"/></td>
              
              <td><input name="item_discount" type="text" id="item_discount" ng-model="outgoing_invoice_excise.item_discount" style="width:65px;" tabindex="33" value=""  ng-change="getTaxableAmt();" /></td>
              <td><input name="item_taxable_total" type="text" id="item_taxable_total" ng-model="outgoing_invoice_excise.item_taxable_total" readonly/></td>
             
              
              <!--<input name="textfield3" type="hidden" id="textfield3" ng-model="outgoing_invoice_excise.discount"></input>
               <input type="text" ng-model="outgoing_invoice_excise.saletaxID" style="display:none" readonly/>
              <input name="saleTax" type="hidden" id="saleTax" ng-model="outgoing_invoice_excise.saleTax"></input>-->
              
              <td><!-- from RG23D --><input name="hsn_code" type="text" id="hsn_code"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.hsn_code" readonly=""/></td>
              <td><!-- from RG23D --><input name="cgst_rate" type="text" id="cgst_rate"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.cgst_rate" readonly=""/></td>
              <td><!-- from RG23D --><input name="cgst_amt" type="text" id="cgst_amt"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.cgst_amt" readonly=""/></td>
              <td><!-- from RG23D --><input name="sgst_rate" type="text" id="sgst_rate"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.sgst_rate" readonly=""/></td>
              <td><!-- from RG23D --><input name="sgst_amt" type="text" id="sgst_amt"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.sgst_amt" readonly=""/></td>
              <td><!-- from RG23D --><input name="igst_rate" type="text" id="igst_rate"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.igst_rate" readonly=""/></td>
              
              <td><!-- from RG23D --><input name="igst_amt" type="text" id="igst_amt"  tabindex="34" style="width:100px;" ng-model="outgoing_invoice_excise.igst_amt" readonly=""/></td>
              <td><!-- rate*issued qty --><input type="text" style="width:50px;" ng-model="outgoing_invoice_excise.tot_price" readonly=""/></td>
            </tr>
            <tr  ng-repeat="item in outgoing_invoice_excise._items">
              <td><input type="text" style="width:50px;" ng-model="item.buyer_item_code"/></td>
              <td><input type="text" name="oinv_codePartNo" id="oinv_codePartNo" ng-model="item.oinv_codePartNo" /></td>
              <td><input type="text" style="width:50px;" ng-model="item.codePartNo_desc"/>
              <hidden id="bpodid" ng-model="item.bpod_Id"></hidden>
              <input type="text" ng-model="item._item_id" style="display:none">
              </td>
              
              <td><input type="text" style="width:50px;" ng-model="item.ordered_qty" readonly/></td>
               <td><input type="text" style="width:50px;" ng-model="item.balance_qty" readonly/></td>
              <td><input type="text" name="iinv_no" id="iinv_no" ng-model="item.iinv_no" readonly/></td>
              <td><input type="text" style="width:50px;" ng-model="item.stock_qty" readonly/></td>
              <td><input type="text" style="width:50px;" ng-model="item.issued_qty" ng-change="ChangeRowOnUpdate(item);" /></td>
              <td><input type="text" style="width:50px;" ng-model="item.oinv_price"/></td>
              
              <td><input type="text" style="width:50px;" ng-model="item.item_discount"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.item_taxable_total"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.hsn_code"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.cgst_rate"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.cgst_amt"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.sgst_rate"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.sgst_amt"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.igst_rate"/></td>
              <td><input type="text" style="width:50px;" ng-model="item.igst_amt"/></td>
              
              <td>
                  <input type="text" style="width:50px;" ng-model="item.tot_price"/>
                  <!--<input type="hidden" style="width:50px;" ng-model="item.discount"/>
                  <input type="hidden" style="width:50px;" ng-model="item.saleTax"/>-->
              </td>
              <td width="2%" ><input type="button" class="button" name="button6" id="button60" value="X" ng-click="removeItem(item)" ></td>
            </tr>
          </table>
      </div>
      <div class="clr"></div><!-- Combination of Part No. &amp; Incoming inv no. will be unique. -->
      <div style="width:5%; float:right;">
        <label>
            <input class="button" type="button" name="button" id="button" ng-click="addItem()" value="Add" />
        </label>
      </div><div class="clr"></div>
      <!--<div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
            <label>
                <span>Discount Amount:</span>
                <input name="textfield3" type="text" id="textfield3" ng-model="outgoing_invoice_excise.total_discount" placeholder="Discount Amount"></input>
            </label>
          </div>
          <div class="clr"></div>
      </div>-->
      <div style="width:50%; float:left;">
          <!--<div style="width:50%; float:left;">
            <label>
                <span>Freight (%):</span>
                <input type="text" name="textfield8" id="txtfreight_percent" ng-model="outgoing_invoice_excise.freight_percent" placeholder="Freight (%)" required readonly></input>
            </label>
          </div>-->
          <div style="width:50%; float:left;">
            <label>
                <span>Freight Amount (F):</span>
                <input type="text" name="textfield10" id="txtfreight_amount" ng-change="BillCalculation();" ng-model="outgoing_invoice_excise.freight_amount" placeholder="Freight Amount"></input>
            </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      
      <div style="width:50%; float:left;">
         <!-- <div style="width:50%; float:left;">
            <label>
                <span>Total ED(A):</span>
                <input type="text" name="textfield4" id="textfield4" ng-model="outgoing_invoice_excise.total_ed" placeholder="Total Educational Duty" required></input>
            </label>
          </div>-->
          
          <div class="clr"></div>
          <!--<div align="center" style="width:100%;">
            <label><span>
            <input type="checkbox" name="checkbox4" id="checkbox4"  ng-model="outgoing_invoice_excise.inclusive_ed_tag"></input>Inclusive(checked if ED applicable col. is Inclusive in basic price)</span>
            </label>
          </div>--><div class="clr"></div>
      </div>
      <!--<div style="width:50%; float:left;">
          <div style="width:50%; float:right;">
            <label>
                <span>Sale Tax Amount:</span>
                <input name="saleTax" type="text" id="saleTax" ng-model="outgoing_invoice_excise.total_saleTax" placeholder="Tax Amount" required />
                <select name="saleTax" id="saleTax"  ng-model="outgoing_invoice_excise.saleTax"  required>
                    <option value="">Select Tax</option>
                    <?php 
                    /*$result =  Param::GetVATCSTList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['SALESTAX_ID']."'>".$row['SALESTAX_DESC']."</option>";
                    }*/?>
                </select>
            </label>
          </div>
          <div class="clr"></div>
      </div>-->
      <div class="clr"></div>
      
      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
            <label>
                <span>P&amp;F Charge(D):</span>
                <input name="textfield5" type="text" id="txtpf_charg" ng-change="BillCalculation();" ng-model="outgoing_invoice_excise.pf_chrg" placeholder="P & F Charge"></input>
                <hidden id="txtpf_charg_percent" ng-model="outgoing_invoice_excise.pf_chrg_percent"></hidden>
                <!-- sum of ED Amt. -->
            </label>
          </div>
          <div style="width:50%; float:left;">
            <label>
                <span>Incidental Charges(E)</span>
                <input name="textfield6" type="text" id="txtincidental_chrg" ng-change="BillCalculation();" ng-model="outgoing_invoice_excise.incidental_chrg" placeholder="Incidental Charges"></input>
                <hidden id="txtincidental_chrg_percent" ng-model="outgoing_invoice_excise.incidental_chrg_percent"></hidden>
            </label>
          </div>
          <div class="clr"></div>
      </div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:right;">
            <label>
                <span>Total Amount:</span>
                <input type="text" name="textfield9" id="textfield9" ng-model="outgoing_invoice_excise.bill_value" placeholder="Total Amount" required readonly></input>
            </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
          <div style="width:50%; float:left;">
            <label>
                <span>Insurance Charge:</span>
                <input type="text" name="ins_charge"   id="ins_charge" is-number ng-change="BillCalculation();" ng-model="outgoing_invoice_excise.ins_charge"  tabindex="39"  />
                
            </label>
          </div>
          <div style="width:50%; float:left;">
            <label>
                <span>Other Charges:</span>
                <input type="text" name="othc_charge" id="othc_charge" is-number ng-change="BillCalculation();" ng-model="outgoing_invoice_excise.othc_charge"  tabindex="40" />
                
            </label>
          </div>
	  </div>
	  <div class="clr"></div>
      <div style="width:50%; float:left;">
            <label>
                <span>Comment's:</span>
                <textarea name="textarea" id="textarea" ng-model="outgoing_invoice_excise.remarks" placeholder="Maximum Char 300."></textarea>
            </label>
      </div>
	  <div id="bmail" style="width:30%; float:left;">
                <label>
                    <span>Buyer Email (Dispatch Information will be received here):</span>
					   <input type="text" name="bemailId" id="bemailId" ng-model="outgoing_invoice_excise.bemailId" tabindex="45" class="input1" required/>
					
                </label>
      </div>
      <div class="clr"></div>
      <div align="center">
          <input type="submit" class="button" name="button3" id="btnsave" value="Save" />
          <input type="button" class="button" name="button3" id="btnupdate" value="Update" ng-click="Update();" />
          <a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_OUTGOING_INVOICE_EXCISE; ?>">Cancel</a>
          <a class="button" style="text-decoration: none; display: none;" id="btnprint" href="<?php print SITE_URL.PRINT_OUTGOING_INVOICE_EXCISE.'?TYP=SELECT&OutgoingInvoiceExciseNum='.$_GET['OutgoingInvoiceExciseNum']; ?>">Print View</a> 
		  <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINT_INVOICE.'?TYP=EXCISE&invoiceID='.$_GET['OutgoingInvoiceExciseNum']; ?>">Print New</a> 
      </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#podate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#time1').datetimepicker({
	lang:'en',
	datepicker:false,
	format:'H:i',step:5,
	scrollInput:false
});
$('#dispatchtime').datetimepicker({
	lang:'en',
	datepicker:false,
	format:'H:i',step:5,
	scrollInput:false
});
$('#dnt_supply').datetimepicker({
	lang:'en',
	timepicker:true,
	format:'Y-m-d H:i',
	formatDate:'Y-m-d H:i',
	scrollInput:false
});
//datetimepicker.setWrapSelectorWheel(false);
</script>
  <!-- <p>if Freight is in percentage then Freight amt=Tot price*freight percent</p>
  <p>Tax=( Grand tot price(1-Discount/100)+A+B+C+D+E)*tax/100;</p>
  <p>&nbsp;</p>
  <p>**=BAL QTY OF ADJACENT INCOMING INV. NO</p>
  <p>Total Amt. or Invoice amt.=Grand tot price(1-Discount/100)+A+B+C+D+E+Tax+F;</p> -->
  
  <br/><br/><br/><br/><br/><br/><br/><br/>

<?php include("../../footer.php") ?>
</form>
</body>
</html>

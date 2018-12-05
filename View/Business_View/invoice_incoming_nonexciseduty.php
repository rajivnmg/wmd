<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Incoming Invoice Without Excise Duty</title>


<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/IncomingInvoiceNonExcise.js'></script>
</head>

<body ng-app="IncomingInvoiceNonExcise_app" >
<?php// include("../../header.php") ?>
<form id="form1" name="form1" method="post" ng-controller="IncomingInvoiceNonExcise_Controller" ng-submit="SaveInvoice('<?php echo $_GET['ID'];?>');" data-ng-init="init('<?php echo $_GET['ID'];?>')"  class="smart-green">
<div>
<hidden id="_invoiceid" ng-model="IncomingInvoiceNonExcise._invoiceid"></hidden>
<div align="center"><h1>Incoming Invoice Without Excise Duty Form</h1></div>
    <div style="width:50%; float:left;">
        <div align="center" style="width:100%; float:left;"><label><span>Manufacturer Detail</span></label></div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Invoice Number:</span><!-- filled by user &amp; value should be unique on peincipal -->
                <input name="inv_no_p" type="text" id="inv_no_p" ng-model="IncomingInvoiceNonExcise.inv_no_p"  placeholder="Invoice Number" ng-keyup="validateInvoiceNumber();"  required></input>
            </label>
        </div>
        <div style="float:left;">
            <label>
                <span>Date:</span>
                <input type="text" name="dt_p" id="dt_p" ng-model="IncomingInvoiceNonExcise.dt_p" placeholder="dd/mm/yyyy" required></input>
            </label>
        </div><div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
        <div align="center" style="width:100%; float:left;"><label><span>Supplier Detail </span></label></div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Invoice Number:</span><!-- filled by user &amp; value should be unique on principal -->
                <input name="inv_no_s" type="text" id="inv_no_s" ng-model="IncomingInvoiceNonExcise.inv_no_s" placeholder="Invoice Number"></input>
            </label>
        </div>
        <div style="float:left;">
            <label>
                <span>Date:</span>
                <input type="text" name="dt_s" id="dt_s" ng-model="IncomingInvoiceNonExcise.dt_s" placeholder="dd/mm/yyyy"></input>
            </label>
        </div><div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:37%; float:left;">
       
        <label><span>Manufacturer</span>
        <input type="text" name="country" id="autocomplete-ajax-principal" style=" z-index: 2; background: transparent;" placeholder="Type Principal Name" ng-model="IncomingInvoiceNonExcise._principalname" required/><input type="text" id="principalid" ng-model="IncomingInvoiceNonExcise._principalID" style="display:none">
           <!-- <select ng-model="IncomingInvoiceNonExcise.PricipalName" ng-change="getPartNo()">
            <option value="" title="select">Select Principal</option>
                 <?php include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                // include("../../Model/DBModel/DbModel.php");
                $result =  Principal_Supplier_Master_Model::Get_Principal_List();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
                }?>
            </select> -->
        </label>
       
        <div class="clr"></div>
    </div>
    <div style=" float:left;">
    
        <label><span>Supplier</span>
        <input type="text" name="country" id="autocomplete-ajax-supplier" style=" z-index: 2; background: transparent;" placeholder="Type Supplier Name" ng-model="IncomingInvoiceNonExcise._suppliername"/><hidden id="supplierid" ng-model="IncomingInvoiceNonExcise._supplrId"></hidden>
            <!-- <select ng-model="IncomingInvoiceNonExcise.SupplierName">
            <option value="" title="select"> Select Supplier</option>
                <?php
                $result =  Principal_Supplier_Master_Model::Get_Supplier_List();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                    echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
                        }?>
            </select> -->
        </label>
      
        <div class="clr"></div>
    </div>
	 <div style="width:15%; float:left;">    
        <label><span>Market segment*:</span>
       
           <select name="marketsegment" id="marketsegment" tabindex="9" ng-model="IncomingInvoiceNonExcise.ms" style=" z-index: 2; background: transparent;" required>
           <option value="0">Select One</option>
               <?php  include( "../../Model/Masters/MarketSegment_Model.php");
			                      $result =  MarketSegmentModel::GetMsList();
			                      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                           echo "<option value='".$row['msid']."'>".$row['msname']."</option>";
                  }?>
            </select> 
        </label>
		
		
      
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div align="center">
           <label><span>Invoice Detail</span></label>
    </div><div class="clr"></div>
    <div style="width:100%; float:left; height:300px; overflow:scroll;">
           <table width="100%" border="1">
                <tr>
                  <td width="12%"><div align="center"><label><span>Item Code Part No.*</span></label></div></td>
                  <td width="25%"><div align="center"><label><span>Description</span></label></div></td>
                  <td width="4%"><div align="center"><label><span>Quantity*</span></label></div></td>
                  <td width="8%"><div align="center"><label><span>Rate*(R)</span></label></div></td>
                  <td width="6%"><div align="center"><label><span>Amount(A)</span></label></div></td>
                  <td width="12%"><div align="center"><label><span>Batch No.</span></label></div></td>
                  <td width="8%"><label><span>Expiry Date</span></label></td>
                  <td width="9%"><label><span>Landing price</span></label></td>
                  <td width="13%"><label><span>Total Landing Price</span></label></td>
                </tr>
                <tr>
                  <td><label></label><hidden id="itemid" ng-model="IncomingInvoiceNonExcise._item_id"></hidden>
                    <!-- <select name="_partno_1_add" id="_partno_1_add" ng-model="IncomingInvoiceNonExcise._partno_1_add" ng-change="itemdesc()"><option value="">Select Item</option>
                  </select> -->
                  <input type="text" name="country" id="_partno_1_add" style=" z-index: 2; background: transparent;" placeholder="Type Item Code Part" ng-model="IncomingInvoiceNonExcise._partno_1_add" />
                  <!--  list from item master with check on principal --></td>
                  <td><input type="text" id="_item_descp_add" name="_item_descp_add" ng-model="IncomingInvoiceNonExcise._item_descp_add" readonly/></td>
                  <td><input name="_qty_add" type="text" id="_qty_add" value="filled by user" size="5" ng-model="IncomingInvoiceNonExcise._qty_add" ng-change="QtyChange()" is-number/></td>
                  <td><input name="_rate_add" type="text" id="_rate_add" value="Filled by user" size="15" ng-model="IncomingInvoiceNonExcise._rate_add" ng-change="RateChange()"/></td>
                  <td><input type="text" id="_amount_add" ng-model="IncomingInvoiceNonExcise._amount_add" readonly/></td>
                  <td><input name="_batch_no_add" type="text" id="_batch_no_add" value="filled by user" ng-model="IncomingInvoiceNonExcise._batch_no_add" /></td>
                  <td><input name="_exp_date_add" type="text" id="_exp_date_add" value="Filled by user" size="15" ng-model="IncomingInvoiceNonExcise._exp_date_add"/></td>
                  <td><input name="_landingprice_add" type="text" id="_landingprice_add" ng-model="IncomingInvoiceNonExcise._landingprice_add" readonly/></td>
                  <td><input name="_totallandingprice_add" type="text" id="_toallandingprice_add" ng-model="IncomingInvoiceNonExcise._toallandingprice_add" readonly/></td>
                  <td>        <label>
            <input class="button" type="button" name="add" id="add" ng-click="addItem()" value="+" />
        </label></td>
                </tr>
                <tr ng-repeat="item in IncomingInvoiceNonExcise._items">
                  <td><input name="_partno_1" type="text" id="_partno_1" ng-model="item._partno_1"></input>
                 <!--  <select name="_partno_1" id="_partno_1" ng-model="IncomingInvoiceNonExcise._partno_1">
                  </select>list from item master with check on principal--></td>
                  <td><input type="text" id="item_descp" name="item_descp" ng-model="item._item_descp"/></td>
                  <td><input name="qty" type="text" id="qty" value="filled by user" size="5" ng-model="item._qty" ng-change="RowChangeEvent(item);" valid-number/> </td>
                  <td><input name="rate" type="text" id="rate" value="Filled by user" size="15" ng-model="item._rate"  ng-change="RowChangeEvent(item);"/></td>
                  <td><input type="text" id="amount" ng-model="item._amount"/></td>
                  <td><input name="batch_no" type="text" id="batch_no" value="filled by user" ng-model="item._batch_no" /></td>
                  <td><input name="exp_date" type="text" id="exp_date" value="Filled by user" size="20" ng-model="item._exp_date"/></td>
                  <td><input name="landingprice" type="text" id="landingprice" ng-model="item._landing_price" readonly/></td>
                  <td><input name="totallandingprice" type="text" id="landingprice" ng-model="item._total_landing_price"  readonly/></td>
                  <td><input type="button"  class="button" name="add2" id="add2" value="-" ng-model="IncomingInvoiceNonExcise.deleteitem" ng-click="DeleteItem(item)" /></td>
                </tr>
              </table>
    </div>
    <div class="clr"></div>
    <div style="width:5%; float:right;">

    </div><div class="clr"></div>
    <div style="width:25%; float:right;">
        <label><span>Total Amount:</span>
            <input type="text" id="_total_amount_details" name="_total_amount_details" ng-model="IncomingInvoiceNonExcise._total_amount_details" placeholder="Total Amount"  readonly  required/>
        </label>
    </div><div class="clr"></div>
     <div style="width:25%; float:left;">
        <label>
            <span>Packing <!-- (E) -->:</span><!-- Filled by user -->
            <input name="textfield3" type="text" id="textfield3" ng-model="IncomingInvoiceNonExcise.Packing" ng-change="getLanding_Price();" text="Packing"></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Insurance<!-- (F) -->:</span><!-- Filled by user -->
            <input name="textfield" type="text" id="textfield" ng-model="IncomingInvoiceNonExcise.Insurance" ng-change="getLanding_Price();" placeholder="Insurance"></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Freight<!-- (G)Filled by user -->:</span>
            <input name="textfield2" type="text" id="textfield2" ng-model="IncomingInvoiceNonExcise.Freight" ng-change="getLanding_Price();" placeholder="Freight"></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Salse Tax<!-- (H)Filled by user -->:</span>
           <select name="textfield4" id="txtsaletax"  ng-model="IncomingInvoiceNonExcise.SaleTax" ng-change="setSaletax_Percent()" required>
                <option value="">Select Tax</option>
              <?php include( "../../Controller/Param/Param.php");
              $result =  Param::GetVATCSTList();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                  echo "<option value='".$row['SALESTAX_ID']."'>".$row['SALESTAX_DESC']."</option>";
              }?>
            </select>
        </label>
        <label>
            <input name="textfield4" type="text" id="textfield4" ng-model="IncomingInvoiceNonExcise.SaleTaxAmount"  placeholder="Salse Tax Amount" ng-change="getLanding_Price();" required></input>

        </label>
    </div>
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>Total Bill Value:</span><!-- SUM of A+E+F+G+H -->
            <input name="inv_no_p3" type="text" id="inv_no_p3" ng-model="IncomingInvoiceNonExcise.TotalBillValue" placeholder="Total Bill Value"  readonly required></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Received Date:</span>
            <input name="inv_no_p2" type="text" id="ReceivedDate" ng-model="IncomingInvoiceNonExcise._rece_date" placeholder="dd/mm/yyyy" required></input>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Comment's:</span>
            <textarea name="comm" id="comm" ng-model="IncomingInvoiceNonExcise.Comments" placeholder="Maximum Char 300."></textarea>
        </label>
    </div>
    <div class="clr"></div>
    <div align="center">
        <input type="submit" class="button" name="button3" id="btnsave" value="Save" />
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_INCOMINGINVOICENONEXCISE; ?>">Cancel</a></span>
        <input type="button" class="button" name="updateinvoice" id="btnupdate" value="Update" ng-click="UpdateInvoice()"/>
        <!-- <input type="button" class="button" name="button4" id="button4" value="Cancel" ng-click="CancelInvoice()"/>
        <input type="button" class="button" name="button5" id="button5" value="Delete" ng-click="DeleteInvoice()"/> -->
      </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#dt_p').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#dt_s').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#_exp_date_add').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#ReceivedDate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>



  <!-- <p>Landing Price=R+(E/SUM OF A)+(F/SUM OF A)+(G/SUM OF A)+(H/SUM OF A+E+F);</p>
  <p>Total Landing Price=Landing Price*QTY</p>
  <p>if BATCH NO IS filled then Expiry Date is Mandatory</p>
  <p>If Part no exist then only show &quot;Part No. Already Exist&quot; but not put the check.</p> -->

<br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

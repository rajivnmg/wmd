<?php 
include('root.php');
include($root_path."GlobalConfig.php");

 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stock Transfer</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/StockTransfer.js'></script>
</head>
<body ng-app="StockTransfer_app">
<?php  include("../../header.php") ?>
<form id="form1" name="form1" method="post" ng-controller="StockTransfer_Controller" ng-submit="SaveStockTransfer()" class="smart-green" data-ng-init="init('<?php echo $_GET['ID'];?>')">
<div align="center"><h1>Stock Transfer Form</h1></div><input type="text" id="_stId" ng-model="StockTransfer._stId" style="display:none"></hidden>

 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
   <td width="50%">

       <label>
        <span>Company:Multiweld Engg. Pvt Limited</span>
			<!-- <select ng-model="StockTransfer._stBuyerId">
			<option value="" title="select">Select Buyer</option>
				 <?php
					include( "../../Model/Param/param_model.php");
					//include("../../Model/DBModel/DbModel.php");
					$result =  ParamModel::GetParamList('COMPANY','MULTI');
					while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
						echo "<option value='".$row['USERID']."'>".$row['COMP_NAME']."</option>";
					   }
					   ?>
			</select> --><!-- MEPL Trading Account -->

   </td>
   <td width="25%">

		<label>
			<span>Through:</span>
			<select ng-model="StockTransfer._mode_delivery" required>
			  <option value="" title="select">Select Mode</option>
				 <?php
				 $result =  ParamModel::GetParamList('DELIVERY','MODE');
					   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
						   echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
					   }?>

			</select>
		</label>

   </td>
  <td width="25%">&nbsp;</td>
  </tr>
 </table>

 <table width="100%" border="0">
  <tr>
   <td width="50%">
    
	<?php
	if(isset($_GET['ID']) && ($_GET['ID'] != '')){
     echo'<div style="width:50%; float:left;"><label>
			<span>Invoice No.:</span>
				<input name="ino" type="text" id="ino" value="" ng-model="StockTransfer._stInvNo" placeholder="Invoice No" required readonly/>
		</label></div>';
	 }else{
		 echo'<input name="ino" type="hidden" id="ino" value="" ng-model="StockTransfer._stInvNo" placeholder="Invoice No" required readonly/>';
	 }
	 ?>
    
    <div style="width:50%; float:left;">
	   <label>
		<span>Invoice Date:</span>
	   <input type="text" name="ino2" id="invdate" placeholder="dd/mm/yyyy" ng-model="StockTransfer._stInvDate" required readonly/>
		</label>
      </div>
   </td>
      <td width="50%">
       <div style="width:50%; float:left;">
	    <label>
		   <span>Stock Transfer Time:</span>
		   <input type="text" name="ino4" id="st_time" ng-model="StockTransfer._st_time" placeholder="HH:MM" required/>
	    </label>
       </div>
       <div style="width:50%; float:left;">
		 <label>
			   <span>Dispatch Time:</span>
			   <input type="text" name="ino3" id="dispatchtime" ng-model="StockTransfer._dispatch_time" placeholder="HH:MM" required/>
		   </label>
       </div>
   </td>
  </tr>
 </table>

 <table width="100%" border="0">
  <tr>
   <td width="50%">
    <div style="width:50%; float:left;">
	 <label>
		<span>Principal:</span>
		<input type="text" name="country" id="autocomplete-ajax-principal" style=" z-index: 2; background: transparent;" placeholder="Type Principal Name" ng-model="StockTransfer._PrincipalName" required/><input type="text" id="principalid" ng-model="StockTransfer._stPrincipalId" style="display:none">
		<!-- <select ng-model="StockTransfer._stPrincipalId" ng-change="getPartNo()">
		<option value="" title="select">Select Principal</option>
			 <?php include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
				   $result =  Principal_Supplier_Master_Model::Get_Principal_List();
				   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
					   echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
				   }?>

		</select> -->
	 </label>
    </div>
    <div style="width:50%; float:left;">
     <label>
		<span>Supplier:</span>
		<input type="text" name="country" id="autocomplete-ajax-supplier" style=" z-index: 2; background: transparent;" placeholder="Type Supplier Name" ng-model="StockTransfer._SupplierName"/><input type="text" id="supplierid" ng-model="StockTransfer._stSupplrId" style="display:none">
		<!-- <select ng-model="StockTransfer._stSupplrId">
		<option value="" title="select">Select Supplier</option>
						<?php
						$result =  Principal_Supplier_Master_Model::Get_Supplier_List();
						while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
							echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
						}?>
		</select> -->
	 </label>
    </div>
   </td>
   <td width="50%">
    <div style="width:100%; float:left;">
            <label>
                <span>Supplier Stage:<br>
                1st&nbsp;Stage <input type="checkbox" name="s1" id="s1" value="1" ng-model="StockTransfer._Supplier_stage_1"/>
                 2nd&nbsp;Stage <input type="checkbox" name="s2" id="s2" value="2" ng-model="StockTransfer._Supplier_stage_2"/>
                 Free&nbsp;Sample <input type="checkbox" name="s3" id="s3" value="F" ng-model="StockTransfer._Supplier_stage_F"/></span>
            </label>
     </div>
   </td>
  </tr>
 </table>



    <div class="clr"></div>
    <div style="width:100%; float:left; overflow:scroll; height:300px;">
         <label><span>Invoice Detail</span></label>
         <table width="150%" border="0">
          <tr>
      <td width="3%">Part No.</td>
      <td width="24%">Description</td>
      <td width="5%">Incoming inv. No.</td>
      <td width="5%">Bal. Qty</td>
      <td width="5%">Issued Qty(A)</td>
      <td width="6%">Rate(R)</td>
      <td width="7%">Amount(B)</td>
      <td width="4%">ED Rate</td>
      <td width="5%">Duty/Unit</td>
      <td width="6%">EDAmt.</td>
      <td width="6%">Entry No.</td>
      <td width="4%">EDU%</td>
      <td width="5%">EDU Amount</td>
      <td width="4%">CVD%</td>
      <td width="6%">CVD Amt.</td>
      <td width="5%">&nbsp;</td>
    </tr>

    <tr>
      <td><input type="text" id="itemid" ng-model="StockTransfer.itemid" style="display:none"/>
           <input type="text" name="country" id="_code_part_no_add" style=" z-index: 2; background: transparent;width:60px;text-align:right;" placeholder="Partno" ng-model="StockTransfer._code_part_no_add" />
      </td>
      <td><input type="text" id="_item_descp_add" ng-model="StockTransfer._item_descp_add" style="width:280px;float:center;text-align:left;" readonly/></td>
      <td><select name="iinv_no_add" id="iinv_no_add" ng-model="StockTransfer._iinv_no_add" style="width:80px;float:center;text-align:right;" ng-change="getinv_rltInfo()"><option value="">Select Invoice</option></select><!--  List of incoming inv. no. for item on the basis of principal -->
      <input type="text" id="_iinv_no" ng-model="StockTransfer._iinv_no" style="display:none" /></td>

      <td><input type="text" ng-model="StockTransfer._bal_qty_add"    style="width:50px;float:center;text-align:right;" valid-number/><!-- filled by user --></td>
      <td><input type="text" ng-model="StockTransfer._issued_qty_add" style="width:50px;float:center;text-align:right;" ng-change="calEdAmt();checkStock(); calAmt();" is-number/><!-- filled by user --></td>
      <td><input type="text" ng-model="StockTransfer._price_add"      style="width:80px;float:center;text-align:right;" ng-change="calAmt()" isNumber readonly/><!-- A*R --></td>
      <td><input type="text" ng-model="StockTransfer._amt_add"        style="width:80px;float:center;text-align:right;" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer._ed_percent_add" style="width:40px;float:center;text-align:right;" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer._ed_perUnit_add" style="width:60px;float:center;text-align:right;" readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer._ed_amt_add"     style="width:80px;float:center;text-align:right;" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer._entryId_add"    style="width:90px;float:center;text-align:right;" readonly/><!-- FROM RG23D --></td>

      <td><input type="text" ng-model="StockTransfer._edu_percent_add" style="width:30px;float:center;text-align:right;" ng-change="calEduAmt();" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer.edu_amt"          style="width:50px;float:center;text-align:right;" readonly/></td>
      <td><input type="text" ng-model="StockTransfer._cvd_percent_add" style="width:30px;float:center;text-align:right;" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" ng-model="StockTransfer._cvd_amt_add"     style="width:90px;float:center;text-align:right;" valid-number readonly/><!-- FROM RG23D --></td>
      <td width="5%"><label><input type="button" class="button" name="add" id="add" value="+" ng-click="addItem()" /> </label></td>
    </tr>
    <tr ng-repeat="item in StockTransfer._items">
      <td><input type="text"  ng-model="item.itemid" style="display:none"/><input  name="st_codePartNo" style="width:60px;float:right;text-align:right;" ng-model="item._st_codePartNo"/></td>
      <td><input type="text"         style="width:280px;float:center;text-align:left;" name="codePartNo_desc" ng-model="item._codePartNo_desc" readonly/></td>
      <td><input type="text" name="iinv_no_add" id="iinv_no_add" ng-model="item._iinv_no_add"  style="width:80px;display:none;" /><input type="text" name="iinv_no" id="iinv_no" style="width:80px;float:center;text-align:right;displlay;none"  ng-model="item._iinv_no" valid-number/></td>

      <!--  List of incoming inv. no. for item on the basis of principal --></td>
      <td><input type="text" style="width:50px;float:center;text-align:right;"  name="bal_qty" ng-model="item._bal_qty"  valid-number/><!-- accoring to adjacent bal. qty. --></td>
      <td><input type="text" style="width:50px;float:center;text-align:right;"  name="issued_qty" ng-model="item._issued_qty" ng-change="ChangeRowOnUpdate(item);" valid-number/><!-- filled by user --></td>
      <td><input type="text" style="width:80px;float:center;text-align:right;"  name="price" ng-model="item._price" valid-number readonly/><!-- filled by user --></td>
      <td><input type="text" style="width:80px;float:center;text-align:right;"  name="amt" ng-model="item._amt" valid-number readonly/><!-- A*R --></td>
      <td><input type="text" style="width:40px;float:center;text-align:right"   name="ed_percent" ng-model="item._ed_percent" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" style="width:60px;float:center;text-align:right;"  name="ed_perUnit" ng-model="item._ed_perUnit" readonly/><!-- FROM RG23D --></td>
      <td><input type="text" style="width:80px;float:center;text-align:right;"  name="ed_amt" ng-model="item._ed_amt" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" style="width:90px;float:center;text-align:right;"  name="entryId" ng-model="item._entryId" readonly/><!-- FROM RG23D --></td>

      <td><input type="text" style="width:30px;float:center;text-align:right;"  name="edu_percent" ng-model="item._edu_percent" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" style="width:50px;float:center;text-align:right;" ng-model="item.edu_amt" readonly/></td>
      <td><input type="text" style="width:30px;float:center;text-align:right;" id="cvd_percent" name="cvd_percent" ng-model="item._cvd_percent" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="text" style="width:90px;float:center;text-align:right;" id="cvd_amt" name="cvd_amt" ng-model="item._cvd_amt" valid-number readonly/><!-- FROM RG23D --></td>
      <td><input type="button" class="button" name="add2" id="add2" value="-" ng-model="IncomingInvoiceNonExcise.deleteitem" ng-click="DeleteItem(item)" /></td>
    </tr>
  </table>
    </div><div class="clr"></div>
     <div class="clr"></div>
    <div style="width:50%; float:left;">
        <div style="width:50%; float:left;">
        <label>
            <span>Discount(%):</span>
            <input type="text" name="ino6" id="_discount" ng-model="StockTransfer._discount" ng-change="add_discount();" style="text-align:right;" is-number/>
        </label>
        </div>
        <div style="width:50%; float:left;">
            <label>
                <span>Tax:</span>
                 <select name="_saleTax" id="_isaleTax" ng-model="StockTransfer._saleTax" required>
                    <option value="">Select</option>
                    <option value="7" >Sales Tax Exempted Unit</option>
                  </select>
             </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:left;">
            <label>
                <span>Print Type:</span>
                <select name="buyer8" id="buyer8" ng-model="StockTransfer._printType" required>
                    <option value="">Select Print</option>
                    <option value="A">ORIGINAL FOR BUYER</option>
                    <option value="B">DUPLICATE FOR TRANSPORTER</option>
                    <option value="C">COPY</option>
                    <option value="D">QUADUPLICATE FOR ASSESSE</option>
                    <option value="E">TRIPLICATE FOR CENTRAL EXCISE</option>
                  </select>
            </label>
        </div>
        <div style="width:50%; float:left;">
         <label>
          <span>Excise Duty Applicability:<br>
          <input type="checkbox" name="inclusive" id="iinclusive" ng-model="StockTransfer._Inclusive" ng-click="inclusiveTag();" />&nbsp;Inclusive(checked if ED applicable col. is Inclusive in basic price)
          <input type="text" name="_Inclusive_Tag" id="iInclusive_Tag" ng-model="StockTransfer._Inclusive_Tag" style="display:none" />
          </span>
         </label>
        </div>
        <div class="clr"></div>
        <div style="width:100%; float:left;">
            <label>
                <span>Remarks:</span>
                <textarea name="rem" id="rem" cols="45" rows="5" ng-model="StockTransfer._remarks"></textarea>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
        <div style="width:50%; float:right;">
            <label>
                <span>Total Amount:</span>
                <input name="ino13" type="text" id="ino13" value="SUM OF B+ED AMT+CVD AMT-DISCOUNT" ng-model="StockTransfer._total_amt" style="text-align:right;" valid-number readonly />
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; float:right;">
            <label>
                <span>Total ED:</span>
                <input type="text" name="ino7" id="ino7" ng-model="StockTransfer._total_ed" style="text-align:right;" valid-number  readonly/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div align="center">
        <input type="submit" class="button" name="button3" id="btnsave" value="Save" />
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_STOCK_TRANSFER; ?>">List</a></span>
       <!--  <input type="button" class="button" name="button4" id="button4" value="Cancel"/>
        <input type="button" class="button" name="button5" id="button5" value="Delete"/>
        <input type="button" class="button" name="button6" id="button6" value="Print"/>
         <input type="button" class="button" name="button7" id="btnupdate" value="Update" ng-click="UpdateStockTransfer()"/>-->
    </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>

$('#st_time').datetimepicker({
	lang:'en',
	datepicker:false,
	format:'H:i',step:5
});
$('#dispatchtime').datetimepicker({
	lang:'en',
	datepicker:false,
	format:'H:i',step:5
});
</script>
</form><br/><br/><br/><br/><br/>
<?php include("../../footer.php") ?>
</body>
</html>

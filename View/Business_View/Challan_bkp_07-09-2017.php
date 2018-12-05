<?php
include('root.php');
include($root_path."GlobalConfig.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Challan</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>

<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>

<script type='text/javascript' src='../js/Business_action_js/Challan.js'></script>
</head>
<body ng-app="Challan_app">
<?php include($root_path."header.php") ?>
<form id="form1" name="form1" method="post" ng-controller="Challan_Controller" ng-submit="SaveChallan('<?php echo $_GET['ID'];?>');" data-ng-init="init('<?php echo $_GET['ID'];?>')" class="smart-green">
<hidden id="_ChallanId" ng-model="Challan._ChallanId"></hidden>
<hidden id="_CustId" ng-model="Challan._CustId"></hidden>
<div>
<div align="center"><h1>Challan Form</h1></div>
    <div style="width:25%; float:left;">
        <label>
            <span>Buyer Name:</span>
              <input type="text" id="buyerid" ng-model="Challan._BuyerId" style="display: none;"> 
            <input type="text" name="country" id="autocomplete-ajax-buyer" style="z-index: 2; background: transparent;" placeholder="Type Buyer Name" ng-model="Challan._BuyerName" onKeyPress="loadBuyerByName(this.value);" required/>
            
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Address:</span>
            <textarea name="textarea" id="txt_oldbuyer_add" ng-model="Challan._CustAddress" placeholder="Address" required readonly></textarea>
            <!-- Automatically come from Buyer Master -->
        </label>
    </div>
    <div style="width:25%; float:left;" id="new_buyer" name="new_buyer">
        <label>
            <span>Customer Name:</span>
            <input name="textfield" type="text" id="txt_new_buyer_name" ng-model="Challan._CustName" placeholder="Customer Name" required readonly />
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Customer Contact Person Name:</span>
            <input type="text" name="challan_no7" id="challan_no7" placeholder="Customer Contact Person Name" ng-model="Challan._cust_contact_name" required/>
        </label>
    </div>
    <div class="clr"></div>
   
		<?php
		if(isset($_GET['ID']) && ($_GET['ID'] != '')){
        echo' <div style="width:25%; float:left;"><label>
            <span>Challan Number:</span>
            <input  type="text" id="challanId" ng-model="payment.ChallanId" style="display: none;" /> 
            <input type="text" name="challan_no" id="challan_no" ng-model="Challan._ChallanNo" placeholder="Challan Number" readonly/>
        </label></div>';
		}else{
		echo'<input  type="hidden" id="challanId" ng-model="payment.ChallanId" style="display: none;" /> <input type="hidden" name="challan_no" id="challan_no" ng-model="Challan._ChallanNo" placeholder="Challan Number" readonly/>
			</label>';
		}
		?>
    
    <div style="width:25%; float:left;">
        <label>
            <span>Challan Date:</span>
            <input type="text" name="challan_no2" id="challan_date" ng-model="Challan._ChallanDate" placeholder="yyyy-mm-dd" required readonly/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>G.C Note:</span>
            <input type="text" name="challan_no3" id="challan_no3" ng-model="Challan._gc_note" placeholder="G.C Note"/>
        </label>
    </div>
    <div style="width:22%; float:left;">
        <label>
            <span>Date:</span>
            <input type="text" name="challan_no4" id="date1" ng-model="Challan._gc_note_date" placeholder="yyyy-mm-dd"/>
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>Executive:</span>
            <select name="select3" id="select2" ng-model="Challan._ExecutiveId"  required>
            <option value="">Select Executive</option>
              <?php
             
              $result =  UserModel::LoadExecutive();
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['USERID']."'>".$row['USER_NAME']."</option>";
              }?>
            </select>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Purpose:</span>
            <select name="select2" id="select" ng-model="Challan._Challan_Purpose" required>
                <option value="">Select Purpose</option>
                <option value="1">Free Sample</option>
                <option value="2">Returnable Sample</option>
                <option value="3">Loan Basis</option>
                <option value="4">Replacement</option>
                <option value="5">To be Billed</option>
             </select>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Chalan Status:</span>
            <select name="Challan_Status" id="Challan_Status" ng-model="Challan._Challan_Status" ng-Change="getSetInfo();"  required>
                <option value="1">Open</option>
				<option value="6">Free Sample</option>
				<option value="7">Close against Loan settlement</option>
                <option value="2">Close Without Outgoing Invoice</option>
                <option value="3">Close With Outgoing Excise</option>
                <option value="4">Close With Outgoing Non-Excise</option>
                <option value="5">Close With Outgoing Excise & Non-Excise</option>

               
             </select>
        </label>
    </div>
       <div style="width:22%; float:left;">
        <label>
            <span>Outgoing Invoice No:</span>
            <input type="text" id="InvoiceType" ng-model="Challan._InvoiceType" style="display: none;"> 
            <input type="text" name="OutgoingInvoiceNo1" id="OutgoingInvoiceNo1" ng-model="Challan._OutgoingInvoiceNo1" placeholder="Invoice Number1" />
            
            <input type="text" name="OutgoingInvoiceNo2" id="OutgoingInvoiceNo2" ng-model="Challan._OutgoingInvoiceNo2" placeholder="Invoice Number2" />
           
            <input type="text" name="OutgoingInvoiceNo3" id="OutgoingInvoiceNo3" ng-model="Challan._OutgoingInvoiceNo3" placeholder="Invoice Number3"/>
        </label>
    </div>
   
     <div class="clr"></div>
      <div style="width:25%; float:left;"> <label>
            <span>Loan Challan Number:</span>
            <input type="text" name="loanno" id="loanno" ng-model="Challan.loanNo" placeholder="Loan Number" valid-number />
        </label></div>
      <div style="width:25%; float:left;">&nbsp;</div>
      <div style="width:25%; float:left;">
        <label>
            <span>Your Order Number:</span>
            <input type="text" name="challan_no5" id="challan_no5" ng-model="Challan._OrderNo" placeholder="Your Order Number" valid-number />
        </label>
        
    </div>
    <div style="width:22%; float:left;">
        <label>
            <span>Order Date:</span>
            <input type="text" name="challan_no6" id="date2" ng-model="Challan._OrderDate" placeholder="yyyy-mm-dd" />
        </label>
    </div>
    <div class="clr"></div>
    <div style="width:100%; float:left; overflow:scroll; height:300px;">
           <label><span>Item Description</span></label>
             <table width="100%" border="0">
                <tr>
                    <td width="5%" ><label><span>Sr No.</span></label></td>
                    <td width="23%" align="center"><label><span>Principal</span></label></td>
                    <td width="15%" align="center"><label><span>Item Code Part Number</span></label></td>
                    <td width="40%" align="center"><label><span>Description</span></label></td>
                    <td width="10%" align="center"><label><span>Quantity</span></label></td>
                    <td width="7%" align="center"><label><span align="center">Action</span></label></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                     <input type="text" name="" id="autocomplete-ajax-principal" style=" z-index: 2; background: transparent;" tabindex="5" placeholder="Type Principal Name" ng-model="Challan._principalname" required/>
                     <input type="text" id="principalid" ng-model="Challan._principalID" style="display: none;"> 	
                    </td>
                    <td>
                    <input type="text" id="itemid" ng-model="Challan.itemid" style="display: none;"> 
                     <input type="text" name="_code_part_no_add" id="_code_part_no_add" style=" z-index: 2; background: transparent;" placeholder="Type Item Code Part"  ng-model="Challan._code_part_no_add" required/>
                   
                    </td>
                    <td><input type="text" ng-model="Challan.item_desc" name="item_desc" id="item_desc" readonly/></td>
                    <td><input type="text" ng-model="Challan._qty_add" name="_qty_add" id="_qty_add" is-number required/></td>
                    <td><label><input type="button" class="button" name="additem" id="additem" value="Add" ng-click="addItem()" /></label></td>
                </tr>
                <tr ng-repeat="item in Challan._items">
                    <td width="5%"><input type="text" name="SrNo" id="SrNo" ng-model="item._SrNo"/></td>
                    <td width="23%"><input type="text" ng-model="item._principalname" style="width:338px;" />
                        <input type="text" ng-model="item._principalID" style="display: none;" />
                    </td>
                    <td width="15%"><input type="text" ng-model="item._code_part_no"  style=" z-index: 2; background: transparent;"  />
                        <input type="text" ng-model="item.itemid" style="display: none;" /></td>
                    <td width="40%"><input type="text"  ng-model="item.item_desc" readonly/></td>
                    <td width="10%"><input type="text" ng-model="item._qty" ng-change="getTotal_Qty();"  valid-number/></td>
                    <td width="7%"><input type="button" class="button" name="deleteitem" id="deleteitem"value="X" ng-model="Challan.deleteitem" ng-click="DeleteItem(item)"/></td>
                </tr>
                </table>
    </div><div class="clr"></div>
    <div class="clr"></div></br>
	
	<div align="center" style="width:70%; float:left;">
           <label><span>Comments</span>
           <textarea name="textarea3" id="txtcomment" ng-model="Challan._remarks" placeholder="Maximum Char. 600" class="ng-pristine ng-valid"></textarea>
           <!-- (max char 600) -->
           </label>
        </div>
    <div style="width:25%; float:right;">
        <label>
            <span>Total Quantity:</span>
            <input type="text" id="_total_Qty" name="_total_Qty" ng-model="Challan._total_Qty" placeholder="Total Quantity" required readonly/>
        </label>
    </div>
    <div class="clr"></div></br>
    <div align="center">
        <input type="submit" class="button" name="button3" id="savechallan" value="Save" />
        <input type="button" class="button" name="Update" id="updatechallan" value="Update" ng-click="UpdateChallan()"/> 
        <span><a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEW_CHALLAN; ?>">Cancel</a></span>
		 <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINTCHALLANPDF.'?TYP=SELECT&ID='.$_GET['ID']; ?>" target="_blank">Print View</a> 
       
    </div>
    <br/><br/><br/>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#challan_date').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#date1').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#date2').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
  </form>
  <?php include($root_path."footer.php") ?>
</body>
</html>

<?php
include('root.php');
include($root_path."GlobalConfig.php");
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>New Quotation</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>

<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
    
<script type='text/javascript' src='../js/Business_action_js/quotation.js'></script> 
</head>
<body ng-app="quotation_app">
<?php include("../../header.php") ?>
<form id="form1" ng-controller="quotation_Controller" ng-submit="AddQuotation();" data-ng-init="init('<?php echo  $_GET['QUOTATIONNUMBER'];?>')"  class="smart-green"> 
<div>
    <div align="center">
    <h1>Quotation Form</h1>
    </div>
       
		<?php if(isset($_GET['QUOTATIONNUMBER']) && ($_GET['QUOTATIONNUMBER'] !='')){
          echo' <div style="width:25%; float:left;"><label>
                <span>Quotation No*:<hidden id="txtquotation_id" ng-model="quotation._quotation_id"></hidden></span>
                <input name="textfield10" type="text" id="txtquotation_no" ng-model="quotation._quotation_no" placeholder="Quotation Number" readonly required></input>
           </label>  </div>';
		   }else{
			echo'<hidden id="txtquotation_id" ng-model="quotation._quotation_id"></hidden>
                <input name="textfield10" type="hidden" id="txtquotation_no" ng-model="quotation._quotation_no" placeholder="Quotation Number" readonly required></input>';
		   }
		   
		   ?>
      
        <div style="width:25%; float:left;">
           <label>
                <span>Quotation Date : </span>
                <input name="txtdate" type="text" id="txtquotationdate" ng-model="quotation._quotation_date" readonly></input>
               
           </label>
        </div>
        <div style="width:25%; float:left;">
           <label>
                <span>Customer Reference Source:</span>
                <input name="txtcustomer_ref_no" type="text" id="txtcustomer_ref_no" ng-model="quotation._coustomer_ref_no" placeholder="Customer Reference Number" required></input>
           </label>
        </div>
        <div style="width:25%; float:left;">
           <label>
                <span>Customer Reference Date:</span>
                <input name="txtdate" type="text" id="txtdate" ng-model="quotation._coustomer_ref_date" placeholder="dd-mm-yyyy" required></input>
                
           </label>
        </div>
        <div class="clr"></div>
        <div style="width:25%; float:left;">
           <label>
                <span>Buyer Name:</span><hidden id="buyerid" ng-model="quotation._buyer_id"></hidden>
                <input type="text" name="country" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" placeholder="Type Buyer Name" ng-model="quotation._coustomer_name" onKeyPress="loadBuyerByName(this.value);" required/>
                
           </label>
        </div>
        <div style="width:25%; float:left;">
           <label>
                <span>Address:</span>
                <textarea name="txt_oldbuyer_add" id="txt_oldbuyer_add"  ng-model="quotation._coustomer_add" placeholder="Address" required readonly></textarea>
				<input type="hidden" name="buyer_state_id" id="buyer_state_id" value="1" />
           </label>
        </div>
        <!--<div style="width:25%; float:left;" id="new_buyer">
           <label>
                <span>Customer Name:</span>
                <input name="textfield" type="text" id="txt_new_buyer_name" ng-model="quotation._coustomer_name" placeholder="Customer Name"  required readonly/>
           </label>
        </div>-->
        <div style="width:25%; float:left;">
           <label>
                <span>Kind Attention*:</span>
                <input name="textfield4" type="text" id="txt_contact_persone" ng-model="quotation._contact_persone" placeholder="Contact Person"  required/>
           </label>
        </div>
        <!--<div class="clr"></div>-->
        
        <div style="width:25%; float:left;">
           <label>
                <span>Product:</span><hidden id="principalid" ng-model="quotation._principal_id"></hidden>
                <input type="text" name="country" ng-model="quotation._principal_name" id="autocomplete-ajax-principal" style="z-index: 2; background:transparent;"                    placeholder="Type Principal Name" required"/> <!-- ng-change="getprincipal();" -->
                <!-- <select name="select3" id="principal"  ng-model="quotation._principal_id" ng-change="getprincipal();">
                    <option value="">Select Principal</option>
                    <?php
                    include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                    $result =  Principal_Supplier_Master_Model::Get_Principal_List();
                    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                        echo "<option value='".$row['Principal_Supplier_Id']."'>".$row['Principal_Supplier_Name']."</option>";
                    }?>
                  </select> -->
           </label>
        </div>
        <div class="clr"></div>
        <div style="width:100%; float:left; height:300px; overflow:scroll;">
           <label><span>Item Description</span></label>
           <table width="100%" border="0">
                <tr>
                  <td width="2%">Sr. No.</td>
                  <td width="18%"><label><span>Item Code Part No*</span></label></td>
                  <td width="25%"><div align="center"><label><span>Material Description</span></label></div></td>
                  <td width="5%"><label><span>Unit*</span></label></td>
                  <td width="10%"><label><span>Quantity*/MOQ</span></label></td>
                  <td width="10%"><label><span>Rate/Per Unit*</span></label></td>
                  <td width="10%"><label><span>Discount (in %)</span></label></td>
				  <!-- BOF for adding GST by Ayush Giri on 19-06-2017 -->
				  <td width="15%"><label><span>HSN Code</span></label></td>
				  <td width="5%"><label><span>CGST Rate(%)</span></label></td>
				  <td width="5%"><label><span>SGST Rate(%)</span></label></td>
				  <td width="5%"><label><span>IGST Rate(%)</span></label></td>
				  <!-- EOF for adding GST by Ayush Giri on 19-06-2017 -->
                </tr>
                <tr>
				  <td></td>
                  <td><hidden id="itemid" ng-model="quotation.itemid"></hidden>
                  <!-- <hidden id="itemcodepart" ng-model="quotation._item_code_part_no"></hidden> -->
                  <input type="text" name="country" id="item_master" style=" z-index: 2; background: transparent;" placeholder="Type Item Code Part" ng-model="quotation._item_code_part_no" />
                  
                  <td><input name="textfield13" type="text" id="txtitem_desp"  ng-model="quotation._item_descp"  value="" readonly/>
                  <!-- filled on select the part no -->
                  </td>
                  <td>
                  <hidden id="unit_masterid"  ng-model="quotation._unit_id"></hidden>
                  <input  type="text" id="unit_master"  ng-model="quotation._unit_name" readonly/>
                  </td>
				  
                  <td><input name="textfield12" type="text" id="txtitemquantity"  ng-model="quotation._quantity" value="" isNumber/>
                  <!-- filled by user -->
                  </td>
                  <td><input name="textfield14" type="text" id="txtitemprice"  ng-model="quotation._price_per_unit" value="" isNumber/>
                  <hidden id="oldprice" ng-model="quotation._item_base_price" ></hidden>
                  </td>  <!-- Rate comes from item master on the basis of code/part no. and principal master -->
				  <td>
                  <input  type="text" id="item_discount"  ng-model="quotation._item_discount" isNumber/>
                  </td>
				  <!-- BOF for adding GST by Ayush Giri on 19-06-2017 -->
				  <td>
                  <input  type="text" id="hsn_code"  ng-model="quotation._hsn_code" readonly/>
                  </td>
				  <td>
                  <input  type="text" id="cgst_rate"  ng-model="quotation._cgst_rate" readonly/>
                  </td>
				  <td>
                  <input  type="text" id="sgst_rate"  ng-model="quotation._sgst_rate" readonly/>
                  </td>
				  <td>
                  <input  type="text" id="igst_rate"  ng-model="quotation._igst_rate" readonly/>
                  </td>
				  <!-- EOF for adding GST by Ayush Giri on 19-06-2017 -->
                </tr>
                <tr ng-repeat="item in quotation._items">
				   <td>{{$index + 1}}</td>
                   <td><hidden ng-model="item.itemid"></hidden><input type="text" ng-model="item._item_code_part_no" /></td>
                   <td><input type="text" ng-model="item._item_descp" /></td>
                   <td><input type="text" ng-model="item._unit_name" /></td>
                   <td><input type="text" ng-model="item._quantity"></td>
                   <td><input type="text" ng-model="item._price_per_unit"/></td>
                   <td><input type="text" ng-model="item._item_discount" /></td>
				   <!-- BOF for adding GST by Ayush Giri on 19-06-2017 -->
				   <td><input type="text" ng-model="item._hsn_code" /></td>
				   <td><input type="text" ng-model="item._cgst_rate" /></td>
				   <td><input type="text" ng-model="item._sgst_rate" /></td>
				   <td><input type="text" ng-model="item._igst_rate" /></td>
				   <!-- EOF for adding GST by Ayush Giri on 19-06-2017 -->
                   <td>
                   <input type="button" name="button6" id="button60" value="X" ng-model="quotation.deleteitem" ng-click="removeItem(item)" class="button"/></td>
                </tr>
              </table>
        </div>
        <div class="clr"></div>
        <div style="width:5%; float:right;">
            <label>
                <input type="button" name="button6" id="button6" value="Add" class="button" ng-model="quotation.additem" ng-click="addItem()" />
            </label>
        </div><div class="clr"></div>
        <div style="width:100%; float:left;">
           <label><span>Terms &amp; conditions</span></label>
        </div>
        <!--Comment by gajendra -->
        <!--<div style="width:25%; float:left;">
           <label><span>Discount (in %)</span>
           <input name="txtdiscount" type="text" id="txtdiscount" ng-model="quotation._discount" placeholder="Discount" is-number/>
           <!-- if buyer is exist then Discount comes from Buyer master on the basis of principal master &amp; editable -->
          <!-- </label>
        </div>-->
        <div style="width:25%; float:left;">
           <label><span>Delivery</span>
           <select name="select7" id="delivery"  ng-model="quotation._delivery">
              <option value="">Select Delivery</option>
              <?php
              include( "../../Controller/Param/Param.php");
              $result =  Param::GetParam("PARAM_CODE","LIST");
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                  echo "<option value='".$row['PARAM_VALUE2']."'>".$row['PARAM_VALUE1']."</option>";
              }?>
            </select>
           </label>
        </div>
        <!--<div style="width:25%; float:left;">
           <label><span>Sales Tax</span>
           <!-- <input name="textfield8" type="text" id="sales" ng-model="quotation._sales_tax" placeholder="Sales Tax" required/> -->
           <!--<select name="select2" id="sales"  ng-model="quotation._sales_tax"><option value="">Select Tax</option></select>
           </label>
        </div>-->
        <div style="width:25%; float:left;">
                <label>
                    <span>Freight Tag:</span>
                    <select name="frgt" id="ifrgt" ng-model="quotation.frgt1" ng-change="getFreight();"  tabindex="42">
                    <option value="">Select Freight Tag</option>
                        <?php include( "../../Model/Param/param_model.php");
			                $result =  ParamModel::GetParamList('CHARGE','FREIGHT');
			                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			                      echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
                        }?>
                      </select>
                </label>
                <input type="hidden" name="frgt" id="ifrgt"  ng-model="quotation.frgt" />
          </div>
        <div class="clr"></div>
        <div style="width:25%; float:left;">
           <label><span>Incidental Charges (in %)</span>
           <input name="textfield7" type="text" id="txtincidental" ng-model="quotation._incidental_chrg" placeholder="Incidental Charges"/>
           <!-- filled by user -->
           </label>
        </div>
        <div style="width:25%; float:left;">
           <label><span>Credit Period</span>
           <input name="textfield6" type="text" id="txtcreditperiod" ng-model="quotation._credit_period" placeholder="Credit Period" required />
           <!-- If Buyer name is exist then value come from buyer master but it will editable for modification -->
           </label>
        </div>
        <div style="width:25%; float:left;" id="ifrgp">
           <label><span>Freight (in %)</span>
           <input name="textfield8" type="text"  ng-model="quotation.frgp" placeholder="Freight (%)" is-number/>
           <!-- Filled by user not manadatory -->
           </label>
        </div>
        <div style="width:25%; float:left;" id="ifrga">
           <label><span>Freight (in Amount)</span>
           <input name="textfield8" type="text"  ng-model="quotation.frga" placeholder="Freight Amount" is-number/>
           <!-- Filled by user not manadatory -->
           </label>
        </div>
        <div class="clr"></div>
        <!--<div style="width:25%; float:left;">
           <label><span>Excise Duty Tag</span>
           <select name="select4" id="cess" ng-model="quotation._ed_edu_tag" required>
           <?php 
			//$result =  ParamModel::GetParamList('EXCISEDUTY','APPLICABLE');
			//while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			//        echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
            //}?>
                <!-- <option value="">Select Educational Cess Tag Type</option> -->
               <!--  <option value="N">No</option>
                <option value="Y">Yes</option> -->
           <!--</select>
           </label>
        </div>
        <div style="width:25%; float:left;">
           <label><span>CVD (in %)</span>
           <input name="hedu" type="text" id="txtcvd" ng-model="quotation._cvd" placeholder="CVD %"  is-number/>
           </label>
        </div> -->
		<div style="width:25%; float:left;">
           <label><span>P & F Charges (in %)</span>
           <input name="pnf" type="text" id="txtpnf" ng-model="quotation._pnf" placeholder="P & F Charges %" />
           </label>
        </div>
		<div style="width:25%; float:left;">
           <label><span>Insurance Charges (in %)</span>
           <input name="ins" type="text" id="txtins" ng-model="quotation._ins" placeholder="Insurance Charges %"/>
           </label>
        </div>
		<div style="width:25%; float:left;">
           <label><span>Other  Charges (in %)</span>
           <input name="othc" type="text" id="txtothc" ng-model="quotation._othc" placeholder="Other Charges %"/>
           </label>
        </div>
        <div class="clr"></div>
        <div align="center" style="width:80%; float:left;">
           <label><span>Remarks</span>
           <textarea name="textarea3" id="txtcomment"  ng-model="quotation._remarks" placeholder="Maximum Char. 300"></textarea>
           <!-- (max char 300) -->
           </label>
        </div>
        <div class="clr"></div>
    </div>
    <div  align="center">
        <input type="submit" class="button" name="button3" id="btnsave" value="Save"></input>
		<input type="submit" class="button" name="button33" id="btnsaveCopy" value="Make a Copy"></input>
        <input type="button" class="button" name="button6" id="btnupdate" value="Update" ng-click="Update()"/>
        <a class="button" style="text-decoration: none;" href="<?php print SITE_URL.VIEWQUATION; ?>">Cancel</a>
         <a class="button" style="text-decoration: none;" id="btnprint" href="<?php print SITE_URL.PRINTQUATIONPDF.'?TYP=SELECT&QUOTATIONNUMBER='.$_GET['QUOTATIONNUMBER']; ?>" target="_blank">Print View</a> 
        <!-- <input type="button" class="button" name="button4" id="button4" value="Cancel"/>
        <input type="button" class="button" name="button5" id="button5" value="Print"/> -->
    <div class="clr"></div>  
    </div><div class="clr"></div>
    
   <br/><br/><br/><br/>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#txtdate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d',
	scrollInput:false
});
</script>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

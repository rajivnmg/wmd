<?php
include('root.php');
include($root_path."GlobalConfig.php");
include( $root_path."Model/Masters/BuyerMaster_Model.php");
include( $root_path."Model/Param/param_model.php");

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Outgoing Invoice Payment</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<link href="../css/web_dialog.css" media="all" rel="stylesheet" type="text/css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Business_action_js/pay.js'></script>
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
<script type='text/javascript'>
    $(document).ready(function(){
    
     $("#btnShowSimple").click(function (e)
      {
         ShowDialog(false);
         e.preventDefault();
      });

      $("#btnShowModal").click(function (e)
      {
         ShowDialog(true);
         e.preventDefault();
      });

      $("#btnClose").click(function (e)
      {
         HideDialog();
         e.preventDefault();
      });
    });
</script>
<script type='text/javascript'>
     function ShowDialog(modal)
   {
      $("#overlay").show();
      $("#dialog").fadeIn(300);

      if (modal)
      {
         $("#overlay").unbind("click");
      }
      else
      {
         $("#overlay").click(function (e)
         {
            HideDialog();
         });
      }
   }

   function HideDialog()
   {
      $("#overlay").hide();
      $("#dialog").fadeOut(300);
   } 
        
</script>
</head>
<body ng-app="pay_app">
<?php include("../../header.php") ?>
<form id="form1" ng-controller="pay_Controller" ng-submit="AddPAY()" data-ng-init="init('<?php echo($_GET['trxnId']) ?>')" class="smart-green">

<div>
<div align="center"><h1>Outgoing Invoice Payment Form</h1></div>

<?php if(isset($_GET['trxnId']) && ($_GET['trxnId'] =='') || $_GET['trxnId'] == 0 || empty($_GET['trxnId'])) { ?>
		  <div style="width:50%; float:left;" id="c_b">
           
                    <label><span>Financial Year:</span> <br/><br/>
                    
           <?php
		            $data = file_get_contents("../../finyear.txt"); //read the file
		            $convert = explode("\n", $data); //create array separate by new line
		            for ($i=0;$i<count($convert);$i++){ 	
						if($i==0){
							 echo '<span style="float:left;"></span><input type="checkbox" name="financialyear" id="financialyear'.$i.'" ng-click="enabledDebit();" class="ng-pristine ng-valid"  style="margin-left:15px;"  value="'.trim($convert[$i]).'" checked="checked">'.$convert[$i].'</span>';
						}else{				
					  echo '<span style="float:left;"></span><input type="checkbox" name="financialyear" id="financialyear'.$i.'" ng-click="enabledDebit();" class="ng-pristine ng-valid"  style="margin-left:15px;"  value="'.trim($convert[$i]).'">'.$convert[$i].'</span>';
					  }
                    }
		   ?>
           </select>
           </label>
        </div> 
            	
 <?php } ?>       
          
    </div>
    <div class="clr"></div>
<div class="clr"></div> 

<?php 
 if(isset($_GET['trxnId']) && ($_GET['trxnId'] != '')){
echo'<div style="width:50%; float:left;">
  <div style="width:50%; float:left;">
 <label>
    <span>Payment Reference Number*:</span>
    <input  type="text" id="trxnid" ng-model="payment.trxnId" style="display: none;" value="'.$_GET['trxnId'].'" /> 
    <input name="trnxNo" type="text" id="trnxNo" ng-model="payment.trnx_no"  readonly ></input>
  </label>
   </div><div class="clr"></div>
</div> ';
}else{
 echo'<div style="width:50%; float:left;">
 <label>
  
    <input  type="text" id="trxnid" ng-model="payment.trxnId" style="display: none;" value="" /> 
    <input name="trnxNo" type="hidden" id="trnxNo" ng-model="payment.trnx_no"  readonly ></input>
  </label>
   </div><div class="clr"></div>
</div> ';

}
?>
 <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Payment Mode</span>
                         <select name="trntype" id="trntype" ng-change="enabledBankDetail();" ng-model="payment.trn_type"  required>
                          <option value="">Select Payment Mode</option>
                          <?php 
                                 $result =  ParamModel::GetParamList('TRXN','MODE');
                                  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                                  echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
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
    <span>Buyer Name*:</span>
   
    <input  type="text" id="buyerid" ng-model="payment.bn" style="display: none;" />
    <input type="text" name="buyer_name" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Buyer Name" ng-model="payment.bn_name" onKeyPress="loadBuyerByName(this.value);" required/>
  </label>
</div></div>
 <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Payment Date</span>
                         <input name="trxndate" type="text" id="trxndate" ng-model="payment.trxn_date" placeholder="yyyy-mm-dd"  required></input>
                    </label>
            </div><div class="clr"></div>
    </div>
<div class="clr"></div>

<div style="width:100%; float:left;">
 <label>
    <span style="font-weight: bold;font-size:15px;">Bank Detail</span>
  </label>  
</div>
 <div class="clr"></div>
    <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Bank Name</span>
                        <input name="bankname" type="text" id="bankname" ng-model="payment.bank_name"  readonly />
                    </label>
            </div><div class="clr"></div>
    </div>
       <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Branch Name</span>
                        <input name="branchname" type="text" id="banchname" ng-model="payment.branch_name"   readonly />
                    </label>
            </div><div class="clr"></div>
    </div>
<div class="clr"></div>
<div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Cheque Number</span>
                        <input name="chequeno" type="text" id="chequeno" ng-model="payment.cheque_no" tabindex="2" readonly />
                    </label>
            </div><div class="clr"></div>
 </div>
       <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Cheque Date</span>
                        <input name="chequedate" type="text" id="chequedate" ng-model="payment.cheque_date" placeholder="yyyy-mm-dd" readonly></input>
                    </label>
            </div><div class="clr"></div>
    </div>
<div class="clr"></div>
<div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>Account Number</span>
                        <input name="accountno" type="text" id="accountno" ng-model="payment.cheque_account_no" tabindex="2" readonly />
                    </label>
                     
            </div><div class="clr"></div>
    </div>
       <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                    <label>
                        <span>UTR Number</span>
                        <input name="utrno" type="text" id="utrno" ng-model="payment.utr_no" tabindex="2" readonly />
                    </label>
                    
            </div><div class="clr"></div>
    </div>
 <div class="clr"></div>
<div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
                   <label>
                        <span>Cash/Cheque/RTGS Amount</span>
                 <input name="chequeamt" type="text" id="chequeamt" ng-model="payment.cheque_amount" tabindex="2" ng-keyup="CheckDecimal();" is-number/>
                    </label>
                    
                     
            </div><div class="clr"></div>
    </div>
       <div style="width:50%; float:left;">
            <div style="width:50%; float:left;">
              <label>
                        <span>Bank Address</span>
                        <textarea name="bank_add" id="ibank_add" tabindex="4" ng-model="payment.bank_add" readonly ></textarea>
                    </label>      
                    
            </div><div class="clr"></div>
    </div>
  <div class="clr"></div>
<div style="width:85%; float:left;">
<div style="width:100%; float:left;">
  <label><span>Pending Payment Invoice List</span>
  <select name="invoicelist" id="invoicelist" ng-model="payment.invoice_list" ng_change="showInvoice();" >
  <option value="">Select Invoice No</option>
                         
  </select>
  </label>
</div><div class="clr"></div>
</div>   
<div class="clr"></div>
<div align="center">
<label><span>Payment Detail</span></label>
</div>
<div class="clr"></div>
<div style="width:100%; float:left; height:300px; overflow:scroll;">
          <table width="2000px;" border="1">
          <tr>
          
          <td style="width:200px;" rowspan="2"><label><span>Invoice No.</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Invoice Date</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Due Date</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Invoice Amount</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Short Amt.</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Excess Amt.</span></label></td>
           <td style="width:200px;" rowspan="2"><label><span>Cash Discount</span></label></td>
          <td style="width:300px;" colspan="3"><div align="center"><label><span>Debit Note</span></label></div></td>
          <td style="width:200px;" rowspan="2"><label><span>Status</span></label></td>          
          <td style="width:200px;" rowspan="2"><label><span>Payabled Amount</span></label></td>
           <td style="width:200px;" rowspan="2"><label><span>Balanced Amount</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Received Amount</span></label></td>
          <td style="width:300px;"  rowspan="2"><label><span>Remarks</span></label></td>
          <td style="width:100px;"  rowspan="2" align="center" valign="middle"><label><span>Action</span></label></td>
          </tr>
          <tr>
              <td width="100px"><label><span>Select</span></label></td>
              <td width="200px"><label><span>DebitId</span></label></td>
              <td width="200px"><label><span>Debit Amount</span></label></td>
           </tr> 
           <tr>
         
          <td style="width:100px;" >
           <input type="text" id="invoiceno" name="invoiceno" ng-model="payment.invoice_no" readonly/>
           	
          </td>
          <td ><input type="text" id="invoicedate" name="invoicedate" ng-model="payment.invoice_date"  readonly/></td>
          <td ><input type="text" id="duedate" name="duedate" ng-model="payment.due_date"  readonly/></td>
          <td  ><input type="text" id="invoiceamt" name="invoiceamt" ng-model="payment.invoice_amt"  readonly/></td>
          <td ><input type="text" id="shortamt" name="shortamt" ng-model="payment.short_amt"  ng-change="calculatePayableAmt();" is-number/></td>
          <td ><input type="text" id="excessamt" name="excessamt" ng-model="payment.excess_amt" ng-change="calculatePayableAmt();" is-number /></td>
          <td ><input type="text" id="discountamt" name="discountamt" ng-model="payment.discount_amt" ng-change="calculatePayableAmt();" is-number/></td>
          <td ><input type="checkbox" name="debitFlag" id="debitFlag" ng-model="payment.debitFlag"  ng-click="enabledDebit();" /></td>
          <td><input type="text" id="debitid" name="debitid" ng-model="payment.debitId" readonly /></td>
          <td><input type="text" id="debitamt" name="debitamt" ng-model="payment.debit_amt" ng-change="calculatePayableAmt();" readonly /></td>
          <td><select name="invoicestatus" id="invoicestatus"  ng-model="payment.invoice_status" >
               <option value="">Select</option>
              <?php
              $result =  ParamModel::GetParamList('PAYMENT','STATUS');
              while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
                   echo "<option value='".$row['PARAM1']."'>".$row['PARAM_VALUE1']."</option>";
              }?>
         </select></td>
          <td><input type="text" id="payabledamt" name="payabledamt" ng-model="payment.payabled_amt" readonly/></td> 
          <td><input type="text" id="balanceamt" name="balanceamt" ng-model="payment.balance_amt" readonly/></td>
          <td><input type="text" id="received_amt" name="receivedamt" ng-model="payment.received_amt" ng-change="calculatePayableAmt(); " is-number/></td>
          
          <td><input type="text" id="remark" name="remark" ng-model="payment.remark"/></td>
          <td align="center"><input class="button" type="button" name="btadd" id="btadd" ng-click="addItem()" tabindex="12" value="Add" /></td>
          </tr>
          <tr id="paymentRow" ng-repeat="item in payment._items" >
          
          <td style="width:100px;" >
           <input type="text" id="invoiceno" name="invoiceno" ng-model="item.invoiceNo" readonly/>
           	<input  type="text" ng-model="item.trxndId" style="display:none;" /> 
          </td>
          <td ><input type="text"  ng-model="item.invoiceDate"  readonly/></td>
          <td ><input type="text"  ng-model="item.dueDate"  readonly/></td>
          <td  ><input type="text"  ng-model="item.invoiceAmount"  readonly/></td>
          <td ><input type="text"  ng-model="item.shortAmount" readonly/></td>
          <td ><input type="text"  ng-model="item.excessAmount" readonly/></td>
           <td ><input type="text" ng-model="item.cash_discount_value" readonly/></td>
          <td ><input type="checkbox"  ng-model="item.debitFlag" ng-checked="item.debitFlag=='1'" /></td>
          <td><input type="text"  ng-model="item.debitId" readonly/></td>
          <td><input type="text"  ng-model="item.debitAmt"  readonly/></td>
          <td> <input type="text"   ng-model="item.payment_status_value" readonly />
              <input type="text"   ng-model="item.payment_status" style="display:none;"/></td>
          <td><input type="text"  ng-model="item.payabledAmount" readonly/></td>
          <td><input type="text"  ng-model="item.balanceAmount" readonly/></td>
           <td><input type="text"  ng-model="item.receivedAmount" readonly/></td>
          <td><input type="text"  ng-model="item.Remarks" readonly/></td>
          <td align="center">
          <?php if($_GET['trxnId']=='' ||$_GET['trxnId']=='0'){?>
    <input class="button" type="button" name="btremove" id="btremove" ng-click="removeItem()" tabindex="12" value="Delete" />    <?php }?>
     </td>
          </tr> 
            
          </table>
      </div>
      <div class="clr"></div><!-- Combination of Part No. &amp; Incoming inv no. will be unique. -->
      
     
      <div style="width:100%; float:left;">
          <div style="width:30%; float:right;">
            <label>
                <span>Total Received Amount:</span>
                <input type="text" name="txttotalamt" id="txttotalamt" ng-model="payment.total_amt" placeholder="Total Received Amount" readonly="true" required></input>
            </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      <div style="width:100%; float:left;" id="canreg">
          <div style="width:30%; float:right;">
            <label>
                <span>Cancel Reason:</span>
                <input type="text" name="txtreason" id="txtreason" ng-model="payment.cancel_reason" placeholder="Cancel Reason" ></input>
            </label>
          </div>
          <div class="clr"></div>
      </div>
      <div class="clr"></div>
      
      <div align="center">
          <input type="submit" class="button" name="button3" id="btnsave" tabindex="14" value="Save" />
		   <input type="hidden" class="button" name="button5" id="btnupdate" tabindex="15" value="Update" ng-click="UpdateTRXN();"/>
          <input type="button" class="button" name="button4" id="btncancelInv" value="Cancel Payment" ng-click="CancelTRXN();" /> 
          <span><a class="button" style="text-decoration: none;" id="btcancel" href="<?php print SITE_URL.PAYMENT_RECEIVED_LIST; ?>">Cancel</a></span>        
      </div>
</div>
<script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#trxndate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});
$('#chequedate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'Y-m-d',
	formatDate:'Y-m-d',
	scrollInput:false
});

</script>
  
  
  <br/><br/><br/><br/><br/><br/><br/><br/>

<?php include("../../footer.php") ?>
</form>
</body>
</html>

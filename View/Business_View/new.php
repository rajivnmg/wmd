<?php session_start(); ?>
<html>
<head>
<meta charset="utf-8">
<title>Payment</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Business_action_js/pay.js'></script> 

<?php include("../../header.php") ;?>
<?php include( "../../Model/Masters/BuyerMaster_Model.php");?> 
<?php include("../../Model/DBModel/DbModel.php");?>
<?php include( "../../Model/Param/param_model.php");?>

</head>
<body ng-app="payment_app">
<form id="form1" ng-controller="payment_Controller" ng-submit="AddPAY();"  class="smart-green">

  
  <table width="100%" border="0">
    <tr>
      <td><div align="center"><strong>Payment</strong> </div></td></tr>
   </table>

  <table width="100%" border="1">
    <tr>
      <td width="19%">Buyer Name</td>
      <td colspan="3">
       
       </td>
    </tr>
    <tr>
      <td>Date</td>
      <td width="32%"><input name="cdt" type="text" id="icdt" value="<?php echo(date("d/m/Y")) ?>" readonly/></td>
      <td width="10%">Type</td>
      <td width="39%">
        
      </td>
    </tr>
  </table>
<div>Bank Detail</div>

  <table width="100%" border="1">
    <tr>
      <td width="19%">Bank Name</td>
      <td width="32%"><input name="bank_name" type="text" id="ibank_name" tabindex="3" ng-model="payment.bank_name" value="" /></td>
      <td width="10%">Amount</td>
      <td width="39%"><input name="amt" type="text" id="iamt" tabindex="5" ng-model="payment.amt" value="" /></td>
    </tr>
    <tr>
      <td>Address</td>
      <td><textarea name="bank_add" id="ibank_add" tabindex="4" ng-model="payment.bank_add"></textarea></td>
      <td>Status</td>
      <td>
       </td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>
    <input type="button" ng-click="getInvoices();" value="Get Invoice" />
  </p>
       <div align="center">
        <input class="" type="submit" name="b1" id="ib1" value="Save">
         <input class="button" type="button" name="button4" id="button4" value="Cancel">
      </div>
  <p>&nbsp;</p>
</form>
</body>
</html>

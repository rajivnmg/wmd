<?php
include('root.php');
include($root_path."GlobalConfig.php");
include("../../header.php");

//session_start();
$buyerStatus;
$buyer_add;
$count;
$TYPE=$_SESSION["USER_TYPE"];
$POID=$_GET['POID'];
$fromPage ='';
if(isset($_GET['fromPage'])) {
$fromPage = $_GET['fromPage'];
}
$BUYERID;$BUYERNAME;$_bill_add2;$_state_name;$_city_name;$_location_name;$_pincode;$_phone;$_mobile;$_fax;$_email;$b_cp;$q_cp;$po_cp;$curr_po_val;$tot_po_val;$b_cl;$b_level;$threshold_amt;$unbilled_poval;
include( "../../Model/Masters/BuyerMaster_Model.php");
include( "../../Model/Business_Action_Model/po_model.php");
include( "../../Model/Business_Action_Model/ma_model.php");
include( "../../Model/Param/param_model.php");
//include("../../Model/DBModel/DbModel.php");

			          $result1 =  Purchaseorder_Model::GetPODetails($POID);
			          //echo("hi");
			          $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
			               $BUYERID=$row1['BuyerId'];
			               $po_cp = $row1['credit_period'];
                           $curr_po_val = $row1['po_val'];

			          $result1 =  Managementapproval_Model::checkBuyerNewExist_UsingInv($BUYERID);
			          $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
			                $total=$row1['total'];

			          if($total>0){
					  	$buyerStatus="EXIST";
					  }else{
					  	$buyerStatus="NEW";
					  }

			          $result1 = Managementapproval_Model::unBilledPOVal($BUYERID);
			          $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
			                $unbilled_poval=$row1['unBilledPOVal'];

			         $result =  ParamModel::GetParamList('AMOUNT','THRESHOLD');
			         $row1=mysql_fetch_array($result, MYSQL_ASSOC);
			         $threshold_amt=$row1['PARAM_VALUE1'];

			          $result1 =  Managementapproval_Model::getQuotCreditPeriod($BUYERID);
			          $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
			          $q_cp=$row1['q_cp'];


			          $result2 =  Managementapproval_Model::outstandingAmount($BUYERID);
			          $row1=mysql_fetch_array($result2, MYSQL_ASSOC);
			          $outstandingPayment=$row1['outstandingPayment'];

			          $result =  BuyerMaster_Model::GetBuyerDetails($BUYERID);
			          $row=mysql_fetch_array($result, MYSQL_ASSOC);
			                    $BUYERNAME=$row['BuyerName'];
			                    $_bill_add1 = $row['Bill_Add1'];
			                    $_bill_add2 = $row['Bill_Add2'];
			                    $_state_name= $row['StateName'];
			                    $_city_name= $row['CityName'];
			                    $_location_name= $row['LocationName'];
			                    $_pincode = $row['Pincode'];
			                    $_phone = $row['Phone'];
			                    $_mobile = $row['Mobile'];
			                    $_fax = $row['FAX'];
			                    $_email = $row['Email'];
			                    $b_cp = $row['Credit_Period'];
                                $b_cl = $row['Credit_Limit'];
                                $b_level = $row['Buyer_Level'];
                                $Credit_Limit=$row['Credit_Limit'];
                                $buyer_add=$_bill_add1.",".$_bill_add2."<br>".$_location_name."<br>".$_city_name.",".$_state_name."<br>INDIA,".$_pincode;

                     ?>

<html>
<head>

<meta charset="utf-8">
<title>Management Approvel</title>
<link href="../css/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>

<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Business_action_js/po.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/ma.js'></script>
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
</head>

<body ng-app="ma_app">
<?php //include("../../header.php");?>
<form id="form1" ng-controller="ma_Controller"   data-ng-init="init('<?php echo($_GET['POID']) ?>','<?php echo($TYPE)?>')" class="smart-green">

<hidden type="text" name="poId" id="ipoId" ng-model="managementapp.poid"  value="<?php echo($_GET['POID']) ?>" >
<div align="center"><strong>Management Approval</strong>
<table width="50%" border="1">
  <tr>
    <td width="23%">Buyer Status</td>
    <td width="77%"><?php echo($buyerStatus); ?></td>
  </tr>
  <tr>
    <td width="23%">Buyer Name</td>
    <td width="77%"><?php echo($BUYERNAME); ?></td>
  </tr>
  <tr>
    <td>Buyer Address</td>
    <td width="77%"> <?php echo($buyer_add); ?></td>
  </tr>
  <tr>
    <td width="23%" height="28" valign="top">Buyer Level</td>
    <td width="77%" valign="top"><div align="left"><?php echo($b_level); ?></div></td>
  </tr>
</table>
</div>
<div align="center">
<table width="50%" border="1">
  <tr>
    <td width="23%">&nbsp;</td>
    <td width="27%"><div align="center">Default</div></td>
    <td width="25%"><div align="center">Quotation</div></td>
    <td width="25%"><div align="center">Purchase Order</div></td>
  </tr>
  <tr>
    <td>Credit Period(Days)</td>
    <td><div align="center"><?php echo($b_cp); ?></div></td>
    <td><div align="center"><?php echo($q_cp); ?></div></td>
    <td><div align="center"><?php echo($po_cp); ?></div></td>
  </tr>
</table>
</div>

<?php if($buyerStatus=="EXIST"){?>


<table width="100%" border="1">
  <tr>
    <td width="8%">&nbsp;</td>
    <td width="23%"><div align="center">Credit Limit</div></td>
    <td width="23%"><div align="center">OutStanding(A)</div></td>
    <td width="23%"><div align="center">Total Unbilled PO Value(B)</div></td>
    <td width="23"><div align="center">Total (A+B)</div></td>
  </tr>
  <tr>
    <td width="8%">Amount</td>
    <td><div align="center"><?php echo($Credit_Limit); ?></div></td>
    <td><div align="center"><?php echo($outstandingPayment); ?></div></td>
    <td><div align="center"><?php echo($unbilled_poval);?></div></td>
    <td><div align="center"><?php echo($outstandingPayment+$unbilled_poval);?></div></td>
  </tr>
</table>
<?php }else{?>
<table width="100%" border="1">
  <tr>
      <td width="10%">&nbsp;</td>
      <td width="18%"><div align="center">Limit</div></td>
      <td width="19%"><div align="center">Current Order Value(Unbilled)</div></td>
      <td width="26%"><div align="center">Pre. Order Value(Unbilled)</div></td>
      <td width="27%"><div align="center">Total</div></td>
  </tr>
    <tr>
      <td>Amount</td>
      <td><div align="center"><?php echo($threshold_amt);?></div></td>
      <td><div align="center"><?php echo($curr_po_val);?></div></td>
      <td><div align="center"><?php echo($unbilled_poval-$curr_po_val);?></div></td>
      <td><div align="center"><?php echo($unbilled_poval);?></div></td>
    </tr>
</table>
 <?php }?>
  <div>&nbsp;<div>
  <table width="100%" border="1">
    <tr>

      <td width="25%" rowspan="2"><div align="center">Principal</div></td>
      <td width="35%" rowspan="2">Product(code-decription-pack size)</td>
      <td width="6%" rowspan="2">Qty</td>
      <td width="10%" rowspan="2"><div align="center">Price Category</div></td>
      <td width="10%" rowspan="2">Price value</td>
      <td width="14%" colspan="2"><div align="center">Discount</div></td>
    </tr>
    <tr>
      <td width="7%"><div align="center">Proposed</div></td>
      <td width="7%"><div align="center">Required</div></td>
    </tr>




    <tr id="maRow" ng-repeat="item in managementapp._items">
     <td><hidden ng-model="item.bpod_Id" /><hidden ng-model="item.po_principalId"/><input style="width:250px;" ng-model="item.po_principalName" readonly /></td>
     <td><hidden ng-model="item.po_codePartNo"/><input style="width:300px;" ng-model="item.product" readonly /></td>
      <td><input style="width:50px;" ng-model="item.po_qty" readonly/></td>
      <td><div align="center">
        <select name="select" id="select" ng-model="item.po_cate">
          <option value="N">Normal Price</option>
          <option value="S">Special Prize</option>
        </select>
      </div></td>
      <td><input style="width:50px;" ng-model="item.po_price" readonly/></td>
      <td><input style="width:50px;" ng-model="item.buyer_discount" readonly/></td>
      <td><input style="width:50px;" ng-model="item.po_discount" readonly/></td>
    </tr>
  </table>


  <table width="100%" border="1">
    <tr>
      <td width="12%">Admin Comments</td>
      <td width="32%"><textarea name="aremarks" id="iarem" ng-model="managementapp.arem"></textarea></td>
      <td width="20%">Management Comments</td>
      <td width="36%"><textarea name="mremarks" id="imrem" ng-model="managementapp.mrem"></textarea></td>
    </tr>
  </table>

    <table width="100%" border="1">

    <tr id="forMA">
      <td width="100%" colspan="2">
       <div align="center">
        <input type="checkbox" name="maTag" id="imaTag" ng-model="managementapp.maTag" >
         (Send For Management Approval)
       </div>
      </td>
     </tr>

     <tr id="appStatus">
      <td width="50%"><div align="right">Approval Status
      </div>
        <label for="select2">
          <div align="center"></div>
        </label>
        <div align="center"></div></td>
      <td width="50%"><select name="select2" id="select2" ng-model="managementapp.asTag" >
        <option value="R">Reject</option>
        <option value="A">Approve</option>
      </select></td>
     </tr>

  </table>


    <table width="100%" border="0">
    <tr>
      <td><div align="center">
      <input class="button" type="button" name="b1" id="ib1" value="Save" ng-click="AddMA('<?php if(isset($_GET['fromPage'])){ echo($_GET['fromPage']); } ?>');">
      <input class="button" type="button" name="b2" id="ib2" value="Save." ng-click="giveApp('<?php if(isset($_GET['fromPage'])){ echo($_GET['fromPage']); }?>');">
      <input class="button" type="button" name="bpo" id="ibp" value="Back To PO" ng-model="managementapp.backToPO" ng-click="Goto(<?php echo($_GET['POID']) ?>);">
      </div></td>
    </tr>
  </table>
  </div>
  <script>
  	  <?php  if($TYPE =="S"){ ?>
  	           $("#ib1").show();
	           $("#iarem").attr('readonly', false);
	           $("#ib2").hide();
	           $("#imrem").attr('readonly', true);
  	           $("#forMA").show();
  	           $("#appStatus").hide();
  	  <?php }else if($TYPE =="M"){ ?>
  	           $("#ib2").show();
	           $("#ib1").hide();
   	           $("#forMA").hide();
  	           $("#appStatus").show();
	           $("#iarem").prop('readonly', true);
	           $("#imrem").prop('readonly', false);
  	  <?php }?>
   </script>
  <br/><br/><br/><br/><br/>
 </form>
 <?php include("../../footer.php") ?>
</body>
</html>

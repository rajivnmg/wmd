<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Purchase Order Schedule</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/pos.js'></script>
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

<body ng-app="pos_app">
<form id="form1" ng-controller="pos_Controller" ng-submit="AddPOS()" data-ng-init="init('<?php echo($_GET['POID']) ?>')" class="smart-green">


  <table width="100%" border="0">
    <tr>
      <td><div align="center"><strong>Recurring PO Schedule</strong> </div>
   </table>
   <table  width="100%" border="0">
    <tr>
     <td  width="20%">Search Recurring PO. No.</td>
     <td>
      <input type="text" name="rpo" id="autocomplete-ajax-PO" style=" z-index: 2; background: transparent;" tabindex="1" placeholder="Type Rec. PO Number" ng-model="pos.rpo" required/>
      <hidden name="poid" type="text" id="ipoid" ng-model="pos.bpoId" />
     
     </td>
    </tr>
  </table>
  <br>
  <table width="100%" border="0">
    <tr>
      <td width="3%">SNo.</td>
      <td width="20%">Principal*</td>
      <td width="15%">Code/Part No*.</td>
      <td width="25%">Item Desc.</td>
      <td width="12%">Buyer Item Code</td>
      <td width="7%"><div align="center">Req. Qty*.  <BR></div></td>
      <td width="10%">Date</td>
      <td width="8%">Qty By Date</td>
    </tr>
    <tr>
      <td>
           <hidden name="bposd_Id" type="hidden" id="ibposd_Id" ng-model="pos.bposd_Id" />
          <input name="pname" type="hidden" id="ipname" ng-model="pos.pname" size="20" readonly>
      	  <input name="cPartNo" type="hidden" id="icPartNo" ng-model="pos.cPartNo" size="20" readonly>
          <input type="text" name="codePartNo" id="icodePartNo"   ng-model="pos.sch_codePartNo" style="display:none">
          <input type="text" name="cPartNo" id="icPartNo"   ng-model="pos.cPartNo" style="display:none">
          <input type="text" name="sch_ed_applicability" id="isch_ed_applicability"   ng-model="pos.sch_ed_applicability" style="display:none">  
      </td>
      <td><select name="principalId" id="iprincipalId" class="input1"  ng-model="pos.sch_principalId" ng-change="showSchCodePartNo();"></select></td>
      <td><select name="bpod_Id" id="ibpod_Id" class="input1"  ng-model="pos.bpod_Id" ng-change="itemdesc();"></select></td>
      <td><input name="item_desc" type="text" id="item_desc"  ng-model="pos.item_desc" value="" size="10" readonly></td>
      <td><input name="buyer_item_code" type="text" id="ibuyer_item_code" ng-model="pos.bic" readonly></td>
      <td><input name="rqty" type="text" id="irqty" ng-model="pos.sch_rqty" is-number value="" size="8" ></td>
      <td><input name="schDate" type="text" id="ischDate" value="" size="10" ng-model="pos.schDate"></td>
      <td><input name="dqty" type="text" id="idqty" ng-model="pos.sch_dqty" is-number value="" size="8" ></td>
    </tr>
    <tr id="poRow" ng-repeat="item in pos._items" >
     <td><input style="width:30px;" ng-model="item.bposd_Id" /></td>
     <td><input ng-model="item.pname" /><hidden ng-model="item.sch_principalId" />
     <hidden ng-model="item.bpod_Id" /> <hidden ng-model="item.sch_ed_applicability" />
     </td>
     <td>
     <input ng-model="item.cPartNo" />
     <input type="text"  name="sch_codePartNo"   ng-model="item.sch_codePartNo" style="display:none">     	
     </td>
     <td><input ng-model="item.item_desc" class="input1" style="width:270px;" readonly/></td>
     <td><input ng-model="item.bic"  class="input1" style="width:150px;" readonly/></td>
     <td><input ng-model="item.sch_rqty"  style="width:50px;"/></td>
     <td><input ng-model="item.schDate" /></td>
     <td><input ng-model="item.sch_dqty"  style="width:50px;"/></td>
     <td><input type="button" name="DEL" id="idel" value="Delete" ng-model="pos.deleteitem" ng-click="removeItem(item)" ></td>
    </tr>

  </table>
  <table width="100%" border="0">
    <tr>
      <td><div align="right">

        <input type="button" name="Add" id="Add" value="Add" ng-model="pos.additem" ng-click="addItem()" class="button" />
     </div></td>
    </tr>
  </table>

    <table width="100%" border="0">
      <tr>
        <td  align="center"><input type="submit" name="b1" id="ib1" value="Save" class="button"></td>
      </tr>
    </table>
  </div>
 <script type='text/javascript' src='../js/jquery.datetimepicker.js'></script>
<script>
$('#ischDate').datetimepicker({
	lang:'en',
	timepicker:false,
	format:'d/m/Y',
	formatDate:'Y-m-d'
});
</script>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</form>
</body>
</html>

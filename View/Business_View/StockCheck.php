<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="../js/css/jquery.datetimepicker.css"/>
<script type='text/javascript' src='../js/jquery.js'></script>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Business_action_js/StockCheck.js'></script>
</head>
<body ng-app="StockCheck_app">
<?php include("../../header.php"); ?>
<form id="f1" ng-controller="StockCheck_Controller" data-ng-init="init('<?php echo $_GET['POID'];?>')"  class="smart-green">
<div align="center"><h1>Stock Check Form</h1></div>

<div>
   <div style="width:50%; float:left;">
        <div style="width:50%; float:left;">
            <label>
                <span>Purchase Order No:</span>
               <!--  Display from variable --><input id="pon" type="text" ng-model="StockCheck.pon"/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
        <div style="width:50%; float:left;">
            <label>
                <span>Purchase Order Type:</span>
               <!--  Display from po table --><input id="pot" type="text" ng-model="StockCheck.pot"/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:50%; float:left;">
        <div style="width:50%; float:left;">
            <label>
                <span>Purchase Order Validity Date:</span>
               <!--  Display from po table --><input id="povdate" type="text" ng-model="StockCheck.povdate"/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div style="width:50%; float:left;">
        <div style="width:50%; float:left;">
            <label>
                <span>Management Approval:</span>
               <!--  Approved/Not Required --><input id="aaproved" type="text" ng-model="StockCheck.aproved"/>
            </label>
        </div>
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <hidden id="sttrf" ng-model="StockCheck.sttrf"></hidden>
    <hidden id="outgoing" ng-model="StockCheck.otinv"></hidden>
    <hidden id="addtobas" ng-model="StockCheck.addtobas"></hidden>
    <hidden id="invnonexcise" ng-model="StockCheck.invnonexcise"></hidden>
    <div style="width:100%; float:left; overflow:scroll; height:300px;" ID="invoicedetails">
         <label><span>Invoice Detail</span></label>
         <table width="100%" border="1">
              <tr>
                <td width="11%">Principal</td>
                <td width="12%">Code/Part No.</td>
                <td width="25%">Description</td>
                <td width="11%">ED applicable</td>
                <td width="8%">PO QTY</td>
                <td width="8%">Available ED Qty</td>
                <td width="5%">Available WED Qty</td>
                <td width="20%">Action</td>
              </tr>
              <tr ng-repeat="item in StockCheck._items">
                <td><!-- Display from po grid table --><input type="text" id="" ng-model="item.Principal"/></td>
                <td><!-- Display from po grid table --><input type="text" id="" ng-model="item.Code_Part"/></td>
                <td><!-- Display from table --><input id="" type="text" ng-model="item.Description"/></td>
                <td><!-- Display from po grid table --><input type="text" id="" ng-model="item.ED_applicable"/></td>
                <td><!-- Display from po grid table --><input type="text" id="" ng-model="item.PO_QTY"/></td>
                <td><!-- from stock --><input type="text" id="" ng-model="item.Available_ED_Qty"/></td>
                <td><!-- from stock --><input type="text" id="" ng-model="item.Available_WED_Qty"/></td>
                <td>
                 <ng-template ng-show="StockCheck.sttrf">
                 <a href="<?php print SITE_URL.NEW_STOCK_TRANSFER; ?>" class="button" id="stock_trans">Stock Transfer</a>
                  <!-- <input type="submit" class="button" name="stock_trans" id="stock_trans" value="Stock Transfer"/> -->
                  </ng-template>
          <ng-template ng-show="StockCheck.otinv">
          <a href="<?php print SITE_URL.NEW_OUTGOING_INVOICE_EXCISE; ?>" class="button" id="stock_outgoing">Outgoing Invoice generate</a>
                <!-- <input type="submit" class="button" name="stock_outgoing" id="stock_outgoing" value="Outgoing Invoice generate" /> -->
                </ng-template>
                <ng-template ng-show="StockCheck.addtobas">
                <a href="" class="button" id="stock_addtobsk">add to basket</a>
                 <!-- <input type="submit" class="button" name="stock_addtobsk" id="stock_addtobsk" value="add to basket" /> -->
                </ng-template>
                <ng-template ng-show="StockCheck.invnonexcise">
                <a href="<?php print SITE_URL.NEW_INCOMING_INVOICE_EXCISE; ?>" class="button" id="stock_invnonex">Invoice Non Excise</a>
                <!-- <input type="submit" class="button" name="stock_invnonex" id="stock_invnonex" value="Invoice Non Excise" /> -->
                </ng-template>
                </td>
              </tr>
            </table>
    </div>
    <div id="message">Query Return No Result</div>
    <div class="clr"></div>     
</div>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

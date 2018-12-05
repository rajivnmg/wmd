
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../css/menu.css" type="text/css" media="screen" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Masters_js/UserMenu.js'></script>
</head>
  <body ng-app ="FormUserMenu_app">
  <form name="f1" method="post" ng-controller="FormUserMenu_Controller" class="smart-green">
  <div align="center" style="width:50%; margin-top:100px;">
        <label>
            <span>Select User</span>
            <select ng-model="FormUserMenu.USER_ID" ng-change="UserTypeChange();">
                <option value="" title="select">Select User</option>
                <?php include( "../../Model/Masters/User_Model.php"); include("../../Model/DBModel/DbModel.php");
                $result =  UserModel::GetUserId();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['USERID']."' title='".$row['USERID']."'>".$row['USERID']."</option>";
                }?>
            </select>
        </label>
    </div>
  <div>
  <ul id="menu">
  <li>Home<input type="checkbox" id="Home" ng-change="homemenu();" ng-model="FormUserMenu.Home"/></li>
    <li>
     Masters<input type="checkbox" id="Masters" ng-change="Masters();" ng-model="FormUserMenu.Masters"/>
        <div class="dropdown_1column">
            <div class="col_1">
                <ul class="simple">
                   <!--  <li>User Master<input type="checkbox" id="usermast" ng-model="FormUserMenu.usermast" ng-change="usermast()"/></li> -->
                    <li>Unit Master<input type="checkbox" id="unitmast" ng-model="FormUserMenu.unitmast" ng-change="unitmast()"/></li>
                    <li>Group Master<input type="checkbox" id="groupmast" ng-model="FormUserMenu.groupmast" ng-change="groupmast()"/></li>
                    <li>State Master<input type="checkbox" id="statemast" ng-model="FormUserMenu.statemast" ng-change="statemast()"/></li>
                    <li>City Master<input type="checkbox" id="citymast" ng-model="FormUserMenu.citymast" ng-change="citymast()"/></li>
                    <li>Location Master<input type="checkbox" id="locationmast" ng-model="FormUserMenu.locationmast" ng-change="locationmast()"/></li>
                    <li>Principal Master<input type="checkbox" id="principalmast" ng-model="FormUserMenu.principalmast" ng-change="principalmast()"/></li>
                    <li>Supplier Master<input type="checkbox" id="suppliermast" ng-model="FormUserMenu.suppliermast" ng-change="suppliermast()"/></li>
                    <li>Item Master<input type="checkbox" id="itemmast" ng-model="FormUserMenu.itemmast" ng-change="itemmast()"/></li>
                    <li>Buyer Master<input type="checkbox" id="Buyermast" ng-model="FormUserMenu.Buyermast" ng-change="Buyermast()"/></li>
                </ul>
            </div>
		</div>
    </li>
    <li>Business Actions<input type="checkbox" id="BusinessAction" ng-model="FormUserMenu.BusinessAction" ng-change="BusinessAction()"/>
        <div class="dropdown_2columns">
            <div class="col_2">
                <ul class="simple">
                    <li>Challan<input type="checkbox" id="Challan" ng-model="FormUserMenu.Challan" ng-change="Challan()"/></li>
                    <li>Quotation<input type="checkbox" id="Quotation" ng-model="FormUserMenu.Quotation" ng-change="Quotation()"/></li>
                    <li>Purchase Order<input type="checkbox" id="PurchaseOrder" ng-model="FormUserMenu.PurchaseOrder" ng-change="PurchaseOrder()"/></li>
                     <li>Recurring Purchase Order<input type="checkbox" id="RecurringPurchaseOrder" ng-model="FormUserMenu.RecurringPurchaseOrder" ng-change="RecurringPurchaseOrder()"/></li>
                    <li>Management Approval<input type="checkbox" id="ManagementApproval" ng-model="FormUserMenu.ManagementApproval" ng-change="ManagementApproval()"/></li>
                    <li>Stock Transfer<input type="checkbox" id="StockTransfer" ng-model="FormUserMenu.StockTransfer" ng-change="StockTransfer()"/></li>
                    <li>Stock Check<input type="checkbox" id="StockCheck" ng-model="FormUserMenu.StockCheck" ng-change="StockCheck()"/></li>
                    <li>Incoming Invoice/s Generation<input type="checkbox" id="IncomingInvoice" ng-model="FormUserMenu.IncomingInvoice" ng-change="IncomingInvoice()"/>
                       <ul  class="simple">
                        <li>Excise<input type="checkbox" id="IncomingInvoiceExcise" ng-model="FormUserMenu.IncomingInvoiceExcise" ng-change="IncomingInvoiceExcise()"/></li>
                        <li>Non Excise<input type="checkbox" id="IncomingInvoiceNonExcise" ng-model="FormUserMenu.IncomingInvoiceNonExcise" ng-change="IncomingInvoiceNonExcise()"/></li>
                       </ul>
                    </li>
                    <li>Outgoing Invoice Generation<input type="checkbox" id="OutgoingInvoice" ng-model="FormUserMenu.OutgoingInvoice" ng-change="OutgoingInvoice()"/>
						<div>
						<ul class="simple">
						 <li>Excise<input type="checkbox" id="OutgoingInvoiceExcise" ng-model="FormUserMenu.OutgoingInvoiceExcise" ng-change="OutgoingInvoiceExcise()"/></li>
						 <li>Non Excise<input type="checkbox" id="OutgoingInvoiceNonExcise" ng-model="FormUserMenu.OutgoingInvoiceNonExcise" ng-change="OutgoingInvoiceNonExcise()"/></li>
						</ul>
						</div>
                    </li>
                    <li>Payment Entry against outgoing invoice<input type="checkbox" id="PaymentEntryagainstoutgoing" ng-model="FormUserMenu.PaymentEntryagainstoutgoing" ng-change="PaymentEntryagainstoutgoing()"/></li>
                </ul>
            </div>
		</div>
    </li>
    <li>Reports<input type="checkbox" id="Reports" ng-model="FormUserMenu.Reports" ng-change="Reports()"/>
        <div class="dropdown_1column">
            <div class="col_1">
                <ul class="simple">
             
                </ul>
            </div>
		</div>
    </li>
    <li>Search<input type="checkbox" id="Search" ng-model="FormUserMenu.Search" ng-change="Search()"/>
	        <div class="dropdown_1column">
	            <div class="col_1">
	                <ul class="simple">
	                    <li>Purchase Order<input type="checkbox" id="SearchPurchaseOrder" ng-model="FormUserMenu.SearchPurchaseOrder" ng-change="SearchPurchaseOrder()"/></li>
	                    
	                </ul>
	            </div>
			</div>
    </li>
    <?php
ob_start();
include('../../siteConfig.php');
?>
  <li id="Home" ><a href="<?php
      session_start();
      if($_SESSION['USER_TYPE'] == "A")
      {
      print SITE_URL.ADMINHOME;
      }
      else
      {
      print SITE_URL.INDEX;
      }
      
      
      
      ?>" class="drop">Back To Home Page</a></li>
  </form>
  
  </body>
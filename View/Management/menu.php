<?php session_start(); include '../../siteConfig.php';

if($_SESSION["USER"]!=null)
{ 
$TYPE = $_SESSION["USER_TYPE"];
?>

<nav class="navbar navbar-default navbar-static-top"  style="margin-bottom: 0" id="menu">
<ul class="nav navbar-top-links navbar-left">
    <li class="dropdown" id="Home">
        <a href="<?php print SITE_URL.DASHBOARD; ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Masters<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <li id="unitmast"><a href="<?php print SITE_URL.UNITMASTER; ?>">Unit Master</a></li>
            <li id="groupmast"><a href="<?php print SITE_URL.GROUPMASTER; ?>">Group Master</a></li>
            <li id="statemast"><a href="<?php print SITE_URL.STATEMASTER; ?>">State Master</a></li>
            <li id="citymast"><a href="<?php print SITE_URL.CITYMASTER; ?>">City Master</a></li>
            <li id="locationmast"><a href="<?php print SITE_URL.LOCATIONMASTER; ?>">Location Master</a></li>
            <li id="principalmast"><a href="<?php print SITE_URL.VIEWPRINCIPALMASTER; ?>">Principal Master</a></li>
            <li id="suppliermast"><a href="<?php print SITE_URL.VIEWSUPPLIERMASTER; ?>">Supplier Master</a></li>
            <li id="itemmast"><a href="<?php print SITE_URL.ITEMMASTER; ?>">Item Master</a></li>
            <li id="Buyermast"><a href="<?php print SITE_URL.VIEWBUYERMASTER; ?>">Buyer Master</a></li>
            <li id="usermast"><a href="<?php print SITE_URL.VIEWUSERMASTER; ?>">User Master</a></li>
            <li id="usermenu" ><a href="<?php print SITE_URL.UserMenu; ?>">Menu Privilege</a></li>
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench fa-fw"></i>Order's<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <li id="Challan"><a href="<?php print SITE_URL.VIEW_CHALLAN; ?>">Challan</a></li>
            <li id="Quotation"><a href="<?php print SITE_URL.VIEWQUATION; ?>">Quotation</a></li>
            <li id="PurchaseOrder"><a href="<?php print SITE_URL.PO; ?>">Purchase Order</a></li>
            <li id="POSCHEDULE"><a href="<?php print SITE_URL.POSCHEDULE; ?>">Recurring Purchase Order</a></li>
            <li id="StockCheck"><a href="<?php print SITE_URL.VIEW_STOCK_TRANSFER; ?>">Stock Transfer</a></li>
            <li id="StockCheck"><a href="<?php print SITE_URL.VIEW_STOCK_Check; ?>">Stock Check</a></li>
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-edit fa-fw"></i>Invoice<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="<?php print SITE_URL.VIEW_INCOMING_INVOICE_EXCISE; ?>" >In-Coming Excise</a></li>
            <li><a href="<?php print SITE_URL.VIEW_INCOMINGINVOICENONEXCISE; ?>">In-Coming Non Excise</a></li>
            <li><a href="<?php print SITE_URL.VIEW_OUTGOING_INVOICE_EXCISE; ?>" >Out-Going Excise</a></li>
            <li><a href="<?php print SITE_URL.VIEW_OUTGOING_INVOICE_NonEXCISE; ?>">Out-Going Non Excise</a></li>
            <li><a href="<?php print SITE_URL.PAYMENT; ?>">Payment</a></li>
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-table fa-fw"></i>Report<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <li id="salsereport"><a href="<?php print SITE_URL.EX_STOCK_STMT_WV; ?>">Excise Stock Statement With Value</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NONEX_STOCK_STMT_WV; ?>">Non Excise Stock Statement With Value</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.EX_STOCK_STMT; ?>">Excise Stock Statement</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NONEX_STOCK_STMT; ?>">Non Excise Stock Statement</a></li>
             
             <li id="salsereport"><a href="<?php print SITE_URL.EX_SECONDARY_SALSE_STATEMENT; ?>">Excise Secondary Sales Statement</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NONEX_SECONDARY_SALSE_STATEMENT; ?>">Non Excise Secondary Sales Statement</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.EX_SALSE_STATEMENT; ?>">Excise Sales Statement</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NONEX_SALSE_STATEMENT; ?>">Non Excise Sales Statement</a></li>
             
             <li id="salsereport"><a href="<?php print SITE_URL.EX_ProductLedger; ?>">Excise Product Ledger</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NON_ProductLedger; ?>">Non Excise Product Ledger</a></li>
             
             <li id="salsereport"><a href="<?php print SITE_URL.EX_SalesTaxReturn; ?>">Sales Tax Return Excise</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.NON_SalesTaxReturn; ?>">Sales Tax Return Non Excise</a></li>
             
             <li id="salsereport"><a href="<?php print SITE_URL.INCOMING_EXCISERETURN; ?>">Incoming Excise Return</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.OUTGOING_EXCISERETURN; ?>">Outgoing Excise Return</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.MARGINREPORT; ?>">Margin Report</a></li>
			<li id="salsereport"><a href="<?php print SITE_URL.PURCHASEREPORT; ?>">Purchase Report</a></li>
			<li id="salsereport"><a href="<?php print SITE_URL.DAILYSALSEREPORT; ?>">Daily Sales Report</a></li>
        </ul>
        <!-- /.nav-second-level -->
    </li>
</ul>
            <!-- /.navbar-header -->
<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> <?php echo($_SESSION["USER_NAME"]); ?> <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
            </li>
            <li><a href="<?php print SITE_URL.CHANGEPASSWORD; ?>"><i class="fa fa-gear fa-fw"></i>Change Password</a></li>
            <li class="divider"></li>
            <li><a href="<?php print SITE_URL.LOGOUT; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
            </li>
        </ul>
        <!-- /.dropdown-user -->
    </li>
    <!-- /.dropdown -->
</ul>
 </nav>

<?php } 
else
{
   header("Location: ".SITE_URL.LOGIN);
}
?>

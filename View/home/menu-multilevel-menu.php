<?php //session_start(); 
//require_once '../../GlobalConfig.php';
include '../../siteConfig.php';
//print_r($_SESSION);
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
			 <li id="salsereport"><a href="<?php print SITE_URL.STOCKTRANSFER_EXCISERETURN; ?>">Stock Transfer Excise Return</a></li>
             <li id="salsereport"><a href="<?php print SITE_URL.MARGINREPORT; ?>">Margin Report Excise</a></li>
			  <li id="salsereport" class="dropdown-submenu"><a href="<?php print SITE_URL.MARGINREPORTNONEXCISE; ?>">Margin Report Non-Excise</a>
			
              
             
                <ul class="dropdown-menu">
                  <li><a tabindex="-1" href="#">Second level</a></li>
                  <li class="dropdown-submenu">
                    <a href="#">Even More..</a>
                    <ul class="dropdown-menu">
                        <li><a href="#">3rd level</a></li>
                    	<li><a href="#">3rd level</a></li>
                    </ul>
                  </li>
                  <li><a href="#">Second level</a></li>
                  <li><a href="#">Second level</a></li>
                </ul>
              </li>         
			 
        </ul>
        <!-- /.nav-second-level -->
    </li>
	 <li class="dropdown">      
		 <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php print SITE_URL.'View/img/payment-icon.png'; ?>"/>Payment<img src="<?php print SITE_URL.'View/img/arrow-down.png'; ?>"/></a>
        <ul class="dropdown-menu dropdown-user">
        <?php
       // if(checkPermission('payment_view')||checkPermission('payment_add'))
      // {
		 echo '<li><a href="'.SITE_URL.PAYMENT_RECEIVED_LIST.'">Make Payment</a></li>';	
	  // }
	   
	 // if(checkPermission('payment_view')||checkPermission('payment_add'))
     //  {
		 echo '<li><a href="'.SITE_URL.OINVOICE_PAYMENT_PENDING_LIST.'" >Payment Pending Invoice List</a></li>';	
	  // }
	   //if(checkPermission('pendinginv_view')||checkPermission('pendinginv_add'))
     //  {
	echo ' <li><a href="'.SITE_URL.OINVOICE_BUYER_PAYMENT_PENDING_LIST.'">Payment Pending Buyer List</a></li>';	
	  // }	   	   
        ?>
		<?php
		if(($TYPE =="M") || ($TYPE =="A")){ 
        
		?>
		<li><a href="<?php print SITE_URL.VIEW_FINALCIAL_YEAR_WISE_REVENUE; ?>" >Financial Year Wise Buyer Revenue Detail</a></li>
            <li><a href="<?php print SITE_URL.VIEW_BUYER_WISE_REVENUE; ?>">Buyer Wise Revenue detail</a></li>
		<?php 
		}
		?>
			<?php if($TYPE =="M"){ 
					           $ma_rep="POs for Approve/Reject";
					       }else if($TYPE =="S"){ 
					           $ma_rep="POs for Management Decision ";
							}else {
							$ma_rep="POs for Approve/Reject";
							}?>
	       <!-- <li><a href="<?php print SITE_URL.POsMS; ?>"><?php echo($ma_rep); ?></a></li> -->
        </ul>
        <!-- /.nav-second-level -->
    </li>
	<?php
	if(($TYPE =="M") || ($TYPE =="A")){ 
	?>
	 <li class="dropdown">      
		 <a class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="<?php print SITE_URL.'View/img/master.png'; ?>"/> Manage Users<img src="<?php print SITE_URL.'View/img/arrow-down.png'; ?>"/></a>
        <ul class="dropdown-menu dropdown-user">
        <?php
        //  if(checkPermission('user_view')||checkPermission('user_add')||checkPermission('user_edit'))
       //   {
		  	 echo '<li id="usermast"><a href="'.SITE_URL.VIEWUSERMASTER.'">User Master</a></li>';
		//  }
		  
		//   if(checkPermission('privilege_view')||checkPermission('privilege_add')||checkPermission('privilege_edit'))
         // {
		  	echo '<li><a href="'.SITE_URL.USERSLIST.'">Add Privilege</a></li>';
		//  }
		  
		 //  if(checkPermission('groupperm_view')||checkPermission('groupperm_add')||checkPermission('groupperm_edit'))
         // {  
            echo '<li><a href="'.SITE_URL.GROUPPERMISSION.'">Group Permission</a></li>';
		 // }
         ?>
         </ul>
        <!-- /.nav-second-level -->
    </li>
	<?php
	}
	?>

	
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
            <li><a href="<?php print SITE_URL.LOGIN; ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
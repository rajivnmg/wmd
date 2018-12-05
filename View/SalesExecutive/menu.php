<?php 
if($_SESSION["USER"]!=null)
{ 
$TYPE = $_SESSION["USER_TYPE"];

if($actual_url == 'View/home/Dashboard.php' && ($TYPE == 'E' || $TYPE == 'B')){
 echo 'Permission Denied'; exit;
}

if($accessKey!='' || $accessKey != NULL){
	
	if(!checkPermission($accessKey)){
		echo 'Permission Denied-1'; exit;
	}
}


if(isset($_SESSION["USER"]) and $_SESSION["USER"] != NULL){
	  if(isLoginSessionExpired()) {
		die('<script type="text/javascript">window.location.href="../../logout.php?se=1";</script>');
	 }  
} 
?>

<nav class="navbar navbar-default navbar-static-top"  style="margin-bottom: 0" id="menu">
<ul class="nav navbar-top-links navbar-left">
    <?php
      if($TYPE=='E' || $TYPE=='B'){
	
    ?>
     <li id="Home" ><a href="<?php print SITE_URL.SalesExecutiveDASHBOARD; ?>" class="drop"> <img src="<?php print SITE_URL.'View/img/dashboard-icon.png'; ?>"/>Dashboard </a> </li>
    <?php }else{?>
    <li id="Home" ><a href="<?php print SITE_URL.DASHBOARD; ?>" class="drop"> <img src="<?php print SITE_URL.'View/img/dashboard-icon.png'; ?>"/>Dashboard </a> </li>
    <?php }?>
    
  
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Masters<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <?php
            if(checkPermission('unit_view')||checkPermission('unit_add')||checkPermission('unit_edit'))
            {
			  echo '<li id="unitmast"><a href="'.SITE_URL.UNITMASTER.'">Unit Master</a></li>';	
			}
			
			if(checkPermission('group_view')||checkPermission('group_add')||checkPermission('group_edit'))
            {
			  echo '<li id="groupmast"><a href="'.SITE_URL.GROUPMASTER.'">Group Master</a></li>';	
			}
			
			if(checkPermission('state_view')||checkPermission('state_add')||checkPermission('state_edit'))
            {
			  echo '<li id="statemast"><a href="'.SITE_URL.STATEMASTER.'">State Master</a></li>';	
			}
			
			if(checkPermission('city_view')||checkPermission('city_add')||checkPermission('city_edit'))
            {
			  echo '<li id="citymast"><a href="'.SITE_URL.CITYMASTER.'">City Master</a></li>';	
			}
			if(checkPermission('location_view')||checkPermission('location_add')||checkPermission('location_edit'))
            {
			  echo ' <li id="locationmast"><a href="'.SITE_URL.LOCATIONMASTER.'">Location Master</a></li>';	
			}
			if(checkPermission('principal_view')||checkPermission('principal_add')||checkPermission('principal_edit'))
            {
			  echo '<li id="principalmast"><a href="'.SITE_URL.VIEWPRINCIPALMASTER.'">Principal Master</a></li>';	
			}
			if(checkPermission('supplier_view')||checkPermission('supplier_add')||checkPermission('supplier_edit'))
            {
			  echo '<li id="suppliermast"><a href="'.SITE_URL.VIEWSUPPLIERMASTER.'">Supplier Master</a></li>';	
			}
			if(checkPermission('item_view')||checkPermission('item_add')||checkPermission('item_edit'))
            {
			  echo '<li id="itemmast"><a href="'.SITE_URL.ITEMMASTER.'">Item Master</a></li>';	
			}
			if(checkPermission('buyer_view')||checkPermission('buyer_add')||checkPermission('buyer_edit'))
            {
			  echo '<li id="Buyermast"><a href="'.SITE_URL.VIEWBUYERMASTER.'">Buyer Master</a></li>';	
			}
            ?>
            
          
            
            
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-wrench fa-fw"></i>Order's<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
       <?php
       if(checkPermission('challan_view')||checkPermission('challan_add')||checkPermission('challan_edit'))
       {
		 echo '<li id="Challan"><a href="'.SITE_URL.VIEW_CHALLAN.'">Challan</a></li>';	
	   }
	   
	   if(checkPermission('quotation_view')||checkPermission('quotation_add')||checkPermission('quotation_edit'))
       {
		 echo '<li id="Quotation"><a href="'.SITE_URL.VIEWQUATION.'">Quotation</a></li>';	
	   }
	   
	   if(checkPermission('po_view')||checkPermission('po_add')||checkPermission('po_edit'))
       {
		 echo '<li id="PurchaseOrder"><a href="'.SITE_URL.POSEARCH.'">Purchase Order</a></li>';	
	   }
	   
	   if(checkPermission('recurringpo_view')||checkPermission('recurringpo_add')||checkPermission('recurringpo_edit'))
       {
		 echo ' <li id="POSCHEDULE"><a href="'.SITE_URL.POSCHEDULE.'">Recurring Purchase Order</a></li>';	
	   }
	if(checkPermission('stock_transfer_view')||checkPermission('stock_transfer_add')||checkPermission('stock_transfer_edit'))
       {
		 echo '<li id="StockCheck"><a href="<?php print SITE_URL.VIEW_STOCK_TRANSFER; ?>">Stock Transfer</a></li>';	
	   }
	   if(checkPermission('stock_check_view')||checkPermission('stock_check_add')||checkPermission('stock_check_edit'))
       {
		 echo '<li id="StockCheck"><a href="<?php print SITE_URL.VIEW_STOCK_Check; ?>">Stock Check</a></li>';	
	   }
	   if(checkPermission('pendingpo_view')||checkPermission('pendingpo_add')||checkPermission('pendingpo_edit'))
       {
 echo '<li id="POPending"><a href="'.SITE_URL.PO_PENDING_STATEMENT.'?repType=pending">Pending Purchase Order</a></li>';	
	   }
	   
	   if(checkPermission('partialpo_view')||checkPermission('partialpo_add')||checkPermission('partialpo_edit'))
       {
		 echo '<li id="Partial"><a href="'.SITE_URL.PO_PARTIAL_DELIVERED_STATEMENT.'?repType=partial">Purchase Order Partialy Deliver</a></li>';	
	   }
	   
	   if(checkPermission('deliveredpo_view')||checkPermission('deliveredpo_add')||checkPermission('deliveredpo_edit'))
       {
		 echo '<li id="Delivered"><a href="'.SITE_URL.PO_DELIVERD_STATEMENT.'?repType=complete">Purchase Order Deliver</a></li>';	
	   }
       ?>    
    </ul>
<!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-edit fa-fw"></i>Invoice<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
        <?php
       if(checkPermission('inexcise_view')||checkPermission('inexcise_add')||checkPermission('inexcise_edit'))
       {
		 echo '<li><a href="'.SITE_URL.VIEW_INCOMING_INVOICE_EXCISE.'" >In-Coming Excise</a></li>';	
	   }
	   	   
	   if(checkPermission('innonexcise_view')||checkPermission('innonexcise_add')||checkPermission('innonexcise_edit'))
       {
		 echo '<li><a href="'.SITE_URL.VIEW_INCOMINGINVOICENONEXCISE.'">In-Coming Non Excise</a></li>';	
	   }
	   if(checkPermission('outexcise_view')||checkPermission('outexcise_add')||checkPermission('outexcise_edit'))
       {
		 echo '<li><a href="'.SITE_URL.VIEW_OUTGOING_INVOICE_EXCISE.'" >Out-Going Excise</a></li>';	
	   }
	   if(checkPermission('outnonexcise_view')||checkPermission('outnonexcise_add')||checkPermission('outnonexcise_edit'))
       {
		 echo '<li><a href="'.SITE_URL.VIEW_OUTGOING_INVOICE_NonEXCISE.'">Out-Going Non Excise</a></li>';	
	   }
	   
        ?>
            
            
            
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-edit fa-fw"></i>Payment<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
        <?php
        if(checkPermission('payment_view')||checkPermission('payment_add'))
       {
		 echo '<li><a href="'.SITE_URL.PAYMENT_RECEIVED_LIST.'">Make Payment</a></li>';	
	   }
	   
	   if(checkPermission('payment_view')||checkPermission('payment_add'))
       {
		 echo '<li><a href="'.SITE_URL.OINVOICE_PAYMENT_PENDING_LIST.'" >Payment Pending Invoice List</a></li>';	
	   }
	   if(checkPermission('pendinginv_view')||checkPermission('pendinginv_add'))
       {
//	echo ' <li><a href="'.SITE_URL.OINVOICE_BUYER_PAYMENT_PENDING_LIST.'">Payment Pending Buyer List</a></li>';	
	   }
        ?>
     </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-edit fa-fw"></i>Revenue<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="<?php print SITE_URL.VIEW_FINALCIAL_YEAR_WISE_REVENUE; ?>" >Financial Year Wise Buyer Revenue Detail</a></li>
            <li><a href="<?php print SITE_URL.VIEW_BUYER_WISE_REVENUE; ?>">Buyer Wise Revenue detail</a></li> 
            
           
        </ul>
        <!-- /.nav-second-level -->
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-table fa-fw"></i>Report<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <?php
      if(checkPermission('excisestockval_View'))
      {
			echo '<li id="salsereport"><a href="'.SITE_URL.EX_STOCK_STMT_WV.'">Excise Stock Statement With Value</a></li>';
	  }
         
      if(checkPermission('nonexcisestockval_view'))
     {
	   echo '<li id="salsereport"><a href="'.SITE_URL.NONEX_STOCK_STMT_WV.'">Non Excise Stock Statement With Value</a></li>';
	 }
	 
	 if(checkPermission('excisenonexcixestoc_view'))
     {
	   echo ' <li id="salsereport">
	   <a href="'.SITE_URL. EX__NEX_CHALLAN_STOCK_STMT.'">Excise,Non-Excise,Challan Stock-Statement</a>
	   </li>';
	 }
	     if(checkPermission('excisestockstmt_view'))
         {
			echo '<li id="salsereport"><a href="'.SITE_URL.EX_STOCK_STMT.'">Excise Stock Statement</a></li>';
	     }
	     
	     if(checkPermission('nonexcisestockstmt_view'))
         {
		echo '<li id="salsereport"><a href="'.SITE_URL.NONEX_STOCK_STMT.'">Non Excise Stock Statement</a></li>';
	     }
	     if(checkPermission('excisesecondsal_view'))
         {
			echo '<li id="salsereport"><a href="'.SITE_URL.EX_SECONDARY_SALSE_STATEMENT.'">Excise Secondary Sales Statement</a></li>';
	     }
	     if(checkPermission('nonexcisesecondsal_view'))
         {
			echo '<li id="salsereport"><a href="'.SITE_URL.NONEX_SECONDARY_SALSE_STATEMENT.'">Non Excise Secondary Sales Statement</a></li>';
	     }
	     if(checkPermission('excisesalstmt_view'))
         {
			echo '<li id="salsereport"><a href="'.SITE_URL.EX_SALSE_STATEMENT.'">Excise Sales Statement</a></li>';
	     }
	if(checkPermission('nonexcisesalstmt_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.NONEX_SALSE_STATEMENT.'">Non Excise Sales Statement</a></li>';
	}
	
	if(checkPermission('exciseproductledger_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.EX_ProductLedger.'">Excise Product Ledger</a></li>';
	} 
	
	if(checkPermission('nonexciseproductledger_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.NON_ProductLedger.'">Non Excise Product Ledger</a></li>';
	} 
	
	if(checkPermission('salestaxreturnexcise_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.EX_SalesTaxReturn.'">Sales Tax Return Excise</a></li>';
	}
	
	if(checkPermission('salestaxreturnnonexcise_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.NON_SalesTaxReturn.'">Sales Tax Return Non Excise</a></li>';
	} 
	
	if(checkPermission('inexcisereturn_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.INCOMING_EXCISERETURN.'">Incoming Excise Return</a></li>';
	}  
	
	if(checkPermission('innonexcisereturn_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.OUTGOING_EXCISERETURN.'">Outgoing Excise Return</a></li>';
	} 
	
	if(checkPermission('innonexcisereturn_view'))
    {
	echo ' <li id="salsereport"><a href="'.SITE_URL.STOCKTRANSFER_EXCISERETURN.'">Stock Transfer Excise Return</a></li>';
	} 
	
	if(checkPermission('marginreport_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.MARGINREPORT.'">Margin Report Excise</a></li>';
	}
	if(checkPermission('marginreport_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.MARGINREPORTNONEXCISE.'">Margin Report Non-Excise</a></li>';
	}
	if(checkPermission('marginreport_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.PURCHASEREPORT.'">Purchase Report</a></li>';
	}
	if(checkPermission('marginreport_view'))
    {
	echo '<li id="salsereport"><a href="'.SITE_URL.DAILYSALSEREPORT.'">Daily Sales Report</a></li>';
	}		
     ?>
        </ul>
        <!-- /.nav-second-level -->
    </li>
   
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-edit fa-fw"></i>Manage Users<i class="fa fa-caret-down"></i></a>
        <ul class="dropdown-menu dropdown-user">
            <?php
          if(checkPermission('user_view')||checkPermission('user_add')||checkPermission('user_edit'))
          {
		  	 echo '<li id="usermast"><a href="'.SITE_URL.VIEWUSERMASTER.'">User Master</a></li>';
		  }
		  
		   if(checkPermission('privilege_view')||checkPermission('privilege_add')||checkPermission('privilege_edit'))
          {
		  	echo '<li><a href="'.SITE_URL.USERSLIST.'">Add Privilege</a></li>';
		  }
		  
		   if(checkPermission('groupperm_view')||checkPermission('groupperm_add')||checkPermission('groupperm_edit'))
          {  
            echo '<li><a href="'.SITE_URL.GROUPPERMISSION.'">Group Permission</a></li>';
		  }
         
          
      
          ?>
        </ul>
        <!-- /.nav-second-level -->
    </li>
</ul>
            <!-- /.navbar-header -->
<ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-user fa-fw"></i> <?php echo($_SESSION["USER_NAME"]); ?> <i class="fa fa-caret-down"></i><p><?php echo($_SESSION["SITENAME"]); ?> Instance</p>
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

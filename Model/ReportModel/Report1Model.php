<?php
class Report1Model{
    public $Data = array();
    public static function GetPOListOfPandingManagmentApproval_Managment(){
		$Query="select po.bpoId, po.bpono,DATE_FORMAT(po.bpoDate,'%d/%m/%Y')bpoDate, po.po_status, DATE_FORMAT(po.bpoVDate,'%d/%m/%Y')bpoVDate , bpoType as Type,po_val, 
bm.BuyerName,LocationName from purchaseorder as po 
LEFT JOIN purchaseorder_detail as pod ON po.bpoId = pod.bpoId 
INNER JOIN buyer_master as bm ON po.BuyerId = bm.BuyerId 
INNER JOIN location_master as lm ON po.po_shipLocationId = lm.LocationId 
WHERE po.management_approval = 'R' 
AND po.approval_status='X' 
AND po.po_status = 'O' 
group by po.bpoId, po.bpono";
	    $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetPOListOfPandingManagmentApproval_SuperAdmin(){
		$Query="select po.bpoId, po.bpono,DATE_FORMAT(po.bpoDate,'%d/%m/%Y')bpoDate, po.po_status, DATE_FORMAT(po.bpoVDate,'%d/%m/%Y')bpoVDate ,bpoType as Type,po_val, 
bm.BuyerName,LocationName from purchaseorder as po 
LEFT JOIN purchaseorder_detail as pod ON po.bpoId = pod.bpoId 
INNER JOIN buyer_master as bm ON po.BuyerId = bm.BuyerId 
INNER JOIN location_master as lm ON po.po_shipLocationId = lm.LocationId 
WHERE po.management_approval = 'D' AND po.po_status = 'O' 
group by po.bpoId, po.bpono";
	    $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
	
//############################ function for getting pending po list ############ 
	public static function GetPendingPoList($executiveId,$repType)
	{
	  $opt='';
	  $opt1='';	
	  if($executiveId!='')
	  {
	  	$opt.=" AND po.executiveId='$executiveId' ";
	  	$opt1.=" AND po.executiveId='$executiveId' ";
	  }
	  if($repType=='pending')
	  {
	  	$opt1.=" AND posd.pos_item_stage IN ('YDE') ";
	  	$opt.=" AND pod.po_item_stage IN ('YDE') ";
	  }
	  else if($repType=='partial')
	  {
	  	$opt1.=" AND posd.pos_item_stage IN ('POIG') ";
	  	$opt.=" AND pod.po_item_stage IN ('POIG') ";
	  }
	  $Query="SELECT po.bpoId AS bpoId,pod.bpod_Id AS bpod_Id,po.bpono,po.bpoDate,bpoVDate,executiveId,bpoType,po_val,b.BuyerName FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt  AND bpoType='N' GROUP BY po.bpoId UNION ALL SELECT posd.bpoId AS bpoId,posd.bpod_Id AS bpod_Id,po.bpono,po.bpoDate,bpoVDate,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt1  AND bpoType='R' GROUP BY posd.bpoId ORDER BY bpoId DESC LIMIT 0,5 ";
	
	  $Result = DBConnection::SelectQuery($Query);
	  return $Result;	
	}
	
	//############################ function for getting QUOTATION's ############ Rajiv #####
	 public static function GetQuotationList($executiveId,$repType){
		 
		if($executiveId!='' && $_SESSION['USER_TYPE'] =="E" ){ 
			$Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed ,
		prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm on qut.salesTax = vcm.SALESTAX_ID left join param as prm on  
		prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId WHERE qut.userId='$executiveId' AND qut.quotNo NOT IN (SELECT po_quotNo  AS quotNo FROM purchaseorder_detail INNER JOIN purchaseorder ON purchaseorder.bpoId = purchaseorder_detail.bpoId WHERE executiveId = '$executiveId' AND quotNo is not null and quotNo != '0' and quotNo != '') ORDER BY quotId DESC LIMIT 0,5"; 
	
		}else{			
			$Query = "SELECT qut.*,psm.Principal_Supplier_Name, vcm.SALESTAX_DESC, vcm.SURCHARGE_DESC,prm.PARAM_VALUE1 as ed ,
		prm1.PARAM_VALUE1 as del FROM quotation as qut left JOIN principal_supplier_master as psm ON qut.principalId = psm.Principal_Supplier_Id left join vat_cst_master as vcm on qut.salesTax = vcm.SALESTAX_ID left join param as prm on  
		prm.PARAM_CODE = 'APPLICABLE' AND prm.PARAM1 = qut.ed_edu_tag left join param as prm1 on prm1.PARAM_CODE = 'LIST' AND prm1.PARAM_VALUE2 = qut.deliveryId WHERE qut.quotNo NOT IN (SELECT po_quotNo AS quotNo FROM purchaseorder_detail INNER JOIN purchaseorder ON purchaseorder.bpoId = purchaseorder_detail.bpoId WHERE po_quotNo is not null and po_quotNo != '0' and po_quotNo != '') ORDER BY quotId DESC LIMIT 0,5"; 
	
			
		}	
		//echo $Query; exit;
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
	//############################ function for getting Buyer name ############ Rajiv #####
	 public static function GetQuotationBuyerName($_buyer_id){
			   
		$BuyerQuery = "select buyer_master.BuyerId, buyer_master.BuyerName, buyer_master.Bill_Add1,buyer_master.Bill_Add2, buyer_master.Phone, buyer_master.Mobile,buyer_master.Pincode,buyer_master.FAX,buyer_master.Email,city_master.CityName, location_master.LocationName, state_master.StateName from buyer_master inner join city_master ON city_master.CityId = buyer_master.CityId inner join location_master ON location_master.LocationId = buyer_master.LocationId inner join state_master ON state_master.StateId = buyer_master.StateId WHERE buyer_master.BuyerId = $_buyer_id";
		
		$BuyerResult = DBConnection::SelectQuery($BuyerQuery);
		$BuyerRow = mysql_fetch_array($BuyerResult, MYSQL_ASSOC);
		return $BuyerRow;
	}
	//############################ function for gettingCustomer name ############ Rajiv #####
	 public static function GetQuotationCustomerName($_cust_id){
			   
		$CustomerQuery = "SELECT  cust_name, cust_add FROM cust_master WHERE cust_id = $_cust_id";
		
		$CustomerResult = DBConnection::SelectQuery($Query);
		$CustomerRow = mysql_fetch_array($CustomerResult, MYSQL_ASSOC);
		return $CustomerRow;
	}
	
	//############################ function for getting Open po list ############ Rajiv #####
	public static function GetOpenPoList($executiveId,$repType)
	{
	  $opt='';
	  $opt1='';	
	  if($executiveId!='')
	  {
	  	$opt.=" AND po.executiveId='$executiveId' ";
	  	$opt1.=" AND po.executiveId='$executiveId' ";
	  }
	  if($repType=='open')
	  {
	  	$opt1.=" AND (posd.pos_item_stage IN ('YDE') OR posd.pos_item_stage IN ('POIG'))";
	  	$opt.=" AND (pod.po_item_stage IN ('YDE') OR pod.po_item_stage IN ('POIG'))";
	  }
	 
	  $Query="SELECT po.bpoId AS bpoId,pod.bpod_Id AS bpod_Id,po.bpono,po.bpoDate,bpoVDate,executiveId,bpoType,po_val,b.BuyerName,po.BuyerId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt  AND bpoType='N' GROUP BY po.bpoId UNION ALL SELECT posd.bpoId AS bpoId,posd.bpod_Id AS bpod_Id,po.bpono,po.bpoDate,bpoVDate,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName,po.BuyerId  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt1  AND bpoType='R' GROUP BY posd.bpoId ORDER BY bpoId DESC LIMIT 0,5 ";

	  $Result = DBConnection::SelectQuery($Query);
	  return $Result;	
	}
	
	
	//**######################## Buyer Wise Revenue reports
	
	public static function GetBuyerWiseRewenue($executiveId)
	{
	   $opt="";
	   if($executiveId!='')
	   {
	   	$opt=" AND po.executiveId='$executiveId' ";	
	   }
	   
	   $Query="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(po_val) AS po_val,b.BuyerName FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt  AND pod.po_item_stage IN ('POIG','OIG')   AND bpoType='N' GROUP BY po.BuyerId UNION ALL SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId  $opt  AND posd.pos_item_stage IN ('POIG','OIG') AND bpoType='R' GROUP BY  po.BuyerId ORDER BY BuyerName  LIMIT 0,5 ";
       $Result = DBConnection::SelectQuery($Query);
	  return $Result;
	}
	
	//################ Invoice Payment Pending List
	 public static function GetPendingPaymentList($executiveId,$payment_status)
	 {
	 	 $opt="";
	 	 if($executiveId!='')
	     {
	   	  $opt=$opt." AND po.executiveId='$executiveId' ";	
	     }
	   	
	     $opt=$opt." AND payment_status='$payment_status' ";
	     
	 	$Query="SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt
	 	UNION ALL SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt ORDER BY invoiceAmount DESC LIMIT 0,5";
	 
	 	$Result = DBConnection::SelectQuery($Query);
         return $Result;
	 }
	 
	 //################ Invoice Payment Pending List
	 public static function GetPendingPaymentListForAdmin($executiveId,$payment_status,$executiveType,$finyear)
	 {
	 	 $opt='';
   	     $opt1='';  	
	     $opt=$opt." AND payment_status='$payment_status' ";
	     $opt1=$opt1." AND payment_status='$payment_status' ";
	   /*  
	     $Query1="(SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId as buyerid,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate  FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId
		INNER JOIN outgoinginvoice_excise_mapping AS oem ON noe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx
		,purchaseorder AS po WHERE noe.pono=po.bpoId AND oem.finyear='$finyear' $opt )";
		$Query2="(SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId as buyerid,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId
		INNER JOIN outgoinginvoice_nonexcise_mapping AS onm ON noe.oinvoice_nexciseID = onm.inner_outgoingInvoiceNonEx
		,purchaseorder AS po WHERE noe.pono=po.bpoId AND onm.finyear='$finyear' $opt1 )";
		
		
	 	$Query="SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt
	 	UNION ALL SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt ORDER BY invoiceAmount DESC LIMIT 0,5"; 
		$Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2.") as s ORDER BY s.invoiceNo,s.invoiceDate DESC LIMIT 0,5 ";
		//echo $Query;
		
		*/
			
	 	$Query="SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId AND noe.oinv_date > '2015-03-31' $opt
	 	UNION ALL SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate,bm.BuyerId as buyerid FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId AND noe.oinv_date > '2015-03-31'  $opt ORDER BY invoiceDate ASC LIMIT 0,5"; 
				
	 	$Result = DBConnection::SelectQuery($Query);
	  	return $Result;
	 }
	
//############################# end ##################################################	
}

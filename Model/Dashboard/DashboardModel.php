<?php
class DashboardModel
{
    public static function LoadListData()
    {
        //$DATA = "";
        $Query1 = "select count(*) result from item_master";
        $result = DBConnection::SelectQuery($Query1);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $Row['result']."#";
        //echo($DATA);
        $Query2 = "select count(*) result FROM purchaseorder WHERE purchaseorder.po_status = 'O' AND purchaseorder.bpoDate > date('Y-m-d')";
        $result = DBConnection::SelectQuery($Query2);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result']."#";
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        //$Query3 = "select count(*) result from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Lsc >= iv.tot_exciseQty OR im.Lsc >= iv.tot_nonExciseQty";
		$Query3 = "select count(*) result from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Lsc >= iv.tot_Qty";
		/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $result = DBConnection::SelectQuery($Query3);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result'];
        return $DATA;
    }
	    
    public static function LoadExecutiveData($USERID)
    {
    	$DATA="0";
    	$opt='';
	    $opt1='';
	  	$opt.=" AND po.executiveId='$USERID' ";
	  	$opt1.=" AND po.executiveId='$USERID' ";
	 
	  	$opt1.=" AND posd.pos_item_stage IN ('YDE') ";
	  	$opt.=" AND pod.po_item_stage IN ('YDE') ";
		
		 //$Query1="SELECT COUNT( * ) AS result FROM (SELECT po.bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId = pod.bpoId AND (po.bpoType =  'N' OR po.bpoType = 'R') AND pod.po_item_stage != 'OIG' AND po.executiveId = '$USERID' GROUP BY po.bpoId ) AS s";
		 
		 $Query1="SELECT COUNT( * ) AS result FROM purchaseorder WHERE bpoId NOT IN(SELECT pono FROM ( SELECT oie.pono AS pono FROM outgoinginvoice_excise AS oie INNER JOIN purchaseorder AS po ON po.bpoId = oie.pono WHERE po.executiveId =  '$USERID' UNION ALL SELECT oine.pono AS pono FROM outgoinginvoice_nonexcise AS oine INNER JOIN purchaseorder AS po ON po.bpoId = oine.pono WHERE po.executiveId = '$USERID') AS pono GROUP BY pono ) AND executiveId =  '$USERID'";
		$result = DBConnection::SelectQuery($Query1);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result']."#"; 
	  	
	  
	    $Query2="select count(*)as result from (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt  AND bpoType='N' GROUP BY po.bpoId UNION ALL SELECT posd.bpoId AS bpoId  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt1  AND bpoType='R' GROUP BY posd.bpoId) as tmp  ";
	
	    $result = DBConnection::SelectQuery($Query2);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result']."#"; 
        $opt='';
	    $opt1='';
	    $opt.=" AND po.executiveId='$USERID' ";
	  	$opt1.=" AND po.executiveId='$USERID' ";
	    $opt1.=" AND posd.pos_item_stage IN ('POIG') ";
	  	$opt.=" AND pod.po_item_stage IN ('POIG') ";
        $Query3="select count(*)as result from (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt  AND bpoType='N' GROUP BY po.bpoId UNION ALL SELECT posd.bpoId AS bpoId  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt1  AND bpoType='R' GROUP BY posd.bpoId) as tmp  ";
	
	    $result = DBConnection::SelectQuery($Query3);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result']."#"; 
    	$Query4="select sum(tmp.balanceAmount) result from (SELECT balanceAmount FROM outgoinginvoice_excise  AS noe,purchaseorder AS po WHERE noe.pono=po.bpoId   AND po.executiveId='$USERID'  AND payment_status='P' UNION ALL SELECT balanceAmount FROM outgoinginvoice_nonexcise  AS noe,purchaseorder AS po WHERE noe.pono=po.bpoId   AND po.executiveId='$USERID'  AND payment_status='P') as tmp";
    	
    	$result = DBConnection::SelectQuery($Query4);
        $Row = mysql_fetch_array($result, MYSQL_ASSOC);
        $DATA = $DATA.$Row['result']; 
		return $DATA;
	}
	
    public static function LoadLSCItemData()
    {
        
        //$Query = "select im.*,iv.*,pm.Principal_Supplier_Name from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo left join principal_supplier_master as pm on im.PrincipalId = pm.Principal_Supplier_Id WHERE im.Lsc >= iv.tot_exciseQty OR im.Lsc >= iv.tot_nonExciseQty";
		$Query = "select im.*,iv.*,pm.Principal_Supplier_Name from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo left join principal_supplier_master as pm on im.PrincipalId = pm.Principal_Supplier_Id WHERE im.Lsc >= iv.tot_Qty";
        $result = DBConnection::SelectQuery($Query);
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        //$Item_Obj = new Inventory_Model(0,0,0,"","","",0,0,0);
		$Item_Obj = new Inventory_Model(0,0,"","","",0,0,0);
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
             $Item_Obj->code_partNo = $Row['ItemId'];
             $Item_Obj->item_codepart = $Row['Item_Code_Partno'];
             $Item_Obj->item_desc = $Row['Item_Desc'];
             $Item_Obj->price = $Row['Cost_Price'];
             $Item_Obj->lsc = $Row['Lsc'];
             $Item_Obj->usc = $Row['Usc'];
             $Item_Obj->principalname = $Row['Principal_Supplier_Name'];
			 /* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
             //$Item_Obj->tot_exciseQty = $Row['tot_exciseQty'];
             //$Item_Obj->tot_nonExciseQty = $Row['tot_nonExciseQty'];
			 $Item_Obj->tot_Qty = $Row['tot_Qty'];
             //$newObj = new Inventory_Model($Item_Obj->code_partNo,$Item_Obj->tot_exciseQty,$Item_Obj->tot_nonExciseQty,$Item_Obj->principalname,$Item_Obj->item_codepart,$Item_Obj->item_desc,$Item_Obj->lsc,$Item_Obj->usc,$Item_Obj->price);
			 $newObj = new Inventory_Model($Item_Obj->code_partNo,$Item_Obj->tot_Qty,$Item_Obj->principalname,$Item_Obj->item_codepart,$Item_Obj->item_desc,$Item_Obj->lsc,$Item_Obj->usc,$Item_Obj->price);
			 /* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
             $objArray[$i] = $newObj;
             $i++;
		}
		return $objArray;
    }
	
    public static function LoadItemData()
    {
        /* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        //$Query = "select im.*,iv.*,pm.Principal_Supplier_Name from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo left join principal_supplier_master as pm on im.PrincipalId = pm.Principal_Supplier_Id where (iv.tot_exciseQty>0 OR iv.tot_nonExciseQty>0)";
		$Query = "select im.*,iv.*,pm.Principal_Supplier_Name from item_master as im left join inventory as iv on im.ItemId = iv.code_partNo left join principal_supplier_master as pm on im.PrincipalId = pm.Principal_Supplier_Id where iv.tot_Qty > 0";
		/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $result = DBConnection::SelectQuery($Query);
        $Item_Obj = new Inventory_Model(0,0,0,"","","",0,0,0);
        $objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
             $Item_Obj->code_partNo = $Row['ItemId'];
             $Item_Obj->item_codepart = $Row['Item_Code_Partno'];
             $Item_Obj->item_desc = $Row['Item_Desc'];
             $Item_Obj->price = $Row['Cost_Price'];
             $Item_Obj->lsc = $Row['Lsc'];
             $Item_Obj->usc = $Row['Usc'];
             $Item_Obj->principalname = $Row['Principal_Supplier_Name'];
			 /* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
             //$Item_Obj->tot_exciseQty = $Row['tot_exciseQty'];
             //$Item_Obj->tot_nonExciseQty = $Row['tot_nonExciseQty'];
			 $Item_Obj->tot_Qty = $Row['tot_Qty'];
			 
             //$newObj = new Inventory_Model($Item_Obj->code_partNo,$Item_Obj->tot_exciseQty,$Item_Obj->tot_nonExciseQty,$Item_Obj->principalname,$Item_Obj->item_codepart,$Item_Obj->item_desc,$Item_Obj->lsc,$Item_Obj->usc,$Item_Obj->price);
			 $newObj = new Inventory_Model($Item_Obj->code_partNo,$Item_Obj->tot_Qty,$Item_Obj->principalname,$Item_Obj->item_codepart,$Item_Obj->item_desc,$Item_Obj->lsc,$Item_Obj->usc,$Item_Obj->price);
			 /* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
             $objArray[$i] = $newObj;
             $i++;
		}
		return $objArray;
    }
	
	
	
	
	public static function LoadPODetails($poid,$bpono,$poType)
    {
        $DATA = "";
      
	   
     //$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oie.oinvoice_No,oie.payment_status,oie.bill_value,oie.oinv_date,oie.oinvoice_exciseID as invid FROM purchaseorder as po INNER JOIN outgoinginvoice_excise as oie ON oie.pono = po.bpoId WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 
	// $Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oine.oinvoice_No,oine.payment_status,oine.bill_value ,oine.oinv_date,oine.oinvoice_nexciseID as invid FROM purchaseorder as po INNER JOIN outgoinginvoice_nonexcise as oine ON oine.pono = po.bpoId WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 	   
	//echo $Query1; exit;    
	
	/*
	
	$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oie.oinvoice_No,oie.payment_status,oie.bill_value,oie.oinv_date,oie.oinvoice_exciseID as invid,oied.oinv_codePartNo,oied.ordered_qty,oied.issued_qty,pod.po_codePartNo,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 
	INNER JOIN outgoinginvoice_excise as oie ON oie.pono = po.bpoId 
	INNER JOIN outgoinginvoice_excise_detail as oied ON oied.oinvoice_exciseID = oie.oinvoice_exciseID
	INNER JOIN purchaseorder_detail as pod ON pod.bpod_Id = oied.oinv_codePartNo
	INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
	WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 
	//echo $Query1; exit;
	$Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oine.oinvoice_No,oine.payment_status,oine.bill_value ,oine.oinv_date,oine.oinvoice_nexciseID as invid,oined.oinv_codePartNo,oined.ordered_qty,oined.issued_qty,pod.po_codePartNo,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 
	 INNER JOIN outgoinginvoice_nonexcise as oine ON oine.pono = po.bpoId 
	 INNER JOIN outgoinginvoice_nonexcise_detail as oined ON oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
	 INNER JOIN purchaseorder_detail as pod ON pod.bpod_Id = oined.oinv_codePartNo
	 INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
	 WHERE po.bpoId = $poid AND po.bpono ='$bpono'"; 
	 
	 */
	 
	 
	 	$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oie.oinvoice_No,oie.payment_status,oie.bill_value,oie.oinv_date,oie.oinvoice_exciseID as invid,oied.oinv_codePartNo,oied.ordered_qty,oied.issued_qty,im.Item_Code_Partno,im.Item_Desc ,oied.codePartNo_desc
		FROM purchaseorder as po 		
		INNER JOIN outgoinginvoice_excise as oie ON oie.pono = po.bpoId 
		INNER JOIN outgoinginvoice_excise_detail as oied ON oied.oinvoice_exciseID = oie.oinvoice_exciseID 
		INNER JOIN item_master as im ON im.ItemId = oied.codePartNo_desc
		WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 
	//echo $Query1; exit;
	$Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oine.oinvoice_No,oine.payment_status,oine.bill_value ,oine.oinv_date,oine.oinvoice_nexciseID as invid,oined.oinv_codePartNo,oined.ordered_qty,oined.issued_qty,im.Item_Code_Partno,im.Item_Desc ,oined.codePartNo_desc
	FROM purchaseorder as po 	
	INNER JOIN outgoinginvoice_nonexcise as oine ON oine.pono = po.bpoId 
	INNER JOIN outgoinginvoice_nonexcise_detail as oined ON oined.oinvoice_nexciseID = oine.oinvoice_nexciseID 
	INNER JOIN item_master as im ON im.ItemId = oined.codePartNo_desc 
	 WHERE po.bpoId = $poid AND po.bpono ='$bpono'"; 
	 
	 
	//echo $Query2; exit;	 
	 $Query="SELECT * FROM  ((".$Query1.")  UNION ALL (".$Query2." )) as s ";	
	// echo $Query; exit;
	 $result = DBConnection::SelectQuery($Query);
     //$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		  $objArray = array();
		$i = 0;		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['bpoId'] = $Row['bpoId'];
			  $objArray[$i]['bpono'] = $Row['bpono'];
			  $objArray[$i]['oinvoice_No'] = $Row['oinvoice_No'];
			  $objArray[$i]['payment_status'] = $Row['payment_status'];
			  $objArray[$i]['po_val'] = $Row['po_val'];
			  $objArray[$i]['bill_value'] = $Row['bill_value'];
			  $objArray[$i]['bpoDate'] = $Row['bpoDate'];
			  $objArray[$i]['oinv_date'] = $Row['oinv_date'];
			  $objArray[$i]['invid'] = $Row['invid'];
			  $objArray[$i]['bpoType'] = $Row['bpoType'];
			  $objArray[$i]['po_codePartNo'] = $Row['codePartNo_desc'];
			  $objArray[$i]['Item_Code_Partno'] = $Row['Item_Code_Partno'];			  
			  $objArray[$i]['ordered_qty'] = $Row['ordered_qty'];
			  $objArray[$i]['issued_qty'] = $Row['issued_qty'];
			  $objArray[$i]['Item_Desc'] = $Row['Item_Desc'];
			 // $objArray[$i]['po_ed_applicability'] = $Row['po_ed_applicability'];
			 
			  $i++;
		}
		
		return $objArray;
		
    }
	public static function LoadPODetails4PickList($poid,$bpono,$poType)
    {
        $DATA = "";
      
	   
     //$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oie.oinvoice_No,oie.payment_status,oie.bill_value,oie.oinv_date,oie.oinvoice_exciseID as invid FROM purchaseorder as po INNER JOIN outgoinginvoice_excise as oie ON oie.pono = po.bpoId WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 
	// $Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oine.oinvoice_No,oine.payment_status,oine.bill_value ,oine.oinv_date,oine.oinvoice_nexciseID as invid FROM purchaseorder as po INNER JOIN outgoinginvoice_nonexcise as oine ON oine.pono = po.bpoId WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 	   
	//echo $Query1; exit;    
	
	
	
	$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oie.oinvoice_No,oie.payment_status,oie.bill_value,oie.oinv_date,oie.oinvoice_exciseID as invid,oied.oinv_codePartNo,oied.ordered_qty,oied.issued_qty,pod.po_codePartNo,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 
	INNER JOIN outgoinginvoice_excise as oie ON oie.pono = po.bpoId 
	INNER JOIN outgoinginvoice_excise_detail as oied ON oied.oinvoice_exciseID = oie.oinvoice_exciseID
	INNER JOIN purchaseorder_detail as pod ON pod.bpod_Id = oied.oinv_codePartNo
	INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
	WHERE po.bpoId = $poid AND po.bpono ='$bpono' "; 
	//echo $Query1; exit;
	$Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oine.oinvoice_No,oine.payment_status,oine.bill_value ,oine.oinv_date,oine.oinvoice_nexciseID as invid,oined.oinv_codePartNo,oined.ordered_qty,oined.issued_qty,pod.po_codePartNo,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 
	 INNER JOIN outgoinginvoice_nonexcise as oine ON oine.pono = po.bpoId 
	 INNER JOIN outgoinginvoice_nonexcise_detail as oined ON oined.oinvoice_nexciseID = oine.oinvoice_nexciseID
	 INNER JOIN purchaseorder_detail as pod ON pod.bpod_Id = oined.oinv_codePartNo
	 INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
	 WHERE po.bpoId = $poid AND po.bpono ='$bpono'"; 
	
	
	//echo $Query2; exit;	 
	 $Query="SELECT * FROM  ((".$Query1.")  UNION ALL (".$Query2." )) as s ";	
	// echo $Query; exit;
	 $result = DBConnection::SelectQuery($Query);
     //$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		  $objArray = array();
		$i = 0;		
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['bpoId'] = $Row['bpoId'];
			  $objArray[$i]['bpono'] = $Row['bpono'];
			  $objArray[$i]['oinvoice_No'] = $Row['oinvoice_No'];
			  $objArray[$i]['payment_status'] = $Row['payment_status'];
			  $objArray[$i]['po_val'] = $Row['po_val'];
			  $objArray[$i]['bill_value'] = $Row['bill_value'];
			  $objArray[$i]['bpoDate'] = $Row['bpoDate'];
			  $objArray[$i]['oinv_date'] = $Row['oinv_date'];
			  $objArray[$i]['invid'] = $Row['invid'];
			  $objArray[$i]['bpoType'] = $Row['bpoType'];
			  $objArray[$i]['po_codePartNo'] = $Row['po_codePartNo'];
			  $objArray[$i]['Item_Code_Partno'] = $Row['Item_Code_Partno'];			  
			  $objArray[$i]['ordered_qty'] = $Row['ordered_qty'];
			  $objArray[$i]['issued_qty'] = $Row['issued_qty'];
			  $objArray[$i]['Item_Desc'] = $Row['Item_Desc'];
			 // $objArray[$i]['po_ed_applicability'] = $Row['po_ed_applicability'];
			 
			  $i++;
		}
		
		return $objArray;
		
    }
    
    public static function LoadPOState($poid,$bpono)
    {
    
		$Query = "select po_state,po_hold_reason FROM purchaseorder WHERE bpoId = $poid AND bpono ='$bpono' "; 
		$result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		//print_r($Row); exit;
		return $Row;
		
    }
    
     public static function LoadPOStatus($poid,$bpono)
    {
    
		$Query = "select po_status,po_close_reason FROM purchaseorder WHERE bpoId = $poid AND bpono ='$bpono' "; 
		$result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		//print_r($Row); exit;
		return $Row;
		
    }
    
    public static function UpdatePoState($POID,$POSTATE,$HOLDREASON){
	
    $Query="UPDATE purchaseorder SET po_state='$POSTATE',po_hold_reason='$HOLDREASON' WHERE bpoId='$POID'";
	$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			
			return QueryResponse::NO;
		}
	}
	
	// change PO status to close 28-JAN -2016
	public static function UpdatePoStatusById($POID,$CLOSEREASON){

	// added on 02-JUNE-2016 due to Handle Special Character
	$CLOSEREASON = mysql_escape_string($CLOSEREASON);
				
    $Query="UPDATE purchaseorder SET po_status='C',po_close_reason='$CLOSEREASON' WHERE bpoId='$POID'";
	$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			
			return QueryResponse::NO;
		}
	}
	
	public static function LoadPOInvoiceDetails($POID,$BPONO,$invno,$oinvoice_exciseID,$bpoType)
    {
		 $findme   = 'E';
		 $pos = strrchr($invno, $findme);
		if($pos !='' || $pos != null){ 
				$_itmes = Outgoing_Invoice_Excise_Model_Details::LoadOutgoingInvoiceExciseDetails($oinvoice_exciseID,$bpoType);
		
		 }else{
			$_itmes = Outgoing_Invoice_NonExcise_Model_Details::LoadOutgoingInvoiceNonExciseDetails($oinvoice_exciseID,$bpoType);
		} 
		//print_r($_itmes);
		return $_itmes; 
				
    }
	
	public static function LoadPendingPoItemDetails($poid,$bpono,$poType)
    {
		  $DATA = "";   
	
	/* $Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,oied.oinv_codePartNo,oied.ordered_qty,oied.issued_qty,pod.po_codePartNo,im.Item_Code_Partno FROM purchaseorder as po 	
	INNER JOIN purchaseorder_detail as pod ON pod.bpoId == po.bpoId
	INNER JOIN outgoinginvoice_excise_detail as oied ON oied.po_codePartNo !=  pod.po_codePartNo
	INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
	WHERE po.bpoId = $poid AND po.bpono ='$bpono' ";  */
	//$Query1="";

	if($poType =='Recurring'){
			$Query1 = "select po.bpoId, po.bpono, po.po_val, po.bpoDate, po.bpoType, pod.po_codePartNo, pod.po_qty, pod.po_ed_applicability, im.Item_Code_Partno, im.Item_Desc,posd.sch_req_qty,posd.sch_scheduledate
			FROM purchaseorder as po 
			INNER JOIN purchaseorder_detail as pod ON pod.bpoId = po.bpoId
			INNER JOIN purchaseorder_schedule_detail as posd ON posd.bpod_Id = pod.bpod_Id
			INNER JOIN item_master as im ON im.ItemId = posd.sch_codePartNo
			WHERE po.bpoId = $poid AND po.bpono ='$bpono' AND (posd.pos_item_stage ='POIG' OR posd.pos_item_stage ='YDE') "; 
			
			//echo $Query1; exit;
		$objArray = array();
		$i = 0;	
		 $result = DBConnection::SelectQuery($Query1);	
		while($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['bpoId'] = $Row['bpoId'];
			  $objArray[$i]['bpono'] = $Row['bpono'];			  
			  $objArray[$i]['po_val'] = $Row['po_val'];			  
			  $objArray[$i]['bpoDate'] = $Row['sch_scheduledate'];		
			  $objArray[$i]['bpoType'] = $Row['bpoType'];
			  $objArray[$i]['po_codePartNo'] = $Row['po_codePartNo'];
			  $objArray[$i]['Item_Code_Partno'] = $Row['Item_Code_Partno'];			  
			  $objArray[$i]['ordered_qty'] = $Row['sch_req_qty'];
			  $objArray[$i]['Item_Desc'] = $Row['Item_Desc'];
			  $objArray[$i]['po_ed_applicability'] = $Row['po_ed_applicability'];
			 
			  
			  $i++;
		  }
		
			
		
	}else{
		$Query1 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,pod.po_codePartNo,pod.po_qty,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 	
		INNER JOIN purchaseorder_detail as pod ON pod.bpoId = po.bpoId
		INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
		WHERE po.bpoId = $poid AND po.bpono ='$bpono' AND pod.bpod_Id NOT IN(select oinv_codePartNo FROM outgoinginvoice_nonexcise_detail) "; 
		
		$Query2 = "select po.bpoId,po.bpono,po.po_val,po.bpoDate,po.bpoType,pod.po_codePartNo,pod.po_qty,pod.po_ed_applicability,im.Item_Code_Partno,im.Item_Desc FROM purchaseorder as po 	
		INNER JOIN purchaseorder_detail as pod ON pod.bpoId = po.bpoId
		INNER JOIN item_master as im ON im.ItemId = pod.po_codePartNo
		WHERE po.bpoId = $poid AND po.bpono ='$bpono' AND pod.bpod_Id NOT IN(select oinv_codePartNo FROM outgoinginvoice_excise_detail) ";  
		$Query="SELECT * FROM  ((".$Query1.") INTERSE (".$Query2." )) as s ";	
	
	//echo $Query1;
    $result = DBConnection::SelectQuery($Query1);
     //$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		$objArray = array();
		$i = 0;		
		while($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
              $objArray[$i]['bpoId'] = $Row['bpoId'];
			  $objArray[$i]['bpono'] = $Row['bpono'];			  
			  $objArray[$i]['po_val'] = $Row['po_val'];			  
			  $objArray[$i]['bpoDate'] = $Row['bpoDate'];		
			  $objArray[$i]['bpoType'] = $Row['bpoType'];
			  $objArray[$i]['po_codePartNo'] = $Row['po_codePartNo'];
			  $objArray[$i]['Item_Code_Partno'] = $Row['Item_Code_Partno'];			  
			  $objArray[$i]['ordered_qty'] = $Row['po_qty'];
			  $objArray[$i]['Item_Desc'] = $Row['Item_Desc'];
			  $objArray[$i]['po_ed_applicability'] = $Row['po_ed_applicability'];
			 
			  
			  $i++;
		  }
		
		//return $objArray;
				
		$result1 = DBConnection::SelectQuery($Query2);
		$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		 $objArray1 = array();
		$i = 0;		
		while($Row1 = mysql_fetch_array($result1, MYSQL_ASSOC)) {
              $objArray1[$i]['bpoId'] = $Row1['bpoId'];
			  $objArray1[$i]['bpono'] = $Row1['bpono'];			  
			  $objArray1[$i]['po_val'] = $Row1['po_val'];			  
			  $objArray1[$i]['bpoDate'] = $Row1['bpoDate'];		
			  $objArray1[$i]['bpoType'] = $Row1['bpoType'];
			  $objArray1[$i]['po_codePartNo'] = $Row1['po_codePartNo'];
			  $objArray1[$i]['Item_Code_Partno'] = $Row1['Item_Code_Partno'];			  
			  $objArray1[$i]['ordered_qty'] = $Row1['po_qty'];
			  $objArray1[$i]['Item_Desc'] = $Row1['Item_Desc'];	
			  $objArray1[$i]['po_ed_applicability'] = $Row1['po_ed_applicability'];		  
			  $i++;
		  } 
		
		$objArray2 = array_merge($objArray1,$objArray);
		//return $objArray1;
		}
		return $objArray;
		
				
    }
    
   public static function getItemCurrentStock($itemid)
    {    
		$Query = "select * FROM inventory WHERE code_partNo = $itemid "; 
		$result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($result, MYSQL_ASSOC);
		return $Row;
		
    }
		
}

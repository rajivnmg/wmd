<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
*/
class PO_Reports_Model
{
         public $bpoId;
         public $bpono;
         public $bpoDate;
         public $bpoVDate;
         public $BuyerId;
         public $BuyerName;
         public $executiveId;
         public $po_val;
         public $poType;
		 public $po_status;
		 public $poState;
     public function __construct($bpoId,$bpono,$bpoDate,$bpoVDate,$BuyerId,$BuyerName,$executiveId,$po_val,$bpoType,$po_status=null,$poState=null)
    {
        $this->bpoId=$bpoId;
        $this->bpono=$bpono;
        $this->bpoDate=$bpoDate;
        $this->bpoVDate=$bpoVDate;
        $this->BuyerId=$BuyerId;
        $this->BuyerName=$BuyerName;
        $this->executiveId=$executiveId;
        $this->po_val=$po_val;
        $this->poType=$bpoType;
        $this->po_status=$po_status;
        $this->poState=$poState;
        
    }
    public static function GetFinYearWiseRevenue($executiveId,$FinYear,$buyerid,$PoType,$principalId,$FromDate,$ToDate,$locationid,$start,$rp)
    {   
	$opt='';
	$opt1='';
	session_start();
	$TYPE = $_SESSION["USER_TYPE"];
	if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A")){ 
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		
		 if(($FromDate!="") && ($ToDate!=""))
		{
			 $opt=$opt." AND po.bpoDate>='$FromDate' AND po.bpoDate<='$ToDate' ";
			 $opt1=$opt1." AND posd.sch_scheduledate>='$FromDate' AND posd.sch_scheduledate<='$ToDate' ";	
		}
		
		 if(!empty($locationid) || ($locationid != 0))
		{
			 $opt=$opt." AND b.LocationId ='$locationid' ";
			 $opt1=$opt1." AND b.LocationId = '$locationid'";	
		}
		
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND po.buyerId='$buyerid'";
		    $opt1=$opt1."AND po.buyerId='$buyerid'";	
		}
		if($FinYear!='')
		{
			$opt_fn=" AND invm.finyear='$FinYear'";
		}
		
		if(!empty($principalId))
		{
			$opt =$opt." AND pod.po_principalId='$principalId'";
			$opt1 =$opt1." AND pod.po_principalId='$principalId'";
		}
		
		$Query1="SELECT COUNT(po.bpoId) AS no_of_po,po.executiveId,po.bpoType,SUM(po.po_val) AS po_val,b.BuyerName,fn.finyear,po.BuyerId,b.locationid,pod.po_principalId FROM (SELECT COUNT(*) AS countInv,finyear,pono FROM (SELECT invm.finyear,inv.pono FROM outgoinginvoice_excise AS inv,outgoinginvoice_excise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceExNo  $opt_fn  UNION ALL  SELECT invm.finyear,inv.pono FROM outgoinginvoice_nonexcise AS inv,outgoinginvoice_nonexcise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceNonExNo  $opt_fn  ORDER BY finyear DESC) AS tmp GROUP BY pono) AS fn INNER JOIN purchaseorder AS po ON fn.pono=po.bpoId
		JOIN purchaseorder_detail AS pod ON pod.bpoId=po.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId AND bpoType='N' $opt GROUP BY po.BuyerId";
		
		
		$Query2="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName ,fn.finyear,po.BuyerId,b.locationid,pod.po_principalId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po INNER JOIN (SELECT COUNT(*) AS countInv,finyear,pono FROM (SELECT invm.finyear,inv.pono FROM outgoinginvoice_excise AS inv,outgoinginvoice_excise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceExNo $opt_fn  UNION ALL  SELECT invm.finyear,inv.pono FROM outgoinginvoice_nonexcise AS inv,outgoinginvoice_nonexcise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceNonExNo $opt_fn  ORDER BY finyear DESC) AS tmp GROUP BY pono) AS fn ON po.bpoId=fn.pono,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId AND posd.pos_item_stage IN ('POIG','OIG') AND bpoType='R' $opt1  GROUP BY  po.BuyerId ";
		
		if($PoType!=null ||$PoType!='')
 	   {
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	   }
	   else
	   {
	     $Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   }
	   
	     $Query = $Query." order by s.BuyerName  LIMIT $start,$rp";
	    
	    //	echo $Query ; exit;
	    
	     $result = DBConnection::SelectQuery($Query);
	     $objArray = array();
          $i = 0;
     
         while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)){
         	if($Row['bpoType']=='N')
         	{
				$Row['bpoType']='Normal';
			}
			else if($Row['bpoType']=='R')
			{
				$Row['bpoType']='Recurring';
			}
					
			$Row['locationName'] = self::getLocationById($Row['locationid']);
			$Row['principalName'] = self::getPrincipalByID($Row['po_principalId']);
			
      	   $objArray[$i]=$Row;
            $i++;
         }	
	     return $objArray;
	}
	
	public static function getPrincipalByID($principalID){
		$Query = "SELECT * FROM principal_supplier_master where Principal_Supplier_Id = $principalID";
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		return $Row['Principal_Supplier_Name'];
	}
	public static function getLocationById($locationID){
		$Query = "SELECT  lm.LocationId,lm.LocationName FROM  location_master as lm WHERE lm.LocationId = $locationID"; 
		$Result = DBConnection::SelectQuery($Query);
		$Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		return $Row['LocationName'];
	}
		
	public static function countFinYearWiseRevenue($executiveId,$FinYear,$buyerid,$PoType,$principalId,$FromDate,$ToDate,$locationid)
	{
		$opt='';
	$opt1='';
	session_start();
	$TYPE = $_SESSION["USER_TYPE"];
	if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A")){ 
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		
		 if(($FromDate!="") && ($ToDate!=""))
		{
			 $opt=$opt." AND po.bpoDate>='$FromDate' AND po.bpoDate<='$ToDate' ";
			 $opt1=$opt1." AND posd.sch_scheduledate>='$FromDate' AND posd.sch_scheduledate<='$ToDate' ";	
		}
		
		 if(!empty($locationid) || ($locationid != 0))
		{
			 $opt=$opt." AND b.LocationId ='$locationid' ";
			 $opt1=$opt1." AND b.LocationId = '$locationid'";	
		}
		
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND po.buyerId='$buyerid'";
		    $opt1=$opt1."AND po.buyerId='$buyerid'";	
		}
		if($FinYear!='')
		{
			$opt_fn=" AND invm.finyear='$FinYear'";
		}
		
		if(!empty($principalId))
		{
			$opt =$opt." AND pod.po_principalId='$principalId'";
			$opt1 =$opt1." AND pod.po_principalId='$principalId'";
		}
		
		$Query1="SELECT COUNT(po.bpoId) AS no_of_po,po.executiveId,po.bpoType,SUM(po.po_val) AS po_val,b.BuyerName,fn.finyear,po.BuyerId,b.locationid,pod.po_principalId FROM (SELECT COUNT(*) AS countInv,finyear,pono FROM (SELECT invm.finyear,inv.pono FROM outgoinginvoice_excise AS inv,outgoinginvoice_excise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceExNo  $opt_fn  UNION ALL  SELECT invm.finyear,inv.pono FROM outgoinginvoice_nonexcise AS inv,outgoinginvoice_nonexcise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceNonExNo  $opt_fn  ORDER BY finyear DESC) AS tmp GROUP BY pono) AS fn INNER JOIN purchaseorder AS po ON fn.pono=po.bpoId
		JOIN purchaseorder_detail AS pod ON pod.bpoId=po.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  AND bpoType='N' $opt GROUP BY po.BuyerId";
		
		
		$Query2="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName ,fn.finyear,po.BuyerId,b.locationid,pod.po_principalId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po INNER JOIN (SELECT COUNT(*) AS countInv,finyear,pono FROM (SELECT invm.finyear,inv.pono FROM outgoinginvoice_excise AS inv,outgoinginvoice_excise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceExNo $opt_fn  UNION ALL  SELECT invm.finyear,inv.pono FROM outgoinginvoice_nonexcise AS inv,outgoinginvoice_nonexcise_mapping AS invm WHERE inv.oinvoice_No=invm.outgoingInvoiceNonExNo $opt_fn  ORDER BY finyear DESC) AS tmp GROUP BY pono) AS fn ON po.bpoId=fn.pono,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   AND posd.pos_item_stage IN ('POIG','OIG') AND bpoType='R' $opt1  GROUP BY  po.BuyerId ";
	    if($PoType!=null ||$PoType!='')
 	   {
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	   }
	   else
	   {
	     $Query="SELECT count(*) as tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   }
	   
	     $Query=$Query." order by s.BuyerName  ";
	   
	     $Result = DBConnection::SelectQuery($Query);	   
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $tot=$Row['tot'];
         return $tot;
	}
    public static function GetBuyerWiseRevenue($executiveId,$Fromdate,$Todate,$buyerid,$PoType,$start,$rp)
    {
    	$opt='';
   	    $opt1='';
		session_start();
		$TYPE = $_SESSION["USER_TYPE"];
   	    if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND po.bpoDate>='$Fromdate' AND po.bpoDate<='$Todate' ";
			 $opt1=$opt1." AND posd.sch_scheduledate>='$Fromdate' AND posd.sch_scheduledate<='$Todate' ";	
		}
		if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A")){ 
				//$opt=$opt."AND po.executiveId='$executiveId'";
				//$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND po.buyerId='$buyerid'";
		    $opt1=$opt1."AND po.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(po_val) AS po_val,b.BuyerName FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId   AND pod.po_item_stage IN ('POIG','OIG')  AND bpoType='N' $opt GROUP BY po.BuyerId ";
		$Query2="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   AND posd.pos_item_stage IN ('POIG','OIG') AND bpoType='R' $opt1  GROUP BY  po.BuyerId ";
		if($PoType!=null ||$PoType!='')
 	   {
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	   }
	   else
	   {
	     $Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   }
	   
	     $Query=$Query." order by s.BuyerName  LIMIT $start,$rp";
	   
	     $result = DBConnection::SelectQuery($Query);
	     $objArray = array();
          $i = 0;
     
         while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         	if($Row['bpoType']=='N')
         	{
				$Row['bpoType']='Normal';
			}
			else if($Row['bpoType']=='R')
			{
				$Row['bpoType']='Recurring';
			}
      	   $objArray[$i]=$Row;
            $i++;
         }	
	     return $objArray;
	}
	public static function countBuyerWiseRevenue($executiveId,$Fromdate,$Todate,$buyerid,$PoType=null)
    {
		$opt='';
   	    $opt1='';
		
		$TYPE = $_SESSION["USER_TYPE"];
   	    if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND po.bpoDate>='$Fromdate' AND po.bpoDate<='$Todate' ";
			 $opt1=$opt1." AND posd.sch_scheduledate>='$Fromdate' AND posd.sch_scheduledate<='$Todate' ";	
		}
		if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
		 if(($TYPE =="M") || ($TYPE =="A")){ 
				//$opt=$opt."AND po.executiveId='$executiveId'";
				//$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND po.buyerId='$buyerid'";
		    $opt1=$opt1."AND po.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(po_val) AS po_val,b.BuyerName FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId   AND pod.po_item_stage IN ('POIG','OIG')  AND bpoType='N' $opt GROUP BY po.BuyerId ";
		$Query2="SELECT COUNT(po.bpoId) AS no_of_po,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   AND posd.pos_item_stage IN ('POIG','OIG') AND bpoType='R' $opt1  GROUP BY  po.BuyerId ";
		if($PoType != null || $PoType != '')
 	   {
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	   }
	   else
	   {
	     $Query="SELECT count(*) as tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   }
	   
	     $Query=$Query." order by s.BuyerName  ";
	   
	     $Result = DBConnection::SelectQuery($Query);	   
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $tot=$Row['tot'];
         return $tot;
	}
	
    public static function buyerPaymentPending($executiveId,$Fromdate,$Todate,$buyerid,$invoiceType,$start,$rp)
   {
   	    $opt='';
   	    $opt1='';
		session_start();
		$TYPE = $_SESSION["USER_TYPE"];
   	    if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";
			 $opt1=$opt1." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";	
		}
   	    if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A") || ($TYPE =="S")){ 
				//$opt=$opt."AND po.executiveId='$executiveId'";
				//$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND noe.buyerId='$buyerid'";
		    $opt1=$opt1."AND noe.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT bm.BuyerName,po.executiveId,noe.oinv_date AS invoiceDate,bill_value as invoiceAmount,balanceAmount,noe.BuyerId  FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId $opt ";
		$Query2="SELECT bm.BuyerName,po.executiveId,noe.oinv_date AS invoiceDate,bill_value aS invoiceAmount,balanceAmount,noe.BuyerId  FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt1 ";
		
		if($invoiceType!=null ||$invoiceType!='')
 	    {
		    if($invoiceType=='E')
		   {
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		   }
		   else if($invoiceType=='N')
		   {
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		   }
	    }
	   else
	   {
	  $Query="SELECT BuyerName,executiveId,invoiceDate,sum(invoiceAmount) as invoiceAmount ,sum(balanceAmount) as balanceAmount,BuyerId FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   }
	  $Query=$Query." GROUP BY s.buyerId ORDER BY s.BuyerName  LIMIT $start,$rp";
	 
	  $result = DBConnection::SelectQuery($Query);
	  $objArray = array();
      $i = 0;
     
      while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      	$objArray[$i]=$Row;
         $i++;
      }	
	  return $objArray;
   } 
   public static function countBuyerInvoice($executiveId,$Fromdate,$Todate,$buyerid,$invoiceType)
   {
   	    $opt='';
   	    $opt1='';
		$TYPE = $_SESSION["USER_TYPE"];
   	   if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";
			 $opt1=$opt1." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";	
		}
   	    if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A")){ 
				//$opt=$opt."AND po.executiveId='$executiveId'";
				//$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		/* if($invoiceno!=NULL ||$invoiceno!="")
		{
			$opt=$opt." AND oinvoice_No='$invoiceno'";
			$opt1=$opt1." AND oinvoice_No='$invoiceno'";	
		} */
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND noe.buyerId='$buyerid'";
		    $opt1=$opt1."AND noe.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT bm.BuyerName,po.executiveId,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId  FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId $opt ";
		$Query2="SELECT bm.BuyerName,po.executiveId,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId  FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId,purchaseorder AS po WHERE noe.pono=po.bpoId  $opt1 ";
		
		if($invoiceType!=null ||$invoiceType!='')
 	    {
		    if($invoiceType=='E')
		   {
			$Query="SELECT BuyerName,executiveId,invoiceDate,sum(invoiceAmount) as invoiceAmount ,sum(balanceAmount) as balanceAmount,BuyerId tot FROM  (".$Query1."  ) as s ";	
		   }
		   else if($invoiceType=='N')
		   {
			$Query="SELECT BuyerName,executiveId,invoiceDate,sum(invoiceAmount) as invoiceAmount ,sum(balanceAmount) as balanceAmount,BuyerId FROM ( ".$Query2." ) as s ";	
		   }
	    }
	else
	{
	  $Query="SELECT BuyerName,executiveId,invoiceDate,sum(invoiceAmount) as invoiceAmount ,sum(balanceAmount) as balanceAmount,BuyerId FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
	  $Query=$Query." GROUP BY s.buyerId ORDER BY s.BuyerName";
	  $sql_sel="select count(*)as tot from ($Query) as tmp";
	 
 	  $Result = DBConnection::SelectQuery($sql_sel);
      $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
      $tot=$Row['tot'];
      return $tot;
   }
 
   public static function paymentPendingInvoice($executiveId,$Fromdate,$Todate,$buyerid,$invoiceType,$invoiceno,$finyear,$start,$rp,$sortname,$sortorder)
   {
	  
		$opt='';
   	    $opt1='';
		session_start();
		$TYPE = $_SESSION["USER_TYPE"];
   	    if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";
			 $opt1=$opt1." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";	
		}
   	    if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A") || ($TYPE =="S")){ 
				 $opt=$opt."AND po.executiveId='$executiveId'";
				 $opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		if($invoiceno!=NULL ||$invoiceno!="")
		{
			$opt=$opt." AND oinvoice_No='$invoiceno'";
		  $opt1=$opt1." AND oinvoice_No='$invoiceno'";	
		}
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND noe.buyerId='$buyerid'";
		    $opt1=$opt1."AND noe.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId
		INNER JOIN outgoinginvoice_excise_mapping AS oem ON noe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx
		,purchaseorder AS po WHERE noe.pono=po.bpoId AND oem.finyear='$finyear' $opt ";
		$Query2="SELECT bm.BuyerName,po.executiveId,oinvoice_No AS invoiceNo,noe.oinv_date AS invoiceDate,bill_value AS invoiceAmount,balanceAmount,noe.BuyerId,po.credit_period,DATE_ADD(noe.oinv_date,INTERVAL po.credit_period DAY)AS dueDate FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId
		INNER JOIN outgoinginvoice_nonexcise_mapping AS onm ON noe.oinvoice_nexciseID = onm.inner_outgoingInvoiceNonEx
		,purchaseorder AS po WHERE noe.pono=po.bpoId AND onm.finyear='$finyear' $opt1 ";
		
		if($invoiceType!=null ||$invoiceType!='')
 	    {
		    if($invoiceType=='E')
		   {
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		   }
		   else if($invoiceType=='N')
		   {
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		   }
	    }
	else
	{
	  $Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	  $Query=$Query." order by $sortname $sortorder LIMIT $start,$rp";
	
	 
	  $result = DBConnection::SelectQuery($Query);
	  $objArray = array();
      $i = 0;
     
      while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      	$objArray[$i]=$Row;
         $i++;
      }	
	  return $objArray;
   } 
   public static function countInvoice($executiveId,$Fromdate,$Todate,$buyerid,$invoiceType,$invoiceno,$finyear)
   {
   	    $opt='';
   	    $opt1='';
		
		$TYPE = $_SESSION["USER_TYPE"];
   	   if(($Fromdate!="") && ($Todate!=""))
		{
			 $opt=$opt." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";
			 $opt1=$opt1." AND noe.oinv_date>='$Fromdate' AND noe.oinv_date<='$Todate' ";	
		}
   	    if($executiveId!=NULL ||$executiveId!=''||$executiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A") || ($TYPE =="S")){ 
				//$opt=$opt."AND po.executiveId='$executiveId'";
				//$opt1=$opt1."AND po.executiveId='$executiveId'";
			}else{
				$opt=$opt."AND po.executiveId='$executiveId'";
				$opt1=$opt1."AND po.executiveId='$executiveId'";
			}
		}
		
		if($invoiceno!=NULL ||$invoiceno!="")
		{
			$opt=$opt." AND oinvoice_No='$invoiceno'";
		  $opt1=$opt1." AND oinvoice_No='$invoiceno'";	
		}
		
		if($buyerid!=NULL ||$buyerid!="")
		{
			$opt=$opt."AND noe.buyerId='$buyerid'";
		    $opt1=$opt1."AND noe.buyerId='$buyerid'";	
		}
		
		$Query1="SELECT oinvoice_No AS invoiceNo FROM outgoinginvoice_excise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId INNER JOIN outgoinginvoice_excise_mapping AS oem ON noe.oinvoice_exciseID = oem.inner_outgoingInvoiceEx,purchaseorder AS po WHERE noe.pono=po.bpoId AND oem.finyear='$finyear' $opt ";
		$Query2="SELECT oinvoice_No AS invoiceNo FROM outgoinginvoice_nonexcise  AS noe INNER JOIN buyer_master AS bm ON noe.buyerId = bm.BuyerId INNER JOIN outgoinginvoice_nonexcise_mapping AS onm ON noe.oinvoice_nexciseID = onm.inner_outgoingInvoiceNonEx,purchaseorder AS po WHERE noe.pono=po.bpoId AND onm.finyear='$finyear' $opt1 ";
		
		if($invoiceType!=null ||$invoiceType!='')
 	    {
		    if($invoiceType=='E')
		   {
			$Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  ) as s ";	
		   }
		   else if($invoiceType=='N')
		   {
			$Query="SELECT COUNT(*)AS tot FROM ( ".$Query2." ) as s ";	
		   }
	    }
	else
	{
	  $Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
	
 	  $Result = DBConnection::SelectQuery($Query);
      $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
      $tot=$Row['tot'];
      return $tot;
   }
 
 public static function countRec($repType,$FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$ExecutiveId,$start,$rp)
 {
 	$opt1="";
 	$opt2="";
 	$opt3="";
 	$opt4="";
 	 if($FromDate!=null && $ToDate!=null)
     {
         $opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt3=$opt3." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt4=$opt4." and po.bpoDate between '$FromDate' and '$ToDate'";
     }
     
     if($PoVD!=null)
     {
        $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
        $opt3=$opt3." and po.bpoVDate='$PoVD'";
        $opt4=$opt4." and po.bpoVDate='$PoVD'";
     }
     if($BuyerId!=null)
     {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
        $opt3=$opt3." and po.BuyerId=$BuyerId"; 
        $opt4=$opt4." and po.BuyerId=$BuyerId"; 
     }
     
      if($repType=='pending')
	  {
	  	$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  }
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  }
	  
	  
      if($ExecutiveId!=null)
      {
	  	 $opt1=$opt1." and po.executiveId='$ExecutiveId'";
         $opt2=$opt2." and po.executiveId='$ExecutiveId'";
         $opt3=$opt3." and po.executiveId='$ExecutiveId'";
         $opt4=$opt4." and po.executiveId='$ExecutiveId'";
	  }
	   if($repType=='pending')
	  {
	  	$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  }
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  }
	  else  if($repType=="complete")
      {
        $opt1.=" AND pod.po_item_stage IN ('OIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('OIG') ";
	 	$opt3.=" AND pod.po_item_stage IN ('YDE','POIG') ";
	  	$opt4.=" AND posd.pos_item_stage IN ('YDE','POIG') ";
	  	$opt1.=" AND po.bpoId NOT IN (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt3  AND bpoType='N' GROUP BY po.bpoId) ";
	  	$opt2.=" AND po.bpoId NOT IN (SELECT posd.bpoId AS bpoId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt4  AND bpoType='R' GROUP BY posd.bpoId) ";
	  	
	  }
    
 	$Query1="SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt1  AND bpoType='N' GROUP BY po.bpoId";
 	$Query2="SELECT posd.bpoId AS bpoId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt2  AND bpoType='R' GROUP BY posd.bpoId";
 
    if($PoType!=null ||$PoType!='')
 	{
		if($PoType=='N')
		{
			$Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT COUNT(*)AS tot FROM ( ".$Query2." ) as s ";	
		}
	}
	else
	{
	  $Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
    
 
 	$Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    	 $tot=$Row['tot'];
    	return $tot;
 
 }
 
  public static function countRecData($repType,$FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$ExecutiveId,$pono,$principalid,$Mode,$Status,$marketsegment,$start,$rp)
 {
 	$opt1="";
 	$opt2="";
 	$opt3="";
 	$opt4="";
 	 if($FromDate!=null && $ToDate!=null)
     {
         $opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt3=$opt3." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt4=$opt4." and po.bpoDate between '$FromDate' and '$ToDate'";
     }
     
     if($PoVD!=null)
     {
        $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
        $opt3=$opt3." and po.bpoVDate='$PoVD'";
        $opt4=$opt4." and po.bpoVDate='$PoVD'";
     }
	 if($pono!=null || $pono!='' || $pono!=0 )
    { 
       $opt1=$opt1." and po.bpono = '".$pono."'";
       $opt2=$opt2." and po.bpono = '".$pono."'";
    } 
     if($BuyerId!=null)
     {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
        $opt3=$opt3." and po.BuyerId=$BuyerId"; 
        $opt4=$opt4." and po.BuyerId=$BuyerId"; 
     }
	  if($principalid!=null)
     {
        $opt1=$opt1." and pod.po_principalId=$principalid"; 
        $opt2=$opt2." and pod.po_principalId=$principalid"; 
        $opt3=$opt3." and pod.po_principalId=$principalid"; 
        $opt4=$opt4." and pod.po_principalId=$principalid"; 
     }
	  if($Mode!=null)
     {
        if($Mode=="NI")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'";
        }
        else if($Mode=="FA")
        {
          $opt1=$opt1." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
          $opt2=$opt2." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
        }
        else if($Mode=="BG")
        {
			$opt1=$opt1." and pod.po_item_stage !='OIG'";
           $opt2=$opt2." and posd.pos_item_stage !='OIG'";       
        }
		else if($Mode=="R")
        {
          $opt1=$opt1." and po.approval_status='R'";
          $opt2=$opt2." and po.approval_status='R'";       
        }else if($Mode=="PSPO")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'"; 
        }
     }
	if($Mode!="FA"){
		if($Status!=null || $Status !='')
			{
				$opt1=$opt1." and po.po_status='".$Status."'"; 
				$opt2=$opt2." and po.po_status='".$Status."'"; 
			}
	}	
	

	if($marketsegment!=null || $marketsegment !=''){
			$opt1=$opt1." and po.msid='".$marketsegment."'"; 
			$opt2=$opt2." and po.msid='".$marketsegment."'"; 
	}
     
     /*  if($repType=='pending')
	  {
	  	$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  }
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  } */
	  
	  
      if($ExecutiveId!=null)
      {
	  	 $opt1=$opt1." and po.executiveId='$ExecutiveId'";
         $opt2=$opt2." and po.executiveId='$ExecutiveId'";
         $opt3=$opt3." and po.executiveId='$ExecutiveId'";
         $opt4=$opt4." and po.executiveId='$ExecutiveId'";
	  }
	if($repType=='pending')
	  {
	  	//$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	//$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  } 
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  }
	  else  if($repType=="complete")
      {
        $opt1.=" AND pod.po_item_stage IN ('OIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('OIG') ";
	 	$opt3.=" AND pod.po_item_stage IN ('YDE','POIG') ";
	  	$opt4.=" AND posd.pos_item_stage IN ('YDE','POIG') ";
	  	$opt1.=" AND po.bpoId NOT IN (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt3  AND bpoType='N' GROUP BY po.bpoId) ";
	  	$opt2.=" AND po.bpoId NOT IN (SELECT posd.bpoId AS bpoId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt4  AND bpoType='R' GROUP BY posd.bpoId) ";
	  	
	  }
 	$Query1="SELECT po.bpoId AS bpoId,pod.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,po_val,b.BuyerName,b.BuyerId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt1  AND bpoType='N' GROUP BY po.bpoId";
 	$Query2="SELECT posd.bpoId AS bpoId,posd.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName,b.BuyerId  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt2  AND bpoType='R' GROUP BY posd.bpoId";
 
    if($PoType!=null ||$PoType!='')
 	{
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	}
	else
	{
	  $Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
    
    
 	$Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    	 $tot=$Row['tot'];
    	return $tot;
 
 }
 
public static function countRecDataPO($repType,$FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$ExecutiveId,$pono,$principalid,$Mode,$Status,$marketsegment,$finyear,$start,$rp)
 {
	
	$TYPE = $_SESSION["USER_TYPE"];
 	$opt1="";
 	$opt2="";
 	$opt3="";
 	$opt4="";
 	
 	$finlast = count($finyear)-1;
 	$convert = explode("-", $finyear[0]); //create array separate by new line	
 	$enDate = $convert[1].'-04-01';
 	$convert = explode("-", $finyear[$finlast]); //create array separate by new line	
	$stDate = $convert[0].'-03-31';
 	
 	 if($FromDate!=null && $ToDate!=null)
     {
         $opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt3=$opt3." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt4=$opt4." and po.bpoDate between '$FromDate' and '$ToDate'";
     }else{
		 $opt1=$opt1." and po.bpoDate between '$stDate' and '$enDate'";
         $opt2=$opt2." and po.bpoDate between '$stDate' and '$enDate'";
         $opt3=$opt3." and po.bpoDate between '$stDate' and '$enDate'";
         $opt4=$opt4." and po.bpoDate between '$stDate' and '$enDate'"; 
	}
     
     if($PoVD!=null)
     {
        $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
        $opt3=$opt3." and po.bpoVDate='$PoVD'";
        $opt4=$opt4." and po.bpoVDate='$PoVD'";
     }
	 if($pono!=null || $pono!='' || $pono!=0 )
    { 
       $opt1=$opt1." and po.bpono = '".$pono."'";
       $opt2=$opt2." and po.bpono = '".$pono."'";
    } 
     if($BuyerId!=null)
     {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
        $opt3=$opt3." and po.BuyerId=$BuyerId"; 
        $opt4=$opt4." and po.BuyerId=$BuyerId"; 
     }
	  if($principalid!=null)
     {
        $opt1=$opt1." and pod.po_principalId=$principalid"; 
        $opt2=$opt2." and pod.po_principalId=$principalid"; 
        $opt3=$opt3." and pod.po_principalId=$principalid"; 
        $opt4=$opt4." and pod.po_principalId=$principalid"; 
     }
	  if($Mode!=null)
     {
        if($Mode=="NI")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'";
        }
        else if($Mode=="FA")
        {
          $opt1=$opt1." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
          $opt2=$opt2." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
        }
        else if($Mode=="BG")
        {
			$opt1=$opt1." and pod.po_item_stage !='OIG' and po.po_status='O'";
           $opt2=$opt2." and posd.pos_item_stage !='OIG' and po.po_status='O'";       
        }
		else if($Mode=="R")
        {
          $opt1=$opt1." and po.approval_status='R'";
          $opt2=$opt2." and po.approval_status='R'";       
        }else if($Mode=="PSPO")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'"; 
        }
     }
	if($Mode!="FA"){
		if($Status!=null || $Status !='')
			{
				$opt1=$opt1." and po.po_status='".$Status."'"; 
				$opt2=$opt2." and po.po_status='".$Status."'"; 
			}
	}	
	

	if($marketsegment!=null || $marketsegment !=''){
			$opt1=$opt1." and po.msid='".$marketsegment."'"; 
			$opt2=$opt2." and po.msid='".$marketsegment."'"; 
	}
     
     /*  if($repType=='pending')
	  {
	  	$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  }
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  } */
	  
  if($ExecutiveId!=NULL ||$ExecutiveId!=''||$ExecutiveId!=0)
   	    {
			if(($TYPE =="M") || ($TYPE =="A") || ($TYPE =="S") || ($ExecutiveId =="All")){ 
				
			}else{
				$opt1=$opt1." and po.executiveId='$ExecutiveId'";
				$opt2=$opt2." and po.executiveId='$ExecutiveId'";
				$opt3=$opt3." and po.executiveId='$ExecutiveId'";
				$opt4=$opt4." and po.executiveId='$ExecutiveId'";
			}
		} 
     
	if($repType=='pending')
	  {
	  	//$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	//$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  } 
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  }
	  else  if($repType=="complete")
      {
        $opt1.=" AND pod.po_item_stage IN ('OIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('OIG') ";
	 	$opt3.=" AND pod.po_item_stage IN ('YDE','POIG') ";
	  	$opt4.=" AND posd.pos_item_stage IN ('YDE','POIG') ";
	  	$opt1.=" AND po.bpoId NOT IN (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt3  AND bpoType='N' GROUP BY po.bpoId) ";
	  	$opt2.=" AND po.bpoId NOT IN (SELECT posd.bpoId AS bpoId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt4  AND bpoType='R' GROUP BY posd.bpoId) ";
	  	
	  }
 	$Query1="SELECT po.bpoId AS bpoId,pod.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,po_val,b.BuyerName,b.BuyerId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt1  AND bpoType='N' GROUP BY po.bpoId";
 	$Query2="SELECT posd.bpoId AS bpoId,posd.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName,b.BuyerId  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt2  AND bpoType='R' GROUP BY posd.bpoId";
 
    if($PoType!=null ||$PoType!='')
 	{
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	}
	else
	{
	  $Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
    
    
 	$Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    	 $tot=$Row['tot'];
    	return $tot;
 
 }
 
 
 
 public static function SelectRequiredData($repType,$FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$ExecutiveId,$pono,$principalid,$Mode,$Status,$marketsegment,$finyear,$start,$rp,$sortname,$sortorder)
 {
	session_start();
    $opt1="";
 	$opt2="";
 	$opt3="";
 	$opt4="";
	$finlast = count($finyear)-1;
 	$convert = explode("-", $finyear[0]); //create array separate by new line	
 	$enDate = $convert[1].'-04-01';
 	$convert = explode("-", $finyear[$finlast]); //create array separate by new line	
	$stDate = $convert[0].'-03-31';
 	
 	
 	 if($FromDate!=null && $ToDate!=null)
     {
         $opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt3=$opt3." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt4=$opt4." and po.bpoDate between '$FromDate' and '$ToDate'";
     }else{
		 $opt1=$opt1." and po.bpoDate between '$stDate' and '$enDate'";
         $opt2=$opt2." and po.bpoDate between '$stDate' and '$enDate'";
         $opt3=$opt3." and po.bpoDate between '$stDate' and '$enDate'";
         $opt4=$opt4." and po.bpoDate between '$stDate' and '$enDate'"; 
	}
	
     
     if($PoVD!=null)
     {
        $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
        $opt3=$opt3." and po.bpoVDate='$PoVD'";
        $opt4=$opt4." and po.bpoVDate='$PoVD'";
     }
	 if($pono!=null || $pono!='' || $pono!=0 )
    { 
       $opt1=$opt1." and po.bpono = '".$pono."'";
       $opt2=$opt2." and po.bpono = '".$pono."'";
    } 
     if($BuyerId!=null)
     {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
        $opt3=$opt3." and po.BuyerId=$BuyerId"; 
        $opt4=$opt4." and po.BuyerId=$BuyerId"; 
     }
    
	  if($principalid!=null)
     {
        $opt1=$opt1." and pod.po_principalId=$principalid"; 
        $opt2=$opt2." and pod.po_principalId=$principalid"; 
        $opt3=$opt3." and pod.po_principalId=$principalid"; 
        $opt4=$opt4." and pod.po_principalId=$principalid"; 
     }
	  if($Mode!=null)
     {
        if($Mode=="NI")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'";
        }
        else if($Mode=="FA")
        {
          $opt1=$opt1." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
          $opt2=$opt2." and po.management_approval='R' and po.approval_status='X' and po.po_status='O'";
        }
        else if($Mode=="BG")
        {
			$opt1=$opt1." and pod.po_item_stage !='OIG' and po.po_status='O'";
           $opt2=$opt2." and posd.pos_item_stage !='OIG' and po.po_status='O'";       
        }
		else if($Mode=="R")
        {
          $opt1=$opt1." and po.approval_status='R'";
          $opt2=$opt2." and po.approval_status='R'";       
        }else if($Mode=="PSPO")
        {
          $opt1=$opt1." and pod.po_item_stage='YDE'";
          $opt2=$opt2." and posd.pos_item_stage='YDE'"; 
        }
     }
	if($Mode!="FA"){
		if($Status!=null || $Status !='')
			{
				$opt1=$opt1." and po.po_status='".$Status."'"; 
				$opt2=$opt2." and po.po_status='".$Status."'"; 
			}
	}	
	

	if($marketsegment!=null || $marketsegment !=''){
			$opt1=$opt1." and po.msid='".$marketsegment."'"; 
			$opt2=$opt2." and po.msid='".$marketsegment."'"; 
	}
     
     /*  if($repType=='pending')
	  {
	  	$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  }
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  } */  
	 
      if($ExecutiveId!=null && ($_SESSION['USER_TYPE'] != "A" && $_SESSION['USER_TYPE'] !="M" && $_SESSION['USER_TYPE'] !="S" && $ExecutiveId !="All"))
      {		
	 $opt1=$opt1." and po.executiveId='$ExecutiveId'";
         $opt2=$opt2." and po.executiveId='$ExecutiveId'";
         $opt3=$opt3." and po.executiveId='$ExecutiveId'";
         $opt4=$opt4." and po.executiveId='$ExecutiveId'";
	  }
	if($repType=='pending')
	  {
	  	//$opt1.=" AND pod.po_item_stage IN ('YDE') ";
	  	//$opt2.=" AND posd.pos_item_stage IN ('YDE') ";
	  	
	  } 
	  else if($repType=='partial')
	  {
	    $opt1.=" AND pod.po_item_stage IN ('POIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('POIG') ";
	  
	  }
	  else  if($repType=="complete")
      {
        $opt1.=" AND pod.po_item_stage IN ('OIG') ";
	  	$opt2.=" AND posd.pos_item_stage IN ('OIG') ";
	 	$opt3.=" AND pod.po_item_stage IN ('YDE','POIG') ";
	  	$opt4.=" AND posd.pos_item_stage IN ('YDE','POIG') ";
	  	$opt1.=" AND po.bpoId NOT IN (SELECT po.bpoId AS bpoId FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt3  AND bpoType='N' GROUP BY po.bpoId) ";
	  	$opt2.=" AND po.bpoId NOT IN (SELECT posd.bpoId AS bpoId FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt4  AND bpoType='R' GROUP BY posd.bpoId) ";
	  	
	  }
 	$Query1="SELECT po.bpoId AS bpoId,pod.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,po_val,b.BuyerName,b.BuyerId,po.po_status,po.po_state FROM purchaseorder AS po INNER JOIN purchaseorder_detail AS pod ON po.bpoId=pod.bpoId,buyer_master AS b WHERE po.BuyerId=b.BuyerId  $opt1  AND bpoType='N' GROUP BY po.bpoId";
 	$Query2="SELECT posd.bpoId AS bpoId,posd.bpod_Id AS bpod_Id,po.bpono,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,executiveId,bpoType,SUM(posd.sch_delbydateqty*pod.po_price) AS po_val,b.BuyerName,b.BuyerId,po.po_status,po.po_state  FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pod ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id AND posd.sch_codePartNo=pod.po_codePartNo,purchaseorder AS po,buyer_master AS b  WHERE po.BuyerId=b.BuyerId AND po.bpoId=posd.bpoId   $opt2  AND bpoType='R' GROUP BY posd.bpoId";
 
    if($PoType!=null ||$PoType!='')
 	{
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
	}
	else
	{
	  $Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	}
	
    
    
    $Query=$Query." order by $sortname $sortorder LIMIT $start,$rp";
    
    //echo $Query; exit;
  
     $result = DBConnection::SelectQuery($Query);
     $objArray = array();
     $i = 0;
     
     while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
         $bpoId = $Row['bpoId'];
         $bpono = $Row['bpono'];
         $bpoDate = $Row['bpoDate'];
         $bpoVDate= $Row['bpoVDate'];
         $BuyerId = $Row['BuyerId'];
         $executiveId= $Row['executiveId'];
         $po_val= $Row['po_val'];
         $po_status = $Row['po_status'];
         $BuyerName=$Row['BuyerName'];
         if($Row['bpoType']=='N')
         {
		 	$poType ='Normal';  
		 }else if($Row['bpoType']=='R')
		 {
		 	$poType ='Recurring';  
		 }
          $poState ='';
		  if($Row['po_state'] == "H"){
			$poState ="Hold";
		  }else if($Row['po_state'] == "U"){
			$poState ="Unhold";
		  }      
         $newObj = new PO_Reports_Model($bpoId,$bpono,$bpoDate,$bpoVDate,$BuyerId,$BuyerName,$executiveId,$po_val,$poType,$po_status,$poState);
         $objArray[$i] = $newObj;
         $i++;
     }
     return $objArray;
   
 }

}
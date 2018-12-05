<?php
Logger::configure($root_path."config.xml");
$logger = Logger::getLogger('Query result');
class Search_Model
{
         public $bpoId;
         public $bpono;
         public $bpoDate;
         public $bpoVDate;
         public $BuyerId;
         public $BuyerName;
         public $po_status;
         public $po_val;
		 public $bpoType;
		 public $poState;
  
        public function __construct($bpoId,$bpono,$bpoDate,$bpoVDate,$BuyerId,$BuyerName,$po_status,$po_val,$bpoType=null,$poState=null)
		{
			$this->bpoId=$bpoId;
			$this->bpono=$bpono;
			$this->bpoDate=$bpoDate;
			$this->bpoVDate=$bpoVDate;
			$this->BuyerId=$BuyerId;
			$this->BuyerName=$BuyerName;
			$this->po_status=$po_status;
			$this->po_val=$po_val;
			$this->bpoType=$bpoType;
			$this->poState=$poState;
		}
 
 public static function countRec($FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$CodePart,$Status,$Mode,$pono,$principal,$marketsegment,$start,$rp)
 {
 	$opt1="";
 	$opt2="";
 	 if($FromDate!=null && $ToDate!=null)
     {
         $opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
         $opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
     }
     if($pono!=null || $pono!='' || $pono!=0 )
    { 
       $opt1=$opt1." and po.bpono = '".$pono."'";
       $opt2=$opt2." and po.bpono = '".$pono."'";
    } 
	
	if($principal!=null || $principal!='' || $principal!=0 )
    { 
      $opt1=$opt1." and pod.po_principalId = '$principal'";
      $opt2=$opt2." and pod.po_principalId = '$principal'";
    }   
     if($PoVD!=null)
     {
       $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
     }
     if($BuyerId!=null)
     {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
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
        }else if($Mode=="R"){
          $opt1=$opt1." and po.management_approval='R'";
          $opt2=$opt2." and po.management_approval='R'";       
        }else if($Mode=="PSPO"){
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
	
	if($marketsegment!=null && $marketsegment !='')
		{
			$opt1=$opt1." and po.msid='".$marketsegment."'"; 
			$opt2=$opt2." and po.msid='".$marketsegment."'"; 
	}
	
 	$Query1="SELECT po.bpoId FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId , buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='N' $opt1 GROUP BY po.bpoId ";
 	$Query2="SELECT po.bpoId FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId INNER JOIN purchaseorder_schedule_detail AS posd ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id,buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='R' $opt2 GROUP BY po.bpoId";
	$Query3="SELECT po.bpoId FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId , buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='B' $opt1 GROUP BY po.bpoId ";
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
		else if($PoType=='B')
		{
			$Query="SELECT COUNT(*)AS tot FROM ( ".$Query3." ) as s ";	
		}
	}
	else
	{
	  //$Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ";	
	   $Query="SELECT COUNT(*)AS tot FROM  (".$Query1."  UNION ALL ".$Query2." UNION ALL ".$Query3." ) as s ";
	}   
 	$Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
    	 $tot=$Row['tot'];
    	return $tot; 
 }
  
 public static function SelectRequiredData($FromDate,$ToDate,$BuyerId,$PoType,$PoVD,$CodePart,$Status,$Mode,$pono,$principal,$marketsegment,$start,$rp)
 {	
	$opt1="";
 	$opt2="";
 	if($FromDate!=null && $ToDate!=null)
    { 
		$opt1=$opt1." and po.bpoDate between '$FromDate' and '$ToDate'";
		$opt2=$opt2." and po.bpoDate between '$FromDate' and '$ToDate'";
    }

    if($pono!=null || $pono!='' || $pono!=0 )
    { 
       $opt1=$opt1." and po.bpono = '".$pono."'";
       $opt2=$opt2." and po.bpono = '".$pono."'";
    } 
	
	if($principal!=null || $principal!='' || $principal!=0 )
    { 
      $opt1=$opt1." and pod.po_principalId = '$principal'";
      $opt2=$opt2." and pod.po_principalId = '$principal'";
    }   	
    if($PoVD!=null)
    {
       $opt1=$opt1." and po.bpoVDate='$PoVD'";
        $opt2=$opt2." and po.bpoVDate='$PoVD'";
    }
    if($BuyerId!=null)
    {
        $opt1=$opt1." and po.BuyerId=$BuyerId"; 
        $opt2=$opt2." and po.BuyerId=$BuyerId"; 
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
	

	if($marketsegment!=null || $marketsegment !='')
		{
			$opt1=$opt1." and po.msid='".$marketsegment."'"; 
			$opt2=$opt2." and po.msid='".$marketsegment."'"; 
	}
  
 	$Query1="SELECT  po.bpoId,po.bpono,po.BuyerId,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,po.po_val,po.bpoType,bm.BuyerName,po_status,po_state FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId , buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='N' $opt1 GROUP BY po.bpoId ";
 	$Query2="SELECT  po.bpoId,po.bpono,po.BuyerId,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,po.po_val,po.bpoType,bm.BuyerName,po_status,po_state FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId INNER JOIN purchaseorder_schedule_detail AS posd ON pod.bpoId=posd.bpoId AND pod.bpod_Id=posd.bpod_Id,buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='R' $opt2 GROUP BY po.bpoId";
	$Query3="SELECT  po.bpoId,po.bpono,po.BuyerId,DATE_FORMAT(po.bpodate,'%d/%m/%Y')bpoDate,DATE_FORMAT(po.bpovdate,'%d/%m/%Y')bpoVDate,po.po_val,po.bpoType,bm.BuyerName,po_status,po_state FROM purchaseorder AS po INNER JOIN  purchaseorder_detail AS pod ON po.bpoId=pod.bpoId , buyer_master AS bm WHERE po.BuyerId=bm.BuyerId AND po.bpoType='B' $opt1 GROUP BY po.bpoId ";
 
    if($PoType!=null || $PoType!='')
 	{
	
		if($PoType=='N')
		{
			$Query="SELECT * FROM  (".$Query1."  ) as s ";	
		}
		else if($PoType=='R')
		{
			$Query="SELECT * FROM ( ".$Query2." ) as s ";	
		}
		else if($PoType=='B')
		{
			$Query="SELECT * FROM ( ".$Query3." ) as s ";	
		}
	}
	else
	{
	  $Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." UNION ALL ".$Query3." ) as s ";	
	}
     $Query = $Query." order by s.bpoId LIMIT $start,$rp";  
     
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
         $po_status= $Row['po_status'];
          if($Row['po_status'] == "O"){
			$po_status ="Open";
		  }else{
			$po_status ="Close";
		  }
         
         $po_val= $Row['po_val'];
         $BuyerName=$Row['BuyerName'];
		 $bpoType ='';
		  if($Row['bpoType'] == "N"){
			$bpoType ="Normal";
		  }else if($Row['bpoType'] == "B"){
			$bpoType ="Bundle";
		  }else{
			$bpoType ="Recurring";
		  }
		 $poState ='';
		  if($Row['po_state'] == "H"){
			$poState ="Hold";
		  }else if($Row['po_state'] == "U"){
			$poState ="Unhold";
		  }

		         
         $newObj = new Search_Model($bpoId,$bpono,$bpoDate,$bpoVDate,$BuyerId,$BuyerName,$po_status,$po_val,$bpoType,$poState);
         $objArray[$i] = $newObj;
         $i++;
     }
	// print_r($objArray); exit;
		return $objArray;
   
 }
	
 public static function getItemStockQty($bpoId){
		
		$colr = 'R';
		$Query="SELECT pod.po_codePartNo, pod.po_ed_applicability, SUM(pod.po_qty ) AS Total FROM purchaseorder_detail AS pod WHERE pod.`bpoId` = '$bpoId' AND pod.po_item_stage !='OIG' GROUP BY po_codePartNo";
		//echo '<br/>Query --> '.$Query;
 	  	$Result = DBConnection::SelectQuery($Query);
 	  	if(mysql_num_rows($Result) > 0){
         while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)){			
			$po_codePartNo = $Row['po_codePartNo'];
			$po_ed_applicability = $Row['po_ed_applicability'];
			$Total = $Row['Total'];	
			$Query1="SELECT tot_Qty AS qty FROM inventory WHERE `code_partNo` ='$po_codePartNo'";
			$Result1 = DBConnection::SelectQuery($Query1);
			$Row1 = mysql_fetch_array($Result1, MYSQL_ASSOC);
			$tot=$Row1['qty'];
			if(($tot >= $Total) && ($tot !='0.00')){
				$colr ="G";
			}else if(($tot < $Total) && ($tot !='0.00') && ($tot >='0.01') ){
				$colr ="Y";
				break;
			}
		}
	}else{
		$colr ="B";
	}
        // print_r($colr);   
   return $colr; 
} 

}

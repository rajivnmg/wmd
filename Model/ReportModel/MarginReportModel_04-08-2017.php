<?php
class MarginReportModel
{
    public static function GetMarginReport($todate,$fromdate,$tag,$value)
    {
		$Query = "select (@cnt := @cnt + 1) AS SN,oe.oinvoice_No, oe.oinv_date,pm.Principal_Supplier_Name,bm.BuyerName
		,im.Item_Code_Partno ,oed.issued_qty ,iied.landing_price ,oe.bill_value,
		po.pf_chrg, po.incidental_chrg, po.freight_tag, po.freight_percent, po.freight_amount,
		pod.po_price, pod.po_discount, pod.po_saleTax,vct.SALESTAX_CHRG, vct.SURCHARGE,
		oed.ed_amt, oed.edu_amt, oed.hedu_amount, oed.cvd_amt,oe.freight_percent
		from outgoinginvoice_excise_detail as oed
		inner join outgoinginvoice_excise as oe on oed.oinvoice_exciseID = oe.oinvoice_exciseID
		inner join principal_supplier_master as pm on oe.principalID = pm.Principal_Supplier_Id
		inner join buyer_master as bm on oe.BuyerID = bm.BuyerId
		inner join item_master as im on oed.codePartNo_desc = im.ItemId
		inner join incominginvoice_excise_detail as iied on oed.iinv_no = iied.entryDId
		inner join purchaseorder_detail as pod on oed.buyer_item_code = pod.bpod_Id
		inner join purchaseorder as po on pod.bpoId = po.bpoId
		inner join vat_cst_master as vct on pod.po_saleTax = vct.SALESTAX_ID
		CROSS JOIN (SELECT @cnt := 0) AS dummy where ";

        switch ($tag)
        {
            case "INVOICENO":
                $Query = $Query." oe.oinvoice_No = '$value'";
                break;
            case "PRINCIPAL":
                $Query = $Query." oe.principalID = $value"." AND oe.oinv_date between '$todate' and '$fromdate'";
                break;
            case "BUYER":
                $Query = $Query." oe.BuyerID = $value"." AND oe.oinv_date between '$todate' and '$fromdate'";
                break;
            default :
                $Query = $Query." oe.oinv_date between '$todate' and '$fromdate'";
                break;
        }
		
        //echo $Query; exit;
        $result = DBConnection::SelectQuery($Query);
        if(mysql_num_rows($result) > 0)
		{
            $counter = 0;
            while($row = mysql_fetch_array($result,MYSQL_ASSOC))
            {
               $data[$counter] = array('SN'=>$row['SN'],'oinvoice_No'=>$row['oinvoice_No'],'oinv_date'=>$row['oinv_date'],
                    'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'BuyerName'=>$row['BuyerName'],
                    'Item_Code_Partno'=>$row['Item_Code_Partno'],
                    'issued_qty'=>$row['issued_qty'],'Salling'=>'',
                    'landing_price'=>$row['landing_price']*$row['issued_qty'],'Margin'=>'','bill_value'=>$row['bill_value']);
                $SallingPrice = 0.00;
                $UnitExcisePrice = $row['po_price'] + ($row['ed_amt']/$row['issued_qty'])+ ($row['edu_amt']/$row['issued_qty'])
                        + ($row['hedu_amount']/$row['issued_qty'])+ ($row['cvd_amt']/$row['issued_qty'])
                        - (($row['po_price'] * $row['po_discount'])/100);
                
                $Unitpf = ($row['pf_chrg'] * $UnitExcisePrice)/100;
                $Unitinci = ($row['incidental_chrg'] * $UnitExcisePrice)/100;
                $UnitTaxableAmount = $UnitExcisePrice + $Unitpf + $Unitinci;
                $unitSaleTax = 0.00;
                if($row['SALESTAX_CHRG'] > 0)
                {
                    $unitSaleTax = ($UnitTaxableAmount * $row['SALESTAX_CHRG'])/100;
                }
                $unitSurcharge = 0.00;
                if($row['SURCHARGE'] > 0)
                {
                    $unitSurcharge = ($unitSaleTax * $row['SURCHARGE'])/100;
                }
                $unitferight = 0.00;
                if($row['freight_percent'] > 0)
                {
                    $unitferight = (($UnitExcisePrice + $Unitpf) * $row['freight_percent'])/100;
                }
                //$UnitSallingPrice = $UnitTaxableAmount + $unitSaleTax + $unitSurcharge + $unitferight;
				//$SallingPrice = $UnitSallingPrice * $row['issued_qty'];
				$SallingPrice = ($row['po_price'] * $row['issued_qty'])+$row['ed_amt'];
				
                $data[$counter]['Salling'] = $SallingPrice;
                $data[$counter]['Margin'] = $SallingPrice - ($row['landing_price']*$row['issued_qty']);
                $counter++;
			}
            return $data;
		}
    }	
    
    
   
	// function ceated by Rajiv for margin report correction. 18-8-2015
//public static function GetMarginReportNew($todate,$fromdate,$principalid,$buyerid,$txtinvoicenumber,$finyear,$marketsegment)
//   {
//	include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
//		/* $Query1="SELECT oe.oinvoice_No,oe.oinvoice_exciseID
//		FROM outgoinginvoice_excise_detail AS oed
//		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
//		WHERE oe.oinv_date
//		BETWEEN  '2015-08-06'
//		AND  '2015-08-10'
//		AND oe.oinvoice_No =  'E-15001923'
//		GROUP BY oe.oinvoice_No"; */
//		
//		 $Query = $Query." ";		 
//		 
//		$Query="SELECT oe.oinvoice_No,oe.oinvoice_exciseID
//		FROM outgoinginvoice_excise_detail AS oed
//		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
//		inner join outgoinginvoice_excise_mapping as oim ON oe.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
//		WHERE  oe.oinv_date between '$todate' and '$fromdate' "; 
//		 if(!empty($principalid)){
//			  $Query = $Query." AND oe.principalID = $principalid";
//			 
//		}
//		 if(!empty($buyerid)){
//			  $Query = $Query." AND oe.BuyerID = $buyerid";
//			 
//		}
//		
//		 if(!empty($txtinvoicenumber)){
//			
//			   $Query = $Query." AND oe.oinvoice_No = '$txtinvoicenumber'";
//			 
//		}
//		 if(!empty($marketsegment)){
//			  if($marketsegment == 'NA'){
//				  $Query = $Query." AND oe.msid = '$marketsegment'";
//			 }else{
//				 $Query = $Query." AND oe.msid = '$marketsegment' AND oe.msid !='0' AND oe.msid != 'NULL' AND oe.msid != ''";
//			 }
//			 
//		}
//		
//		 $finyrs ='';
//        if(!empty($finyear)){			 
//				$finyears = explode(',',$finyear);
//				 foreach ($finyears as $year){
//					 if(empty($finyrs)){
//							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
//					}else{
//						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
//					}					 
//				}				
//		} 
//				
//		$Query = $Query." AND ($finyrs) GROUP BY oe.oinvoice_No";
//		//echo $Query; exit;
//		$result1 = DBConnection::SelectQuery($Query); 
//		$array1 =  array();
//		$report = array();
//	     if(mysql_num_rows($result1) > 0){
//		
//			while($row = mysql_fetch_array($result1,MYSQL_ASSOC)){								
//				$Print = Outgoing_Invoice_Excise_Model::LoadOutgoingInvoiceExcise($row['oinvoice_exciseID']);	
//				$report = array_merge($report, $Print);
//			}			
//		}
//		//echo 'REPORT --->'; print_r($report);echo '<br/>'; //exit;
//if(!empty($report)){
//			
//           $counter = 0;
//			$i=0;
//			$total_val=0;
//			$invoice = array();
//			foreach($report as $row) {
//			//echo 'row --->'; print_r($row);echo '<br/>';
//			//print_r($row->oinvoice_No);
//				//$percentDiscount = (self::GetPercentDiscount($row->pono));
//				if($row->ms == 1){
//					$ms = 'AUTO';
//				}else if($row->ms == 2){
//					$ms = 'GEN';
//				}else if($row->ms == 3){
//					$ms = 'MRO';
//				}else if($row->ms == 4){
//					$ms = 'OEM';
//				}else{
//					$ms = 'N/A';
//				}
//				
//				$totalPoVal =0;
//				$inci_charge = 0;
//				$pf_chrg = 0;
//				foreach($row->_itmes as $item) {
//					 $totalPoVal = $totalPoVal + ($item->oinv_price * $item->issued_qty);				
//				}			
//				
//				$inci_charge = ($row->incidental_chrg / $totalPoVal);
//				$pf_chrg = ($row->pf_chrg / $totalPoVal);
//				
//				 foreach($row->_itmes as $k) {	
//						$percentDiscount = (self::GetPercentDiscountNEW($k->bpod_Id,$row->pono));
//						///echo 'k --->'; print_r($k);echo '<br/>';					
//						//print_r($k->oinv_codePartNo);
//						$lp = 0;
//						$TempSallingPrice = 0;	
//						//echo 'k-->iinv_no ---> '.$k->iinv_no;echo '<br/>';			
//						//if($k->iinv_no){
//							 //print_r($k);
//							$lp = ((self::GetlandingPrice($k->entryDId))*$k->issued_qty);
//							//$lp = ((self::GetlandingPrice($k->iinv_no))*$k->issued_qty);
//						//} 
//						if($lp == 0)
//						{
//							$lp = ((self::GetNonExciselandingPrice($row->principalID,$k->_item_id)) * $k->issued_qty);
//							//echo 'row --->'; print_r($row);echo '<br/>';
//							//echo 'k --->'; print_r($k);echo '<br/>';
//							//echo 'éxit';exit;
//						}
//						///echo 'éxit';exit;
//						$link=null;
//						$findme   = 'E';
//							$pos = strrchr($row->oinvoice_No, $findme);
//							if($pos !='' || $pos != null){ 
//							
//							$link =' ../Business_View/invoice_outgoingexcise.php?TYP=SELECT&OutgoingInvoiceExciseNum='.$k->oinvoice_exciseID;
//							}else{
//							$link =' ../Business_View/invoice_outgoingNonExcise.php?TYP=SELECT&OutgoingInvoiceNonExciseNum='.$k->oinvoice_exciseID;
//							} 
//							
//						if (in_array($k->oinvoice_exciseID, $invoice)){
//							 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>",'oinv_date'=>$row->oinv_date,
//							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
//							'Item_Code_Partno'=>$k->oinv_codePartNo,
//							'codePartNo_desc'=>$k->codePartNo_desc,
//							'issued_qty'=>$k->issued_qty,'Salling'=>'',
//							'landing_price'=>number_format($lp, 2, '.', ''),'Margin'=>$lp,'bill_value'=>'---','ms'=>$ms);	
//							$tempAmount = (($k->oinv_price * $k->issued_qty));
//							/* $SallingPrice = ((($k->oinv_price * $k->issued_qty)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt)-(($k->oinv_price * $k->issued_qty)* $percentDiscount)/100)+((($k->oinv_price * $k->issued_qty)*$row->_popf_charge)/100)+((($k->oinv_price * $k->issued_qty)* $row->_poinc_charge)/100);		 */	
//							if($row->inclusive_ed_tag =="I"){
//								$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount));
//							}else{
//								$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt);
//							}
//							
//							//$SallingPrice = ($TempSallingPrice + (($TempSallingPrice*$row->_popf_charge)/100)+(($TempSallingPrice * $row->_poinc_charge)/100));
//							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $row->freight_percent)/100);
//							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
//							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
//						}else{
//							
//							 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>",'oinv_date'=>$row->oinv_date,
//							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
//							'Item_Code_Partno'=>$k->oinv_codePartNo,
//							'codePartNo_desc'=>$k->codePartNo_desc,
//							'issued_qty'=>$k->issued_qty,'Salling'=>'',
//							'landing_price'=>number_format($lp, 2, '.', ''),'Margin'=>$lp,'bill_value'=>number_format($row->bill_value, 2, '.', ''),'ms'=>$ms);	
//							$tempAmount = ($k->oinv_price * $k->issued_qty); 
//						
//							
//							/* $SallingPrice = ((($k->oinv_price * $k->issued_qty)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt)-(($k->oinv_price * $k->issued_qty)* $percentDiscount)/100)+((($k->oinv_price * $k->issued_qty)*$row->_popf_charge)/100)+((($k->oinv_price * $k->issued_qty)* $row->_poinc_charge)/100);		 */	
//							if($row->inclusive_ed_tag =="I"){
//								$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount));
//							}else{
//								$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt);
//							}
//						
//							
//							//$SallingPrice = ($TempSallingPrice + (($TempSallingPrice*$row->_popf_charge)/100)+(($TempSallingPrice * $row->_poinc_charge)/100));
//							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $row->freight_percent)/100);
//						
//							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
//							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
//							
//							$invoice[] = $k->oinvoice_exciseID;
//							$total_val = $total_val + $row->bill_value ;
//						}
//						 
//						$counter++; 
//				} 
//					$data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'','Item_Code_Partno'=>'','codePartNo_desc'=>'','issued_qty'=>'','Salling'=>'','landing_price'=>'','Margin'=>'Grand Total ','bill_value'=>$total_val,'ms'=>'');
//			} 
//			//print_r($data);exit;
//			
//			
//		
//		}                  
//       return $data; 	
//   }
	
	public static function GetMarginReportNew($todate,$fromdate,$principalid,$buyerid,$txtinvoicenumber,$finyear,$marketsegment)
   {
	include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");		 
		 
		$Query="SELECT oe.oinvoice_No,oe.oinvoice_exciseID
		FROM outgoinginvoice_excise_detail AS oed
		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
		inner join outgoinginvoice_excise_mapping as oim ON oe.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
		WHERE  oe.oinv_date between '$todate' and '$fromdate' "; 
		 if(!empty($principalid)){
			  $Query = $Query." AND oe.principalID = $principalid";
			 
		}
		 if(!empty($buyerid)){
			  $Query = $Query." AND oe.BuyerID = $buyerid";
			 
		}
		
		 if(!empty($txtinvoicenumber)){
			
			   $Query = $Query." AND oe.oinvoice_No = '$txtinvoicenumber'";
			 
		}
		 if(!empty($marketsegment)){
			  if($marketsegment == 'NA'){
				  $Query = $Query." AND oe.msid = '$marketsegment'";
			 }else{
				 $Query = $Query." AND oe.msid = '$marketsegment' AND oe.msid !='0' AND oe.msid != 'NULL' AND oe.msid != ''";
			 }
			 
		}
		
		 $finyrs ='';
        if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} 
				
		$Query = $Query." AND ($finyrs) GROUP BY oe.oinvoice_No";
		//echo $Query; exit;
		$result1 = DBConnection::SelectQuery($Query); 
		$array1 =  array();
		$report = array();
	     if(mysql_num_rows($result1) > 0){
		
			while($row = mysql_fetch_array($result1,MYSQL_ASSOC)){								
				$Print = Outgoing_Invoice_Excise_Model::LoadOutgoingInvoiceExcise($row['oinvoice_exciseID']);	
				$report = array_merge($report, $Print);
			}			
		}
		//echo 'REPORT --->'; print_r($report);echo '<br/>'; //exit;
if(!empty($report)){
			
           $counter = 0;
			$i=0;
			$total_val=0;
			$invoice = array();
			foreach($report as $row) {
			//echo 'row --->'; print_r($row);echo '<br/>';
			//print_r($row->oinvoice_No);
				//$percentDiscount = (self::GetPercentDiscount($row->pono));
				if($row->ms == 1){
					$ms = 'AUTO';
				}else if($row->ms == 2){
					$ms = 'GEN';
				}else if($row->ms == 3){
					$ms = 'MRO';
				}else if($row->ms == 4){
					$ms = 'OEM';
				}else{
					$ms = 'N/A';
				}
				
				$totalPoVal =0;
				$inci_charge = 0;
				$pf_chrg = 0;
				/* foreach($row->_itmes as $item) {
					 $totalPoVal = $totalPoVal + ($item->oinv_price * $item->issued_qty);				
				}	 */		
				
				//$inci_charge = ($row->incidental_chrg / $totalPoVal);
				//$pf_chrg = ($row->pf_chrg / $totalPoVal);
				
				 foreach($row->_itmes as $k) {	
						
						//echo 'row --->'; print_r($row);echo '<br/>';
						//echo 'k --->'; print_r($k);echo '<br/>';
						//exit;
						
						$lp = 0;
						$TempSallingPrice = 0;	
						//echo 'k-->iinv_no ---> '.$k->iinv_no;echo '<br/>';			
						//if($k->iinv_no){
							 //print_r($k);
							$lp = ((self::GetlandingPrice($k->entryDId))*$k->issued_qty);
							//$lp = ((self::GetlandingPrice($k->iinv_no))*$k->issued_qty);
						//} 
						if($lp === false)
						{
							$lp = ((self::GetNonExciselandingPrice($row->principalID,$k->_item_id)) * $k->issued_qty);
							//echo 'row --->'; print_r($row);echo '<br/>';
							//echo 'k --->'; print_r($k);echo '<br/>';
							//echo 'éxit';exit;
						}
						///echo 'éxit';exit;
						$link=null;
						//$link ='../Business_View/invoice_outgoingexcise.php?TYP=SELECT&OutgoingInvoiceExciseNum='.$k->oinvoice_exciseID;
						$link ='../../pdf/print_excise_invoice.php?printtype=1&invoiceId='.$k->oinvoice_exciseID;
					
						$taxable_total = ($k->item_taxable_total);
						$pf_chrg = $row->pf_chrg;
						$incidental_chrg = $row->incidental_chrg;
						$ins_charge = $row->ins_charge;
						$othc_charge = $row->othc_charge;
						$freight_percent = $row->freight_percent;
						if($freight_percent != 0)
						{
							$freight_amount = ($taxable_total * ($freight_percent/100));
						}
						else
						{
							$freight_amount = $row->freight_amount;
						}
						
						if($pf_chrg != 0)
						{
							$pf_amount = ($taxable_total * ($pf_chrg/100));
						}
						else
						{
							$pf_amount = 0;
						}
						
						if($incidental_chrg != 0)
						{
							$incidental_amount = ($taxable_total * ($incidental_chrg/100));
						}
						else
						{
							$incidental_amount = 0;
						}
						
						if($ins_charge != 0)
						{
							$ins_amount = ($taxable_total * ($ins_charge/100));
						}
						else
						{
							$ins_amount = 0;
						}
						
						if($othc_charge != 0)
						{
							$othc_amount = ($taxable_total * ($othc_charge/100));
						}
						else
						{
							$othc_amount = 0;
						}
						
						$SallingPrice = ($taxable_total + $freight_amount + $pf_amount + $incidental_amount + $ins_amount + $othc_amount);
						
						if (in_array($k->oinvoice_exciseID, $invoice))
						{
							$data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>", 'oinvoice_No_excel'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date,
							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
							'Item_Code_Partno'=>$k->oinv_codePartNo,
							'codePartNo_desc'=>$k->codePartNo_desc,
							'issued_qty'=>$k->issued_qty,'Salling'=>number_format($SallingPrice, 2, '.', ''),
							'landing_price'=>number_format($lp, 2, '.', ''),'Margin'=>number_format($SallingPrice - ($lp), 2, '.', ''),'bill_value'=>'---','ms'=>$ms);	
							
						}
						else
						{
							$data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>", 'oinvoice_No_excel'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date, 'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name, 'Item_Code_Partno'=>$k->oinv_codePartNo,'codePartNo_desc'=>$k->codePartNo_desc,'issued_qty'=>$k->issued_qty,'Salling'=>number_format($SallingPrice, 2, '.', ''), 'landing_price'=>number_format($lp, 2, '.', ''), 'Margin'=>number_format($SallingPrice - ($lp), 2, '.', ''), 'bill_value'=>number_format($row->bill_value, 2, '.', ''),'ms'=>$ms);	
							
							$invoice[] = $k->oinvoice_exciseID;
							$total_val = $total_val + $row->bill_value ;
						}
						 
						$counter++; 
				} 
					$data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'', 'oinvoice_No_excel'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'','Item_Code_Partno'=>'','codePartNo_desc'=>'','issued_qty'=>'','Salling'=>'','landing_price'=>'','Margin'=>'Grand Total','bill_value'=>$total_val,'ms'=>'');
			} 
			//print_r($data);exit;
			
			
		
		}                  
       return $data; 	
   }
	
		// function ceated by Rajiv for margin report correction. 18-8-2015
 public static function GetMarginReportNewPDF($todate,$fromdate,$principalid,$buyerid,$txtinvoicenumber,$finyear,$marketsegment)
    {
	include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
		/* $Query1="SELECT oe.oinvoice_No,oe.oinvoice_exciseID
		FROM outgoinginvoice_excise_detail AS oed
		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
		WHERE oe.oinv_date
		BETWEEN  '2015-08-06'
		AND  '2015-08-10'
		AND oe.oinvoice_No =  'E-15001923'
		GROUP BY oe.oinvoice_No"; */
		
		 $Query = $Query." ";
		 
		 
		 
		$Query="SELECT oe.oinvoice_No,oe.oinvoice_exciseID
		FROM outgoinginvoice_excise_detail AS oed
		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
		inner join outgoinginvoice_excise_mapping as oim ON oe.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
		WHERE  oe.oinv_date between '$todate' and '$fromdate' "; 
		 if(!empty($principalid)){
			  $Query = $Query." AND oe.principalID = $principalid";
			 
		}
		 if(!empty($buyerid)){
			  $Query = $Query." AND oe.BuyerID = $buyerid";
			 
		}
		
		 if(!empty($txtinvoicenumber)){
			   $Query = $Query." AND oe.oinvoice_No = '$txtinvoicenumber'";
			 
		}
		 if(!empty($marketsegment)){
			   $Query = $Query." AND oe.msid = '$marketsegment'";
			 
		}
		    
    
        $finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} 
             
          
      
		$Query = $Query." AND ($finyrs) GROUP BY oe.oinvoice_No";
		//echo $Query;exit;
		$result1 = DBConnection::SelectQuery($Query); 
		$array1 =  array();
		$report = array();
		
        if(mysql_num_rows($result1) > 0){
		
			 while($row = mysql_fetch_array($result1,MYSQL_ASSOC)){								
				$Print = Outgoing_Invoice_Excise_Model::LoadOutgoingInvoiceExcise($row['oinvoice_exciseID']);	
				$report = array_merge($report, $Print);
			}			
			//print_r($report); exit;
		}

        if(!empty($report)){
			
            $counter = 0;
			$i=0;
			$total_val=0;
			$total_sel =0;
			$total_land =0;
			$total_marg =0;
			$invoice = array();
			foreach($report as $row) {
			//print_r($row->oinvoice_No);
				//$percentDiscount = (self::GetPercentDiscount($row->pono));
				if($row->ms == 1){
					$ms = 'AUTO';
				}else if($row->ms == 2){
					$ms = 'GEN';
				}else if($row->ms == 3){
					$ms = 'MRO';
				}else if($row->ms == 4){
					$ms = 'OEM';
				}else{
					$ms = 'N/A';
				}
				
				$totalPoVal =0;
				$inci_charge = 0;
				$pf_chrg = 0;
				foreach($row->_itmes as $item) {
					 $totalPoVal = $totalPoVal + ($item->oinv_price * $item->issued_qty);				
				}			
				
				$inci_charge = ($row->incidental_chrg / $totalPoVal);
				$pf_chrg = ($row->pf_chrg / $totalPoVal);
				
				 foreach($row->_itmes as $k) {
						$percentDiscount = (self::GetPercentDiscountNEW($k->bpod_Id,$row->pono));
						//print_r($k->oinv_codePartNo);
						$lp = 0;						
						 if($k->iinv_no){
							$lp = ((self::GetlandingPrice($k->entryDId))*$k->issued_qty);
						} 
						$findme   = 'E';
							$pos = strrchr($row->oinvoice_No, $findme);
						
					if (in_array($k->oinvoice_exciseID, $invoice)){
							
						 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date,
						'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
						'Item_Code_Partno'=>$k->oinv_codePartNo,
						'codePartNo_desc'=>$k->codePartNo_desc,
						'issued_qty'=>$k->issued_qty,'Salling'=>'',
						'landing_price'=>$lp,'Margin'=>$lp,'bill_value'=>'---','ms'=>$ms);	
						$tempAmount = (($k->oinv_price * $k->issued_qty));
						/* $SallingPrice = ((($k->oinv_price * $k->issued_qty)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt)-(($k->oinv_price * $k->issued_qty)* $percentDiscount)/100)+((($k->oinv_price * $k->issued_qty)*$row->_popf_charge)/100)+((($k->oinv_price * $k->issued_qty)* $row->_poinc_charge)/100);		 */	
						if($row->inclusive_ed_tag =="I"){
							$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount));
						}else{
							$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount)+(($tempAmount * $row->freight_percent)/100)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt);
						}
						
						//$SallingPrice = ($TempSallingPrice + (($TempSallingPrice*$row->_popf_charge)/100)+(($TempSallingPrice * $row->_poinc_charge)/100));
						$SallingPrice = $TempSallingPrice;
						$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
						$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
					}else{
						$data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date,
						'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
						'Item_Code_Partno'=>$k->oinv_codePartNo,
						'codePartNo_desc'=>$k->codePartNo_desc,
						'issued_qty'=>$k->issued_qty,'Salling'=>'',
						'landing_price'=>$lp,'Margin'=>$lp,'bill_value'=>number_format($row->bill_value, 2, '.', ''),'ms'=>$ms);	
						$tempAmount = (($k->oinv_price * $k->issued_qty));
						/* $SallingPrice = ((($k->oinv_price * $k->issued_qty)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt)-(($k->oinv_price * $k->issued_qty)* $percentDiscount)/100)+((($k->oinv_price * $k->issued_qty)*$row->_popf_charge)/100)+((($k->oinv_price * $k->issued_qty)* $row->_poinc_charge)/100);		 */	
						if($row->inclusive_ed_tag =="I"){
							$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount));
						}else{
							$TempSallingPrice = (($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $tempAmount)+($inci_charge * $tempAmount)+(($tempAmount * $row->freight_percent)/100)+$k->ed_amt+$k->edu_amt+$k->hedu_amount+$k->cvd_amt);
						}
						
						//$SallingPrice = ($TempSallingPrice + (($TempSallingPrice*$row->_popf_charge)/100)+(($TempSallingPrice * $row->_poinc_charge)/100));
						$SallingPrice = $TempSallingPrice;
						$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
						$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
						
						$invoice[] = $k->oinvoice_exciseID;
						$total_val = $total_val + $row->bill_value ;
					}
						$total_sel = $total_sel + number_format($SallingPrice, 2, '.', '');
						$total_land = $total_land + number_format($lp, 2, '.', '');
						$total_marg = $total_marg + number_format($SallingPrice - ($lp), 2, '.', '');
						$counter++; 
				} 
				
				$data[$counter] = array('SN'=>'','oinvoice_No'=>'Grand Total','oinv_date'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'','Item_Code_Partno'=>'','codePartNo_desc'=>'','issued_qty'=>'','Salling'=>$total_sel,'landing_price'=>$total_land,'Margin'=>$total_marg,'bill_value'=>$total_val,'ms'=>'');	
			} 
			
		}                  
        return $data; 	
    }
	
	
	public static function GetlandingPrice($principal_invoice)
    {
		/* $Query="SELECT ied.total_landing_price,ied.landing_price
		FROM incominginvoice_excise_detail AS ied
		WHERE ied.entryDId = ".$principal_invoice.""; */ 
		$Query="SELECT IF(ied.s_qty > 0,(ied.taxable_amt/ied.s_qty),(ied.taxable_amt/ied.p_qty)) AS TAXABLE_AMOUNT
		FROM incominginvoice_excise_detail AS ied JOIN incominginventory iinv ON ied.entryDId = iinv.entryDId
		WHERE iinv.incoming_inventory_id = ".$principal_invoice.""; 
		//echo $Query; exit;
		$result = DBConnection::SelectQuery($Query); 
		$data = array();
        if(mysql_num_rows($result) > 0){
		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				return $row['TAXABLE_AMOUNT'];
			}		
		}
		else 
		{
			$Query="SELECT ied.basic_purchase_price FROM incominginvoice_excise_detail_old AS ied WHERE ied.entryDId = ".$principal_invoice."";
			
			$result = DBConnection::SelectQuery($Query);
			if(mysql_num_rows($result) > 0)
			{
				while($row = mysql_fetch_array($result,MYSQL_ASSOC))
				{
					return $row['basic_purchase_price']; 				
				}
			}
			else
			{
				return false;
			}
		}
		return false;
    }
	
	public static function GetPercentDiscount($bpoid)
    {
		$Query="SELECT po_discount FROM purchaseorder_detail WHERE bpoId = ".$bpoid." LIMIT 1"; 
		$result = DBConnection::SelectQuery($Query); 
		if(mysql_num_rows($result) > 0){
		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				return $row['po_discount']; 
				break;
			}		
		}
		return 0;
    }
    
    public static function GetPercentDiscountNEW($bpo_id,$poid)
    {
		$query = "SELECT bpoType FROM purchaseorder WHERE bpoId = ".$poid;
		$result = DBConnection::SelectQuery($query); 
		$row = mysql_fetch_array($result);
		$bpoType = $row['bpoType'];
		if($bpoType == 'R'){
			$Query="SELECT po_discount FROM purchaseorder_detail as pd, purchaseorder_schedule_detail as psd WHERE pd.po_codePartNo=psd.sch_codePartNo AND pd.bpoId=psd.bpoId AND psd.bposd_Id = ".$bpo_id." LIMIT 1"; 
		}else{
			$Query="SELECT po_discount FROM purchaseorder_detail WHERE bpod_Id = ".$bpo_id." LIMIT 1"; 
		}
		$result = DBConnection::SelectQuery($Query); 
		if(mysql_num_rows($result) > 0){
		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				return $row['po_discount']; 
				break;
			}		
		}
		return 0;
    }
	
	public static function GetMarginReportNonExcise($todate,$fromdate,$principalid,$buyerid,$txtinvoicenumber,$finyear,$marketsegment)
    {
	include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_NonExcise_Model.php");
		 /* $Query1="SELECT oie.oinvoice_No,oie.oinvoice_nexciseID
		FROM outgoinginvoice_nonexcise_detail AS oined
		INNER JOIN outgoinginvoice_nonexcise AS oie ON oined.oinvoice_nexciseID= oie .oinvoice_nexciseID
		WHERE oie.oinv_date
		BETWEEN  '2015-08-06'
		AND  '2015-09-10'
		AND oie.oinvoice_No =  'T-15001001'
		GROUP BY  oie.oinvoice_No";  */
		$Query="SELECT oie.oinvoice_No,oie.oinvoice_nexciseID
		FROM outgoinginvoice_nonexcise_detail AS oined
		INNER JOIN outgoinginvoice_nonexcise AS oie ON oined.oinvoice_nexciseID= oie .oinvoice_nexciseID
		inner join outgoinginvoice_nonexcise_mapping AS oim ON oie.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 
		WHERE  oie.oinv_date between '$todate' and '$fromdate' "; 		
		
		
		
		 if(!empty($principalid)){
			  $Query = $Query." AND oie.principalID = $principalid";
			 
		}
		 if(!empty($buyerid)){
			  $Query = $Query." AND oie.BuyerID = $buyerid";
			 
		}
		
		 if(!empty($txtinvoicenumber)){
			   $Query = $Query." AND oie.oinvoice_No = '$txtinvoicenumber'";
			 
		}
		 if(!empty($marketsegment)){
			   $Query = $Query." AND oie.msid = '$marketsegment'";
			 
		}
			
        $finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} 
        		
		$Query = $Query." AND ($finyrs) GROUP BY  oie.oinvoice_No";
		//echo $Query1; exit;
		$result1 = DBConnection::SelectQuery($Query); 
		$array1 =  array();
		$report = array();
		
        if(mysql_num_rows($result1) > 0){
		
			 while($row = mysql_fetch_array($result1,MYSQL_ASSOC)){		 
				$Print = Outgoing_Invoice_NonExcise_Model::LoadOutgoingInvoiceNonExcise($row['oinvoice_nexciseID']);	
				$report = array_merge($report, $Print);
			}			
		}
		

        if(!empty($report)){
			
            $counter = 0;
			$i=0;
			$total_val=0;		
			$invoice = array();
			foreach($report as $row) {
						
				//$percentDiscount = (self::GetPercentDiscount($row->pono));
				if($row->ms == 1){
					$ms = 'AUTO';
				}else if($row->ms == 2){
					$ms = 'GEN';
				}else if($row->ms == 3){
					$ms = 'MRO';
				}else if($row->ms == 4){
					$ms = 'OEM';
				}else{
					$ms = 'N/A';
				}	
				$totalPoVal =0;
				$inci_charge = 0;
				$pf_chrg = 0;
				$freigt_percent = 0;
				foreach($row->_itmes as $item) {
					 $totalPoVal = $totalPoVal + $item->item_amount;				
				}			
				
				$inci_charge = ($row->incidental_chrg / $totalPoVal);
				$pf_chrg = ($row->pf_chrg / $totalPoVal);
				 foreach($row->_itmes as $k) {	
						$percentDiscount = (self::GetPercentDiscountNEW($k->bpod_Id,$row->pono));
						//print_r($k->oinv_codePartNo); exit;
						$lp = 0;
						$TempSallingPrice = 0;
						$link=null;
						$findme   = 'E';
							$pos = strrchr($row->oinvoice_No, $findme);
							if($pos !='' || $pos != null){ 
							
							$link =' ../Business_View/invoice_outgoingexcise.php?TYP=SELECT&OutgoingInvoiceExciseNum='.$row->oinvoice_nexciseID;
							}else{
							$link =' ../Business_View/invoice_outgoingNonExcise.php?TYP=SELECT&OutgoingInvoiceNonExciseNum='.$row->oinvoice_nexciseID;
							} 
												
						
						$lp = ((self::GetNonExciselandingPrice($row->principalID,$k->_item_id)) * $k->issued_qty);
						
						 if(in_array($row->oinvoice_nexciseID, $invoice)){
							 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>",'oinv_date'=>$row->oinv_date,
							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
							'Item_Code_Partno'=>$k->oinv_codePartNo,
							'codePartNo_desc'=>$k->codePartNo_desc,
							'issued_qty'=>$k->issued_qty,'Salling'=>'',
							'landing_price'=>$lp,'Margin'=>$lp,'bill_value'=>'---','ms'=>$ms);	
							$tempAmount = ($k->oinv_price * $k->issued_qty);
							$TempSallingPrice = ($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $k->item_amount)+($inci_charge * $k->item_amount);
							//$SallingPrice = $TempSallingPrice+$row->po_saleTax;
							//$SallingPrice = $TempSallingPrice;
							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $freigt_percent)/100);
							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
						
						}else{
							 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>"<a href='".$link."' target='_blank'>$row->oinvoice_No</a>",'oinv_date'=>$row->oinv_date,
							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
							'Item_Code_Partno'=>$k->oinv_codePartNo,
							'codePartNo_desc'=>$k->codePartNo_desc,
							'issued_qty'=>$k->issued_qty,'Salling'=>'',
							'landing_price'=>number_format($lp, 2, '.', ''),'Margin'=>$lp,'bill_value'=>number_format($row->bill_value, 2, '.', ''),'ms'=>$ms);	
							$tempAmount = ($k->oinv_price * $k->issued_qty);
							$TempSallingPrice = ($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $k->item_amount)+($inci_charge * $k->item_amount);
							//$SallingPrice = $TempSallingPrice+$row->po_saleTax;
							//$SallingPrice = $TempSallingPrice;
							
							$freigt_percent = 	(($row->freight_amount * 100)/$TempSallingPrice);					
							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $freigt_percent)/100);
							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
							$invoice[] = $row->oinvoice_nexciseID;
							$total_val = $total_val + $row->bill_value ;
						}
						$counter++; 
				}
				
				$data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'','Item_Code_Partno'=>'','codePartNo_desc'=>'','issued_qty'=>'','Salling'=>'','landing_price'=>'','Margin'=>'Grand Total ','bill_value'=>number_format($total_val, 2, '.', ''),'ms'=>''); 
			} 
		//	print_r($data);exit;
		}                  
        return $data; 	
    }
	
public static function GetMarginReportNonExcisePDF($todate,$fromdate,$principalid,$buyerid,$txtinvoicenumber,$finyear,$marketsegment)
    {
	include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_NonExcise_Model.php");
		
		$Query="SELECT oie.oinvoice_No,oie.oinvoice_nexciseID
		FROM outgoinginvoice_nonexcise_detail AS oined
		INNER JOIN outgoinginvoice_nonexcise AS oie ON oined.oinvoice_nexciseID= oie .oinvoice_nexciseID
		inner join outgoinginvoice_nonexcise_mapping AS oim ON oie.oinvoice_nexciseID=oim.inner_outgoingInvoiceNonEx 
		WHERE  oie.oinv_date between '$todate' and '$fromdate' "; 		
		
		
		
		 if(!empty($principalid)){
			  $Query = $Query." AND oie.principalID = $principalid";
			 
		}
		 if(!empty($buyerid)){
			  $Query = $Query." AND oie.BuyerID = $buyerid";
			 
		}
		
		 if(!empty($txtinvoicenumber)){
			   $Query = $Query." AND oie.oinvoice_No = '$txtinvoicenumber'";
			 
		}
		 if(!empty($marketsegment)){
			   $Query = $Query." AND oie.msid = '$marketsegment'";
			 
		}	
        $finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}
			} 
		$Query = $Query." AND ($finyrs) GROUP BY  oie.oinvoice_No";
		//echo $Query1; exit;
		$result1 = DBConnection::SelectQuery($Query); 
		$array1 =  array();
		$report = array();
		
        if(mysql_num_rows($result1) > 0){
		
			 while($row = mysql_fetch_array($result1,MYSQL_ASSOC)){		 
				$Print = Outgoing_Invoice_NonExcise_Model::LoadOutgoingInvoiceNonExcise($row['oinvoice_nexciseID']);	
				$report = array_merge($report, $Print);
			}			
	
		}

        if(!empty($report)){
			
            $counter = 0;
			$i=0;
			$total_val=0;
			$total_sel =0;
			$total_land =0;
			$total_marg =0;
			$invoice = array();
			foreach($report as $row) {
			
				//$percentDiscount = (self::GetPercentDiscount($row->pono));
				if($row->ms == 1){
					$ms = 'AUTO';
				}else if($row->ms == 2){
					$ms = 'GEN';
				}else if($row->ms == 3){
					$ms = 'MRO';
				}else if($row->ms == 4){
					$ms = 'OEM';
				}else{
					$ms = 'N/A';
				}	
				
				$totalPoVal =0;
				$inci_charge = 0;
				$pf_chrg = 0;
				$freigt_percent = 0;
				
				foreach($row->_itmes as $item) {
					 $totalPoVal = $totalPoVal + $item->item_amount;				
				}			
				
				$inci_charge = ($row->incidental_chrg / $totalPoVal);
				$pf_chrg = ($row->pf_chrg / $totalPoVal);
				
				 foreach($row->_itmes as $k) {
						$percentDiscount = (self::GetPercentDiscountNEW($k->bpod_Id,$row->pono));
						//print_r($k->oinv_codePartNo); exit;
						$lp = 0;
						 $TempSallingPrice = 0;					
						$lp = ((self::GetNonExciselandingPrice($row->principalID,$k->_item_id))*$k->issued_qty);
						
						 
						 
						 if (in_array($row->oinvoice_nexciseID, $invoice)){
							 $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date,
							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
							'Item_Code_Partno'=>$k->oinv_codePartNo,
							'codePartNo_desc'=>$k->codePartNo_desc,
							'issued_qty'=>$k->issued_qty,'Salling'=>'',
							'landing_price'=>$lp,'Margin'=>$lp,'bill_value'=>'---','ms'=>$ms);	
							$tempAmount = ($k->oinv_price * $k->issued_qty);
							$TempSallingPrice = ($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $k->item_amount)+($inci_charge * $k->item_amount);
							//$SallingPrice = $TempSallingPrice+$row->po_saleTax;
							//$SallingPrice = $TempSallingPrice;
							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $freigt_percent)/100);
							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
							
						
						}else{
							  $data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row->oinvoice_No,'oinv_date'=>$row->oinv_date,
							'Principal_Supplier_Name'=>$row->Principal_Name,'BuyerName'=>$row->Buyer_Name,
							'Item_Code_Partno'=>$k->oinv_codePartNo,
							'codePartNo_desc'=>$k->codePartNo_desc,
							'issued_qty'=>$k->issued_qty,'Salling'=>'',
							'landing_price'=>$lp,'Margin'=>$lp,'bill_value'=>number_format($row->bill_value, 2, '.', ''),'ms'=>$ms);	
							$tempAmount = ($k->oinv_price * $k->issued_qty);
							$TempSallingPrice = ($tempAmount-($tempAmount * $percentDiscount)/100)+($pf_chrg * $k->item_amount)+($inci_charge * $k->item_amount);
							//$SallingPrice = $TempSallingPrice+$row->po_saleTax;
							//$SallingPrice = $TempSallingPrice;
							$freigt_percent = 	(($row->freight_amount * 100)/$TempSallingPrice);					
							$SallingPrice = $TempSallingPrice + (($TempSallingPrice  * $freigt_percent)/100);
							$data[$counter]['Salling'] = number_format($SallingPrice, 2, '.', '');
							$data[$counter]['Margin'] = number_format($SallingPrice - ($lp), 2, '.', '');
							$invoice[] = $row->oinvoice_nexciseID;
							$total_val = $total_val + $row->bill_value ;
							
						}
						
						$total_sel = $total_sel + number_format($SallingPrice, 2, '.', '');
						$total_land = $total_land + number_format($lp, 2, '.', '');
						$total_marg = $total_marg + number_format($SallingPrice - ($lp), 2, '.', '');
												
						$counter++; 
				} 
				
				$data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'Grand Total','Item_Code_Partno'=>'','codePartNo_desc'=>'','issued_qty'=>'','Salling'=>$total_sel,'landing_price'=>$total_land,'Margin'=>$total_marg,'bill_value'=>number_format($total_val, 2, '.', ''),'ms'=>''); 
			} 
		//	print_r($data);exit;
		}                  
        return $data; 	
    }
	public static function GetNonExciselandingPrice($principal,$codepart)
    {
		$Query="SELECT iined.total_landing_price,iined.landing_price
		FROM incominginvoice_without_excise_detail_old AS iined 
		INNER JOIN incominginvoice_without_excise_old AS iine ON iined.incominginvoice_we = iine.incominginvoice_we
		WHERE iine.principalID = ".$principal." AND iined.itemID_code_partNo =".$codepart." ORDER BY iined.incominginvoice_we DESC LIMIT 1"; 
			
		$result = DBConnection::SelectQuery($Query); 
		$data = array();
        if(mysql_num_rows($result) > 0){
		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				return $row['landing_price']; 				
			}		
		}
		return 0;
    }
	
	
}

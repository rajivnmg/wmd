<?php
class TallyModel {
	
	public function GetSalesTally($fromdate, $todate)
	{
		$opt ='';
		
		$Query = "SELECT oe.oinvoice_No,oe.oinvoice_exciseID, DATE_FORMAT(oe.oinv_date,'%d-%b-%y') AS INVOICE_DATE,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.pf_chrg,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) + TRUNCATE(oe.p_f_gst_amount,2)) AS P_F_TOTAL,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.pf_chrg,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)) AS P_F_AMOUNT,
(TRUNCATE(oe.p_f_gst_amount,2)) AS P_F_GST,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.incidental_chrg,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) + TRUNCATE(oe.inc_gst_amount,2)) AS INC_TOTAL,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.incidental_chrg,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)) AS INC_AMOUNT,
(TRUNCATE(oe.inc_gst_amount,2)) AS INC_GST,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.insurance_charge,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) + TRUNCATE(oe.ins_gst_amount,2)) AS INS_TOTAL,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.insurance_charge,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)) AS INS_AMOUNT,
(TRUNCATE(oe.ins_gst_amount,2)) AS INS_GST,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.other_charge,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) + TRUNCATE(oe.othc_gst_amount,2)) AS OTHC_TOTAL,
((SELECT TRUNCATE((TRUNCATE(SUM(TRUNCATE(taxable_amt,2)),2) * TRUNCATE((TRUNCATE(oe.other_charge,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)) AS OTHC_AMOUNT,
(TRUNCATE(oe.othc_gst_amount,2)) AS OTHC_GST,
((IF(((oe.freight_percent IS NULL) OR (oe.freight_percent = '') OR (oe.freight_percent = 0)),TRUNCATE(oe.freight_amount,2),(SELECT TRUNCATE((SUM(TRUNCATE(taxable_amt,2)) * TRUNCATE((TRUNCATE(oe.freight_percent,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID))) + TRUNCATE(oe.freight_gst_amount,2)) AS FREIGHT_TOTAL,
((IF(((oe.freight_percent IS NULL) OR (oe.freight_percent = '') OR (oe.freight_percent = 0)),TRUNCATE(oe.freight_amount,2),(SELECT TRUNCATE((SUM(TRUNCATE(taxable_amt,2)) * TRUNCATE((TRUNCATE(oe.freight_percent,2)/100),2)),2) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)))) AS FREIGHT_AMOUNT,
(TRUNCATE(oe.freight_gst_amount,2)) AS FREIGHT_GST,
bill_value, oed.issued_qty,  oed.oinv_price, TRUNCATE((TRUNCATE(oed.oinv_price,2) * oed.issued_qty),2) AS AMOUNT, oed.discount_percent, TRUNCATE((TRUNCATE((TRUNCATE(oed.oinv_price,2) * oed.issued_qty),2) * TRUNCATE((oed.discount_percent/100),2)),2) AS DISCOUNT, oed.taxable_amt, oed.hsn_code, oed.cgst_rate,  oed.cgst_amt, oed.sgst_rate,  oed.sgst_amt, oed.igst_rate,  oed.igst_amt, oed.total, bm.BuyerName, IF((oed.igst_rate > 0),TRUNCATE(oed.igst_rate,0),TRUNCATE((oed.sgst_rate + oed.cgst_rate),0)) AS GST_RATE, im.Item_Desc, (SELECT SUM(taxable_amt) FROM outgoinginvoice_excise_detail oedd WHERE oedd.oinvoice_exciseID = oe.oinvoice_exciseID ) AS TOTAL_TAXABLE_AMOUNT
		FROM outgoinginvoice_excise_detail AS oed
		INNER JOIN outgoinginvoice_excise AS oe ON oed.oinvoice_exciseID = oe.oinvoice_exciseID
		INNER JOIN outgoinginvoice_excise_mapping as oim ON oe.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
		INNER JOIN buyer_master bm ON bm.BuyerId = oe.BuyerID
		INNER JOIN item_master im ON im.ItemId = oed.codePartNo_desc WHERE oe.oinv_date BETWEEN '$fromdate' AND '$todate' ORDER BY oe.oinvoice_No DESC, oed.oinvoice_exciseDID DESC";
		
		//INNER JOIN purchaseorder AS po ON oe.pono = po.bpoId 
		//INNER JOIN purchaseorder_detail AS pod ON pod.bpoId = po.bpoId 
		
		$result = DBConnection::SelectQuery($Query);
		$counter = 0;
		$data = array();
		$invoice = array();
		
		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_assoc($result))
			{
				if($row['igst_rate'] > 0)
				{
					//////////////////////////////////////////////////////////////////////
					if($row['FREIGHT_AMOUNT'] == '')
					{
						$freight = 0;
					}
					else
					{
						$freight = $row['FREIGHT_AMOUNT'];
					}
					$freight_cgst = 0;
					$freight_sgst = 0;
					$freight_igst = $row['FREIGHT_GST'];
					if($row['FREIGHT_TOTAL'] == '')
					{
						$freight_total = 0;
					}
					else
					{
						$freight_total = $row['FREIGHT_TOTAL'];
					}
					/////////////////////////////////////////////////////////////////////////////////
					if($row['P_F_AMOUNT'] == '')
					{
						$p_f = 0;
					}
					else
					{
						$p_f = $row['P_F_AMOUNT'];
					}
					
					$p_f_cgst = 0;
					$p_f_sgst = 0;
					$p_f_igst = $row['P_F_GST'];
					
					if($row['P_F_TOTAL'] == '')
					{
						$p_f_total = 0;
					}
					else
					{
						$p_f_total = $row['P_F_TOTAL'];
					}
					/////////////////////////////////////////////////////////////////////////////////
					if($row['INC_AMOUNT'] == '')
					{
						$inc = 0;
					}
					else
					{
						$inc = $row['INC_AMOUNT'];
					}
					
					$inc_cgst = 0;
					$inc_sgst = 0;
					$inc_igst = $row['INC_GST'];
					if($row['INC_TOTAL'] == '')
					{
						$inc_total = 0;
					}
					else
					{
						$inc_total = $row['INC_TOTAL'];
					}
					//////////////////////////////////////////////////////////////////////////
					if($row['INS_AMOUNT'] == '')
					{
						$ins = 0;
					}
					else
					{
						$ins = $row['INS_AMOUNT'];
					}
					
					$ins_cgst = 0;
					$ins_sgst = 0;
					$ins_igst = $row['INS_GST'];
					if($row['INS_TOTAL'] == '')
					{
						$ins_total = 0;
					}
					else
					{
						$ins_total = $row['INS_TOTAL'];
					}
					//////////////////////////////////////////////////////////////////////////////
					if($row['OTHC_AMOUNT'] == '')
					{
						$othc = 0;
					}
					else
					{
						$othc = $row['OTHC_AMOUNT'];
					}
					
					$othc_cgst = 0;
					$othc_sgst = 0;
					$othc_igst = $row['OTHC_GST'];
					if($row['OTHC_TOTAL'] == '')
					{
						$othc_total = 0;
					}
					else
					{
						$othc_total = $row['OTHC_TOTAL'];
					}
				}
				else
				{//////////////////////////////////////////////////////////////////////
					if($row['FREIGHT_AMOUNT'] == '')
					{
						$freight = 0;
					}
					else
					{
						$freight = $row['FREIGHT_AMOUNT'];
					}
					$freight_cgst = $row['FREIGHT_GST']/2;
					$freight_sgst = $row['FREIGHT_GST']/2;
					$freight_igst = 0;
					if($row['FREIGHT_TOTAL'] == '')
					{
						$freight_total = 0;
					}
					else
					{
						$freight_total = $row['FREIGHT_TOTAL'];
					}
					/////////////////////////////////////////////////////////////////////////////////
					if($row['P_F_AMOUNT'] == '')
					{
						$p_f = 0;
					}
					else
					{
						$p_f = $row['P_F_AMOUNT'];
					}
					
					$p_f_cgst = $row['P_F_GST']/2;
					$p_f_sgst = $row['P_F_GST']/2;
					$p_f_igst = 0;
					
					if($row['P_F_TOTAL'] == '')
					{
						$p_f_total = 0;
					}
					else
					{
						$p_f_total = $row['P_F_TOTAL'];
					}
					/////////////////////////////////////////////////////////////////////////////////
					if($row['INC_AMOUNT'] == '')
					{
						$inc = 0;
					}
					else
					{
						$inc = $row['INC_AMOUNT'];
					}
					
					$inc_cgst = $row['INC_GST']/2;
					$inc_sgst = $row['INC_GST']/2;
					$inc_igst = 0;
					if($row['INC_TOTAL'] == '')
					{
						$inc_total = 0;
					}
					else
					{
						$inc_total = $row['INC_TOTAL'];
					}
					//////////////////////////////////////////////////////////////////////////
					if($row['INS_AMOUNT'] == '')
					{
						$ins = 0;
					}
					else
					{
						$ins = $row['INS_AMOUNT'];
					}
					
					$ins_cgst = $row['INS_GST']/2;
					$ins_sgst = $row['INS_GST']/2;
					$ins_igst = 0;
					if($row['INS_TOTAL'] == '')
					{
						$ins_total = 0;
					}
					else
					{
						$ins_total = $row['INS_TOTAL'];
					}
					//////////////////////////////////////////////////////////////////////////////
					if($row['OTHC_AMOUNT'] == '')
					{
						$othc = 0;
					}
					else
					{
						$othc = $row['OTHC_AMOUNT'];
					}
					
					$othc_cgst = $row['OTHC_GST']/2;
					$othc_sgst = $row['OTHC_GST']/2;
					$othc_igst = 0;
					if($row['OTHC_TOTAL'] == '')
					{
						$othc_total = 0;
					}
					else
					{
						$othc_total = $row['OTHC_TOTAL'];
					}
				}
				
				if (in_array($row['oinvoice_No'], $invoice))
				{
					$data[$counter] = array('Sales_Type'=>'Sales','Invoice_date'=>$row['INVOICE_DATE'], 'Invoice_number'=>$row['oinvoice_No'], 'Party_Name'=>$row['BuyerName'], 'Sales_Ledger'=>'GST Sales @ '.$row['GST_RATE'].'%','Item_name'=>$row['Item_Desc'], 'HSN_Code'=>$row['hsn_code'], 'Rate'=>$row['oinv_price'], 'Qty'=>number_format($row['issued_qty'], 2, '.', ''), 'Amount'=>$row['AMOUNT'], 'discount_percent'=>$row['discount_percent'].'%','Discount'=>$row['DISCOUNT'],'Taxable_Amount'=>$row['taxable_amt'], 'CGST'=>$row['cgst_rate'].'%', 'CGST_AMOUNT'=>$row['cgst_amt'], 'SGST'=>$row['sgst_rate'].'%', 'SGST_AMOUNT'=>$row['sgst_amt'], 'IGST'=>$row['igst_rate'].'%', 'IGST_AMOUNT'=>$row['igst_amt'], 'Total'=> $row['total'], 'Freight'=> '', 'Freight_CGST'=> '', 'Freight_SGST'=>'', 'Freight_IGST'=> '', 'Freight_Total'=> '', 'P_F'=> '', 'P_F_CGST'=> '', 'P_F_SGST'=> '', 'P_F_IGST'=> '', 'P_F_Total'=> '', 'INC'=> '', 'Inc_CGST'=> '', 'Inc_SGST'=> '', 'Inc_IGST'=> '', 'Inc_Total'=> '', 'INS'=> '', 'INS_CGST'=> '', 'Ins_SGST'=> '', 'Ins_IGST'=> '', 'INS_Total'=> '', 'OTHC'=> '', 'OTHC_CGST'=> '', 'OTHC_SGST'=> '', 'Othc_IGST'=> '', 'OTHC_Total'=> '', 'Total_Invoice'=> '' );
				}
				else
				{
					$data[$counter] = array('Sales_Type'=>'Sales','Invoice_date'=>$row['INVOICE_DATE'], 'Invoice_number'=>$row['oinvoice_No'], 'Party_Name'=>$row['BuyerName'], 'Sales_Ledger'=>'GST Sales @ '.$row['GST_RATE'].'%','Item_name'=>$row['Item_Desc'], 'HSN_Code'=>$row['hsn_code'], 'Rate'=>$row['oinv_price'], 'Qty'=>number_format($row['issued_qty'], 2, '.', ''), 'Amount'=>$row['AMOUNT'], 'discount_percent'=>$row['discount_percent'].'%','Discount'=>$row['DISCOUNT'],'Taxable_Amount'=>$row['taxable_amt'], 'CGST'=>$row['cgst_rate'].'%', 'CGST_AMOUNT'=>$row['cgst_amt'], 'SGST'=>$row['sgst_rate'].'%', 'SGST_AMOUNT'=>$row['sgst_amt'], 'IGST'=>$row['igst_rate'].'%', 'IGST_AMOUNT'=>$row['igst_amt'], 'Total'=> $row['total'], 'Freight'=> $freight, 'Freight_CGST'=> $freight_cgst, 'Freight_SGST'=> $freight_sgst, 'Freight_IGST'=> $freight_igst, 'Freight_Total'=> $freight_total, 'P_F'=> $p_f, 'P_F_CGST'=> $p_f_cgst, 'P_F_SGST'=> $p_f_sgst, 'P_F_IGST'=> $p_f_igst, 'P_F_Total'=> $p_f_total, 'INC'=> $inc, 'Inc_CGST'=> $inc_cgst, 'Inc_SGST'=> $inc_sgst, 'Inc_IGST'=> $inc_igst, 'Inc_Total'=> $inc_total, 'INS'=> $ins, 'INS_CGST'=> $ins_cgst, 'Ins_SGST'=> $ins_sgst, 'Ins_IGST'=> $ins_igst, 'INS_Total'=> $ins_total, 'OTHC'=> $othc, 'OTHC_CGST'=> $othc_cgst, 'OTHC_SGST'=> $othc_sgst, 'Othc_IGST'=> $othc_igst, 'OTHC_Total'=> $othc_total, 'Total_Invoice'=> $row['bill_value'] );
					$invoice[] = $row['oinvoice_No'];
				}
				
				$counter++;
			}
		}
		
		$data_1 = array_reverse($data);
		
		return $data_1;
	}	
}


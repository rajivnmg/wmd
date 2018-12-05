<?php
class RgReportModel
{
		
	public static function getRg23dReport($fromdate,$todate,$pid){
		
		$query = "SELECT iie.entryId, iie.principal_inv_no, iie.principal_inv_date, iie.supplier_inv_no, iie.supplier_inv_date, iemp.display_EntryNo, iied.* ,psm.Principal_Supplier_Name,psm.ADD1,psm.ADD2,psm.PS_RANGE, psm.PS_DIVISION, psm.PS_COMMISSIONERATE, psm.ECC_CODENO ,supplierPsm.Principal_Supplier_Name as supplier_name,supplierPsm.ADD1 as supplier_address1,supplierPsm.ADD2 as supplier_address2,
		supplierPsm.PS_RANGE as supplier_PS_RANGE, supplierPsm.PS_DIVISION as supplier_PS_DIVISION, supplierPsm.PS_COMMISSIONERATE as supplier_PS_COMMISSIONERATE, supplierPsm.ECC_CODENO as supplier_ECC_CODENO, im.Item_Desc, im.Tarrif_Heading, oiie.oinvoice_No, oiie.oinv_date, bm.Division, bm.BuyerName, bm.Commissionerate, bm.Buyer_Range, bm.ECC,
		oiid.issued_qty, oiid.ed_amt as outgoing_ed_amt, oiid.oinv_price as outgoing_oinv_price, oiid.hedu_amount as outgoing_hedu_amount, oiid.cvd_amt as outgoing_cvd_amt, um.UNITNAME
		FROM incominginvoice_excise AS iie
		LEFT JOIN incominginvoice_entryno_mapping as iemp ON iie.entryId = iemp.inner_EntryNo
		LEFT JOIN incominginvoice_excise_detail AS iied ON iied.entryId = iie.entryId
		LEFT JOIN incominginvoice_excise_detail_mapping AS iidem ON iidem.IncomingInvoiceDetailID = iied.entryDId
		LEFT JOIN item_master AS im ON iied.itemID_code_partNo = im.ItemId
		LEFT JOIN unit_master AS um ON um.UnitId = im.UnitId
		LEFT JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = iie.principalId
		LEFT JOIN principal_supplier_master AS supplierPsm ON supplierPsm.Principal_Supplier_Id = iie.supplierId
		LEFT JOIN outgoinginvoice_excise_detail AS oiid ON iied.entryDId = oiid.iinv_no
		LEFT JOIN outgoinginvoice_excise AS oiie ON oiie.oinvoice_exciseID = oiid.oinvoice_exciseID
		LEFT JOIN buyer_master AS bm ON bm.BuyerId = oiie.BuyerID
		where 1=1 ";
		//Query conditions
		//$query .= "and (iie.entryId = 8360 or iie.entryId = 1)";
		$query .= " and iie.rece_date BETWEEN '$todate' AND '$fromdate'";
		if(!empty($pid)){
			$query .= " and iie.principalId = '$pid'";
		}
		//order by
		$query .= " order by iie.entryId ASC, oiid.oinvoice_exciseDID ASC ";
		$result = DBConnection::SelectQuery($query);
		$data = array();
		$prev_entryId = 0;
		$prev_entryDid = 0;
		$counter = 0; 
		if(mysql_num_rows($result) > 0){	
			$sn = 0;	
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				
				$incoming_invoice_No_and_date = $row['principal_inv_no'].'<br style="mso-data-placement:same-cell;" /> '.$row['principal_inv_date'];
				
				$manufacture_name_and_address = $row['Principal_Supplier_Name'].'<br style="mso-data-placement:same-cell;" /> '.$row['ADD1'].' '.$row['ADD2'].'<br style="mso-data-placement:same-cell;" />Range: '.$row['PS_RANGE'].'<br style="mso-data-placement:same-cell;" />Division: '.$row['PS_DIVISION'].'<br style="mso-data-placement:same-cell;" />Commission rate: '.$row['PS_COMMISSIONERATE'].'<br style="mso-data-placement:same-cell;" />ECC CodeNo: '.$row['ECC_CODENO'];
				
				$supplier_invice_no_and_date = !empty($row['supplier_inv_no']) ? $row['supplier_inv_no'].'<br style="mso-data-placement:same-cell;" />'.$row['supplier_inv_date']:'';
				
				$name_and_address_manufacture = !empty($row['supplier_inv_no']) ? $row['supplier_name'].'<br/> '.$row['supplier_address1'].'<br style="mso-data-placement:same-cell;" />'.$row['supplier_address2'].'<br style="mso-data-placement:same-cell;" />Range: '.$row['supplier_PS_RANGE'].'<br style="mso-data-placement:same-cell;" />Division: '.$row['supplier_PS_DIVISION'].'<br style="mso-data-placement:same-cell;" />Commission rate: '.$row['supplier_PS_COMMISSIONERATE'].'<br style="mso-data-placement:same-cell;" />ECC CodeNo: '.$row['supplier_ECC_CODENO']:' ';
				
				$customer_name = !empty($row['BuyerName'])?$row['BuyerName'].'<br style="mso-data-placement:same-cell;" />Range: '.$row['Buyer_Range'].'<br style="mso-data-placement:same-cell;" />Division: '.$row['Division'].'<br style="mso-data-placement:same-cell;" />Commission rate: '.$row['Commissionerate'].'<br style="mso-data-placement:same-cell;" />ECC CodeNo: '.$row['ECC']:'';
				
				if($row['display_EntryNo'] != $prev_entryId){
					$sn = $row['display_EntryNo'];
				}else{
					$sn = '';
				}
				
				if($row['entryDId'] != $prev_entryDid){
					$prev_entryDid = $row['entryDId'];
					$sell_item_count = 0;
					$sn = $sn;
					$incoming_invoice_No_and_date = $incoming_invoice_No_and_date;
					$manufacture_name_and_address = $manufacture_name_and_address;
					$incoming_quantity = $row['p_qty'].' '.$row['UNITNAME'];
					$incoming_amount_rate = $row['ed_percent'].'%';
					$incoming_amount_of_duty = $row['ed_amt'];
					$incoming_sh_edu_cess = $row['hedu_amount'];
					$incoming_spl_add_duty_of_custom = $row['cvd_amt'];
					$description_of_goods = $row['Item_Desc'];
					$supplier_invice_no_and_date = $supplier_invice_no_and_date;
					$name_and_address_manufacture = $name_and_address_manufacture;
					$supplier_quantity = $row['s_qty'].' '.$row['UNITNAME'];
					$tariff_heading = $row['Tarrif_Heading'];
					$issue_amount_of_duty = $row['outgoing_ed_amt'];
					$issue_edu_cess = 0.00;
					$issue_sh_edu_cess = $row['outgoing_hedu_amount'];
					$issue_duty_of_custom = $row['outgoing_cvd_amt'];
					
				}else{
					$sn = '';
					$incoming_invoice_No_and_date = '';
					$manufacture_name_and_address = '';
					$incoming_quantity = '';
					$incoming_amount_rate = '';
					$incoming_amount_of_duty = '';
					$incoming_sh_edu_cess = '';
					$incoming_spl_add_duty_of_custom = '';
					$description_of_goods = '';
					$supplier_invice_no_and_date = '';
					$name_and_address_manufacture = '';
					$supplier_quantity = '';
					$tariff_heading = '';
					$issue_amount_of_duty = '';
					$issue_edu_cess = '';
					$issue_sh_edu_cess = '';
					$issue_duty_of_custom = '';
				}
				$data[$counter] = array(
								'SN'=>$sn,
								'incoming_invoice_No_and_date'=>$incoming_invoice_No_and_date,
								'manufacture_name_and_address'=>$manufacture_name_and_address,
								'incoming_quantity'=>$incoming_quantity,
								'incoming_amount_rate'=>$incoming_amount_rate,
								'incoming_amount_of_duty'=>$incoming_amount_of_duty,
								'incoming_education_cess'=>$incoming_education_cess,
								'incoming_sh_edu_cess'=>$incoming_sh_edu_cess,
								'incoming_spl_add_duty_of_custom'=>$incoming_spl_add_duty_of_custom,
								'description_of_goods'=>$description_of_goods,
								'supplier_invice_no_and_date'=>$supplier_invice_no_and_date,
								'name_and_address_manufacture'=>$name_and_address_manufacture,
								'supplier_quantity'=>$supplier_quantity,
								'tariff_heading'=>$tariff_heading,
								'issue_amount_of_duty'=>$issue_amount_of_duty,
								'issue_edu_cess'=>$issue_edu_cess,
								'issue_sh_edu_cess'=>$issue_sh_edu_cess,
								'issue_duty_of_custom'=>$issue_duty_of_custom,
								'outgoing_invoice_no_and_date'=>$row['oinvoice_No'].'<br style="mso-data-placement:same-cell;" /> '.$row['oinv_date'],
								'customer_name'=>$customer_name,
								'outgoing_quantity'=>!empty($row['issued_qty']) ? $row['issued_qty'].' '.$row['UNITNAME']:'',
								'outgoing_amount_of_duty'=>$row['outgoing_ed_amt'],
								'outgoing_edu_cess'=>!empty($row['issued_qty']) ? '0.00':'',
								'outgoing_sh_edu_cess'=>$row['outgoing_hedu_amount'],
								'outgoing_spl_add_duty_of_custom'=>$row['outgoing_cvd_amt'],
								'remarks'=>empty($row['supplier_name'])?($row['p_qty']-$row['issued_qty']-$sell_item_count).$row['UNITNAME'].' remaining':($row['s_qty']-$row['issued_qty']-$sell_item_count).$row['UNITNAME'].' remaining');
				$counter++; 
				$prev_entryId = $row['display_EntryNo'];
				$sell_item_count = $sell_item_count + $row['issued_qty'];
			}
		}
		return array('page'=>1,'total'=>mysql_num_rows($result),'rows'=>$data);
    }
}

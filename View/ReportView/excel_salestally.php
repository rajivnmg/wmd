<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=SalesTally.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/TallyModel.php");
include_once("../../Model/Param/param_model.php");

echo 'Sales Type' . "\t" . 'Invoice Date'."\t".'Invoice No.' . "\t" . 'Party Name'."\t".'Sales Ledger' ."\t".'Item Name' ."\t".'HSN Code '. "\t".'Rate'. "\t" . 'Qty.'."\t". 'Amount'."\t".'DISCOUNT%'. "\t" .'DISCOUNT AMOUNT'. "\t" .'TAXABLE AMOUNT'. "\t" .'CGST'. "\t" .'CGST Amount'. "\t" .'SGST'. "\t" .'SGST Amount'. "\t" .'IGST'. "\t" .'IGST Amount'. "\t" .'Total'. "\t" .'Freight'. "\t" . 'Freight CGST'."\t". 'Freight SGST'."\t".'Freight IGST'."\t".'Freight Total'."\t".'P&F'. "\t" . 'P&F CGST'."\t". 'P&F SGST'."\t".'P&F IGST'."\t".'P&F Total'."\t".'Insurance'. "\t" . 'Insurance CGST'."\t". 'Insurance SGST'."\t".'Insurance IGST'."\t".'Insurance Total'."\t".'Incidental'. "\t" . 'Incidental CGST'."\t". 'Incidental SGST'."\t".'Incidental IGST'."\t".'Incidental Total'."\t".'Other'. "\t" . 'Other CGST'."\t". 'Other SGST'."\t".'Other IGST'."\t".'Other Total'."\t".'Total Invoice'."\t"."\n";

//$total =0;		
$objQuery = TallyModel::GetSalesTally($_REQUEST['fromdate'],$_REQUEST['todate']);
if(empty($objQuery)){
 exit;
}
foreach ($objQuery as $eachResult) 
{	
	echo $eachResult['Sales_Type'] . "\t" . $eachResult['Invoice_date'] ."\t".$eachResult['Invoice_number'] . "\t" . $eachResult['Party_Name'] ."\t".$eachResult['Sales_Ledger'] . "\t" .$eachResult['Item_name'] . "\t" . $eachResult['HSN_Code'] ."\t". $eachResult['Rate'] ."\t".$eachResult['Qty'] ."\t".$eachResult['Amount']."\t".$eachResult['discount_percent'] ."\t".$eachResult['Discount'] ."\t".$eachResult['Taxable_Amount'] ."\t".$eachResult['CGST'] ."\t".$eachResult['CGST_AMOUNT'] ."\t".$eachResult['SGST'] ."\t". $eachResult['SGST_AMOUNT'] ."\t". $eachResult['IGST'] ."\t". $eachResult['IGST_AMOUNT'] ."\t" . $eachResult['Total'] ."\t" . $eachResult['Freight'] ."\t".$eachResult['Freight_CGST'] . "\t" . $eachResult['Freight_SGST'] ."\t".$eachResult['Freight_IGST'] . "\t" .$eachResult['Freight_Total'] . "\t" . $eachResult['P_F'] ."\t". $eachResult['P_F_CGST'] ."\t".$eachResult['P_F_SGST'] ."\t".$eachResult['P_F_IGST']."\t".$eachResult['P_F_Total'] ."\t".$eachResult['INS'] ."\t".$eachResult['INS_CGST'] ."\t".$eachResult['Ins_SGST'] ."\t".$eachResult['Ins_IGST'] ."\t".$eachResult['INS_Total'] ."\t". $eachResult['INC'] ."\t". $eachResult['Inc_CGST'] ."\t". $eachResult['Inc_SGST'] ."\t" . $eachResult['Inc_IGST'] ."\t" .$eachResult['Inc_Total'] ."\t".$eachResult['OTHC'] ."\t". $eachResult['OTHC_CGST'] ."\t". $eachResult['OTHC_SGST'] ."\t". $eachResult['Othc_IGST'] ."\t" . $eachResult['OTHC_Total'] ."\t"  . $eachResult['Total_Invoice'] ."\t" ."\n";
}
?>
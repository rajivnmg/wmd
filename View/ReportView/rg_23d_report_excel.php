<?php
ini_set('display_errors', 0); 
ini_set('display_startup_errors', 0);
error_reporting(0); 
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=rg-23d-Report.xls");
include '../../Model/DBModel/DbModel.php';
include_once("../../Model/ReportModel/RgReportModel.php");
include_once("../../Model/Param/param_model.php");
$body = '<table border="1">';
$CompanyInfo = ParamModel::GetCompanyInfo();
$header = '<tr>
				<td colspan = "4">'.$CompanyInfo["Name"].'<br style="mso-data-placement:same-cell;" />'.str_replace(","," ",$CompanyInfo["Address"]).'</td>
				<td></td>
				<td></td>
				<td colspan = "4">RG 23 D : From -'.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'</td>
				<td colspan = "16"></td>
		   </tr>';
$tableHeader = 
"<thead>
<tr>
	<th rowspan = '2'>S. No.</th>
	<th rowspan = '2'>Manufacture or Importer <br style='mso-data-placement:same-cell;' />Invoice No or Bill<br style='mso-data-placement:same-cell;' /> of Entry No & Date</th>
	<th rowspan = '2'>Name & Address of Manufacture<br style='mso-data-placement:same-cell;' /> or Importer including <br style='mso-data-placement:same-cell;' />Central Excise Range Division<br style='mso-data-placement:same-cell;' /> and Commissionrate, <br style='mso-data-placement:same-cell;' />Custom house and his <br style='mso-data-placement:same-cell;' />New Excise control (Manufacture) or <br style='mso-data-placement:same-cell;' />Imorter Export Code (Importer)</th>
	<th rowspan = '2'>Quantity</th>
	<th colspan = '5'>Receipt Amount</th>
	<th rowspan = '2'>Description of Goods</th>
	<th rowspan = '2'>Invoice No. & Date<br style='mso-data-placement:same-cell;' /> or Bill of Entry & Date of the<br style='mso-data-placement:same-cell;' /> Supplier if he is not <br style='mso-data-placement:same-cell;' />manufacture or importer </th>
	<th rowspan = '2'>Name & Address of the <br style='mso-data-placement:same-cell;' />Manufacture or Importer including <br style='mso-data-placement:same-cell;' />Central Excise Range Division<br style='mso-data-placement:same-cell;' /> & Commissionrate, Custom house</th>
	<th rowspan = '2'>Quantity</th>
	<th rowspan = '2'>Tariff heading or <br style='mso-data-placement:same-cell;' />Sub heading No.</th>
	<th colspan = '4'>Issue Amount</th>
	<th rowspan= '2'>Invoice No. & Date</th>
	<th rowspan= '2'>Name of the Customer<br style='mso-data-placement:same-cell;' /> to whom goods are<br style='mso-data-placement:same-cell;' /> sold including Central <br style='mso-data-placement:same-cell;' />Excise range, Division <br style='mso-data-placement:same-cell;' />& Commissionerate Custom House</th>
	<th rowspan = '2'>Quantity</th>
	<th colspan = '4'>Total Amount of Duty</th>
	<th rowspan = '2'>Remarks</th>
</tr>";
$tableHeader .= 
'<tr>
	<th>rate</th>
	<th>amount of duty</th>
	<th>education Cess</th>
	<th>s&h edu cess</th>
	<th>Spl. Add Duty of Custom</th>
	<th>amount of Duty</th>
	<th>Edu. Cess</th>
	<th>S & H Edu Cess</th>
	<th>Add Duty of Custom</th>
	<th>amount of Duty</th>
	<th>Edu Cess</th>
	<th>S & H Edu Cess</th>
	<th>Spl add duty of Custom</th>
</tr>
</thead>';
$tableBody = '';
$Print = RgReportModel::getRg23dReport($_REQUEST['fromdate'],$_REQUEST['todate'],$_REQUEST['pid']);
foreach($Print['rows'] as $row){
	$tableBody .= '<tr>';
	$tableBody .= '<td>'.$row['SN'].'</td>';
	$tableBody .= '<td>'.$row['incoming_invoice_No_and_date'].'</td>';
	$tableBody .= '<td>'.$row['manufacture_name_and_address'].'</td>';
	$tableBody .= '<td>'.$row['incoming_quantity'].'</td>';
	$tableBody .= '<td>'.$row['incoming_amount_rate'].'</td>';
	$tableBody .= '<td>'.$row['incoming_amount_of_duty'].'</td>';
	$tableBody .= '<td>'.$row['incoming_education_cess'].'</td>';
	$tableBody .= '<td>'.$row['incoming_sh_edu_cess'].'</td>';
	$tableBody .= '<td>'.$row['incoming_spl_add_duty_of_custom'].'</td>';
	$tableBody .= '<td>'.$row['description_of_goods'].'</td>';
	$tableBody .= '<td>'.$row['supplier_invice_no_and_date'].'</td>';
	$tableBody .= '<td>'.$row['name_and_address_manufacture'].'</td>';
	$tableBody .= '<td>'.$row['supplier_quantity'].'</td>';
	$tableBody .= '<td>'.$row['tariff_heading'].'</td>';
	$tableBody .= '<td>'.$row['issue_amount_of_duty'].'</td>';
	$tableBody .= '<td>'.$row['issue_edu_cess'].'</td>';
	$tableBody .= '<td>'.$row['issue_sh_edu_cess'].'</td>';
	$tableBody .= '<td>'.$row['issue_duty_of_custom'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_invoice_no_and_date'].'</td>';
	$tableBody .= '<td>'.$row['customer_name'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_quantity'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_amount_of_duty'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_edu_cess'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_sh_edu_cess'].'</td>';
	$tableBody .= '<td>'.$row['outgoing_spl_add_duty_of_custom'].'</td>';
	$tableBody .= '<td>'.$row['remarks'].'</td>';
	$tableBody .= '</tr>';
}
echo $body.$header.$tableHeader.$tableBody.'</table>';
exit;

<?php
/*
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
*/
ini_set('memory_limit', '128M');
ini_set('max_execution_time', 3600);
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/ReportModel/SalseReportModel.php");
include_once("../../Model/ReportModel/StockStatementModel.php");
include_once("../../Model/ReportModel/MarginReportModel.php");
include_once("../../Model/ReportModel/TallyModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
include_once("../../Model/Business_Action_Model/Outgoing_Invoice_NonExcise_Model.php");


$Type = $_REQUEST['TYP'];
switch($Type){
    case "SALSEREPORT": // call to show the sales report from report
        $Print = SalseReportModel::SearchSalseReport($_REQUEST['RT'],$_REQUEST['CN'],$_REQUEST['DF'],$_REQUEST['DT'],$_REQUEST['PI'],$_REQUEST['II'],$_REQUEST['BI'],$_REQUEST['SI']);
        echo json_encode($Print);
        return;
        break;
	case "DAILYSALESREPORT":		// call when accesss the daily sales report from report menu
        $data = SalseReportModel::getDailySalesReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value'],$_REQUEST['pid'],$_REQUEST['bid']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
		
	case "SALESTALLYREPORT":		// call when accesss the daily sales report from report menu
        $data = TallyModel::GetSalesTally($_REQUEST['fromdate'],$_REQUEST['todate']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
		
	case "PURCHASEREPORT": // call when accesss the Purchase Report from report menu but now this is not in use
        $data = SalseReportModel::GetPurchaseReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value']);
		$offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
        
	case "PURCHASEREPORTNEW":	// Call when access the purchase report from report menu
	
	    $data = SalseReportModel::GetPurchaseReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketseg'],$_REQUEST['invoicenumber'],$_REQUEST['pid'],$_REQUEST['itemid']);
		//print_r($data); exit;
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;	
	
    case "EXCISESECONDARYSALSESTATEMENT":	// Call when access the EXCISE-SECONDARY SALES STATEMENT from report menu
		$userdata = array();
        $data = StockStatementModel::GetExciseSecondarySalesStatement($_REQUEST['Principal'],$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketsegment'],$_REQUEST['finyear'],$_REQUEST['buyerid']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
				
		if (!empty($data)){
			$userdata = array_slice($data,$limit, $offset);
		}
		$response = array(
	    'page' => $page,
	    'total' => count($data),
	    'rows' => $userdata
	);
        echo json_encode($response);
        break;
    case "NONEXCISESECONDARYSALSESTATEMENT":		// Call when access the NON-EXCISE-SECONDARY SALES STATEMENT from report menu
    	$userdata = array();
    	
        $data = StockStatementModel::GetNonExciseSecondarySalesStatement($_REQUEST['Principal'],$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['marketsegment'],$_REQUEST['finyear'],$_REQUEST['buyerid']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
		if (!empty($data)){
			$userdata = array_slice($data,$limit, $offset);
		}
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
    case "EXCISESECONDARYSALSE":	// Call when access the EXCISE SALES STATEMENT from report menu
		$userdata = array();
			
        $data = StockStatementModel::GetExciseSecondarySales($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['salseuser'],$_REQUEST['marketsegment'],$_REQUEST['buyerid']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
		
	
		if (!empty($data)){
			$userdata = array_slice($data,$limit, $offset);
		}

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
		echo json_encode($response);
        break;
    case "NONEXCISESECONDARYSALSE":	// Call when access the NON-EXCISE SALES STATEMENT from report menu
		$userdata = array();
        $data = StockStatementModel::GetNonExciseSecondarySales($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['salseuser'],$_REQUEST['marketsegment'],$_REQUEST['buyerid']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
       if (!empty($data)){
			$userdata = array_slice($data,$limit, $offset);
		}

	$response = array(
	    'page' => $page,
	    'total' => count($data),
	    'rows' => $userdata
	);
        echo json_encode($response);
        break;
    case "EXCISEProductLedger":		//// Call when access the EXCISE-PRODUCT LEDGER from report menu
   
        $data = StockStatementModel::GetExciseProductLedger($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['finyear']);
		$offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
    case "NONEXCISEProductLedger":	// Call when access the NON-EXCISE from report menu
        $data = StockStatementModel::GetNonExciseProductLedger($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['itemid'],$_REQUEST['finyear']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
    case "EXCISESalesTaxReturn":	// Call when access the EXCISE-SALES TAX RETURN from report menu
        $data = StockStatementModel::GetSalesTaxReturnExcise($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['BUYERID'],$_REQUEST['InvoiceNo']);
        echo json_encode($data);
        break;
    case "NonEXCISESalesTaxReturn":		// Call when access the NON-EXCISE-SALES TAX RETURN from report menu
        $data = StockStatementModel::GetSalesTaxReturnNonExcise($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['BUYERID'],$_REQUEST['InvoiceNo']);
        echo json_encode($data);
        break;
    case "INCOMINGEXCISERETURN":		// Call when access the INCOMING-EXCISE RETURN from report menu
        $data = StockStatementModel::GetExciseReturn("INCOMING",$_REQUEST['todate'],$_REQUEST['fromdate']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
    case "OUTGOINGEXCISERETURN":	// Call when access the OUTGOING-EXCISE RETURN from report menu
        $data = StockStatementModel::GetExciseReturn("OUTGOING",$_REQUEST['todate'],$_REQUEST['fromdate']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);
		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
	case "STOCKTRANSFEREXCISERETURN":		// Call when access the STOCK TRANSFER-EXCISE RETURN from report menu
        $data = StockStatementModel::GetStockTransferExciseReturn("STOCKTRANSFER",$_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['finyear']);
        $offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
    case "MARGINREPORT":		// Call when access the EXCISE-MARGIN-REPORT from report menu
        //$data = MarginReportModel::GetMarginReport($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['tag'],$_REQUEST['value']);
        $data = MarginReportModel::GetMarginReportNew($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['buyerid'],$_REQUEST['txtinvoicenumber'],$_REQUEST['finyear'],$_REQUEST['marketsegment']);
		$offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
		
	case "MARGINREPORTNONExcise":    // Call when access the NON-EXCISE-MARGIN-REPORT from report menu
		
		$data = MarginReportModel::GetMarginReportNonExcise($_REQUEST['todate'],$_REQUEST['fromdate'],$_REQUEST['principalid'],$_REQUEST['buyerid'],$_REQUEST['txtinvoicenumber'],$_REQUEST['finyear'],$_REQUEST['marketsegment']);
		$offset = isset($_POST['rp']) ? intval($_POST['rp']) : 10;
        $page=isset($_POST['page']) ? intval($_POST['page']) : 1;
		$limit = ($page-1)*$offset;
        $userdata = array_slice($data,$limit, $offset);

		$response = array(
			'page' => $page,
			'total' => count($data),
			'rows' => $userdata
		);
        echo json_encode($response);
        break;
	
	case "STOCKSTATEMENTWITHDATEVALUE":   // Call when access the EXCISE/NON-EXCISE STOCK STATEMENT WITH DATE AND VALUE from report menu
		$output ='';
		$Type = $_REQUEST['st_type'];
		$curentFinYear = $_REQUEST['finyear'];
		$date = $_REQUEST['tilldate'];	
		/* BOF to merge EXCISE and NON-EXCISE data by Ayush Giri on 22-07-2017 */
		/* if($Type == 'E'){
			$data =  StockStatementModel::GetExciseStockWithValue();
		}else{
			$data =  StockStatementModel::GetNonExciseStockWithValue();
		} */
		$data =  StockStatementModel::GetExciseStockWithValue();
		/* EOF to merge EXCISE and NON-EXCISE data by Ayush Giri on 22-07-2017 */
		
		$output.='<table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>Part No.</th>
                                            <th>GroupDesc</th>
                                            <th>Product Description</th>
                                            <th>HSN Code</th>
                                            <th>Quantity</th>
                                            <th>Unit</th>
                                            <th>Price</th>
                                            <th>Total Price</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th>Grand Total</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>';                   
				while($row=mysql_fetch_array($data, MYSQL_ASSOC)){   
					
					$code_partNo = $row["ItemId"];
					$qty = 0;
					//$qty = StockStatementModel::checkInventoryByDate($Type,$code_partNo,$curentFinYear,$date);       
					
					$qty = StockStatementModel::checkInventoryByDateFromTransaction($Type,$code_partNo,$curentFinYear,$date);     
					 
					             
					$output.="<tr>";
					$output.="<td>".$row["SN"]."</td>";
					$output.="<td>".$row["Item_Code_Partno"]."</td>";
					$output.="<td>".$row["GroupDesc"]."</td>";
					$output.="<td>".$row["Item_Desc"]."</td>";
					$output.="<td>".$row["Tarrif_Heading"]."</td>";
					$output.="<td>".$qty."</td>";
					$output.="<td>".$row["UNITNAME"]."</td>";
					$output.="<td>".$row["Cost_Price"]."</td>";
					$output.="<td>".($qty * $row["Cost_Price"])."</td>";
					$output.="</tr>";
				}	
				
				$output.="</tbody></table>";
		echo $output;
	    return;
  		break;
				
		
		
	case "INVOICEHISTORY":		// Call to sghow the history of invoice if edited or updated
       
		$data = StockStatementModel::getInvoiceHistory($_REQUEST['id'],$_REQUEST['type'],$_REQUEST['codepart'],$_REQUEST['tranctionType']);
		$i = 1;
		$output ='';
        $output.='<table class="table table-striped table-bordered table-hover">
                       <thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Qty</th>
								<th>Update Qty</th>
								<th>Balance Qty</th>
							</tr>
					</thead>
					<tbody>';
					foreach($data as $row){
				
					  $output.=' <tr class="odd gradeX">
						   <td>'.$i++.'</td>
						   <td>'.$row['date'].'</td>
						   <td>'.$row['qty'].'</td>
						   <td>'.$row['update_qty'].'</td>
						   <td>'.$row['balance_qty'].'</td>
						</tr>'; 
						}
				   $output.=' </tbody>
				</table>';		
		echo $output;
	    return;
  		break;
				
    default:
        break;
}


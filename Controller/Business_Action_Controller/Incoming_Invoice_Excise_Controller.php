<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Invoice_Excise_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
include_once("../../Model/DBModel/SalseTaxModel.php");
include_once( "../../Model/Param/param_model.php");
include_once("../../log4php/Logger.php");

//Logger::configure("../../config.xml");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){ //log file settion for gurgaon due to run all instance from single set of code
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){ //log file settion for rudrapur due to run all instance from single set of code
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){ //log file settion for manesar due to run all instance from single set of code
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){ //log file settion for haridwar due to run all instance from single set of code
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('INVEX_controller');

$Type = $_REQUEST['TYP'];
	
if($Type == null)
    $Type = QueryModel::PAGELOAD;
  session_start();
switch($Type){
    case QueryModel::INSERT:
		$Data = $_REQUEST['INCOMING_INVOICE_EXCISE_DATA'];
		$Data = str_replace('\\','', $Data); 
		$logger->info($Data);	// to create info type log file
        $obj = json_decode($Data);
		$curentFinYear=ParamModel::getFinYear();
        $Print = 0;
		$USERID=$_SESSION["USER"];
        $logger->debug($curentFinYear);	// to create debug type log file
		$Print=Incoming_Invoice_Excise_Model::Insert_Incoming_Invoice_Excise($obj->{'_mode_delivery'}, $obj->{'_vehcle_no'}, $obj->{'_dnt_supply'}, $obj->{'_supply_place'}, $obj->{'_reverse_charge_payable'}, $obj->{'_principal_inv_no'}, $obj->{'_principal_inv_date'}, $obj->{'_principal_Id'}, $obj->{'_principal_gstin'}, $obj->{'_supplier_inv_no'}, $obj->{'_supplier_inv_date'}, $obj->{'_supplier_Id'}, $obj->{'_supplier_gstin'}, $obj->{'_pf_chrg'}, $obj->{'_insurance'}, $obj->{'_freight'}, $obj->{'_total_bill_val'}, $obj->{'_rece_date'}, $obj->{'ms'}, $obj->{'_remarks'},  $USERID);
		$logger->debug($Print );	// to create debug type log file
		Incoming_Invoice_Excise_Model::INSERT_DISPLAY_ENTRY_MAPING($Print);
        if($obj->{'_supplier_Id'}>0 && $obj->{'_supplier_inv_no'}!="")
        {
            $tranId=Transaction_Model::InsertTransaction($Print,"IE",date("Y-m-d"),$obj->{'_supplier_Id'});

        }
        else
        {
            $tranId=Transaction_Model::InsertTransaction($Print,"IE",date("Y-m-d"),$obj->{'_principal_Id'});
        }
        $i = 0;
		$logger->debug($tranId );	// to create debug type log file
        while($i < sizeof($obj->{'_items'}))
        {
            $princQty = $obj->_items[$i]->_principal_qty;
            $SupplierQty = $obj->_items[$i]->_supplier_qty;
            if($princQty < 0 || $princQty == ""){
               $princQty = 0;
            }else if($SupplierQty < 0 || $SupplierQty == ""){
               $SupplierQty = 0;
            }
            $_entryDId = 0;
            if($Print > 0)
            {
                //$_entryDId = Incoming_Invoice_Excise_Model_Details::Insert_Incoming_Invoice_Excise_Details($Print,$obj->_items[$i]->_item_id,$obj->_items[$i]->_item_id,$princQty,$obj->_items[$i]->_itemID_unitid,$SupplierQty,$obj->_items[$i]->_itemID_unitid,$obj->_items[$i]->_expire_date,$obj->_items[$i]->_batch_number,$obj->_items[$i]->_basic_purchase_price, $obj->_items[$i]->_total, $obj->_items[$i]->_discount, $obj->_items[$i]->_taxable_total, $obj->_items[$i]->_cgst_rate, $obj->_items[$i]->_cgst_amt, $obj->_items[$i]->_sgst_rate, $obj->_items[$i]->_sgst_amt, $obj->_items[$i]->_igst_rate, $obj->_items[$i]->_igst_amt, $obj->_items[$i]->_landing_price, $obj->_items[$i]->_total_landing_price);
				
				$_entryDId = Incoming_Invoice_Excise_Model_Details::Insert_Incoming_Invoice_Excise_Details($Print,$obj->_items[$i]->_item_id,$obj->_items[$i]->_item_id,$princQty,$obj->_items[$i]->_itemID_unitid,$SupplierQty,$obj->_items[$i]->_itemID_unitid,$obj->_items[$i]->_expire_date,$obj->_items[$i]->_batch_number,$obj->_items[$i]->_basic_purchase_price, $obj->_items[$i]->_total, $obj->_items[$i]->_discount, $obj->_items[$i]->_discounted_amt, $obj->_items[$i]->_packing_percent, $obj->_items[$i]->_packing_amt, $obj->_items[$i]->_insurance_percent, $obj->_items[$i]->_insurance_amt, $obj->_items[$i]->_freight_percent, $obj->_items[$i]->_freight_amt, $obj->_items[$i]->_other_percent, $obj->_items[$i]->_other_amt, $obj->_items[$i]->_taxable_total, $obj->_items[$i]->_cgst_rate, $obj->_items[$i]->_cgst_amt, $obj->_items[$i]->_sgst_rate, $obj->_items[$i]->_sgst_amt, $obj->_items[$i]->_igst_rate, $obj->_items[$i]->_igst_amt, $obj->_items[$i]->_landing_price, $obj->_items[$i]->_total_landing_price);
				$logger->debug($_entryDId );	// to create debug type log file
            }
            if($tranId>0)
            {
            	if($obj->{'_supplier_Id'}>0 && $obj->{'_supplier_inv_no'}!="")
                {
					// Change by Rajiv On 12-8-15 for stocks Testing
					//  $Balance_qty = 	Inventory_Model::UpdateInventory("E",$obj->_items[$i]->_item_id,$SupplierQty,"A");
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear);
				
                    TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"EX",$SupplierQty,"c",$Balance_qty);
                    Incoming_Inventory_Model::Insert_Incoming_Inventory($_entryDId,$obj->_items[$i]->_item_id,$Print,date("Y-m-d"),"E",$obj->{'_principal_Id'},$obj->{'_supplier_Id'},$SupplierQty,0);
					
					$logger->debug($Balance_qty );	// to create debug type log file
                }
                else
                {
					// Change by Rajiv On 12-8-15 for stocks Testing
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear);
					$logger->debug($Balance_qty );	// to create debug type log file
                    //$Balance_qty = Inventory_Model::UpdateInventory("E",$obj->_items[$i]->_item_id,$princQty,"A");
                    TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"EX",$princQty,"c",$Balance_qty);
                    Incoming_Inventory_Model::Insert_Incoming_Inventory($_entryDId,$obj->_items[$i]->_item_id,$Print,date("Y-m-d"),"E",$obj->{'_principal_Id'},$obj->{'_supplier_Id'},$princQty,0);					
					
                }
            }
            $i++;
        }
        echo 'Success';
        return;
        break;
    case QueryModel::SELECT:
        $EntryId = $_REQUEST['IncomingInvoiceExciseNum'];
		$logger->debug($EntryId );	// to create debug type log file
        $Print = Incoming_Invoice_Excise_Model::LoadIncomingInvoiceExcise($EntryId);
        echo json_encode($Print); 
        return;
        break;
    case QueryModel::UPDATE: 
	 
        $Data = $_REQUEST['INCOMING_INVOICE_EXCISE_DATA'];
		$Data = str_replace('\\','', $Data);
		$logger->info($Data);	// to create debug type log file
        $obj = json_decode($Data);
        $curentFinYear=ParamModel::getFinYear();
		
        $Print = 0;
        $USERID=$_SESSION["USER"];
        
		$logger->debug($curentFinYear);	// to create debug type log file
		//$TXNDATA = Transaction_Model::LoadTransactionByRefID($obj->{'_entry_Id'},"IE");
        $resultArr=Incoming_Invoice_Excise_Model_Details::GetIncomingInvoiceExciseInfo($obj->{'_entry_Id'});
		$i = 0;
		$logger->debug(json_decode($resultArr));	// to create debug type log file
		for($i=0;$i<count($resultArr);$i++)
		{
			$codePartNo=$resultArr[$i]['codePartNo'];
			$preInputQty=$resultArr[$i]['qty'];
			$entryDId=$resultArr[$i]['iinv_no'];
			$reversedQty = Inventory_Model::UpdateInventory("E",$codePartNo,$preInputQty,"S");
            $print=Incoming_Inventory_Model::DeleteIncomingInventoryByEntryDId($entryDId);
            Incoming_Inventory_Model::Update_Incoming_Inventory($entryDId,"E",$preInputQty,"S");
           	$logger->debug($reversedQty);	// to create debug type log file
        }
		 //var_dump($obj);
		 //exit(0);
        Incoming_Invoice_Excise_Model_Details::DeleteItem($obj->{'_entry_Id'});
        //$Print =Incoming_Invoice_Excise_Model::update_Incoming_Invoice_Excise($obj->{'_entry_Id'},$obj->{'_vehcle_no'}, $obj->{'_mode_delivery'}, $obj->{'_principal_inv_no'},$obj->{'_principal_inv_date'},$obj->{'_principal_Id'},$obj->{'_supplier_inv_no'},$obj->{'_supplier_inv_date'},$obj->{'_supplier_Id'},$obj->{'_pf_chrg'},$obj->{'_insurance'},$obj->{'_freight'},$obj->{'_sale_Tax'},$obj->{'SaleTaxAmount'},$obj->{'_total_bill_val'},$obj->{'_rece_date'},$obj->{'_remarks'},$USERID,$obj->{'ms'});
		$Print=Incoming_Invoice_Excise_Model::update_Incoming_Invoice_Excise($obj->{'_entry_Id'}, $obj->{'_mode_delivery'}, $obj->{'_vehcle_no'}, $obj->{'_dnt_supply'}, $obj->{'_supply_place'}, $obj->{'_reverse_charge_payable'}, $obj->{'_principal_inv_no'}, $obj->{'_principal_inv_date'}, $obj->{'_principal_Id'}, $obj->{'_principal_gstin'}, $obj->{'_supplier_inv_no'}, $obj->{'_supplier_inv_date'}, $obj->{'_supplier_Id'}, $obj->{'_supplier_gstin'}, $obj->{'_pf_chrg'}, $obj->{'_insurance'}, $obj->{'_freight'}, $obj->{'_total_bill_val'}, $obj->{'_rece_date'}, $obj->{'ms'}, $obj->{'_remarks'},  $USERID);
		
		
		
       	$logger->debug($Print);	// to create debug type log file
	  if($obj->{'_supplier_Id'}>0 && $obj->{'_supplier_inv_no'}!=""){
            $tranId=Transaction_Model::InsertTransaction($obj->{'_entry_Id'},"IE",date("Y-m-d"),$obj->{'_supplier_Id'},"UPDATE");
        }else{
			$tranId=Transaction_Model::InsertTransaction($obj->{'_entry_Id'},"IE",date("Y-m-d"),$obj->{'_principal_Id'},"UPDATE");
        }	
		$logger->debug($tranId);	// to create debug type log file
        $i = 0;
        while($i < sizeof($obj->{'_items'}))
        {
            $princQty = $obj->_items[$i]->_principal_qty;
            $SupplierQty = $obj->_items[$i]->_supplier_qty;
            if($princQty < 0 || $princQty == "")
            {
               $princQty = 0;
            }
            else if($SupplierQty < 0 || $SupplierQty == "")
            {
               $SupplierQty = 0;
            }
            $_entryDId = 0;

            //$_entryDId = Incoming_Invoice_Excise_Model_Details::Insert_Incoming_Invoice_Excise_Details($obj->{'_entry_Id'},$obj->_items[$i]->_item_id,$obj->_items[$i]->_item_id,$princQty,$obj->_items[$i]->_itemID_unitid,$SupplierQty,$obj->_items[$i]->_itemID_unitid,$obj->_items[$i]->_total_ass_value,$obj->_items[$i]->_ed_percent,$obj->_items[$i]->_ed_amount,$obj->_items[$i]->_ed_unit,$obj->_items[$i]->_edu_cess_percent,$obj->_items[$i]->_edu_cess_amount,$obj->_items[$i]->_hedu_percent,$obj->_items[$i]->_hedu_amount,$obj->_items[$i]->_cvd_percent,$obj->_items[$i]->_cvd_amount,$obj->_items[$i]->_expire_date,$obj->_items[$i]->_batch_number,$obj->_items[$i]->_supplier_rg23d,$obj->_items[$i]->_basic_purchase_price,$obj->_items[$i]->_landing_price,$obj->_items[$i]->_total_landing_price);
			$logger->debug($_entryDId);	// to create debug type log file
			
			//$_entryDId = Incoming_Invoice_Excise_Model_Details::Insert_Incoming_Invoice_Excise_Details($obj->{'_entry_Id'},$obj->_items[$i]->_item_id,$obj->_items[$i]->_item_id,$princQty,$obj->_items[$i]->_itemID_unitid,$SupplierQty,$obj->_items[$i]->_itemID_unitid,$obj->_items[$i]->_expire_date,$obj->_items[$i]->_batch_number,$obj->_items[$i]->_basic_purchase_price, $obj->_items[$i]->_total, $obj->_items[$i]->_discount, $obj->_items[$i]->_taxable_total, $obj->_items[$i]->_cgst_rate, $obj->_items[$i]->_cgst_amt, $obj->_items[$i]->_sgst_rate, $obj->_items[$i]->_sgst_amt, $obj->_items[$i]->_igst_rate, $obj->_items[$i]->_igst_amt, $obj->_items[$i]->_landing_price, $obj->_items[$i]->_total_landing_price);
			$_entryDId = Incoming_Invoice_Excise_Model_Details::Insert_Incoming_Invoice_Excise_Details($obj->{'_entry_Id'},$obj->_items[$i]->_item_id,$obj->_items[$i]->_item_id,$princQty,$obj->_items[$i]->_itemID_unitid,$SupplierQty,$obj->_items[$i]->_itemID_unitid,$obj->_items[$i]->_expire_date,$obj->_items[$i]->_batch_number,$obj->_items[$i]->_basic_purchase_price, $obj->_items[$i]->_total, $obj->_items[$i]->_discount, $obj->_items[$i]->_discounted_amt, $obj->_items[$i]->_packing_percent, $obj->_items[$i]->_packing_amt, $obj->_items[$i]->_insurance_percent, $obj->_items[$i]->_insurance_amt, $obj->_items[$i]->_freight_percent, $obj->_items[$i]->_freight_amt, $obj->_items[$i]->_other_percent, $obj->_items[$i]->_other_amt, $obj->_items[$i]->_taxable_total, $obj->_items[$i]->_cgst_rate, $obj->_items[$i]->_cgst_amt, $obj->_items[$i]->_sgst_rate, $obj->_items[$i]->_sgst_amt, $obj->_items[$i]->_igst_rate, $obj->_items[$i]->_igst_amt, $obj->_items[$i]->_landing_price, $obj->_items[$i]->_total_landing_price);
			$logger->debug($_entryDId );
	       
            if(true)
            {
			
			
              if($obj->{'_supplier_Id'}>0 && $obj->{'_supplier_inv_no'}!="")
                {
                    Incoming_Inventory_Model::Insert_Incoming_Inventory($_entryDId,$obj->_items[$i]->_item_id,$obj->{'_entry_Id'},date("Y-m-d"),"E",$obj->{'_principal_Id'},$obj->{'_supplier_Id'},$SupplierQty,0);
                    $Balanceqty =Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear);
					$logger->debug($Balanceqty);	// to create debug type log file
					$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"EX",$SupplierQty,"c",$Balanceqty,"UPDATE");
                   	$logger->debug($trxndid);	// to create debug type log file
                }
                else
                {
                    Incoming_Inventory_Model::Insert_Incoming_Inventory($_entryDId,$obj->_items[$i]->_item_id,$obj->{'_entry_Id'},date("Y-m-d"),"E",$obj->{'_principal_Id'},$obj->{'_supplier_Id'},$princQty,0);
                    $Balanceqty =Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear);
                    TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"EX",$princQty,"c",$Balanceqty,"UPDATE");
                 	$logger->debug($Balanceqty);	// to create debug type log file
                }
            }
            $i++;
        }

        echo json_encode($Print);
        return;
        break;
    case "AUTOCODE":
        $Print = Incoming_Invoice_Excise_Model::GetLastInvoiceNumber();
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "CODEPARTNO_EXCISE":
        $pid= $_REQUEST['PRINCIPALID'];
        $Print = Incoming_Invoice_Excise_Model_Details::getCodePartNo($pid);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GETTAXDETAILS":
        $Print = SalseTaxModel::LoadSalseTax($_REQUEST['TAXID']);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case QueryModel::SEARCH:
        Search($_REQUEST['YEAR'],$_REQUEST['val1'],$_REQUEST['val2'],$_REQUEST['val3'],$_REQUEST['val4']);
        return;
        break;
    case "FIND_INVOIC_IN_OGINV_ST":
        $Print = Incoming_Invoice_Excise_Model::FIND_INVOIC_IN_OGINV_ST($_REQUEST['INVOICENO']);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
     case "VINO":
        $buyerId = $_REQUEST['BUYERID'];
        $invno= $_REQUEST['INVNO'];
        $Print = Incoming_Invoice_Excise_Model::validateInvoice($buyerId,$invno);
        echo json_encode($Print);
        return;
        break;      
    case "IncomingDetailsForPrint":
        $Print = Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetailsbycodepart($_REQUEST['INVOICENO'],$_REQUEST['CODEPART']);
        echo json_encode($Print);
        return;
        break;
    case "GET_INV_NUM_BY_DISPLAY":
        $Print = Incoming_Invoice_Excise_Model::GET_INV_NUM_BY_DISPLAY($_REQUEST['DISPLAYID'],$_REQUEST['YEAR']);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
	/* BOF to add GST details by Ayush Giri on 21-06-2017 */
	case "INCOMING_PRINCIPAL_GST":
        $_principal_id = $_REQUEST['PRINCIPALID'];
        $result = Incoming_Invoice_GST_Model::Load_Principal_Supplier_Incoming_GST($_principal_id,"P");
		//echo 'result<pre>'; print_r($result); echo '<pre>';
        echo json_encode($result);
        return;
        break;
	case "INCOMING_SUPPLIER_GST":
        $_supplier_id = $_REQUEST['SUPPLIERID'];
        $result = Incoming_Invoice_GST_Model::Load_Principal_Supplier_Incoming_GST($_supplier_id,"S");
		//echo 'result<pre>'; print_r($result); echo '<pre>';
        echo json_encode($result);
        return;
        break;
	case "GETGST":
		$itemId = isset($_REQUEST['ITEMID'])?$_REQUEST['ITEMID']:'';
		$principal_id = isset($_REQUEST['PRINCIPAL_ID'])?$_REQUEST['PRINCIPAL_ID']:'';
		$supplier_ID = isset($_REQUEST['SUPPLIER_ID'])?$_REQUEST['SUPPLIER_ID']:'';
		$buyer_ID = isset($_REQUEST['BUYER_ID'])?$_REQUEST['BUYER_ID']:'';
		$Print = Incoming_Invoice_GST_Model::GetItemGST($itemId, $principal_id, $supplier_ID);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
	/* EOF to add GST details for Principal by Ayush Giri on 21-06-2017 */
    default:
        break;
}

function Pageload(){
    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = Incoming_Invoice_Excise_Model::LoadIncomingInvoiceExcise(0,$start,$rp);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                '_entry_Id'       => $row->_entry_Id,
                'prici_inv_no'       => $row->_principal_inv_no,
                'supp_inv_no'       => $row->_supplier_inv_no,
                '_principal_name'     => $row->_principal_name,
                '_supplier_name'     => $row->_supplier_name,
                '_total_bill_val'       => $row->_total_bill_val,
                '_insert_Date'     => $row->_insert_Date
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("incominginvoice_excise","");
    echo json_encode($jsonData);
}
function Search($year,$val1,$val2,$val3,$val4){
    //session_start();
	
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
	
	//$rows = Incoming_Invoice_Excise_Model::SearchIncomingInvoiceExcise($year,$val1,$val2,$val3,$val4,$start,$rp,$count);
	//change on 21-10-2015
   $rows = Incoming_Invoice_Excise_Model::SearchIncomingInvoiceExciseNew($year,$val1,$val2,$val3,$val4,$start,$rp,$count);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                '_entry_Id'       => $row->_entry_Id,
                'prici_inv_no'       => $row->_principal_inv_no,
                'supp_inv_no'       => $row->_supplier_inv_no,
                '_principal_name'     => $row->_principal_name,
                '_supplier_name'     => $row->_supplier_name,
                '_total_bill_val'       => $row->_total_bill_val,
                '_insert_Date'     => $row->_principal_inv_date,
                '_rece_date'     => $row->_rece_date
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = $count;
    echo json_encode($jsonData);
}

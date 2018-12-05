<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/DBModel/SalseTaxModel.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
include_once( "../../Model/Business_Action_Model/pos_model.php");
include_once( "../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Invoice_Excise_Model.php");
include_once( "../../Model/Business_Action_Model/ma_model.php");
include_once( "../../Model/Param/param_model.php");
include_once("../Param/Param.php");
include_once( "../../Model/Masters/Event.php");
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
$logger = Logger::getLogger('OIE_controller');

$Type = $_REQUEST['TYP'];

if($Type == QueryModel::INSERT || $Type == QueryModel::UPDATE)
{
	session_start();
}
else
{
	ob_start();
}

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['OUTGOING_INVOICE_EXCISE_DATA'];
        $logger->debug($Data);	// to create debug type log file
        $Data = str_replace('\\','', $Data);
		$obj = json_decode($Data);
		//echo '<pre>'; print_r($obj); echo '</pre>';exit;
		$curentFinYear=ParamModel::getFinYear();
		$logger->debug($curentFinYear);	// to create debug type log file
        $bpoType=$obj->{'pot'};
        $Print = 0;
        $USERID = $_SESSION["USER"];
		
		// check PO credit period for management approvel		
		// management approval
        $automatic_MA="N";
        $aRemarks="";
	    $res =  Managementapproval_Model::checkBuyerNewExist_UsingInv($obj->{'BuyerID'});		
	    $rw=mysql_fetch_array($res, MYSQL_ASSOC);
	    $total=$rw['total'];
	    //comment by gajendra
	    //~ $bill_value = Outgoing_Invoice_Excise_Model::ValidateOutgoingInvoiceExciseDataCalculation($Data);
	    //~ if($bill_value == 0){
			//~ echo "WRONG_CALULATION";
			//~ return;
			//~ exit;
		//~ }
	    if($total>0){
			
		}else{
		// function to check approved status 
		$res =  Managementapproval_Model::checkPoApprovalStatus($obj->{'poid'});	
		if($res == 0){
			$logger->info($total);	// to create debug type log file
			$res = Managementapproval_Model::getPoCreditPeriod($obj->{'poid'});
			$date = Date('Y-m-d');
			$days = ParamModel::getDaysBetweenDate($res['bpoDate'],$date); 
				
			if($days > ($res['credit_period'] + 10)){
			 $automatic_MA="Y";
			 $aRemarks=$aRemarks."PO credit period Exceeded"."\n";
			}	
			$logger->info($aRemarks);	// to create debug type log file			
				if($aRemarks!=""){				
					 $Print1 = Managementapproval_Model::InsertMA($obj->{'poid'},$aRemarks,$USERID);
					 $logger->info($Print1);	// to create debug type log file
					if($Print1 != "NO"){
					   $Print1 = Managementapproval_Model::UpdatePO_BYMA_NEW($obj->{'poid'},true);
					}
					echo "CREDIT_TIME_ERROR";
					return;
					break;         
				}
			
			}
		}
		$obj->{'oinvoice_No'} = Outgoing_Invoice_Excise_Model::GetLastInvoiceNumber($obj->{'BuyerID'});
		//echo 'oinvoice_No ---> '.$obj->{'oinvoice_No'};exit;
		$Print = 
		//Outgoing_Invoice_Excise_Model::InsertOutgoingInvoiceExcise($obj->{'oinvoice_No'}, $obj->{'poid'}, $obj->{'BuyerID'},$obj->{'principalID'},$obj->{'supplierID'},$obj->{'mode_delivery'},$obj->{'oinv_date'},$obj->{'oinv_time'},$obj->{'poid'},$obj->{'total_discount'},$obj->{'ed_tag'},$obj->{'freight_percent'},$obj->{'freight_amount'}, $obj->{'pf_chrg'},$obj->{'incidental_chrg'},$obj->{'bill_value'},"P",$obj->{'remarks'},$USERID,$obj->{'ms'},$obj->{'ins_charge'},$obj->{'othc_charge'}, $obj->{'_supply_place'}, $obj->{'_reverse_charge_payable'}, $obj->{'_dnt_supply'}, $obj->{'_docket_no'});
		Outgoing_Invoice_Excise_Model::InsertOutgoingInvoiceExcise($obj->{'oinvoice_No'}, $obj->{'poid'}, $obj->{'BuyerID'},$obj->{'principalID'},$obj->{'supplierID'},$obj->{'mode_delivery'},$obj->{'oinv_date'},$obj->{'oinv_time'},$obj->{'poid'},$obj->{'total_discount'},$obj->{'ed_tag'},$obj->{'freight_percent'},$obj->{'freight_amount'}, $obj->{'pf_chrg'},$obj->{'incidental_chrg'},$obj->{'bill_value'},"P",$obj->{'remarks'},$USERID,$obj->{'ms'},$obj->{'ins_charge'},$obj->{'othc_charge'}, $obj->{'_supply_place'}, $obj->{'_reverse_charge_payable'}, $obj->{'_dnt_supply'}, $obj->{'_docket_no'}, $obj->{'p_f_gst_rate'}, $obj->{'p_f_gst_amount'}, $obj->{'inc_gst_rate'}, $obj->{'inc_gst_amount'}, $obj->{'ins_gst_rate'}, $obj->{'ins_gst_amount'}, $obj->{'othc_gst_rate'}, $obj->{'othc_gst_amount'}, $obj->{'freight_gst_rate'}, $obj->{'freight_gst_amount'});
        $logger->debug($Print);	// to create debug type log file
		// insert po details in event table to send the email
		//$invoice_ack = Event::addEvent(EVENT_MAIL_TYPE,EVENT_INVOICE_OE,$Data,$Print);
		//echo '<br/>invoice_ack ---> '.$invoice_ack;
		
		if($Print == 0)
        {
			
        }
        else
        {
            Outgoing_Invoice_Excise_Model::INSERT_OIV_DISPLAY_ENTRY_MAPING($Print,$obj->{'oinvoice_No'});
            if($obj->{'supplierID'}>0)
            {
               $tranId=Transaction_Model::InsertTransaction($Print,"OE",date("Y-m-d"),$obj->{'supplierID'});
            }
            else
            {
               $tranId=Transaction_Model::InsertTransaction($Print,"OE",date("Y-m-d"),$obj->{'principalID'});
            }
			//echo '<br/>tranId ---> '.$tranId;exit;
			 $logger->debug($tranId);	// to create debug type log file
            $i = 0;
            while($i < sizeof($obj->{'_items'}))
            {
               //comment by gajendra
               //~ Outgoing_Invoice_Excise_Model_Details::InsertOutgoingInvoiceExciseDetails($Print,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,$obj->_items[$i]->ordered_qty,$obj->_items[$i]->entryDId,$obj->_items[$i]->issued_qty,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->ed_percent,$obj->_items[$i]->ed_amt,$obj->_items[$i]->ed_perUnit,$obj->_items[$i]->entryId,$obj->_items[$i]->edu_percent,$obj->_items[$i]->edu_amt,$obj->_items[$i]->hedu_percent,$obj->_items[$i]->hedu_amount,$obj->_items[$i]->cvd_percent,$obj->_items[$i]->cvd_amt);
               
               $outgoing_detail_id = Outgoing_Invoice_Excise_Model_Details::InsertOutgoingInvoiceExciseDetails($Print,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,$obj->_items[$i]->ordered_qty,$obj->_items[$i]->entryDId,$obj->_items[$i]->issued_qty,$obj->_items[$i]->item_discount,$obj->_items[$i]->item_taxable_total,$obj->_items[$i]->hsn_code,$obj->_items[$i]->entryId,$obj->_items[$i]->cgst_rate,$obj->_items[$i]->sgst_rate,$obj->_items[$i]->igst_rate,$obj->_items[$i]->cgst_amt,$obj->_items[$i]->sgst_amt,$obj->_items[$i]->igst_amt,$obj->_items[$i]->tot_price,$obj->_items[$i]->oinv_price);
			   
			   if($outgoing_detail_id == QueryResponse::ERROR)
			   {
				   Outgoing_Invoice_Excise_Model::DeleteOutgoingInvoiceExcise($Print, $tranId, $invoice_ack);
				   echo 'MYSQL_ERROR';
				   return;
			   }
                Outgoing_Invoice_Excise_Model::UpdatePOItemStage("OIG",$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,'E',$bpoType);
				
               if($tranId>0)
               {
                   //$Balanceqty = Inventory_Model::UpdateInventory("E",$obj->_items[$i]->_item_id,$obj->_items[$i]->issued_qty,"S");
				   $Balanceqty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear);
				   $logger->debug($Balanceqty);	// to create debug type log file
                   $trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"OE",$obj->_items[$i]->issued_qty,"d",$Balanceqty);
				   $logger->debug($trxndid);	// to create debug type log file
                   Incoming_Inventory_Model::Update_Incoming_Inventory($obj->_items[$i]->entryDId,"E",$obj->_items[$i]->issued_qty,"S");
               }
               $i++;
            }
        }
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        //echo " function start  ".date("Y-m-d h:i:sa");
        $oinvoice_exciseID = $_REQUEST['OutgoingInvoiceExciseNum'];
        //$Print = Outgoing_Invoice_Excise_Model::GetOutgoingInvoiceExciseDetails($oinvoice_exciseID);
        $Print = Outgoing_Invoice_Excise_Model::LoadOutgoingInvoiceExcise($oinvoice_exciseID);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
       
        return;
        break;
    case QueryModel::UPDATE:
        $USERID = $_SESSION["USER"];
		$curentFinYear=ParamModel::getFinYear();
        $logger->debug($Print);	// to create debug type log file
        $Data = $_REQUEST['OUTGOING_INVOICE_EXCISE_DATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
         $bpoType=$obj->{'pot'};
		 // print_r($obj);exit;  
        //$TXNDATA = Transaction_Model::LoadTransactionByRefID($obj->{'oinvoice_exciseID'},"OE");
        //TransactionDetails_Model::DeleteTransactionDetails($TXNDATA[0]->_transactionId);
        //Transaction_Model::DeleteTransaction($TXNDATA[0]->_transactionId);
		//7532950949
        $resultArr=Outgoing_Invoice_Excise_Model_Details::GetOutgoingInvoiceExciseInfo($obj->{'oinvoice_exciseID'});
		$logger->debug($resultArr);	// to create debug type log file
		for($i=0;$i<count($resultArr);$i++)
		{
			$codePartNo=$resultArr[$i]['codePartNo'];
			$preIssuedQty=$resultArr[$i]['issued_qty'];
			$entryDId=$resultArr[$i]['iinv_no'];
			$bpoDId=$resultArr[$i]['bpoDId'];
			$bpoType=$resultArr[$i]['bpoType'];
			$oinvoice_exciseDID=$resultArr[$i]['oinvoice_exciseDID'];
			$reversedQty = Inventory_Model::UpdateInventory("E",$codePartNo,$preIssuedQty,"A");
			$logger->debug($reversedQty);	// to create debug type log file
            Incoming_Inventory_Model::Update_Incoming_Inventory($entryDId,"E",$preIssuedQty,"A");
            Outgoing_Invoice_Excise_Model_Details::DeleteItem($oinvoice_exciseDID);
            Outgoing_Invoice_Excise_Model::UpdatePOItemStage("YDE",$bpoDId,$codePartNo,'E',$bpoType);
         
        }
           
        $Print = 0;
         //print_r($obj);exit;
        $Print = Outgoing_Invoice_Excise_Model::UpdateOutgoingInvoiceExcise($obj->{'oinvoice_exciseID'},$obj->{'oinvoice_No'}, $obj->{'poid'}, $obj->{'BuyerID'},$obj->{'principalID'},$obj->{'supplierID'},$obj->{'mode_delivery'},$obj->{'oinv_date'},$obj->{'oinv_time'},$obj->{'poid'},$obj->{'total_discount'},$obj->{'ed_tag'},$obj->{'freight_percent'},$obj->{'freight_amount'},$obj->{'pf_chrg'},$obj->{'incidental_chrg'},$obj->{'bill_value'},"P",$obj->{'remarks'},$USERID,$obj->{'ms'},$obj->{'ins_charge'},$obj->{'othc_charge'}, $obj->{'_supply_place'}, $obj->{'_reverse_charge_payable'}, $obj->{'_dnt_supply'}, $obj->{'_docket_no'});
        $logger->debug($Print);	// to create debug type log file
        
        

        if($obj->{'supplierID'}>0)
        {
           
            $tranId=Transaction_Model::InsertTransaction($obj->{'oinvoice_exciseID'},"OE",date("Y-m-d"),$obj->{'supplierID'},"UPDATE");
        }
        else
        {
           
            $tranId=Transaction_Model::InsertTransaction($obj->{'oinvoice_exciseID'},"OE",date("Y-m-d"),$obj->{'principalID'},"UPDATE");
        }
		 $logger->debug($tranId);	// to create debug type log file
        
        $i = 0;

        while($i < sizeof($obj->{'_items'}))
        {
            //$QueryResponse=Outgoing_Invoice_Excise_Model_Details::InsertOutgoingInvoiceExciseDetails($obj->{'oinvoice_No'},$obj->{'principalID'},$obj->{'principalID'},$obj->_items[$i]->_item_id,$obj->_items[$i]->ordered_qty,$obj->_items[$i]->entryId,$obj->_items[$i]->issued_qty,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->ed_percent,$obj->_items[$i]->ed_amt,$obj->_items[$i]->ed_perUnit,$obj->_items[$i]->entryId,$obj->_items[$i]->edu_percent,$obj->_items[$i]->edu_amt,$obj->_items[$i]->hedu_percent,$obj->_items[$i]->hedu_amount,$obj->_items[$i]->cvd_percent,$obj->_items[$i]->cvd_amt);
            $QueryResponse=Outgoing_Invoice_Excise_Model_Details::InsertOutgoingInvoiceExciseDetails($obj->{'oinvoice_exciseID'},$obj->_items[$i]->bpod_Id,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,$obj->_items[$i]->ordered_qty,$obj->_items[$i]->entryDId,$obj->_items[$i]->issued_qty,$obj->_items[$i]->item_discount,$obj->_items[$i]->item_taxable_total,$obj->_items[$i]->hsn_code,$obj->_items[$i]->entryId,$obj->_items[$i]->cgst_rate,$obj->_items[$i]->sgst_rate,$obj->_items[$i]->igst_rate,$obj->_items[$i]->cgst_amt,$obj->_items[$i]->sgst_amt,$obj->_items[$i]->igst_amt,$obj->_items[$i]->tot_price,$obj->_items[$i]->oinv_price);
            $logger->debug($tranId);	// to create debug type log file
         
         
               $QueryResponse1=Outgoing_Invoice_Excise_Model::UpdatePOItemStage("OIG",$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,'E',$bpoType);
               $logger->debug($QueryResponse1);	// to create debug type log file
            if($QueryResponse1=="SUCCESS")
            {               
                //$Balanceqty = Inventory_Model::UpdateInventory("E",$obj->_items[$i]->_item_id,$obj->_items[$i]->issued_qty,"S");
				$Balanceqty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->_item_id,$curentFinYear); // Function update the quantity in inventory table of a codePartNo. 
				$logger->debug($Balanceqty);	// to create debug type log file
                $trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"OE",$obj->_items[$i]->issued_qty,"d",$Balanceqty,"UPDATE");
                $logger->debug($trxndid);	// to create debug type log file
                Incoming_Inventory_Model::Update_Incoming_Inventory($obj->_items[$i]->entryDId,"E",$obj->_items[$i]->issued_qty,"S");
             }
            $i++;
        }
        echo json_encode($obj->{'oinvoice_exciseID'});
        return;
        break;
    case "AUTOCODE":
        $Print = Outgoing_Invoice_Excise_Model::GetLastInvoiceNumber(); // use to invoice number auto generate but no longer use from here
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GET_PO_PRINCIPAL":
        //$Print = purchaseorder_Details_Model::LoadPrincipal($_REQUEST['PO_ID']);
        $Print = Outgoing_Invoice_Excise_Model::LoadPrincipal($_REQUEST['PO_ID'],$_REQUEST['po_ed_applicability']);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GETPODID":
        $Print = purchaseorder_Details_Model::LoadPODetailsByPrincipal($_REQUEST['PODID'],$_REQUEST['TAG'],$_REQUEST['PRINCIPALID'],$_REQUEST['BPOTYPE']);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GET_ABV_OUANTITY":
		$invoiveIds4Qty = 0;
		if(isset($_REQUEST['INVOICEDID'])){
			$invoiveIds4Qty = $_REQUEST['INVOICEDID'];
		}else if(isset($_REQUEST['INVOICEID'])){
			$invoiveIds4Qty = $_REQUEST['INVOICEID'];
		}
		
        $Print = Incoming_Inventory_Model::GetQuantity($invoiveIds4Qty,$_REQUEST['INVOICETYPE']);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GET_INVOICE_LIST":
        $Print = Incoming_Inventory_Model::LoadIncoming_InventoryForBilling($_REQUEST['ITEMID'],"E");
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GET_Recuring_List":
        $Print = purchaseorder_Schedule_Model::getSCHDetails($_REQUEST['POID'],$_REQUEST['PRINCIPALID'],$_REQUEST['po_ed_applicability']);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GET_INVOICE_DETAILS":
    
		$invoiveIds = 0;
		if(isset($_REQUEST['INVOICEDID'])){
			$invoiveIds = $_REQUEST['INVOICEDID'];
		}else if(isset($_REQUEST['INVOICEID'])){
			$invoiveIds = $_REQUEST['INVOICEID'];
		}
        //~ $Print = Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetailsbycodepart($invoiveIds);
        $Print = Incoming_Invoice_Excise_Model_Details::LoadIncomingInvoiceExciseDetailsbyIncomingInventoryId($invoiveIds);
		
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GET_SURCHARGE":
        $TaxId = Outgoing_Invoice_Excise_Model::GetTaxByPOID($_REQUEST['POID'],$_REQUEST['PRINCID'],$_REQUEST['ITEMID']);
        $Print = SalseTaxModel::GetSURCHARGETaxValue($TaxId);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case "GETPOLIST":
			if(isset($_REQUEST['PONUMBER']) && !empty($_REQUEST['PONUMBER'])){ 
				$Print = Outgoing_Invoice_Excise_Model::GetPurchaseOrderForBilling($_REQUEST['PONUMBER']);
			}else{
				$Print = Outgoing_Invoice_Excise_Model::GetPurchaseOrderForBilling();
			}
			//print_r($Print); exit;
			$logger->debug(json_encode($Print));	// to create debug type log file
			echo json_encode($Print);
        return;
        break;    
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
    case "GETTAXDETAILS":
        $Print = SalseTaxModel::LoadSalseTax($_REQUEST['TAXID']);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    case QueryModel::SEARCH:
		Search($_REQUEST['YEAR'],$_REQUEST['oinv'],$_REQUEST['Fromdate'],$_REQUEST['Todate'],$_REQUEST['Principalid'],$_REQUEST['Buyerid']);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "GET_OINV_NUM_BY_DISPLAY":
        $Print = Outgoing_Invoice_Excise_Model::GET_OINV_NUM_BY_DISPLAY($_REQUEST['DISPLAYID'],$_REQUEST['YEAR']);
        $logger->debug(json_encode($Print));	// to create debug type log file
		echo json_encode($Print);
        return;
        break;
    default:
        break;
}
function Pageload(){
    //session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	$year = isset($_REQUEST['YEAR']) ? $_REQUEST['YEAR'] : ParamModel::getFinYear();;
    $rows = Outgoing_Invoice_Excise_Model::LoadOutgoingInvoiceExcise(0,$start,$rp,$year );
   // header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'oinvoice_exciseID'       => $row->oinvoice_exciseID,
                'oinvoice_No'     => $row->oinvoice_No,
                'pono'     => $row->pono,
                'oinv_date'     => $row->oinv_date,
                'po_date'     => $row->po_date,
                'Buyer_Name'       => $row->Buyer_Name,
                'Principal_Name'     => $row->Principal_Name
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("outgoinginvoice_excise","");
    echo json_encode($jsonData);
}
function Search($year,$oinv,$Fromdate,$Todate,$Principalid,$Buyerid){

    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
    $rows = Outgoing_Invoice_Excise_Model::SearchOutgoingInvoiceExcise($year,$oinv,$Fromdate,$Todate,$Principalid,$Buyerid,$start,$rp,$count);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;    
    //print_r($rows); exit;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'oinvoice_exciseID'   => $row->oinvoice_exciseID,
                'oinvoice_No'     => $row->oinvoice_No,
                'pono'     => $row->pono,
                'oinv_date' => $row->oinv_date,
                'po_date'     => $row->po_date,
                'Buyer_Name'     => $row->Buyer_Name,
                'Principal_Name' => $row->Principal_Name
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = $count;
    echo json_encode($jsonData);
}
?>

<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_NonExcise_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Invoice_Excise_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
include_once( "../../Model/Business_Action_Model/pos_model.php");
include_once( "../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Outgoing_Inventory_Model.php");
include_once("../../Model/DBModel/SalseTaxModel.php");
include_once( "../../Model/Business_Action_Model/ma_model.php");
include_once( "../../Model/Param/param_model.php");
include_once( "../../Model/Masters/Event.php");
include_once("../Param/Param.php");
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

$logger = Logger::getLogger('OBIC');

$Type = $_REQUEST['TYP'];

if($Type == QueryModel::INSERT || $Type == QueryModel::UPDATE)
{
	session_start();
}
else
{
	ob_start();
}
$curentFinYear=ParamModel::getFinYear();
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['OUTGOING_BUNDLE_INVOICE_NONEXCISE_DATA'];
        $Data = str_replace('\\','', $Data);
        $logger->debug($Data);	// to create debug type log file
        $obj = json_decode($Data);
        $Print = 0;
		$USERID = $_SESSION["USER"];
        $bpoType=$obj->{'pot'};
		//check PO credit period for management approvel		
		// management approval
        $automatic_MA="N";
        $aRemarks="";
	    $res =  Managementapproval_Model::checkBuyerNewExist_UsingInv($obj->{'BuyerID'});		
	    $rw=mysql_fetch_array($res, MYSQL_ASSOC);
	    $total=$rw['total'];
		$logger->info($total);	// to create debug type log file
 		if($total>0){
			
		}else{
		// function to check approved status 
		$res =  Managementapproval_Model::checkPoApprovalStatus($obj->{'poid'});	
		$logger->info($res);	// to create debug type log file
		if($res == 0){
	
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
				   $Print1=Managementapproval_Model::UpdatePO_BYMA_NEW($obj->{'poid'},true);
				}
				echo "CREDIT_TIME_ERROR";
				return;
				break;         
			}
		}
	}
		
        $Print = Outgoing_Invoice_NonExcise_Model::InsertOutgoingInvoiceNonExcise("I",$obj->{'oinvoice_No'},$obj->{'oinv_date'}, $obj->{'poid'}, $obj->{'BuyerID'},$obj->{'principalID'},$obj->{'mode_delivery'},$obj->{'total_discount'},$obj->{'pf_chrg'},$obj->{'incidental_chrg'},$obj->{'freight_amount'},$obj->{'total_saleTax'},$obj->{'bill_value'},"P",$obj->{'remarks'},$USERID,$obj->{'ms'});
		$logger->debug($Print);	// to create debug type log file
		
		// insert po details in event table to send the email
		$invoice_ack = Event::addEvent(EVENT_MAIL_TYPE,EVENT_INVOICE_ONE,$Data,$Print);		
        if($Print == 0)
        {

        }else{		
			 Outgoing_Invoice_NonExcise_Model::INSERT_OIV_DISPLAY_ENTRY_MAPING($Print,$obj->{'oinvoice_No'});
			 $tranId=Transaction_Model::InsertTransaction($Print,"ONE",date("Y-m-d"),$obj->{'principalID'});
			
			 $logger->debug($tranId);	// to create debug type log file
			 $j = 0 ;
			 while($j < sizeof($obj->{'bundles'})){
			 
				$bundle_id =  Outgoing_Invoice_NonExcise_Model_Details::InsertBundleInvoiceDetails($Print,$obj->bundles[$j]->bpoId,$obj->bundles[$j]->bundle_id,$obj->bundles[$j]->ibglAcc,$obj->bundles[$j]->ibitem_desc,$obj->bundles[$j]->ibpo_qty,$obj->bundles[$j]->ibpo_qty,$obj->bundles[$j]->ibpo_unit,$obj->bundles[$j]->ibpo_price,$obj->bundles[$j]->ibpo_discount,$obj->bundles[$j]->ibpo_saleTax,$obj->bundles[$j]->ibpo_totVal,$USERID);
				$i=0;
				 while($i < sizeof($obj->bundles[$j]->items)){
							
							Outgoing_Invoice_NonExcise_Model_Details::InsertOutgoingInvoiceNonExciseDetails($Print,$obj->bundles[$j]->items[$i]->bpod_Id,$obj->bundles[$j]->items[$i]->bpod_Id,$obj->bundles[$j]->items[$i]->po_codePartNo,$obj->bundles[$j]->items[$i]->po_qty,$obj->bundles[$j]->items[$i]->issued_qty,$obj->bundles[$j]->items[$i]->bpod_Id,$bundle_id);
							
							 Outgoing_Invoice_Excise_Model::UpdatePOItemStage("OIG",$obj->bundles[$j]->items[$i]->bpod_Id,$obj->bundles[$j]->items[$i]->po_codePartNo,'N',$bpoType);
							if($tranId>0){
								$Balanceqty = Inventory_Model::UpdateInventory("N",$obj->bundles[$j]->items[$i]->po_codePartNo,$obj->bundles[$j]->items[$i]->issued_qty,"S");
								$logger->debug($Balanceqty);	// to create debug type log file
								$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->bundles[$j]->items[$i]->po_codePartNo,"ON",$obj->bundles[$j]->items[$i]->issued_qty,"d",$Balanceqty);
								$logger->debug($trxndid);	// to create debug type log file								
							}
				  $i++;
				}    
				$j++;	
			}          
        } 
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $oinvoice_exciseID = $_REQUEST['outgoing_bundle_invoice_nonexcise'];
		$logger->debug($oinvoice_exciseID);	// to create debug type log file
        $Print = Outgoing_Invoice_NonExcise_Model::LoadOutgoingBundleInvoiceNonExcise($oinvoice_exciseID);
		//print_r($Print); exit;
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case QueryModel::UPDATE:
		// UPdate Outgoing NOn-excise invoice.
        $USERID = $_SESSION["USER"];
        $Data = $_REQUEST['OUTGOING_INVOICE_NONEXCISE_DATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
		$logger->debug($Data);	// to create debug type log file
        $bpoType=$obj->{'pot'};
        $resultArr=Outgoing_Invoice_NonExcise_Model_Details::GetOutgoingInvoiceNonExciseInfo($obj->{'oinvoice_nexciseID'});
		$logger->debug(json_encode($resultArr));	// to create debug type log file
		
        $oldItemList=array();
		for($i=0;$i<count($resultArr);$i++)
		{

			$codePartNo=$resultArr[$i]['codePartNo'];
			$preIssuedQty=$resultArr[$i]['issued_qty'];
			$oinvoice_nexcisedID=$resultArr[$i]['oinvoice_nexcisedID'];
			$oinv_codePartNo=$resultArr[$i]['oinv_codePartNo'];
            $bpoType=$resultArr[$i]['bpoType'];
			$reversedQty = Inventory_Model::UpdateInventory("N",$codePartNo,$preIssuedQty,"A");
			Outgoing_Invoice_NonExcise_Model_Details::DeleteItem($oinvoice_nexcisedID);
            Outgoing_Invoice_Excise_Model::UpdatePOItemStage("YDE",$oinv_codePartNo,$codePartNo,'N',$bpoType);
			$logger->debug($reversedQty);	// to create debug type log file
        }
		
        $Print = 0;
        $Print = Outgoing_Invoice_NonExcise_Model::UpdateOutgoingInvoiceNonExcise($obj->{'oinvoice_nexciseID'},"I",$obj->{'oinvoice_No'},$obj->{'oinv_date'}, $obj->{'pono'}, $obj->{'BuyerID'},$obj->{'principalID'},$obj->{'mode_delivery'},$obj->{'total_discount'},$obj->{'pf_chrg'},$obj->{'incidental_chrg'},$obj->{'freight_amount'},$obj->{'total_saleTax'},$obj->{'bill_value'},"P",$obj->{'remarks'},$USERID,$obj->{'ms'});
        $logger->debug($Print);	// to create debug type log file
		$tranId=Transaction_Model::InsertTransaction($obj->{'oinvoice_nexciseID'},"ONE",date("Y-m-d"),$obj->{'principalID'},"UPDATE");
		$logger->debug($tranId);	// to create debug type log file
        $i = 0;
        while($i < sizeof($obj->{'_items'}))
        {
			
			$logger->debug($obj->_items[$i]->_item_id );	// to create debug type log file
            Outgoing_Invoice_NonExcise_Model_Details::InsertOutgoingInvoiceNonExciseDetails($obj->{'oinvoice_nexciseID'},$obj->_items[$i]->bpod_Id,$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,$obj->_items[$i]->ordered_qty,$obj->_items[$i]->issued_qty,$obj->_items[$i]->bpod_Id);
            Outgoing_Invoice_Excise_Model::UpdatePOItemStage("OIG",$obj->_items[$i]->bpod_Id,$obj->_items[$i]->_item_id,'N',$bpoType);

            if(true)
            {
                $Balanceqty = Inventory_Model::UpdateInventory("N",$obj->_items[$i]->_item_id,$obj->_items[$i]->issued_qty,"S");
				$logger->debug($Balanceqty);	// to create debug type log file
				$trxndid =  TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"ON",$obj->_items[$i]->issued_qty,"d",$Balanceqty,"UPDATE");				 
                //$Balanceqty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->_item_id,$curentFinYear);
				$logger->debug($trxndid);	// to create debug type log file
			}
            $i++;
        }
        echo json_encode($obj->{'oinvoice_nexciseID'});
        return;
        break;
    case "AUTOCODE":
        $Print = Outgoing_Invoice_NonExcise_Model::GetLastInvoiceNumber();
		$logger->debug($Print);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GET_PO_PRINCIPAL":
        $Print = purchaseorder_Details_Model::LoadPrincipal($_REQUEST['PO_ID'],$_REQUEST['po_ed_applicability']);
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
        $ITEMID=$_REQUEST['ITEMID'];
       // Inventory_Model::checkAndUpdateInventory("N",$ITEMID,$curentFinYear);
        $Print = Inventory_Model::LoadInventory($ITEMID);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GET_SURCHARGE":
        $TaxId = Outgoing_Invoice_Excise_Model::GetTaxByPOID($_REQUEST['POID'],$_REQUEST['PRINCID'],$_REQUEST['ITEMID']);
		$logger->debug($TaxId);	// to create debug type log file
        $Print = SalseTaxModel::GetSURCHARGETaxValue($TaxId);
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
    case "GETPOLIST":
        /*$Print = Param::GetPurchaseOrderForBilling();
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print); */
       
        if(isset($_REQUEST['PONUMBER']) && !empty($_REQUEST['PONUMBER'])){ 
			$Print = Outgoing_Invoice_Excise_Model::GetBundlePurchaseOrderForBilling($_REQUEST['PONUMBER']);
		}else{
			$Print = Outgoing_Invoice_Excise_Model::GetBundlePurchaseOrderForBilling();
		}
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
    case QueryModel::SEARCH:
	    Search($_REQUEST['YEAR'],$_REQUEST['oinv'],$_REQUEST['Todate'],$_REQUEST['Fromdate'],$_REQUEST['Principalid'],$_REQUEST['Buyerid']);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "GET_OINV_NUM_BY_DISPLAY":
        $Print = Outgoing_Invoice_NonExcise_Model::GET_OINV_NUM_BY_DISPLAY($_REQUEST['DISPLAYID'],$_REQUEST['YEAR']);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
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
	$year = isset($_REQUEST['YEAR']) ? $_REQUEST['YEAR'] : ParamModel::getFinYear();
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = Outgoing_Invoice_NonExcise_Model::LoadOutgoingInvoiceNonExcise(0,$start,$rp,$year);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'oinvoice_exciseID'       => $row->oinvoice_nexciseID,
                'oinvoice_type'     => $row->oinvoice_type,
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
    $jsonData['total'] = ParamModel::CountRecord("outgoinginvoice_nonexcise","");
    echo json_encode($jsonData);
}
function Search($year,$oinv,$Todate,$Fromdate,$Principalid,$Buyerid){

    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
    $rows = Outgoing_Invoice_NonExcise_Model::SearchOutgoingBundleInvoiceNonExcise($year,$oinv,$Todate,$Fromdate,$Principalid,$Buyerid,$start,$rp,$count);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'oinvoice_exciseID'       => $row->oinvoice_nexciseID,
                'oinvoice_type'     => $row->oinvoice_type,
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
    $jsonData['total'] = $count;
    echo json_encode($jsonData);
}
?>

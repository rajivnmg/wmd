<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Param/param_model.php");
include_once( "../../Model/Business_Action_Model/IncomingInvoiceNonExcise_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
include_once("../../Model/DBModel/SalseTaxModel.php");
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
$logger = Logger::getLogger('INVNONEX_controller');
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT: 
		// To create a new incoming non-excise invoice
        $Data = $_REQUEST['INCOMINGINVOICENONEXCISEDATA'];
        $Data = str_replace('\\','', $Data);
		$logger->info($Data);	// to create info type log file		
        $obj = json_decode($Data);
		//$invExist=IncomingInvoiceNonExcise_Model::validateInvoice($obj->{'_principal_Id'},$obj->{'inv_no_p'});
		//if($invExist){
		$curentFinYear = ParamModel::getFinYear();
        $InvId = IncomingInvoiceNonExcise_Model::InsertIncomingInvoiceNonExcise($obj->{'inv_no_p'}, $obj->{'_principal_Id'}, $obj->{'dt_p'},  $obj->{'inv_no_s'},$obj->{'_supplier_Id'}, $obj->{'dt_s'},$obj->{'Packing'},$obj->{'Insurance'},$obj->{'Freight'},$obj->{'SaleTax'},$obj->{'SaleTaxAmount'},$obj->{'TotalBillValue'},$obj->{'_rece_date'},$obj->{'Comments'},$obj->{'ms'});
		$logger->debug($InvId);	// to create debug type log file
        IncomingInvoiceNonExcise_Model::INSERT_DISPLAY_ENTRY_MAPING($InvId);
 
        if($obj->{'_supplier_Id'}>0 && $obj->{'inv_no_s'}!="")

       {
           $tranId=Transaction_Model::InsertTransaction($InvId,"InNonEx",date("Y-m-d"),$obj->{'_supplier_Id'});

       }
       else
       {
           $tranId=Transaction_Model::InsertTransaction($InvId,"InNonEx",date("Y-m-d"),$obj->{'_principal_Id'});
       }
		$logger->debug($tranId);	// to create debug type log file
        $i = 0;
        $_entryDId = 0;

        while($i < sizeof($obj->{'_items'}))
        {
            $_entryDId = IncomingInvoiceNonExciseDetails_Model::InsertIncomingInvoiceNonExciseDetails($InvId,$obj->_items[$i]->_item_id,$obj->_items[$i]->_qty,$obj->_items[$i]->_rate,$obj->_items[$i]->_exp_date,$obj->_items[$i]->_batch_no,$obj->_items[$i]->_landing_price,$obj->_items[$i]->_total_landing_price);
            if(true)
           {
              // Change by Rajiv On 12-8-15 for stocks 
			  //$Balance_qty = Inventory_Model::UpdateInventory("N",$obj->_items[$i]->_item_id,$obj->_items[$i]->_qty,"A");
				$Balanceqty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->_item_id,$curentFinYear);
				$logger->debug($Balanceqty);	// to create debug type log file
				$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"INEx",$obj->_items[$i]->_qty,"c",$Balanceqty);
               //Incoming_Inventory_Model::Insert_Incoming_Inventory($_entryDId,$obj->_items[$i]->_item_id,$InvId,date("Y-m-d"),"IN",$obj->{'_principal_Id'},$obj->{'_supplier_Id'},0,$obj->_items[$i]->_qty);
				$logger->debug($trxndid);	// to create debug type log file
           }
           $i++;
        }
        //}
        //exit;
        echo json_encode($Print);
        return;
        break;
    case QueryModel::UPDATE:
        //exit();
        session_start();
        $USERID = $_SESSION["USER"];
        $Data = $_REQUEST['INCOMINGINVOICENONEXCISEUPDATE'];
        $Data = str_replace('\\','', $Data);
		$logger->debug($Data);	// to create debug type log file
		$obj = json_decode($Data);
        $curentFinYear=ParamModel::getFinYear();
        $logger->debug($curentFinYear);	// to create debug type log file
        //$TXNDATA = Transaction_Model::LoadTransactionByRefID($obj->{'_invoiceid'},"InNonEx");
        $resultArr=IncomingInvoiceNonExciseDetails_Model::GetIncomingInvoiceNonExciseInfo($obj->{'_invoiceid'});
		for($i=0;$i<count($resultArr);$i++)
		{
			$codePartNo=$resultArr[$i]['codePartNo'];
			$preIssuedQty=$resultArr[$i]['qty'];
			$reversedQty = Inventory_Model::UpdateInventory("N",$codePartNo,$preIssuedQty,"S");
			$logger->debug($reversedQty);	// to create debug type log file
          
        }


        IncomingInvoiceNonExciseDetails_Model::DeleteInvoiceNonExciseDetails($obj->{'_invoiceid'});
        //IncomingInvoiceNonExcise_Model::DELETE_DISPLAY_ENTRY_MAPING($obj->{'_invoiceid'});
        //IncomingInvoiceNonExcise_Model::DeleteInvoiceNonExcise($obj->{'_invoiceid'});


        $PRINT = IncomingInvoiceNonExcise_Model::UpdateIncomingInvoiceNonExcise($obj->{'_invoiceid'},$obj->{'inv_no_p'}, $obj->{'_principal_Id'}, $obj->{'dt_p'},  $obj->{'inv_no_s'},$obj->{'_supplrId'}, $obj->{'dt_s'},$obj->{'Packing'},$obj->{'Insurance'},$obj->{'Freight'},$obj->{'SaleTax'},$obj->{'SaleTaxAmount'},$obj->{'TotalBillValue'},$obj->{'_rece_date'},$obj->{'Comments'},$obj->{'ms'});
		$logger->debug($PRINT);	// to create debug type log file
        //IncomingInvoiceNonExcise_Model::INSERT_DISPLAY_ENTRY_MAPING($InvId);
		
		// Change By Rajiv 21/8/2015 To track transaction detail
		if($obj->{'_supplier_Id'}>0 && $obj->{'inv_no_s'}!=""){
           $tranId=Transaction_Model::InsertTransaction($obj->{'_invoiceid'},"InNonEx",date("Y-m-d"),$obj->{'_supplier_Id'},"UPDATE");
       }else{
           $tranId=Transaction_Model::InsertTransaction($obj->{'_invoiceid'},"InNonEx",date("Y-m-d"),$obj->{'_principal_Id'},"UPDATE");
       }
	   
        $i = 0;
        $_entryDId = 0;
		$logger->debug(sizeof($obj->{'_items'}));	// to create debug type log file
        if(sizeof($obj->{'_items'})>0){
          while($i < sizeof($obj->{'_items'}))
          {
            $_entryDId = IncomingInvoiceNonExciseDetails_Model::InsertIncomingInvoiceNonExciseDetails($obj->{'_invoiceid'},$obj->_items[$i]->_item_id,$obj->_items[$i]->_qty,$obj->_items[$i]->_rate,$obj->_items[$i]->_exp_date,$obj->_items[$i]->_batch_no,$obj->_items[$i]->_landing_price,$obj->_items[$i]->_total_landing_price);
			$logger->debug($_entryDId);	// to create debug type log file
		   if(true)
           {
               $Balanceqty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->_item_id,$curentFinYear);
               $logger->debug($Balanceqty);	// to create debug type log file
               $trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->_item_id,"INEx",$obj->_items[$i]->_qty,"c",$Balanceqty,"UPDATE");
			   $logger->debug($trxndid);	// to create debug type log file
           }
            $i++;
          }
        }else{
			$Comments="@Delete By ".$USERID.' '.date("Y-m-d H:i:s"); 
			$PRINT = IncomingInvoiceNonExcise_Model::UpdateIncomingInvoiceNonExcise($obj->{'_invoiceid'},$obj->{'inv_no_p'}, $obj->{'_principal_Id'}, $obj->{'dt_p'},NULL,NULL,NULL,0,0,0,0,$obj->{'SaleTaxAmount'},0.00,$obj->{'_rece_date'},$Comments);
			$logger->debug($PRINT);	// to create debug type log file
		}
        //TransactionDetails_Model::DeleteTransactionDetails($TXNDATA[0]->_transactionId);
        //Transaction_Model::DeleteTransaction($TXNDATA[0]->_transactionId);

        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $Print =IncomingInvoiceNonExcise_Model::LoadAll($_REQUEST['invoiceId']);
		$logger->debug($PRINT);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "GETTAXDETAILS":
        $Print = SalseTaxModel::LoadSalseTax($_REQUEST['TAXID']);
		$logger->debug($PRINT);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "FIND_INVOIC_IN_OGINV":
        $Print = IncomingInvoiceNonExciseDetails_Model::FIND_INVOIC_IN_OGINV($_REQUEST['INVOICENO']);
		$logger->debug($PRINT);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SEARCH:
        Search($_REQUEST['YEAR'],$_REQUEST['val1'],$_REQUEST['val2'],$_REQUEST['val3'],$_REQUEST['val4']);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "VINO":
        $buyerId = $_REQUEST['BUYERID'];
        $invno= $_REQUEST['INVNO'];
        $Print = IncomingInvoiceNonExcise_Model::validateInvoice($buyerId,$invno);
		$logger->debug($PRINT);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;     
    case "GET_WIN_NUM_BY_DISPLAY":
        $Print = IncomingInvoiceNonExcise_Model::GET_WIN_NUM_BY_DISPLAY($_REQUEST['DISPLAYID'],$_REQUEST['YEAR']);
		$logger->debug($PRINT);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    default:
        break;
}
function pageload()
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $IncomingInvoiceNonExciseId=0;
    $rows = IncomingInvoiceNonExcise_Model::LoadAll($IncomingInvoiceNonExciseId,$start,$rp);
    //$rows=UnitMasterModel::LoadAll1();
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){

        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'INCOMININVOICENONEXCISEID'=> $row->_incominginvoiceid,
                'INCOMININVOICENONEXCISENUMBER'=> $row->_incoming_inv_no_p,
                'PRINCIPALNAME'=> $row->_principalname,
                'SUPPLIERNAME'=>$row->_suppliername,
                'BILLAMOUNT'=>$row->_tot_bill_val,
                'DATE'=>$row->_principal_inv_date
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("incominginvoice_without_excise","");
    echo json_encode($jsonData);
}
function Search($year,$val1,$val2,$val3,$val4){
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
    $rows = IncomingInvoiceNonExcise_Model::SearchIncomingInvoiceNonExcise($year,$val1,$val2,$val3,$val4,$start,$rp,$count);
    //$rows=UnitMasterModel::LoadAll1();
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    //echo $rows[0]._principalname;
    foreach($rows AS $row){

        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'INCOMININVOICENONEXCISEID'=> $row->_incominginvoiceid,
                'INCOMININVOICENONEXCISENUMBER'=> $row->_incoming_inv_no_p,
                'PRINCIPALNAME'=> $row->_principalname,
                'SUPPLIERNAME'=>$row->_suppliername,
                'BILLAMOUNT'=>$row->_tot_bill_val,
                'DATE'=>$row->_principal_inv_date,
                'rece_date'=>$row->_rece_date
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = $count;
    echo json_encode($jsonData);
    }
}

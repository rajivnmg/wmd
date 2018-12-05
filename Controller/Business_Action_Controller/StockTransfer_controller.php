<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once("../../Model/Business_Action_Model/Stocktransfer_Model.php");
include_once("../../Model/Business_Action_Model/Transaction_Model.php");
include_once("../../Model/Masters/Principal_Supplier_Master_Model.php");
include_once("../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
include_once("../../Model/Business_Action_Model/Incoming_Invoice_Excise_Model.php");
include_once("../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Param/param_model.php");
include_once("root.php");
include_once($root_path."log4php/Logger.php");
//Logger::configure($root_path."config.xml");
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
$logger = Logger::getLogger('StockTransfer_controller');

$Type = $_REQUEST['TYP'];
$logger->info($Type);// to create info type log file
$curentFinYear=ParamModel::getFinYear();
$logger->debug($curentFinYear);// to create debug type log file
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $StockId=0;
        $Data = $_REQUEST['StockTransferData'];

        $Data = str_replace('\\','', $Data);
		$logger->debug($Data);// to create debug type log file
        $obj = json_decode($Data);
        $StockId = Stocktransfer_Model::InsertStockTransfer($obj->{'_stInvNo'}, $obj->{'_stInvDate'}, $obj->{'_stBuyerId'},  $obj->{'_stPrincipalId'},$obj->{'_stSupplrId'}, $obj->{'_mode_delivery'},$obj->{'_st_time'},$obj->{'_dispatch_time'},$obj->{'_Supplier_stage'},$obj->{'_discount'},$obj->{'_Inclusive_Tag'},$obj->{'_total_ed'},$obj->{'_saleTax'},$obj->{'_total_amt'},$obj->{'_printType'},$obj->{'_remarks'});
		$logger->debug($StockId);// to create debug type log file
        if($StockId > 0)
        {
            $i = 0;
			
		    $tranId=Transaction_Model::InsertTransaction($StockId,"Stock",date("Y-m-d"),$obj->{'_stPrincipalId'});
			$logger->debug($tranId);// to create debug type log file		
            while($i < sizeof($obj->{'_items'}))
            {
                StocktransferDetails_Model::InsertStockTransferDetails($StockId,$obj->_items[$i]->itemid,$obj->_items[$i]->itemid,$obj->_items[$i]->_iinv_no_add,$obj->_items[$i]->_bal_qty,$obj->_items[$i]->_issued_qty,$obj->_items[$i]->_price,$obj->_items[$i]->_amt,$obj->_items[$i]->_ed_percent,$obj->_items[$i]->_ed_perUnit,$obj->_items[$i]->_ed_amt,$obj->_items[$i]->_entryId,$obj->_items[$i]->_edu_percent,$obj->_items[$i]->_cvd_percent,$obj->_items[$i]->_cvd_amt);

                if(true)
                {
				//Change By Rajiv on 31-8-15 due to stock issue                   
				   // Inventory_Model::UpdateInventory("E",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"S");
				   	$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->itemid,$curentFinYear);
					$logger->debug($Balance_qty);// to create debug type log file		
					 $trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"OST",$obj->_items[$i]->_issued_qty,"d",$Balance_qty);
					 $logger->debug($trxndid);// to create debug type log file		
                    //Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"A");
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->itemid,$curentFinYear);
					$logger->debug($Balance_qty);// to create debug type log file		
					$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"IST",$obj->_items[$i]->_issued_qty,"c",$Balance_qty);
					$logger->debug($trxndid);// to create debug type log file		
                  }
                $i++;
            }
        }
        echo json_encode($StockId);
        return;
        break;
    case QueryModel::UPDATE:
        $StockId=0;
        $Data = $_REQUEST['StockTransferData'];
        $Data = str_replace('\\','', $Data);
        $logger->info($Data);// to create info type log file	          
        $obj = json_decode($Data);
       
        $res=Stocktransfer_Model::updateStockTransfer($obj->{'_stId'},$obj->{'_stInvNo'}, $obj->{'_stInvDate'},   $obj->{'_stPrincipalId'},$obj->{'_stSupplrId'}, $obj->{'_mode_delivery'},$obj->{'_st_time'},$obj->{'_dispatch_time'},$obj->{'_Supplier_stage'},$obj->{'_discount'},$obj->{'_Inclusive_Tag'},$obj->{'_total_ed'},$obj->{'_saleTax'},$obj->{'_total_amt'},$obj->{'_printType'},$obj->{'_remarks'});
        $logger->debug($res);// to create debug type log file		
        if($res=="SUCCESS"){
        $resultArr=StocktransferDetails_Model::GetStockTransferInfo($obj->{'_stId'});
		$logger->debug($resultArr);// to create debug type log file		
        $i = 0;
		
    	for($i=0;$i<count($resultArr);$i++)
		{
			$codePartNo=$resultArr[$i]['codePartNo'];
			$preInputQty=$resultArr[$i]['qty'];
			$entryDId=$resultArr[$i]['iinv_no'];
			$reversedEQty = Inventory_Model::UpdateInventory("E",$codePartNo,$preInputQty,"A");
			$logger->debug($reversedEQty);// to create debug type log file		
            $reversedNEQty =Inventory_Model::UpdateInventory("N",$codePartNo,$preInputQty,"S");
			$logger->debug($reversedNEQty);// to create debug type log file		
            Incoming_Inventory_Model::Update_Incoming_Inventory($entryDId,"E",$preInputQty,"A");
          
        }
		    $tranId=Transaction_Model::InsertTransaction($obj->{'_stId'},"Stock",date("Y-m-d"),$obj->{'_stPrincipalId'},"UPDATE");
			$logger->debug($tranId);// to create debug type log file		

			StocktransferDetails_Model::DeleteStockTransferDetail($obj->{'_stId'});
          $i = 0;
            while($i < sizeof($obj->{'_items'}))
            {
				$logger->debug($obj->_items[$i]->_iinv_no);// to create debug type log file	
              
                if($obj->_items[$i]->_cvd_amt=="" || $obj->_items[$i]->_cvd_amt==NULL){
                   $obj->_items[$i]->_cvd_amt="0.00";
                }

                StocktransferDetails_Model::InsertStockTransferDetails($obj->{'_stId'},$obj->_items[$i]->itemid,$obj->_items[$i]->itemid,$obj->_items[$i]->_iinv_no_add,$obj->_items[$i]->_bal_qty,$obj->_items[$i]->_issued_qty,$obj->_items[$i]->_price,$obj->_items[$i]->_amt,$obj->_items[$i]->_ed_percent,$obj->_items[$i]->_ed_perUnit,$obj->_items[$i]->_ed_amt,$obj->_items[$i]->_entryId,$obj->_items[$i]->_edu_percent,$obj->_items[$i]->_cvd_percent,$obj->_items[$i]->_cvd_amt);
				
				//Change By Rajiv on 31-8-15 due to stock issue
                   
				   // Inventory_Model::UpdateInventory("E",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"S");
				   	$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->itemid,$curentFinYear);
					$logger->debug($Balance_qty);// to create debug type log file	
					$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"OST",$obj->_items[$i]->_issued_qty,"d",$Balance_qty,"UPDATE");
                    $logger->debug($trxndid);// to create debug type log file	
                    //Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"A");
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->itemid,$curentFinYear);
					$logger->debug($Balance_qty);// to create debug type log file	
					$trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"IST",$obj->_items[$i]->_issued_qty,"c",$Balance_qty,"UPDATE");
                    $logger->debug($trxndid);// to create debug type log file	

                //Inventory_Model::UpdateInventory("E",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"S");
               // Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"A");
                $i++;
            }

        echo json_encode($obj->{'_stId'});
      }
        return;

        break;

    case "AUTOCODE":
        $Print = Stocktransfer_Model::GetLastTransferNumber();
		$logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    case "SYSDATETIME":
	        $Print = Stocktransfer_Model::getSystemDateTime();
			 $logger->debug(json_encode($Print));// to create debug type log file	
	        echo json_encode($Print);
	        return;
        break;
    case "SELECTINVOICE":
        //$Print =Incoming_Inventory_Model::LoadIncoming_InventoryWithPrincipal($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
        $Print =StocktransferDetails_Model::LoadIncomingExciseList($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
		$logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    case "SELECTINVOICERLTINFO":
        //$Print =Incoming_Inventory_Model::LoadIncoming_Inventory($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
        //echo json_encode($Print);
        $Print=Incoming_Invoice_Excise_Model_Details::Get_Incoming_Excise_invoice($_REQUEST['Codepart'],$_REQUEST['invno']);
        $logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    case "SELECTINVOICERLTINFO_ST":
       
        $Print=StocktransferDetails_Model::showIncomingExciseDetail($_REQUEST['CODEPART'],$_REQUEST['INVNO']);
        $logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;

    case "SELECTINVOICE_BAlQTY":
        $Print=Incoming_Inventory_Model::LoadIncoming_Inventory_qty($_REQUEST['incomingInvNo'],$_REQUEST['incomingInvTyp']);
		 $logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $Print =Stocktransfer_Model::LoadAll($_REQUEST['StockId']);
        echo json_encode($Print);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload($_REQUEST['YEAR'],$_REQUEST['val1'],$_REQUEST['val2'],$_REQUEST['val3'],$_REQUEST['val4']);
        return;
        break;
    case "GET_ST_NUM_BY_DISPLAY":
        $Print = Stocktransfer_Model::GET_ST_NUM_BY_DISPLAY($_REQUEST['DISPLAYID'],$_REQUEST['YEAR']);
		 $logger->debug(json_encode($Print));// to create debug type log file	
        echo json_encode($Print);
        return;
        break;
    default:
        break;
}
function pageload($year,$value1,$value2,$value3,$value4)
{
    session_start();
    //echo "pageload";
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
   
    //$rows = Stocktransfer_Model::LoadAll($stockId);
    $rows = StockTransfer_Model::SearchStockTransfer($year,$value1,$value2,$value3,$value4,$start,$rp,$count);

    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){

        $entry = array('id' => $i,
            'cell'=>array(
                'StockId'=> $row->_stId,
                'stInvNo'=> $row->_stInvNo,
                'stInvDate'=>$row->_stInvDate,
                'stPrincipalName'=>$row->_PrincipalName,
                'stSupplrName'=>$row->_SupplierName,
                'total_amt'=>$row->_total_amt



            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = StockTransfer_Model::countRec($year,$value1,$value2,$value3,$value4);
    echo json_encode($jsonData);
}
?>



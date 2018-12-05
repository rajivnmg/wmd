<?php
//error_log("Stocktransfer1##", 3, "/tmp/my-errors1.log");
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
//error_log("Stocktransfer2", 3, "C:\my-errors.log");
include_once("../../Model/Business_Action_Model/Stocktransfer_Model.php");
//error_log("Stocktransfer3**", 3, "/tmp/st-errors.log");
include_once("../../Model/Business_Action_Model/Transaction_Model.php");
include_once("../../Model/Masters/Principal_Supplier_Master_Model.php");
include_once("../../Model/Business_Action_Model/Incoming_Inventory_Model.php");
//error_log("Stocktransfer4**", 3, "/tmp/st-errors.log");
include_once("../../Model/Business_Action_Model/Incoming_Invoice_Excise_Model.php");
include_once("../../Model/Business_Action_Model/Inventory_Model.php");
include_once( "../../Model/Param/param_model.php");
//include_once("../.../errors.php");
//error_log("Stcontroller5**", 3, "/tmp/st-errors.log");
$Type = $_REQUEST['TYP'];
//error_log("$Type", 3, "/tmp/st-errors.log");

$curentFinYear=ParamModel::getFinYear();

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $StockId=0;
        $Data = $_REQUEST['StockTransferData'];

        $Data = str_replace('\\','', $Data);
		error_log("\r\nDate-:".date("Y-m-d h:i:s")."  Type-:" .$Type. "\r\n" , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
        error_log("\r\n".$Data , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
        $obj = json_decode($Data);
        //error_log($Data, 3, "C:\my-errors.log");
        $StockId = Stocktransfer_Model::InsertStockTransfer($obj->{'_stInvNo'}, $obj->{'_stInvDate'}, $obj->{'_stBuyerId'},  $obj->{'_stPrincipalId'},$obj->{'_stSupplrId'}, $obj->{'_mode_delivery'},$obj->{'_st_time'},$obj->{'_dispatch_time'},$obj->{'_Supplier_stage'},$obj->{'_discount'},$obj->{'_Inclusive_Tag'},$obj->{'_total_ed'},$obj->{'_saleTax'},$obj->{'_total_amt'},$obj->{'_printType'},$obj->{'_remarks'});
		error_log("\r\n dtockID ".$StockId, 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
        if($StockId > 0)
        {
            $i = 0;
			
		    $tranId=Transaction_Model::InsertTransaction($StockId,"Stock",date("Y-m-d"),$obj->{'_stPrincipalId'});
			error_log("\r\nTranctionID : ".$tranId, 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
            //error_log("sizeof($obj->{'_items'})--||".sizeof($obj->{'_items'}), 3, "C:\my-errors1.log");
			//error_log("\r\nsizeof($obj->{'_items'})--||".sizeof($obj->{'_items'}) , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
            while($i < sizeof($obj->{'_items'}))
            {
                StocktransferDetails_Model::InsertStockTransferDetails($StockId,$obj->_items[$i]->itemid,$obj->_items[$i]->itemid,$obj->_items[$i]->_iinv_no_add,$obj->_items[$i]->_bal_qty,$obj->_items[$i]->_issued_qty,$obj->_items[$i]->_price,$obj->_items[$i]->_amt,$obj->_items[$i]->_ed_percent,$obj->_items[$i]->_ed_perUnit,$obj->_items[$i]->_ed_amt,$obj->_items[$i]->_entryId,$obj->_items[$i]->_edu_percent,$obj->_items[$i]->_cvd_percent,$obj->_items[$i]->_cvd_amt);

                if(true)
                {
				//Change By Rajiv on 31-8-15 due to stock issue
                   
				   // Inventory_Model::UpdateInventory("E",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"S");
				   	$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->itemid,$curentFinYear);
					error_log("\r\n Excise Balance QTy : ".$Balance_qty , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
					 TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"OST",$obj->_items[$i]->_issued_qty,"d",$Balance_qty);
                    //Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"A");
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->itemid,$curentFinYear);
					error_log("\r\n nonExcise Balance QTy : ".$Balance_qty , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
					 TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"IST",$obj->_items[$i]->_issued_qty,"c",$Balance_qty);
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
       // error_log($Data, 3, "c:\data-errors.log");
	   error_log("\r\nDate-:".date("Y-m-d h:i:s")."  Type-:" .$Type. "\r\n" , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
        error_log("\r\n".$Data , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
        $obj = json_decode($Data);
        $obj = json_decode($Data);
        $res=Stocktransfer_Model::updateStockTransfer($obj->{'_stId'},$obj->{'_stInvNo'}, $obj->{'_stInvDate'},   $obj->{'_stPrincipalId'},$obj->{'_stSupplrId'}, $obj->{'_mode_delivery'},$obj->{'_st_time'},$obj->{'_dispatch_time'},$obj->{'_Supplier_stage'},$obj->{'_discount'},$obj->{'_Inclusive_Tag'},$obj->{'_total_ed'},$obj->{'_saleTax'},$obj->{'_total_amt'},$obj->{'_printType'},$obj->{'_remarks'});
        error_log($res, 3, "c:\update-errors2.log");
        if($res=="SUCCESS"){
        $resultArr=StocktransferDetails_Model::GetStockTransferInfo($obj->{'_stId'});
        $i = 0;
    	for($i=0;$i<count($resultArr);$i++)
		{
			$codePartNo=$resultArr[$i]['codePartNo'];
			$preInputQty=$resultArr[$i]['qty'];
			$entryDId=$resultArr[$i]['iinv_no'];
			$reversedEQty = Inventory_Model::UpdateInventory("E",$codePartNo,$preInputQty,"A");
            $reversedNEQty =Inventory_Model::UpdateInventory("N",$codePartNo,$preInputQty,"S");
            Incoming_Inventory_Model::Update_Incoming_Inventory($entryDId,"E",$preInputQty,"A");
            //error_log($reversedQty, 3, "c:\in-errors7.log");
        }
		    $tranId=Transaction_Model::InsertTransaction($obj->{'_stId'},"Stock",date("Y-m-d"),$obj->{'_stPrincipalId'},"UPDATE");
			error_log("\r\nTranctionID : ".$tranId, 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");

			StocktransferDetails_Model::DeleteStockTransferDetail($obj->{'_stId'});
          $i = 0;
            while($i < sizeof($obj->{'_items'}))
            {
                error_log($obj->_items[$i]->_iinv_no, 3, "c:\in-errors7.log");
                if($obj->_items[$i]->_cvd_amt=="" || $obj->_items[$i]->_cvd_amt==NULL){
                   $obj->_items[$i]->_cvd_amt="0.00";
                }

                StocktransferDetails_Model::InsertStockTransferDetails($obj->{'_stId'},$obj->_items[$i]->itemid,$obj->_items[$i]->itemid,$obj->_items[$i]->_iinv_no_add,$obj->_items[$i]->_bal_qty,$obj->_items[$i]->_issued_qty,$obj->_items[$i]->_price,$obj->_items[$i]->_amt,$obj->_items[$i]->_ed_percent,$obj->_items[$i]->_ed_perUnit,$obj->_items[$i]->_ed_amt,$obj->_items[$i]->_entryId,$obj->_items[$i]->_edu_percent,$obj->_items[$i]->_cvd_percent,$obj->_items[$i]->_cvd_amt);
				
				//Change By Rajiv on 31-8-15 due to stock issue
                   
				   // Inventory_Model::UpdateInventory("E",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"S");
				   	$Balance_qty = Inventory_Model::checkAndUpdateInventory("E",$obj->_items[$i]->itemid,$curentFinYear);
					error_log("\r\n Excise Balance QTy : ".$Balance_qty , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
					 TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"OST",$obj->_items[$i]->_issued_qty,"d",$Balance_qty,"UPDATE");
                    //error_log("start UpdateInventory ES"."<br/>", 3, "C:\my-errors2.log");
                    //Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_issued_qty,"A");
					$Balance_qty = Inventory_Model::checkAndUpdateInventory("N",$obj->_items[$i]->itemid,$curentFinYear);
					error_log("\r\n nonExcise Balance QTy : ".$Balance_qty , 3, "..".DIRECTORY_SEPARATOR ."..".DIRECTORY_SEPARATOR ."log".DIRECTORY_SEPARATOR ."stockTransfer-errors.log");
					 TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"IST",$obj->_items[$i]->_issued_qty,"c",$Balance_qty,"UPDATE");
                   // error_log($obj->_items[$i]->_issued_qty.",", 3, "C:\my-errors22.log");

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
        echo json_encode($Print);
        return;
        break;
    case "SYSDATETIME":
	        $Print = Stocktransfer_Model::getSystemDateTime();
	        echo json_encode($Print);
	        return;
        break;
    case "SELECTINVOICE":
        //$Print =Incoming_Inventory_Model::LoadIncoming_InventoryWithPrincipal($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
        $Print =StocktransferDetails_Model::LoadIncomingExciseList($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
        echo json_encode($Print);
        return;
        break;
    case "SELECTINVOICERLTINFO":
        //$Print =Incoming_Inventory_Model::LoadIncoming_Inventory($_REQUEST['ITEMID'],$_REQUEST['incomingInvTyp']);
        //echo json_encode($Print);
        $Print=Incoming_Invoice_Excise_Model_Details::Get_Incoming_Excise_invoice($_REQUEST['Codepart'],$_REQUEST['invno']);
        //error_log("SELECTINVOICERLTINFO", 3, "C:\my-errors.log");
        echo json_encode($Print);
        return;
        break;
    case "SELECTINVOICERLTINFO_ST":
        //error_log("ANKUR", 3, "C:\my-errors.log");
        $Print=StocktransferDetails_Model::showIncomingExciseDetail($_REQUEST['CODEPART'],$_REQUEST['INVNO']);
        //error_log("ANKUR1", 3, "C:\my-errors.log");
        echo json_encode($Print);
        return;
        break;

    case "SELECTINVOICE_BAlQTY":
        $Print=Incoming_Inventory_Model::LoadIncoming_Inventory_qty($_REQUEST['incomingInvNo'],$_REQUEST['incomingInvTyp']);
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
    //echo "pageloa".$year;
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


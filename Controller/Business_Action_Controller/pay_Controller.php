<?php
//error_reporting(E_ALL);
//ini_set('display_errors',1);
session_start();
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/pay_model.php");
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
$logger = Logger::getLogger('Pay_controller');

$UID=$_SESSION["USER"];
$Type = $_REQUEST['TYP'];
$logger->info($Type);	// to create info type log file
if($Type == null)
    $Type = QueryModel::PAGELOAD;

switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['PAYDATA'];
        $Data = str_replace('\\','', $Data);
        $logger->debug($Data);	// to create debug type log file
        $obj = json_decode($Data);
        $USERID = $_SESSION["USER"];
        $Print=payment_Model::InsertPayment($obj->{'trnx_no'},$obj->{'bn'},$obj->{'trxn_date'},$obj->{'trn_type'},$obj->{'bank_name'},$obj->{'branch_name'},$obj->{'cheque_no'},$obj->{'cheque_date'},$obj->{'cheque_account_no'},$obj->{'utr_no'},$obj->{'bank_add'},$obj->{'total_amt'},$USERID);
		$logger->debug($Print);	// to create debug type log file 
        $i=0;
        while($i < sizeof($obj->{'_items'}))
        {   
        	 $logger->debug(sizeof($obj->{'_items'}));	// to create debug type log file 
            payment_Details_Model::InsertPaymentDetails($Print,$obj->_items[$i]->invoiceNo,$obj->_items[$i]->invoiceDate,$obj->_items[$i]->dueDate,$obj->_items[$i]->invoiceAmount,$obj->_items[$i]->shortAmount,$obj->_items[$i]->excessAmount,$obj->_items[$i]->cash_discount_value,$obj->_items[$i]->debitFlag,$obj->_items[$i]->debitId,$obj->_items[$i]->debitAmt,$obj->_items[$i]->payment_status,$obj->_items[$i]->payabledAmount,$obj->_items[$i]->balanceAmount,$obj->_items[$i]->receivedAmount,$obj->_items[$i]->Remarks,$USERID);
           
            $payment_status='P';
            if($obj->_items[$i]->balanceAmount=='0'||$obj->_items[$i]->balanceAmount=='0.00')
            {
				$payment_status='C';
			}
			$logger->debug($payment_status);	// to create debug type log file
            payment_Details_Model::updatePaymentStatus($obj->_items[$i]->invoiceNo,$obj->_items[$i]->balanceAmount,$payment_status);
            $i++;
        }
        
        echo json_encode($Print);
        return;
        break;
	 case QueryModel::UPDATE:
        $Data = $_REQUEST['PAYDATA'];
		$trxnId = $_REQUEST['trxnId'];
        $Data = str_replace('\\','', $Data);
		$logger->debug($Data);	// to create debug type log file 
        $obj = json_decode($Data);
		$USERID = $_SESSION["USER"];
        $Print=payment_Model::UpdatePayment($obj->{'trxnId'},$obj->{'trnx_no'},$obj->{'bn'},$obj->{'trxn_date'},$obj->{'trn_type'},$obj->{'bank_name'},$obj->{'branch_name'},$obj->{'cheque_no'},$obj->{'cheque_date'},$obj->{'cheque_account_no'},$obj->{'utr_no'},$obj->{'bank_add'},$obj->{'total_amt'},$USERID);
		$logger->debug($Print);	// to create debug type log file 
		payment_Details_Model::DeletePaymentDetails($obj->{'trxnId'});
        $i=0;
		
        while($i < sizeof($obj->{'_items'}))
        {           	 
            payment_Details_Model::InsertPaymentDetails($obj->{'trxnId'},$obj->_items[$i]->invoiceNo,$obj->_items[$i]->invoiceDate,$obj->_items[$i]->dueDate,$obj->_items[$i]->invoiceAmount,$obj->_items[$i]->shortAmount,$obj->_items[$i]->excessAmount,$obj->_items[$i]->cash_discount_value,$obj->_items[$i]->debitFlag,$obj->_items[$i]->debitId,$obj->_items[$i]->debitAmt,$obj->_items[$i]->payment_status,$obj->_items[$i]->payabledAmount,$obj->_items[$i]->balanceAmount,$obj->_items[$i]->receivedAmount,$obj->_items[$i]->Remarks,$USERID);
           
            $payment_status='P';
            if($obj->_items[$i]->balanceAmount=='0'||$obj->_items[$i]->balanceAmount=='0.00')
            {
				$payment_status='C';
			}
            payment_Details_Model::updatePaymentStatus($obj->_items[$i]->invoiceNo,$obj->_items[$i]->balanceAmount,$payment_status);
            $i++;
        }
        
        echo json_encode($Print);
        return;
        break;
     case QueryModel::SEARCH:
          $Buyerid= $_REQUEST['Buyerid'];
          $trxnNo= $_REQUEST['trxnNo'];
          $Fromdate= $_REQUEST['Fromdate'];
          $Todate= $_REQUEST['Todate'];
          $pono= $_REQUEST['pono'];
          $finyear= $_REQUEST['finyear'];
          Search($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear);
          return;
          break;    
     
    case "AUTOCODE":
        $Print = payment_Model::GetLastTransactionNumber();
		$logger->debug(json_encode($Print));	// to create debug type log file 
        echo json_encode($Print);
        return;
        break; 
    case "CANCELTRXN":
         $DATA=$_REQUEST['PAYMENT_DATA']; 
         $DATA = str_replace('\\','', $DATA);
         $logger->debug($DATA);	// to create debug type log file 
         $obj = json_decode($DATA);      
         $Print=payment_Model::PaymentTransactionCancel($obj->{'trxnId'},$obj->{'cancel_reason'});
		 $logger->debug($DATA);	// to create debug type log file 
         if($Print)
         {  $BUYERID=$obj->{'bn'};
             $i=0;
         	while($i< sizeof($obj->{'_items'}))
         	{ 
				$invoiceNo=$obj->_items[$i]->invoiceNo;
				$trxndId=$obj->_items[$i]->trxndId;
				$receivedAmount=$obj->_items[$i]->receivedAmount;
				$payabledAmount=$obj->_items[$i]->payabledAmount;
				$shortAmount=$obj->_items[$i]->shortAmount;
				$excessAmount=$obj->_items[$i]->excessAmount;
				$debitAmt=$obj->_items[$i]->debitAmt;
				$cash_discount_value=$obj->_items[$i]->cash_discount_value;
				$newAMT=($receivedAmount+$cash_discount_value+$shortAmount+$debitAmt)-($excessAmount);
				$ROWS=Payment_Details_Model::loadInvoices($BUYERID,$invoiceNo);
				$Arr=(array)$ROWS;
				$logger->debug($Arr);	// to create debug type log file 
				foreach($Arr as $row)
				{ 
				    $Arr1=(array)$Arr[0];
				    $balanceAMT=0;
				    $newBalanceAmt=0;
					$balanceAMT=$Arr1['balanceAmount'];
					$logger->debug($balanceAMT);	// to create debug type log file 
					$newBalanceAmt=$balanceAMT+$newAMT;
					$logger->debug($newBalanceAmt);	// to create debug type log file 					
					$res=payment_Details_Model::updatePaymentDetailStatus($trxndId,'cancelled');
					$res1=payment_Details_Model::updatePaymentStatus($invoiceNo,$newBalanceAmt,'P');					
				}
				 $i++;
			}
		 }
         
        echo json_encode($Print);
        return;
        break;  
              
    case "GETINV":
        $BUYERID= $_REQUEST['BUYERID'];
        $INVOICENO= $_REQUEST['INVOICENO'];
        $Print = Payment_Details_Model::loadInvoices($BUYERID,$INVOICENO);
		$logger->debug(json_encode($Print));	// to create debug type log file 					
        echo json_encode($Print);
        return;
        break;
   
   case "GETINV_NEW":
        $BUYERID= $_REQUEST['BUYERID'];
        $INVOICENO= $_REQUEST['INVOICENO'];
        $finyears= $_REQUEST['finyears'];
        $Print = Payment_Details_Model::loadInvoicesNew($BUYERID,$INVOICENO,$finyears);
		$logger->debug(json_encode($Print));	// to create debug type log file 					
        echo json_encode($Print);
        return;
        break;
   case QueryModel::SELECT:
       
        $trxnId= $_REQUEST['trxnId'];
        $Print = payment_Model::LoadPAYByID($trxnId);
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
        $start=(($page-1)*$rp);      
        
     
        $rows=Payment_Model::SearchPaymentTransaction($Fromdate,$Todate,$trxnNo,$Buyerid);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 0;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'trxnId'=>$row['trxnId'],
                    'trnx_no'=>$row['trnx_no'],
                    'trxn_date'=>$row['trxn_date'],
                    'bn_name'=>$row['BuyerName'],
                    'trxn_type'=>$row['trxn_type'],
                    'received_amt'=>$row['received_amt'],
                    'UserId'=>$row['UserId']
                    )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =Payment_Model::countRec($Fromdate,$Todate,$trxnNo,$Buyerid);
         
        echo json_encode($jsonData);
        
}     
   function Search($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear){
  
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
       // $Data=$_REQUEST['SearchData'];
       
		//$Data = str_replace('\\','', $Data);
		//$obj = json_decode($Data);   
		//$SearchKey=$obj->{'SearchKey'};
	         
        $rows=Payment_Model::SearchPaymentTransaction($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear,$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 1;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
			$status ='';
			if($row['status']=='created'){
				$status ='Active';
			}else if($row['status']=='cancelled'){
					$status ='Cancelled';
			}else{
				$status =$row['status'];
			}
            $entry = array('id' => $i,
                'cell'=>array(
                     'trxnId'=>$row['trxnId'],
                    'trnx_no'=>$row['trnx_no'],
                    'bpono'=>$row['bpono'],
                    'trxn_date'=>$row['trxn_date'],
                    'bn_name'=>$row['BuyerName'],
                    'trxn_type'=>$row['trxn_type'],
					'status'=>$status,
                    'received_amt'=>$row['received_amt'],
                    'UserId'=>$row['UserId']
                    )
            );
            $i++;            
            $jsonData['rows'][] = $entry;
        }
        //here countRec() return the total number of record 
        $jsonData['total'] =Payment_Model::countRec($Fromdate,$Todate,$trxnNo,$Buyerid,$pono,$finyear);
        echo json_encode($jsonData);
        
        
}     
        

?>

<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Challan_Model.php");
include_once( "../../Model/Business_Action_Model/Quation_Model.php");
include_once( "../../Model/Masters/BuyerMaster_Model.php");
include_once( "../../Model/Business_Action_Model/Transaction_Model.php");
include_once( "../../Model/Business_Action_Model/Inventory_Model.php");
include_once("root.php");
include_once($root_path."log4php/Logger.php");
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
	
$logger = Logger::getLogger('Challan_controller');
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
        $Data = $_REQUEST['CHALLANDATA'];
		
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $ChallanId = 0;
        $buyerID = $obj->{'_BuyerId'};
        $create_Id = $_SESSION["USER"];
        $create_time=date('Y-m-d H:i:s');
        $Cust_id = 0;
        $logger->info($Data);	// to create info type log file
        if($buyerID == 0)
        {
            $Cust_id = Cusotmer::InsertCustomer($obj->{'_CustName'}, $obj->{'_CustAddress'});
            $buyerID = 0;
        }
        else
        {
            $Cust_id = 0;
        }
        $logger->debug($Cust_id);	// to create debug type log file
        $ChallanId = Challan_Model::InsertChallan($obj->{'_ChallanDate'}, $obj->{'_ChallanNo'}, $obj->{'_BuyerId'},  $obj->{'_gc_note'},$obj->{'_gc_note_date'}, $obj->{'_ExecutiveId'},$obj->{'_Challan_Purpose'},$obj->{'_OrderNo'},$obj->{'_OrderDate'},$obj->{'_cust_contact_name'},$Cust_id,$obj->{'_Challan_Status'},$obj->{'_InvoiceType'},$obj->{'_OutgoingInvoiceNo'},$obj->{'_total_Qty'},$obj->{'loanNo'},$obj->{'_remarks'},$obj->{'_OutgoingInvoiceNo2'},$obj->{'_OutgoingInvoiceNo3'});
		$logger->debug($ChallanId);	// to create debug type log file
		$tranId=Transaction_Model::InsertTransaction($ChallanId,"Challan",date("Y-m-d"),$obj->{'_BuyerId'});
		$logger->debug($tranId);	// to create debug type log file
        if($ChallanId > 0)
        {
        $i = 0;
        while($i < sizeof($obj->{'_items'}))
        {
           $chdid = ChallanDetails_Model::InsertChallanDetails($ChallanId,$obj->_items[$i]->itemid,$obj->_items[$i]->_qty,$obj->_items[$i]->_principalID,$create_Id,$create_time);
		   $logger->debug($chdid);	// to create debug type log file
            if($tranId>0)
            {
            
                $trxndid = TransactionDetails_Model::InsertTransactionDetails($tranId,$obj->_items[$i]->itemid,"Challan",$obj->_items[$i]->_qty,"c",0);
              //Inventory_Model::UpdateInventory("N",$obj->_items[$i]->itemid,$obj->_items[$i]->_qty,"S"); 
			   $logger->debug($trxndid);	// to create debug type log file
            }
            $i++;
        }
        }
        echo json_encode($ChallanId);
        return;
        break;
    case QueryModel::UPDATE:
        $Data = $_REQUEST['CHALLANUPDATE'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $Print = 0;
        $buyerID = $obj->{'_BuyerId'};
        $ChallanId=$obj->{'_ChallanId'};
        $create_Id = $_SESSION["USER"];
        $create_time=date('Y-m-d H:i:s');
        $logger->info($Data);	// to create info type log file
        $Cust_id = 0;
        if($buyerID == 0)
        {
			
            $Cust_id = Cusotmer::UpdateCustomer($obj->{'_CustId'},$obj->{'_CustName'}, $obj->{'_CustAddress'},'N');
            $buyerID = 0;
        }
        else
        {
            $Cust_id = 0;
        }
        
       
		$logger->debug($Cust_id);	// to create debug type log file
        $Print = Challan_Model::UpdateChallan($obj->{'_ChallanId'},$obj->{'_ChallanDate'}, $obj->{'_ChallanNo'}, $obj->{'_BuyerId'},  $obj->{'_gc_note'},$obj->{'_gc_note_date'}, $obj->{'_ExecutiveId'},$obj->{'_Challan_Purpose'},$obj->{'_OrderNo'},$obj->{'_OrderDate'},$obj->{'_cust_contact_name'},$obj->{'_CustId'},$obj->{'_Challan_Status'},$obj->{'_InvoiceType'},$obj->{'_OutgoingInvoiceNo1'},$obj->{'_total_Qty'},$obj->{'loanNo'},$obj->{'_remarks'},$obj->{'_OutgoingInvoiceNo2'},$obj->{'_OutgoingInvoiceNo3'});
        $logger->debug($Print);	// to create debug type log file
        $res=ChallanDetails_Model::UpdateStatus($obj->{'_ChallanId'},$create_Id,$create_time);
		$logger->debug($res);	// to create debug type log file
        if($res)
        {
		  $i = 0;
          while($i < sizeof($obj->{'_items'}))
          {
              $chdid = ChallanDetails_Model::InsertChallanDetails($ChallanId,$obj->_items[$i]->itemid,$obj->_items[$i]->_qty,$obj->_items[$i]->_principalID,$create_Id,$create_time);
			  $logger->debug($chdid);	// to create debug type log file
            $i++;
          }	
		}
       
        echo json_encode($Print);
        return;
        break; 
    case QueryModel::SELECT:    
        $Print =Challan_Model::LoadChallan($_REQUEST['ChallanId']); 
        echo json_encode($Print);
        return;
        break;
    case "AUTOCODE":        
        $Print = Challan_Model::GetLastChallanNumber();
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;  
    case QueryModel::SEARCH:
          $Buyerid= $_REQUEST['Buyerid'];
          $txtchallanNo= $_REQUEST['txtchallanNo'];
          $Fromdate= $_REQUEST['Fromdate'];
          $Todate= $_REQUEST['Todate'];
		  $status= $_REQUEST['status'];
		  $purpose= $_REQUEST['purpose'];
		  $executive = $_REQUEST['executive'];
		  $contactName = $_REQUEST['contactName'];
          if(isset($_REQUEST['pono'])){
		  $pono = $_REQUEST['pono'];
		  }
          Search($Fromdate,$Todate,$txtchallanNo,$Buyerid,$status,$purpose,$executive,$contactName);
          return;
          break;       
    case QueryModel::PAGELOAD:
            pageload();
            return;
            break;
    case "BUYERCHALLANDETAIL": 
		$BUYERID = $_REQUEST['BUYERID'];       
		$Print =ChallanDetails_Model::BuyerWiseChallanDetail($BUYERID); 
		echo json_encode($Print);
		return;
		break; 
	case "CHALLANDETAIL": 
		$chlnid = $_REQUEST['challanID'];       
		$Print =ChallanDetails_Model::LoadChallanDetails($chlnid); 
		$logger->debug(json_encode($Print));	// to create debug type log file
		$i = 1;
		 $output ='';
         $output.='<table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
											<th>Principal</th>
											<th>CodePart</th>
                                            <th>Discription</th>
											<th>Quantity</th>
											
										                                          
                                    </tr>
                                    </thead>
                                    <tbody>';
							if(sizeof($Print) > 0){									
									foreach($Print as $row){ 
									  $output.=' <tr class="odd gradeX">
                                           <td>'.$i++.'</td>
                                           <td>'.$row['principalname'].'</td>
                                           <td>'.$row['item_codepart'].'</td>
										   <td>'.$row['item_desc'].'</td>
										   <td>'.$row['tot_exciseQty'].'</td>
										   
										</tr>'; 
									}
							}
                                   $output.=' </tbody>
                                </table>';		
		
		echo $output;		
		//echo json_encode($Print);		
        return;
        break;
		exit;
           
}


function pageload()
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $ChallanId=0;
	
    $rows = Challan_Model::LoadChallan($ChallanId);
	$logger->debug(json_encode($rows));	// to create debug type log file
    //$rows=UnitMasterModel::LoadAll1();
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        $customerName="";
        $BuyerName="";
        if($row->_BuyerId == 0)
        {
            $rows_CustomerName=Cusotmer::GetCustomer($row->_CustId);
            while ($Row_Buyer = mysql_fetch_array($rows_CustomerName, MYSQL_ASSOC)) {
                $customerName=$Row_Buyer['cust_name'];
            }
            $entry = array('id' => $i,
            'cell'=>array(
                'ChallanId'=> $row->_ChallanId,
                'ChallanDate'=> $row->_ChallanDate,
                'ChallanNo'=>$row->_ChallanNo,
                'BuyerName'=>$BuyerName,
                'CustomerName'=>$customerName,
                'gc_note'=>$row->_gc_note,
                'gc_note_date'=>$row->_gc_note_date,
                'ExecutiveId'=>$row->_ExecutiveId
       
               
            )
        );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        else if($row->_BuyerId>0)
        {
            
            $rows_BuyerName=BuyerMaster_Model::GetBuyerDetails($row->_BuyerId);
            while ($Row_Buyer = mysql_fetch_array($rows_BuyerName, MYSQL_ASSOC)) {
                $BuyerName=$Row_Buyer['BuyerName'];
        }
            $entry = array('id' => $i,
             'cell'=>array(
                 'ChallanId'=> $row->_ChallanId,
                 'ChallanDate'=> $row->_ChallanDate,
                 'ChallanNo'=>$row->_ChallanNo,
                 'BuyerName'=>$BuyerName,
                 'CustomerName'=>$customerName,
                 'gc_note'=>$row->_gc_note,
                 'gc_note_date'=>$row->_gc_note_date,
                 'ExecutiveId'=>$row->_ExecutiveId
        
                
             )
         );
            $i++;
            
            $jsonData['rows'][] = $entry; 
        }
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}
function Search($Fromdate,$Todate,$challanNo,$Buyerid,$status,$purpose,$executive,$contactName){
    session_start();
    
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
     
       
        $rows=Challan_Model::SearchChallan($Fromdate,$Todate,$challanNo,$Buyerid,$status,$purpose,$executive,$contactName,$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 0;
		//print_r($rows); exit;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
			
			$cstatus ='';
			$title = '';
			$open_date = explode(" ",$row['create_time']);
			$open_date = $open_date[0];
			$mod_date = explode(" ",$row['modify_time']);
			$close = $mod_date[0];
			
			if($open_date == '0000-00-00'){
				$open_date = '00-00-0000';
			}else{
				$open_date = date("d/m/Y",strtotime($open_date));
			}
			if($close == '0000-00-00'){
				$close = '00-00-0000';
			}else{
				$close = date("d/m/Y",strtotime($close));
			}
			
			if($row['challan_status'] == 1){
				$cstatus = 'Open';
				$title = 'Open';
				$close ='';				
			}else if($row['challan_status'] == 2){
				$cstatus = 'CWOI';
				$title = 'Close Without Outgoing Invoice'; 
			}else if($row['challan_status'] == 3){
				$cstatus = 'CWOE';
				$title = 'Close With Outgoing Excise'; 
			}else if($row['challan_status'] == 4){
				$cstatus = 'CWONE';
				$title = 'Close With Outgoing Non-Excise'; 
			}else if($row['challan_status'] == 5){
				$cstatus = 'CWE & NE';
				$title = 'Close With Outgoing Excise & Non-Excise'; 
			}else if($row['challan_status'] == 6){
				$cstatus = 'Free Sample';
				$title = 'Free Sample'; 
			}else if($row['challan_status'] == 7){
				$cstatus = 'CALS';
				$title = 'Close Against Loan Settlement'; 
			}
            $entry = array('id' => $i,
             'cell'=>array(
                 'ChallanId'=> $row['ChallanId'],
                 'ChallanDate'=> date("d/m/Y",strtotime($row['ChallanDate'])),
                 'ChallanNo'=>$row['ChallanNo'],
                 'bn_name'=>$row['BuyerName'],
                 'gc_note'=>$row['gc_note'],
                 'gc_note_date'=>date("d/m/Y",strtotime($row['gc_note_date'])),
                 'ExecutiveId'=>$row['ExecutiveId'],
				 'opendate'=> $open_date,
				 'closedate'=>$close,				 
				 'status'=>("<a href='javascript:challanDetail(".$row['ChallanId'].");' title=".$title.">$cstatus</a>") 
             )
         );
            
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        
        $jsonData['total'] =Challan_Model::countRec($Fromdate,$Todate,$challanNo,$Buyerid,$status,$purpose,$executive,$contactName);
         
        echo json_encode($jsonData);
        
        
}     
?>

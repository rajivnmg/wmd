<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/PO_Reports_Model.php");

include_once( "../../Model/Business_Action_Model/Search_Model.php");
//Logger::configure("../../config.xml");
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
$logger = Logger::getLogger('PO_Controller'); 

$Type = $_REQUEST['TYP'];
 $logger->debug($Type); // function to write in log file
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case "SEARCH":
    
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : "s.bpoId";
		$sortname = !empty($sortname) ? $sortname : "s.bpoId";
        $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : "ASC";
        $start=(($page-1)*$rp);
       
        $Data = $_REQUEST['SearchData'];
		$logger->debug($Data); // function to write in log file
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);   
        $rows= PO_Reports_Model::SelectRequiredData($obj->{'repType'},$obj->{'FromDate'}, $obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'PoVD'},$obj->{'executiveId'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'Mode'},$obj->{'Status'},$obj->{'marketsegment'},$obj->{'finYer'},$start,$rp,$sortname,$sortorder);     
        header("Content-type: application/json");
       
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 1;
        
        foreach($rows AS $row){   
			$picklistLink='------';
			// function to get the stock qty 
            $colr = Search_Model::getItemStockQty($row->bpoId);
            if($colr =="G"){
				$btnShow = '<span><img src="../../images/green.png" height="20" width="50" title="All Items Are Available In Stock"/></span>';
			}else if($colr =="Y"){
				$btnShow = '<span><img src="../../images/yellow.jpg" height="20" width="50" title="Some Items Are Available In Stock"/></span>';
			}else if($colr =="B"){
				$btnShow = '<span><img src="../../images/blue.png" height="20" width="50" title="All Items Are Deliverd"/></span>';
			}else{
				$btnShow = '<span><img src="../../images/red.png" height="20" width="50" title="No Items Are Available In Stock"/></span>';
			}
			if((($colr == "G") || ($colr == "Y")) && ($_SESSION['USER_TYPE']=="B") && $row->poState !='Hold'){
				$picklistLink='<a href="javascript:pickListForm('.$row->bpoId.',\''.$row->bpono.'\',\''.$row->BuyerName.'\',\''.$row->bpoDate.'\','.$row->BuyerId.')">Create Pick List</a>';
			}         
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
					'cell'=>array(
                    'sno' =>($i+1),
					'bpoId'=> $row->bpoId,
                    'bpono'=> $row->bpono,
                    'bpoDate'=>$row->bpoDate,
                    'bpoVDate'=>$row->bpoVDate,
                    'BuyerName'=>$row->BuyerName,
                    'executiveId'=>$row->executiveId,
                    'bpoType'=>$row->poType,
                    'po_state'=>$row->poState,                    
					'po_status' => $row->po_status,
					'picklist'=>$picklistLink,
					'stockAvailabe' =>$btnShow,					
                    'po_val'=>number_format($row->po_val, 2, '.', '')
                )
            );
            $i++;            
            $jsonData['rows'][] = $entry;
        }
        
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =PO_Reports_Model::countRecDataPO($obj->{'repType'},$obj->{'FromDate'}, $obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'PoVD'},$obj->{'executiveId'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'Mode'},$obj->{'Status'},$obj->{'marketsegment'},$obj->{'finYer'},$start,$rp);
        
      
        echo json_encode($jsonData);
        return;
        break;
   case "SEARCHINVOICE":
       $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
        $Data=$_REQUEST['SearchData'];
        $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : "s.invoiceDate";
		$sortname = !empty($sortname) ? $sortname : "s.invoiceDate";
        $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : "DESC";
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);   
       //$SearchKey=$obj->{'SearchKey'};
       
        $rows=PO_Reports_Model::paymentPendingInvoice($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'InvoiceType'},$obj->{'invoice_no'},$obj->{'finyear'},$start,$rp,$sortname,$sortorder);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i =$start;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
			$now = time(); // or your date as well
			$your_date = strtotime($row['invoiceDate']);
			$datediff = $now - $your_date;
			$d = floor($datediff/(60*60*24));
			
			
			//$day = $diff->format("%R%a day");
            $entry = array('id' => $i,
                'cell'=>array(
                    'sno' =>$i+1,
                    'invoiceNo'=>$row['invoiceNo'],
                    'invoiceDate'=>date("d/m/Y",strtotime($row['invoiceDate'])),
                    'dueDate'=>date("d/m/Y",strtotime($row['dueDate'])),
					'day'=> $d,
                    'invoiceAmount'=>$row['invoiceAmount'],
                    'BuyerName'=>$row['BuyerName'],
                    'executiveId'=>$row['executiveId'],
                    'dueAmount'=>$row['balanceAmount']
                    )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =PO_Reports_Model::countInvoice($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'InvoiceType'},$obj->{'invoice_no'},$obj->{'finyear'});
         
        echo json_encode($jsonData);
       return;
       break;
      case "SEARCHBUYERINVOICE":
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 15;
        $start=(($page-1)*$rp);      
        $Data=$_REQUEST['SearchData'];
        
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);   
        //$SearchKey=$obj->{'SearchKey'};
       
        $rows=PO_Reports_Model::buyerPaymentPending($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'InvoiceType'},$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i =$start;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'sno' =>$i+1,
                    'BuyerName'=>$row['BuyerName'],
                    'invoiceAmount'=>$row['invoiceAmount'],
                    'executiveId'=>$row['executiveId'],
                    'dueAmount'=>$row['balanceAmount']
                    )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =PO_Reports_Model::countBuyerInvoice($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'InvoiceType'});
        
        echo json_encode($jsonData);
       return;
       break;
      case "SEARCHBUYERWISEREVEUE":
           $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
        $Data=$_REQUEST['SearchData'];
        
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);          
       
        $rows=PO_Reports_Model::GetBuyerWiseRevenue($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i =$start;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'sno' =>$i+1,
                    'BuyerName'=>$row['BuyerName'],
                    'no_of_po'=>$row['no_of_po'],
                    'bpoType'=>$row['bpoType'],
                    'executiveId'=>$row['executiveId'],
                    'po_val'=>$row['po_val']
                    )
				);
            $i++;            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =PO_Reports_Model::countBuyerWiseRevenue($obj->{'executiveId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'});
      
        echo json_encode($jsonData);
       return;
       break;
       
       case "SEARCHFINYEARWISEBUYERREVEUE";
       $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
        $Data=$_REQUEST['SearchData'];
        
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);        
        $rows=PO_Reports_Model::GetFinYearWiseRevenue($obj->{'executiveId'},$obj->{'FnYear'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'principalId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'locationid'},$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i =$start;
        
      
        foreach($rows AS $row){
			
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'sno' =>$i+1,
                    'BuyerName'=>$row['BuyerName'],
                    'no_of_po'=>$row['no_of_po'],
                    'bpoType'=>$row['bpoType'],
                    'executiveId'=>$row['executiveId'],
                    'finyear'=>$row['finyear'],                   
                    'po_val'=>$row['po_val'],
                    'locationName'=>$row['locationName'],
                    'principalName'=>$row['principalName']
                    )
            );
            $i++;            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =PO_Reports_Model::countFinYearWiseRevenue($obj->{'executiveId'},$obj->{'FnYear'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'principalId'},$obj->{'FromDate'},$obj->{'ToDate'},$obj->{'locationid'});
       
        echo json_encode($jsonData);
       return;
       break;
}

<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Search_Model.php");
//Logger::configure($root_path."config.xml");
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
$logger = Logger::getLogger('Query result');

$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case "SEARCH":
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);
        $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
        $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
        $query = isset($_POST['query']) ? $_POST['query'] : false;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
        $Data = $_REQUEST['SearchData'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $logger->debug(json_encode($Data));	// to create debug type log file
        //echo $Data;
     
        $rows= Search_Model::SelectRequiredData($obj->{'FromDate'}, $obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'PoVD'},0,$obj->{'Status'},$obj->{'Mode'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'marketsegment'},$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 0;
        foreach($rows AS $row){
            
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'bpoId'=> $row->bpoId,
                    'bpono'=> $row->bpono,
                    'bpoDate'=>$row->bpoDate,
                    'bpoVDate'=>$row->bpoVDate,
                    'BuyerId'=>$row->BuyerId,
                    'BuyerName'=>$row->BuyerName,
                    'po_status'=>$row->po_status,
                    'po_val'=>$row->po_val
           
                   
                )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =Search_Model::countRec($obj->{'FromDate'}, $obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'PoVD'},0,$obj->{'Status'},$obj->{'Mode'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'marketsegment'},$start,$rp);
        echo json_encode($jsonData);
        break;
   
    case "SEARCHDASHBOARD":
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);
        $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
        $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
        $query = isset($_POST['query']) ? $_POST['query'] : false;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
        $Data = $_REQUEST['SearchData'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $logger->debug(json_encode($Data));	// to create debug type log file
      // echo $Data; exit;
     
        $rows= Search_Model::SelectRequiredData($obj->{'FromDate'}, $obj->{'ToDate'},$obj->{'BuyerId'},$obj->{'PoType'},$obj->{'PoVD'},$obj->{'CodePart'},$obj->{'Status'},$obj->{'Mode'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'marketsegment'},$start,$rp);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 1;
		foreach($rows AS $row){
            $picklistLink='------';
            // function to get the stock qty 
            $colr = Search_Model::getItemStockQty($row->bpoId);
            if($colr =="G"){
				$btnShow = '<span style="color:red;"><img src="../../images/green.png" height="20" width="50" title="All Items Are Available In Stock"/></span>';
			}else if($colr =="Y"){
				$btnShow = '<span style="color:red;"><img src="../../images/yellow.jpg" height="20" width="50" title="Some Items Are Available In Stock"/></span>';
			}else if($colr =="B"){
				$btnShow = '<span style="color:red;"><img src="../../images/blue.png" height="20" width="50" title="All Items Are Deliverd"/></span>';
			}else{
				$btnShow = '<span style="color:red;"><img src="../../images/red.png" height="20" width="50" title="No Items Are Available In Stock"/></span>';
			}
			
			if((($colr == "G") || ($colr == "Y")) && ($row->poState !='Hold')){
					$picklistLink='<a href="javascript:pickListForm('.$row->bpoId.',\''.$row->bpono.'\',\''.$row->BuyerName.'\',\''.$row->bpoDate.'\','.$row->BuyerId.')">Create Pick List</a>';
			} 
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'bpoId'=> $row->bpoId,
                    'bpono'=> $row->bpono,
                    'bpoDate'=>$row->bpoDate,
                    'bpoVDate'=>$row->bpoVDate,
                    'BuyerId'=>$row->BuyerId,
                    'BuyerName'=>$row->BuyerName,
                    'po_status'=>$row->po_status,
                    'po_state'=>$row->poState,                    
					'poType' => $row->bpoType,
					'stockAvailabe' =>$btnShow,	
					'picklist'=>$picklistLink,				
                    'po_val'=>$row->po_val           
                   
                )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] = Search_Model::countRec($obj->{'FromDate'}, $obj->{'ToDate'}, $obj->{'BuyerId'},$obj->{'PoType'}, $obj->{'PoVD'}, $obj->{'CodePart'}, $obj->{'Status'}, $obj->{'Mode'},$obj->{'ponumber'},$obj->{'principalid'},$obj->{'marketsegment'},$start,$rp);
		//echo '<pre>';
		//print_r($jsonData); exit;
	
        echo json_encode($jsonData);
		//exit;
        break;  
}
?>

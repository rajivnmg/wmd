<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Quation_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
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
$logger = Logger::getLogger('Quot_controller');
$Type = $_REQUEST['TYP'];
$logger->info($Type);	// to create info type log file
session_start();
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['QUOTATIONDATA'];
		//~ echo '<br>Data<pre>'; print_r($Data); echo '</pre>';
		$logger->debug($Data);	// to create debug type log file
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $Print = 0;
        $buyerID = $obj->{'_buyer_id'};
        $Cust_id = "NULL";
        /* if($buyerID == 0)
        {
            $Cust_id = Cusotmer::InsertCustomer($obj->{'_coustomer_name'}, $obj->{'_coustomer_add'});
            $buyerID = "NULL";
        }
        else
        {
            $Cust_id = "NULL";
        } */
		
        if(true)
        {
        $USERID = $_SESSION["USER"];
			/* BOF for adding GST by Ayush Giri on 20-06-2017 */
            //$Print = Quotation_Model::InsertQuotation($obj->{'_quotation_no'},$obj->{'_coustomer_ref_no'},$obj->{'_coustomer_ref_date'}, $buyerID, $Cust_id,$obj->{'_contact_persone'},$obj->{'_principal_id'},$obj->{'_discount'},$obj->{'_delivery'},$obj->{'_sales_tax'},$obj->{'_incidental_chrg'},$obj->{'frgt'},$obj->{'frgp'}, $obj->{'frga'},$obj->{'_credit_period'},$obj->{'_ed_edu_tag'},$obj->{'_cvd'},$obj->{'_remarks'},$USERID);
			$obj->{'_discount'} = 0;
			$Print = Quotation_Model::InsertQuotation($obj->{'_quotation_no'}, $obj->{'_coustomer_ref_no'}, $obj->{'_coustomer_ref_date'}, $buyerID, $Cust_id, $obj->{'_contact_persone'}, $obj->{'_principal_id'}, $obj->{'_discount'}, $obj->{'_delivery'}, $obj->{'_incidental_chrg'}, $obj->{'frgt'}, $obj->{'frgp'}, $obj->{'frga'}, $obj->{'_credit_period'}, $obj->{'_remarks'}, $obj->{'_pnf'}, $obj->{'_ins'}, $obj->{'_othc'}, $USERID);
			/* EOF for adding GST by Ayush Giri on 20-06-2017 */
			$logger->debug($Print);	// to create debug type log file
        }
        $i = 0;
        while($i < sizeof($obj->{'_items'}))
        {
            //$qtdid = Quotation_Details_Model::InsertQuotationDetails($Print,$obj->_items[$i]->itemid,$obj->_items[$i]->_unit_id,$obj->_items[$i]->_quantity,$obj->_items[$i]->_price_per_unit);
			$qtdid = Quotation_Details_Model::InsertQuotationDetails($Print,$obj->_items[$i]->itemid,$obj->_items[$i]->_unit_id,$obj->_items[$i]->_item_discount,$obj->_items[$i]->_hsn_code,$obj->_items[$i]->_cgst_rate,$obj->_items[$i]->_sgst_rate,$obj->_items[$i]->_igst_rate,$obj->_items[$i]->_quantity,$obj->_items[$i]->_price_per_unit);
			$logger->debug($qtdid);	// to create debug type log file
            $i++;
        }
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $QuotationNumber = $_REQUEST['QUOTATIONNUMBER'];
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'qut.quotDate'; 
		$sortname = !empty($sortname) ? $sortname : "qut.quotDate";
		$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
		$sortorder = !empty($sortorder) ? $sortorder : "DESC";
        $Print = Quotation_Model::LoadQuotation($QuotationNumber,0,0,$sortname,$sortorder);
        echo json_encode($Print);
        return;
        break;
    case "FIND_QNO_IN_PO":
        $QuotationNumber = $_REQUEST['QUOTATIONNUMBER'];
        $Print = Quotation_Model::FIND_QNO_IN_PO($QuotationNumber);
        echo json_encode($Print);
        return;
        break;
    case QueryModel::UPDATE:
    //exit();
        $Data = $_REQUEST['QUOTATIONDATA'];
        $Data = str_replace('\\','', $Data);
       
       	$logger->debug($Data);	// to create debug type log file
        $obj = json_decode($Data);
        
        Quotation_Details_Model::DeleteItem($obj->{'_quotation_id'});
        Quotation_Model::DeleteQuotation($obj->{'_quotation_id'});
        $Print = 0;
        $buyerID = $obj->{'_buyer_id'};
        $Cust_id = 0;
        if($buyerID == 0)
        {
            $Cust_id = Cusotmer::InsertCustomer($obj->{'_coustomer_name'}, $obj->{'_coustomer_add'});
            $buyerID = "NULL";
        }
        else
        {
            $Cust_id = "NULL";
        }
		$logger->debug($Cust_id);	// to create debug type log file
        if(true)
        {
        $USERID = $_SESSION["USER"];
            //$Print = Quotation_Model::InsertQuotation($obj->{'_quotation_no'}, $obj->{'_coustomer_ref_no'},$obj->{'_coustomer_ref_date'}, $buyerID, $Cust_id,$obj->{'_contact_persone'},$obj->{'_principal_id'},$obj->{'_discount'},$obj->{'_delivery'},$obj->{'_sales_tax'},$obj->{'_incidental_chrg'},$obj->{'frgt'},$obj->{'frgp'}, $obj->{'frga'},$obj->{'_credit_period'},$obj->{'_ed_edu_tag'},$obj->{'_cvd'},$obj->{'_remarks'},$USERID);
			$Print = Quotation_Model::InsertQuotation($obj->{'_quotation_no'}, $obj->{'_coustomer_ref_no'}, $obj->{'_coustomer_ref_date'}, $buyerID, $Cust_id, $obj->{'_contact_persone'}, $obj->{'_principal_id'}, $obj->{'_discount'}, $obj->{'_delivery'}, $obj->{'_incidental_chrg'}, $obj->{'frgt'}, $obj->{'frgp'}, $obj->{'frga'}, $obj->{'_credit_period'}, $obj->{'_remarks'}, $obj->{'_pnf'}, $obj->{'_ins'}, $obj->{'_othc'}, $USERID);
			$logger->debug($Print);	// to create debug type log file
		}
        $i = 0;
       
        while($i < sizeof($obj->{'_items'}))
        {
            //$qutdid = Quotation_Details_Model::InsertQuotationDetails($Print,$obj->_items[$i]->itemid,$obj->_items[$i]->_unit_id,$obj->_items[$i]->_quantity,$obj->_items[$i]->_price_per_unit);
			$qutdid = Quotation_Details_Model::InsertQuotationDetails($Print, $obj->_items[$i]->itemid, $obj->_items[$i]->_unit_id,$obj->_items[$i]->_item_discount, $obj->_items[$i]->_hsn_code,$obj->_items[$i]->_cgst_rate,$obj->_items[$i]->_sgst_rate,$obj->_items[$i]->_igst_rate,$obj->_items[$i]->_quantity, $obj->_items[$i]->_price_per_unit);
			$logger->debug($qutdid);	// to create debug type log file
		   $i++;
        }
        echo json_encode($Print);
        return;
        break;
    case QueryModel::AUTO_COMPLEAT:
        $Print = Quotation_Model::GetQuotationNumberList();
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SEARCH:
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'qut.quotDate'; 
		$sortname = !empty($sortname) ? $sortname : "qut.quotDate";
		$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
		$sortorder = !empty($sortorder) ? $sortorder : "DESC";
        Search($_REQUEST['coulam'],$_REQUEST['val1'],$_REQUEST['val2'],$_REQUEST['val3'],$_REQUEST['val4'],$sortname,$sortorder);
        return;
        break;
		
	case "SEARCHQUOTATION":
		$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'qut.quotDate'; 
		$sortname = !empty($sortname) ? $sortname : "qut.quotDate";
		$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
		$sortorder = !empty($sortorder) ? $sortorder : "DESC";
        SearchQuotations($_REQUEST['Fromdate'],$_REQUEST['Todate'],$_REQUEST['buyerid'],$_REQUEST['principalid'],$_REQUEST['quotno'],$_REQUEST['quotStatus'],$_REQUEST['executive'],$sortname,$sortorder);
        return;
        break;
	
    case "AUTOCODE":
        $Print = Quotation_Model::AutoGenerateQuotationNumber();
		$logger->debug($Print);	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case "SALESTAX":
        $buyerId = $_REQUEST['BUYERID'];
        $Print = Quotation_Model::getSalesTax($buyerId);
		$logger->debug(json_encode($Print));	// to create debug type log file
        echo json_encode($Print);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
	case 'REDIRECT':
		//$print = SITE_URL.PRINTQUATIONPDF.'?TYP=SELECT&QUOTATIONNUMBER='.$_GET['QUOTATIONNUMBER'];
		$print = SITE_URL.'pdf/quot.php?TYP=SELECT&QUOTATIONNUMBER='.$_GET['QUOTATIONNUMBER'];
		echo json_encode($print);
		//header('Location: '.SITE_URL.PRINTQUATIONPDF.'?TYP=SELECT&QUOTATIONNUMBER='.$_GET['QUOTATIONNUMBER']);
		break;
    default:
        break;
}
function Pageload(){
    //session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'qut.quotDate'; 
	$sortname = !empty($sortname) ? $sortname : "qut.quotDate";
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
	$sortorder = !empty($sortorder) ? $sortorder : "DESC";
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = Quotation_Model::LoadQuotation(0,$start,$rp,$sortname,$sortorder);

    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
		$Print = Quotation_Model::quotationPoList($row->_quotation_id,$row->_quotation_no);	
		
		if(!empty($Print)){
			$polink = "<a href='javascript:quotationPurchaseOrders(".$row->_quotation_id.",\"".$row->_quotation_no."\")'); ' title='Click To View PO List'>".$row->_quotation_no."</a>";
		}else{
			$polink = $row->_quotation_no;
		}
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'quotId'       => $row->_quotation_id,
                'quotNo'     => $polink,
                'quotDate'     => $row->_quotation_date,
                'Principal_Supplier_Name'       => $row->_principal_name,
                'BuyerName'     => $row->_coustomer_name,
                'contact_person'     => $row->_contact_persone
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    //$jsonData['total'] = ParamModel::CountRecord("quotation","");
	 $jsonData['total'] = Quotation_Model::CountRecordQuot("quotation","");
    echo json_encode($jsonData);    
}
function Search($col,$val1,$val2,$val3,$val4,$sortname,$sortorder){
   
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    
    $start = ($page - 1) * $rp;
    
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
    
    $rows = Quotation_Model::SearchQuotation($col,$val1,$val2,$val3,$val4,$count,$start,$rp);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
		$Print = Quotation_Model::quotationPoList($row->_quotation_id,$row->_quotation_no);	
		
		if(!empty($Print)){
			$polink = "<a href='javascript:quotationPurchaseOrders(".$row->_quotation_id.",\"".$row->_quotation_no."\")'); ' title='Click To View PO List'>".$row->_quotation_no."</a>";
		}else{
			$polink = $row->_quotation_no;
		}
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'quotId'       => $row->_quotation_id,
                'quotNo'     => $polink ,
                'quotDate'     => $row->_quotation_date,
                'Principal_Supplier_Name'       => $row->_principal_name,
                'BuyerName'     => $row->_coustomer_name,
                'contact_person'     => $row->_contact_persone
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = Quotation_Model::CountRecord("quotation","");
    echo json_encode($jsonData);    
}


function SearchQuotations($Fromdate,$Todate,$buyerid,$principalid,$quotno,$quotStatus,$executive,$sortorder){
  
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    
    $start = ($page - 1) * $rp;
    
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $count = 0;
    
    $rows = Quotation_Model::SearchQuotations($Fromdate,$Todate,$buyerid,$principalid,$quotno,$quotStatus,$executive,$count,$start,$rp);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
		$Print = Quotation_Model::quotationPoList($row->_quotation_id,$row->_quotation_no);	
		
		if(!empty($Print)){
			$polink = "<a href='javascript:quotationPurchaseOrders(".$row->_quotation_id.",\"".$row->_quotation_no."\")'); ' title='Click To View PO List'>".$row->_quotation_no."</a>";
		}else{
			$polink = $row->_quotation_no;
		}
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'quotId'       => $row->_quotation_id,
                'quotNo'     => $polink ,
                'quotDate'     => $row->_quotation_date,
                'Principal_Supplier_Name'       => $row->_principal_name,
                'BuyerName'     => $row->_coustomer_name,
                'contact_person'     => $row->_contact_persone
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = Quotation_Model::QuotCountRecords($Fromdate,$Todate,$buyerid,$principalid,$quotno,$quotStatus,$executive,$count,$start,$rp);
    echo json_encode($jsonData);    
}

?>

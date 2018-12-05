<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/ItemMaster_Model.php");
include_once( "../../Model/Param/param_model.php");
include_once( "../../Model/Masters/HSNMaster_Model.php");
$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $GROUPID = $_REQUEST['GROUPID'];
        $PRINCIPALID = $_REQUEST['PRINCIPALID'];
        $ITEM_CODE_PARTNO = $_REQUEST['ITEM_CODE_PARTNO'];
        $ITME_DESCP = $_REQUEST['ITME_DESCP'];
        $UNITID = $_REQUEST['UNITID'];
        $ITEM_IDENTIFICATION_MARK = $_REQUEST['ITEM_IDENTIFICATION_MARK'];
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$ITEM_TARRIF_HEADING = $_REQUEST['ITEM_TARRIF_HEADING'];
		$HSN_CODE = $_REQUEST['HSN_CODE'];
		/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        $ITEM_COST_PRICE = $_REQUEST['ITEM_COST_PRICE'];
        $LSC = $_REQUEST['LSC'];
        $USC = $_REQUEST['USC'];
        $REMARKS = $_REQUEST['REMARKS'];
        session_start();
        $USERID=$_SESSION["USER"];        
        if($ITEM_IDENTIFICATION_MARK =="NEW"){
			$ITEM_IDENTIFICATION_MARK = $_REQUEST['NEWIDENTIFICATION'];
			ParamModel::insertItemInParam($ITEM_IDENTIFICATION_MARK,$USERID);
			
		}
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$Print = ItemMaster_Model::InsertItem($GROUPID, $PRINCIPALID, $ITEM_CODE_PARTNO, $ITME_DESCP, $UNITID, $ITEM_IDENTIFICATION_MARK, $ITEM_TARRIF_HEADING,$ITEM_COST_PRICE, $LSC, $USC, $REMARKS,$USERID);
		$Print = ItemMaster_Model::InsertItem($GROUPID, $PRINCIPALID, $ITEM_CODE_PARTNO, $ITME_DESCP, $UNITID, $ITEM_IDENTIFICATION_MARK, $HSN_CODE,$ITEM_COST_PRICE, $LSC, $USC, $REMARKS,$USERID);
		/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $principalid = $_REQUEST['PRINCIPALID'];
        $result = ItemMaster_Model::LoadItemByPrincipalID($principalid);
        echo json_encode($result);
        return;
        break;
    case "LOADITEM":
        $itemid = $_REQUEST['ITEMID'];
        $result = ItemMaster_Model::LoadItem($itemid);
        echo json_encode($result);
        return;
        break;
	case "LOADITEMINFO":
        $itemid = $_REQUEST['ITEMID'];
        $result = ItemMaster_Model::LoadItemINFO($itemid);
        echo json_encode($result);
        return;
        break;    
    case QueryModel::UPDATE:
        $itemid = $_REQUEST['ITEMID'];
        $ITEM_CODE_PARTNO = $_REQUEST['ITEM_CODE_PARTNO'];
        $ITME_DESCP = $_REQUEST['ITME_DESCP'];
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$ITEM_TARRIF_HEADING = $_REQUEST['ITEM_TARRIF_HEADING'];
		$HSN_CODE = $_REQUEST['HSN_CODE'];
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        $ITEM_COST_PRICE = $_REQUEST['ITEM_COST_PRICE'];
        $LSC = $_REQUEST['LSC'];
        $USC = $_REQUEST['USC'];
        $REMARKS = $_REQUEST['REMARKS'];
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$responseQ = ItemMaster_Model::UpdateItemDetails($itemid.trim(),$ITEM_CODE_PARTNO,$ITME_DESCP,$ITEM_TARRIF_HEADING,$ITEM_COST_PRICE, $LSC,$USC,$REMARKS,$_REQUEST['GID'],$_REQUEST['PID'],$_REQUEST['UID'],$_REQUEST['IDENTITY']);
		$responseQ = ItemMaster_Model::UpdateItemDetails($itemid.trim(),$ITEM_CODE_PARTNO,$ITME_DESCP,$HSN_CODE,$ITEM_COST_PRICE, $LSC,$USC,$REMARKS,$_REQUEST['GID'],$_REQUEST['PID'],$_REQUEST['UID'],$_REQUEST['IDENTITY']);
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        echo $responseQ;
        return;
        break;
    case QueryModel::PAGELOAD:

       // $itemid = $_REQUEST['ITEMID']; exit;
	   
		$group = isset($_REQUEST['group']) ? $_REQUEST['group'] : 0;
		$principal = isset($_REQUEST['principal']) ? $_REQUEST['principal'] : 0;
		$unit = isset($_REQUEST['unit']) ? $_REQUEST['unit'] : 0;
		$identity = isset($_REQUEST['identity']) ? $_REQUEST['identity'] : null;
        PageLoad(0,$_REQUEST['TAG'],$_REQUEST['ID'],$group,$principal,$unit,$identity);
        return;
        break;
	/* BOF for adding GST by Ayush Giri on 08-06-2017 */
	case 'SELECT_TAX':
		$hsn_code = $_REQUEST['HSN_CODE'];
		$result = HSNMasterModel::GetTaxRateByHSNCode($hsn_code);
		//var_dump($result);
		echo json_encode($result);
        return;
        break;
	/* EOF for adding GST by Ayush Giri on 08-06-2017 */	
    default:
        break;
}

function PageLoad($itemid,$TAG,$ID,$group,$principal,$unit,$identity)
{
session_start();

$page = isset($_POST['page']) ? $_POST['page'] : 1;
$rp = isset($_POST['rp']) ? $_POST['rp'] : 10;

$start = ($page - 1) * $rp;

$sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
$sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
$query = isset($_POST['query']) ? $_POST['query'] : false;
$qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

$rows = ItemMaster_Model::LoadItem($itemid,$TAG,$ID, $start, $rp,$group,$principal,$unit,$identity);
header("Content-type: application/json");
$jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
$i = 0;
foreach($rows AS $row){

    //If cell's elements have named keys, they must match column names
    //Only cell's with named keys and matching columns are order independent.
    $entry = array('id' => $i,
        'cell'=>array(
            'ITEMID'       => $row->_item_id,
            'ITEM_CODE_PARTNO'     => $row->_item_code_partno,
            'ITME_DESCP'     => $row->_item_descp,
            'ITEM_IDENTIFICATION_MARK'       => $row->_item_identification_marks,
            'GD'       => $row->_group_desc,
            'GC'     => $row->_groupcode,
            'PNAME'     => $row->_principalname,
            'UNITNAME'       => $row->_unitname,
			/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
            //'ITEM_TARRIF_HEADING'     => $row->_item_tarrif_heading,
			'HSN_CODE'     => $row->_hsn_code,
			/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
			'TAX_RATE'     => $row->_tax_rate,
            'ITEM_COST_PRICE'    => $row->_item_cost_price,
            'LSC'       => $row->_lsc,
            'USC'     => $row->_usc,
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            //'exq'       => $row->_exq,
            //'nexq'     => $row->_nexq,
			'qty'       => $row->_qty,
			/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            'REMARKS'     => $row->_remarks
        )
    );
    $i++;

    $jsonData['rows'][] = $entry;
}
//$jsonData['total'] = $count;
$jsonData['total'] = ParamModel::CountRecord("item_master",""); // count($rows) //;
echo json_encode($jsonData);
}

?>

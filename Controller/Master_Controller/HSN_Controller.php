
<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/HSNMaster_Model.php");
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $HSNCode = $_REQUEST['HSNCODE'];
        $HSNDESC = $_REQUEST['HSNDESC'];
        $Remark = $_REQUEST['REMARKS'];
		$TaxId = $_REQUEST['TAXID'];
        session_start();
        $Print = HSNMasterModel::InsertHSN($HSNCode, $HSNDESC, $Remark, $TaxId);
        echo json_encode($Print);
        break;
    case QueryModel::SELECT:
       $print= HSNMasterModel::LoadHSN($_REQUEST['HSNID']);
	   echo json_encode($print);
	   return;
        break;
    case QueryModel::UPDATE:
        $HSNID = $_REQUEST['HSNID'];
        $HSNCode = $_REQUEST['HSNCODE'];
        $HSNDESC = $_REQUEST['HSNDESC'];
        $Remark = $_REQUEST['REMARKS'];
		$TaxId = $_REQUEST['TAXID'];
        $Print = HSNMasterModel::UpdateHSNDiscription($HSNID,$HSNCode, $HSNDESC,$Remark.trim(), $TaxId);
        echo json_encode($Print);
        break;
    case QueryModel::PAGELOAD:
		$tax = isset($_REQUEST['tax']) ? trim($_REQUEST['tax']) : null;
		$hsn_code = isset($_REQUEST['hsn_code']) ? trim($_REQUEST['hsn_code']) : null;
		$hsn_desc = isset($_REQUEST['hsn_desc']) ? trim($_REQUEST['hsn_desc']) : null;
        PageLoad(0,$_REQUEST['TAG'],$_REQUEST['ID'],$tax,$hsn_code,$hsn_desc);
        //Pageload();
        return;
        break;
    default:
        break;
}
function PageLoad($hsnid,$TAG,$ID,$tax,$hsn_code,$hsn_desc)
{
	session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
	
    $rows = HSNMasterModel::LoadHSN($hsnid,$TAG,$ID,$tax,$hsn_code,$hsn_desc);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'HSNID'     => $row->_hsn_id,
                'HSNCODE'   => $row->_hsn_code,
                'HSNDESC'   => $row->_hsn_desc,
				'TAXRATE'   => $row->_tax_rate,
                'REMARKS'   => $row->_remark
            )
        );
        $i++;
		
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}
/*function Pageload(){
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = HSNMasterModel::LoadHSN(0);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'HSNID'     => $row->_hsn_id,
                'HSNCODE'   => $row->_hsn_code,
                'HSNDESC'   => $row->_hsn_desc,
				'TAXRATE'   => $row->_tax_rate,
                'REMARKS'   => $row->_remark
            )
        );
        $i++;
		
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}*/

?>
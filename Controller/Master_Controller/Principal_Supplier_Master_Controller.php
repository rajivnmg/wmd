
<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/Principal_Supplier_Master_Model.php");
$Type = $_REQUEST['TYP'];
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $Principal_Supplier_Name = $_REQUEST['PRINCIPALSUPPLIERNAME'];
        $ADD1 = $_REQUEST['ADD1'];
        $ADD2 = $_REQUEST['ADD2'];
        $CITYID = $_REQUEST['CITYID'];
        $STATEID = $_REQUEST['STATEID'];
        $PINCODE = $_REQUEST['PINCODE'];
        $PS_RANGE = $_REQUEST['PS_RANGE'];
        $PS_DIVISION = $_REQUEST['PS_DIVISION'];
        $PS_COMMISSIONERATE = $_REQUEST['PS_COMMISSIONERATE'];
        $ECC_CODENO = $_REQUEST['ECC_CODENO'];
        $TINNO = $_REQUEST['TINNO'];
        $PANNO = $_REQUEST['PANNO'];
        $TYPE = $_REQUEST['ADDTYPE'];
        
        $BANK_NAME = $_REQUEST['BANK_NAME'];
        $BANK_ACCOUNT_NO = $_REQUEST['BANK_ACCOUNT_NO'];
        $BANK_ADDRESS = $_REQUEST['BANK_ADDRESS'];
        $RTGS = $_REQUEST['RTGS'];
        $NEFT = $_REQUEST['NEFT'];
        $ACCOUNTTYPE = $_REQUEST['ACCOUNTTYPE'];
        $PRINCIPAL_SUPPLIER_ID = Principal_Supplier_Master_Model::Insert_Principal_Supplier($Principal_Supplier_Name, $ADD1, $ADD2, $CITYID, $STATEID, $PINCODE, $PS_RANGE,
                                                                            $PS_DIVISION,$PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO, $TYPE);
        if($PRINCIPAL_SUPPLIER_ID > 0){
            $Print = Principal_Supplier_Master_Model::Insert_Principal_Supplier_BankDetails($PRINCIPAL_SUPPLIER_ID, $BANK_NAME, $BANK_ACCOUNT_NO, 
                $BANK_ADDRESS, $RTGS, $NEFT, $ACCOUNTTYPE); 
        }
        else{
            return QueryResponse::NO;
        }
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:
        $_principal_supplier_id = $_REQUEST['PRINCIPAL_SUPPLIER_ID'];
        $_principal_supplier_type = $_REQUEST['PRINCIPAL_SUPPLIER_TYPE'];
        $result = Principal_Supplier_Master_Model::Load_Principal_Supplier($_principal_supplier_id,$_principal_supplier_type);
        echo json_encode($result);
        return;
        break;
    case QueryModel::UPDATE:
        $_principal_supplier_id = $_REQUEST['PRINCIPAL_SUPPLIER_ID'];
        $Result = Principal_Supplier_Master_Model::Update_Principal_Supplier($_REQUEST['PRINCIPAL_SUPPLIER_ID'],$_REQUEST['PRINCIPALSUPPLIERNAME'], 
            $_REQUEST['ADD1'], $_REQUEST['ADD2'],$_REQUEST['CITYID'], $_REQUEST['STATEID'], $_REQUEST['PINCODE'], $_REQUEST['PS_RANGE'],$_REQUEST['PS_DIVISION'],
            $_REQUEST['PS_COMMISSIONERATE'], $_REQUEST['ECC_CODENO'], $_REQUEST['TINNO'], $_REQUEST['PANNO']);
        $Result = Principal_Supplier_Master_Model::Update_Principal_Supplier_BankDetails($_REQUEST['PRINCIPAL_SUPPLIER_ID'], $_REQUEST['BANK_NAME'],
            $_REQUEST['BANK_ACCOUNT_NO'],$_REQUEST['BANK_ADDRESS'], $_REQUEST['RTGS'], $_REQUEST['NEFT'], $_REQUEST['ACCOUNTTYPE']);
        echo json_encode($Result);
        return;
        break;
    case QueryModel::PAGELOAD:
        $_principal_supplier_id = $_REQUEST['PRINCIPAL_SUPPLIER_ID'];
        PageLoad($_principal_supplier_id,$_REQUEST['PRINCIPAL_SUPPLIER_TYPE']);
        return;
        break;
    default:
        break;
}

function PageLoad($_principal_supplier_id,$_principal_supplier_type)
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = Principal_Supplier_Master_Model::Load_Principal_Supplier($_principal_supplier_id,$_principal_supplier_type);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'PRINCIPALID'       => $row->_principal_supplier_id,
                'PRINCIPALNAME'     => $row->_principal_supplier_name,
                'STATENAME'     => $row->_state_id,
                'CITYNAME'       => $row->_city_id,
                'ADD1'     => $row->_add1,
                'ADD2'     => $row->_add2,
                'PINCODE'       => $row->_pincode,
                'RANGE'     => $row->_pc_range,
                'DIVISION'     => $row->_pc_division,
                'COMMISSIONRATE'       => $row->_commission_rate,
                'ECC'     => $row->_ecc_codeno,
                'TINNO'     => $row->_tin_no,
                'PANNO'       => $row->_pan_no,
                'BANKNAME'     => $row->_bankname,
                'ACCOUNTNO'     => $row->_accountnumber,
                'BANKADDRESS'       => $row->_bankaddress,
                'RTGS'     => $row->_rtgs,
                'NEFT'     => $row->_neft,
                'ACCOUNTTYPE'     => $row->_accounttype
            )
        );
        $i++;
        
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}

?>
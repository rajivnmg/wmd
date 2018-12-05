<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/Principal_Supplier_Master_Model.php");
include_once( "../../Model/Param/param_model.php");
include_once("root.php");
include_once($root_path."log4php/Logger.php");
Logger::configure($root_path."config.xml");
$logger = Logger::getLogger('Principal_Controller');
$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['PRINCIPALDATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
		$logger->debug($Data);	// to create debug type log file
        $Principal_Supplier_Name = $obj->{'_principal_supplier_name'};
        $ADD1 = $obj->{'_add1'};
        $ADD2 = $obj->{'_add2'};
        $CITYID = $obj->{'_city_id'};
        $STATEID = $obj->{'_state_id'};
        $PINCODE = $obj->{'_pincode'};
        $PS_RANGE = $obj->{'_pc_range'};
        $PS_DIVISION = $obj->{'_pc_division'};
        $PS_COMMISSIONERATE = $obj->{'_commission_rate'};
        $ECC_CODENO = $obj->{'_ecc_codeno'};
        $TINNO = $obj->{'_tin_no'};
        $PANNO = $obj->{'_pan_no'};
        $TYPE = "P";
		$TAX_TYPE = $obj->{'_tax_type'};// Added by Ayush Giri

        $BANK_NAME = $obj->{'_bankname'};
        $BANK_ACCOUNT_NO = $obj->{'_accountnumber'};
        $BANK_ADDRESS = $obj->{'_bankaddress'};
        $RTGS = $obj->{'_rtgs'};
        $NEFT = $obj->{'_neft'};
        $ACCOUNTTYPE = $obj->{'_accounttype'};
        session_start();
      $USERID=$_SESSION["USER"];
		/* BOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        //$PRINCIPAL_SUPPLIER_ID = Principal_Supplier_Master_Model::Insert_Principal_Supplier($Principal_Supplier_Name, $ADD1, $ADD2, $CITYID, $STATEID, $PINCODE, $PS_RANGE,$PS_DIVISION,$PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO, $TYPE,$USERID);
		$PRINCIPAL_SUPPLIER_ID = Principal_Supplier_Master_Model::Insert_Principal_Supplier($Principal_Supplier_Name, $ADD1, $ADD2, $CITYID, $STATEID, $PINCODE, $PS_RANGE,$PS_DIVISION,$PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO, $TYPE, $TAX_TYPE,$USERID);
		/* EOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        $logger->debug($PRINCIPAL_SUPPLIER_ID);	// to create debug type log file
		if($PRINCIPAL_SUPPLIER_ID > 0){
            $Print = Principal_Supplier_Master_Model::Insert_Principal_Supplier_BankDetails($PRINCIPAL_SUPPLIER_ID, $BANK_NAME, $BANK_ACCOUNT_NO,$BANK_ADDRESS, $RTGS, $NEFT, $ACCOUNTTYPE);
            $i = 0;
            while($i < sizeof($obj->{'_EmployeeList'}))
            {
                Pricipal_Supplier_Contact_Info::Insert_Principal_Supplier_ContactInfo($PRINCIPAL_SUPPLIER_ID,$obj->_EmployeeList[$i]->_title,$obj->_EmployeeList[$i]->_first_name,$obj->_EmployeeList[$i]->_last_name,$obj->_EmployeeList[$i]->_dept_id,$obj->_EmployeeList[$i]->_phone,$obj->_EmployeeList[$i]->_email);
                $i++;
            }
			/* BOF to add to add GST details for Principal by Ayush Giri on 12-06-2017 */
			$i = 0;
			while($i < sizeof($obj->{'_GSTList'}))
			{
				Principal_Supplier_GST_Info::Insert_Principal_Supplier_GSTInfo($PRINCIPAL_SUPPLIER_ID,$obj->_GSTList[$i]->_gst_state_id, $obj->_GSTList[$i]->_gst_reg_status, $obj->_GSTList[$i]->_gst_mig_status, $obj->_GSTList[$i]->_gst_no, $obj->_GSTList[$i]->_gst_reg_date, $obj->_GSTList[$i]->_arn_no, $obj->_GSTList[$i]->_perm_gst);
				$i++;
			}
			/* EOF to add to add GST details for Principal by Ayush Giri on 12-06-2017 */
        }
        else{
            return QueryResponse::NO;
        }
        echo json_encode($PRINCIPAL_SUPPLIER_ID);
        return;
        break;
    case QueryModel::SELECT:
        $_principal_supplier_id = $_REQUEST['PRINCIPALID'];
        $result = Principal_Supplier_Master_Model::Load_Principal_Supplier($_principal_supplier_id,"P",1,100);
		//echo 'result<pre>'; print_r($result); echo '<pre>';
        echo json_encode($result);
        return;
        break;
    case QueryModel::UPDATE:
        $Data = $_REQUEST['PRINCIPALDATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
		
		$logger->debug($Data);	// to create debug type log file
        $Result = Principal_Supplier_Master_Model::Update_Principal_Supplier($obj->{'_principal_supplier_id'},$obj->{'_principal_supplier_name'},$obj->{'_add1'}, $obj->{'_add2'}, $obj->{'_pincode'}, $obj->{'_pc_range'},$obj->{'_pc_division'},$obj->{'_commission_rate'}, $obj->{'_ecc_codeno'}, $obj->{'_tin_no'}, $obj->{'_pan_no'}, $obj->{'_city_id'}, $obj->{'_state_id'});
		$Result = Principal_Supplier_Master_Model::Update_Principal_Supplier($obj->{'_principal_supplier_id'},$obj->{'_principal_supplier_name'},$obj->{'_add1'}, $obj->{'_add2'}, $obj->{'_pincode'}, $obj->{'_pc_range'},$obj->{'_pc_division'},$obj->{'_commission_rate'}, $obj->{'_ecc_codeno'}, $obj->{'_tin_no'}, $obj->{'_pan_no'}, $obj->{'_city_id'}, $obj->{'_state_id'}, $obj->{'_tax_type'});
        $logger->debug($Result);	// to create debug type log file
		//$Result = Principal_Supplier_Master_Model::Update_Principal_Supplier_BankDetails($obj->{'_principal_supplier_id'}, $obj->{'_bankname'},$obj->{'_accountnumber'},$obj->{'_bankaddress'}, $obj->{'_rtgs'}, $obj->{'_neft'}, $obj->{'_accounttype'});
        Principal_Supplier_Master_Model::Delete_Principal_Supplier_BankDetails($obj->{'_principal_supplier_id'});
        $Print = Principal_Supplier_Master_Model::Insert_Principal_Supplier_BankDetails($obj->{'_principal_supplier_id'}, $obj->{'_bankname'},$obj->{'_accountnumber'},$obj->{'_bankaddress'}, $obj->{'_rtgs'}, $obj->{'_neft'}, $obj->{'_accounttype'});
		$logger->debug($Print);	// to create debug type log file
	    Pricipal_Supplier_Contact_Info::DeleteItem($obj->{'_principal_supplier_id'});
        $i = 0;
        while($i < sizeof($obj->{'_EmployeeList'}))
        {
            Pricipal_Supplier_Contact_Info::Insert_Principal_Supplier_ContactInfo($obj->{'_principal_supplier_id'},$obj->_EmployeeList[$i]->_title,$obj->_EmployeeList[$i]->_first_name,$obj->_EmployeeList[$i]->_last_name,$obj->_EmployeeList[$i]->_dept_id,$obj->_EmployeeList[$i]->_phone,$obj->_EmployeeList[$i]->_email);
            $i++;
        }
		/* BOF to add GST details for Principal by Ayush Giri on 12-06-2017 */
		Principal_Supplier_GST_Info::DeleteItem($obj->{'_principal_supplier_id'});
        $i = 0;
        while($i < sizeof($obj->{'_GSTList'}))
        {
			Principal_Supplier_GST_Info::Insert_Principal_Supplier_GSTInfo($obj->{'_principal_supplier_id'},$obj->_GSTList[$i]->_gst_state_id, $obj->_GSTList[$i]->_gst_reg_status, $obj->_GSTList[$i]->_gst_mig_status, $obj->_GSTList[$i]->_gst_no, $obj->_GSTList[$i]->_gst_reg_date, $obj->_GSTList[$i]->_arn_no, $obj->_GSTList[$i]->_perm_gst);
            $i++;
        }
		/* EOF to add GST details for Principal by Ayush Giri on 12-06-2017 */
        echo json_encode($Result);
        return;
        break;
    case QueryModel::PAGELOAD:
        $_principal_supplier_id = $_REQUEST['PRINCIPAL_SUPPLIER_ID'];
        PageLoad($_principal_supplier_id);
        return;
        break;
    case "ADD_CONTECTPERSON":
        $Result = Pricipal_Supplier_Contact_Info::Insert_Principal_Supplier_ContactInfo($_REQUEST['PRINCIPAL_SUPPLIER_ID'],$_REQUEST['EMP_TITLE'], $_REQUEST['EMP_FNAME'], $_REQUEST['EMP_LNAME'], $_REQUEST['EMP_DEPT'], $_REQUEST['EMP_PHONE'],$_REQUEST['EMP_EMAIL']);
        $Result = LoadContact($_REQUEST['PRINCIPAL_SUPPLIER_ID'].trim());
		$logger->debug(json_encode($Result));	// to create debug type log file
        echo json_encode($Result);
        return;
        break;
    case "UPDATE_CONTECTPERSON":
        $Result = Pricipal_Supplier_Contact_Info::Update_Principal_Supplier_ContactInfo($_REQUEST['PS_Contact_info_id'],$_REQUEST['EMP_TITLE'], $_REQUEST['EMP_FNAME'], $_REQUEST['EMP_LNAME'], $_REQUEST['EMP_DEPT'], $_REQUEST['EMP_PHONE'],$_REQUEST['EMP_EMAIL']);
       	$logger->debug(json_encode($Result));	// to create debug type log file
		//$Result = LoadContact($_REQUEST['PS_Contact_info_id'].trim());
        echo json_encode($Result);
        return;
        break;
    case "GET_CONTECTPERSON":
        LoadContact($_REQUEST['PRINCIPAL_SUPPLIER_ID']);
		$logger->debug($Data);	// to create debug type log file
        return;
        break;
    case "GET_CONTECTPERSON_DETAILS":
        $_emp_id = $_REQUEST['EMP_ID'];
        $result = Pricipal_Supplier_Contact_Info::Load_Emp($_emp_id);
		$logger->debug(json_encode($result));	// to create debug type log file
        echo json_encode($result);
        return;
        break;
    default:
        break;
}
function LoadContact($_principal_supplier_id)
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = Pricipal_Supplier_Contact_Info::Load_Principal_Supplier($_principal_supplier_id);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){

        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'PS_Contact_info_id'       => $row->_ps_contact_info_id,
                'PRINCIPALID'       => $row->_principal_supplier_id,
                'TITLE'     => $row->_title,
                'FNAME'       => $row->_first_name,
                'LNAME'     => $row->_last_name,
                'DEPT'     => $row->_dept_id,
                'PHONE'       => $row->_phone,
                'EMAIL'     => $row->_email
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}
function PageLoad($_principal_supplier_id)
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = Principal_Supplier_Master_Model::Load_Principal_Supplier($_principal_supplier_id,"P",$start,$rp);
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
                'STATENAME'     => $row->_state_name,
                'CITYNAME'       => $row->_city_name,
                'ADD1'     => $row->_add1,
                'ADD2'     => $row->_add2,
                'PINCODE'       => $row->_pincode,
                'RANGE'     => $row->_pc_range,
                'DIVISION'     => $row->_pc_division,
                'COMMISSIONRATE'       => $row->_commission_rate,
                'ECC'     => $row->_ecc_codeno,
                'TINNO'     => $row->_tin_no,
                'PANNO'       => $row->_pan_no,
                //'BANKNAME'     => $row->_bankname,
                //'ACCOUNTNO'     => $row->_accountnumber,
                //'BANKADDRESS'       => $row->_bankaddress,
                //'RTGS'     => $row->_rtgs,
                //'NEFT'     => $row->_neft,
                //'ACCOUNTTYPE'     => $row->_accounttype,
                'CITYID'     => $row->_city_id,
                'STATEID'     => $row->_state_id
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("principal_supplier_master","P");
    echo json_encode($jsonData);
}

?>

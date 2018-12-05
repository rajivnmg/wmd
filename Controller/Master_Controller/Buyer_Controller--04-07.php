<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Masters/BuyerMaster_Model.php");
include_once( "../../Model/Param/param_model.php");
//Logger::configure("../../config.xml");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('Buyer_Controller');
$Type = null;
if(isset($_REQUEST['TYP'])){
$Type = $_REQUEST['TYP'];
}
if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case QueryModel::INSERT:
		
        $Data = $_REQUEST['BUYERDATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $logger->debug($Data); // function to write in log file
        session_start();
		$USERID=$_SESSION["USER"];
        $Print = BuyerMaster_Model::InsertBuyer($obj->{'_buyer_code'}, $obj->{'_buyer_name'}, $obj->{'_vendor_code'},$obj->{'_buyer_range'}, $obj->{'_division'}, $obj->{'_commission_rate'},$obj->{'_ecc'}, $obj->{'_tin'}, $obj->{'_pan'},$obj->{'_bill_add1'}, $obj->{'_bill_add2'}, $obj->{'_state_id'},$obj->{'_city_id'}, $obj->{'_country_id'}, $obj->{'_location_id'},$obj->{'_pincode'}, $obj->{'_phone'}, $obj->{'_mobile'},$obj->{'_fax'}, $obj->{'_email'}, $obj->{'_executive_id'},$obj->{'_credit_period'},$obj->{'Buyer_Level'},$obj->{'Credit_Limit'}, $obj->{'_tax_type'}, $obj->{'_remarks'},$USERID);
        $logger->debug($Print); // function to write in log file
		if($Print > 0)
        {
            $i = 0;
            while($i < sizeof($obj->{'_EmployeeList'}))
            {
                Buyer_Contact_Info::Insert_Buyer_ContactInfo($Print,$obj->_EmployeeList[$i]->_title,$obj->_EmployeeList[$i]->_first_name,$obj->_EmployeeList[$i]->_last_name,$obj->_EmployeeList[$i]->_dept_id,$obj->_EmployeeList[$i]->_phone,$obj->_EmployeeList[$i]->_email);
                $i++;
            }
            $m = 0;
            while($m < sizeof($obj->{'_DiscountList'}))
            {
                Buyer_Discount::Insert_Buyer_Discount($Print,$obj->_DiscountList[$m]->_principal_id,$obj->_DiscountList[$m]->_discount);
                $m++;
            }
            if($obj->{'_add1'} != "")
            {
                BuyerMaster_Model::InsertBuyerShippingAddress($Print,$obj->{'_check_add'},$obj->{'_add1'},$obj->{'_add2'},$obj->{'_shipping_state_id'},$obj->{'_shipping_city_id'},$obj->{'_shipping_country_id'},$obj->{'_shipping_location_id'},$obj->{'_shipping_pincode'},$obj->{'_shipping_phone'},$obj->{'_shipping_mobile'},$obj->{'_shipping_fax'},$obj->{'_shipping_email'});
                if($obj->{'shipping2add1'} != "" && $obj->{'shipping2add2'} != "")
                {
                    BuyerMaster_Model::InsertBuyerShippingAddress($Print,"",$obj->{'shipping2add1'},$obj->{'shipping2add2'},$obj->{'shippingstate2'},$obj->{'shippingcity2'},$obj->{'shippingcountry2'},$obj->{'shippinglocation2'},$obj->{'shippingpincode2'},$obj->{'shippingphone2'},$obj->{'shippingmobile2'},$obj->{'shippingfax2'},$obj->{'shippingemail2'});
                }
            }
			
			/* BOF to add to add GST details for Buyer by Ayush Giri on 13-06-2017 */
			$i = 0;
			while($i < sizeof($obj->{'_GSTList'}))
			{
				Buyer_GST_Info::Insert_Buyer_GSTInfo($Print,$obj->_GSTList[$i]->_gst_state_id, $obj->_GSTList[$i]->_gst_reg_status, $obj->_GSTList[$i]->_gst_mig_status, $obj->_GSTList[$i]->_gst_no, $obj->_GSTList[$i]->_gst_reg_date, $obj->_GSTList[$i]->_arn_no, $obj->_GSTList[$i]->_perm_gst);
				$i++;
			} 
			/* EOF to add to add GST details for Buyer by Ayush Giri on 13-06-2017 */

        }
        echo json_encode($Print);
        return;
        break;
    case QueryModel::SELECT:		
		if(isset($_REQUEST['BUYERNAME']) && !empty($_REQUEST['BUYERNAME'])){								
			 $result = BuyerMaster_Model::LoadBuyerDetailsByName($_REQUEST['BUYERID'],"A",null,null,$_REQUEST['BUYERNAME']);
		}else{				
			 $result = BuyerMaster_Model::LoadBuyerDetails($_REQUEST['BUYERID'],"A",null,null);
			
		}	
		
        echo json_encode($result);	
        return;
        break;
    case "SELECT_fromMasterAndPayment":

        $result = BuyerMaster_Model::LoadBuyerDetails($_REQUEST['BUYERID'],"B",null,null);
        echo json_encode($result);
        return;
        break;
    case QueryModel::UPDATE:
        $Data = $_REQUEST['BUYERDATA'];
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);
        $logger->debug($Data); // function to write in log file
        $Print = BuyerMaster_Model::UpdateBuyerDetails($obj->{'_buyer_id'},$obj->{'_buyer_name'},$obj->{'_vendor_code'},$obj->{'_buyer_range'},$obj->{'_division'},$obj->{'_commission_rate'},$obj->{'_ecc'},$obj->{'_tin'},$obj->{'_pan'},$obj->{'_bill_add1'},$obj->{'_bill_add2'},$obj->{'_state_id'},$obj->{'_city_id'},$obj->{'_country_id'},$obj->{'_location_id'},$obj->{'_pincode'},$obj->{'_phone'},$obj->{'_mobile'},$obj->{'_fax'},$obj->{'_email'},$obj->{'_executive_id'},$obj->{'_credit_period'},$obj->{'Buyer_Level'},$obj->{'Credit_Limit'},$obj->{'_tax_type'},$obj->{'_remarks'});
        Buyer_Contact_Info::DeleteItem($obj->{'_buyer_id'});
        $i = 0;
        while($i < sizeof($obj->{'_EmployeeList'}))
        {
            Buyer_Contact_Info::Insert_Buyer_ContactInfo($obj->{'_buyer_id'},$obj->_EmployeeList[$i]->_title,$obj->_EmployeeList[$i]->_first_name,$obj->_EmployeeList[$i]->_last_name,$obj->_EmployeeList[$i]->_dept_id,$obj->_EmployeeList[$i]->_phone,$obj->_EmployeeList[$i]->_email);
            $i++;
        }
        Buyer_Discount::DeleteItem($obj->{'_buyer_id'});
        $m = 0;
        while($m < sizeof($obj->{'_DiscountList'}))
        {
            Buyer_Discount::Insert_Buyer_Discount($obj->{'_buyer_id'},$obj->_DiscountList[$m]->_principal_id,$obj->_DiscountList[$m]->_discount);
            $m++;
        }
        BuyerMaster_Model::DeleteBuyerShippingDetails($obj->{'_buyer_id'});
        if($obj->{'_add1'} != "")
            {
                BuyerMaster_Model::InsertBuyerShippingAddress($obj->{'_buyer_id'},$obj->{'_check_add'},$obj->{'_add1'},$obj->{'_add2'},$obj->{'_shipping_state_id'},$obj->{'_shipping_city_id'},$obj->{'_shipping_country_id'},$obj->{'_shipping_location_id'},$obj->{'_shipping_pincode'},$obj->{'_shipping_phone'},$obj->{'_shipping_mobile'},$obj->{'_shipping_fax'},$obj->{'_shipping_email'});
                if($obj->{'shipping2add1'} != "" && $obj->{'shipping2add2'} != "")
                {
                    BuyerMaster_Model::InsertBuyerShippingAddress($obj->{'_buyer_id'},"",$obj->{'shipping2add1'},$obj->{'shipping2add2'},$obj->{'shippingstate2'},$obj->{'shippingcity2'},$obj->{'shippingcountry2'},$obj->{'shippinglocation2'},$obj->{'shippingpincode2'},$obj->{'shippingphone2'},$obj->{'shippingmobile2'},$obj->{'shippingfax2'},$obj->{'shippingemail2'});
                }
            }
		/* BOF to add GST details for Principal by Ayush Giri on 12-06-2017 */
		Buyer_GST_Info::DeleteItem($obj->{'_buyer_id'});
        $i = 0;
        while($i < sizeof($obj->{'_GSTList'}))
        {
			Buyer_GST_Info::Insert_Buyer_GSTInfo($obj->{'_buyer_id'},$obj->_GSTList[$i]->_gst_state_id, $obj->_GSTList[$i]->_gst_reg_status, $obj->_GSTList[$i]->_gst_mig_status, $obj->_GSTList[$i]->_gst_no, $obj->_GSTList[$i]->_gst_reg_date, $obj->_GSTList[$i]->_arn_no, $obj->_GSTList[$i]->_perm_gst);
            $i++;
        }
		/* EOF to add GST details for Principal by Ayush Giri on 12-06-2017 */
        echo json_encode($Print);
        return;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    case "ADD_CONTECTPERSON":
		
        $Result = Buyer_Contact_Info::Insert_Buyer_ContactInfo($_REQUEST['BUYERID'].trim(),$_REQUEST['EMP_TITLE'], $_REQUEST['EMP_FNAME'], $_REQUEST['EMP_LNAME'], $_REQUEST['EMP_DEPT'], $_REQUEST['EMP_PHONE'],$_REQUEST['EMP_EMAIL']);
		$logger->debug($Result); // function to write in log file
        $Result = LoadContact($_REQUEST['BUYERID'].trim());
	
        echo json_encode($Result);
        return;
        break;
    case "UPDATE_CONTECTPERSON":
        $Result = Buyer_Contact_Info::Update_Buyer_ContactInfo($_REQUEST['BUYERCONTACTID'].trim(),$_REQUEST['EMP_TITLE'], $_REQUEST['EMP_FNAME'], $_REQUEST['EMP_LNAME'], $_REQUEST['EMP_DEPT'], $_REQUEST['EMP_PHONE'],$_REQUEST['EMP_EMAIL']);
        $Result = LoadContact($_REQUEST['BUYERCONTACTID'].trim());
        echo json_encode($Result);
        return;
        break;
    case "GET_CONTECTPERSON":
        LoadContact($_REQUEST['BUYERID']);
        return;
        break;
    case "GET_CONTECTPERSON_LIST":
        $Result = Buyer_Contact_Info::Load_BuyerContact($_REQUEST['BUYERID']);
        echo json_encode($Result);
        return;
        break;
    case "GET_CONTECTPERSON_DETAILS":
        $Result = Buyer_Contact_Info::Load_BuyerContactDetails($_REQUEST['EMPID']);
        echo json_encode($Result);
        return;
        break;
    case "ADD_DISCOUNT":
        $Result = Buyer_Discount::Insert_Buyer_Discount($_REQUEST['BUYERID'].trim(),$_REQUEST['PRINCIPALID'], $_REQUEST['BUYER_DISCOUNT']);
        $Result = LoadContact($_REQUEST['BUYERID'].trim());
        echo json_encode($Result);
        return;
        break;
    case "Update_DISCOUNT":
        $Result = Buyer_Discount::Update_Buyer_Discount($_REQUEST['BUYERID'].trim(),$_REQUEST['PRINCIPALID'], $_REQUEST['BUYER_DISCOUNT']);
        $Result = LoadContact($_REQUEST['BUYERID'].trim());
        echo json_encode($Result);
        return;
        break;
    case "GET_DISCOUNT":
        LoadDiscount($_REQUEST['BUYER_ID'].trim(),$_REQUEST['PRINCIPAL_SUPPLIER_ID'].trim());
        return;
        break;
    case "GET_DISCOUNT_DETAILS":
        $Result = Buyer_Discount::Load_Principal_Discount($_REQUEST['BUYERID'],$_REQUEST['PRINCIPALID']);
        echo json_encode($Result);
        return;
        break;
    case "GET_SHIPPINGADDRESS":
        $Result = BuyerMaster_Model::LoadBuyerShippingDetails($_REQUEST['BUYERID']);
        echo json_encode($Result);
        return;
        break;
    case "AUTOCODE":
        $Print = BuyerMaster_Model::GetBuyerCode();
        echo json_encode($Print);
        return;
        break;
    case "GETBUYERLIST":
        $Print = BuyerMaster_Model::GetBuyersNameIdList();
        echo json_encode($Print);
        return;
        break;      
    case QueryModel::SEARCH:
        //SearchBuyer($_REQUEST['coulam'],$_REQUEST['val1'],$_REQUEST['val2']);
		SearchBuyer($_REQUEST['coulam'],$_REQUEST['buyerid'],$_REQUEST['level'],$_REQUEST['State_Id'],$_REQUEST['cityid'],$_REQUEST['location']);
        return;
        break;
    default:
        break;
}

// Get Discount details of principal on the basis of buyer
function LoadDiscount($Buyer_id,$PrincipalId)
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = Buyer_Discount::Load_Principal_Discount($Buyer_id,$PrincipalId);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'BUYERID'       => $row->_buyer_id,
                'PRINCIPAL_SUPPLIER_ID'     => $row->_principal_id,
                'Principal_Supplier_Name'  => $row->_principalname,
                'DISCOUNT'       => $row->_discount
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}

// Load contact details of buyer on the basis of buyer_id
function LoadContact($Buyer_id)
{
    session_start();

    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

    $rows = Buyer_Contact_Info::Load_BuyerContact($Buyer_id);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){

        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'BUYERContactID'       => $row->_buyer_contact_id,
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
function Pageload(){	// Load all Buyer on page load from buyer_master table
    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = BuyerMaster_Model::LoadBuyerDetails(0,"B",$start,$rp);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'BuyerId'       => $row->_buyer_id,
                'BuyerCode'     => $row->_buyer_code,
                'BuyerName'     => $row->_buyer_name,
                'Vendor_Code'       => $row->_vendor_code,
                'Buyer_Range'     => $row->_buyer_range,
                'Division'     => $row->_division,
                'Commissionerate'       => $row->_commission_rate,
                'ECC'     => $row->_ecc,
                'TIN'     => $row->_tin,
                'PAN'       => $row->_pan,
                'Bill_Add1'     => $row->_bill_add1,
                'Bill_Add2'     => $row->_bill_add2,
                'StateId'       => $row->_state_id,
                'CityId'     => $row->_city_id,
                'CountryId'     => $row->_country_id,
                'LocationId'       => $row->_location_id,

                'StateName'       => $row->_state_name,
                'CityName'     => $row->_city_name,
                'LocationName'       => $row->_location_name,
				'Buyer_Level'       => $row->Buyer_Level,
                'Pincode'     => $row->_pincode,
                'Phone'     => $row->_phone,
                'Mobile'       => $row->_mobile,
                'FAX'     => $row->_fax,
                'Email'     => $row->_email,
                'Executive_ID'       => $row->_executive_id,
                'Credit_Period'     => $row->_credit_period,
                'Tax_Type'     => $row->_tax_type,
                'Remarks'     => $row->_remarks
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = ParamModel::CountRecord("buyer_master","");
    echo json_encode($jsonData);
}
function SearchBuyer($col,$buyerid,$level,$State_Id,$cityid,$location){

    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $start = ($page - 1) * $rp;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = BuyerMaster_Model::SearchBuyer($col,$buyerid,$level,$State_Id,$cityid,$location,$start,$rp,$count);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
	
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'BuyerId'       => $row->_buyer_id,
                'BuyerCode'     => $row->_buyer_code,
                'BuyerName'     => $row->_buyer_name,
                'Vendor_Code'       => $row->_vendor_code,
                'Buyer_Range'     => $row->_buyer_range,
                'Division'     => $row->_division,
                'Commissionerate'       => $row->_commission_rate,
                'ECC'     => $row->_ecc,
                'TIN'     => $row->_tin,
                'PAN'       => $row->_pan,
                'Bill_Add1'     => $row->_bill_add1,
                'Bill_Add2'     => $row->_bill_add2,
                'StateId'       => $row->_state_id,
                'CityId'     => $row->_city_id,
                'CountryId'     => $row->_country_id,
                'LocationId'       => $row->_location_id,

                'StateName'       => $row->_state_name,
                'CityName'     => $row->_city_name,
                'LocationName'       => $row->_location_name,
				'Buyer_Level'       => $row->Buyer_Level,

                'Pincode'     => $row->_pincode,
                'Phone'     => $row->_phone,
                'Mobile'       => $row->_mobile,
                'FAX'     => $row->_fax,
                'Email'     => $row->_email,
                'Executive_ID'       => $row->_executive_id,
                'Credit_Period'     => $row->_credit_period,
                'Tax_Type'     => $row->_tax_type,
                'Remarks'     => $row->_remarks
            )
        );
        $i++;

        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = $count;
    echo json_encode($jsonData);
}
?>

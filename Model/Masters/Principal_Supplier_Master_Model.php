<?php
class Principal_Supplier_Master_Model
{
    public $_principal_supplier_id;
    public $_principal_supplier_name;
    public $_add1;
    public $_add2;
    public $_city_id;
    public $_state_id;
    public $_city_name;
    public $_state_name;
    public $_pincode;
    public $_pc_range;
    public $_pc_division;
    public $_commission_rate;
    public $_ecc_codeno;
    public $_tin_no;
    public $_pan_no;
    public $_type;
    public $_bankname;
    public $_accountnumber;
    public $_bankaddress;
    public $_rtgs;
    public $_neft;
    public $_accounttype;
    public $_EmployeeList  = array();
	public $_GSTList  = array(); // Added by Ayush Giri to add GST details for Principal on 09-06-2017

	//public function __construct($principalsupplierid, $principalsuppliername, $add1, $add2, $cityid, $stateid, $pincode, $pcrange,$pcdivision, $commissionrate, $ecccodeno,$tinno,$panno,$type,$bankname,$accountnumber,$bankaddress,$rtgs,$neft,$accounttype,$cityname,$statename,$_EmployeeList){
	public function __construct($principalsupplierid, $principalsuppliername, $add1, $add2, $cityid, $stateid, $pincode, $pcrange,$pcdivision, $commissionrate, $ecccodeno, $tinno, $panno, $type, $tax_type, $bankname, $accountnumber, $bankaddress, $rtgs, $neft,$accounttype, $cityname, $statename, $_EmployeeList, $_GSTList){
		
        $this->_principal_supplier_id = $principalsupplierid;
		$this->_principal_supplier_name = $principalsuppliername;
        $this->_add1 = $add1;
		$this->_add2 = $add2;
		$this->_city_id = $cityid;
        $this->_state_id = $stateid;
        $this->_city_name = $cityname;
        $this->_state_name = $statename;
		$this->_pincode = $pincode;
        $this->_pc_range = $pcrange;
		$this->_pc_division = $pcdivision;
		$this->_commission_rate = $commissionrate;
        $this->_ecc_codeno = $ecccodeno;
		$this->_tin_no = $tinno;
        $this->_pan_no = $panno;
		$this->_type = $type;
		$this->_tax_type = $tax_type; // Added by Ayush Giri 
        $this->_bankname = $bankname;
        $this->_accountnumber = $accountnumber;
        $this->_bankaddress = $bankaddress;
        $this->_rtgs = $rtgs;
        $this->_neft = $neft;
        $this->_accounttype = $accounttype;
        $this->_EmployeeList = $_EmployeeList;
		$this->_GSTList = $_GSTList; // Added by Ayush Giri to add GST details for Principal on 09-06-2017
	}
	public static function  Load_Principal_Supplier($Principal_supplier_id,$Principal_supplier_type, $start, $rp)
	{	
        $result;
        if($Principal_supplier_id > 0)
        {
            $result = self::Get_Principal_Supplier_Details($Principal_supplier_id);
        }
        else
        {
            if($Principal_supplier_type == "P")
            {
                $result = self::Get_Principal_List($start,$rp);
            }
            else if($Principal_supplier_type == "S")
            {
                $result = self::Get_Supplier_List($start,$rp);
            }
        }
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_principal_supplier_id = $Row['Principal_Supplier_Id'];
            $_principal_supplier_name = $Row['Principal_Supplier_Name'];
            $_add1 = $Row['ADD1'];
            $_add2 = $Row['ADD2'];
            $_city_name = $Row['CityName'];
            $_state_name = $Row['StateName'];
            $_city_id = $Row['CityId'];
            $_state_id = $Row['StateId'];
            $_pincode = $Row['PINCODE'];
            $_pc_range = $Row['PS_RANGE'];
            $_pc_division = $Row['PS_DIVISION'];
            $_commission_rate = $Row['PS_COMMISSIONERATE'];
            $_ecc_codeno = $Row['ECC_CODENO'];
            $_tin_no = $Row['TINNO'];
            $_pan_no = $Row['PANNO'];
            $_type = $Row['TYPE'];
			$_tax_type = $Row['Tax_Type']; // Added by Ayush Giri 
            if($Principal_supplier_id > 0)
            {
                $BankDetails = self::Get_Principal_Supplier_BankDetails($_principal_supplier_id);
                $BankRow = mysql_fetch_array($BankDetails, MYSQL_ASSOC);
                if(sizeof($BankRow) > 0)
                {
                    $_bankname = $BankRow['BANK_NAME'];
                    $_accountnumber = $BankRow['BANK_ACCOUNT_NO'];
                    $_bankaddress = $BankRow['BANK_ADDRESS'];
                    $_rtgs = $BankRow['RTGS'];
                    $_neft = $BankRow['NEFT'];
                    $_accounttype = $BankRow['ACCOUNTTYPE'];
                }
                $_EmployeeList = Pricipal_Supplier_Contact_Info::Load_Principal_Supplier($_principal_supplier_id);
				$_GSTList = Principal_Supplier_GST_Info::Load_Principal_Supplier($_principal_supplier_id); // Added by Ayush Giri to add GST details for Principal on 12-06-2017
            }
            else
            {
                $_bankname = null;
                $_accountnumber = null;
                $_bankaddress = null;
                $_rtgs = null;
                $_neft = null;
                $_accounttype = null;
                $_EmployeeList = null;
				$_GSTList = null; // Added by Ayush Giri to add GST details for Principal on 12-06-2017
            }
			/* BOF to add to add GST details for Principal by Ayush Giri on 12-06-2017 */
            //$newObj = new Principal_Supplier_Master_Model($_principal_supplier_id,$_principal_supplier_name,$_add1,$_add2,$_city_id,$_state_id,$_pincode,$_pc_range,$_pc_division,$_commission_rate,$_ecc_codeno,$_tin_no,$_pan_no,$_type,$_bankname,$_accountnumber,$_bankaddress,$_rtgs,$_neft,$_accounttype,$_city_name,$_state_name,$_EmployeeList);
			$newObj = new Principal_Supplier_Master_Model($_principal_supplier_id, $_principal_supplier_name, $_add1, $_add2,$_city_id, $_state_id, $_pincode, $_pc_range, $_pc_division, $_commission_rate, $_ecc_codeno, $_tin_no, $_pan_no, $_type, $_tax_type, $_bankname, $_accountnumber, $_bankaddress, $_rtgs, $_neft, $_accounttype, $_city_name, $_state_name, $_EmployeeList, $_GSTList);
			/* EOF to add to add GST details for Principal by Ayush Giri on 12-06-2017 */
            $objArray[$i] = $newObj;
            $i++;
		}
		//echo 'objArray<pre>'; print_r($objArray); echo '<pre>';
		return $objArray;
	}
	/* BOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
    //public static function Insert_Principal_Supplier($Principal_Supplier_Name, $ADD1, $ADD2, $CITYID, $STATEID, $PINCODE, $PS_RANGE,$PS_DIVISION, $PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO, $TYPE,$UserId){
	public static function Insert_Principal_Supplier($Principal_Supplier_Name, $ADD1, $ADD2, $CITYID, $STATEID, $PINCODE, $PS_RANGE,$PS_DIVISION, $PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO, $TYPE, $TAX_TYPE, $UserId){
	/* EOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        $date = date("Y-m-d");
        
        $Principal_Supplier_Name=str_replace("'","\'",$Principal_Supplier_Name);
        $ADD1=str_replace("'","\'",$ADD1);
        $ADD2=str_replace("'","\'",$ADD2);
        $PS_RANGE=str_replace("'","\'",$PS_RANGE);
        $PS_DIVISION=str_replace("'","\'",$PS_DIVISION);
        $PS_COMMISSIONERATE=str_replace("'","\'",$PS_COMMISSIONERATE);
        
        /*$Principal_Supplier_Name	 =  mysql_escape_string($Principal_Supplier_Name);
		$ADD1	 =  mysql_escape_string($ADD1);
		$ADD2	 =  mysql_escape_string($ADD2);
		$PS_RANGE	 =  mysql_escape_string($PS_RANGE);
		$PS_DIVISION	 =  mysql_escape_string($PS_DIVISION);
		$PS_COMMISSIONERATE	 =  mysql_escape_string($PS_COMMISSIONERATE);
		*/
		/* BOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        //$Query = "INSERT INTO principal_supplier_master (Principal_Supplier_Name, ADD1, ADD2, CITYID, STATEID, PINCODE, PS_RANGE,PS_DIVISION, PS_COMMISSIONERATE, ECC_CODENO, TINNO, PANNO, TYPE,UserId, InsertDate) VALUES ('".$Principal_Supplier_Name."','".$ADD1."','".$ADD2."','".$CITYID."','".$STATEID."','".$PINCODE."', '".$PS_RANGE."', '".$PS_DIVISION."', '".$PS_COMMISSIONERATE."', '".$ECC_CODENO."', '".$TINNO."','".$PANNO."','".$TYPE."','$UserId','$date')";
		$Query = "INSERT INTO principal_supplier_master (Principal_Supplier_Name, ADD1, ADD2, CITYID, STATEID, PINCODE, PS_RANGE,PS_DIVISION, PS_COMMISSIONERATE, ECC_CODENO, TINNO, PANNO, TYPE, Tax_Type, UserId, InsertDate) VALUES ('".$Principal_Supplier_Name."','".$ADD1."','".$ADD2."','".$CITYID."','".$STATEID."','".$PINCODE."', '".$PS_RANGE."', '".$PS_DIVISION."', '".$PS_COMMISSIONERATE."', '".$ECC_CODENO."', '".$TINNO."','".$PANNO."','".$TYPE."','".$TAX_TYPE."','$UserId','$date')";
		/* EOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
           echo($Query);
			$Result = DBConnection::InsertQuery($Query);
			if($Result != QueryResponse::ERROR){
				return $Result;
			}
			else{
				return 0;
			}
	}
	/* BOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
    //public static function Update_Principal_Supplier($PRINCIPAL_SUPPLIER_ID,$Principal_Supplier_Name, $ADD1, $ADD2, $PINCODE, $PS_RANGE,$PS_DIVISION, $PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO,$CITYID, $STATEID){
	public static function Update_Principal_Supplier($PRINCIPAL_SUPPLIER_ID,$Principal_Supplier_Name, $ADD1, $ADD2, $PINCODE, $PS_RANGE,$PS_DIVISION, $PS_COMMISSIONERATE, $ECC_CODENO, $TINNO, $PANNO,$CITYID, $STATEID, $TAX_TYPE){
	/* EOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
		
        $Principal_Supplier_Name=str_replace("'","\'",$Principal_Supplier_Name);
        $ADD1=str_replace("'","\'",$ADD1);
        $ADD2=str_replace("'","\'",$ADD2);
        $PS_RANGE=str_replace("'","\'",$PS_RANGE);
        $PS_DIVISION=str_replace("'","\'",$PS_DIVISION);
        $PS_COMMISSIONERATE=str_replace("'","\'",$PS_COMMISSIONERATE);
                
        /*
        $Principal_Supplier_Name	 =  mysql_escape_string($Principal_Supplier_Name);
		$ADD1	 =  mysql_escape_string($ADD1);
		$ADD2	 =  mysql_escape_string($ADD2);
		$PS_RANGE	 =  mysql_escape_string($PS_RANGE);
		$PS_DIVISION	 =  mysql_escape_string($PS_DIVISION);
		$PS_COMMISSIONERATE	 =  mysql_escape_string($PS_COMMISSIONERATE);
		*/ 
		
        /* BOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        //$Query = "UPDATE principal_supplier_master SET Principal_Supplier_Name = '".$Principal_Supplier_Name."', ADD1 = '".$ADD1."', ADD2 = '".$ADD2."',CITYID = '".$CITYID."', STATEID = '".$STATEID."', PINCODE = '".$PINCODE."',PS_RANGE = '".$PS_RANGE."',PS_DIVISION = '".$PS_DIVISION."', PS_COMMISSIONERATE = '".$PS_COMMISSIONERATE."', ECC_CODENO = '".$ECC_CODENO."', TINNO = '".$TINNO."', PANNO = '".$PANNO."' WHERE Principal_Supplier_Id = $PRINCIPAL_SUPPLIER_ID";
		
		$Query = "UPDATE principal_supplier_master SET Principal_Supplier_Name = '".$Principal_Supplier_Name."', ADD1 = '".$ADD1."', ADD2 = '".$ADD2."',CITYID = '".$CITYID."', STATEID = '".$STATEID."', PINCODE = '".$PINCODE."',PS_RANGE = '".$PS_RANGE."',PS_DIVISION = '".$PS_DIVISION."', PS_COMMISSIONERATE = '".$PS_COMMISSIONERATE."', ECC_CODENO = '".$ECC_CODENO."', TINNO = '".$TINNO."', PANNO = '".$PANNO."', Tax_Type = '".$TAX_TYPE."' WHERE Principal_Supplier_Id = $PRINCIPAL_SUPPLIER_ID";
		/* EOF to add to add GST details for Principal by Ayush Giri on 21-06-2017 */
        $Result = DBConnection::UpdateQuery($Query);
        if($Result != QueryResponse::ERROR){
            return $Result;
        }
        else{
            return 0;
        }
        
	}
    public static function Insert_Principal_Supplier_BankDetails($PRINCIPAL_SUPPLIER_ID, $BANK_NAME, $BANK_ACCOUNT_NO, $BANK_ADDRESS, $RTGS, $NEFT, $ACCOUNTTYPE){
       
       // added on 01-JUNE-2016 due to Handle Special Character
       $BANK_NAME	 =  mysql_escape_string($BANK_NAME);
       $BANK_ACCOUNT_NO	 =  mysql_escape_string($BANK_ACCOUNT_NO);
       $BANK_ADDRESS	 =  mysql_escape_string($BANK_ADDRESS);
       $RTGS	 =  mysql_escape_string($RTGS);
       $NEFT	 =  mysql_escape_string($NEFT);
       $ACCOUNTTYPE	 = mysql_escape_string($ACCOUNTTYPE);
	
        $Query = "INSERT INTO principal_supplier_bankdetails (PRINCIPAL_SUPPLIER_ID, BANK_NAME, BANK_ACCOUNT_NO, BANK_ADDRESS, RTGS, NEFT, ACCOUNTTYPE)VALUES($PRINCIPAL_SUPPLIER_ID,'".$BANK_NAME."','".$BANK_ACCOUNT_NO."', '".$BANK_ADDRESS."', '".$RTGS."', '".$NEFT."', '$ACCOUNTTYPE' )";
        $Result = DBConnection::InsertQuery($Query);
        if($Result != QueryResponse::ERROR){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function Update_Principal_Supplier_BankDetails($PRINCIPAL_SUPPLIER_ID, $BANK_NAME, $BANK_ACCOUNT_NO, $BANK_ADDRESS, $RTGS, $NEFT, $ACCOUNTTYPE){
		
		 // added on 01-JUNE-2016 due to Handle Special Character
		$BANK_NAME	 =  mysql_escape_string($BANK_NAME);
		$BANK_ACCOUNT_NO	 =  mysql_escape_string($BANK_ACCOUNT_NO);
		$BANK_ADDRESS	 =  mysql_escape_string($BANK_ADDRESS);
		$RTGS	 =  mysql_escape_string($RTGS);
		$NEFT	 =  mysql_escape_string($NEFT);
		$ACCOUNTTYPE	 = mysql_escape_string($ACCOUNTTYPE);
		
        $Query = "INSERT INTO principal_supplier_bankdetails (PRINCIPAL_SUPPLIER_ID, BANK_NAME, BANK_ACCOUNT_NO, BANK_ADDRESS, RTGS, NEFT, ACCOUNTTYPE)VALUES($PRINCIPAL_SUPPLIER_ID,'".$BANK_NAME."','".$BANK_ACCOUNT_NO."', '".$BANK_ADDRESS."', '".$RTGS."', '".$NEFT."', '$ACCOUNTTYPE' ) ON DUPLICATE KEY UPDATE PRINCIPAL_SUPPLIER_ID = $PRINCIPAL_SUPPLIER_ID";
        //$Query = "UPDATE principal_supplier_bankdetails SET BANK_NAME = '$BANK_NAME', BANK_ACCOUNT_NO = $BANK_ACCOUNT_NO, BANK_ADDRESS = '$BANK_ADDRESS', RTGS = '$RTGS', NEFT = '$NEFT', ACCOUNTTYPE = '$ACCOUNTTYPE' WHERE PRINCIPAL_SUPPLIER_ID = $PRINCIPAL_SUPPLIER_ID";
        $Result = DBConnection::UpdateQuery($Query);

        if($Result != QueryResponse::ERROR){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
	}
    public static function Delete_Principal_Supplier_BankDetails($PRINCIPAL_SUPPLIER_ID){
        $Query = "DELETE FROM principal_supplier_bankdetails WHERE PRINCIPAL_SUPPLIER_ID = $PRINCIPAL_SUPPLIER_ID";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function Get_Principal_Supplier_BankDetails($Principal_supplier_id){
        $Query = "SELECT * FROM principal_supplier_bankdetails WHERE PRINCIPAL_SUPPLIER_ID = $Principal_supplier_id";
        $Result = DBConnection::SelectQuery($Query);
        return $Result;
    }
    public static function Get_Principal_Supplier_Details($Principal_supplier_id){
        //psb.*, INNER JOIN principal_supplier_bankdetails AS psb ON psb.PRINCIPAL_SUPPLIER_ID = psm.Principal_Supplier_Id
		$Query = "SELECT psm.*,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM principal_supplier_master psm INNER JOIN city_master as cm ON psm.CITYID = cm.CityId INNER JOIN state_master as sm ON psm.STATEID = sm.StateId WHERE psm.Principal_Supplier_Id = $Principal_supplier_id";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

    public static function Get_Supplier_List($start = null,$rp = null){
    //psb.*, INNER JOIN principal_supplier_bankdetails AS psb ON psb.PRINCIPAL_SUPPLIER_ID = psm.Principal_Supplier_Id
		$Query = "SELECT psm.*,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM principal_supplier_master psm INNER JOIN city_master as cm ON psm.CITYID = cm.CityId INNER JOIN state_master as sm ON psm.STATEID = sm.StateId WHERE psm.TYPE = 'S' ORDER BY psm.Principal_Supplier_Id ASC";
		if($start === null)
        {
        }
        else
        {
           $Query = $Query." LIMIT $start , $rp";
        }
       // echo $Query; 
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function Get_Principal_List($start = null,$rp = null){
    //psb.*, INNER JOIN principal_supplier_bankdetails AS psb ON psb.PRINCIPAL_SUPPLIER_ID = psm.Principal_Supplier_Id
		$Query = "SELECT psm.*,cm.CityName,cm.CityId,sm.StateId,sm.StateName FROM principal_supplier_master psm INNER JOIN city_master as cm ON psm.CITYID = cm.CityId INNER JOIN state_master as sm ON psm.STATEID = sm.StateId WHERE psm.TYPE = 'P' ORDER BY psm.Principal_Supplier_Id ASC";
		if($start === null)
        {
        }
        else
        {
           $Query = $Query." LIMIT $start , $rp";
        }
//echo $Query;exit;
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function Get_principal_supplier_name($principlaaId)
    {
        $Query = "SELECT * FROM principal_supplier_master";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}

class Pricipal_Supplier_Contact_Info
{
    public $_principal_supplier_id;
    public $_title;
    public $_first_name;
    public $_last_name;
    public $_dept_id;
    public $_phone;
    public $_email;
    public $titlename;
    public $deptname;

    public function __construct($principalsuppliercontactinfoid,$title,$firstname,$lastname,$deptid,$phone,$email,$titlename,$deptname){

        $this->_principal_supplier_id = $principalsuppliercontactinfoid;
		$this->_title = $title;
        $this->_first_name = $firstname;
		$this->_last_name = $lastname;
		$this->_dept_id = $deptid;
        $this->_phone = $phone;
		$this->_email = $email;
        $this->titlename = $titlename;
		$this->deptname = $deptname;
	}
    public static function  Load_Emp($_emp_id){
        $result = self::Get_ContactInfo_Details($_emp_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_principal_supplier_id = $Row['PRINCIPAL_SUPPLIER_ID'];
            $_title = $Row['TITLEID'];
            $_first_name = $Row['FIRSTNAME'];
            $_last_name = $Row['LASTNAME'];
            $_dept_id = $Row['DEPT_ID'];
            $_phone = $Row['PHONE'];
            $_email = $Row['EMAIL'];
            $titlename = $Row['titlename'];
            $deptname = $Row['departmentname'];
            $newObj = new Pricipal_Supplier_Contact_Info($_principal_supplier_id,$_title,$_first_name,$_last_name,$_dept_id,$_phone,$_email,$titlename,$deptname);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function  Load_Principal_Supplier($Principal_supplier_id){
        $result = self::Get_Principal_Supplier_ContactInfo_List($Principal_supplier_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_principal_supplier_id = $Row['PRINCIPAL_SUPPLIER_ID'];
            $_title = $Row['TITLEID'];
            $_first_name = $Row['FIRSTNAME'];
            $_last_name = $Row['LASTNAME'];
            $_dept_id = $Row['DEPT_ID'];
            $_phone = $Row['PHONE'];
            $_email = $Row['EMAIL'];
            $titlename = $Row['titlename'];
            $deptname = $Row['departmentname'];
            $newObj = new Pricipal_Supplier_Contact_Info($_principal_supplier_id,$_title,$_first_name,$_last_name,$_dept_id,$_phone,$_email,$titlename,$deptname);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function Insert_Principal_Supplier_ContactInfo($PRINCIPAL_SUPPLIER_ID, $TITLEID, $FIRSTNAME, $LASTNAME, $DEPT_ID, $PHONE, $EMAIL){
		
		$FIRSTNAME	 =  mysql_escape_string($FIRSTNAME);
		$LASTNAME	 =  mysql_escape_string($LASTNAME);
		$PHONE	 =  mysql_escape_string($PHONE);
		$EMAIL	 =  mysql_escape_string($EMAIL);
			
        $Query = "INSERT INTO principal_supplier_contact_info (PRINCIPAL_SUPPLIER_ID, TITLEID, FIRSTNAME, LASTNAME, DEPT_ID, PHONE, EMAIL) VALUES ($PRINCIPAL_SUPPLIER_ID,$TITLEID,'".$FIRSTNAME."','".$LASTNAME."','".$DEPT_ID."','".$PHONE."','".$EMAIL."')";
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}
    public static function DeleteItem($PRINCIPAL_SUPPLIER_ID){
        $Query = "DELETE FROM principal_supplier_contact_info WHERE PRINCIPAL_SUPPLIER_ID = $PRINCIPAL_SUPPLIER_ID";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function Update_Principal_Supplier_ContactInfo($TITLEID, $FIRSTNAME, $LASTNAME, $DEPT_ID, $PHONE, $EMAIL){
		
		$FIRSTNAME	 =  mysql_escape_string($FIRSTNAME);
		$LASTNAME	 =  mysql_escape_string($LASTNAME);
		$PHONE	 =  mysql_escape_string($PHONE);
		$EMAIL	 =  mysql_escape_string($EMAIL);
		
		$Query = "UPDATE principal_supplier_contact_info SET TITLEID = $TITLEID, FIRSTNAME = '".$FIRSTNAME."', LASTNAME = '".$LASTNAME."', DEPT_ID = '".$DEPT_ID."', PHONE = '".$PHONE."', EMAIL = '".$EMAIL."'";
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}

    public static function Get_Principal_Supplier_ContactInfo_List($Principal_supplier_id){
        //$Query = "SELECT * FROM principal_supplier_contact_info WHERE PRINCIPAL_SUPPLIER_ID = $Principal_supplier_id";
        $Query = "SELECT psci.*,tm.titlename,dm.departmentname FROM principal_supplier_contact_info as psci
INNER JOIN title_master as tm ON psci.TITLEID = tm.titleid
INNER JOIN department_master as dm ON psci.DEPT_ID = dm.departmentid
WHERE psci.PRINCIPAL_SUPPLIER_ID =  $Principal_supplier_id ";

		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function Get_ContactInfo_Details($emp_id){
        $Query = "SELECT * FROM principal_supplier_contact_info";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}

/* BOF to add to add GST details for Principal by Ayush Giri on 09-06-2017 */
class Principal_Supplier_GST_Info
{
    public $_principal_supplier_id;
    public $_gst_state_id;
	public $_gst_state_name;
    public $_gst_reg_status;
	public $_gst_reg_status_name;
    public $_gst_mig_status;
	public $_gst_mig_status_name;
    public $_gst_no;
    public $_gst_reg_date;
    public $_arn_no;
    public $_perm_gst;

    public function __construct($principal_supplier_id, $gst_state_id, $gst_state_name, $gst_reg_status, $gst_reg_status_name, $gst_mig_status, $gst_mig_status_name, $gst_no, $gst_reg_date, $arn_no, $perm_gst){
        $this->_principal_supplier_id = $principal_supplier_id;
		$this->_gst_state_id = $gst_state_id;
		$this->_gst_state_name = $gst_state_name;
        $this->_gst_reg_status = $gst_reg_status;
		$this->_gst_reg_status_name = $gst_reg_status_name;
		$this->_gst_mig_status = $gst_mig_status;
		$this->_gst_mig_status_name = $gst_mig_status_name;
		$this->_gst_no = $gst_no;
        $this->_gst_reg_date = $gst_reg_date;
		$this->_arn_no = $arn_no;
        $this->_perm_gst = $perm_gst;
	}
    public static function  Load_GST($_gst_id){
        $result = self::Get_GSTInfo_Details($_gst_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_principal_supplier_id = $Row['principal_supplier_id'];
            $_gst_state_id = $Row['gst_state_id'];
			$_gst_state_name = $Row['gst_state_name'];
            $_gst_reg_status = $Row['gst_reg_status'];
			$_gst_reg_status_name = $Row['gst_reg_status_name'];
            $_gst_mig_status = $Row['gst_mig_status'];
			$_gst_mig_status_name = $Row['gst_mig_status_name'];
            $_gst_no = $Row['gst_no'];
            $_gst_reg_date = $Row['gst_reg_date'];
            $_arn_no = $Row['arn_no'];
            $_perm_gst = $Row['perm_gst'];
            $newObj = new Principal_Supplier_GST_Info($_principal_supplier_id, $_gst_state_id, $_gst_state_name, $_gst_reg_status, $_gst_reg_status_name, $_gst_mig_status, $_gst_mig_status_name, $_gst_no,$_gst_reg_date, $_arn_no, $_perm_gst);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function  Load_Principal_Supplier($Principal_supplier_id){
        $result = self::Get_Principal_Supplier_GSTInfo_List($Principal_supplier_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_principal_supplier_id = $Row['principal_supplier_id'];
            $_gst_state_id = $Row['gst_state_id'];
			$_gst_state_name = $Row['gst_state_name'];
            $_gst_reg_status = $Row['gst_reg_status'];
			$_gst_reg_status_name = $Row['gst_reg_status_name'];
            $_gst_mig_status = $Row['gst_mig_status'];
			$_gst_mig_status_name = $Row['gst_mig_status_name'];
            $_gst_no = $Row['gst_no'];
            $_gst_reg_date = $Row['gst_reg_date'];
            $_arn_no = $Row['arn_no'];
            $_perm_gst = $Row['perm_gst'];
            $newObj = new Principal_Supplier_GST_Info($_principal_supplier_id, $_gst_state_id, $_gst_state_name, $_gst_reg_status, $_gst_reg_status_name, $_gst_mig_status, $_gst_mig_status_name, $_gst_no,$_gst_reg_date, $_arn_no, $_perm_gst);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function Insert_Principal_Supplier_GSTInfo($principal_supplier_id, $gst_state_id, $gst_reg_status, $gst_mig_status, $gst_no, $gst_reg_date, $arn_no, $perm_gst){
		
		$principal_supplier_id	 =  mysql_escape_string($principal_supplier_id);
		$gst_state_id	 =  mysql_escape_string($gst_state_id);
		$gst_reg_status	 =  mysql_escape_string($gst_reg_status);
		$gst_mig_status	 =  mysql_escape_string($gst_mig_status);
		$gst_no	 =  mysql_escape_string($gst_no);
		$gst_reg_date	 =  mysql_escape_string($gst_reg_date);
		$arn_no	 =  mysql_escape_string($arn_no);
		$perm_gst =  mysql_escape_string($perm_gst);
			
        $Query = "INSERT INTO principal_supplier_gst_details (principal_supplier_id, gst_state_id, gst_reg_status, gst_mig_status, gst_no, gst_reg_date, arn_no, perm_gst) VALUES('".$principal_supplier_id."', '".$gst_state_id."', '".$gst_reg_status."', '".$gst_mig_status."', '".$gst_no."', '".$gst_reg_date."', '".$arn_no."', '".$perm_gst."')";
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}
    public static function DeleteItem($principal_supplier_id){
        $Query = "DELETE FROM principal_supplier_gst_details WHERE principal_supplier_id = $principal_supplier_id";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    /* public static function Update_Principal_Supplier_GSTInfo($principal_supplier_id, $gst_state_id, $gst_reg_status, $gst_mig_status, $gst_no, $gst_reg_date, $arn_no, $perm_gst){
		
		$principal_supplier_id	 =  mysql_escape_string($principal_supplier_id);
		$gst_state_id	 =  mysql_escape_string($gst_state_id);
		$gst_reg_status	 =  mysql_escape_string($gst_reg_status);
		$gst_mig_status	 =  mysql_escape_string($gst_mig_status);
		$gst_no	 =  mysql_escape_string($gst_no);
		$gst_reg_date	 =  mysql_escape_string($gst_reg_date);
		$arn_no	 =  mysql_escape_string($arn_no);
		$perm_gst =  mysql_escape_string($perm_gst);
		
		$Query = "UPDATE principal_supplier_gst_details SET gst_state_id = '".$gst_state_id."', gst_reg_status = '".$gst_reg_status."', gst_mig_status = '".$gst_mig_status."', gst_no = '".$gst_no."', gst_reg_date = '".$gst_reg_date."', arn_no = '".$arn_no."', perm_gst = '".$perm_gst."' WHERE principal_supplier_id = ".$principal_supplier_id;
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	} */

    public static function Get_Principal_Supplier_GSTInfo_List($Principal_supplier_id){
        $Query = "SELECT psgd.principal_supplier_id, psgd.gst_state_id, sm.StateName AS gst_state_name, psgd.gst_reg_status, IF(psgd.gst_reg_status = 1,'Active','Disabled') AS gst_reg_status_name, psgd.gst_mig_status, IF(psgd.gst_mig_status = 1,'Active','Disabled') AS gst_mig_status_name, psgd.gst_no, psgd.gst_reg_date, psgd.arn_no, psgd.perm_gst FROM principal_supplier_gst_details psgd JOIN state_master sm ON sm.StateId = psgd.gst_state_id WHERE psgd.principal_supplier_id = ".$Principal_supplier_id;

		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function Get_GSTInfo_Details($gst_id){
        $Query = "SELECT psgd.principal_supplier_id, psgd.gst_state_id, sm.StateName AS gst_state_name, psgd.gst_reg_status, IF(psgd.gst_reg_status = 1,'Active','Disabled') AS gst_reg_status_name, psgd.gst_mig_status, IF(psgd.gst_mig_status = 1,'Active','Disabled') AS gst_mig_status_name, psgd.gst_no, psgd.gst_reg_date, psgd.arn_no, psgd.perm_gst FROM principal_supplier_gst_details psgd JOIN state_master sm ON sm.StateId = psgd.gst_state_id";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}
/* EOF to add to add GST details for Principal by Ayush Giri on 09-06-2017 */

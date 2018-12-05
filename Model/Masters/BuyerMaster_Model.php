<?php
class BuyerMaster_Model
{
    public $_buyer_id;
    public $_buyer_code;
    public $_buyer_name;
    public $_vendor_code;
    public $_buyer_range;
    public $_division;
    public $_commission_rate;
    public $_ecc;
    public $_tin;
    public $_pan;
    public $_bill_add1;
    public $_bill_add2;
    public $_state_id;
    public $_city_id;
    public $_country_id;
    public $_location_id;
    public $_state_name;
    public $_city_name;
    public $_location_name;
    public $_pincode;
    public $_phone;
    public $_mobile;
    public $_fax;
    public $_email;
    public $_executive_id;
    public $_credit_period;
    public $_tax_type;
    public $_remarks;
    public $_userid;
    public $_insert_date;
    public $Buyer_Level;
    public $Credit_Limit;
    public $_check_add;
    public $_add1;
    public $_add2;
    public $_shipping_country_id;
    public $_shipping_state_id;
    public $_shipping_city_id;
    public $_shipping_location_id;
    public $_shipping_pincode;
    public $_shipping_phone;
    public $_shipping_mobile;
    public $_shipping_fax;
    public $_shipping_email;
    public $_shipping_buyerid;

    public $_principal_supplier_id;
    public $_discount;

    public $_EmployeeList  = array();
    public $_DiscountList  = array();
	/* BOF for adding GST by Ayush Giri on 13-06-2017 */
	public $_GSTList  = array();

	
    //public function __construct($buyerid, $buyercode, $buyername, $vendorcode, $buyerrange, $division, $commissionrate,$ecc, $tin, $pan, $billadd1, $billadd2, $stateid, $cityid, $countryid,$locationid, $pincode, $phone, $mobile, $fax, $email, $executiveid, $creditperiod,$Buyer_Level,$Credit_Limit,$taxtype, $remarks,$statename, $cityname, $locationname,$shippingbuyerid,$checkadd, $add1, $add2, $shippingcountryid, $shippingstateid, $shippingcityid, $shippinglocationid, $shippingpincode, $shippingphone, $shippingmobile, $shippingfax, $shippingemail,$_EmployeeList,$_DiscountList)
	public function __construct($buyerid, $buyercode, $buyername, $vendorcode, $buyerrange, $division, $commissionrate,$ecc, $tin, $pan, $billadd1, $billadd2, $stateid, $cityid, $countryid,$locationid, $pincode, $phone, $mobile, $fax, $email, $executiveid, $creditperiod, $Buyer_Level, $Credit_Limit, $taxtype, $remarks, $statename, $cityname, $locationname, $shippingbuyerid, $checkadd, $add1, $add2, $shippingcountryid, $shippingstateid, $shippingcityid, $shippinglocationid, $shippingpincode, $shippingphone, $shippingmobile, $shippingfax, $shippingemail, $_EmployeeList, $_DiscountList, $_GSTList)
    {
	/* EOF for adding GST by Ayush Giri on 13-06-2017 */
		$this->_buyer_id = $buyerid;
        $this->_buyer_code = $buyercode;
        $this->_buyer_name = $buyername;
        $this->_vendor_code = $vendorcode;
		$this->_buyer_range = $buyerrange;
        $this->_division = $division;
        $this->_commission_rate = $commissionrate;
        $this->_ecc = $ecc;
		$this->_tin = $tin;
        $this->_pan = $pan;
        $this->_bill_add1 = $billadd1;
		$this->_bill_add2 = $billadd2;
        $this->_state_id = $stateid;
		$this->_city_id = $cityid;
		$this->_country_id = $countryid;
        $this->_location_id = $locationid;
		$this->_pincode = $pincode;
        $this->_phone = $phone;
		$this->_mobile = $mobile;
		$this->_fax = $fax;
        $this->_email = $email;
		$this->_executive_id = $executiveid;
        $this->_credit_period = $creditperiod;
		$this->_tax_type = $taxtype;
		$this->_remarks = $remarks;
        $this->_state_name = $statename;
        $this->_city_name = $cityname;
        $this->_location_name = $locationname;
        $this->Buyer_Level = $Buyer_Level;
        $this->Credit_Limit = $Credit_Limit;

        $this->_shipping_buyerid = $shippingbuyerid;
        $this->_check_add = $checkadd;
        $this->_add1 = $add1;
        $this->_add2 = $add2;
        $this->_shipping_country_id = $shippingcountryid;
        $this->_shipping_state_id = $shippingstateid;
        $this->_shipping_city_id = $shippingcityid;
        $this->_shipping_location_id = $shippinglocationid;
        $this->_shipping_pincode = $shippingpincode;
        $this->_shipping_phone = $shippingphone;
        $this->_shipping_mobile = $shippingmobile;
        $this->_shipping_fax = $shippingfax;
        $this->_shipping_email = $shippingemail;

        $this->_EmployeeList = $_EmployeeList;
        $this->_DiscountList = $_DiscountList;
		$this->_GSTList = $_GSTList;  // Added by Ayush Giri for GST
	}

    public static function  LoadBuyerShippingDetails($BuyerId)
	{
        $result = self::GetBuyerShippingDetails($BuyerId);

		$objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_check_add = $Row['CHECK_ADD']; $_add1 = $Row['ADD1']; $_add2 = $Row['ADD2']; $_shipping_country_id = $Row['COUNTRYID']; $_shipping_state_id = $Row['STATEID']; $_shipping_city_id = $Row['CITYID']; $_shipping_location_id = $Row['LOCATIONID']; $_shipping_pincode = $Row['PINCODE'];  $_shipping_phone = $Row['PHONE']; $_shipping_mobile = $Row['MOBILE']; $_shipping_fax = $Row['FAX']; $_shipping_email = $Row['EMAIL'];$_shipping_buyerid = $Row['shipping_add_id'];

            $newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate,$_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id,$_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period,$Buyer_Level,$Credit_Limit,$_tax_type, $_remarks,$_state_name,$_city_name,$_location_name,$_shipping_buyerid,$_check_add, $_add1, $_add2, $_shipping_country_id, $_shipping_state_id, $_shipping_city_id, $_shipping_location_id,$_shipping_pincode, $_shipping_phone, $_shipping_mobile, $_shipping_fax, $_shipping_email);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function  SearchBuyer($col,$buyerid,$level,$State_Id,$cityid,$location,$start,$rp,&$count)
	{
        $Query = "SELECT bm.*,cm.CityId,lm.LocationId,sm.StateId,cm.CityName,lm.LocationName,sm.StateName FROM buyer_master as bm INNER JOIN city_master as cm ON bm.CityId = cm.CityId INNER JOIN location_master as lm ON bm.LocationId = lm.LocationId INNER JOIN state_master as sm ON bm.StateId = sm.StateId WHERE ";
		$CountQuery = "SELECT count(*) as total FROM buyer_master as bm INNER JOIN city_master as cm ON bm.CityId = cm.CityId INNER JOIN location_master as lm ON bm.LocationId = lm.LocationId INNER JOIN state_master as sm ON bm.StateId = sm.StateId WHERE ";
		$QueryCond =''; 
		$CountQueryCond =''; 
		
		if($State_Id!=0){
			$QueryCond = $QueryCond." bm.StateId = $State_Id";
			$CountQueryCond =$CountQueryCond." bm.StateId = $State_Id"; 
		}
		if($cityid!=0){
			 $QueryCond = $QueryCond." AND bm.CityId = $cityid";
			 $CountQueryCond =$CountQueryCond." AND bm.CityId = $cityid"; 
		}				
		if($location!=0){
			  $QueryCond = $QueryCond." AND bm.LocationId = $location";
			 $CountQueryCond =$CountQueryCond." AND bm.LocationId = $location"; 
		}	
	
		if($level != 0){
			 if($State_Id!=0){
				 $QueryCond = $QueryCond." AND bm.Buyer_Level = '$level' ";
				 $CountQueryCond = $CountQueryCond." AND bm.Buyer_Level = '$level' ";
			}else{
					$QueryCond = $QueryCond." bm.Buyer_Level = '$level' ";
					$CountQueryCond = $CountQueryCond." bm.Buyer_Level = '$level' ";
			}
		}

        switch($col)
        {
            case "BuyerId":
				if($buyerid!=0){
					$Query = $Query." bm.BuyerId = $buyerid ";
					$CountQuery = $CountQuery." bm.BuyerId = $buyerid ";
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				}
               break;
            case "State":
				if($State_Id!=0){
					 $Query = $Query.$QueryCond;
					 $CountQuery = $CountQuery.$CountQueryCond;
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				}
              
               break;
            case "City":
				if($cityid!=0){
					$Query = $Query.$QueryCond;
					 $CountQuery = $CountQuery.$CountQueryCond;
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				}
              
               break;
            case "Location":
				if($location!=0){
					$Query = $Query.$QueryCond;
					 $CountQuery = $CountQuery.$CountQueryCond;
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				}
               
               break;
            case "Period":
				/* if($val1!=0){
					 $Query = $Query." bm.Credit_Period BETWEEN $val1 AND $val2 ";
					$CountQuery = $CountQuery." bm.Credit_Period BETWEEN $val1 AND $val2 ";
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				} */
               
               break;
            case "Limit":
				/* if($val1!=0){
					 $Query = $Query." bm.Credit_Limit BETWEEN $val1 AND $val2 ";
					 $CountQuery = $CountQuery." bm.Credit_Limit BETWEEN $val1 AND $val2 ";
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				} */
              
               break;
            case "Lavel":
				if($level!=0){
					$Query = $Query.$QueryCond;
					 $CountQuery = $CountQuery.$CountQueryCond;
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				}
              
               break;
            case "Executive":
				/* if($val1!=0){
					 $Query = $Query." bm.Executive_ID = $val1 ";
					$CountQuery = $CountQuery." bm.Executive_ID = $val1 ";
				}else{
					$Query = $Query." 1";
					$CountQuery = $CountQuery." 1 ";
				} */
              
               break;
            default :
               break;
        }

		
        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];

        $Query = $Query." LIMIT $start , $rp";
      //  echo($Query); exit;
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
    		 $_buyer_id = $Row['BuyerId']; $_buyer_code = $Row['BuyerCode']; $_buyer_name = $Row['BuyerName']; $_vendor_code = $Row['Vendor_Code'];$_buyer_range = $Row['Buyer_Range']; $_division = $Row['Division']; $_commission_rate = $Row['Commissionerate']; $_ecc = $Row['ECC']; $_tin = $Row['TIN']; $_pan = $Row['PAN']; $_bill_add1 = $Row['Bill_Add1']; $_bill_add2 = $Row['Bill_Add2']; $_state_id = $Row['StateId']; $_city_id = $Row['CityId']; $_country_id = $Row['CountryId'];$_location_id = $Row['LocationId']; $_pincode = $Row['Pincode'];$_phone = $Row['Phone']; $_mobile = $Row['Mobile']; $_fax = $Row['FAX']; $_email = $Row['Email'];$_executive_id = $Row['Executive_ID']; $_credit_period = $Row['Credit_Period'];$_tax_type = $Row['Tax_Type']; $_remarks = $Row['Remarks'];
             $Buyer_Level = $Row['Buyer_Level'];
             $Credit_Limit = $Row['Credit_Limit'];
             $_state_name = $Row['StateName']; $_city_name = $Row['CityName']; $_location_name = $Row['LocationName'];
             $_EmployeeList = null;// Buyer_Contact_Info::Load_BuyerContact($_buyer_id);
             $_DiscountList = null;// Buyer_Discount::Load_Principal_Discount($_buyer_id,0) ;
            $newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate,
                                             $_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id,
                                             $_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period,$Buyer_Level,$Credit_Limit,
                                             $_tax_type, $_remarks,$_state_name,$_city_name,$_location_name,null,null, null, null, null, null, null, null, null, null, null, null, null,$_EmployeeList,$_DiscountList);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	public static function  LoadBuyerDetails($BuyerId = null,$flag = null,$start = null,$rp = null)
	{
		$logger = Logger::getLogger('Buyer_Model'); 
		//echo "Query Start Time".DateTimeClass::udate('Y-m-d H:i:s:u');
        $result;
      	$logger->debug($BuyerId); // function to write in log file
        if($BuyerId > 0)
        {
           
            $result = self::GetBuyerDetails($BuyerId,$flag);
           	$logger->debug($result); // function to write in log file
        }
        else
        {
            if($start === null)
            {
                $result = self::GetBuyerList($flag);
            }
            else
            {
               $result = self::GetBuyerListWithPaging($start,$rp);
            }
        }
		//echo "Query End Time".DateTimeClass::udate('Y-m-d H:i:s:u');
		$objArray = array();
		$i = 0;
	
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		 $_buyer_id = $Row['BuyerId']; $_buyer_code = $Row['BuyerCode']; $_buyer_name = $Row['BuyerName']; $_vendor_code = $Row['Vendor_Code'];$_buyer_range = $Row['Buyer_Range']; $_division = $Row['Division']; $_commission_rate = $Row['Commissionerate']; $_ecc = $Row['ECC']; $_tin = $Row['TIN']; $_pan = $Row['PAN']; $_bill_add1 = $Row['Bill_Add1']; $_bill_add2 = $Row['Bill_Add2']; $_state_id = $Row['StateId']; $_city_id = $Row['CityId']; $_country_id = $Row['CountryId'];$_location_id = $Row['LocationId']; $_pincode = $Row['Pincode'];$_phone = $Row['Phone']; $_mobile = $Row['Mobile']; $_fax = $Row['FAX']; $_email = $Row['Email'];$_executive_id = $Row['Executive_ID']; $_credit_period = $Row['Credit_Period'];$_tax_type = $Row['Tax_Type']; $_remarks = $Row['Remarks'];
             $Buyer_Level = $Row['Buyer_Level'];
             $Credit_Limit = $Row['Credit_Limit'];
             $_state_name = $Row['StateName']; $_city_name = $Row['CityName']; $_location_name = $Row['LocationName'];
             if($BuyerId > 0)
             {
                 $_EmployeeList = Buyer_Contact_Info::Load_BuyerContact($_buyer_id);
                 $_DiscountList = Buyer_Discount::Load_Principal_Discount($_buyer_id,0);
				 $_GSTList = Buyer_GST_Info::Load_BuyerGST($_buyer_id);
             }
             else
             {
                $_EmployeeList = null;
                $_DiscountList = null;
				$_GSTList = null;
             }
			/* BOF for adding GST by Ayush Giri on 13-06-2017 */
            //$newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate,$_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id,$_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period,$Buyer_Level,$Credit_Limit,$_tax_type, $_remarks,$_state_name,$_city_name,$_location_name,null,null, null, null, null, null, null, null, null, null, null, null, null,$_EmployeeList,$_DiscountList);
			$newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate,$_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id,$_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period,$Buyer_Level,$Credit_Limit,$_tax_type, $_remarks,$_state_name,$_city_name,$_location_name,null,null, null, null, null, null, null, null, null, null, null, null, null,$_EmployeeList,$_DiscountList,$_GSTList);
			/* EOF for adding GST by Ayush Giri on 13-06-2017 */
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	  //####################### Load list of buyerBy Name ############
	public static function  LoadBuyerDetailsByName($BuyerId = null,$flag = null,$start = null,$rp = null,$name=null)
	{
		$logger = Logger::getLogger('Buyer_Model'); 
		//echo "Query Start Time".DateTimeClass::udate('Y-m-d H:i:s:u');
        $result;
      	$logger->debug($BuyerId); // function to write in log file
        if($BuyerId > 0)
        {
           
            $result = self::GetBuyerDetails($BuyerId,$flag);
           	$logger->debug($result); // function to write in log file
        }
        else
        {
            if($start === null)
            {
                $result = self::GetBuyerList($flag,$name);
            }
            else
            {
               $result = self::GetBuyerListWithPaging($start,$rp,$name);
            }
        }
	
		//echo "Query End Time".DateTimeClass::udate('Y-m-d H:i:s:u');
		$objArray = array();
		$i = 0;

		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		 $_buyer_id = $Row['BuyerId']; $_buyer_code = $Row['BuyerCode']; $_buyer_name = $Row['BuyerName']; $_vendor_code = $Row['Vendor_Code'];$_buyer_range = $Row['Buyer_Range']; $_division = $Row['Division']; $_commission_rate = $Row['Commissionerate']; $_ecc = $Row['ECC']; $_tin = $Row['TIN']; $_pan = $Row['PAN']; $_bill_add1 = $Row['Bill_Add1']; $_bill_add2 = $Row['Bill_Add2']; $_state_id = $Row['StateId']; $_city_id = $Row['CityId']; $_country_id = $Row['CountryId'];$_location_id = $Row['LocationId']; $_pincode = $Row['Pincode'];$_phone = $Row['Phone']; $_mobile = $Row['Mobile']; $_fax = $Row['FAX']; $_email = $Row['Email'];$_executive_id = $Row['Executive_ID']; $_credit_period = $Row['Credit_Period'];$_tax_type = $Row['Tax_Type']; $_remarks = $Row['Remarks'];
             $Buyer_Level = $Row['Buyer_Level'];
             $Credit_Limit = $Row['Credit_Limit'];
             $_state_name = $Row['StateName']; $_city_name = $Row['CityName']; $_location_name = $Row['LocationName'];
             if($BuyerId > 0)
             {
                 $_EmployeeList = Buyer_Contact_Info::Load_BuyerContact($_buyer_id);
                 $_DiscountList = Buyer_Discount::Load_Principal_Discount($_buyer_id,0);
				 $_GSTList = Buyer_GST_Info::Load_BuyerGST($_buyer_id);
             }
             else
             {
                $_EmployeeList = null;
                $_DiscountList = null;
				$_GSTList = null;
             }
            //$newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate,$_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id,$_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period,$Buyer_Level,$Credit_Limit,$_tax_type, $_remarks,$_state_name,$_city_name,$_location_name,null,null, null, null, null, null, null, null, null, null, null, null, null,$_EmployeeList,$_DiscountList);
			$newObj = new BuyerMaster_Model($_buyer_id, $_buyer_code, $_buyer_name, $_vendor_code, $_buyer_range, $_division, $_commission_rate, $_ecc, $_tin, $_pan, $_bill_add1, $_bill_add2, $_state_id, $_city_id, $_country_id, $_location_id, $_pincode, $_phone, $_mobile, $_fax, $_email, $_executive_id, $_credit_period, $Buyer_Level, $Credit_Limit, $_tax_type, $_remarks, $_state_name, $_city_name, $_location_name, null, null, null, null, null, null, null, null, null, null, null, null, null, $_EmployeeList, $_DiscountList, $_GSTList);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	   //####################### Buyer Name AND BuyerId List############
		
	 public static function GetBuyersNameIdList()
	 {  
	   $logger = Logger::getLogger('Buyer_Model'); 
	 	$Query="SELECT bm.BuyerId as _buyer_id,bm.BuyerName as _buyer_name FROM buyer_master AS bm WHERE bm.Buyer_Level !='X'";
		$logger->debug($Query); // function to write in log file
	 	$Result = DBConnection::SelectQuery($Query);
	     $objArray = array();
	     $i = 0;
	    while ($Row=mysql_fetch_array($Result, MYSQL_ASSOC)) {
	 		$newObj= array();
	  	    $newObj=$Row;
	 	    $objArray[$i]=$newObj;
            $i++;
	 	}	
	 	return $objArray;
	 }
	//################################################################### 
    public static function InsertBuyer($BuyerCode, $BuyerName, $Vendor_Code, $Buyer_Range, $Division, $Commissionerate, $ECC, $TIN, $PAN,$Bill_Add1, $Bill_Add2, $StateId, $CityId, $CountryId, $LocationId, $Pincode, $Phone, $Mobile, $FAX, $Email, $Executive_ID, $Credit_Period,$Buyer_Level,$Credit_Limit, $Tax_Type, $Remarks, $UserId){
		$logger = Logger::getLogger('Buyer_Model'); 
		// added on 01-JUNE-2016 due to Handle Special Character
		
		 $BuyerName = mysql_escape_string($BuyerName);		
		$Vendor_Code = mysql_escape_string($Vendor_Code);
		$Buyer_Range = mysql_escape_string($Buyer_Range);
		$Division = mysql_escape_string($Division);
		$Commissionerate = mysql_escape_string($Commissionerate);
		$ECC = mysql_escape_string($ECC);
		$TIN = mysql_escape_string($TIN);
		$PAN = mysql_escape_string($PAN);
		$Bill_Add1 = mysql_escape_string($Bill_Add1);
		$Bill_Add2 = mysql_escape_string($Bill_Add2);
		$Pincode = mysql_escape_string($Pincode);
		$Phone = mysql_escape_string($Phone);
		$Mobile = mysql_escape_string($Mobile);
		$FAX = mysql_escape_string($FAX);
		$Email = mysql_escape_string($Email);
		$Executive_ID = mysql_escape_string($Executive_ID);
		$Credit_Period = mysql_escape_string($Credit_Period);
		$Buyer_Level = mysql_escape_string($Buyer_Level);
		$Credit_Limit = mysql_escape_string($Credit_Limit);
		$Tax_Type = mysql_escape_string($Tax_Type);
		$Remarks = mysql_escape_string($Remarks);		
		
		
		$BuyerCode = self::GetBuyerCode();
		$Resp = self::CheckBuyerCode($BuyerCode);
		if($Resp == QueryResponse::NO){
			
			$date = date("Y-m-d");
			$Query = "INSERT INTO buyer_master (BuyerCode, BuyerName, Vendor_Code, Buyer_Range, Division, Commissionerate, ECC, TIN, PAN,Bill_Add1, Bill_Add2, StateId, CityId, CountryId, LocationId, Pincode, Phone, Mobile, FAX, Email, Executive_ID, Credit_Period,Credit_Limit,Buyer_Level,Tax_Type, Remarks, UserId, InsertDate) VALUES ('".$BuyerCode."', '".$BuyerName."', '".$Vendor_Code."', '".$Buyer_Range."', '".$Division."','".$Commissionerate."', '".$ECC."', '".$TIN."', '".$PAN."','".$Bill_Add1."', '".$Bill_Add2."', '".$StateId."', '".$CityId."', '".$CountryId."', '".$LocationId."','".$Pincode."', '".$Phone."', '".$Mobile."', '".$FAX."', '".$Email."', '".$Executive_ID."', '".$Credit_Period."','".$Credit_Limit."','".$Buyer_Level."', '$Tax_Type', '".$Remarks."', '".$UserId."', '$date')";
			
			
			//echo $Query ; exit;
			$logger->info($Query); // function to write in log file
			$Result = DBConnection::InsertQuery($Query);
			if($Result > 0){
				return $Result;
			}
			else{
				return QueryResponse::NO;
			}
		}
	    else{
			return 0;
		}
	}

    public static function InsertPrincipalDetails($BuyerID,$PrincipalId,$Discount){
        $logger = Logger::getLogger('Buyer_Model'); 
		$Query = "INSERT INTO buyer_discount_info (BUYERID, PRINCIPAL_SUPPLIER_ID, DISCOUNT ) VALUES ($BuyerID,$PrincipalId,$Discount)";
		$logger->debug($Query); // function to write in log file
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }

	public static function CheckBuyerCode($BuyerCode){
		$logger = Logger::getLogger('Buyer_Model'); 
		$Query = "SELECT BuyerCode FROM buyer_master WHERE BuyerCode = '$BuyerCode'";
		$logger->debug($Query); // function to write in log file
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		
		if($Row['BuyerCode'] == $BuyerCode){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetBuyerCode(){ // function to create buyer code by the System
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "SELECT BuyerId FROM buyer_master ORDER BY BuyerId DESC LIMIT 1";
		$logger->debug($Query); // function to write in log file
        $Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row["BuyerId"] == null)
            return "MEPL0001";
        if($Row["BuyerId"] > 0)
        {
            $id = $Row["BuyerId"] + 1;
            $s = sprintf("%04d", $id);
			$logger->debug("MEPL".$s); // function to write in log file
            return "MEPL".$s;
        }
        else
            return null;
    }
    public static function UpdateBuyerDetails($BuyerId,$BuyerName,$Vendor_Code,$Buyer_Range,$Division,$Commissionerate,$ECC,$TIN,$PAN,$Bill_Add1,$Bill_Add2,$StateId,$CityId,$CountryId,$LocationId,$Pincode,$Phone,$Mobile,$FAX,$Email,$Executive_ID,$Credit_Period,$Buyer_Level,$Credit_Limit,$tax_type,$Remarks){
		$logger = Logger::getLogger('Buyer_Model'); 
		
		// added on 01-JUNE-2016 due to Handle Special Character
		$BuyerName = mysql_escape_string($BuyerName);
		$Vendor_Code = mysql_escape_string($Vendor_Code);
		$Buyer_Range = mysql_escape_string($Buyer_Range);
		$Division = mysql_escape_string($Division);
		$Commissionerate = mysql_escape_string($Commissionerate);
		$ECC = mysql_escape_string($ECC);
		$TIN = mysql_escape_string($TIN);
		$PAN = mysql_escape_string($PAN);
		$Bill_Add1 = mysql_escape_string($Bill_Add1);
		$Bill_Add2 = mysql_escape_string($Bill_Add2);
		$Pincode = mysql_escape_string($Pincode);
		$Phone = mysql_escape_string($Phone);
		$Mobile = mysql_escape_string($Mobile);
		$FAX = mysql_escape_string($FAX);
		$Email = mysql_escape_string($Email);
		$Executive_ID = mysql_escape_string($Executive_ID);
		$Credit_Period = mysql_escape_string($Credit_Period);
		$Buyer_Level = mysql_escape_string($Buyer_Level);
		$Credit_Limit = mysql_escape_string($Credit_Limit);
		$tax_type = mysql_escape_string($tax_type);
		$Remarks = mysql_escape_string($Remarks);
		
		$Query = "UPDATE buyer_master SET BuyerName = '".$BuyerName."', Vendor_Code = '".$Vendor_Code."', Buyer_Range = '".$Buyer_Range."', Division = '".$Division."', Commissionerate = '".$Commissionerate."', ECC = '".$ECC."', TIN = '".$TIN."', PAN = '".$PAN."', Bill_Add1 = '".$Bill_Add1."', Bill_Add2 = '".$Bill_Add2."',StateId='$StateId',CityId='$CityId',CountryId='$CountryId',LocationId='$LocationId', Pincode = '".$Pincode."', Phone = '".$Phone."', Mobile = '$Mobile', FAX = '".$FAX."', Email = '".$Email."',Executive_ID = '".$Executive_ID."', Credit_Period = '$Credit_Period',Credit_Limit='$Credit_Limit',Buyer_Level = '$Buyer_Level', Tax_Type = '$tax_type', Remarks = '".$Remarks."' WHERE BuyerId = $BuyerId";
		$logger->debug($Query); // function to write in log file
		$Result = DBConnection::UpdateQuery($Query);
		$logger->debug($Result); // function to write in log file
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetBuyerList($flag=null,$name=null){
	
		$Query = "SELECT bm.*,cm.CityId,lm.LocationId,sm.StateId,cm.CityName,lm.LocationName,sm.StateName FROM buyer_master as bm INNER JOIN city_master as cm ON bm.CityId = cm.CityId INNER JOIN location_master as lm ON bm.LocationId = lm.LocationId INNER JOIN state_master as sm ON bm.StateId = sm.StateId AND bm.Buyer_Level !='X' AND bm.BuyerName LIKE '".$name."%' ";
		if($flag=="A"){
		$Query =$Query." and bm.Buyer_Level !='X'";
		}
		
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetBuyerListWithPaging($start,$rp,$name=null){
		$Query = "SELECT bm.*,cm.CityId,lm.LocationId,sm.StateId,cm.CityName,lm.LocationName,sm.StateName FROM buyer_master as bm INNER JOIN city_master as cm ON bm.CityId = cm.CityId INNER JOIN location_master as lm ON bm.LocationId = lm.LocationId INNER JOIN state_master as sm ON bm.StateId = sm.StateId AND bm.Buyer_Level !='X' AND bm.BuyerName LIKE '".$name."%' LIMIT $start , $rp";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

    public static function GetBuyerDetails($BuyerId,$flag=NULL){
		
		$Query = "SELECT bm.*,cm.CityId,lm.LocationId,sm.StateId,cm.CityName,lm.LocationName,sm.StateName FROM buyer_master as bm INNER JOIN city_master as cm ON bm.CityId = cm.CityId INNER JOIN location_master as lm ON bm.LocationId = lm.LocationId INNER JOIN state_master as sm ON bm.StateId = sm.StateId WHERE bm.BuyerId = $BuyerId ";
		if($flag=="A"){
		$Query =$Query." and bm.Buyer_Level !='X'";
		}
	
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function InsertBuyerShippingAddress($BUYERID, $CHECK_ADD, $ADD1, $ADD2, $STATEID, $CITYID, $COUNTRYID, $LOCATIONID, $PINCODE, $PHONE, $MOBILE, $FAX, $EMAIL){
		$logger = Logger::getLogger('Buyer_Model'); 
		// added on 01-JUNE-2016 due to Handle Special Character
		$CHECK_ADD = mysql_escape_string($CHECK_ADD);
		$ADD1 = mysql_escape_string($ADD1);
		$ADD2 = mysql_escape_string($ADD2);
		$PINCODE = mysql_escape_string($PINCODE);
		$PHONE = mysql_escape_string($PHONE);
		$MOBILE = mysql_escape_string($MOBILE);
		$FAX  = mysql_escape_string($FAX);
		$EMAIL = mysql_escape_string($EMAIL);
        $Query = "INSERT INTO buyer_shipping_details (BUYERID, CHECK_ADD, ADD1, ADD2, STATEID, CITYID, COUNTRYID, LOCATIONID, PINCODE, PHONE, MOBILE, FAX, EMAIL) VALUES ($BUYERID, '$CHECK_ADD', '$ADD1', '$ADD2', '$STATEID', '$CITYID', '$COUNTRYID', '$LOCATIONID', '$PINCODE', '$PHONE', '$MOBILE', '$FAX', '$EMAIL')";
		$logger->debug($Query); // function to write in log file
        //exit();
        $Result = DBConnection::InsertQuery($Query);
        if($Result > 0){
            return $Result;
        }
        else{
            return QueryResponse::NO;
        }
    }
    public static function GetBuyerShippingDetails($BuyerId){
		$Query = "SELECT bsd.*,sm.StateName,cm.CityName,lm.LocationName FROM buyer_shipping_details as bsd INNER JOIN state_master as sm ON bsd.STATEID = sm.StateId INNER JOIN city_master as cm ON bsd.CITYID = cm.CityId INNER JOIN location_master as lm ON bsd.LOCATIONID = lm.LocationId WHERE bsd.BUYERID = $BuyerId";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
	}

    public static function UpdateBuyerShippingDetails($add1, $add2, $shippingcountryid, $shippingstateid, $shippingcityid, $shippinglocationid, $shippingpincode, $shippingphone, $shippingmobile, $shippingfax, $shippingemail,$checkadd,$addressidId){
	$logger = Logger::getLogger('Buyer_Model'); 
	// added on 01-JUNE-2016 due to Handle Special Character
	$add1 = mysql_escape_string($add1);
	$add2 = mysql_escape_string($add2);
	$shippingpincode = mysql_escape_string($shippingpincode);
	$shippingphone = mysql_escape_string($shippingphone);
	$shippingmobile = mysql_escape_string($shippingmobile);
	$shippingfax = mysql_escape_string($shippingfax);
	$shippingemail = mysql_escape_string($shippingemail);
	$checkadd = mysql_escape_string($shippingemail);
	
	
	$Query = "INSERT INTO buyer_shipping_details (BUYERID, CHECK_ADD, ADD1, ADD2, STATEID, CITYID, COUNTRYID, LOCATIONID, PINCODE, PHONE, MOBILE, FAX, EMAIL) VALUES ($BUYERID, '$CHECK_ADD', '$ADD1', '$ADD2', '$STATEID', '$CITYID', '$COUNTRYID', '$LOCATIONID', '$PINCODE', '$PHONE', '$MOBILE', '$FAX', '$EMAIL') ON DUPLICATE KEY UPDATE shipping_add_id = $addressidId";
    //$Query = "UPDATE buyer_shipping_details SET CHECK_ADD = '$checkadd' , ADD1 = '$add1', ADD2 = '$add2' , STATEID = $shippingstateid, CITYID = $shippingcityid, COUNTRYID = $shippingcountryid, LOCATIONID = $shippinglocationid, PINCODE = $shippingpincode, PHONE = '$shippingphone', MOBILE = $shippingmobile, FAX = '$shippingfax' , EMAIL = '$shippingemail'  WHERE shipping_add_id = $addressidId";
	$logger->debug($Query); // function to write in log file
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
    public static function DeleteBuyerShippingDetails($BUYERID){
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "DELETE FROM buyer_shipping_details WHERE BUYERID = $BUYERID";
		$logger->warn($Query); // function to write in log file
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
		$logger->error(QueryResponse::NO); // function to write in log file
            return QueryResponse::NO;
        }
    }
}

class Buyer_Contact_Info
{
    public $_buyer_id;
    public $_title;
    public $titlename;
    public $_first_name;
    public $_last_name;
    public $_dept_id;
    public $deptname;
    public $_phone;
    public $_email;

    public function __construct($_buyer_id,$title,$titlename,$firstname,$lastname,$deptid,$deptname,$phone,$email){

        $this->_buyer_id = $_buyer_id;
		$this->_title = $title;
        $this->titlename = $titlename;
        $this->_first_name = $firstname;
		$this->_last_name = $lastname;
		$this->_dept_id = $deptid;
        $this->deptname = $deptname;
        $this->_phone = $phone;
		$this->_email = $email;
	}
	public static function  Load_BuyerContact($Buyer_id){
        $result = self::Get_Buyer_ContactInfo_List($Buyer_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_buyer_id = $Row['BUYERID'];
            $_title = $Row['TITLE'];
            $titlename = $Row['titlename'];
            $_first_name = $Row['FIRSTNAME'];
            $_last_name = $Row['LASTNAME'];
            $_dept_id = $Row['DEPT'];
            $deptname = $Row['departmentname'];
            $_phone = $Row['PHONE'];
            $_email = $Row['EMAIL'];
            $newObj = new Buyer_Contact_Info($_buyer_id,$_title,$titlename,$_first_name,$_last_name,$_dept_id,$deptname,$_phone,$_email);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function Insert_Buyer_ContactInfo($BUYERID, $TITLEID, $FIRSTNAME, $LASTNAME, $DEPT_ID, $PHONE, $EMAIL){
		$logger = Logger::getLogger('Buyer_Model'); 
		// added on 01-JUNE-2016 due to Handle Special Character
		$FIRSTNAME = mysql_escape_string($FIRSTNAME);
		$LASTNAME = mysql_escape_string($LASTNAME);
		$DEPT_ID = mysql_escape_string($DEPT_ID);
		$PHONE  = mysql_escape_string($PHONE);
		$EMAIL = mysql_escape_string($EMAIL);
        $Query = "INSERT INTO buyer_contact_info (BUYERID, TITLE, FIRSTNAME, LASTNAME, DEPT, PHONE, EMAIL) VALUES ($BUYERID,'$TITLEID','$FIRSTNAME','$LASTNAME','$DEPT_ID','$PHONE','$EMAIL')";
		$logger->info($Query); // function to write in log file
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}
    public static function DeleteItem($BUYERID){
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "DELETE FROM buyer_contact_info WHERE BUYERID = $BUYERID";
		$logger->warn($Query); // function to write in log file
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
			$logger->error(QueryResponse::NO); // function to write in log file
            return QueryResponse::NO;
        }
    }
    public static function Update_Buyer_ContactInfo($BUYERCONTACTID, $TITLEID, $FIRSTNAME, $LASTNAME, $DEPT_ID, $PHONE, $EMAIL){
		$logger = Logger::getLogger('Buyer_Model'); 
		//added on 01-JUNE-2016 due to Handle Special Character
		$FIRSTNAME = mysql_escape_string($FIRSTNAME); 
		$LASTNAME = mysql_escape_string($LASTNAME);
		$DEPT_ID = mysql_escape_string($DEPT_ID);
		$PHONE = mysql_escape_string($PHONE);
		$EMAIL = mysql_escape_string($EMAIL);
		
		$Query = "UPDATE buyer_contact_info SET TITLE = $TITLEID, FIRSTNAME = '$FIRSTNAME', LASTNAME = '$LASTNAME', DEPT = '$DEPT_ID', PHONE = '$PHONE', EMAIL = '$EMAIL' ";
		$logger->debug($Query); // function to write in log file
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			$logger->error(QueryResponse::NO); // function to write in log file
			return QueryResponse::NO;
		}
	}
    public static function Get_Buyer_ContactInfo_List($Buyer_id){
        //$Query = "SELECT * FROM buyer_contact_info WHERE BUYERID = $Buyer_id";
		$Query = "SELECT bci.*,tm.titlename,dm.departmentname FROM buyer_contact_info as bci
INNER JOIN title_master as tm ON bci.TITLE = tm.titleid
INNER JOIN department_master as dm ON bci.DEPT = dm.departmentid
WHERE bci.BUYERID =  $Buyer_id";
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function Get_Buyer_ContactInfo_Details($Emp_id){
        $Query = "SELECT * FROM buyer_contact_info WHERE buyer_contact_id = $Emp_id";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function  Load_BuyerContactDetails($Emp_id){
        $result = self::Get_Buyer_ContactInfo_Details($Emp_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_buyer_id = $Row['BUYERID'];
            $_title = $Row['TITLE'];
            $_first_name = $Row['FIRSTNAME'];
            $_last_name = $Row['LASTNAME'];
            $_dept_id = $Row['DEPT'];
            $_phone = $Row['PHONE'];
            $_email = $Row['EMAIL'];
            $newObj = new Buyer_Contact_Info($_buyer_id,$_title,$_first_name,$_last_name,$_dept_id,$_phone,$_email);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
}

class Buyer_Discount{

    public $_buyer_id;
    public $_principal_id;
    public $_discount;
    public $_principalname;

    public function __construct($buyerid,$principalid,$discount,$principalname){

        $this->_buyer_id = $buyerid;
		$this->_principal_id = $principalid;
        $this->_discount = $discount;
        $this->_principalname = $principalname;
	}
    public static function Insert_Buyer_Discount($BUYERID, $PRINCIPAL_SUPPLIER_ID, $DISCOUNT){
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "INSERT INTO buyer_discount_info (BUYERID, PRINCIPAL_SUPPLIER_ID, DISCOUNT) VALUES ($BUYERID,'$PRINCIPAL_SUPPLIER_ID','$DISCOUNT')";
      	$logger->debug($Query); // function to write in log file
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}
    public static function DeleteItem($BUYERID){
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "DELETE FROM buyer_discount_info WHERE BUYERID = '$BUYERID'";
		 $logger->warn($Query); // function to write in log file
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
			$logger->error(QueryResponse::NO); // function to write in log file
            return QueryResponse::NO;
        }
    }
    public static function  Load_Principal_Discount($Buyer_id ,$PrincipalId){
        $result = "";
        if($PrincipalId > 0)
        {
            $result = self::Get_Buyer_DiscountForPrincipal($Buyer_id,$PrincipalId);
        }
        else
        {
            $result = self::Get_Buyer_Discount($Buyer_id);
        }
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_buyer_id = $Row['BUYERID'];
            $_principal_id = $Row['PRINCIPAL_SUPPLIER_ID'];
            $_discount = $Row['DISCOUNT'];
            $_principalname = $Row['Principal_Supplier_Name'];
            $newObj = new Buyer_Discount($_buyer_id,$_principal_id,$_discount,$_principalname);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

    public static function Update_Buyer_Discount($BUYERID, $PRINCIPAL_SUPPLIER_ID, $DISCOUNT){
		$logger = Logger::getLogger('Buyer_Model'); 
        $Query = "UPDATE buyer_discount_info SET DISCOUNT = '$DISCOUNT' WHERE  BUYERID = '$BUYERID' AND PRINCIPAL_SUPPLIER_ID = '$PRINCIPAL_SUPPLIER_ID'";
		$logger->debug($Query); // function to write in log file
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}

    public static function Get_Buyer_Discount($Buyer_id){
        $Query = "SELECT bdi.*,psm.Principal_Supplier_Name FROM buyer_discount_info as bdi INNER JOIN principal_supplier_master as psm ON bdi.PRINCIPAL_SUPPLIER_ID = psm.Principal_Supplier_Id WHERE bdi.BUYERID = $Buyer_id";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function Get_Buyer_DiscountForPrincipal($Buyer_id,$principalID){
        $Query = "SELECT bdi.*,psm.Principal_Supplier_Name FROM buyer_discount_info as bdi INNER JOIN principal_supplier_master as psm ON bdi.PRINCIPAL_SUPPLIER_ID = psm.Principal_Supplier_Id WHERE bdi.BUYERID = $Buyer_id AND bdi.PRINCIPAL_SUPPLIER_ID = $principalID";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}

class Buyer_GST_Info
{
    public $_buyer_id;
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

    public function __construct($buyer_id, $gst_state_id, $gst_state_name, $gst_reg_status, $gst_reg_status_name, $gst_mig_status, $gst_mig_status_name, $gst_no, $gst_reg_date, $arn_no, $perm_gst){
        $this->_buyer_id = $buyer_id;
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
	public static function  Load_BuyerGST($Buyer_id){
        $result = self::Get_Buyer_GSTInfo_List($Buyer_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_buyer_id = $Row['buyer_id'];
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
            $newObj = new Buyer_GST_Info($_buyer_id, $_gst_state_id, $_gst_state_name, $_gst_reg_status, $_gst_reg_status_name, $_gst_mig_status, $_gst_mig_status_name, $_gst_no,$_gst_reg_date, $_arn_no, $_perm_gst);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function Insert_Buyer_GSTInfo($buyer_id, $gst_state_id, $gst_reg_status, $gst_mig_status, $gst_no, $gst_reg_date, $arn_no, $perm_gst){
		$buyer_id	 =  mysql_escape_string($buyer_id);
		$gst_state_id	 =  mysql_escape_string($gst_state_id);
		$gst_reg_status	 =  mysql_escape_string($gst_reg_status);
		$gst_mig_status	 =  mysql_escape_string($gst_mig_status);
		$gst_no	=  mysql_escape_string($gst_no);
		$gst_reg_date	 =  mysql_escape_string($gst_reg_date);
		$arn_no	 =  mysql_escape_string($arn_no);
		$perm_gst =  mysql_escape_string($perm_gst);
		
        $Query = "INSERT INTO buyer_gst_details (buyer_id, gst_state_id, gst_reg_status, gst_mig_status, gst_no, gst_reg_date, arn_no, perm_gst) VALUES('".$buyer_id."', '".$gst_state_id."', '".$gst_reg_status."', '".$gst_mig_status."', '".$gst_no."', '".$gst_reg_date."', '".$arn_no."', '".$perm_gst."')";
        $Result = DBConnection::InsertQuery($Query);
        return $Result;
	}
    public static function DeleteItem($buyer_id){
        $Query = "DELETE FROM buyer_gst_details WHERE buyer_id = $buyer_id";
        $Result = DBConnection::UpdateQuery($Query);
        if($Result > 0){
            return QueryResponse::YES;
        }
        else{
            return QueryResponse::NO;
        }
    }
    /* public static function Update_Buyer_ContactInfo($BUYERCONTACTID, $TITLEID, $FIRSTNAME, $LASTNAME, $DEPT_ID, $PHONE, $EMAIL){
		$logger = Logger::getLogger('Buyer_Model'); 
		//added on 01-JUNE-2016 due to Handle Special Character
		$FIRSTNAME = mysql_escape_string($FIRSTNAME); 
		$LASTNAME = mysql_escape_string($LASTNAME);
		$DEPT_ID = mysql_escape_string($DEPT_ID);
		$PHONE = mysql_escape_string($PHONE);
		$EMAIL = mysql_escape_string($EMAIL);
		
		$Query = "UPDATE buyer_contact_info SET TITLE = $TITLEID, FIRSTNAME = '$FIRSTNAME', LASTNAME = '$LASTNAME', DEPT = '$DEPT_ID', PHONE = '$PHONE', EMAIL = '$EMAIL' ";
		$logger->debug($Query); // function to write in log file
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			$logger->error(QueryResponse::NO); // function to write in log file
			return QueryResponse::NO;
		}
	} */
    public static function Get_Buyer_GSTInfo_List($Buyer_id){
		$Query = "SELECT bgd.buyer_id, bgd.gst_state_id, sm.StateName AS gst_state_name, bgd.gst_reg_status, IF(bgd.gst_reg_status = 1,'Active','Disabled') AS gst_reg_status_name, bgd.gst_mig_status, IF(bgd.gst_mig_status = 1,'Active','Disabled') AS gst_mig_status_name, bgd.gst_no, bgd.gst_reg_date, bgd.arn_no, bgd.perm_gst FROM buyer_gst_details bgd JOIN state_master sm ON sm.StateId = bgd.gst_state_id WHERE buyer_id = ".$Buyer_id;
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    /* public static function Get_Buyer_ContactInfo_Details($Emp_id){
        $Query = "SELECT * FROM buyer_contact_info WHERE buyer_contact_id = $Emp_id";
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    } 
    public static function  Load_BuyerContactDetails($Emp_id){
        $result = self::Get_Buyer_ContactInfo_Details($Emp_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $_buyer_id = $Row['BUYERID'];
            $_title = $Row['TITLE'];
            $_first_name = $Row['FIRSTNAME'];
            $_last_name = $Row['LASTNAME'];
            $_dept_id = $Row['DEPT'];
            $_phone = $Row['PHONE'];
            $_email = $Row['EMAIL'];
            $newObj = new Buyer_Contact_Info($_buyer_id,$_title,$_first_name,$_last_name,$_dept_id,$_phone,$_email);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}*/
}


class Buyer_SHIP_Info
{
    public $_buyer_id;
    public $_add1;
	public $_add2;
    public $_state_id;
    public $_city_id;
	public $_country_id;
    public $_location_id;
    public $_pincode;
    public $_phone;
    public $_mobile;
    public $_fax;
	public $_email;

    public function __construct($buyer_id, $add1, $add2, $state_id, $city_id, $country_id, $location_id, $pincode, $phone, $mobile, $fax, $email){
        $this->_buyer_id = $buyer_id;
		$this->_add1 = $add1;
		$this->_add2 = $add2;
        $this->_state_id = $state_id;
		$this->_city_id = $city_id;
		$this->_country_id = $country_id;
		$this->_location_id = $location_id;
		$this->_pincode = $pincode;
        $this->_phone = $phone;
		$this->_mobile = $mobile;
        $this->_fax = $fax;
		$this->_email = $email;
	}          
	public static function  Load_Buyer_ShippingInfo($Buyer_id){
        $result = self::Get_Buyer_ShippingInfo_List($Buyer_id);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $buyer_id = $Row['BUYERID'];
            $add1 = $Row['ADD1'];
			$add2 = $Row['ADD2'];
            $state_id = $Row['STATEID'];
			$city_id = $Row['CITYID'];
            $country_id = $Row['COUNTRYID'];
			$location_id = $Row['LOCATIONID'];
            $pincode = $Row['PINCODE'];
            $phone = $Row['PHONE'];
            $mobile = $Row['MOBILE'];
            $fax = $Row['FAX'];
            $email = $Row['EMAIL'];

			$newObj = new Buyer_SHIP_Info($buyer_id, $add1, $add2, $state_id, $city_id, $country_id, $location_id, $pincode, $phone, $mobile, $fax, $email);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
    public static function Get_Buyer_ShippingInfo_List($Buyer_id){
		$Query = "SELECT `BUYERID`, `ADD1`, `ADD2`, `STATEID`, `CITYID`, `COUNTRYID`, `LOCATIONID`, `PINCODE`, `PHONE`, `MOBILE`, `FAX`, `EMAIL` FROM `buyer_shipping_details` WHERE  BUYERID = ".$Buyer_id;
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}

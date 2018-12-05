<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/po_model.php");
class Param
{
    public static function GetPurchaseOrderForBilling(){
        $Query = "SELECT * FROM purchaseorder WHERE PO_STATUS='O' AND (management_approval='N' OR (management_approval='R' AND APPROVAL_STATUS='A'))"; 
		$Result = DBConnection::SelectQuery($Query);
        
		return $Result;
    }
    public static function GetRecuringPurchaseOrderList(){
        $Query = "SELECT * FROM purchaseorder WHERE po_status = 'O' AND bpoType = 'R'"; 
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
    public static function GetParamList($PARAMTYPE,$PARAMCODE){
		$Query = "SELECT PARAM_VALUE1,PARAM_VALUE2,PARAM1,COMP_NAME,USERID FROM param WHERE PARAM_TYPE='$PARAMTYPE' AND PARAM_CODE='$PARAMCODE' AND STATUS='Y'"; 
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function GetDepartmentList(){	// Function to return list of DEPARTMENT form  department_master
		$Query = "select * from department_master"; 
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function GetTitleList(){
		$Query = "select * from title_master"; 
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function GetVATCSTList(){	// Function to return list of VAT -CST  form  vat_cst_master
		$Query = "select * from vat_cst_master"; 
        $Result = DBConnection::SelectQuery($Query);
		return $Result;
	}
    public static function GetParam($CoulamName,$Value){
        $Query = "";
        switch($CoulamName)
        {
            case "PARAM_TYPE":
                $Query = "SELECT * FROM param WHERE PARAM_TYPE = '$Value'"; 
                break;
            case "PARAM_CODE":
                $Query = "SELECT * FROM param WHERE PARAM_CODE = '$Value'"; 
                break;
            default:
                return;
                break;
        }
		$Result = DBConnection::SelectQuery($Query);
        return $Result;
    }
}

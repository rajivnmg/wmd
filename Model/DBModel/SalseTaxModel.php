<?php
//include_once("Enum_Model.php");
//include_once("DbModel.php");
class SalseTaxModel
{
    public $SALESTAX_ID;
    public $TYPE;
    public $SALESTAX_DESC;
    public $SALESTAX_CHRG;
    public $SURCHARGE_DESC;
    public $SURCHARGE;
    public $STATUS;
    
    public function __construct($SALESTAX_ID, $TYPE, $SALESTAX_DESC, $SALESTAX_CHRG, $SURCHARGE_DESC, $SURCHARGE, $STATUS){
        $this->SALESTAX_ID = $SALESTAX_ID; 
        $this->TYPE = $TYPE;
        $this->SALESTAX_DESC = $SALESTAX_DESC; 
        $this->SALESTAX_CHRG = $SALESTAX_CHRG; 
        $this->SURCHARGE_DESC = $SURCHARGE_DESC; 
        $this->SURCHARGE = $SURCHARGE; 
        $this->STATUS = $STATUS; 
	}
    public static function  LoadSalseTax($TAX_ID)
	{
        $result = self::GetSalseTax($TAX_ID);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $SALESTAX_ID = $Row['SALESTAX_ID']; 
            $TYPE = $Row['TYPE']; 
            $SALESTAX_DESC = $Row['SALESTAX_DESC']; 
            $SALESTAX_CHRG = $Row['SALESTAX_CHRG']; 
            $SURCHARGE_DESC = $Row['SURCHARGE_DESC']; 
            $SURCHARGE = $Row['SURCHARGE']; 
            $STATUS = $Row['STATUS']; 
            $newObj = new SalseTaxModel($SALESTAX_ID, $TYPE, $SALESTAX_DESC, $SALESTAX_CHRG, $SURCHARGE_DESC, $SURCHARGE, $STATUS);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
    public static function GetSalseTax($TAX_ID){
    
		$Query = "SELECT * FROM vat_cst_master WHERE SALESTAX_ID = $TAX_ID"; 
        //echo($Query);
		$Result = DBConnection::SelectQuery($Query);
        
		return $Result;
	}
    
    public static function GetSalseTaxValue($TAX_ID){
		$Query = "SELECT SALESTAX_CHRG FROM vat_cst_master WHERE SALESTAX_ID = $TAX_ID"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        $Value = $Row['SALESTAX_CHRG'];
		return $Value;
	}
    public static function GetSURCHARGETaxValue($TAX_ID){
		$Query = "SELECT SURCHARGE FROM vat_cst_master WHERE SALESTAX_ID = $TAX_ID"; 
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
        $Value = $Row['SURCHARGE'];
        if($Value==NULL){
			return 0;
		}
        else{
            return $Value;
        }
		
	}
}

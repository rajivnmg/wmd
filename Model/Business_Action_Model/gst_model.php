<?php
class gst_Model
{
    public $gst_rate;
    public $cgst_rate;
    public $sgst_rate;
    public $igst_rate;
	public $hsn_code;
    
    public function __construct($gst_rate,$cgst_rate,$sgst_rate,$igst_rate,$hsn_code){
        $this->gst_rate = $gst_rate;
        $this->cgst_rate = $cgst_rate;
        $this->sgst_rate  =$sgst_rate;
        $this->igst_rate  =$igst_rate;
		$this->hsn_code  =$hsn_code;
	}
	
	/* public static function getGSTRates($shipped_state_id, $item_code)
	{
		$Query = "SELECT COUNT(*) AS TOTAL_STATE FROM state_master WHERE StateId = ".mysql_escape_string($shipped_state_id);
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
        $Row = mysql_fetch_array($result, MYSQL_ASSOC)
		$total_state = $Row['TOTAL_STATE'];
        
		if($Row['TOTAL_STATE'] == 1)
		{
			$Query = "SELECT hm.hsn_code, tm.tax_rate FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '".$item_code."'";
			$result = DBConnection::SelectQuery($Query);
			$Row = mysql_fetch_array($result, MYSQL_ASSOC);
			
			$hsn_code = $Row['hsn_code'];
			if($shipped_state_id == 1)
			{
				$cgst = $Row['tax_rate']/2;
				$sgst = $Row['tax_rate']/2;
				$igst = 0;
			}
			else
			{
				$cgst = 0;
				$sgst = 0;
				$igst = $Row['tax_rate'];
			}
			$gst = $Row['tax_rate'];
		}
		else
		{
			$cgst = '';
			$sgst = '';
			$igst = '';
			$gst  = '';
			$hsn_code = '';
		}
		$objArray[0] = new gst_Model($gst, $gst, $gst, $gst, $po_itemId, $hsn_code);
		return $objArray;
	} */
	
	public static function getGSTRates($shipped_state_id, $item_code)
	{
		$Query = "SELECT hm.hsn_code, tm.tax_rate FROM item_master im JOIN hsn_master hm ON hm.hsn_code = im.Tarrif_Heading JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '".$item_code."'";
		$result = DBConnection::SelectQuery($Query);
		
		$objArray = array();
	    $i = 0;
	    while ($Row=mysql_fetch_array($Result, MYSQL_ASSOC)) {
	 	    $objArray[$i]=$Row;
            $i++;
	 	}	
	 	return $objArray;
	}
}


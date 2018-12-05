<?php
class ItemMaster_Model
{
    public $_item_id;
    public $_group_id;
    public $_principal_id;
    public $_item_code_partno;
    public $_item_descp;
    public $_unit_id;
    public $_item_identification_marks;
	/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
    //public $_item_tarrif_heading;
	public $_hsn_code;
	/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
	public $_tax_rate;
    public $_item_cost_price;
    public $_lsc;
    public $_usc;

	/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
    //public $_exq;
    //public $_nexq;
	public $_qty;
	/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */

    public $_remarks;
    private $_createdId;
    private $_createDate;

    public $_groupcode;
    public $_group_desc;
    public $_principalname;
    public $_unitname;

	/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
	//public function __construct($itemid,$groupid,$principalid,$itemcodepartno,$itemdescp,$unitid,$itemidentificationmarks,$itemtarrifheading,$itemcostprice,$lsc,$usc,$exq,$nexq,$remarks,$createdId,$createDate,$groupcode,$groupdesc,$principalname,$unitname){
	public function __construct($itemid,$groupid,$principalid,$itemcodepartno,$itemdescp,$unitid,$itemidentificationmarks,$hsncode,$taxrate,$itemcostprice,$lsc,$usc, $qty, $remarks,$createdId,$createDate,$groupcode,$groupdesc,$principalname,$unitname){
	/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        $this->_item_id = $itemid;
        $this->_group_id = $groupid;
        $this->_principal_id = $principalid;
        $this->_item_code_partno = $itemcodepartno;
        $this->_item_descp = $itemdescp;
        $this->_unit_id = $unitid;
        $this->_item_identification_marks = $itemidentificationmarks;
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$this->_item_tarrif_heading = $itemtarrifheading;
		$this->_hsn_code = $hsncode;
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
		$this->_tax_rate = $taxrate;
        $this->_item_cost_price = $itemcostprice;
        $this->_lsc = $lsc;
        $this->_usc = $usc;
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        //$this->_exq = $exq;
        //$this->_nexq = $nexq;
		$this->_qty = $qty;
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $this->_remarks = $remarks;
        $this->_createdId = $createdId;
        $this->_createDate = $createDate;

        $this->_groupcode = $groupcode;
        $this->_group_desc = $groupdesc;
        $this->_principalname = $principalname;
        $this->_unitname = $unitname;
	}
	public static function LoadItemINFO($itemid)
	{
		$Query="";
		$Query="SELECT Item_Desc AS _item_descp FROM item_master WHERE ItemId='$itemid' ";
		$result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		 $objArray[$i]=$Row;
		 $i++;
		}
		return $objArray;	
	}
	public static function  LoadItem($itemid,$TAG=null,$ID=null,$page=null,$rp=null,$count=null,$group,$principal,$unit,$identity)
	{
        $result;
        if($itemid > 0)
        {
			/* BOF for showing tax rate in item master by Ayush Giri on 15-06-2017 */
            //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.ITEMID = $itemid";
            //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.ITEMID = $itemid";
			
			$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME,CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.ITEMID = $itemid";
			//echo $Query;exit;
            $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.ITEMID = $itemid";
			/* EOF for showing tax rate in item master by Ayush Giri on 15-06-2017 */
		    $result = DBConnection::SelectQuery($Query);
        }
        else
        {
		$itemCon ='';
				
			/* if($group != 0){
				$itemCon = $itemCon." AND gm.GroupId = $group";
			
			}
			if($principal != 0){  
					$itemCon = $itemCon." AND pm.Principal_Supplier_Id  = $principal";
			}
			if($unit != 0){  
					$itemCon = $itemCon." AND um.UNITID  = $unit";
			}
			if($identity != null){  
					$itemCon = $itemCon." AND im.Item_Identification_Mark  = '$identity'";
			} */
			
            switch($TAG)
            {
                 case "G":
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE gm.GroupId = $ID LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE gm.GroupId = $ID LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc, pm.Principal_Supplier_Name, um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE gm.GroupId = $ID LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE gm.GroupId = $ID LIMIT $page , $rp";
					
		            $result = DBConnection::SelectQuery($Query);
                    break;
                case "P":
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE pm.Principal_Supplier_Id = $ID LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE pm.Principal_Supplier_Id = $ID LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE pm.Principal_Supplier_Id = $ID LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE pm.Principal_Supplier_Id = $ID LIMIT $page , $rp";
		            $result = DBConnection::SelectQuery($Query);
                    break;
                case "U":
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE um.UNITID = $ID LIMIT $page , $rp";
		            //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE um.UNITID = $ID LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE um.UNITID = $ID LIMIT $page , $rp";
		            $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE um.UNITID = $ID LIMIT $page , $rp";
                    $result = DBConnection::SelectQuery($Query);
                    break;
                case "I":
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Identification_Mark = '$ID' LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Identification_Mark = '$ID' LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Identification_Mark = '$ID' LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Identification_Mark = '$ID' LIMIT $page , $rp";
		            $result = DBConnection::SelectQuery($Query);
                    break;
                case "C":
									
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Code_Partno = '$ID' $itemCon LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Code_Partno = '$ID' $itemCon LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '$ID' $itemCon LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Code_Partno = '$ID' $itemCon LIMIT $page , $rp";
		            $result = DBConnection::SelectQuery($Query);
                    break;
                case "D":
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Desc = '$ID' $itemCon LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.Item_Desc = '$ID' $itemCon LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Desc = '$ID' $itemCon LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id WHERE im.Item_Desc = '$ID' $itemCon LIMIT $page , $rp";
		            $result = DBConnection::SelectQuery($Query);
                    break;
                case "L":
                    break;
                case "U":
                    break;
                case "E":
                    break;
                case "N":
                    break;
                default:
                    //$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo  LIMIT $page , $rp";
                    //$CountQuery = "SELECT count(*) as total FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo  LIMIT $page , $rp";
					
					$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME, CONCAT(tm.tax_rate,'%') AS TAX_RATE FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id LIMIT $page , $rp";
                    $CountQuery = "SELECT count(*) as total FROM item_master as im LEFT JOIN group_master as gm ON im.GroupId = gm.GroupId LEFT JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId LEFT JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo LEFT JOIN hsn_master hm ON im.Tarrif_Heading = hm.hsn_code LEFT JOIN tax_master tm ON tm.tax_id = hm.tax_id LIMIT $page , $rp";
		            //echo $Query;exit();
                    $result = DBConnection::SelectQuery($Query);
                    break;
            }
        }

        $CountResult = DBConnection::SelectQuery($CountQuery);
        $RowCount = mysql_fetch_array($CountResult, MYSQL_ASSOC);
        $count = $RowCount['total'];

		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    		$_item_id = $Row['ItemId'];
            $_group_id = $Row['GroupId'];
            $_principal_id = $Row['PrincipalId'];
            $_item_code_partno = $Row['Item_Code_Partno'];
            $_item_descp = $Row['Item_Desc'];
            $_unit_id = $Row['UnitId'];
            $_item_identification_marks = $Row['Item_Identification_Mark'];
			/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
            //$_item_tarrif_heading = $Row['Tarrif_Heading'];
			$_hsn_code = $Row['Tarrif_Heading'];
			/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
			$_tax_rate = $Row['TAX_RATE'];
            $_item_cost_price = $Row['Cost_Price'];
            $_lsc = $Row['Lsc'];
            $_usc = $Row['Usc'];
            $_exq = $Row['tot_exciseQty'];
            $_nexq = $Row['tot_nonExciseQty'];
			$_qty = $Row['tot_Qty'];
            $_remarks = $Row['Remarks'];
            $_createdId = $Row['UserId'];
            $_createDate = $Row['InsertDate'];

            $_groupcode = $Row['GroupCode'];
            $_group_desc = $Row['GroupDesc'];
            $_principalname = $Row['Principal_Supplier_Name'];
            $_unitname = $Row['UNITNAME'];
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            //$newObj = new ItemMaster_Model($_item_id,$_group_id,$_principal_id,$_item_code_partno,$_item_descp,$_unit_id,$_item_identification_marks,$_item_tarrif_heading,$_item_cost_price,$_lsc,$_usc,$_exq,$_nexq,$_remarks,$_createdId,$_createDate,$_groupcode,$_group_desc,$_principalname,$_unitname);
			$newObj = new ItemMaster_Model($_item_id, $_group_id, $_principal_id, $_item_code_partno, $_item_descp, $_unit_id, $_item_identification_marks, $_hsn_code, $_tax_rate, $_item_cost_price, $_lsc, $_usc, $_qty, $_remarks, $_createdId, $_createDate, $_groupcode, $_group_desc, $_principalname, $_unitname);
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	//public static function InsertItem($GROUPID, $PRINCIPALID, $ITEM_CODE_PARTNO, $ITME_DESCP, $UNITID, $ITEM_IDENTIFICATION_MARK,$ITEM_TARRIF_HEADING, $ITEM_COST_PRICE, $LSC, $USC, $REMARKS, $CreatorID){
	public static function InsertItem($GROUPID, $PRINCIPALID, $ITEM_CODE_PARTNO, $ITME_DESCP, $UNITID, $ITEM_IDENTIFICATION_MARK,$HSN_CODE, $ITEM_COST_PRICE, $LSC, $USC, $REMARKS, $CreatorID){
		$Resp = self::CheckItemCodePartNoByPrincipalID($ITEM_CODE_PARTNO,$PRINCIPALID);
		if($Resp == QueryResponse::NO){
			$date = date("Y-m-d");
			/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
            /*if($ITEM_TARRIF_HEADING==NULL){
			    $ITEM_TARRIF_HEADING='NULL';
		    }*/
			/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
		   	// added on 01-JUNE-2016 due to Handle Special Character
		    $ITEM_CODE_PARTNO = mysql_escape_string($ITEM_CODE_PARTNO);
		    $ITME_DESCP = mysql_escape_string($ITME_DESCP);
		    $ITEM_IDENTIFICATION_MARK = mysql_escape_string($ITEM_IDENTIFICATION_MARK);
			/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
		    //$ITEM_TARRIF_HEADING = mysql_escape_string($ITEM_TARRIF_HEADING);
			$HSN_CODE = mysql_escape_string($HSN_CODE);
			/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
			$ITEM_COST_PRICE = mysql_escape_string($ITEM_COST_PRICE);
			$LSC = mysql_escape_string($LSC);
			$USC = mysql_escape_string($USC);
			$REMARKS = mysql_escape_string($REMARKS);
			
		    /* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
			//$Query = "INSERT INTO item_master (GroupId, PrincipalId, Item_Code_Partno, Item_Desc, UnitId, Item_Identification_Mark, Tarrif_Heading, Cost_Price, Lsc, Usc, Remarks, UserId, InsertDate) VALUES ($GROUPID,'$PRINCIPALID','$ITEM_CODE_PARTNO','$ITME_DESCP','$UNITID','$ITEM_IDENTIFICATION_MARK','$ITEM_TARRIF_HEADING','$ITEM_COST_PRICE','$LSC','$USC','$REMARKS','$CreatorID','$date')";
			$Query = "INSERT INTO item_master (GroupId, PrincipalId, Item_Code_Partno, Item_Desc, UnitId, Item_Identification_Mark, Tarrif_Heading, Cost_Price, Lsc, Usc, Remarks, UserId, InsertDate) VALUES ($GROUPID,'$PRINCIPALID','$ITEM_CODE_PARTNO','$ITME_DESCP','$UNITID','$ITEM_IDENTIFICATION_MARK','$HSN_CODE','$ITEM_COST_PRICE','$LSC','$USC','$REMARKS','$CreatorID','$date')";
			/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
			$Result = DBConnection::InsertQuery($Query);
			if($Result > 0){
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            //$Query = "INSERT INTO inventory (code_partNo, tot_exciseQty, tot_nonExciseQty) VALUES ($Result,0,0)";
			$Query = "INSERT INTO inventory (code_partNo, tot_Qty) VALUES ($Result,0)";
			/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
			$Result = DBConnection::InsertQuery($Query);
				return QueryResponse::YES;
			}
			else{
				return QueryResponse::NO;
			}
		}
	    else{
			return QueryResponse::NO;
		}
	}
	private static function CheckItemCodePartNoByPrincipalID($CodePartNo, $PrincipalID){
		$Query = "SELECT ITEM_CODE_PARTNO FROM item_master WHERE ITEM_CODE_PARTNO = '$CodePartNo' AND PRINCIPALID = $PrincipalID";
		$Result = DBConnection::SelectQuery($Query);
        $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		if($Row['ITEM_CODE_PARTNO'] == $CodePartNo){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
	//public static function UpdateItemDetails($ItemID, $Item_Code_Partno,$Item_Desc,$Tarrif_Heading,$Cost_Price,$Lsc,$Usc,$Remarks,$gid,$pid,$uid,$identity){
	public static function UpdateItemDetails($ItemID, $Item_Code_Partno,$Item_Desc,$HSN_CODE,$Cost_Price,$Lsc,$Usc,$Remarks,$gid,$pid,$uid,$identity){
	/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        $ItemID = $ItemID.trim();
        // added on 01-JUNE-2016 due to Handle Special Character
        $Item_Code_Partno = mysql_escape_string($Item_Code_Partno);
        $Item_Desc = mysql_escape_string($Item_Desc);
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        //$Tarrif_Heading = mysql_escape_string($Tarrif_Heading);
		$HSN_CODE = mysql_escape_string($HSN_CODE);
		/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
        $Lsc  = mysql_escape_string($Lsc);
        $Usc  = mysql_escape_string($Usc);
        $Remarks  = mysql_escape_string($Remarks);
        $identity  = mysql_escape_string($identity);
        
		/* BOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
		//$Query = "UPDATE item_master SET GroupId = $gid,PrincipalId = $pid,  Item_Code_Partno = '$Item_Code_Partno', Item_Desc = '$Item_Desc',UnitId = $uid,Item_Identification_Mark = '$identity', Tarrif_Heading = '$Tarrif_Heading', Cost_Price = '$Cost_Price', Lsc = '$Lsc', Usc = '$Usc', Remarks = '$Remarks'  WHERE ItemId = $ItemID";
		$Query = "UPDATE item_master SET GroupId = $gid,PrincipalId = $pid,  Item_Code_Partno = '$Item_Code_Partno', Item_Desc = '$Item_Desc',UnitId = $uid,Item_Identification_Mark = '$identity', Tarrif_Heading = '$HSN_CODE', Cost_Price = '$Cost_Price', Lsc = '$Lsc', Usc = '$Usc', Remarks = '$Remarks'  WHERE ItemId = $ItemID";
		/* EOF for replacing Tarrif with HSN Code by Ayush Giri on 08-06-2017 */
		$Result = DBConnection::UpdateQuery($Query);
		if($Result == QueryResponse::SUCCESS){
			return QueryResponse::YES;
		}
		else{
			return QueryResponse::NO;
		}
	}
	public static function GetAllItemList($page,$rp){
		$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo  LIMIT $page , $rp";
		//echo $Query;exit();
        $Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
	public static function GetItemListByID($ITEMID){
		$Query = "SELECT im.*,iv.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID left join inventory as iv on im.ItemId = iv.code_partNo WHERE im.ITEMID = $ITEMID";
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}


    public static function  LoadItemByPrincipalID($principalid)
	{
        //echo "Query Start Time".DateTimeClass::udate('Y-m-d H:i:s:u');
        $result = self::GetItemListByPrincipalID($principalid);
        //echo "Query End Time".DateTimeClass::udate('Y-m-d H:i:s:u');
		$objArray = array();
		$i = 0;
        //echo "Loop Start Time".DateTimeClass::udate('Y-m-d H:i:s:u');
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		               $_item_id= $Row['ItemId'];
		               $_item_code_partno= $Row['Item_Code_Partno'];
		               $objArray[$_item_id] = $_item_code_partno;
		            $i++;
				}
		        //while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		        //    $_item_id = $Row['ItemId'];
		        //    $_item_code_partno = $Row['Item_Code_Partno'];
		        //    $_group_id = null; // $Row['GroupId'];
		        //    $_principal_id = null; // $Row['PrincipalId'];

		        //    $_item_descp = null; // $Row['Item_Desc'];
		        //    $_unit_id = null; // $Row['UnitId'];
		        //    $_item_identification_marks = null; // $Row['Item_Identification_Mark'];
		        //    $_item_tarrif_heading = null; // $Row['Tarrif_Heading'];
		        //    $_item_cost_price = null; // $Row['Cost_Price'];
		        //    $_lsc = null; // $Row['Lsc'];
		        //    $_usc = null; // $Row['Usc'];
		        //    $_remarks = null; // $Row['Remarks'];
		        //    $_createdId = null; // $Row['UserId'];
		        //    $_createDate = null; // $Row['InsertDate'];

		        //    $_groupcode = null; // $Row['GroupCode'];
		        //    $_group_desc = null; // $Row['GroupDesc'];
		        //    $_principalname = null; // $Row['Principal_Supplier_Name'];
		        //    $_unitname = null; // $Row['UNITNAME'];
		        //    $newObj = new ItemMaster_Model($_item_id,$_group_id,$_principal_id,$_item_code_partno,$_item_descp,$_unit_id,$_item_identification_marks,
		        //    $_item_tarrif_heading,$_item_cost_price,$_lsc,$_usc,$_remarks,$_createdId,$_createDate,$_groupcode,$_group_desc,$_principalname,$_unitname);
		        //    $objArray[$i] = $newObj;
		        //    $i++;
        //}
        //echo "Number of Record".sizeof($objArray);
        //echo "Loop End Time".DateTimeClass::udate('Y-m-d H:i:s:u');
		return $objArray;
	}
    public static function GetItemListByPrincipalID($principalid){
		$Query = "SELECT im.*,gm.GroupCode, gm.GroupDesc,pm.Principal_Supplier_Name,um.UNITNAME FROM item_master as im INNER JOIN group_master as gm ON im.GroupId = gm.GroupId INNER JOIN principal_supplier_master as pm ON pm.Principal_Supplier_Id = im.PrincipalId INNER JOIN unit_master as um ON im.UnitId = um.UNITID WHERE im.PrincipalId = $principalid";
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
}

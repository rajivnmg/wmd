<?php
class purchaseorder_Schedule_Auto_Model
{
	public $bpoid;
    public $bpono;
    public function __construct($bpoid,$bpono){
		$this->bpoId = $bpoid;
        $this->rpo  =$bpono;
	}
    public static function getAutoList(){
		$result = self::showAutoList();
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $bpoid= $Row['BPOID'];
               $bpono= $Row['bpono'];
              $newObj = new purchaseorder_Schedule_Auto_Model($bpoid,$bpono);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
    public static function showAutoList(){
		$Query = "SELECT BPOID,CONCAT(bpono, ' ', BUYERNAME,' ',LM.LocationName)bpono
		FROM purchaseorder AS PO,buyer_master AS BM,location_master as LM
		WHERE bpoType='R' AND BM.BUYERID=PO.BUYERID AND BM.LocationID=LM.LocationId";
		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
}
class purchaseorder_Schedule_Model
{
    public $bpod_Id;
    public $sch_principalId;
    public $sch_principalName;
    public $sch_itemId;
    public $sch_codePartNo;
    public $sch_buyeritemcode;
    public $sch_rqty;
    public $schDate;
    public $itemdescp;
    public $sch_dqty;
    public $sch_ed_applicability;
    public function __construct($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability){
        $this->bpod_Id = $bpod_Id;
        $this->sch_principalId  =$sch_principalId;
        $this->sch_principalName  =$sch_principalName;
        $this->pname  =$sch_principalName;
        $this->sch_codePartNo = $sch_itemId;
        $this->cPartNo = $sch_codePartNo;
        $this->item_desc = $itemdescp;
        $this->bic=$sch_buyeritemcode;
        $this->sch_rqty = $sch_rqty;
        $this->schDate=$schDate;
        $this->sch_dqty = $sch_dqty;
        $this->sch_ed_applicability=$po_ed_applicability;
	}

      public static function getSCHPrincipal($poid){
		$result = self::showSCHPrincipal($poid);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $sch_principalId= $Row['PID'];
               $sch_principalName= $Row['PNAME'];
              $newObj = new purchaseorder_Schedule_Model($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
        
        public static function getSCHDetails($poid,$PRINCIPALID,$po_ed_applicability){
         $opt="";
         /* if($po_ed_applicability!='')
         {
		 	$opt=" AND pd.po_ed_applicability='$po_ed_applicability'";
		 }	 */
		//$Query = "SELECT im.Item_Code_Partno, posd.sch_req_qty, posd.sch_scheduledate, posd.sch_delbydateqty ,pd.po_ed_applicability FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pd ON posd.bpoId=pd.bpoId AND posd.bpod_Id=pd.bpod_Id AND posd.sch_codePartNo=pd.po_codePartNo $opt INNER JOIN item_master AS im ON im.ItemId = posd.sch_codePartNo WHERE posd.bpoId ='$poid' AND posd.sch_principalId='$PRINCIPALID' AND posd.pos_item_stage IN ('YDE','POIG') ORDER BY posd.sch_scheduledate DESC";
		
		$Query = "SELECT im.Item_Code_Partno, posd.sch_req_qty, posd.sch_scheduledate, posd.sch_delbydateqty ,pd.po_ed_applicability FROM purchaseorder_schedule_detail AS posd JOIN purchaseorder_detail AS pd ON posd.bpoId=pd.bpoId AND posd.bpod_Id=pd.bpod_Id AND posd.sch_codePartNo=pd.po_codePartNo $opt INNER JOIN item_master AS im ON im.ItemId = posd.sch_codePartNo WHERE posd.bpoId ='$poid' AND posd.sch_principalId='$PRINCIPALID' AND posd.pos_item_stage = 'YDE' ORDER BY posd.sch_scheduledate DESC";
		
		
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
               $sch_codePartNo= $Row['Item_Code_Partno'];
               $sch_rqty= $Row['sch_req_qty'];
               $schDate= $Row['sch_scheduledate'];
               $sch_dqty= $Row['sch_delbydateqty'];
              $newObj = new purchaseorder_Schedule_Model($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
        
        
        
        
    public static function showSCHPrincipal($poid){
		$Query = "select DISTINCT (pd.po_principalId) PID,pm.Principal_Supplier_Name PNAME FROM purchaseorder as po,purchaseorder_detail as pd,principal_supplier_master as pm where po.bpoId=pd.bpoId and pm.Principal_Supplier_Id=pd.po_principalId and po.bpoType='R' AND pd.bpoId = $poid";

		//echo $Query;
		$Result = DBConnection::SelectQuery($Query);
		return $Result;
    }
//#############
public static function getBPODItemDetail($bpod_Id)
{
    $Query ="SELECT ITEMID,ITEM_CODE_PARTNO,ITEM_DESC,pd.po_ed_applicability as sch_ed_applicability FROM purchaseorder_detail AS pd,item_master AS im WHERE pd.po_codePartNo=im.itemId AND pd.bpod_Id='$bpod_Id'";
    // echo $Query;
    $objArray = array(); 
 	$Result = DBConnection::SelectQuery($Query);
 	$i=0;
 	while($Row=mysql_fetch_array($Result, MYSQL_ASSOC)) {
 	
 	 $objArray[$i]=$Row;
 	  $i++;	
 	}	
 	
    return $objArray;	
}
//##########
public static function getCodePartNo($poid,$pid){
		$result = self::showCodePartNo($poid,$pid);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $sch_codePartNo= $Row['ITEM_CODE_PARTNO'];
               $sch_itemId= $Row['ITEMID'];
               $bpod_Id=$Row['bpod_Id'];
               $po_ed_applicability=$Row['po_ed_applicability'];
               $newObj = new purchaseorder_Schedule_Model($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
 public static function showCodePartNo($poid,$pid){
 	$Query = "select pd.po_codePartNo, ITEMID,ITEM_CODE_PARTNO,pd.bpod_Id,pd.po_ed_applicability from purchaseorder_detail as pd,item_master as im WHERE pd.po_codePartNo=im.itemId and pd.bpoId =$poid and pd.po_principalId=$pid ";
 	//echo $Query;
 	$Result = DBConnection::SelectQuery($Query);
    return $Result;
 }

 public static function getBuyerItemCode($poid,$cpn){
		$result = self::showBuyerItemCode($poid,$cpn);
		$objArray = array();
		$i = 0;
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
               $sch_buyeritemcode= $Row['bic'];
                $newObj = new purchaseorder_Schedule_Model($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
public static function showBuyerItemCode($poid,$cpn){
 	$Query = "select DISTINCT(po_buyeritemcode) bic from purchaseorder_detail where po_CodePartNo=$cpn and bpoid=$poid  ";
 	$Result = DBConnection::SelectQuery($Query);
    return $Result;

}

public static function InsertPODetails($bpoId,$bpod_Id,$sch_principalId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$sch_dqty){
		$schDate1=MultiweldParameter::xFormatDate($schDate);//date("Y-m-d",strtotime($schDate));
				
		//added on 03-JUNE-2016 due to Handle Special Character
		$sch_codePartNo = mysql_escape_string($sch_codePartNo);
		$sch_buyeritemcode = mysql_escape_string($sch_buyeritemcode);	
		
	    $Query = "INSERT INTO purchaseorder_schedule_detail (bpoId,bpod_Id,sch_principalId,sch_codePartNo,sch_buyeritemcode,sch_req_qty,sch_scheduledate,sch_delbydateqty,pos_item_stage) VALUES ($bpoId,$bpod_Id,$sch_principalId,$sch_codePartNo,'$sch_buyeritemcode',$sch_rqty,'$schDate1',$sch_dqty,'YDE')";
	    $Result = DBConnection::InsertQuery($Query);
        if($Result > 0)
        {
            return QueryResponse::YES;
        }
        else
        {
            return QueryResponse::NO;
        }
	}




    public static function GetItemList($poId){
		$Query = "SELECT pd.*,pm.Principal_Supplier_Name,im.Item_Code_Partno, im.Item_Desc,im.Item_Identification_Mark FROM purchaseorder_detail as pd INNER JOIN principal_supplier_master as pm ON pd.sch_principalId = pm.Principal_Supplier_Id INNER JOIN item_master as im ON im.ItemId = pd.sch_codePartNo WHERE pd.bpoId = $poId";
		$Result = DBConnection::SelectQuery($Query);;
		return $Result;
	}
    public static function  LoadPO($poId)
	{
        $result = self::GetItemList($poId);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $bpod_Id = $Row['bpod_Id'];
            $sch_pquotNo= $Row['sch_pquotNo'];
            $sch_quotNo= $Row['sch_quotNo'];
            $sch_principalId= $Row['sch_principalId'];
            $sch_principalName = $Row['Principal_Supplier_Name'];
            $sch_itemId= $Row['sch_codePartNo'];
            $sch_codePartNo= $Row['Item_Code_Partno'];
            $sch_buyeritemcode= $Row['sch_buyeritemcode'];
            $itemdescp = $Row['Item_Desc'];
            $Item_Identification_Mark=$Row['$Item_Identification_Mark'];
            $sch_qty= $Row['sch_qty'];
            $sch_unit= $Row['sch_unit'];
            $sch_price= $Row['sch_price'];
            $sch_discount= $Row['sch_discount'];
            $sch_ed_applicability= $Row['sch_ed_applicability'];
            $sch_saleTax= $Row['sch_saleTax'];
            $sch_deliverybydate= $Row['sch_deliverybydate'];
            $sch_totVal = self::getRowAmount($sch_qty,$sch_price,$sch_discount);

            $newObj = new purchaseorder_Schedule_Model($bpod_Id,$sch_principalId,$sch_principalName,$sch_itemId,$sch_codePartNo,$sch_buyeritemcode,$sch_rqty,$schDate,$itemdescp,$sch_dqty,$po_ed_applicability);
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}

}
?>

<?php
class SearchDetail_Model
{
         public $principalname;
         public $codepartno;
         public $po_qty;
         public $po_unit;
         public $po_ed_applicability;
         public $po_item_stage;
         
  
         public function __construct($principalname,$codepartno,$po_qty,$po_unit,$po_ed_applicability,$po_item_stage)
    {
        $this->principalname=$principalname;
        $this->codepartno=$codepartno;
        $this->po_qty=$po_qty;
        $this->po_unit=$po_unit;
        $this->po_ed_applicability=$po_ed_applicability;
        $this->po_item_stage=$po_item_stage;
       
    }
 public static function SelectRequiredData($POID)
 {
     $Query="SELECT psm.Principal_Supplier_Name AS principalname, im.Item_Code_Partno AS codepartno, pod.po_qty, pod.po_unit, pod.po_ed_applicability, pod.po_item_stage
FROM purchaseorder_detail AS pod
LEFT JOIN principal_supplier_master AS psm ON pod.po_principalId = psm.Principal_Supplier_Id
LEFT JOIN item_master AS im ON pod.po_codePartNo = im.ItemId where pod.bpoId=$POID";
     $result = DBConnection::SelectQuery($Query);
     $objArray = array();
     $i = 0;
     while ($Row = mysql_fetch_array($result, MYSQL_ASSOC))
     {
      
         $principalname = $Row['principalname'];
         $codepartno = $Row['codepartno'];
         $po_qty= $Row['po_qty'];
         $po_unit = $Row['po_unit'];
         $po_ed_applicability= $Row['po_ed_applicability'];
         $po_item_stage= $Row['po_item_stage'];
         $newObj = new SearchDetail_Model($principalname,$codepartno,$po_qty,$po_unit,$po_ed_applicability,$po_item_stage);
         $objArray[$i] = $newObj;
         $i++;
     }
     return $objArray;
   
 }
}
<?php

class StockCheck_Model
{
    public $poid;
    public $pon;
    public $pot;
    public $povdate;
    public $aproved;
    public $_items=array();
    public function __construct($poid,$pon,$pot,$povdate,$aproved,$items)
    {
        $this->poid=$poid;
        $this->pon=$pon;
        $this->pot=$pot;
        $this->povdate=$povdate;
        $this->aproved=$aproved;
        $this->_items=$items;
    }
    public static function LoadStockCheck($POID)
    {
        if($POID==0)
        {
            $result= self::getAllStockCheck();
        }
        else if($POID>0)
        {
            $result=self::getStockCheck($POID);
        }
        $objArray = array();
        $i = 0;
        
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            
            $poid=$Row['bpoId'];
            $pon=$Row['bpono'];
            $pot=$Row['bpoType'];
            $povdate=$Row['bpoVDate'];
            $aproved=$Row['approval_status'];
            $ManagementApproval=$Row['management_approval'];
            $PoStatus=$Row['po_status'];
            if($aproved=="A" && $ManagementApproval=="R" && $PoStatus=="O")
            {
                if($pot=="R")
                {
                $items=StockCheckDetails_Model::LoadAllForR($poid);
            
                }
                else
                {
               $items=StockCheckDetails_Model::LoadAll($poid);
                }
            }
            else if($ManagementApproval=="N" && $PoStatus=="O")
            {
             if($pot=="R")
                {
                $items=StockCheckDetails_Model::LoadAllForR($poid);
            
                }
                else
                {
               $items=StockCheckDetails_Model::LoadAll($poid);
                }
            }
            else
            {
            $items="";
            }
            $newObj = new StockCheck_Model($poid,$pon,$pot,$povdate,$aproved,$items);
            $objArray[$i] = $newObj;
            $i++;
            
        }
        return $objArray;
        
    }
    public static function getAllStockCheck()
    {
        $Query = "SELECT * FROM purchaseorder"; 
        $Result = DBConnection::SelectQuery($Query);
        return $Result;
    }
    public static function getStockCheck($POID)
    {
        $Query = "SELECT * FROM purchaseorder where bpoId=$POID"; 
        $Result = DBConnection::SelectQuery($Query);
        return $Result;  
    }
}
class StockCheckDetails_Model
{
    public $Principal;
    public $Code_Part;
    public $Description;
    public $ED_applicable;
    public $PO_QTY;
    public $Available_ED_Qty;
    public $Available_WED_Qty;
    public function __construct($Principal,$Code_Part,$Description,$ED_applicable,$PO_QTY,$Available_ED_Qty,$Available_WED_Qty)
    {
        $this->Principal=$Principal;
        $this->Code_Part=$Code_Part;
        $this->Description=$Description;
        $this->ED_applicable=$ED_applicable;
        $this->PO_QTY=$PO_QTY;
        $this->Available_ED_Qty=$Available_ED_Qty;
        $this->Available_WED_Qty=$Available_WED_Qty;
        
    }
    public static function LoadAllForR($poid)
    {
     $result=self::getStockCheckDetailsForR($poid);
      $objArray = array();
        $i = 0;
        
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $Principal=$Row['Principal'];
            $Code_Part=$Row['Code_Part'];
            $Description=$Row['Description'];
            $ED_applicable=$Row['po_ed_applicability'];
            $PO_QTY=$Row['po_qty'];
            $Available_ED_Qty=$Row['Available_ED_Qty'];
            $Available_WED_Qty=$Row['Available_WED_Qty'];
            $newObj = new StockCheckDetails_Model($Principal,$Code_Part,$Description,$ED_applicable,$PO_QTY,$Available_ED_Qty,$Available_WED_Qty);
            $objArray[$i] = $newObj;
            $i++;
            
        }
        return $objArray;
    }
       public static function LoadAll($poid)
       {
        if($poid==0)
        {
            $result= self::getAllStockCheckDetails();
        }
        else if($poid>0)
        {
            $result=self::getStockCheckDetails($poid);
        }
        $objArray = array();
        $i = 0;
        
        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $Principal=$Row['Principal'];
            $Code_Part=$Row['Code_Part'];
            $Description=$Row['Description'];
            $ED_applicable=$Row['po_ed_applicability'];
            $PO_QTY=$Row['po_qty'];
            $Available_ED_Qty=$Row['Available_ED_Qty'];
            $Available_WED_Qty=$Row['Available_WED_Qty'];
            $newObj = new StockCheckDetails_Model($Principal,$Code_Part,$Description,$ED_applicable,$PO_QTY,$Available_ED_Qty,$Available_WED_Qty);
            $objArray[$i] = $newObj;
            $i++;
            
        }
        return $objArray;
    }
    
          public static function getStockCheckDetails($poid)
           {
               $Query = "SELECT pod.po_ed_applicability,pod.po_qty,psm.Principal_Supplier_Name as Principal,im.Item_Code_Partno as Code_Part,im.Item_Desc as Description,inv.tot_exciseQty as Available_ED_Qty,inv.tot_nonExciseQty as Available_WED_Qty FROM purchaseorder_detail as pod Left join principal_supplier_master as psm on pod.po_principalId=psm.Principal_Supplier_Id Left join item_master as im on pod.po_codePartNo=im.ItemId Left join inventory as inv on pod.po_codePartNo=inv.code_partNo where pod.bpoId=$poid"; 
               $Result = DBConnection::SelectQuery($Query);
               return $Result; 
          }
          public static function getStockCheckDetailsForR($poid)
          {
          $Query="SELECT pod.po_ed_applicability,posd.sch_req_qty as po_qty,psm.Principal_Supplier_Name as Principal,im.Item_Code_Partno as Code_Part,im.Item_Desc as Description,inv.tot_exciseQty as Available_ED_Qty,inv.tot_nonExciseQty as Available_WED_Qty 
FROM purchaseorder_detail as pod 
Left join principal_supplier_master as psm on pod.po_principalId=psm.Principal_Supplier_Id 
Left join item_master as im on pod.po_codePartNo=im.ItemId 
Left join inventory as inv on pod.po_codePartNo=inv.code_partNo 
Left join purchaseorder_schedule_detail as posd on pod.bpoId=posd.bpoId
where pod.bpoId=$poid";
 $Result = DBConnection::SelectQuery($Query);
               return $Result; 
          }
          
}
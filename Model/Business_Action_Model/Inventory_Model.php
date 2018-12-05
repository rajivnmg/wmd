<?php
Logger::configure($root_path."config.xml");
$logger = Logger::getLogger('Inventory_Model');
include("../../Utility.php");
class Inventory_Model
{
    public $code_partNo;
	/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
    //public $tot_exciseQty;
    //public $tot_nonExciseQty;
	public $tot_Qty;
	/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
    public $principalname;
    public $item_codepart;
    public $item_desc;
    public $lsc;
    public $usc;
    public $price;
	
	/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
    //public function __construct($code_partNo, $tot_exciseQty, $tot_nonExciseQty,$principalname,$item_codepart,$item_desc,$lsc,$usc,$price){
	public function __construct($code_partNo, $tot_Qty, $principalname,$item_codepart,$item_desc,$lsc,$usc,$price){
	/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $this->code_partNo = $code_partNo;
		/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        //$this->tot_exciseQty = $tot_exciseQty;
        //$this->tot_nonExciseQty = $tot_nonExciseQty;
		$this->tot_Qty = $tot_Qty;
		/* EOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
        $this->principalname = $principalname;
        $this->item_codepart = $item_codepart;
        $this->item_desc = $item_desc;
        $this->lsc = $lsc;
        $this->usc = $usc;
        $this->price = $price;
	}
    public static function  checkAndUpdateInventory($Type,$code_partNo,$curentFinYear){
		
		$logger = Logger::getLogger('Inventory_Model');
		$log = "";
		$log .= "Aruguments Are:- Type=".$Type." CodePart=".$code_partNo." curentFinYear=".$curentFinYear.",";
         $Query = "";
         if($Type == "N")
         {
                $Query = "SELECT ((openQty+inQty+stQty)-outQty) as tot_nonExciseQty from ";
		        $Query =$Query."(select IFNULL(SUM(openingQty),0) AS openQty FROM  openinginventory ";
		        $Query =$Query."where openinginventory.code_partNo=$code_partNo) as t1, ";
				$Query =$Query."(select IFNULL(SUM(qty),0) AS inQty FROM ";
				$Query =$Query."incominginvoice_without_excise_detail as iwed,incominginvoice_we_mapping  as iwm ";
				$Query =$Query."where iwed.incominginvoice_we=iwm.inner_incomingInvoiceWe ";
				$Query =$Query."and itemID_code_partNo=$code_partNo and iwm.finyear='$curentFinYear') AS t2, ";
                $Query =$Query."(select IFNULL(SUM(issued_qty),0) AS stQty FROM stocktransfer_detail as std,stocktransfer_mapping as stm ";
                //$Query =$Query."where std.stdId = stm.inner_stId and std.st_codePartNo=$code_partNo and stm.finyear='$curentFinYear') as t3,";
				 $Query =$Query."where std.stId = stm.inner_stId and std.st_codePartNo=$code_partNo and stm.finyear='$curentFinYear') as t3,";
				$Query =$Query."(select IFNULL(SUM(issued_qty),0) AS outQty FROM outgoinginvoice_nonexcise_detail AS ond,outgoinginvoice_nonexcise_mapping as onm ";
				$Query =$Query."where ond.oinvoice_nexciseID=onm.inner_outgoingInvoiceNonEx ";
			    $Query =$Query."and codePartNo_desc=$code_partNo and onm.finyear='$curentFinYear') as t4";

          }else{
				
             // $Query = "select IFNULL(SUM(ExciseQty),0) AS tot_exciseQty FROM incominginventory WHERE  code_PartNo=$code_partNo ";
				$Query ="SELECT ((openQty+inQty)-(outQty)) as tot_exciseQty from ";
				$Query =$Query."(select IFNULL(SUM(openingQty),0) AS openQty ";
				$Query =$Query."FROM  openinginventory where code_partNo=$code_partNo) as t1,";
				$Query =$Query."(select IFNULL(SUM(IF(s_qty>0,s_qty,p_qty)),0) AS inQty FROM ";
				$Query =$Query."incominginvoice_excise_detail as ied,";
				$Query =$Query."incominginvoice_entryno_mapping  as iem ";
				$Query =$Query."where ied.entryId=iem.inner_EntryNo ";
				$Query =$Query."and itemID_code_partNo=$code_partNo and iem.finyear='$curentFinYear') AS t2,";
				$Query =$Query."(select IFNULL(SUM(issued_qty),0) AS outQty ";
				$Query =$Query."FROM outgoinginvoice_excise_detail AS oed,";
				$Query =$Query."outgoinginvoice_excise_mapping as oem ";
				$Query =$Query."where oed.oinvoice_exciseID=oem.inner_outgoingInvoiceEx ";
				$Query =$Query."and codePartNo_desc=$code_partNo and oem.finyear='$curentFinYear') as t3";
          }
			
          $log .= "First Query:- ".$Query.",";
          $Result = DBConnection::SelectQuery($Query);
          $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
		  $NewQty = 0;
		 if($Type == "E")
		 {
			 $log .= "NewQty:- ".$Row['tot_exciseQty'].",";
			 $NewQty = $Row['tot_exciseQty'];
		 }       
		 $Query_check = "";
         if($Type == "E")
         {
             $Query_check = "SELECT tot_Qty FROM inventory WHERE code_partNo = $code_partNo";
         }        
		 $log .= "Query_check :- ".$Query_check.",";
         $Result1 = DBConnection::SelectQuery($Query_check);
         $RecordCount=0;
         $RecordCount= mysql_num_rows($Result1);		 
		 
         if($Type == "E"){  
			if($RecordCount>0){
			  	$Query = "UPDATE inventory SET tot_Qty = $NewQty WHERE code_partNo = $code_partNo";
			}else{
			  	$Query = "INSERT INTO inventory (code_partNo,tot_Qty) values('$code_partNo','$NewQty')";
			}             
         }
       	 $log .= "Second Query:- ".$Query.",";
         $Result = DBConnection::UpdateQuery($Query);
         if($Type == "E")
         {
			 
             $Query = "SELECT tot_Qty FROM inventory WHERE code_partNo = $code_partNo";
         }        
		 $log .= "Third Query:- ".$Query.",";
         $Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $Quantity = 0;
         if($Type == "E")
         {
			 $log .= "Quantity:- ".$Row["tot_Qty"].",";
             $Quantity = $Row["tot_Qty"];
         }		
		 $logger->debug($log);// to create info type log file	
         return $Quantity;


    }

    public static function  UpdateInventory($Type,$code_partNo,$Qty,$EditTag){
		$logger = Logger::getLogger('Inventory_Model');	
		$log = "";			
		$log .= "Aruguments Are:- Type=".$Type." code_partNo=".$code_partNo." Qty=".$Qty." EditTag=".$EditTag.",";
			
         $Query = "";
         if($Type == "E")
         { 
             $Query = "SELECT tot_Qty FROM inventory WHERE code_partNo = $code_partNo";
         }
        
		 $log .= "First Query:- ".$Query.",";
         $Result = DBConnection::SelectQuery($Query);
           $RecordCount=0;
           $RecordCount= mysql_num_rows($Result);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $Prev_Qty = 0;
         if($Type == "E")
         {
             $Prev_Qty = $Row['tot_Qty'];
			  $log .= "Prev_Qty:- ".$Row['tot_Qty']." ,";
         }
         else if($Type == "N")
         {
             $Prev_Qty = $Row['tot_nonExciseQty'];
			 $log .= "Prev_Qty:- ".$Row['tot_nonExciseQty']." ,";
         }
         $NewQty = 0;
         if($EditTag == "A")
         {
             $NewQty = $Prev_Qty + $Qty;
			 $log .= "NewQty:- ".$Prev_Qty."+".$Qty." ,";
         } 
         else if($EditTag == "S")
         {
             if($Prev_Qty > $Qty || $Prev_Qty == $Qty)
             {
                 $NewQty = $Prev_Qty - $Qty;
				 $log .= "NewQty:- ".$Prev_Qty."-".$Qty." ,";
             }
         }
          if($Type == "E")
         {    if($RecordCount>0)
              {
			  	$Query = "UPDATE inventory SET tot_Qty = $NewQty WHERE code_partNo = $code_partNo";
			  }
			  else
			  {
			  	$Query = "INSERT INTO inventory (code_partNo,tot_Qty) values('$code_partNo','$NewQty')";
			  }
             
         }
         
		  $log .= "Second Query:- ".$Query." ,";
         $Result = DBConnection::UpdateQuery($Query);
         if($Type == "E")
         {
             $Query = "SELECT tot_Qty FROM inventory WHERE code_partNo = $code_partNo";
         }
         
		 $log .= "Third Select Query:- ".$Query." ,";
         $Result = DBConnection::SelectQuery($Query);
         $Row = mysql_fetch_array($Result, MYSQL_ASSOC);
         $Quantity = 0;
         if($Type == "E")
         {
             $Quantity = $Row["tot_Qty"];
			 $log .= "Quantity:- ".$Row["tot_Qty"]." ,";
         }
		 $logger->debug($log);// to create info type log file	
         return $Quantity;
    }
    public static function  LoadInventory($code_partNo)
	{
        $Query = "SELECT * FROM inventory WHERE code_partNo = $code_partNo";
		$Result = DBConnection::SelectQuery($Query);
		$objArray = array();
		$i = 0;
		while ($Row = mysql_fetch_array($Result, MYSQL_ASSOC)) {
            $code_partNo = $Row['code_partNo'];
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            //$tot_exciseQty = $Row['tot_exciseQty'];
            //$tot_nonExciseQty = $Row['tot_nonExciseQty'];
			$tot_Qty = $Row['tot_Qty'];
			//$newObj = new Inventory_Model($code_partNo, $tot_exciseQty, $tot_nonExciseQty,null,null,null,null,null,null);
            $newObj = new Inventory_Model($code_partNo, $tot_Qty,null,null,null,null,null,null);
			/* BOF for merging Excise and Non Excise quantity  by Ayush Giri on 08-06-2017 */
            $objArray[$i] = $newObj;
            $i++;
		}
		return $objArray;
	}
	
	
	
}

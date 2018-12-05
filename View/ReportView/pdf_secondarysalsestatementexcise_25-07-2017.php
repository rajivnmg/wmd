<?php 
include('../../Model/DBModel/fpdf.php');
$d=date('d_m_Y');
 $total = 0;
class PDF extends FPDF
{

    function Header()
    {
        $this->SetFont('Helvetica','',25);
        $this->SetFontSize(40);
        //Move to the right
        $this->Cell(80);
        //Line break
        $this->Ln();
    }

    //Page footer
    function Footer()
    {
        
    }

    //Load data
    function LoadData($file)
    {
        //Read file lines
        $lines=file($file);
        $data=array();
        foreach($lines as $line)
            $data[]=explode(';',chop($line));
        return $data;
    }

    //Simple table
    function BasicTable($data)
    {
        //$this->SetFillColor(255,255,255);
        //$this->SetDrawColor(255, 0, 0);
        
        //Data
        $this->SetFont('Arial','',10);
        foreach ($data as $eachResult) 
        { //width 
			$lines = (strlen($eachResult["Item_Desc"]))/40;
			$row_height = 5;
			if($lines > 1) {
				$row_height = $row_height * ceil($lines);
			}
			
			
			if (strlen($eachResult["Item_Desc"]) > 22){
				$Item_Desc = substr($eachResult["Item_Desc"], 0, 22) . '...';
			}else{
				$Item_Desc = $eachResult["Item_Desc"];
			}
			
			if (strlen($eachResult["BuyerName"]) > 22){
				$bname = substr($eachResult["BuyerName"], 0, 22) . '...';
			}else{
				$bname = $eachResult["BuyerName"];
			}
			
			
			$marketsegment = '';
				if($eachResult["msid"] == 1){
					$marketsegment = 'AUTO';
				}else if($eachResult["msid"] == 2){
					$marketsegment = 'GEN';
				}else if($eachResult["msid"] == 3){
					$marketsegment = 'MRO';
				}else if($eachResult["msid"] == 4){
					$marketsegment = 'OEM';
				}else{
					$marketsegment = 'N/A';
				}
			
            $this->Cell(1);
            $this->Cell(6,$row_height,$eachResult["SN"],0);
            $this->Cell(22,$row_height,$eachResult["oinvoice_No"],0);
            $this->Cell(22,$row_height,$eachResult["oinv_date"],0,0);
            $this->Cell(25,$row_height,$eachResult["BuyerCode"],0);
            $this->Cell(55,$row_height,$bname,0);
            $this->Cell(18,$row_height,$eachResult["Item_Code_Partno"],0);
			$this->Cell(50,$row_height,$Item_Desc,0);
			$this->Cell(12,$row_height,$eachResult["issued_qty"],0);
            $this->Cell(12,$row_height,$eachResult["UNITNAME"],0);
            $this->Cell(18,$row_height,$eachResult["basic_purchase_price"],0);
            $this->Cell(20,$row_height,number_format((float)$eachResult["totalprice"], 2, '.', ''),0);
            $this->Cell(15,$row_height,$marketsegment,0);
            $this->Ln();
             $total = $total + number_format((float)$eachResult["totalprice"], 2, '.', '');
        }
        
        
			$this->Cell(1);
            $this->Cell(6,5,'',0);
            $this->Cell(22,5,'',0);
            $this->Cell(22,5,'',0,0);
            $this->Cell(25,5,'',0);
            $this->Cell(55,5,'',0);
            $this->Cell(18,5,'',0);
            $this->Cell(50,5,'',0);
            $this->Cell(12,5,'',0);
            $this->Cell(12,5,'Grand Total : ',0);
            $this->Cell(18,5,'',0);
            $this->Cell(20,5,$total,0);
            $this->Cell(15,5,'',0);
            $this->Ln();
    }

    //Better table
}

$pdf=new PDF();

$header=array('SNo','InvoiceNo','InvoiceDate','BuyerCode','BuyerName','PartNo','Item Desc','Qty','Unit','Price','TotalPrice','Market Segment');
//Data loading
//*** Load MySQL Data ***//
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
include_once("../../Model/Param/param_model.php");
$CompanyInfo = ParamModel::GetCompanyInfo();
//*** Table 1 ***//
$pdf->AddPage('L');

$pdf->SetFont('Helvetica','',14);
$pdf->Cell(55);

$pdf->Write(5, 'Excise Secondary Sales Statement Report : From  '.date("d-m-Y", strtotime($_REQUEST['fromdate'])).' To '.date("d-m-Y", strtotime($_REQUEST['todate'])).'');
$pdf->Ln();
$pdf->Cell(25);
$pdf->Write(5, $CompanyInfo["Name"].' ,'.$CompanyInfo["Address"]);
$pdf->Ln();
$pdf->SetFillColor(255,255,255);
$w=array(6,22,22,25,55,18,50,12,12,18,20,15);
$pdf->SetFont('Arial','B',9);
for($i=0;$i<count($header);$i++)
    $pdf->Cell($w[$i],7,$header[$i],0,0,'L',true);
$pdf->Ln();
$GrandTotal = 0.00;
/* $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,pod.po_price ,
(oied.issued_qty * pod.po_price) as totalprice from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID 
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join purchaseorder_detail as pod ON oied.oinv_price = pod.bpod_Id
CROSS JOIN (SELECT @cnt := 0) AS dummy 
where oie.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' AND oie.principalID = ".$_REQUEST['pid']."  order by SN ASC;"; */





/*
		$Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,iod.basic_purchase_price ,
(oied.issued_qty * iod.basic_purchase_price) as totalprice, ms.msname from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join market_segment as ms on ms.msid = oie.msid 
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$_REQUEST['todate']."' AND '".$_REQUEST['fromdate']."' AND oie.principalID = ".$_REQUEST['pid']."  order by SN ASC;";
		
		*/
		
		$todate = $_REQUEST['todate'];
		$fromdate = $_REQUEST['fromdate'];
		$finyear = $_REQUEST['finyear'];
		$principalid = $_REQUEST['Principal'];
		$itemid = $_REQUEST['itemid'];
		$salseuser = $_REQUEST['salseuser'];
		$marketsegment = $_REQUEST['marketsegment'];
		$buyerid	= $_REQUEST['buyerid'];

         $cond ='';
         $cond1 ='';
        if(!empty($principalid))
        {
            $cond = $cond."AND oie.principalID = '".$principalid."'";
          
        }
        if(!empty($buyerid))
        {
            $cond = $cond."AND oie.BuyerID = '".$buyerid."'";
          
        }
        if(!empty($itemid))
        {
            $cond = $cond."AND oied.codePartNo_desc = '".$itemid."'";
          
        }
               
        if(!empty($salseuser)) {
            $cond = $cond."AND po.executiveId LIKE '".$salseuser."%'";
        }
        if(!empty($marketsegment)) {
            $cond = $cond."AND oie.msid = '".$marketsegment."'";
        }        
     
        $finyrs ='';
         if(!empty($finyear)){			 
				$finyears = explode(',',$finyear);
				 foreach ($finyears as $year){
					 if(empty($finyrs)){
							$finyrs = $finyrs.'oim.finyear = "'.$year.'"';
					}else{
						$finyrs = $finyrs.' OR oim.finyear = "'.$year.'"';
					}					 
				}				
		} 
        
        
        
          $Query = "select (@cnt := @cnt + 1) AS SN, oie.oinvoice_No, oie.oinv_date, bm.BuyerCode,
bm.BuyerName ,im.Item_Code_Partno, im.Item_Desc,oied.issued_qty ,um.UNITNAME ,iod.basic_purchase_price ,
(oied.issued_qty * iod.basic_purchase_price) as totalprice,oie.msid,oim.finyear from outgoinginvoice_excise_detail as oied
inner join outgoinginvoice_excise as oie on oied.oinvoice_exciseID = oie.oinvoice_exciseID
inner join outgoinginvoice_excise_mapping as oim ON oie.oinvoice_exciseID=oim.inner_outgoingInvoiceEx
inner join buyer_master as bm ON oie.BuyerID = bm.BuyerId
inner join item_master as im on im.ItemId = oied.codePartNo_desc
inner join unit_master as um ON im.UnitId = um.UnitId
inner join incominginvoice_excise_detail as iod ON oied.iinv_no = iod.entryDId
CROSS JOIN (SELECT @cnt := 0) AS dummy
where oie.oinv_date BETWEEN '".$todate."' AND '".$fromdate."' $cond AND ($finyrs) order by SN ASC;";
   




		
$objQuery = DBConnection::SelectQuery($Query);
    if(mysql_num_rows($objQuery) > 0)
    {
        $resultData = array();
        for ($i=0;$i<mysql_num_rows($objQuery);$i++) {
            $result = mysql_fetch_array($objQuery);
            
          
          //  $GrandTotal = $GrandTotal + $result[10];
            array_push($resultData,$result);
        }
        $pdf->BasicTable($resultData);
    }
$pdf->SetFont('Arial','',12);
$pdf->Cell(220);
//$pdf->Write(5, "Grand Total : ". $GrandTotal);
$pdf->Ln();
$pdf->Output();
?>

<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");
include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
include '../../Model/Masters/GroupMaster_Model.php';
header("Content-disposition: attachment; filename=Excise_StockStatement_WithValue.xls");

echo 'SNo' . "\t" . 'PartNo'."\t".'Product Description' . "\t" . 'HSN Code'."\t".'Quantity' 
        . "\t" . 'Unit'."\t". 'Price' . "\t" . 'TotalPrice'."\t"."\n";
$groupData = GroupMasterModel::GetGroupList();

$GrandTotal = 0.00;
while ($grouprow = mysql_fetch_array($groupData,MYSQL_ASSOC))
{
		//print_r($grouprow); exit;
    $objQuery = StockStatementModel::GetExciseStock($grouprow["GroupId"]);
    if(mysql_num_rows($objQuery) > 0)
    {
        echo "Group : ". $grouprow["GroupDesc"]."\t"."\n";
        $GrossTotal = 0.00;
        while ($row = mysql_fetch_array($objQuery,MYSQL_ASSOC)) {
			
            echo $row['SN'] . "\t" . $row['Item_Code_Partno'] ."\t".$row['Item_Desc'] . "\t" . $row['Tarrif_Heading'] 
                    ."\t".$row['tot_Qty'] . "\t" . $row['UNITNAME'] ."\t".$row['Cost_Price'] 
                    . "\t" . number_format((float)$row["total_price"], 2, '.', '')."\t"."\n";
            $GrossTotal = $GrossTotal + $row['total_price'];
            
        }
        echo "\t"."\t"."\t"."\t"."\t"."\t"."Gross Total : ". $GrossTotal."\t"."\n";
        $GrandTotal = $GrandTotal + $GrossTotal;
    }
}
echo "\t"."\t"."\t"."\t"."\t"."\t"."Grand Total : ". $GrandTotal."\t";


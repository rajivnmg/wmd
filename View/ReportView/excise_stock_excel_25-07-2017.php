<?php
header("Content-type: application/x-msexcel");
header("Content-Type: application/vnd.ms-excel");

include '../../Model/DBModel/DbModel.php';
include '../../Model/ReportModel/StockStatementModel.php';
include '../../Model/Masters/GroupMaster_Model.php';
header("Content-disposition: attachment; filename=Excise_Stock.xls");
echo 'SNo' . "\t" . 'PartNo'."\t".'Product Description' . "\t" . 'TarifHeading'."\t".'Quantity' 
        . "\t" . 'Unit'."\t"."\n";
$groupData = GroupMasterModel::GetGroupList();
while ($grouprow = mysql_fetch_array($groupData,MYSQL_ASSOC))
{
    $objQuery = StockStatementModel::GetExciseStock($grouprow["GroupId"]);
    if(mysql_num_rows($objQuery) > 0)
    {
        echo "Group : ". $grouprow["GroupDesc"]."\t"."\n";
        while ($row = mysql_fetch_array($objQuery,MYSQL_ASSOC))
        {
            echo $row['SN'] . "\t" . $row['Item_Code_Partno'] ."\t".$row['Item_Desc'] . "\t" . $row['Tarrif_Heading'] 
                    ."\t".$row['tot_exciseQty'] . "\t" . $row['UNITNAME'] ."\t"."\n";
        }
    }
}
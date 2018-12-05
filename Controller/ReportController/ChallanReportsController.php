<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/ReportModel/StockStatementModel.php");

$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case "SEARCH":
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $start=(($page-1)*$rp);      
        $Data=$_REQUEST['SearchData'];
        
        $Data = str_replace('\\','', $Data);
        $obj = json_decode($Data);   
       $SearchKey=$obj->{'SearchKey'};
       
        $rows=StockStatementModel::GetItemExciseNonExciseChallanIssuedQty($SearchKey); // function to return chalan issue qty,Non-exice/excise qty and total qty
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 0;
        foreach($rows AS $row){
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                    'SN' =>$i+1,
                    'Item_Code_Partno'=>$row['Item_Code_Partno'],
                    'Item_desc'=>$row['Item_desc'],
                    'tot_exciseQty'=>$row['tot_exciseQty'],
                    'tot_nonExciseQty'=>$row['tot_nonExciseQty'],
                    'issue_qty'=>'<a href="javascript:showChalans('.$row['Item_Code_Partno'].','.$row['itemid'].');" title ="Click To View Challans">'.$row['issue_qty'].'</a>',
                    'stock_qty'=>$row['stock_qty']
                    )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        //$jsonData['total'] = count($rows);
        $jsonData['total'] =StockStatementModel::countRec($SearchKey);
         
        echo json_encode($jsonData);
        break;
}


<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/SearchDetail_Model.php");

$Type = $_REQUEST['TYP'];

if($Type == null)
    $Type = QueryModel::PAGELOAD;
switch($Type){
    case "SEARCH":
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
        $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
        $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
        $query = isset($_POST['query']) ? $_POST['query'] : false;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;

     
        $rows= SearchDetail_Model::SelectRequiredData($_REQUEST['POID']);
        header("Content-type: application/json");
        $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
        $i = 0;
        foreach($rows AS $row){
            
            //If cell's elements have named keys, they must match column names
            //Only cell's with named keys and matching columns are order independent.
            $entry = array('id' => $i,
                'cell'=>array(
                   // 'bpoId'=> $row->bpoId,
                    'principalname'=> $row->principalname,
                    'codepartno'=>$row->codepartno,
                    'po_qty'=>$row->po_qty,
                    //'BuyerId'=>$row->BuyerId,
                    //'BuyerName'=>$row->BuyerName,
                    'po_unit'=>$row->po_unit,
                    'po_ed_applicability'=>$row->po_ed_applicability,
                    'po_item_stage'=>$row->po_item_stage
                   
                )
            );
            $i++;
            
            $jsonData['rows'][] = $entry;
        }
        $jsonData['total'] = count($rows);
        echo json_encode($jsonData);
        break;
        
}
?>
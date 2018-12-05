<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	$Print = DashboardModel::LoadPODetails($_REQUEST['POID'],$_REQUEST['BPONO']);
	$pending = DashboardModel::LoadPendingPoItemDetails($_REQUEST['POID'],$_REQUEST['BPONO']);
	//print_r($Print); exit;
	//print_r($pending); exit;
	$itempart = array();
		$i = 1;
		$items = array();
        echo'<table class="table table-striped table-bordered table-hover">
					<thead><tr ><th rowspan="2" style="vertical-align: middle;text-align: center;">#</th><th rowspan="2" style="vertical-align: middle;text-align: center;">CODEPART NO</th><th rowspan="2" style="vertical-align: middle;text-align: center;">ITEM DESC</th><th rowspan="2" style="vertical-align: middle;text-align: center;">ORDER QTY</th><th rowspan="2" style="vertical-align: middle;text-align: center;">ISSUED QTY</th><th rowspan="2" style="vertical-align: middle;text-align: center;">BALANCE QTY</th><th colspan="2" style="text-align: center;">STOCK QTY</th></tr>
					<tr><th>EX QTY</th><th>NON-EX QTY</th></tr></thead><tbody>';
					if(sizeof($Print) > 0){						
						foreach($Print as $row){ 						
						$currentStock = DashboardModel::getItemCurrentStock($row['po_codePartNo']);	
						$link=null;
						$findme   = 'E';
							$pos = strrchr($row['oinvoice_No'], $findme);
							
							if(array_key_exists($row['Item_Code_Partno'], $itempart)){								
								$itempart[$row['Item_Code_Partno']] = $itempart[$row['Item_Code_Partno']] + $row['issued_qty'];// + $itempart[$row['Item_Code_Partno']];
								
							}else{							
								$itempart[$row['Item_Code_Partno']] =	$row['issued_qty'];																	
							}					
							echo'<tr class="odd gradeX">
							<td>'.$i++.'</td>';// <td><a href="javascript:showPoInvoiceDetails('.$row['bpoId'].',\''.$row['oinvoice_No'].'\',\''.$row['bpono'].'\',\''.$row['invid'].'\',\''.$row['bpoType'].'\')">'.$row['oinvoice_No'].'</a></td>				   
							echo'<td>'.$row['Item_Code_Partno'].'</td><td>'.$row['Item_Desc'].'</td><td>'.$row['ordered_qty'].'</td><td>'.$row['issued_qty'].'</td><td>'.($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]).'</td><td>'.$currentStock['tot_exciseQty'].'</td><td>'.$currentStock['tot_nonExciseQty'].'</td></tr>';
							$items[] = $row['Item_Code_Partno'];
							
							if(($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]) == 0){
								$itempart[$row['Item_Code_Partno']] = 0;
							}
											
							}
						}
						if(sizeof($pending) > 0){
							
							foreach($pending as $rows){ 
								if(!in_array($rows['Item_Code_Partno'], $items)){								
								$currentStock = DashboardModel::getItemCurrentStock($rows['po_codePartNo']);	
								
                                  echo'<tr class="odd gradeX"><td>'.$i++.'</td><td>'.$rows['Item_Code_Partno'].'</td> <td>'.$rows['Item_Desc'].'</td><td>'.$rows['ordered_qty'].'</td><td>0</td><td>'.$rows['ordered_qty'].'</td><td>'.$currentStock['tot_exciseQty'].'</td><td>'.$currentStock['tot_nonExciseQty'].'</td></tr>'; 
								}
							}
						}
						
						echo' </tbody></table>';		
					 
?>
					

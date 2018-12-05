<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	$Print = DashboardModel::LoadPODetails4PickList($_REQUEST['POID'],$_REQUEST['BPONO']);	
	$pending = DashboardModel::LoadPendingPoItemDetails($_REQUEST['POID'],$_REQUEST['BPONO']);
	
	$itempart = array();
		$i = 0;
		$items = array();
        echo'<div><form name="formPickList" id="formPickList" action="../../pdf/printPickList.php" method="post" target="_blank">
       
			<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr >
							<th rowspan="2" style="vertical-align: middle;text-align: center;">#</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">CODEPART NO</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">ITEM DESC</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">ORDER QTY</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">ISSUED QTY</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">BALANCE QTY</th>
							<th colspan="2" style="text-align: center;">STOCK QTY</th>
							<th rowspan="2" style="vertical-align: middle;text-align: center;">PICK LIST QTY</th>
						</tr>
						<tr>
							<th>EX QTY</th>	
							<th>NON-EX QTY</th>
						</tr>
					</thead>
					<tbody>';
					
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
									<td>'.++$i.'</td>
									<td>'.$row['Item_Code_Partno'].'</td>
									<td>'.$row['Item_Desc'].'</td>
									<td>'.$row['ordered_qty'].'</td>
									<td>'.$row['issued_qty'].'</td>
									<td>'.($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]).'</td>
									<td>'.$currentStock['tot_exciseQty'].'</td>
									<td>'.$currentStock['tot_nonExciseQty'].'</td>
									<td>
										<input type="checkbox" name="checkbox'.$i.'" id="'.$i.'" onChange="showPickListQtyBox(this.id)">
										<span style="display: none;" id="picklistQtyDiv'.$i.'">
											<input type="text" name="pickQty'.$i.'" id="pickQty'.$i.'" size="5" onblur="validatePickListQty(this.value,'.$i.',\''.$row['po_ed_applicability'].'\');" />
										</span>
								
									 </td>
							
								   </tr>
									<input type="hidden" name="Item_Code_Partno'.$i.'" id="Item_Code_Partno'.$i.'" value="'.$row['Item_Code_Partno'].'" />
									<input type="hidden" name="Item_Desc'.$i.'" id="Item_Desc'.$i.'" value="'.$row['Item_Desc'].'" />
									<input type="hidden" name="OrderQty'.$i.'" id="OrderQty'.$i.'" value="'.$row['ordered_qty'].'" />
									<input type="hidden" name="issueQty'.$i.'" id="issueQty'.$i.'" value="'.$row['issued_qty'].'" />
									<input type="hidden" name="balanceQty'.$i.'" id="balanceQty'.$i.'" value="'.($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]).'" />
								    <input type="hidden" name="exStockQty'.$i.'" id="exStockQty'.$i.'" value="'.$currentStock['tot_exciseQty'].'" />
									<input type="hidden" name="nonExStockQty'.$i.'" id="nonExStockQty'.$i.'" value="'.$currentStock['tot_nonExciseQty'].'" />
								   ';
								$items[] = $row['Item_Code_Partno'];							
								if(($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]) == 0){
									$itempart[$row['Item_Code_Partno']] = 0;
								}											
							}
						}
						if(sizeof($pending) > 0){
							foreach($pending as $rows){ 
								$currentStock = DashboardModel::getItemCurrentStock($rows['po_codePartNo']);	
								if(!in_array($rows['Item_Code_Partno'], $items)){
                                  echo'<tr class="odd gradeX">
                                  <td>'.++$i.'</td>
                                  <td>'.$rows['Item_Code_Partno'].'</td>
                                  <td>'.$rows['Item_Desc'].'</td>
                                  <td>'.$rows['ordered_qty'].'</td>
                                  <td>0</td>
                                  <td>'.$rows['ordered_qty'].'</td>
                                  <td>'.$currentStock['tot_exciseQty'].'</td>
                                  <td>'.$currentStock['tot_nonExciseQty'].'</td>
                                  <td><input type="checkbox" name="checkbox'.$i.'" id="'.$i.'" onChange="showPickListQtyBox(this.id)" value="'.$i.'">
									<span style="display: none;" id="picklistQtyDiv'.$i.'">
										<input type="text" name="pickQty'.$i.'" id="pickQty'.$i.'" size="5" onblur="validatePickListQty(this.value,'.$i.',\''.$rows['po_ed_applicability'].'\');" />
									</span>
							
								
								</td>						
								
								</tr>
								<input type="hidden" name="Item_Code_Partno'.$i.'" id="Item_Code_Partno'.$i.'" value="'.$rows['Item_Code_Partno'].'" />
								<input type="hidden" name="Item_Desc'.$i.'" id="Item_Desc'.$i.'" value="'.$rows['Item_Desc'].'" />
								<input type="hidden" name="OrderQty'.$i.'" id="OrderQty'.$i.'" value="'.$rows['ordered_qty'].'" />
								<input type="hidden" name="issueQty'.$i.'" id="issueQty'.$i.'" value="0" />
								<input type="hidden" name="balanceQty'.$i.'" id="balanceQty'.$i.'" value="'.$rows['ordered_qty'].'" />
								<input type="hidden" name="exStockQty'.$i.'" id="exStockQty'.$i.'" value="'.$currentStock['tot_exciseQty'].'" />
							    <input type="hidden" name="nonExStockQty'.$i.'" id="nonExStockQty'.$i.'" value="'.$currentStock['tot_nonExciseQty'].'" />
								   '; 
								
								}
							}
						}
						
						echo' </tbody></table>';
						
						echo' <input type="hidden" name="buyerIDs" id="buyerIDs" value="'.$_REQUEST['BUYERIDS'].'" />
							  <input type="hidden" name="poID" id="poID" value="'.$_REQUEST['POID'].'"/>
							  <input type="hidden" name="totalCount" id="totalCount" value="'.$i.'"/>';
							
														
						echo" <input type='submit' class='btn btn-primary' id='popicklist' name='bbb' style='margin-left: 46%;' value='Print List'/> </form></div>";			
					 
?>
					

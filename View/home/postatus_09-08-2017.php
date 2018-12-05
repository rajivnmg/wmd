<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	
			
	$Print = DashboardModel::LoadPODetails($_REQUEST['POID'],$_REQUEST['BPONO'],$_REQUEST['poType']);
	
	$pending = DashboardModel::LoadPendingPoItemDetails($_REQUEST['POID'],$_REQUEST['BPONO'],$_REQUEST['poType']);
	$itempart = array();
		$i = 1;
		$items = array();
        echo'<table class="table table-striped table-bordered table-hover">
					<thead><tr><th>#</th><th>PONO</th><th>PO DATE</th><th>INVOICE No.</th><th>INVOICE DATE</th><th>CODEPART NO</th><th>ITEM DESC</th><th>ORDER QTY</th><th>ISSUED QTY</th><th>BALANCE QTY</th><th>BILL VALUE</th></tr></thead><tbody>';
					if(sizeof($Print) > 0){					
						foreach($Print as $row){ 
						$link=null;
						$findme   = 'E';
							$pos = strrchr($row['oinvoice_No'], $findme);
							if($pos !='' || $pos != null){ 
							
							$link =' ../Business_View/invoice_outgoingexcise.php?TYP=SELECT&OutgoingInvoiceExciseNum='.$row['invid'];
							}else{
							$link =' ../Business_View/invoice_outgoingNonExcise.php?TYP=SELECT&OutgoingInvoiceNonExciseNum='.$row['invid'];
							} 
							
							if(array_key_exists($row['Item_Code_Partno'], $itempart)){
								
								$itempart[$row['oinvoice_No']][$row['Item_Code_Partno']] = $itempart[$row['oinvoice_No']][$row['Item_Code_Partno']] + $row['issued_qty'];
								
							}else{							
								$itempart[$row['oinvoice_No']][$row['Item_Code_Partno']] =	$row['issued_qty'];																	
							}
							
								echo'<tr class="odd gradeX">
								<td>'.$i++.'</td><td>'.$row['bpono'].'</td><td>'.$row['bpoDate'].'</td><td><a href="'.$link.'" target="_blank">'.$row['oinvoice_No'].'</a></td> ';// <td><a href="javascript:showPoInvoiceDetails('.$row['bpoId'].',\''.$row['oinvoice_No'].'\',\''.$row['bpono'].'\',\''.$row['invid'].'\',\''.$row['bpoType'].'\')">'.$row['oinvoice_No'].'</a></td>				   
								echo'<td>'.$row['oinv_date'].'</td><td>'.$row['Item_Code_Partno'].'</td><td>'.$row['Item_Desc'].'</td><td>'.$row['ordered_qty'].'</td><td>'.$row['issued_qty'].'</td><td>'.($row['ordered_qty'] - $itempart[$row['oinvoice_No']][$row['Item_Code_Partno']]).'</td><td>'.$row['bill_value'].'</td></tr>';
								$items[] = $row['Item_Code_Partno'];
								
								if(($row['ordered_qty'] - $itempart[$row['oinvoice_No']][$row['Item_Code_Partno']]) == 0){
									$itempart[$row['oinvoice_No']][$row['Item_Code_Partno']] = 0;
								}
											
							}
						}
												
						if(sizeof($pending) > 0){
							foreach($pending as $rows){ 
								
								if($_REQUEST['poType']=='Recurring'){
									 echo'<tr class="odd gradeX"><td>'.$i++.'</td><td>'.$rows['bpono'].'</td><td>'.$rows['bpoDate'].'</td><td>---</td><td>---</td><td>'.$rows['Item_Code_Partno'].'</td> <td>'.$rows['Item_Desc'].'</td><td>'.$rows['ordered_qty'].'</td><td>0</td><td>'.$rows['ordered_qty'].'</td><td>---</td></tr>'; 
								}
								if(!in_array($rows['Item_Code_Partno'], $items)){
                                  echo'<tr class="odd gradeX"><td>'.$i++.'</td><td>'.$rows['bpono'].'</td><td>'.$rows['bpoDate'].'</td><td>---</td><td>---</td><td>'.$rows['Item_Code_Partno'].'</td> <td>'.$rows['Item_Desc'].'</td><td>'.$rows['ordered_qty'].'</td><td>0</td><td>'.$rows['ordered_qty'].'</td><td>---</td></tr>'; 
								}
							}
						} 
						echo' </tbody></table>';		
					 
?>
					

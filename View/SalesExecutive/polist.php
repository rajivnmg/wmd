<?php //session_start();
	print_r('Rajiv'); exit;
	include('root.php');
	include($root_path."GlobalConfig.php");
	include_once( $root_path."Model/Business_Action_Model/Quation_Model.php");
	include_once( $root_path."Model/Business_Action_Model/po_model.php");
	echo 'here';
	$Print = DashboardModel::quotationPoList($_REQUEST['quotId'],$_REQUEST['quotNo']);
	echo '2';
	print_r($Print); exit;
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
								
								$itempart[$row['Item_Code_Partno']] = $itempart[$row['Item_Code_Partno']] + $row['issued_qty'];// + $itempart[$row['Item_Code_Partno']];
								
							}else{							
								$itempart[$row['Item_Code_Partno']] =	$row['issued_qty'];																	
							}
							
								echo'<tr class="odd gradeX">
								<td>'.$i++.'</td><td>'.$row['bpono'].'</td><td>'.$row['bpoDate'].'</td><td><a href="'.$link.'" target="_blank">'.$row['oinvoice_No'].'</a></td> ';// <td><a href="javascript:showPoInvoiceDetails('.$row['bpoId'].',\''.$row['oinvoice_No'].'\',\''.$row['bpono'].'\',\''.$row['invid'].'\',\''.$row['bpoType'].'\')">'.$row['oinvoice_No'].'</a></td>				   
								echo'<td>'.$row['oinv_date'].'</td><td>'.$row['Item_Code_Partno'].'</td><td>'.$row['Item_Desc'].'</td><td>'.$row['ordered_qty'].'</td><td>'.$row['issued_qty'].'</td><td>'.($row['ordered_qty'] - $itempart[$row['Item_Code_Partno']]).'</td><td>'.$row['bill_value'].'</td></tr>';
								$items[] = $row['Item_Code_Partno'];
											
							}
						}						
		echo' </tbody></table>';		
					 
?>
					
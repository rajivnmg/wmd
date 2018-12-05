<?php
	include('root.php');
	include($root_path."GlobalConfig.php");
	include_once( $root_path."Model/Business_Action_Model/Quation_Model.php");
	include_once( $root_path."Model/Business_Action_Model/po_model.php");
	$Print = Quotation_Model::quotationPoList($_REQUEST['quotId'],$_REQUEST['quotNo']);	
	$i = 1;
    echo'<table class="table table-striped table-bordered table-hover">
			<thead><tr><th>#</th><th>PONO</th><th>PO DATE</th><th>CODEPART NO</th><th>ORDER QTY</th><th>ITEM DESC</th></tr></thead><tbody>';
			if(sizeof($Print) > 0){					
				foreach($Print as $row){ 
					echo'<tr class="odd gradeX">
					<td>'.$i++.'</td>
					<td>'.$row['bpono'].'</td>
					<td>'.$row['bpoDate'].'</td>';// <td><a href="javascript:showPoInvoiceDetails('.$row['bpoId'].',\''.$row['oinvoice_No'].'\',\''.$row['bpono'].'\',\''.$row['invid'].'\',\''.$row['bpoType'].'\')">'.$row['oinvoice_No'].'</a></td>				   
					echo'<td>'.$row['Item_Code_Partno'].'</td>
					<td>'.$row['po_qty'].'</td>
					<td>'.$row['Item_Desc'].'</td>
				  </tr>';
				}
			} echo'</tbody></table>';							 
?>
					
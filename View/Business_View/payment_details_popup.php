<?php
	include('root.php');
	include($root_path."GlobalConfig.php");
	include_once( "../../Model/Business_Action_Model/pay_model.php");
	// Quotation_Model::quotationPoList($_REQUEST['quotId'],$_REQUEST['quotNo']);	
	$Print = payment_Model::LoadPAYMENTDETAILSByID($_REQUEST['paymentid']);
	//print_r($Print['invoiceNo']); exit;
	$i = 1;
    echo'<table class="table table-striped table-bordered table-hover">
			<thead><tr><th>#</th><th>invoiceNo</th><th>invoiceDate</th><th>dueDate</th><th>invoiceAmount</th><th>payabledAmount</th><th>receivedAmount</th><th>balanceAmount</th><th>shortAmount</th></tr></thead><tbody>';
			if(sizeof($Print) > 0){					
				foreach($Print as $row){ 
					echo'<tr class="odd gradeX">
					<td>'.$i++.'</td>
					<td>'.$row['invoiceNo'].'</td>
					<td>'.$row['invoiceDate'].'</td>';// <td><a href="javascript:showPoInvoiceDetails('.$row['bpoId'].',\''.$row['oinvoice_No'].'\',\''.$row['bpono'].'\',\''.$row['invid'].'\',\''.$row['bpoType'].'\')">'.$row['oinvoice_No'].'</a></td>				   
					echo'<td>'.$row['dueDate'].'</td>
					<td>'.$row['invoiceAmount'].'</td>
					<td>'.$row['payabledAmount'].'</td>
					<td>'.$row['receivedAmount'].'</td>
					<td>'.$row['balanceAmount'].'</td>
					<td>'.$row['shortAmount'].'</td>
				  </tr>';
				}
	} echo'</tbody></table>';							 

					

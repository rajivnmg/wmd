<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/StockStatementModel.php");		
	$Rows = StockStatementModel::GetItemExciseNonExciseChallanIssuedQtyDetails($_REQUEST['itemid'],$_REQUEST['codepartno']);
	
    echo'<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr >
							<th style="vertical-align: middle;text-align: center;">#</th>
							<th style="vertical-align: middle;text-align: center;">Chalan No</th>
							<th style="vertical-align: middle;text-align: center;">Chalan Date</th>
							<th style="vertical-align: middle;text-align: center;">Principal</th>
							<th style="vertical-align: middle;text-align: center;">Buyer</th>
							<th style="vertical-align: middle;text-align: center;">QTY</th>
							<th style="vertical-align: middle;text-align: center;">Executive</th>
						</tr>	
					</thead><tbody>';
					foreach ($Rows as $Row) {
						echo '<tr>
								<td>'.$Row['SN'].'</td>
								<td>'.$Row['ChallanNo'].'</td>
								<td>'.$Row['ChallanDate'].'</td>
								<td>'.$Row['Principal_Supplier_Name'].'</td>
								<td>'.$Row['BuyerName'].'</td>
								<td>'.$Row['qty'].'</td>
								<td>'.$Row['ExecutiveId'].'</td>							
						</tr>';
					}
						
	echo' </tbody></table>';		
			 
?>
					

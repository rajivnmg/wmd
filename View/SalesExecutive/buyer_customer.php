<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	if($_REQUEST['type']=='buyer'){
			$row = Report1Model::GetQuotationBuyerName($_REQUEST['id']);	
	}else if($_REQUEST['type']=='cust'){
			//$Print = Report1Model::GetQuotationCustomerName($_REQUEST['id']);	
			return;
			exit;
	}else{
			return;
			exit;
	}
	$i = 1;
	echo '<table class="table table-striped table-bordered table-hover">
            <thead>
				<tr><td>BuyerName</td> <td>Address</td>  <td>Location</td><td>City</td><td>State</td>									   <td>Pincode</td><td>Phone</td> <td>Email</td> <td>FAX</td></tr></thead><tbody>';
									
									 echo' <tr class="odd gradeX">
                                                                             
                                           <td>'.$row['BuyerName'].'</td>
										   <td>'.$row['Bill_Add1'].'</td>
										   <td>'.$row['LocationName'].'</td>
										   <td>'.$row['CityName'].'</td>
                                           <td>'.$row['StateName'].'</td>
										   <td>'.$row['Pincode'].'</td>
                                           <td>'.$row['Phone'].'</td>
										   <td>'.$row['Email'].'</td>
										   <td>'.$row['FAX'].'</td>
										  
                                            
                                        </tr>'; 
									
                                 echo' </tbody>
                                </table>';		
	
					 
?>
					
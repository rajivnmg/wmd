<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	include_once("../../Model/Business_Action_Model/po_model.php");
	$row = DashboardModel::LoadPOStatus($_REQUEST['POID'],$_REQUEST['BPONO']);
	
	echo'<table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;"><tbody>
			 <tr>
				<td>				
					<label><span>';
					if($row['po_status']=='C'){
						echo'<input type="checkbox" name="checkbox4" id="poChangeStatus" checked></input>&nbsp;&nbsp;&nbsp;Close(checked if PO Closed)</span>';
					}else{
						echo'<input type="checkbox" name="checkbox4" id="poChangeStatus" ></input>&nbsp;&nbsp;&nbsp;Close(checked if PO Closed)</span>';
					}					
					echo'</label>		
				</td>
			</tr>
			<tr>
				<td>
					<label>
						<span>Reason:</span>
						<textarea class="FormElement" style="height: 40px;" textarea name="poChangeStatusReason" id="poChangeStatusReason" cols="40" rows="4">'.$row['po_close_reason'].'</textarea>
					</label>				
				</td>				
				<input type="hidden" name="ponumber" id="ponumber" value="'.$_REQUEST['BPONO'].'"/>
				<input type="hidden" name="postatetype" id="postatetype" value="'.$row['po_state'].'"/>				
          </tr>';        
			if(($_SESSION['USER_TYPE'] == "A" || $_SESSION['USER_TYPE'] == "M" || $_SESSION['USER_TYPE'] == "S") && ($row['po_status']=='O') ){
			echo" <tr>
					<td>		
						<button type='button' class='btn btn-primary' id='postat' name='bbb' onClick='changePoStatusSave(".$_REQUEST['POID'].");' style='margin-left: 48%;'>Save</button>
					</td>
				</tr>";	
			}			
	
		echo' </tbody></table>';		

					

<?php 
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");		
	include_once("../../Model/Dashboard/DashboardModel.php");	
	include_once("../../Model/Business_Action_Model/po_model.php");
	$row = DashboardModel::LoadPOState($_REQUEST['POID'],$_REQUEST['BPONO']);
	$total =  Purchaseorder_Model::invoiceGeneratedNew($_REQUEST['POID']);
	echo'<table class="table table-striped table-bordered table-hover"><tbody>
			 <tr>
				<td>
				
					<label><span>';
					if($row['po_state']=='H'){
						echo'<input type="checkbox" name="checkbox4" id="poholdstate" ng-model="purchaseOrder.po_hold_state" ng-change="poHoldReason();" checked></input>ON HOLD (checked if PO Create In Hold State)</span>';
					}else{
						echo'<input type="checkbox" name="checkbox4" id="poholdstate"  ng-model="purchaseOrder.po_hold_state" ng-change="poHoldReason();"></input>ON HOLD (checked if PO Create In Hold State)</span>';
					}					
					echo'</label>		
				</td>
			</tr>
			<tr>
				<td>
					<label>
						<span>PO Hold Reason:</span>
						<textarea class="FormElement" style="height: 40px;" textarea name="po_hold_reason" id="po_hold_reason" ng-model="purchaseOrder.po_hold_reason" cols="40" rows="4">'.$row['po_hold_reason'].'</textarea>
					</label>
				
				</td>
				
				
				<input type="hidden" name="ponumber" id="ponumber" value="'.$_REQUEST['BPONO'].'"/>
				<input type="hidden" name="postatetype" id="postatetype" value="'.$row['po_state'].'"/>
				
				
          </tr>';
          if($total < 1){
			if($_SESSION['USER_TYPE'] == "A" || $_SESSION['USER_TYPE'] == "M" || $_SESSION['USER_TYPE'] == "S"){
			echo" <tr>
					<td>		
						<button type='button' class='btn btn-primary' id='postat' name='bbb' onClick='changePoState(".$_REQUEST['POID'].");' style='margin-left: 48%;'>Save</button>
					</td>
				</tr>";	
			}else if(($_SESSION['USER_TYPE'] != "A" || $_SESSION['USER_TYPE'] != "M" || $_SESSION['USER_TYPE'] != "S")  && ($row['po_state']=='U')){
				echo" <tr>
					<td>		
						<button type='button' class='btn btn-primary' id='postat' name='bbb' onClick='changePoState(".$_REQUEST['POID'].");' style='margin-left: 48%;'>Save</button>
					</td>
				</tr>";	
			}	
			
		}	
		echo' </tbody></table>';		
					 
?>
					

<?php
if ( !isset($_SESSION) ) {session_start(); }
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Business_Action_Model/Bundle_Model.php");
include_once( "../../Model/Masters/BuyerMaster_Model.php");
include_once( "../../Model/Business_Action_Model/ma_model.php");
include_once( "../../Model/Param/param_model.php");
include_once( "../../Model/Masters/Event.php");

//Logger::configure("../../config.xml");
include_once("root.php");
include_once($root_path."log4php/Logger.php");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){ //log file settion for gurgaon due to run all instance from single set of code
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){ //log file settion for rudrapur due to run all instance from single set of code
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){ //log file settion for manesar due to run all instance from single set of code
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){ //log file settion for haridwar due to run all instance from single set of code
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('Bundle_Controller');
$UID=$_SESSION["USER"];
$Type = $_REQUEST['TYP'];
 $logger->info($Type); // function to write in log file
if($Type == null)
    $Type = QueryModel::PAGELOAD;
	switch($Type){
    case QueryModel::INSERT:
        $Data = $_REQUEST['PODATA'];
        $Data = str_replace('\\','', $Data);	
	//	$logger->info($Data); // function to write in log file
		$obj = json_decode($Data);
        $Print = Bundle_Model::InsertPO($obj->{'bn'}, $obj->{'mode_de'}, $obj->{'pon'}, $obj->{'cp'}, $obj->{'pod'},$obj->{'exec'},$obj->{'ipovd'},$obj->{'cd'},$obj->{'cdt'},$obj->{'pot'},$obj->{'addTag'},$obj->{'sadd1'},$obj->{'sadd2'},$obj->{'scountry1'},$obj->{'sstate1'},$obj->{'scity1'},$obj->{'slocation1'},$obj->{'spincode'},$obj->{'sphno'},$obj->{'smobno'},$obj->{'sfax'},$obj->{'semail'},$obj->{'poVal'},$obj->{'pf_chrg'},$obj->{'inci_chrg'},$obj->{'frgt'},$obj->{'frgtp'},$obj->{'frgta'},"D","X","O",$obj->{'ms'},$obj->{'rem'},$UID);
		
		// insert po details in event table to send the email
		$po_ack = Event::addEvent(EVENT_MAIL_TYPE,EVENT_PO_ACKNOWLEDGE,$Data,$Print);
	  
		//$logger->debug($Print); // function to write in log file
		$logger->info('sizeof'.sizeof($obj->{'bundles'})); // function to write in log file
		$j = 0 ;
		while($j < sizeof($obj->{'bundles'}))
        {
           $bundle_id =  Bundle_Details_Model::InsertBundlePo($Print,$obj->bundles[$j]->ibglAcc,$obj->bundles[$j]->ibitem_desc,$obj->bundles[$j]->ibpo_qty,$obj->bundles[$j]->ibpo_unit,$obj->bundles[$j]->ibpo_price,$obj->bundles[$j]->ibpo_discount,$obj->bundles[$j]->ibpo_saleTax,$obj->bundles[$j]->ibpo_totVal,$UID,"NEW");
			
           	$i=0;
				
			    while($i < sizeof($obj->bundles[$j]->items))
					{
						$logger->info($obj->bundles[$j]->items[$i]->cPartNo); // function to write in log file
						Bundle_Details_Model::InsertPODetails($Print,"NULL",$obj->bundles[$j]->items[$i]->po_principalId,$obj->bundles[$j]->items[$i]->po_codePartNo,$obj->bundles[$j]->items[$i]->po_buyeritemcode,$obj->bundles[$j]->items[$i]->unit_id,$obj->bundles[$j]->items[$i]->po_qty,$obj->bundles[$j]->items[$i]->po_price,$obj->bundles[$j]->items[$i]->po_price_category,$obj->bundles[$j]->items[$i]->po_discount,$obj->bundles[$j]->items[$i]->po_ed_applicability,$obj->bundles[$j]->ibpo_saleTax,$obj->bundles[$j]->items[$i]->po_deliverybydate,"YDE",$bundle_id);
						$i++;
					}  
				$j++;	
		}
	
		//management approval
        $automatic_MA="N";
        $aRemarks="";
	    $res =  Managementapproval_Model::checkBuyerNewExist_UsingInv($obj->{'bn'});		
	    $rw=mysql_fetch_array($res, MYSQL_ASSOC);
	    $total=$rw['total'];
 		if($total>0){
			$buyerStatus="EXIST";
		}else{
			$buyerStatus="NEW";
		}
        //echo $buyerStatus;
        $result =  BuyerMaster_Model::GetBuyerDetails($obj->{'bn'});
	    $row=mysql_fetch_array($result, MYSQL_ASSOC);
        $BUYER_STATUS=$row['Buyer_Level'];
        $Credit_Limit=$row['Credit_Limit'];
        if($BUYER_STATUS=="X"){
           $automatic_MA="Y";
           $aRemarks="Buyer is Black Listed"."\n";
        }

        //echo $q_cp;

        if($buyerStatus=="NEW"){
           $result =  ParamModel::GetParamList('AMOUNT','THRESHOLD');
           $row=mysql_fetch_array($result, MYSQL_ASSOC);
           $max_permitted_amt_forNew=$row['PARAM_VALUE1'];

           //echo "max_permitted_amt_forNew --->".$max_permitted_amt_forNew;

  		   $result1 = Managementapproval_Model::unBilledPOVal($obj->{'bn'});
		   $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
		   $unbilled_poval = $row1['unBilledPOVal'];
           //echo " unbilled_poval--->".$unbilled_poval;
			$automatic_MA = "Y";
            $aRemarks = $aRemarks."This in New Buyer Please Approve \n";
           if($unbilled_poval > $max_permitted_amt_forNew){

		   	  $automatic_MA = "Y";
              $aRemarks = $aRemarks."New Customer Order Value can not be greater than ".$max_permitted_amt_forNew."\n";
		   }

         }

        if($aRemarks!=""){
		       $Print1 = Managementapproval_Model::InsertMA($Print,$aRemarks,$UID);
            if($Print1>0){
               $Print1=Managementapproval_Model::UpdatePO_BYMA($Print,true);
            }
            $Print="A";
        }else{
		    Managementapproval_Model::UpdatePO_BYMA($Print,false);
	    }		
		
		echo json_encode($Print);
        return;
        break;

    case QueryModel::UPDATE:
        $Data = $_REQUEST['PODATA'];
        $Data = str_replace('\\','', $Data);
		$logger->debug($Data); // function to write in log file
	    $obj = json_decode($Data);
        $Print = Bundle_Model::UpdatePO($obj->{'_bpoId'},$obj->{'buyerid'}, $obj->{'mode_de'}, $obj->{'pon'}, $obj->{'cp'}, $obj->{'pod'},$obj->{'exec'},$obj->{'ipovd'},$obj->{'cd'},$obj->{'cdt'},$obj->{'pot'},$obj->{'addTag'},$obj->{'sadd1'},$obj->{'sadd2'},$obj->{'scountry1'},$obj->{'sstate1'},$obj->{'scity1'},$obj->{'slocation1'},$obj->{'spincode'},$obj->{'sphno'},$obj->{'smobno'},$obj->{'sfax'},$obj->{'semail'},$obj->{'poVal'},$obj->{'pf_chrg'},$obj->{'inci_chrg'},$obj->{'frgt'},$obj->{'frgtp'},$obj->{'frgta'},"D","X","O",$obj->{'ms'},$obj->{'rem'},$UID);
		$logger->debug($Print); // function to write in log file
        if($Print=="YES"){
		   $Print=$obj->{'_bpoId'};	
		}else if($Print=="NO"){
		   $Print=0;	
		}
		Bundle_Details_Model::DeleteItem($obj->{'_bpoId'});
		Bundle_Details_Model::DeleteBundle($obj->{'_bpoId'});
		$logger->info('sizeof'.sizeof($obj->{'bundles'})); // function to write in log file
		$j = 0 ;
		while($j < sizeof($obj->{'bundles'}))
        {
           $bundle_id =  Bundle_Details_Model::InsertBundlePo($Print,$obj->bundles[$j]->ibglAcc,$obj->bundles[$j]->ibitem_desc,$obj->bundles[$j]->ibpo_qty,$obj->bundles[$j]->ibpo_unit,$obj->bundles[$j]->ibpo_price,$obj->bundles[$j]->ibpo_discount,$obj->bundles[$j]->ibpo_saleTax,$obj->bundles[$j]->ibpo_totVal,$UID,"NEW");
			
           	$i=0;
				
			    while($i < sizeof($obj->bundles[$j]->items))
					{
						$logger->info($obj->bundles[$j]->items[$i]->cPartNo); // function to write in log file
						Bundle_Details_Model::InsertPODetails($Print,"NULL",$obj->bundles[$j]->items[$i]->po_principalId,$obj->bundles[$j]->items[$i]->po_codePartNo,$obj->bundles[$j]->items[$i]->po_buyeritemcode,$obj->bundles[$j]->items[$i]->unit_id,$obj->bundles[$j]->items[$i]->po_qty,$obj->bundles[$j]->items[$i]->po_price,$obj->bundles[$j]->items[$i]->po_price_category,$obj->bundles[$j]->items[$i]->po_discount,$obj->bundles[$j]->items[$i]->po_ed_applicability,$obj->bundles[$j]->ibpo_saleTax,$obj->bundles[$j]->items[$i]->po_deliverybydate,"YDE",$bundle_id);
						$i++;
					}  
				$j++;	
		}   
         // management approval
        $automatic_MA="N";
        $aRemarks="";
	    $res =  Managementapproval_Model::checkBuyerNewExist_UsingInv($obj->{'bn'}); // function to check buyer is new OR already exist for credit limit approval
	    $rw=mysql_fetch_array($res, MYSQL_ASSOC);
	    $total=$rw['total'];
 		if($total>0){
			$buyerStatus="EXIST";
		}else{
			$buyerStatus="NEW";
		}
        //echo $buyerStatus;
        $result =  BuyerMaster_Model::GetBuyerDetails($obj->{'bn'});
	    $row=mysql_fetch_array($result, MYSQL_ASSOC);
        $BUYER_STATUS=$row['Buyer_Level'];
        $Credit_Limit=$row['Credit_Limit'];
        if($BUYER_STATUS=="X"){
           $automatic_MA="Y";
           $aRemarks="Buyer is Black Listed"."\n";
        }

 	    $result1 =  Managementapproval_Model::getQuotCreditPeriod($obj->{'bn'}); // get the buyer Quotation credit period 
        while($row1=mysql_fetch_array($result1, MYSQL_ASSOC)){
			  $q_cp=$row1['q_cp'];
              if($obj->{'cp'}>$q_cp){
                 $automatic_MA="Y";
                 $aRemarks=$aRemarks."Quotation credit period is lower than PO credit period"."\n";
             }
        }
        //echo $q_cp;

        if($buyerStatus=="NEW"){
           $result =  ParamModel::GetParamList('AMOUNT','THRESHOLD');
           $row=mysql_fetch_array($result, MYSQL_ASSOC);
           $max_permitted_amt_forNew=$row['PARAM_VALUE1'];

           //echo "max_permitted_amt_forNew --->".$max_permitted_amt_forNew;

  		   $result1 = Managementapproval_Model::unBilledPOVal($obj->{'bn'}); // Function to get the bill value for PO
		   $row1=mysql_fetch_array($result1, MYSQL_ASSOC);
		   $unbilled_poval = $row1['unBilledPOVal'];
           //echo " unbilled_poval--->".$unbilled_poval;

           if($unbilled_poval > $max_permitted_amt_forNew){

		   	  $automatic_MA = "Y";
              $aRemarks = $aRemarks."New Customer Order Value can not be greater than ".$max_permitted_amt_forNew."\n";
		   }

         }else{
			$result1 = Managementapproval_Model::unBilledPOVal($obj->{'bn'});
			$row1=mysql_fetch_array($result1, MYSQL_ASSOC);
			$unbilled_poval=$row1['unBilledPOVal'];
			$result =  Managementapproval_Model::outstandingAmount($BUYERID); // Gte the outstanding amount of buyer to check the credit limit and send for management approval 
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$outstandingPayment=$row['outstandingPayment'];

            $total_amt=($outstandingPayment+$unbilled_poval);

		    //echo "outstandingPayment --->".$outstandingPayment.", total_amt--->".$total_amt;

		   /*  if($total_amt>$Credit_Limit){
		 	   $automatic_MA="Y";
               $aRemarks=$aRemarks." Sum of Outstanding Payment and total unbilled PO value is greater than threshold Payment".$Credit_Limit." Rs <br>";
		    } */

		 }

	// end
        $k=0;
        while($k < sizeof($obj->{'_items'}))
        {

             if($obj->_items[$k]->po_discount==""){
				$obj->_items[$k]->po_discount="0";
			}
            if($obj->_items[$k]->po_odiscount==""){
				$obj->_items[$k]->po_odiscount="0";
			}
            if($obj->_items[$k]->po_quotNo!="0"){

				if($obj->_items[$k]->po_discount_category=="M"){
		 	       $automatic_MA="Y1";
                   $aRemarks=$aRemarks."Quotation Discount is less than PO discount."."\n";
				}
				if($obj->_items[$k]->po_price_category=="S"){
		 	       $automatic_MA="Y2";
                   $aRemarks=$aRemarks."Quotation Price is greater than PO Price"."\n";

				}
				 $logger->Debug('PO PRICE : '.$obj->_items[$i]->po_price); // function to write in log file
				//Check Item quotation Price for management approval
				$itemQuotPrice = Managementapproval_Model::getItemQuatationPrice($obj->_items[$k]->po_codePartNo,$obj->_items[$k]->po_principalId,$obj->_items[$k]->po_quotNo);
				 $logger->Debug('QUOTATION PRICE : '.$itemQuotPrice); // function to write in log file
				if($obj->_items[$k]->po_price > $itemQuotPrice){
		 	       $automatic_MA="Y3";
                   $aRemarks=$aRemarks."PO Price is greater than Quotation Price\n";

				}
				 $logger->debug($aRemarks); // function to write in log file
			}
			
			// Check Item Landing Price for management approval
			$itemlandingPrice = Managementapproval_Model::getItemLandingPrice($obj->_items[$k]->po_codePartNo,$obj->_items[$k]->po_principalId,$obj->_items[$i]->po_ed_applicability);
            if($itemlandingPrice != 0){
				$itemlandingPrice = (($itemlandingPrice + ($itemlandingPrice * PROFIT_MARGIN))/100);
				if($itemlandingPrice > $obj->_items[$k]->po_price){
					 $automatic_MA="Y";
					 $aRemarks=$aRemarks."Profit Margin is less than 5% "."\n";

				}
			}else{
				 $automatic_MA="Y";
				 $aRemarks=$aRemarks."Landing Price Not Available"."\n";
			}
            $k++;
        }
        if($aRemarks!=""){
		       $Print1 = Managementapproval_Model::InsertMA($Print,$aRemarks,$UID);
            if($Print1>0){
               $Print1=Managementapproval_Model::UpdatePO_BYMA($Print,true);
            }
            $Print="A";
        }else{
		    Managementapproval_Model::UpdatePO_BYMA($Print,false);
	    }
   
       
        echo json_encode($Print);
        return;
        break;
    case "POQUOTATION":
        $buyerId = $_REQUEST['BUYERID'];
        $Print = Bundle_Details_Model::getPOQuotation($buyerId);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "SALESTAX":
        $buyerId = $_REQUEST['BUYERID'];
        $Print = Bundle_Details_Model::getSalesTax($buyerId);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "BILLINGADD":
        $buyerId = $_REQUEST['BUYERID'];
	    $Print = BuyerMaster_Model::LoadBuyerDetails($buyerId);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "POPRINCIPAL":
        $quotNo= $_REQUEST['QUOTNO'];
        //echo $quotNo;
        $Print = Bundle_Details_Model::getPOPrincipalSupplier('QUOTATION',$quotNo,'P'); 
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "POCODEPARTNO":
        $qno= $_REQUEST['QUOTNO'];
        $pid= $_REQUEST['PRINCIPALID'];      
        //$Print = purchaseorder_Details_Model::getCodePartNo($qno,$pid);
        $Print = Bundle_Details_Model::getCodePartNoForAutoComplete($qno,$pid);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "GET_QUOTDISCOUNT":
        $qno= $_REQUEST['QUOTNO'];
        $Print = Bundle_Details_Model::getDiscount($qno);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "GET_PRINCIPAL_DISCOUNT_DETAILS":
        $BUYERID=$_REQUEST['BUYERID'];
        $PRINCIPALID=$_REQUEST['PRINCIPALID'];
        $Result = Bundle_Details_Model::getPrincipalBuyerDiscount($BUYERID,$PRINCIPALID);
		$logger->debug(json_encode($Result)); // function to write in log file
        echo json_encode($Result);
        return;
        break;
    case QueryModel::SELECT:
        $PO_num= $_REQUEST['PO_NUMBER'];
        $Print = Bundle_Model::LoadPurchase($PO_num);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
	case "PODETAILINV":
        $Print = Bundle_Model::LoadPurchaseOrderDescByBpoNo($_REQUEST['PO_NUMBER']);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;   
    case "MA_FILL":     
        $Print = Bundle_Model::LoadPurchaseByID($_REQUEST['PO_NUMBER'],$_REQUEST['po_ed_applicability']);	
		//print_r($Print); exit;
		$logger->debug(json_encode($Print)); // function to write in log file		
        echo json_encode($Print); 
        return;
        break;
    case "VPO":
        $buyerId = $_REQUEST['BUYERID'];
        $pon= $_REQUEST['PONO'];
        $poid= $_REQUEST['POID'];
        $Print = Bundle_Model::validatePO($buyerId,$pon,$poid);
		$logger->debug(json_encode($Print)); // function to write in log file
        echo json_encode($Print);
        return;
        break;
    case "LOADITEM":
        $buyerId = $_REQUEST['BUYERID'];
		$quotId = 0;
        $principalId = $_REQUEST['PID'];
        $itemId = $_REQUEST['ITEMID'];
		 $Print = Bundle_Details_Model::GetItemDescription($buyerId,$quotId,$principalId,$itemId);
		 $logger->debug(json_encode($Print)); // function to write in log file
        //$Print = purchaseorder_Details_Model::GetItemDesc($buyerId,$quotId,$principalId,$itemId);
        echo json_encode($Print);
        return;
        break;
    case "LOADPOITEMDETAIL" :
        $bpod_Id = $_REQUEST['BPODID'];
        $bpoType = $_REQUEST['BPOTYPE'];  
        $Print = Bundle_Details_Model::GetPOItemDetail($bpod_Id,$bpoType);
        echo json_encode($Print);
        return;
        break;  
    case "LOADPODETAILS":
        $Print = Bundle_Details_Model::GetPoDetails($_REQUEST['POID'],$_REQUEST['PRINID'],$_REQUEST['ITEMID'],$_REQUEST['TAG'],$_REQUEST['BPOTYPE']);
        echo json_encode($Print);
        return;
        break;
    case QueryModel::PAGELOAD:
        Pageload();
        return;
        break;
    default:
        break;
}
function Pageload(){
    session_start();
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $rp = isset($_POST['rp']) ? $_POST['rp'] : 10;
    $sortname = isset($_POST['sortname']) ? $_POST['sortname'] : 'UNITNAME';
    $sortorder = isset($_POST['sortorder']) ? $_POST['sortorder'] : 'desc';
    $query = isset($_POST['query']) ? $_POST['query'] : false;
    $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : false;
    $rows = NULL;//Purchaseorder_Model::LoadPO(0);
    header("Content-type: application/json");
    $jsonData = array('page'=>$page,'total'=>0,'rows'=>array());
    $i = 0;
    foreach($rows AS $row){
        //If cell's elements have named keys, they must match column names
        //Only cell's with named keys and matching columns are order independent.
        $entry = array('id' => $i,
            'cell'=>array(
                'quotId'       => $row->_quotation_id,
                'quotNo'     => $row->_quotation_no,
                'quotdate'     => $row->_quotation_date,
                'principalname'       => $row->_principal_name,
                'customername'     => $row->_coustomer_name,
                'contactpersone'     => $row->_contact_persone
            )
        );
        $i++;
        $jsonData['rows'][] = $entry;
    }
    $jsonData['total'] = count($rows);
    echo json_encode($jsonData);
}


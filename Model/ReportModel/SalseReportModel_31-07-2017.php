<?php
class SalseReportModel
{
    public $srn;
    public $txnid;
    public $date;
    public $principal;
    public $buyer;
    public $totalbillcost;
    public $type;
    public $Details = array();
    
    public function __construct($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details)
    {
        $this->srn=$srn;
        $this->txnid=$txnid;
        $this->date=$date;
        $this->principal=$principal;
        $this->buyer=$buyer;
        $this->totalbillcost=$totalbillcost;
        $this->type=$type;
        $this->Details=$Details;
    }
    public static function SearchSalseReport($ReportType,$ColoumName,$datefrom,$dateto,$principalId,$Itemid,$Buyerid,$SupplierId){
        $Query = "";
        $objArray = array();
		$i = 0;
        switch($ReportType)
        {
            case "1":
                switch($ColoumName)
                {
                    case "Date":
                        $Query = "select iie.entryId, iie.total_bill_val, iie.principal_inv_date,pm.Principal_Supplier_Name from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.principalId where iie.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['entryId'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['total_bill_val'];
                            $type = "Incoming Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Principal":
                        $Query = "select iie.entryId, iie.total_bill_val, iie.principal_inv_date,pm.Principal_Supplier_Name from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.principalId where iie.principalId = $principalId AND iie.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['entryId'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['total_bill_val'];
                            $type = "Incoming Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Codepart":
                        $Query = "select iie.entryId, iie.total_bill_val, iie.principal_inv_date,pm.Principal_Supplier_Name from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.principalId inner join incominginvoice_excise_detail as ied on ied.entryId = iie.entryId  where ied.itemID_code_partNo = $Itemid AND iie.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['entryId'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['total_bill_val'];
                            $type = "Incoming Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Supplier":
                        $Query = "select iie.entryId, iie.total_bill_val, iie.supplier_inv_date,pm.Principal_Supplier_Name from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.supplierId where iie.supplierId = $SupplierId AND iie.supplier_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['entryId'];
                            $date = $Row['supplier_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['total_bill_val'];
                            $type = "Incoming Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    default:
                        return;
                        break;
                }
                break;
            case "2":
                switch($ColoumName)
                {
                    case "Date":
                        $Query = "select iiwe.incominginvoice_we, iiwe.principal_inv_date, iiwe.tot_bill_val, pm.Principal_Supplier_Name from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id where iiwe.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['incominginvoice_we'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['tot_bill_val'];
                            $type = "Incoming Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Principal":
                        $Query = "select iiwe.incominginvoice_we, iiwe.principal_inv_date, iiwe.tot_bill_val, pm.Principal_Supplier_Name from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id where iiwe.principalID = $principalId AND iiwe.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['incominginvoice_we'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['tot_bill_val'];
                            $type = "Incoming Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Codepart":
                        $Query = "select iiwe.incominginvoice_we, iiwe.principal_inv_date, iiwe.tot_bill_val, pm.Principal_Supplier_Name from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id inner join incominginvoice_without_excise_detail as iiewd on iiwe.incominginvoice_we = iiewd.incominginvoice_we where iiewd.itemID_code_partNo = $Itemid AND iiwe.principal_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['incominginvoice_we'];
                            $date = $Row['principal_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['tot_bill_val'];
                            $type = "Incoming Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Supplier":
                        $Query = "select iiwe.incominginvoice_we, iiwe.supplr_inv_date, iiwe.tot_bill_val, pm.Principal_Supplier_Name from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.supplrId = pm.Principal_Supplier_Id inner join incominginvoice_without_excise_detail as iiewd on iiwe.incominginvoice_we = iiewd.incominginvoice_we where iiwe.supplrId = $SupplierId AND iiwe.supplr_inv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['incominginvoice_we'];
                            $date = $Row['supplr_inv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = "Multiweld Pvt. Ltd.";
                            $totalbillcost = $Row['tot_bill_val'];
                            $type = "Incoming Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    default:
                        return;
                        break;
                }
                break;
            case "3":
                switch($ColoumName)
                {
                    case "Date":
                        $Query = "select oie.oinvoice_exciseID, oie.oinv_date, oie.bill_value , pm.Principal_Supplier_Name , bm.BuyerName  from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId where oie.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        //echo($Query);
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_exciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Principal":
                        $Query = "select oie.oinvoice_exciseID, oie.oinv_date, oie.bill_value , pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId where oie.principalID = $principalId AND oie.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_exciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Codepart":
                        $Query = "select oie.oinvoice_exciseID, oie.oinv_date, oie.bill_value , pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId inner join outgoinginvoice_excise_detail as oied on oied.oinvoice_exciseID = oie.oinvoice_exciseID where oied.oinv_codePartNo = $Itemid AND oie.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_exciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Excise";                                                              
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Buyer":
                        $Query = "select oie.oinvoice_exciseID, oie.oinv_date, oie.bill_value , pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId where oie.BuyerID = $Buyerid AND oie.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_exciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Excise";                                                              
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    default:
                        return;
                        break;
                }
                break;
            case "4":  
                switch($ColoumName)
                {
                    case "Date":
                        $Query = "select oine.oinvoice_nexciseID, oine.oinv_date, oine.bill_value, pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId where oine.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        //echo($Query);
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_nexciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Principal":
                        $Query = "select oine.oinvoice_nexciseID, oine.oinv_date, oine.bill_value , pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId where oine.principalID = $principalId AND oine.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_nexciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Non-Excise";                                                               
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Codepart":
                        $Query = "select oine.oinvoice_nexciseID, oine.oinv_date, oine.bill_value , pm.Principal_Supplier_Name ,bm.BuyerName from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId inner join outgoinginvoice_nonexcise_detail as oined on oine.oinvoice_nexciseID = oined.oinvoice_nexciseID where oined.oinv_codePartNo = $Itemid AND oine.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_nexciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Non-Excise";                                                              
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    case "Date_With_Buyer":
                        $Query = "select oine.oinvoice_nexciseID, oine.oinv_date, oine.bill_value , pm.Principal_Supplier_Name , bm.BuyerName from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId where oine.BuyerID = $Buyerid AND oine.oinv_date BETWEEN '$datefrom' AND '$dateto'"; 
                        $result = DBConnection::SelectQuery($Query);
                        while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                            $srn = $i + 1;
                            $txnid = $Row['oinvoice_nexciseID'];
                            $date = $Row['oinv_date'];
                            $principal = $Row['Principal_Supplier_Name'];
                            $buyer = $Row['BuyerName'];
                            $totalbillcost = $Row['bill_value'];
                            $type = "Outgoing Non-Excise";                                                              
                            $Details = SalseReportDetailsModel::SearchSalseReportDetails($ReportType,$ColoumName,$txnid);
                            $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                            $objArray[$i] = $newObj;
                            $i++;
		                }
                        break;
                    default:
                        return;
                        break;
                }
                break;
            case "5":
                $Query = "";
                switch($ColoumName)
                {
                    case "Date":
                    $Query = "select iie.entryId as id , iie.total_bill_val as cost, iie.principal_inv_date as invdate , pm.Principal_Supplier_Name as PrincipalName,'Incoming' as type, 'Multiweld Pvt. Ltd'  as buyername from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.principalId where  iie.principal_inv_date BETWEEN '$datefrom' AND '$dateto' UNION select oie.oinvoice_exciseID  as id ,oie.bill_value  as cost, oie.oinv_date  as invdate,  pm.Principal_Supplier_Name as pm ,'Outgoing' as type, bm.BuyerName  as buyername from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId where oie.oinv_date BETWEEN '$datefrom' AND '$dateto' order by invdate";
                        break;
                    case "Date_With_Principal":
                    $Query = "select iie.entryId as id , iie.total_bill_val as cost, iie.principal_inv_date as invdate , pm.Principal_Supplier_Name as PrincipalName,'Incoming' as type, 'Multiweld Pvt. Ltd'  as buyername from incominginvoice_excise as iie inner join principal_supplier_master as pm ON pm.Principal_Supplier_Id = iie.principalId where  iie.principalId = $principalId AND iie.principal_inv_date BETWEEN '$datefrom' AND '$dateto' UNION select oie.oinvoice_exciseID  as id ,oie.bill_value  as cost, oie.oinv_date  as invdate,  pm.Principal_Supplier_Name as pm ,'Outgoing' as type, bm.BuyerName  as buyername from outgoinginvoice_excise as oie inner join principal_supplier_master as pm on oie.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oie.BuyerID = bm.BuyerId where  oie.principalID = $principalId AND oie.oinv_date BETWEEN '$datefrom' AND '$dateto' order by invdate";
                        break;
                    default:
                        break;
                }  
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $txnid = $Row['id'];
                    $date = $Row['invdate'];
                    $principal = $Row['PrincipalName'];
                    $buyer = $Row['buyername'];
                    $totalbillcost = $Row['cost'];
                    $type = $Row['type'];  
                    $Details = null;
                    $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                    $objArray[$i] = $newObj;
                    $i++;
		        }
                break;
            case "6":
                $Query = "";
                switch($ColoumName)
                {
                    case "Date":
                    $Query = "select iiwe.incominginvoice_we as id , iiwe.principal_inv_date as invdate, iiwe.tot_bill_val as cost, pm.Principal_Supplier_Name as PrincipalName ,'Incoming' as type, 'Multiweld Pvt. Ltd'  as buyername from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id where iiwe.principal_inv_date BETWEEN '$datefrom' AND '$dateto' UNION select oine.oinvoice_nexciseID as id , oine.oinv_date as invdate, oine.bill_value as cost, pm.Principal_Supplier_Name as PrincipalName,'Outgoing' as type, bm.BuyerName as buyername from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId where oine.oinv_date BETWEEN '$datefrom' AND '$dateto' order by invdate"; 
                        break;
                    case "Date_With_Principal":
                    $Query = "select iiwe.incominginvoice_we as id , iiwe.principal_inv_date as invdate, iiwe.tot_bill_val as cost, pm.Principal_Supplier_Name as PrincipalName ,'Incoming' as type, 'Multiweld Pvt. Ltd'  as buyername from incominginvoice_without_excise as iiwe inner join principal_supplier_master as pm on iiwe.principalID = pm.Principal_Supplier_Id where iiwe.principalID = $principalId AND iiwe.principal_inv_date BETWEEN '$datefrom' AND '$dateto' UNION select oine.oinvoice_nexciseID as id , oine.oinv_date as invdate, oine.bill_value as cost, pm.Principal_Supplier_Name as PrincipalName,'Outgoing' as type, bm.BuyerName as buyername from outgoinginvoice_nonexcise as oine inner join principal_supplier_master as pm on oine.principalID = pm.Principal_Supplier_Id inner join buyer_master as bm on oine.BuyerID = bm.BuyerId where oine.principalID = $principalId AND oine.oinv_date BETWEEN '$datefrom' AND '$dateto' order by invdate"; 
                        break;
                    default:
                        break;
                }
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $txnid = $Row['id'];
                    $date = $Row['invdate'];
                    $principal = $Row['PrincipalName'];
                    $buyer = $Row['buyername'];
                    $totalbillcost = $Row['cost'];
                    $type = $Row['type'];  
                    $Details = null;
                    $newObj = new SalseReportModel($srn,$txnid,$date,$principal,$buyer,$totalbillcost,$type,$Details);
                    $objArray[$i] = $newObj;
                    $i++;
		        }
                break;
            default:
                return;
                break;
        }
		return $objArray;
	}
	
	// function ceated by Codefire for Daily SaLES Report correction. 18-12-2015
	public static function getDailySalesReport($todate,$fromdate,$tag,$value,$pid,$bid){	
		
		$opt ='';
		//$opt1 ='';
		if(!empty($pid)){
			$opt =$opt.'AND oe.principalID =' .$pid;
			//$opt1 =$opt1.'AND one.principalID =' .$pid;
		}
		
		if(!empty($bid)){
			
			$opt =$opt.' AND oe.BuyerID =' .$bid;
			//$opt1 =$opt1.' AND one.BuyerID =' .$bid;
		}
		
		
		/* $Query1="SELECT oe.oinvoice_No,oe.oinvoice_exciseID as id,oe.oinv_date,oe.bill_value,psm.Principal_Supplier_Name,bm.BuyerName,oe.saleTax as tax_amt,'E' as Type
		FROM outgoinginvoice_excise AS oe
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = oe.principalId
		INNER JOIN buyer_master as bm on oe.BuyerID = bm.BuyerId
		WHERE oe.oinv_date BETWEEN '$todate' AND '$fromdate' $opt";
		$Query2="SELECT one.oinvoice_No,one.oinvoice_nexciseID as id,one.oinv_date,one.bill_value,psm.Principal_Supplier_Name,bm.BuyerName,one.po_saleTax as tax_amt,'N' as Type
		FROM outgoinginvoice_nonexcise AS one 
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = one.principalId
		INNER JOIN buyer_master as bm on one.BuyerID = bm.BuyerId
		WHERE one.oinv_date BETWEEN '$todate' AND '$fromdate' $opt1"; */
				
		
		
		$Query = "SELECT oe.oinvoice_No, oe.oinvoice_exciseID as id, oe.oinv_date, oe.bill_value, psm.Principal_Supplier_Name, bm.BuyerName, (SELECT SUM(taxable_amt) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS TAXABLE_AMOUNT, (SELECT SUM(cgst_amt) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS CGST_AMOUNT, (SELECT SUM(sgst_amt) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS SGST_AMOUNT, (SELECT SUM(igst_amt) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS IGST_AMOUNT, (SELECT SUM(total) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS TOTAL_AMOUNT  , (SELECT (SUM(taxable_amt) * (oe.pf_chrg/100)) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS P_F_AMOUNT, (SELECT (SUM(taxable_amt) * (oe.incidental_chrg/100)) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS INCIDENTAL_AMOUNT, (SELECT (SUM(taxable_amt) * (oe.insurance_charge/100)) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS INSURANCE_AMOUNT, (SELECT (SUM(taxable_amt) * (oe.other_charge/100)) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID) AS OTHER_AMOUNT, IF(((oe.freight_percent IS NULL) OR (oe.freight_percent = '') OR (oe.freight_percent = 0)),oe.freight_amount,(SELECT (SUM(taxable_amt) * (oe.freight_percent/100)) FROM outgoinginvoice_excise_detail oied WHERE oied.oinvoice_exciseID = oe.oinvoice_exciseID)) AS FREIGHT_AMOUNT FROM outgoinginvoice_excise AS oe INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = oe.principalId INNER JOIN buyer_master as bm on oe.BuyerID = bm.BuyerId WHERE oe.oinv_date BETWEEN '$todate' AND '$fromdate' $opt ORDER BY oe.oinvoice_No, oe.oinv_date ASC";
		
		//echo $Query ; exit(0);
		
		//$Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ORDER BY s.oinvoice_No,s.oinv_date ASC";
	
		$result = DBConnection::SelectQuery($Query); 	
		$data = array();
		$inv_details= array();
		$counter = 0;
		$i=0;
       $total_val=0;
        if(mysql_num_rows($result) > 0){		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
				 
				 /* $taxable_amt = $row['bill_value'] - $row['tax_amt'] ;
				 
				if($row['Type'] == "E"){
																
					$data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row['oinvoice_No'],'oinv_date'=>$row['oinv_date'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'BuyerName'=>$row['BuyerName'],'taxable_amt'=>$taxable_amt,'tax_amt'=>$row['tax_amt'],'bill_value'=>$row['bill_value']);					
					$total_val = $total_val + $row['bill_value'];
					
				}else{					
					//$inv_details = Outgoing_Invoice_NonExcise_Model::LoadOutgoingInvoiceNonExcise($row['id']);
					
					$data[$counter] = array('SN'=>++$i,'oinvoice_No'=>$row['oinvoice_No'],'oinv_date'=>$row['oinv_date'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'BuyerName'=>$row['BuyerName'],'taxable_amt'=>$taxable_amt,'tax_amt'=>$row['tax_amt'],'bill_value'=>$row['bill_value']);					
					$total_val = $total_val + $row['bill_value'];
				} */				
				$data[$counter] = array(
										'SN'=>++$i,
										'oinvoice_No'=>$row['oinvoice_No'],
										'oinv_date'=>$row['oinv_date'],
										'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],
										'BuyerName'=>$row['BuyerName'],
										'taxable_amt'=>round($row['TAXABLE_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'cgst_amt'=>round($row['CGST_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'sgst_amt'=>round($row['SGST_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'igst_amt'=>round($row['IGST_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'p_f_amt'=>round($row['P_F_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'inc_amt'=>round($row['INCIDENTAL_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'ins_amt'=>round($row['INSURANCE_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'other_amt'=>round($row['OTHER_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'freight_amount'=>round($row['FREIGHT_AMOUNT'], 2, PHP_ROUND_HALF_DOWN),
										'bill_value'=>round($row['bill_value'], 2, PHP_ROUND_HALF_DOWN)
										);					
					$total_val = $total_val + $row['bill_value'];		 
				
				$counter++; 
			}			
			//$data[$counter] = array('SN'=>'','oinvoice_No'=>'','oinv_date'=>'','Principal_Supplier_Name'=>'','BuyerName'=>'','taxable_amt'=>'','tax_amt'=>'Total : ','bill_value'=>$total_val);
			$data[$counter] = array(
								'SN'=>'',
								'oinvoice_No'=>'',
								'oinv_date'=>'',
								'Principal_Supplier_Name'=>'',
								'BuyerName'=>'',
								'taxable_amt'=>'',
								'cgst_amt'=>'',
								'sgst_amt'=>'',
								'igst_amt'=>'',
								'p_f_amt'=>'',
								'inc_amt'=>'',
								'ins_amt'=>'',
								'other_amt'=>'Total : ',
								'freight_amount'=>'',
								'bill_value'=>$total_val
								);
		}   
		return $data;
    }
	
	// function ceated by Codefire for Purchase Report. 21-12-2015
	public static function GetPurchaseReport($todate,$fromdate,$tag,$value){		
		
		$opt1 ='';
		$opt2 ='';
		switch ($tag)
        {
            case "INVOICENO":
                $opt1 = $opt1."AND ie.principal_inv_no = '$value' ORDER BY ie.principal_inv_date ASC";
				$opt2 = $opt2."AND iwe.incoming_inv_no_p = '$value' ORDER BY iwe.principal_inv_date ASC";
                break;
            case "PRINCIPAL":
				 $opt1 = $opt1."AND ie.principalID = '$value' ORDER BY ie.principal_inv_date ASC";
				$opt2 = $opt2."AND iwe.principalID = '$value' ORDER BY iwe.principal_inv_date ASC";
			     break;
            case "codepart":
                $opt1 = $opt1." AND im.Item_Code_Partno LIKE '$value%'"." ORDER BY ie.principal_inv_date ASC";
				$opt2 = $opt2." AND im.Item_Code_Partno LIKE '$value%'"." ORDER BY iwe.principal_inv_date ASC";
                break;
			case "INDUSTRY":
                $opt1 = $opt1." AND ie.msid = $value"." ORDER BY ie.principal_inv_date ASC";
				$opt2 = $opt2."AND iwe.msid = $value"." ORDER BY iwe.principal_inv_date ASC";
                break;
				
            default :
                break;
        } 
		$Query1="(SELECT ie.principal_inv_no as invoice,ie.principal_inv_date as invoiceDate,ie.total_bill_val as totalbill,ied.p_qty as qty,ied.basic_purchase_price as unitrate,psm.Principal_Supplier_Name,im.Item_Code_Partno,im.Item_Desc,ie.msid FROM incominginvoice_excise AS ie 
		INNER JOIN incominginvoice_excise_detail AS ied ON ie.entryId = ied.entryId
		INNER JOIN item_master AS im ON ied.itemID_code_partNo = im.ItemId
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = ie.principalId
		WHERE ie.principal_inv_date BETWEEN '$todate' AND '$fromdate' $opt1 ) ";		
		
		$Query2="(SELECT iwe.incoming_inv_no_p as invoice,iwe.principal_inv_date as invoiceDate,iwe.tot_bill_val as totalbill,iwed.qty as qty,iwed.rate as unitrate,psm.Principal_Supplier_Name,im.Item_Code_Partno,im.Item_Desc,iwe.msid
		FROM incominginvoice_without_excise AS iwe
		INNER JOIN incominginvoice_without_excise_detail AS iwed ON iwed.incominginvoice_we = iwe.incominginvoice_we
		INNER JOIN item_master AS im ON iwed.itemID_code_partNo = im.ItemId
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = iwe.principalID
		WHERE iwe.principal_inv_date BETWEEN '$todate' AND '$fromdate' $opt2) ";	
		$Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ORDER BY s.invoiceDate ASC";
		
		//echo $Query; exit;		
		$result = DBConnection::SelectQuery($Query); 	
		$data = array();
		$counter = 0;
		$i=0;
        $total_val=0;
		$invoice = array();
        if(mysql_num_rows($result) > 0){		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){		
							
			if (in_array($row['invoice'], $invoice)){
				$data[$counter] = array('SN'=>++$i,'invoice_No'=>$row['invoice'],'inv_date'=>$row['invoiceDate'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'Item_Code_Partno'=>$row['Item_Code_Partno'],'Item_Desc'=>$row['Item_Desc'],'qty'=>$row['qty'],'unitrate'=>$row['unitrate'],'basic_value'=>($row['qty']*$row['unitrate']),'bill_value'=>'---');	
			  }else{
				$data[$counter] = array('SN'=>++$i,'invoice_No'=>$row['invoice'],'inv_date'=>$row['invoiceDate'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'Item_Code_Partno'=>$row['Item_Code_Partno'],'Item_Desc'=>$row['Item_Desc'],'qty'=>$row['qty'],'unitrate'=>$row['unitrate'],'basic_value'=>($row['qty']*$row['unitrate']),'bill_value'=>$row['totalbill']);	
				$total_val = $total_val + $row['totalbill'];
				$invoice[] = $row['invoice'];
			  }
				
				$counter++; 
			}
			$data[$counter] = array('SN'=>'','invoice_No'=>'','inv_date'=>'','Principal_Supplier_Name'=>'','Item_Code_Partno'=>'','Item_Desc'=>'','qty'=>'','unitrate'=>'','basic_value'=>'Total : ','bill_value'=>$total_val);
		}   
		return $data; 	
    }
	
	
	// function ceated by Codefire for Purchase Report. 13-1-2016
	public static function GetPurchaseReportNew($todate,$fromdate,$marketseg,$invoicenumber,$pid,$itemid){		
	
		$opt1 ='';
		$opt2 ='';
		//var_dump($pid);
		if($marketseg != 0 || $marketseg != ''){
			$opt1 = $opt1." AND ie.msid = $marketseg";
			$opt2 = $opt2." AND iwe.msid = $marketseg";
			
		}
		if($invoicenumber != 0 || $invoicenumber != ''){
			 $opt1 = $opt1." AND ie.principal_inv_no = '$invoicenumber' ";
			 $opt2 = $opt2." AND iwe.incoming_inv_no_p = '$invoicenumber'";
		}
		
		if(!empty($pid)){
			 $opt1 = $opt1." AND ie.principalID = ".$pid;
			 $opt2 = $opt2." AND iwe.principalID = ".$pid;
			
		}      
		if($itemid != 0 || $itemid != ''){
			$opt1 = $opt1." AND im.Item_Code_Partno LIKE '$itemid%'";
			$opt2 = $opt2." AND im.Item_Code_Partno LIKE '$itemid%'";
			
		}     
		$Query1="(SELECT ie.principal_inv_no as invoice,ie.principal_inv_date as invoiceDate,ie.total_bill_val as totalbill,ied.p_qty as qty,ied.basic_purchase_price as unitrate,psm.Principal_Supplier_Name,im.Item_Code_Partno,im.Item_Desc,ie.msid FROM incominginvoice_excise AS ie 
		INNER JOIN incominginvoice_excise_detail AS ied ON ie.entryId = ied.entryId
		INNER JOIN item_master AS im ON ied.itemID_code_partNo = im.ItemId
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = ie.principalId
		WHERE ie.principal_inv_date BETWEEN '$todate' AND '$fromdate' $opt1 ) ";		
		
		$Query2="(SELECT iwe.incoming_inv_no_p as invoice,iwe.principal_inv_date as invoiceDate,iwe.tot_bill_val as totalbill,iwed.qty as qty,iwed.rate as unitrate,psm.Principal_Supplier_Name,im.Item_Code_Partno,im.Item_Desc,iwe.msid
		FROM incominginvoice_without_excise AS iwe
		INNER JOIN incominginvoice_without_excise_detail AS iwed ON iwed.incominginvoice_we = iwe.incominginvoice_we
		INNER JOIN item_master AS im ON iwed.itemID_code_partNo = im.ItemId
		INNER JOIN principal_supplier_master AS psm ON psm.Principal_Supplier_Id = iwe.principalID
		WHERE iwe.principal_inv_date BETWEEN '$todate' AND '$fromdate' $opt2) ";	
		$Query="SELECT * FROM  (".$Query1."  UNION ALL ".$Query2." ) as s ORDER BY s.invoiceDate,s.invoice ASC";
		
		//echo $Query; exit;	
		$result = DBConnection::SelectQuery($Query); 	
		$data = array();
		$counter = 0;
		$i=0;
        $total_val=0;
		$invoice = array();
        if(mysql_num_rows($result) > 0){		
			 while($row = mysql_fetch_array($result,MYSQL_ASSOC)){		
							
			if (in_array($row['invoice'], $invoice)){
				$data[$counter] = array('SN'=>++$i,'invoice_No'=>$row['invoice'],'inv_date'=>$row['invoiceDate'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'Item_Code_Partno'=>$row['Item_Code_Partno'],'Item_Desc'=>$row['Item_Desc'],'qty'=>$row['qty'],'unitrate'=>$row['unitrate'],'basic_value'=>($row['qty']*$row['unitrate']),'bill_value'=>'---');	
			  }else{
				$data[$counter] = array('SN'=>++$i,'invoice_No'=>$row['invoice'],'inv_date'=>$row['invoiceDate'],'Principal_Supplier_Name'=>$row['Principal_Supplier_Name'],'Item_Code_Partno'=>$row['Item_Code_Partno'],'Item_Desc'=>$row['Item_Desc'],'qty'=>$row['qty'],'unitrate'=>$row['unitrate'],'basic_value'=>($row['qty']*$row['unitrate']),'bill_value'=>$row['totalbill']);	
				$total_val = $total_val + $row['totalbill'];
				$invoice[] = $row['invoice'];
			  }
				
				$counter++; 
			}
			$data[$counter] = array('SN'=>'','invoice_No'=>'','inv_date'=>'','Principal_Supplier_Name'=>'','Item_Code_Partno'=>'','Item_Desc'=>'','qty'=>'','unitrate'=>'','basic_value'=>'Total : ','bill_value'=>$total_val);
		}   
		return $data; 	
    }
	
	
	
	
}
class SalseReportDetailsModel
{
    public $srn;
    public $codepart;
    public $description;
    public $quantity;
    public $baseprice;
    public $type;
    public function __construct($srn,$codepart,$description,$quantity,$baseprice,$type)
    {
        $this->srn=$srn;
        $this->codepart=$codepart;
        $this->description=$description;
        $this->quantity=$quantity;
        $this->baseprice=$baseprice;
        $this->type=$type;
    }
    public static function SearchSalseReportDetails($ReportType,$ColoumName,$RecordId){
        $Query = "";
        $objArray = array();
		$i = 0;
        switch($ReportType)
        {
            case "1":
                $Query = "select im.Item_Code_Partno, im.Item_Desc, ied.p_qty, ied.basic_purchase_price from incominginvoice_excise_detail as ied inner join item_master as im ON im.ItemId = ied.itemID_code_partNo where ied.entryId = $RecordId"; 
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $codepart = $Row['Item_Code_Partno'];
                    $description = $Row['Item_Desc'];
                    $quantity = $Row['p_qty'];
                    $baseprice = $Row['basic_purchase_price'];
                    $type = "Incoming Excise";  
                    $newObj = new SalseReportDetailsModel($srn,$codepart,$description,$quantity,$baseprice,$type);
                    $objArray[$i] = $newObj;
                    $i++;
		        }
                break;
            case "2":
                $Query = "select iwed.qty, iwed.rate, im.Item_Code_Partno, im.Item_Desc from incominginvoice_without_excise_detail as iwed inner join item_master as im on iwed.itemID_code_partNo = im.ItemId where iwed.incominginvoice_we = $RecordId"; 
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $codepart = $Row['Item_Code_Partno'];
                    $description = $Row['Item_Desc'];
                    $quantity = $Row['qty'];
                    $baseprice = $Row['rate'];
                    $type = "Incoming Non-Excise";  
                    $newObj = new SalseReportDetailsModel($srn,$codepart,$description,$quantity,$baseprice,$type);
                    $objArray[$i] = $newObj;
                    $i++;
		        }
                break;
            case "3":
                $Query = "select oied.issued_qty, oied.oinv_price, im.Item_Code_Partno, im.Item_Desc from outgoinginvoice_excise_detail as oied inner join item_master as im on oied.oinv_codePartNo = im.ItemId where oied.oinvoice_exciseID = $RecordId"; 
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $codepart = $Row['Item_Code_Partno'];
                    $description = $Row['Item_Desc'];
                    $quantity = $Row['issued_qty'];
                    $baseprice = $Row['oinv_price'];
                    $type = "Outgoing Excise";  
                    $newObj = new SalseReportDetailsModel($srn,$codepart,$description,$quantity,$baseprice,$type);
                    $objArray[$i] = $newObj;
                    $i++;
		        } 
                break;
            case "4": 
                $Query = "select oind.issued_qty, oind.oinv_price, im.Item_Code_Partno, im.Item_Desc from outgoinginvoice_nonexcise_detail as oind inner join item_master as im on oind.oinv_codePartNo = im.ItemId where oind.oinvoice_nexciseID = $RecordId"; 
                $result = DBConnection::SelectQuery($Query);
                while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                    $srn = $i + 1;
                    $codepart = $Row['Item_Code_Partno'];
                    $description = $Row['Item_Desc'];
                    $quantity = $Row['issued_qty'];
                    $baseprice = $Row['oinv_price'];
                    $type = "Outgoing Non-Excise";  
                    $newObj = new SalseReportDetailsModel($srn,$codepart,$description,$quantity,$baseprice,$type);
                    $objArray[$i] = $newObj;
                    $i++;
		        } 
                break;
            case "5":
                $Type = "BalanceSheetExcise";    
                $Query = ""; 
                break;
            default:
                return;
                break;
        }
		return $objArray;
	}
}

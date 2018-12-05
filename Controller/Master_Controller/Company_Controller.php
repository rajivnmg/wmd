<?php
include_once("../../Model/DBModel/DbModel.php");
include_once("../../Model/DBModel/Enum_Model.php");
include_once( "../../Model/Param/param_model.php");
//Logger::configure("../../config.xml");
if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='GURGAON'){
		Logger::configure($root_path."config.gur.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='RUDRAPUR'){
		Logger::configure($root_path."config.rudr.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='MANESAR'){
		Logger::configure($root_path."config.mane.xml");
	}else if(isset($_SESSION['SITENAME']) && $_SESSION['SITENAME']=='HARIDWAR'){
		Logger::configure($root_path."config.hari.xml");
	}else{
		Logger::configure($root_path."config.xml");
	}
$logger = Logger::getLogger('Company_Controller');

if(isset($_REQUEST['TYP']) && $_REQUEST['TYP'] == "UPDATE"){
	
	$company_nm = $_REQUEST['company_nm'];
	$company_add = $_REQUEST['company_add'];
	$company_phone = $_REQUEST['company_phone'];
	$company_email = $_REQUEST['company_email'];
	$company_website = $_REQUEST['company_website'];
	$company_CERegnNo = $_REQUEST['company_CERegnNo'];
	$company_RangeMulti = $_REQUEST['company_RangeMulti'];
	$companyDivision = $_REQUEST['companyDivision'];
	$commissionrate = $_REQUEST['commissionrate'];
	$company_ECCNo = $_REQUEST['company_ECCNo'];
	$company_tin = $_REQUEST['company_tin'];
	$gstin_number = $_REQUEST['gstin_number'];
	$company_pan = $_REQUEST['company_pan'];
	$term_text1 = $_REQUEST['term_text1'];
	$term_text2 = $_REQUEST['term_text2'];
	$term_text3 = $_REQUEST['term_text3'];
	$term_text4 = $_REQUEST['term_text4'];
	$term_text5 = $_REQUEST['term_text5'];
	$term_text6 = $_REQUEST['term_text6'];
	$term_text7 = $_REQUEST['term_text7'];
	$term_text8 = $_REQUEST['term_text8'];
	$term_text9 = $_REQUEST['term_text9'];
	$term_text10 = $_REQUEST['term_text10'];
	$term_text11= $_REQUEST['term_text11'];
	$term_text12 = $_REQUEST['term_text12'];
	
	$Query = "UPDATE company_info SET Name = '$company_nm', Address = '$company_add', Phone = '$company_phone', email = '$company_email', Website = '$company_website', CERegnNo = '$company_CERegnNo', RangeMulti = '$company_RangeMulti', Division = '$companyDivision', Commisionarate = '$commissionrate',ECCNo = '$company_ECCNo',TIN='$company_tin', gstin_number='$gstin_number', PAN='$company_pan',txt1='$term_text1',txt2='$term_text2', txt3 = '$term_text3', txt4 = '$term_text4', txt5 = '$term_text5', txt6 = '$term_text6', txt7 = '$term_text7',txt8 = '$term_text8', txt9 = '$term_text9',txt10='$term_text10',txt11 = '$term_text11', PLACE = '$term_text12'";
	$logger->debug($Query); // function to write in log file
	$Result = DBConnection::UpdateQuery($Query);
	
	if($Result == QueryResponse::SUCCESS){
		echo "YES";
		return;exit;  
	}
	else{
		echo "NO";
		return;exit;
	}
   
}

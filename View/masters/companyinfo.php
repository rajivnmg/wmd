<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");
	include($root_path."Model/Param/param_model.php");
	$CompanyInfo = ParamModel::GetCompanyInfo();
		
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Company Info</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<style>
.form-section .custom-error {
    color:#FF0000;
}
</style>
<!-- <script type='text/javascript' src='../js/ang_js/jquery-1.6.4.min.js'></script>
<script type='text/javascript' src='../js/js/ang_js/none.js'></script> -->
<!-- <script type='text/javascript' src='../js/ang_js/angular.min.js'></script> -->

<script type='text/javascript' src='../js/Masters_js/companyinfo.js?v=1.1'></script>
</head>
<body ng-app = "companyinfo_app">
<?php include("../../header.php") ?>
<form name="form1" id="form1" ng-controller="companyinfo_Controller" ng-submit="submitForm();"  class="smart-green">

<div>
<div align="center"><h1>Manage Company Info</h1></div>
    <div style="width:50%; float:left;">
        <label>
            <span>Company Name*:</span>
            <input type="text" name="company_nm" id="company_nm" placeholder="Company Name" tabindex="1" value="<?php print $CompanyInfo['Name']; ?>" required/>
        </label>
    </div>
      
    <div class="clr"></div>
    <div><h2>Company Address Details</h2></div>
    <div style="width:50%; float:left;">
        <label>
            <span>Address Line 1:</span>
            
            <textarea name="company_add" id="company_add"  placeholder="Maximum Char 300." class="ng-pristine ng-valid"> <?php print $CompanyInfo['Address']; ?></textarea>
            
        </label>
    </div>
    
    <div style="width:25%;  float:left;">
        <label>
            <span>Phone No.:</span><br/>
            <input type="text" name="company_phone" id="company_phone"  value="<?php print $CompanyInfo['Phone']; ?>" placeholder="Phone No" tabindex="12" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>Mobile No.:</span><br/>
            <input type="text" name="company_mobile" id="company_mobile"  placeholder="Mobile No" tabindex="11" valid-number/>
        </label>
    </div><div class="clr"></div>
     <div style="width:33%; float:left;">
        <label>
            <span>Email Id:</span><br/>
            <input type="text" name="company_email" id="company_email" value="<?php print $CompanyInfo['email']; ?>"  placeholder="Email Id" tabindex="14" required/> 
        </label>
    </div>
    <div style="width:33%; float:left;">
        <label>
             <span>Website:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br/>
            <input type="text" name="company_website" id="company_website" value="<?php print $CompanyInfo['Website']; ?>"  placeholder="www.example.com" tabindex="14" required/> 
        </label>
    </div>  
   
    <div style="width:33%; float:left;">
        <label>
            <span>Fax No.:</span><br/>
            <input type="text" name="company_fax" id="company_fax"  placeholder="Fax No" tabindex="13" />
        </label>
    </div>
    
    <div class="clr"></div>
   
    
    
    <div class="clr"></div>
   
    <div><h2>Other Details</h2> <hr/></div>
    <div style="width:33%; float:left;">
        <label>
            <span>CERegnNo:</span>
            <input type="text"  name="company_CERegnNo" id="company_CERegnNo" value="<?php print $CompanyInfo['CERegnNo']; ?>" placeholder="CERegnNo" tabindex="15" required/>
        </label>
    </div>
    <div style="width:33%; float:left;">
        <label>
            <span>RangeMulti:</span>
            <input type="text"  name="company_RangeMulti" id="company_RangeMulti" value="<?php print $CompanyInfo['RangeMulti']; ?>" placeholder="Range" tabindex="18" required/>
        </label>
    </div>
    
    <div style="width:33%; float:left;">
        <label>
            <span>ECCNo*:</span>
            <input type="text" name="company_ECCNo" id="company_ECCNo" value="<?php print $CompanyInfo['ECCNo']; ?>" placeholder="ECCNo" tabindex="21"/>
        </label>
    </div>
    
    <div class="clr"></div>
    <div style="width:25%; float:left;">
        <label>
            <span>PAN No:</span>
            <input type="text" name="company_pan" id="company_pan"  value="<?php print $CompanyInfo['PAN']; ?>" placeholder="PAN No" tabindex="16" required/>
        </label>
    </div>
    <div style="width:25%; float:left;">
        <label>
            <span>TIN No*:</span>
            <input type="text"  name="company_tin" id="company_tin" value="<?php print $CompanyInfo['TIN']; ?>" placeholder="TIN No" tabindex="19" required/>
        </label>
    </div>
	<div style="width:25%; float:left;">
        <label>
            <span>GSTIN No*:</span>
            <input type="text"  name="gstin_number" id="gstin_number" value="<?php print $CompanyInfo['gstin_number']; ?>" placeholder="GSTIN No" required/>
        </label>
    </div>
     
   <div style="width:25%; float:left;">
        <label>
            <span>Commissionerate:</span>
            <input type="text" name="commissionrate" id="commissionrate" value="<?php print $CompanyInfo['Commisionarate']; ?>" placeholder="Commissione Rate" tabindex="20" required/>
        </label>
    </div>
   
    <div class="clr"></div>
   
    <div style="width:50%; float:left;">
        <label>
            <span>Division:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            <input type="text" name="companyDivision" id="companyDivision" value="<?php print $CompanyInfo['Division']; ?>" placeholder="Division" tabindex="20" required/>
        </label>
    </div>
   
    <div class="clr"></div>
    <div><h2>Company Terms & Policy</h2> <hr/></div>   
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 1:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text1" id="term_text1"  value="<?php print $CompanyInfo['txt1']; ?>" placeholder="Text 1" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 2:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text2" id="term_text2" value="<?php print $CompanyInfo['txt2']; ?>" placeholder="Text 2" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 3:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text3" id="term_text3"  value="<?php print $CompanyInfo['txt3']; ?>" placeholder="Text 3" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 4:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text4" id="term_text4" value="<?php print $CompanyInfo['txt4']; ?>" placeholder="Text 4" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
     <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 5:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text5" id="term_text5"  value="<?php print $CompanyInfo['txt5']; ?>" placeholder="Text 5" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 6:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text6" id="term_text6" value="<?php print $CompanyInfo['txt6']; ?>" placeholder="Text 6" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
     <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 7:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text7" id="term_text7"  value="<?php print $CompanyInfo['txt7']; ?>" placeholder="Text 7" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 8:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text8" id="term_text8" value="<?php print $CompanyInfo['txt8']; ?>" placeholder="Text 8" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
     <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 9:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text9" id="term_text9"  value="<?php print $CompanyInfo['txt9']; ?>" placeholder="Text 9" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 10:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text10" id="term_text10" value="<?php print $CompanyInfo['txt10']; ?>" placeholder="Text 10" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
     <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>Line 11:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text11" id="term_text11"  value="<?php print $CompanyInfo['txt11']; ?>" placeholder="Text 11" tabindex="27" required/>
            </label>
        </div>       
        <div class="clr"></div>
    </div>
    <div style="width:49%; float:left;">   
        <div style="width:99.9%; float:left;">
            <label>
                <span>PLACE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <input type="text" name="term_text12" id="term_text12" value="<?php print $CompanyInfo['PLACE']; ?>" placeholder="place" tabindex="38"/>
            </label>
        </div>               
        <div class="clr"></div>
    </div>
    <div class="clr"></div>
    
  <div class="clr"></div><br/>
    <div align="center">
        
         <input type="submit" name="addcon"  class="button" id="btnupdatecompany" value="Update" onClick="UpdateCompanyInfo();"  tabindex="60" />
         <span><a tabindex="61" style="text-decoration: none;" class="button" href="<?php //print SITE_URL.VIEWBUYERMASTER; ?>">Cancle</a></span>
        
    </div>
</div>
<div align="left" id="BuyerDiscount_Div" style=" width:90%;margin-left:30px;"></div>
<div align="left" id="ContactInfo_Div" style=" width:90%;margin-left:30px;"></div>
<br/><br/><br/><br/><br/>

<?php include("../../footer.php") ?>   
</body>
</html>

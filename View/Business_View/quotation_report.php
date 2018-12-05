<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Print Quotation</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type='text/javascript' src='../js/Business_action_js/quotation.js'></script> 
<script type='text/javascript' src='../js/Business_action_js/print.js'></script> 
</head>
<body  ng-app="quotation_app">

<form id="form1" ng-controller="quotation_Controller" data-ng-init="init('<?php echo  $_GET['QUOTATIONNUMBER'];?>')"> 
<div id="main">

<div class="tx_ivc">
<div id="printableArea">  
<div style="border-bottom:1px solid #4a4a4a; padding-bottom:5px;"> 
    <br/>
	<img src="../../images/Qthead.png" height="" width="100%"/>
    <div class="clr"></div>
	
</div>
<div style="background:rgba(0, 0, 0, 0) url('../../images/Qbg.png') no-repeat scroll center center / 600px auto; border: 0 none;">
<div> 
        <table cellpadding="0" cellspacing="0">
        <tr>
        <td colspan="2" class="invc_tp_hedi" style="padding-top:18px;"><u>QUOTATION</u></td> 
        </tr>
		<tr>
        <td colspan="2" class="invc_tp_hedi" style="padding-top:18px;"></td> 
        </tr>
        <tr>
        <td class="invc_runng" style="width:450px;"><span >Our Ref: {{quotation._quotation_no}}<!--  MW/QTN/13099 --></span></td>
        <td class="invc_runng"><span>Date: {{quotation._quotation_date}}<!-- 05/06/2014 --> </span></td>
        
        
        </tr>
<tr>
        <td class="invc_runng" style="width:450px;"><span >Your Ref: {{quotation._coustomer_ref_no}}<!--  MW/QTN/13099 --></span></td>
        <td class="invc_runng" ><span>Ref Date: {{quotation._coustomer_ref_date}}<!-- 05/06/2014 --> </span></td>
        </tr>
        <tr>
        <td class="invc_runng" style="padding-top:10px;"><span>M/S {{quotation._coustomer_name}} <br />{{quotation._coustomer_add}} </span></td>
        <td class="invc_runng">&nbsp;</td>
        </tr>

        <tr>
        <td class="invc_runng" style="padding-top:13px;"><span><u>Kind attention: {{quotation._contact_persone}}<!--  Nawanshu Dwivedi --></u></span></td>
        <td class="invc_runng">&nbsp;</td>
        </tr>

        <tr>
        <td class="invc_runng" style="padding-top:13px;">Dear Sir/Madam,</td>
        <td class="invc_runng">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2" class="invc_runng" style="padding-top:13px;">We thank you for your valuable enquiries. We are pleased to offer our rates for the following <span>{{quotation._principal_name}}</span>  products</td>
        
        </tr>
    </table>
    <div class="clr"></div>
    </div>

    <div style="border:1px solid #4a4a4a;margin-top:5px;">
        <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <th class="cst_bdr_one invc_runng" >S.No.</th>
            <th class="cst_bdr_one invc_runng">Material Description </th>
            <th class="cst_bdr_one invc_runng">Code Part</th>
            <th class="cst_bdr_one invc_runng">QTY/MOQ</th>
            <th class="cst_bdr_two invc_runng">Rate/Unit</th>
        </tr>
        <tr ng-repeat="item in quotation._items">
            <td class="cst_bdr_one invc_runng" >{{item.sn}}</td>
            <td class="cst_bdr_one invc_runng" >{{item._item_descp}}</td>
            <td class="cst_bdr_one invc_runng" >{{item._item_code_part_no}}</td>
            <td class="cst_bdr_one invc_runng" >{{item._quantity}}</td>
            <td class="cst_bdr_one invc_runng" >{{item._price_per_unit}}/{{item._unit_name}}</td>
        </tr>
        <!-- <tr>
            <td class="cst_bdr_three">1</td>
            <td class="cst_bdr_fr">Loctite Fixmaster Metal Magic Steel</td>
            <td class="cst_bdr_three">113 GMS</td>
            <td class="cst_bdr_sx" style="text-align:left;">Open</td>
            <td class="cst_bdr_fv">777.00</td>
        </tr> -->
    </table>
    <div class="clr"></div>
    </div>


    <div style="padding-top:20px;">
    <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td colspan="2" class="invc_runng"><span><u>Terms & Conditions:</u></span></td>
        </tr>
        <tr id="show_print_discount" style="display:none;">
            <td class="invc_runng">Discount</td>
            <td class="invc_runng">:{{quotation._discount}} %<!--  VAT @ 4% against From DI & surcharge on VAT @ 5% --></td>
        </tr>
        <tr id="show_print_saletax" style="display:none;">
            <td class="invc_runng">Sales Tax</td>
            <td class="invc_runng">:{{quotation.sale_tax_text}}<!--  VAT @ 4% against From DI & surcharge on VAT @ 5% --></td>
        </tr>
        <tr id="show_print_edu" style="display:none;">
            <td class="invc_runng">Excise Duty & EDU. Cess</td>
            <td class="invc_runng">:{{quotation.edu_text}} <!-- Inclusive --></td>
        </tr>
        <tr id="show_print_delivery" style="display:none;">
            <td class="invc_runng">Delivery</td>
            <td class="invc_runng">:{{quotation.Delivery_text}} <!-- Ex-Stock/Within 07-15 Days --></td>
        </tr>
        <tr id="show_print_payment" style="display:none;">
            <td class="invc_runng">Payment</td>
            <td class="invc_runng">:Within {{quotation._credit_period}} Days</td>
        </tr>
        <tr id="ifrgp" style="display:none;">
            <td class="invc_runng">Freight</td>
            <td class="invc_runng">:{{quotation.frgp}} %</td>
        </tr>
        <tr id="ifrga" style="display:none;">
            <td class="invc_runng">Freight</td>
            <td class="invc_runng">:{{quotation.frga}} INR.</td>
        </tr>
        <tr id="show_print_incidental" style="display:none;">
            <td class="invc_runng">Incidental Charges</td>
            <td class="invc_runng">:{{quotation._incidental_chrg}} %</td>
        </tr>
        <tr id="show_print_cvd" style="display:none;">
            <td class="invc_runng">Cvd Charge</td>
            <td class="invc_runng">:{{quotation._cvd}} %</td>
        </tr>
        <tr>
		 <tr>
			<td class="invc_runng">Remarks</td>
            <td colspan="" class="invc_runng">:{{quotation._remarks}}</td>           
        </tr>
            <td colspan="2" class="invc_runng" style="padding-top:20px;">We look forward to receive your valuable orders.</td>           
        </tr>
        <tr>
            <td colspan="2" class="invc_runng" style="padding-top:18px;">Thanking You, <br /> Yours faithfully <br /> For Multiweld Engineering Pvt. Ltd.</td>           
        </tr>

        <tr>
    <td colspan="2" class="invc_runng" style="padding-top:18px;"><?php session_start(); echo $_SESSION["USER_NAME"]; ?></td>           
        </tr>
       
    </table>
    <div class="clr"></div>
    </div>
	</br>
	</div>
	<div style="border-bottom:1px solid #4a4a4a; padding-bottom:1px;" align="center">    
	<img src="../../images/footer.png" height="" width="100%"/>
   
	
</div>
 </div>
<div class="clr"></div>
</div>
</div>

<div style="margin-top:10px; margin:bottom:10px;" align="center">
<input type="button" name="prt" id="ipr" value="Print" style="width:50px;" onclick="printDoc('printableArea');"/>&nbsp;
<input type="button" name="cnl" id="icn" value="Cancel" style="width:55px;" onclick="sendBack();"/>
</div>

</body>
</form>
</html>
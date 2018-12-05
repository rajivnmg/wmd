<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<title>Print - Outgoing Non-Excise</title>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/Business_action_js/Outgoing_Invoice_NonExcise.js'></script>
<script type='text/javascript' src='../js/Business_action_js/print.js'></script>
<script>
    
function invtype()
{
    if($("#invtypeselect").val() != 0)
    {
        $("#invtypetext").text($("#invtypeselect").val());
    }
    else
    {
        alert("Please select print type.");
    }
}
</script>
</head>
<body ng-app="Outgoing_Invoice_NonExcise_App">
<form id="form1" ng-controller="Outgoing_Invoice_NonExcise_Controller" data-ng-init="print('<?php echo $_GET['OutgoingInvoiceNonExciseNum'];?>');" class="smart-green">
<div align="center" style="width:100%;"> 
        <select id="invtypeselect" onchange="invtype()">
            <option value="0">Select Print Type</option>
            <option value="ORIGINAL FOR BUYER">ORIGINAL FOR BUYER</option>
            <option value="DUPLICATE FOR TRANSPORTER">DUPLICATE FOR TRANSPORTER</option>
            <option value="COPY">COPY</option>
            <option value="QUADUPLICATE FOR ASSESSE">QUADUPLICATE FOR ASSESSE</option>
            <option value="TRIPLICATE FOR CENTRAL EXCISE">TRIPLICATE FOR CENTRAL EXCISE</option>
        </select>
    </div>
    
    <div id="main">

<div class="tx_ivc">
<div id="printableArea">
    <div style="width: 100%; height: 150px; border: 0px solid #000;">
        <div style="width: 68%; height: 150px; float: left;">
            <div style="height:120px;">
                <div style="width: 50%; padding-top: 60px; float: left;" class="invc_tp_one invc_runng">
                    
                    TIN 06911916320</div>
                <div style="width: 50%; float: left;" align="center">
                    <div class="invc_tp_hedi" style="height:40px;">
                        <span id="vat_invoice" style="display: none;">TAX INVOICE</span>
                        <span id="cst_invoice" style="display: none;">RETAIL INVOICE</span>
                    </div>
                    <div>
                        <div id="vat_invoice2" class="invc_tp_tw" style="display: none;">VALID FOR INPUT TAX</div>
                    </div>
                </div>
                <div class="clr"></div>
            </div>
            <div><span style=" color: #000; font-weight: bolder; font-size: 20px; ">MULTIWELD ENGINEERING PVT. LTD.</span></div>
        </div>
        <div style="width: 32%;height: 150px; float: left; font-size: 14px;">
            <div class="invc_tp_three invc_runng" style="height:55px;"><span id="invtypetext" ></span></div>
            <div class="invc_tp_three invc_runng">
                B-583A, Sushantlok, Phase-I, <br />
                Gurgaon-122002(Hr.)<br />
                Ph. 4063759, 4377027<br />
                E-Mail : multiweld@vsnl.net<br />
                Website : www.multiweld.net
            </div>
        </div>
        <div class="clr"></div>
    </div>
    
    <div >

        <table style="width: 100%; height: 120px; border:1px solid #4a4a4a;" cellpadding="0" cellspacing="0">
        <tr>
        <td rowspan="3" class="invc_runng_one" style=" border-right: 1px solid #4a4a4a;"><span>To, <br />
            M/s &nbsp; {{BuyerDetaile._buyer_name}} </span><br />
            {{BuyerDetaile._bill_add1}},{{BuyerDetaile._bill_add2}}<!--,{{BuyerDetaile._location_name}}, {{BuyerDetaile._city_name}}--> </td>
                <td  class="invc_two invc_runng_one"><span>Tax Invoice No. : {{OutgoingInvoiceNonExciseNum.oinvoice_No}}</span></td>
                <td class="invc_three invc_runng_one"><span>Date </span> : {{OutgoingInvoiceNonExciseNum.oinv_date}}</td>


        </tr>

        <tr>
        <td class="invc_runng_one"><span>Order No.</span> : {{PODetaile.pon}}</td>
        <td class="invc_runng_one"><span>Date </span> : {{PODetaile.pod}}</td>


        </tr>

        <tr>
        <td class="invc_runng_one"><span>Through </span> : {{OutgoingInvoiceNonExciseNum.mod_delivery_text}}</td>
        <td class="invc_runng_one">&nbsp;</td>
        </tr>
    </table>
    <div class="clr"></div>
    </div>

    <div style="border:1px solid #000; color: #000; height:340px;">
        <div style="float: left;height:340px; width: 8%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">S.No.</div>
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.sn}}</div>
        </div>
        <div style="float: left;height:340px; width: 24%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">Customer Item Code</div>
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.codepart}}</div>
        </div>
        <div style="float: left;height:340px; width: 30%; text-align: left; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">DESCRIPTION</div>
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.desc}}<div>{{item.cpart}}</div></div>
        </div>
        <div style="float: left;height:340px; width: 10%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">QTY</div>
            <div style="margin-top: 7px; margin-left: 2px; height: 28px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.qty}}</div>
        </div>
        <div style="float: left;height:340px; width: 12%; text-align: right; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">RATE</div>
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.rate}}</div>
        </div>
        <div style="float: left;height:340px; width: 15%; text-align: right; font-weight: bold; font-size: 14px; border-right: 0px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">AMOUNT</div>
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" ng-repeat="item in PODetaile._items2">{{item.amount.toFixed(2)}}</div>
        </div>
        <div class="clr"></div>
    </div>

    <div style="margin:auto; height:auto;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="color: #000;">
        <tr>
            <td rowspan="4" valign="top" class="tin_one" style=" border-right:1px solid #2c2c2c; border-left:1px solid #2c2c2c; padding-left:5px; font-weight: bold; color: #000;">TIN NO. : {{BuyerDetaile._tin}}</td>
            <td class="tin_two" style="border-bottom:1px solid #2c2c2c;  padding:5px;">
                <div style=" font-weight: bold; color: #000;">Total Amount</div>
                <div id="discountdetails">- Discount @ {{BillDetaile.discount_percent}} %</div>
                <div id="pfblog">+ P&F Charge @ {{BillDetaile.pf_percent}} %</div>
                <div id="inciblog">Incidental Charge @ {{BillDetaile.inci_percent}} %</div>
            </td>
            <td class="tin_three" style="border-bottom:1px solid #2c2c2c; border-right:1px solid #2c2c2c;">
                <div>{{BillDetaile.basic_amount.toFixed(2)}}</div>
                <div id="discountdetails2">{{BillDetaile.discount_amt}}</div>
                <div id="pfblog2">{{BillDetaile.pf_amt}}</div>
                <div id="inciblog2">{{BillDetaile.inci_amt}}</div>
            </td>
        </tr>
        <tr>
            <td class="tin_two" style="border-bottom:1px solid #2c2c2c; padding-left:5px;">Taxable Amount</td>
            <td class="tin_three" style="border-bottom:1px solid #2c2c2c; border-right:1px solid #2c2c2c; padding-left:5px;">{{BillDetaile.TaxableAmount}}</td>
        </tr>
        <tr>
            <td class="tin_two" style="border-bottom:1px solid #2c2c2c; padding:5px;">
                <div>+ {{TaxDetaile.SALESTAX_DESC}}</div>
                <div id="surchargeblog">+ {{TaxDetaile.SURCHARGE_DESC}}</div>
                <div id="ferightblog">+ Freight  {{BillDetaile.feright_percent}} </div>
            </td>
            <td class="tin_three" style="border-bottom:1px solid #2c2c2c; border-right:1px solid #2c2c2c; padding-left:5px;">
                <div>{{BillDetaile.SaleTaxAmount}}</div>
                <div id="surchargeblog2">{{BillDetaile.SurchargeAmount}}</div>
                <div id="ferightblog2">{{BillDetaile.feright_amt}}</div>
            </td>
        </tr>
        <tr>
            <td class="tin_two" style="padding-left:5px; font-weight: bold; color: #000;">Total Payable Amount</td>
            <td class="tin_three" style=" border-right:1px solid #2c2c2c; font-weight: bold; color: #000;">{{OutgoingInvoiceNonExciseNum.bill_value}}</td>
        </tr>
        <tr>
            <td colspan="3" class="tin_fr" style="border-bottom: 1px solid #2c2c2c; border-right:1px solid #2c2c2c; border-left:1px solid #2c2c2c; padding:5px; font-weight: bold; color: #000;">Rupees: {{OutgoingInvoiceNonExciseNum.toatlvalueinword}} Only</td>

        </tr>

    </table>
    <div class="clr"></div>
    </div>

    <div style="padding-top:5px;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style=" height: 110px; color: #000;" >
        <tr>
            <td class="all_txt">1. All disputes are Subject to Gurgaon Jurisdiction.</td>
            <td class="multi" style=" font-weight: bold; color: #000;"><strong>for, Multiweld Engineering Pvt. Ltd.</strong></td>
        </tr>

        <tr>
            <td class="all_txt">2. Our responsibility ceases on delivery of goods.</td>
            <td >&nbsp;</td>
        </tr>
        <tr>
            <td class="all_txt">3. All Taxes extra, as applicable at the time of supply.</td>
            <td >&nbsp;</td>
        </tr>
        <tr>
            <td class="all_txt">4. Interest @ 24% per annum will be charged after due date.</td>
            <td >&nbsp;</td>
        </tr>

        <tr>
            <td colspan="2" class="auth">Authorised Dealers</td>
        </tr>
    </table>
    <div class="clr"></div>
    </div>

    <div>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="color: #000;">
        
        <tr>
            <td align="center" style=" font-weight: bold; color: #000;"><strong>OKS</strong></td>
            <td align="center" style=" font-weight: bold; color: #000;"><strong>HENKEL LOCTITE</strong></td>
            <td align="center" style=" font-weight: bold; color: #000;"><strong>FESTO</strong></td>
        </tr>

        <tr>
            <td align="center" >Lubricants</td>
            <td align="center" >Adhesives</td>
            <td align="center" >Pneumatics</td>
        </tr>

    </table>
    <div class="clr"></div>
    </div>
    <div class="regi" style="  font-weight: bold; color: #000;">
        <hr style="border: 1px solid #000;" />
        <span>Registered Office : B3/83A LAWRENCE ROAD, NEW DELHI-35</span>
    </div>
</div>
</div>
</div>
<div align="center" style="height:25px;">
<input type="button" name="prt" id="ipr" value="Print" style="width:50px;" onclick="printDoc('printableArea');"/>&nbsp;
<input type="button" name="cnl" id="icn" value="Cancel" style="width:55px;" onclick="location.href='view_Outgoing_Invoice_NonExcise.php'"/>
</div>
</form>
</body>
</html>
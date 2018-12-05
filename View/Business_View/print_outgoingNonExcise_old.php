<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Print - Outgoing Non-Excise</title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />

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
    <table style="width: 100%;" border="0px">
       
        <tr id="vat_invoice"  style="display: none;">
            <td class="invc_tp_one">&nbsp;</td>
            <td class="invc_tp_hedi invc_tp_two">TAX INVOICE</td>
            <td class="invc_tp_three invc_cp"><span id="invtypetext" style="font-size: 12px;"></span></td>
        </tr>
        <tr id="cst_invoice"  style="display: none;">
            <td class="invc_tp_one">&nbsp;</td>
            <td class="invc_tp_hedi invc_tp_two">RETAIL INVOICE</td>
            <td class="invc_tp_three invc_cp"><span id="invtypetext" style="font-size: 12px;"></span></td>
        </tr>
        <tr>
            <td valign="top" class="invc_tp_one invc_runng">TIN 06911916320</td>
            <td valign="top" class="invc_tp_two invc_tp_tw"><div id="vat_invoice2" style="display: none;">VALID FOR INPUT TAX</div></td>
            <td valign="top" class="invc_tp_three invc_runng">
            B-583A, Sushantlok, Phase-I, <br />
            Gurgaon-122002(Hr.)<br />
            Ph. 2300543, 4377027<br />
            E-Mail : multimedia@vsnl.net
            Website : www.multiweld.net
            </td>
        </tr>

        <tr>
            <td colspan="2" class="invc_multi">MULTIWELD ENGINEERING PVT. LTD.</td>
            <td>&nbsp;</td>
        </tr>
    </table>
    <div >

        <table style="width: 100%; border:1px solid #4a4a4a;" cellpadding="0" cellspacing="0">
        <tr>
        <td rowspan="3" class="invc_runng_one" style=" border-right: 1px solid #4a4a4a;"><span>To, <br />
            M/s &nbsp; &nbsp; {{BuyerDetaile._buyer_name}} </span><br />
            {{BuyerDetaile._bill_add1}},{{BuyerDetaile._bill_add2}},{{BuyerDetaile._location_name}}, {{BuyerDetaile._city_name}} </td>
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

    <div style="border:1px solid #4a4a4a; min-height:150px!important;">
        <table  style="height: 100%; width: 100%;" border="0" cellpadding="0" cellspacing="0"  >
        <tr>
            <th class="cst_bdr_one s_one" >S.no.</th>
            <th class="cst_bdr_one s_two">Customer Item Code</th>
            <th class="cst_bdr_one s_three">DESCRIPTION </th>
            <th class="cst_bdr_one s_for">QTY</th>
            <th class="cst_bdr_one s_fv">RATE</th>
            <th class="cst_bdr_two s_sx">AMOUNT</th>
        </tr>
            <tr ng-repeat="item in PODetaile._items2"  style="height: 25px;">
            <td class="exc_bdr_left_rght">{{item.sn}}</td>
            <td class="exc_bdr_right">{{item.codepart}}</td>
        <td class="exc_bdr_right">{{item.desc}} <div>{{item.cpart}}</div></td>
        <td class="exc_bdr_right"  style="text-align: center;">{{item.qty}}</td>
        <td class="exc_bdr_right" style="text-align: right;">{{item.rate}}</td>
        <td class="exc_bdr_right" style="text-align: right;">{{item.amount.toFixed(2)}}</td>
        </tr>
        <!-- <tr>
            <td class="cst_bdr_three">1</td>
            <td class="cst_bdr_three">&nbsp;</td>
            <td class="cst_bdr_fr">03049 GLOVE KLN/GRD BN (S) (57371) </td>
            <td class="cst_bdr_three">1,000.00 Set</td>
            <td class="cst_bdr_sx">11.40</td>
            <td class="cst_bdr_fv">11,400.00</td>
        </tr>
        <tr>
            <td class="cst_bdr_three">1</td>
            <td class="cst_bdr_three">&nbsp;</td>
            <td class="cst_bdr_fr">03049 GLOVE KLN/GRD BN (S) (57371) </td>
            <td class="cst_bdr_three">1,000.00 Set</td>
            <td class="cst_bdr_sx">11.40</td>
            <td class="cst_bdr_fv">11,400.00</td>
        </tr> -->




    </table>
    <div class="clr"></div>
    </div>

    <div>
    <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td rowspan="4" valign="top" class="tin_one">TIN NO. : {{BuyerDetaile._tin}}</td>
            <td class="tin_two">
                <div>Total Amount</div>
                <div id="discountdetails">- Discount @ {{BillDetaile.discount_percent}} %</div>
                <div id="pfblog">+ P&F Charge @ {{BillDetaile.pf_percent}} %</div>
                <div id="inciblog">Incidental Charge @ {{BillDetaile.inci_percent}} %</div>
            </td>
            <td class="tin_three">
                <div>{{BillDetaile.basic_amount.toFixed(2)}}</div>
                <div id="discountdetails2">{{BillDetaile.discount_amt}}</div>
                <div id="pfblog2">{{BillDetaile.pf_amt}}</div>
                <div id="inciblog2">{{BillDetaile.inci_amt}}</div>
            </td>
        </tr>
        <tr>
            <td class="tin_two">Taxable Amount</td>
            <td class="tin_three">{{BillDetaile.TaxableAmount}}</td>
        </tr>
        <tr>
            <td class="tin_two">
                <div>+ {{TaxDetaile.SALESTAX_DESC}}</div>
                <div id="surchargeblog">+ {{TaxDetaile.SURCHARGE_DESC}}</div>
                <div id="ferightblog">+ Freight @ {{BillDetaile.feright_percent}} %</div>
            </td>
            <td class="tin_three">
                <div>{{BillDetaile.SaleTaxAmount}}</div>
                <div id="surchargeblog2">{{BillDetaile.SurchargeAmount}}</div>
                <div id="ferightblog2">{{BillDetaile.feright_amt}}</div>
            </td>
        </tr>
        <tr>
            <td class="tin_two">Total Payable Amount</td>
            <td class="tin_three">{{OutgoingInvoiceNonExciseNum.bill_value}}</td>
        </tr>
        <tr>
            <td colspan="3" class="tin_fr">Rupees: {{OutgoingInvoiceNonExciseNum.toatlvalueinword}}</td>

        </tr>

    </table>
    <div class="clr"></div>
    </div>

    <div style="padding-top:5px;">
    <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td class="all_txt">1. All disputes are Subject to Gurgaon Jurisdiction.</td>
            <td class="multi">for, <span> Multiweld Engineering Pvt. Ltd.</span></td>
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

    <div style="padding-bottom:20px;">
    <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td class="oks">OKS</td>
            <td class="oks">HENKEL LOCTITE</td>
            <td class="oks">FESTO</td>
        </tr>

        <tr>
            <td class="lubri">Lubricants</td>
            <td class="lubri">Adhesives</td>
            <td class="lubri">Pneumatics</td>
        </tr>

        <tr>
            <td colspan="3" class="regi">Registered Office : B3/83A LAWRENCE ROAD, NEW DELHI-35</td>

        </tr>

    </table>
    <div class="clr"></div>
    </div>
</div>
<div class="clr"></div>
</div>

<div class="clr"></div>
</div>
<div align="center">
<input type="button" name="prt" id="ipr" value="Print" style="width:50px;" onclick="printDoc('printableArea');"/>&nbsp;
<input type="button" name="cnl" id="icn" value="Cancel" style="width:55px;" onclick="location.href='view_Outgoing_Invoice_NonExcise.php'"/>
</div>
</form>
</body>
</html>
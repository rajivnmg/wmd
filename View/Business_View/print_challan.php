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
<script type='text/javascript' src='../js/Business_action_js/Challan.js'></script>
<script type='text/javascript' src='../js/Business_action_js/printChalan.js'></script>
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
<style>
@page {
}
.page-break {
		page-break-before:always;
	}
@media print {
	.footer_part {
		display: block; 
		position: fixed;
		bottom: 0;
		border-top:1px solid #000;
	}
}
</style>
</head>
<body ng-app="Challan_app">
<form id="form1" ng-controller="Challan_Controller" data-ng-init="init('<?php echo $_GET['ID'];?>');" class="smart-green">
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
	<div style="width: 100%; height: 140px; border: 0px solid #000;" >
        <div style="height: 150px; float: left;">
            <div style="height:60px;">
                <div style="padding-top: 10px; float: left;" class="invc_tp_one invc_runng">
                      <span id="cst_invoice" style="">TIN 06911916320</span>
                 </div>
                <div style="width: 50%; float: left;" align="center">
                    <div class="invc_tp_hedi" style="height:40px;">
                        <span id="vat_invoice">CHALLAN</span>
                      
                    </div>
                  
                </div>
				
                <div class="clr"></div>
            </div>
			<div id="header_part">
				<div style="border-bottom:0px solid #4a4a4a;">  
					<img src="../../images/cominfo.png" height="" width="70%" style="float:left;"/>
					<img src="../../images/logo.png" height="100px;" width="20%" style="float:right;"/>
					<div class="clr"></div>

				</div>
			</div>
        </div>
     
    </div>
    
    <div></br></br></br>

        <table style="width: 100%; height: 125px; border:1px solid #4a4a4a;margin-top:15px;" cellpadding="0" cellspacing="0">
        <tr>
        <td rowspan="3" class="invc_runng_one" style=" border-right: 1px solid #4a4a4a;"><span>To, <br />
            M/s &nbsp; {{Challan._BuyerName}} </span><br />
            {{Challan._CustAddress}},{{BuyerDetaile._bill_add2}}<!--,{{BuyerDetaile._location_name}}, {{BuyerDetaile._city_name}}--> </td>
                <td  class="invc_two invc_runng_one"><span>Challan No. </span>: {{Challan._ChallanNo}}</td>
                <td class="invc_three invc_runng_one"><span>Date </span> : {{Challan._ChallanDate}}</td>


        </tr>

        <tr>
        <td class="invc_runng_one"><span>G.C Note.</span> : {{Challan._gc_note}}</td>
        <td class="invc_runng_one"><span>Date </span> : {{Challan._gc_note_date}}</td>


        </tr>

        <tr>
        <td class="invc_runng_one"><span>Your Order No. </span> : {{Challan._OrderNo}}</td>
        <td class="invc_runng_one"><span>Date </span> : {{Challan._OrderDate}}</td>
        </tr>
    </table>
    <div class="clr"></div>
    </div>
    <div style="color: #000; min-height:340px;background:rgba(0, 0, 0, 0) url('../../images/Qbg.png') no-repeat scroll center center/ 60% auto; ">
		<div style="float: left; width: 10%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">S.No.</div>
           
        </div>
        <div style="float: left; width: 30%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">Part No.</div>
          
        </div>
        <div style="float: left; width: 45%; text-align: left; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">Description</div>
           
        </div>
        <div style="float: left;width: 14%; text-align: center; font-weight: bold; font-size: 14px; ">
            <div style="border-bottom: 1px solid #000; height: 22px; line-height: 22px; text-align: center;">Quantity</div>
            
        </div>
	<div ng-repeat="item in Challan._items" id="{{ 'SrNo-' + $index }}">
        <div style="float: left;min-height:33px; width: 10%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
           
            <div id="" style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;">{{item._SrNo}}
			</div>
        </div>
        <div style="float: left;min-height:33px; width: 30%; text-align: center; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
            
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" >{{item._code_part_no}}</div>
        </div>
        <div style="float: left;min-height:33px; width: 45%; text-align: left; font-weight: bold; font-size: 14px; border-right: 1px solid #000;">
           
            <div style="padding: 5px; height: 25px;font-weight: normal; font-size: 14px;" >{{item.item_desc}}<div>{{item.cpart}}</div></div>
        </div>
        <div style="float: left;min-height:33px; width: 10%; text-align: center; font-weight: bold; font-size: 14px; ">
           
            <div style="margin-top: 7px; margin-left: 2px; height: 28px;font-weight: normal; font-size: 14px;" >{{item._qty}}</div>
        </div>
		
		<div style="clear:both"></div>
    </div>
	
        <div class="clr"></div>
	<input type="hidden" name="breakpoint" id="breakpoint" value="{{ $index }}" class="ng-pristine ng-valid">
    </div>

    <div style="margin:auto; height:auto;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0"  style="color: #000;">
        <tr>
            <td rowspan="4" valign="top" class="tin_one" style=" border-right:1px solid #2c2c2c; border-left:1px solid #2c2c2c; padding-left:5px; font-weight: bold; color: #000; width:500px;" align="right">TOTAL </td>
            <td class="tin_two" style="border-bottom:1px solid #2c2c2c;  padding:5px;">
                <div style=" font-weight: bold; color: #000;"></div>
               
            </td>
            <td class="tin_three" style="border-bottom:1px solid #2c2c2c; border-right:1px solid #2c2c2c;font-weight: bold;text-align: center; " >
                <div>{{Challan._total_Qty}}</div>
               
            </td>
        </tr>
     
     
   
    </table>
    <div class="clr"></div>
    </div>
	 </br>
   <div style="padding-top:5px;">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style=" height:auto; color: #000;">
        <tbody>
		<tr>
		
	       <td class="all_txt">Status : {{Challan._Challan_st}}</td>
            <td>&nbsp;</td>
        </tr>
		<tr>
            <td class="all_txt">&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
		
		<tr>
            <td class="all_txt">1. All disputes are Subject to Gurgaon Jurisdiction.</td>
			 <td>&nbsp;</td>
        </tr>

        <tr>
            <td class="all_txt">2. Our responsibility ceases on delivery of goods.</td>
            <td class="multi">FOR <strong>MULTIWELD ENGINEERING PVT. LTD.</strong></td>
        </tr>
        <tr>
            <td class="all_txt">3. All Taxes extra, as applicable at the time of supply.</td>
            <td>&nbsp;</td>
        </tr>
		
       

       
    </tbody></table>
    <div class="clr"></div>
    </div>
  

  </br>
	<div id="footer_part">
		 <div class="footer_part" style="border-bottom:1px solid #4a4a4a; padding-bottom:1px;" align="center">    
			<img src="../../images/footer.png" height="" width="100%"/>
		</div>
	</div>
	
</div>
</div>
</div>
<div align="center" style="height:25px;">
<input type="button" name="prt" id="ipr" value="Print" style="width:50px;" onclick="printDoc('printableArea');"/>&nbsp;
<input type="button" name="cnl" id="icn" value="Cancel" style="width:55px;" onclick="location.href='ChallanView.php'"/>
</div>
</form>
</body>
</html>
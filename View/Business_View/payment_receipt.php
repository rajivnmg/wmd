<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Print - Outgoing Excise</title>
<head>
    <title>Print - Payment Received Receipt</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/print.css" rel="stylesheet" type="text/css" 
media="print" />
<style>
@page {
  size: landscape;
}	
</style>
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' 
src='../js/Business_action_js/Outgoing_Invoice_Excise.js'></script> 
<script type='text/javascript' 
src='../js/Business_action_js/print.js'></script> 
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
.largefont { 
  color: #696963; 
  font-family:arial; 
  font-size:22px;
  font-style: normal;
  font-weight: bold; 
}	
</style>
</head>
<body ng-app="Payment_App">
<form id="form1" ng-controller="Payment_Controller"  data-ng-init="print('<?php echo $_GET['trxnId'];?>');" class="smart-green">
<div style="width: 100%; font-size: 15px; font-weight: 700; text-align: center;">
<div style="width:100%;float:left;text-align:center;"><span class="largefont">MULTIWELD ENGINEERING PVT. LTD.</span></div>
<div style="width:100%;float:left;">
<span style="align:left">
B-583A, Sushantlok, Phase-I,<br>
Gurgaon-122002(Hr.)<br>
Ph. 4063759,4377027<br>
E-Mail : multiweld@vsnl.net<br>
Website : www.multiweld.net<br>	
</span>

</div>
</div>

<div style="width: 100%; font-size: 15px; font-weight: 700; text-align: center;">
     <br/><br/>  <span class="largefont"><u>Payment Received Receipt</u></span>
</div>

<div class="clr"></div>
<div style="width: 100%; font-size: 15px; font-weight: 700; text-align: center;">
     <span class="largefont">&nbsp;</span>
</div>
<div style="width:100%; float:left;">
          <table width="80%" border="1" cellpadding="0" cellspacing="0" align="center">
          <tr>
          
          <td style="width:200px;" rowspan="2"><label><span>Invoice No.</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Invoice Amount</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Short Amt.</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Excess Amt.</span></label></td>
           <td style="width:200px;" rowspan="2"><label><span>Cash Discount</span></label></td>
          <td style="width:300px;" colspan="3"><div align="center"><label><span>Debit Note</span></label></div></td>
          <td style="width:200px;" rowspan="2"><label><span>Status</span></label></td>          
          <td style="width:200px;" rowspan="2"><label><span>Payabled Amount</span></label></td>
           <td style="width:200px;" rowspan="2"><label><span>Balanced Amount</span></label></td>
          <td style="width:200px;" rowspan="2"><label><span>Received Amount</span></label></td>
         
          </tr>
          <tr>
              <td width="100px"><label><span>Select</span></label></td>
              <td width="200px"><label><span>DebitId</span></label></td>
              <td width="200px"><label><span>Debit Amount</span></label></td>
           </tr> 
 </table>
 </div>          

</form>
	
</body>
</html>
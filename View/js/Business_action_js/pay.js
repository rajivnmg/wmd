var BuyerList = {};
function CallToBuyer(BuyerList) {
    'use strict';
    var buyerArray = $.map(BuyerList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-buyer').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: buyerArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
        	
            var re = new RegExp('^' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
            
        },
        onSelect: function (suggestion) {
            ActionOnBuyer(suggestion.value, suggestion.data);
            //$('#selction-ajax-buyer').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-buyer').val(hint);
        },
        onInvalidateSelection: function () {
            NoneBuyer();
            //$('#selction-ajax-buyer').html('You selected: none');
        }
    });
}
function NoneBuyer() {
    $("#buyerid").val(0);
    
}
// commented due to page loading performance on 25-11-2015 by Codefire
/* jQuery.ajax({
    url: "../../Controller/Master_Controller/Buyer_Controller.php",
    type: "post",
    data: { TYP: "GETBUYERLIST", BUYERID: 0 },
    success: function (jsondata) {
    	//alert(jsondata);
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            var obj;
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                BuyerList[obj._buyer_id] = obj._buyer_name;
            }
            CallToBuyer(BuyerList);
        }
    }
}); */


// added due to page loading performance on 25-11-2015 by Codefire
 function loadBuyerByName(buyer){ 
	if(buyer.length > 1 && buyer.length < 3){ 
		jQuery.ajax({
			url: "../../Controller/Master_Controller/Buyer_Controller.php",
			type: "post",
			data: { TYP: "SELECT", BUYERID: 0, BUYERNAME: buyer },
			success: function (jsondata) { 
			
				var objs = jQuery.parseJSON(jsondata);
				
				if (jsondata != "") {
					var obj;
					for (var i = 0; i < objs.length; i++) {
						var obj = objs[i];
						BuyerList[obj._buyer_id] = obj._buyer_name;
					}
					
					CallToBuyer(BuyerList);
				}
			}
		});
	}else{
		CallToBuyer(BuyerList);
	}
}
function ActionOnBuyer(value, data) {
  if (value != "" && data!="") {
	  var finyears = [];
		$('#c_b :checked').each(function() {
			finyears.push($(this).val());
		});
		  
    $("#buyerid").val(data);
    jQuery.ajax({ url: "../../Controller/Business_Action_Controller/pay_Controller.php", type: "POST",
    data: {TYP: "GETINV_NEW",BUYERID: data,INVOICENO:'',finyears:finyears},
    success: function (jsondata) {
    	//alert(jsondata);
    $('#invoicelist').empty();
    $("#invoicelist").append("<option value='0'>Select Invoice No</option>");
    var objs = jQuery.parseJSON(jsondata);
    if (jsondata != "") {
      var obj;
      for (var i = 0; i < objs.length; i++) {
        var obj = objs[i];
       
        $("#invoicelist").append("<option value=\"" +obj.invoiceNo+"\"> Invoice No : " + obj.invoiceNo +" Invoice Date : "+obj.invoiceDate+" Due Date : "+obj.dueDate+",Invoice Amount : "+obj.invoiceAmount+",Balance Amount : "+obj.balanceAmount+"</option>");
      }
    }
   }
   });
        
  }  
 }       

var payment_app = angular.module('pay_app', []);
payment_app.controller('pay_Controller', function ($scope) {
	var sample_pay ={_items: [{trxndId:'',invoiceNo:'',invoiceDate:'', dueDate:'',invoiceAmount:0,shortAmount:0,excess_amt:0,cash_discount_value:0,debitFlag:'',debitId:'',debitAmt:0,payabledAmount:0,balanceAmount:0,receivedAmount:0,payment_status:'',payment_status_value:'',Remarks:''}] };
    $scope.payment = sample_pay;
	$scope.init=function(number){

     	if(number!="")
     	{
     		 $("#btnsave").hide();
     		 $('#txtreason').attr('readonly',false);
 			 jQuery.ajax({
			 url: "../../Controller/Business_Action_Controller/pay_Controller.php",
			 type: "POST",
		     data: { 
		     TYP: "SELECT" ,
		            trxnId:number},
		            success: function (jsondata){
		            $scope.$apply(function () {
		               var objs=jQuery.parseJSON(jsondata);
		             //  alert(jsondata);
		              // $('#btadd').hide();
		                
		               $('#trxnid').val(objs[0].trxnId);
		               $scope.payment.trnx_no=objs[0].trnx_no;
		               $scope.payment.bn=objs[0].buyerId;
		              
		              // $scope.showInvoicelist(objs[0].buyerId);
		               $scope.payment.bn_name=objs[0].BuyerName;
		               $scope.payment.trxn_date=objs[0].trxn_date;
		               $("#trntype").val(objs[0].trxn_type);
		               $scope.payment.bank_name=objs[0].bank_name;
		               $scope.payment.branch_name=objs[0].branch_name;
		               $scope.payment.cheque_no=objs[0].cheque_no;
		               $scope.payment.cheque_account_no=objs[0].cheque_account_no;
		               $scope.payment.cheque_date=objs[0].cheque_date;
		               $scope.payment.bank_add=objs[0].bank_add;
		               $scope.payment.total_amt=objs[0].received_amt;
		               $scope.payment.cheque_amount=objs[0].received_amt;
		               $scope.payment._items= objs[0]._items;
		               if($('#trntype').val()=='' ||$('#trntype').val()=='C')
	   	  {
		  	$('#bankname').attr('readonly',true);
		  	$('#banchname').attr('readonly',true);
		  	$('#chequeno').attr('readonly',true);
		  	$('#chequedate').attr('disabled',true);
		  	$('#ibank_add').attr('readonly',true);
		    $('#accountno').attr('readonly',true);
		  	$('#utrno').attr('readonly',true);
		  	msg="Cash Amount";
		  }
		  else if($('#trntype').val()=='H')
		  {
		  	$('#bankname').attr('readonly',false);
		  	$('#banchname').attr('readonly',false);
		  	$('#chequedate').attr('disabled',false);
		  	$('#chequeno').attr('readonly',false);
		  	$('#ibank_add').attr('readonly',false);
		  	$('#accountno').attr('readonly',false);
		  	$('#utrno').attr('readonly',true);
		  	 msg="Cheque Amount";
		  }
		  else if($('#trntype').val()=='R')
		  {
		  	$('#bankname').attr('readonly',false);
		  	$('#banchname').attr('readonly',false);
		  	$('#utrno').attr('readonly',false);
		  	$('#chequedate').attr('disabled',true);
		  	$('#chequeno').attr('readonly',true);
		  	$('#ibank_add').attr('readonly',false);
		  	$('#accountno').attr('readonly',false);
		  	msg="RTGS Amount";
		  }
		 $('#btadd').hide();
		 // $("#btremove").attr('disabled',true);
		               if(objs[0].status=='cancelled')
		               {
					   	$("#btncancelInv").hide();
						$("#btnupdate").hide();
						
						$("#btremove").attr('disabled',true);
						$scope.payment.cancel_reason=objs[0].cancel_reason;
					   }
		               for(var i=0;i<objs[0]._items.length;i++)
		               {
		               //$scope.payment._items[i]["btremove"].hide();
						
					   }
                       
                    });
		          }
		          });
		  }else{
		  	$("#btncancelInv").hide();
		  	$("#btnupdate").hide();
		  	$('#txtreason').attr('readonly',true);
		  	$scope.payment._items.splice($scope.payment._items.indexOf(0), 1);
		  	jQuery.ajax({
                 url: "../../Controller/Business_Action_Controller/pay_Controller.php",
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {//alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $("#trnxNo").val(objs);
                    $scope.$apply(function () {
                        $scope.payment.trnx_no = objs;
                    });
                }
            });
		  }
		 }
		$scope.removeItem = function (item) {
	     if($("#trxnid").val()==''||$("#trxnid").val()==null||$("#trxnid").val()=='0')
	     {
		   $scope.payment._items.splice($scope.payment._items.indexOf(item), 1);
           $scope.payment.total_amt= 0;
           var k = 0;
            while (k < $scope.payment._items.length) {
            $scope.payment.total_amt= parseFloat($scope.payment._items[k]["received_amt"]) + parseFloat($scope.payment.total_amt);
            k++;
            }	
		 }
		 else
		 {
		 	alert("You can't delete Row");
		 	return ;
		 }
        
        }
        
        $scope.CancelTRXN=function()
        {
        	if($("#txtreason").val()=="")
            {
				alert("Please Enter Cancel Reason !");
				return;
			}
        	$("#btncancelInv").hide();
            $("#btcancel").hide();
            $("#btnsave").hide();
            
            $scope.payment.trxnId=$("#trxnid").val();
            var json_string = JSON.stringify($scope.payment);
			jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/pay_Controller.php",
            type: "POST",
            data: { TYP: "CANCELTRXN",PAYMENT_DATA:json_string},
            success: function (jsondata) {
                alert("Payment Transaction Successfully Cancelled") 
                $scope.payment = null;
                location.href ="payment_received_list.php";
             }
           });
		}
        
        $scope.showInvoicelist=function(data)
        {
			jQuery.ajax({ url: "../../Controller/Business_Action_Controller/pay_Controller.php", type: "POST",
            data: {TYP: "GETINV",BUYERID: data,INVOICENO:''},
            success: function (jsondata) {
              //alert(jsondata);
             $('#invoicelist').empty();
             $("#invoicelist").append("<option value='0'>Select Invoice No</option>");
             var objs = jQuery.parseJSON(jsondata);
            if (jsondata != "") {
              var obj;
                for (var i = 0; i < objs.length; i++) {
                 var obj = objs[i];
       
               $("#invoicelist").append("<option value=\"" +obj.invoiceNo+"\"> Invoice No : " + obj.invoiceNo +" Invoice Date : "+obj.invoiceDate+" Due Date : "+obj.dueDate+",Invoice Amount : "+obj.invoiceAmount+",Balance Amount : "+obj.balanceAmount+"</option>");
               }
           }
         }
        });
	   }
       $scope.addItem = function () {
       	 $scope.payment.invoice_no=$("#invoiceno").val();
       	 $scope.payment.invoice_date=$("#invoicedate").val();
       	 $scope.payment.due_date=$("#duedate").val();
       	 $scope.payment.invoice_amt=$("#invoiceamt").val();
       	 $scope.payment.short_amt=$("#shortamt").val();
       	 $scope.payment.excess_amt=$("#excessamt").val();
       	 $scope.payment.discount_amt=$("#discountamt").val();
       	 $scope.payment.debitFlag=$("#debitFlag").val();
       	 $scope.payment.debitId=$("#debitid").val();
       	 $scope.payment.debit_amt=$("#debitamt").val();
       	 $scope.payment.payment_status=$("#invoicestatus").val();
       	 var invoceStatus=$("#invoicestatus :selected").text() 
       	 $scope.payment.payabled_amt=$("#payabledamt").val();
       	 $scope.payment.balance_amt=$("#balanceamt").val();
       	 $scope.payment.received_amt=$("#received_amt").val();
       	 $scope.payment.remark=$("#remark").val();
       	 var debitFlag=0;
       	 var msg='';
       	 if($('#trntype').val()==null||$('#trntype').val()=='')
       	 {
		 	alert("Please Select Payment Mode");
				return;
		 }
       	 if($('#trntype').val()=='' ||$('#trntype').val()=='C')
	   	  {
		  	
		  	msg="Cash Amount";
		  }
		  else if($('#trntype').val()=='H')
		  {
		  	
		  	 msg="Cheque Amount";
		  }
		  else if($('#trntype').val()=='R')
		  {
		  	
		  	msg="RTGS Amount";
		  }
       	 if($("#debitFlag").prop( "checked" )==true)
       	 {   debitFlag=1;
		 	if($("#debitid").val()==''||$("#debitid").val()==null)
		 	{
				alert("Please Enter Debit Id");
				$("#debitid").focus();
				return;
			}
			else if($("#debitamt").val()==''||$("#debitamt").val()==null||$("#debitamt").val()=='0')
			{
				alert("Please Enter Debit Amount");
				$("#debitamt").focus();
				return;
			}
		 }
		 if($("#invoicestatus").val()==''||$("#invoicestatus").val()==null)
		 {
		 	alert("Please Select Payment Status");
		 	$("#invoicestatus").focus();
			return;
		 }
		 if($("#chequeamt").val()==''||$("#chequeamt").val()==null)
	   	  {
		   	 alert("Please Enter "+msg);
		   	 $("#chequeamt").focus();
		   	 return;
		  }
		  
		  if($("#received_amt").val()==''||$("#received_amt").val()==null ||$("#received_amt").val()<=0)
		  {
		  	 alert("Please Enter Received Amount");
		  	 $("#received_amt").focus();
		  	
		  	 return;
		  }
       	 $scope.payment._items.push({ trxndId:'',invoiceNo:$scope.payment.invoice_no,invoiceDate:$scope.payment.invoice_date, dueDate:$scope.payment.due_date,invoiceAmount:$scope.payment.invoice_amt,shortAmount:$scope.payment.short_amt,excessAmount:$scope.payment.excess_amt,cash_discount_value:$scope.payment.discount_amt,debitFlag:debitFlag,debitId:$scope.payment.debitId,debitAmt:$scope.payment.debit_amt,payment_status:$scope.payment.invoice_status,invoice_status_value:invoceStatus,payabledAmount:$scope.payment.payabled_amt,balanceAmount: $scope.payment.balance_amt,receivedAmount:$scope.payment.received_amt,payment_status:$scope.payment.payment_status,payment_status_value:invoceStatus,Remarks:$scope.payment.remark});
       	 $scope.calculateTotal();
       
       	 $scope.payment.invoice_no="";
       	 $scope.payment.invoice_date="";
       	 $scope.payment.due_date="";
       	 $scope.payment.invoice_amt="";
       	 $scope.payment.short_amt="";
       	 $scope.payment.excess_amt="";
       	 $scope.payment.discount_amt="";
       	 $scope.payment.debitFlag="";
       	 $scope.payment.debitId="";
       	 $scope.payment.debit_amt="";
       	 $scope.payment.invoice_status="";
       	 $scope.payment.payabled_amt="";
       	 $scope.payment.balance_amt="";
       	 $scope.payment.received_amt="";
       	 $scope.payment.remark="";
	   }
	   $scope.enabledDebit=function()
	   {
	   	  if($("#debitFlag").prop( "checked" )==true)
       	 {
		 	 $('#debitid').attr('readonly',false);
		 	 $('#debitamt').attr('readonly',false);		 
		 }
		 else if($("#debitFlag").prop( "checked" )==false)
		 {
		 	 $('#debitid').attr('readonly',true);
		 	 $('#debitamt').attr('readonly',true);	
		 }
	   }
	   $scope.calculateTotal=function()
	   {   
	       var k = 0;
	       var sum=0;
	   	   while (k < $scope.payment._items.length) {
	   	   	   
	   	       sum=parseFloat(sum)+parseFloat($scope.payment._items[k]["receivedAmount"]);
	   	   	k++;
	   	   }
	   	   
	   	   if(sum>$("#chequeamt").val())
	   	   {
		   	 alert("Sum of received amount greater than Cheque Amount");
		   	 $scope.removeItem(k);
		   	 return;
		   }
		   else
		   {
		   	$scope.payment.total_amt=sum;
		   }
	   	   	
	   }
       $scope.calculatePayableAmt=function()
       { 
		  
	   	  var paymentAmt=0;
	   	  var balanceAmount=0;
	   	  var debitAmt=0;
	   	 
	   	  if($("#excessamt").val()==null || $("#excessamt").val()=='')
	   	  {
		  	$("#excessamt").val(0);
		  }
		  
		  if($("#shortamt").val()==null || $("#shortamt").val()=='')
	   	  {
		  	$("#shortamt").val(0);
		  }
		  
		  if($("#discountamt").val()==null || $("#discountamt").val()=='')
	   	  {
		  	$("#discountamt").val(0);
		  }
		  
		  if($("#debitamt").val()==null || $("#debitamt").val()=='')
	   	  {
		  	$("#debitamt").val(0);
		  }
		  
	      if($("#debitamt").val()==null || $("#debitamt").val()=='')
	   	  {
		  	$("#debitamt").val(0);
		  }
		  
	   	  if($("#received_amt").val()==null || $("#received_amt").val()=='')
	   	  {
		  	$("#received_amt").val(0);
		  } 
	   	  paymentAmt=(parseFloat($("#invoiceamt").val())+parseFloat($("#excessamt").val()))-(parseFloat($("#shortamt").val())+parseFloat($("#debitamt").val())+parseFloat($("#discountamt").val()));
		 //  paymentAmt=(parseFloat($("#payabledamt").val())+parseFloat($("#excessamt").val()))-(parseFloat($("#shortamt").val())+parseFloat($("#debitamt").val())+parseFloat($("#discountamt").val()));
	   	
	   	  balanceAmount=parseFloat(paymentAmt)-parseFloat($("#received_amt").val());
		
	   	  $scope.payment.payabled_amt=paymentAmt.toFixed(2);
	   	  $scope.payment.balance_amt=balanceAmount.toFixed(2);
	   	  
	   }	  
	   $scope.showInvoice=function()
	   {
	   	 var BuyerId=$("#buyerid").val();
	   	 var InvoiceNo=$("#invoicelist").val();
	   	 jQuery.ajax({ 
	   	   url: "../../Controller/Business_Action_Controller/pay_Controller.php", 
	   	   type: "POST",
	   	   data: {TYP: "GETINV",BUYERID:BuyerId,INVOICENO:InvoiceNo},
           success: function (jsondata) {
            var objs = jQuery.parseJSON(jsondata);
            if (jsondata != "") {
               var obj;
               for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                $("#invoiceno").val(obj.invoiceNo);
                $("#invoicedate").val(obj.invoiceDate);
                $("#duedate").val(obj.dueDate);
                $("#invoiceamt").val(obj.invoiceAmount);
                $("#balanceamt").val(obj.balanceAmount);
                $("#payabledamt").val(obj.balanceAmount);
               
		  	    $("#excessamt").val(0);
                $("#shortamt").val(0);
                $("#discountamt").val(0);
		  	    $("#debitamt").val(0);
		  	    $("#debitamt").val(0);
		        $("#received_amt").val(0);

                
               }
               
            }
          }
         });
	   }	 
       $scope.enabledBankDetail=function(){
      
       	    $('#bankname').val('');
		  	$('#banchname').val('');
		  	$('#chequeno').val('');
		  	$('#chequedate').val('');
		  	$('#chequeno').val('');
		  	$('#ibank_add').val('');
		  	$('#accountno').val('');
		  	$('#utrno').val('');
		  	var msg='';
	   	  if($('#trntype').val()=='' ||$('#trntype').val()=='C')
	   	  {
		  	$('#bankname').attr('readonly',true);
		  	$('#banchname').attr('readonly',true);
		  	$('#chequeno').attr('readonly',true);
		  	$('#chequedate').attr('disabled',true);
		  	$('#ibank_add').attr('readonly',true);
		    $('#accountno').attr('readonly',true);
		  	$('#utrno').attr('readonly',true);
		  	msg="Cash Amount";
		  }
		  else if($('#trntype').val()=='H')
		  {
		  	$('#bankname').attr('readonly',false);
		  	$('#banchname').attr('readonly',false);
		  	$('#chequedate').attr('disabled',false);
		  	$('#chequeno').attr('readonly',false);
		  	$('#ibank_add').attr('readonly',false);
		  	$('#accountno').attr('readonly',false);
		  	$('#utrno').attr('readonly',true);
		  	 msg="Cheque Amount";
		  }
		  else if($('#trntype').val()=='R')
		  {
		  	$('#bankname').attr('readonly',false);
		  	$('#banchname').attr('readonly',false);
		  	$('#utrno').attr('readonly',false);
		  	$('#chequedate').attr('disabled',true);
		  	$('#chequeno').attr('readonly',true);
		  	$('#ibank_add').attr('readonly',false);
		  	$('#accountno').attr('readonly',false);
		  	msg="RTGS Amount";
		  }
	   }
	   
       $scope.AddPAY = function () {
       	
       	 if($('#trntype').val()=='' ||$('#trntype').val()=='C')
	   	  {
		  	
		  	msg="Cash Amount";
		  }
		  else if($('#trntype').val()=='H')
		  {
		  	if($('#bankname').val()==''||$('#bankname').val()==null)
		  	{
				alert("Please Enter Bank Name");
			    $("#bankname").focus();
                return;
			}
			if($('#banchname').val()==''||$('#banchname').val()==null)
		  	{
				alert("Please Enter Branch Name");
			    $("#banchname").focus();
                return;
			}
			if($('#chequedate').val()==''||$('#chequedate').val()==null)
		  	{
				alert("Please Enter Cheque Date");
			    $("#chequedate").focus();
                return;
			}
			if($('#chequeno').val()==''||$('#chequeno').val()==null)
		  	{
				alert("Please Enter Cheque Number");
			    $("#chequeno").focus();
                return;
			}
			
			if($('#accountno').val()==''||$('#accountno').val()==null)
		  	{
				alert("Please Enter Account Number");
			    $("#accountno").focus();
                return;
			}
			
			if($('#ibank_add').val()==''||$('#ibank_add').val()==null)
		  	{
				alert("Please Enter Bank Address");
			    $("#ibank_add").focus();
                return;
			}
		  	 msg="Cheque Amount";
		  }
		  else if($('#trntype').val()=='R')
		  {
		  	if($('#bankname').val()==''||$('#bankname').val()==null)
		  	{
				alert("Please Enter Bank Name");
			    $("#bankname").focus();
                return;
			}
			if($('#banchname').val()==''||$('#banchname').val()==null)
		  	{
				alert("Please Enter Branch Name");
			    $("#banchname").focus();
                return;
			}
			
			if($('#utrno').val()==''||$('#utrno').val()==null)
		  	{
				alert("Please Enter UTR Number");
			    $("#chequeno").focus();
                return;
			}
			
			if($('#accountno').val()==''||$('#accountno').val()==null)
		  	{
				alert("Please Enter Account Number");
			    $("#accountno").focus();
                return;
			}
			
			if($('#ibank_add').val()==''||$('#ibank_add').val()==null)
		  	{
				alert("Please Enter Bank Address");
			    $("#ibank_add").focus();
                return;
			}
		  	msg="RTGS Amount";
		  }
       	if ($scope.payment._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        if($("#chequeamt").val()<=0||$("#chequeamt").val()==''||$("#chequeamt").val()==null)
        {
			alert("Please Enter "+msg);
			$("#chequeamt").focus();
            return;
		}
        if($("#chequeamt").val()!=$("#txttotalamt").val())
        {
			alert("Total Received Amount Should be Equal To "+msg);
			return;
		}
       	$scope.payment.bn=$('#buyerid').val();
       	$scope.payment.bn_name=$('#autocomplete-ajax-buyer').val();
       	$scope.payment.trxn_date=$('#trxndate').val();
       	$scope.payment.trn_type=$('#trntype').val();
       	$scope.payment.bank_name=$('#bankname').val();
       	$scope.payment.cheque_no=$('#chequeno').val();
       	$scope.payment.cheque_date=$('#chequedate').val();
		$scope.payment.cheque_account_no = $('#accountno').val();
		$scope.payment.utr_no = $('#utrno').val();
		
       	
        var json_string = JSON.stringify($scope.payment);
        //alert(json_string); return false;
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/pay_Controller.php",
            type: "POST",
            data: { TYP: "INSERT", PAYDATA: json_string },
            success: function (jsondata) {
             alert("save successfully");
             $scope.payment='';
             location.href = "payment_received_list.php";
            },
            error : function () {
				alert("falied..");
			}
        });
    }
  
  
  
/*   $scope.UpdateTRXN = function () {
       	
       	 if($('#trntype').val()=='' ||$('#trntype').val()=='C')
	   	  {
		  	
		  	msg="Cash Amount";
		  }
		  else if($('#trntype').val()=='H')
		  {
		  	if($('#bankname').val()==''||$('#bankname').val()==null)
		  	{
				alert("Please Enter Bank Name");
			    $("#bankname").focus();
                return;
			}
			if($('#banchname').val()==''||$('#banchname').val()==null)
		  	{
				alert("Please Enter Branch Name");
			    $("#banchname").focus();
                return;
			}
			if($('#chequedate').val()==''||$('#chequedate').val()==null)
		  	{
				alert("Please Enter Cheque Date");
			    $("#chequedate").focus();
                return;
			}
			if($('#chequeno').val()==''||$('#chequeno').val()==null)
		  	{
				alert("Please Enter Cheque Number");
			    $("#chequeno").focus();
                return;
			}
			
			if($('#accountno').val()==''||$('#accountno').val()==null)
		  	{
				alert("Please Enter Account Number");
			    $("#accountno").focus();
                return;
			}
			
			if($('#ibank_add').val()==''||$('#ibank_add').val()==null)
		  	{
				alert("Please Enter Bank Address");
			    $("#ibank_add").focus();
                return;
			}
		  	 msg="Cheque Amount";
		  }
		  else if($('#trntype').val()=='R')
		  {
		  	if($('#bankname').val()==''||$('#bankname').val()==null)
		  	{
				alert("Please Enter Bank Name");
			    $("#bankname").focus();
                return;
			}
			if($('#banchname').val()==''||$('#banchname').val()==null)
		  	{
				alert("Please Enter Branch Name");
			    $("#banchname").focus();
                return;
			}
			
			if($('#utrno').val()==''||$('#utrno').val()==null)
		  	{
				alert("Please Enter UTR Number");
			    $("#chequeno").focus();
                return;
			}
			
			if($('#accountno').val()==''||$('#accountno').val()==null)
		  	{
				alert("Please Enter Account Number");
			    $("#accountno").focus();
                return;
			}
			
			if($('#ibank_add').val()==''||$('#ibank_add').val()==null)
		  	{
				alert("Please Enter Bank Address");
			    $("#ibank_add").focus();
                return;
			}
		  	msg="RTGS Amount";
		  }
       	if ($scope.payment._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        if($("#chequeamt").val()<=0||$("#chequeamt").val()==''||$("#chequeamt").val()==null)
        {
			alert("Please Enter "+msg);
			$("#chequeamt").focus();
            return;
		}
        if($("#chequeamt").val()!=$("#txttotalamt").val())
        {
			alert("Total Received Amount Should be Equal To "+msg);
			return;
		}
		
		var trxnID = $('#trxnid').val(); 
		if(!trxnID){
		 alert("Payment not Exist");
		 return false;
		}
       	$scope.payment.bn=$('#buyerid').val();
       	$scope.payment.bn_name=$('#autocomplete-ajax-buyer').val();
       	$scope.payment.trxn_date=$('#trxndate').val();
       	$scope.payment.trn_type=$('#trntype').val();
       	$scope.payment.bank_name=$('#bankname').val();
       	$scope.payment.cheque_no=$('#chequeno').val();
       	$scope.payment.cheque_date=$('#chequedate').val();
		$scope.payment.cheque_account_no = $('#accountno').val();
		$scope.payment.utr_no = $('#utrno').val();
		$scope.payment.trxnId = $('#trxnid').val();
		
        var json_string = JSON.stringify($scope.payment);
       // alert(json_string); return false;
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/pay_Controller.php",
            type: "POST",
            data: { TYP: "UPDATE", PAYDATA: json_string,trxnId:trxnID},
            success: function (jsondata) {
             alert("Update successfully");
             $scope.payment='';
             location.href = "payment_received_list.php";
            },
            error : function () {
				alert("falied..");
			}
        });
    }
     */
    $scope.CheckDecimal = function () {
 
    	var amt=$("#chequeamt").val();
    	if(amt!= amt.replace(/[^0-9\.]/g,'')) {
            amt= amt.replace(/[^0-9\.]/g,'');
        }
        $("#chequeamt").val(amt);
    }   
     	
 	});
	
	
payment_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('payment.cheque_amount', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.payment.cheque_amount = oldValue;
                }
            });
        }
    };
});
 	
payment_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('payment.short_amt', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.payment.short_amt = oldValue;
                }
            });
        }
    };
});
 	
payment_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('payment.excess_amt', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.payment.excess_amt = oldValue;
                }
            });
        }
    };
});
 	
payment_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('payment.discount_amt', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.payment.discount_amt = oldValue;
                }
            });
        }
    };
});
 	
payment_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('payment.received_amt', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.payment.received_amt = oldValue;
                }
            });
        }
    };
});
 	 	

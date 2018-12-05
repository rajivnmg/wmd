var URL = "../../Controller/Business_Action_Controller/pay_Controller.php";
var method = "POST";
var BuyerList = {};
function CallToBuyer(BuyerList) {
    'use strict';
    var buyerArray = $.map(BuyerList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-buyer').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: buyerArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
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
            //$('#selction-ajax-buyer').html('You selected: none');F
        }
    });
}

function ActionOnBuyer(value, data) {
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        //SearchByBuyer();
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
    $('.outgoing_invoice_excise_list').flexOptions({ url: URL });
    $('.outgoing_invoice_excise_list').flexReload();
}
///////////////////////////////// commented due to page loading performance on 25-11-2015 by Codefire
/* 
$( document ).ready(function() {
    jQuery.ajax({
    url: "../../Controller/Master_Controller/Buyer_Controller.php",
    type: "post",
    data: { TYP: "SELECT", BUYERID: 0 },
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
    
}); */

///////////////////////////////// added due to page loading performance on 25-11-2015 by Codefire
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
function LoadReceivedPayment() {
    //alert('url'+ URL);
   
   // var path = URL + '?TYP=SEARCH&trxnNo='+trxnNo + '&Buyerid='+Buyerid+"&Fromdate=" +Fromdate+ '&Todate='+Todate;
    $(".received_payment_list").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'Payment ID', name: 'trxnId', width: 100, sortable: false, align: 'center', process: HitMe  },
                { display: 'Payment Referrence Number', name: 'trnx_no', width:150, sortable: false, align: 'left', process: function (col, id)	{ col.innerHTML = "<a href='javascript:showPaymentHistory(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
                 { display: 'PO Number', name: 'bpono', width:150, sortable: false, align: 'left' },
                { display: 'Payment Date', name: 'trxn_date', width:125, sortable: false, align: 'left' },
                { display: 'Buyer Name', name: 'bn_name', width:300, sortable: false, align: 'left' },
                { display: 'Payment Type', name: 'trxn_type', width: 150, sortable: false, align: 'left' },
				 { display: 'Status', name: 'status', width: 100, sortable: false, align: 'left' },
                { display: 'Received Amount', name: 'received_amt', width: 150, sortable: false, align: 'right' },
                { display: 'User', name: 'UserId', width:100, sortable: false, align: 'left'}],
        
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1360,
        height: 300
    });
    
   
}
LoadReceivedPayment();
function Search() {
    var trxnNo = $('#txttrxnNo').val();
    var Buyerid = $('#buyerid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var pono=$("#txtpono").val();
    var finyear=$("#ddlfinancialyear").val();
    var path = URL + '?TYP=SEARCH&trxnNo='+trxnNo+'&Buyerid='+Buyerid+"&Fromdate="+Fromdate+ '&Todate='+Todate+'&pono='+pono+"&finyear="+finyear;
    
     if (Fromdate != "" && Todate != "") {
		 $(".received_payment_list").flexOptions({ url: path });
		 $(".received_payment_list").flexReload();
    }else {
		alert("Please select date");
    }   
   
}

function HitMe(celDiv, id) {
    $(celDiv).click(function () {
        var trxnId=celDiv.innerText;
         var path = 'payment.php?TYP=SELECT&trxnId=' +trxnId;
                //alert(path);
                window.location.href = path;
        
    });
}


// added on 9-3-2016 to show payment history
function showPaymentHistory(celDiv){	
	
	var paymentid = $("#row" + celDiv).children ("td:first").children ("div").text();
	var paymentnumber = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(2)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	
	
	
	$("#paumentnumn").text(paymentnumber);
	$("#bname").text(bname);
	$('#paymenthistorybox').modal('show');
	/*
	 * alert(paymentid);
	alert(paymentnumber);
	alert(bpono);
	alert(bname)
	 */ 
	jQuery.ajax({         
			url: "payment_details_popup.php?paymentid="+paymentid+"&BPONO="+bpono+"&paymentnumber"+paymentnumber,
            type: "POST",
            data: { TYP: "PAYMENTDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#payment_details').html(jsondata);
               
            }
        });
        

}

function Getpdf() {
	
    var trxnNo = $('#txttrxnNo').val();
	var Buyerid = $('#buyerid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var finyear=$("#ddlfinancialyear").val();
    var pono=$("#txtpono").val();
	 if (Fromdate != "" && Todate != "") {
		 window.open('payment_received_list_pdf.php?trxnNo='+trxnNo+'&Buyerid='+Buyerid+"&Fromdate="+Fromdate+ '&Todate='+Todate+'&pono='+pono+'&finyear='+finyear,'_blank');
    }else {
		alert("Please select date");
    }   
}
function Getexcel() { 
	var trxnNo = $('#txttrxnNo').val();
	var Buyerid = $('#buyerid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var pono=$("#txtpono").val();
   var finyear=$("#ddlfinancialyear").val();
    if (Fromdate != "" && Todate != "") {
		 window.open('payment_received_list_excel.php?trxnNo='+trxnNo+'&Buyerid='+Buyerid+"&Fromdate="+Fromdate+ '&Todate='+Todate+'&pono='+pono+'&finyear='+finyear,'_blank');    
    }
    else {
        alert("Please select date");
    }
   
}

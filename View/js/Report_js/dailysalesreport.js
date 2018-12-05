var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";

var PrincipalList = {};
function CallToPrincipal(PrincipalList) {
    'use strict';
    var principalArray = $.map(PrincipalList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-principal').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: principalArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnPrincipal(suggestion.value, suggestion.data);
            //$('#selction-ajax-principal').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-principal').val(hint);
        },
        onInvalidateSelection: function () {
            NonePrincipal();
            //$('#selction-ajax-principal').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "../../Controller/Master_Controller/Principal_Controller.php",
    type: "post",
    data: { TYP: "SELECT", PRINCIPALID: 0 },
    success: function (jsondata) {
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            var obj;
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                PrincipalList[obj._principal_supplier_id] = obj._principal_supplier_name;
            }
            CallToPrincipal(PrincipalList);
        }
    }
});
function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
     //   Search("PRINCIPAL",data);
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
}
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
            //$('#selction-ajax-buyer').html('You selected: none');
        }
    });
}

// function to load all buyer and store in a array BuyerList
/*
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
*/

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



function ActionOnBuyer(value, data) {
    //$("#new_buyer").show();
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        //Search("BUYER",data);
    }
}


function NoneBuyer() {
    $("#buyerid").val(0);
}

function LoadDailySalesReport() {
    //alert('url'+ URL);
    $(".dailysalesreport").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'S. No.', name: 'SN', width: 80, sortable: false, align: 'center' },
        { display: 'Invoice No.', name: 'oinvoice_No', width: 100, sortable: false, align: 'left' },
        { display: 'Invoice Date', name: 'oinv_date', width: 100, sortable: false, align: 'left' },
 		{ display: 'Principal Name', name: 'Principal_Supplier_Name', width: 350, sortable: false, align: 'left' },
 		{ display: 'Buyer Name', name: 'BuyerName', width: 320, sortable: false, align: 'left' },
		{ display: 'Taxable Amount', name: 'taxable_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'CGST Amount', name: 'cgst_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'SGST Amount', name: 'sgst_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'IGST Amount', name: 'igst_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'FREIGHT Amount', name: 'freight_amount', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F Amount', name: 'p_f_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'INSURANCE Amount', name: 'ins_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'INCIDENTAL Amount', name: 'inc_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'OTHER Amount', name: 'other_amt', width: 100, sortable: false, align: 'left'},
		{ display: 'Total Amount', name: 'bill_value', width: 100, sortable: false, align: 'left'}
				
        ],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1280,
        height: 300
    });
}
LoadDailySalesReport();
function Search(type,value) {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var pid = $("#principalid").val();
    var bid = $("#buyerid").val();
    var path = URL + '?TYP=DAILYSALESREPORT'+'&todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value+'&pid='+pid+'&bid='+bid;
    if (Fromdate != "" && Todate != "") {
        $('.dailysalesreport').flexOptions({ url: path });
        $('.dailysalesreport').flexReload();
    }
    else {
        alert("Please select date");
    }
}

function Getpdf() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();  
    var pid = $("#principalid").val();
    var bid = $("#buyerid").val();
    var value = 0;
    var type = "";  
	 if (Fromdate != "" && Todate != "") {
		 window.open('dailysalesreport_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value+'&pid='+pid+'&bid='+bid,'_blank');
    }else {
		alert("Please select date");
    }   
}
function Getexcel() {
   var Fromdate = $("#txtdatefrom").val();
   var Todate = $("#txtdateto").val();  
   var pid = $("#principalid").val();
   var bid = $("#buyerid").val(); 
   var value = 0;
   var type = "";
     if (Fromdate != "" && Todate != "") {
		 window.open('dailysalesreport_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value+'&pid='+pid+'&bid='+bid,'_blank');    
    }
    else {
        alert("Please select date");
    }
   
}

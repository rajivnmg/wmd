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
       // Search("PRINCIPAL",data);
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
}
﻿var BuyerList = {};
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
function ActionOnBuyer(value, data) {
    //$("#new_buyer").show();
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        Search("BUYER",data);
    }
}


function NoneBuyer() {
    $("#buyerid").val(0);
}

// added  to search purchase by codePart 22.12.15.
 function filterPurchaseReportByCodePart(codepart){
	var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();    
    if (Fromdate != "" && Todate != "") {
        if(codepart.length >= 1){ 
			//Search("codepart",codepart);
		}
    }else {
			alert("Please select date");
    }   
}

function selectedItemChanged(value) {
	var Fromdate = $("#txtdatefrom").val();
  	var Todate = $("#txtdateto").val();
    
    if (Fromdate != "" && Todate != "") {
        if (value != "" && value > 0) { 		
			//Search("INDUSTRY",value);
		}
    }else {
			alert("Please select date");
    }   
}
function Loadmarginreport() {
    //alert('url'+ URL);	
	
    $(".purchasereport").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'S. No.', name: 'SN', width: 50, sortable: false, align: 'center' },
                { display: 'Invoice No.', name: 'invoice_No', width: 100, sortable: false, align: 'left' },
                { display: 'Invoice Date', name: 'inv_date', width: 110, sortable: false, align: 'left' },
                { display: 'Principal Name', name: 'Principal_Supplier_Name', width: 350, sortable: false, align: 'left' },
                { display: 'CodePart', name: 'Item_Code_Partno', width: 110, sortable: false, align: 'left' },
		{ display: 'CodePart Desc', name: 'Item_Desc', width: 250, sortable: false, align: 'left' },
		{ display: 'Qty', name: 'qty', width: 50, sortable: false, align: 'left' },
		{ display: 'Basic Price', name: 'unitrate', width: 100, sortable: false, align: 'left'},
                { display: 'Basic Value', name: 'basic_value', width: 100, sortable: false, align: 'left'},
                { display: 'Invoice Value', name: 'bill_value', width: 100, sortable: false, align: 'left'}
        ],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1340,
        height: 300
    });
}
Loadmarginreport();
function Search(type,value) {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var path = URL + '?TYP=PURCHASEREPORT'+'&todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value;
    if (Fromdate != "" && Todate != "") {
        $('.purchasereport').flexOptions({ url: path });
        $('.purchasereport').flexReload();
    }
    else {
        alert("Please select date");
    }
}
$('#txtinvoicenumber').on('keypress', function (e) { 
	var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
	
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) { 
	     e.preventDefault();
        if ($('#txtinvoicenumber').val() != "") {
            var path = URL + '?TYP=PURCHASEREPORT'+'&todate='+Todate+'&fromdate='+Fromdate+'&tag=INVOICENO&value=' + $('#txtinvoicenumber').val();
            $(".purchasereport").flexOptions({ url: path });
            $(".purchasereport").flexReload();
        }
    }
});
function Getpdf() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var pid = $("#principalid").val();
	var invid = $("#txtinvoicenumber").val();
    var value = 0;
    var type = "";
    if(pid > 0)
    {
        type = "PRINCIPAL";
        value = pid;
    }   
    if(invid != "")
    {
        type = "INVOICENO";
        value = invid;
    }
    window.open('purchasereport_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value,'_blank');
    
}
function Getexcel() {
   var Fromdate = $("#txtdatefrom").val();
   var Todate = $("#txtdateto").val();
   var pid = $("#principalid").val();  
   var invid = $("#txtinvoicenumber").val();
   var value = 0;
   var type = "";
    if(pid > 0)
    {
        type = "PRINCIPAL";
        value = pid;
    }
    if(invid != "")
    {
        type = "INVOICENO";
        value = invid;
    }
    window.open('purchasereport_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&tag='+type+'&value='+ value,'_blank');
    
}

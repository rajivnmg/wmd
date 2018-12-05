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
       // SearchByPrincipal();
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
}


// Added on 8 APRIL 2016 to serch by buyer
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
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
}


/////////////////////////////////  on 08-4-2016 by Codefire
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




function LoadOutgoingInvoiceExcise() {
    //alert('url'+ URL);
    $(".outgoing_invoice_excise_list").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'S. No.', name: 'SN', width: 80, sortable: false, align: 'center' },
                { display: 'Invoice No.', name: 'oinvoice_No', width: 100, sortable: false, align: 'left' },
                { display: 'Invoice Date', name: 'oinv_date', width: 100, sortable: false, align: 'left' },
                { display: 'Buyer Code', name: 'BuyerCode', width: 120, sortable: false, align: 'left' },
                { display: 'Buyer Name', name: 'BuyerName', width: 250, sortable: false, align: 'left' },
                { display: 'Part No', name: 'Item_Code_Partno', width: 100, sortable: false, align: 'left' },
                { display: 'Product Description', name: 'Item_Desc', width: 340, sortable: false, align: 'left'},
                { display: 'Quantity', name: 'issued_qty', width: 80, sortable: false, align: 'left' },
                { display: 'Unit', name: 'UNITNAME', width: 70, sortable: false, align: 'left' },
                { display: 'Price', name: 'po_price', width: 110, sortable: false, align: 'left' },
				{ display: 'Total Value', name: 'totalprice', width: 110, sortable: false, align: 'left'},
                { display: 'Market Segment', name: 'marketsegment', width: 70, sortable: false, align: 'left'}],
        // buttons: [{name: 'New',bclass: 'new',onpress: NewGroup},
        //         {name: 'Edit',bclass: 'edit',onpress: EditItem},
        //      {separator: true}],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1290,
        height: 300
    });
}
LoadOutgoingInvoiceExcise();

/* // Commented on 15-March2016 due to remove autofilter and add "Get Records" button
function SearchByPrincipal() {
    var Principalid = $('#principalid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var rpttype = $("#rpttype").val();
    var path = URL + '?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&type=Principal&value='+ Principalid;
    if (Fromdate != "" && Todate != "") {
        $('.outgoing_invoice_excise_list').flexOptions({ url: path });
        $('.outgoing_invoice_excise_list').flexReload();
    }
    else {
        alert("Please select date");
    }
    
} */
function SearchSecondarySalesStatement() {
    var Principalid = $('#principalid').val();
    var buyerid  = $("#buyerid").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var marketsegment = $("#marketsegment").val();
    var finyear = $("#ddlfinancialyear").val();
   
    var rpttype = $("#rpttype").val();
    var path = URL + '?TYP='+rpttype+'&finyear='+finyear+'&todate='+Todate+'&fromdate='+Fromdate+'&Principal='+ Principalid+'&buyerid='+ buyerid+"&marketsegment="+marketsegment;
    if(finyear == null || finyear == ''){
		alert("Please select Financial Year");
    }else if (Fromdate != "" && Todate != "") {
        $('.outgoing_invoice_excise_list').flexOptions({ url: path });
        $('.outgoing_invoice_excise_list').flexReload();
    }
    else {
        alert("Please select date");
    }
    
} 



function getpdf()
{
    var Principalid = $('#principalid').val();
    var buyerid  = $("#buyerid").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var marketsegment = $("#marketsegment").val();
    var finyear = $("#ddlfinancialyear").val();   
    var rpttype = $("#rpttype").val();
    
    if(finyear == null || finyear == ''){
		alert("Please select Financial Year");
    }else if (Fromdate != "" && Todate != "") {
       if (rpttype == "EXCISESECONDARYSALSESTATEMENT") {
			window.open('pdf_secondarysalsestatementexcise.php?finyear='+finyear+'&todate='+Todate+'&fromdate='+Fromdate+'&Principal='+ Principalid+'&buyerid='+ buyerid+"&marketsegment="+marketsegment, '_blank');
		}
		else if (rpttype == "NONEXCISESECONDARYSALSESTATEMENT"){
			window.open('pdf_secondarysalsestatementnonexcise.php?finyear='+finyear+'&todate='+Todate+'&fromdate='+Fromdate+'&Principal='+ Principalid+'&buyerid='+ buyerid+"&marketsegment="+marketsegment, '_blank');
		}
    }
    else {
       alert("Please select date");
    }
    
    
}
function getexcel()
{
	var Principalid = $('#principalid').val();
	var buyerid  = $("#buyerid").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var marketsegment = $("#marketsegment").val();
    var finyear = $("#ddlfinancialyear").val();   
    var rpttype = $("#rpttype").val();
    
    if(finyear == null || finyear == ''){
		alert("Please select Financial Year");
    }else if (Fromdate != "" && Todate != "") {
        if (rpttype == "EXCISESECONDARYSALSESTATEMENT") {
			window.open('excel_secondarysalsestatementexcise.php?finyear='+finyear+'&todate='+Todate+'&fromdate='+Fromdate+'&Principal='+ Principalid+'&buyerid='+ buyerid+"&marketsegment="+marketsegment, '_blank');
		}else if (rpttype == "NONEXCISESECONDARYSALSESTATEMENT"){
			window.open('excel_secondarysalsestatementnonexcise.php?finyear='+finyear+'&todate='+Todate+'&fromdate='+Fromdate+'&Principal='+ Principalid+'&buyerid='+ buyerid+"&marketsegment="+marketsegment, '_blank');
		}
    }
    else {
        alert("Please select date");
    }
    
   
}

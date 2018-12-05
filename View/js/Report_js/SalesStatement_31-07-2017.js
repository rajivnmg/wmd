var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";

var PrincipalList = {};
var ItemList = {};
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
    ItemList = {};
    if (value != "" && data > 0) {
        $("#principalid").val(data);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Item_Controller.php",
            type: "post",
            data: { TYP: "SELECT", PRINCIPALID: data },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                ItemList = {};
                    for (var items in objs) {
                        ItemList[items] = objs[items];

                    }
                    CallToItem(ItemList);
                }
            }
        });
     //   Search("Principal",data);
        //Getpdf("Principal",data);
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
    ItemList = {};
}
function CallToItem(ItemList) {
    'use strict';
    var itemArray = $.map(ItemList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-codepart').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: itemArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnItem(suggestion.value, suggestion.data);
            //$('#selction-ajax-supplier').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-supplier').val(hint);
        },
        onInvalidateSelection: function () {
            NoneItem();
            // $('#selction-ajax-supplier').html('You selected: none');
        }
    });
}
function ActionOnItem(value, data) {
    if (value != "" && data > 0) {
        $("#itemid").val(data);
      //  Search("Codepart",data);
        //Getpdf("Codepart",data);
    }
    else {
    }
}
function NoneItem() {
    $("#itemid").val(0);
}
function Salse()
{
   // Search("Salse",$("#salseuser").val());
    //Getpdf("Salse",$("#salseuser").val());
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
                { display: 'Unit', name: 'UNITNAME', width: 80, sortable: false, align: 'left' },
                { display: 'Price', name: 'po_price', width: 110, sortable: false, align: 'left' },
                { display: 'Total Value', name: 'totalprice', width: 110, sortable: false, align: 'left'},
                { display: 'Executive', name: 'salesExecutive', width: 100, sortable: false, align: 'left' },
                { display: 'Market Segment', name: 'marketsegment', width: 70, sortable: false, align: 'left' }],
        // buttons: [{name: 'New',bclass: 'new',onpress: NewGroup},
        // {name: 'Edit',bclass: 'edit',onpress: EditItem},
        // {separator: true}],
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
/*
 * // commented on 15 March 2016 Due to remove auto filter
 * function Search(type,value) {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var rpttype = $("#rpttype").val();
    var path = URL + '?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&type='+type+'&value='+ value;
    if (Fromdate != "" && Todate != "") {
        $('.outgoing_invoice_excise_list').flexOptions({ url: path });
        $('.outgoing_invoice_excise_list').flexReload();
    }
    else {
        alert("Please select date");
    }
    
}*/

// Added on 15 March 2016 Due to remove auto filter
function SearchSalesStatement() {
    var rpttype = $("#rpttype").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var finyear = $("#ddlfinancialyear").val();
  	var principalid =  $("#principalid").val();
  	var buyerid  = $("#buyerid").val();
    var itemid = $("#itemid").val();    
    var salseuser = $("#salseuser").val();
    var marketsegment = $("#marketsegment").val();
    var path = URL + '?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear+'&principalid='+ principalid+'&buyerid='+ buyerid+"&itemid="+itemid+"&salseuser="+salseuser+"&marketsegment="+marketsegment;
    if (Fromdate != "" && Todate != "") {
        $('.outgoing_invoice_excise_list').flexOptions({ url: path });
        $('.outgoing_invoice_excise_list').flexReload();
    }
    else {
        alert("Please select date");
    }
    
}

function Getpdf() {
	var rpttype = $("#rpttype").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var finyear = $("#ddlfinancialyear").val();
  	var principalid =  $("#principalid").val();
  	var buyerid  = $("#buyerid").val();
    var itemid = $("#itemid").val();    
    var salseuser = $("#salseuser").val();
    var marketsegment = $("#marketsegment").val();
    
    if(finyear == null || finyear == ''){
		alert("Please select Financial Year");
    }else if (Fromdate != "" && Todate != "") {
       if (rpttype == "EXCISESECONDARYSALSE") {
			window.open('pdf_secondarysalseexcise.php?todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear+'&principalid='+ principalid+'&buyerid='+ buyerid+"&itemid="+itemid+"&salseuser="+salseuser+"&marketsegment="+marketsegment, '_blank');
		}
		else if (rpttype == "NONEXCISESECONDARYSALSE"){
			window.open('pdf_secondarysalsenonexcise.php?todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear+'&principalid='+ principalid+'&buyerid='+ buyerid+"&itemid="+itemid+"&salseuser="+salseuser+"&marketsegment="+marketsegment, '_blank');
		}
    }
    else {
       alert("Please select date");
    }
      
}
function getexcel() {
    var rpttype = $("#rpttype").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var finyear = $("#ddlfinancialyear").val();
  	var principalid =  $("#principalid").val();
  	var buyerid  = $("#buyerid").val();
    var itemid = $("#itemid").val();    
    var salseuser = $("#salseuser").val();
    var marketsegment = $("#marketsegment").val();
	
	if(finyear == null || finyear == ''){
		alert("Please select Financial Year");
    }else if (Fromdate != "" && Todate != "") {
       //if (rpttype == "EXCISESECONDARYSALSE") {
			window.open('excel_secondarysalseexcise.php?todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear+'&principalid='+ principalid+'&buyerid='+ buyerid+"&itemid="+itemid+"&salseuser="+salseuser+"&marketsegment="+marketsegment, '_blank');
		/* }
		else if (rpttype == "NONEXCISESECONDARYSALSE"){
			window.open('excel_secondarysalsenonexcise.php?todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear+'&principalid='+ principalid+'&buyerid='+ buyerid+"&itemid="+itemid+"&salseuser="+salseuser+"&marketsegment="+marketsegment, '_blank');
		} */
    }
    else {
       alert("Please select date");
    }
	   
}

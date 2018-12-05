var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";

var PrincipalList = {};
var ItemList = {};

function test_principal(){	
	var p = $("#principalid").val();
	 if(p=='' || p=="NULL" || p==0){
	  alert('Please select Principal');
	  return false;
	 }
}
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
           // $('#selction-ajax-principal').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
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
      // commented by rajiv on 04-09-15 for required principal id 
	  //  Search("Principal",data);
	  
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
       // Search("Codepart",data);
    }
    else {
    }
}
function NoneItem() {
    $("#itemid").val(0);
}
function LoadOutgoingInvoiceExcise() {
    //alert('url'+ URL);
    $(".outgoing_invoice_excise_list").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'S. No.', name: 'SN', width: 100, sortable: false, align: 'center' },
                { display: 'Invoice No.', name: 'invno', width: 200, sortable: false, align: 'left' },
                { display: 'Invoice Date', name: 'invdate', width: 100, sortable: false, align: 'left' },
                { display: 'Principal/Buyer Name', name: 'name', width: 350, sortable: false, align: 'left' },
                { display: 'Incomming Qty.', name: 'incomingqty', width: 150, sortable: false, align: 'left' },
                { display: 'Outgoing Qty.', name: 'outgoingqty', width: 140, sortable: false, align: 'left' },
                { display: 'Balance Qty.', name: 'balanceqty', width: 140, sortable: false, align: 'left'},
				{ display: 'History.', name: 'history', width: 100, sortable: false, align: 'left'}],
					
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

function Search(type,value) {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();    
    var finyear = $("#ddlfinancialyear").val(); 
    var itemid = $("#itemid").val();
    var principalid = $("#principalid").val();   
    var rpttype = $("#rpttype").val();
    var path = URL + '?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&principalid='+principalid+'&itemid='+itemid+"&finyear="+finyear;
    if (finyear.length== 0) {
		
		alert("Please select Year");
       
    }else if (Fromdate == "" && Todate == "") {
		alert("Please select date");
	}else if (principalid == "" || principalid == 0) {
		alert("Please select Principal");
	}else if (itemid == "" || itemid == 0) {
		alert("Please select Item");
	}
    else {
         $('.outgoing_invoice_excise_list').flexOptions({ url: path });
         $('.outgoing_invoice_excise_list').flexReload();
    }
    
}

function showHistory(id,type,codepart,invoice,currentqty,tranctionType){ 
	$('#invoiceHistory').modal('show');
	 var invtype;
	if(type == 'IE'){
	 invtype = 'Incomming Excise';
	}else if(type=='OE'){
	  invtype = 'Outgoing Excise';
	}else if(type=='ONE'){
		invtype = 'Outgoing Non-Excise';
	}else if(type=='InNonEx'){
		invtype = 'Incoming Non-Excise';
	}else if(type=='Stock'){
		invtype = 'Stock Transfer';
	}
	$('.modal-title').text('Invoice History : '+invtype+'->'+invoice+'->'+currentqty);
	 var path = URL + '?id='+id+'&type='+type+'&codepart='+codepart+'&tranctionType'+tranctionType;
	jQuery.ajax({
            url: path,
            type: "POST",
            data: { TYP: "INVOICEHISTORY" },
			//beforeSend: function() { jQuery('#waitPo').css("display","block");},
			//complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
                //var objs = jQuery.parseJSON(jsondata);
				//alert(objs);
				$('#invoiceHistories').html(jsondata);
               // $scope.$apply(function () {
                    //$scope.dashboard._items = objs;
               // });
            }
        }); 

}

function Getpdf() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();    
    var finyear = $("#ddlfinancialyear").val(); 
    var itemid = $("#itemid").val();  
    var principalid = $("#principalid").val();   
    var rpttype = $("#rpttype").val();
    var type = "";
    if (finyear.length== 0) {
		
		alert("Please select Year");
       
    }else if (Fromdate == "" && Todate == "") {
		alert("Please select date");
	}else if (principalid == "" || principalid == 0) {
		alert("Please select Principal");
	}else if (itemid == "" || itemid == 0) {
		alert("Please select Item");
	}
    else {   
    
		if (rpttype == "EXCISEProductLedger") {
			window.open('ProductLedgerExcise_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&principalid='+principalid+'&itemid='+itemid+"&finyear="+finyear,'_blank');
		}
		else if (rpttype == "NONEXCISEProductLedger"){
			window.open('ProductLedgerNonExcise_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&principalid='+principalid+'&itemid='+itemid+"&finyear="+finyear,'_blank');
		}
	}
    
}
function Getexcel(){
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();    
    var finyear = $("#ddlfinancialyear").val(); 
    var itemid = $("#itemid").val();  
    var principalid = $("#principalid").val();   
    var rpttype = $("#rpttype").val();
    var type = "";
    if (finyear.length== 0) {
		
		alert("Please select Year");
       
    }else if (Fromdate == "" && Todate == "") {
		alert("Please select date");
	}else if (principalid == "" || principalid == 0) {
		alert("Please select Principal");
	}else if (itemid == "" || itemid == 0) {
		alert("Please select Item");
	}
    else {   
    
			if (rpttype == "EXCISEProductLedger") {
				window.open('ProductLedgerExcise_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&principalid='+principalid+'&itemid='+itemid+"&finyear="+finyear,'_blank');
			}
			else if (rpttype == "NONEXCISEProductLedger"){
				window.open('ProductLedgerNonExcise_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&principalid='+principalid+'&itemid='+itemid+"&finyear="+finyear,'_blank');
			}
    }
}

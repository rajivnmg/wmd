var URL = "../../Controller/Business_Action_Controller/Quation_Controller.php";
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
$(document).ready(function (){
// commented due to page loading performance on 25-11-2015 by Codefire
/*     jQuery.ajax({
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
}); */
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
});

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
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        SearchByBuyer(data);
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
}
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

function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
        SearchByPrincipal(data);
    }
}
function NonePrincipal() {
$("#principalid").val(0);
}
function LoadQuation() {
//alert('url'+ URL);
    $(".quotation_list").flexigrid({
        url: "../../Controller/Business_Action_Controller/Quation_Controller.php?TYP=",
        dataType: 'json',
        colModel: [{ display: 'Quation ID', name: 'quotId', width: 100, sortable: true, align: 'center',process: procMe  },
                { display: 'Quation Number', name: 'quotNo', width: 150, sortable: true, align: 'left',  },
                { display: 'Quotation Date', name: 'quotDate', width: 130, sortable: true, align: 'left' },
                { display: 'Principal Name', name: 'Principal_Supplier_Name', width: 300, sortable: true, align: 'left' },
                { display: 'Customer Name', name: 'BuyerName', width: 300, sortable: false, align: 'left' },
                { display: 'Contact Person', name: 'contact_person', width: 280, sortable: true, align: 'left'}],
       // buttons: [{name: 'New',bclass: 'new',onpress: NewGroup},
       //  {name: 'Edit',bclass: 'edit',onpress: EditItem},
		// {separator: true}],
        sortorder: "Desc",
        usepager: true,
        title: 'Quation Master',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1280,
        height: 320
    });
}
function procMe(celDiv, id) {
    $(celDiv).click(function () {
        var QuotationNumber = celDiv.innerText;
        var path = 'quotation.php?TYP=SELECT&QUOTATIONNUMBER=' + QuotationNumber;
        //alert(path);
        window.location.href = path;
    });
}
LoadQuation();
function SearchByPrincipal(Principalid){ 
var path = URL + '?TYP=SEARCH&coulam=Principal&val1='+Principalid+"&val2=&val3=&val4=";
$(".quotation_list").flexOptions({ url: path });
$(".quotation_list").flexReload();
}
function SearchByBuyer(Buyerid){
var path = URL + '?TYP=SEARCH&coulam=Buyer&val1='+Buyerid+"&val2=&val3=&val4=";
$(".quotation_list").flexOptions({ url: path });
$(".quotation_list").flexReload();
}

// commented on 23-12-2015 due 
/* function SearchQuotation(){
var Fromdate = $("#txtdatefrom").val();
var Todate = $("#txtdateto").val();
var buyerId = $("#buyerid").val();
var principalId = $("#principalid").val();
var quotno = $("#quotno").val();

var path = URL + '?TYP=';
if(Fromdate != "" && Todate != "" && quotno == ""){ 
		if(buyerId > 0 && principalId > 0){
		   path = URL + '?TYP=SEARCH&coulam=Principal_WITH_Buyer_WITH_DATE&val1='+buyerId+'&val2='+principalId+'&val3='+Fromdate+'&val4='+Todate;
		}else if(buyerId > 0 && principalId == ""){
			path = URL + '?TYP=SEARCH&coulam=Buyer_WITH_DATE&val1='+Fromdate+'&val2='+Todate+'&val3='+buyerId+'&val4=';
		}else if(principalId > 0 && buyerId == ""){ 
			path = URL + '?TYP=SEARCH&coulam=Principal_WITH_DATE&val1='+Fromdate+'&val2='+Todate+'&val3='+principalId+'&val4=';
		}else{
			path = URL + '?TYP=SEARCH&coulam=Date&val1='+Fromdate+'&val2='+Todate+'&val3=&val4=';
		}
	}else if(buyerId > 0 && principalId > 0){
		path = URL + '?TYP=SEARCH&coulam=Principal_WITH_Buyer&val1='+buyerId+'&val2='+principalId+"&val3=&val4=";
	}else if(quotno != ""){ 
			path = URL + '?TYP=SEARCH&coulam=QuationNo&val1='+quotno+'&val2=&val3=&val4=';
	}else if(quotno != "" && Fromdate != "" && Todate != ""){
		path = URL + '?TYP=SEARCH&coulam=QuationNo&val1='+quotno+'&val2=&val3=&val4=';
	}
$(".quotation_list").flexOptions({ url: path });
$(".quotation_list").flexReload();
} 
 */
 
function SearchQuotation(){
var Fromdate = $("#txtdatefrom").val();
var Todate = $("#txtdateto").val();
var buyer = $("#buyerid").val();
var principal = $("#principalid").val();
var quotno = $("#quotno").val();
var quotStatus = $("#quotationStatus").val();
var executive = $("#salseuser").val();
var path = URL + '?TYP=';
if(Fromdate != "" && Todate != "" && quotno ==""){		
		path = URL + '?TYP=SEARCHQUOTATION&Fromdate='+Fromdate+'&Todate='+Todate+'&buyerid='+buyer+'&principalid='+principal+'&quotno='+quotno+'&quotStatus='+quotStatus+'&executive='+executive;	
	}else if(quotno !=""){
		path = URL + '?TYP=SEARCHQUOTATION&Fromdate='+Fromdate+'&Todate='+Todate+'&buyerid='+buyer+'&principalid='+principal+'&quotno='+quotno+'&quotStatus='+quotStatus+'&executive='+executive;	
	}else{
		alert("Please Select Date Range!"); return;
	}
$(".quotation_list").flexOptions({ url: path });
$(".quotation_list").flexReload();
} 
 
//function to download Quotation As PDF
function Getpdf() {
var Fromdate = $("#txtdatefrom").val();
var Todate = $("#txtdateto").val();
var buyerId = $("#buyerid").val();
var principalId = $("#principalid").val();
var quotno = $("#quotno").val();
    if(Fromdate != "" && Todate != "") {
    window.open('QuotationView_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&buyerId='+buyerId+'&principalId='+ principalId+'&quotno'+quotno,'_blank');
     }
    else {
        alert("Please select date"); return false;
    }

}
//function to download Quotation As Excel
function Getexcel() {
var Fromdate = $("#txtdatefrom").val();
var Todate = $("#txtdateto").val();
var buyerId = $("#buyerid").val();
var principalId = $("#principalid").val();
var quotno = $("#quotno").val();
 if(Fromdate != "" && Todate != "") {
    window.open('QuotationView_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&buyerId='+buyerId+'&principalId='+ principalId+'&quotno'+quotno,'_blank');
	 }
    else {
        alert("Please select date"); return false;
    }

    
}


// List of purchase order related to Quotation
function quotationPurchaseOrders(quotId,quotNo){	
	$('#myQuotDetails').modal('show');
	
	jQuery.ajax({
            //url: "../../Controller/Dashboard/Dashboard_Controller.php?POID="+bpoId+"&BPONO="+bpono,
			url: "polist.php?quotId="+quotId+"&quotNo="+quotNo,
            type: "POST",
            data: { TYP: "QUOTATION_PO_LIST" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
                //var objs = jQuery.parseJSON(jsondata);
				//alert(objs);
				$('#polist').html(jsondata);
               // $scope.$apply(function () {
                    //$scope.dashboard._items = objs;
               // });
            }
        });

}












var PO_Reports_app= angular.module('PO_Reports_app', []);
// create angular controller

PO_Reports_app.controller('PO_Reports_Controller', function ($scope) {
  
    $scope.SearchPo = function (repType,executiveId) {      
		
        $scope.PO_Reports.FromDate = $("#txtdatefrom_po").val();
        $scope.PO_Reports.ToDate = $("#txtdateto_po").val();
        $scope.PO_Reports.PoVD = $("#txtpovaliditydate").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
		$scope.PO_Reports.ponumber = $("#ponumber").val();
        $scope.PO_Reports.PoType = $("#potype").val();
		$scope.PO_Reports.principalid = $("#principalid").val();
		$scope.PO_Reports.Mode = $("#Mode").val();
		$scope.PO_Reports.Status = $("#Status").val();
		$scope.PO_Reports.marketsegment = $("#marketsegment").val();
        $scope.PO_Reports.repType =repType;
        $scope.PO_Reports.executiveId =executiveId;           
        json_string = JSON.stringify($scope.PO_Reports);       
        LoadChallanData(json_string);      
		//alert(json_string);
        
    }
    
     $scope.SearchPendingPayment = function (executiveId) {
        $scope.PO_Reports.FromDate = $("#txtdatefrom_po").val();
        $scope.PO_Reports.ToDate = $("#txtdateto_po").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.InvoiceType = $("#invoicetype").val();
        $scope.PO_Reports.invoice_no=$("#invoiceno").val();
        $scope.PO_Reports.executiveId =executiveId; 
               
        json_string = JSON.stringify($scope.PO_Reports);       
        LoadInvoiceData(json_string);
        
    }
     $scope.SearchBuyerPendingPayment=function (executiveId) {    
        $scope.PO_Reports.FromDate = $("#txtdatefrom_po").val();
        $scope.PO_Reports.ToDate = $("#txtdateto_po").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.InvoiceType = $("#invoicetype").val();
 
        $scope.PO_Reports.executiveId =executiveId; 
               
        json_string = JSON.stringify($scope.PO_Reports);       
         LoadBuyerPaymentPendingData(json_string);
        
    }
    
    $scope.SearchBuyerWiseRevenue=function (executiveId) {    
        $scope.PO_Reports.FromDate = $("#txtdatefrom_po").val();
        $scope.PO_Reports.ToDate = $("#txtdateto_po").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.PoType = $("#potype").val();
 
        $scope.PO_Reports.executiveId =executiveId; 
               
        json_string = JSON.stringify($scope.PO_Reports);       
         LoadBuyerWiseRevenueData(json_string);
        
    }
    $scope.SearchFinancialYearBuyerRevenue=function(executiveId)
    {
	  
        $scope.PO_Reports.FnYear= $("#financialyear").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.PoType = $("#potype").val();
 
        $scope.PO_Reports.executiveId =executiveId; 
               
        json_string = JSON.stringify($scope.PO_Reports);       
         LoadFinancialYearWiseBuyerRevenueData(json_string);
	}
} );

function LoadFinancialYearWiseBuyerRevenueData()
{
	var URL="../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCHFINYEARWISEBUYERREVEUE&SearchData="+json_string;

	 $(".polist").flexigrid({
        url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:80, sortable: true, align: 'left'},
                   { display: 'Buyer Name', name: 'BuyerName', width:350, sortable: true, align: 'left' },
                   { display: 'No Of Orders', name: 'no_of_po', width:150, sortable: true, align: 'right' },
                   { display: 'PO Type', name: 'bpoType', width:150, sortable: true, align: 'left' },
                   { display: 'Executive', name: 'executiveId', width:150, sortable: true, align: 'left'},
                   { display: 'Financial Year', name: 'finyear', width:150, sortable: true, align: 'left'},
                   { display: 'Revenue(Amount)', name: 'po_val', width:150, sortable: true, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1240,
        height: 400

    });
    
    $(".polist").flexOptions({ url: URL });
    $(".polist").flexReload();
}
function LoadBuyerWiseRevenueData()
{
var URL="../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCHBUYERWISEREVEUE&SearchData="+json_string;

	 $(".polist").flexigrid({
        url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:80, sortable: false, align: 'left'},
                   { display: 'Buyer Name', name: 'BuyerName', width:550, sortable: false, align: 'left' },
                   { display: 'No Of Orders', name: 'no_of_po', width:150, sortable: false, align: 'right' },
                   { display: 'PO Type', name: 'bpoType', width:150, sortable: false, align: 'left' },
                   { display: 'Executive.', name: 'executiveId', width:150, sortable: false, align: 'left'},
                   { display: 'Revenue(Amount)', name: 'po_val', width:150, sortable: false, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1240,
        height: 400

    });
    
    $(".polist").flexOptions({ url: URL });
    $(".polist").flexReload();	
}
function LoadBuyerPaymentPendingData()
{
var URL="../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCHBUYERINVOICE&SearchData="+json_string;

	 $(".polist").flexigrid({
        url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:100, sortable: false, align: 'left'},
                   { display: 'Buyer Name', name: 'BuyerName', width:480, sortable: false, align: 'left' },
                   { display: 'Bill Amt.', name: 'invoiceAmount', width:250, sortable: false, align: 'right' },
                   { display: 'Executive', name: 'executiveId', width:250, sortable: false, align: 'left' },
                   { display: 'Due Amt.', name: 'dueAmount', width:150, sortable: false, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 15,
        showTableToggleBtn: false,
        width: 1240,
        height: 400

    });
    
    $(".polist").flexOptions({ url: URL });
    $(".polist").flexReload();	
}
function LoadInvoiceData()
{
	 var URL="../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCHINVOICE&SearchData="+json_string;

	 $(".polist").flexigrid({
        url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:50, sortable: true, align: 'left'},
                     { display: 'Invoice Number', name: 'invoiceNo', width:130, sortable: true, align: 'left' },
                             { display: 'Invoice Date', name: 'invoiceDate', width:150, sortable: true, align: 'center' },
                             { display: 'Due Date', name: 'dueDate', width:130, sortable: true, align: 'center' },
							 { display: 'Day', name: 'day', width:100, sortable: true, align: 'center' },
                             { display: 'Invoice Amt.', name: 'invoiceAmount', width: 150, sortable: true, align: 'right' },
                              { display: 'Buyer Name', name: 'BuyerName', width:280, sortable: true, align: 'left' },
                             { display: 'Executive', name: 'executiveId', width:120, sortable: true, align: 'left' },
                           { display: 'Due Amt.', name: 'dueAmount', width:150, sortable: true, align: 'right'}],
       sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1240,
        height: 400

    });
    
    $(".polist").flexOptions({ url: URL });
    $(".polist").flexReload();
}

function LoadChallanData() {
        //alert(json_string);
       
    $(".polist").flexigrid({
        url: '../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCH&SearchData='+json_string,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:50, sortable: true, align: 'left'},
					{ display: 'POID', name: 'bpoId', width:130, sortable: true, align: 'left',process: procMe},
                     { display: 'Order Number', name: 'bpono', width:130, sortable: true, align: 'left' },
                             { display: 'Order Date', name: 'bpoDate', width:150, sortable: true, align: 'left' },
                             { display: 'Validity Date', name: 'bpoVDate', width:150, sortable: true, align: 'left' },
                             { display: 'BuyerName', name: 'BuyerName', width: 350, sortable: true, align: 'left' },
                              { display: 'Sales Executive', name: 'executiveId', width:150, sortable: true, align: 'left' },
                             { display: 'PO Type', name: 'bpoType', width: 100, sortable: true, align: 'left' },
                           { display: 'PO Amount', name: 'po_val', width:150, sortable: true, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1240,
        height: 400
		
    });
    var path = '../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCH&SearchData='+json_string;
        $(".polist").flexOptions({ url: path });
        $(".polist").flexReload();
		
}
function procMe(celDiv, id) {
    $(celDiv).click(function () {
        var PONumber = celDiv.innerText;
        var path = '../Business_View/purchaseorder.php?POID=' + PONumber;
        //alert(path);
        window.location.href = path;
    });
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
    $("#buyerid").val('');
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

$(document).ready(function (){
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

function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
    }
}
function NonePrincipal() {
$("#principalid").val(0);
}

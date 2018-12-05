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
      var usertype = $("#usertype").val();
      if(usertype=='B'){
      	  $scope.PO_Reports.executiveId = $("#salseuser").val();
      }else{
      	 $scope.PO_Reports.executiveId =executiveId; 
      }
     
      $scope.PO_Reports.finYer =$("#cfinancialyear").val();
      json_string = JSON.stringify($scope.PO_Reports); 
    //  alert(json_string);
      LoadChallanData(json_string);      
		//alert(json_string);
        
    }
    
     $scope.SearchPendingPayment = function (executiveId) {
        $scope.PO_Reports.FromDate = $("#txtdatefrom_po").val();
        $scope.PO_Reports.ToDate = $("#txtdateto_po").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.InvoiceType = $("#invoicetype").val();
        $scope.PO_Reports.invoice_no=$("#invoiceno").val();
		$scope.PO_Reports.finyear=$("#ddlfinancialyear").val();	
		var utype = $("#usertype").val();
		if(utype == 'E'){
			 $scope.PO_Reports.executiveId =executiveId;
		}else{
			 
			 $scope.PO_Reports.executiveId =$("#salseuser").val();
		}
           
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
        $scope.PO_Reports.FromDate = $("#txtdatefrom").val();
        $scope.PO_Reports.ToDate = $("#txtdateto").val();
        $scope.PO_Reports.BuyerId = $("#buyerid").val();
        $scope.PO_Reports.PoType = $("#potype").val();
	    $scope.PO_Reports.principalId = $("#principalid").val();
	    $scope.PO_Reports.locationid = $("#location").val();
		var utype = $("#usertype").val();
		if(utype == 'E'){
			 $scope.PO_Reports.executiveId =executiveId;
		}else{
			 
			 $scope.PO_Reports.executiveId =$("#salseuser").val();
		}
		
		
       // $scope.PO_Reports.executiveId =executiveId; 
               
        json_string = JSON.stringify($scope.PO_Reports);  
       // alert(json_string);    
        LoadFinancialYearWiseBuyerRevenueData(json_string);
	}
} );

function LoadFinancialYearWiseBuyerRevenueData(json_string)
{
	var URL="../../Controller/Business_Action_Controller/po_reports_controller.php?TYP=SEARCHFINYEARWISEBUYERREVEUE&SearchData="+json_string;

	 $(".polist").flexigrid({
        url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'sno', width:80, sortable: true, align: 'left'},
				   { display: 'Principal/Supplier Name', name: 'principalName', width:300, sortable: true, align: 'left' },
                   { display: 'Buyer Name', name: 'BuyerName', width:300, sortable: true, align: 'left' },
                   { display: 'Location Name', name: 'locationName', width:120, sortable: true, align: 'left' },
                   { display: 'No Of Orders', name: 'no_of_po', width:100, sortable: true, align: 'right' },
                   { display: 'PO Type', name: 'bpoType', width:90, sortable: true, align: 'left' },
                   { display: 'Executive', name: 'executiveId', width:100, sortable: true, align: 'left'},
                   { display: 'Financial Year', name: 'finyear', width:100, sortable: true, align: 'left'},
                   { display: 'Revenue(Amount)', name: 'po_val', width:140, sortable: true, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1260,
        height: 380

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
        colModel: [{ display: 'S.NO.', name: 'sno', width:80, sortable: true, align: 'left'},
                	{ display: 'Invoice Number', name: 'invoiceNo', width:130, sortable: true, align: 'left' },
                        { display: 'Invoice Date', name: 'invoiceDate', width:100, sortable: true, align: 'center' },
                        { display: 'Due Date', name: 'dueDate', width:110, sortable: true, align: 'center' },
			{ display: 'Day', name: 'day', width:100, sortable: false, align: 'center' },
                        { display: 'Invoice Amt.', name: 'invoiceAmount', width: 120, sortable: true, align: 'right' },
                        { display: 'Buyer Name', name: 'BuyerName', width:340, sortable: true, align: 'left' },
                        { display: 'Executive', name: 'executiveId', width:120, sortable: true, align: 'left' },
                        { display: 'Due Amt.', name: 'dueAmount', width:120, sortable: true, align: 'right'}],
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
        colModel: [{ display: 'POID', name: 'bpoId', width:80, sortable: true, align: 'left',process: procMe},
               { display: 'Order Number', name: 'bpono', width:180, sortable: true, align: 'left', process: function (col, id)	{ col.innerHTML = "<a href='javascript:showPoDetails(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
               { display: 'Order Date', name: 'bpoDate', width:100, sortable: true, align: 'left' },
               { display: 'Validity Date', name: 'bpoVDate', width:100, sortable: true, align: 'left' },
               { display: 'BuyerName', name: 'BuyerName', width: 300, sortable: true, align: 'left' },
               { display: 'Sales Executive', name: 'executiveId', width:100, sortable: true, align: 'left' },
               { display: 'PO Type', name: 'bpoType', width: 100, sortable: true, align: 'left' },
               { display: 'Po Status', name: 'po_status', width: 70, sortable: true ,align: 'left',process: function (col, id)	{ col.innerHTML = "<a href='javascript:changePoStatusToClose(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
			   { display: 'PO State', name: 'po_state', width: 80, sortable: true ,align: 'left', process: function (col, id){ col.innerHTML = "<a href='javascript:poStateChange(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; }},
			   { display: 'Stock Availability', name: 'stockAvailabe', width: 75, sortable: true, align: 'left',process: function (col, id){ col.innerHTML = "<a href='javascript:showPoStockAvailability(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
			   { display: 'Pick List', name: 'picklist', width: 100, sortable: false, align: 'left' },
		       { display: 'PO Amount', name: 'po_val', width:100, sortable: true, align: 'right'}],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1260,
        height: 350
		
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

function showPoDetails(celDiv){	
	
	var bpoId = $("#row" + celDiv).children ("td:first").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	$("#bname").text(bname);
	$('#myPoDetails').modal('show');
	
	jQuery.ajax({         
			url: "../home/postatus.php?POID="+bpoId+"&BPONO="+bpono,
         type: "POST",
         data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
           	$('#podetails').html(jsondata);
               
            }
        });

}

function poStateChange(celDiv){	
	
	var bpoId = $("#row" + celDiv).children ("td:first").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	var postate = $("#row" + celDiv).children ("td:eq(6)").children ("div").text();
		
	$("#ponum").text(bpono);
	$("#pstate").text(postate);
	$("#pobname").text(bname);
	$('#myPoState').modal('show');	
	jQuery.ajax({         
			url: "../home/postate.php?POID="+bpoId+"&BPONO="+bpono+"&POSTATE="+postate,
         type: "POST",
         data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
         success: function (jsondata) {
              	$('#poState').html(jsondata);
               
            }
        });

}

function changePoState(poid){	
var ponumber = $("#ponumber").val();
var postatetype = $("#postatetype").val();
var poholdreason = $("#po_hold_reason").val();
	
	if(postatetype == 'U' && poholdreason ==''){
		alert('Please Write Reason For Hold');
		return;
	}	
	if($('#poholdstate').is(":checked")) {
	 	postatetype='H';
		
	} else {
		postatetype='U';
	  
	}

	jQuery.ajax({         
	    url: "../home/postateupdate.php?POID="+poid+"&POSTATE="+postatetype+"&HOLDREASON="+poholdreason,
            type: "POST",
            data: { TYP: "POSTATEUPDATE" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {

              location.href = "po_pending_report.php";
               
            }
        });
}

function showPoStockAvailability(celDiv){		

	var bpoId = $("#row" + celDiv).children ("td:first").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	var pdate = $("#row" + celDiv).children ("td:eq(2)").children ("div").text();

	$("#stockponum").text(bpono);
	$("#stockpdate").text(pdate);
	$("#stockpobname").text(bname);
	$('#showPoStock').modal('show');	
	jQuery.ajax({         
			url: "../home/poStockView.php?POID="+bpoId+"&BPONO="+bpono+"&POSTATE=",
            type: "POST",
            data: { TYP: "POSTOCKVIEW" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#PoStock').html(jsondata);
               
            }
        });

}

function pickListForm(purchaseId,purchaseNo,pobname,podate,buyerIDS){

	$("#picklistponum").text(purchaseNo);
	$("#picklistpdate").text(podate);
	$("#picklistpobname").text(pobname);
	$('#showPoPickList').modal('show');	
	
	jQuery.ajax({         
			url: "../home/poPickList.php?POID="+purchaseId+"&BPONO="+purchaseNo+"&BUYERIDS="+buyerIDS,
         type: "POST",
         data: { TYP: "POPICKLIST" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#PoPickList').html(jsondata);
               
            }
        });

}
// Validate pick list quantity 

function validatePickListQty(values,id,posType){
	if(posType == 'N'){
		if(($("#nonExStockQty"+id).val()) < parseFloat(values) )	{	
			alert("Picked Quantity Can Not Be Greater Than Stock Quantity");
			$("#pickQty"+id).val(0);
			return;
		}else if(($("#balanceQty"+id).val()) < parseFloat(values) ){
			
			alert("Picked Quantity Can Not Be Greater Than  Balance Quantity");
			$("#pickQty"+id).val(0);
			return;
		}		
	}else{
		
		if(($("#exStockQty"+id).val()) < parseFloat(values) )	{		
			alert("Picked Quantity Can Not Be Greater Than Stock Quantity");
			$("#pickQty"+id).val(0);
			return;
			
		}else if(($("#balanceQty"+id).val()) < parseFloat(values) ){
			
			alert("Picked Quantity Can Not Be Greater Than Balance Quantity");
			$("#pickQty"+id).val(0);
			return;
		}			
	}
}

function showPickListQtyBox(id) { 
	if($("#"+id).is(':checked')){
		$("#picklistQtyDiv"+id).show();
	}else{
		$("#picklistQtyDiv"+id).hide();
	}
}

// function to change the po status from open to close 


function changePoStatusToClose(celDiv){	
	
	var bpoId = $("#row" + celDiv).children ("td:first").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	var pdate = $("#row" + celDiv).children ("td:eq(2)").children ("div").text();
	$("#picklistponum1").text(bpono);
	$("#picklistpdate1").text(pdate);
	$("#picklistpobname1").text(bname);
	$('#changePOStatusDiv').modal('show');
	
	jQuery.ajax({         
			url: "../home/changepostatus.php?POID="+bpoId+"&BPONO="+bpono,
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#changePOStatus').html(jsondata);
               
            }
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
           // $('#selction-ajax-buyer').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
           // $('#autocomplete-ajax-x-buyer').val(hint);
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



function GetpdfPendingPaymentList(executiveId) {
     var usertype;
     var utype = $("#usertype").val();
		if(utype == 'E'){
			usertype = executiveId;
		}else{			 
			 usertype = $("#salseuser").val();
		}
   
    var InvoiceType = $("#invoicetype").val();
    var invoice_no = $("#invoiceno").val();
	var finyear = $("#ddlfinancialyear").val();	     
	var Fromdate = $("#txtdatefrom_po").val();
	var Todate = $("#txtdateto_po").val();
	var bid = $("#buyerid").val();
	
	 if (Fromdate != "" && Todate != "") { 
		 window.open('outgoing_invoice_payment_pending_list_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&InvoiceType='+InvoiceType+'&invoice_no='+ invoice_no+'&finyear='+finyear+'&bid='+bid+'&usertype='+usertype,'_blank');
    }else {
		alert("Please select date");
    }   
}
function GetexcelPendingPaymentList(executiveId) {
		
	 var usertype;
     var utype = $("#usertype").val();
		if(utype == 'E'){
			usertype = executiveId;
		}else{
			 
			 usertype = $("#salseuser").val();
		}
            
    var InvoiceType = $("#invoicetype").val();
    var invoice_no = $("#invoiceno").val();
	var finyear = $("#ddlfinancialyear").val();	     
	var Fromdate = $("#txtdatefrom_po").val();
	var Todate = $("#txtdateto_po").val();
	var bid = $("#buyerid").val();
	
     if (Fromdate != "" && Todate != "") { 
		 window.open('outgoing_invoice_payment_pending_list_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&InvoiceType='+InvoiceType+'&invoice_no='+ invoice_no+'&finyear='+finyear+'&bid='+bid+'&usertype='+usertype,'_blank');
    }
    else {
        alert("Please select date");
    }
   
}


function GetpdfYearRevenue(executiveId) {
	 var usertype;
     var utype = $("#usertype").val();
		if(utype == 'E'){
			usertype = executiveId;
		}else{			 
			 usertype = $("#salseuser").val();
		}
   
    var potype = $("#potype").val();
    var principalid = $("#principalid").val();
	var finyear = $("#financialyear").val();	     
	
	var bid = $("#buyerid").val();
	var FromDate = $("#txtdatefrom").val();
    var ToDate = $("#txtdateto").val();
    var locationid = $("#location").val();
	
		 window.open('yearRevenue_pdf.php?potype='+potype+'&principalid='+ principalid+'&finyear='+finyear+'&bid='+bid+'&usertype='+usertype+'&FromDate='+FromDate+'&ToDate='+ToDate+'&locationid='+locationid,'_blank');
    
}
function GetexcelYearRevenue(executiveId) {
		
	var usertype;
    var utype = $("#usertype").val();
		if(utype == 'E'){
			usertype = executiveId;
		}else{			 
			 usertype = $("#salseuser").val();
		}
   
    var potype = $("#potype").val();
    var principalid = $("#principalid").val();
	var finyear = $("#financialyear").val();	     
	
	var bid = $("#buyerid").val();
	 
	var FromDate = $("#txtdatefrom").val();
    var ToDate = $("#txtdateto").val();
    var locationid = $("#location").val();
		 window.open('yearRevenue_excel.php?potype='+potype+'&principalid='+ principalid+'&finyear='+finyear+'&bid='+bid+'&usertype='+usertype+'&FromDate='+FromDate+'&ToDate='+ToDate+'&locationid='+locationid,'_blank');
}

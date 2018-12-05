var dashboard_app = angular.module('dashboard_app', []);
//create angular controller
dashboard_app.controller('dashboard_Controller', ['$scope', '$http', function quotation_Controller($scope, $http) {
    $scope.dashboard = [{}];
    $scope.init = function () {
        jQuery.ajax({
            url: "../../Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LISTDATA" },
            success: function (jsondata) {
                var data = jsondata.split('#');
                $scope.$apply(function () {
                    $scope.dashboard.total_item = data[0].split('"')[1];
                    $scope.dashboard.new_purchase_order = data[1];
                    $scope.dashboard.lsc_item = data[2].split('"')[0];
                });
                
            }
        });
    }
    var ItemData = { _items: [{ code_partNo: 0,item_codepart: 0, item_desc: '', principalname: 0, price: 0, lsc: 0, usc: 0, tot_exciseQty: 0, tot_nonExciseQty: 0}] };
    $scope.dashboard = ItemData;
  /*   $scope.SearchPo = function () {
        $scope.dashboard.FromDate = $("#txtfromdate").val();
        $scope.dashboard.ToDate = $("#txttodate").val();
        $scope.dashboard.PoVD = $("#txtpodate").val();
        $scope.dashboard.ponumber = $("#ponumber").val();
        $scope.dashboard.BuyerId = $("#buyerid").val();
        $scope.dashboard.principalid = $("#principalid").val();
		// $scope.dashboard.Mode = $("#Mode").val();
		//$scope.dashboard.Status = $("#Status").val();
		//$scope.dashboard.PoType = $("#PoType").val(); 
        json_string = JSON.stringify($scope.dashboard);
        LoadChallanData(json_string);
    
    } */
	
	 $scope.SearchPoDashboard = function () {
        $scope.dashboard.FromDate = $("#txtdatefrom_po").val();
        $scope.dashboard.ToDate = $("#txtdateto_po").val();
        $scope.dashboard.PoVD = $("#txtpovaliditydate").val();
        $scope.dashboard.ponumber = $("#ponumber").val();
        $scope.dashboard.BuyerId = $("#buyerid").val();
        $scope.dashboard.principalid = $("#principalid").val();
		$scope.dashboard.Mode = $("#Mode").val();
		$scope.dashboard.Status = $("#Status").val();
		$scope.dashboard.PoType = $("#PoType").val();
		$scope.dashboard.marketsegment = $("#marketsegment").val();
		$scope.dashboard.CodePart = 0;
        json_string = JSON.stringify($scope.dashboard);
		//alert(json_string);
        LoadChallanDataDashboard(json_string);
    
    }
	
    $scope.callAllItem = function () {
        jQuery.ajax({
            url: "../../Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LOADITEMLIST" },
            success: function (jsondata) {
			//alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
				
                $scope.$apply(function () {
                    $scope.dashboard._items = objs;
                });
            }
        });
    }
    $scope.callLSCItem = function () {
        jQuery.ajax({
            url: "../../Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LOADLSCITEMLIST" },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    $scope.dashboard._items = objs;
                });
            }
        });
    }
} ]);

dashboard_app.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            if (!ngModelCtrl) {
                return;
            }
            ngModelCtrl.$parsers.push(function (val) {
                var clean = val.replace(/[^0-9]+/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
        }
    }
});

function LoadChallanData() {
        //alert(json_string);
    $(".polist").flexigrid({
        url: '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCH&SearchData='+json_string,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'POID', name: 'bpoId', width: 50, sortable: true, align: 'center', process: procMe },
                             { display: 'bpono', name: 'bpono', width: 200, sortable: true, align: 'left' },
                             { display: 'bpoDate', name: 'bpoDate', width: 200, sortable: true, align: 'left' },
                             { display: 'bpoVDate', name: 'bpoVDate', width: 200, sortable: true, align: 'left' },
                              { display: 'BuyerName', name: 'BuyerName', width: 200, sortable: true, align: 'left' },
                             { display: 'po_status', name: 'po_status', width: 200, sortable: true, align: 'left' },
                           { display: 'po_val', name: 'po_val', width: 200, sortable: true, align: 'left'}],
        buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
//        searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        //width: 1360,
        height: 400

    });
    var path = '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCH&SearchData='+json_string;
        $(".polist").flexOptions({ url: path });
        $(".polist").flexReload();
}

function LoadChallanDataDashboard() {
        //alert(json_string);
    $(".polist").flexigrid({
        url: '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCHDASHBOARD&SearchData='+json_string,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'POID', name: 'bpoId', width: 50, sortable: true, align: 'center', process: procMe },
                 { display: 'bpono', name: 'bpono', width: 200, sortable: true, align: 'left', process: function (col, id)			{ col.innerHTML = "<a href='javascript:showPoDetails(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
                 { display: 'bpoDate', name: 'bpoDate', width: 100, sortable: true, align: 'left' },
                 { display: 'bpoVDate', name: 'bpoVDate', width: 100, sortable: true, align: 'left' },
                 { display: 'BuyerName', name: 'BuyerName', width: 300, sortable: true, align: 'left' },
                 { display: 'Po Status', name: 'po_status', width: 50, sortable: true ,align: 'left',process: function (col, id)				{ col.innerHTML = "<a href='javascript:changePoStatusToClose(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
		 { display: 'PO State', name: 'po_state', width: 50, sortable: true ,align: 'left', process: function (col, id)			{ col.innerHTML = "<a href='javascript:poStateChange(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; }},
		 { display: 'Stock Availability', name: 'stockAvailabe', width: 100, sortable: true, align: 'left',process: function (col, id)			{ col.innerHTML = "<a href='javascript:showPoStockAvailability(" + id + ");' id='flex_col" + id + "'>" + col.innerHTML + "</a>"; } },
		{ display: 'PoType', name: 'poType', width: 80, sortable: true, align: 'left' },
		{ display: 'Pick List', name: 'picklist', width: 100, sortable: false, align: 'left' },
                { display: 'po_val', name: 'po_val' ,width: 100, sortable: true, align: 'left'}],
					 //  buttons: [{ name: 'Edit', bclass: 'edit'}, { separator: true}],
//       buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
//        searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: true,
        //width: 1360,
        height: 400

    });
	
    var path = '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCHDASHBOARD&SearchData='+json_string;
        $(".polist").flexOptions({ url: path });
        $(".polist").flexReload();
}


function procMe(celDiv, id) {
	
    $(celDiv).bind("click", function () {
	var poType = $("#row" + id).children ("td:eq(8)").children ("div").text();
	var PONumber = celDiv.innerHTML;
	if(poType =="Bundle"){
		var path = '../Business_View/bundle.php?POID=' + PONumber;
	}else{
	   var path = '../Business_View/purchaseorder.php?POID=' + PONumber;
	}  
     //alert(path);
     window.location.href = path;
    });
}

function showPoDetails(celDiv){	
	
	var bpoId = $("#row" + celDiv).children ("td:first").children ("div").text();
	var bpono = $("#row" + celDiv).children ("td:eq(1)").children ("div").text();
	var bname = $("#row" + celDiv).children ("td:eq(4)").children ("div").text();
	var poType = $("#row" + celDiv).children ("td:eq(8)").children ("div").text();

	$("#bname").text(bname);
	$('#myPoDetails').modal('show');
	
	jQuery.ajax({         
			url: "postatus.php?POID="+bpoId+"&BPONO="+bpono+"&poType="+poType,
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#podetails').html(jsondata);
               
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
	    url: "postateupdate.php?POID="+poid+"&POSTATE="+postatetype+"&HOLDREASON="+poholdreason,
            type: "POST",
            data: { TYP: "POSTATEUPDATE" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {

              	location.href = "Dashboard.php";
               
            }
        });
}

// function to change the po state Hold / Unhold from open to close show 

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
			url: "postate.php?POID="+bpoId+"&BPONO="+bpono+"&POSTATE="+postate,
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#poState').html(jsondata);
               
            }
        });

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
			url: "changepostatus.php?POID="+bpoId+"&BPONO="+bpono,
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#changePOStatus').html(jsondata);
               
            }
        });

}



function changePoStatusSave(poid){	
var reason = $("#poChangeStatusReason").val();	
	
	if (($('#poChangeStatus').is(":checked")) && ($('#poChangeStatusReason').val() == '')) {
            alert("Please Enter reason for close before submit form.");
            return;
    }
	
	jQuery.ajax({         
	    url: "postateupdate.php?POID="+poid+"&CLOSEREASON="+reason,
            type: "POST",
            data: { TYP: "POSTATUSUPDATE" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {

              	location.href = "Dashboard.php";
               
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
			url: "poStockView.php?POID="+bpoId+"&BPONO="+bpono+"&POSTATE=",
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
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
/////////////////////////////////// commented due to page loading performance on 25-11-2015 by Codefire
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


function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
    }
}
function NonePrincipal() {
$("#principalid").val(0);
}

// To show buyer/customer deatils in popup 
function buyer_customer_detail(id, type) {
   $('#Quataion_buyer_customer_detail').modal('show');	
	  jQuery.ajax({
            url: "../SalesExecutive/buyer_customer.php",
           type: "POST",
           data: { TYP:"BUYERCUSTOMER" ,id:id,type:type},
			beforeSend: function() { jQuery('#wait').css("display","block");},
			complete: function() { jQuery('#wait').css("display","none");},
            success: function (jsondata) {
			//alert(jsondata);
			//var objs = jQuery.parseJSON(jsondata);
			//alert(objs);
			$('#qBCdetail').html(jsondata);
							   
            }            
        });  
}

	

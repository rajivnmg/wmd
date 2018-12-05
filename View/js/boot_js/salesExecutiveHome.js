var dashboard_app = angular.module('dashboard_app', []);
var json_string;
// create angular controller
dashboard_app.controller('dashboard_Controller', ['$scope', '$http', function quotation_Controller($scope, $http) {
    $scope.dashboard = [{}];
    $scope.init = function (ExecutiveId) {
        jQuery.ajax({
            url: "../../Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LISTEXECUTIVEDATA",ExecutiveId:ExecutiveId },
            success: function (jsondata) {
                var data = jsondata.split('#');
                $scope.$apply(function () {
				 if(data != ''){
                    $scope.dashboard.total_pending_po= data[0].split('"')[1];
                    $scope.dashboard.total_deliverd_po=data[1];
                    $scope.dashboard.total_partial_deliverd_po=data[2];
                    $scope.dashboard.total_pending_payment=data[3].split('"')[0];
					}
                });
                
            }
        });
    }
    var ItemData = { _items: [{ code_partNo: 0, item_codepart: 0, item_desc: '', principalname: 0, price: 0, lsc: 0, usc: 0, tot_exciseQty: 0, tot_nonExciseQty: 0}] };
    $scope.dashboard = ItemData;
    $scope.SearchPo = function () {
        $scope.dashboard.FromDate = $("#txtfromdate").val();
        $scope.dashboard.ToDate = $("#txttodate").val();
        $scope.dashboard.PoVD = $("#txtpodate").val();
        $scope.dashboard.ponumber = $("#ponumber").val();
        $scope.dashboard.BuyerId = $("#buyerid").val();
        $scope.dashboard.principalid = $("#principalid").val();
        json_string = JSON.stringify($scope.dashboard);
        LoadChallanData(json_string);
    
    }
    $scope.callAllItem = function () {
        jQuery.ajax({
            url: "../../Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LOADITEMLIST" },
            success: function (jsondata) {
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
//        buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
//        searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1360,
        height: 400

    });
    var path = '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCH&SearchData='+json_string;
        $(".polist").flexOptions({ url: path });
        $(".polist").flexReload();
}
LoadChallanData();
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
// To show buyer/customer deatils in popup 
function buyer_customer_detail(id, type) {
   $('#Quataion_buyer_customer_detail').modal('show');	
	  jQuery.ajax({
            url: "buyer_customer.php",
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

// details of sales dashboard PO
function salesPoDetail(bpoId,bpono){	
	
	$('#myPoDetails').modal('show');	
	jQuery.ajax({
            //url: "../../Controller/Dashboard/Dashboard_Controller.php?POID="+bpoId+"&BPONO="+bpono,
			url: "../home/postatus.php?POID="+bpoId+"&BPONO="+bpono,
            type: "POST",
            data: { TYP: "POSTATUSDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
                //var objs = jQuery.parseJSON(jsondata);
				//alert(objs);
				$('#podetails').html(jsondata);
               // $scope.$apply(function () {
                    //$scope.dashboard._items = objs;
               // });
            }
        });

}


var BuyerList = {};
var PrincipalList = {};
var ItemList = {};
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
$( document ).ready(function() {
// added due to page loading performance on 25-11-2015 by Codefire
/* jQuery.ajax({
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
        //alert(jsondata);
        if (jsondata != "") {     	
            var obj;
            //alert(objs.length);
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
    //$("#new_buyer").show();
    if (value != "" && data > 0) {
        //alert(data);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "post",
            data: { TYP: "SELECT", BUYERID: data },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $("#buyerid").val(objs[0]._buyer_id);
                $("#txt_new_buyer_name").val(objs[0]._buyer_name);
                $("#txt_oldbuyer_add").val(objs[0]._bill_add1);
            }
        });
    }
    else {
		 
    }
}
function NoneBuyer() {
    //$("#new_buyer").hide();
    $("#buyerid").val(0);
    $("#txt_new_buyer_name").val("");
    $("#txt_oldbuyer_add").val("");
}
function ActionOnPrincipal(value, data) {
    ItemList = {};
    if (value != "" && data > 0) {
    $("#principalid").val(data);
    jQuery.ajax({
      url: "../../Controller/Master_Controller/Item_Controller.php",
      type: "post",
      data: { TYP: "SELECT", PRINCIPALID: $("#principalid").val() },
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
  }
  else {
		
    }
}
function NonePrincipal() {
    $('#_code_part_no_add').empty();
    $("#principalid").val(0);
    ItemList = {};
}
function CallToItem(ItemList) {
    'use strict';
    var itemArray = $.map(ItemList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#_code_part_no_add').autocomplete({
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
	//	alert();
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Item_Controller.php",
            type: "POST",
            data: { TYP: "LOADITEMINFO", ITEMID: data },
            //cache: false,
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                 //alert(jsondata);
                $("#item_desc").val(objs[0]._item_descp);
//                $scope.$apply(function () {
//                    $scope.IncomingInvoiceNonExcise._item_descp_add = objs[0]._item_descp;
//                    $scope.IncomingInvoiceNonExcise._rate_add = objs[0]._item_cost_price;

//                });

            }
        });
    }
    else {
    }
}

function NoneItem() {
    $("#itemid").val(0);
}
var Challan_app = angular.module('Challan_app', []);
//Challan_app.controller('Challan_Controller', function ($scope) {
Challan_app.controller('Challan_Controller', ['$scope', '$http', function Challan_Controller($scope, $http) {

    var sample_Challan = { _items: [{ itemid: 0, _code_part_no: 0, _item_descp: '', _qty: 0}] };
    //alert(sample_Challan);
    $scope.Challan = sample_Challan;
    $scope.addItem = function () {
		
	 var principalid =  $("#principalid").val();
     if (principalid == 0 || principalid == "" || principalid == 'NULL' ) {
            alert("Please select Principal");
            return;
     }
     
      var itemid =  $("#itemid").val();
     if (itemid == 0 || itemid == "" || itemid == 'NULL' ) {
            alert("Please select Item");
            return;
     }
     
      var qty_add =  $("#_qty_add").val();
     if (qty_add == 0 || qty_add == "" || qty_add == 'NULL' ) {
            alert("Please Enter Quentity");
            return;
     }
        
    $scope.Challan._principalname=$("#autocomplete-ajax-principal").val();
    $scope.Challan._principalID=$("#principalid").val();
    $scope.Challan.itemid =$("#itemid").val();
    $scope.Challan._code_part_no_add = $("#_code_part_no_add").val();
    $scope.Challan.item_desc = $("#item_desc").val();
        //alert("here");
    $scope.Challan._items.push({ _SrNo: $scope.Challan._items.length + 1,_principalname: $scope.Challan._principalname,_principalID:$scope.Challan._principalID, itemid: $scope.Challan.itemid, _code_part_no: $scope.Challan._code_part_no_add,item_desc: $scope.Challan.item_desc, _qty: $scope.Challan._qty_add
        });
        var k = 0;
        $scope.Challan._total_Qty = 0;
        while (k < $scope.Challan._items.length) {
            $scope.Challan._total_Qty = parseFloat($scope.Challan._items[k]["_qty"]) + parseFloat($scope.Challan._total_Qty);
            k++
        }
        $scope.Challan._principalname="";
        $scope.Challan.principalid="";
        $scope.Challan._code_part_no_add="";
        $scope.Challan.item_desc="";
        $("#autocomplete-ajax-principal").val("");
        $("#_code_part_no_add").val("");
        $("#principalid").val("");
        $("#itemid").val("");
        $("#item_desc").val("");
         $("#_qty_add").val("");
    }

    $scope.getTotal_Qty=function()
    {
		 var k = 0;
        $scope.Challan._total_Qty = 0;
        while (k < $scope.Challan._items.length) {
            $scope.Challan._total_Qty = parseFloat($scope.Challan._items[k]["_qty"]) + parseFloat($scope.Challan._total_Qty);
            k++
        }
	}
    $scope.DeleteItem = function (item) {
        $scope.Challan._items.splice($scope.Challan._items.indexOf(item), 1);
        var k = 0;
        $scope.Challan._total_Qty = 0;
        while (k < $scope.Challan._items.length) {
            $scope.Challan._total_Qty = parseFloat($scope.Challan._items[k]["_qty"]) + parseFloat($scope.Challan._total_Qty);
            k++
        }
    }

    $scope.getBuyerDetails = function () {
        if ($scope.Challan._BuyerId == 0) {
            //alert("here");
            //$("#new_buyer").show();

        }
        else if ($scope.Challan._BuyerId > 0) {
            //$("#new_buyer").hide();
            var BuyerId = $scope.Challan._BuyerId;
            jQuery.ajax({
                url: "../../Controller/Master_Controller/Buyer_Controller.php",
                type: "POST",
                data: { TYP: "SELECT", BUYERID: BuyerId },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        $scope.Challan._CustAddress = objs[0]._bill_add1;
                    });
                }
            });
        }
        else if ($scope.Challan._BuyerId == -1) {
            //$("#new_buyer").hide();

        }
    }
    $scope.SaveChallan = function () {
        if ($scope.Challan._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        var bid  = $("#buyerid").val();         
     
        if (bid == 0 || bid == "" || bid == 'NULL' ) {
            alert("Please select Buyer");
            return;
        }
        $scope.Challan._BuyerId = $("#buyerid").val();
        
        
        
        
        $scope.Challan._ChallanDate = $("#challan_date").val();
        $scope.Challan._gc_note_date = $("#date1").val();
        $scope.Challan._OrderDate = $("#date2").val();
      
       // alert($scope.Challan._Challan_Status);
      // alert($("#Challan_Status").val());
       $scope.Challan._Challan_Status= $("#Challan_Status").val();
       
        // alert("here");
		$("#savechallan").hide();
        var json_string = JSON.stringify($scope.Challan);
		//alert(json_string); return false;
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/Challan_Controller.php",
            type: "POST",
            data: { TYP: "INSERT", CHALLANDATA: json_string },
            success: function (jsondata) {
                // alert("Success");
                $scope.$apply(function () {
                    $scope.Challan = null;
                    location.href = "ChallanView.php";
                });
            }
        });
    }
    $scope.GetItemDesc = function () {
        // alert("here");
        var json_string = JSON.stringify($scope.Challan);
        ITEMID = $scope.Challan._code_part_no_add;
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Item_Controller.php",
            type: "POST",
            data: { TYP: "LoadItemINFO", ITEMID: ITEMID },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                // alert(objs[0]._item_descp);
                $scope.$apply(function () {
                    $scope.Challan._item_desc_add = objs[0]._item_descp;
                });
            }
        });
    }
    $scope.UpdateChallan = function () {
        //alert("update");
           
		$scope.Challan._ChallanDate = $("#challan_date").val();
        var json_string = JSON.stringify($scope.Challan);
		//alert(json_string); return false;
		$("#savechallan").hide();
		$("#updatechallan").hide();
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/Challan_Controller.php",
            type: "POST",
            data: { TYP: "UPDATE", CHALLANUPDATE: json_string },
            success: function (jsondata) {
               location.href = "ChallanView.php";
            }
        });

    }
    $scope.init = function (number) {
        $scope.Challan._ChallanId = number;
        if (number != "") {
            $("#savechallan").hide();
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/Challan_Controller.php",
                type: "POST",
                data: { TYP: "SELECT", ChallanId: number },
                success: function (jsondata) {         	
					//alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
					 //var btrakpoint = Object.keys(objs[0]._items).length;
					$("#breakpoint").val(Object.keys(objs[0]._items).length);
                    $scope.$apply(function () {
                        $scope.Challan = objs[0];
						//alert(objs[0]._Challan_Status);
                       // alert(objs[0]._BuyerId);
                       /*  if(objs[0]._Challan_Status=='2'||objs[0]._Challan_Status=='3'||objs[0]._Challan_Status=='4')
                        {
						   $("#updatechallan").hide(); 	
						} */
						
						 
						if(objs[0]._Challan_Status=='1'||objs[0]._Challan_Status=='2')
						{
						   $("#InvoiceType").attr("disabled",true);
			               $("#challan_no5").attr("disabled",true);
			               $("#OutgoingInvoiceNo").attr("disabled",true);
						   $("#OutgoingInvoiceNo1").attr("disabled",true);
							$("#OutgoingInvoiceNo2").attr("disabled",true);
							$("#OutgoingInvoiceNo3").attr("disabled",true);
			               $("#date2").attr("disabled",true);	
						}else if(objs[0]._Challan_Status == '3' || objs[0]._Challan_Status == '4' || objs[0]._Challan_Status == '5'){
							$("#InvoiceType").val("E");
							$("#InvoiceType").attr("disabled",false);
							$("#challan_no5").attr("disabled",false);
							$("#OutgoingInvoiceNo1").attr("disabled",false);
							$("#OutgoingInvoiceNo2").attr("disabled",false);
							$("#OutgoingInvoiceNo3").attr("disabled",false);
							$("#date2").attr("disabled",false);
						}else{
							$("#InvoiceType").val("");
			
							$("#challan_no5").val("");
							$("#date2").val("");
							$("#OutgoingInvoiceNo").val("");
							$("#InvoiceType").attr("disabled",true);			
							$("#challan_no5").attr("disabled",true);
							$("#OutgoingInvoiceNo1").attr("disabled",true);
							$("#OutgoingInvoiceNo2").attr("disabled",true);
							$("#OutgoingInvoiceNo3").attr("disabled",true);
							$("#date2").attr("disabled",true); 
						}
						 if(objs[0]._Challan_Status=='2'||objs[0]._Challan_Status=='3'||objs[0]._Challan_Status=='4' ||objs[0]._Challan_Status=='5' ||objs[0]._Challan_Status=='7')
                        {
						   $("#updatechallan").hide(); 	
						}
                        $scope.Challan._BuyerId = objs[0]._BuyerId;
                        $scope.Challan._Challan_Status=objs[0]._Challan_Status;
                       
                        $scope.Challan._items = objs[0]._items;
						if(objs[0]._Challan_Status=='1'){
							$scope.Challan._Challan_st = 'Open';
						}else if(objs[0]._Challan_Status=='2'){
							$scope.Challan._Challan_st = 'Close Without Outgoing Invoice';
						}else if(objs[0]._Challan_Status=='3'){
							$scope.Challan._Challan_st = 'Close With Outgoing Excise';
						}else if(objs[0]._Challan_Status=='4'){
							$scope.Challan._Challan_st = 'Close With Outgoing Non-Excise';
						}else if(objs[0]._Challan_Status=='5'){
							$scope.Challan._Challan_st = 'Close With Outgoing Excise & Non-Excise';
						}else if(objs[0]._Challan_Status=='6'){
							$scope.Challan._Challan_st = 'Free Sample';
						}else if(objs[0]._Challan_Status=='7'){
							$scope.Challan._Challan_st = 'Close against Loan settlement';
						}
                    });
                }
            });
			
			
        }
        else {
            //$("#updatechallan").hide();
			var d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            };
            var day = d.getDate();
            $scope.Challan._ChallanDate = year + "-" + month + "-" + day;
			$scope.Challan._Challan_Status=1;
		    $scope.Challan._items.splice($scope.Challan._items.indexOf(0), 1);
            jQuery.ajax({
                 url: "../../Controller/Business_Action_Controller/Challan_Controller.php",
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {//alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $("#challan_no").val(objs);
                    $scope.$apply(function () {
                        $scope.Challan._ChallanNo = objs;
                    });
                }
            });
            $("#updatechallan").hide();           
            $("#InvoiceType").attr("disabled",true);
			$("#challan_no5").attr("disabled",true);
			$("#OutgoingInvoiceNo").attr("disabled",true);
			$("#date2").attr("disabled",true);			
			$("#InvoiceType").val("");
			$("#challan_no5").val("");
			$("#date2").val("");
			
			
			$("#OutgoingInvoiceNo1").attr("disabled",true);
			$("#OutgoingInvoiceNo2").attr("disabled",true);
			$("#OutgoingInvoiceNo3").attr("disabled",true);
			$("#btnprint").hide();
						
        }
    }
    $scope.getSetInfo=function()
    {
		var chalan_status=$("#Challan_Status").val();
		
		if(chalan_status=="3")
		{
			$("#InvoiceType").val("E");
			$("#InvoiceType").attr("disabled",false);
			$("#challan_no5").attr("disabled",false);
			$("#OutgoingInvoiceNo1").attr("disabled",false);
			$("#OutgoingInvoiceNo2").attr("disabled",false);
			$("#OutgoingInvoiceNo3").attr("disabled",false);
			$("#date2").attr("disabled",false);
		}
		else if(chalan_status=="4")
		{
			$("#InvoiceType").val("N");
			$("#InvoiceType").attr("disabled",false);
			$("#challan_no5").attr("disabled",false);
			$("#OutgoingInvoiceNo1").attr("disabled",false);
			$("#OutgoingInvoiceNo2").attr("disabled",false);
			$("#OutgoingInvoiceNo3").attr("disabled",false);
			$("#date2").attr("disabled",false);
		}
		else if(chalan_status=="5")
		{
			$("#InvoiceType").val("B");
			$("#InvoiceType").attr("disabled",false);
			$("#challan_no5").attr("disabled",false);
			$("#OutgoingInvoiceNo1").attr("disabled",false);
			$("#OutgoingInvoiceNo2").attr("disabled",false);
			$("#OutgoingInvoiceNo3").attr("disabled",false);
			$("#date2").attr("disabled",false);
		}
		else
		{
			$("#InvoiceType").val("");
			
			$("#challan_no5").val("");
			$("#date2").val("");
			$("#OutgoingInvoiceNo").val("");
			$("#InvoiceType").attr("disabled",true);			
			$("#challan_no5").attr("disabled",true);
			$("#OutgoingInvoiceNo1").attr("disabled",true);
			$("#OutgoingInvoiceNo2").attr("disabled",true);
			$("#OutgoingInvoiceNo3").attr("disabled",true);
			$("#date2").attr("disabled",true); 
		}
	}

} ]);

Challan_app.directive('validNumber', function () { 
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

Challan_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('Challan._qty_add', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.Challan._qty_add = oldValue;
                }
            });
        }
    };
});

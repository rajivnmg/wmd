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

// commented due to page loading performance on 25-11-2015 by Codefire
/* $( document ).ready(function() {
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
}); */

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
                $("#txtcreditperiod").val(objs[0]._credit_period);
            }
        });
        jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Quation_Controller.php", type: "POST",
            data: { TYP: "SALESTAX", BUYERID: data },
            success: function (jsondata) {
                //alert(jsondata);
                $('#sales').empty();
                $("#sales").append("<option value='0'>Select Tax</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj; for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        $("#sales").append("<option value=\"" + obj._sales_tax + "\">" + obj.sale_tax_text + "</option>");
                    }
                    //$("#isalestax").val(0);
                }
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
    $("#txtcreditperiod").val("");
}
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
$( document ).ready(function() {
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
    ItemList = {};
    if (value != "" && data > 0) {
        var buyerid = $("#buyerid").val();
        $("#principalid").val(data);
        if (buyerid > 0) {
            jQuery.ajax({
                url: "../../Controller/Master_Controller/Buyer_Controller.php",
                type: "POST",
                data: { TYP: "GET_DISCOUNT_DETAILS", BUYERID: buyerid, PRINCIPALID: data },
                success: function (jsondata) {
                    if (jsondata != "[]") {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $("#txtdiscount").val(objs[0]._discount);
                    }
                }
            });
        }

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
    }
}
function NonePrincipal() {
    $('#item_master').val("");
    $("#txtdiscount").val("");
    ItemList = {};
}
function CallToItem(ItemList) {
    'use strict';
    var itemArray = $.map(ItemList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#item_master').autocomplete({
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
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Item_Controller.php",
            type: "POST",
            data: { TYP: "LOADITEM", ITEMID: data },
            //cache: false,
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                // alert(jsondata);
                $("#txtitem_desp").val(objs[0]._item_descp);
                $("#unit_masterid").val(objs[0]._unit_id);
                $("#unit_master").val(objs[0]._unitname);
                $("#oldprice").val(objs[0]._item_cost_price);
            }
        });
    }
    else {
    }
}
function NoneItem() {
    $("#itemid").val(0);
}
var URL = "../../Controller/Business_Action_Controller/Quation_Controller.php";
var method = "POST";
var quotation_app = angular.module('quotation_app', []);
// create angular controller

quotation_app.controller('quotation_Controller', ['$scope', '$http', function quotation_Controller($scope, $http) {

    var sample_quotation = { _items: [{ itemid: '', _item_code_part_no: '', _item_descp: '', _unit_id:'', _unit_name: '', _quantity: '', _price_per_unit: 0.00, sn: 0}] };
    $scope.quotation = sample_quotation;
    $scope.addItem = function () {

        $scope.quotation.itemid = $("#itemid").val();
        $scope.quotation._item_code_part_no = $("#item_master").val();
        $scope.quotation._item_descp = $("#txtitem_desp").val();
        $scope.quotation._unit_id = $("#unit_masterid").val();
        $scope.quotation._unit_name = $("#unit_master").val();
        $scope.quotation._item_base_price = $("#oldprice").val();
        $scope.quotation._quantity = $("#txtitemquantity").val();
        if (parseInt($scope.quotation._quantity) <= 0 || $scope.quotation._quantity == "") {
            alert("Minimum Quantity should be one.");
            return;
        }
        var falg = false;
        if (parseInt($scope.quotation._item_base_price) > parseInt($scope.quotation._price_per_unit)) {

            falg = confirm("Aru you confirm ? Giving price is less then to base price.");
        }
        else {
            falg = true;
        }

        if (falg) {
            $scope.quotation._items.push({ itemid: $scope.quotation.itemid, _item_code_part_no: $scope.quotation._item_code_part_no, _item_descp: $scope.quotation._item_descp, _unit_id: $scope.quotation._unit_id, _unit_name: $scope.quotation._unit_name, _quantity: $scope.quotation._quantity, _price_per_unit: $scope.quotation._price_per_unit });
            $scope.quotation._item_id =null;
            $scope.quotation._item_code_part_no = null;
            $scope.quotation._item_descp =null;
            $scope.quotation._price_per_unit =null;
            $scope.quotation._quantity =null;
            $scope.quotation._unit_id =null;
            $scope.quotation._unit_name =null;
            $("#txtitem_desp").val("");
            $("#unit_master").val("");
            $("#unit_masterid").val("");
        }
    }
    $scope.removeItem = function (item) {
        $scope.quotation._items.splice($scope.quotation._items.indexOf(item), 1);
    }
    $scope.AddQuotation = function () {
        if ($scope.quotation._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $scope.quotation._coustomer_ref_date = $("#txtdate").val();
        $scope.quotation._buyer_id = $("#buyerid").val();
        $scope.quotation._principal_id = $("#principalid").val();
        $scope.quotation._discount = $("#txtdiscount").val();
        $scope.quotation._credit_period = $("#txtcreditperiod").val();
        var json_string = JSON.stringify($scope.quotation);
		//alert(json_string); return;
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", QUOTATIONDATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    $scope.quotation = null;
                    location.href = "viewQuotation.php";
                });
            }
        });
    }
    $scope.Update = function () {
        $scope.quotation._coustomer_ref_date = $("#txtdate").val();
        var json_string = JSON.stringify($scope.quotation);
        //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", QUOTATIONDATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    $scope.quotation = null;
                    location.href = "viewQuotation.php";
                });
            }
        });
    }
    $scope.checkBuyer = function () {
        //alert($scope.quotation.isbuyer);
        if ($scope.quotation.isbuyer == 0) {
            $("#new_buyer").show();
            $scope.quotation._buyer_id = 0;
            $scope.quotation._coustomer_name = "";
            $scope.quotation._coustomer_add = "";
            $scope.quotation._credit_period = "";
        }
        else if ($scope.quotation.isbuyer > 0) {
            $("#new_buyer").show();
            var BuyerId = $scope.quotation.isbuyer;
            jQuery.ajax({
                url: "../../Controller/Master_Controller/Buyer_Controller.php",
                type: "POST",
                data: { TYP: "SELECT", BUYERID: BuyerId },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        $scope.quotation._buyer_id = objs[0]._buyer_id;
                        $scope.quotation._coustomer_name = objs[0]._buyer_name;
                        $scope.quotation._coustomer_add = objs[0]._bill_add1;
                        $scope.quotation._credit_period = objs[0]._credit_period;
                    });
                }
            });
        }
        else if ($scope.quotation.isbuyer == -1) {
            $("#new_buyer").hide();
            $scope.quotation._buyer_id = 0;
            $scope.quotation._coustomer_name = "";
            $scope.quotation._coustomer_add = "";
            $scope.quotation._credit_period = "";
        }
    }
    $scope.getFreight = function () {

        $scope.quotation.frgt = $scope.quotation.frgt1;
        //$("#ifrgp").empty();
        //$("#ifrga").empty();
        if ($scope.quotation.frgt == "P") {
            $("#ifrgp").show();
            $("#ifrga").hide();
        } else if ($scope.quotation.frgt == "A") {
            $("#ifrga").show();
            $("#ifrgp").hide();
        } else {
            $("#ifrga").hide();
            $("#ifrgp").hide();
        }


    }
    $scope.Saletaxcall = function (buyerid, saletaxid) {
        jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Quation_Controller.php", type: "POST",
            data: { TYP: "SALESTAX", BUYERID: buyerid },
            success: function (jsondata) {
                //alert(jsondata);
                $('#sales').empty();
                $("#sales").append("<option value='0'>Select Tax</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj; for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        $("#sales").append("<option value=\"" + obj._sales_tax + "\">" + obj.sale_tax_text + "</option>");
                    }
                    $("#sales").val(saletaxid);
                }
            }
        });

    }
    $scope.init = function (number) {
        if (number > 0) {
            $("#btnsave").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT", QUOTATIONNUMBER: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(number);
                    $scope.$apply(function () {
                        //alert(jsondata);
                        $scope.quotation = objs[0];
                        $scope.quotation.isbuyer = objs[0]._buyer_id;
						$("#buyerid").val(objs[0]._buyer_id);
                        $scope.Saletaxcall(objs[0]._buyer_id, objs[0]._sales_tax);
                        $scope.quotation._items = objs[0]._itmes;
                        for (var i = 0; i < $scope.quotation._items.length; i++) {
                            $scope.quotation._items[i]['sn'] = i + 1;
                        }
                        $scope.quotation.frgt1 = $scope.quotation.frgt;
                        if ($scope.quotation.frgt == "P") {
                            $("#ifrgp").show();
                            $("#ifrga").hide();
                        } else if ($scope.quotation.frgt == "A") {
                            $("#ifrga").show();
                            $("#ifrgp").hide();
                        }
                        if (parseFloat($scope.quotation._discount) > 0) {
                            $("#show_print_discount").show();
                        }
                        if (parseFloat($scope.quotation._delivery) > 0) {
                            $("#show_print_delivery").show();
                        }
                        if (parseFloat($scope.quotation._sales_tax) > 0) {
                            $("#show_print_saletax").show();
                        }
                        if (parseFloat($scope.quotation._incidental_chrg) > 0) {
                            $("#show_print_incidental").show();
                        }
                        if ($scope.quotation._ed_edu_tag == "I" || $scope.quotation._ed_edu_tag == "E") {
                            $("#show_print_edu").show();
                        }
                        if (parseFloat($scope.quotation._credit_period) > 0) {
                            $("#show_print_payment").show();
                        }
                        if (parseFloat($scope.quotation._cvd) > 0) {
                            $("#show_print_cvd").show();
                        }
                    });
                    ActionOnPrincipal("Principalid", $scope.quotation._principal_id);
                    jQuery.ajax({
                        url: URL,
                        type: "POST",
                        data: { TYP: "FIND_QNO_IN_PO", QUOTATIONNUMBER: objs[0]._quotation_no },
                        success: function (jsondata) {
                            var objs = jQuery.parseJSON(jsondata);
                            if (objs > 0) {
                                $("#btnupdate").hide();
                            }
                        }
                    });
                }
            });
        }
        else {
			$("#btnsaveCopy").hide();
            $("#btnupdate").hide();
            $("#btnprint").hide();
            $("#ifrga").hide();
            $("#ifrgp").hide();
            var d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            };
            var day = d.getDate();
            $scope.quotation._quotation_date = day + "/" + month + "/" + year;
            $scope.quotation._ed_edu_tag = "N";
            $scope.quotation._items.splice($scope.quotation._items.indexOf(0), 1);
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        $scope.quotation._quotation_no = objs;
                    });
                }
            });
        }
    }

} ]);

quotation_app.directive('validNumber', function () { 
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
quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation._price_per_unit', function (newValue, oldValue) {

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
                    scope.quotation._price_per_unit = oldValue;
                }
            });
        }
    };
});

quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation._quantity', function (newValue, oldValue) {

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
                    scope.quotation._quantity = oldValue;
                }
            });
        }
    };
});

quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation._discount', function (newValue, oldValue) {

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
                    scope.quotation._discount = oldValue;
                }
            });
        }
    };
});


quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation._incidental_chrg', function (newValue, oldValue) {

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
                    scope.quotation._incidental_chrg = oldValue;
                }
            });
        }
    };
});



quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation._cvd', function (newValue, oldValue) {

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
                    scope.quotation._cvd = oldValue;
                }
            });
        }
    };
});

quotation_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('quotation.frgt', function (newValue, oldValue) {

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
                    scope.quotation.frgt = oldValue;
                }
            });
        }
    };
});
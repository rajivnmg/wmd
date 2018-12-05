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
            //$('#selction-ajax-buyer').html('You selected: none');
        }
    });
}
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
function ActionOnBuyer(value, data) {
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
    }
    else {
		 $("#buyerid").val(0);
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
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
                    var obj;
                    for (var items in objs) {
                        ItemList[items] = objs[items];
                    }
                    CallToItem(ItemList);
                }
            }
        });
    }
    else {
		  $("#principalid").val(0);
    }
}
function NonePrincipal() {
    $('#_code_part_no_add').val("");
    $("#principalid").val(0);
    ItemList = {};
}
var SupplierList = {};
function CallToSullpier(SupplierList) {
    'use strict';
    var supplierArray = $.map(SupplierList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-supplier').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: supplierArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnSupplier(suggestion.value, suggestion.data);
            //$('#selction-ajax-supplier').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-supplier').val(hint);
        },
        onInvalidateSelection: function () {
            NoneSupplier();
            // $('#selction-ajax-supplier').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "../../Controller/Master_Controller/Supplier_Controller.php",
    type: "post",
    data: { TYP: "SELECT", SUPPLIERID: 0 },
    success: function (jsondata) {
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            var obj;
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                SupplierList[obj._principal_supplier_id] = obj._principal_supplier_name;
            }
            CallToSullpier(SupplierList);
        }
    }
});
function ActionOnSupplier(value, data) {
    if (value != "" && data > 0) {
        $("#supplierid").val(data);
    }
    else {
    }
}
function NoneSupplier() {
    $("#supplierid").val(0);
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

        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
            type: "POST",
            data: { TYP: "SELECTINVOICE", ITEMID: data, incomingInvTyp: "E" },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $('#iinv_no_add').empty();
                $("#iinv_no_add").append("<option value='0'>Select Invoice</option>");
                var objs = jQuery.parseJSON(jsondata);
                //alert(objs);
                if (jsondata != "") {
                    var obj,iinv_no,iIndex,iLen;
                    for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        iinv_no=obj._iinv_no;
                        iIndex=iinv_no.indexOf("|");
                        iinv_no_val=iinv_no.substring(0,iIndex);
                        iinv_no_text=iinv_no.substring(iIndex+1)+"&nbsp;&nbsp;&nbsp;&nbsp;"+obj._bal_qty;
                        $("#_item_descp_add").val(obj._codePartNo_desc);
                        $("#iinv_no_add").append("<option value=\"" + iinv_no_val + "\">" + iinv_no_text + "</option>");
                    }
                }

            }
        });
    }
    else {
    }
}
function NoneItem() {
    $("#itemid").val(0);
    $('#iinv_no_add').empty();
    $("#iinv_no_add").append("<option value='0'>Select Invoice</option>");
}
var StockTransfer_app = angular.module('StockTransfer_app', []);

StockTransfer_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {
			scope.$watch('StockTransfer._price_add', function(newValue,oldValue) {

                if(newValue==undefined){
					newValue="";
				}
				if(oldValue==undefined){
					oldValue="";
				}
               //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.' )) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.StockTransfer._price_add = oldValue;
                }
            });
		}
	};
});


StockTransfer_app.controller('StockTransfer_Controller', function ($scope) {
    var sample_StockTransfer = { _items: [{ itemid: 0, _st_codePartNo: 0, _codePartNo_desc: '', _iinv_no: 0,_iinv_no_add: 0, _bal_qty: 0, _issued_qty: 0, _price: 0,
        _amt: 0, _ed_percent: 0, _ed_perUnit: 0, _ed_amt: 0, _entryId: 0, _edu_percent: 0, edu_amt: 0, _cvd_percent: 0, _cvd_amt: 0
    }]
    };
    $scope.StockTransfer = sample_StockTransfer;
    $scope.addItem = function () {
        var issuedQty = parseInt($scope.StockTransfer._issued_qty_add);
        if (issuedQty > 0) {
            //alert($scope.StockTransfer._amt_add);

            $scope.StockTransfer.itemid = $("#itemid").val();
            $scope.StockTransfer._code_part_no_add = $("#_code_part_no_add").val();
            $scope.StockTransfer._item_descp_add = $("#_item_descp_add").val();
            $scope.StockTransfer._discount=$("#_discount").val();
            $scope.StockTransfer._ed_percent=$("#ed_percent").val();

            $scope.StockTransfer._items.push({ itemid: $scope.StockTransfer.itemid, _st_codePartNo: $scope.StockTransfer._code_part_no_add, _codePartNo_desc: $scope.StockTransfer._item_descp_add,
                _iinv_no: $scope.StockTransfer._iinv_no,_iinv_no_add: $scope.StockTransfer._iinv_no_add, _bal_qty: $scope.StockTransfer._bal_qty_add, _issued_qty: $scope.StockTransfer._issued_qty_add,
                _price: $scope.StockTransfer._price_add, _amt: $scope.StockTransfer._amt_add, _ed_percent: $scope.StockTransfer._ed_percent_add, _ed_perUnit: $scope.StockTransfer._ed_perUnit_add,
                _ed_amt: $scope.StockTransfer._ed_amt_add, _entryId: $scope.StockTransfer._entryId_add, _edu_percent: $scope.StockTransfer._edu_percent_add, edu_amt: $scope.StockTransfer.edu_amt,
                _cvd_percent: $scope.StockTransfer._cvd_percent_add, _cvd_amt: $scope.StockTransfer._cvd_amt_add
            });

            var k = 0;
            var TaxableAmount =0.00;
            $scope.StockTransfer._total_ed = 0;
            $scope.StockTransfer._total_amt = 0;



            var edu_amt = 0.00;
            $scope.StockTransfer.basic_amount = 0.00;

            while (k < $scope.StockTransfer._items.length) {
                // calculate total ED amount
                $scope.StockTransfer._total_ed = parseFloat($scope.StockTransfer._items[k]["_ed_amt"]) + parseFloat($scope.StockTransfer._total_ed);
                $scope.StockTransfer._total_amt = parseFloat($scope.StockTransfer._items[k]["_amt"]) + parseFloat($scope.StockTransfer._total_amt);
                edu_amt = parseFloat(edu_amt) + parseFloat($scope.StockTransfer._items[k]["edu_amt"]);
                $scope.StockTransfer.basic_amount = parseFloat($scope.StockTransfer.basic_amount) + parseFloat($scope.StockTransfer._items[k]["_amt"]);
                k++
            }
            //alert($scope.StockTransfer._Inclusive_Tag);
            //alert("basic_amount :-"+$scope.StockTransfer.basic_amount);
            //alert("_total_ed :-" + $scope.StockTransfer._total_ed);

             if($scope.StockTransfer._discount==""){
                if ($scope.StockTransfer._Inclusive){
                    TaxableAmount = parseFloat($scope.StockTransfer.basic_amount) ;
                }else{
                    TaxableAmount = parseFloat($scope.StockTransfer.basic_amount) + parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
                }
             }else{
                if ($scope.StockTransfer._Inclusive){
                    TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100);
                }else{
                    TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100) + parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
                }
             }

             //var TaxableAmount = parseFloat($scope.StockTransfer.basic_amount) + parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
             // Taxable amount = basic amount + ed amount + edu amount - discount
             //alert("TaxableAmount :-" + TaxableAmount);
            // Pay Amount = Taxable amount + sale Tax + freight_amount
            var PayAmount = parseFloat(TaxableAmount);

            // Bill val = PayAmount + incidental_amount
            $scope.StockTransfer._total_amt = (parseFloat(PayAmount)).toFixed(2);
        $scope.StockTransfer._total_ed = (parseFloat($scope.StockTransfer._total_ed)).toFixed(2);



                     $scope.StockTransfer.itemid="";
	              $scope.StockTransfer._code_part_no_add="";
	              $scope.StockTransfer._item_descp_add  ="";

	              $scope.StockTransfer._iinv_no_add="";
	              $scope.StockTransfer._iinv_no="";
	              $scope.StockTransfer._bal_qty_add="";
	              $scope.StockTransfer._issued_qty_add="";
	              $scope.StockTransfer._price_add="";
	              $scope.StockTransfer._amt_add="";
	              $scope.StockTransfer._ed_percent_add="";
	              $scope.StockTransfer._ed_perUnit_add="";
	              $scope.StockTransfer._ed_amt_add="";
	              $scope.StockTransfer._entryId_add="";
	              $scope.StockTransfer._edu_percent_add="";
	              $scope.StockTransfer.edu_amt="";
	              $scope.StockTransfer._cvd_percent_add="";
                      $scope.StockTransfer._cvd_amt_add="";

        }
        else {
            alert("amount and issued quantity naver be blank.");
        }
        //alert("here");


    }


    $scope.DeleteItem = function (item) {
        $scope.StockTransfer._items.splice($scope.StockTransfer._items.indexOf(item), 1);
        var k = 0;
        $scope.StockTransfer._total_ed = 0;
        $scope.StockTransfer._total_amt = 0;

        var edu_amt = 0.00;
        var basic_amount = 0.00;

        while (k < $scope.StockTransfer._items.length) {
            // calculate total ED amount
            $scope.StockTransfer._total_ed = parseFloat($scope.StockTransfer._items[k]["_ed_amt"]) + parseFloat($scope.StockTransfer._total_ed);


            edu_amt = parseFloat(edu_amt) + parseFloat($scope.StockTransfer._items[k]["edu_amt"]);

            basic_amount = parseFloat(basic_amount) + parseFloat($scope.StockTransfer._items[k]["_amt"]);
            k++
        }
        //alert("basic_amount :-" + basic_amount);
        //alert("_total_ed :-" + $scope.StockTransfer._total_ed);
        //alert("edu_amt :-" + edu_amt);
        // Taxable amount = basic amount + ed amount + edu amount - discount
        var TaxableAmount = parseFloat(basic_amount) + parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
        //alert("TaxableAmount :-" + TaxableAmount);

        // Pay Amount = Taxable amount + sale Tax + freight_amount
        var PayAmount = parseFloat(TaxableAmount);

        // Bill val = PayAmount + incidental_amount
        $scope.StockTransfer._total_amt = (parseFloat(PayAmount)).toFixed(2);
        $scope.StockTransfer._total_ed = (parseFloat($scope.StockTransfer._total_ed)).toFixed(2);
    }
    $scope.getPartNo = function () {
        if ($scope.StockTransfer._stPrincipalId > 0) {
            var TYPE = "SELECT";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, PRINCIPALID: $scope.StockTransfer._stPrincipalId },
                    //cache: false,
                    success: function (jsondata) {
                        $('#_code_part_no_add').empty();
                        $("#_code_part_no_add").append("<option value='0'>Select Item</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj;
                            for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#_code_part_no_add").append("<option value=\"" + obj._item_id + "\">" + obj._item_code_partno + "</option>");
                            }
                        }
                    }

                });
            }
        }
    }

    $scope.getinv_rltInfo = function () {
        $scope.StockTransfer._iinv_no = $("#iinv_no_add option:selected").text();
        $scope.StockTransfer.itemid=$("#itemid").val();
        //alert($scope.StockTransfer.itemid);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
            type: "POST",
            data: { TYP: "SELECTINVOICERLTINFO_ST", CODEPART: $scope.StockTransfer.itemid, INVNO: $scope.StockTransfer._iinv_no_add },

            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);

                $scope.$apply(function () {
                    //alert(jsondata);
                    $scope.StockTransfer._issued_qty_add = '';
                    $scope.StockTransfer._bal_qty_add = objs[0]._bal_qty;
					$scope.StockTransfer._price_add = objs[0]._price_add;
                    $scope.StockTransfer._ed_percent_add = objs[0]._ed_percent;
                    $scope.StockTransfer._ed_amt_add = objs[0]._ed_amount;
                    $scope.StockTransfer._ed_perUnit_add = objs[0]._ed_perUnit;

                    $scope.StockTransfer._entryId_add = objs[0]._entryId;
                    $scope.StockTransfer._edu_percent_add = objs[0]._edu_percent;
 
                    $scope.StockTransfer._cvd_percent_add = objs[0]._cvd_percent;
                    $scope.StockTransfer._cvd_amt_add = objs[0]._cvd_amt;



                });
            }
        });
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
            type: "POST",
            data: { TYP: "SELECTINVOICE_BAlQTY", incomingInvNo: $scope.StockTransfer._iinv_no_add, incomingInvTyp: "E" },

            success: function (jsondata) {

                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
                $scope.$apply(function () {
                    //$scope.StockTransfer._bal_qty_add = objs[0].ExciseQty;


                });

            }
        });
    }
    $scope.calEdAmt = function (){
    	    var quantity = parseFloat($scope.StockTransfer._issued_qty_add);
    	    var edperunit = parseFloat($scope.StockTransfer._ed_perUnit_add);
    	    if (quantity >= 0 && edperunit>=0) {
    		//$scope.StockTransfer._ed_amt_add="3";
    		$scope.StockTransfer._ed_amt_add =( parseFloat($scope.StockTransfer._issued_qty_add) * parseFloat($scope.StockTransfer._ed_perUnit_add)).toFixed(2);
                $scope.StockTransfer.edu_amt =(( parseFloat($scope.StockTransfer._ed_amt_add) * parseFloat($scope.StockTransfer._edu_percent_add))/100).toFixed(2);
    	    }
    }


    $scope.add_discount = function () {
                   var TaxableAmount=0.00;
                   //alert($scope.StockTransfer._items.length);

                   if($scope.StockTransfer._Inclusive) {
                       $scope.StockTransfer._Inclusive_Tag="Y";
                   }else{
                       $scope.StockTransfer._Inclusive_Tag="N";
                   }

                 if ($scope.StockTransfer._items.length >0) {
                     var k = 0;
                     var TaxableAmount =0.00;
                     $scope.StockTransfer._total_ed = 0;
                     $scope.StockTransfer._total_amt = 0;
                     var edu_amt = 0.00;
                     $scope.StockTransfer.basic_amount = 0.00;
                     while (k < $scope.StockTransfer._items.length) {
                       // calculate total ED amount
                       $scope.StockTransfer._total_ed = parseFloat($scope.StockTransfer._items[k]["_ed_amt"]) + parseFloat($scope.StockTransfer._total_ed);
                       $scope.StockTransfer._total_amt = parseFloat($scope.StockTransfer._items[k]["_amt"]) + parseFloat($scope.StockTransfer._total_amt);
                       edu_amt = parseFloat(edu_amt) + parseFloat($scope.StockTransfer._items[k]["edu_amt"]);
                       $scope.StockTransfer.basic_amount = parseFloat($scope.StockTransfer.basic_amount) + parseFloat($scope.StockTransfer._items[k]["_amt"]);
                       k++
                     }
                   if($scope.StockTransfer._Inclusive) {
                     if($scope.StockTransfer._discount==""){
                         TaxableAmount = parseFloat($scope.StockTransfer.basic_amount) ;
                     }else{
                         TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100) ;
                     }
                   } else {
                     if($scope.StockTransfer._discount==""){
                         TaxableAmount = parseFloat($scope.StockTransfer.basic_amount)+parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
                     }else{
                         TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100)+ parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
                     }
                   }
                    $scope.StockTransfer._total_amt = (parseFloat(TaxableAmount)).toFixed(2);
                    $scope.StockTransfer._total_ed=(parseFloat($scope.StockTransfer._total_ed)).toFixed(2);
          }
    }
    $scope.inclusiveTag = function () {
            var TaxableAmount=0.00;
            //alert($scope.StockTransfer._items.length);

            if($scope.StockTransfer._Inclusive) {
                $scope.StockTransfer._Inclusive_Tag="Y";
            }else{
                $scope.StockTransfer._Inclusive_Tag="N";
            }

          if ($scope.StockTransfer._items.length >0) {
              var k = 0;
              var TaxableAmount =0.00;
              $scope.StockTransfer._total_ed = 0;
              $scope.StockTransfer._total_amt = 0;
              var edu_amt = 0.00;
              $scope.StockTransfer.basic_amount = 0.00;
              while (k < $scope.StockTransfer._items.length) {
                // calculate total ED amount
                $scope.StockTransfer._total_ed = parseFloat($scope.StockTransfer._items[k]["_ed_amt"]) + parseFloat($scope.StockTransfer._total_ed);
                $scope.StockTransfer._total_amt = parseFloat($scope.StockTransfer._items[k]["_amt"]) + parseFloat($scope.StockTransfer._total_amt);
                edu_amt = parseFloat(edu_amt) + parseFloat($scope.StockTransfer._items[k]["edu_amt"]);
                $scope.StockTransfer.basic_amount = parseFloat($scope.StockTransfer.basic_amount) + parseFloat($scope.StockTransfer._items[k]["_amt"]);
                k++
              }
            if($scope.StockTransfer._Inclusive) {
              if($scope.StockTransfer._discount==""){
                  TaxableAmount = parseFloat($scope.StockTransfer.basic_amount) ;
              }else{
                  TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100) ;
              }
            } else {
              if($scope.StockTransfer._discount==""){
                  TaxableAmount = parseFloat($scope.StockTransfer.basic_amount)+parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
              }else{
                  TaxableAmount = (((parseFloat($scope.StockTransfer.basic_amount))*(100-parseFloat($scope.StockTransfer._discount)))/100)+ parseFloat($scope.StockTransfer._total_ed) + parseFloat(edu_amt);
              }
            }
             $scope.StockTransfer._total_amt = (parseFloat(TaxableAmount)).toFixed(2);
             $scope.StockTransfer._total_ed=(parseFloat($scope.StockTransfer._total_ed)).toFixed(2);
          }

    }

    $scope.SaveStockTransfer = function () {
        var TYP="";
        //alert($scope.StockTransfer._stId);
        if($scope.StockTransfer._stId==""){
		   TYP="INSERT";
		   
		}else{
			TYP="UPDATE";
			
		}
        $("#btnsave").hide();
        
       var principalid =  $("#principalid").val();
        if (principalid == 0 || principalid == "" || principalid == 'NULL' ) {
            alert("Please select Principal");
            return;
        }
        
        if ($scope.StockTransfer._items.length <= 0 && $scope.StockTransfer._stId=="") {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $scope.StockTransfer._st_time = $("#st_time").val();
        $scope.StockTransfer._dispatch_time = $("#dispatchtime").val();

        $scope.StockTransfer._stBuyerId = $("#buyerid").val();
        $scope.StockTransfer._stPrincipalId = $("#principalid").val();
        $scope.StockTransfer._stSupplrId = $("#supplierid").val();
        $scope.StockTransfer._stInvDate = $("#invdate").val();
        if ($scope.StockTransfer._Supplier_stage_1) {
            $scope.StockTransfer._Supplier_stage = "1";
        }
        else if ($scope.StockTransfer._Supplier_stage_2) {
            $scope.StockTransfer._Supplier_stage = "2";
        }
        else if ($scope.StockTransfer._Supplier_stage_F) {
            $scope.StockTransfer._Supplier_stage = "F";
        }
        var json_string = JSON.stringify($scope.StockTransfer);
        //alert(json_string);

        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
            type: "POST",
            data: { TYP: TYP, StockTransferData: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    $scope.StockTransfer = null;
                    location.href = "StockTransferView.php";
                });
            }
        });

    }
    
     $scope.ChangeRowOnUpdate = function (item) {
        var Rowindex = $scope.StockTransfer._items.indexOf(item);

        var stockQty = parseInt($scope.StockTransfer._items[Rowindex]['_bal_qty']);
        var issuedQty = parseInt($scope.StockTransfer._items[Rowindex]['_issued_qty']);
       
        if (issuedQty > stockQty) {
            $scope.StockTransfer._items[Rowindex]['_issued_qty'] = 0;
            alert("issued quantity naver be gretar then balance quantity.");
        }

        if (issuedQty >= 0) {
            $scope.StockTransfer._items[Rowindex]['_amt'] = parseInt(issuedQty) * parseFloat($scope.StockTransfer._items[Rowindex]['_price']);
            $scope.StockTransfer._items[Rowindex]['_ed_amt'] = parseFloat($scope.StockTransfer._items[Rowindex]['_ed_perUnit']) * parseInt(issuedQty);
            $scope.StockTransfer._items[Rowindex]['edu_amt'] = (parseFloat($scope.StockTransfer._items[Rowindex]['_ed_amt']) * parseFloat($scope.StockTransfer._items[Rowindex]['_edu_percent'])) / 100;
            $scope.StockTransfer._items[Rowindex]['_cvd_amt'] = parseFloat($scope.StockTransfer._items[Rowindex]['_cvd_amt']) * parseInt(issuedQty);
        }
        $scope.inclusiveTag();
    }  
    
    
    
    $scope.UpdateStockTransfer = function () {
        $scope.StockTransfer._st_time = $("#st_time").val();
        $scope.StockTransfer._st_time = $("#st_time").val();
        $scope.StockTransfer._dispatch_time = $("#dispatchtime").val();

        $scope.StockTransfer._stBuyerId = $("#buyerid").val();
        $scope.StockTransfer._stPrincipalId = $("#principalid").val();
        $scope.StockTransfer._stSupplrId = $("#supplierid").val();
        if ($scope.StockTransfer._Supplier_stage_1) {
            $scope.StockTransfer._Supplier_stage = $scope.StockTransfer._Supplier_stage_1;
        }
        else if ($scope.StockTransfer._Supplier_stage_2) {
            $scope.StockTransfer._Supplier_stage = $scope.StockTransfer._Supplier_stage_2;
        }
        else if ($scope.StockTransfer._Supplier_stage_F) {
            $scope.StockTransfer._Supplier_stage = $scope.StockTransfer._Supplier_stage_F;
        }
        var json_string = JSON.stringify($scope.StockTransfer);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
            type: "POST",
            data: { TYP: "UPDATE", StockTransferData: json_string },
            success: function (jsondata) {
                $scope.StockTransfer = null;
                location.href = "StockTransferView.php";
            }
        });

    }
    $scope.init = function (number) {
        $scope.StockTransfer._stId = number;
        if (number != "") {
            document.getElementById("btnsave").value="Update";
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
                type: "POST",
                data: { TYP: "SELECT", StockId: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(objs[0]._Inclusive_Tag);
                    //alert(jsondata);
                    $scope.StockTransfer = objs[0];
                    if (objs[0]._Supplier_stage == "1") {
                        $('#s1').attr("checked", "checked");
                        $scope.StockTransfer._Supplier_stage_1 = "1";
                    }
                    else if (objs[0]._Supplier_stage == "2") {
                        $scope.StockTransfer._Supplier_stage_2 = "2";
                        $('#s2').attr("checked", "checked");
                    }
                    else if (objs[0]._Supplier_stage == "F") {
                        $scope.StockTransfer._Supplier_stage_F = "F";
                        $('#s3').attr("checked", "checked");
                    }

                   if (objs[0]._Inclusive_Tag == "Y") {
                        $scope.StockTransfer._Inclusive = true;
                    }else{
                        $scope.StockTransfer._Inclusive =false;
                    }
                    $scope.$apply(function () {
                        $scope.StockTransfer._items = objs[0]._items;
                    });

                }
            });
        }
        else {
            document.getElementById("btnsave").value="Save";
            $scope.StockTransfer._items.splice($scope.StockTransfer._items.indexOf(0), 1);
            
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $("#ino").val(objs);
                    $scope.$apply(function () {
                        $scope.StockTransfer._stInvNo = objs;
                    });
                }
            });
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/StockTransfer_controller.php",
                type: "POST",
                data: { TYP: "SYSDATETIME" },
                success: function (jsondata) {
                    //alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(objs+"|"+objs.substring(0,10)+"|"+objs.substring(11,16));
                    //$("#invdate").val(objs);
                    $scope.$apply(function () {
                        $scope.StockTransfer._stInvDate = objs.substring(0,10);
                        $scope.StockTransfer._st_time = objs.substring(11,16);

                    });
                }
            });
        }
    }
    $scope.checkStock = function () {
        var stockQty = parseInt($scope.StockTransfer._bal_qty_add);
        var issuedQty = parseInt($scope.StockTransfer._issued_qty_add);
        if (issuedQty > stockQty) {
            $scope.StockTransfer._issued_qty_add = 0;
            alert("issued quantity naver be gretar then stock quantity or order quantity.");
        }
    }

    $scope.calEduAmt = function (){
      var edAmt = parseFloat($scope.StockTransfer._ed_amt_add);
        if (edAmt > 0) {
                      $scope.StockTransfer.edu_amt =(( parseFloat($scope.StockTransfer._ed_amt_add) * parseFloat($scope.StockTransfer._edu_percent_add))/100);
                      $scope.StockTransfer.edu_amt =(parseFloat($scope.StockTransfer.edu_amt)).toFixed(2);
        }
    }




    $scope.calAmt = function () {
        var issuedQty = parseInt($scope.StockTransfer._issued_qty_add);
        if (issuedQty > 0) {
            $scope.StockTransfer._amt_add = parseFloat($scope.StockTransfer._issued_qty_add) * parseFloat($scope.StockTransfer._price_add);
        }
    }

});

StockTransfer_app.directive('validNumber', function () {
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


StockTransfer_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('StockTransfer._issued_qty_add', function (newValue, oldValue) {

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
                    scope.StockTransfer._issued_qty_add = oldValue;
                }
            });
        }
    };
});


StockTransfer_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('StockTransfer._discount', function (newValue, oldValue) {

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
                    scope.StockTransfer._discount = oldValue;
                }
            });
        }
    };
});

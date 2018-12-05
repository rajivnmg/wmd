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
        //alert(value+"|"+data);
        $("#principalid").val(data);
        InvoiceExist($("#principalid").val(),$("#principal_invoice_no").val());
		jQuery.ajax({
			url: "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php",
			type: "post",
			data: { TYP: "INCOMING_PRINCIPAL_GST", PRINCIPALID: data },
			success: function (jsondata) {
				//alert(jsondata);
				var objs = jQuery.parseJSON(jsondata);
				if (jsondata != "[]") 
				{
					$('#principal_address').val(objs[0]._address);
					$('#principal_city').val(objs[0]._city);
					$('#principal_state').val(objs[0]._state);
					$('#principal_gstin').val(objs[0]._gstin);
				}else{
					$('#principal_address').val('');
					$('#principal_city').val('');
					$('#principal_state').val('');
					$('#principal_gstin').val('');
				}
				//alert($("#autocomplete-ajax-supplier").val());
				if($("#autocomplete-ajax-supplier").val() == '')
				{
					$("#autocomplete-ajax-supplier").prop('disabled', true);
					$("#autocomplete-ajax-supplier").css("background-color","gray");
					$("#supplier_unitid").val('0');
					$("#supplier_unitid").prop('disabled', true);
					$("#supplier_unitid").css("background-color","gray");
					$("#supplier_gstin").prop('disabled', true);
					$("#supplier_gstin").css("background-color","gray");
				}
				else
				{
					$("#principal_unitid").val('0');
					$("#principal_unitid").prop('disabled', true);
					$("#principal_unitid").css("background-color","gray");
					//$("#principal_gstin").prop('disabled', true);
					//$("#principal_gstin").css("background-color","gray");
				}
			}
		});
		
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
				//                    var obj;
				//                    for (var i = 0; i < objs.length; i++) {
				//                        var obj = objs[i];
				//                        ItemList[obj._item_id] = obj._item_code_partno;
				//                    }
				                    CallToItem(ItemList);
                }
            }
        });
    }
    else {

    }
}

function NonePrincipal() {
    $('#item_master').empty();
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
		jQuery.ajax({
			url: "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php",
			type: "post",
			data: { TYP: "INCOMING_SUPPLIER_GST", SUPPLIERID: data },
			success: function (jsondata) {
				//alert(jsondata);
				var objs = jQuery.parseJSON(jsondata);
				if (jsondata != "[]") 
				{
					$('#supplier_address').val(objs[0]._address);
					$('#supplier_city').val(objs[0]._city);
					$('#supplier_state').val(objs[0]._state);
					$('#supplier_gstin').val(objs[0]._gstin);
				}else{
					$('#supplier_address').val('');
					$('#supplier_city').val('');
					$('#supplier_state').val('');
					$('#supplier_gstin').val('');
				}
				$("#principal_unitid").val('0');
				$("#principal_unitid").prop('disabled', true);
				$("#principal_unitid").css("background-color","gray");
				//$("#principal_gstin").prop('disabled', true);
				//$("#principal_gstin").css("background-color","gray");
			}
		});
    }
}

function NoneSupplier() {
    $("#supplierid").val(0);
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
			var principal_id = '', supplierID = '';
			if($('#principalid').val() != '')
			{
				principal_id = $('#principalid').val();
			}
			if($('#supplierid').val() != '') {
				supplierID = $('#supplierid').val();
			}
			if($('#supplierid').val() == '' && $('#principalid').val() == '') {
				alert('Select either Principal or Supplier');
			}
			//alert(principal_supplier_id + ' <--> '+ suggestion.value);
			GetGSTRates(principal_id,suggestion.data, supplierID);
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
                //$("#item_descp").val(objs[0]._item_descp);
                $("#item_tarrief_heading").val(objs[0]._item_tarrif_heading);
                $("#item_unitname").val(objs[0]._unitname);
                $("#item_descp").val(objs[0]._item_descp);
                $("#ddlunit").val(objs[0]._unitname);
                $("#ddlunitid").val(objs[0]._unit_id);
            }
        });
    }
}

function NoneItem() {
    $("#itemid").val(0);
}

//*************************
function InvoiceExist(principalID,invoiceNo)
{ 
  var TYPE = "VINO";
  if (true){
   jQuery.ajax({   url: "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php",
   type: "POST",
   data: {
            TYP: TYPE,
            BUYERID:principalID, INVNO: invoiceNo
         },
         success: function (jsondata) {
           //alert(jsondata);
         var objs = jQuery.parseJSON(jsondata);
         var tot = parseInt(objs);
         if (tot > 0) {
         if($("#_invoiceid").val()=="" ||$("#_invoiceid").val()==null)
         {   
             alert("Invoice Number already exist");
             $("#principal_invoice_no").val("");
             return;
          }   
        }
       }
    });
  }
}

//**********************

var URL = "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php";
var method = "POST";
var Incoming_Invoice_Excise_App = angular.module('Incoming_Invoice_Excise_App', []);

Incoming_Invoice_Excise_App.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._pf_chrg', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._pf_chrg = oldValue.toFixed(2);
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._insurance', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._insurance = oldValue.toFixed(2);
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._freight', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._freight = oldValue.toFixed(2);
                }
            });
        }
    };
});


Incoming_Invoice_Excise_App.controller('Incoming_Invoice_Excise_Controller', ['$scope', '$http', function Incoming_Invoice_Excise_Controller($scope, $http) {

	var sample_incoming_invoice_excise = { _items: [{ _item_id: 0, _item_code_part_no: '', _itemID_descp: '', _hsn_code: '', _principal_qty: '', _supplier_qty: '', _itemID_unitname: '', _itemID_unitid: '', _basic_purchase_price: '', _total: '', _discount: '', _taxable_total: '', _cgst_rate: '', _cgst_amt: '', _sgst_rate: '', _sgst_amt: '', _igst_rate: '', _igst_amt: '', _batch_number: '', _expire_date: '', _landing_price: '', _total_landing_price: ''}] };

    $scope.incoming_invoice_excise = sample_incoming_invoice_excise;
	
	$scope.addItem = function () {
		
		$scope.incoming_invoice_excise._item_id = $("#itemid").val();
		$scope.incoming_invoice_excise._item_code_part_no = $("#item_master").val();
		$scope.incoming_invoice_excise._itemID_descp = $("#item_descp").val();
		$scope.incoming_invoice_excise._hsn_code = $("#hsn_code").val();
		$scope.incoming_invoice_excise._itemID_unitname = $("#item_unitname").val();
		$scope.incoming_invoice_excise._itemID_unitid = $("#ddlunitid").val();
		$scope.incoming_invoice_excise._cgst_rate = $("#cgst_rate").val();
		$scope.incoming_invoice_excise._sgst_rate = $("#sgst_rate").val();
		$scope.incoming_invoice_excise._igst_rate = $("#igst_rate").val();
		$scope.incoming_invoice_excise._expire_date = $("#expire_date").val();
		
        $scope.incoming_invoice_excise._items.push({ 
			_item_id: $scope.incoming_invoice_excise._item_id,
			_item_code_part_no: $scope.incoming_invoice_excise._item_code_part_no,
			_itemID_descp: $scope.incoming_invoice_excise._itemID_descp,
			_hsn_code: $scope.incoming_invoice_excise._hsn_code,
			_principal_qty: $scope.incoming_invoice_excise._principal_qty,
			_supplier_qty: $scope.incoming_invoice_excise._supplier_qty,
			_itemID_unitname: $scope.incoming_invoice_excise._itemID_unitname,
			_itemID_unitid: $scope.incoming_invoice_excise._itemID_unitid,
			_basic_purchase_price: $scope.incoming_invoice_excise._basic_purchase_price,
			_total: $scope.incoming_invoice_excise._total,
			_discount: $scope.incoming_invoice_excise._discount,
			_discounted_amt: $scope.incoming_invoice_excise._discounted_amt,
			_packing_percent: $scope.incoming_invoice_excise._packing_percent,
			_packing_amt: $scope.incoming_invoice_excise._packing_amt,
			_insurance_percent: $scope.incoming_invoice_excise._insurance_percent,
			_insurance_amt: $scope.incoming_invoice_excise._insurance_amt,
			_freight_percent: $scope.incoming_invoice_excise._freight_percent,
			_freight_amt: $scope.incoming_invoice_excise._freight_amt,
			_other_percent: $scope.incoming_invoice_excise._other_percent,
			_other_amt: $scope.incoming_invoice_excise._other_amt,
			_taxable_total: $scope.incoming_invoice_excise._taxable_total,
			_cgst_rate: $scope.incoming_invoice_excise._cgst_rate,  
			_cgst_amt: $scope.incoming_invoice_excise._cgst_amt,
			_sgst_rate: $scope.incoming_invoice_excise._sgst_rate,
			_sgst_amt: $scope.incoming_invoice_excise._sgst_amt,
			_igst_rate: $scope.incoming_invoice_excise._igst_rate,
			_igst_amt: $scope.incoming_invoice_excise._igst_amt,
			_batch_number: $scope.incoming_invoice_excise._batch_number,
			_expire_date: $scope.incoming_invoice_excise._expire_date,
			_landing_price: $scope.incoming_invoice_excise._landing_price,
			_total_landing_price: $scope.incoming_invoice_excise._total_landing_price
		});

        $scope.incoming_invoice_excise._item_id = "";
        $scope.incoming_invoice_excise._item_code_part_no = "";
        $scope.incoming_invoice_excise._itemID_descp = "";
        $scope.incoming_invoice_excise._hsn_code = "";
        $scope.incoming_invoice_excise._principal_qty = "";
        $scope.incoming_invoice_excise._supplier_qty = "";
        $scope.incoming_invoice_excise._itemID_unitname = "";
        $scope.incoming_invoice_excise._itemID_unitid = "";
        $scope.incoming_invoice_excise._basic_purchase_price = "";
        $scope.incoming_invoice_excise._total = "";
        $scope.incoming_invoice_excise._discount = "";
		$scope.incoming_invoice_excise._discounted_amt = "";
		$scope.incoming_invoice_excise._packing_percent = "";
		$scope.incoming_invoice_excise._packing_amt = "";
		$scope.incoming_invoice_excise._insurance_percent = "";
		$scope.incoming_invoice_excise._insurance_amt = "";
		$scope.incoming_invoice_excise._freight_percent = "";
		$scope.incoming_invoice_excise._freight_amt = "";
		$scope.incoming_invoice_excise._other_percent = "";
		$scope.incoming_invoice_excise._other_amt = "";
        $scope.incoming_invoice_excise._taxable_total = "";
        $scope.incoming_invoice_excise._cgst_rate = "";
        $scope.incoming_invoice_excise._cgst_amt = "";
        $scope.incoming_invoice_excise._sgst_rate = "";
        $scope.incoming_invoice_excise._sgst_amt = "";
        $scope.incoming_invoice_excise._igst_rate = "";
        $scope.incoming_invoice_excise._igst_amt = "";
        $scope.incoming_invoice_excise._batch_number = "";
        $scope.incoming_invoice_excise._expire_date = "";
        $scope.incoming_invoice_excise._landing_price = "";
        $scope.incoming_invoice_excise._total_landing_price = "";
        
        $("#item_tarrief_heading").val("");
        $("#item_descp").val("");
        $("#item_unitname").val("");
        $("#ddlunit").val("");
        $scope.getLanding_Price();
    }
	
    $scope.removeItem = function (item) {
        $scope.incoming_invoice_excise._items.splice($scope.incoming_invoice_excise._items.indexOf(item), 1);
        $scope.getLanding_Price();
    }
	
	 $scope.AddIncomingInvoiceExcise = function (number) {
	
    	if(number!="")
    	{
			return;
		}
        if ($scope.incoming_invoice_excise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $("#btnsave").hide();
        $scope.incoming_invoice_excise._principal_inv_date = $("#principal_invoice_date").val();
        $scope.incoming_invoice_excise._supplier_inv_date = $("#supplier_invoice_date").val();
        $scope.incoming_invoice_excise._rece_date = $("#txtreceiveddate").val();
        $scope.incoming_invoice_excise._principal_Id = $("#principalid").val();
        $scope.incoming_invoice_excise._supplier_Id = $("#supplierid").val();
		$scope.incoming_invoice_excise.ms = $("#marketsegment").val();
		$scope.incoming_invoice_excise._dnt_supply = $("#dnt_supply").val();
		$scope.incoming_invoice_excise._principal_address = $("#principal_address").val();
		$scope.incoming_invoice_excise._principal_city = $("#principal_city").val();
		$scope.incoming_invoice_excise._principal_state = $("#principal_state").val();
		$scope.incoming_invoice_excise._principal_gstin = $("#principal_gstin").val();
		$scope.incoming_invoice_excise._supplier_address = $("#supplier_address").val();
		$scope.incoming_invoice_excise._supplier_city = $("#supplier_city").val();
		$scope.incoming_invoice_excise._supplier_state = $("#supplier_state").val();
		$scope.incoming_invoice_excise._supplier_gstin = $("#supplier_gstin").val();
		
		if($scope.incoming_invoice_excise._principal_Id != '' && $scope.incoming_invoice_excise._principal_gstin == ''){
			alert("Principal Gstin can not blank");
            return;
		} 
		
		if($scope.incoming_invoice_excise._supplier_Id != '' && $scope.incoming_invoice_excise._supplier_gstin == ''){
			alert("Supplier Gstin can not blank");
            return;
		} 
		
        InvoiceExist($scope.incoming_invoice_excise._principalID,$scope.incoming_invoice_excise._principal_inv_no);
        var json_string = JSON.stringify($scope.incoming_invoice_excise);
		
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", INCOMING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
				if(jsondata == 'Success'){
					$scope.$apply(function () {
						alert("Entry saved Successfully!");					
						$scope.incoming_invoice_excise = null;
						location.href = "view_incoming_invoice_excise.php";
					});
				}
            }
        });
    }
   
    $scope.Update = function () {
        if ($scope.incoming_invoice_excise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $("#btnupdate").hide();
		$scope.incoming_invoice_excise._principal_inv_date = $("#principal_invoice_date").val();
        $scope.incoming_invoice_excise._supplier_inv_date = $("#supplier_invoice_date").val();
        $scope.incoming_invoice_excise._rece_date = $("#txtreceiveddate").val();
        $scope.incoming_invoice_excise._principal_Id = $("#principalid").val();
        $scope.incoming_invoice_excise._supplier_Id = $("#supplierid").val();
		//$scope.incoming_invoice_excise.ms = $("#marketsegment").val();
		$scope.incoming_invoice_excise._dnt_supply = $("#dnt_supply").val();
		$scope.incoming_invoice_excise._principal_address = $("#principal_address").val();
		$scope.incoming_invoice_excise._principal_city = $("#principal_city").val();
		$scope.incoming_invoice_excise._principal_state = $("#principal_state").val();
		$scope.incoming_invoice_excise._principal_gstin = $("#principal_gstin").val();
		$scope.incoming_invoice_excise._supplier_address = $("#supplier_address").val();
		$scope.incoming_invoice_excise._supplier_city = $("#supplier_city").val();
		$scope.incoming_invoice_excise._supplier_state = $("#supplier_state").val();
		$scope.incoming_invoice_excise._supplier_gstin = $("#supplier_gstin").val();
		/*if($scope.incoming_invoice_excise._principal_Id != '' && $scope.incoming_invoice_excise._principal_gstin == ''){
			alert("Principal Gstin can not blank");
            return;
		} 
		
		if($scope.incoming_invoice_excise._supplier_Id != '' && $scope.incoming_invoice_excise._supplier_gstin == ''){
			alert("Supplier Gstin can not blank");
            return;
		} */
        var json_string = JSON.stringify($scope.incoming_invoice_excise);
		console.log(json_string);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", INCOMING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
				//alert(jsondata);
				//alert(URL);
                alert("Entry updated Successfully!");
                $scope.incoming_invoice_excise = null;
                location.href = "view_incoming_invoice_excise.php";
            }
        });
    }
    //****************
   
     $scope.validateInvoiceNumber= function () {
       $scope.incoming_invoice_excise._principalID= $("#principalid").val();
       InvoiceExist($scope.incoming_invoice_excise._principalID,$scope.incoming_invoice_excise._principal_inv_no);
        
    }
    
    //********************
    $scope.init = function (number) {
        //alert(number);
        $scope.incoming_invoice_excise._invoiceid = number;
        $("#_invoiceid").val(number);
        if (number > 0) {
            $("#btnsave").hide();
            $("#principal_invoice_no").attr('readonly','true');
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT", IncomingInvoiceExciseNum: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    $scope.$apply(function () {
                        $scope.incoming_invoice_excise = objs[0];
                        $scope.incoming_invoice_excise._items = objs[0]._itmes;
                        $scope.getprincipal();
                        //alert(objs[0]._entry_Id+"|"+objs[0]._principal_name+"|"+objs[0]._principal_Id);
                        
                        ActionOnPrincipal(objs[0]._principal_name, objs[0]._principal_Id);
						ActionOnSupplier(objs[0]._supplier_name, objs[0]._supplier_Id);
                        jQuery.ajax({
                            url: URL,
                            type: "POST",
                            data: { TYP: "FIND_INVOIC_IN_OGINV_ST", INVOICENO: objs[0]._entry_Id },
                            success: function (jsondata) {
                                var objs = jQuery.parseJSON(jsondata);
                                //alert(objs);
                                if (objs > 0) {
                                    alert("Outgoing Invoice or Stocktransfer or both has been generated from this invoice!");
                                    //$("#btnupdate").hide();
									
                                }
								$("#btnupdate").show();
                            }
                        });
                    });
                }
            });

        }
        else {
            $("#btnupdate").hide();
            $scope.incoming_invoice_excise._items.splice($scope.incoming_invoice_excise._items.indexOf(0), 1);
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $("#invoice_intry_id").val(objs._entry_Id);
                    $("#invoice_mapping_id").val(objs._mapping_Id);
                    $scope.$apply(function () {
                        $scope.incoming_invoice_excise._entry_Id = objs._entry_Id;
                        $scope.incoming_invoice_excise._mapping_Id = objs._mapping_Id;
                    });
                }
            });
        }
    }
    
    $scope.getprincipal = function () {
        var TYPE = "SELECT";
        $scope.itemmaster = [{}];
        var responsePromise = $http.get("../../Controller/Master_Controller/Item_Controller.php?TYP=SELECT&PRINCIPALID=" + $scope.incoming_invoice_excise._principal_Id);
        responsePromise.success(function (data, status, headers, config) {
            $scope.itemmaster = data;
        });
        responsePromise.error(function (data, status, headers, config) {
            alert("AJAX failed!");
        });
    }
    
    $scope.itemdesc = function () {
        if ($scope.incoming_invoice_excise._item_code_part_no > 0) {
            var TYPE = "LOADITEM";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.incoming_invoice_excise._item_code_part_no },
                    //cache: false,
                    success: function (jsondata) {
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            $scope.incoming_invoice_excise._itemID_terrif_heading = objs[0]._item_tarrif_heading;
                            $scope.incoming_invoice_excise._unit_name = objs[0]._unitname;
                            $scope.incoming_invoice_excise._itemID_descp = objs[0]._item_descp;
                            $scope.incoming_invoice_excise._itemID_unitname = objs[0]._unitname;
                            $scope.incoming_invoice_excise._itemID_unitid = objs[0]._unit_id;
                        });

                    }
                });
            }
        }
        else {
            $scope.incoming_invoice_excise._itemID_terrif_heading = "";
            $scope.incoming_invoice_excise._item_descp = "";
            $scope.incoming_invoice_excise._price_per_unit = "";
        }
    }

    
    $scope.getLanding_Price = function () {
        var pf_chrg = 0, insurance = 0, freight = 0;
        /* if (!isNaN($scope.incoming_invoice_excise._pf_chrg) && $scope.incoming_invoice_excise._pf_chrg != "" && $scope.incoming_invoice_excise._pf_chrg != null) {
            pf_chrg = parseFloat($scope.incoming_invoice_excise._pf_chrg).toFixed(2);
        }
        if (!isNaN($scope.incoming_invoice_excise._insurance) && $scope.incoming_invoice_excise._insurance != "" && $scope.incoming_invoice_excise._insurance != null) {
            insurance = parseFloat($scope.incoming_invoice_excise._insurance).toFixed(2);
        }
        if (!isNaN($scope.incoming_invoice_excise._freight) && $scope.incoming_invoice_excise._freight != "" && $scope.incoming_invoice_excise._freight != null) {
            freight = parseFloat($scope.incoming_invoice_excise._freight).toFixed(2);
        } */  
        var m = 0;
        var TotalAmount = 0
		while (m < $scope.incoming_invoice_excise._items.length) {
			TotalAmount = TotalAmount + parseFloat($scope.incoming_invoice_excise._items[m]['_total_landing_price']);
			m++;
		}
		TotalAmount =  parseFloat(TotalAmount) + parseFloat(pf_chrg) + parseFloat(insurance) + parseFloat(freight);
		$scope.incoming_invoice_excise._total_bill_val = TotalAmount.toFixed(2);
    }

	/* BOF for Adding GST by Ayush Giri on 22-06-2017 */
	$scope.getTotalAmt = function () {
		var basic_price = $scope.incoming_invoice_excise._basic_purchase_price;
		var qtyp = $scope.incoming_invoice_excise._principal_qty;
		var qtys = $scope.incoming_invoice_excise._supplier_qty;
		
		if(qtyp > 0)
		{
			$scope.incoming_invoice_excise._total = qtyp * basic_price;
			$scope.incoming_invoice_excise._supplier_qty = '0';
		}
		else if(qtys > 0)
		{
			$scope.incoming_invoice_excise._total = qtys * basic_price;
			$scope.incoming_invoice_excise._principal_qty = '0';
		}
		else
		{
			alert('Please enter quantity.');
		}
		
		$scope.incoming_invoice_excise._discount = '0';
		$scope.getDiscountedAmt();
	}
	
	$scope.getDiscountedAmt = function () {
		var discount_per = parseFloat($scope.incoming_invoice_excise._discount);
		var total_amt = parseFloat($scope.incoming_invoice_excise._total);
		var discounted_amt = parseFloat(((100 - discount_per)/100) * total_amt);
		$scope.incoming_invoice_excise._discounted_amt = discounted_amt.toFixed(2);
		$scope.incoming_invoice_excise._packing_percent = '0';
		$scope.getPackingAmt();
		$scope.incoming_invoice_excise._insurance_percent = '0';
		$scope.getInsuranceAmt();
		$scope.incoming_invoice_excise._freight_percent = '0';
		$scope.getFreightAmt();
		$scope.incoming_invoice_excise._other_percent = '0';
		$scope.getOtherAmt();
		$scope.getTaxableAmt();
	}
	
	$scope.getPackingAmt = function () {
		var packing_per = parseFloat($scope.incoming_invoice_excise._packing_percent);
		var discounted_amt = parseFloat($scope.incoming_invoice_excise._discounted_amt);
		var packing_amt = parseFloat(((packing_per)/100) * discounted_amt);
		$scope.incoming_invoice_excise._packing_amt = packing_amt.toFixed(2);
		$scope.getTaxableAmt();
	}
	
	$scope.getInsuranceAmt = function () {
		var insurance_per = parseFloat($scope.incoming_invoice_excise._insurance_percent);
		var discounted_amt = parseFloat($scope.incoming_invoice_excise._discounted_amt);
		var insurance_amt = parseFloat(((insurance_per)/100) * discounted_amt);
		$scope.incoming_invoice_excise._insurance_amt = insurance_amt.toFixed(2);
		$scope.getTaxableAmt();
	}
	
	$scope.getFreightAmt = function () {
		var freight_per = parseFloat($scope.incoming_invoice_excise._freight_percent);
		var discounted_amt = parseFloat($scope.incoming_invoice_excise._discounted_amt);
		var freight_amt = parseFloat(((freight_per)/100) * discounted_amt);
		$scope.incoming_invoice_excise._freight_amt = freight_amt.toFixed(2);
		$scope.getTaxableAmt();
	}
	
	$scope.getOtherAmt = function () {
		var other_per = parseFloat($scope.incoming_invoice_excise._other_percent);
		var discounted_amt = parseFloat($scope.incoming_invoice_excise._discounted_amt);
		var other_amt = parseFloat(((other_per)/100) * discounted_amt);
		$scope.incoming_invoice_excise._other_amt = other_amt.toFixed(2);
		$scope.getTaxableAmt();
	}
	
	$scope.getTaxableAmt = function () {
		var discounted_amt = parseFloat($scope.incoming_invoice_excise._discounted_amt);
		var packing_amt = parseFloat($scope.incoming_invoice_excise._packing_amt);
		var insurance_amt = parseFloat($scope.incoming_invoice_excise._insurance_amt);
		var freight_amt = parseFloat($scope.incoming_invoice_excise._freight_amt);
		var other_amt = parseFloat($scope.incoming_invoice_excise._other_amt);
		var taxable_amt = parseFloat(discounted_amt + packing_amt + insurance_amt + freight_amt + other_amt);
		
		var cgst_rate = parseFloat($("#cgst_rate").val());
		var sgst_rate = parseFloat($("#sgst_rate").val());
		var igst_rate = parseFloat($("#igst_rate").val());
		
		var cgst_amt = parseFloat((cgst_rate * taxable_amt)/100);
		var sgst_amt = parseFloat((sgst_rate * taxable_amt)/100);
		var igst_amt = parseFloat((igst_rate * taxable_amt)/100);
		
		var qtyp = $scope.incoming_invoice_excise._principal_qty;
		var qtys = $scope.incoming_invoice_excise._supplier_qty;
		var qty = 0;
		
		if(qtyp > 0)
		{
			qty = qtyp;
		}
		else if(qtys > 0)
		{
			qty = qtys;
		}
		else
		{
			alert('Please enter quantity.');
		}
		
		var total_landing_price = parseFloat(taxable_amt + cgst_amt + sgst_amt + igst_amt);
		var landing_price = parseFloat(total_landing_price/qty);
		
		$scope.incoming_invoice_excise._taxable_total = taxable_amt.toFixed(2);
		$scope.incoming_invoice_excise._cgst_amt = cgst_amt.toFixed(2);
		$scope.incoming_invoice_excise._sgst_amt = sgst_amt.toFixed(2);
		$scope.incoming_invoice_excise._igst_amt = igst_amt.toFixed(2);
		$scope.incoming_invoice_excise._landing_price = landing_price.toFixed(2);
		$scope.incoming_invoice_excise._total_landing_price = total_landing_price.toFixed(2);
	}
	/* EOF for Adding GST by Ayush Giri on 22-06-2017 */
} ]);


Incoming_Invoice_Excise_App.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            if (!ngModelCtrl) {
                return;
            }
            ngModelCtrl.$parsers.push(function (val) {
			//var clean = val.replace(/[a-zA-Z\s:]|[^\w\d\.]/, '');
				var clean = val.replace(/[^\d.]/g, ''); // Codefire Changes for float quantity 27.07.15
                //var clean = val.replace(/(\d*[.])?\d+/, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
        }
    }
});


Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._total_bill_val', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._total_bill_val = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._principal_qty', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._principal_qty = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._supplier_qty', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._supplier_qty = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._total_ass_value', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._total_ass_value = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._ed_percent', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._ed_percent = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._ed_amount', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._ed_amount = oldValue;
                }
            });
        }
    };
});


Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._ed_unit', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._ed_unit = oldValue;
                }
            });
        }
    };
});


Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._edu_cess_percent', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._edu_cess_percent = oldValue;
                }
            });
        }
    };
});
Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._edu_cess_amount', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._edu_cess_amount = oldValue;
                }
            });
        }
    };
});
Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._unit_ass_value', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._unit_ass_value = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._hedu_percent', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._hedu_percent = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._hedu_amount', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise._hedu_amount = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._cvd_percent', function (newValue, oldValue) {
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
                    scope.incoming_invoice_excise._cvd_percent = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._cvd_amount', function (newValue, oldValue) {
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
                    scope.incoming_invoice_excise._cvd_amount = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._basic_purchase_price', function (newValue, oldValue) {
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
                    scope.incoming_invoice_excise._basic_purchase_price = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._landing_price', function (newValue, oldValue) {
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
                    scope.incoming_invoice_excise._landing_price = oldValue;
                }
            });
        }
    };
});

Incoming_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise._total_landing_price', function (newValue, oldValue) {
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
                    scope.incoming_invoice_excise._total_landing_price = oldValue;
                }
            });
        }
    };
});

/* BOF to add GST Rates by Ayush Giri on 16-06-2017 */
function GetGSTRates(principal_id,item_code, supplierID){
	console.log(principal_id + ' I '+ item_code + 'S' + supplierID);
	if (item_code != "") {
		jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php", type: "POST",
			data: {
				TYP: "GETGST",
				ITEMID:item_code,
				//BUYER_ID:buyer_id,
				PRINCIPAL_ID:principal_id,
				SUPPLIER_ID:supplierID
			},
			success: function (jsondata) {
				 var objs1 = jQuery.parseJSON(jsondata);
				 if (objs1.length > 0) {
					 var objs = objs1[0];
					 $("#hsn_code").val(objs.HSN_CODE);
					 $("#cgst_rate").val(objs.CGST_RATE);
					 $("#sgst_rate").val(objs.SGST_RATE);
					 $("#igst_rate").val(objs.IGST_RATE);
					 $("#gst_rate").val(objs.GST_RATE);
				 }else{
					console.log('HSN code not found');
				 }
			}
		});
     }
}
/* EOF to add GST Rates by Ayush Giri on 16-06-2017 */

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
        //alert(value+"|"+data)
        $("#principalid").val(data);
         InvoiceExist($("#principalid").val(),$("#principal_invoice_no").val());
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
                //$("#item_descp").val(objs[0]._item_descp);
                $("#item_tarrief_heading").val(objs[0]._item_tarrif_heading);
                $("#item_unitname").val(objs[0]._unitname);
                $("#item_descp").val(objs[0]._item_descp);
                $("#ddlunit").val(objs[0]._unitname);
                $("#ddlunitid").val(objs[0]._unit_id);
            }
        });
    }
    else {
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
                    scope.incoming_invoice_excise._pf_chrg = oldValue;
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
                    scope.incoming_invoice_excise._insurance = oldValue;
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
                    scope.incoming_invoice_excise._freight = oldValue;
                }
            });
        }
    };
});
Incoming_Invoice_Excise_App.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('incoming_invoice_excise.SaleTaxAmount', function (newValue, oldValue) {

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
                    scope.incoming_invoice_excise.SaleTaxAmount = oldValue;
                }
            });
        }
    };
});


Incoming_Invoice_Excise_App.controller('Incoming_Invoice_Excise_Controller', ['$scope', '$http', function Incoming_Invoice_Excise_Controller($scope, $http) {

    var sample_incoming_invoice_excise = { _items: [{ _item_id: 0, _itemID_terrif_heading: '', _item_code_part_no: '', _itemID_descp: '', _itemID_unitid: '', _itemID_unitname: '', _principal_qty: '', _supplier_qty: '', _unit_name: '', _total_ass_value: '', _unit_ass_value: '', _ed_percent: '', _ed_amount: '', _ed_unit: '', _edu_cess_percent: '', _edu_cess_amount: '', _hedu_percent: 0, _hedu_amount: 0, _cvd_percent: '', _cvd_amount: '', _basic_purchase_price: '', _batch_number: '', _expire_date: '', _supplier_rg23d: '', _landing_price: '', _total_landing_price: ''}] };

    $scope.incoming_invoice_excise = sample_incoming_invoice_excise;

    $scope.addItem = function () {
        $scope.incoming_invoice_excise._expire_date = $("#expire_date").val();
        $scope.incoming_invoice_excise._unit_name = $("#ddlunit").val();
        $scope.incoming_invoice_excise._item_id = $("#itemid").val();
        $scope.incoming_invoice_excise._item_code_part_no = $("#item_master").val();
        $scope.incoming_invoice_excise._itemID_terrif_heading = $("#item_tarrief_heading").val();
        $scope.incoming_invoice_excise._itemID_unitname = $("#ddlunit").val();
        $scope.incoming_invoice_excise._itemID_unitid = $("#ddlunitid").val();
        $scope.incoming_invoice_excise._itemID_descp = $("#item_descp").val();

        $scope.incoming_invoice_excise._items.push({ _item_id: $scope.incoming_invoice_excise._item_id, _itemID_terrif_heading: $scope.incoming_invoice_excise._itemID_terrif_heading, _item_code_part_no: $scope.incoming_invoice_excise._item_code_part_no, _itemID_descp: $scope.incoming_invoice_excise._itemID_descp, _itemID_unitid: $scope.incoming_invoice_excise._itemID_unitid, _itemID_unitname: $scope.incoming_invoice_excise._itemID_unitname, _principal_qty: $scope.incoming_invoice_excise._principal_qty, _supplier_qty: $scope.incoming_invoice_excise._supplier_qty, _unit_name: $scope.incoming_invoice_excise._unit_name, _total_ass_value: $scope.incoming_invoice_excise._total_ass_value, _unit_ass_value: $scope.incoming_invoice_excise._unit_ass_value, _ed_percent: $scope.incoming_invoice_excise._ed_percent, _ed_amount: $scope.incoming_invoice_excise._ed_amount, _ed_unit: $scope.incoming_invoice_excise._ed_unit, _edu_cess_percent: $scope.incoming_invoice_excise._edu_cess_percent, _edu_cess_amount: $scope.incoming_invoice_excise._edu_cess_amount, _hedu_percent: $scope.incoming_invoice_excise._hedu_percent, _hedu_amount: $scope.incoming_invoice_excise._hedu_amount, _cvd_percent: $scope.incoming_invoice_excise._cvd_percent, _cvd_amount: $scope.incoming_invoice_excise._cvd_amount, _basic_purchase_price: $scope.incoming_invoice_excise._basic_purchase_price, _batch_number: $scope.incoming_invoice_excise._batch_number, _expire_date: $scope.incoming_invoice_excise._expire_date, _supplier_rg23d: $scope.incoming_invoice_excise._supplier_rg23d, _landing_price: $scope.incoming_invoice_excise._landing_price, _total_landing_price: $scope.incoming_invoice_excise._total_landing_price });

        $scope.incoming_invoice_excise._item_id = "";
        $scope.incoming_invoice_excise._itemID_terrif_heading = "";
        $scope.incoming_invoice_excise._item_code_part_no = "";
        $scope.incoming_invoice_excise._itemID_descp = "";
        $scope.incoming_invoice_excise._itemID_unitid = "";
        $scope.incoming_invoice_excise._itemID_unitname = "";
        $scope.incoming_invoice_excise._principal_qty = "";
        $scope.incoming_invoice_excise._supplier_qty = "";
        $scope.incoming_invoice_excise._unit_name = "";
        $scope.incoming_invoice_excise._total_ass_value = "";
        $scope.incoming_invoice_excise._unit_ass_value = "";
        $scope.incoming_invoice_excise._ed_percent = "";
        $scope.incoming_invoice_excise._ed_amount = "";
        $scope.incoming_invoice_excise._ed_unit = "";
        $scope.incoming_invoice_excise._edu_cess_percent = "";
        $scope.incoming_invoice_excise._edu_cess_amount = "";
        $scope.incoming_invoice_excise._hedu_percent = "";
        $scope.incoming_invoice_excise._hedu_amount = "";
        $scope.incoming_invoice_excise._cvd_percent = "";
        $scope.incoming_invoice_excise._cvd_amount = "";
        $scope.incoming_invoice_excise._basic_purchase_price = "";
        $scope.incoming_invoice_excise._batch_number = "";
        $scope.incoming_invoice_excise._expire_date = "";
        $scope.incoming_invoice_excise._supplier_rg23d = "";
        $scope.incoming_invoice_excise._landing_price = "";
        $scope.incoming_invoice_excise._total_landing_price = "";
        $("#item_tarrief_heading").val("");
        $("#item_descp").val("");
        $("#item_unitname").val("");
         $("#ddlunit").val("");
        $scope.getLanding_Price();
        //        var k = 0;
        //        $scope.incoming_invoice_excise._total_bill_val = 0;
        //        while (k < $scope.incoming_invoice_excise._items.length) {
        //            $scope.incoming_invoice_excise._total_bill_val = parseFloat($scope.incoming_invoice_excise._items[k]["_total_landing_price"]) + parseFloat($scope.incoming_invoice_excise._total_bill_val);
        //            k++
        //        }
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
        InvoiceExist($scope.incoming_invoice_excise._principalID,$scope.incoming_invoice_excise._principal_inv_no);
        var json_string = JSON.stringify($scope.incoming_invoice_excise);
            jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", INCOMING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    alert("Entry saved Successfully!");					
                    $scope.incoming_invoice_excise = null;
                    location.href = "view_incoming_invoice_excise.php";
                });
            }
			
        });
   }
   
   // rajiv commented to check and fix the doublr entry on double click 30-7-15
	/*  var scrollflag = true;
	if(scrollflag){
     $scope.AddIncomingInvoiceExcise = function (number) {
	 var scrollflag =  false;
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
        InvoiceExist($scope.incoming_invoice_excise._principalID,$scope.incoming_invoice_excise._principal_inv_no);
        var json_string = JSON.stringify($scope.incoming_invoice_excise);
            jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", INCOMING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    alert("Entry saved Successfully!");					
                    $scope.incoming_invoice_excise = null;
                    location.href = "view_incoming_invoice_excise.php";
                });
            }
			
        }).always(function(){
    scrollflag = true; //Reset the flag here
   });
   }
 }  */
 var isF = false;
    $scope.Update = function () {  	
        if ($scope.incoming_invoice_excise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $("#btnupdate").hide();
        var json_string = JSON.stringify($scope.incoming_invoice_excise);
		
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", INCOMING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
				//alert(URL);
                alert("Entry updated Successfully!");
                $scope.incoming_invoice_excise = null;
               // location.href = "view_incoming_invoice_excise.php";
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
 
                        jQuery.ajax({
                            url: URL,
                            type: "POST",
                            data: { TYP: "FIND_INVOIC_IN_OGINV_ST", INVOICENO: objs[0]._entry_Id },
                            success: function (jsondata) {
                                var objs = jQuery.parseJSON(jsondata);
                                //alert(objs);
                                if (objs > 0) {
                                    alert("Outgoing Invoice or Stocktransfer or both has been generated from this invoice!");
                                    $("#btnupdate").hide();
                                }
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

   $scope.getED_Unit = function () {
        if ($scope.incoming_invoice_excise._supplier_qty > 0) {
            $scope.incoming_invoice_excise._ed_unit = parseFloat($scope.incoming_invoice_excise._ed_amount) / parseFloat($scope.incoming_invoice_excise._supplier_qty);
             $scope.incoming_invoice_excise._ed_unit= $scope.incoming_invoice_excise._ed_unit.toFixed(3);
             if($scope.incoming_invoice_excise._ed_unit=='NaN')
             {
			 	 $scope.incoming_invoice_excise._ed_unit=0;
			 }
        }
        else if ($scope.incoming_invoice_excise._principal_qty > 0) {
            $scope.incoming_invoice_excise._ed_unit = parseFloat($scope.incoming_invoice_excise._ed_amount) / parseFloat($scope.incoming_invoice_excise._principal_qty);
            $scope.incoming_invoice_excise._ed_unit= $scope.incoming_invoice_excise._ed_unit.toFixed(3);
            if($scope.incoming_invoice_excise._ed_unit=='NaN')
            {
			 	 $scope.incoming_invoice_excise._ed_unit=0;
			}
           
        }
        else {
            $scope.incoming_invoice_excise._ed_unit = 0;
        }
    }

     $scope.getUnitAssValue = function () {
        if ($scope.incoming_invoice_excise._supplier_qty > 0) {
            $scope.incoming_invoice_excise._unit_ass_value = parseFloat($scope.incoming_invoice_excise._total_ass_value) / parseFloat($scope.incoming_invoice_excise._supplier_qty);
             $scope.incoming_invoice_excise._unit_ass_value= $scope.incoming_invoice_excise._unit_ass_value.fixed(3);
             if($scope.incoming_invoice_excise._unit_ass_value=='NaN')
             {
			 	$scope.incoming_invoice_excise._unit_ass_value=0;
			 }
        }
        else if ($scope.incoming_invoice_excise._principal_qty > 0) {
            $scope.incoming_invoice_excise._unit_ass_value = parseFloat($scope.incoming_invoice_excise._total_ass_value) / parseFloat($scope.incoming_invoice_excise._principal_qty);
            $scope.incoming_invoice_excise._unit_ass_value= $scope.incoming_invoice_excise._unit_ass_value.toFixed(3);
            if($scope.incoming_invoice_excise._unit_ass_value=='NaN')
             {
			 	$scope.incoming_invoice_excise._unit_ass_value=0;
			 }
        }
        else {
            $scope.incoming_invoice_excise._unit_ass_value = 0;
        }
    }
    $scope.getEDU_Cess_Amount = function () {
        $scope.incoming_invoice_excise._edu_cess_amount = (parseFloat($scope.incoming_invoice_excise._ed_amount) * parseFloat($scope.incoming_invoice_excise._edu_cess_percent)) / 100;
         $scope.incoming_invoice_excise._edu_cess_amount= $scope.incoming_invoice_excise._edu_cess_amount.toFixed(3);
         if($scope.incoming_invoice_excise._edu_cess_amount=='NaN')
         {
			$scope.incoming_invoice_excise._edu_cess_amount=0;
	     }
    }
    $scope.getHEDU_Amount = function () {
        $scope.incoming_invoice_excise._hedu_amount = (parseFloat($scope.incoming_invoice_excise._ed_amount) * parseFloat($scope.incoming_invoice_excise._hedu_percent)) / 100;
        $scope.incoming_invoice_excise._hedu_amount=$scope.incoming_invoice_excise._hedu_amount.toFixed(3);
         if($scope.incoming_invoice_excise._hedu_amount=='NaN')
         {
			$scope.incoming_invoice_excise._hedu_amount=0;
	     }
    }

    $scope.ChangeRowOnUpdate = function (item) {
        var Rowindex = $scope.incoming_invoice_excise._items.indexOf(item);

        if ($scope.incoming_invoice_excise._items[Rowindex]['_supplier_qty'] > 0) {
            $scope.incoming_invoice_excise._items[Rowindex]['_unit_ass_value'] = parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_total_ass_value']) / parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_supplier_qty']);
        }
        else if ($scope.incoming_invoice_excise._items[Rowindex]['_principal_qty'] > 0) {
            $scope.incoming_invoice_excise._items[Rowindex]['_unit_ass_value'] = parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_total_ass_value']) / parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_principal_qty']);
        }
        else {
            $scope.incoming_invoice_excise._items[Rowindex]['_unit_ass_value'] = 0;
        }


        if ($scope.incoming_invoice_excise._items[Rowindex]['_supplier_qty'] > 0) {
            $scope.incoming_invoice_excise._items[Rowindex]['_ed_unit'] = parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_ed_amount']) / parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_supplier_qty']);
        }
        else if ($scope.incoming_invoice_excise._items[Rowindex]['_principal_qty'] > 0) {
            $scope.incoming_invoice_excise._items[Rowindex]['_ed_unit'] = parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_ed_amount']) / parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_principal_qty']);
        }
        else {
            $scope.incoming_invoice_excise._items[Rowindex]['_ed_unit'] = 0;
        }

        $scope.incoming_invoice_excise._items[Rowindex]['_edu_cess_amount'] = (parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_ed_amount']) * parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_edu_cess_percent'])) / 100;
        $scope.incoming_invoice_excise._items[Rowindex]['_hedu_amount'] = (parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_ed_amount']) * parseFloat($scope.incoming_invoice_excise._items[Rowindex]['_hedu_percent'])) / 100;
        $scope.getLanding_Price();
    }
    $scope.getLanding_Price = function () {
        var index = 0;
        var Sum = 0.00;
        var num = 0;
        var Qty = 0;
        var Qtyp = 0;
        var Qtys = 0;
        while (num < $scope.incoming_invoice_excise._items.length) {

            if (!isNaN($scope.incoming_invoice_excise._items[num]['_supplier_qty'])) {
                Qtys = parseFloat($scope.incoming_invoice_excise._items[num]['_supplier_qty']);
            }
            if (!isNaN($scope.incoming_invoice_excise._items[num]['_principal_qty'])) {
                Qtyp = parseFloat($scope.incoming_invoice_excise._items[num]['_principal_qty']);
            }
            if (Qtys > 0) {
                Qty = Qtys;
            } else {
                Qty = Qtyp;
            }
            var LocalVal = parseFloat($scope.incoming_invoice_excise._items[num]['_basic_purchase_price']) * Qty;
            Sum = Sum + LocalVal;
            num++;
        }
        var BasicPrice = 0.00, Ed = 0.00, Cess = 0.00, Hed = 0.00, Cvd = 0.00, Packing = 0.00, Insurance = 0.00;
        var TaxableAmount = 0.00, SaleTax = 0.00, SaleTaxUnit = 0.00, Freight = 0.00, TotalAmount = 0.00, TotalSaleTax = 0.00, SaletaxPurcent = 0.00, SurchargePercent = 0.00;
        var surchargeamount = 0.00;
        var educess = 0.00, cvdamount = 0.00, heduamount = 0.00;
        var _supplier_qty = 0, _principal_qty = 0, _qty = 0;
        var LandingPrice = 0.00, TotalLandingPrice = 0.00;
        var pf_chrg = 0, insurance = 0, freight = 0, sale_tax = 0,saleTaxAmt=0;
        if (!isNaN($scope.incoming_invoice_excise._pf_chrg) && $scope.incoming_invoice_excise._pf_chrg != "" && $scope.incoming_invoice_excise._pf_chrg != null) {
            pf_chrg = parseFloat($scope.incoming_invoice_excise._pf_chrg);
        }
        if (!isNaN($scope.incoming_invoice_excise._insurance) && $scope.incoming_invoice_excise._insurance != "" && $scope.incoming_invoice_excise._insurance != null) {
            insurance = parseFloat($scope.incoming_invoice_excise._insurance);
        }
        if (!isNaN($scope.incoming_invoice_excise._freight) && $scope.incoming_invoice_excise._freight != "" && $scope.incoming_invoice_excise._freight != null) {
            freight = parseFloat($scope.incoming_invoice_excise._freight);
        }
        if (!isNaN($scope.incoming_invoice_excise._sale_Tax) && $scope.incoming_invoice_excise._sale_Tax != "" && $scope.incoming_invoice_excise._sale_Tax != null) {
            sale_tax = parseFloat($scope.incoming_invoice_excise._sale_Tax);
        } else {
            sale_tax = "0.00";
        }
        if (!isNaN($scope.incoming_invoice_excise.SaleTaxAmount) && $scope.incoming_invoice_excise.SaleTaxAmount != "" && $scope.incoming_invoice_excise.SaleTaxAmount != null) {
            saleTaxAmt = parseFloat($scope.incoming_invoice_excise.SaleTaxAmount);
        }        
        jQuery.ajax({
            url: URL,
            type: "GET",
            data: { TYP: "GETTAXDETAILS", TAXID: $("#txtsaletax").val() },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                var obj = objs[0];
                $scope.$apply(function () {
                    if (obj.SALESTAX_CHRG != null) {
                        SaletaxPurcent = obj.SALESTAX_CHRG;
                    }
                    else {
                        SaletaxPurcent = 0;
                    }
                    if (obj.SURCHARGE != null) {
                        SurchargePercent = obj.SURCHARGE;
                    }
                    else {
                        SurchargePercent = 0;
                    }
                    
                    while (index < $scope.incoming_invoice_excise._items.length) {
                        if (!isNaN($scope.incoming_invoice_excise._items[index]['_supplier_qty'])) {
                            _supplier_qty = parseFloat($scope.incoming_invoice_excise._items[index]['_supplier_qty']);
                        }
                        if (!isNaN($scope.incoming_invoice_excise._items[index]['_principal_qty'])) {
                            _principal_qty = parseFloat($scope.incoming_invoice_excise._items[index]['_principal_qty']);
                        }
                        if (_supplier_qty > 0) {
                            _qty = _supplier_qty;
                        } else {
                            _qty = _principal_qty;
                        }
                        BasicPrice = parseFloat($scope.incoming_invoice_excise._items[index]['_basic_purchase_price']);
                        Ed = parseFloat($scope.incoming_invoice_excise._items[index]['_ed_unit']);
                        Cess = parseFloat($scope.incoming_invoice_excise._items[index]['_edu_cess_amount']) / _qty;
                        Hed = parseFloat($scope.incoming_invoice_excise._items[index]['_hedu_amount']) / _qty;
                        if (!isNaN($scope.incoming_invoice_excise._items[index]['_cvd_amount']) && $scope.incoming_invoice_excise._items[index]['_cvd_amount'] != undefined && $scope.incoming_invoice_excise._items[index]['_cvd_amount'] != "") {
                            Cvd = parseFloat($scope.incoming_invoice_excise._items[index]['_cvd_amount']) / _qty;
                        }
                        Packing = (pf_chrg * BasicPrice) / Sum;
                        Insurance = (insurance * BasicPrice) / Sum;
                        TaxableAmount = parseFloat(BasicPrice) + parseFloat(Ed) + parseFloat(Cess) + parseFloat(Hed) + parseFloat(Cvd) + parseFloat(Packing) + parseFloat(Insurance);

                        SaleTax = ((parseFloat(TaxableAmount) * parseFloat(SaletaxPurcent)) / 100);
                        surchargeamount = ((parseFloat(SaleTax) * parseFloat(SurchargePercent)) / 100);
                        Freight = (freight * BasicPrice) / Sum;
                        LandingPrice = parseFloat(TaxableAmount) + parseFloat(SaleTax) + parseFloat(surchargeamount) + parseFloat(Freight); // +SaleTax + Freight;
                        $scope.incoming_invoice_excise._items[index]['_landing_price'] = LandingPrice.toFixed(2);
                        TotalLandingPrice = parseFloat(LandingPrice) * _qty;
                        $scope.incoming_invoice_excise._items[index]['_total_landing_price'] = TotalLandingPrice.toFixed(2);
                        TotalSaleTax = TotalSaleTax + (SaleTax);
                        index++;
                    }
                    var m = 0;
                    while (m < $scope.incoming_invoice_excise._items.length) {
                        TotalAmount = TotalAmount + parseFloat($scope.incoming_invoice_excise._items[m]['_total_landing_price']);
                        m++;
                    }
                    $scope.incoming_invoice_excise._total_bill_val = TotalAmount.toFixed(2);
                });
            }
        });

    }

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


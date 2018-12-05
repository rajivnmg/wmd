var URL = "../../Controller/Business_Action_Controller/IncomingInvoiceNonExcise_controller.php";
var method = "POST";
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
    	//alert(jsondata);
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
});

function ActionOnPrincipal(value, data) {
    ItemList = {};
    if (value != "" && data > 0) {
           $("#principalid").val(data);
          InvoiceExist($("#principalid").val(),$("#inv_no_p").val());
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
    $('#_partno_1_add').empty();
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
    $('#_partno_1_add').autocomplete({
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
                $("#_item_descp_add").val(objs[0]._item_descp);
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

function InvoiceExist(principalID,invoiceNo)
{ 

  var TYPE = "VINO";
            if (true) {
                jQuery.ajax({   url: "../../Controller/Business_Action_Controller/IncomingInvoiceNonExcise_controller.php",
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
                            	$("#inv_no_p").val("");
                            }
                            
                        }
                    }
                });
            }
}

var IncomingInvoiceNonExcise_app = angular.module('IncomingInvoiceNonExcise_app', []);
IncomingInvoiceNonExcise_app.controller('IncomingInvoiceNonExcise_Controller', function ($scope) {
    var sample_IncomingInvoiceNonExcise = { _items: [{ _item_id: 0, _partno_1: 0, _item_descp: '', _qty: 0, _rate: 0, _amount: 0, _batch_no: '', _exp_date: '', _landing_price: 0.00, _total_landing_price: 0.00}] };
    $scope.IncomingInvoiceNonExcise = sample_IncomingInvoiceNonExcise;
    $scope.IncomingInvoiceNonExcise._total_amount_details = 0;
    var SaletaxPurcent = 0.00, SurchargePercent = 0.00;
    $scope.addItem = function () {

        $scope.IncomingInvoiceNonExcise._item_id = $("#itemid").val();
        $scope.IncomingInvoiceNonExcise._partno_1_add = $("#_partno_1_add").val();
        $scope.IncomingInvoiceNonExcise._item_descp_add = $("#_item_descp_add").val();
        $scope.IncomingInvoiceNonExcise._exp_date_add = $("#_exp_date_add").val();

        // $scope.IncomingInvoiceNonExcise._partno_1_add = $scope.IncomingInvoiceNonExcise._partno_1_add.text;
        //alert($scope.IncomingInvoiceNonExcise._partno_1_add.value);
        $scope.IncomingInvoiceNonExcise._items.push({ _item_id: $scope.IncomingInvoiceNonExcise._item_id, _partno_1: $scope.IncomingInvoiceNonExcise._partno_1_add, _item_descp: $scope.IncomingInvoiceNonExcise._item_descp_add, _qty: $scope.IncomingInvoiceNonExcise._qty_add, _rate: $scope.IncomingInvoiceNonExcise._rate_add, _amount: $scope.IncomingInvoiceNonExcise._amount_add, _batch_no: $scope.IncomingInvoiceNonExcise._batch_no_add,
            _exp_date: $scope.IncomingInvoiceNonExcise._exp_date_add, _landing_price: $scope.IncomingInvoiceNonExcise._landingprice_add, _total_landing_price: $scope.IncomingInvoiceNonExcise._toallandingprice_add
        });
        $scope.IncomingInvoiceNonExcise._item_id = "";
        $scope.IncomingInvoiceNonExcise._partno_1_add = "";
        $scope.IncomingInvoiceNonExcise._item_descp_add = "";
        $scope.IncomingInvoiceNonExcise._qty_add = "";
        $scope.IncomingInvoiceNonExcise._rate_add = "";
        $scope.IncomingInvoiceNonExcise._amount_add = "";
        $scope.IncomingInvoiceNonExcise._batch_no_add = "";
        $scope.IncomingInvoiceNonExcise._exp_date_add = "";
        $scope.IncomingInvoiceNonExcise._landingprice_add = "";
        $scope.IncomingInvoiceNonExcise._toallandingprice_add = "";
        $scope.getLanding_Price();
        //alert("getLanding_Price()");
        //$scope.setSaletax_Percent();
    }
    $scope.DeleteItem = function (item) {
        //alert(item);

        $scope.IncomingInvoiceNonExcise._items.splice($scope.IncomingInvoiceNonExcise._items.indexOf(item), 1);

         $scope.setSaletax_Percent();

    }
    $scope.getPartNo = function () {
        if ($scope.IncomingInvoiceNonExcise.PricipalName > 0) {
            var TYPE = "SELECT";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, PRINCIPALID: $scope.IncomingInvoiceNonExcise.PricipalName },
                    //cache: false,
                    success: function (jsondata) {
                        $('#_partno_1_add').empty();
                        $("#_partno_1_add").append("<option value='0'>Select Item</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj;
                            for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];

                                //                                $("#_partno_1").append("<option value=\"" + obj._item_id + "\">" + obj._item_code_partno + "</option>");
                                $("#_partno_1_add").append("<option value=\"" + obj._item_id + "\">" + obj._item_code_partno + "</option>");
                            }
                        }
                    }
                });
            }
        }
        else {
            $('#partno_1_add').empty();
            $("#partno_1_add").append("<option value='0'>Select Item</option>");
        }
    }
    $scope.itemdesc = function () {
        if ($scope.IncomingInvoiceNonExcise._partno_1_add > 0) {
            var TYPE = "LOADITEM";
            //alert("here");
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: "LOADITEM", ITEMID: $scope.IncomingInvoiceNonExcise._partno_1_add },
                    //cache: false,
                    success: function (jsondata) {
                        var objs = jQuery.parseJSON(jsondata);
                        // alert(jsondata);
                        $scope.$apply(function () {
                            $scope.IncomingInvoiceNonExcise._item_descp_add = objs[0]._item_descp;
                            $scope.IncomingInvoiceNonExcise._rate_add = objs[0]._item_cost_price;

                        });

                    }
                });
            }
        }
        else {
            $scope.IncomingInvoiceNonExcise._item_descp = "";
            $scope.IncomingInvoiceNonExcise._rate = 0.00;
        }
    }
    $scope.SaveInvoice = function (number) {
    	
    	if(number!="")
    	{
			return ;
		}
        if ($scope.IncomingInvoiceNonExcise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }

        if($("#txtsaletax").val()=="")
        {
		    alert("Please select sale tax ");
            return;
		}
		$("#btnsave").hide();
        $scope.IncomingInvoiceNonExcise.dt_p = $("#dt_p").val();
        $scope.IncomingInvoiceNonExcise.dt_s = $("#dt_s").val();
        $scope.IncomingInvoiceNonExcise._rece_date = $("#ReceivedDate").val();

        $scope.IncomingInvoiceNonExcise._principal_Id = $("#principalid").val();
        $scope.IncomingInvoiceNonExcise._supplier_Id = $("#supplierid").val();
		$scope.IncomingInvoiceNonExcise.ms = $("#marketsegment").val();
      
        var json_string = JSON.stringify($scope.IncomingInvoiceNonExcise);
		$("#btnsave").hide();
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/IncomingInvoiceNonExcise_controller.php",
            type: "POST",
            data: { TYP: "INSERT", INCOMINGINVOICENONEXCISEDATA: json_string },
            success: function (jsondata) {//alert(jsondata);
                $scope.$apply(function () {
                    $scope.IncomingInvoiceNonExcise = null;
                    location.href = "IncomingInvoiceNonExciseView.php";
                });
            }
        });
    }


    $scope.setSaletax_Percent =function(){
      if($("#txtsaletax").val()!=""){
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "GETTAXDETAILS", TAXID: $("#txtsaletax").val() },
            success: function (jsondata) {
            	//alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                var obj = objs[0];
                //alert(obj.SALESTAX_CHRG);
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
                });
            }
        });
      }
     }
    $scope.getLanding_Price = function () {
        var index = 0;
        var Sum = 0.00;
        var num = 0;
        var Qty = 0;
        while (num < $scope.IncomingInvoiceNonExcise._items.length) {
            Qty = parseFloat($scope.IncomingInvoiceNonExcise._items[num]['_qty']);
            var LocalVal = parseFloat($scope.IncomingInvoiceNonExcise._items[num]['_amount']);
            Sum = Sum + LocalVal;
            num++;
        }
        //alert("Sum-"+Sum);
        $scope.IncomingInvoiceNonExcise._total_amount_details = parseFloat(Sum).toFixed(2);
        var Amount = 0.00;
        var BasicPrice = 0.00, Packing = 0.00, Insurance = 0.00,SaletaxAmount=0.00;
        var TaxableAmount = 0.00,totTaxableAmount = 0.00, SaleTax = 0.00, Freight = 0.00, TotalAmount = 0.00;
        var surchargeamount = 0.00;
        var quantity = 0;
        var LandingPrice = 0.00, TotalLandingPrice = 0.00;
        var pf_chrg = 0, insurance = 0, freight = 0, sale_tax = 0, saleTaxAmt = 0;

        if (!isNaN($scope.IncomingInvoiceNonExcise.Packing) && $scope.IncomingInvoiceNonExcise.Packing != "" && $scope.IncomingInvoiceNonExcise.Packing != null) {
            pf_chrg = parseFloat($scope.IncomingInvoiceNonExcise.Packing);
        }
        if (!isNaN($scope.IncomingInvoiceNonExcise.Insurance) && $scope.IncomingInvoiceNonExcise.Insurance != "" && $scope.IncomingInvoiceNonExcise.Insurance != null) {
            insurance = parseFloat($scope.IncomingInvoiceNonExcise.Insurance);
        }
        if (!isNaN($scope.IncomingInvoiceNonExcise.Freight) && $scope.IncomingInvoiceNonExcise.Freight != "" && $scope.IncomingInvoiceNonExcise.Freight != null) {
            freight = parseFloat($scope.IncomingInvoiceNonExcise.Freight);
        }
        if (!isNaN($scope.IncomingInvoiceNonExcise.SaleTaxAmount) && $scope.IncomingInvoiceNonExcise.SaleTaxAmount != "" && $scope.IncomingInvoiceNonExcise.SaleTaxAmount != null) {
            saleTaxAmt = parseFloat($scope.IncomingInvoiceNonExcise.SaleTaxAmount);
        }
        $scope.setSaletax_Percent();
        //alert("saleTaxAmt---"+$("#txtsaletax").val());
                  while (index < $scope.IncomingInvoiceNonExcise._items.length) {
                        BasicPrice = parseFloat($scope.IncomingInvoiceNonExcise._items[index]['_rate']);
                        quantity = parseFloat($scope.IncomingInvoiceNonExcise._items[index]['_qty']);
                        Packing = (parseFloat(pf_chrg) * parseFloat(BasicPrice)) / Sum;
                        Insurance = (parseFloat(insurance) * parseFloat(BasicPrice)) / Sum;
                        TaxableAmount = parseFloat(BasicPrice) + parseFloat(Packing) + parseFloat(Insurance);
                        SaleTax = ((parseFloat(TaxableAmount) * parseFloat(SaletaxPurcent)) / 100);
                     // alert(SaleTax);
                        surchargeamount = ((parseFloat(SaleTax) * parseFloat(SurchargePercent)) / 100);
                        totTaxableAmount =(totTaxableAmount +TaxableAmount);
                        Freight = (parseFloat(freight) * parseFloat(BasicPrice)) / Sum;
                        LandingPrice = parseFloat(TaxableAmount) + parseFloat(SaleTax) + parseFloat(surchargeamount) + parseFloat(Freight);
 		     //LandingPrice = parseFloat(TaxableAmount) + ((parseFloat(TaxableAmount)* parseFloat(saleTaxAmt))/parseFloat(totTaxableAmount)) + parseFloat(Freight);
                        $scope.IncomingInvoiceNonExcise._items[index]['_landing_price'] = parseFloat(LandingPrice).toFixed(2);
                        TotalLandingPrice = LandingPrice * quantity;
                        $scope.IncomingInvoiceNonExcise._items[index]['_total_landing_price'] = parseFloat(TotalLandingPrice).toFixed(2);
                        index++;
                    }
                    var m = 0, add_val = 0.00;
                    while (m < $scope.IncomingInvoiceNonExcise._items.length) {
                        TotalAmount = TotalAmount + parseFloat($scope.IncomingInvoiceNonExcise._items[m]['_amount']);
                        add_val = add_val + parseFloat($scope.IncomingInvoiceNonExcise._items[m]['_total_landing_price']);
                        m++;
                    }
                    //$scope.IncomingInvoiceNonExcise._total_amount_details = parseFloat(TotalAmount).toFixed(2);
                    var add_val = parseFloat(TotalAmount) + parseFloat(pf_chrg) + parseFloat(insurance) + parseFloat(freight) + parseFloat(saleTaxAmt);
                    $scope.IncomingInvoiceNonExcise.TotalBillValue = add_val.toFixed(2);



    }
    $scope.QtyChange = function () {
        if ($scope.IncomingInvoiceNonExcise._qty_add != "" && $scope.IncomingInvoiceNonExcise._rate_add != "") {
            $scope.IncomingInvoiceNonExcise._amount_add = parseFloat($scope.IncomingInvoiceNonExcise._rate_add) * parseFloat($scope.IncomingInvoiceNonExcise._qty_add);
        }
    }
    $scope.RateChange = function () {
        if ($scope.IncomingInvoiceNonExcise._qty_add != "" && $scope.IncomingInvoiceNonExcise._rate_add != "") {
            $scope.IncomingInvoiceNonExcise._amount_add = parseFloat($scope.IncomingInvoiceNonExcise._rate_add) * parseFloat($scope.IncomingInvoiceNonExcise._qty_add);
        }
    }
    $scope.RowChangeEvent = function (item) {
        var Rowindex = $scope.IncomingInvoiceNonExcise._items.indexOf(item);
        if ($scope.IncomingInvoiceNonExcise._items[Rowindex]['_qty'] != "" && $scope.IncomingInvoiceNonExcise._items[Rowindex]['_rate'] != "") {
            $scope.IncomingInvoiceNonExcise._items[Rowindex]['_amount'] = parseFloat($scope.IncomingInvoiceNonExcise._items[Rowindex]['_rate']) * parseFloat($scope.IncomingInvoiceNonExcise._items[Rowindex]['_qty']);
        }
        $scope.getLanding_Price();
    }
    //****************
   
     $scope.validateInvoiceNumber= function () {
       $scope.IncomingInvoiceNonExcise._principalID= $("#principalid").val();
       InvoiceExist($scope.IncomingInvoiceNonExcise._principalID,$scope.IncomingInvoiceNonExcise.inv_no_p);
        
    }
    
    //********************
    $scope.UpdateInvoice = function () {
       /* 
        if ($scope.IncomingInvoiceNonExcise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }*/
        $("#btnupdate").hide();
        $scope.IncomingInvoiceNonExcise.dt_p = $("#dt_p").val();
        $scope.IncomingInvoiceNonExcise.dt_s = $("#dt_s").val();
        $scope.IncomingInvoiceNonExcise._rece_date = $("#ReceivedDate").val();

        $scope.IncomingInvoiceNonExcise._principal_Id = $("#principalid").val();
        $scope.IncomingInvoiceNonExcise._supplier_Id = $("#supplierid").val();
        var json_string = JSON.stringify($scope.IncomingInvoiceNonExcise);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/IncomingInvoiceNonExcise_controller.php",
            type: "POST",
            data: { TYP: "UPDATE", INCOMINGINVOICENONEXCISEUPDATE: json_string },
            success: function (jsondata) {//alert(jsondata);
                $scope.IncomingInvoiceNonExcise = null;
                location.href = "IncomingInvoiceNonExciseView.php";
            }
        });
    }
    $scope.init = function (number) {
        $scope.IncomingInvoiceNonExcise._invoiceid = number;
        $("#_invoiceid").val(number);
        //alert(number);
        if (number != "") {
            $("#btnsave").hide();
             $('#inv_no_p').attr('readonly','true');	
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/IncomingInvoiceNonExcise_controller.php",
                type: "POST",
                data: { TYP: "SELECT", invoiceId: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        //alert(jsondata);
                        $scope.IncomingInvoiceNonExcise._principalname = objs[0]._principalname;
                        $scope.IncomingInvoiceNonExcise._suppliername = objs[0]._suppliername;
                        $scope.IncomingInvoiceNonExcise._invoiceid = objs[0]._incominginvoiceid;
                        $scope.IncomingInvoiceNonExcise._principalID = objs[0]._principalID;
                        $scope.IncomingInvoiceNonExcise.inv_no_p = objs[0]._incoming_inv_no_p;
                        $scope.IncomingInvoiceNonExcise.dt_p = objs[0]._principal_inv_date;
						$scope.IncomingInvoiceNonExcise.ms = objs[0].ms;
                        $scope.IncomingInvoiceNonExcise.inv_no_s = objs[0]._incoming_inv_no_s;
                        $scope.IncomingInvoiceNonExcise.dt_s = objs[0]._supplr_inv_date;
                        $scope.IncomingInvoiceNonExcise._supplrId = objs[0]._supplrId;
                        $scope.IncomingInvoiceNonExcise.Packing = objs[0]._pf_chrg;
                        $scope.IncomingInvoiceNonExcise.Insurance = objs[0]._insurance;
                        $scope.IncomingInvoiceNonExcise.Freight = objs[0]._freight;
                        $scope.IncomingInvoiceNonExcise.SaleTax = objs[0]._saletax;
                        $scope.IncomingInvoiceNonExcise.SaleTaxAmount = objs[0].SaleTaxAmount;
                        $scope.IncomingInvoiceNonExcise.TotalBillValue = objs[0]._tot_bill_val;
                        $scope.IncomingInvoiceNonExcise._rece_date = objs[0]._rece_date;
                        $scope.IncomingInvoiceNonExcise.Comments = objs[0]._remarks;
                        $scope.IncomingInvoiceNonExcise._items = objs[0]._items;
                        $scope.IncomingInvoiceNonExcise._old_items = objs[0]._items;
                        // $scope.getprincipal();
                        jQuery.ajax({
                            url: "../../Controller/Master_Controller/Item_Controller.php",
                            type: "post",
                            data: { TYP: "SELECT", PRINCIPALID: objs[0]._principalID },
                            success: function (jsondata1) {
                                var objs2 = jQuery.parseJSON(jsondata1);
                                if (jsondata1 != "") {
                                    for (var i = 0; i < objs2.length; i++) {
                                        var obj22 = objs2[i];
                                        ItemList[obj22._item_id] = obj22._item_code_partno;
                                    }
                                    CallToItem(ItemList);
                                }
                            }
                        });
                        var m = 0, TotalAmount = 0.00;
                        while (m < $scope.IncomingInvoiceNonExcise._items.length) {
                            TotalAmount = parseFloat(TotalAmount) + parseFloat($scope.IncomingInvoiceNonExcise._items[m]['_amount']);
                            m++;
                        }
                        $scope.IncomingInvoiceNonExcise._total_amount_details = parseFloat(TotalAmount).toFixed(2);
                        //alert(objs[0]._incominginvoiceid);
                        ActionOnPrincipal(objs[0]._principalname, objs[0]._principalID);
                        jQuery.ajax({
                            url: URL,
                            type: "POST",
                            data: { TYP: "FIND_INVOIC_IN_OGINV", INVOICENO: objs[0]._incominginvoiceid },
                            success: function (jsondata) {
                                var objs = jQuery.parseJSON(jsondata);
                                if (objs > 0) {
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
            $scope.IncomingInvoiceNonExcise._items.splice($scope.IncomingInvoiceNonExcise._items.indexOf(0), 1);
        }
    }


});
IncomingInvoiceNonExcise_app.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            if (!ngModelCtrl) {
                return;
            }
            ngModelCtrl.$parsers.push(function (val) {
                //var clean = val.replace(/[^0-9]+/g, '');// Codefire Changes for float quantity 27.07.15
				var clean = val.replace(/[^\d.]/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
        }
    }
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise._qty_add', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise._qty_add = oldValue;
                }
            });
        }
    };
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise._rate_add', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise._rate_add = oldValue;
                }
            });
        }
    };
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise.Packing', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise.Packing = oldValue;
                }
            });
        }
    };
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise.Insurance', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise.Insurance = oldValue;
                }
            });
        }
    };
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise.Freight', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise.Freight = oldValue;
                }
            });
        }
    };
});

IncomingInvoiceNonExcise_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('IncomingInvoiceNonExcise.SaleTaxAmount', function (newValue, oldValue) {

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
                    scope.IncomingInvoiceNonExcise.SaleTaxAmount = oldValue;
                }
            });
        }
    };
});

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

}
function NoneBuyer() {
    $("#buyerid").val(0);
}
var PurchaseOrderList = {};
function CallToPurchaseOrder(PurchaseOrderList) {
    'use strict';
    var PurchaseOrderArray = $.map(PurchaseOrderList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-PurchaseOrder').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: PurchaseOrderArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnPurchaseOrder(suggestion.value, suggestion.data);
            //$('#selction-ajax-buyer').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-buyer').val(hint);
        },
        onInvalidateSelection: function () {
            NonePurchaseOrder();
            //$('#selction-ajax-buyer').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
    type: "post",
    data: { TYP: "GETPOLIST" },
    success: function (jsondata) {
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            var obj;
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                PurchaseOrderList[obj.poid] = obj.pono;
            }
            CallToPurchaseOrder(PurchaseOrderList);
        }
    }
});
function ActionOnPurchaseOrder(value, data) {
    if (value != "" && data > 0) {
        var PO_num = data;
        $("#PurchaseOrderid").val(data);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: method,
            data: { TYP: "MA_FILL", PO_NUMBER: data },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $("#buyerid").val(objs[0].bn);
                $("#autocomplete-ajax-buyer").val(objs[0].bn_name);
                $("#inv_po_date").val(objs[0].pod);
                $("#autocomplete-ajax-PurchaseOrder").val(objs[0].pon);
                //alert(objs[0].pf_chrg);
                //alert(objs[0].inci_chrg);
                if (objs[0].pf_chrg == null) {

                    $("#txtpf_charg").val(0);
                    $("#txtpf_charg_percent").val(0);
                }
                else {
                    $("#txtpf_charg_percent").val(objs[0].pf_chrg);
                }
                if (objs[0].inci_chrg == null) {

                    $("#txtincidental_chrg").val(0);
                    $("#txtincidental_chrg_percent").val(0);
                }
                else {
                    $("#txtincidental_chrg_percent").val(objs[0].inci_chrg);
                }
                if (objs[0].frgtp == null) {
                    $("#txtfreight_percent").val(0);
                }
                else {
                    $("#txtfreight_percent").val(objs[0].frgtp);
                }
                if (objs[0].frgta == null || objs[0].frgta == "null") {
                    $("#txtfreight_amount").val(0);
                }
                else {
                    $("#txtfreight_amount").val(objs[0].frgta);
                }
            }
        });

        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "GET_PO_PRINCIPAL", PO_ID: PO_num },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                $('#principal_list').empty();
                $("#principal_list").append("<option value=\'0'\ title=\'select'>Select Principal</option>");
                for (var i = 0; i < objs.length; i++) {
                    $("#principal_list").append("<option value=\'" + objs[i].po_principalId + "'\ title=\'" + objs[i].po_principalName + "\'>" + objs[i].po_principalName + "</option>");
                }
            }
        });
    }

}
function NonePurchaseOrder() {
    $("#PurchaseOrderid").val(0);
}

var URL = "../../Controller/Business_Action_Controller/Outgoing_Invoice_NonExcise_Controller.php";
var method = "POST";
var Outgoing_Invoice_NonExcise_App = angular.module('Outgoing_Invoice_NonExcise_App', []);

Outgoing_Invoice_NonExcise_App.controller('Outgoing_Invoice_NonExcise_Controller', ['$scope', '$http', function Outgoing_Invoice_NonExcise_Controller($scope, $http) {
    var sample_outgoing_invoice_nonexcise = { _items: [{ bpod_Id: 0, buyer_item_code: '', oinv_codePartNo: '', _item_id: 0, codePartNo_desc: '', ordered_qty: '', stock_qty: '', issued_qty: '', oinv_price: '', item_amount: '', discount: 0, saletax: 0}] };

    $scope.outgoing_invoice_nonexcise = sample_outgoing_invoice_nonexcise;
    $scope.addItem = function () {
        var issuedQty = parseInt($scope.outgoing_invoice_nonexcise.issued_qty);
        if (issuedQty > 0) {
            $scope.outgoing_invoice_nonexcise._item_id = $("#item_master").val();
            $scope.outgoing_invoice_nonexcise.oinv_codePartNo = $("#item_master option:selected").text();

            $scope.outgoing_invoice_nonexcise.pf_chrg_percent = $("#txtpf_charg_percent").val();
            $scope.outgoing_invoice_nonexcise.pf_chrg = 0;
            $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = $("#txtincidental_chrg_percent").val();
            $scope.outgoing_invoice_nonexcise.incidental_chrg = 0;
            $scope.outgoing_invoice_nonexcise.freight_percent = $("#txtfreight_percent").val();
            $scope.outgoing_invoice_nonexcise.freight_amount = $("#txtfreight_amount").val();
            var check = 0;
            var discount_flag = false;
            while (check < $scope.outgoing_invoice_nonexcise._items.length) {
                if (parseFloat($scope.outgoing_invoice_nonexcise.discount) == parseFloat($scope.outgoing_invoice_nonexcise._items[check]["discount"])) {
                    discount_flag = true;
                }
                else {
                    discount_flag = false;
                }
                check++;
            }
            if (parseInt($scope.outgoing_invoice_nonexcise._items.length) == 0) {
                discount_flag = true;
            }
            if (discount_flag) {
                $scope.outgoing_invoice_nonexcise._items.push({ bpod_Id: $scope.outgoing_invoice_nonexcise.bpod_Id, buyer_item_code: $scope.outgoing_invoice_nonexcise.buyer_item_code, oinv_codePartNo: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, _item_id: $scope.outgoing_invoice_nonexcise._item_id, codePartNo_desc: $scope.outgoing_invoice_nonexcise.codePartNo_desc, ordered_qty: $scope.outgoing_invoice_nonexcise.ordered_qty, stock_qty: $scope.outgoing_invoice_nonexcise.stock_qty, issued_qty: $scope.outgoing_invoice_nonexcise.issued_qty, oinv_price: $scope.outgoing_invoice_nonexcise.oinv_price, item_amount: $scope.outgoing_invoice_nonexcise.item_amount, discount: $scope.outgoing_invoice_nonexcise.discount, saletax: $scope.outgoing_invoice_nonexcise.saletax });
                $scope.BillCalculation();
            }
            else {
                alert("This item have diffrent discount so we can not added this item in this invoice.");
            }
            $scope.outgoing_invoice_nonexcise.bpod_Id = "";
            $scope.outgoing_invoice_nonexcise.buyer_item_code = "";
            $scope.outgoing_invoice_nonexcise.oinv_codePartNo = "";
            $scope.outgoing_invoice_nonexcise._item_id = "";
            $scope.outgoing_invoice_nonexcise.codePartNo_desc = "";
            $scope.outgoing_invoice_nonexcise.ordered_qty = "";
            $scope.outgoing_invoice_nonexcise.stock_qty = "";
            $scope.outgoing_invoice_nonexcise.issued_qty = "";
            $scope.outgoing_invoice_nonexcise.oinv_price = "";
            $scope.outgoing_invoice_nonexcise.item_amount = "";
            $scope.outgoing_invoice_nonexcise.discount = "";
            $scope.outgoing_invoice_nonexcise.saletax = "";
        }
        else {
            alert("amount and issued quantity naver be blank.");
        }
    }

    $scope.BillCalculation = function () {
        var k = 0;
        $scope.outgoing_invoice_nonexcise.bill_value = 0;
        $scope.outgoing_invoice_nonexcise.total_discount = 0;
        $scope.outgoing_invoice_nonexcise.total_saleTax = 0;
        var basic_amount = 0.00;
        var SurchargeAmount = 0.00;
        var Discount_percent = 0.00, Discount_Amount = 0.00;
        while (k < $scope.outgoing_invoice_nonexcise._items.length) {
            Discount_percent = parseFloat($scope.outgoing_invoice_nonexcise._items[k]["discount"]);
            basic_amount = parseFloat(basic_amount) + parseFloat($scope.outgoing_invoice_nonexcise._items[k]["item_amount"]);
            k++;
        }
        Discount_Amount = (parseFloat(basic_amount) * parseFloat(Discount_percent)) / 100;
        $scope.outgoing_invoice_nonexcise.total_discount = Discount_Amount.toFixed(2);
        var CalculateAmount = basic_amount - Discount_Amount;
        var pf_charge_amount = (parseFloat($scope.outgoing_invoice_nonexcise.pf_chrg_percent) * CalculateAmount) / 100;
        //alert("pf_charge_amount : " + pf_charge_amount);
        var incidental_amount = (parseFloat($scope.outgoing_invoice_nonexcise.incidental_chrg_percent) * CalculateAmount) / 100;
        $scope.outgoing_invoice_nonexcise.pf_chrg = pf_charge_amount.toFixed(2);
        $scope.outgoing_invoice_nonexcise.incidental_chrg = incidental_amount.toFixed(2);
        //alert("incidental_amount : " + incidental_amount);
        // Taxable amount = basic amount - discount + pf_charge_amount
        var TaxableAmount = parseFloat(CalculateAmount) + parseFloat(pf_charge_amount) + parseFloat(incidental_amount);
        var SaletaxPurcent = 0.00, SurchargePercent = 0.00;
        var TaxAmount = 0.00, SurchargeAmount = 0.00;
        //alert($scope.outgoing_invoice_nonexcise.saletaxID);
        var taxid = parseInt($scope.outgoing_invoice_nonexcise.saletaxID);
        //alert(taxid);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "GETTAXDETAILS", TAXID: taxid },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                if (objs[0].SALESTAX_CHRG != null) {
                    SaletaxPurcent = objs[0].SALESTAX_CHRG;
                }
                else {
                    SaletaxPurcent = 0;
                }
                if (objs[0].SURCHARGE != null) {
                    SurchargePercent = objs[0].SURCHARGE;
                }
                else {
                    SurchargePercent = 0;
                }
                TaxAmount = TaxableAmount * SaletaxPurcent;
                TaxAmount = TaxAmount / 100;
                SurchargeAmount = SurchargePercent * TaxAmount;
                SurchargeAmount = parseFloat(SurchargeAmount) / 100;
                var F_amt = 0.00;
                if (parseFloat($scope.outgoing_invoice_nonexcise.freight_percent) > 0) {
                    F_amt = ((CalculateAmount + pf_charge_amount) / 100) * parseFloat($scope.outgoing_invoice_nonexcise.freight_percent);
                    $scope.outgoing_invoice_nonexcise.freight_amount = F_amt.toFixed(2);
                } else if (parseFloat($scope.outgoing_invoice_nonexcise.freight_amount) > 0) {
                    F_amt = parseFloat($scope.outgoing_invoice_nonexcise.freight_amount);
                    $scope.outgoing_invoice_nonexcise.freight_amount = F_amt.toFixed(2);
                    $scope.outgoing_invoice_nonexcise.freight_percent = (parseFloat($scope.outgoing_invoice_nonexcise.freight_amount) * 100) / (CalculateAmount + pf_charge_amount);
                }
                $scope.$apply(function () {
                    $scope.outgoing_invoice_nonexcise.total_saleTax = (parseFloat(TaxAmount) + parseFloat(SurchargeAmount)).toFixed(2);

                    // Pay Amount = Taxable amount + sale Tax + freight_amount
                    var PayAmount = parseFloat(TaxableAmount) + parseFloat($scope.outgoing_invoice_nonexcise.total_saleTax) + parseFloat(F_amt.toFixed(2));

                    // Bill val = PayAmount + incidental_amount
                    $scope.outgoing_invoice_nonexcise.bill_value = parseFloat(PayAmount.toFixed(2));
                });

            }
        });
    }

    $scope.removeItem = function (item) {
        $scope.outgoing_invoice_nonexcise._items.splice($scope.outgoing_invoice_nonexcise._items.indexOf(item), 1);
        $scope.BillCalculation();
    }
    $scope.BuyerDetaile = [{}];
    $scope.PrincipalDetaile = [{}];
    $scope.BillDetaile = [{}];
    $scope.TaxDetaile = [{}];
    $scope.PODetaile = [{}];
    $scope.AddRow = function (i, objspo, data) {
        $scope.PODetaile._items2.push({ sn: i + 1, codepart: objspo[0]._items[i].po_itemId, desc: objspo[0]._items[i].itemdescp, qty: data[0]._itmes[i].issued_qty, rate: objspo[0]._items[i].po_price, amount: data[0]._itmes[i].oinv_price });
        $scope.BillDetaile.basic_amount = parseFloat($scope.BillDetaile.basic_amount) + parseFloat($scope.PODetaile._items2[i]["amount"]);
    };
    $scope.GetIncomingInvoiceData = function (i, objspo, data) {
        $scope.AddRow(i, objspo, data);
        if (i == objspo.length - 1) {
            $scope.CalculateTax(objspo, data);
        }
    }

    var print_itme_list = [{ sn: 0, codepart: '', desc: '', qty: '', rate: '', amount: ''}];
    $scope.print = function (number) {
        var BuyerId = 0, PoID = 0;
        var responsePromise = $http.get(URL + "?TYP=SELECT&OutgoingInvoiceNonExciseNum=" + number);
        responsePromise.success(function (data, status, headers, config) {
            $scope.OutgoingInvoiceNonExciseNum = data[0];
            PoID = data[0].pono;
            var responsePO = $http.get("../../Controller/Business_Action_Controller/po_Controller.php?TYP=MA_FILL&PO_NUMBER=" + PoID+"&po_ed_applicability=N");
            responsePO.success(function (objspo, status, headers, config) {
                $scope.PODetaile = objspo[0];
                $scope.PODetaile._items2 = print_itme_list;
                BuyerId = objspo[0].bn;
                $scope.PODetaile._items2.splice($scope.PODetaile._items2.indexOf(0), 1);
                $scope.BillDetaile.discount_amt = data[0].discount;
                $scope.BillDetaile.discount_percent = objspo[0]._items[0].po_discount;
                $scope.BillDetaile.basic_amount = 0.00, $scope.BillDetaile.TaxableAmount = 0.00;
                for (var i = 0; i < objspo[0]._items.length; i++) {
                    $scope.GetIncomingInvoiceData(i, objspo, data);
                }
            });
            var responseBuyer = $http.get("../../Controller/Master_Controller/Buyer_Controller.php?TYP=SELECT&BUYERID=" + BuyerId);
            responseBuyer.success(function (data3, status, headers, config) {
                $scope.BuyerDetaile = data3[0];
            });
        });
    };

    $scope.CalculateTax = function (objspo, data) {
        $scope.BillDetaile.TaxableAmount = parseFloat($scope.BillDetaile.basic_amount) - parseFloat($scope.BillDetaile.discount_amt) + parseFloat(data[0].pf_chrg) + parseFloat(data[0].incidental_chrg);
        var responseTax = $http.get(URL + "?TYP=GETTAXDETAILS&TAXID=" + data[0].po_saleTax);
        responseTax.success(function (datatax, status, headers, config) {
            $scope.TaxDetaile = datatax[0];
            $scope.BillDetaile.SaleTaxAmount = (parseFloat($scope.BillDetaile.TaxableAmount) * parseFloat(datatax[0].SALESTAX_CHRG)) / 100;
            $scope.BillDetaile.SurchargeAmount = (parseFloat($scope.BillDetaile.SaleTaxAmount) * parseFloat(datatax[0].SURCHARGE)) / 100;
        });
    }

    $scope.init = function (number) {
        if (number > 0) {
            $("#btnsave").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT", OutgoingInvoiceNonExciseNum: number },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                   // alert(jsondata);
                    $scope.$apply(function () {
                        $scope.outgoing_invoice_nonexcise = objs[0];
                        $scope.outgoing_invoice_nonexcise._items = objs[0]._itmes;
                        //$scope.GetPurchaseOrderDetails();
                        $("#principal_list").val(objs[0].principalID);
                        //ActionOnPurchaseOrder("y", objs[0].pono);
                        $("#PurchaseOrderid").val(objs[0].pono);
                        jQuery.ajax({
                            url: "../../Controller/Business_Action_Controller/po_Controller.php",
                            type: method,
                            data: { TYP: "MA_FILL", PO_NUMBER: objs[0].pono },
                            success: function (jsondata) {
                                //alert(jsondata);
                                var objsuuuu = jQuery.parseJSON(jsondata);
                                $("#buyerid").val(objsuuuu[0].bn);
                                $("#autocomplete-ajax-buyer").val(objsuuuu[0].bn_name);
                                $("#inv_po_date").val(objsuuuu[0].pod);
                                $("#autocomplete-ajax-PurchaseOrder").val(objsuuuu[0].pon);
                                $scope.outgoing_invoice_nonexcise.fright_percent = objsuuuu[0].frgtp;
                                $scope.outgoing_invoice_nonexcise.pf_chrg_percent = objsuuuu[0].pf_chrg;
                                $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = objsuuuu[0].inci_chrg;
                            }
                        });
                        jQuery.ajax({
                            url: URL,
                            type: method,
                            data: { TYP: "GET_PO_PRINCIPAL", PO_ID: objs[0].pono },
                            success: function (jsondata) {
                                var objshhhh = jQuery.parseJSON(jsondata);
                                $('#principal_list').empty();
                                $("#principal_list").append("<option value=\'0'\ title=\'select'>Select Principal</option>");
                                for (var i = 0; i < objs.length; i++) {
                                    $("#principal_list").append("<option value=\'" + objshhhh[i].po_principalId + "'\ title=\'" + objshhhh[i].po_principalName + "\'>" + objshhhh[i].po_principalName + "</option>");
                                }
                            }
                        });
                        for (var i = 0; i < objs[0]._items.length; i++) {
                            $scope.outgoing_invoice_nonexcise._items[i].oldinventory = objs[0]._itmes[i].issued_qty;
                            $scope.BindCurrentQuantity(objs[0]._itmes[i].itemid, i);
                        }
                        $scope.outgoing_invoice_nonexcise.total_discount = objs[0].discount;
                        $scope.outgoing_invoice_nonexcise.total_saleTax = objs[0].po_saleTax;
                    });
                }
            });
        }
        else {
            $("#btnupdate").hide();
            $("#btnprint").hide();
            $scope.outgoing_invoice_nonexcise._items.splice($scope.outgoing_invoice_nonexcise._items.indexOf(0), 1);
            var d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            };
            var day = d.getDate();
            $scope.outgoing_invoice_nonexcise.oinv_date = day + "/" + month + "/" + year;
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $("#txt_outgoing_invoice_num").val(objs);
                    $scope.$apply(function () {
                        $scope.outgoing_invoice_nonexcise.oinvoice_No = objs;
                    });
                }
            });
        }
    }
    $scope.BindCurrentQuantity = function (Itemid, rowindex) {
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "GET_ABV_OUANTITY", ITEMID: Itemid },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    $scope.outgoing_invoice_nonexcise._items[rowindex]["stock_qty"] = objs[0].tot_nonExciseQty;
                });
            }
        });
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: "POST",
            data: { TYP: "LOADPODETAILS", ITEMID: Itemid, PRINID: $scope.outgoing_invoice_nonexcise.principalID, POID: $("#PurchaseOrderid").val(), TAG: "N" },
            //cache: false,
            success: function (jsondatadd) {
                //alert(jsondatadd);
                var objsdd = jQuery.parseJSON(jsondatadd);
                //alert(objsdd[0].po_saleTax);
                $scope.$apply(function () {
                    $scope.outgoing_invoice_nonexcise._items[rowindex]["discount"] = objsdd[0].po_discount;
                    $scope.outgoing_invoice_nonexcise.saletaxID = objsdd[0].po_saleTax;
                });
            }
        });
    }
    $scope.AddOutgoingInvoiceExcise = function () {
        if ($scope.outgoing_invoice_nonexcise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $scope.outgoing_invoice_nonexcise.oinv_date = $("#inv_date").val();
        $scope.outgoing_invoice_nonexcise.po_date = $("#inv_po_date").val();
        $scope.outgoing_invoice_nonexcise.poid = $("#PurchaseOrderid").val();
        $scope.outgoing_invoice_nonexcise.BuyerID = $("#buyerid").val();
        var json_string = JSON.stringify($scope.outgoing_invoice_nonexcise);
        //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", OUTGOING_INVOICE_NONEXCISE_DATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                    //alert("Successfully Saved.");
                    $scope.outgoing_invoice_nonexcise = null;
                    location.href = "view_Outgoing_Invoice_NonExcise.php";
                });
            }
        });
    }
    $scope.Update = function () {
        $scope.outgoing_invoice_nonexcise.BuyerID = $("#buyerid").val();
        var json_string = JSON.stringify($scope.outgoing_invoice_nonexcise);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", OUTGOING_INVOICE_NONEXCISE_DATA: json_string },
            success: function (jsondata) {
                //alert(jsondata);
                $scope.outgoing_invoice_nonexcise = null;
                location.href = "view_Outgoing_Invoice_NonExcise.php";
            }
        });
    }
    $scope.GetPurchaseOrderDetails = function () {
        var PO_num = $("#invoice_no").val();
        //alert(PO_num);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: method,
            data: { TYP: "MA_FILL", PO_NUMBER: PO_num },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
                $scope.$apply(function () {
                    $("#buyer_id").val(objs[0].buyerid);
                    $scope.outgoing_invoice_nonexcise.po_date = objs[0].pod;
                    $scope.outgoing_invoice_nonexcise.BuyerID = objs[0].bn;

                    if (objs[0].pf_chrg === null) {
                        $scope.outgoing_invoice_nonexcise.pf_chrg_percent = 0;
                        $scope.outgoing_invoice_nonexcise.pf_chrg = 0;
                    }
                    else {
                        $scope.outgoing_invoice_nonexcise.pf_chrg_percent = objs[0].pf_chrg;
                    }

                    if (objs[0].inci_chrg == null) {
                        $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = parseInt(0);
                        $scope.outgoing_invoice_nonexcise.incidental_chrg = parseInt(0);
                    }
                    else {
                        $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = objs[0].inci_chrg;

                    }
                    if (objs[0].frgtp == null) {
                        //alert("here");

                        $scope.outgoing_invoice_nonexcise.fright_percent = parseInt("0");
                        //alert($scope.outgoing_invoice_nonexcise.freight_percent);
                    }
                    else {
                        $scope.outgoing_invoice_nonexcise.fright_percent = objs[0].frgtp;
                    }
                    if (objs[0].frgta == null || objs[0].frgta == "N") {
                        $scope.outgoing_invoice_nonexcise.freight_amount = parseInt(0);
                    }
                    else {
                        $scope.outgoing_invoice_nonexcise.freight_amount = objs[0].frgta;
                    }

                });
            }
        });
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "GET_PO_PRINCIPAL", PO_ID: $scope.outgoing_invoice_nonexcise.pono },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
                $scope.$apply(function () {
                    $('#principal_list').empty();
                    $("#principal_list").append("<option value=\'0'\ title=\'select'>select</option>");
                    for (var i = 0; i < objs.length; i++) {
                        $("#principal_list").append("<option value=\'" + objs[i].po_principalId + "'\ title=\'" + objs[i].po_principalName + "\'>" + objs[i].po_principalName + "</option>");
                        //$("#principal_list").append("<option value=\'" + objs[i].bpod_Id + "'\ title=\'" + objs[i].po_principalName + "\'>" + objs[i].po_principalName + "(" + objs[i].po_codePartNo + " " + objs[i].po_ed_applicability + ")" + "</option>");
                    }
                });
            }
        });
    }
    $scope.getprincipal = function () {
        var POid = $("#PurchaseOrderid").val();
        var passtag = "N";
        if ($scope.outgoing_invoice_nonexcise.principalID > 0) {
            var TYPE = "GETPODID";
            if (true) {
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: { TYP: TYPE, PODID: POid, PRINCIPALID: $scope.outgoing_invoice_nonexcise.principalID, TAG: passtag },
                    //cache: false,
                    success: function (jsondata) {
                        $('#item_master').empty();
                        $("#item_master").append("<option value='0'>Select Item</option>");
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj;

                            //                            $scope.$apply(function () {
                            //                                $scope.outgoing_invoice_nonexcise.discount = objs[0].po_discount;
                            //                                $scope.outgoing_invoice_nonexcise.po_saleTax = objs[0].po_saleTax;
                            //                                $scope.outgoing_invoice_nonexcise.bpod_Id = objs[0].bpod_Id;
                            //                                $scope.outgoing_invoice_nonexcise.buyer_item_code = objs[0].po_buyeritemcode;
                            //                                $scope.outgoing_invoice_nonexcise.oinv_price = objs[0].po_price;
                            //                                $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                            //                            });
                            for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#item_master").append("<option value=\"" + obj.po_itemId + "\">" + obj.po_codePartNo + "</option>");
                            }
                        }
                    }
                });
            }
        }
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "GET_Recuring_List", POID: POid },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    if (objs.length > 0) {
                        $("#help").show();
                        var index = 0;
                        //alert(jsondata);
                        while (index < objs.length) {
                            $("#rowdata").prepend("<tr><td>" + objs[index].cPartNo + "</td><td>" + objs[index].sch_rqty + "</td><td>" + objs[index].schDate + "</td><td>" + objs[index].sch_dqty + "</td></tr>");
                            index++;
                        }
                    }
                });
            }
        });
    }
    $scope.getTotal_Price = function () {
        var issuedQty = parseInt($scope.outgoing_invoice_nonexcise.issued_qty);
        if (issuedQty > 0) {
            $scope.outgoing_invoice_nonexcise.item_amount = $scope.outgoing_invoice_nonexcise.issued_qty * $scope.outgoing_invoice_nonexcise.oinv_price;
        }

    }
    $scope.checkStock = function () {
        var stockQty = parseInt($scope.outgoing_invoice_nonexcise.stock_qty);
        var issuedQty = parseInt($scope.outgoing_invoice_nonexcise.issued_qty);
        var orderQty = parseInt($scope.outgoing_invoice_nonexcise.ordered_qty);
        if (issuedQty > orderQty || issuedQty > stockQty) {
            $scope.outgoing_invoice_nonexcise.issued_qty = 0;
            alert("issued quantity naver be gretar then stock quantity or order quantity.");
        }
    }
    $scope.UpdateOnRow = function (item) {
        var Rowindex = $scope.outgoing_invoice_nonexcise._items.indexOf(item);
        var stockQty = parseInt($scope.outgoing_invoice_nonexcise._items[Rowindex]["stock_qty"]);
        var issuedQty = parseInt($scope.outgoing_invoice_nonexcise._items[Rowindex]["issued_qty"]);
        var orderQty = parseInt($scope.outgoing_invoice_nonexcise._items[Rowindex]["ordered_qty"]);
        if (issuedQty > orderQty || issuedQty > stockQty) {
            $scope.outgoing_invoice_nonexcise._items[Rowindex]["issued_qty"] = 0;
            alert("issued quantity naver be gretar then stock quantity or order quantity.");
        }
        if (issuedQty > 0) {
            $scope.outgoing_invoice_nonexcise._items[Rowindex]["item_amount"] = $scope.outgoing_invoice_nonexcise._items[Rowindex]["issued_qty"] * $scope.outgoing_invoice_nonexcise._items[Rowindex]["oinv_price"];
        }
        else {
            $scope.outgoing_invoice_nonexcise._items[Rowindex]["item_amount"] = 0;
        }
        $scope.BillCalculation();
    }
    $scope.itemdesc = function () {
        var ppppp = 0;
        while (ppppp < $scope.outgoing_invoice_nonexcise._items.length) {
            if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo == $scope.outgoing_invoice_nonexcise._items[ppppp]["_item_id"]) {
                alert("Naver be select same code part.");
                $("#item_master").val(0);
                return;
            }
            ppppp++;
        }
        if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
            //alert($scope.outgoing_invoice_nonexcise.oinv_codePartNo);
            var TYPE = "LOADITEM";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo },
                    //cache: false,
                    success: function (jsondata) {
                        var objs = jQuery.parseJSON(jsondata);
                        //alert(jsondata);
                        $scope.$apply(function () {
                            $scope.outgoing_invoice_nonexcise.codePartNo_desc = objs[0]._item_descp;
                        });

                    }
                });
            }
        }
        if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
            var TYPE = "GET_ABV_OUANTITY";
            if (true) {
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo },
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            var m = 0, liq = 0;
                            while (m < $scope.outgoing_invoice_nonexcise._items.length) {
                                liq = parseInt(liq) + parseInt($scope.outgoing_invoice_nonexcise._items[m]["issued_qty"]);
                                m++;
                            }
                            if (liq > 0) {
                                $scope.outgoing_invoice_nonexcise.stock_qty = parseInt(objs[0].tot_nonExciseQty) - parseInt(liq);
                            }
                            else {
                                $scope.outgoing_invoice_nonexcise.stock_qty = objs[0].tot_nonExciseQty;
                            }
                        });

                    }
                });
            }
        }
        if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
            var TYPE = "LOADPODETAILS";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Business_Action_Controller/po_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, PRINID: $scope.outgoing_invoice_nonexcise.principalID, POID: $("#PurchaseOrderid").val(), TAG: "N" },
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            $scope.outgoing_invoice_nonexcise.buyer_item_code = objs[0].po_buyeritemcode;
                            $scope.outgoing_invoice_nonexcise.oinv_price = objs[0].po_price;
                            $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                            $scope.outgoing_invoice_nonexcise.discount = objs[0].po_discount;
                            $scope.outgoing_invoice_nonexcise.saletaxID = objs[0].po_saleTax;
                            $scope.outgoing_invoice_nonexcise.po_saleTax = objs[0].po_saleTax;
                            $scope.outgoing_invoice_nonexcise.bpod_Id = objs[0].bpod_Id;
                            var m = 0, liq = 0;
                            while (m < $scope.outgoing_invoice_nonexcise._items.length) {
                                liq = parseInt(liq) + parseInt($scope.outgoing_invoice_nonexcise._items[m]["issued_qty"]);
                                m++;
                            }
                            if (liq > 0) {
                                $scope.outgoing_invoice_nonexcise.ordered_qty = parseInt(objs[0].po_qty) - parseInt(liq);
                            }
                            else {
                                $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                            }
                        });
                    }
                });
            }
        }
    }

} ]);

Outgoing_Invoice_NonExcise_App.directive('validNumber', function () {
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


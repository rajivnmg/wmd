var BuyerList = {};
var a = ['', 'One ', 'Two ', 'Three ', 'Four ', 'Five ', 'Six ', 'Seven ', 'Eight ', 'Nine ', 'Ten ', 'Eleven ', 'Twelve ', 'Thirteen ', 'Fourteen ', 'Fifteen ', 'Sixteen ', 'Seventeen ', 'Eighteen ', 'Nineteen '];
var b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

function inWords(num) {
    if ((num = num.toString()).length > 9)
        return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n)
        return;
    var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
    return str;
}

function CallToBuyer(BuyerList) {
    'use strict';
    var buyerArray = $.map(BuyerList, function (value, key) {
        return {value: value, data: key};
    });
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
    var PurchaseOrderArray = $.map(PurchaseOrderList, function (value, key) {
        return {value: value, data: key};
    });
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

function ActionOnPurchaseOrder(value, data) {
    if (value != "" && data > 0) {
        var PO_num = data;
        $("#PurchaseOrderid").val(data);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: method,
            data: {TYP: "MA_FILL", PO_NUMBER: data,po_ed_applicability:'N'},
            success: function (jsondata) {
               // alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $("#buyerid").val(objs[0].bn);
                $("#bpoType").val(objs[0].pot);//######### added by aksoni             
                $("#autocomplete-ajax-buyer").val(objs[0].bn_name);
                $("#inv_po_date").val(objs[0].pod);
                $("#autocomplete-ajax-PurchaseOrder").val(objs[0].pon);

		$("#bemailId").val(objs[0].bemailId);
                $("#freightTag").val(objs[0].frgt);//###### added by aksoni on 09/04/2015
                //alert(objs[0].pf_chrg);
                //alert(objs[0].inci_chrg);
				$("#marketsegment").val(objs[0].ms);
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
            data: {TYP: "GET_PO_PRINCIPAL", PO_ID: PO_num,po_ed_applicability:'N'},
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
// commented by codefire to page increase loading performance 24.11.15.
/* $(document).ready(function () {
    jQuery.ajax({
        url: "../../Controller/Master_Controller/Buyer_Controller.php",
        type: "post",
        data: {TYP: "SELECT", BUYERID: 0},
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
        url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
        type: "post",
        data: {TYP: "GETPOLIST"},
        success: function (jsondata) {
            var objs = jQuery.parseJSON(jsondata);
            if (jsondata != "") {
                var obj;
                for (var items in objs) {
                    PurchaseOrderList[items] = objs[items];
                }
                CallToPurchaseOrder(PurchaseOrderList);
            }
        }
    });
}); */

// added by codefire to page increase loading performance 24.11.15.
 function loadPoByNumber(PONUMBER){ 
	if(PONUMBER.length > 1 && PONUMBER.length < 3){ 
		  jQuery.ajax({
				url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
				type: "post",
				data: { TYP: "GETPOLIST", PONUMBER: PONUMBER},
				success: function (jsondata) {
					var objs = jQuery.parseJSON(jsondata);
					if (jsondata != "") {
						var obj;
						for (var items in objs) {
							PurchaseOrderList[items] = objs[items];
						}
						CallToPurchaseOrder(PurchaseOrderList);
			//            for (var i = 0; i < objs.length; i++) {
			//                var obj = objs[i];
			//                PurchaseOrderList[obj.poid] = obj.pono;
			//            }
						
					}
				}
			});
	}else{
		CallToPurchaseOrder(PurchaseOrderList);
	}
}


var URL = "../../Controller/Business_Action_Controller/Outgoing_Invoice_NonExcise_Controller.php";
var method = "POST";
var Outgoing_Invoice_NonExcise_App = angular.module('Outgoing_Invoice_NonExcise_App', []);

Outgoing_Invoice_NonExcise_App.controller('Outgoing_Invoice_NonExcise_Controller', ['$scope', '$http', function Outgoing_Invoice_NonExcise_Controller($scope, $http) {



        var sample_outgoing_invoice_nonexcise = {_items: [{bpod_Id: 0, buyer_item_code: '', oinv_codePartNo: '', _item_id: 0, codePartNo_desc: '', ordered_qty: '',balance_qty: '', stock_qty: '', issued_qty: '', oinv_price: '', item_amount: '', discount: 0, saletaxID: 0}]};

        $scope.outgoing_invoice_nonexcise = sample_outgoing_invoice_nonexcise;

        $scope.addItem = function () {
			
            var issuedQty = parseFloat($scope.outgoing_invoice_nonexcise.issued_qty);
            if (issuedQty > 0) {
				$("#btnsave").hide();
                $scope.outgoing_invoice_nonexcise._item_id = $("#itemid").val();
                $scope.outgoing_invoice_nonexcise.bpod_Id= $("#bpodid").val();               
                $scope.outgoing_invoice_nonexcise.oinv_codePartNo = $("#bpodid option:selected").text();

                $scope.outgoing_invoice_nonexcise.pf_chrg_percent = $("#txtpf_charg_percent").val();
                $scope.outgoing_invoice_nonexcise.pf_chrg = 0;
                $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = $("#txtincidental_chrg_percent").val();
                $scope.outgoing_invoice_nonexcise.incidental_chrg = 0;
                $scope.outgoing_invoice_nonexcise.freight_percent = $("#txtfreight_percent").val();
                $scope.outgoing_invoice_nonexcise.freight_amount = $("#txtfreight_amount").val();


                var check = 0;
                var discount_flag = false;
                var saletax_flag = false;
                //alert($scope.outgoing_invoice_nonexcise._items.length);
                while (check < $scope.outgoing_invoice_nonexcise._items.length) {
                   // alert(parseFloat($scope.outgoing_invoice_nonexcise.discount));
                   // alert(parseFloat($scope.outgoing_invoice_nonexcise._items[check]["discount"]));
                    if (parseFloat($scope.outgoing_invoice_nonexcise.discount) == parseFloat($scope.outgoing_invoice_nonexcise._items[check]["discount"])) {
                        discount_flag = true;
                    }
                    if (parseFloat($scope.outgoing_invoice_nonexcise.saletaxID) == parseFloat($scope.outgoing_invoice_nonexcise._items[check]["saletaxID"])) {
                        saletax_flag = true;
                    }
                    
                    check++;
                }
                if (parseInt($scope.outgoing_invoice_nonexcise._items.length) == 0) {
                    discount_flag = true;
                    saletax_flag = true;
                }
                //alert("discount_flag-->"+discount_flag);
                if (discount_flag && saletax_flag) {
                    $scope.outgoing_invoice_nonexcise._items.push({bpod_Id: $scope.outgoing_invoice_nonexcise.bpod_Id, buyer_item_code: $scope.outgoing_invoice_nonexcise.buyer_item_code, oinv_codePartNo: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, _item_id: $scope.outgoing_invoice_nonexcise._item_id, codePartNo_desc: $scope.outgoing_invoice_nonexcise.codePartNo_desc, ordered_qty: $scope.outgoing_invoice_nonexcise.ordered_qty,balance_qty: $scope.outgoing_invoice_nonexcise.balance_qty,stock_qty: $scope.outgoing_invoice_nonexcise.stock_qty, issued_qty: $scope.outgoing_invoice_nonexcise.issued_qty, oinv_price: $scope.outgoing_invoice_nonexcise.oinv_price, item_amount: $scope.outgoing_invoice_nonexcise.item_amount, discount: $scope.outgoing_invoice_nonexcise.discount, saletaxID: $scope.outgoing_invoice_nonexcise.saletaxID});
                    $scope.calculation();

                }
                else {
                    alert("This item have different discount or sale tax or both so we can not added this item in this invoice.");
                }

                $scope.outgoing_invoice_nonexcise.bpod_Id = "";
                $scope.outgoing_invoice_nonexcise.buyer_item_code = "";
                $scope.outgoing_invoice_nonexcise.oinv_codePartNo = "";
                $scope.outgoing_invoice_nonexcise._item_id = "";
                $scope.outgoing_invoice_nonexcise.codePartNo_desc = "";
                $scope.outgoing_invoice_nonexcise.ordered_qty = "";
                $scope.outgoing_invoice_nonexcise.balance_qty = "";
                $scope.outgoing_invoice_nonexcise.stock_qty = "";
                $scope.outgoing_invoice_nonexcise.issued_qty = "";
                $scope.outgoing_invoice_nonexcise.oinv_price = "";
                $scope.outgoing_invoice_nonexcise.item_amount = "";
                $scope.outgoing_invoice_nonexcise.discount = "";
                $scope.outgoing_invoice_nonexcise.saletax = "";
				$("#btnsave").show();
            }
            else {
                alert("amount and issued quantity never be blank.");
            }

        }
 $scope.removeItem = function (item) {
            $scope.outgoing_invoice_nonexcise._items.splice($scope.outgoing_invoice_nonexcise._items.indexOf(item), 1);
            //alert(item);
            $scope.calculation();
 }
 $scope.calculation = function () {

                     var k = 0;
                     $scope.outgoing_invoice_nonexcise.bill_value = 0;
                     $scope.outgoing_invoice_nonexcise.total_discount = 0;
                     $scope.outgoing_invoice_nonexcise.total_saleTax = 0;

                     var basic_amount = 0.00;taxid =0;
                     var SaletaxPurcent = 0.00, SurchargePercent = 0.00;var SurchargeAmount = 0.00;
                     var Discount_percent = 0.00, Discount_Amount = 0.00;
                     var CalculateAmount =0.00;var pf_charge_amount =0.00;var incidental_amount =0.00;
                     var TaxableAmount =0.00;var TaxAmount = 0.00;salesTaxAmount=0.00;PayAmount =0.00;
                     var F_tag='';
                     
                     //alert($scope.outgoing_invoice_nonexcise._items.length);

                     var F_tag=$("#freightTag").val();
                    
                     //alert($scope.outgoing_invoice_nonexcise.saletaxID);
                      while (k < $scope.outgoing_invoice_nonexcise._items.length) {

                         taxid = parseInt($scope.outgoing_invoice_nonexcise._items[k]["saletaxID"]);
                         Discount_percent = parseFloat($scope.outgoing_invoice_nonexcise._items[k]["discount"]);
                         basic_amount = parseFloat(basic_amount) + parseFloat($scope.outgoing_invoice_nonexcise._items[k]["item_amount"]);
                         Discount_Amount = ((parseFloat(basic_amount) * parseFloat(Discount_percent)) / 100);
                         CalculateAmount = basic_amount - Discount_Amount;
                         //alert("|dp"+Discount_percent+"|ba--"+basic_amount+"|da-"+Discount_Amount+"cal-"+CalculateAmount);


                         if (!isNaN($scope.outgoing_invoice_nonexcise.pf_chrg_percent) && $scope.outgoing_invoice_nonexcise.pf_chrg_percent != "" && $scope.outgoing_invoice_nonexcise.pf_chrg_percent != null) {
                              pf_charge_amount =((parseFloat($scope.outgoing_invoice_nonexcise.pf_chrg_percent) * CalculateAmount) / 100);
                          }
 			if (!isNaN($scope.outgoing_invoice_nonexcise.incidental_chrg_percent) && $scope.outgoing_invoice_nonexcise.incidental_chrg_percent != "" && $scope.outgoing_invoice_nonexcise.incidental_chrg_percent != null) {
 			    incidental_amount = (parseFloat($scope.outgoing_invoice_nonexcise.incidental_chrg_percent) * CalculateAmount) / 100;
 			}
                         TaxableAmount = parseFloat(CalculateAmount) + parseFloat(pf_charge_amount) + parseFloat(incidental_amount);
                         //alert("pfa--"+pf_charge_amount+"ia--"+incidental_amount);

                          k++;
                     }

                         $scope.outgoing_invoice_nonexcise.total_discount=Discount_Amount;
                         //alert("---t"+TaxableAmount);
                         //alert(taxid);
                         jQuery.ajax({
                         url: URL,
                         type: "POST",
                         data: {TYP: "GETTAXDETAILS", TAXID: taxid},
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
                             //alert("TaxableAmount--"+TaxableAmount+"--"+SaletaxPurcent+"--sur--"+SurchargePercent);
                             TaxAmount =(( parseFloat(TaxableAmount) * parseFloat(SaletaxPurcent))/100);
                             //alert("TaxAmount-->"+TaxAmount);
                             SurchargeAmount = ((parseFloat(SurchargePercent) * parseFloat(TaxAmount))/100);
                             salesTaxAmount=parseFloat(TaxAmount)+parseFloat(SurchargeAmount);
                             //alert("sa--"+salesTaxAmount);
                             //TaxAmount = TaxAmount / 100;
                             //SurchargeAmount = parseFloat(SurchargeAmount) / 100;
                              var F_amt = 0.00;
                              if (F_tag=='P') {//#### condition modify by aksoni 09/04/2015 
                            
                             
                                 F_amt = ((parseFloat(TaxableAmount)) / 100) * parseFloat($("#txtfreight_percent").val());
                                 $scope.outgoing_invoice_nonexcise.freight_amount = F_amt.toFixed(2);
                             }else if (F_tag=='A') {//#### condition modify by aksoni 09/04/2015
                                 F_amt = parseFloat($("#txtfreight_amount").val());
                                 $scope.outgoing_invoice_nonexcise.freight_amount = F_amt.toFixed(2);
                                 $scope.outgoing_invoice_nonexcise.freight_percent = (parseFloat(F_amt) * 100) / (parseFloat(TaxableAmount));
                             }

                             $scope.$apply(function () {
                             //var totalSaleTaxAmt=parseFloat(TaxAmount.toFixed(2)) + parseFloat(SurchargeAmount.toFixed(2));
                             $scope.outgoing_invoice_nonexcise.total_discount = Discount_Amount.toFixed(2);
                             $scope.outgoing_invoice_nonexcise.pf_chrg = pf_charge_amount.toFixed(2);
                             $scope.outgoing_invoice_nonexcise.incidental_chrg = incidental_amount.toFixed(2);
                             $scope.outgoing_invoice_nonexcise.total_saleTax = (parseFloat(salesTaxAmount).toFixed(2));
                             //alert("totsaletax"+$scope.outgoing_invoice_nonexcise.total_saleTax);
                             //alert(parseFloat(TaxableAmount));
                             //alert(parseFloat(salesTaxAmount));
                             PayAmount = parseFloat(TaxableAmount) + parseFloat(salesTaxAmount) + parseFloat(F_amt);

                             $scope.outgoing_invoice_nonexcise.bill_value = parseFloat(PayAmount.toFixed(2));
                             });

                         }
                     });
				

 }
 
 $scope.UpdateOnRow = function (item) {

        var Rowindex = $scope.outgoing_invoice_nonexcise._items.indexOf(item);
                   var Rowindex = $scope.outgoing_invoice_nonexcise._items.indexOf(item,1);
                //alert(Rowindex);
                if(Rowindex<0){
                Rowindex =parseInt(Rowindex)+1;
                }
        //alert(Rowindex);
        var stockQty = parseFloat($scope.outgoing_invoice_nonexcise._items[Rowindex]['stock_qty']);
        var issuedQty = parseFloat($scope.outgoing_invoice_nonexcise._items[Rowindex]['issued_qty']);
        var orderQty = parseFloat($scope.outgoing_invoice_nonexcise._items[Rowindex]['ordered_qty']);
        if (issuedQty > orderQty || issuedQty > stockQty) {
            $scope.outgoing_invoice_nonexcise._items[Rowindex]['issued_qty'] = 0;
            $scope.outgoing_invoice_nonexcise._items[Rowindex]['item_amount'] =0;
            alert("issued quantity naver be gretar then stock quantity or order quantity.");
        }else{

           $scope.outgoing_invoice_nonexcise._items[Rowindex]['item_amount'] = parseFloat(issuedQty) * parseFloat($scope.outgoing_invoice_nonexcise._items[Rowindex]['oinv_price']);
        }
        $scope.calculation();
    }



        $scope.BuyerDetaile = [{}];
        $scope.PrincipalDetaile = [{}];
        $scope.BillDetaile = [{}];
        $scope.TaxDetaile = [{}];
        $scope.PODetaile = [{}];
        $scope.AddRow = function (i, objspo, data) {
		
            $scope.PODetaile._items2.push({sn: i + 1, codepart: data[0]._itmes[i].buyer_item_code, desc: data[0]._itmes[i].codePartNo_desc, cpart: '( ' + data[0]._itmes[i].oinv_codePartNo + ' ) ', qty: data[0]._itmes[i].issued_qty + ' ' + data[0]._itmes[i]._unitname, rate: data[0]._itmes[i].oinv_price, amount: (data[0]._itmes[i].oinv_price * data[0]._itmes[i].issued_qty)});
            $scope.BillDetaile.basic_amount = parseFloat($scope.BillDetaile.basic_amount) + parseFloat($scope.PODetaile._items2[i]["amount"]);
        };
        $scope.GetIncomingInvoiceData = function (i, objspo, data) {
            $scope.AddRow(i, objspo, data);
            if (i == data[0]._itmes.length - 1) {
                $scope.CalculateTax(objspo, data);
            }
        }

        var print_itme_list = [{sn: 0, codepart: '', desc: '', qty: '', rate: '', amount: '', cpart: ''}];
        $scope.print = function (number) {
            var BuyerId = 0, PoID = 0;
            var responsePromise = $http.get(URL + "?TYP=SELECT&OutgoingInvoiceNonExciseNum=" + number);
            responsePromise.success(function (data, status, headers, config) {
                $scope.OutgoingInvoiceNonExciseNum = data[0];
                var totalamt = $scope.OutgoingInvoiceNonExciseNum.bill_value;
                var round_off_value = parseFloat(totalamt.toString().split(".")[1]);
                if(round_off_value >= 50)
                {
                    $scope.OutgoingInvoiceNonExciseNum.bill_value = parseFloat(totalamt.toString().split(".")[0]) + 1;
                }
                else
                {
                    $scope.OutgoingInvoiceNonExciseNum.bill_value = parseFloat(totalamt.toString().split(".")[0]);
                }
                $scope.OutgoingInvoiceNonExciseNum.toatlvalueinword = inWords(parseInt($scope.OutgoingInvoiceNonExciseNum.bill_value));
                PoID = data[0].pono;
				
                var responsePO = $http.get("../../Controller/Business_Action_Controller/po_Controller.php?TYP=MA_FILL&PO_NUMBER=" + PoID+"&po_ed_applicability=N");
                responsePO.success(function (objspo, status, headers, config) {
				
                    $scope.PODetaile = objspo[0];
                    $scope.PODetaile._items2 = print_itme_list;
                    BuyerId = objspo[0].bn;
                    var responseBuyer = $http.get("../../Controller/Master_Controller/Buyer_Controller.php?TYP=SELECT&BUYERID=" + BuyerId);
                    responseBuyer.success(function (data3, status, headers, config) {
                        $scope.BuyerDetaile = data3[0];
                    });
                    $scope.PODetaile._items2.splice($scope.PODetaile._items2.indexOf(0), 1);
                    $scope.BillDetaile.discount_amt = data[0].discount;
                   // $scope.BillDetaile.discount_percent = objspo[0]._items[0].po_discount;
				    $scope.BillDetaile.discount_percent = data[0]._itmes[0].discount;
					
                    if ($scope.BillDetaile.discount_amt <= 0 || $scope.BillDetaile.discount_amt == null)
                    {
                        $("#discountdetails").hide();
                        $("#discountdetails2").hide();
                    }
                    $scope.BillDetaile.pf_amt = data[0].pf_chrg;
                    $scope.BillDetaile.pf_percent = parseFloat(objspo[0].pf_chrg);
                    if (parseFloat(data[0].pf_chrg) <= 0 || data[0].pf_chrg == null)
                    {
                        $("#pfblog").hide();
                        $("#pfblog2").hide();
                        //$("#billdetailsrow").attr("rowspan","5");
                    }

                    $scope.BillDetaile.inci_amt = data[0].incidental_chrg;
                    $scope.BillDetaile.inci_percent = parseFloat(objspo[0].inci_chrg);
                    if (parseFloat(data[0].incidental_chrg) <= 0 || data[0].incidental_chrg == null)
                    {
                        $("#inciblog").hide();
                        $("#inciblog2").hide();
                        //$("#billdetailsrow").attr("rowspan","5");
                    }
                    $scope.BillDetaile.feright_amt = data[0].freight_amount;
                    if(objspo[0].frgtp==0 ||objspo[0].frgtp==""||objspo[0].frgtp==0.00)
                   {
					$scope.BillDetaile.feright_percent='';
				   }
				    else
				    {
					 $scope.BillDetaile.feright_percent = ' @ '+parseFloat(objspo[0].frgtp)+'%';
				   }
                    if (parseFloat(data[0].freight_amount) <= 0 || data[0].freight_amount == null)
                    {
                        $("#ferightblog").hide();
                        $("#ferightblog2").hide();
                        //$("#billdetailsrow").attr("rowspan","5");
                    }
					$scope.BillDetaile.discount_percent = parseFloat(data[0]._itmes[0].discount);
					$("#discount_percent").text(objspo[0]._items[0].po_discount);

                    $scope.BillDetaile.basic_amount = 0.00, $scope.BillDetaile.TaxableAmount = 0.00;
                    for (var i = 0; i < objspo[0]._items.length; i++) {
                        $scope.GetIncomingInvoiceData(i, objspo, data);
                    }


                });

            });
        };

        $scope.CalculateTax = function (objspo, data) {
			$scope.BillDetaile.discount_percent = Math.round(($scope.BillDetaile.discount_amt * 100)/$scope.BillDetaile.basic_amount); // this line added by discount not showing for invoice number :T-15001601, po_no :4544942664 from code-fire 3-12-15 
            $scope.BillDetaile.TaxableAmount = parseFloat($scope.BillDetaile.basic_amount) - parseFloat($scope.BillDetaile.discount_amt) + parseFloat(data[0].pf_chrg) + parseFloat(data[0].incidental_chrg);

            $scope.BillDetaile.TaxableAmount = ($scope.BillDetaile.TaxableAmount).toFixed(2);
            var responseTax = $http.get(URL + "?TYP=GETTAXDETAILS&TAXID=" + data[0]._itmes[0].saletaxID);
            responseTax.success(function (datatax, status, headers, config) {
                $scope.TaxDetaile = datatax[0];
                if (datatax[0].TYPE == "C")
                {
                    $("#cst_invoice").show();
                }
                else
                {
                    $("#vat_invoice").show();
                    $("#vat_invoice2").show();
                }
                $scope.BillDetaile.SaleTaxAmount = (parseFloat($scope.BillDetaile.TaxableAmount) * parseFloat(datatax[0].SALESTAX_CHRG)) / 100;
                $scope.BillDetaile.SurchargeAmount = (parseFloat($scope.BillDetaile.SaleTaxAmount) * parseFloat(datatax[0].SURCHARGE)) / 100;
                $scope.BillDetaile.SaleTaxAmount = ($scope.BillDetaile.SaleTaxAmount).toFixed(2);
                $scope.BillDetaile.SurchargeAmount = ($scope.BillDetaile.SurchargeAmount).toFixed(2);
                if (parseFloat($scope.BillDetaile.SurchargeAmount) <= 0 || $scope.BillDetaile.SurchargeAmount == "NaN")
                {
                    $("#surchargeblog").hide();
                    $("#surchargeblog2").hide();
                }
            });
        }

        $scope.init = function (number) {
            if (number > 0) {
                $("#btnsave").hide();
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: {TYP: "SELECT", OutgoingInvoiceNonExciseNum: number},
                    success: function (jsondata) {
                        var objs = jQuery.parseJSON(jsondata);
                        //alert(jsondata);
                        $scope.$apply(function () {
                            $scope.outgoing_invoice_nonexcise = objs[0];
                            $scope.outgoing_invoice_nonexcise._items = objs[0]._itmes;
                            //$scope.outgoing_invoice_nonexcise._old_items = objs[0]._itmes;
                            //alert(objs[0].po_discount);
                            //$scope.GetPurchaseOrderDetails();
                            //ActionOnPurchaseOrder("y", objs[0].pono);
                            $("#PurchaseOrderid").val(objs[0].pono);
                            
                            jQuery.ajax({
                                url: "../../Controller/Business_Action_Controller/po_Controller.php",
                                type: method,
                                data: {TYP: "MA_FILL", PO_NUMBER: objs[0].pono,po_ed_applicability:'N'},
                                success: function (jsondata_po) {
                                    //alert(jsondata_po);
                                    var objs_po = jQuery.parseJSON(jsondata_po);
                                    $("#buyerid").val(objs_po[0].bn);
                                    $("#bpoType").val(objs_po[0].pot);
                                    $("#freightTag").val(objs_po[0].frgt);//###### added by aksoni on 09/04/2015 
                                    $("#bemailId").val( objs_po[0].bemailId);
                                    //********
                                    
                                    if($("#freightTag").val()=='P')
                                    {
										$("#txtfreight_percent").val(objs_po[0].frgtp);
									}
									else if($("#freightTag").val()=='A'){
										 $("#txtfreight_amount").val(objs[0].freight_amount);
									}
                                    
                                    //***
                                     $scope.getprincipal();
                                    $("#autocomplete-ajax-buyer").val(objs_po[0].bn_name);
                                    $("#inv_po_date").val(objs_po[0].pod);
                                    $("#autocomplete-ajax-PurchaseOrder").val(objs_po[0].pon);
                                    $scope.outgoing_invoice_nonexcise.pf_chrg_percent = objs_po[0].pf_chrg;
                                    $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = objs_po[0].inci_chrg;

                                    $("#txtsaletax").val(objs_po[0]._items[0].po_saleTax);
                                }
                            });

                            jQuery.ajax({
                                url: URL,
                                type: method,
                                data: {TYP: "GET_PO_PRINCIPAL", PO_ID: objs[0].pono,po_ed_applicability:'N'},
                                success: function (jsondata) {
                                    var objs3333 = jQuery.parseJSON(jsondata);
                                    $('#principal_list').empty();
                                    $("#principal_list").append("<option value=\'0'\ title=\'select'>Select Principal</option>");
                                    for (var i = 0; i < objs3333.length; i++) {
                                        $("#principal_list").append("<option value=\'" + objs3333[i].po_principalId + "'\ title=\'" + objs3333[i].po_principalName + "\'>" + objs3333[i].po_principalName + "</option>");
                                    }
                                    $('#principal_list').val(objs[0].principalID);
                                }
                            });
                             
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
                }
                ;
                var day = d.getDate();
                $scope.outgoing_invoice_nonexcise.oinv_date = day + "/" + month + "/" + year;
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: {TYP: "AUTOCODE"},
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
        $scope.AddOutgoingInvoiceExcise = function () {
            if ($scope.outgoing_invoice_nonexcise._items.length <= 0) {
                alert("Atleast one row should be added in a item grid before submit form.");
                return;
            }
            $("#btnsave").hide();
            $scope.outgoing_invoice_nonexcise.oinv_date = $("#inv_date").val();
            $scope.outgoing_invoice_nonexcise.po_date = $("#inv_po_date").val();
            $scope.outgoing_invoice_nonexcise.poid = $("#PurchaseOrderid").val();
            $scope.outgoing_invoice_nonexcise.BuyerID = $("#buyerid").val();
            $scope.outgoing_invoice_nonexcise.pot=$("#bpoType").val();//######### added by aksoni 01/04/2015
			$scope.outgoing_invoice_nonexcise.ms = $("#marketsegment").val();
			$scope.outgoing_invoice_nonexcise.pono = $("#autocomplete-ajax-PurchaseOrder").val();
			$scope.outgoing_invoice_nonexcise.bemailId = $("#bemailId").val();
            var json_string = JSON.stringify($scope.outgoing_invoice_nonexcise);
            //alert(json_string); return;
            jQuery.ajax({
                url: URL,
                type: method,
                data: {TYP: "INSERT", OUTGOING_INVOICE_NONEXCISE_DATA: json_string},
                success: function (jsondata) {
                    $scope.$apply(function () {					
						if(jsondata == "CREDIT_TIME_ERROR"){
							alert("Not Saved, PO is Blocked Due To Exceeded Credit Time Period"); 
							return ;
						
						}else if(jsondata == "ALREADY"){	
							
						}else if(jsondata == "WRONG_CALULATION"){	
							alert("Not Saved, Wrong Calulation");
						}else{
							alert("Successfully Saved.");
							$scope.outgoing_invoice_nonexcise = null;
							location.href = "print_outgoingNonExcise.php?OutgoingInvoiceNonExciseNum=" + jsondata;
						}                      

                    });
                }
            });
        }
        $scope.Update = function () {
        	 $scope.outgoing_invoice_nonexcise.pot=$("#bpoType").val();//######### added by aksoni 01/04/2015
		 $scope.outgoing_invoice_nonexcise.ms = $("#marketsegment").val();
        	  $("#btnupdate").hide();
        	  $("#btnprint").hide();
		  $scope.outgoing_invoice_nonexcise.pono = $("#PurchaseOrderid").val();
            var json_string = JSON.stringify($scope.outgoing_invoice_nonexcise);
			
            jQuery.ajax({
                url: URL,
                type: method,
                data: {TYP: "UPDATE", OUTGOING_INVOICE_NONEXCISE_DATA: json_string},
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
                data: {TYP: "MA_FILL", PO_NUMBER: PO_num,po_ed_applicability:'N'},
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
                            $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = parseFloat(0);
                            $scope.outgoing_invoice_nonexcise.incidental_chrg = parseFloat(0);
                        }
                        else {
                            $scope.outgoing_invoice_nonexcise.incidental_chrg_percent = objs[0].inci_chrg;

                        }
                        if (objs[0].frgtp == null) {
                            //alert("here");

                            $scope.outgoing_invoice_nonexcise.fright_percent = parseFloat("0");
                            //alert($scope.outgoing_invoice_nonexcise.freight_percent);
                        }
                        else {
                            $scope.outgoing_invoice_nonexcise.fright_percent = objs[0].frgtp;
                        }
                        if (objs[0].frgta == null || objs[0].frgta == "N") {
                            $scope.outgoing_invoice_nonexcise.freight_amount = parseFloat(0);
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
                data: {TYP: "GET_PO_PRINCIPAL", PO_ID: $scope.outgoing_invoice_nonexcise.pono,po_ed_applicability:'N'},
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
            var bpoType=$("#bpoType").val();
          
            var passtag = "N";
            //alert(POid+"|"+$scope.outgoing_invoice_nonexcise.principalID+"|"+passtag);
            if ($scope.outgoing_invoice_nonexcise.principalID > 0) { 				
                var TYPE = "GETPODID";
                if (true) {
                
                    jQuery.ajax({
                        url: URL,
                        type: "POST",
                        data: {TYP: TYPE, PODID: POid, PRINCIPALID: $scope.outgoing_invoice_nonexcise.principalID, TAG: passtag,BPOTYPE:bpoType},
                        //cache: false,
                        success: function (jsondata) {
                            $('#bpodid').empty();
                            $("#bpodid").append("<option value='0'>Select Item</option>");
                            //alert(jsondata);
                            var objs = jQuery.parseJSON(jsondata);
                            if (jsondata != "") {
                                var obj;

                                                            $scope.$apply(function () {
                                //                                $scope.outgoing_invoice_nonexcise.discount = objs[0].po_discount;
                                //                                $scope.outgoing_invoice_nonexcise.po_saleTax = objs[0].po_saleTax;
                                //                                $scope.outgoing_invoice_nonexcise.bpod_Id = objs[0].bpod_Id;
                                //                                $scope.outgoing_invoice_nonexcise.buyer_item_code = objs[0].po_buyeritemcode;
                                //                                $scope.outgoing_invoice_nonexcise.oinv_price = objs[0].po_price;
                                //                                $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                                                           for (var i = 0; i < objs.length; i++) {
                                                                var obj = objs[i];
                                                                $("#bpodid").append("<option value=\"" + obj.bpod_Id+ "\">" + obj.po_codePartNo + "</option>");
                                                               }
                                                            });

                            }
                        }
                    });
                }
            }
            jQuery.ajax({
                url: URL,
                type: method,
                data: {TYP: "GET_Recuring_List", POID: POid,PRINCIPALID:$scope.outgoing_invoice_nonexcise.principalID,po_ed_applicability:'N'},
                success: function (jsondata) {
                   // alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        if (objs.length > 0) {
                            $("#help").show();
                            var index = 0;
                           // alert(jsondata);
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
            var issuedQty = parseFloat($scope.outgoing_invoice_nonexcise.issued_qty);
            if (issuedQty > 0) {
                $scope.outgoing_invoice_nonexcise.item_amount = $scope.outgoing_invoice_nonexcise.issued_qty * $scope.outgoing_invoice_nonexcise.oinv_price;
            }

        }
        $scope.checkStock = function () {
            var stockQty = parseFloat($scope.outgoing_invoice_nonexcise.stock_qty);
            var issuedQty = parseFloat($scope.outgoing_invoice_nonexcise.issued_qty);
            var orderQty = parseFloat($scope.outgoing_invoice_nonexcise.ordered_qty);
            var balance_qty = parseFloat($scope.outgoing_invoice_nonexcise.balance_qty);

            if (issuedQty > balance_qty || issuedQty > stockQty) {
                $scope.outgoing_invoice_nonexcise.issued_qty = 0;
                alert("issued quantity never be gretar then stock quantity or balance quantity.");
            }

        }
        $scope.itemdesc = function () {
            //alert($scope.outgoing_invoice_nonexcise.oinv_codePartNo);
              var bpoType=$("#bpoType").val();
              var passtag="N";
          //  if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
          	
          	 if ($scope.outgoing_invoice_nonexcise.bpod_Id > 0) {
           
                // var TYPE = "LOADITEM";
                var TYPE = "LOADPOITEMDETAIL";
                if (true) {
                    jQuery.ajax({
                       // url: "../../Controller/Master_Controller/Item_Controller.php",
                        url: "../../Controller/Business_Action_Controller/po_Controller.php",
                        type: "POST",
                       // data: {TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo},
                        data: {TYP: TYPE, BPODID: $scope.outgoing_invoice_nonexcise.bpod_Id,BPOTYPE:bpoType,TAG: passtag},
                        //cache: false,
                        success: function (jsondata) {
                            var objs = jQuery.parseJSON(jsondata);
                           //alert(jsondata);
                            $scope.$apply(function () {
                                $scope.outgoing_invoice_nonexcise.codePartNo_desc = objs[0].Item_Desc;
                                $scope.outgoing_invoice_nonexcise.oinv_codePartNo=objs[0].Item_Code_Partno;
                                $scope.outgoing_invoice_nonexcise._item_id= objs[0].po_codePartNo;
                                $scope.outgoing_invoice_nonexcise.stock_qty= objs[0].stock_qty;
                                $scope.outgoing_invoice_nonexcise.buyer_item_code = objs[0].po_buyeritemcode;
                                $scope.outgoing_invoice_nonexcise.oinv_price = objs[0].po_price;
                                $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                                //$scope.outgoing_invoice_nonexcise.balance_qty = objs[0].po_balance_qty;
                                $scope.outgoing_invoice_nonexcise.discount = objs[0].po_discount;
                                $scope.outgoing_invoice_nonexcise.saletaxID = objs[0].po_saleTax;
                                $scope.outgoing_invoice_nonexcise.po_saleTax = objs[0].po_saleTax;
                              //  $scope.outgoing_invoice_nonexcise.bpod_Id = objs[0].bpod_Id;
                                var m = 0, liq = 0;
                                while (m < $scope.outgoing_invoice_nonexcise._items.length) {
                                  if (($scope.$scope.outgoing_invoice_nonexcise._item_id==$scope.outgoing_invoice_nonexcise._items[m]["_item_id"])&&($scope.outgoing_invoice_nonexcise.bpod_Id==$scope.outgoing_invoice_nonexcise._items[m]["bpod_Id"] )) {
                                    liq = parseFloat(liq) + parseFloat($scope.outgoing_invoice_nonexcise._items[m]["issued_qty"]);
                                  }
                                   m++;
                                }
                                //alert(liq);
                                if (liq > 0) {
                                   $scope.outgoing_invoice_nonexcise.balance_qty = parseFloat(objs[0].po_qty) - parseFloat(liq);
                                }
                                else {
                                   $scope.outgoing_invoice_nonexcise.balance_qty = parseFloat(objs[0].po_balance_qty);
                                }
                            });

                        }
                    });
                }
            }
           /* if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
                var TYPE = "GET_ABV_OUANTITY";
                if (true) {
                    jQuery.ajax({
                        url: URL,
                        type: "POST",
                        data: {TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo},
                        //cache: false,
                        success: function (jsondata) {
                            //alert(jsondata);
                            var objs = jQuery.parseJSON(jsondata);
                            $scope.$apply(function () {
                                $scope.outgoing_invoice_nonexcise.stock_qty = objs[0].tot_nonExciseQty;
                            });

                        }
                    });
                }
            }*/
           /* if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo > 0) {
            	//alert(bpoType);
                var TYPE = "LOADPODETAILS";
                if (true) {
                    jQuery.ajax({
                        url: "../../Controller/Business_Action_Controller/po_Controller.php",
                        type: "POST",
                        data: {TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, PRINID: $scope.outgoing_invoice_nonexcise.principalID, POID: $("#PurchaseOrderid").val(), TAG: "N",BPOTYPE:bpoType},
                        //cache: false,
                        success: function (jsondata) {
                            //alert(jsondata);
                            var objs = jQuery.parseJSON(jsondata);
                            $scope.$apply(function () {
                            	//alert(jsondata);
                                $scope.outgoing_invoice_nonexcise.buyer_item_code = objs[0].po_buyeritemcode;
                                $scope.outgoing_invoice_nonexcise.oinv_price = objs[0].po_price;
                                $scope.outgoing_invoice_nonexcise.ordered_qty = objs[0].po_qty;
                                //$scope.outgoing_invoice_nonexcise.balance_qty = objs[0].po_balance_qty;
                                $scope.outgoing_invoice_nonexcise.discount = objs[0].po_discount;
                                $scope.outgoing_invoice_nonexcise.saletaxID = objs[0].po_saleTax;
                                $scope.outgoing_invoice_nonexcise.po_saleTax = objs[0].po_saleTax;
                                $scope.outgoing_invoice_nonexcise.bpod_Id = objs[0].bpod_Id;
                                var m = 0, liq = 0;
                                while (m < $scope.outgoing_invoice_nonexcise._items.length) {
                                  if ($scope.outgoing_invoice_nonexcise.oinv_codePartNo == $scope.outgoing_invoice_nonexcise._items[m]["_item_id"]) {
                                    liq = parseInt(liq) + parseInt($scope.outgoing_invoice_nonexcise._items[m]["issued_qty"]);
                                  }
                                   m++;
                                }
                                //alert(liq);
                                if (liq > 0) {
                                   $scope.outgoing_invoice_nonexcise.balance_qty = parseFloat(objs[0].po_balance_qty) - parseFloat(liq);
                                }
                                else {
                                   $scope.outgoing_invoice_nonexcise.balance_qty = parseFloat(objs[0].po_balance_qty);
                                }
                            });
                        }
                    });
                }
            }*/
//        if($("#potype").val() == "R")
//        {
//            jQuery.ajax({
//                url: URL,
//                type: "POST",
//                data: { TYP: "GET_RECPO_QTY", ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, DATE: $scope.outgoing_invoice_nonexcise.oinv_date, POID: $scope.outgoing_invoice_nonexcise.pono },
//                //cache: false,
//                success: function (jsondata) {
//                    var objs = jQuery.parseJSON(jsondata);
//                    $scope.$apply(function () {
//                        $scope.outgoing_invoice_nonexcise.ordered_qty = objs;
//                    });
//                }
//            });
//        }
            if (true) {
                var TYPE = "GET_SURCHARGE";
                if (true) {
                    //                jQuery.ajax({
                    //                    url: URL,
                    //                    type: "POST",
                    //                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_nonexcise.oinv_codePartNo, PRINCID: $scope.outgoing_invoice_nonexcise.principalID, POID: $scope.outgoing_invoice_nonexcise.pono },
                    //                    //cache: false,
                    //                    success: function (jsondata) {
                    //                        var objs = jQuery.parseJSON(jsondata);
                    //                        $scope.$apply(function () {
                    //                            $scope.outgoing_invoice_excise.Surcharge_percent = objs;
                    //                        });
                    //                    }
                    //                });
                }
            }
        }

    }]);

Outgoing_Invoice_NonExcise_App.directive('validNumber', function () {
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


Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.issued_qty', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.issued_qty = oldValue;
                }
            });
        }
    };
});

Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.total_discount', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.total_discount = oldValue;
                }
            });
        }
    };
});

Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.pf_chrg', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.pf_chrg = oldValue;
                }
            });
        }
    };
});

Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.total_saleTax', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.total_saleTax = oldValue;
                }
            });
        }
    };
});


Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.fright_percent', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.fright_percent = oldValue;
                }
            });
        }
    };
});

Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.freight_amount', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.freight_amount = oldValue;
                }
            });
        }
    };
});


Outgoing_Invoice_NonExcise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_nonexcise.incidental_chrg', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_nonexcise.incidental_chrg = oldValue;
                }
            });
        }
    };
});



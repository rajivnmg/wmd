var URL = "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php";
var BuyerList = {};
var a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
var b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
 
function inWords (num) {
    if ((num = num.toString()).length > 9) return 'overflow';
    n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
    if (!n) return; var str = '';
    str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'Crore ' : '';
    str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'Lakh ' : '';
    str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'Thousand ' : '';
    str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'Hundred ' : '';
    str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) : '';
    return str;
}

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

function ActionOnBuyer(value, data) {
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
//        jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php", type: "POST",
//            data: {
//                TYP: "POQUOTATION",
//                BUYERID: data
//            },
//            success: function (jsondata) {
//                //alert(jsondata);
//                $('#iquotNo').empty();
//                $("#iquotNo").append("<option value=''></option>");
//                var objs = jQuery.parseJSON(jsondata);
//                if (jsondata != "") {
//                    var obj; for (var i = 0; i < objs.length; i++) {
//                        var obj = objs[i];
//                        $("#iquotNo").append("<option value=\"" + obj.po_quotNo + "\">" + obj.po_quotNo + "</option>");
//                    }
//                    $("#iquotNo").val(0);
//                }
//            }
//        });
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

function ActionOnPurchaseOrder(value, data) { 
    if (value != "" && data > 0) {
        var PO_num = data;
        $("#PurchaseOrderid").val(data);
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: method,
            data: { TYP: "MA_FILL", PO_NUMBER: data,po_ed_applicability:'E' },
            success: function (jsondata) {
			//document.write(jsondata);
              // alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $("#buyerid").val(objs[0].bn);
                $("#autocomplete-ajax-buyer").val(objs[0].bn_name);

		$("#bemailId").val(objs[0].bemailId);
                $("#podate").val(objs[0].pod);
                 $("#bpoType").val(objs[0].pot);//######### added by aksoni   
                $("#autocomplete-ajax-PurchaseOrder").val(objs[0].pon);
                $("#freightTag").val(objs[0].frgt);//###### added by aksoni on 09/04/2015
                //                alert(objs[0].pf_chrg);
                //                alert(objs[0].inci_chrg);
                //                alert(objs[0].frgtp);
                //                alert(objs[0].frgta);
				//purchaseOrder.ms
				//marketsegment
				$("#marketsegment").val(objs[0].ms);
                if (objs[0].pf_chrg == null) {

                    $("#txtpf_charg").val(0);
                    $("#txtpf_charg_percent").val(0);
                }
                else {
					//add by gajendra
					$("#txtpf_charg").val(objs[0].pf_chrg);
					//end
                    $("#txtpf_charg_percent").val(objs[0].pf_chrg);
                }
                if (objs[0].inci_chrg == null) {

                    $("#txtincidental_chrg").val(0);
                    $("#txtincidental_chrg_percent").val(0);
                }
                else {
					//add by gajendra
					$("#txtincidental_chrg").val(objs[0].inci_chrg);
					//end
                    $("#txtincidental_chrg_percent").val(objs[0].inci_chrg);
                }
                if (objs[0].ins_charge == null) {
					$("#ins_charge").val(0);
                }
                else {
					//add by gajendra
					$("#ins_charge").val(objs[0].ins_charge);
					//end
                }
                if (objs[0].othc_charge == null) {
					$("#othc_charge").val(0);
                }
                else {
					//add by gajendra
					$("#othc_charge").val(objs[0].othc_charge);
					//end
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
            data: { TYP: "GET_PO_PRINCIPAL", PO_ID: PO_num,po_ed_applicability:'E'},
            success: function (jsondata) {//alert(jsondata);
            
                var objs = jQuery.parseJSON(jsondata);
                $('#principal_list').empty();
                $("#principal_list").append("<option value=\'0'\ title=\'select'>Select Principal</option>");
                for (var i = 0; i < objs.length; i++) {
                    $("#principal_list").append("<option value=\'" + objs[i].principalID + "'\ title=\'" + objs[i].Principal_Name + "\'>" + objs[i].Principal_Name + "</option>");
                }
            }
        });
    }

}
function NonePurchaseOrder() {
    $("#PurchaseOrderid").val(0);
}

// commented by codefire to page increase loading performance 25.11.15.
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
    jQuery.ajax({
    url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
    type: "post",
    data: { TYP: "GETPOLIST" },
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
}); */

// added by codefire to page increase loading performance 25.11.15.
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
var method = "POST";
var Outgoing_Invoice_Excise_App = angular.module('Outgoing_Invoice_Excise_App', []);

Outgoing_Invoice_Excise_App.controller('Outgoing_Invoice_Excise_Controller', ['$scope', '$http', function Outgoing_Invoice_Excise_Controller($scope, $http) {


    var sample_outgoing_invoice_excise = { _items: [{ bpod_Id: 0, buyer_item_code: '', oinv_codePartNo: '', _item_id: 0, codePartNo_desc: '', ordered_qty: '', balance_qty: '', iinv_no: '', stock_qty: '', issued_qty: '', oinv_price: '', tot_price: '', discount: 0, saleTax: 0, ed_percent: '', ed_perUnit: '', ed_amt: '', entryId: '', edu_percent: '', edu_amt: '', hedu_percent: 0, hedu_amount: 0, cvd_percent: '', cvd_amt: '',entryDId:0,mappingid:0}] };

    $scope.outgoing_invoice_excise = sample_outgoing_invoice_excise;

    $scope.addItem = function () {
		
        var issuedQty = parseInt($scope.outgoing_invoice_excise.issued_qty);
        if (issuedQty > 0) {
			$("#btnsave").hide();
        	//alert($("#itemid").val());
            $scope.outgoing_invoice_excise._item_id=$("#itemid").val();
            $scope.outgoing_invoice_excise.bpod_Id= $("#bpodid").val();
            $scope.outgoing_invoice_excise.oinv_codePartNo = $("#bpodid option:selected").text();
            $scope.outgoing_invoice_excise.iinv_no = $("#invoice_list option:selected").text();
            $scope.outgoing_invoice_excise.entryDId = $("#invoice_list").val();
            $scope.outgoing_invoice_excise.pf_chrg_percent = $("#txtpf_charg_percent").val();
            //$scope.outgoing_invoice_excise.pf_chrg = 0;
            $scope.outgoing_invoice_excise.incidental_chrg_percent = $("#txtincidental_chrg_percent").val();
            //$scope.outgoing_invoice_excise.incidental_chrg = 0;
            $scope.outgoing_invoice_excise.freight_percent = $("#txtfreight_percent").val();
            $scope.outgoing_invoice_excise.freight_amount = $("#txtfreight_amount").val();

            var check = 0;
            //~ var discount_flag = false;
            //~ while (check < $scope.outgoing_invoice_excise._items.length) {
                //~ if (parseFloat($scope.outgoing_invoice_excise.discount) == parseFloat($scope.outgoing_invoice_excise._items[check]["discount"])) {
                    //~ discount_flag = true;
                //~ }
                //~ else {
                    //~ discount_flag = false;
                //~ }
                //~ check++;
            //~ }
            //~ if (parseInt($scope.outgoing_invoice_excise._items.length) == 0) {
                //~ discount_flag = true;
            //~ }
			discount_flag = true;
            if (discount_flag) {
                $scope.outgoing_invoice_excise._items.push({ bpod_Id: $scope.outgoing_invoice_excise.bpod_Id, buyer_item_code: $scope.outgoing_invoice_excise.buyer_item_code, oinv_codePartNo: $scope.outgoing_invoice_excise.oinv_codePartNo, _item_id: $scope.outgoing_invoice_excise._item_id, codePartNo_desc: $scope.outgoing_invoice_excise.codePartNo_desc, ordered_qty: $scope.outgoing_invoice_excise.ordered_qty,balance_qty: $scope.outgoing_invoice_excise.balance_qty,iinv_no: $scope.outgoing_invoice_excise.iinv_no, stock_qty: $scope.outgoing_invoice_excise.stock_qty, issued_qty: $scope.outgoing_invoice_excise.issued_qty, oinv_price: $scope.outgoing_invoice_excise.oinv_price, item_discount: $scope.outgoing_invoice_excise.item_discount, item_taxable_total: $scope.outgoing_invoice_excise.item_taxable_total, hsn_code: $scope.outgoing_invoice_excise.hsn_code, cgst_rate: $scope.outgoing_invoice_excise.cgst_rate,sgst_rate: $scope.outgoing_invoice_excise.sgst_rate,igst_rate: $scope.outgoing_invoice_excise.igst_rate, cgst_amt: $scope.outgoing_invoice_excise.cgst_amt, sgst_amt: $scope.outgoing_invoice_excise.sgst_amt, igst_amt: $scope.outgoing_invoice_excise.igst_amt, tot_price: $scope.outgoing_invoice_excise.tot_price, discount: $scope.outgoing_invoice_excise.discount, saleTax: $scope.outgoing_invoice_excise.saleTax, ed_percent: $scope.outgoing_invoice_excise.ed_percent, ed_perUnit: $scope.outgoing_invoice_excise.ed_perUnit, ed_amt: $scope.outgoing_invoice_excise.ed_amt, entryId: $scope.outgoing_invoice_excise.entryId, edu_percent: $scope.outgoing_invoice_excise.edu_percent, edu_amt: $scope.outgoing_invoice_excise.edu_amt, hedu_percent: $scope.outgoing_invoice_excise.hedu_percent, hedu_amount: $scope.outgoing_invoice_excise.hedu_amount, cvd_percent: $scope.outgoing_invoice_excise.cvd_percent, cvd_amt: $scope.outgoing_invoice_excise.cvd_amt,entryDId:$scope.outgoing_invoice_excise.entryDId,mappingid:$scope.outgoing_invoice_excise.mappingid });
                $scope.BillCalculation();
			
            }
            else {
                alert("This CodePart have different discount so we can not added this item in this invoice.");
            }
        }
        else {
            alert("amount and issued quantity never be blank.");
            return;
        }

        $scope.outgoing_invoice_excise.cgst_rate = "";
        $scope.outgoing_invoice_excise.discount = "";
        $scope.outgoing_invoice_excise.tot_price = "";
        $scope.outgoing_invoice_excise.igst_amt = "";
        $scope.outgoing_invoice_excise.sgst_amt = "";
        $scope.outgoing_invoice_excise.cgst_amt = "";
        $scope.outgoing_invoice_excise.igst_rate = "";
        $scope.outgoing_invoice_excise.sgst_rate = "";
        $scope.outgoing_invoice_excise.item_taxable_total = "";
        $scope.outgoing_invoice_excise.hsn_code = "";
        $scope.outgoing_invoice_excise.bpod_Id = "";
        $scope.outgoing_invoice_excise.buyer_item_code = "";
        $scope.outgoing_invoice_excise.oinv_codePartNo = "";
        $scope.outgoing_invoice_excise._item_id = "";
        $scope.outgoing_invoice_excise.codePartNo_desc = "";
        $scope.outgoing_invoice_excise.ordered_qty = "";
         $scope.outgoing_invoice_excise.balance_qty = "";
        $scope.outgoing_invoice_excise.iinv_no = "";
        $scope.outgoing_invoice_excise.stock_qty = "";
        $scope.outgoing_invoice_excise.issued_qty = "";
        $scope.outgoing_invoice_excise.item_discount = "";
        $scope.outgoing_invoice_excise.oinv_price = "";
        $scope.outgoing_invoice_excise.tot_price = "";
        $scope.outgoing_invoice_excise.discount = "";
        //$scope.outgoing_invoice_excise.saleTax = "";
        $scope.outgoing_invoice_excise.ed_percent = "";
        $scope.outgoing_invoice_excise.ed_perUnit = "";
        $scope.outgoing_invoice_excise.ed_amt = "";
        $scope.outgoing_invoice_excise.entryId = "";
        $scope.outgoing_invoice_excise.edu_percent = "";
        $scope.outgoing_invoice_excise.edu_amt = "";
        $scope.outgoing_invoice_excise.hedu_percent = "";
        $scope.outgoing_invoice_excise.hedu_amount = "";
        $scope.outgoing_invoice_excise.cvd_percent = "";
        $scope.outgoing_invoice_excise.cvd_amt = "";
        $scope.outgoing_invoice_excise.entryDId = "";
        $scope.outgoing_invoice_excise.mappingid = "";
        
        $("#btnsave").show();
    }

    $scope.BillCalculation = function () {
		var PFChargeAmt =0;
        var IncidentalChargeAmt =0;
        var InsuranceChargeAmt =0;
        var OtherChargeAmt =0;
        var FreightChargeAmt =0;
        var pf_chrg = 0.00, incidental_chrg = 0.00, ins_charge = 0.00, othc_charge = 0.00, freight_amount = 0.00;
        var k = 0;
        $scope.outgoing_invoice_excise.bill_value = 0; 
        var totalTaxableValue = 0;
        while (k < $scope.outgoing_invoice_excise._items.length) {
            // calculate total ED amount
           // ed_amt = parseFloat($scope.outgoing_invoice_excise._items[k]["ed_amt"]) + ed_amt;
            //Discount_percent = parseFloat($scope.outgoing_invoice_excise._items[k]["discount"]);
            //edu_amt = parseFloat(edu_amt) + parseFloat($scope.outgoing_invoice_excise._items[k]["edu_amt"]);
           // hedu_amt = hedu_amt + parseFloat($scope.outgoing_invoice_excise._items[k]["hedu_amount"]);
          //  cvd_amt = cvd_amt + parseFloat($scope.outgoing_invoice_excise._items[k]["cvd_amt"]);
            $scope.outgoing_invoice_excise.bill_value = parseFloat($scope.outgoing_invoice_excise.bill_value) + parseFloat($scope.outgoing_invoice_excise._items[k]["tot_price"]);
            totalTaxableValue = parseFloat(totalTaxableValue) + parseFloat($scope.outgoing_invoice_excise._items[k]["item_taxable_total"]);
			totalTaxableValue = parseFloat(totalTaxableValue).toFixed(2);
            k++
        }
        var PayAmount = 0;
        //~ if(!isNaN($scope.outgoing_invoice_excise.freight_amount) && $scope.outgoing_invoice_excise.freight_amount != "" && $scope.outgoing_invoice_excise.freight_amount != null)
        //~ {
			//~ freight_amount = $scope.outgoing_invoice_excise.freight_amount;
        //~ }
        $scope.outgoing_invoice_excise.pf_chrg = $("#txtpf_charg").val(); 
        if(!isNaN($scope.outgoing_invoice_excise.pf_chrg) && $scope.outgoing_invoice_excise.pf_chrg != ""  && $scope.outgoing_invoice_excise.pf_chrg != null){
			pf_chrg = $scope.outgoing_invoice_excise.pf_chrg;
        }
        PFChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(pf_chrg))/100);
        PFChargeAmt = parseFloat(PFChargeAmt).toFixed(2);
        $scope.outgoing_invoice_excise.incidental_chrg = $("#txtincidental_chrg").val(); 
        if(!isNaN($scope.outgoing_invoice_excise.incidental_chrg) && $scope.outgoing_invoice_excise.incidental_chrg != ""  && $scope.outgoing_invoice_excise.incidental_chrg != null){
			incidental_chrg = $scope.outgoing_invoice_excise.incidental_chrg;
		}
		IncidentalChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(incidental_chrg))/100);
        IncidentalChargeAmt = parseFloat(IncidentalChargeAmt).toFixed(2);
        
        $scope.outgoing_invoice_excise.ins_charge = $("#ins_charge").val(); 
		if(!isNaN($scope.outgoing_invoice_excise.ins_charge) && $scope.outgoing_invoice_excise.ins_charge != "" && $scope.outgoing_invoice_excise.ins_charge !=null){
			ins_charge = $scope.outgoing_invoice_excise.ins_charge;
		}
		InsuranceChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(ins_charge))/100);
		InsuranceChargeAmt = parseFloat(InsuranceChargeAmt).toFixed(2);
		
		$scope.outgoing_invoice_excise.othc_charge = $("#othc_charge").val(); 
		if(!isNaN($scope.outgoing_invoice_excise.othc_charge) && $scope.outgoing_invoice_excise.othc_charge != "" && $scope.outgoing_invoice_excise.othc_charge !=null){
			othc_charge = $scope.outgoing_invoice_excise.othc_charge;
		}
		OtherChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(othc_charge))/100);
        OtherChargeAmt = parseFloat(OtherChargeAmt).toFixed(2);
        $scope.outgoing_invoice_excise.frgt = $("#freightTag").val(); 
        if ($scope.outgoing_invoice_excise.frgt == "P") {
			$scope.outgoing_invoice_excise.freight_percent = $("#txtfreight_percent").val(); 
            if (!isNaN($scope.outgoing_invoice_excise.freight_percent) && $scope.outgoing_invoice_excise.freight_percent != "" && $scope.outgoing_invoice_excise.freight_percent != null) {
            freight_amount = parseFloat($scope.outgoing_invoice_excise.freight_percent);
			}
			FreightChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(freight_amount))/100);
			FreightChargeAmt = parseFloat(FreightChargeAmt).toFixed(2);
        } else if ($scope.outgoing_invoice_excise.frgt == "A") {
			$scope.outgoing_invoice_excise.freight_amount = $("#txtfreight_amount").val(); 
            FreightChargeAmt = parseFloat($scope.outgoing_invoice_excise.freight_amount).toFixed(2);
        } else {
          FreightChargeAmt = 0;
        }
        $scope.outgoing_invoice_excise.bill_value = parseFloat($scope.outgoing_invoice_excise.bill_value) + parseFloat(PFChargeAmt) + parseFloat(IncidentalChargeAmt) + parseFloat(InsuranceChargeAmt) + parseFloat(OtherChargeAmt) + parseFloat(FreightChargeAmt);
        // calculate discount amount for all row


        //Discount_Amount = (parseFloat(basic_amount) * parseFloat(Discount_percent)) / 100;
        //$scope.outgoing_invoice_excise.total_discount = Discount_Amount.toFixed(2);
        //$scope.outgoing_invoice_excise.total_ed = ed_amt.toFixed(2);
        //~ var ExciseAmount = 0.00;
        //~ if(isNaN(cvd_amt) || cvd_amt=="" || cvd_amt==null){        
           //~ cvd_amt=0.00;
        //~ }
        //~ if($scope.outgoing_invoice_excise.inclusive_ed_tag == "I" || $scope.outgoing_invoice_excise.inclusive_ed_tag == true)
        //~ {
            //~ ExciseAmount = basic_amount - Discount_Amount;
        //~ }
        //~ else
        //~ {
            //~ ExciseAmount = basic_amount - Discount_Amount + ed_amt + edu_amt + hedu_amt + cvd_amt;
        //~ }
       
        //~ if($scope.outgoing_invoice_excise.pf_chrg_percent== "" ||$scope.outgoing_invoice_excise.pf_chrg_percent==null)
        //~ {
            //~ $scope.outgoing_invoice_excise.pf_chrg_percent = 0.00;
        //~ }
        //~ if($scope.outgoing_invoice_excise.incidental_chrg_percent == "" ||$scope.outgoing_invoice_excise.incidental_chrg_percent==null)
        //~ {
            //~ $scope.outgoing_invoice_excise.incidental_chrg_percent = 0.00;
        //~ }
        //alert(isNaN($scope.outgoing_invoice_excise.pf_chrg_percent));
        //~ if(isNaN($scope.outgoing_invoice_excise.pf_chrg_percent)){
        //~ $scope.outgoing_invoice_excise.pf_chrg_percent=0.00;
        //~ 
        //~ }
        
        //alert("pf_charge_percent : " + $scope.outgoing_invoice_excise.pf_chrg_percent);
        //var pf_charge_amount = (parseFloat($scope.outgoing_invoice_excise.pf_chrg_percent) * ExciseAmount) / 100;
        //~ if(!isNaN($scope.outgoing_invoice_excise.incidental_chrg_percent) && $scope.outgoing_invoice_excise.incidental_chrg_percent != ""  && $scope.outgoing_invoice_excise.incidental_chrg_percent != null)
        //~ {
          //~ //sale_tax = parseFloat($scope.outgoing_invoice_excise.incidental_chrg_percent);
        //~ }else{
            //~ $scope.outgoing_invoice_excise.incidental_chrg_percent = 0.00;
        //~ }        
        
        
        //alert("pf_charge_amount : " + pf_charge_amount);
        //~ var incidental_amount = ((ExciseAmount + pf_charge_amount) * parseFloat($scope.outgoing_invoice_excise.incidental_chrg_percent)) / 100;
       
        //alert("incidental_amount : " + incidental_amount);
        // Taxable amount = basic amount + ed amount + edu amount - discount + pf_charge_amount
        
        
        
         
//alert("pf_charge_amount : " + pf_charge_amount);
//alert("incidental_amount : " + incidental_amount);
//alert("ExciseAmount : " + ExciseAmount);         
       
        //var TaxableAmount = ExciseAmount + parseFloat(pf_charge_amount) + parseFloat(incidental_amount);
        //var TaxableAmount =parseFloat(ExciseAmount) + parseFloat($scope.outgoing_invoice_excise.pf_chrg) + parseFloat($scope.outgoing_invoice_excise.incidental_chrg);
       //alert("TaxableAmount : " + TaxableAmount);
        //var d = 0;
        //var SaletaxPurcent = 0.00, SurchargePercent = 0.00;
        //var TaxAmount = 0.00, SurchargeAmount = 0.00;
        //var taxid = parseInt($scope.outgoing_invoice_excise.saleTaxID);
        //~ jQuery.ajax({
            //~ url: URL,
            //~ type: "POST",
            //~ data: { TYP: "GETTAXDETAILS", TAXID: taxid },
            //~ success: function (jsondata) {
                //~ var objs = jQuery.parseJSON(jsondata);
                //~ 
                //~ if (objs[0].SALESTAX_CHRG != null) {
                    //~ SaletaxPurcent = objs[0].SALESTAX_CHRG;
                //~ }
                //~ else {
                    //~ SaletaxPurcent = 0;
                //~ }
                //~ if (objs[0].SURCHARGE != null) {
                    //~ SurchargePercent = objs[0].SURCHARGE;
                //~ }
                //~ else {
                    //~ SurchargePercent = 0;
                //~ }
                //~ TaxAmount = TaxableAmount * SaletaxPurcent;
                //~ TaxAmount = TaxAmount / 100;
                //~ SurchargeAmount = SurchargePercent * TaxAmount;
                //~ SurchargeAmount = parseFloat(SurchargeAmount) / 100;
                //~ $scope.$apply(function () {
                	//~ 
                    //~ $scope.outgoing_invoice_excise.total_saleTax = (parseFloat(TaxAmount) + parseFloat(SurchargeAmount)).toFixed(2);
                    //~ var F_amt = 0.00;
                    //~ if (F_tag=='P') {//#### condition modify by aksoni 09/04/2015
                        //~ F_amt = ((parseFloat(TaxableAmount)) / 100) * parseFloat($("#txtfreight_percent").val());
                        //~ $scope.outgoing_invoice_excise.freight_amount = F_amt.toFixed(2);
                    //~ } else if (F_tag=='A') {//#### condition modify by aksoni 09/04/2015
                        //~ F_amt = parseFloat($("#txtfreight_amount").val());
                        //~ $scope.outgoing_invoice_excise.freight_amount = F_amt.toFixed(2);
                        //~ F_percent=(parseFloat(F_amt) * 100) /(parseFloat(TaxableAmount));
                        //~ $scope.outgoing_invoice_excise.freight_percent=F_percent.toFixed(2);
                        //~ 
                    //~ }
//~ 
                    //~ // Pay Amount = Taxable amount + sale Tax + freight_amount
                    //~ var PayAmount = parseFloat(TaxableAmount.toFixed(2)) + parseFloat($scope.outgoing_invoice_excise.total_saleTax) + parseFloat(F_amt.toFixed(2));
//~ 
                    //~ // Bill val = PayAmount + incidental_amount
                    //~ $scope.outgoing_invoice_excise.bill_value = parseFloat(PayAmount.toFixed(2)); // +parseFloat(incidental_amount);
                //~ });
//~ 
            //~ }
        //~ });
			
    }


    $scope.removeItem = function (item) {
        $scope.outgoing_invoice_excise._items.splice($scope.outgoing_invoice_excise._items.indexOf(item), 1);

        $scope.BillCalculation();
    }
	
    $scope.BuyerDetaile = [{}];
    $scope.PrincipalDetaile = [{}];
    $scope.BillDetaile = [{}];
    $scope.TaxDetaile = [{}];
    $scope.PODetaile = [{}];
    var asCallState = new Array();
    
   String.prototype.splice = function( idx, rem, s ) {
			return (this.slice(0,idx) + s + this.slice(idx + Math.abs(rem)));
	};

    $scope.AddRow = function (i, objspo, IncomingData, data) {
        var buyer_itemCode = data[0]._itmes[i].buyer_item_code;
        if(buyer_itemCode.length > 10)
        {
            var result = buyer_itemCode.splice( 8, 0, '\\' );
        }
        $scope.PODetaile._items2.push({ sn: i + 1,cpart: '(' +data[0]._itmes[i].oinv_codePartNo + ')', codepart: data[0]._itmes[i].buyer_item_code.splice( 10, 0, '\r\n' ), desc: data[0]._itmes[i].codePartNo_desc, idmark: data[0]._itmes[i].Item_Identification_Mark, tarrif: IncomingData[0]._itemID_terrif_heading, qty: parseFloat(data[0]._itmes[i].issued_qty), rate: data[0]._itmes[i].oinv_price.splice( 6, 0, '\r\n' ), amount: data[0]._itmes[i].tot_price, amt_ed: data[0]._itmes[i].ed_amt, edu_cess: (parseFloat(data[0]._itmes[i].edu_amt) + parseFloat(data[0]._itmes[i].hedu_amount)), rate_ed: parseFloat(data[0]._itmes[i].ed_percent), duty_per_unit: data[0]._itmes[i].ed_perUnit.splice( 5, 0, '\r\n' ), entry_in23d: data[0]._itmes[i].mappingid, invno: IncomingData[0].iinvno.splice( 10, 0, '\r\n' ), date: IncomingData[0].iinvdate, princqty: IncomingData[0]._principal_qty, ass_val: IncomingData[0]._total_ass_value.splice( 9, 0, '\r\n' ), amt_edu: parseFloat(IncomingData[0]._ed_amount) ,amt_cess : (parseFloat(IncomingData[0]._edu_amt) + parseFloat(IncomingData[0]._hedu_amount)).toFixed(2), cvd_amt: data[0]._itmes[i].cvd_amt,unitname:IncomingData[0]._unit_name });
        $scope.BillDetaile.ed_amt = parseFloat($scope.PODetaile._items2[i]["amt_ed"]) + parseFloat($scope.BillDetaile.ed_amt);
        $scope.BillDetaile.edu_amt = (parseFloat($scope.BillDetaile.edu_amt) + parseFloat(data[0]._itmes[i].edu_amt)); //$scope.PODetaile._items2[i]["edu_cess"]);
        //$scope.BillDetaile.edu_amt = ($scope.BillDetaile.edu_amt).toFixed(2);
        $scope.BillDetaile.hedu_amt = parseFloat($scope.BillDetaile.hedu_amt) + parseFloat(data[0]._itmes[i].hedu_amount);
        $scope.BillDetaile.cvd_amt = parseFloat($scope.BillDetaile.cvd_amt) + parseFloat($scope.PODetaile._items2[i]["cvd_amt"]);
        $scope.BillDetaile.basic_amount = parseFloat($scope.BillDetaile.basic_amount) + parseFloat($scope.PODetaile._items2[i]["amount"]);
    };
    $scope.GetIncomingInvoiceData = function (m, objspo, data) {

        var responseIncomingInvoice = $http.get("../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php?TYP=IncomingDetailsForPrint&INVOICENO=" + data[0]._itmes[m].entryDId + "&CODEPART=" + data[0]._itmes[m]._item_id);
        
        responseIncomingInvoice.success(function (IncomingData, status, headers, config) {
            //$scope.asCallState.push({});
            
            var obj = new Object();
            obj.objspo = objspo;
            obj.IncomingData = IncomingData;
            obj.data = data;
            obj.mystate = m;
            asCallState[m] = obj;
            //$scope.AddRow(i, objspo, IncomingData, data);
            
            
            var size = 0, key;
            for (key in asCallState) {
                if (asCallState.hasOwnProperty(key)) size++;
            }
            
            if (size == data[0]._itmes.length) {
                for(var j = 0; j < asCallState.length ; j++)
                {
                    //console.log(j);
                    //if(asCallState[j] == null)
                    //{
                    //    alert("bug");
                    //    if(m == 1)
                    //    {
                    //        alert("sumit");
                    //    }
                    //}   
                    $scope.AddRow(j, asCallState[j].objspo, asCallState[j].IncomingData, asCallState[j].data);
                }
                $scope.CalculateTax(asCallState[asCallState.length-1].objspo, asCallState[asCallState.length-1].data);
                
                while(asCallState.length > 0) {
                asCallState.pop();
                }
            }

        });
    }
    
    
    $scope.Ctime = function (time) {
        var Atime = time.split(':');
        var hr = 0;
        var spell = "";
        if(Atime[0] > 12)
        {
            hr = parseInt(Atime[0]) - 12;
            spell = "PM";
        }
        else
        {
            hr = Atime[0];
            if(Atime[0] == 12)
            {
                spell = "PM";
            }
            else
            {
                spell = "AM";
            }
        }
        
        return hr+":"+Atime[1]+" "+spell;
    }
    var print_itme_list = [{ sn: 0,cpart:'', codepart: '', desc: '', idmark: 0, tarrif: '', qty: '', rate: '', amount: '', amt_ed: '', edu_cess: '', rate_ed: 0, duty_per_unit: 0, entry_in23d: '', invno: '', date: '', princqty: '', ass_val: '', amt_edu: '',amt_cess: '', cvd_amt: 0, unitname:''}];
    $scope.print = function (number) {
        var BuyerId = 0, PrincipalId = 0, PoID = 0;
        var responsePromise = $http.get(URL + "?TYP=SELECT&OutgoingInvoiceExciseNum=" + number);
        responsePromise.success(function (data, status, headers, config) {
            $scope.outgoing_invoice_excise = data[0];
            $scope.outgoing_invoice_excise.oinv_time = $scope.Ctime(data[0].oinv_time);
            $scope.outgoing_invoice_excise.dispatch_time = $scope.Ctime(data[0].dispatch_time);
            var totalamt = $scope.outgoing_invoice_excise.bill_value;
            var round_off_value = parseFloat(totalamt.toString().split(".")[1]);
            if(round_off_value >= 50)
            {
                $scope.outgoing_invoice_excise.round_off_value = "+ 0."+(100 - parseInt(round_off_value));
                $scope.outgoing_invoice_excise.final_pay_value = parseInt($scope.outgoing_invoice_excise.bill_value) + 1;
                $scope.outgoing_invoice_excise.final_pay_inwords = inWords(parseInt($scope.outgoing_invoice_excise.final_pay_value));
            }
            else
            {
                $scope.outgoing_invoice_excise.round_off_value = "- 0."+(parseInt(round_off_value));
                $scope.outgoing_invoice_excise.final_pay_value = parseInt($scope.outgoing_invoice_excise.bill_value);
                $scope.outgoing_invoice_excise.final_pay_inwords = inWords(parseInt($scope.outgoing_invoice_excise.final_pay_value));
            }
            
            PoID = data[0].pono;
            var ed_tag= data[0].inclusive_ed_tag;
            PrincipalId = data[0].principalID;
            BuyerId = data[0].BuyerID;
            if (data[0].Supplier_stage == "1") {
                $scope.outgoing_invoice_excise.Supplier_stage = "1ST STAGE DEALER";
            }
            else if (data[0].Supplier_stage == "2") {
                $scope.outgoing_invoice_excise.Supplier_stage = "2ND STAGE DEALER";
            }
            else if (data[0].Supplier_stage == "F") {
                $scope.outgoing_invoice_excise.Supplier_stage = "Free Sample";
            }
            var responsePO = $http.get("../../Controller/Business_Action_Controller/po_Controller.php?TYP=MA_FILL&PO_NUMBER=" + PoID+"&po_ed_applicability="+ed_tag);
            responsePO.success(function (objspo, status, headers, config) {
                $scope.PODetaile = objspo[0];
                $scope.PODetaile._items2 = print_itme_list;
              
                $scope.PODetaile._items2.splice($scope.PODetaile._items2.indexOf(0), 1);
                $scope.BillDetaile.ed_percent = parseInt(data[0]._itmes[0].ed_percent);
                $scope.BillDetaile.ed_percentinword = inWords(parseInt(data[0]._itmes[0].ed_percent));
                $scope.BillDetaile.edu_percent = parseInt(data[0]._itmes[0].edu_percent);
                $scope.BillDetaile.hedu_percent = parseInt(data[0]._itmes[0].hedu_percent);
                $scope.BillDetaile.cvd_percent = parseInt(data[0]._itmes[0].cvd_percent);
                $scope.BillDetaile.discount_amt = data[0].discount;
                $scope.BillDetaile.discount_percent = parseFloat(objspo[0]._items[0].po_discount);
                if(parseFloat(data[0].discount) <= 0 || data[0].discount == null)
                {
                    $("#discountblog1").hide();
                    $("#discountblog2").hide();
                }
                
                $scope.BillDetaile.pf_amt = data[0].pf_chrg;
                $scope.BillDetaile.pf_percent = parseFloat(objspo[0].pf_chrg);
                if(parseFloat(data[0].pf_chrg) <= 0 || data[0].pf_chrg == null)
                {
                    $("#pfblog").hide();
                    $("#pfblog2").hide();
                    //$("#billdetailsrow").attr("rowspan","5");
                }
                
                $scope.BillDetaile.inci_amt = data[0].incidental_chrg;
                $scope.BillDetaile.inci_percent = parseFloat(objspo[0].inci_chrg);
                if(parseFloat(data[0].incidental_chrg) <= 0 || data[0].incidental_chrg == null)
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
               
                if(parseFloat(data[0].freight_amount) <= 0 || data[0].freight_amount == null)
                {
                    $("#ferightblog").hide();
                    $("#ferightblog2").hide();
                    //$("#billdetailsrow").attr("rowspan","5");
                }
                $scope.BillDetaile.ed_amt = 0.00, $scope.BillDetaile.edu_amt = 0.00, $scope.BillDetaile.hedu_amt = 0.00, $scope.BillDetaile.basic_amount = 0.00, $scope.BillDetaile.cvd_amt = 0.00, $scope.BillDetaile.TaxableAmount = 0.00;
                for (var i = 0; i < data[0]._itmes.length; i++) {
                    $scope.GetIncomingInvoiceData(i, objspo, data);
                }
            });
            var responsePrincipal = $http.get("../../Controller/Master_Controller/Principal_Controller.php?TYP=SELECT&PRINCIPALID=" + PrincipalId);
            responsePrincipal.success(function (data2, status, headers, config) {
                $scope.PrincipalDetaile = data2[0];
            });
            var responseBuyer = $http.get("../../Controller/Master_Controller/Buyer_Controller.php?TYP=SELECT&BUYERID=" + BuyerId);
            responseBuyer.success(function (data3, status, headers, config) {
                $scope.BuyerDetaile = data3[0];
            });
        });
    };

    $scope.CalculateTax = function (objspo, data) {
        if(data[0].inclusive_ed_tag == "I")
        {
            $("#excisebill").hide();
            $("#edubill").hide();
            $scope.BillDetaile.TaxableAmount = parseFloat($scope.BillDetaile.basic_amount) - parseFloat($scope.BillDetaile.discount_amt) + parseFloat(data[0].pf_chrg) + parseFloat(data[0].incidental_chrg);
        }
        else if(data[0].inclusive_ed_tag == "E")
        {
            $scope.BillDetaile.TaxableAmount = parseFloat($scope.BillDetaile.basic_amount) - parseFloat($scope.BillDetaile.discount_amt) + parseFloat($scope.BillDetaile.ed_amt) + parseFloat($scope.BillDetaile.edu_amt)+ parseFloat($scope.BillDetaile.hedu_amt) + parseFloat($scope.BillDetaile.cvd_amt) + parseFloat(data[0].pf_chrg) + parseFloat(data[0].incidental_chrg);
        }
        if(parseFloat($scope.BillDetaile.cvd_amt) <= 0)
        {
            $("#cvdblog").hide();
            $("#cvdbill").hide();
        }
        $scope.BillDetaile.TaxableAmount = ($scope.BillDetaile.TaxableAmount).toFixed(2);
        var before_flot = parseFloat($scope.BillDetaile.ed_amt).toString().split(".")[0];
        var after_flot = parseFloat($scope.BillDetaile.ed_amt).toString().split(".")[1];
        if(parseInt(after_flot) > 0)
        {
            $scope.BillDetaile.exice_amt_inword = inWords(parseInt(before_flot)) + " and Paise " + inWords(parseInt(after_flot));
        }
        else
        {
            $scope.BillDetaile.exice_amt_inword = inWords(parseInt(before_flot));
        }
        var responseTax = $http.get(URL + "?TYP=GETTAXDETAILS&TAXID=" + objspo[0]._items[0].po_saleTax);
        responseTax.success(function (datatax, status, headers, config) {
            $scope.TaxDetaile = datatax[0];
            $scope.BillDetaile.SaleTaxAmount = (parseFloat($scope.BillDetaile.TaxableAmount) * parseFloat(datatax[0].SALESTAX_CHRG)) / 100;
            $scope.BillDetaile.SurchargeAmount = (parseFloat($scope.BillDetaile.SaleTaxAmount) * parseFloat(datatax[0].SURCHARGE)) / 100;
            $scope.BillDetaile.SaleTaxAmount = ($scope.BillDetaile.SaleTaxAmount).toFixed(2);
            $scope.BillDetaile.SurchargeAmount = ($scope.BillDetaile.SurchargeAmount).toFixed(2);
            if($scope.BillDetaile.SaleTaxAmount<=0||  $scope.BillDetaile.SaleTaxAmount=="NaN")
            {
				  $scope.BillDetaile.SaleTaxAmount=0.00;
			}
            if(parseFloat($scope.BillDetaile.SurchargeAmount) <= 0 || $scope.BillDetaile.SurchargeAmount == "NaN")
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
                data: { TYP: "SELECT", OutgoingInvoiceExciseNum: number },
                success: function (jsondata) {
                  // alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        if (objs[0].Supplier_stage == "1") {
                            $('#checkbox').attr("checked", "checked");
                            $scope.outgoing_invoice_excise._Supplier_stage_1 = "1";
                        }
                        else if (objs[0].Supplier_stage == "2") {
                            $scope.outgoing_invoice_excise._Supplier_stage_2 = "2";
                            $('#checkbox2').attr("checked", "checked");
                        }
                        else if (objs[0].Supplier_stage == "F") {
                            $scope.outgoing_invoice_excise._Supplier_stage_F = "F";
                            $('#checkbox3').attr("checked", "checked");
                        }
                        if (objs[0].inclusive_ed_tag == "I") {
                            $('#checkbox4').attr("checked", "checked");
                            $('#checkbox4').val(objs[0].inclusive_ed_tag);
                         }
                         else
                         {
						 	 $('#checkbox4').val(objs[0].inclusive_ed_tag);
						 }
                       
                      //  alert( $('#checkbox4').val());
                        $scope.outgoing_invoice_excise = objs[0];
                        $scope.outgoing_invoice_excise._items = objs[0]._itmes;
                        //$scope.outgoing_invoice_excise._old_items = objs[0]._itmes;
                        $scope.outgoing_invoice_excise.poid = objs[0].pono;
                        //ActionOnPurchaseOrder("y", objs[0].pono);
                        $("#PurchaseOrderid").val(objs[0].pono);
                        jQuery.ajax({
                            url: "../../Controller/Business_Action_Controller/po_Controller.php",
                            type: method,
                            data: { TYP: "MA_FILL", PO_NUMBER: objs[0].pono,po_ed_applicability:'E' },
                            success: function (jsondata1) {
                                var objs11 = jQuery.parseJSON(jsondata1);
                                $("#buyerid").val(objs11[0].bn);
                                $("#bpoType").val(objs11[0].pot);
                                $("#bemailId").val( objs11[0].bemailId);
                            

                                $("#freightTag").val(objs11[0].frgt);//###### added by aksoni on 09/04/2015
                                
                                    if($("#freightTag").val()=='P')
                                    {
										$("#txtfreight_percent").val(objs[0].freight_percent);
									}
									else if($("#freightTag").val()=='A'){
										 $("#txtfreight_amount").val(objs[0].freight_amount);
									}
                                  $scope.getprincipal();
                                $("#autocomplete-ajax-buyer").val(objs11[0].bn_name);
                                $("#podate").val(objs11[0].pod);
                                $("#autocomplete-ajax-PurchaseOrder").val(objs11[0].pon);
                                $scope.outgoing_invoice_excise.pf_chrg_percent = objs11[0].pf_chrg;
                                $scope.outgoing_invoice_excise.incidental_chrg_percent = objs11[0].inci_chrg;
                                $scope.outgoing_invoice_excise.saleTax = objs11[0]._items[0].po_saleTax;

                            }
                        });
                        //alert(objs[0].pono);
                        jQuery.ajax({
                            url: URL,
                            type: method,
                            data: { TYP: "GET_PO_PRINCIPAL", PO_ID: objs[0].pono,po_ed_applicability:'E' },
                            success: function (jsondata2) {
                                var objs22 = jQuery.parseJSON(jsondata2);
                                $('#principal_list').empty();
                                $("#principal_list").append("<option value=\'0'\ title=\'select'>Select Principal</option>");
                                var obj;
                                for (var i = 0; i < objs22.length; i++) {
                                    //obj = objs22[i];
                                    $("#principal_list").append("<option value=\'" + objs22[i].principalID + "'\ title=\'" + objs22[i].Principal_Name + " selected\'>" + objs22[i].Principal_Name + "</option>");
                                    //$("#principal_list").append("<option value=\'" + obj.principalID + "'\ title=\'" + obj.Principal_Name + "\'>" + obj.Principal_Name + "</option>");
                                }
                                $("#principal_list").val(objs[0].principalID);
                            }
                        });
                        for (var i = 0; i < objs[0]._items.length; i++) {
                            $scope.outgoing_invoice_excise._items[i].oldinventory = objs[0]._itmes[i].issued_qty;
                            $scope.BindCurrentQuantity(objs[0]._itmes[i].entryId, objs[0]._itmes[i]._item_id, i);
                        }
                        $scope.outgoing_invoice_excise.total_discount = objs[0].discount;
                        $scope.outgoing_invoice_excise.total_saleTax = objs[0].saleTax;
                        //$scope.outgoing_invoice_excise.principalID = objs[0].principalID;
                    });
                }
            });
        }
        else {
            $("#btnprint").hide();
            $("#btnupdate").hide();
            $scope.outgoing_invoice_excise._items.splice($scope.outgoing_invoice_excise._items.indexOf(0), 1);
            var d = new Date();
            var year = d.getFullYear();
            var month = d.getMonth() + 1;
            if (month < 10) {
                month = "0" + month;
            };
            var day = d.getDate();
            var hour = d.getHours();
            var minut = d.getMinutes();
            if (minut < 10) {
                minut = "0" + minut;
            };
            $scope.outgoing_invoice_excise.oinv_date = day + "/" + month + "/" + year;
            $scope.outgoing_invoice_excise.oinv_time = hour + ":" + minut;
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $("#txt_outgoing_invoice_num").val(objs);
                    $scope.$apply(function () {
                        $scope.outgoing_invoice_excise.oinvoice_No = objs;
                    });
                }
            });
        }
    }
    $scope.ChangeRowOnUpdate = function (item) {
        var Rowindex = $scope.outgoing_invoice_excise._items.indexOf(item);
		
        var stockQty = parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['stock_qty']);
        var issuedQty = parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['issued_qty']);
        var orderQty = parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['ordered_qty']);
        if (issuedQty > orderQty || issuedQty > stockQty) {
            $scope.outgoing_invoice_excise._items[Rowindex]['issued_qty'] = 0;
            alert("issued quantity naver be gretar then stock quantity or order quantity.");
        }
	
        if (issuedQty >= 0) {
			
            $scope.outgoing_invoice_excise._items[Rowindex]['tot_price'] = parseFloat(issuedQty) * parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['oinv_price']);
            $scope.outgoing_invoice_excise._items[Rowindex]['ed_amt'] = parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['ed_perUnit']) * parseFloat(issuedQty);
            $scope.outgoing_invoice_excise._items[Rowindex]['edu_amt'] = (parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['ed_amt']) * parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['edu_percent'])) / 100;
            $scope.outgoing_invoice_excise._items[Rowindex]['hedu_amount'] = (parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['ed_amt']) * parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['hedu_percent'])) / 100;
            $scope.outgoing_invoice_excise._items[Rowindex]['cvd_amt'] = parseFloat($scope.outgoing_invoice_excise._items[Rowindex]['cvd_amt']) * parseFloat(issuedQty);
        }
        $scope.BillCalculation();
    }
    $scope.BindCurrentQuantity = function (InvoiceNo, Itemid, rowindex) {

        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "GET_ABV_OUANTITY", INVOICEID: InvoiceNo, ITEMID: Itemid, INVOICETYPE: "E" },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    $scope.outgoing_invoice_excise._items[rowindex]["stock_qty"] = objs;
                });
            }
        });
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "GET_INVOICE_DETAILS", INVOICEID: InvoiceNo, ITEMID: Itemid },
            success: function (jsondata) {
                //alert(jsondata);
                //var objs = jsondata;
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    if (objs[0]._cvd_percent == null) {
                        objs[0]._cvd_percent = 0;
                    }
                    if (objs[0]._cvd_amount == null) {
                        objs[0]._cvd_amount = 0;
                    }
                    var unit_qnty = 0.00;
                    if (parseFloat(objs[0]._cvd_percent) > 0 && objs[0]._cvd_percent != "") {
                        var InvoiceQuantity = 0;
                        if (objs[0]._principal_qty > 0) {
                            InvoiceQuantity = objs[0]._principal_qty;
                        }
                        else if (objs[0]._supplier_qty > 0) {
                            InvoiceQuantity = objs[0]._supplier_qty;
                        }
                        unit_qnty = parseFloat(objs[0]._cvd_amount) / parseInt(InvoiceQuantity);
                    }
                    $scope.outgoing_invoice_excise._items[rowindex].cvd_unit_amount = unit_qnty;
                });
            }
        });
        var TYPE = "LOADPODETAILS";
        if (true) {
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/po_Controller.php",
                type: "POST",
                data: { TYP: TYPE, ITEMID: Itemid, PRINID: $scope.outgoing_invoice_excise.principalID, POID: $("#PurchaseOrderid").val(), TAG: "E" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        //alert(objs[0].po_discount);
                        $scope.outgoing_invoice_excise._items[rowindex]["discount"] = objs[0].po_discount;
                        $scope.outgoing_invoice_excise.saleTaxID = objs[0].po_saleTax;
                        //$scope.outgoing_invoice_excise._items[rowindex]["saleTax"] = objs[0].po_saleTax;
                    });
                }
            });
        }
    }
    $scope.AddOutgoingInvoiceExcise = function () {
        if ($scope.outgoing_invoice_excise._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        $("#btnsave").hide();
        if ($scope.outgoing_invoice_excise.Supplier_stage1) {
            $scope.outgoing_invoice_excise.Supplier_stage = "1";
        }
        else if ($scope.outgoing_invoice_excise.Supplier_stage2) {
            $scope.outgoing_invoice_excise.Supplier_stage = "2";
        }
        else if ($scope.outgoing_invoice_excise.Supplier_stageF) {
            $scope.outgoing_invoice_excise.Supplier_stage = "F";
        }
        $scope.outgoing_invoice_excise.oinv_date = $("#date").val();
        $scope.outgoing_invoice_excise._dnt_supply = $("#dnt_supply").val();
        $scope.outgoing_invoice_excise.oinv_time = $("#time1").val();
        $scope.outgoing_invoice_excise.po_date = $("#podate").val();
        $scope.outgoing_invoice_excise.dispatch_time = $("#dispatchtime").val();
         $scope.outgoing_invoice_excise.pot=$("#bpoType").val();//######### added by aksoni 02/04/2015
        $scope.outgoing_invoice_excise.poid = $("#PurchaseOrderid").val();
        $scope.outgoing_invoice_excise.BuyerID = $("#buyerid").val();
		$scope.outgoing_invoice_excise.ms = $("#marketsegment").val();
		$scope.outgoing_invoice_excise.pono = $("#autocomplete-ajax-PurchaseOrder").val();
		$scope.outgoing_invoice_excise.bemailId =  $("#bemailId").val();
        if ($scope.outgoing_invoice_excise.inclusive_ed_tag) {
            $scope.outgoing_invoice_excise.ed_tag = "I";
        }
        else
        {
            $scope.outgoing_invoice_excise.ed_tag = "E";
        }
		if($scope.outgoing_invoice_excise.principalID != '' && ($scope.outgoing_invoice_excise._principal_gstin == '' && $scope.outgoing_invoice_excise._principal_gstin == undefined)) {
			alert("Principal Gstin can not blank");
            return;
		}
		if($scope.outgoing_invoice_excise.supplierID != '' && ($scope.outgoing_invoice_excise._supplier_gstin == '' && $scope.outgoing_invoice_excise._supplier_gstin == undefined)) {
			alert("Supplier Gstin can not blank");
            return;
		}
        var json_string = JSON.stringify($scope.outgoing_invoice_excise);
		//alert(json_string); return ;
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", OUTGOING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
                $scope.$apply(function () {
                   // alert(jsondata);
                   // alert("Successfully Saved.");
                   if(jsondata == "CREDIT_TIME_ERROR"){
						alert("Not Saved, PO is Blocked Due To Exceeded Credit Time Period"); 
						return ;
					}else if(jsondata == "ALREADY"){	
						
                    }else if(jsondata == "WRONG_CALULATION"){	
						alert("Not Saved, Wrong Calulation");
					}else{
						alert("Successfully Saved.");
                        $scope.outgoing_invoice_excise = null;
                        location.href = 'print_outgoingexcise.php?OutgoingInvoiceExciseNum='+jsondata;
                    }
                });
            }
        });
    }
    $scope.Update = function () {
    	  $scope.outgoing_invoice_excise.pot=$("#bpoType").val();//######### added by aksoni 02/04/2015
		  $scope.outgoing_invoice_excise.ms = $("#marketsegment").val();
		  $scope.outgoing_invoice_excise.pono = $("#autocomplete-ajax-PurchaseOrder").val();
    	 if($scope.outgoing_invoice_excise.inclusive_ed_tag=="I" || $scope.outgoing_invoice_excise.inclusive_ed_tag == true)
    	  {
		  	$scope.outgoing_invoice_excise.inclusive_ed_tag="I";
		  }
		  else
		  {
		  	$scope.outgoing_invoice_excise.inclusive_ed_tag="E";
		  } 
        var json_string = JSON.stringify($scope.outgoing_invoice_excise);
         $("#btnupdate").hide();
         $("#btnprint").hide();
        
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", OUTGOING_INVOICE_EXCISE_DATA: json_string },
            success: function (jsondata) {
                $scope.outgoing_invoice_excise = null;
                location.href = "view_Outgoing_Invoice_Excise.php";
            }
        });
    }
	$scope.getSupplierGSTN = function () {
		jQuery.ajax({
				url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
				type: "post",
				data: { TYP: "INCOMING_SUPPLIER_GST", SUPPLIERID: $scope.outgoing_invoice_excise.supplierID },
				success: function (jsondata) {
					//alert(jsondata);
					var objs = jQuery.parseJSON(jsondata);
					if (jsondata != "[]") 
					{
						$('#supplier_gstin').val(objs[0]._gstin);
					}else{
						$('#supplier_gstin').val('');
					}
				}
			});
	}
    $scope.getprincipal = function () {
        var POid = $("#PurchaseOrderid").val();
         var bpoType=$("#bpoType").val();// added by aksoni 02/04/2015
        //alert("PurchaseOrderid :-" + POid + "  PRINCIPALID :- " + $scope.outgoing_invoice_excise.principalID);
        //alert( bpoType);
        var passtag = "E";
        if ($scope.outgoing_invoice_excise.principalID > 0) {
            var TYPE = "GETPODID";
            if (true) {
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: { TYP: TYPE, PODID: POid, PRINCIPALID: $scope.outgoing_invoice_excise.principalID, TAG: passtag,BPOTYPE:bpoType },
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        $('#bpodid').empty();
                        $("#bpodid").append("<option value='0'>Select Item</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            //$scope.outgoing_invoice_excise.discount = objs[0].po_discount;
                            //$scope.outgoing_invoice_excise.saleTax = objs[0].po_saleTax;
                            //$scope.outgoing_invoice_excise.bpod_Id = objs[0].bpod_Id;
                            //$scope.outgoing_invoice_excise.buyer_item_code = objs[0].po_buyeritemcode;
                            //$scope.outgoing_invoice_excise.oinv_price = objs[0].po_price;
                            //$scope.outgoing_invoice_excise.ordered_qty = objs[0].po_qty;
                            if (jsondata != "") {
                                var obj;
                                for (var i = 0; i < objs.length; i++) {
                                    var obj = objs[i];
                                    $("#bpodid").append("<option value=\"" + obj.bpod_Id + "\">" + obj.po_codePartNo + "</option>");
                                }
                            }
                        });

                    }
                });
            }
            jQuery.ajax({
				url: "../../Controller/Business_Action_Controller/Outgoing_Invoice_Excise_Controller.php",
				type: "post",
				data: { TYP: "INCOMING_PRINCIPAL_GST", PRINCIPALID: $scope.outgoing_invoice_excise.principalID },
				success: function (jsondata) {
					//alert(jsondata);
					var objs = jQuery.parseJSON(jsondata);
					if (jsondata != "[]") 
					{
						//~ $('#principal_address').val(objs[0]._address);
						//~ $('#principal_city').val(objs[0]._city);
						//~ $('#principal_state').val(objs[0]._state);
						//~ $('#principal_gstin').val(objs[0]._gstin);
						$('#principal_gstin').val(objs[0]._gstin);
					}else{
						$('#principal_gstin').val('');
						//~ $('#principal_address').val('');
						//~ $('#principal_city').val('');
						//~ $('#principal_state').val('');
						//~ $('#principal_gstin').val('');
					}
				}
			});
        }
        
         $("#rowdata").empty();
        //alert(POid);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "GET_Recuring_List", POID: POid,PRINCIPALID: $scope.outgoing_invoice_excise.principalID,po_ed_applicability:'E'},
            success: function (jsondata) {
                //alert(jsondata);
                if (jsondata != '') {
                	 var objs=$();
                     objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        if (objs.length > 0) {
                            $("#help").show();
                            var index = 0;
                            //alert(jsondata);
                            $("#rowdata").empty();
                            while (index < objs.length) {
                                $("#rowdata").prepend("<tr><td>" + objs[index].cPartNo + "</td><td>" + objs[index].sch_rqty + "</td><td>" + objs[index].schDate + "</td><td>" + objs[index].sch_dqty + "</td></tr>");
                                index++;
                            }
                            
                        }
                        
                    });
                }
            }
        });
    }

    $scope.itemdesc = function () {
    	
     var bpoType=$("#bpoType").val();
     var passtag="E";
     if ($scope.outgoing_invoice_excise.bpod_Id > 0) {
     var TYPE = "LOADPOITEMDETAIL";
     if (true) {
      jQuery.ajax({
      url: "../../Controller/Business_Action_Controller/po_Controller.php",
      type: "POST",
      data: {TYP: TYPE, BPODID: $scope.outgoing_invoice_excise.bpod_Id,BPOTYPE:bpoType,TAG: passtag}, 
      success: function (jsondata) {
      var objs = jQuery.parseJSON(jsondata);
                           //alert(jsondata);
      $scope.$apply(function () {
      $scope.outgoing_invoice_excise.codePartNo_desc= objs[0].Item_Desc;
      $scope.outgoing_invoice_excise.item_discount= objs[0].po_discount;
      $scope.outgoing_invoice_excise.item_taxable_total= objs[0].taxable_amt;
      $scope.outgoing_invoice_excise.hsn_code= objs[0].po_hsn_code;
      $scope.outgoing_invoice_excise.cgst_rate= objs[0].po_cgst_rate;
      $scope.outgoing_invoice_excise.cgst_amt= objs[0].po_cgst_amt;
      $scope.outgoing_invoice_excise.sgst_rate= objs[0].po_sgst_rate;
      $scope.outgoing_invoice_excise.sgst_amt= objs[0].po_sgst_amt;
      $scope.outgoing_invoice_excise.igst_rate= objs[0].po_igst_rate;
      $scope.outgoing_invoice_excise.igst_amt= objs[0].po_igst_amt;
      $scope.outgoing_invoice_excise.tot_price= objs[0].total;
      $scope.outgoing_invoice_excise.oinv_codePartNo=objs[0].Item_Code_Partno;
      $scope.outgoing_invoice_excise._item_id= objs[0].po_codePartNo;
     // $scope.outgoing_invoice_excise.stock_qty= objs[0].stock_qty;
      $scope.outgoing_invoice_excise.buyer_item_code = objs[0].po_buyeritemcode;
      $scope.outgoing_invoice_excise.oinv_price = objs[0].po_price;
      $scope.outgoing_invoice_excise.ordered_qty = objs[0].po_qty;
      //~ $scope.outgoing_invoice_excise.discount = objs[0].po_discount;
      $scope.outgoing_invoice_excise.saleTaxID = objs[0].po_saleTax;
      $scope.outgoing_invoice_excise.saleTax = objs[0].po_saleTax;
      var m = 0, liq = 0;
      while (m < $scope.outgoing_invoice_excise._items.length) 
      {
        if (($scope.outgoing_invoice_excise._item_id==$scope.outgoing_invoice_excise._items[m]["_item_id"])&&($scope.outgoing_invoice_excise.bpod_Id==$scope.outgoing_invoice_excise._items[m]["bpod_Id"] )) {
        liq = parseInt(liq) + parseInt($scope.outgoing_invoice_excise._items[m]["issued_qty"]);
       
        
        }
        m++;
      }
      //alert(liq);
     if (liq > 0) {
      $scope.outgoing_invoice_excise.balance_qty = parseFloat(objs[0].po_qty) - parseFloat(liq);
     }
     else {
             $scope.outgoing_invoice_excise.balance_qty = parseFloat(objs[0].po_balance_qty);
     }
     if (objs[0].po_ed_applicability == "I") {
          $scope.outgoing_invoice_excise.inclusive_ed_tag = true;
     }
     else if (objs[0].po_ed_applicability == "E")
     {
        $scope.outgoing_invoice_excise.inclusive_ed_tag = false;
     }
     
      if ($scope.outgoing_invoice_excise._item_id > 0) {
            var TYPE = "GET_INVOICE_LIST";
            if (true) {
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_excise._item_id },
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            $('#invoice_list').empty();
                            $("#invoice_list").append("<option value='0'>Invoice List</option>");
                            var objs = jQuery.parseJSON(jsondata);
                            if (jsondata != "") {
                                var obj;
                                for (var i = 0; i < objs.length; i++) {
                                    var obj = objs[i];
                                    $("#invoice_list").append("<option value=\'" + obj._entryDId +"'\>" + obj.principal_inv_no +" - "+obj.ExciseQty+" "+"</option>");
                                }
                            }
                        });

                    }
                });
            }
        }
     
    });

   }
 });
 }
 }

    	  /*var bpoType=$("#bpoType").val();
        if ($scope.outgoing_invoice_excise.oinv_codePartNo > 0) {
            var TYPE = "LOADITEM";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Master_Controller/Item_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_excise.oinv_codePartNo },
                    //cache: false,
                    success: function (jsondata) {
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            //$scope.outgoing_invoice_excise._itemID_terrif_heading = objs[0]._item_tarrif_heading;
                            $scope.outgoing_invoice_excise.codePartNo_desc = objs[0]._item_descp;
                            //$scope.outgoing_invoice_excise._itemID_unitname = objs[0]._unitname;
                        });
                    }
                });
            }
        }*/
        /*if ($scope.outgoing_invoice_excise.oinv_codePartNo > 0) {
            var TYPE = "GET_INVOICE_LIST";
            if (true) {
                jQuery.ajax({
                    url: URL,
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_excise.oinv_codePartNo },
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            $('#invoice_list').empty();
                            $("#invoice_list").append("<option value='0'>Invoice List</option>");
                            var objs = jQuery.parseJSON(jsondata);
                            if (jsondata != "") {
                                var obj;
                                for (var i = 0; i < objs.length; i++) {
                                    var obj = objs[i];
                                    $("#invoice_list").append("<option value=\'" + obj._entryDId +"'\>" + obj.principal_inv_no +" - "+obj.ExciseQty+" "+"</option>");
                                }
                            }
                        });

                    }
                });
            }
        }*/
  /*      if (true) {
            var TYPE = "LOADPODETAILS";
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Business_Action_Controller/po_Controller.php",
                    type: "POST",
                    data: { TYP: TYPE, ITEMID: $scope.outgoing_invoice_excise.oinv_codePartNo, PRINID: $scope.outgoing_invoice_excise.principalID, POID: $("#PurchaseOrderid").val(), TAG: "E" ,BPOTYPE:bpoType},
                    //cache: false,
                    success: function (jsondata) {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        $scope.$apply(function () {
                            $scope.outgoing_invoice_excise._item_id = objs[0].po_codePartNo;
                            $scope.outgoing_invoice_excise.discount = objs[0].po_discount;
                            $scope.outgoing_invoice_excise.saleTaxID = objs[0].po_saleTax;
                            $scope.outgoing_invoice_excise.saleTax = objs[0].po_saleTax;
                            $scope.outgoing_invoice_excise.bpod_Id = objs[0].bpod_Id;
                            $scope.outgoing_invoice_excise.buyer_item_code = objs[0].po_buyeritemcode;
                            $scope.outgoing_invoice_excise.oinv_price = objs[0].po_price;
                            $scope.outgoing_invoice_excise.ordered_qty = objs[0].po_qty;
                            $scope.outgoing_invoice_excise.balance_qty = objs[0].po_balance_qty;
                            var m = 0, liq = 0;
                            while (m < $scope.outgoing_invoice_excise._items.length) {
                                if ($scope.outgoing_invoice_excise.oinv_codePartNo == $scope.outgoing_invoice_excise._items[m]["_item_id"]) {
                                    liq = parseInt(liq) + parseInt($scope.outgoing_invoice_excise._items[m]["issued_qty"]);
                                }
                                m++;
                            }
                          
                            if (liq > 0) {
                                $scope.outgoing_invoice_excise.balance_qty = parseFloat(objs[0].po_balance_qty) - parseFloat(liq);
                            }
                            else {
                                $scope.outgoing_invoice_excise.balance_qty = parseFloat(objs[0].po_balance_qty)
                            }
                            if (objs[0].po_ed_applicability == "I") {
                                $scope.outgoing_invoice_excise.inclusive_ed_tag = true;
                            }
                            else if (objs[0].po_ed_applicability == "E")
                            {
                                $scope.outgoing_invoice_excise.inclusive_ed_tag = false;
                            }

                        });
                    }
                });
            }
        }*/
    }

    $scope.getTotal_Price = function () {
        var issuedQty = parseFloat($scope.outgoing_invoice_excise.issued_qty);
        if (issuedQty > 0) {
            $scope.outgoing_invoice_excise.tot_price = parseFloat($scope.outgoing_invoice_excise.issued_qty) * parseFloat($scope.outgoing_invoice_excise.oinv_price);
            //~ $scope.outgoing_invoice_excise.ed_amt = parseFloat($scope.outgoing_invoice_excise.ed_perUnit) * parseFloat($scope.outgoing_invoice_excise.issued_qty);
            //~ $scope.outgoing_invoice_excise.edu_amt = (parseFloat($scope.outgoing_invoice_excise.ed_amt) * parseFloat($scope.outgoing_invoice_excise.edu_percent)) / 100;
            //~ $scope.outgoing_invoice_excise.hedu_amount = (parseFloat($scope.outgoing_invoice_excise.ed_amt) * parseFloat($scope.outgoing_invoice_excise.hedu_percent)) / 100;
            //~ $scope.outgoing_invoice_excise.cvd_amt = parseFloat($scope.outgoing_invoice_excise.cvd_unit_amount) * parseFloat($scope.outgoing_invoice_excise.issued_qty);
        }
        $scope.getTaxableAmt();

    }
    
    /* BOF for Adding GST by Gajendra on 27-06-2017 */
	
	
	$scope.getTaxableAmt = function () {
		$item_discount = $("#item_discount").val();
		if($item_discount == '' || $item_discount == undefined){
			$item_discount = 0;
		} 
		var discount_per = parseFloat($item_discount);
		//~ var total_amt = parseFloat($scope.outgoing_invoice_excise.tot_price);
		var total_amt = parseFloat($scope.outgoing_invoice_excise.issued_qty) * parseFloat($scope.outgoing_invoice_excise.oinv_price);
		var taxable_amt = parseFloat(((100 - discount_per)/100) * total_amt);
		var cgst_rate = $("#cgst_rate").val();
		var sgst_rate = $("#sgst_rate").val();
		var igst_rate = $("#igst_rate").val();
		if(cgst_rate == "") {
			cgst_rate = 0;
		}
		if(sgst_rate == "") {
			sgst_rate = 0;
		}
		if(igst_rate == "") {
			igst_rate = 0;
		}
		var cgst_rate = parseFloat(cgst_rate);
		var sgst_rate = parseFloat(sgst_rate);
		var igst_rate = parseFloat(igst_rate);
		
		var cgst_amt = parseFloat((cgst_rate * taxable_amt)/100);
		var sgst_amt = parseFloat((sgst_rate * taxable_amt)/100);
		var igst_amt = parseFloat((igst_rate * taxable_amt)/100);
		
		
		
		var total_price = parseFloat(taxable_amt + cgst_amt + sgst_amt + igst_amt);
		
		$scope.outgoing_invoice_excise.item_taxable_total = taxable_amt.toFixed(2);
		$scope.outgoing_invoice_excise.cgst_amt = cgst_amt.toFixed(2);
		$scope.outgoing_invoice_excise.sgst_amt = sgst_amt.toFixed(2);
		$scope.outgoing_invoice_excise.igst_amt = igst_amt.toFixed(2);
		
		$scope.outgoing_invoice_excise.tot_price = total_price.toFixed(2);
	}
	/* EOF for Adding GST by Gajendra on 27-06-2017 */
    
    $scope.checkStock = function () {
        var stockQty = parseFloat($scope.outgoing_invoice_excise.stock_qty);
        var issuedQty = parseFloat($scope.outgoing_invoice_excise.issued_qty);
        var orderQty = parseFloat($scope.outgoing_invoice_excise.ordered_qty);
        var balance_qty = parseFloat($scope.outgoing_invoice_excise.balance_qty);
        if (issuedQty > balance_qty || issuedQty > stockQty) {
            $scope.outgoing_invoice_excise.issued_qty = 0;
            alert("issued quantity naver be gretar then stock quantity or balance quantity.");
        }
    }

    $scope.get_Invoice_Details = function () {
        var m = 0, flag_invoice = false;
        while (m < $scope.outgoing_invoice_excise._items.length) {
            if (($scope.outgoing_invoice_excise.oinv_codePartNo == $scope.outgoing_invoice_excise._items[m]["_item_id"]) && ($scope.outgoing_invoice_excise.iinv_no == $scope.outgoing_invoice_excise._items[m]["entryId"])) {
                flag_invoice = true;
            }
            m++;
        }
        if (flag_invoice) {
            alert("Same invoice number can not be select for same code part number.");
            $("#invoice_list").val(0);
            return;
        }
        var TYPE = "GET_INVOICE_DETAILS";
        if (true) {
             var iinvobj = $("#invoice_list").val();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: TYPE, INVOICEDID: iinvobj},
                //cache: false,
                success: function (jsondata) {
                    //alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        var indexselect = 0;
                       
                        //~ $scope.outgoing_invoice_excise.ed_percent = objs[indexselect]._ed_percent;
                        //~ $scope.outgoing_invoice_excise.ed_perUnit = objs[indexselect]._ed_per_Unit;
                        $scope.outgoing_invoice_excise.entryId = objs[indexselect]._entry_Id;
                        $scope.outgoing_invoice_excise.mappingid = objs[indexselect].mappingid;
                        $scope.outgoing_invoice_excise.edu_percent = objs[indexselect]._edu_cess_percent;
                        $scope.outgoing_invoice_excise.hedu_percent = objs[indexselect]._hedu_percent;
                        $scope.outgoing_invoice_excise.cvd_percent = objs[indexselect]._cvd_percent;
                        var unit_qnty = 0.00;
                        if (parseFloat(objs[indexselect]._cvd_percent) > 0 && objs[indexselect]._cvd_percent != "") {
                            var InvoiceQuantity = 0;
                            if (objs[indexselect]._principal_qty > 0) {
                                InvoiceQuantity = objs[indexselect]._principal_qty;
                            }
                            else if (objs[indexselect]._supplier_qty > 0) {
                                InvoiceQuantity = objs[indexselect]._supplier_qty;
                            }
                            unit_qnty = parseFloat(objs[indexselect]._cvd_amount) / parseInt(InvoiceQuantity);
                        }
                        $scope.outgoing_invoice_excise.cvd_unit_amount = unit_qnty;
                    });
                }
            });
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "GET_ABV_OUANTITY", INVOICEDID: iinvobj,INVOICETYPE:'E' },
                success: function (jsondata) {
                    //alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        $scope.outgoing_invoice_excise.stock_qty = objs;
                    });
                }
            });
        }
    }
    
    


} ]);

Outgoing_Invoice_Excise_App.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            if (!ngModelCtrl) {
                return;
            }
            ngModelCtrl.$parsers.push(function (val) {
                //var clean = val.replace(/[^0-9]+/g, '');
				var clean = val.replace(/[^\d.]/g, ''); // Codefire Changes for float quantity 27.07.15
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
        }
    }
});


Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.issued_qty', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.issued_qty = oldValue;
                }
            });
        }
    };
});

Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.total_discount', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.total_discount = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.total_ed', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.total_ed = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.pf_chrg', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.pf_chrg = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.incidental_chrg', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.incidental_chrg = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.total_saleTax', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.total_saleTax = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.freight_amount', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.freight_amount = oldValue;
                }
            });
        }
    };
});
Outgoing_Invoice_Excise_App.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('outgoing_invoice_excise.bill_value', function (newValue, oldValue) {

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
                    scope.outgoing_invoice_excise.bill_value = oldValue;
                }
            });
        }
    };
});

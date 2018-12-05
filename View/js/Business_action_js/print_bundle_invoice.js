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

var URL = "../../Controller/Business_Action_Controller/BundleInvoice_Controller.php";
var method = "POST";
var Outgoing_Bundle_Invoice_NonExcise_App = angular.module('Outgoing_Bundle_Invoice_NonExcise_App', []);
Outgoing_Bundle_Invoice_NonExcise_App.controller('Bundle_Controller', ['$scope', '$http', function Bundle_Controller($scope, $http) {
        var sample_outgoing_invoice_nonexcise = {_items: [{bpod_Id: 0, buyer_item_code: '', oinv_codePartNo: '', _item_id: 0, codePartNo_desc: '', ordered_qty: '',balance_qty: '', stock_qty: '', issued_qty: '', oinv_price: '', item_amount: '', discount: 0, saletaxID: 0}]};

        $scope.outgoing_bundle_invoice_nonexcise = sample_outgoing_invoice_nonexcise;


   
 $scope.calculationNew = function () {
					var totalAmt = 0;
					var k = 0;
					while (k < $scope.outgoing_bundle_invoice_nonexcise.bundles.length) {
							totalAmt = totalAmt + parseFloat($scope.outgoing_bundle_invoice_nonexcise.bundles[k].ibpo_totVal);
						k++;
					}					
					$scope.outgoing_bundle_invoice_nonexcise.bill_value = totalAmt ;
					$scope.outgoing_bundle_invoice_nonexcise.total_saleTax = parseFloat((totalAmt * $scope.outgoing_bundle_invoice_nonexcise.bundles[0].saletax_chrg)/100);
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
           
        }
		
		// call area for print bundle outgoing invoice
        var print_itme_list = [{sn: 0, codepart: '', desc: '', qty: '', rate: '', amount: '', cpart: ''}];
        $scope.print = function (number) { 
            var BuyerId = 0, PoID = 0;
            var responsePromise = $http.get(URL + "?TYP=SELECT&outgoing_bundle_invoice_nonexcise=" + number);
            responsePromise.success(function (data, status, headers, config) {
			
                $scope.outgoing_bundle_invoice_nonexcise = data[0];
             
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
					
					var bsAmt = 0.00, TaxableAmount = 0.00;
                    var k = 0;
					while (k < data[0].bundles.length) {					
						bsAmt = parseFloat(data[0].bundles[k].ibpo_totVal) + bsAmt;
						k++;
					}
                    $scope.PODetaile._items2.splice($scope.PODetaile._items2.indexOf(0), 1);
                    $scope.BillDetaile.discount_amt = data[0].discount;
                    $scope.BillDetaile.discount_percent = objspo[0]._items[0].po_discount;
                    if ($scope.BillDetaile.discount_amt <= 0 || $scope.BillDetaile.discount_amt == null)
                    {
                        $("#discountdetails").hide();
                        $("#discountdetails2").hide();
                    }
                    $scope.BillDetaile.pf_amt = parseFloat((bsAmt*parseFloat(data[0].pf_chrg))/100);
                    $scope.BillDetaile.pf_percent = parseFloat(objspo[0].pf_chrg);
                    if (parseFloat(data[0].pf_chrg) <= 0 || data[0].pf_chrg == null)
                    {
                        $("#pfblog").hide();
                        $("#pfblog2").hide();
                      
                    }

                    $scope.BillDetaile.inci_amt =parseFloat((bsAmt*parseFloat(data[0].incidental_chrg))/100); 
                    $scope.BillDetaile.inci_percent = parseFloat(objspo[0].inci_chrg);
                    if (parseFloat(data[0].incidental_chrg) <= 0 || data[0].incidental_chrg == null)
                    {
                        $("#inciblog").hide();
                        $("#inciblog2").hide();
                       
                    }
                    $scope.BillDetaile.feright_amt = data[0].freight_amount;
                    if(objspo[0].frgtp==0 ||objspo[0].frgtp==""||objspo[0].frgtp==0.00){
					$scope.BillDetaile.feright_percent='';
				   }else{
					 $scope.BillDetaile.feright_percent = ' @ '+parseFloat(objspo[0].frgtp)+'%';
				   }
                   if (parseFloat(data[0].freight_amount) <= 0 || data[0].freight_amount == null){
                        $("#ferightblog").hide();
                        $("#ferightblog2").hide();
                       
                    }
                  
					$scope.BillDetaile.basic_amount = bsAmt;					
					$scope.BillDetaile.TaxableAmount = parseFloat($scope.BillDetaile.basic_amount) - parseFloat($scope.BillDetaile.discount_amt) + parseFloat((bsAmt*parseFloat(data[0].pf_chrg))/100) + parseFloat((bsAmt*parseFloat(data[0].incidental_chrg))/100);

					$scope.BillDetaile.TaxableAmount = ($scope.BillDetaile.TaxableAmount).toFixed(2);
					var responseTax = $http.get(URL + "?TYP=GETTAXDETAILS&TAXID=" + data[0].bundles[0].ibpo_saleTax);
					responseTax.success(function (datatax, status, headers, config) {
					$scope.TaxDetaile = datatax[0];
					var scharge = 0;
					var stax = 0;
					if (datatax[0].TYPE == "C"){
						$("#cst_invoice").show();
					}else{
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
						scharge = 0;
					}else{
						scharge = parseFloat($scope.BillDetaile.SurchargeAmount);
					}
					
					if (parseFloat($scope.BillDetaile.SaleTaxAmount) <= 0 || $scope.BillDetaile.SaleTaxAmount == "NaN")
					{					
						stax = 0;
					}else{
						stax = parseFloat($scope.BillDetaile.SaleTaxAmount);
					}
					
				var totalamt = parseFloat(parseFloat(scharge) + parseFloat(stax)+ parseFloat($scope.BillDetaile.TaxableAmount));
			
				 $scope.outgoing_bundle_invoice_nonexcise.bill_value = totalamt;
                var round_off_value = parseFloat(totalamt.toString().split(".")[1]);
                if(round_off_value >= 50)
                {
                    $scope.outgoing_bundle_invoice_nonexcise.bill_value = parseFloat(totalamt.toString().split(".")[0]) + 1;
                }
                else
                {
                    $scope.outgoing_bundle_invoice_nonexcise.bill_value = parseFloat(totalamt.toString().split(".")[0]);
                }
                $scope.outgoing_bundle_invoice_nonexcise.toatlvalueinword = inWords(parseInt($scope.outgoing_bundle_invoice_nonexcise.bill_value));
					
					});

                });

            });
        };			// End call area for print bundle outgoing invoice


    }]);





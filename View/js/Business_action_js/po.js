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

// change comment due to page loading performance on 23-11-2015 by Codefire
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

///////////////////////////////// change due to page loading performance on 23-11-2015 by Codefire

 function loadBuyerById(buyer){ 
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
    //alert("ankur");
    if (value != "" && data!="") {
        $("#buyerid").val(data);
        jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
            data: {
                TYP: "POQUOTATION",
                BUYERID: data
            },
            success: function (jsondata) {
                //alert(jsondata);
                $('#iquotNo').empty();
                $("#iquotNo").append("<option value='0'>Select Quotation</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj; for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        $("#iquotNo").append("<option value=\"" + obj.po_quotNo + "\">" + obj.po_quotNo + "</option>");
                    }
                    //$("#iquotNo").val(0);
                }
            }
        });
        
           jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
            data: {
                TYP: "SALESTAX",
                BUYERID: data
            },

            success: function (jsondata) {
                //alert(jsondata);
                $('#isalestax').empty();
                $("#isalestax").append("<option value='0'>Select Tax</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj; for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        $("#isalestax").append("<option value=\"" + obj.sTax + "\">" + obj.po_saleTax + "</option>");
                    }
                    //$("#isalestax").val(0);
                }
            }
        });    
        
        
        
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: "POST",
            data: { TYP: "BILLINGADD", BUYERID: data },
            //cache: false,
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    $("#ic_p").val(objs[0]._credit_period);
                    $("#ibadd1").val(objs[0]._bill_add1);
                    $("#ibadd2").val(objs[0]._bill_add2);
                    $("#billingcountry1").val(objs[0]._country_id);
                    $("#billingcountryName1").val("INDIA");
                    $("#billingstate1").val(objs[0]._state_id);
                    $("#billingstateName1").val(objs[0]._state_name);
                    $("#bcity1").val(objs[0]._city_id);
                    $("#bcityName1").val(objs[0]._city_name);
                    $("#blocation1").val(objs[0]._location_id);
                    $("#blocationname1").val(objs[0]._location_name);
                    $("#ibpincode").val(objs[0]._pincode);
                    $("#iphno").val(objs[0]._phone);
                    $("#imobno").val(objs[0]._mobile);
                    $("#ibfax").val(objs[0]._fax);
                    $("#ibemail").val(objs[0]._email);
		//$("#bemailId").val(objs[0]._email);
                }else {
                    $("#ic_p").val("");
                    $("#ibadd1").val("");
                    $("#ibadd2").val("");
                    $("#billingcountry1").val(0);
                    $("#billingcountryName1").val("");
                    $("#billingstate1").val(0);
                    $("#billingstateName1").val("");
                    $("#bcity1").val(0);
                    $("#bcityName1").val("");
                    $("#blocation1").val(0);
                    $("#blocationname1").val("");
                    $("#ibpincode").val("");
                    $("#iphno").val("");
                    $("#imobno").val("");
                    $("#ibfax").val("");
                    $("#ibemail").val("");
                }
            }
        });
		/* BOF for fetching Shipping Address by Ayush Giri on 21-07-2017 */
		jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/po_Controller.php",
            type: "POST",
            data: { TYP: "SHIPPINGADD", BUYERID: data },
            //cache: false,
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
						
                    $("#isadd1").val(objs[0]._add1);
                    $("#isadd2").val(objs[0]._add2);
                    $("#shppingcountry1").val(objs[0]._country_id);
                    $("#shppingstate1").val(objs[0]._state_id);
                    //$("#shppingcity1").val(objs[0]._city_id);
                   //$("#shppinglocation1").val(objs[0]._location_id);
                    $("#ispincode").val(objs[0]._pincode);
                    $("#isphno").val(objs[0]._phone);
                    $("#ismobno").val(objs[0]._mobile);
                    $("#isfax").val(objs[0]._fax);
                    $("#isemail").val(objs[0]._email);
					CITYID = objs[0]._city_id;
			//alert(CITYID);
            STATEID = objs[0]._state_id;
            locationid = objs[0]._location_id;
            //$scope.showCity($scope.purchaseOrder.sstate1);
            var TYPE = "SELECT"; if (true) {
                jQuery.ajax({ url: "../../Controller/Master_Controller/City_Controller.php", type: "POST",
                    data: { TYP: TYPE, TAG: "STATE", STATEID: STATEID, CITYID : CITYID}, success: function (jsondata) {
                        $('#shppingcity1').empty();
                        $("#shppingcity1").append("<option value=''>Select City</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj; for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#shppingcity1").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");
                            }
                            $("#shppingcity1").val(CITYID);
                        }
                    }
                });
            }
            var TYPE = "SELECT";
            if (true) {
                jQuery.ajax({ url: "../../Controller/Master_Controller/LocationController.php", type: "POST",
                    data: {
                        TYP: TYPE,
                        CITYID: CITYID
                    }, success: function (jsondata) {
                        $('#shppinglocation1').empty();
                        $("#shppinglocation1").append("<option value=''>Select Location</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj; for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#shppinglocation1").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");
                            }
                            $("#shppinglocation1").val(locationid);
                        }
                    }
                });
            }
					
					
                }else {
                    $("#isadd1").val('');
                    $("#isadd2").val('');
                    $("#shppingcountry1").val(0);
                    $("#shppingstate1").val(0);
                    $("#shppingcity1").val(0);
                    $("#shppinglocation1").val(0);
                    $("#ispincode").val('');
                    $("#isphno").val('');
                    $("#ismobno").val('');
                    $("#isfax").val('');
                    $("#isemail").val('');
                }
            }
        });
		/* EOF for fetching Shipping Address by Ayush Giri on 21-07-2017 */
    }
    
}
function NoneBuyer() {
    $("#buyerid").val(0);
    $('#ipquotNo').empty();
    $("#ipquotNo").append("<option value=''></option>");
    $("#ic_p").val("");
    $("#ibadd1").val("");
    $("#ibadd2").val("");
    $("#billingcountry1").val(0);
    $("#billingcountryName1").val("");
    $("#billingstate1").val(0);
    $("#billingstateName1").val("");
    $("#bcity1").val(0);
    $("#bcityName1").val("");
    $("#blocation1").val(0);
    $("#blocationname1").val("");
    $("#ibpincode").val("");
    $("#iphno").val("");
    $("#imobno").val("");
    $("#ibfax").val("");
    $("#ibemail").val("");
}

var CodePartNoList = {};
//var codepartnoArray = null;
function CallToCodePartNo(CodePartNoList,shipped_state_id) {
    'use strict';
    //if(codepartnoArray != null)
    var codepartnoArray = $.map(CodePartNoList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-CodePartNo').autocomplete({
    	//minChars : 3,
    	delay : 0,
        //serviceUrl: '/autosuggest/service/',
        lookup: codepartnoArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
        	var d = new Date();
			var n = d.getTime();
        	console.log("before regular expression time is " + d.toString());
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            var d1 = new Date();
			var n1 = d.getTime();
        	
        	var x =  re.test(suggestion.value);
        	
        	console.log("After regular expression difference " + (n1 - n).toString());
        	return x;
            
        },
        onSelect: function (suggestion) {
            ActionOnCodePartNo(suggestion.value, suggestion.data);
            //$('#selction-ajax-codepartno').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
			//alert($("#iprincipalId").val());
			GetGSTRates(shipped_state_id, suggestion.data,$("#iprincipalId").val());
			//$('#hsn_code').val(suggestion.value);
        },
        onHint: function (hint) {
            //alert("ankur");
            //$('#autocomplete-ajax-x-codepartno').val(hint);
        },
        onInvalidateSelection: function () {
            NoneCodePartNo();
            //$('#selction-ajax-codepartno').html('You selected: none');
        }
    });
}

/* BOF to add GST Rates by Ayush Giri on 16-06-2017 */
function GetGSTRates(buyer_id,item_code, principal_supplier_id){
	if (item_code != "" && buyer_id > 0) {
		
		jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
			data: {
				TYP: "GETGST",
				//ITEMID:$("#icodePartNo").val(),
				//SHIPPED_STATE_ID:$("#shppingstate1").val()// Ayush
				ITEMID:item_code,
				BUYER_ID:buyer_id,
				PRINCIPAL_SUPPLIER_ID:principal_supplier_id
				},
			success: function (jsondata) {
					 //alert(jsondata);
					 var objs1 = jQuery.parseJSON(jsondata);
					 var objs = objs1[0];
					 if (jsondata != "") {
						 $("#hsn_code").val(objs.HSN_CODE);
						 $("#cgst_rate").val(objs.CGST_RATE);
						 $("#sgst_rate").val(objs.SGST_RATE);
						 $("#igst_rate").val(objs.IGST_RATE);
						 $("#gst_rate").val(objs.GST_RATE);
					 }
			}
		});
     }
}
/* BOF to add GST Rates by Ayush Giri on 16-06-2017 */

function ActionOnCodePartNo(value,data){
	if (value != "" && data > 0) {
            $("#icodePartNo").val(data);
            //alert($("#icodePartNo").val()+"|"+$("#iquotNo").val()+"|"+$("#iprincipalId").val());
            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                data: {
                    TYP: "LOADITEM",
                    BUYERID: $("#buyerid").val(),
					QUOTID:$("#iquotNo").val(),
					PID:$("#iprincipalId").val(),
					ITEMID:$("#icodePartNo").val(),
					SHIPPED_STATE_ID:$("#shppingstate1").val()// Ayush
					},
                    success: function (jsondata) {
                             //alert(jsondata);
                             var objs1 = jQuery.parseJSON(jsondata);
                             var objs = objs1[0];
                             if (jsondata != "") {
                                 $("#iden_mark").val(objs.Item_Identification_Mark); 
                                 $("#item_desc").val(objs.item_desc); 
                                 $("#iunit").val(objs.po_unit);
                                 $("#unitId").val(objs.unit_id);  
                                 $("#iprice").val(objs.po_price);
                                 $("#ioprice").val(objs.po_price);									
							     $("#ipo_price_category").val("N");
                             }
                    }
            });
     }
}
function NoneCodePartNo(){
	
}
var URL = "../../Controller/Business_Action_Controller/po_Controller.php";
var method = "POST";
var purchaseorder_app = angular.module('po_app', []).directive('animate', function () {
    return function (scope, elm, attrs) {
        setTimeout(function () {
            elm.addClass('show');
        });
    };
}).directive('remove', function () {
    return function (scope, elm, attrs) {
        elm.bind('click', function (e) {
            e.preventDefault();
            elm.removeClass('show');
            setTimeout(function () {
                scope.$apply(function () {
                    scope.$eval(attrs.remove);
                });
            }, 200);
        });
    };
});
purchaseorder_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('purchaseOrder.pf_chrg', function(newValue,oldValue) {
 
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
                    scope.purchaseOrder.pf_chrg = oldValue;
                }
            });
		}
	};
});
purchaseorder_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('purchaseOrder.inci_chrg', function(newValue,oldValue) {
 
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
                    scope.purchaseOrder.inci_chrg = oldValue;
                }
            });
		}
	};
});
purchaseorder_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('purchaseOrder.frgtp', function(newValue,oldValue) {
 
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
                    scope.purchaseOrder.frgtp = oldValue;
                }
            });
		}
	};
});
purchaseorder_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('purchaseOrder.frgta', function(newValue,oldValue) {
 
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
                    scope.purchaseOrder.frgta = oldValue;
                }
            });
		}
	};
});
purchaseorder_app.directive('ngBlur', function () {
    return function (scope, elem, attrs) {
        elem.bind('blur', function () {
            scope.$apply(attrs.ngBlur);
        });
    };
});
function App($scope) {
    $scope.names = [];
    var data = [{ SN: 0, ITEM: $scope.itemdesc, DSC: $scope.itemdesc}];
    $scope.add = function () {
        if (data.length)
            $scope.names.push(data.pop());
    };
    $scope.remove = function (index) {
        data.push($scope.names.splice(index, 1)[0]);
    };
}
// create angular controller
purchaseorder_app.controller('po_Controller', function ($scope) {
    $scope.purchaseOrder =  {number: 1, validity: true};
    /* var sample_po = { _items: [{ bpod_Id: 0, eda1: '', po_quotNo: '', pname: '', 
    po_principalId: '', cPartNo: '', po_codePartNo: '', iden_mark: '', item_desc: '', 
    po_buyeritemcode: '', po_qty: '', po_unitid: '', po_unit: '', po_price: '', po_discount: '',
     po_ed_applicability: '', sTax: '', po_saleTax: '', po_deliverybydate: '', po_totVal: '',
     po_odiscount:'', po_oprice: '',po_price_category:'',po_discount_category:''}] }; */
	 
	var sample_po = { 
		_items: [{ 
			bpod_Id: 0, 
			eda1: '',
			po_quotNo: '',
			pname: '', 
			po_principalId: '',
			cPartNo: '',
			po_codePartNo: '',
			iden_mark: '',
			item_desc: '', 
			po_buyeritemcode: '',
			po_qty: '',
			po_unitid: '',
			po_unit: '',
			po_price: '',
			po_discount: '',
			po_hsn_code: '',
			po_cgst_rate: '',
			po_cgst_amt: '',
			po_sgst_rate: '',
			po_sgst_amt: '',
			po_igst_rate: '',
			po_igst_amt: '',
			po_deliverybydate: '',
			//po_totVal: '',
			po_taxable_amt: '',
			po_finVal: '',
			po_odiscount:'',
			po_oprice: '',
			po_price_category:'',
			po_discount_category:''
		}] 
	};
	 
    $scope.purchaseOrder = sample_po;
    $scope.addItem = function () {
		
	/* 	$scope.purchaseOrder.eda1=$scope.purchaseOrder.po_ed_applicability;
		$scope.purchaseOrder.po_saleTax = $scope.purchaseOrder.po_saleTax; */
        var isExist="B";
        //alert($scope.purchaseOrder.po_price_category);
        //alert($("#ipo_price_category").val());
        //$scope.purchaseOrder.po_quotNo = $("#iquotNo").val();
        //added by gajendra
        if($scope.purchaseOrder.cPartNo == ''){
			alert('Please enter Part No.');
			return;
		}
		if($scope.purchaseOrder.po_qty == ''){
			alert('Please enter quantity');
			return;
		}
		if($scope.purchaseOrder.po_price == ''){
			alert('Please enter price');
			return;
		}
		//end
        $scope.purchaseOrder.po_deliverybydate = $("#ideldate").val();
        $scope.purchaseOrder.po_codePartNo = $("#icodePartNo").val();
        $scope.purchaseOrder.cPartNo = $("#autocomplete-ajax-CodePartNo").val();
        $scope.purchaseOrder.po_unitid = $("#unitId").val();
        $scope.purchaseOrder.po_price = $("#iprice").val();
        $scope.purchaseOrder.po_oprice = $("#ioprice").val();
        $scope.purchaseOrder.pname = $("#ipname").val();
        if($scope.purchaseOrder.po_codePartNo==undefined){
		    document.getElementById("icodePartNo").style.backgroundColor = "yellow";
			document.getElementById("icodePartNo").value="";
            isExist="A";
		} 
        if($scope.purchaseOrder.po_qty==undefined){
			document.getElementById("iqty").style.backgroundColor = "yellow";
			document.getElementById("iqty").value="";
            isExist="A.";
		}
        if($scope.purchaseOrder.po_price==undefined){
			document.getElementById("iprice").style.backgroundColor = "yellow";
			document.getElementById("iprice").value="";
            isExist="A..";
		}         
       //~ if($scope.purchaseOrder.po_ed_applicability==undefined){
		    //~ document.getElementById("iedapp").style.backgroundColor = "yellow";
			//~ document.getElementById("iedapp").value="";
			//~ isExist="A...";
		//~ }  
       //~ if($scope.purchaseOrder.po_saleTax=="0"){
		    //~ document.getElementById("isalestax").style.backgroundColor = "yellow";
			//~ document.getElementById("isalestax").value="";
			//~ isExist="A....";
		//~ }         
       if($scope.purchaseOrder.pot=="N" && $scope.purchaseOrder.po_deliverybydate==""){
		    document.getElementById("ideldate").style.backgroundColor = "yellow";
			document.getElementById("ideldate").value="";
			isExist="A.....";
		}
        var j=0,pr,cpn,pr1,cpn_it,pr_po_ed,pr_it_ed,pst,pst_it;
        while(j < $scope.purchaseOrder._items.length){
			
		      pr=$scope.purchaseOrder.po_principalId;
		      cpn=$scope.purchaseOrder.po_codePartNo;
		      cpn_it=$scope.purchaseOrder._items[j]["po_codePartNo"];
		      pr1=$scope.purchaseOrder._items[j]["po_principalId"];
		      pr_po_ed=$scope.purchaseOrder.po_ed_applicability;
		      pr_it_ed=$scope.purchaseOrder._items[j]["po_ed_applicability"];
		      pst=$scope.purchaseOrder.po_saleTax;
		      pst_it=$scope.purchaseOrder._items[j]["po_saleTax"];
		      //~ if(cpn_it==cpn){
			  	 //~ if((pr_po_ed=="")||((pr_po_ed=="E" && (pr_it_ed=="I" || pr_it_ed=="N")) || (pr_po_ed=="I" && (pr_it_ed=="E" || pr_it_ed=="N")) || (pr_po_ed=="N" && (pr_it_ed=="E" || pr_it_ed=="I")))){
				 	//~ isExist="A......";
				 //~ }
			  //~ }
				if(cpn_it==cpn)
				{
					alert('You have already added this item.');
					$scope.purchaseOrder.eda1=null;
					$scope.purchaseOrder.pname=null;

					$scope.purchaseOrder.cPartNo="";
					$scope.purchaseOrder.po_codePartNo =null;
					$scope.purchaseOrder.po_codePartNo =null;
					$scope.purchaseOrder.iden_mark =null;
					$scope.purchaseOrder.item_desc =null;
					$scope.purchaseOrder.po_buyeritemcode="";
					$scope.purchaseOrder.po_qty="";
					$scope.purchaseOrder.po_unitid =null;
					$scope.purchaseOrder.po_unit=null;
					$scope.purchaseOrder.po_price=null;
					$scope.purchaseOrder.po_discount=null;
					$("#idiscount").val("");
					$("#hsn_code").val("");
					$("#igst_rate").val("");
					$("#cgst_rate").val("");
					$("#sgst_rate").val("");
					$("#iden_mark").val("");
					$("#item_desc").val("");
					$("#iunit").val("");
					$("#ideldate").val("");
					$scope.purchaseOrder.po_hsn_code = "";
					$scope.purchaseOrder.po_cgst_rate = "";
					$scope.purchaseOrder.po_cgst_amt = "";
					$scope.purchaseOrder.po_sgst_rate = "";
					$scope.purchaseOrder.po_sgst_amt = "";
					$scope.purchaseOrder.po_igst_rate = "";
					$scope.purchaseOrder.po_igst_amt = "";
					$scope.purchaseOrder.po_deliverybydate ="";
					$scope.purchaseOrder.po_taxable_amt=null;
					$scope.purchaseOrder.po_finVal=null;
					$scope.purchaseOrder.po_oprice=null;
					$scope.purchaseOrder.po_odiscount=null;
					$("#iodiscount").val("");   
					$scope.purchaseOrder.po_price_category=null;
					$scope.purchaseOrder.po_discount_category=null;
					ActionOnBuyer(0,$("#buyerid").val());
					$("#iquotNo").val("0");
					$scope.showPOPrincipal();	
					$("#isalestax").val(0);
					$("#iprincipalId").val("0");
					isExist = "A......";
					break;
			  }
		      j++;
		}
        if($scope.purchaseOrder.po_quotNo!=undefined){
			$scope.purchaseOrder.po_principalId = $("#iprincipalId").val();
		}
        //alert(isExist);
        if(isExist!="B"){
			return;
		}else{
			
			/* $scope.purchaseOrder._items.push({ bpod_Id: $scope.purchaseOrder.bpod_Id, 
			eda1: $scope.purchaseOrder.eda1,po_quotNo: $("#iquotNo").val(),
			 pname: $scope.purchaseOrder.pname, po_principalId: $scope.purchaseOrder.po_principalId, 
			 cPartNo: $scope.purchaseOrder.cPartNo, po_codePartNo: $scope.purchaseOrder.po_codePartNo,
			  iden_mark: $("#iden_mark").val(), item_desc:$("#item_desc").val(), 
			  po_buyeritemcode: $scope.purchaseOrder.po_buyeritemcode, 
			  po_qty: $scope.purchaseOrder.po_qty, unit_id: $scope.purchaseOrder.po_unitid, 
			  po_unit: $("#iunit").val(), po_price: $("#iprice").val(), 
			  po_discount: $("#idiscount").val(), po_ed_applicability: $scope.purchaseOrder.po_ed_applicability, 
			  sTax: $scope.purchaseOrder.sTax, po_saleTax: $scope.purchaseOrder.po_saleTax, 
			  po_deliverybydate: $scope.purchaseOrder.po_deliverybydate, 
			  po_totVal: $scope.purchaseOrder.po_totVal,po_odiscount: $("#iodiscount").val(),
			   po_oprice: $("#ioprice").val(),po_price_category:$scope.purchaseOrder.po_price_category,po_discount_category:$scope.purchaseOrder.po_discount_category}); */
			   
			$scope.purchaseOrder._items.push({
				bpod_Id: $scope.purchaseOrder.bpod_Id, 
				eda1: $scope.purchaseOrder.eda1,
				po_quotNo: $("#iquotNo").val(),
				pname: $scope.purchaseOrder.pname, 
				po_principalId: $scope.purchaseOrder.po_principalId, 
				cPartNo: $scope.purchaseOrder.cPartNo,
				po_codePartNo: $scope.purchaseOrder.po_codePartNo,
				iden_mark: $("#iden_mark").val(), 
				item_desc:$("#item_desc").val(), 
				po_buyeritemcode: $scope.purchaseOrder.po_buyeritemcode, 
				po_qty: $scope.purchaseOrder.po_qty,
				unit_id: $scope.purchaseOrder.po_unitid,
				po_unit: $("#iunit").val(),
				po_price: $("#iprice").val(), 
				po_discount: $("#idiscount").val(),
				po_hsn_code: $("#hsn_code").val(),
				po_cgst_rate: $("#cgst_rate").val(),
				po_cgst_amt: $("#cgst_amt").val(),
				po_sgst_rate: $("#sgst_rate").val(),
				po_sgst_amt: $("#sgst_amt").val(),
				po_igst_rate: $("#igst_rate").val(),
				po_igst_amt: $("#igst_amt").val(),
				po_deliverybydate: $scope.purchaseOrder.po_deliverybydate, 
				//po_totVal: $scope.purchaseOrder.po_totVal,
				po_taxable_amt: $scope.purchaseOrder.po_taxable_amt,
				po_finVal: $scope.purchaseOrder.po_finVal,
				po_odiscount: $("#iodiscount").val(),
				po_oprice: $("#ioprice").val(),
				po_price_category:$scope.purchaseOrder.po_price_category,
				po_discount_category:$scope.purchaseOrder.po_discount_category
				});
			
		}  
          
        $scope.purchaseOrder.poVal = 0;
        var k = 0;
        var totalTaxableValue = 0;
        while (k < $scope.purchaseOrder._items.length) {
            //$scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_totVal"]) + parseFloat($scope.purchaseOrder.poVal);
			$scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_finVal"]) + parseFloat($scope.purchaseOrder.poVal);
			totalTaxableValue = parseFloat(totalTaxableValue) + parseFloat($scope.purchaseOrder._items[k]["po_taxable_amt"]);
			totalTaxableValue = parseFloat(totalTaxableValue).toFixed(2);
            k++;
        }
        var PFChargeAmt =0;
        var IncidentalChargeAmt =0;
        var InsuranceChargeAmt =0;
        var OtherChargeAmt =0;
        var FreightChargeAmt =0;
        var pf_chrg = 0, incident = 0, insurance = 0, other = 0, freight = 0;
        if (!isNaN($scope.purchaseOrder.pf_chrg) && $scope.purchaseOrder.pf_chrg != "" && $scope.purchaseOrder.pf_chrg != null) {
            pf_chrg = parseFloat($scope.purchaseOrder.pf_chrg);
        }
        PFChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(pf_chrg))/100);
        PFChargeAmt = parseFloat(PFChargeAmt).toFixed(2);
        if (!isNaN($scope.purchaseOrder.inci_chrg) && $scope.purchaseOrder.inci_chrg != "" && $scope.purchaseOrder.inci_chrg != null) {
            incident = parseFloat($scope.purchaseOrder.inci_chrg);
        }
        IncidentalChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(incident))/100);
        IncidentalChargeAmt = parseFloat(IncidentalChargeAmt).toFixed(2);
		if (!isNaN($scope.purchaseOrder.ins_charge) && $scope.purchaseOrder.ins_charge != "" && $scope.purchaseOrder.ins_charge != null) {
            insurance = parseFloat($scope.purchaseOrder.ins_charge);
        }
		InsuranceChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(insurance))/100);
		InsuranceChargeAmt = parseFloat(InsuranceChargeAmt).toFixed(2);
		 if (!isNaN($scope.purchaseOrder.othc_charge) && $scope.purchaseOrder.othc_charge != "" && $scope.purchaseOrder.othc_charge != null) {
            other = parseFloat($scope.purchaseOrder.othc_charge);
        }
        OtherChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(other))/100);
        OtherChargeAmt = parseFloat(OtherChargeAmt).toFixed(2);
        
		if ($scope.purchaseOrder.frgt == "P") {
            if (!isNaN($scope.purchaseOrder.frgtp) && $scope.purchaseOrder.frgtp != "" && $scope.purchaseOrder.frgtp != null) {
            freight = parseFloat($scope.purchaseOrder.frgtp);
        }
			FreightChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(freight))/100);
			FreightChargeAmt = parseFloat(FreightChargeAmt).toFixed(2);
        } else if ($scope.purchaseOrder.frgt == "A") {
            FreightChargeAmt = parseFloat($scope.purchaseOrder.frgta).toFixed(2);
        } else {
          FreightChargeAmt = 0;
        }
        $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder.poVal) + parseFloat(PFChargeAmt) + parseFloat(IncidentalChargeAmt) + parseFloat(InsuranceChargeAmt) + parseFloat(OtherChargeAmt) + parseFloat(FreightChargeAmt);
        
	    $scope.purchaseOrder.eda1=null;
        $scope.purchaseOrder.pname=null;
        //$scope.purchaseOrder.sTax=null; uncommented, due to not changes in scope value
 
        $scope.purchaseOrder.cPartNo="";
        $scope.purchaseOrder.po_codePartNo =null;
        $scope.purchaseOrder.po_codePartNo =null;
        $scope.purchaseOrder.iden_mark =null;
        $scope.purchaseOrder.item_desc =null;
        $scope.purchaseOrder.po_buyeritemcode="";
        $scope.purchaseOrder.po_qty="";
        $scope.purchaseOrder.po_unitid =null;
        $scope.purchaseOrder.po_unit=null;
        $scope.purchaseOrder.po_price=null;
        $scope.purchaseOrder.po_discount=null;
        $("#idiscount").val("");
        $("#hsn_code").val("");
        $("#igst_rate").val("");
        $("#cgst_rate").val("");
		$("#sgst_rate").val("");
        $("#iden_mark").val("");
        $("#item_desc").val("");
        $("#iunit").val("");
        $("#ideldate").val("");
		/* BOF for adding GST by Ayush Giri on 19-06-2017 */
        //$scope.purchaseOrder.po_ed_applicability="";
        //$scope.purchaseOrder.po_saleTax=$("#isalestax").val("0");
		$scope.purchaseOrder.po_hsn_code = "";
		$scope.purchaseOrder.po_cgst_rate = "";
		$scope.purchaseOrder.po_cgst_amt = "";
		$scope.purchaseOrder.po_sgst_rate = "";
		$scope.purchaseOrder.po_sgst_amt = "";
		$scope.purchaseOrder.po_igst_rate = "";
		$scope.purchaseOrder.po_igst_amt = "";
		/* EOF for adding GST by Ayush Giri on 19-06-2017 */
        $scope.purchaseOrder.po_deliverybydate ="";
        //$scope.purchaseOrder.po_totVal=null;
        $scope.purchaseOrder.po_taxable_amt=null;
		$scope.purchaseOrder.po_finVal=null; // Ayush Giri
        $scope.purchaseOrder.po_oprice=null;
        $scope.purchaseOrder.po_odiscount=null;
        $("#iodiscount").val("");   
        $scope.purchaseOrder.po_price_category=null;
        $scope.purchaseOrder.po_discount_category=null;
        	ActionOnBuyer(0,$("#buyerid").val());
        	$("#iquotNo").val("0");
            $scope.showPOPrincipal();	
            $("#isalestax").val(0);
            $("#iprincipalId").val("0");
    }
    //##################### aksoni
    $scope.RowChangeEvent = function (item) {
		//alert('RowChange');
        var Rowindex =  $scope.purchaseOrder._items.indexOf(item);
        var q=parseFloat($scope.purchaseOrder._items[Rowindex]['po_qty']);
        if($scope.purchaseOrder._items[Rowindex]['po_price'] == null){
			$scope.purchaseOrder._items[Rowindex]['po_price'] = 0;
		}
        var p=parseFloat($scope.purchaseOrder._items[Rowindex]['po_price']);
        var d=parseFloat($scope.purchaseOrder._items[Rowindex]['po_discount']); 
        var total_amt=0.00;
        if ( q!= "" && p!= "") {
        	 if(d!="")
        	 {
				d = parseFloat(d);
				$scope.purchaseOrder._items[Rowindex]['po_taxable_amt'] = parseFloat(((100 - d)/100) * (p*q)).toFixed(2);
        	 	   //var tot=(((q * p) * (100 - d)) / 100); 
			 	   //$scope.purchaseOrder._items[Rowindex]['po_totVal'] =parseFloat(tot).toFixed(2);  
			 	  // $scope.purchaseOrder._items[Rowindex]['po_taxable_amt'] =parseFloat(tot).toFixed(2);  
			 }
			 else
			 {
			 	 
			 	    var tot=(q * p);
			 	   //$scope.purchaseOrder._items[Rowindex]['po_totVal']=parseFloat(tot).toFixed(2);
			 	   $scope.purchaseOrder._items[Rowindex]['po_taxable_amt']=parseFloat(tot).toFixed(2);
			 }
           
        }
        if($scope.purchaseOrder._items[Rowindex]['po_cgst_rate'] == ""){
			var cgst_rate = 0;
		} else {
			var cgst_rate = parseFloat($scope.purchaseOrder._items[Rowindex]['po_cgst_rate']);
		}
		
		if($scope.purchaseOrder._items[Rowindex]['po_sgst_rate'] == ""){
			var sgst_rate = 0;
		} else {
			var sgst_rate = parseFloat($scope.purchaseOrder._items[Rowindex]['po_sgst_rate']);
		}
		
		if($scope.purchaseOrder._items[Rowindex]['po_igst_rate'] == ""){
			var igst_rate = 0;
		} else {
			var igst_rate = parseFloat($scope.purchaseOrder._items[Rowindex]['po_igst_rate']);
		}
		$scope.purchaseOrder._items[Rowindex]['po_cgst_amt'] = parseFloat(($scope.purchaseOrder._items[Rowindex]['po_taxable_amt'] * cgst_rate)/100 ).toFixed(2);
		$scope.purchaseOrder._items[Rowindex]['po_sgst_amt'] = parseFloat(($scope.purchaseOrder._items[Rowindex]['po_taxable_amt'] * sgst_rate)/100 ).toFixed(2);
		$scope.purchaseOrder._items[Rowindex]['po_igst_amt'] = parseFloat(($scope.purchaseOrder._items[Rowindex]['po_taxable_amt'] * igst_rate)/100 ).toFixed(2);
		var final_value = parseFloat($scope.purchaseOrder._items[Rowindex]['po_taxable_amt']) + parseFloat($scope.purchaseOrder._items[Rowindex]['po_cgst_amt']) + parseFloat($scope.purchaseOrder._items[Rowindex]['po_sgst_amt']) + parseFloat($scope.purchaseOrder._items[Rowindex]['po_igst_amt']);
		$scope.purchaseOrder._items[Rowindex]['po_finVal'] = parseFloat(final_value).toFixed(2);
		$scope.getLanding_Price();
        //~ $scope.purchaseOrder.poVal = 0;
        //~ var k = 0;
        //~ var sum=0;
        //~ while (k < $scope.purchaseOrder._items.length) {
           //~ // sum= parseFloat($scope.purchaseOrder._items[k]["po_totVal"])+ parseFloat(sum);
            //~ sum= parseFloat($scope.purchaseOrder._items[k]["taxable_amt"])+ parseFloat(sum);
            //~ k++;
        //~ }
        //~ $scope.purchaseOrder.poVal=parseFloat(sum).toFixed(2);
       
    }
    //########################## end 
    $scope.removeItem = function (item) {
        $scope.purchaseOrder._items.splice($scope.purchaseOrder._items.indexOf(item), 1);
        $scope.purchaseOrder.poVal = 0;
        var k = 0;
        while (k < $scope.purchaseOrder._items.length) {
            $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_totVal"]) + parseFloat($scope.purchaseOrder.poVal);
            k++;
        }
    }
    $scope.AddPO = function () { 
		$scope.purchaseOrder.sadd1 = $("#isadd1").val();
		$scope.purchaseOrder.sadd2 = $("#isadd2").val();
		$scope.purchaseOrder.scountry1 = $("#shppingcountry1").val();
		$scope.purchaseOrder.sstate1 = $("#shppingstate1").val();
		$scope.purchaseOrder.scity1 = $("#shppingcity1").val();
		$scope.purchaseOrder.slocation1 = $("#shppinglocation1").val();
		$scope.purchaseOrder.spincode = $("#ispincode").val();
		$scope.purchaseOrder.sphno = $("#isphno").val();
		$scope.purchaseOrder.smobno = $("#ismobno").val();
		$scope.purchaseOrder.sfax = $("#isfax").val();
		$scope.purchaseOrder.semail = $("#isemail").val();
        if ($scope.purchaseOrder._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
	
	if (($scope.purchaseOrder.po_hold_state == true) && ($('#po_hold_reason').val() == '')) {
            alert("Please Enter reason for hold before submit form.");
            return;
        }
		
        $("#btnsave").hide();
        $scope.purchaseOrder.pod = $("#ipod").val();
        $scope.purchaseOrder.ipovd = $("#ipovd").val();
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder.cp = $("#ic_p").val();
	$scope.purchaseOrder.ms = $("#marketsegment").val();
        var json_string = JSON.stringify($scope.purchaseOrder);
	//alert(json_string); return;
        //alert(URL); return
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", PODATA: json_string },
            success: function (jsondata) {
                //alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
				//console.log(objs); return;
               //alert(objs); return;
			   //alert(redirect_url);
                if (objs > 0) {
                    var x = confirm("Saved and Do you want to go for Management Approval?");
                    if (x == true) {
                        location.href = "management_approval_form.php?fromPage=PO&POID=" + jsondata;
                    } else {
                        //location.href = "purchaseorder.php";
						//location.href = "../home/Dashboard.php";
						//location.href = "http://192.168.1.17/multiweld/home/Dashboard.php";
						//location.href = "../ReportView/po_pending_report.php";
						//location.href = "/multiweld/View/ReportView/po_pending_report.php";
						//window.location = "../../ReportView/po_pending_report.php";
						location.href = "redirect.php";
                    }
                } else if(objs=="A"){
                        alert("Purchase Order created and automatically send for Management Decision !!");
                	    //location.href = "purchaseorder.php";
						//location.href = "../home/Dashboard.php";
						//location.href = "http://192.168.1.17/multiweld/home/Dashboard.php";
						//location.href = "../ReportView/po_pending_report.php";
						//location.href = "/multiweld/View/ReportView/po_pending_report.php";
						//window.location = "../../ReportView/po_pending_report.php";
						location.href = "redirect.php";
                }else {
                    alert("Not Saved");
                    location.href = "purchaseorder.php";
                }
            },
            error: function () {
                alert("failed..");
            }
        });
    }
    $scope.UpdatePO = function () {
        if ($scope.purchaseOrder._items.length <0) {
            alert("Please insert at least one row.");
            return;
        }
        $("#btnupdate").hide();
        $scope.purchaseOrder.pod = $("#ipod").val();
        $scope.purchaseOrder.ipovd = $("#ipovd").val();
        $scope.purchaseOrder.ms = $("#marketsegment").val();
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder.cp = $("#ic_p").val();
        var json_string = JSON.stringify($scope.purchaseOrder);
        
       
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", PODATA: json_string },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
				//alert(objs);
                if (objs > 0) {
                    var x = confirm("Saved and Do you want to go for Management Approval?");
                    //alert(x);
                    if (x == true) {
						//location.href = "management_approval_form.php?POID=" + jsondata;
                        location.href = "management_approval_form.php?POID=" + objs;
                    } else {
                         location.href = "purchaseorder.php";
                    }
                } else if(objs=="A"){
                        alert("Purchase Order updated and automatically send for Management Decision !!");
                	    location.href = "purchaseorder.php";
                } else {
                    alert("Not Saved");
                      location.href = "purchaseorder.php";
                }

            },
            error: function () {

                alert("failed..");
            }
        });
    }
    $scope.showCity = function (cityid) {
        var TYPE = "SELECT"; if (true) {
            jQuery.ajax({ url: "../../Controller/Master_Controller/City_Controller.php", type: "POST",
                data: { TYP: TYPE, TAG: "STATE", STATEID: $scope.purchaseOrder.sstate1 }, success: function (jsondata) {
                    $('#shppingcity1').empty();
                    $("#shppingcity1").append("<option value=''>Select City</option>");
                    var objs = jQuery.parseJSON(jsondata);
                    if (jsondata != "") {
                        var obj; for (var i = 0; i < objs.length; i++) {
                            var obj = objs[i];
                            $("#shppingcity1").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");
                        }
                        $("#shppingcity1").val(cityid);
                    }
                }
            });
        }
    }
    $scope.showLocation = function (locationid) {
        var TYPE = "SELECT";
        if (true) {
            jQuery.ajax({ url: "../../Controller/Master_Controller/LocationController.php", type: "POST",
                data: {
                    TYP: TYPE,
                    CITYID: $scope.purchaseOrder.scity1
                }, success: function (jsondata) {
                    $('#shppinglocation1').empty();
                    $("#shppinglocation1").append("<option value=''>Select Location</option>");
                    var objs = jQuery.parseJSON(jsondata);
                    if (jsondata != "") {
                        var obj; for (var i = 0; i < objs.length; i++) {
                            var obj = objs[i];
                            $("#shppinglocation1").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");
                        }
                        $("#shppinglocation1").val(locationid);
                    }
                }
            });
        }
    }
    $scope.putShippingAdd = function () {
        //alert("hello");
        if ($scope.purchaseOrder.addTag) {
            $scope.purchaseOrder.sadd1 = $("#ibadd1").val();
            $scope.purchaseOrder.sadd2 = $("#ibadd2").val();
            $scope.purchaseOrder.scountry1 = $("#billingcountry1").val();
            $scope.purchaseOrder.sstate1 = $("#billingstate1").val();
            $scope.purchaseOrder.scity1 = $("#bcity1").val();
            $scope.purchaseOrder.slocation1 = $("#blocation1").val();
            $scope.purchaseOrder.spincode = $("#ibpincode").val();
            $scope.purchaseOrder.sphno = $("#iphno").val();
            $scope.purchaseOrder.smobno = $("#imobno").val();
            $scope.purchaseOrder.sfax = $("#ibfax").val();
            $scope.purchaseOrder.semail = $("#ibemail").val();
            CITYID = $("#bcity1").val();
			//alert(CITYID);
            STATEID = $("#billingstate1").val();
            locationid = $("#blocation1").val();
            //$scope.showCity($scope.purchaseOrder.sstate1);
            var TYPE = "SELECT"; if (true) {
                jQuery.ajax({ url: "../../Controller/Master_Controller/City_Controller.php", type: "POST",
                    data: { TYP: TYPE, TAG: "STATE", STATEID: STATEID, CITYID : CITYID}, success: function (jsondata) {
                        $('#shppingcity1').empty();
                        $("#shppingcity1").append("<option value=''>Select City</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj; for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#shppingcity1").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");
                            }
                            $("#shppingcity1").val(CITYID);
                        }
                    }
                });
            }
            var TYPE = "SELECT";
            if (true) {
                jQuery.ajax({ url: "../../Controller/Master_Controller/LocationController.php", type: "POST",
                    data: {
                        TYP: TYPE,
                        CITYID: CITYID
                    }, success: function (jsondata) {
                        $('#shppinglocation1').empty();
                        $("#shppinglocation1").append("<option value=''>Select Location</option>");
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            var obj; for (var i = 0; i < objs.length; i++) {
                                var obj = objs[i];
                                $("#shppinglocation1").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");
                            }
                            $("#shppinglocation1").val(locationid);
                        }
                    }
                });
            }
        } else {
            $scope.purchaseOrder.sadd1 = "";
            $scope.purchaseOrder.sadd2 = "";
            $scope.purchaseOrder.scountry1 = "";
            $scope.purchaseOrder.sstate1 = "";
            $scope.purchaseOrder.scity1 = "";
            $scope.purchaseOrder.slocation1 = "";
            $scope.purchaseOrder.spincode = "";
            $scope.purchaseOrder.sphno = "";
            $scope.purchaseOrder.smobno = "";
            $scope.purchaseOrder.sfax = "";
            $scope.purchaseOrder.semail = "";
        }
    }
    $scope.getBillingAddress = function () { 
        if ($scope.purchaseOrder.bn > -1) {
            if (true) {
                jQuery.ajax({
                    url: "../../Controller/Business_Action_Controller/po_Controller.php",
                    type: "POST",
                    data: { TYP: "BILLINGADD", BUYERID: $scope.purchaseOrder.bn },
                    //cache: false,
                    success: function (jsondata) {
                       
                        $scope.$apply(function () {
                            var objs = jQuery.parseJSON(jsondata);
                            if (jsondata != "") {
                                $scope.purchaseOrder.cp = objs[0]._credit_period;
                                $scope.purchaseOrder.badd1 = objs[0]._bill_add1;
                                $scope.purchaseOrder.badd2 = objs[0]._bill_add2;
                                $scope.purchaseOrder.bcountry1 = objs[0]._country_id;
                                $scope.purchaseOrder.bcountryName1 = "INDIA";
                                $scope.purchaseOrder.bstate1 = objs[0]._state_id;
                                $scope.purchaseOrder.bstateName1 = objs[0]._state_name;
                                $scope.purchaseOrder.bcity1 = objs[0]._city_id;
                                $scope.purchaseOrder.bcityName1 = objs[0]._city_name;
                                $scope.purchaseOrder.blocation1 = objs[0]._location_id;
                                $scope.purchaseOrder.blocationName1 = objs[0]._location_name;
                                $scope.purchaseOrder.bpincode = objs[0]._pincode;
                                $scope.purchaseOrder.bphno = objs[0]._phone;
                                $scope.purchaseOrder.bmobno = objs[0]._mobile;
                                $scope.purchaseOrder.bfax = objs[0]._fax;
                                $scope.purchaseOrder.bemail = objs[0]._email;
				//$scope.purchaseOrder.bemailId = objs[0]._email;
                            }
                            else {
                                $scope.purchaseOrder.badd1 = "";
                                $scope.purchaseOrder.badd2 = "";
                                $scope.purchaseOrder.bcountry1 = "";
                                $scope.purchaseOrder.bcountryName1 = "";
                                $scope.purchaseOrder.bstate1 = "";
                                $scope.purchaseOrder.bstateName1 = "";
                                $scope.purchaseOrder.bcity1 = "";
                                $scope.purchaseOrder.bcityName1 = "";
                                $scope.purchaseOrder.blocation1 = "";
                                $scope.purchaseOrder.blocationName1 = "";
                                $scope.purchaseOrder.bpincode = "";
                                $scope.purchaseOrder.bphno = "";
                                $scope.purchaseOrder.bmobno = "";
                                $scope.purchaseOrder.bfax = "";
                                $scope.purchaseOrder.bemail = "";
                            }
                        });

                    }
                });
            }
        }
    }
    $scope.showPOPrincipal = function () {
      //alert($scope.purchaseOrder.po_quotNo);
      if($scope.purchaseOrder.po_quotNo!=undefined){
        //alert("719 showPOPrincipal "+$scope.purchaseOrder.po_quotNo);
        var TYPE = "POPRINCIPAL";
        //$scope.purchaseOrder.po_quotNo=$("#iquotNo").val();
        if (true) {
            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                data: {
                    TYP: TYPE,
                    QUOTNO:$("#iquotNo").val()
                },
                success: function (jsondata) 
                {
                    //alert(jsondata);
                   $('#iprincipalId').empty();
                    $("#iprincipalId").append("<option value='0'>Select Principal</option>");
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    if (jsondata != "") {
                        $("#iden_mark").val("");
                        $("#item_desc").val("");
                        $scope.purchaseOrder.po_buyeritemcode = "";
                        $scope.purchaseOrder.po_qty = "";
                        $("#unitId").val("");
                        $("#iunit").val("");
                        $("#iprice").val("");                        
                        $("#idiscount").val("");//$scope.purchaseOrder.po_discount=""; 
                        $scope.purchaseOrder.po_ed_applicability="";
                        $("#isalestax").val("0");
                        //$scope.purchaseOrder.po_saleTax="0";                        
                        if ($scope.purchaseOrder.pot != "R") {
                     	  $("#ideldate").val("");
                     	}   
                        //$scope.purchaseOrder.po_totVal="";
                        $scope.purchaseOrder.taxable_amt="";
                        
                        $("#ioprice").val("");                        
                        $("#iodiscount").val("");//$scope.purchaseOrder.po_discount="";                         
                        $scope.purchaseOrder.po_price_category="";
                        //$scope.purchaseOrder.po_discount_category="";                        
                        var obj; 
                        for (var i = 0; i < objs.length; i++) {
                            var obj = objs[i];
                            if(objs.length==1){
 								$("#iprincipalId").append("<option value=\"" + obj.po_principalId + "\" selected> " + obj.po_principalName + "</option>");
							}else{
								$("#iprincipalId").append("<option value=\"" + obj.po_principalId + "\">" + obj.po_principalName + "</option>");			
								
							}
                     		}
                             $scope.getQuotDiscount();
                             $scope.showPOCodePartNo();
                    }
               }
            });
        }
     }
    }
    $scope.getQuotDiscount= function(){
    //alert($scope.purchaseOrder.po_quotNo);
    if($scope.purchaseOrder.po_quotNo!=undefined){
    //alert("ANKUR");
    var TYPE = "GET_QUOTDISCOUNT";
    var QUOTNO;
    //$scope.purchaseOrder.po_quotNo=$("#iquotNo").val();
    if(true) {
          jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                data: {
                    TYP: TYPE,
			        QUOTNO:$("#iquotNo").val()			
                },
                success: function (jsondata) {
                    $scope.$apply(function () {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        //alert(objs.length);
                        if (parseInt(objs.length)>0) {
                            //alert(jsondata);
                            $scope.purchaseOrder.po_discount = objs[0].po_discount;
                            $scope.purchaseOrder.po_odiscount = objs[0].po_discount;
                            $scope.purchaseOrder.po_discount_category = "N";                        
                        }
                    });
                }
            });
     }
    }
    }
    $scope.getDiscount = function () {
		var BUYERID = $("#buyerid").val();
		if(BUYERID == 0 || BUYERID ==''){
			alert("Please Select Buyer First");		
			$("#autocomplete-ajax-buyer").focus();	return;		
		}
        var TYPE = "GET_PRINCIPAL_DISCOUNT_DETAILS";
        if (true) {
            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                data: {
                    TYP: TYPE,
                    BUYERID: $("#buyerid").val(),
                    PRINCIPALID: $scope.purchaseOrder.po_principalId
                },
                success: function (jsondata) {
                    $scope.$apply(function () {
                        //alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                        	if(objs[0] != 0)
                        	{
							   $scope.purchaseOrder.po_discount = objs[0].po_discount;
                               $scope.purchaseOrder.po_odiscount = objs[0].po_odiscount;	
							}
                            else
                            {
								alert("No Discount on this buyer");
							}
                        }
                    });
                }
            });
        }
    }
    $scope.getLanding_Price = function () {
		$scope.purchaseOrder.poVal = 0;
		var k = 0;
        var totalTaxableValue = 0;
        while (k < $scope.purchaseOrder._items.length) {
            //$scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_totVal"]) + parseFloat($scope.purchaseOrder.poVal);
			$scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_finVal"]) + parseFloat($scope.purchaseOrder.poVal);
			totalTaxableValue = parseFloat(totalTaxableValue) + parseFloat($scope.purchaseOrder._items[k]["po_taxable_amt"]);
			totalTaxableValue = parseFloat(totalTaxableValue).toFixed(2);
            k++;
        }
		var PFChargeAmt =0;
		var IncidentalChargeAmt =0;
		var InsuranceChargeAmt =0;
		var OtherChargeAmt =0;
		var FreightChargeAmt =0;
		var pf_chrg = 0, incident = 0, insurance = 0, other = 0, freight = 0;
		if (!isNaN($scope.purchaseOrder.pf_chrg) && $scope.purchaseOrder.pf_chrg != "" && $scope.purchaseOrder.pf_chrg != null) {
            pf_chrg = parseFloat($scope.purchaseOrder.pf_chrg);
        }
        PFChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(pf_chrg))/100);
		PFChargeAmt = parseFloat(PFChargeAmt).toFixed(2);
        if (!isNaN($scope.purchaseOrder.inci_chrg) && $scope.purchaseOrder.inci_chrg != "" && $scope.purchaseOrder.inci_chrg != null) {
            incident = parseFloat($scope.purchaseOrder.inci_chrg);
        }
        IncidentalChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(incident))/100);
		IncidentalChargeAmt = parseFloat(IncidentalChargeAmt).toFixed(2);
        if (!isNaN($scope.purchaseOrder.ins_charge) && $scope.purchaseOrder.ins_charge != "" && $scope.purchaseOrder.ins_charge != null) {
            insurance = parseFloat($scope.purchaseOrder.ins_charge);
        }
        InsuranceChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(insurance))/100);
		InsuranceChargeAmt = parseFloat(InsuranceChargeAmt).toFixed(2);
        if (!isNaN($scope.purchaseOrder.othc_charge) && $scope.purchaseOrder.othc_charge != "" && $scope.purchaseOrder.othc_charge != null) {
            other = parseFloat($scope.purchaseOrder.othc_charge);
        }
        OtherChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(other))/100);
		OtherChargeAmt = parseFloat(OtherChargeAmt).toFixed(2);
		
		if ($scope.purchaseOrder.frgt == "P") {
			if (!isNaN($scope.purchaseOrder.frgtp) && $scope.purchaseOrder.frgtp != "" && $scope.purchaseOrder.frgtp != null) {
			freight = parseFloat($scope.purchaseOrder.frgtp);
		}
			FreightChargeAmt = ((parseFloat(totalTaxableValue) * parseFloat(freight))/100);
			FreightChargeAmt = parseFloat(FreightChargeAmt).toFixed(2);
		} else if ($scope.purchaseOrder.frgt == "A") {
			FreightChargeAmt = parseFloat($scope.purchaseOrder.frgta).toFixed(2);
		} else {
		  FreightChargeAmt = 0;
		}

         $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder.poVal) + parseFloat(PFChargeAmt) + parseFloat(IncidentalChargeAmt) + parseFloat(InsuranceChargeAmt) + parseFloat(OtherChargeAmt) + parseFloat(FreightChargeAmt);
	}
    $scope.showPOCodePartNo = function () {
        //alert($scope.purchaseOrder.po_principalId);
		//alert($("#buyerid").val());
        var PID,QNO,TYPE;
		//var shipped_state_id = $scope.purchaseOrder.sstate1;
		var shipped_state_id = $("#buyerid").val();
		//if((shipped_state_id == '')||(shipped_state_id == undefined))
		if(false)
		{
			alert('Invalid Shipping Address State');
		}
		else
		{
			//alert(shipped_state_id);
			//$("#iquotNo").val("0");
			$("#ipname").val($("#iprincipalId option:selected").text());
		   // alert( $("#ipname").val());
			$scope.purchaseOrder.po_codePartNo="";
			$scope.purchaseOrder.cPartNo="";
			$("#iden_mark").val("");
			$("#item_desc").val("");
			$scope.purchaseOrder.po_buyeritemcode = "";
			$scope.purchaseOrder.po_qty = "";
			$("#unitId").val("");
			$("#iunit").val("");
			$("#iprice").val("");                        
			$scope.purchaseOrder.po_discount=""; 
			$("#idiscount").val("");
			$scope.purchaseOrder.po_ed_applicability="";
			$("#isalestax").val("0");
			//$scope.purchaseOrder.po_saleTax="0";                        
			if ($scope.purchaseOrder.pot != "R") {
			  $("#ideldate").val("");
			}   
			//$scope.purchaseOrder.po_totVal="";
			$scope.purchaseOrder.taxable_amt="";
			$scope.purchaseOrder.po_price_category="";
			QNO=$("#iquotNo").val();//$scope.purchaseOrder.po_quotNo;
		  //  alert(QNO);
			PID=$scope.purchaseOrder.po_principalId;			
			TYPE = "POCODEPARTNO";        
			if(QNO==undefined){
			   QNO="";
			}
			if(PID==undefined){
			   PID="";
			}
			//alert(PID+"*826*"+QNO);
			if (PID != "" || QNO != "") {
	 
				if (true) {
					jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
						data: {
							TYP: TYPE,
							PRINCIPALID: PID,
							QUOTNO:QNO
						},
						success: function (jsondata) {
							//alert(jsondata);
							$('#icodePartNo').empty();
							//$("#icodePartNo").append("<option value=''></option>");
							var objs = jQuery.parseJSON(jsondata);
							CodePartNoList = {};
							if (jsondata != "") {
								var obj;
								for(var items in objs)
								{
									CodePartNoList[items] = objs[items] ;
									
								} 
								//for (var i = 0; i < objs.length; i++) {
									//var obj = objs[i]; //alert(obj.po_itemID);
									//CodePartNoList[obj.po_itemId] = obj.po_codePartNo;
									//$("#icodePartNo").append("<option value=\"" + obj.po_itemId + "\">" + obj.po_codePartNo + "</option>");
								//}
								//$("#icodePartNo").val(0);
								CallToCodePartNo(CodePartNoList, shipped_state_id);
							}
						}
					});
				}
				$scope.purchaseOrder.pname = $("#iprincipalId option:selected").text();
			  
			}
		}
    }
    $scope.cashDisc = function () {
        //alert($scope.purchaseOrder.cd);
        if ($scope.purchaseOrder.cd) {
            $("#dcdt").show();
            $("#icdt").show();
        } else {
            $scope.purchaseOrder.cdt = "";
            $("#dcdt").hide();
            $("#icdt").hide();
        }
    }
    $scope.showQtyDelivery = function () {
        if ($scope.purchaseOrder.pot == "R") {
            $scope.purchaseOrder.po_qty = "0";
           // $scope.purchaseOrder.po_totVal = "0";
            $scope.purchaseOrder.taxable_amt = "0";
            $("#dbd").hide();
            $("#dbd1").show();
        } else {
            $scope.purchaseOrder.po_qty = "";
            //$scope.purchaseOrder.po_totVal = "";
            $scope.purchaseOrder.taxable_amt = "";
            $("#dbd1").hide();
            $("#dbd").show()
        }

    }
    $scope.getFreight = function () {

        $scope.purchaseOrder.frgt = $scope.purchaseOrder.frgt1;
        $("#ifrgp").empty();
        $("#ifrga").empty();
        if ($scope.purchaseOrder.frgt == "P") {
            $("#dfrgp").show();
            $("#ifrgp").show();
            $("#dfrga").hide();
        } else if ($scope.purchaseOrder.frgt == "A") {
            $("#dfrga").show();
            $("#ifrga").show();
            $("#dfrgp").hide();
        } else {
            $("#dfrga").hide();
            $("#dfrgp").hide();
        }


    }
    $scope.checkDiscount= function (){
	   var p = $("#idiscount").val();
       var p1 = $("#iodiscount").val();
       if(p1==""){
	   	 p1="0";
	   }
      if(p==""){
	   	 p="0";
	   } 
       //alert("checkDiscount | "+p+"|"+p1);
       if($scope.purchaseOrder.po_quotNo!=undefined){
	   	  //alert("d1");
	   	  if(isNaN(p) || $scope.purchaseOrder.po_discount==undefined){
	   	  	//alert("d2");
	   	  }else{
             if(parseFloat(p)>parseFloat(p1)){
		        //alert("d3");
		        $scope.purchaseOrder.po_discount_category="M";
		        //alert("d4");
		     }else{
		  	    //alert("d5");
		  	    $scope.purchaseOrder.po_discount_category="N";
		        //alert("d6");
		     }
	      }
	   }	
	}
    $scope.checkPrice = function () {
       var p = $("#iprice").val();
       var p1 = $("#ioprice").val();
       //alert("checkprice");
       if($scope.purchaseOrder.po_quotNo!=undefined){
	   	  //alert("p1");
	   	  if(isNaN(p) || $scope.purchaseOrder.po_price==undefined){
	   	  	//alert(p+"|"+p1);
	   	  }else{
             if(parseFloat(p1)>parseFloat(p)){
		        //alert("p3");
		        $scope.purchaseOrder.po_price_category="S";
		        //alert("p4");
		     }else{
		  	    //alert("p5");
		  	    $scope.purchaseOrder.po_price_category="N";
		        //alert("p6");
		     }
	      }
	      //alert($scope.purchaseOrder.po_price);
	      //alert($scope.purchaseOrder.po_price_category);
	   }
    }
    $scope.calculateValue = function () {
        var q = $scope.purchaseOrder.po_qty;
        var p = $("#iprice").val();//$scope.purchaseOrder.po_price;
        var d = $("#idiscount").val();
        //alert(isNaN(q)+"|"+q);
        //alert(isNaN(p)+"|"+$scope.purchaseOrder.po_price);
        if(isNaN(q) && $scope.purchaseOrder.po_qty!="undefined"){
			alert("Insert Numeric value.. ");
			$scope.purchaseOrder.po_qty="";
		}else if(isNaN(p) && $scope.purchaseOrder.po_price!="undefined"){
			alert("Insert Numeric value... ");
			$scope.purchaseOrder.po_price="";			
		}else if(isNaN(d) && $scope.purchaseOrder.po_discount!="undefined"){
		    alert("Insert Numeric value.... ");
			$scope.purchaseOrder.po_discount="";
		}else {
			if(q == "") {
				q = 0;
			}
            q = parseFloat(q);
            if(p == "") {
				p = 0;
			}
			
            p = parseFloat(p);
            //alert(d);
            if(d!=""){
		       d = parseFloat(d);
              // $scope.purchaseOrder.po_totVal = parseFloat(((q * p) * (100 - d)) / 100).toFixed(2);
              $scope.purchaseOrder.po_taxable_amt = parseFloat(((100 - d)/100) * (p*q)).toFixed(2);
			}else{
				//$scope.purchaseOrder.po_totVal =parseFloat(q * p).toFixed(2);
				$scope.purchaseOrder.po_taxable_amt =parseFloat(((100 - d)/100) * (p*q)).toFixed(2);
			}
        }
        //$scope.calculatePoValue();
        if($("#cgst_rate").val() == ""){
			var cgst_rate = 0;
		} else {
			var cgst_rate = parseFloat($("#cgst_rate").val());
		}
		
		if($("#sgst_rate").val() == ""){
			var sgst_rate = 0;
		} else {
			var sgst_rate = parseFloat($("#sgst_rate").val());
		}
		
		if($("#igst_rate").val() == ""){
			var igst_rate = 0;
		} else {
			var igst_rate = parseFloat($("#igst_rate").val());
		}
		
		
		
		$scope.purchaseOrder.po_cgst_amt = parseFloat(($scope.purchaseOrder.po_taxable_amt * cgst_rate)/100 ).toFixed(2);
		$scope.purchaseOrder.po_sgst_amt = parseFloat(($scope.purchaseOrder.po_taxable_amt * sgst_rate)/100 ).toFixed(2);
		$scope.purchaseOrder.po_igst_amt = parseFloat(($scope.purchaseOrder.po_taxable_amt * igst_rate)/100 ).toFixed(2);
		
		var final_value = parseFloat($scope.purchaseOrder.po_taxable_amt) + parseFloat($scope.purchaseOrder.po_cgst_amt) + parseFloat($scope.purchaseOrder.po_sgst_amt) + parseFloat($scope.purchaseOrder.po_igst_amt);
		
		$scope.purchaseOrder.po_finVal = parseFloat(final_value).toFixed(2);
    }

    $scope.validatePO = function () {
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder.poid=$("#ipoId").val();
        if ($scope.purchaseOrder.pon != "") {
            var TYPE = "VPO";
            if (true) { 
                jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                    data: {
                        TYP: TYPE,
                        BUYERID: $scope.purchaseOrder.bn, PONO: $scope.purchaseOrder.pon, POID:$scope.purchaseOrder.poid
                    },
                    success: function (jsondata) { 
                        var objs = jQuery.parseJSON(jsondata);
                        var tot = parseInt(objs);
			
                        if (tot > 0) {
                            alert("PO No. already exist");
                            document.getElementById("ipo_no").value = "";
                            $scope.purchaseOrder.pon = "";
                        }
                    }
                });
            }
        }
    }
    $scope.checkBuyer = function () {
        if ($scope.quotation.isbuyer == 1) {
            $("#new_buyer").show();
        }
        else if ($scope.quotation.isbuyer == 2) {
            $("#new_buyer").hide();
            $scope.quotation._buyer_id = 0;
        }
        else if ($scope.quotation.isbuyer == 0) {
            $("#new_buyer").hide();
            $scope.quotation._buyer_id = 0;
        }
    }
    
    $scope.init = function (number) { 
        if (number > 0) {
            //alert(number);
         
            $("#btnsave").hide();
            $("#fbt").show();
            $("#ib2").show();
            $("#ib1").hide();
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/po_Controller.php",
                type: "POST",
                data: {
                    TYP: "MA_FILL",
                    PO_NUMBER: number,
					po_ed_applicability : $("#iedapp").val()
                },
                success: function (jsondata) {
                	//alert(jsondata);
					//document.write(jsondata);
                	
                    $scope.$apply(function () {
                    var objs = jQuery.parseJSON(jsondata);
		    //$scope.purchaseOrder.ms=2;
                    ActionOnBuyer(objs[0].bn_name,objs[0].buyerid);                       
                    if(objs[0].oig_status>0)
                    {
							$("#btnupdate").hide();
				    }
						if(objs[0]._approval_status=='R')
						{
						   $('#ipo_no').attr('readonly','true');	
						}
										
                        $scope.purchaseOrder = objs[0];
			
                        if (objs[0].cd == "Y") {
                            $scope.purchaseOrder.cd = true;
                            $("#dcdt").show();
                            $("#icdt").show();
                        }else{
							$scope.purchaseOrder.cdt = "";
                            $("#dcdt").hide();
                            $("#icdt").hide();
						}
                        if(objs[0].pot == "N"){
							$("#dbd1").hide();
                            $("#dbd").show()
						}else{
							$("#dbd").hide();
                            $("#dbd1").show()
						}
                        if (objs[0].addTag == "Y") {
                            $scope.purchaseOrder.addTag = true;
                        }
                       // $scope.purchaseOrder.poVal = 0;
                        if (objs[0].frgt == "P") {
                            $("#dfrgp").show();
                            $("#dfrga").hide();
                            $("#ifrga").hide();
                            $("#ifrgp").show();
                        } else if (objs[0].frgt == "A") {
                            $("#dfrga").show();
                            $("#dfrgp").hide();
                            $("#ifrga").show();
                            $("#ifrgp").hide();
                        } else {
                            $("#dfrga").hide();
                            $("#dfrgp").hide();
                            $("#ifrga").hide();
                            $("#ifrgp").hide();
                        }
                       
			if (objs[0].po_hold_state == "H") {
                        	 $scope.purchaseOrder.po_hold_state = true;
				$("#hold_reason").show();
                        }
                      
			
                        //~ var k = 0;
                        //~ while (k < $scope.purchaseOrder._items.length) {
                            //~ $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["taxable_amt"]) + parseFloat($scope.purchaseOrder.poVal);
                            //~ k++;
                        //~ }
                        STATEID = objs[0].sstate1;
                        CITYID = objs[0].scity1;
                        LOCATIONID = objs[0].slocation1;
                        $scope.purchaseOrder._items = objs[0]._items;

                        var TYPE = "BILLINGADD";
                        if (true) {
                            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                                data: {
                                    TYP: TYPE,
                                    BUYERID: $scope.purchaseOrder.bn
                                },
                                success: function (jsondata) {
                                   // alert(jsondata);
                                    var objs = jQuery.parseJSON(jsondata);
                                    //alert(objs[0]._bill_add1);
                                    $scope.$apply(function () {
                                        $scope.purchaseOrder.badd1 = objs[0]._bill_add1;
                                        $scope.purchaseOrder.badd2 = objs[0]._bill_add2;
                                        $scope.purchaseOrder.bcountry1 = objs[0]._country_id;
                                        $scope.purchaseOrder.bcountryName1 = "INDIA";
                                        $scope.purchaseOrder.bstate1 = objs[0]._state_id;
                                        $scope.purchaseOrder.bstateName1 = objs[0]._state_name;
                                        $scope.purchaseOrder.bcity1 = objs[0]._city_id;
                                        $scope.purchaseOrder.bcityName1 = objs[0]._city_name;
                                        $scope.purchaseOrder.blocation1 = objs[0]._location_id;
                                        $scope.purchaseOrder.blocationName1 = objs[0]._location_name;
                                        $scope.purchaseOrder.bpincode = objs[0]._pincode;
                                        $scope.purchaseOrder.bphno = objs[0]._phone;
                                        $scope.purchaseOrder.bmobno = objs[0]._mobile;
                                        $scope.purchaseOrder.bfax = objs[0]._fax;
                                        $scope.purchaseOrder.bemail = objs[0]._email;
					//$scope.purchaseOrder.bemailId = objs[0]._email;
                                    });
                                }
                            });
                        }
                        var TYPE = "SELECT";
                        if (true) {
                            jQuery.ajax({ url: "../../Controller/Master_Controller/City_Controller.php", type: "POST",
                                data: { TYP: TYPE, TAG: "STATE", STATEID: STATEID,CITYID:CITYID}, success: function (jsondata) {
                                    $('#shppingcity1').empty();
                                    $("#shppingcity1").append("<option value=''>Select City</option>");
                                    var objs = jQuery.parseJSON(jsondata);
                                    if (jsondata != "") {
                                        var obj; for (var i = 0; i < objs.length; i++) {
                                            var obj = objs[i];
                                            $("#shppingcity1").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");
                                        }
                                        $("#shppingcity1").val(CITYID);
                                    }
                                }
                            });
                        }

                        var TYPE = "SELECT";
                        if (true) {
                            jQuery.ajax({ url: "../../Controller/Master_Controller/LocationController.php", type: "POST",
                                data: {
                                    TYP: TYPE,
                                    CITYID: CITYID
                                }, success: function (jsondata) {
                                    $('#shppinglocation1').empty();
                                    $("#shppinglocation1").append("<option value=''>Select Location</option>");
                                    var objs = jQuery.parseJSON(jsondata);
                                    if (jsondata != "") {
                                        var obj; for (var i = 0; i < objs.length; i++) {
                                            var obj = objs[i];
                                            $("#shppinglocation1").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");
                                        }
                                        $("#shppinglocation1").val(LOCATIONID);
                                    }
                                }
                            });
                        }
                      
                    }); 
                }

            });
		
        }else {
        	$("#btnupdate").hide();
            $scope.purchaseOrder._items.splice($scope.purchaseOrder._items.indexOf(0), 1);
            $("#ib1").show();
            $("#ib2").hide();
            $("#icdt").hide();
            $("#ifrga").hide();
            $("#ifrgp").hide();
            $("#fbt").hide();
           
        }

    }
    $scope.getText1 = function () {
        //alert(prm);
        //alert($("#icPartNo option:selected").text());
        $scope.purchaseOrder.cPartNo = $("#icodePartNo option:selected").text();
    }
    $scope.getText2 = function () {
        //alert(prm);
        $scope.purchaseOrder.eda1 = $("#iedapp option:selected").text();
    }
    $scope.getText3 = function () {
           
		   if($scope.purchaseOrder.po_saleTax!="0"){
		   	  //alert($scope.purchaseOrder.po_saleTax);
		   	  $scope.purchaseOrder.sTax = $("#isalestax option:selected").text();
		   }

    }
    $scope.Goto = function (id) {
        location.href = "management_approval_form.php?POID=" + id;
    }
	 $scope.GotoCancel = function (id) {
        location.href = "../ReportView/po_pending_report.php?repType=pending";
    }
	
 $scope.poHoldReason = function () {           
		if($scope.purchaseOrder.po_hold_state == true){
			$("#hold_reason").show();
		}else{
			$("#hold_reason").hide();
		}

    }
    
    $scope.SearchQuotation = function () {
        var number = $scope.search_quotation._quotation_no;
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "SELECT", POID: number },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
                $scope.quotation = objs[0]; //angular.fromJson(jsondata);
                //angular.copy($scope.quotation, objs[0]);
            }
        });
    }

	
});



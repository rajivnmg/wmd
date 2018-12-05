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

//////////////////////////////// change due to page loading performance on 23-11-2015 by Codefire

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
     if (value != "" && data!="") {
        $("#buyerid").val(data);
        jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
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
        
           jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
            data: {
                TYP: "SALESTAX",
                BUYERID: data
            },

            success: function (jsondata) {
               // alert(jsondata);
                $('#bisalestax').empty();
                $("#bisalestax").append("<option value='0'>Select Tax</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj; for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        $("#bisalestax").append("<option value=\"" + obj.sTax + "\">" + obj.po_saleTax + "</option>");
                    }
                    //$("#isalestax").val(0);
                }
            }
        });    
        
        
        
        jQuery.ajax({
            url: "../../Controller/Business_Action_Controller/Bundle_Controller.php",
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
					$("#bemailId").val(objs[0]._email);
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
function CallToCodePartNo(CodePartNoList) {
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
function ActionOnCodePartNo(value,data){
	if (value != "" && data > 0) {
            $("#icodePartNo").val(data);
          //  alert($("#icodePartNo").val()+"|"+$("#buyerid").val()+"|"+$("#bprincipalId").val());
            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
                data: {
                    TYP: "LOADITEM",
                    BUYERID: $("#buyerid").val(),PID:$("#bprincipalId").val(),ITEMID:$("#icodePartNo").val()},
                    success: function (jsondata) {
                          
                             var objs1 = jQuery.parseJSON(jsondata);
							 
                             var objs = objs1[0];
                             if (jsondata != "") {
                                 $("#iden_mark").val(objs.Item_Identification_Mark); 
                                 $("#item_desc").val(objs.item_desc); 
                                 $("#iunit").val(objs.po_unit);
                                 $("#unitId").val(objs.unit_id);  
                                 $("#iprice").val(objs1.price);
                                 $("#ioprice").val(objs.po_price);									
							     $("#ipo_price_category").val("N");
                             }
                    }
            });
     }
}
function NoneCodePartNo(){
	
}
var URL = "../../Controller/Business_Action_Controller/Bundle_Controller.php";
var method = "POST";
var purchaseorder_app = angular.module('bundle_app', []).directive('animate', function () {
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
purchaseorder_app.controller('bundle_Controller', function ($scope) {
    $scope.purchaseOrder =  {number: 1, validity: true};
    var sample_po = { _items: [{ bpod_Id: 0, eda1: '', po_quotNo: '', pname: '', 
    po_principalId: '', cPartNo: '', po_codePartNo: '', iden_mark: '', item_desc: '', 
    po_buyeritemcode: '', po_qty: '', po_unitid: '', po_unit: '', po_price: '', po_discount: '',
     po_ed_applicability: '', sTax: '', po_saleTax: '', po_deliverybydate: '', po_totVal: '',
     po_odiscount:'', po_oprice: '',po_price_category:'',po_discount_category:''}] };
  
	$scope.purchaseOrder = sample_po;
	
	// bundles array contain bundle 
	var sample_bundle = { _bundles: [{ bpod_Id: 0, items : '' ,bglAcc: '', bitem_desc: '', bpo_qty: '', 
    bpo_unitid: '', bpo_unit: '', bpo_price: '', bpo_discount: '', bpo_saleTax: '', 
    bpo_totVal: ''}] }; 
    //$scope.purchaseOrder = sample_bundle;
	// add bundle in PO
	$scope.purchaseOrder.bundles = [];
	$scope.addBundle = function () { 
		//alert($scope.purchaseOrder.bundles.length);
		for(var i = 0; i <= $scope.purchaseOrder.bundles.length; i++){
			//alert($scope.purchaseOrder._items.length);
			if ($scope.purchaseOrder._items.length <= 0) {
				alert("Atleast one item should be added in a bundle item grid before Add.");
				return;
			}
		}	
		 
		var isExist="B";
            
        $scope.purchaseOrder.bglAcc = $("#bglAcc").val();
        $scope.purchaseOrder.bitem_desc = $("#bitem_desc").val();		
        $scope.purchaseOrder.bpo_qty = $("#biqty").val();		
        $scope.purchaseOrder.bpo_unitid = $("#unitId").val();
        $scope.purchaseOrder.bpo_unit = $("#biunit").val();
        $scope.purchaseOrder.bpo_price = $("#biprice").val();
        $scope.purchaseOrder.bpo_discount = $("#bidiscount").val();
		$scope.purchaseOrder.bpo_saleTax = $("#bisalestax").val();
		$scope.purchaseOrder.bpo_totVal = $("#bitotVal").val();	
		
		 // alert($scope.purchaseOrder.bglAcc);
        if($scope.purchaseOrder.bglAcc==undefined || $scope.purchaseOrder.bglAcc ==""){
		    document.getElementById("bglAcc").style.backgroundColor = "yellow";
			document.getElementById("bglAcc").value="";
            isExist="A";
		} 
        if($scope.purchaseOrder.bitem_desc==undefined || $scope.purchaseOrder.bitem_desc == ""){
			document.getElementById("bitem_desc").style.backgroundColor = "yellow";
			document.getElementById("bitem_desc").value="";
            isExist="A.";
		}
        if($scope.purchaseOrder.bpo_qty==undefined || $scope.purchaseOrder.bpo_qty ==""){
			document.getElementById("biqty").style.backgroundColor = "yellow";
			document.getElementById("biqty").value="";
            isExist="A..";
		} 
		 if($scope.purchaseOrder.bpo_unitid==undefined || $scope.purchaseOrder.bpo_unitid ==""){
			document.getElementById("unitId").style.backgroundColor = "yellow";
			document.getElementById("unitId").value="";
            isExist="A..";
		} 
		 if($scope.purchaseOrder.bpo_unit==undefined || $scope.purchaseOrder.bpo_unit ==""){
			document.getElementById("biunit").style.backgroundColor = "yellow";
			document.getElementById("biunit").value="";
            isExist="A..";
		} 
		 if($scope.purchaseOrder.bpo_price==undefined || $scope.purchaseOrder.bpo_price ==""){
			document.getElementById("biprice").style.backgroundColor = "yellow";
			document.getElementById("biprice").value="";
            isExist="A..";
		} 
		 if($scope.purchaseOrder.bpo_saleTax==undefined || $scope.purchaseOrder.bpo_saleTax ==""){
			document.getElementById("bisalestax").style.backgroundColor = "yellow";
			document.getElementById("bisalestax").value="";
            isExist="A..";
		}  
     
        if(isExist!="B"){
			return;
		}else{
		$scope.purchaseOrder.bundles.push({ibglAcc : $scope.purchaseOrder.bglAcc,ibitem_desc : $scope.purchaseOrder.bitem_desc,ibpo_qty : $scope.purchaseOrder.bpo_qty,ibpo_unitid : $scope.purchaseOrder.bpo_unitid,ibpo_unit : $scope.purchaseOrder.bpo_unit,ibpo_price : $scope.purchaseOrder.bpo_price,ibpo_discount : $scope.purchaseOrder.bpo_discount,ibpo_saleTax : $scope.purchaseOrder.bpo_saleTax,ibpo_totVal : $scope.purchaseOrder.bpo_totVal,items:$scope.purchaseOrder._items});
		
		/*  for(var i = 0; i <= $scope.purchaseOrder.bundles.length; ++ i)
		alert($scope.purchaseOrder.bundles[i].items[0].length);  */
		
		 $scope.purchaseOrder.poVal = 0;
        var k = 0;
        var sum=0;
        while (k < $scope.purchaseOrder.bundles.length) {
            sum= parseFloat($scope.purchaseOrder.bundles[k]["ibpo_totVal"])+ parseFloat(sum);
            k++;
        }
        $scope.purchaseOrder.poVal=parseFloat(sum).toFixed(2);	
		//$scope.purchaseOrder.poVal = totalVal;
		$scope.purchaseOrder._items =[];
		$scope.purchaseOrder.bglAcc=null;
        $scope.purchaseOrder.bitem_desc=null;
        $scope.purchaseOrder.bglAcc=""; 
		$scope.purchaseOrder.bitem_desc =null;			
        $scope.purchaseOrder.bpo_qty="";
		$scope.purchaseOrder.bpo_unitid =null;	
        $scope.purchaseOrder.bpo_unit="";     
        $scope.purchaseOrder.bpo_price =null;
        $scope.purchaseOrder.bpo_discount=null;
        $scope.purchaseOrder.bpo_saleTax=null;
        $scope.purchaseOrder.bpo_totVal="";
       }
       
    }	
	
    $scope.addItem = function () { 	
        var isExist="B";
      
        //alert($("#ipo_price_category").val());
        //$scope.purchaseOrder.po_quotNo = $("#iquotNo").val();
        $scope.purchaseOrder.po_deliverybydate = $("#ideldate").val();
        $scope.purchaseOrder.po_codePartNo = $("#icodePartNo").val();		
        $scope.purchaseOrder.cPartNo = $("#autocomplete-ajax-CodePartNo").val();		
        $scope.purchaseOrder.po_unitid = $("#unitId").val();
        $scope.purchaseOrder.po_price = $("#iprice").val();
        $scope.purchaseOrder.po_oprice = $("#ioprice").val();
        $scope.purchaseOrder.pname = $("#ipname").val();
		//alert($scope.purchaseOrder.po_price);
        if($scope.purchaseOrder.po_codePartNo==undefined || $scope.purchaseOrder.po_codePartNo ==""){
		    document.getElementById("icodePartNo").style.backgroundColor = "yellow";
			document.getElementById("autocomplete-ajax-CodePartNo").style.backgroundColor = "yellow";
			document.getElementById("icodePartNo").value="";
            isExist="A";
		} 
        if($scope.purchaseOrder.po_qty==undefined || $scope.purchaseOrder.po_qty == ""){
			document.getElementById("iqty").style.backgroundColor = "yellow";
			document.getElementById("iqty").value="";
            isExist="A.";
		}
        if($scope.purchaseOrder.po_price==undefined || $scope.purchaseOrder.po_price ==""){
			document.getElementById("iprice").style.backgroundColor = "yellow";
			document.getElementById("iprice").value="";
            isExist="A..";
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
		      if(cpn_it==cpn){
			  	 if((pr_po_ed=="")||((pr_po_ed=="E" && (pr_it_ed=="I" || pr_it_ed=="N")) || (pr_po_ed=="I" && (pr_it_ed=="E" || pr_it_ed=="N")) || (pr_po_ed=="N" && (pr_it_ed=="E" || pr_it_ed=="I")))){
				 	isExist="A......";
				 }
			  }
		      j++;
		}
     
        if(isExist!="B"){
			return;
		}else{
			
			$scope.purchaseOrder._items.push({ bpod_Id: $scope.purchaseOrder.bpod_Id, 
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
			   po_oprice: $("#ioprice").val(),po_price_category:$scope.purchaseOrder.po_price_category,po_discount_category:$scope.purchaseOrder.po_discount_category});
			
		}  
          
       /*  $scope.purchaseOrder.poVal = 0;
        var k = 0;
        while (k < $scope.purchaseOrder._items.length) {
            $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_totVal"]) + parseFloat($scope.purchaseOrder.poVal);
            k++;
        }
 */
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
        $("#iden_mark").val("");
        $("#item_desc").val("");
        $("#iunit").val("");
        $("#ideldate").val("");
        $scope.purchaseOrder.po_ed_applicability="";
        //$scope.purchaseOrder.po_saleTax=$("#isalestax").val("0");
        $scope.purchaseOrder.po_deliverybydate ="";
        $scope.purchaseOrder.po_totVal=null;
        $scope.purchaseOrder.po_oprice=null;
        $scope.purchaseOrder.po_odiscount=null;
        $("#iodiscount").val("");   
        $scope.purchaseOrder.po_price_category=null;
        $scope.purchaseOrder.po_discount_category=null;
        	ActionOnBuyer(0,$("#buyerid").val());
        	$("#iquotNo").val("0");        
            $("#isalestax").val(0);
           // $("#iprincipalId").val("0");
    }
    //##################### Rajiv
    $scope.RowChangeEvent = function (item) {
        var Rowindex =  $scope.purchaseOrder._items.indexOf(item);
        var q=parseFloat($scope.purchaseOrder._items[Rowindex]['po_qty']);
        var p=parseFloat($scope.purchaseOrder._items[Rowindex]['po_price']);
        var d=parseFloat($scope.purchaseOrder._items[Rowindex]['po_discount']); 
        var total_amt=0.00;
        if ( q!= "" && p!= "") {
        	 if(d!="")
        	 {
        	 	   var tot=(((q * p) * (100 - d)) / 100); 
			 	   $scope.purchaseOrder._items[Rowindex]['po_totVal'] =parseFloat(tot).toFixed(2);  
			 }
			 else
			 {
			 	 
			 	    var tot=(q * p);
			 	   $scope.purchaseOrder._items[Rowindex]['po_totVal']=parseFloat(tot).toFixed(2);
			 }
           
        }
        $scope.purchaseOrder.poVal = 0;
        var k = 0;
        var sum=0;
        while (k < $scope.purchaseOrder._items.length) {
            sum= parseFloat($scope.purchaseOrder._items[k]["po_totVal"])+ parseFloat(sum);
            k++;
        }
        $scope.purchaseOrder.poVal=parseFloat(sum).toFixed(2);
       
    }
    //########################## end 
	
	// Remove item form bundle list before bundle add & after item add 
    $scope.removeItem = function (item) { 
        $scope.purchaseOrder._items.splice($scope.purchaseOrder._items.indexOf(item), 1);      
    }
	
	//Remove item form bundle list after bundle add
    $scope.removeBundle= function (bundle) {
        $scope.purchaseOrder.bundles.splice($scope.purchaseOrder.bundles.indexOf(bundle), 1);    
		$scope.purchaseOrder.poVal = 0;		
        var k = 0;
        while (k < $scope.purchaseOrder.bundles.length) {
            $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder.bundles[k].ibpo_totVal) + parseFloat($scope.purchaseOrder.poVal);
            k++;
        }
    }
	
 //Remove item form bundle list after bundle add 10-12--15
 $scope.removeBundleItem = function (item,index1,index2) {	
	 var k = index1;
	$scope.purchaseOrder.bundles[k].items.splice($scope.purchaseOrder.bundles[k].items.indexOf(item), 1);		
	
  } 
	 //########################## save bundle PO
    $scope.AddBundlePO = function () { 
        if ($scope.purchaseOrder.bundles.length <= 0) {
            alert("Atleast one Bundle should be added in a bundle grid before submit form.");
            return;
        }
		
        $("#btnsave").hide();
        $scope.purchaseOrder.pod = $("#ipod").val();
        $scope.purchaseOrder.ipovd = $("#ipovd").val();
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder.cp = $("#ic_p").val();
		$scope.purchaseOrder.ms = $("#marketsegment").val();
		$scope.purchaseOrder.pot = "B";
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
                //alert(objs);
                if (objs > 0) { 
                    var x = confirm("Saved and Do you want to go for Management Approval?");
                    if (x == true) {
                        location.href = "management_approval_form.php?fromPage=PO&POID=" + jsondata;
                    } else {
                        location.href = "bundle.php";
                    }
                } else if(objs=="A"){
                        alert("Bundle Purchase Order created and automatically send for Management Decision !!");
                	    location.href = "bundle.php";
                }else {
                    alert("Not Saved");
                    location.href = "bundle.php";
                }
            },
            error: function () {
                alert("failed..");
            }
        });
    }
    $scope.UpdatePO = function () {
        if ($scope.purchaseOrder.bundles.length <= 0) {
            alert("Atleast one Bundle should be added in a bundle grid before submit form.");
            return;
        }
        $("#btnupdate").hide();
        $scope.purchaseOrder.pod = $("#ipod").val();
        $scope.purchaseOrder.ipovd = $("#ipovd").val();
        $scope.purchaseOrder.ms = $("#marketsegment").val();
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder.cp = $("#ic_p").val();
        var json_string = JSON.stringify($scope.purchaseOrder);
        
       //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "UPDATE", PODATA: json_string },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                //alert(jsondata);
                if (objs > 0) {
                    var x = confirm("Saved and Do you want to go for Management Approval?");
                    //alert(x);
                    if (x == true) {
                        location.href = "management_approval_form.php?POID=" + jsondata;
                    } else {
                         location.href = "bundle.php";
                    }
                } else {
                    alert("Not Saved");
                      location.href = "bundle.php";
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
                    url: "../../Controller/Business_Action_Controller/Bundle_Controller.php",
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
								$scope.purchaseOrder.bemailId = objs[0]._email;
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

   
    $scope.getDiscount = function () {
		var BUYERID = $("#buyerid").val();
		if(BUYERID == 0 || BUYERID ==''){
			alert("Please Select Buyer First");		
			$("#autocomplete-ajax-buyer").focus();	return;		
		}
        var TYPE = "GET_PRINCIPAL_DISCOUNT_DETAILS";
        if (true) {
            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
                data: {
                    TYP: TYPE,
                    BUYERID: $("#buyerid").val(),
                    PRINCIPALID: $scope.purchaseOrder.po_principalId
                },
                success: function (jsondata) {
                    $scope.$apply(function () {
                       // alert(jsondata);
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                        	if(objs[0] != 0)
                        	{
							   $scope.purchaseOrder.bpo_discount = objs[0].po_discount;
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
    $scope.showPOCodePartNo = function () {
        //alert($scope.purchaseOrder.po_principalId);
        var PID,QNO,TYPE;
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
        $scope.purchaseOrder.po_totVal="";
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
		$("#bprincipalId").val(PID);//$scope.purchaseOrder.po_quotNo;
            if (true) {
                jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
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
                            CallToCodePartNo(CodePartNoList);
                        }
                    }
                });
            }
            $scope.purchaseOrder.pname = $("#iprincipalId option:selected").text();
          
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
        //var d = $("#idiscount").val();;
       // alert(isNaN(q)+"|"+q);
        //alert(isNaN(p)+"|"+$scope.purchaseOrder.po_price);
        if(isNaN(q) && $scope.purchaseOrder.po_qty!="undefined"){ 
			alert("Insert Numeric value.. ");
			$scope.purchaseOrder.po_qty="";
		}else if(isNaN(p) && $scope.purchaseOrder.po_price!="undefined"){
			alert("Insert Numeric value... ");
			$scope.purchaseOrder.po_price="";			
		}else {
            q = parseFloat(q);
            p = parseFloat(p);
            //alert(d);          
			$scope.purchaseOrder.po_totVal =parseFloat(q * p).toFixed(2);
			
 
        }
        //$scope.calculatePoValue();
    }
	 $scope.calculateBundleValue = function () { 
        var q = $scope.purchaseOrder.bpo_qty;	
		var p = $scope.purchaseOrder.bpo_price;	
        var d = $scope.purchaseOrder.bpo_discount;
		if(d >= 100){
			alert("DISCOUNT CAN'T BE GREATER THEN 100%");
			$scope.purchaseOrder.bpo_discount =0;
			return;
		}
		$("#bundleqty").val(q);
		$("#bundlerpice").val(p);
		$("#bundlediscount").val(d)
     /*   alert(isNaN(q)+"|"+$scope.purchaseOrder.bpo_qty);
        alert(isNaN(p)+"|"+$scope.purchaseOrder.bpo_price);
		 alert(isNaN(d)+"|"+$scope.purchaseOrder.bpo_discount); */
        if(isNaN(q) && $scope.purchaseOrder.bpo_qty!="undefined"){ 
			//alert("Insert Numeric value.. ");
			$scope.purchaseOrder.bpo_qty="";
		}else if(isNaN(p) && $scope.purchaseOrder.bpo_price!="undefined"){
			//alert("Insert Numeric value... ");
			$scope.purchaseOrder.bpo_price="";			
		}else {
            q = parseFloat(q);
            p = parseFloat(p);
            //alert(d);          
			$scope.purchaseOrder.bpo_totVal = parseFloat((q * p)-(((q * p)*d)/100)).toFixed(2);
			
 
        }
        //$scope.calculatePoValue();
    }

    $scope.validatePO = function () { 
        $scope.purchaseOrder.bn = $("#buyerid").val();
        $scope.purchaseOrder._bpoId=$("#ipoId").val();
		if($scope.purchaseOrder.bn == ""){
		return;
		}
/* 		if($scope.purchaseOrder._bpoId == ""){
		return;
		} */
        if ($scope.purchaseOrder.pon != "") {
            var TYPE = "VPO";
            if (true) {
                jQuery.ajax({ url: "../../Controller/Business_Action_Controller/po_Controller.php", type: "POST",
                    data: {
                        TYP: TYPE,
                        BUYERID: $scope.purchaseOrder.bn, PONO: $scope.purchaseOrder.pon, POID:$scope.purchaseOrder._bpoId
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
       
    $scope.init = function (number) { 
        if (number > 0) {
            //alert(number);
         
            $("#btnsave").hide();
            $("#fbt").show();
            $("#ib2").show();
            $("#ib1").hide();
            jQuery.ajax({
                url: "../../Controller/Business_Action_Controller/Bundle_Controller.php",
                type: "POST",
                data: {
                    TYP: "MA_FILL",
                    PO_NUMBER: number,
					po_ed_applicability : 'N'
                },
                success: function (jsondata) {
                	//alert(jsondata);
					//document.write(jsondata);
                	  
                    $scope.$apply(function () {
                    var objs = jQuery.parseJSON(jsondata);					
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
						}else if(objs[0].pot == "B"){
							$("#dbd").hide();
                            $("#dbd1").show()
						}else{
							$("#dbd").hide();
                            $("#dbd1").show()
						}
                        if (objs[0].addTag == "Y") {
                            $scope.purchaseOrder.addTag = true;
                        }
                        //$scope.purchaseOrder.poVal = 0;
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
                       
                       

                        /*  var k = 0;
                        while (k < $scope.purchaseOrder._items.length) {
                            $scope.purchaseOrder.poVal = parseFloat($scope.purchaseOrder._items[k]["po_totVal"]) + parseFloat($scope.purchaseOrder.poVal);
                            k++;
                        }
						 */
						var pid = objs[0].bundles[0].items[0].po_principalId;
						$scope.purchaseOrder.poVal=objs[0].poVal;
						$scope.purchaseOrder.po_principalId = pid;
						STATEID = objs[0].sstate1;
                        CITYID = objs[0].scity1;
                        LOCATIONID = objs[0].slocation1;
                        $scope.purchaseOrder._items = objs[0]._items;

                        var TYPE = "BILLINGADD";
                        if (true) {
                            jQuery.ajax({ url: "../../Controller/Business_Action_Controller/Bundle_Controller.php", type: "POST",
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
										$scope.purchaseOrder.bemailId = objs[0]._email;
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

});

purchaseorder_app.directive('isNumber', function () { 
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('purchaseOrder.bpo_discount', function (newValue, oldValue) {

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
                    scope.purchaseOrder.bpo_discount = oldValue;
                }
            });           
         
        }
    };
});


//Remove item form bundle list after bundle add
/* function removeBundleItem(data){
	$(data).parent().parent().parent().remove();
}
 */

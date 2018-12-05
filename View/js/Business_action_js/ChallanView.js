var URL = "../../Controller/Business_Action_Controller/Challan_Controller.php";
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
            //$('#selction-ajax-buyer').html('You selected: none');F
        }
    });
}

function ActionOnBuyer(value, data) {
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        //SearchByBuyer();
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
    $('.outgoing_invoice_excise_list').flexOptions({ url: URL });
    $('.outgoing_invoice_excise_list').flexReload();
}

// commented due to page loading performance on 25-11-2015 by Codefire
/* $( document ).ready(function() {
    jQuery.ajax({
    url: "../../Controller/Master_Controller/Buyer_Controller.php",
    type: "post",
    data: { TYP: "SELECT", BUYERID: 0 },
    success: function (jsondata) {
    	//alert(jsondata);
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
    
}); */

// added due to page loading performance on 25-11-2015 by Codefire
 function loadBuyerByName(buyer){ 
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

function LoadChallanData() {
    // alert("here");
    $(".flexme4").flexigrid({
        url: '',
        dataType: 'json',
        colModel: [{ display: 'ChallanId', name: 'ChallanId', width: 100, sortable: true, align: 'center', process: procme },
                             { display: 'ChallanDate', name: 'ChallanDate', width: 100, sortable: true, align: 'left' },
                             { display: 'ChallanNo', name: 'ChallanNo', width: 120, sortable: true, align: 'left' },
                             { display: 'BuyerName', name: 'bn_name', width:350, sortable: true, align: 'left' },
                             { display: 'gc_note', name: 'gc_note', width: 150, sortable: true, align: 'left' },
                             { display: 'gc_note_date', name: 'gc_note_date', width: 100, sortable: true, align: 'left' },
                             { display: 'ExecutiveId', name: 'ExecutiveId', width: 150, sortable: true, align: 'left'},
							 { display: 'Open Date', name: 'opendate', width: 80, sortable: true, align: 'left'},
							 { display: 'Close Date', name: 'closedate', width: 80, sortable: true, align: 'left'},
							 { display: 'Status', name: 'status', width: 100, sortable: true, align: 'left'}],
        // buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
        //searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1360,
        height: 240

    });

}
LoadChallanData();
function Search() {
    var txtchallanNo = $('#txtchallanNo').val();
    var Buyerid = $('#buyerid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val(); 
	var c_status = $("#challan_status").val(); 
	var c_purpose = $("#challan_purpose").val();  
	var executive = $("#salseuser").val();  
	var finyear = $("#ddlfinancialyear").val(); 
	
	var contactName = $("#challan_contactName").val();  
	URL = '../../Controller/Business_Action_Controller/Challan_Controller.php'	;
    var path = URL + '?TYP=SEARCH&txtchallanNo='+txtchallanNo + '&Buyerid='+Buyerid+"&Fromdate=" +Fromdate+ '&Todate='+Todate+'&status='+c_status+'&purpose='+c_purpose+'&executive='+executive+'&contactName='+contactName+"&finyear="+finyear;
    //alert(path);
    $(".flexme4").flexOptions({ url: path });
    $(".flexme4").flexReload();
}

function procme(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        var path = 'Challan.php?TYP=SELECT&ID=' + id;
        window.location.href = path;
    });
}

var challan_app = angular.module('challan_app', []);
function challanDetail(id) {
	$('#challanDetail').modal('show');	
	jQuery.ajax({
           url :"../../Controller/Business_Action_Controller/Challan_Controller.php",
           type: "POST",
           data: { TYP: "CHALLANDETAIL" ,challanID:id},
			beforeSend: function() { jQuery('#wait').css("display","block");},
			complete: function() { jQuery('#wait').css("display","none");},
            success: function (jsondata) {
			//alert(jsondata);
			//var objs = jQuery.parseJSON(jsondata);
			//alert(objs);
			$('#chlnDtl').html(jsondata);
					//$scope.$apply(function () {
                  // $scope.challan._items = objs;
			//});				   
            }            
        });
	
}

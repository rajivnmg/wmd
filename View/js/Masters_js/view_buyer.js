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
/*
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
*/

///////////////////////////////// added due to page loading performance on 25-11-2015 by Codefire
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


function ActionOnBuyer(value, data) {
    if (value != "" && data > 0) {
        $("#buyerid").val(data);
        SearchBuyer("BuyerId",data,0);
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
    LoadBuyer();
}
function showCity(State_Id, cityid) {
    SearchBuyer("State",State_Id,0);
    //State_Id = State_Id.replace(/(\r\n|\n|\r)/gm, "");
    //cityid = cityid.replace(/(\r\n|\n|\r)/gm, "");
	 $("#city").html("<option value=\'0'>Select City</option>");
	 $("#location").html("<option value=\'0'>Select Location</option>");
		
var TYPE = "SELECT";
    if (true) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/City_Controller.php",
            type: "POST",
            data: { TYP: TYPE , TAG:"STATE", STATEID :State_Id },
            //cache: false,
            success: function (jsondata) {
                $('#city').empty();
                $("#city").append("<option value=\'0'>Select City</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj;
                    for(var i = 0; i < objs.length; i++){
                    //for (index in objs) {
                    var obj = objs[i];
                    //var index = 0;
                    $("#city").append("<option value=\'" + obj._city_id + "'\ title=\'" + obj._city_nameame + "\'>" + obj._city_nameame + "</option>");
                    }
            }
            $('#city').val(cityid);
            }
        });
    }
}
function showLocation(City_Id,locationid){
	var TYPE = "SELECT";
	if (true) {
	SearchBuyer("City",City_Id,0);
			jQuery.ajax({url: "../../Controller/Master_Controller/LocationController.php",type: "POST",
				data: { TYP: TYPE , CITYID :City_Id },success: function (jsondata) {$('#location').empty();
				$("#location").append("<option value=\'0'>Select Location</option>");
				var objs = jQuery.parseJSON(jsondata);
					if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
						$("#location").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");}
						$("#location").val(locationid);}}});
	}
}
function SearchBuyer(coulam,value,val2){
		if(coulam != "BuyerId")
		{
			$('#autocomplete-ajax-buyer').val("");
			$("#buyerid").val(0);
		}
			var  buyerid = $("#buyerid").val();	
			var  level = $("#byrlvl").val();
			var  State_Id =$("#state").val();
			var  cityid = $("#city").val();
			var  location = $("#location").val();				
			/*	 alert(buyerid);
		 alert(level);
		alert(State_Id);
		alert(cityid);
		alert(location);  */
		//alert(level);
		//var path = '../../Controller/Master_Controller/Buyer_Controller.php?TYP=SEARCH&coulam='+coulam+'&val1='+value+'&val2='+val2;
		var path = '../../Controller/Master_Controller/Buyer_Controller.php?TYP=SEARCH&coulam='+coulam+'&buyerid='+buyerid+'&level='+level+"&State_Id="+State_Id+"&cityid="+cityid+"&location="+location;
		$(".BuyerList").flexOptions({ url: path });
		$(".BuyerList").flexReload();
}
function LoadBuyer(){
$(".BuyerList").flexigrid({
    url : '../../Controller/Master_Controller/Buyer_Controller.php?TYP=',
    dataType : 'json',
    colModel : [ {display : 'Buyer Id',name : 'BuyerId', width : 50, sortable : true, align : 'center', process: HitMe }, 
                    {display : 'Buyer Code',name : 'BuyerCode', width : 120,sortable : true,align : 'left' },    
                    {display : 'Buyer Name',name : 'BuyerName',width : 120, sortable : true, align : 'left'},
                    {display : 'Vendor Code',name : 'Vendor_Code', width : 120,sortable : true,align : 'left'},
                             
                    {display : 'Buyer Range',name : 'Buyer_Range', width : 90, sortable : true, align : 'center' }, 
                    {display : 'Division',name : 'Division', width : 120,sortable : true,align : 'left' },    
                    {display : 'Commissione Rate',name : 'Commissionerate',width : 120, sortable : true, align : 'left'},
                    {display : 'ECC',name : 'ECC', width : 120,sortable : true,align : 'left'},
                             
                    {display : 'TIN',name : 'TIN', width : 90, sortable : true, align : 'center' }, 
                    {display : 'PAN',name : 'PAN', width : 120,sortable : true,align : 'left' },    
                    {display : 'Bill_Add1',name : 'Bill_Add1',width : 120, sortable : true, align : 'left'},
                    {display : 'Bill_Add2',name : 'Bill_Add2', width : 120,sortable : true,align : 'left'},
                    //12
//                             {display : 'StateId',name : 'StateId', width : 90, sortable : true, align : 'center' }, 
//                             {display : 'CityId',name : 'CityId', width : 120,sortable : true,align : 'left' },    
//                             //{display : 'CountryId',name : 'CountryId',width : 120, sortable : true, align : 'left'},
//                             {display : 'LocationId',name : 'LocationId', width : 120,sortable : true,align : 'left'}, 

                    {display : 'StateName',name : 'StateName', width : 90, sortable : true, align : 'center' }, 
                    {display : 'CityName',name : 'CityName', width : 120,sortable : true,align : 'left' },
                   
				    {display : 'Buyer Level',name : 'Buyer_Level', width : 50,sortable : true,align : 'left'}, 

                    {display : 'Pincode',name : 'Pincode', width : 90, sortable : true, align : 'center' }, 
                    {display : 'Phone',name : 'Phone', width : 120,sortable : true,align : 'left' },    
                    {display : 'Mobile',name : 'Mobile',width : 120, sortable : true, align : 'left'},
                    {display : 'FAX',name : 'FAX', width : 120,sortable : true,align : 'left'},
                             
                    {display : 'Email',name : 'Email', width : 90, sortable : true, align : 'center' }, 
                    {display : 'Executive_ID',name : 'Executive_ID', width : 120,sortable : true,align : 'left' },    
                    {display : 'Credit_Period',name : 'Credit_Period',width : 120, sortable : true, align : 'left'},
                    {display : 'Tax_Type',name : 'Tax_Type', width : 120,sortable : true,align : 'left'}, 
                    {display : 'Remarks',name : 'Remarks', width : 120,sortable : true,align : 'left'}
                ],
//                buttons : [{name : 'New',bclass : 'new',onpress : EditBuyer},{name : 'Edit',bclass : 'edit',onpress : EditBuyer},
//                           {name : 'Contact Details',bclass : 'contact',onpress : EditBuyer},{name : 'Buyer Discount',bclass : 'discount',onpress : EditBuyer},
//                {separator : true}],
//                searchitems : [ {display : 'Buyer Id',name : 'BUYERID'}, {display : 'Buyer Name',name : 'BUYERNAME',isdefault : false}],
    sortorder : "asc",
    usepager : true,
    //title : 'Buyer Master',
    useRp : true,
    rp : 10,
    showTableToggleBtn : true,
    width : 1500,
    height : 300,
                
});
}
LoadBuyer();
function HitMe(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        var path = 'buyer.php?TYP=SELECT&ID=' + id;
        window.location.href = path;
    });
}

/*jslint  browser: true, white: true, plusplus: true */
/*global $, countries */
var BuyerList = {};
function CallToBuyer(BuyerList) {
    'use strict';
    var buyerArray = $.map(BuyerList, function (value, key) { return { value: value, data: key }; });
    // Setup jQuery ajax mock:
//    $.mockjax({
//        url: '*',
//        responseTime: 2000,
//        response: function (settings) {
//        
//            if (settings.data.length > 0) {
//                var query = settings.data.query,
//                queryLowerCase = query.toLowerCase(),
//                re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi'),
//                suggestions = $.grep(buyerArray, function (buyer) {
//                    // return country.value.toLowerCase().indexOf(queryLowerCase) === 0;
//                    return re.test(buyer.value);
//                }),
//                response = {
//                    query: query,
//                    suggestions: suggestions
//                };
//            }
//            this.responseText = JSON.stringify(response);
//        }
//    });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-buyer').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: buyerArray,
        lookupFilter: function(suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function(suggestion) {
            ActionOnBuyer(suggestion.value, suggestion.data);
            //$('#selction-ajax-buyer').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x-buyer').val(hint);
        },
        onInvalidateSelection: function() {
            $('#selction-ajax-buyer').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "Controller/Master_Controller/Buyer_Controller.php",
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
        //alert(data);
        jQuery.ajax({
            url: "Controller/Master_Controller/Buyer_Controller.php",
            type: "post",
            data: { TYP: "SELECT", BUYERID: data },
            success: function (jsondata) {
                //alert(data);
                //alert(jsondata);
//                var objs = jQuery.parseJSON(jsondata);
//                alert(objs[0]._buyer_name);
            },
            Error: function (response) {
                alert(response);
            }
        });
    }
    else {
    }
}
/*jslint  browser: true, white: true, plusplus: true */
/*global $, countries */
var SupplierList = {};
function CallToSullpier(SupplierList) {
    'use strict';
    var supplierArray = $.map(SupplierList, function (value, key) { return { value: value, data: key }; });
    // Setup jQuery ajax mock:
    $.mockjax({
        url: '*',
        responseTime: 2000,
        response: function (settings) {
            var query = settings.data.query,
                queryLowerCase = query.toLowerCase(),
                re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi'),
                suggestions = $.grep(supplierArray, function (supplier) {
                    // return country.value.toLowerCase().indexOf(queryLowerCase) === 0;
                    return re.test(supplier.value);
                }),
                response = {
                    query: query,
                    suggestions: suggestions
                };

            this.responseText = JSON.stringify(response);
        }
    });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-supplier').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: supplierArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            $('#selction-ajax-supplier').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            $('#autocomplete-ajax-x-supplier').val(hint);
        },
        onInvalidateSelection: function () {
            $('#selction-ajax-supplier').html('You selected: none');
        }
    });
}
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
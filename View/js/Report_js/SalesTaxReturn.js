var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";
var BuyerList = {};
var table;
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
        //Search();
    }
}
function Search()
{
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var rpttype = $("#rpttype").val();
    var invoiceno = $("#txtinvoicenumber").val();
    jQuery.ajax({
    url: URL,
    type: "post",
    data: { TYP: rpttype,todate:Todate,fromdate:Fromdate, BUYERID: $("#buyerid").val(),InvoiceNo: invoiceno},
    success: function (jsondata) {
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            $("#datarow").empty();
            if(table != null)
            {
                //table.clear();
            }
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                $("#datarow").append("<tr><td>"+obj.SN+"</td><td>"+obj.invno+"</td><td>"+obj.invdate+"</td><td>"+obj.buyername+"</td><td>"+obj.tin_vat_no+"</td><td>"+obj.taxable_amount+"</td><td>"+obj.tax_amount+"</td></tr>");
            }
            table = null;
            BindTable();
        }
    }
});
}
function NoneBuyer() {
    $("#buyerid").val(0);
}

function BindTable()
{
$(document).ready(function() {
     table = $('#example').DataTable({
        "bDestroy": true,
        "displayLength": 25,
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                } );
            // Update footer
            $( api.column( 6 ).footer() ).html(total +'/- INR'
            );
        }
    } );
} );


    
}

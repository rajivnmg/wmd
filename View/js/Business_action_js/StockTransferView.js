var URL = "../../Controller/Business_Action_Controller/StockTransfer_controller.php";
var method = "POST";

var PrincipalList = {};
function CallToPrincipal(PrincipalList) {
    'use strict';
    var principalArray = $.map(PrincipalList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-principal').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: principalArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnPrincipal(suggestion.value, suggestion.data);
            //$('#selction-ajax-principal').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-principal').val(hint);
        },
        onInvalidateSelection: function () {
            NonePrincipal();
            //$('#selction-ajax-principal').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "../../Controller/Master_Controller/Principal_Controller.php",
    type: "post",
    data: { TYP: "SELECT", PRINCIPALID: 0 },
    success: function (jsondata) {
        //alert(jsondata);
        var objs = jQuery.parseJSON(jsondata);
        if (jsondata != "") {
            var obj;
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                PrincipalList[obj._principal_supplier_id] = obj._principal_supplier_name;
            }
            CallToPrincipal(PrincipalList);
        }
    }
});
function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
        //SearchByPrincipal();
    }
}
function NonePrincipal() {
    $("#principalid").val("");
    $('.outgoing_invoice_excise_list').flexOptions({ url: URL });
    $('.outgoing_invoice_excise_list').flexReload();
}




function StockTransferData() {
    // alert("here");
    $(".outgoing_invoice_excise_list").flexigrid({
        url: '',
        dataType: 'json',
        colModel: [{ display: 'StockId', name: 'StockId', width: 100, sortable: true, align: 'center', process: procme },
                   { display: 'Stocktransfer No', name: 'stInvNo', width: 150, sortable: true, align: 'left' },
                   { display: 'Stocktransfer Date', name: 'stInvDate', width: 150, sortable: true, align: 'left' },
                   { display: 'Principal Name', name: 'stPrincipalName', width:350, sortable: true, align: 'left' },
                   { display: 'Supplier Name', name: 'stSupplrName', width: 350, sortable: true, align: 'left' },
                   { display: 'Amount', name: 'total_amt', width: 200, sortable: true, align: 'left'}],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1300,
        height: 300
      });

}
StockTransferData();

function Search() { 
    var oinv = $('#txtinvoicenumber').val();
    var Principalid = $('#principalid').val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var year = $("#ddlfinancialyear").val();
    //alert(year.length);
    var path = URL + '?TYP=&YEAR='+year+'&val1=' + oinv + '&val2=' + Fromdate + '&val3='+Todate+'&val4='+ Principalid ;
	
    $(".outgoing_invoice_excise_list").flexOptions({ url: path });
    $(".outgoing_invoice_excise_list").flexReload();
}

function procme(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        jQuery.ajax({
            url: URL,
            type: "post",
            data: { TYP: "GET_ST_NUM_BY_DISPLAY", DISPLAYID: id,YEAR:$("#ddlfinancialyear").val() },
            success: function (jsondata) {
                var obj = jQuery.parseJSON(jsondata);
                var id = parseInt(obj);
                var path = 'StockTransfer.php?TYP=SELECT&ID=' + id;
                //alert(path);
                window.location.href = path;
            }
        });
    });
}

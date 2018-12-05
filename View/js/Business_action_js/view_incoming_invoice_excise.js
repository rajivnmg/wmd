var URL = "../../Controller/Business_Action_Controller/Incoming_Invoice_Excise_Controller.php";
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
$( document ).ready(function() {
    jQuery.ajax({
    url: "../../Controller/Master_Controller/Principal_Controller.php",
    type: "post",
    data: { TYP: "SELECT", PRINCIPALID: 0 },
    success: function (jsondata) {
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
});

function ActionOnPrincipal(value, data) {
    if (value != "" && data > 0) {
        $("#principalid").val(data);
        SearchByPrincipal(data);
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
}
function LoadIncomingInvoiceExcise() {
//alert('url'+ URL + "");
    $(".incoming_invoice_excise_list").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'Invoice ID', name: '_entry_Id', width: 100, sortable: false, align: 'center', process: HitMe },
                { display: 'Principal Invoice No', name: 'prici_inv_no', width: 150, sortable: false, align: 'left' },
               
                { display: 'Invoice Date', name: '_insert_Date', width: 100, sortable: false, align: 'left' },
                { display: 'Invoice Receive Date', name: '_rece_date', width: 150, sortable: false, align: 'left' },
                { display: 'Principal Name', name: '_principal_name', width: 330, sortable: false, align: 'left' },
                { display: 'Supplier Invoice No', name: 'supp_inv_no', width: 150, sortable: false, align: 'left' },
                { display: 'Supplier Name', name: '_supplier_name', width: 200, sortable: false, align: 'left' },
                { display: 'Total Bill Value', name: '_total_bill_val', width: 120, sortable: false, align: 'left' }],
       // buttons: [{name: 'New',bclass: 'new',onpress: NewGroup},
         //         {name: 'Edit',bclass: 'edit',onpress: EditItem},
  //      {separator: true}],
        sortorder: "asc",
        usepager: true,
        //title: 'Quation Master',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1310,
        height: 390
    });
}
function HitMe(celDiv, id) {
    $(celDiv).click(function () {
        var DisplayIncomingInvoiceExciseNum = celDiv.innerHTML;
        jQuery.ajax({
            url: URL,
            type: "post",
            data: { TYP: "GET_INV_NUM_BY_DISPLAY", DISPLAYID: DisplayIncomingInvoiceExciseNum,YEAR:$("#ddlfinancialyear").val() },
            success: function (jsondata) {
                var obj = jQuery.parseJSON(jsondata);
                var IncomingInvoiceExciseNum = parseInt(obj);
                //alert(IncomingInvoiceExciseNum);
                var path = 'new_incoming_invoice_excise.php?TYP=SELECT&IncomingInvoiceExciseNum=' + IncomingInvoiceExciseNum;
                //alert(path);
                window.location.href = path;
            }
        });
        
    });
}
LoadIncomingInvoiceExcise();
function SearchByPrincipal(Principalid) {
    var path = URL + '?TYP=SEARCH&coulam=Principal&val1=' + Principalid + "&val2=&val3=&val4=";
    $(".incoming_invoice_excise_list").flexOptions({ url: path });
    $(".incoming_invoice_excise_list").flexReload();
}
function SearchIncomingInvoiceExcise() {   
    var iin =$("#txtinvoicenumber").val();
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var principalId = $("#principalid").val();
    var year = $("#ddlfinancialyear").val();
    var path = URL + '?TYP=SEARCH&YEAR='+year+'&val1='+iin+'&val2=' + Fromdate + '&val3=' + Todate + '&val4=' + principalId;
    $(".incoming_invoice_excise_list").flexOptions({ url: path });
    $(".incoming_invoice_excise_list").flexReload();
}
SearchIncomingInvoiceExcise();
/*
$('#txtinvoicenumber').on('keypress', function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
        e.preventDefault();
        if ($('#txtinvoicenumber').val() != "") {
            var path = URL + '?TYP=SEARCH&coulam=InvoiceNumber&val1=' + $('#txtinvoicenumber').val() + '&val2=&val3=&val4=';
            $(".incoming_invoice_excise_list").flexOptions({ url: path });
            $(".incoming_invoice_excise_list").flexReload();
        }
        else {
            $(".incoming_invoice_excise_list").flexOptions({ url: URL });
            $(".incoming_invoice_excise_list").flexReload();
        }   
    }
});
*/

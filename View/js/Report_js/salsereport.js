var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";
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
        SearchByBuyer(data);
    }
}
function NoneBuyer() {
    $("#buyerid").val(0);
}
var PrincipalList = {};
var ItemList = {};
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
    ItemList = {};
    if (value != "" && data > 0) {
        //alert(value+"|"+data)
        $("#principalid").val(data);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Item_Controller.php",
            type: "post",
            data: { TYP: "SELECT", PRINCIPALID: data },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj;
                    for (var i = 0; i < objs.length; i++) {
                        var obj = objs[i];
                        ItemList[obj._item_id] = obj._item_code_partno;
                    }
                    CallToItem(ItemList);
                }
            }
        });
    }
    else {

    }
}
function NonePrincipal() {
    $("#principalid").val(0);
    ItemList = {};
}
var SupplierList = {};
function CallToSullpier(SupplierList) {
    'use strict';
    var supplierArray = $.map(SupplierList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-supplier').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: supplierArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnSupplier(suggestion.value, suggestion.data);
            //$('#selction-ajax-supplier').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-supplier').val(hint);
        },
        onInvalidateSelection: function () {
            NoneSupplier();
            // $('#selction-ajax-supplier').html('You selected: none');
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
function ActionOnSupplier(value, data) {
    if (value != "" && data > 0) {
        $("#supplierid").val(data);
    }
    else {
    }
}
function NoneSupplier() {
    $("#supplierid").val(0);
}
function CallToItem(ItemList) {
    'use strict';
    var itemArray = $.map(ItemList, function (value, key) { return { value: value, data: key }; });

    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-codepart').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: itemArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnItem(suggestion.value, suggestion.data);
            //$('#selction-ajax-supplier').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-supplier').val(hint);
        },
        onInvalidateSelection: function () {
            NoneItem();
            // $('#selction-ajax-supplier').html('You selected: none');
        }
    });
}
function ActionOnItem(value, data) {
    if (value != "" && data > 0) {
        $("#itemid").val(data);
    }
    else {
    }
}
function NoneItem() {
    $("#itemid").val(0);
}
function ChangeInvoiceType() {
    var TypeID = $("#invoicetypelist").val();
    if (TypeID == 1 || TypeID == 2) {
        $("#supplierlist").show();
    } else {
        $("#supplierlist").hide();
    }
    if (TypeID == 3 || TypeID == 4) {
        $("#buyerlist").show();
    } else {
        $("#buyerlist").hide();
    }
}
function SearchSalseReport() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var PrincipalID = $("#principalid").val();
    var CodePart = $("#codepart").val();
    var BuyerID = $("#buyerid").val();
    var SupplierID = $("#supplierid").val();
    var Type = $("#invoicetypelist").val();
    var url = "";
    if (Fromdate != "" && Todate != "") {
        switch (Type) {
            case "1":
                url = "new_incoming_invoice_excise.php?TYP=SELECT&IncomingInvoiceExciseNum=";
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else if (SupplierID > 0) {
                    CallData(Type, "Date_With_Supplier", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            case "2":
                url = "invoice_incoming_nonexciseduty.php?TYP=SELECT&ID=";
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else if (SupplierID > 0) {
                    CallData(Type, "Date_With_Supplier", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            case "3":
                url = "new_Outgoing_Invoice_Excise.php?TYP=SELECT&OutgoingInvoiceExciseNum=";
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else if (BuyerID > 0) {
                    CallData(Type, "Date_With_Supplier", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            case "4":
                url = "new_Outgoing_Invoice_NonExcise.php?TYP=SELECT&OutgoingInvoiceNonExciseNum=";
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else if (BuyerID > 0) {
                    CallData(Type, "Date_With_Supplier", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            case "5":
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            case "6":
                if (PrincipalID > 0) {
                    if (CodePart > 0) {
                        CallData(Type, "Date_With_Codepart", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                    else {
                        CallData(Type, "Date_With_Principal", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                    }
                }
                else {
                    CallData(Type, "Date", Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID, url);
                }
                break;
            default:
                $("#rowdata").empty();
                break;
        }
    }
    else {
        alert("Salse Report only we evaluate between two date's.");
    }
}
function CallData(Type, ColoumName, Fromdate, Todate, PrincipalID, CodePart, BuyerID, SupplierID,urllink) {
    jQuery.ajax({
        url: URL,
        type: method,
        data: { TYP: "SALSEREPORT", RT: Type, CN: ColoumName, DF: Fromdate, DT: Todate, PI: PrincipalID, II: CodePart, BI: BuyerID, SI: SupplierID },
        success: function (jsondata) {
            var objs = jQuery.parseJSON(jsondata);
            if (jsondata != "") {
                var obj;
                $("#rowdata").empty();
                for (var i = 0; i < objs.length; i++) {
                    var obj = objs[i];
                    if (obj.Details != null) {
                        $("#rowdata").append("<tr><td>" + obj.srn + "</td><td>" + obj.date + "</td><td>" + obj.principal + "</td><td>" + obj.buyer + "</td><td>" + obj.totalbillcost + "</td><td>" + obj.type + "</td><td><div id='detailsdiv" + i + "' class='pop'></div></td><td><a  href='../Business_View/" + urllink + obj.txnid + "'>Go To Details</a></td></tr>");
                        $("#detailsdiv" + i).append("<table><thead><tr>Sr.</tr><tr>Code Part</tr><tr>Description</tr><tr>Quantity</tr><tr>Basic Price</tr><tr>Type</tr></thead><tbody id='detailsrowdata" + i + "'></tbody></table>");
                        for (var j = 0; j < obj.Details.length; j++) {
                            $("#detailsrowdata" + i).append("<tr><td>" + obj.Details[j].srn + "</td><td>" + obj.Details[j].codepart + "</td><td>" + obj.Details[j].description + "</td><td>" + obj.Details[j].quantity + "</td><td>" + obj.Details[j].baseprice + "</td><td>" + obj.Details[j].type + "</td></tr>");
                        }
                    }
                    else {
                        $("#rowdata").append("<tr><td>" + obj.srn + "</td><td>" + obj.date + "</td><td>" + obj.principal + "</td><td>" + obj.buyer + "</td><td>" + obj.totalbillcost + "</td><td>" + obj.type + "</td></tr>");
                    }
                }
                $.pop();
            }
        }
    });
}
var URL = "../../Controller/ReportController/RgReportController.php";
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
     //   Search("PRINCIPAL",data);
    }
}
function NonePrincipal() {
    $("#principalid").val(0);
}

function LoadDailySalesReport() {
    $(".rd-23d-report").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'S. No.', name: 'SN', width: 50, sortable: false, align: 'center' },
        { display: 'Manufacture <br/>or Importer <br/>Invoice No<br/> or Bill of <br/>Entry No & <br/>Date', name: 'incoming_invoice_No_and_date', width: 100, sortable: false, align: 'left' },
        { display: 'Name & Address of Manufacture <br/>or Importer including Central Excise <br/>Range Division and Commissionrate, <br/>Custom house and his New <br/>Excise control (Manufacture) <br/>or Imorter Export Code (Importer)', name: 'manufacture_name_and_address', width: 250, sortable: false, align: 'left' },
 		{ display: 'Quantity', name: 'incoming_quantity', width: 100, sortable: false, align: 'left' },
 		{ display: 'Receipt Amount <br/>rate', name: 'incoming_amount_rate', width: 100, sortable: false, align: 'left' },
		{ display: 'Receipt Amount<br/> amount of duty', name: 'incoming_amount_of_duty', width: 100, sortable: false, align: 'left'},
		{ display: 'Receipt Amount <br/>education Cess', name: 'incoming_education_cess', width: 100, sortable: false, align: 'left'},
		{ display: 'Receipt Amount <br/>s&h edu cess', name: 'incoming_sh_edu_cess', width: 100, sortable: false, align: 'left'},
		{ display: 'Receipt Amount <br/>Spl. Add Duty<br/> of Custom', name: 'incoming_spl_add_duty_of_custom', width: 100, sortable: false, align: 'left'},
		{ display: 'Description of <br/>Goods', name: 'description_of_goods', width: 150, sortable: false, align: 'left'},
		{ display: 'Invoice No. & Date <br/>or Bill of Entry &<br/> Date of the<br/> Supplier if he<br/> is not manufacture <br/>or importer ', name: 'supplier_invice_no_and_date', width: 100, sortable: false, align: 'left'},
		{ display: 'Name & Address of<br/>the Manufacture<br/> or Importer<br/> including Central<br/> Excise Range Division <br/>& Commissionrate, <br/>Custom house', name: 'name_and_address_manufacture', width: 150, sortable: false, align: 'left'},
		{ display: 'Quantity', name: 'supplier_quantity', width: 100, sortable: false, align: 'left'},
		{ display: 'Tariff <br/>heading or Sub <br/>heading No.', name: 'tariff_heading', width: 100, sortable: false, align: 'left'},
		{ display: 'Issue Amount<br/> amount of Duty', name: 'issue_amount_of_duty', width: 100, sortable: false, align: 'left'},
		{ display: 'Issue Amount<br/> Edu. Cess', name: 'issue_edu_cess', width: 100, sortable: false, align: 'left'},
		{ display: 'Issue Amount <br/>S & H Edu Cess', name: 'issue_sh_edu_cess', width: 100, sortable: false, align: 'left'},
		{ display: 'Issue Amount <br/>Spl Add Duty of Custom', name: 'issue_duty_of_custom', width: 100, sortable: false, align: 'left'},
		{ display: 'Invoice No. <br/>& Date', name: 'outgoing_invoice_no_and_date', width: 100, sortable: false, align: 'left'},
		{ display: 'Name of the Customer <br/>to whom goods are sold <br/>including Central Excise <br/>range, Division & <br/>Commissionerate Custom <br/>House', name: 'customer_name', width: 150, sortable: false, align: 'left'},
		{ display: 'Quantity', name: 'outgoing_quantity', width: 100, sortable: false, align: 'left'},
		{ display: 'Total Amount of Duty <br/>amount of Duty', name: 'outgoing_amount_of_duty', width: 130, sortable: false, align: 'left'},
		{ display: 'Total Amount of Duty<br/> Edu Cess', name: 'outgoing_edu_cess', width: 130, sortable: false, align: 'left'},
		{ display: 'Total Amount of Duty <br/>S & H Edu Cess', name: 'outgoing_sh_edu_cess', width: 130, sortable: false, align: 'left'},
		{ display: 'Total Amount of Duty <br/>Spl add duty of Custom', name: 'outgoing_spl_add_duty_of_custom', width: 130, sortable: false, align: 'left'},
		{ display: 'Remarks', name: 'remarks', width: 100, sortable: false, align: 'left'}
				
        ],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width : 3015,
        height : 300
    });
}
LoadDailySalesReport();

function Search() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var pid = $("#principalid").val();
    var path = URL + '?TYP=RG23DReport'+'&todate='+Todate+'&fromdate='+Fromdate+'&pid='+pid;
    if (Fromdate != "" && Todate != "") {
        $('.rd-23d-report').flexOptions({ url: path });
        $('.rd-23d-report').flexReload();
    }
    else {
        alert("Please select date");
    }
}

function Getpdf() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();  
    var pid = $("#principalid").val();
	 if (Fromdate != "" && Todate != "") {
		 window.open('rg_23d_report_pdf.php?todate='+Todate+'&fromdate='+Fromdate+'&pid='+pid,'_blank');
    }else {
		alert("Please select date");
    }   
}
function Getexcel() {
   var Fromdate = $("#txtdatefrom").val();
   var Todate = $("#txtdateto").val();  
   var pid = $("#principalid").val();
   if (Fromdate != "" && Todate != "") {
		 window.open('rg_23d_report_excel.php?todate='+Todate+'&fromdate='+Fromdate+'&pid='+pid,'_blank');    
   }
   else {
        alert("Please select date");
   }
   
}

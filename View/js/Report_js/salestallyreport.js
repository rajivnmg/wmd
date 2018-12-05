var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";

var PrincipalList = {};

var BuyerList = {};

function LoadSalesTallyReport() {
    //alert('url'+ URL);
    $(".salestallyreport").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'Sales Type', name: 'Sales_Type', width: 100, sortable: false, align: 'center' },
        { display: 'Invoice Date', name: 'Invoice_date', width: 100, sortable: false, align: 'left' },
        { display: 'Invoice No.', name: 'Invoice_number', width: 100, sortable: false, align: 'left' },
 		{ display: 'Party Name', name: 'Party_Name', width: 100, sortable: false, align: 'left' },
 		{ display: 'Sales Ledger', name: 'Sales_Ledger', width: 100, sortable: false, align: 'left' },
		{ display: 'Item Name', name: 'Item_name', width: 100, sortable: false, align: 'left'},
		{ display: 'HSN Code', name: 'HSN_Code', width: 100, sortable: false, align: 'left'},
		{ display: 'Rate', name: 'Rate', width: 100, sortable: false, align: 'left'},
		{ display: 'Qty', name: 'Qty', width: 100, sortable: false, align: 'left'},
		{ display: 'Amount', name: 'Amount', width: 100, sortable: false, align: 'left'},
		{ display: 'DISCOUNT%', name: 'discount_percent', width: 100, sortable: false, align: 'left'},
		{ display: 'DISCOUNT AMOUNT', name: 'Discount', width: 100, sortable: false, align: 'left'},
		{ display: 'TAXABLE AMOUNT', name: 'Taxable_Amount', width: 100, sortable: false, align: 'left'},
		{ display: 'CGST', name: 'CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'CGST Amount', name: 'CGST_AMOUNT', width: 100, sortable: false, align: 'left'},
        { display: 'SGST', name: 'SGST', width: 100, sortable: false, align: 'left' },
 		{ display: 'SGST Amount', name: 'SGST_AMOUNT', width: 100, sortable: false, align: 'left' },
 		{ display: 'IGST', name: 'IGST', width: 100, sortable: false, align: 'left' },
		{ display: 'IGST Amount', name: 'IGST_AMOUNT', width: 100, sortable: false, align: 'left'},
		{ display: 'Total', name: 'Total', width: 100, sortable: false, align: 'left'},
		{ display: 'Freight', name: 'Freight', width: 100, sortable: false, align: 'left'},
		{ display: 'Freight CGST', name: 'Freight_CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Freight SGST', name: 'Freight_SGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Freight IGST', name: 'Freight_IGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Freight Total', name: 'Freight_Total', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F', name: 'P_F', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F CGST', name: 'P_F_CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F SGST', name: 'P_F_SGST', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F IGST', name: 'P_F_IGST', width: 100, sortable: false, align: 'left'},
		{ display: 'P&F Total', name: 'P_F_Total', width: 100, sortable: false, align: 'left'},
		{ display: 'Insurance', name: 'INS', width: 100, sortable: false, align: 'left'},
		{ display: 'Insurance CGST', name: 'INS_CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Insurance SGST', name: 'Ins_SGST', width: 100, sortable: false, align: 'left'},
        { display: 'Insurance IGST', name: 'Ins_IGST', width: 100, sortable: false, align: 'left' },
 		{ display: 'Insurance Total', name: 'INS_Total', width: 100, sortable: false, align: 'left' },
 		{ display: 'Incidental', name: 'INC', width: 100, sortable: false, align: 'left' },
		{ display: 'Incidental CGST', name: 'Inc_CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Incidental SGST', name: 'Inc_SGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Incidental IGST', name: 'Inc_IGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Incidental Total', name: 'Inc_Total', width: 100, sortable: false, align: 'left'},
		{ display: 'Other', name: 'OTHC', width: 100, sortable: false, align: 'left'},
		{ display: 'Other CGST', name: 'OTHC_CGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Other SGST', name: 'OTHC_SGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Other IGST', name: 'Othc_IGST', width: 100, sortable: false, align: 'left'},
		{ display: 'Other Total', name: 'OTHC_Total', width: 100, sortable: false, align: 'left'},
		{ display: 'Total Invoice', name: 'Total_Invoice', width: 100, sortable: false, align: 'left'}
				
        ],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 4700,
        height: 300
    });
}
LoadSalesTallyReport();
function Search(type,value) {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var path = URL + '?TYP=SALESTALLYREPORT'+'&todate='+Todate+'&fromdate='+Fromdate;
    if (Fromdate != "" && Todate != "") {
        $('.salestallyreport').flexOptions({ url: path });
        $('.salestallyreport').flexReload();
    }
    else {
        alert("Please select date");
    }
}

function Getexcel() {
   var Fromdate = $("#txtdatefrom").val();
   var Todate = $("#txtdateto").val();  
     if (Fromdate != "" && Todate != "") {
		 window.open('excel_salestally.php?todate='+Todate+'&fromdate='+Fromdate,'_blank');    
    }
    else {
        alert("Please select date");
    }
   
}

function gettally() 
{
    window.open('excel_salestally.php', '_blank');
}

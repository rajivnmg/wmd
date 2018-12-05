var URL = "../../Controller/ReportController/SalseReportController.php";
var method = "POST";

function LoadStockTransferInvoiceExcise() {
    //alert('url'+ URL);
    $(".stocktransfer_invoice_excise_list").flexigrid({
        url: "",
        dataType: 'json',
        colModel: [{ display: 'Invoice No.', name: 'invno', width: 200, sortable: false, align: 'left' },
                { display: 'Invoice Date', name: 'invdate', width: 200, sortable: false, align: 'left' },
                { display: 'Description of Goods', name: 'groupdesc', width: 200, sortable: false, align: 'left' },
                { display: 'CETSH No', name: 'tarrifheading', width: 260, sortable: false, align: 'left'},
                { display: 'Quantity Code', name: 'unitname', width: 200, sortable: false, align: 'left' },
                { display: 'Quantity', name: 'quantity', width: 200, sortable: false, align: 'left' },
                { display: 'Amount of Duty Involved(Rs)', name: 'duty', width: 200, sortable: false, align: 'left' }],
        sortorder: "asc",
        usepager: true,
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1260,
        height: 300
    });
}
LoadStockTransferInvoiceExcise();
function Search() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var rpttype = $("#rpttype").val();
    var finyear = $("#ddlfinancialyear").val();   
    var path = URL + '?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear;
    if (Fromdate != "" && Todate != "") {
        $('.stocktransfer_invoice_excise_list').flexOptions({ url: path });
        $('.stocktransfer_invoice_excise_list').flexReload();
    }
    else {
        alert("Please select date");
    }
    
}
function Getpdf() {
    var Fromdate = $("#txtdatefrom").val();
    var Todate = $("#txtdateto").val();
    var rpttype = $("#rpttype").val();
    var finyear = $("#ddlfinancialyear").val();   
    if(Fromdate != "" && Todate != "") {
        window.open('stocktransferexcisereturn_pdf.php?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear,'_blank');
    }
    else {
        alert("Please select date"); return false;
    }
   
    
}
function Getexcel() {
  var Fromdate = $("#txtdatefrom").val();
  var Todate = $("#txtdateto").val();
  var rpttype = $("#rpttype").val();
  var finyear = $("#ddlfinancialyear").val();   
   if (Fromdate != "" && Todate != "") {
      window.open('stocktransferexcisereturn_excel.php?TYP='+rpttype+'&todate='+Todate+'&fromdate='+Fromdate+'&finyear='+finyear,'_blank');
    }else {
       alert("Please select date"); return false;
    }    
}

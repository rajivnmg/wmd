var PO_Reports_app= angular.module('PO_Reports_app', []);
// create angular controller

PO_Reports_app.controller('PO_Reports_Controller', function ($scope) {
  
    $scope.SearchPo = function () {        
        $scope.PO_Reports.SearchKey=$("#txtsearchkey").val(); 
        json_string='';    
        json_string = JSON.stringify($scope.PO_Reports);       
        LoadChallanData(json_string);
        
    }
   
} );



function LoadChallanData() {
       
     var URL='../../Controller/ReportController/ChallanReportsController.php?TYP=SEARCH&SearchData='+json_string;  
    $(".polist").flexigrid({
         url:URL,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'S.NO.', name: 'SN', width:80, sortable: false, align: 'left'},
                     { display: 'CodePart No.', name: 'Item_Code_Partno', width:190, sortable: false, align: 'left' },
                             { display: 'Item Description', name: 'Item_desc', width:415, sortable: false, align: 'left' },
                             { display: 'Excise Stock', name: 'tot_exciseQty', width:110, sortable: false, align: 'right' },
                             { display: 'Non-Excise Stock', name: 'tot_nonExciseQty', width: 150, sortable: false, align: 'right' },
                             { display: 'Issue Qty (In Challan)', name: 'issue_qty', width:180, sortable: false, align: 'right' },
                             { display: 'Stock', name: 'stock_qty', width: 110, sortable: false, align: 'right' }],
         sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        width: 1260,
        height: 500

    });
        
    $(".polist").flexOptions({ url: URL });
    $(".polist").flexReload();
       
}

function GetpdfChallanReport() {
    var txtsearchkey = $("#txtsearchkey").val(); 
   	window.open('excise_nonexcise_ckstatement_pdf.php?txtsearchkey='+txtsearchkey,'_blank');
   
}
function GetexcelChallanReport() {
    var txtsearchkey = $("#txtsearchkey").val();    
	window.open('excise_nonexcise_ckstatement_excel.php?txtsearchkey='+txtsearchkey,'_blank');
   
}



// function to show to chllam stock details from open to close show 

function showChalans(codepartno,itemid){	
	
	$("#codepartno").text(codepartno);
	$('#viewChallanD').modal('show');	
	jQuery.ajax({         
			url: "../home/viewChalanStock.php?itemid="+itemid+"&codepartno="+codepartno,
            type: "POST",
            data: { TYP: "CHALANSTOCKDETAILS" },
			beforeSend: function() { jQuery('#waitPo').css("display","block");},
			complete: function() { jQuery('#waitPo').css("display","none");},
            success: function (jsondata) {
              	$('#viewChallan').html(jsondata);
               
            }
        });
}



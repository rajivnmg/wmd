var json_string;
var Search_app = angular.module('Search_app', []);
Search_app.controller('Search_Controller', function ($scope) {
    $scope.SearchPo = function () {
        $scope.Search.FromDate = $("#txtfromdate").val();
        $scope.Search.ToDate = $("#txttodate").val();
        $scope.Search.PoVD = $("#txtpodate").val();
		 $scope.Search.Mode = $("#Mode").val();
		$scope.Search.Status = $("#Status").val();
		$scope.Search.PoType = $("#PoType").val();
		$scope.Search.BuyerId = $("#BuyerId").val();
		$scope.Search.principalid = $("#principalid").val();
		$scope.Search.ponumber = $("#ponumber").val();
		$scope.Search.Mode = $("#Mode").val();
		$scope.Search.Status = $("#Status").val();
		$scope.Search.PoType = $("#PoType").val();
		$scope.Search.marketsegment = $("#marketsegment").val();
		json_string = JSON.stringify($scope.Search);
		//alert(json_string);
		LoadChallanData(json_string);
    
    }
});
function LoadChallanData(json_string) {
        //alert(json_string);
    $(".flexme4").flexigrid({
        url: '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCH&SearchData='+json_string,
        //data: { TYP: "SEARCH", SearchData: json_string },
        dataType: 'json',
        type: "POST",
        colModel: [{ display: 'POID', name: 'bpoId', width: 50, sortable: true, align: 'center', process: procMe },
                             { display: 'bpono', name: 'bpono', width: 200, sortable: true, align: 'left' },
                             { display: 'bpoDate', name: 'bpoDate', width: 200, sortable: true, align: 'left' },
                             { display: 'bpoVDate', name: 'bpoVDate', width: 200, sortable: true, align: 'left' },
                              { display: 'BuyerName', name: 'BuyerName', width: 200, sortable: true, align: 'left' },
                             { display: 'po_status', name: 'po_status', width: 200, sortable: true, align: 'left' },
                           { display: 'po_val', name: 'po_val', width: 200, sortable: true, align: 'left'}],
		// buttons: [{ name: 'Edit', bclass: 'edit', onpress: UserMasterGrid }, { separator: true}],
		// searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
        sortorder: "asc",
        usepager: true,
        title : 'Search Result',
        useRp: true,
        rp: 10,
        showTableToggleBtn: false,
        //width: 1360,
        height: 400

    });
    var path = '../../Controller/Business_Action_Controller/Search_controller.php?TYP=SEARCH&SearchData='+json_string;
    $(".flexme4").flexOptions({ url: path });
    $(".flexme4").flexReload();
}
function procMe(celDiv, id) {
    $(celDiv).click(function () {
        var PONumber = celDiv.innerText;
        var path = 'purchaseorder.php?POID=' + PONumber;
        //alert(path);
        window.location.href = path;
    });
}
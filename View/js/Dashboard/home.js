var dashboard_app = angular.module('dashboard_app', []);
// create angular controller

dashboard_app.controller('dashboard_Controller', ['$scope', '$http', function quotation_Controller($scope, $http) {
    $scope.dashboard = [{}];
    $scope.init = function () {
        jQuery.ajax({
            url: "Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LISTDATA" },
            success: function (jsondata) {
                var data = jsondata.split('#');
                $scope.$apply(function () {
                    $scope.dashboard.total_item = data[0].split('"')[1];
                    $scope.dashboard.new_purchase_order = data[1];
                    $scope.dashboard.lsc_item = data[2].split('"')[0];
                });
                
            }
        });
    }
    //var ItemData = { _items: [{ code_partNo: 0, item_codepart: 0, item_desc: '', principalname: 0, price: 0, lsc: 0, usc: 0, tot_exciseQty: 0, tot_nonExciseQty: 0}] };
	var ItemData = { _items: [{ code_partNo: 0, item_codepart: 0, item_desc: '', principalname: 0, price: 0, lsc: 0, usc: 0, tot_Qty: 0}] };
    $scope.dashboard = ItemData;
    $scope.callAllItem = function () {
        jQuery.ajax({
            url: "Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LOADITEMLIST" },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    $scope.dashboard._items = objs;
                });
            }
        });
    }
    $scope.callLSCItem = function () {
        jQuery.ajax({
            url: "Controller/Dashboard/Dashboard_Controller.php",
            type: "POST",
            data: { TYP: "LOADLSCITEMLIST" },
            success: function (jsondata) {
                var objs = jQuery.parseJSON(jsondata);
                $scope.$apply(function () {
                    $scope.dashboard._items = objs;
                });
            }
        });
    }

} ]);

dashboard_app.directive('validNumber', function () {
    return {
        require: '?ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            if (!ngModelCtrl) {
                return;
            }
            ngModelCtrl.$parsers.push(function (val) {
                var clean = val.replace(/[^0-9]+/g, '');
                if (val !== clean) {
                    ngModelCtrl.$setViewValue(clean);
                    ngModelCtrl.$render();
                }
                return clean;
            });
        }
    }
});

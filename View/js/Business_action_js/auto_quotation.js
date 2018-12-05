var app = angular.module('quotation_app', ["ngTouch", "angucomplete"]);

app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13) {
                scope.$apply(function () {
                    scope.$eval(attrs.ngEnter);
                });
                event.preventDefault();
            }
        });
    };
});

app.controller('MainController', ['$scope', '$http', function MainController($scope, $http) {

    $scope.principal = [{}];
    var responsePromise = $http.get("../../Controller/Master_Controller/Principal_Controller.php?TYP=SELECT&PRINCIPAL_SUPPLIER_ID=0");
    responsePromise.success(function (data, status, headers, config) {
        $scope.principal = data;
    });
    responsePromise.error(function (data, status, headers, config) {
        alert("AJAX failed!");
    });
    $scope.buyer = [{}];
    var responsePromise = $http.get("../../Controller/Master_Controller/Buyer_Controller.php?TYP=SELECT&BUYERID=0");
    responsePromise.success(function (data, status, headers, config) {
        $scope.buyer = data;
    });
    responsePromise.error(function (data, status, headers, config) {
        alert("AJAX failed!");
    });
}
]);
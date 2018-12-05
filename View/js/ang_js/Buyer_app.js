
var buyer_app = angular.module('buyer_app', []);

// create angular controller
buyer_app.controller('buyer_Controller', function ($scope) {

    $scope.change = function () {
        $scope.buyer.shipping1add1 = $scope.buyer.add1;
        $scope.buyer.shipping1add2 = $scope.buyer.add2;
        $scope.buyer.shippingcity1 = $scope.buyer.city;
        $scope.buyer.shippinglocation1 = $scope.buyer.location;
        $scope.buyer.shippingpincode1 = $scope.buyer.pincode;
        $scope.buyer.shippingphone1 = $scope.buyer.phonno;
        $scope.buyer.shippingmobile1 = $scope.buyer.mobileno;
        $scope.buyer.shippingfax1 = $scope.buyer.fax;
        $scope.buyer.shippingemail1 = $scope.buyer.email;
    }
    $scope.submitForm = function () {
        var Type = $scope.buyer.save;
        var json_string = JSON.stringify($scope.buyer);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: "INSERT", BUYERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                    $("#Form_Div").hide();
                    $("#ShowData_Div").show();
                }
                else {
                }
            }
        });
    }
});
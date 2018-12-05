var Create_app = angular.module('Create_app', []);
Create_app.controller('Create_Controller', function ($scope) {
    $scope.submitForm = function () {
	alert("hERE");
        var json_string = JSON.stringify($scope.Create);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Create_Controller.php?TYP=",
            type: "POST",
            data: { TYP: "INSERT", CREATEUSERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                   
                }
                else {
                }
            }
        });
    }
});

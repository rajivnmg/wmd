// app.js
	// create angular app
	var validationApp = angular.module('validationApp',[]);

	// create angular controller
	validationApp.controller('mainController', function($scope) {

		// function to submit the form after all validation has occurred			
		//$scope.submitForm = function(isValid) {
	    $scope.submitForm = function () {
			// check to make sure the form is completely valid

	        alert($scope.buyer.name);

		};

	});
//	validationApp.directive('validNumber', function() {
//  return {
//    require: '?ngModel',
//    link: function(scope, element, attrs, ngModelCtrl) {
//      if(!ngModelCtrl) {
//        return; 
//      }
//      
//      ngModelCtrl.$parsers.push(function(val) {
//        var clean = val.replace( /[^0-9]+/g, '');
//        if (val !== clean) {
//          ngModelCtrl.$setViewValue(clean);
//          ngModelCtrl.$render();
//        }
//        return clean;
//      });
//      
//      
//    }
//  };
//});



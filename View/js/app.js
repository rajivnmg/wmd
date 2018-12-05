// app.js
	// create angular app
var validationApp = angular.module('Create_app', []);

	// create angular controller
validationApp.controller('Create_Controller', function ($scope) {

		// function to submit the form after all validation has occurred			
		$scope.submitForm = function(isValid) {

			// check to make sure the form is completely valid
		   // alert($scope.user.txtphone);
			if (isValid) {
				alert('our form is amazing');
			}

		};

	});
	validationApp.directive('validNumber', function() {
  return {
    require: '?ngModel',
    link: function(scope, element, attrs, ngModelCtrl) {
      if(!ngModelCtrl) {
        return; 
      }
      
      ngModelCtrl.$parsers.push(function(val) {
        var clean = val.replace( /[^0-9]+/g, '');
        if (val !== clean) {
          ngModelCtrl.$setViewValue(clean);
          ngModelCtrl.$render();
        }
        return clean;
      });
      
      
    }
  };
});



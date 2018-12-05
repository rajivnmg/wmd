
//var AdminHomePath = "http://www.techalay.com/demos/multiweld/AdminHome.php";
//var SitePath = "http://www.techalay.com/demos/multiweld/index.php";/*
/*var SitePath = "http://136.243.9.202/multiDemo/View/home/Dashboard.php";
var AdminHomePath = "http://136.243.9.202/multiDemo/View/home/AdminHome.php";
var SalesExecutiveHomePath="http://136.243.9.202/multiDemo/View/SalesExecutive/Dashboard.php";
var ManagementHomePath="http://136.243.9.202/multiDemo/View/Management/Dashboard.php";*/

var SitePath = "http://localhost/multiDemo/View/home/Dashboard.php";
var AdminHomePath = "http://localhost/multiDemo/View/home/AdminHome.php";
var SalesExecutiveHomePath="http://localhost/multiDemo/View/SalesExecutive/Dashboard.php";
var ManagementHomePath="http://localhost/multiDemo/View/Management/Dashboard.php";



var Create_app = angular.module('Create_app', []);
Create_app.controller('Create_Controller', function ($scope) {
    $scope.submitForm = function () {
        //alert("hERE");
		//$scope.Create.USER_TYPE = $('#USER_TYPE').val();
        var json_string = JSON.stringify($scope.Create);
		//alert(json_string); return false;
        jQuery.ajax({
            url: "../../Controller/Master_Controller/User_Controller.php?TYP=",
            type: "POST",
            data: { TYP: "INSERT", ACTION: "CreateUser", CREATEUSERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.Create = null;
                        location.href = "userinfo.php";
                    });
                }
                else {
                } 
            }
        });
    }
    $scope.Update = function () {
        //alert("hERE");
       var USERID=document.getElementById("USERID").value;
       var NAME=document.getElementById("NAME").value;
       var USER_TYPE=document.getElementById("USER_TYPE").value;
       var PHONE=document.getElementById("PHONE").value;
       var email=document.getElementById("email").value;
       var MOBILE=document.getElementById("MOBILE").value;
       var ACTIVE;
       if(document.getElementById("ACTIVE").checked)
       ACTIVE="Y";
       else
        ACTIVE="N";

        //var json_string = JSON.stringify($scope.Create);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/User_Controller.php?TYP=",
            type: "POST",
            data: { TYP: "UPDATE", USERID: USERID,NAME: NAME, USER_TYPE: USER_TYPE, PHONE: PHONE, email: email, MOBILE: MOBILE, ACTIVE: ACTIVE },
            success: function (jsondata) {
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.Create = null;
                        location.href = "userinfo.php";
                    });
                }
                else {
                }
            }
        });
    }
    $scope.init = function (number) {
        //$scope.Challan._ChallanId = number;
        if (number != "") {
            $("#btnsave").hide();
            jQuery.ajax({
                url: "../../Controller/Master_Controller/User_Controller.php?TYP=",
                type: "POST",
                data: { TYP: "SELECT", USERID: number },
                success: function (jsondata) {


                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    $("#pd").hide();
                    document.getElementById("USERID").value = objs[0]._USERID;
                    document.getElementById("PASSWD").value = objs[0]._PASSWD;
                    document.getElementById("NAME").value = objs[0]._USER_NAME;
                    document.getElementById("USER_TYPE").value = objs[0]._USER_TYPE;
                    document.getElementById("PHONE").value = objs[0]._PHONE;
                    document.getElementById("email").value = objs[0]._email;
                    document.getElementById("MOBILE").value = objs[0]._MOBILE;
                    if (objs[0]._ACTIVE == "Y")
                        document.getElementById("ACTIVE").checked = true;
                    else
                        document.getElementById("ACTIVE").checked = false;
//                    var objs = jQuery.parseJSON(jsondata);
//                    alert(jsondata);
//                    $scope.Create.USERID = objs[0]._USERID;
//                    $scope.Create.PASSWD = objs[0]._PASSWD;
//                    $scope.Create.NAME = objs[0]._USER_NAME;
//                    $scope.Create.USER_TYPE = objs[0]._USER_TYPE;
//                    $scope.Create.PHONE = objs[0]._PHONE;
//                    $scope.Create.email = objs[0]._email;
//                    $scope.Create.MOBILE = objs[0]._MOBILE;
//                    if (objs[0]._ACTIVE == "Y")
//                        $scope.Create.ACTIVE = true;
                }
            });
        }
        else {
            $("#btnupdate").hide();
        }
    }

});
var Login_app = angular.module('Login_app', []);
Login_app.controller('Login_Controller', function ($scope) {
    $scope.login = function () {
        $scope.Login.PASSWD = $("#txtpassword").val();
        //alert($scope.Login.PASSWD);
        //alert($scope.Login.USERID);
        var json_string = JSON.stringify($scope.Login);
        //alert(json_string);
        jQuery.ajax({
            url: "Controller/Master_Controller/User_Controller.php",
            type: "POST",
            data: { TYP: "SELECT", ACTION: "Login", LOGINCREDENTIAL: json_string },
            success: function (jsondata) {
               // alert(jsondata);
                var objs = jQuery.parseJSON(jsondata);
                //alert(objs);
               
               //##########
               if (objs == "A") {
                    location.href = AdminHomePath;
                }
                else if (objs=="E") {
                    location.href =SalesExecutiveHomePath;
                }
                else if (objs == "NO") {
                    alert("User name dose not exits.");

                }
                else  {
                    location.href = SitePath;
                }
               
               //##########

            }
        });
    }
});
var ChangePassword_app = angular.module('ChangePassword_app', []);
ChangePassword_app.controller('ChangePassword_Controller', function ($scope) {
    $scope.Change = function () {
        if ($scope.ChangePassword.newpass == $scope.ChangePassword.confirmpass) {
            var json_string = JSON.stringify($scope.ChangePassword);
            jQuery.ajax({
                url: "../../Controller/Master_Controller/User_Controller.php",
                type: "POST",
                data: { TYP: "UPDATE", ACTION: "ChangePassword", ChangePassword: json_string },
                success: function (jsondata) {
                    //alert(jsondata);
                     var objs = jQuery.parseJSON(jsondata);
                    if (objs=="YES") {
                        alert("Password Changed Successfully");
                        location.href = SitePath;
                    }else{
                        alert("Password not Changed ");
                    }
                }
            });
        }
        else {
            alert("Password Not matched");
        }
    }
});

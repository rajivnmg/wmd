var FormUserMenu_app = angular.module('FormUserMenu_app', []);
FormUserMenu_app.controller('FormUserMenu_Controller', function ($scope) {

    $scope.homemenu = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Home) {
            var MenuId = 1;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Home) {
            var MenuId = 1;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.Masters = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Masters) {
            var MenuId = 2;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Masters) {
            var MenuId = 2;
            DeleteMenu(MenuId, userid);
        }
    }
//    $scope.usermast = function () {
//        var userid = $scope.FormUserMenu.USER_ID;
//        if ($scope.FormUserMenu.usermast) {

//            var MenuId = 201;
//            savemenu(MenuId, userid);
//        }
//        else if (!$scope.FormUserMenu.usermast) {
//            var MenuId = 201;
//            DeleteMenu(MenuId, userid);
//        }
//    }
    $scope.unitmast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.unitmast) {
            var MenuId = 202;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.unitmast) {
            var MenuId = 202;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.groupmast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.groupmast) {
            var MenuId = 203;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.groupmast) {
            var MenuId = 203;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.statemast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.statemast) {
            var MenuId = 204;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.statemast) {
            var MenuId = 204;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.citymast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.citymast) {
            var MenuId = 205;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.citymast) {

            var MenuId = 205;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.locationmast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.locationmast) {

            var MenuId = 206;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.locationmast) {

            var MenuId = 206;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.principalmast = function () {
        var userid = $scope.FormUserMenu.USER_ID;

        if ($scope.FormUserMenu.principalmast) {

            var MenuId = 207;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.principalmast) {

            var MenuId = 207;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.suppliermast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.suppliermast) {

            var MenuId = 208;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.suppliermast) {

            var MenuId = 208;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.itemmast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.itemmast) {

            var MenuId = 209;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.itemmast) {

            var MenuId = 209;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.Buyermast = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Buyermast) {

            var MenuId = 210;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Buyermast) {

            var MenuId = 210;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.BusinessAction = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.BusinessAction) {

            var MenuId = 3;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.BusinessAction) {

            var MenuId = 3;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.Challan = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Challan) {

            var MenuId = 301;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Challan) {

            var MenuId = 301;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.Quotation = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Quotation) {

            var MenuId = 302;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Quotation) {

            var MenuId = 302;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.homemenu = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.PurchaseOrder) {

            var MenuId = 303;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.PurchaseOrder) {

            var MenuId = 303;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.ManagementApproval = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.ManagementApproval) {

            var MenuId = 304;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.ManagementApproval) {

            var MenuId = 304;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.StockTransfer = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.StockTransfer) {
            var MenuId = 305;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.StockTransfer) {
            var MenuId = 305;
            DeleteMenu(MenuId, userid);
        }
    }
    
   
    $scope.IncomingInvoice = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.IncomingInvoice) {

            var MenuId = 306;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.IncomingInvoice) {

            var MenuId = 306;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.IncomingInvoiceExcise = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.IncomingInvoiceExcise) {
            var MenuId = 307;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.IncomingInvoiceExcise) {
            var MenuId = 307;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.IncomingInvoiceNonExcise = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.IncomingInvoiceNonExcise) {
            var MenuId = 308;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.IncomingInvoiceNonExcise) {
            var MenuId = 308;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.OutgoingInvoice = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.OutgoingInvoice) {
            var MenuId = 309;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.OutgoingInvoice) {
            var userid = $scope.FormUserMenu.USER_ID;
            var MenuId = 309;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.OutgoingInvoiceExcise = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.OutgoingInvoiceExcise) {
            var MenuId = 310;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.OutgoingInvoiceExcise) {
            var MenuId = 310;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.OutgoingInvoiceNonExcise = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.OutgoingInvoiceNonExcise) {
            var MenuId = 311;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.OutgoingInvoiceNonExcise) {
            var MenuId = 311;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.PaymentEntryagainstoutgoing = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.PaymentEntryagainstoutgoing) {
            var MenuId = 312;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.PaymentEntryagainstoutgoing) {
            var MenuId = 312;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.RecurringPurchaseOrder = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.RecurringPurchaseOrder) {
            var MenuId = 313;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.RecurringPurchaseOrder) {
            var MenuId = 313;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.StockCheck = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.StockCheck) {

            var MenuId = 314;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.StockCheck) {

            var MenuId = 314;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.Reports = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Reports) {
            var MenuId = 4;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Reports) {
            var MenuId = 4;
            DeleteMenu(MenuId, userid);
        }

    }
    $scope.Search = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.Search) {
            var MenuId = 5;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.Search) {
            var MenuId = 5;
            DeleteMenu(MenuId, userid);
        }

    }
    $scope.SearchPurchaseOrder = function () {
        var userid = $scope.FormUserMenu.USER_ID;
        if ($scope.FormUserMenu.SearchPurchaseOrder) {
            var MenuId = 501;
            savemenu(MenuId, userid);
        }
        else if (!$scope.FormUserMenu.SearchPurchaseOrder) {
            var MenuId = 501;
            DeleteMenu(MenuId, userid);
        }
    }
    $scope.UserTypeChange = function () {
        
           // document.getElementById("Home").checked = false;
            document.getElementById("Masters").checked = false;
            //document.getElementById("usermast").checked = false;
            document.getElementById("unitmast").checked = false;
            document.getElementById("groupmast").checked = false;
            document.getElementById("statemast").checked = false;
            document.getElementById("citymast").checked = false;
            document.getElementById("locationmast").checked = false;
            document.getElementById("principalmast").checked = false;
            document.getElementById("suppliermast").checked = false;

            document.getElementById("itemmast").checked = false;

            document.getElementById("Buyermast").checked = false;

            document.getElementById("BusinessAction").checked = false;

            document.getElementById("Challan").checked = false;

            document.getElementById("Quotation").checked = false;

            document.getElementById("PurchaseOrder").checked = false;

            document.getElementById("ManagementApproval").checked = false;

            document.getElementById("StockCheck").checked = false;

            document.getElementById("IncomingInvoice").checked = false;

            document.getElementById("IncomingInvoiceExcise").checked = false;

            document.getElementById("IncomingInvoiceNonExcise").checked = false;

            document.getElementById("OutgoingInvoice").checked = false;

            document.getElementById("OutgoingInvoiceExcise").checked = false;

            document.getElementById("OutgoingInvoiceNonExcise").checked = false;
            document.getElementById("PaymentEntryagainstoutgoing").checked = false;

            document.getElementById("Reports").checked = false;

            document.getElementById("Search").checked = false;

            document.getElementById("SearchPurchaseOrder").checked = false;
            document.getElementById("StockCheck").checked = false;
            document.getElementById("RecurringPurchaseOrder").checked = false;
        
        var userid = $scope.FormUserMenu.USER_ID;
        alert(userid);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/UserMenu_Controller.php",
            type: "POST",
            data: { TYP: "SELECT", userid: userid },
            success: function (jsondata) {
                if (jsondata != "") {
                    // alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    // alert(objs.length);
                    var i = 0;
                    while (objs.length > 0) {
                        var menuid = objs[i]._MenuId;
                        i++;

                        if (menuid == 1) {
                            document.getElementById("Home").checked = true;
                            
                        }

                        if (menuid == 2) {
                            
                            document.getElementById("Masters").checked = true;
                        }

//                        if (menuid == 201) {
//                            
//                            document.getElementById("usermast").checked = true;
//                        }

                        if (menuid == 202) {
                            
                            document.getElementById("unitmast").checked = true;
                        }

                        if (menuid == 203) {
                        
                            document.getElementById("groupmast").checked = true;
                        }

                        if (menuid == 204) {
                           
                            document.getElementById("statemast").checked = true;
                        }

                        if (menuid == 205) {
                  
                            document.getElementById("citymast").checked = true;
                        }

                        if (menuid == 206) {
                           
                            document.getElementById("locationmast").checked = true;
                        }

                        if (menuid == 207) {
                         
                            document.getElementById("principalmast").checked = true;
                        }

                        if (menuid == 208) {
                           
                            document.getElementById("suppliermast").checked = true;
                        }

                        if (menuid == 209) {
                  
                            document.getElementById("itemmast").checked = true;
                        }

                        if (menuid == 210) {
    
                            document.getElementById("Buyermast").checked = true;
                        }

                        if (menuid == 3) {
                          
                            document.getElementById("BusinessAction").checked = true;
                        }

                        if (menuid == 301) {
                           
                            document.getElementById("Challan").checked = true;
                        }

                        if (menuid == 302) {
                          
                            document.getElementById("Quotation").checked = true;
                        }

                        if (menuid == 303) {
                          
                            document.getElementById("PurchaseOrder").checked = true;
                        }
                        
                        if (menuid == 304) {
                      
                            document.getElementById("ManagementApproval").checked = true;
                        }

                        if (menuid == 305) {
                          
                            document.getElementById("StockTransfer").checked = true;
                        }

                        if (menuid == 306) {
                           
                            document.getElementById("IncomingInvoice").checked = true;
                        }

                        if (menuid == 307) {
                           
                            document.getElementById("IncomingInvoiceExcise").checked = true;
                        }

                        if (menuid == 308) {
                           
                            document.getElementById("IncomingInvoiceNonExcise").checked = true;
                        }

                        if (menuid == 309) {
                            
                            document.getElementById("OutgoingInvoice").checked = true;
                        }

                        if (menuid == 310) {
                 
                            document.getElementById("OutgoingInvoiceExcise").checked = true;
                        }

                        if (menuid == 311) {
         
                            document.getElementById("OutgoingInvoiceNonExcise").checked = true;
                        }

                        if (menuid == 312) {
                       
                            document.getElementById("PaymentEntryagainstoutgoing").checked = true;
                        }
                        if (menuid == 313) {

                            document.getElementById("RecurringPurchaseOrder").checked = true;
                        }
                        if (menuid == 314) {

                            document.getElementById("StockCheck").checked = true;
                        }
                        if (menuid == 4) {
                     
                            document.getElementById("Reports").checked = true;
                        }

                        if (menuid == 5) {
                          
                            document.getElementById("Search").checked = true;
                        }

                        if (menuid == 501) {
                           
                            document.getElementById("SearchPurchaseOrder").checked = true;
                        }

                    }
                }
                else {


                }
            }
        });
    }
});
function savemenu(menuid, userid)
{
jQuery.ajax({
            url: "../../Controller/Master_Controller/UserMenu_Controller.php",
            type: "POST",
            data: { TYP: "INSERT", menuid: menuid, userid: userid },
            success: function (jsondata) {
                if (jsondata != "") {
                   
                }
                else {


                }
            }
        });
    }
    function DeleteMenu(MenuId, userid) {
        alert("In Delete");
        jQuery.ajax({
            url: "../../Controller/Master_Controller/UserMenu_Controller.php",
            type: "POST",
            data: { TYP: "DELETE", menuid: MenuId, userid: userid },
            success: function (jsondata) {
                if (jsondata != "") {
                  
                }
                else {


                }
            }
        });
    }
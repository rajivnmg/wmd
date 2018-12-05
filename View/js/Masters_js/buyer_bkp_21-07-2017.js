var URL = "../../Controller/Master_Controller/Buyer_Controller.php?TYP=";
var buyer_app = angular.module('buyer_app', []);
var flexCreatedForContactDeatails = false;

// create angular controller
//buyer_app.controller('buyer_Controller', function ($scope) {
buyer_app.controller('buyer_Controller', ['$scope', '$http', function buyer_Controller($scope, $http) {
    
	/* BOF to add GST details for Buyer by Ayush Giri on 13-06-2017 */
    //var Employee_Status = { _EmployeeList: [{ _title: 0,titlename:'',_first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}] ,_DiscountList: [{ _principal_id: 0,_principalname: '', _discount: 0}] };
	var Employee_Status = { _EmployeeList: [{ _title: 0,titlename:'',_first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}] ,_DiscountList: [{ _principal_id: 0,_principalname: '', _discount: 0}],_GSTList: [{ _gst_state_name: '',_gst_state_id: 0, _gst_reg_status_name: '', _gst_reg_status: 0, gst_mig_status_name: '', gst_mig_status: 0, _gst_no: '', _gst_reg_date: '', _arn_no: '', _perm_gst: ''}] };
	/* EOF to add GST details for Buyer by Ayush Giri on 13-06-2017 */
    $scope.buyer = Employee_Status;
//    var Discount_Status = { _DiscountList: [{ principalid: 0,principalname: '', discount: 0}] };
//    $scope.buyer.discountList = Discount_Status;

     $scope.AddEmployee = function () {
     $scope.buyer.titlename = $("#emp_title option:selected").text();
     $scope.buyer.deptname = $("#emp_dept option:selected").text();
        $scope.buyer._EmployeeList.push({ _title: $scope.buyer._title, titlename: $scope.buyer.titlename, _first_name: $scope.buyer._first_name, _last_name: $scope.buyer._last_name, _dept_id: $scope.buyer._dept_id, deptname: $scope.buyer.deptname, _phone: $scope.buyer._phone1,_email:$scope.buyer._email1 });
       $("#emp_title").val("");
       $("#emp_fname").val("");
       $("#emp_lname").val("");
       $("#emp_dept").val("");
       $("#emp_phon").val("");
       $("#emp_email").val("");
    }
    $scope.RemoveEmployee = function (item) {
        $scope.buyer._EmployeeList.splice($scope.buyer._EmployeeList.indexOf(item), 1);
    }

$scope.AddDiscount = function () {
$scope.buyer._principalname = $("#principalid option:selected").text();
        $scope.buyer._DiscountList.push({ _principal_id: $scope.buyer._principal_id, _principalname: $scope.buyer._principalname, _discount: $scope.buyer._discount });
          $("#principalid").val("");
           $("#buyer_discount").val("");
    }
    
    $scope.RemoveDiscount = function (item) {
        $scope.buyer._DiscountList.splice($scope.buyer._DiscountList.indexOf(item), 1);
    }
	
	/* BOF to add GST details for Buyer by Ayush Giri on 12-06-2017 */
	$scope.AddGST = function () {
		
		$scope.buyer._gst_state_name = $("#gst_state_id option:selected").text();
        $scope.buyer._gst_reg_status_name = $("#gst_reg_status option:selected").text();
        $scope.buyer._gst_mig_status_name = $("#gst_mig_status option:selected").text();
		$scope.buyer._gst_reg_date = $("#gst_reg_date").val();
		
        $scope.buyer._GSTList.push({ _gst_state_name: $scope.buyer._gst_state_name, _gst_state_id: $scope.buyer._gst_state_id, _gst_reg_status: $scope.buyer._gst_reg_status, _gst_reg_status_name: $scope.buyer._gst_reg_status_name, _gst_mig_status: $scope.buyer._gst_mig_status, _gst_mig_status_name: $scope.buyer._gst_mig_status_name, _gst_no: $scope.buyer._gst_no, _gst_reg_date: $scope.buyer._gst_reg_date, _arn_no: $scope.buyer._arn_no, _perm_gst: $scope.buyer._perm_gst });
    }
	
	$scope.RemoveGST = function (item) {
        $scope.buyer._GSTList.splice($scope.buyer._GSTList.indexOf(item), 1);
    }
	/* EOF to add GST details for Buyer by Ayush Giri on 12-06-2017 */

    $scope.init = function (BuyerId) {
		//alert('BuyerId--> ' + typeof(BuyerId) );
		if(BuyerId == '')
		{
			BuyerId = 0;
		}
		//alert('BuyerId--> ' + BuyerId );
        if (BuyerId > 0) {
			//alert('BuyerId inside if --> ' + BuyerId );
             $("#btnaddbuyer").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT_fromMasterAndPayment", BUYERID: BuyerId },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    $scope.$apply(function () {
                        $scope.buyer = objs[0];
                        $scope.buyer._EmployeeList = objs[0]._EmployeeList;
                        $scope.buyer._DiscountList = objs[0]._DiscountList;
						$scope.buyer._GSTList = objs[0]._GSTList;//To add GST details for Buyer by Ayush Giri on 12-06-2017
                        showCity(objs[0]._state_id,objs[0]._city_id);
                        showLocation(objs[0]._city_id,objs[0]._location_id);
                        jQuery.ajax({
                        url: "../../Controller/Master_Controller/Buyer_Controller.php",
                        type: "POST",
                        data: { TYP: "GET_SHIPPINGADDRESS", BUYERID: $scope.buyer._buyer_id},
                        success: function (jsondata) {
                        $scope.$apply(function () {
                        //alert(jsondata)
						//console.log(jsondata);
                        var objs = jQuery.parseJSON(jsondata);if(objs.length <= 0) return;
						console.log(objs);
                            if (objs[0]._check_add == "Y") {
                                //$scope.buyer._check_add = objs[0]._check_add;
                                $scope.buyer._check_add = true;
							}
							$scope.buyer._add1 = objs[0]._add1;
							$scope.buyer._add2 = objs[0]._add2;
							showCityForShipping1(objs[0]._shipping_state_id,objs[0]._shipping_city_id);
							showLocationForShipping1(objs[0]._shipping_city_id,objs[0]._shipping_location_id);
							$scope.buyer._shipping_pincode = objs[0]._shipping_pincode;
							$scope.buyer._shipping_phone = objs[0]._shipping_phone;
							$scope.buyer._shipping_mobile = objs[0]._shipping_mobile;
							$scope.buyer._shipping_fax = objs[0]._shipping_fax;
							$scope.buyer._shipping_email = objs[0]._shipping_email;
							$scope.buyer._shipping_country_id = objs[0]._shipping_country_id;
							$scope.buyer._shipping_state_id = objs[0]._shipping_state_id;
							$scope.buyer._shipping_city_id = objs[0]._shipping_city_id;
							$scope.buyer._shipping_location_id = objs[0]._shipping_location_id;
                             
                             if(objs.length > 1)
                             {
                                $scope.buyer.shipping2add1 = objs[1]._add1;
                                $scope.buyer.shipping2add2 = objs[1]._add2;
                                showCityForShipping2(objs[1]._shipping_state_id,objs[1]._shipping_city_id);
                                showLocationForShipping2(objs[1]._shipping_city_id,objs[1]._shipping_location_id);
                                $scope.buyer.shippingpincode2 = objs[1]._shipping_pincode;
                                $scope.buyer.shippingphone2 = objs[1]._shipping_phone;
                                $scope.buyer.shippingmobile2 = objs[1]._shipping_mobile;
                                $scope.buyer.shippingfax2 = objs[1]._shipping_fax;
                                $scope.buyer.shippingemail2 = objs[1]._shipping_email;
                                $scope.buyer.shippingcountry2 = objs[1]._shipping_country_id;
                                $scope.buyer.shippingstate2 = objs[1]._shipping_state_id;
                                $scope.buyer.shippingcity2 = objs[1]._shipping_city_id;
                                $scope.buyer.shippinglocation2 = objs[1]._shipping_location_id;
                             }
                             });
                        }
                        });
                    });
                }
            });
        }
        else {
			//alert('BuyerId inside else --> ' + BuyerId );
            $scope.buyer._DiscountList.splice($scope.buyer._DiscountList.indexOf(0), 1);
            $scope.buyer._EmployeeList.splice($scope.buyer._EmployeeList.indexOf(0), 1);
			$scope.buyer._GSTList.splice($scope.buyer._GSTList.indexOf(0), 1);//To add GST details for Buyer by Ayush Giri on 12-06-2017
            $("#btnupdatebuyer").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "AUTOCODE" },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $("#buyercode").val(objs);
                    $scope.$apply(function () {
                        $scope.buyer._buyer_code = objs;
                    });
                }
            });
        }
    }

    $scope.change = function () {
        if ($scope.buyer._check_add) {
                $scope.buyer._check_add = true;
                $scope.buyer._add1 = $scope.buyer._bill_add1;
                $scope.buyer._add2 = $scope.buyer._bill_add2;
                showCityForShipping1($scope.buyer._state_id,$scope.buyer._city_id);
                showLocationForShipping1($scope.buyer._city_id,$scope.buyer._location_id);
                $scope.buyer._shipping_pincode = $scope.buyer._pincode;
                $scope.buyer._shipping_phone = $scope.buyer._phone;
                $scope.buyer._shipping_mobile = $scope.buyer._mobile;
                $scope.buyer._shipping_fax = $scope.buyer._fax;
                $scope.buyer._shipping_email = $scope.buyer._email;
                $scope.buyer._shipping_country_id = $scope.buyer._country_id;
                $scope.buyer._shipping_state_id = $scope.buyer._state_id;
                $scope.buyer._shipping_city_id = $scope.buyer._city_id;
                $scope.buyer._shipping_location_id = $scope.buyer._location_id;
            }
            else
            {
                $scope.buyer._check_add = null;
                $scope.buyer._add1 = "";
                $scope.buyer._add2 = "";
                $scope.buyer._shipping_pincode = "";
                $scope.buyer._shipping_phone = "";
                $scope.buyer._shipping_mobile = "";
                $scope.buyer._shipping_fax = "";
                $scope.buyer._shipping_email = "";
                $scope.buyer._shipping_country_id = "";
                $scope.buyer._shipping_state_id = "";
                $scope.buyer._shipping_city_id = "";
                $scope.buyer._shipping_location_id = "";
            }
    }

    $scope.submitForm = function () {
        if ($scope.buyer._check_add) {
            $scope.buyer._check_add = "Y";
        }
         $("#btnaddbuyer").hide();
        var json_string = JSON.stringify($scope.buyer);
		console.log(json_string);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "INSERT", BUYERDATA: json_string },
            success: function (jsondata) {
				//alert(jsondata);
                if (jsondata != "") {
                $scope.$apply(function () {
                        $scope.buyer = null;
                        location.href = "ViewBuyer.php";
                    });
                }
                else {
                }
            }
        });
    }
    $scope.Update = function () {
    if ($scope.buyer._check_add) {
            $scope.buyer._check_add = "Y";
        }
       $("#btnupdatebuyer").hide();
        var json_string = JSON.stringify($scope.buyer);
        //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "UPDATE", BUYERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                $scope.$apply(function () {
                        $scope.buyer = null;
                        location.href = "ViewBuyer.php";
                    });
                }
                else {
                }
            }
        });
    }
}]);
 buyer_app.directive('validNumber', function () {
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
//document.getElementById('btnupdatebuyer').style.visibility='hidden'; // hide 
function showCity(State_Id,cityid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/City_Controller.php",type: "POST",
            data: { TYP: TYPE ,TAG:"STATE", STATEID :State_Id },success: function (jsondata) {$('#city').empty();
             $("#city").append("<option value=''>Select City</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#city").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");}
                    $("#city").val(cityid);}}});
                    }}

function showCityForShipping1(State_Id,cityid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/City_Controller.php",type: "POST",
            data: { TYP: TYPE ,TAG:"STATE", STATEID :State_Id },success: function (jsondata) {$('#shppingcity1').empty();
            $("#shppingcity1").append("<option value=''>Select City</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#shppingcity1").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");}
                    $("#shppingcity1").val(cityid);}}});
                    
                    }}


function showCityForShipping2(State_Id,cityid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/City_Controller.php",type: "POST",
            data: { TYP: TYPE ,TAG:"STATE", STATEID :State_Id },success: function (jsondata) {$('#shppingcity2').empty();
            $("#shppingcity2").append("<option value=''>Select City</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#shppingcity2").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");}
                    $("#shppingcity2").val(cityid);}}});
                    }}

function showLocation(City_Id,locationid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/LocationController.php",type: "POST",
            data: { TYP: TYPE , CITYID :City_Id },success: function (jsondata) {$('#location').empty();
            $("#location").append("<option value=''>Select Location</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#location").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");}
                    $("#location").val(locationid);}}});
                    }}

function showLocationForShipping1(City_Id,locationid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/LocationController.php",type: "POST",
            data: { TYP: TYPE , CITYID :City_Id },success: function (jsondata) {$('#shppinglocation1').empty();
            $("#shppinglocation1").append("<option value=''>Select Location</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#shppinglocation1").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");}
                    $("#shppinglocation1").val(locationid);}}});
                    }}
function showLocationForShipping2(City_Id,locationid){var TYPE = "SELECT";if (true) {
        jQuery.ajax({url: "../../Controller/Master_Controller/LocationController.php",type: "POST",
            data: { TYP: TYPE , CITYID :City_Id },success: function (jsondata) {$('#shppinglocation2').empty();
            $("#shppinglocation2").append("<option value=''>Select Location</option>");
            var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
                    $("#shppinglocation2").append("<option value=\"" + obj._location_id + "\">" + obj._locationName + "</option>");}
                    $("#shppinglocation2").val(locationid);}}});
                    }}
function LoadBuyer(){
$(".flexme4").flexigrid({
                url : '../../Controller/Master_Controller/Buyer_Controller.php',
                dataType : 'json',
                colModel : [ {display : 'Buyer Id',name : 'BuyerId', width : 50, sortable : true, align : 'center' }, 
                             {display : 'Buyer Code',name : 'BuyerCode', width : 120,sortable : true,align : 'left' },    
                             {display : 'Buyer Name',name : 'BuyerName',width : 120, sortable : true, align : 'left'},
                             {display : 'Vendor Code',name : 'Vendor_Code', width : 120,sortable : true,align : 'left'},
                             
                             {display : 'Buyer Range',name : 'Buyer_Range', width : 90, sortable : true, align : 'center' }, 
                             {display : 'Division',name : 'Division', width : 120,sortable : true,align : 'left' },    
                             {display : 'Commissione Rate',name : 'Commissionerate',width : 120, sortable : true, align : 'left'},
                             {display : 'ECC',name : 'ECC', width : 120,sortable : true,align : 'left'},
                             
                             {display : 'TIN',name : 'TIN', width : 90, sortable : true, align : 'center' }, 
                             {display : 'PAN',name : 'PAN', width : 120,sortable : true,align : 'left' },    
                             {display : 'Bill_Add1',name : 'Bill_Add1',width : 120, sortable : true, align : 'left'},
                             {display : 'Bill_Add2',name : 'Bill_Add2', width : 120,sortable : true,align : 'left'},
                             //12
                             {display : 'StateId',name : 'StateId', width : 90, sortable : true, align : 'center' }, 
                             {display : 'CityId',name : 'CityId', width : 120,sortable : true,align : 'left' },    
                             //{display : 'CountryId',name : 'CountryId',width : 120, sortable : true, align : 'left'},
                             {display : 'LocationId',name : 'LocationId', width : 120,sortable : true,align : 'left'}, 

                             {display : 'StateName',name : 'StateName', width : 90, sortable : true, align : 'center' }, 
                             {display : 'CityName',name : 'CityName', width : 120,sortable : true,align : 'left' },
                             {display : 'LocationName',name : 'LocationName', width : 120,sortable : true,align : 'left'}, 

                             {display : 'Pincode',name : 'Pincode', width : 90, sortable : true, align : 'center' }, 
                             {display : 'Phone',name : 'Phone', width : 120,sortable : true,align : 'left' },    
                             {display : 'Mobile',name : 'Mobile',width : 120, sortable : true, align : 'left'},
                             {display : 'FAX',name : 'FAX', width : 120,sortable : true,align : 'left'},
                             
                             {display : 'Email',name : 'Email', width : 90, sortable : true, align : 'center' }, 
                             {display : 'Executive_ID',name : 'Executive_ID', width : 120,sortable : true,align : 'left' },    
                             {display : 'Credit_Period',name : 'Credit_Period',width : 120, sortable : true, align : 'left'},
                             {display : 'Tax_Type',name : 'Tax_Type', width : 120,sortable : true,align : 'left'}, 
                             {display : 'Remarks',name : 'Remarks', width : 120,sortable : true,align : 'left'}
                           ],
                buttons : [{name : 'New',bclass : 'new',onpress : EditBuyer},{name : 'Edit',bclass : 'edit',onpress : EditBuyer},
                           {name : 'Contact Details',bclass : 'contact',onpress : EditBuyer},{name : 'Buyer Discount',bclass : 'discount',onpress : EditBuyer},
                {separator : true}],
                searchitems : [ {display : 'Buyer Id',name : 'BUYERID'}, {display : 'Buyer Name',name : 'BUYERNAME',isdefault : false}],
                sortorder : "asc",
                usepager : false,
                //title : 'Buyer Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1500,
                height : 300,
                
            });
}
//LoadBuyer();

function EditBuyer(com, grid) {
        if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    $("#ShowData_Div").hide();
                    $("#Form_Div").show(); 
                    $("#BuyerDiscount_Div").hide();
                    $("#ContactInfo_Div").hide(); 
                    var BuyerId = value.children[0].innerText||value.children[0].textContent;
                    if (BuyerId > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: "SELECT", BUYERID: BuyerId},
            success: function (jsondata) {
                if (jsondata != "") {
                    var objs = jQuery.parseJSON(jsondata);
                    document.getElementById("buyer_id").value = objs[0]._buyer_id; 
                    document.getElementById("buyercode").value = objs[0]._buyer_code; 
                    document.getElementById("buyer_nm").value = objs[0]._buyer_name; 
                    document.getElementById("vendercode").value = objs[0]._vendor_code; 
                    document.getElementById("range").value = objs[0]._buyer_range; 
                    document.getElementById("division").value = objs[0]._division; 
                    document.getElementById("commissionrate").value = objs[0]._commission_rate; 
                    document.getElementById("ecc").value = objs[0]._ecc; 
                    document.getElementById("tin").value = objs[0]._tin; 
                    document.getElementById("pan").value = objs[0]._pan; 
                    document.getElementById("buyer_add1").value = objs[0]._bill_add1; 
                    document.getElementById("buyer_add2").value = objs[0]._bill_add2; 
                    document.getElementById("pincode").value = objs[0]._pincode; 
                    document.getElementById("phone").value = objs[0]._phone; 
                    document.getElementById("mobile").value = objs[0]._mobile; 
                    document.getElementById("fax").value = objs[0]._fax; 
                    document.getElementById("email").value = objs[0]._email; 
                    document.getElementById("credit").value = objs[0]._credit_period; 
                    document.getElementById("comm").value = objs[0]._remarks;
                    document.getElementById("Credit_Limit").value = objs[0].Credit_Limit;

                    $("#buyer_country").val(objs[0]._country_id);
                    $("#buyer_executive").val(objs[0]._executive_id);
                    $("#txntype").val(objs[0]._tax_type);
                    $("#Buyer_Level").val(objs[0].Buyer_Level);

                    var stateid = objs[0]._state_id;
                    $("#state").val(stateid);
                    var cityid = objs[0]._city_id;
                    var locationid = objs[0]._location_id;
                    showCity(stateid,cityid);
                    showLocation(cityid,locationid);
                    $("#state").attr("disabled", "disabled");
                    $("#city").attr("disabled", "disabled");
                    $("#location").attr("disabled", "disabled");

                    jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: "GET_SHIPPINGADDRESS", BUYERID: BuyerId},
            success: function (jsondata) {
                if (jsondata != "") {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    if(jsondata == "[]"){
						var ship1state = objs[0]._shipping_state_id;
	                    var ship1city = objs[0]._shipping_city_id;
	                    var ship1location = objs[0]._shipping_location_id;
	                    showCityForShipping1(ship1state,ship1city);
	                    showLocationForShipping1(ship1city,ship1location);
	                    document.getElementById("shipping1_addressid").value = objs[0]._shipping_buyerid; 
	                    document.getElementById("ship1_add1").value = objs[0]._add1; 
	                    document.getElementById("ship1_add2").value = objs[0]._add2; 
	                    document.getElementById("shppingcountry1").value = objs[0]._shipping_country_id; 
	                    document.getElementById("shppingstate1").value = objs[0]._shipping_state_id; 
	                    document.getElementById("shppingcity1").value = objs[0]._shipping_city_id; 
	                    document.getElementById("shppinglocation1").value = objs[0]._shipping_location_id; 
	                    document.getElementById("ship1_pincode").value = objs[1]._shipping_pincode; 
	                    document.getElementById("ship1_phon").value = objs[1]._shipping_phone; 
	                    document.getElementById("ship1_mobile").value = objs[1]._shipping_mobile; 
	                    document.getElementById("ship1_fax").value = objs[1]._shipping_fax;
	                    document.getElementById("ship1_email").value = objs[1]._shipping_email;
	                    $("#shppingcountry1").attr("disabled", "disabled");
	                    $("#shppingstate1").attr("disabled", "disabled");
	                    $("#shppingcity1").attr("disabled", "disabled");
	                    $("#shppinglocation1").attr("disabled", "disabled");
					}
                    
                    if(objs.length == 2)
                    {
                    var ship2state = objs[1]._shipping_state_id;
                    var ship2city = objs[1]._shipping_city_id;
                    var ship2location = objs[1]._shipping_location_id;
                    showCityForShipping2(ship2state,ship2city);
                    showLocationForShipping2(ship2city,ship2location);
                    document.getElementById("shipping2_addressid").value = objs[1]._shipping_buyerid; 
                    document.getElementById("ship2_add1").value = objs[1]._add1; 
                    document.getElementById("ship2_add2").value = objs[1]._add2; 
                    document.getElementById("shppingcountry2").value = objs[1]._shipping_country_id; 
                    document.getElementById("shppingstate2").value = objs[1]._shipping_state_id; 
                    document.getElementById("shppingcity2").value = objs[1]._shipping_city_id; 
                    document.getElementById("shppinglocation2").value = objs[1]._shipping_location_id; 
                    document.getElementById("ship2_pincode").value = objs[1]._shipping_pincode; 
                    document.getElementById("ship2_phon").value = objs[1]._shipping_phone; 
                    document.getElementById("ship2_mobile").value = objs[1]._shipping_mobile; 
                    document.getElementById("ship2_fax").value = objs[1]._shipping_fax;
                    document.getElementById("ship2_email").value = objs[1]._shipping_email; 
                    $("#shppinglocation2").attr("disabled", "disabled");
                    $("#shppingcity2").attr("disabled", "disabled");
                    $("#shppingstate2").attr("disabled", "disabled");
                    $("#shppingcountry2").attr("disabled", "disabled");
                    }
                }
            }
        });




                }
            }
        });
    }
            });    
        }
    }
    else if (com == 'New') {
        if(true){
            $("#ShowData_Div").hide();
            $("#Form_Div").show(); 
            $("#BuyerDiscount_Div").hide();
            $("#ContactInfo_Div").hide();  
            document.getElementById("state").value = 0;
document.getElementById("city").value = 0; 
document.getElementById("location").value = 0;
        }
    }
    else if (com == 'Contact Details') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    var id =  value.children[0].innerText||value.children[0].textContent;
                     
                    document.getElementById("buyer_id_for_employee").value = value.children[0].innerText||value.children[0].textContent;
                    if(!flexCreatedForContactDeatails)
                        LoadPrincipalContact(id);
                        else
                        var urlnew22 = '../../Controller/Master_Controller/Buyer_Controller.php?TYP=GET_CONTECTPERSON&BUYERID=' + id;
          $('.loademp').flexOptions({ url: urlnew22 }).flexReload();
            });
            
            $("#ShowData_Div").hide();
            $("#Form_Div").hide(); 
            $("#BuyerDiscount_Div").hide();
            $("#ContactInfo_Div").show();    
        }
    }
    else if (com == 'Buyer Discount') {
        if(true){
            $("#ShowData_Div").hide();
            $("#Form_Div").hide(); 
            $("#BuyerDiscount_Div").show();
            $("#ContactInfo_Div").hide();  
            $.each($('.trSelected', grid),
                function(key, value){
                    var id =  value.children[0].innerText||value.children[0].textContent;
                    document.getElementById("buyerid_for_discount").value = value.children[0].innerText||value.children[0].textContent;
                    LoadPrincipalDiscount(0,id);
                    $(".loaddiscount").flexReload();
            });   
        }
    }
}

function LoadPrincipalContact(id){
//flexCreatedForContactDeatails = true;
var urlnew = '../../Controller/Master_Controller/Buyer_Controller.php?TYP=GET_CONTECTPERSON&BUYERID=' + id;
$(".loademp").flexigrid({
                url : urlnew,
                dataType : 'json',
                colModel : [ {
                    display : 'Contact ID',
                    name : 'BUYERContactID',
                    width : 90,
                    sortable : true,
                    align : 'center'
                    }, {
                        display : 'Title',
                        name : 'TITLE',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    }, {
                        display : 'First NAME',
                        name : 'FNAME',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Last NAME',
                        name : 'LNAME',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Deptt',
                        name : 'DEPT',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Phone',
                        name : 'PHONE',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display : 'Email',
                        name : 'EMAIL',
                        width : 120,
                        sortable : true,
                        align : 'left'
                    },
                     ],
                buttons : [ 
                    {
                        name : 'Edit',
                        bclass : 'edit',
                        onpress : EditPrincipalContact
                    }
                    ,
                    {
                        separator : true
                    } 
                ],
                searchitems : [ {
                    display : 'City Id',
                    name : 'CITYID'
                    }, {
                        display : 'City Name',
                        name : 'CITYNAME',
                        isdefault : true
                } ],
                sortorder : "asc",
                usepager : true,
                //title : 'Principal Contact Person',
                useRp : true,
                rp : 15,
                showTableToggleBtn : true,
                width : 750,
                height : 200,
                
            });
}
function EditPrincipalContact(com, grid) {
         if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    // collect the data
                    var Empid = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
                    if (Empid > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: "GET_CONTECTPERSON_DETAILS", EMPID: Empid},
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    //alert(jsondata);
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(objs);
                    document.getElementById("buyer_id_for_employee").value = objs[0]._buyer_contact_id; 
                    document.getElementById("emp_title").value = objs[0]._title;
                    document.getElementById("emp_fname").value = objs[0]._first_name;
                    document.getElementById("emp_lname").value = objs[0]._last_name;
                    document.getElementById("emp_dept").value = objs[0]._dept_id;
                    document.getElementById("emp_phon").value = objs[0]._phone;
                    document.getElementById("emp_email").value = objs[0]._email;
                }
                else {
                }
            }
        });
    }


                                
            });    
        }
    }
}
function AddEmployee()
{
var TYPE = "ADD_CONTECTPERSON";
    var buyer_id = document.getElementById("buyer_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
    var emp_title = document.getElementById("emp_title").value;
    var emp_fname = document.getElementById("emp_fname").value;
    var emp_lname = document.getElementById("emp_lname").value;
    var emp_dept = document.getElementById("emp_dept").value;
    var emp_phon = document.getElementById("emp_phon").value;
    var emp_email = document.getElementById("emp_email").value;
    if (buyer_id > 0 && emp_title != "") {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: TYPE, BUYERID: buyer_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email  },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    
                }
                else {
                }
            }
        });
    }
    $(".loademp").flexReload();
}
function UpdateEmployee()
{
var TYPE = "UPDATE_CONTECTPERSON";
    var buyer_id = document.getElementById("buyer_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
    var emp_title = document.getElementById("emp_title").value;
    var emp_fname = document.getElementById("emp_fname").value;
    var emp_lname = document.getElementById("emp_lname").value;
    var emp_dept = document.getElementById("emp_dept").value;
    var emp_phon = document.getElementById("emp_phon").value;
    var emp_email = document.getElementById("emp_email").value;
    if (buyer_id > 0 && emp_title != "") {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: TYPE, BUYERCONTACTID: buyer_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email  },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    
                }
                else {
                }
            }
        });
    }
    $(".loademp").flexReload();
}
function CancelEmployee(){
$("#Form_Div").hide();
$("#ContactInfo_Div").hide();
$("#BuyerDiscount_Div").hide();
$("#ShowData_Div").show();
 document.getElementById("buyer_id_for_employee").value ="";
 $(".flexme4").flexReload();
}

function CancelBuyerDiscount(){
$("#Form_Div").hide();
$("#ContactInfo_Div").hide();
$("#BuyerDiscount_Div").hide();
$("#ShowData_Div").show();
$(".flexme4").flexReload();
}

function AddBuyerDiscount()
{
    var TYPE = "ADD_DISCOUNT";
    var buyer_id = document.getElementById("buyerid_for_discount").value.replace(/(\r\n|\n|\r)/gm,"");
    var principalid = document.getElementById("principalid").value.replace(/(\r\n|\n|\r)/gm,"");
    var buyer_discount = document.getElementById("buyer_discount").value.replace(/(\r\n|\n|\r)/gm,"");
    if (buyer_id > 0 && emp_title != "") {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: TYPE, BUYERID: buyer_id, PRINCIPALID : principalid, BUYER_DISCOUNT: buyer_discount },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    
                }
                else {
                }
            }
        });
    }
    $(".loaddiscount").flexReload();
}
function UpdateBuyerDiscount()
{
var TYPE = "Update_DISCOUNT";
    var buyer_id = document.getElementById("buyerid_for_discount").value.replace(/(\r\n|\n|\r)/gm,"");
    var principalid = document.getElementById("principalid_for_discount").value.replace(/(\r\n|\n|\r)/gm,"");
    var buyer_discount = document.getElementById("buyer_discount").value.replace(/(\r\n|\n|\r)/gm,"");
    if (principalid > 0 && buyer_discount > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: TYPE, BUYERID: buyer_id, PRINCIPALID : principalid, BUYER_DISCOUNT: buyer_discount },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    
                }
                else {
                }
            }
        });
    }
    $(".loaddiscount").flexReload();
}
function LoadPrincipalDiscount(principalid,buyerid){
$(".loaddiscount").flexigrid({
                url : '../../Controller/Master_Controller/Buyer_Controller.php?TYP=GET_DISCOUNT&PRINCIPAL_SUPPLIER_ID=' + principalid + '&BUYER_ID='+buyerid,
                dataType : 'json',
                colModel : [ {
                    display : 'Buyer ID',
                    name : 'BUYERID',
                    width : 50,
                    sortable : true,
                    align : 'center'
                    }, {
                        display : 'PRINCIPAL ID',
                        name : 'PRINCIPAL_SUPPLIER_ID',
                        width : 250,
                        sortable : true,
                        align : 'left'
                    }, {
                        display : 'Principal Name',
                        name : 'Principal_Supplier_Name',
                        width : 250,
                        sortable : true,
                        align : 'left'
                    },{
                        display : 'Discount',
                        name : 'DISCOUNT',
                        width : 250,
                        sortable : true,
                        align : 'left'
                    },
                     ],
                buttons : [ 
                    {
                        name : 'Edit',
                        bclass : 'edit',
                        onpress : EditPrincipalDiscount
                    }
                    ,
                    {
                        separator : true
                    } 
                ],
                searchitems : [ {
                    display : 'City Id',
                    name : 'CITYID'
                    }, {
                        display : 'City Name',
                        name : 'CITYNAME',
                        isdefault : true
                } ],
                sortorder : "asc",
                usepager : true,
                //title : 'Principal Discount',
                useRp : true,
                rp : 15,
                showTableToggleBtn : true,
                width : 800,
                height : 200,
                
            });
}
function EditPrincipalDiscount(com, grid) {
         if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    // collect the data
                    var BuyerId = value.children[0].innerText||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
                    var PrincipalID = value.children[1].innerText||value.children[1].textContent.replace(/(\r\n|\n|\r)/gm,"");
                    

                    if (BuyerId > 0 && PrincipalID > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: "GET_DISCOUNT_DETAILS", BUYERID: BuyerId, PRINCIPALID : PrincipalID },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    var objs = jQuery.parseJSON(jsondata);
                    document.getElementById("buyerid_for_discount").value = objs[0]._buyer_id; 
                    document.getElementById("principalid_for_discount").value = objs[0]._principal_id;
                    document.getElementById("buyer_discount").value = objs[0]._discount;
                }
                else {
                }
            }
        });
    }

                                
            });    
        }
    }
}

function UpdateBuyer()
{
var TYPE = "UPDATE";
    var buyer_id = document.getElementById("buyer_id").value.replace(/(\r\n|\n|\r)/gm,"");
    var name =  document.getElementById("buyer_nm").value; 
    var vendor =  document.getElementById("vendercode").value; 
    var range =  document.getElementById("range").value; 
    var division =  document.getElementById("division").value; 
    var commission =  document.getElementById("commissionrate").value; 
    var ecc =  document.getElementById("ecc").value; 
    var tin =  document.getElementById("tin").value; 
    var pan =  document.getElementById("pan").value; 
    var add1 =  document.getElementById("buyer_add1").value; 
    var add2 =  document.getElementById("buyer_add2").value; 
    var pincode =  document.getElementById("pincode").value; 
    var phone =  document.getElementById("phone").value; 
    var mobile =  document.getElementById("mobile").value; 
    var fax =  document.getElementById("fax").value; 
    var email = document.getElementById("email").value; 
    var credit = document.getElementById("credit").value; 
    var comment = document.getElementById("comm").value;
    var creditlimit = document.getElementById("Credit_Limit").value; 
    var buyerlevel = document.getElementById("Buyer_Level").value;
    var ship_addid1 = document.getElementById("shipping1_addressid").value;
    var ship1_add1 = document.getElementById("ship1_add1").value;
    var ship1_add2 = document.getElementById("ship1_add2").value;
    var ship1_country = document.getElementById("shppingcountry1").value;
    var ship1_state = document.getElementById("shppingstate1").value;
    var ship1_city = document.getElementById("shppingcity1").value;
    var ship1_location = document.getElementById("shppinglocation1").value;
    var ship1_pincode = document.getElementById("ship1_pincode").value;
    var ship1_phone = document.getElementById("ship1_phon").value;
    var ship1_mobile = document.getElementById("ship1_mobile").value;
    var ship1_fax = document.getElementById("ship1_fax").value;
    var ship1_email = document.getElementById("ship1_email").value;
    var checkadd1 = document.getElementById("checkbox4").value;
    var ship_addid2 = document.getElementById("shipping2_addressid").value;
    var ship2_add1 = document.getElementById("ship2_add1").value;
    var ship2_add2 = document.getElementById("ship2_add2").value;
    var ship2_country = document.getElementById("shppingcountry2").value;
    var ship2_state = document.getElementById("shppingstate2").value;
    var ship2_city = document.getElementById("shppingcity2").value;
    var ship2_location = document.getElementById("shppinglocation2").value;
    var ship2_pincode = document.getElementById("ship2_pincode").value;
    var ship2_phone = document.getElementById("ship2_phon").value;
    var ship2_mobile = document.getElementById("ship2_mobile").value;
    var ship2_fax = document.getElementById("ship2_fax").value;
    var ship2_email = document.getElementById("ship2_email").value;
    var checkadd2 = 'S';

    $("#shppinglocation2").removeAttr("disabled");
    $("#shppingcity2").removeAttr("disabled");
    $("#shppingstate2").removeAttr("disabled");
    $("#shppingcountry2").removeAttr("disabled");

    $("#shppinglocation1").removeAttr("disabled");
    $("#shppingcity1").removeAttr("disabled");
    $("#shppingstate1").removeAttr("disabled");
    $("#shppingcountry1").removeAttr("disabled");

    $("#state").removeAttr("disabled");
    $("#city").removeAttr("disabled");
    $("#location").removeAttr("disabled");


    if (buyer_id > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Buyer_Controller.php",
            type: "POST",
            data: { TYP: TYPE, buyerid: buyer_id, name : name, vendorcode: vendor
                    , range: range, division : division, commissionrate: commission, ecccode: ecc, tinno : tin, panno: pan
                    , add1: add1, add2 : add2, pincode: pincode, phonno: phone, mobileno : mobile, fax: fax,
                    email: email, creditperiod : credit,buyer_level: buyerlevel,creditlimit: creditlimit, comment: comment,SHIP1ADD1:ship1_add1,SHIP1ADD2:ship1_add2,SHIP2ADD1:ship2_add1,SHIP2ADD2:ship2_add2,CHECKADD1:checkadd1,CHECKADD2:checkadd2,SHIPADDID1:ship_addid1,SHIPADDID2:ship_addid2,SHIP1COUNTRY:ship1_country,SHIP2COUNTRY:ship2_country,SHIP1STATE:ship1_state,SHIP2STATE:ship2_state, SHIP1CITY: ship1_city,SHIP2CITY: ship2_city,SHIP1LOCATION: ship1_location,SHIP2LOCATION : ship2_location,SHIP1PINCODE : ship1_pincode,SHIP2PINCODE : ship2_pincode,SHIP1PHON : ship1_phone,SHIP2PHON : ship2_phone,SHIP1MOBILE : ship1_mobile,SHIP2MOBILE : ship2_mobile,SHIP1FAX : ship1_fax,SHIP2FAX : ship2_fax,SHIP1EMAIL : ship1_email,SHIP2EMAIL : ship2_email},
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    $("#Form_Div").hide();
                    $("#ContactInfo_Div").hide();
                    $("#BuyerDiscount_Div").hide();
                    $("#ShowData_Div").show();
                }
                else {
                }
            }
        });
    }
    $(".flexme4").flexReload();
}

buyer_app.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('buyer._commission_rate', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.buyer._commission_rate = oldValue;
                }
            });
        }
    };
});
buyer_app.directive('isNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope) {
            scope.$watch('buyer._discount', function (newValue, oldValue) {

                if (newValue == undefined) {
                    newValue = "";
                }
                if (oldValue == undefined) {
                    oldValue = "";
                }
                //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.')) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.buyer._discount = oldValue;
                }
            });
        }
    };
});

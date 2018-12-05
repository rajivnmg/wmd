var URL = "../../Controller/Master_Controller/Principal_Controller.php?TYP=";
var principal_app = angular.module('principal_app', []);
// create angular controller
principal_app.controller('principal_Controller', ['$scope', '$http', function principal_Controller($scope, $http) {
    
	/* BOF to add GST details for Principal by Ayush Giri on 13-06-2017 */
    //var Employee_Status = { _EmployeeList: [{ _title: 0, titlename: '', _first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}] };
	var Employee_Status = { _EmployeeList: [{ _title: 0, titlename: '', _first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}],_GSTList: [{ _gst_state_name: '',_gst_state_id: 0, _gst_reg_status_name: '', _gst_reg_status: 0, gst_mig_status_name: '', gst_mig_status: 0, _gst_no: '', _gst_reg_date: '', _arn_no: '', _perm_gst: ''}]  };
	/* EOF to add GST details for Principal by Ayush Giri on 13-06-2017 */
    $scope.principal = Employee_Status;

    $scope.AddEmployee = function () {
		//alert('CHECK ADD Employee 1');
        $scope.principal.titlename = $("#emp_title option:selected").text();
        $scope.principal.deptname = $("#emp_dept option:selected").text();
		//alert('CHECK ADD Employee 2');
        $scope.principal._EmployeeList.push({ _title: $scope.principal._title, titlename: $scope.principal.titlename, _first_name: $scope.principal._first_name, _last_name: $scope.principal._last_name, _dept_id: $scope.principal._dept_id, deptname: $scope.principal.deptname, _phone: $scope.principal._phone1, _email: $scope.principal._email1 });
		//$scope.principal._EmployeeList.push({ _title: '', titlename: '', _first_name: '', _last_name: '', _dept_id: '', deptname: '', _phone: '', _email: '' });
		//alert('CHECK ADD Employee 3');
    }

    $scope.RemoveEmployee = function (item) {
        $scope.principal._EmployeeList.splice($scope.principal._EmployeeList.indexOf(item), 1);
    }
	/* BOF to add GST details for Principal by Ayush Giri on 12-06-2017 */
	//var GST_Status = { _GSTList: [{ _gst_state_id: '', _gst_reg_status: '', gst_mig_status: '', _gst_no: '', _gst_reg_date: '', _arn_no: '', _perm_gst: ''}] };
    //$scope.principal = GST_Status;
	
	$scope.AddGST = function () {
		//alert('CHECK ADD GST 1');
		$scope.principal._gst_state_name = $("#gst_state_id option:selected").text();
        $scope.principal._gst_reg_status_name = $("#gst_reg_status option:selected").text();
        $scope.principal._gst_mig_status_name = $("#gst_mig_status option:selected").text();
		$scope.principal._gst_reg_date = $("#gst_reg_date").val();
		
		/* $scope.principal._gst_state_id = $("#gst_state_id option:selected").val();
        $scope.principal._gst_reg_status = $("#gst_reg_status option:selected").val();
        $scope.principal._gst_mig_status = $("#gst_mig_status option:selected").val(); */
		//alert('CHECK ADD GST 2');
        //$scope.principal._GSTList.push({ _gst_state_id: $scope.principal._gst_state_id, _gst_reg_status: $scope.principal._gst_reg_status, _gst_mig_status: $scope.principal._gst_mig_status, _gst_no: $scope.principal._gst_no, _gst_reg_date: $scope.principal._gst_reg_date, _arn_no: $scope.principal._arn_no, _perm_gst: $scope.principal._perm_gst });
		$scope.principal._GSTList.push({ _gst_state_name: $scope.principal._gst_state_name, _gst_state_id: $scope.principal._gst_state_id, _gst_reg_status: $scope.principal._gst_reg_status, _gst_reg_status_name: $scope.principal._gst_reg_status_name, _gst_mig_status: $scope.principal._gst_mig_status, _gst_mig_status_name: $scope.principal._gst_mig_status_name, _gst_no: $scope.principal._gst_no, _gst_reg_date: $scope.principal._gst_reg_date, _arn_no: $scope.principal._arn_no, _perm_gst: $scope.principal._perm_gst });
		//alert('CHECK ADD GST 3');
    }
	
	$scope.RemoveGST = function (item) {
        $scope.principal._GSTList.splice($scope.principal._GSTList.indexOf(item), 1);
    }
	/* EOF to add GST details for Principal by Ayush Giri on 12-06-2017 */

    $scope.init = function (PrincipalId) {
        if (PrincipalId > 0) {
            $("#btnaddprincipal").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT", PRINCIPALID: PrincipalId },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    $scope.$apply(function () {
                        $scope.principal = objs[0];
                        $scope.principal._EmployeeList = objs[0]._EmployeeList;
                        showCity(objs[0]._state_id, objs[0]._city_id);
						$scope.principal._GSTList = objs[0]._GSTList; //To add GST details for Principal by Ayush Giri on 12-06-2017
                    });
                }
            });
        }
        else {
            $scope.principal._EmployeeList.splice($scope.principal._EmployeeList.indexOf(0), 1);
			$scope.principal._GSTList.splice($scope.principal._GSTList.indexOf(0), 1); //To add GST details for Principal by Ayush Giri on 12-06-2017
            $("#btnupdateprincipal").hide();
        }
    }
    $scope.submitForm = function () {
		if(!$scope.principal._state_id){
			alert('Please Select State'); return false ;
		}
		
		 $("#btnaddprincipal").hide();
        var json_string = JSON.stringify($scope.principal);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "INSERT", PRINCIPALDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.principal = null;
                        location.href = "ViewPrincipal.php";
                        //$('#city').empty(); $("#city").append("<option >Select City</option>");
                    });
                }
                else {
                }
            }
        });
    }
    $scope.Update = function () {
		
		if(!$scope.principal._state_id){
			alert('Please Select State'); return false ;
		}
		$("#btnupdateprincipal").hide();
		
        var json_string = JSON.stringify($scope.principal);
        //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "UPDATE", PRINCIPALDATA: json_string },
            success: function (jsondata) {
				//alert(jsondata);
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.principal = null;
                        location.href = "ViewPrincipal.php";
                    });
                }
                else {
                }
            }
        });
    }
} ]);
principal_app.directive('validNumber', function () {
    return {
        //require: '?ngModel',
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
function showCity(State_Id,cityId) {
    var TYPE = "SELECT";
    if (State_Id > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/City_Controller.php",
            type: "POST",
            data: { TYP: TYPE,TAG:"STATE", STATEID: State_Id },
            //cache: false,
            success: function (jsondata) {
                $('#city').empty();
                $("#city").append("<option >Select City</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj;
                    for (var i = 0; i < objs.length; i++) {
                        //for (index in objs) {
                        var obj = objs[i];
                        //var index = 0;
                        $("#city").append("<option value=\"" + obj._city_id + "\">" + obj._city_nameame + "</option>");
                    }
                    $('#city').val(cityId);
                }
                else {

                }
            }
        });
    }
    else {
        $('#city').empty(); $("#city").append("<option >Select City</option>");
    }
}
//function LoadPrincipal(){
//$(".flexme4").flexigrid({
//                url : '../../Controller/Master_Controller/Principal_Controller.php',
//                dataType : 'json',
//                colModel : [ {
//                    display : 'Principal ID',
//                    name : 'PRINCIPALID',
//                    width : 50,
//                    sortable : true,
//                    align : 'center'
//                    }, {
//                        display : 'Principal NAME',
//                        name : 'PRINCIPALNAME',
//                        width : 70,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display: 'CITY ID',
//                        name: 'CITYID',
//                        width: 375,
//                        sortable: true,
//                        align: 'left',
//                        hide: true
//                    },
//                    {
//                        display: 'STATE ID',
//                        name: 'STATEID',
//                        width: 375,
//                        sortable: true,
//                        align: 'left',
//                        hide :true
//                    },
//                    {
//                        display : 'State NAME',
//                        name : 'STATENAME',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    }, {
//                        display : 'City NAME',
//                        name : 'CITYNAME',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Address 1',
//                        name : 'ADD1',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Address 2',
//                        name : 'ADD2',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Pincode',
//                        name : 'PINCODE',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Range',
//                        name : 'RANGE',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Division',
//                        name : 'DIVISION',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Commission Rate',
//                        name : 'COMMISSIONRATE',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'ECC Code No',
//                        name : 'ECC',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Tin No',
//                        name : 'TINNO',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Pan No',
//                        name : 'PANNO',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Bank Name',
//                        name : 'BANKNAME',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Bank Account',
//                        name : 'ACCOUNTNO',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Bank Address',
//                        name : 'BANKADDRESS',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'RTGS',
//                        name : 'RTGS',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'NEFT',
//                        name : 'NEFT',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Account Type',
//                        name : 'ACCOUNTTYPE',
//                        width : 80,
//                        sortable : true,
//                        align : 'left'
//                    },
//                     ],
//                buttons : [ {
//                        name : 'New',
//                        bclass : 'new',
//                        onpress : NewGroup
//                    },
//                    {
//                        name : 'Edit',
//                        bclass : 'edit',
//                        onpress : EditPrincipal
//                    },
//                    {
//                        name : 'Contact Person',
//                        bclass : 'contact_persone',
//                        onpress : EditPrincipal
//                    }
//                    ,
//                    {
//                        separator : true
//                    } 
//                ],
//                searchitems : [ {
//                    display : 'City Id',
//                    name : 'CITYID'
//                    }, {
//                        display : 'City Name',
//                        name : 'CITYNAME',
//                        isdefault : false
//                } ],
//                sortorder : "asc",
//                usepager : false,
//                //title : 'Principal Master',
//                useRp : false,
//                rp : 15,
//                showTableToggleBtn : false,
//                width : 1500,
//                height : 400,
//                
//            });
//}
////LoadPrincipal();

//function Cancle(){
//$("#Form_Div").hide();
//$("#ShowData_Div").show();
//$("#btnaddprincipal").show();
//$("#btnupdateprincipal").hide();
//Exception.clear();
//}
//function NewGroup(){
//$("#ShowData_Div").hide();
//$("#Form_Div").show();
//$("#btnaddprincipal").show();
//$("#btnupdateprincipal").hide();
//document.getElementById("state").value = 0;
//document.getElementById("city").value = 0;
//document.getElementById("principal_id").value = ""; 
//document.getElementById("princ_name").value = "";
//document.getElementById("princ_add1").value = "";
//document.getElementById("princ_add2").value = "";
//document.getElementById("state").value = "";
//document.getElementById("city").value = "";
//document.getElementById("princ_pin").value = "";
//document.getElementById("princ_range").value = "";
//document.getElementById("princ_division").value = "";
//document.getElementById("princ_commission").value = "";
//document.getElementById("princ_ecc").value = "";
//document.getElementById("princ_tin").value = "";
//document.getElementById("princ_pan").value = "";
//document.getElementById("princ_bankname").value = "";
//document.getElementById("bank_add").value = "";
//document.getElementById("princ_accountnumber").value = "";
//document.getElementById("princ_rtgs").value = "";
//document.getElementById("princ_neft").value = "";
//document.getElementById("princ_account").value = "";
//}
//function CancelEmployee()
//{
//$("#ContactInfo_Div").hide();
//$("#ShowData_Div").show();
//document.getElementById("principal_id_for_employee").value = "";
//$("#state").removeAttr("disabled");
//$("#city").removeAttr("disabled");
//Exception.clear();
//}
//function LoadPrincipalContact(id){
//$(".loademp").flexigrid({
//                url : '../../Controller/Master_Controller/Principal_Controller.php?TYP=GET_CONTECTPERSON&PRINCIPAL_SUPPLIER_ID=' + id,
//                dataType : 'json',
//                colModel : [ {
//                    display : 'Contact ID',
//                    name : 'PS_Contact_info_id',
//                    width : 50,
//                    sortable : true,
//                    align : 'center'
//                    }, {
//                        display : 'Title',
//                        name : 'TITLE',
//                        width : 100,
//                        sortable : true,
//                        align : 'left'
//                    }, {
//                        display : 'First NAME',
//                        name : 'FNAME',
//                        width : 200,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Last NAME',
//                        name : 'LNAME',
//                        width : 200,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Deptt',
//                        name : 'DEPT',
//                        width : 150,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Phone',
//                        name : 'PHONE',
//                        width : 200,
//                        sortable : true,
//                        align : 'left'
//                    },
//                    {
//                        display : 'Email',
//                        name : 'EMAIL',
//                        width : 300,
//                        sortable : true,
//                        align : 'left'
//                    },
//                     ],
//                buttons : [ 
//                    {
//                        name : 'Edit',
//                        bclass : 'edit',
//                        onpress : EditPrincipalContact
//                    }
//                    ,
//                    {
//                        separator : true
//                    } 
//                ],
//                searchitems : [ {
//                    display : 'City Id',
//                    name : 'CITYID'
//                    }, {
//                        display : 'City Name',
//                        name : 'CITYNAME',
//                        isdefault : false
//                } ],
//                sortorder : "asc",
//                usepager : false,
//                //title : 'Principal Contact Person',
//                useRp : false,
//                rp : 15,
//                showTableToggleBtn : false,
//                width : 1200,
//                height : 200,
//                
//            });
//}
//function EditPrincipalContact(com, grid) {
//         if (com == 'Edit') {
//        if(true){
//            $.each($('.trSelected', grid),
//                function(key, value){
//                    var emp_id = value.children[0].innerText||value.children[0].textContent;
//                    if (emp_id > 0) {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: "GET_CONTECTPERSON_DETAILS", EMP_ID: emp_id},
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    var objs = jQuery.parseJSON(jsondata);
//                    document.getElementById("principal_id_for_employee").value = objs[0]._ps_contact_info_id; 
//                    document.getElementById("emp_title").value = objs[0]._title;
//                    document.getElementById("emp_fname").value = objs[0]._first_name;
//                    document.getElementById("emp_lname").value = objs[0]._last_name;
//                    document.getElementById("emp_dept").value = objs[0]._dept_id;
//                    document.getElementById("emp_phon").value = objs[0]._phone;
//                    document.getElementById("emp_email").value = objs[0]._email;
//                    
//                }
//            }
//        });
//    }                           
//            });    
//        }
//    }
//}
//function EditPrincipal(com, grid) {
//         if (com == 'Edit') {
//        if(true){
//            $.each($('.trSelected', grid),
//                function(key, value){
//                    var principalID = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
//                    if (principalID > 0) {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: "SELECT", PRINCIPAL_SUPPLIER_ID: principalID},
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    var objs = jQuery.parseJSON(jsondata);
//                    document.getElementById("principal_id").value = objs[0]._principal_supplier_id; 
//                    document.getElementById("princ_name").value = objs[0]._principal_supplier_name;
//                    document.getElementById("princ_add1").value = objs[0]._add1;
//                    document.getElementById("princ_add2").value = objs[0]._add2;
//                    $("#state option[title='"+objs[0]._state_name+"']").attr("selected","true");
//                    var stateid = objs[0]._state_id;
//                    var cityid = objs[0]._city_id;
//                    showCity(stateid,cityid);
//                    $("#city").val(cityid);
//                    $("#state").attr("disabled", "disabled"); 
//                    $("#city").attr("disabled", "disabled");
//                    document.getElementById("princ_pin").value = objs[0]._pincode;
//                    document.getElementById("princ_range").value = objs[0]._pc_range;
//                    document.getElementById("princ_division").value = objs[0]._pc_division;
//                    document.getElementById("princ_commission").value = objs[0]._commission_rate;
//                    document.getElementById("princ_ecc").value = objs[0]._ecc_codeno;
//                    document.getElementById("princ_tin").value = objs[0]._tin_no;
//                    document.getElementById("princ_pan").value = objs[0]._pan_no;
//                    document.getElementById("princ_bankname").value = objs[0]._bankname;
//                    document.getElementById("bank_add").value = objs[0]._bankaddress;
//                    document.getElementById("princ_accountnumber").value = objs[0]._accountnumber;
//                    document.getElementById("princ_rtgs").value = objs[0]._rtgs;
//                    document.getElementById("princ_neft").value = objs[0]._neft;
//                    document.getElementById("princ_account").value = objs[0]._accounttype;
//                }
//                else {
//                }
//            }
//        });
//    }
//                    

//                    $("#Form_Div").show();
//                    $("#ShowData_Div").hide();
//                    $("#btnaddprincipal").hide();
//                    $("#btnupdateprincipal").show();
//                    document.getElementById("princ_name").focus();
//                                
//            });    
//        }
//    }
//    else if (com == 'Contact Person') {
//        if(true){
//            $.each($('.trSelected', grid),
//                function(key, value){
//                    var id = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
//                    document.getElementById("principal_id_for_employee").value = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,""); 
//                    $("#ShowData_Div").hide();
//                    $("#ContactInfo_Div").show();
//                    LoadPrincipalContact(id);
//                    //$(".loademp").flexReload();
//            });    
//        }
//    }
//}
//function AddPrincipalMaster() {
//Exception.validate("form1");
//    var result = Exception.validStatus;
//    if (result) {
//    var TYPE = "INSERT";
//    var princ_name = document.getElementById("princ_name").value;
//    var princ_add1 = document.getElementById("princ_add1").value;
//    var princ_add2 = document.getElementById("princ_add2").value;
//    var stateid = document.getElementById("state").value;
//    var cityid = document.getElementById("city").value;
//    var pincode = document.getElementById("princ_pin").value;
//    var range = document.getElementById("princ_range").value;
//    var division = document.getElementById("princ_division").value;
//    var commisionrate = document.getElementById("princ_commission").value;
//    var ecc = document.getElementById("princ_ecc").value;
//    var tin = document.getElementById("princ_tin").value;
//    var pan = document.getElementById("princ_pan").value;
//    var addtype = "P";
//    var bankname = document.getElementById("princ_bankname").value;
//    var bankadd = document.getElementById("bank_add").value;
//    var bankaccount = document.getElementById("princ_accountnumber").value;
//    var rtgs = document.getElementById("princ_rtgs").value;
//    var neft = document.getElementById("princ_neft").value;
//    var accounttype = document.getElementById("princ_account").value;
//    if (bankaccount != "" && princ_name != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPALSUPPLIERNAME: princ_name, ADD1: princ_add1, ADD2: princ_add2, STATEID: stateid, CITYID: cityid,
//                PINCODE: pincode,PS_RANGE: range, PS_DIVISION: division,
//                PS_COMMISSIONERATE: commisionrate, ECC_CODENO: ecc, TINNO: tin, PANNO: pan, ADDTYPE: addtype,
//                BANK_NAME: bankname, BANK_ADDRESS: bankadd, BANK_ACCOUNT_NO: bankaccount, RTGS: rtgs, NEFT: neft, ACCOUNTTYPE: accounttype
//            },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    $(".flexme4").flexReload();
//                    $("#Form_Div").hide();
//                    $("#ShowData_Div").show();
//                }
//                else {
//                }
//            }
//        });
//    }
//    }
//}
//function UpdatePrincipalMaster() {
//Exception.validate("form1");
//    var result = Exception.validStatus;
//    if (result) {
//    var TYPE = "UPDATE";
//    var princ_id = document.getElementById("principal_id").value.replace(/(\r\n|\n|\r)/gm,"");
//    var princ_name = document.getElementById("princ_name").value;
//    var princ_add1 = document.getElementById("princ_add1").value;
//    var princ_add2 = document.getElementById("princ_add2").value;
//    var stateid = document.getElementById("state").value;
//    var cityid = document.getElementById("city").value;
//    var pincode = document.getElementById("princ_pin").value;
//    var range = document.getElementById("princ_range").value;
//    var division = document.getElementById("princ_division").value;
//    var commisionrate = document.getElementById("princ_commission").value;
//    var ecc = document.getElementById("princ_ecc").value;
//    var tin = document.getElementById("princ_tin").value;
//    var pan = document.getElementById("princ_pan").value;
//    var bankname = document.getElementById("princ_bankname").value;
//    var bankadd = document.getElementById("bank_add").value.replace(/(\r\n|\n|\r)/gm,"");
//    var bankaccount = document.getElementById("princ_accountnumber").value.replace(/(\r\n|\n|\r)/gm,"");
//    var rtgs = document.getElementById("princ_rtgs").value;
//    var neft = document.getElementById("princ_neft").value;
//    var accounttype = document.getElementById("princ_account").value;

//    $("#state").removeAttr("disabled");
//    $("#city").removeAttr("disabled");
//    if (bankaccount != "" && princ_name != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPAL_SUPPLIER_ID: princ_id, PRINCIPALSUPPLIERNAME: princ_name, ADD1: princ_add1, ADD2: princ_add2, STATEID: stateid, CITYID: cityid,
//                PINCODE: pincode, PS_RANGE: range, PS_DIVISION: division,
//                PS_COMMISSIONERATE: commisionrate, ECC_CODENO: ecc, TINNO: tin, PANNO: pan,
//                BANK_NAME: bankname, BANK_ADDRESS: bankadd, BANK_ACCOUNT_NO: bankaccount, RTGS: rtgs, NEFT: neft, ACCOUNTTYPE: accounttype
//            },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    $(".flexme4").flexReload();
//                    $("#Form_Div").hide();
//                    $("#ShowData_Div").show();
//                }
//                else {
//                }
//            }
//        });
//    }
//    }
//}
//function AddEmployee()
//{
//var TYPE = "ADD_CONTECTPERSON";
//    var princ_id = document.getElementById("principal_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
//    var emp_title = document.getElementById("emp_title").value;
//    var emp_fname = document.getElementById("emp_fname").value;
//    var emp_lname = document.getElementById("emp_lname").value;
//    var emp_dept = document.getElementById("emp_dept").value;
//    var emp_phon = document.getElementById("emp_phon").value;
//    var emp_email = document.getElementById("emp_email").value;
//    if (princ_id > 0 && emp_title != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPAL_SUPPLIER_ID: princ_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email  },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                 //alert(jsondata);
//                    $(".loademp").flexReload();
//                }
//                else {
//                }
//            }
//        });
//    }
//    //LoadPrincipalContact(princ_id);
//    //$(".loademp").flexReload();
//    
//}

//function UpdateEmployee()
//{
//var TYPE = "UPDATE_CONTECTPERSON";
//    var princ_id = document.getElementById("principal_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
//    var emp_title = document.getElementById("emp_title").value;
//    var emp_fname = document.getElementById("emp_fname").value;
//    var emp_lname = document.getElementById("emp_lname").value;
//    var emp_dept = document.getElementById("emp_dept").value;
//    var emp_phon = document.getElementById("emp_phon").value;
//    var emp_email = document.getElementById("emp_email").value;
//    if (princ_id > 0 && emp_title > 0) {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Principal_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PS_Contact_info_id: princ_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email  },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    $(".loademp").flexReload();
//                    document.getElementById("emp_title").value = "";
//                    document.getElementById("emp_fname").value = "";
//                    document.getElementById("emp_lname").value = "";
//                    document.getElementById("emp_dept").value = "";
//                    document.getElementById("emp_phon").value = "";
//                    document.getElementById("emp_email").value = "";
//                }
//                else {
//                }
//            }
//        });
//    }
//    //LoadPrincipalContact(princ_id);
//    
//}

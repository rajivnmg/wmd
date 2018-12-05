var URL = "../../Controller/Master_Controller/Supplier_Controller.php?TYP=";
var supplier_app = angular.module('supplier_app', []);
// create angular controller

supplier_app.controller('supplier_Controller', ['$scope', '$http', function supplier_Controller($scope, $http) {

    //var Employee_Status = { _EmployeeList: [{ _title: 0, titlename: '', _first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}] };
	var Employee_Status = { _EmployeeList: [{ _title: 0, titlename: '', _first_name: '', _last_name: '', _dept_id: 0, deptname: '', _phone: '', _email: ''}],_GSTList: [{ _gst_state_name: '',_gst_state_id: 0, _gst_reg_status_name: '', _gst_reg_status: 0, gst_mig_status_name: '', gst_mig_status: 0, _gst_no: '', _gst_reg_date: '', _arn_no: '', _perm_gst: ''}] };
    $scope.supplier = Employee_Status;

    $scope.AddEmployee = function () {
        $scope.supplier.titlename = $("#emp_title option:selected").text();
        $scope.supplier.deptname = $("#emp_dept option:selected").text();

        $scope.supplier._EmployeeList.push({ _title: $scope.supplier._title, titlename: $scope.supplier.titlename, _first_name: $scope.supplier._first_name, _last_name: $scope.supplier._last_name, _dept_id: $scope.supplier._dept_id, deptname: $scope.supplier.deptname, _phone: $scope.supplier._phone1, _email: $scope.supplier._email1 });
    }

    $scope.RemoveEmployee = function (item) {
        $scope.supplier._EmployeeList.splice($scope.supplier._EmployeeList.indexOf(item), 1);
    }
	
	/* BOF to add GST details for Supplier by Ayush Giri on 12-06-2017 */
	//var GST_Status = { _GSTList: [{ _gst_state_id: '', _gst_reg_status: '', gst_mig_status: '', _gst_no: '', _gst_reg_date: '', _arn_no: '', _perm_gst: ''}] };
    //$scope.principal = GST_Status;
	
	$scope.AddGST = function () {
		//alert('CHECK ADD GST 1');
		$scope.supplier._gst_state_name = $("#gst_state_id option:selected").text();
        $scope.supplier._gst_reg_status_name = $("#gst_reg_status option:selected").text();
        $scope.supplier._gst_mig_status_name = $("#gst_mig_status option:selected").text();
		$scope.supplier._gst_reg_date = $("#gst_reg_date").val();
		
		/* $scope.supplier._gst_state_id = $("#gst_state_id option:selected").val();
        $scope.supplier._gst_reg_status = $("#gst_reg_status option:selected").val();
        $scope.supplier._gst_mig_status = $("#gst_mig_status option:selected").val(); */
		//alert('CHECK ADD GST 2');

        //$scope.supplier._GSTList.push({ _gst_state_id: $scope.supplier._gst_state_id, _gst_reg_status: $scope.supplier._gst_reg_status, _gst_mig_status: $scope.supplier._gst_mig_status, _gst_no: $scope.supplier._gst_no, _gst_reg_date: $scope.supplier._gst_reg_date, _arn_no: $scope.supplier._arn_no, _perm_gst: $scope.supplier._perm_gst });
		$scope.supplier._GSTList.push({ _gst_state_name: $scope.supplier._gst_state_name, _gst_state_id: $scope.supplier._gst_state_id, _gst_reg_status: $scope.supplier._gst_reg_status, _gst_reg_status_name: $scope.supplier._gst_reg_status_name, _gst_mig_status: $scope.supplier._gst_mig_status, _gst_mig_status_name: $scope.supplier._gst_mig_status_name, _gst_no: $scope.supplier._gst_no, _gst_reg_date: $scope.supplier._gst_reg_date, _arn_no: $scope.supplier._arn_no, _perm_gst: $scope.supplier._perm_gst });
		//alert('CHECK ADD GST 3');
    }
	
	$scope.RemoveGST = function (item) {
        $scope.supplier._GSTList.splice($scope.supplier._GSTList.indexOf(item), 1);
    }
	/* EOF to add GST details for Supplier by Ayush Giri on 12-06-2017 */


    $scope.init = function (SupplierId) {
        if (SupplierId > 0) {
            $("#btnaddsupplier").hide();
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: "SELECT", SUPPLIERID: SupplierId },
                success: function (jsondata) {
                    var objs = jQuery.parseJSON(jsondata);
                    //alert(jsondata);
                    $scope.$apply(function () {
                        $scope.supplier = objs[0];
                        $scope.supplier._EmployeeList = objs[0]._EmployeeList;
                        showCity(objs[0]._state_id, objs[0]._city_id);
						$scope.supplier._GSTList = objs[0]._GSTList; //To add GST details for Principal by Ayush Giri on 12-06-2017
                    });
                }
            });
        }
        else {
            $scope.supplier._EmployeeList.splice($scope.supplier._EmployeeList.indexOf(0), 1);
			$scope.supplier._GSTList.splice($scope.supplier._GSTList.indexOf(0), 1); //To add GST details for Principal by Ayush Giri on 12-06-2017
            $("#btnupdatesupplier").hide();
        }
    }
    $scope.submitForm = function () {
		if(!$scope.supplier._state_id){
			alert('Please Select State'); return false ;
		}
		
        var json_string = JSON.stringify($scope.supplier);
        $("#btnaddsupplier").hide();
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "INSERT", SUPPLIERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.supplier = null;
                        location.href = "ViewSupplier.php";
                    });
                }
                else {
                }
            }
        });
    }
    $scope.Update = function () {
		if(!$scope.supplier._state_id){
			alert('Please Select State'); return false ;
		}
		
        var json_string = JSON.stringify($scope.supplier);
        $("#btnupdatesupplier").hide();
        //alert(json_string);
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "UPDATE", SUPPLIERDATA: json_string },
            success: function (jsondata) {
                if (jsondata != "") {
                    $scope.$apply(function () {
                        $scope.supplier = null;
                        location.href = "ViewSupplier.php";
                    });
                }
                else {
                }
            }
        });
    }
} ]);
supplier_app.directive('validNumber', function () {
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


//function LoadSupplier(){
//$(".flexme4").flexigrid({
//                url : '../../Controller/Master_Controller/Supplier_Controller.php',
//                dataType : 'json',
//                colModel : [ {
//                    display : 'Supplier ID',
//                    name : 'SupplierID',
//                    width : 50,
//                    sortable : true,
//                    align : 'center'
//                    }, {
//                        display : 'Supplier NAME',
//                        name : 'SupplierNAME',
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
//                        onpress : EditSupplier
//                    },
//                    {
//                        name : 'Contact Person',
//                        bclass : 'contact_persone',
//                        onpress : EditSupplier
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
//                //title : 'Supplier Master',
//                useRp : false,
//                rp : 15,
//                showTableToggleBtn : false,
//                width : 1500,
//                height : 400,
//                
//            });
//}
////LoadSupplier();
//function Cancle(){
//$("#Form_Div").hide();
//$("#ShowData_Div").show();
//$("#btnaddsupplier").show();
//$("#btnupdatesupplier").hide();
//Exception.clear();
//}
//function NewGroup(){
//$("#ShowData_Div").hide();
//$("#Form_Div").show();
//$("#btnaddsupplier").show();
//$("#btnupdatesupplier").hide();
//document.getElementById("state").value = 0;
//document.getElementById("city").value = 0;
//}
//function CancelEmployee()
//{
//$("#ContactInfo_Div").hide();
//$("#ShowData_Div").show();
//document.getElementById("supplier_id_for_employee").value = "";
//$("#state").removeAttr("disabled");
//$("#city").removeAttr("disabled");
//Exception.clear();
//}
//function LoadSupplierContact(id){
//$(".loademp").flexigrid({
//                url : '../../Controller/Master_Controller/Supplier_Controller.php?TYP=GET_CONTECTPERSON&PRINCIPAL_SUPPLIER_ID=' + id,
//                dataType : 'json',
//                colModel : [ {
//                    display : 'Conatact Person ID',
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
//                        isdefault : true
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
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: "GET_CONTECTPERSON_DETAILS", EMP_ID: emp_id},
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    var objs = jQuery.parseJSON(jsondata);
//                    document.getElementById("supplier_id").value = objs[0]._principal_supplier_id; 
//                    document.getElementById("supplier_id_for_employee").value = objs[0]._ps_contact_info_id; 
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
//function EditSupplier(com, grid) {
//        if (com == 'Edit') {
//        if(true){
//            $.each($('.trSelected', grid),
//                function(key, value){
//                    // collect the data
//                    var supplier_id = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
//                    if (supplier_id > 0) {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: "SELECT", PRINCIPAL_SUPPLIER_ID: supplier_id},
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    var objs = jQuery.parseJSON(jsondata);
//                    document.getElementById("supplier_id").value = objs[0]._principal_supplier_id; 
//                    document.getElementById("supp_name").value = objs[0]._principal_supplier_name;
//                    document.getElementById("supp_add1").value = objs[0]._add1;
//                    document.getElementById("supp_add2").value = objs[0]._add2;
//                    //$("#state option[title='"+objs[0]._state_name+"']").attr("selected","true");
//                    var stateid = objs[0]._state_id;
//                    var cityid = objs[0]._city_id;
//                    showCity(stateid,cityid);
//                    $("#state").val(stateid);
//                    $("#city").val(cityid);
//                    $("#state").attr("disabled", "disabled"); 
//                    $("#city").attr("disabled", "disabled"); 
//                    document.getElementById("supp_pincode").value = objs[0]._pincode;
//                    document.getElementById("supp_range").value = objs[0]._pc_range;
//                    document.getElementById("supp_division").value = objs[0]._pc_division;
//                    document.getElementById("supp_commission").value = objs[0]._commission_rate;
//                    document.getElementById("supp_ecc").value = objs[0]._ecc_codeno;
//                    document.getElementById("supp_tin").value = objs[0]._tin_no;
//                    document.getElementById("supp_pan").value = objs[0]._pan_no;
//                    document.getElementById("supp_bankname").value = objs[0]._bankname;
//                    document.getElementById("bank_add").value = objs[0]._bankaddress;
//                    document.getElementById("supp_accountnumber").value = objs[0]._accountnumber;
//                    document.getElementById("supp_rtgs").value = objs[0]._rtgs;
//                    document.getElementById("supp_neft").value = objs[0]._neft;
//                    document.getElementById("supp_account").value = objs[0]._accounttype;
//                }
//                else {
//                }
//            }
//        });
//    }
//                    $("#Form_Div").show();
//                    $("#ShowData_Div").hide();
//                    $("#btnaddsupplier").hide();
//                    $("#btnupdatesupplier").show();

//                    document.getElementById("supp_name").focus();
//                                
//            });    
//        }
//    }
//    else if (com == 'Contact Person') {
//        if(true){
//            $.each($('.trSelected', grid),
//                function(key, value){
//                    var id = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,"");
//                    document.getElementById("supplier_id_for_employee").value = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm,""); 
//                    $("#ShowData_Div").hide();
//                    $("#ContactInfo_Div").show();
//                    LoadSupplierContact(id);
//                    //$(".loademp").flexReload();
//            });    
//        }
//    }
//}
//function AddSupplierMaster() {
//Exception.validate("form1");
//    var result = Exception.validStatus;
//    if (result) {
//    var TYPE = "INSERT";
//    var supp_name = document.getElementById("supp_name").value;
//    var supp_add1 = document.getElementById("supp_add1").value;
//    var supp_add2 = document.getElementById("supp_add2").value;
//    var stateid = document.getElementById("state").value;
//    var cityid = document.getElementById("city").value;
//    var pincode = document.getElementById("supp_pincode").value;
//    var range = document.getElementById("supp_range").value;
//    var division = document.getElementById("supp_division").value;
//    var commisionrate = document.getElementById("supp_commission").value;
//    var ecc = document.getElementById("supp_ecc").value;
//    var tin = document.getElementById("supp_tin").value;
//    var pan = document.getElementById("supp_pan").value;
//    var addtype = "S";
//    var bankname = document.getElementById("supp_bankname").value;
//    var bankadd = document.getElementById("bank_add").value;
//    var bankaccount = document.getElementById("supp_accountnumber").value;
//    var rtgs = document.getElementById("supp_rtgs").value;
//    var neft = document.getElementById("supp_neft").value;
//    var accounttype = document.getElementById("supp_account").value;
//    if (bankaccount != "" && supp_name != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPALSUPPLIERNAME: supp_name, ADD1: supp_add1, ADD2: supp_add2, STATEID: stateid, CITYID: cityid,
//                PINCODE: pincode, PS_RANGE: range, PS_DIVISION: division,
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
//function UpdateSupplierMaster() {
//Exception.validate("form1");
//    var result = Exception.validStatus;
//    if (result) {
//    var TYPE = "UPDATE";
//    var supplier_id = document.getElementById("supplier_id").value.replace(/(\r\n|\n|\r)/gm,"");
//    var supp_name = document.getElementById("supp_name").value;
//    var supp_add1 = document.getElementById("supp_add1").value;
//    var supp_add2 = document.getElementById("supp_add2").value;
//    var pincode = document.getElementById("supp_pincode").value;
//    var range = document.getElementById("supp_range").value;
//    var division = document.getElementById("supp_division").value;
//    var commisionrate = document.getElementById("supp_commission").value;
//    var ecc = document.getElementById("supp_ecc").value;
//    var tin = document.getElementById("supp_tin").value;
//    var pan = document.getElementById("supp_pan").value;
//    var bankname = document.getElementById("supp_bankname").value;
//    var bankadd = document.getElementById("bank_add").value.replace(/(\r\n|\n|\r)/gm,"");
//    var bankaccount = document.getElementById("supp_accountnumber").value.replace(/(\r\n|\n|\r)/gm,"");
//    var rtgs = document.getElementById("supp_rtgs").value;
//    var neft = document.getElementById("supp_neft").value;
//    var accounttype = document.getElementById("supp_account").value;
//    $("#state").removeAttr("disabled");
//    $("#city").removeAttr("disabled");
//    if (bankaccount != "" && supp_name != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPAL_SUPPLIER_ID: supplier_id, PRINCIPALSUPPLIERNAME: supp_name, ADD1: supp_add1, ADD2: supp_add2,
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
//    var supplier_id = document.getElementById("supplier_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
//    var emp_title = document.getElementById("emp_title").value;
//    var emp_fname = document.getElementById("emp_fname").value;
//    var emp_lname = document.getElementById("emp_lname").value;
//    var emp_dept = document.getElementById("emp_dept").value;
//    var emp_phon = document.getElementById("emp_phon").value;
//    var emp_email = document.getElementById("emp_email").value;
//    if (supplier_id > 0 && emp_title > 0) {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PRINCIPAL_SUPPLIER_ID: supplier_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email  },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    //LoadPrincipalContact(supplier_id);
//                    //alert(jsondata);
//                    $(".loademp").flexReload();
//                }
//                else {
//                }
//            }
//        });
//    }
//    
//    
//}
//function UPDATEEmployee()
//{
//var TYPE = "UPDATE_CONTECTPERSON";
//    var empid_id = document.getElementById("supplier_id_for_employee").value.replace(/(\r\n|\n|\r)/gm,"");
//    var emp_title = document.getElementById("emp_title").value;
//    var emp_fname = document.getElementById("emp_fname").value;
//    var emp_lname = document.getElementById("emp_lname").value;
//    var emp_dept = document.getElementById("emp_dept").value;
//    var emp_phon = document.getElementById("emp_phon").value;
//    var emp_email = document.getElementById("emp_email").value;
//    if (empid_id > 0 && emp_fname != "") {
//        jQuery.ajax({
//            url: "../../Controller/Master_Controller/Supplier_Controller.php",
//            type: "POST",
//            data: { TYP: TYPE, PS_Contact_info_id: empid_id, EMP_TITLE : emp_title,EMP_FNAME: emp_fname, EMP_LNAME: emp_lname, EMP_DEPT: emp_dept, EMP_PHONE: emp_phon, EMP_EMAIL: emp_email },
//            //cache: false,
//            success: function (jsondata) {
//                if (jsondata != "") {
//                    //LoadPrincipalContact(supplier_id);
//                    
//                }
//                else {
//                }
//            }
//        });
//    }
//    $(".loademp").flexReload();
//    
//}

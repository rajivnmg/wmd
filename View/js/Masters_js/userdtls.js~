function LoadUnitData() {
$(".flexme4").flexigrid({
    url: '../../Controller/Master_Controller/User_Controller.php?TYP=',
                dataType : 'json',
                colModel: [{ display: 'USER ID', name: 'USERID', width: 60, sortable: true, align: 'center', process: procme },
                             //{ display: 'PASSWORD', name: 'PASSWD', width: 100, sortable: true, align: 'left' },
                             { display: 'NAME', name: 'NAME', width: 200, sortable: true, align: 'left' },
                             { display: 'USER_TYPE', name: 'USER_TYPE', width: 200, sortable: true, align: 'left' },
                             { display: 'PHONE', name: 'PHONE', width: 200, sortable: true, align: 'left' },
                             { display: 'Email', name: 'email', width: 200, sortable: true, align: 'left' },
                             { display: 'MOBILE', name: 'MOBILE', width: 200, sortable: true, align: 'left' },
                             { display: 'ACTIVE', name: 'ACTIVE', width: 100, sortable: true, align: 'left' } ],
                //buttons : [{name : 'Edit', bclass : 'edit', onpress : UserMasterGrid },{ separator : true }],
                //searchitems : [ { display : 'USERID', name : 'USERID'}, { display : 'PASSWD', name : 'PASSWD', isdefault : false } ],
                sortorder : "asc",
                usepager : false,
                //title : 'UNIT Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1360,
                height : 400
                
            });
			
}
LoadUnitData();
function procme(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        var path = 'CreateUser.php?ID=' + id;
        window.location.href = path;
    });
}
var UserEdit_app = angular.module('UserEdit_app', []);
UserEdit_app.controller('UserEdit_Controller', function ($scope) {
    //alert("suraj");

    $scope.UpdateUnit = function () {
     //   alert("hERE");

        //alert(document.getElementById("USERID").value);
        var USERID = document.getElementById("USERID").value;
        var PASSWD = document.getElementById("PASSWD").value;
        var NAME = document.getElementById("NAME").value;
        var USER_TYPE = document.getElementById("USER_TYPE").value;
        var PHONE = document.getElementById("PHONE").value;
        var email = document.getElementById("email").value;
        var MOBILE = document.getElementById("MOBILE").value;
        var ACTIVE = document.getElementById("ACTIVE").value;
        // var json_string = JSON.stringify($scope.UserEdit);
        //  alert(json_string);
        jQuery.ajax({
            url: "../../Controller/Master_Controller/User_Controller.php",
            type: "POST",
            data: { TYP: "UPDATE", ACTION: "CreateUser", USERID: USERID, PASSWD: PASSWD, NAME: NAME, USER_TYPE: USER_TYPE, PHONE: PHONE
            , email: email, MOBILE: MOBILE, ACTIVE: ACTIVE
            },
            success: function (jsondata) {
                // document.getElementById("irid").value = "";
               // document.getElementById("ir").value = "";
                if (jsondata != "") {
                $("#showunitdiv").show();
                $("#addunitdiv").hide();
                $(".flexme4").flexReload();
                }
                else {
                }
            }
        });
    }
    $scope.Cancel = function () {
       // document.getElementById("ir").value = "";
        $("#showunitdiv").show();
        $("#addunitdiv").hide();
        $("#btnaddunit").show();
        $("#btnupdateunit").hide();
        $(".flexme4").flexReload();
    }
});



function UserMasterGrid(com, grid) {
                 if (com == 'Edit') {
                    if(true){
                        $.each($('.trSelected', grid),
                            function (key, value) {
                                // collect the data
                                var TYPE = "SELECT";
                                alert(value.children[0].innerText.replace(/(\r\n|\n|\r)/gm, "")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm, ""));
                                 var USERID = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm, "") || value.children[0].textContent.replace(/(\r\n|\n|\r)/gm, "");
                                //var USERID = "EMP1";
                                if (USERID != "") {
                                    jQuery.ajax({
                                        url: "../../Controller/Master_Controller/User_Controller.php",
                                        type: "POST",
                                        data: { TYP: TYPE, ACTION: "CreateUser", USERID: USERID },
                                        //cache: false,
                                        success: function (jsondata) {
                                            if (jsondata != "") {
                                                var objs = jQuery.parseJSON(jsondata);
                                                //  alert(objs[0]._USERID);
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


                                            }
                                        }
                                    });
                                }

                                $("#showunitdiv").hide();
                                $("#addunitdiv").show();
                                $("#btnaddunit").hide();
                                $("#btnupdateunit").show();


                            });    
                    }
                }
            }

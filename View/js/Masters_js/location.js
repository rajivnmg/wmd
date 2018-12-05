function showCity(State_Id, cityid) {
    //State_Id = State_Id.replace(/(\r\n|\n|\r)/gm, "");
    //cityid = cityid.replace(/(\r\n|\n|\r)/gm, "");
var TYPE = "SELECT";
    if (true) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/City_Controller.php",
            type: "POST",
            data: { TYP: TYPE , TAG:"STATE", STATEID :State_Id },
            //cache: false,
            success: function (jsondata) {
                $('#city').empty();
                $("#city").append("<option value=\'0'>Select City</option>");
                var objs = jQuery.parseJSON(jsondata);
                if (jsondata != "") {
                    var obj;
                    for(var i = 0; i < objs.length; i++){
                    //for (index in objs) {
                    var obj = objs[i];
                    //var index = 0;
                    $("#city").append("<option value=\'" + obj._city_id + "'\ title=\'" + obj._city_nameame + "\'>" + obj._city_nameame + "</option>");
                    }
            }
            $('#city').val(cityid);
            }
        });
    }
}
function Addlocation() {
   // if (document.getElementById("location").value == "") {
   //     return;
   // }
	 Exception.validate("form1");	
var result = Exception.validStatus; 
	if(result)
	{
	   var TYPE = "INSERT";
    var Locationname = document.getElementById("location").value;
    var Stateid = document.getElementById("state").value;
    var Cityid =  document.getElementById("city").value;
    if (Locationname != "" && Cityid > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/LocationController.php",
            type: "POST",
            data: { TYP: TYPE, LOCATIONNAME: Locationname, STATEID: Stateid, CITYID: Cityid },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    document.getElementById("location").value = "";
                    $(".flexme4").flexReload();
                    $("#Form_Div").hide();
                    $("#ShowData_Div").show();
                }
                else {
                }
            }
        });
    }
	}
else
{
return false;
}

	
	
	
    
}
function Updatelocation() {
    var TYPE = "UPDATE";
    var Locationname = document.getElementById("location").value;
    var LocationId = document.getElementById("locationid").value;
    if (Locationname != "" && LocationId > 0) {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/LocationController.php",
            type: "POST",
            data: { TYP: TYPE, LOCATIONNAME: Locationname, LOCATIONID: LocationId },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    $(".flexme4").flexReload();
                    document.getElementById("location").value = "";
                    document.getElementById("locationid").value = "";
                    $("#Form_Div").hide();
                    $("#ShowData_Div").show();
                }
                else {
                }
            }
        });
    }
}
function LoadLocation(){
$(".flexme4").flexigrid({
    url: '../../Controller/Master_Controller/LocationController.php?TYP=',
                dataType : 'json',
                colModel : [ {
                    display : 'LOCATION ID',
                    name : 'LOCATIONID',
                    width : 330,
                    sortable : true,
                    align : 'center'
                    }, {
                        display : 'LOCATION NAME',
                        name : 'LOCATIONNAME',
                        width : 330,
                        sortable : true,
                        align : 'left'
                    },{
                        display : 'CITY NAME',
                        name : 'CITYNAME',
                        width : 340,
                        sortable : true,
                        align : 'left'
                    },
                    {
                        display: 'CITY ID',
                        name: 'CITYID',
                        width: 200,
                        sortable: true,
                        align: 'left',
                        hide: true
                    },
                    {
                        display: 'STATE ID',
                        name: 'STATEID',
                        width: 200,
                        sortable: true,
                        align: 'left',
                        hide :true
                    },
                    {
                        display : 'STATE NAME',
                        name : 'STATENAME',
                        width : 340,
                        sortable : true,
                        align : 'left'
                    }, 
                     ],
                buttons : [{
                        name : 'New',
                        bclass : 'new',
                        onpress : NewGroup
                    },
                    {
                        name : 'Edit',
                        bclass : 'edit',
                        onpress : EditLocation
                    }
                    ,
                    {
                        separator : true
                    } 
                ],
                searchitems : [ {
                    display : 'Location Id',
                    name : 'LOCATIONID'
                    }, {
                        display : 'Location Name',
                        name : 'LOCATIONNAME',
                        isdefault : false
                }
                 ],
                    sortorder: "asc",
                usepager : true,
                //title : 'Location Master',
                useRp: true,
                rp : 10,
                showTableToggleBtn : false,
                width : 1330,
                height : 300
            });
}
LoadLocation();
function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnaddlocation").show();
$("#btnupdatelocation").hide();
document.getElementById("location").value = "";
document.getElementById("locationid").value = "";
$("#txtstate").hide();
$("#txtcity").hide();
$("#state").show();
$("#city").show();
$("#txtstate").text("");
$("#txtcity").text("");
Exception.clear();
}
function NewGroup(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnaddlocation").show();
$("#btnupdatelocation").hide();
document.getElementById("location").value = "";
document.getElementById("locationid").value = "";
document.getElementById("state").value = 0;
document.getElementById("city").value = 0;
Exception.clear();
}

function EditLocation(com, grid) {
        if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function (key, value) {
                    var locationid = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm, "") || value.children[0].textContent.replace(/(\r\n|\n|\r)/gm, "");
                    jQuery.ajax({
                        url: "../../Controller/Master_Controller/LocationController.php",
                        type: "POST",
                        //var GROUPID=document.getElementById("gid").value ;
                        data: { TYP: "SELECT_LOCATION", LOCATIONID: locationid },
                        //cache: false,
                        success: function (jsondata) {
                            if (jsondata != "") {
                                var objs = jQuery.parseJSON(jsondata);
                                document.getElementById("locationid").value = objs[0]._location_id;
                                document.getElementById("location").value = objs[0]._locationName;
                                var stateid = objs[0]._state_id;
                                var cityid = objs[0]._city_id;
                                $("#state").val(stateid);
                                showCity(stateid, cityid);
                                $("#Form_Div").show();
                                $("#ShowData_Div").hide();
                                $("#btnaddlocation").hide();
                                $("#btnupdatelocation").show();
                            }
                        }
                    });


                });    
        }
    }
}
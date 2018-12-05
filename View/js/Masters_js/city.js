
function LoadCity(){
$(".flexme4").flexigrid({
    url: '../../Controller/Master_Controller/City_Controller.php?TYP=',
                dataType : 'json',
                colModel : [{display : 'CITY ID',name : 'CITYID',width : 300,sortable : true, align : 'center' }, 
                            {display : 'CITY NAME', name : 'CITYNAME', width : 400, sortable : true,align : 'left' },
                            { display: 'STATE NAME', name: 'STATENAME', width: 400, sortable: true, align: 'left' },
                            { display: 'STATE ID', name: 'StateId', width: 400, sortable: true, align: 'left',hide:true }, ],
                buttons : [{name : 'New',bclass : 'new', onpress : NewGroup},{name : 'Edit',bclass : 'edit', onpress : EditCity},{separator : true}],
                searchitems : [ { display : 'City Id', name : 'CITYID' }, { display : 'City Name', name : 'CITYNAME', isdefault : false } ],
                sortorder : "asc",
                usepager : false,
                //title : 'City Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1000,
                height : 300
            });
}
LoadCity();

function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnaddcity").show();
$("#btnupdatecity").hide();
document.getElementById("ir").value = "";
document.getElementById("irid").value = "";
Exception.clear();
}
function NewGroup(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnaddcity").show();
$("#btnupdatecity").hide();
document.getElementById("ir").value = "";
document.getElementById("irid").value = "";
document.getElementById("state").value = 0;
}
function AddCity() {
    Exception.validate("form1");
    var result = Exception.validStatus;
    if (result) {
        var TYPE = "INSERT";
        var Cityname = document.getElementById("ir").value;
        var Stateid = document.getElementById("state").value;
        if (Cityname != "" && Stateid > 0) {
            jQuery.ajax({
                url: "../../Controller/Master_Controller/City_Controller.php",
                type: "POST",
                data: { TYP: TYPE, CITYNAME: Cityname, STATEID: Stateid },
                //cache: false,
                success: function (jsondata) {
                    if (jsondata != "") {
                        document.getElementById("ir").value = "";
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
    
}
function UpdateCity() {
    Exception.validate("form1");
    var result = Exception.validStatus;
    if (result) {
        var TYPE = "UPDATE";
        var Cityname = document.getElementById("ir").value;
        var cityid = document.getElementById("irid").value;
        if (Cityname != "" && cityid > 0) {
            jQuery.ajax({
                url: "../../Controller/Master_Controller/City_Controller.php",
                type: "POST",
                data: { TYP: TYPE, CITYNAME: Cityname, CITYID: cityid },
                //cache: false,
                success: function (jsondata) {
                    if (jsondata != "") {
                        $(".flexme4").flexReload();
                        document.getElementById("ir").value = "";
                        document.getElementById("irid").value = "";
                        $("#Form_Div").hide();
                        $("#ShowData_Div").show();
                    }
                    else {
                    }
                }
            });
        }
    }
}
function EditCity(com, grid) {
        if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    // collect the data
                    var CITYID = value.children[0].innerText.replace(/(\r\n|\n|\r)/gm, "")||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm, "");
										jQuery.ajax({
        url:  "../../Controller/Master_Controller/City_Controller.php",
        type: "POST",
		//var GROUPID=document.getElementById("gid").value ;
        data: { TYP: "SELECT", TAG:"CITY", CITYID: CITYID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
			var objs=jQuery.parseJSON(jsondata);
                document.getElementById("irid").value = objs[0]._city_id; 
                    document.getElementById("ir").value = objs[0]._city_nameame; 
					document.getElementById("state").value=objs[0]._state_id; 
            }
        } 
    });
	
                    //document.getElementById("state").value=value.children[2].innerText||value.children[2].textContent;
                    $("#Form_Div").show();
                    $("#ShowData_Div").hide();
                    $("#btnaddcity").hide();
                    $("#btnupdatecity").show();
            });    
        }
    }
}
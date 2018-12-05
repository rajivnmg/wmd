var URL = "../../Controller/Master_Controller/Group_Controller.php?TYP=";
function LoadGroup(){
$(".flexme4").flexigrid({
                url : URL,
                dataType : 'json',
                colModel : [ {display : 'GROUP ID',name : 'GROUPID',width : 330, sortable : true,align : 'center'},
                             {display : 'GROUP CODE',name : 'GROUPCODE', width : 330, sortable : true, align : 'left'}, 
                             {display : 'GROUP DESC',name : 'GROUPDESC',width : 340,sortable : true,align : 'left'}, 
                             {display : 'REMARKS',name : 'REMARKS',width : 340,sortable : true,align : 'left'},],
                buttons  : [ {name : 'New', bclass : 'new', onpress : NewGroup },{name : 'Edit', bclass : 'edit', onpress : EditGroup },
                             {separator : true }],
                searchitems : [ {display : 'GROUPID',name : 'GROUPID'}, {display : 'GROUPCODE',name : 'GROUPCODE'},
                                { display : 'GROUPDESC', name : 'GROUPDESC',isdefault : false} ],
                sortorder : "asc",
                usepager : false,
                //title : 'Group Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1340,
                height : 300,
            });
}
LoadGroup();
function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnaddgroup").show();
$("#btnupdategroup").hide();
document.getElementById("gid").value = "";
document.getElementById("gc").value = "";
document.getElementById("gd").value = "";
document.getElementById("nts").value = "";
Exception.clear();
}
function NewGroup(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnaddgroup").show();
$("#btnupdategroup").hide();
document.getElementById("gid").value = "";
document.getElementById("gc").value = "";
document.getElementById("gd").value = "";
document.getElementById("nts").value = "";
GetAutoGroupCode();
}
function GetAutoGroupCode() {
    jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: "AUTOCODE"},
            success: function (jsondata) {
                if (jsondata != "") {
                    var objs = jQuery.parseJSON(jsondata);
                    document.getElementById("gc").value = objs;
                }
                else {
                }
            }
        });
}
function AddGroup() {
Exception.validate("form1");
var result = Exception.validStatus;
    if (result) {
        var TYPE = "INSERT";
        var GroupCode = document.getElementById("gc").value;
        var GroupDesc = document.getElementById("gd").value;
        var Remark = document.getElementById("nts").value;
        if (GroupCode != "" && GroupDesc != "") {
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: TYPE, GROUPCODE: GroupCode,GROUPDESC : GroupDesc , REMARKS : Remark },
                success: function (jsondata) {
                    if (jsondata != "") {
                        $("#Form_Div").hide();
                        $("#ShowData_Div").show();
                        $(".flexme4").flexReload();
                    }
                    else {
                    }
                }
            });
        }
    }
}
function UpdateGroup() {
Exception.validate("form1");
var result = Exception.validStatus;
if (result) {
    var TYPE = "UPDATE";
    var GroupID = document.getElementById("gid").value.replace(/(\r\n|\n|\r)/gm,"");
    var GroupCode = document.getElementById("gc").value;
    var GroupDesc = document.getElementById("gd").value;
    var Remark = document.getElementById("nts").value.replace(/(\r\n|\n|\r)/gm,"");
    if (GroupCode != "" && GroupDesc != "" && GroupID > 0) {
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: TYPE, GROUPID: GroupID, GROUPCODE: GroupCode, GROUPDESC: GroupDesc, REMARKS: Remark },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                $("#Form_Div").hide();
                $("#ShowData_Div").show();
                    document.getElementById("gid").value = "";
                    document.getElementById("gc").value = "";
                    document.getElementById("gd").value = "";
                    document.getElementById("nts").value = "";
                    $(".flexme4").flexReload();
                }
                else {
                }
            }
        });
    }
  }
}
function EditGroup(com, grid) {
    if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
					var GROUPID = value.children[0].innerText||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
					jQuery.ajax({
        url:  URL,
        type: "POST",
        data: { TYP : "SELECT" , GROUPID : GROUPID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
			 var objs = jQuery.parseJSON(jsondata);
                 document.getElementById("gid").value = objs[0]._group_id;
                    document.getElementById("gc").value = objs[0]._group_coad;
                    document.getElementById("gd").value = objs[0]._group_desc;
                    document.getElementById("nts").value =objs[0]._remark;;
            }
        } 
    });
                    $("#Form_Div").show();
                    $("#ShowData_Div").hide();
                    $("#btnaddgroup").hide();
                    $("#btnupdategroup").show();
            });    
        }
    }
}
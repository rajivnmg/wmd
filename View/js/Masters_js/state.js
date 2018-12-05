
function LoadState(){
$(".flexme4").flexigrid({
        url : '../../Controller/Master_Controller/State_Controller.php?TYP=',
        dataType : 'json',
        colModel : [ {display : 'STATE ID',name : 'STATEID',width : 250,sortable : true,align : 'center'},
					{display : 'STATE NAME',name : 'STATENAME',width : 250,sortable : true,align : 'left'},
					{display : 'STATE CODE',name : 'STATECODE',width : 250,sortable : true,align : 'left'},
					{display : 'TIN NUMBER',name : 'TINNUMBER',width : 250,sortable : true,align : 'left'},
				   ],
        buttons : [{name : 'New',bclass : 'new', onpress : NewGroup},{name : 'Edit',bclass : 'edit', onpress : EditState},{separator : true}],
        searchitems : [ {display : 'STATEID',name : 'STATEID'}, {display : 'STATENAME',name : 'STATENAME',isdefault : false} ],
        sortorder : "asc",
        usepager : false,
        //title : 'State Master',
        useRp : false,
        rp : 15,
        showTableToggleBtn : false,
        width : 1000,
        height : 300,
    });
}
LoadState();

function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnsavestate").show();
$("#btnupdatestate").hide();
document.getElementById("ir").value = "";
document.getElementById("irid").value = "";
Exception.clear();
}
function NewGroup(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnsavestate").show();
$("#btnupdatestate").hide();
document.getElementById("ir").value = "";
document.getElementById("irid").value = "";
document.getElementById("state_code").value = "";
document.getElementById("tin_number").value = "";
}

function AddState() {
  Exception.validate("form1");
  var result = Exception.validStatus;
  if (result) {
      var TYPE = "INSERT";
      var StateName = document.getElementById("ir").value;
	  var StateCode = document.getElementById("state_code").value;
	  var TinNumber = document.getElementById("tin_number").value;
      if(StateName != "")
      {
  	      jQuery.ajax({
            url:  "../../Controller/Master_Controller/State_Controller.php",
            type: "POST",
            //data: { TYP : TYPE , STATENAME : StateName },
			data: { TYP : TYPE , STATENAME : StateName , STATECODE : StateCode , TINNUMBER : TinNumber },
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
function UpdateState() {
Exception.validate("form1");
var result = Exception.validStatus;
  if (result) {
    var TYPE = "UPDATE";
    var StateName = document.getElementById("ir").value;
    var StateId = document.getElementById("irid").value;
	var StateCode = document.getElementById("state_code").value;
	var TinNumber = document.getElementById("tin_number").value;
    if (StateName != "") {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/State_Controller.php",
            type: "POST",
            //data: { TYP: TYPE, STATENAME: StateName, STATEID: StateId },
			data: { TYP : TYPE , STATENAME : StateName , STATECODE : StateCode , TINNUMBER : TinNumber , STATEID: StateId },
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
function EditState(com, grid) {
        if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    var StateID=value.children[0].innerText||value.children[0].textContent; 
						jQuery.ajax({
        url:  "../../Controller/Master_Controller/State_Controller.php",
        type: "POST",
		//var GROUPID=document.getElementById("gid").value ;
        data: { TYP : "SELECT" , STATEID : StateID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
			var objs=jQuery.parseJSON(jsondata);
                document.getElementById("irid").value = objs[0]._stateId; 
                document.getElementById("ir").value = objs[0]._stateName;
				document.getElementById("state_code").value = objs[0]._stateCode;
				document.getElementById("tin_number").value = objs[0].tin_no;
            }
        } 
    });
					            
                    $("#Form_Div").show();
                    $("#ShowData_Div").hide();
                    $("#btnsavestate").hide();
                    $("#btnupdatestate").show();
                                
            });    
        }
    }
}

function LoadUnitData() {
$(".flexme4").flexigrid({
                url : '../../Controller/Master_Controller/Unit_Controller.php?TYP=',
                dataType : 'json',
                colModel : [ {display : 'UNIT ID', name : 'UNITID', width : 500, sortable : true, align : 'center'}, 
                             {display : 'UNIT NAME', name : 'UNITNAME', width : 500, sortable : true, align : 'left' }, ],
                buttons : [{name : 'Add', bclass : 'add', onpress : OpenUnitMaster },
                 {name : 'Edit', bclass : 'edit', onpress : UnitMasterGrid },{ separator : true }],
                searchitems : [ { display : 'UNITID', name : 'UNITID'}, { display : 'UNITNAME', name : 'UNITNAME', isdefault : false } ],
                sortorder : "asc",
                usepager : false,
                //title : 'UNIT Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1000,
                height : 300,
                
            });
			
}
LoadUnitData();
function OpenUnitMaster()
{
$("#showunitdiv").hide();
$("#addunitdiv").show();

$("#btnaddunit").show();
$("#btnupdateunit").hide();
}
function Cancle()
{
document.getElementById("ir").value = "";
$("#showunitdiv").show();
$("#addunitdiv").hide();
$("#btnaddunit").show();
$("#btnupdateunit").hide();
Exception.clear();
}
function AddUnit() {
Exception.validate("form1");
var result = Exception.validStatus;
  if (result) {
      var TYPE = "INSERT";
      var UnitName = document.getElementById("ir").value;
      if(UnitName != "")
      {
  	    jQuery.ajax({
            url:  "../../Controller/Master_Controller/Unit_Controller.php",
            type: "POST",
            data: { TYP : TYPE , UNITNAME : UnitName },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                     $(".flexme4").flexReload();
                     document.getElementById("ir").value = "";
                     $("#showunitdiv").show();
                     $("#addunitdiv").hide();
                }
            }
        });
      }
  }
  
 
}
function UpdateUnit() {
Exception.validate("form1");
var result = Exception.validStatus;
  if (result) {
    var TYPE = "UPDATE";
      var UnitName = document.getElementById("ir").value;
      var UnitID = document.getElementById("irid").value;
      if(UnitName != "")
      {
  	    jQuery.ajax({
            url:  "../../Controller/Master_Controller/Unit_Controller.php",
            type: "POST",
            data: { TYP : TYPE , UNITID : UnitID ,UNITNAME : UnitName },
            //cache: false,
            success: function (jsondata) {
                if (jsondata != "") {
                    document.getElementById("irid").value = "";
                    document.getElementById("ir").value = "";
                    $("#showunitdiv").show();
                    $("#addunitdiv").hide();
                    $(".flexme4").flexReload();
                }
            } 
        });
      }
  }
  
}
 function UnitMasterGrid(com, grid) {
                 if (com == 'Edit') {
                    if(true){
                        $.each($('.trSelected', grid),
                            function(key, value){
                                // collect the data
  var TYPE = "SELECT";
   document.getElementById("irid").value = value.children[0].innerText|| value.children[0].textContent;
   document.getElementById("ir").value = value.children[1].innerText|| value.children[1].textContent;
  var UnitName = document.getElementById("ir").value;
  var UnitID = document.getElementById("irid").value.replace(/(\r\n|\n|\r)/gm,"");
  if(UnitID != "")
  {
  	  jQuery.ajax({
        url:  "../../Controller/Master_Controller/Unit_Controller.php",
        type: "POST",
        data: { TYP : TYPE , UNITID : UnitID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
                var objs = jQuery.parseJSON(jsondata);
                document.getElementById("irid").value = objs[0]._uniId;
                document.getElementById("ir").value = objs[0]._unitName;
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
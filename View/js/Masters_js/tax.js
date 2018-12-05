
function LoadTax(){
$(".flexme4").flexigrid({
        url : '../../Controller/Master_Controller/Tax_Controller.php?TYP=',
        dataType : 'json',
        colModel : [ {display : 'TAX ID',name : 'TAXID',width : 333,sortable : true,align : 'center'},
                     {display : 'TAX RATE',name : 'TAXRATE',width : 333,sortable : true,align : 'left'},
					 {display : 'TAX DESCRIPTION',name : 'TAXDESC',width : 333,sortable : true,align : 'left'},],
        buttons : [{name : 'New',bclass : 'new', onpress : NewTax},{name : 'Edit',bclass : 'edit', onpress : EditTax},{separator : true}],
        searchitems : [ {display : 'TAXID',name : 'TAXID'}, {display : 'TAXRATE',name : 'TAXRATE',isdefault : false}, {display : 'TAXDESC',name : 'TAXDESC',isdefault : false} ],
        sortorder : "asc",
        usepager : false,
        //title : 'Tax Master',
        useRp : false,
        rp : 15,
        showTableToggleBtn : false,
        width : 1000,
        height : 170,
    });
}
LoadTax();

function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnsavetax").show();
$("#btnupdatetax").hide();
document.getElementById("ir").value = "";
document.getElementById("taxd").value = "";
document.getElementById("irid").value = "";
Exception.clear();
}
function NewTax(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnsavetax").show();
$("#btnupdatetax").hide();
document.getElementById("ir").value = "";
document.getElementById("irid").value = "";
}

function AddTax() {
  Exception.validate("form1");
  var result = Exception.validStatus;
  if (result) {
      var TYPE = "INSERT";
      var TAXrate = document.getElementById("ir").value;
	  var TAXdesc = document.getElementById("taxd").value;
      if(TAXrate != "")
      {
		
		jQuery.ajax({
			url:  "../../Controller/Master_Controller/Tax_Controller.php",
			type: "POST",
			data: { TYP : TYPE , TAXRATE : TAXrate , TAXDESC : TAXdesc },
			//cache: false,
			success: function (jsondata) {
				if ((jsondata != "") && (JSON.parse(jsondata) != 'NO')) {
				document.getElementById("ir").value = "";
				document.getElementById("taxd").value = "";
				$(".flexme4").flexReload();
				$("#Form_Div").hide();
				$("#ShowData_Div").show();
				}
				else {
					alert('Tax Rate already exist.');
				}
			}
		});
		
      }
  }
}
function UpdateTax() {
Exception.validate("form1");
var result = Exception.validStatus;
  if (result) {
    var TYPE = "UPDATE";
    var TAXrate = document.getElementById("ir").value;
	var TAXdesc = document.getElementById("taxd").value;
    var TAXId = document.getElementById("irid").value;
    if (TAXrate != "") {
        jQuery.ajax({
            url: "../../Controller/Master_Controller/Tax_Controller.php",
            type: "POST",
            data: { TYP: TYPE, TAXRATE: TAXrate, TAXDESC: TAXdesc, TAXID: TAXId },
            //cache: false,
            success: function (jsondata) {
                if ((jsondata != "") && (JSON.parse(jsondata) != 'NO')) {
                    $(".flexme4").flexReload();
                    document.getElementById("ir").value = "";
					document.getElementById("taxd").value = "";
                    document.getElementById("irid").value = "";
                    $("#Form_Div").hide();
                    $("#ShowData_Div").show();
                }
                else {
					alert('Tax Rate already exist.');
                }
            }
        });
    }
  }
}
function EditTax(com, grid) {
        if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
                    var TaxID=value.children[0].innerText||value.children[0].textContent; 
						jQuery.ajax({
        url:  "../../Controller/Master_Controller/Tax_Controller.php",
        type: "POST",
        data: { TYP : "SELECT" , TAXID : TaxID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
			var objs=jQuery.parseJSON(jsondata);
                document.getElementById("irid").value = objs[0]._TaxId; 
                document.getElementById("ir").value = objs[0]._TAXrate;
				document.getElementById("taxd").value = objs[0]._TAXdesc;
            }
        } 
    });
                    $("#Form_Div").show();
                    $("#ShowData_Div").hide();
                    $("#btnsavetax").hide();
                    $("#btnupdatetax").show();
            });    
        }
    }
}
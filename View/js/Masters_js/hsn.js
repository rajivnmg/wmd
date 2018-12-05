var URL = "../../Controller/Master_Controller/HSN_Controller.php?TYP=";
function LoadHSN(){
$(".flexme4").flexigrid({
                url : URL,
                dataType : 'json',
                colModel : [ {display : 'HSN ID',name : 'HSNID',width : 200, sortable : true,align : 'center'},
                             {display : 'HSN CODE',name : 'HSNCODE', width : 200, sortable : true, align : 'left'}, 
                             {display : 'HSN DESC',name : 'HSNDESC',width : 200,sortable : true,align : 'left'},
							 {display : 'TAX RATE',name : 'TAXRATE',width : 200,sortable : true,align : 'left'},
                             {display : 'REMARKS',name : 'REMARKS',width : 200,sortable : true,align : 'left'},],
                buttons  : [ {name : 'New', bclass : 'new', onpress : NewHSN },{name : 'Edit', bclass : 'edit', onpress : EditHSN },
                             {separator : true }],
                searchitems : [ {display : 'HSNID',name : 'HSNID'}, {display : 'HSNCODE',name : 'HSNCODE'},
                                { display : 'HSNDESC', name : 'HSNDESC',isdefault : false} ],
                sortorder : "asc",
                usepager : false,
                //title : 'HSN Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1000,
                height : 300,
            });
}
LoadHSN();
function SearchHsn(Type) { 
    var Id = 0;
    switch (Type) {
        case "T":
            Id = $("#search_tax_rate").val();
            break;
        case "C":
            Id = $("#txt_search_hsn_code").val();
            break;
        case "D":
            Id = $("#txt_search_hsn_desc").val();
            break;
        default:
            break;
    }
    var newurl = "";
	var tax = $("#search_tax_rate").val();
	var hsn_code  = $("#txt_search_hsn_code").val();
	var hsn_desc  = $("#txt_search_hsn_desc").val();
	if(Type == "T"){ 		
				newurl = "../../Controller/Master_Controller/HSN_Controller.php?TYP=&TAG=" + Type + "&ID="+Id+"&tax="+tax+"&hsn_code=" + hsn_code+"&hsn_desc=" + hsn_desc;
			
	}else if(Type == "C"){ 		
				newurl = "../../Controller/Master_Controller/HSN_Controller.php?TYP=&TAG=" + Type + "&ID="+Id+"&tax="+tax+"&hsn_code=" + hsn_code+"&hsn_desc=" + hsn_desc;
			
	}else if(Type == "D"){ 
			
				newurl = "../../Controller/Master_Controller/HSN_Controller.php?TYP=&TAG=" + Type + "&ID=" + Id+"&tax="+tax+"&hsn_code=" + hsn_code+"&hsn_desc=" + hsn_desc;
			
	}else{	
			if (Id > 0 || Id != '') {
				newurl = "../../Controller/Master_Controller/HSN_Controller.php?TYP=&TAG=" + Type + "&ID=" + Id;
			}
			else {
				newurl = "../../Controller/Master_Controller/HSN_Controller.php?TYP=&TAG=" + Type + "&ID=0";
			}
		}
   
    $(".flexme4").flexOptions({ url: newurl });
    $(".flexme4").flexReload();
}
function Cancle(){
$("#Form_Div").hide();
$("#ShowData_Div").show();
$("#btnaddhsn").show();
$("#btnupdatehsn").hide();
document.getElementById("gid").value = "";
document.getElementById("gc").value = "";
document.getElementById("gd").value = "";
document.getElementById("nts").value = "";
Exception.clear();
}
function NewHSN(){
$("#ShowData_Div").hide();
$("#Form_Div").show();
$("#btnaddhsn").show();
$("#btnupdatehsn").hide();
document.getElementById("gid").value = "";
document.getElementById("gc").value = "";
document.getElementById("gd").value = "";
document.getElementById("nts").value = "";
document.getElementById("txttax").value = 0;
}

function AddHSN() {
Exception.validate("form1");
var result = Exception.validStatus;
    if (result) {
        var TYPE = "INSERT";
        var HSNCode = document.getElementById("gc").value;
        var HSNDesc = document.getElementById("gd").value;
        var Remark = document.getElementById("nts").value;
		var TaxId = document.getElementById("txttax").value;
        if (HSNCode != "" && HSNDesc != "") {
            jQuery.ajax({
                url: URL,
                type: "POST",
                data: { TYP: TYPE, HSNCODE: HSNCode,HSNDESC : HSNDesc , REMARKS : Remark , TAXID : TaxId },
                success: function (jsondata) {
					//alert(jsondata);
					//if (jsondata != "") {
                    if ((jsondata != "") && (JSON.parse(jsondata) != 'NO')) {
                        $("#Form_Div").hide();
                        $("#ShowData_Div").show();
                        $(".flexme4").flexReload();
                    }
                    else {
						alert('HSN Code already exist.');
                    }
                }
            });
        }
    }
}
function UpdateHSN() {
Exception.validate("form1");
var result = Exception.validStatus;
if (result) {
    var TYPE = "UPDATE";
    var HSNID = document.getElementById("gid").value.replace(/(\r\n|\n|\r)/gm,"");
    var HSNCode = document.getElementById("gc").value;
    var HSNDesc = document.getElementById("gd").value;
    var Remark = document.getElementById("nts").value.replace(/(\r\n|\n|\r)/gm,"");
	var TaxId = document.getElementById("txttax").value;
	
    if (HSNCode != "" && HSNDesc != "" && HSNID > 0) {
		
        jQuery.ajax({
            url: URL,
            type: "POST",
            data: { TYP: TYPE, HSNID: HSNID, HSNCODE: HSNCode, HSNDESC: HSNDesc, REMARKS: Remark, TAXID: TaxId },
            //cache: false,
            success: function (jsondata) {
                if ((jsondata != "") && (JSON.parse(jsondata) != 'NO')) {
					$("#Form_Div").hide();
					$("#ShowData_Div").show();
                    document.getElementById("gid").value = "";
                    document.getElementById("gc").value = "";
                    document.getElementById("gd").value = "";
                    document.getElementById("nts").value = "";
					document.getElementById("txttax").value = 0;
                    $(".flexme4").flexReload();
                }
                else {
					alert('HSN Code already exist.');
                }
            }
        });
    }
  }
}
function EditHSN(com, grid) {
    if (com == 'Edit') {
        if(true){
            $.each($('.trSelected', grid),
                function(key, value){
					var HSNID = value.children[0].innerText||value.children[0].textContent.replace(/(\r\n|\n|\r)/gm,"");
					jQuery.ajax({
        url:  URL,
        type: "POST",
        data: { TYP : "SELECT" , HSNID : HSNID },
        //cache: false,
        success: function (jsondata) {
            if (jsondata != "") {
			 var objs = jQuery.parseJSON(jsondata);
                 document.getElementById("gid").value = objs[0]._hsn_id;
                    document.getElementById("gc").value = objs[0]._hsn_code;
                    document.getElementById("gd").value = objs[0]._hsn_desc;
                    document.getElementById("nts").value = objs[0]._remark;
					document.getElementById("txttax").value = objs[0]._tax_id;
            }
        } 
    });
                    $("#Form_Div").show();
                    $("#ShowData_Div").hide();
                    $("#btnaddhsn").hide();
                    $("#btnupdatehsn").show();
            });    
        }
    }
}
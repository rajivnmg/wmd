var POList = {};
function CallToPO(POList) {
    'use strict';
    var POArray = $.map(POList, function (value, key) { return { value: value, data: key }; });
    // Initialize ajax autocomplete:
    $('#autocomplete-ajax-PO').autocomplete({
        //serviceUrl: '/autosuggest/service/',
        lookup: POArray,
        lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
            var re = new RegExp('\\b' + $.Autocomplete.utils.escapeRegExChars(queryLowerCase), 'gi');
            return re.test(suggestion.value);
        },
        onSelect: function (suggestion) {
            ActionOnPO(suggestion.value, suggestion.data);
            //$('#selction-ajax-PO').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
        },
        onHint: function (hint) {
            //$('#autocomplete-ajax-x-PO').val(hint);
        },
        onInvalidateSelection: function () {
            NonePO();
            //$('#selction-ajax-PO').html('You selected: none');
        }
    });
}
jQuery.ajax({
    url: "../../Controller/Business_Action_Controller/pos_Controller.php",
    type: "post",
    data: { TYP: "AUTOLIST"},
    success: function (jsondata) {
        var objs = jQuery.parseJSON(jsondata);
        //alert(jsondata);
        if (jsondata != "") {
            var obj;
            
            for (var i = 0; i < objs.length; i++) {
                var obj = objs[i];
                POList[obj.bpoId] = obj.rpo;
            }
            CallToPO(POList);
        }
    }
});
function ActionOnPO(value, data) {
    //$("#new_buyer").show();
    //alert(value+"|"+data);
    if (value != "" && data > 0) {
        $("#ipoid").val(data);
        jQuery.ajax({url: "../../Controller/Business_Action_Controller/pos_Controller.php",type: "POST",
		            data: { 
		            TYP: "SCHPRINCIPAL" ,
		            POID :data},
		            success: function (jsondata){
		            //alert(jsondata);
		            $('#iprincipalId').empty();
		            $("#iprincipalId").append("<option value=''></option>");
		            var objs = jQuery.parseJSON(jsondata);
		                //alert(jsondata);
		                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){var obj = objs[i];
		                   
		                  $("#iprincipalId").append("<option value=\"" + obj.sch_principalId + "\">" + obj.sch_principalName + "</option>");}
		                    //$("#iprincipalId").val(0);
		                    }}});
    }
}
function NonePO() {
    
	if(document.getElementById("ipoid").value==""){
	$('#ipname').val("");
	$('#icPartNo').val("");
	$('#iprincipalId').val("");
    $("#iprincipalId").append("<option value=''></option>");
	$('#icodePartNo').empty();
    $("#icodePartNo").append("<option value=''></option>");
    $('#item_desc').val("");
    $('#ibuyer_item_code').val("");
    $('#irqty').val("");
    $('#ischDate').val("");
    $('#idqty').val("");
	}


}

var URL = "../../Controller/Business_Action_Controller/pos_Controller.php";
var method = "POST";
var schedule_app = angular.module('pos_app', []);
schedule_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('pos.sch_rqty', function(newValue,oldValue) {
 
                if(newValue==undefined){
					newValue="";
				}
				if(oldValue==undefined){
					oldValue="";
				}
               //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.' )) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.pos.sch_rqty = oldValue;
                }
            });
		}
	};
});
schedule_app.directive('isNumber', function () {
	return {
		require: 'ngModel',
		link: function (scope) {	
			scope.$watch('pos.sch_dqty', function(newValue,oldValue) {
 
                if(newValue==undefined){
					newValue="";
				}
				if(oldValue==undefined){
					oldValue="";
				}
               //alert(newValue+","+oldValue);
                var arr = String(newValue).split("");
                if (arr.length === 0) return;
                if (arr.length === 1 && (arr[0] == '-' || arr[0] === '.' )) return;
                if (arr.length === 2 && newValue === '-.') return;
                if (isNaN(newValue)) {
                    scope.pos.sch_dqty = oldValue;
                }
            });
		}
	};
});
schedule_app.directive('ngBlur', function() {
  return function( scope, elem, attrs ) {
    elem.bind('blur', function() {
      scope.$apply(attrs.ngBlur);
    });
  };
});
schedule_app.controller('pos_Controller', function ($scope){
	var sample_pos = { _items: [{ bposd_Id:0,bpod_Id:0,bpodId:0,pname:'',sch_principalId:'',cPartNo:'',sch_codePartNo:'', item_desc: '',bic:'',sch_rqty:'',schDate:'',sch_dqty:'',sch_ed_applicability:''}] };
    $scope.pos = sample_pos;
    $scope.addItem = function () {
       var isExist="B";
       $scope.pos.schDate = $("#ischDate").val();
        if($scope.pos.sch_principalId==undefined){
		    document.getElementById("iprincipalId").style.backgroundColor = "yellow";
			document.getElementById("iprincipalId").value="";
            isExist="A";
		}         
        if($scope.pos.sch_codePartNo==undefined){
		    document.getElementById("icodePartNo").style.backgroundColor = "yellow";
			document.getElementById("icodePartNo").value="";
            isExist="A";
		} 
    	if($scope.pos.sch_rqty==undefined){
			document.getElementById("irqty").style.backgroundColor = "yellow";
			document.getElementById("irqty").value="";
            isExist="A";
		}
       if($scope.pos.schDate==""){
		    document.getElementById("ischDate").style.backgroundColor = "yellow";
			document.getElementById("ischDate").value="";
			isExist="A";
		}
    	if($scope.pos.sch_dqty==undefined){
			document.getElementById("idqty").style.backgroundColor = "yellow";
			document.getElementById("idqty").value="";
            isExist="A";
		}
        if($scope.pos.sch_rqty!=undefined && $scope.pos.sch_dqty!=undefined){
          if(parseFloat($scope.pos.sch_dqty)>parseFloat($scope.pos.sch_rqty)){
			document.getElementById("irqty").style.backgroundColor = "yellow";
			document.getElementById("idqty").style.backgroundColor = "yellow";
			document.getElementById("idqty").value="";
			document.getElementById("irqty").value="";
            isExist="A";
		    }
		}
        
        if(isExist=="A"){
			return;
		}else{
		/* var po_itemType="";
		
		if($("#isch_ed_applicability").val()=="E"){
		 po_itemType="Excise";	
		}
	    else if($("#isch_ed_applicability").val()=="I"){
		  po_itemType="Inclusive";		
		}
		else if($("#isch_ed_applicability").val()=="N"){
		  po_itemType="NonExcise";		
		} */
		//$scope.pos.sch_ed_applicability
		//$scope.pos.cPartNo=$scope.pos.cPartNo+" ("+po_itemType+")";
		
    	$scope.pos._items.push({
			bpod_Id:$scope.pos.bpod_Id,
			pname:$scope.pos.pname,
			sch_principalId:$scope.pos.sch_principalId,
			cPartNo:$scope.pos.cPartNo,
			sch_codePartNo:$scope.pos.sch_codePartNo,
			item_desc:$scope.pos.item_desc,
			bic:$scope.pos.bic,sch_rqty:$scope.pos.sch_rqty,
			schDate:$scope.pos.schDate,
			sch_dqty:$scope.pos.sch_dqty,
			sch_ed_applicability:$scope.pos.sch_ed_applicability
		});
    	
    	//alert("pushed1");
     	$("#ibpod_id").val(0);
    	$scope.pos.sch_principalId="";
    	$scope.pos.pname=null;
    	$scope.pos.sch_codePartNo="";
    	$scope.pos.cPartNo=null;
    	$scope.pos.item_desc=null;
    	$scope.pos.bic=null;    	
    	$scope.pos.sch_rqty=null;
    	$("#ischDate").val("");
		$scope.pos.schDate = '';
    	$scope.pos.sch_dqty=null;
    	$scope.pos.sch_codePartNo="";
    	$scope.pos.sch_ed_applicability="";
    	$scope.pos.bpod_Id="";
    	}
    	

    }
    $scope.removeItem = function (item) {
        $scope.pos._items.splice($scope.pos._items.indexOf(item), 1);
    }
    $scope.getText1= function () {
 			$scope.pos.cPartNo=$("#icodePartNo option:selected").text();
	}
    $scope.AddPOS = function () {
        var json_string = JSON.stringify($scope.pos);
        if ($scope.pos._items.length <= 0) {
            alert("Atleast one row should be added in a item grid before submit form.");
            return;
        }
        //alert(json_string);
		//console.log(json_string);
        jQuery.ajax({
            url: URL,
            type: method,
            data: { TYP: "INSERT", PODATA: json_string },
            success: function (jsondata) {
             var objs = jQuery.parseJSON(jsondata);
              //alert(objs);
             if(objs=="YES"){
			 	alert("Save successfully");
			 }else{
			 	alert("Not Saved");
			 }

             location.href="po_schedule.php?POID=";
            },
            error : function () {
				alert("falied..");
			}
        });
    }
    $scope.init=function(number){
     	if(number > 0)
     	{
     		alert(number);         
		}
		else
		{
		$scope.pos._items.splice($scope.pos._items.indexOf(0), 1);	
		}
	}

    $scope.showSchCodePartNo=function(){
	  		var TYPE = "SCHCODEPARTNO";
	  		$scope.pos.bpoId = $("#ipoid").val();
               //alert($scope.pos.sch_principalId);
               if (true) {
		        jQuery.ajax({url: "../../Controller/Business_Action_Controller/pos_Controller.php",type: "POST",
		            data: { 
		            TYP: TYPE ,
		            POID :$scope.pos.bpoId,PRINCIPALID :$scope.pos.sch_principalId},
		            success: function (jsondata){
		            //alert(jsondata);
		            $("#ibpod_Id").empty();
		            $("#ibpod_Id").append("<option value=''></option>");
		            var objs = jQuery.parseJSON(jsondata);
		                if (jsondata != "") {var obj;for(var i = 0; i < objs.length; i++){
							var obj = objs[i];
		                    /* var po_itemType="";
		                if(obj.sch_ed_applicability=="E"){
						   po_itemType="Excise";	
				        }
				        else if(obj.sch_ed_applicability=="I"){
						   po_itemType="Inclusive";		
				        }
				        else if(obj.sch_ed_applicability=="N"){
						   po_itemType="NonExcise";		
				        } */
				         
		                //$("#ibpod_Id").append("<option value=\"" + obj.bpod_Id + "\">" + obj.cPartNo+" ("+po_itemType+ " )</option>");
						$("#ibpod_Id").append("<option value=\"" + obj.bpod_Id + "\">" + obj.cPartNo+" </option>");
						}
		                   // $("#icodePartNo").val(0);
		                    }}});
                    }
           $scope.pos.pname=$("#iprincipalId option:selected").text();
    }
    $scope.getBuyerItemCode=function(){
	       var TYPE,POID="",CPN="";
	       TYPE = "BUYERITEMCODE";
               //alert($scope.pos.bpoId+","+$scope.pos.sch_codePartNo);
               if (true) {
		        jQuery.ajax(
		        {url: "../../Controller/Business_Action_Controller/pos_Controller.php",type: "POST",
		            data:{ 
		            TYP: TYPE ,
		            POID :$scope.pos.bpoId,CPN :$scope.pos.sch_codePartNo
		            },
		            success: function (jsondata){
 		                $scope.$apply(function () {
                               //alert(POID+"|"+CPN);
                               //alert(jsondata);
                               var objs = jQuery.parseJSON(jsondata);
                               if (jsondata != "") {
                                          //alert(jsondata);
                                          $scope.pos.bic = objs[0].bic;
                              }
                       });
		            }
		        
		        });
                }

    }
    $scope.itemdesc = function () {
      
       if ($scope.pos.bpod_Id > -1) {
        	
            var TYPE = "LOADPOITEM";
            if (true) {
                jQuery.ajax({
                   url: "../../Controller/Business_Action_Controller/pos_Controller.php",type: "POST",
                   data: { TYP: TYPE, BPODID: $scope.pos.bpod_Id },
                                       
                    success: function (jsondata) {
                    	//alert(jsondata);
                    	$scope.$apply(function () {
                        var objs = jQuery.parseJSON(jsondata);
                        if (jsondata != "") {
                            $scope.pos.item_desc = objs[0].ITEM_DESC;
                            $scope.pos.sch_codePartNo = objs[0].ITEMID;
                            $scope.pos.cPartNo=objs[0].ITEM_CODE_PARTNO;
                            $scope.pos.sch_ed_applicability = objs[0].sch_ed_applicability;
                            
                            $scope.getBuyerItemCode();
                        }
                        else {
                            $scope.pos.schDate = "";
                            $scope.pos.item_desc = "";
                            $scope.pos.bic = ""; 
                            $scope.pos.sch_rqty="";
                            $scope.pos.sch_dqty="";                                               
                        }
                    });
                        
                    }
                });
            }
        }
    }
});

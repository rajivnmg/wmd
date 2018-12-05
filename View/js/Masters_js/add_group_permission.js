var myApp = angular.module('myApp', []);
myApp.controller('groupPermissionController', function ($scope) {
  $scope.selection=[];
  // toggle selection for permission
 $scope.toggleSelection = function toggleSelection(prmName,sid) {
     var idx =$scope.selection.indexOf(prmName);
     //alert(idx);
     if (idx > -1) {

       $scope.selection.splice(idx,1);

     }

     else {
       $scope.selection.push(prmName);
     
     }
     //alert($scope.selection);
   }
   
   
   $scope.init=function(groupid)
   {
   	 // alert(groupid);
   	  jQuery.ajax({
   	   	url: "../../Controller/Master_Controller/UserType_Controller.php",
            type: "POST",
            data: { TYP: "SELECT",GROUPID:groupid},
            success: function (jsondata) {
            var objs = jQuery.parseJSON(jsondata);
            var perm=jQuery.parseJSON(objs.PERMISSION);
            $scope.selection=[];
           
            for (var i = 0; i < perm.length; i++)
            {
            	 $scope.selection.push(perm[i]);
				
			}
            //alert($scope.selection);
            	
               
            }
   	   });
	   	 
	   	
   }
   $scope.Save=function()
   {
   	  //alert($scope.selection);
   	  var groupId=$("#group_id").val();
   	//  alert(groupId);
   	   jQuery.ajax({
   	   	url: "../../Controller/Master_Controller/UserType_Controller.php",
            type: "POST",
            data: { TYP: "INSERT",GROUPID:groupId,"PERMISSIONDATA":$scope.selection },
            success: function (jsondata) {
            	var obj=jQuery.parseJSON(jsondata);
            	if(obj=="YES")
            	{
					alert("Permission successfully saved");
					window.location.href="../../View/masters/GroupPermission.php";
				}
				else
				{
					alert("Error: Invalid Request ");
				}
               
            }
   	   });
	   	
   }
   $scope.Update=function()
   {
   	  var id=$("#id").val();
   	 // alert($scope.selection);
   	  
   	   jQuery.ajax({
   	   	url: "../../Controller/Master_Controller/UserType_Controller.php",
            type: "POST",
            data: { TYP: "UPDATE","ID":id,"PERMISSIONDATA":$scope.selection },
            success: function (jsondata) {
            	
            	var obj=jQuery.parseJSON(jsondata);
            	//alert(obj);
            	if(obj=="YES")
            	{
					alert("Permission successfully updated");
				window.location.href="../../View/masters/GroupPermission.php";
				}
				else
				{
					alert("Error: Invalid Request ");
				}
               
            }
   	   });
	   	
   }
   
});

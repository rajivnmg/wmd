<?php 
include('root.php');
include $root_path.'GlobalConfig.php'; 
include_once("../../permissionArray.php");   

?>
<?php include("../home/head.php") ?>

<!DOCTYPE html>
<html>
<head>
<!--########################## CSS #######################################-->
<link href="../css/my_temp_new.css" rel="stylesheet" type="text/css" />
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<!--########################## END ####################################-->
<!--############################## js ######################################-->
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Masters_js/angular.js'></script>
<script type='text/javascript' src='../js/Masters_js/addUserPrivilege.js'></script>
<!--################################ end ###################################################-->
</head>
<body ng-app="myApp">
 <?php 
 //include '../SalesExecutive/header.php';
include("../../header.php");
 ?>
<form id="form1" ng-controller="groupPermissionController" data-ng-init="init('<?php echo $_GET['USERID'];?>');" class="smart-green">
<div id="wrapper">

<div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
<div class="row">
<div class="col-lg-8" style="width: 100%;">
<div class="panel panel-default">
<?php

 $ID='';
 $result= UserModel::LoadUserDetails($_GET['USERID']);
 while ($Row = mysql_fetch_array($result, MYSQL_ASSOC)) {
 	//print_r($Row);
 	$gperm=json_decode($Row['PERMISSION']);
 	$USERID=$Row['USERID'];
 	$USER_NAME=$Row['USER_NAME'];
 	$USER_TYPE_NAME=$Row['USER_TYPE_NAME'];
 }	
 
 
 
?>
<div class="panel-heading">
<i class="fa fa-bar-chart-o fa-fw"></i>Add Privilege For <?php echo $USER_NAME;?>(<?php echo $USER_TYPE_NAME;?>)
</div>
<div class="panel-body">

<input type="text" value="<?php echo $USERID;?>" id="userid" style="display:none">
<div align="center" style=" width:100%; height:auto;">
<div id="showunitdiv">
<table border="0" cellpadding="5" cellspacing="1"  width="100%;" style="text-transform:capitalize;font-variant:small-caps;">
 <tr >
     
      <th  width="50%"  align="left">Permission Name</th>
      <th  width="10%" align="center">View</th>
      <th  width="10%" align="center">Add</th>
      <th  width="10%" align="center">Edit</th>
      <th  width="10%" align="center">Cancel</th>	
     	
      <th  width="10%" align="center">Explode</th>
 </tr>

 <tr>
 <td colspan="6" width="100%">
  <div style="width:100%; float:left; height:440px; overflow:scroll;">
   <table border="1" width="100%">
   <?php
   foreach($PermissionArray as $titleKey=>$titleVal)
   {
  ?> 
  <tr bgcolor="#dddddd" >
      <td colspan="6" width="100%">&nbsp;<i class="fa fa-bar-chart-o fa-fw">&nbsp;&nbsp;</i><?php echo $titleKey; ?></td>	
  </tr>
<?php
  if(is_array($titleVal))
  {  $i=1;
   foreach($titleVal as $permKey=>$permVal)
   {
   	 echo '<tr>';
	 echo '<td width="50%">'.$permKey.'</td>';
	 if(is_array($permVal))
	 {   $j=1;
	   foreach($permVal as $actionKey=>$actionVal)
	   {
	   	  if($actionVal!='')
		  {
		  	 $str="";
		  	 
		  	 if(in_array($actionVal,$gperm))
		  	 {
			 	$str='ng-checked="true"';
			 	$sid='1';
			 	
			 }
			 else
			 {
			 	//$str='ng-checked="selection.indexOf(\''.$actionVal.'\') > -1"';
			 	$str='ng-checked="false"';
			 	$sid='0';
			 }
			
		  	?>
		  	<td width="10%" align="center"><input name="perm_<?php echo $i.'_'.$j;?>" id="id_<?php echo $i.'_'.$j;?>" type="checkbox" value="<?php echo $actionVal; ?>"   ng-click="toggleSelection('<?php echo $actionVal; ?>','<?php echo $sid;?>')" <?php echo $str;?> /></td>
		  	<?php
		  	$j++;
		  }
		  else
		  {
		  	echo '<td width="10%">&nbsp;</td>';	
		  }	
	   }
	   $i++;
	 }  	
	 echo '</tr>';
   }	
?>
   
<?php
 }
}
 ?>
   </table>
   
  </div>	
 </td>
 </tr>
 </table>

</div>
<div class="clr"></div>
      <div align="center">
         <?php
         if($USERID!='')
         {
		 echo '<input type="button" class="button" name="button3" id="btnupdate" value="Update" ng-click="Update();" />';	
		 }
		 else
		 {
	echo '<input type="button" class="button" name="button3" id="btnsave" tabindex="14" value="Save" ng-click="Save();" />';
		 }
         ?>
          
          <!--<input type="button" class="button" name="button3" id="btnupdate" value="Update" ng-click="Update();" />-->
          
          <a class="button" style="text-decoration: none;" id="btcancel"  href="<?php print SITE_URL.USERSLIST; ?>">Cancel</a>
          
      </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</form>
<script src="../js/boot_js/jquery-1.11.0.js"></script>
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script>
</body>

</html>

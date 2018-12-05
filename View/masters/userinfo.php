<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php");
if($_SESSION["USER"]!=null)
      {?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>View Employee</title>
    <link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
    <script type='text/javascript' src='../js/jquery.min.js'></script>
    <link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
    <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
    <script type='text/javascript' src='../js/ang_js/angular.js'></script>
</head>
<body ng-app ="UserEdit_app">
<?php 
//include("../../AdminHeader.php") 
//include("../../header.php") 
?>
<form name="f1" method="post" ng-controller="UserEdit_Controller" class="smart-green">
<div align="center"><h1>Employee List/Details</h1></div>
<div align="center" style="margin-top:30px;"><span><a class="button" href="<?php print SITE_URL.USERMASTER; ?>">New Employee</a></span></div>
<div align="center" style="margin-left:-40px; margin-top:30px; width:100%; height:auto;">
    <div id="showunitdiv"><table class="flexme4" style="display: none; width:100%;"></table></div>
    <script type='text/javascript' src='../js/Masters_js/userdtls.js'></script>
</div>
</form>
<?php include("../../footer.php") ?>
</body>
</html>
<?php 
      } 
      else
      {
          //echo 'notinsession';
          header('Location: LoginUser.php');
      }?>
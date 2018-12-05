<?php
session_start();
$_SESSION["USER"]=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<title>Untitled Document</title>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript">
		var SitePath = "<?php echo SitePath ?>";
		var AdminHomePath = "<?php echo AdminHomePath ?>";
		var SalesExecutiveHomePath = "<?php echo SalesExecutiveHomePath ?>";
		var ManagementHomePath = "<?php echo ManagementHomePath ?>";
</script>

<script type='text/javascript' src='../js/Masters_js/User.js'></script>
</head>

<body ng-app ="Login_app">
<!--logo container start-->
<!--mid start-->
<form name="f1" ng-controller="Login_Controller"  ng-submit="submitForm()">

<div id="mid">
<!--login container start-->
<div class="login_container">
<div class="login_hedi">Login</div>
<div class="login_row">
<div class="log_nam">User Name:</div>
<div class="log_fld_dv"><input type="text" class="usr_fld" placeholder="User name" ng-model="Login.USERID"/></div>
<div class="clr"></div>
</div>
<div class="login_row">
<div class="log_nam">Password:</div>
<div class="log_fld_dv"><input type="text" class="usr_fld" placeholder="Password" ng-model="Login.PASSWD" type="password"/></div>
<div class="clr"></div>
</div>
<div class="login_row">
<div class="log_nam">&nbsp;</div>
<div class="log_fld_dv"><input type="submit" value="Login" class="lgn_btn"/></div>
<div class="clr"></div>
</div>
<div class="login_row">
<div class="log_nam">&nbsp;</div>
<div class="log_fld_dv"><div class="frgt_pass "><a href="forget_password.html"> Forgot Password ?</a></div></div>
</div>
<div class="clr"></div>
</div>
<!--login container end-->
<div class="clr"></div>
</div>
<!--mid end-->
<?php include("../../footer.php") ?> 
</form>
<!--fotter start-->
<!--fotter end-->

<div class="clr"></div>
</div>
  
</body>
</html>

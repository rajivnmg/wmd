<?php
include('root.php');
include($root_path."GlobalConfig.php");
//include("../home/head.php"); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>New Employee</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.js'></script>
<script type="text/javascript">
		var SitePath = "<?php echo SitePath ?>";
		var AdminHomePath = "<?php echo AdminHomePath ?>";
		var SalesExecutiveHomePath = "<?php echo SalesExecutiveHomePath ?>";
		var ManagementHomePath = "<?php echo ManagementHomePath ?>";
</script>

<script type='text/javascript' src='../js/Masters_js/User.js'></script>

<!-- 
<script src="../js/app.js"></script>
	 <script language="javascript" type="text/javascript" src="../js/app.js"></script>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />

  <script language="javascript" type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
    <script type='text/javascript' src='../js/ang_js/none.js'></script>
<script type='text/javascript' src='../js/ang_js/angular.min.js'></script>

  <script src="../js/index.js"></script>
  
        <script src="../js/jquery-ui.js"></script> -->
</head>
<body ng-app ="Create_app">
<?php   
//include '../SalesExecutive/header.php';
include("../../header.php") 
 ?>

<form id="form1" ng-controller="Create_Controller"  ng-submit="submitForm()" class="smart-green" data-ng-init="init('<?php echo  $_GET['ID'];?>')">
<div align="center"><h1>Employee Form</h1></div>
<div style="margin-left:470px;">
      <div style="width:50%; float:left;">
    
            <span>User ID:</span>
            <input type="text" name="USERID" ID="USERID" placeholder="User ID" ng-model="Create.USERID" tabindex="1" required></input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;" id="pd">
        <label>
            <span>User Password:</span>
            <input type="password" ID="PASSWD" placeholder="User Password" ng-model="Create.PASSWD" tabindex="2" required></input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
        <label>
            <span>User Name:</span>
            <input type="text" ID="NAME" placeholder="User name" ng-model="Create.NAME" tabindex="3" required></input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
        <label>
            <span>User Type:</span>
            <select name="USER_TYPE" ID="USER_TYPE" ng-model="Create.USER_TYPE" tabindex="4" required>
            <option value="" title="select">Select User</option>
            <?php 
                    $result =  UserModel::GetUserType();
                    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['PARAM_VALUE1']."' title='".$row['PARAM_VALUE1']."'>".$row['PARAM_VALUE1']."</option>";
                    }?>
            </select>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
        <label>
            <span>Phon No:</span>
            <input type="text" name="PHONE" ID="PHONE" placeholder="Phone No" ng-model="Create.PHONE" tabindex="5" valid-number>
            </input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;" class="form-group"
       ng-class="{'has-error': form1.email.$invalid}">
        <label>
            <span>Email Id:</span>
            <input type="email" name="email" ID="email" placeholder="Email Id" ng-model="Create.email" tabindex="6" required></input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
        <label>
            <span>Mobile No:</span>
            <input type="text" valid-number name="MOBILE" ID="MOBILE" placeholder="Mobile No" ng-model="Create.MOBILE" tabindex="7"></input>
        </label>
      </div>
      <div class="clr"></div>
      <div style="width:50%; float:left;">
        <label>
            <span> <input type="checkbox" placeholder="ACTIVE" ID="ACTIVE" ng-model="Create.ACTIVE" tabindex="8"></input> Active</span>
           
        </label>
      </div>
      <div class="clr"></div>
      
</div></br>
<div align="center">
<label>
    <input type="submit" class="button" name="B1" value="Save" id="btnsave" ng-model="Create.save" tabindex="9" ></input>
     <input type="button" class="button" name="B1" value="Update" id="btnupdate" ng-click="Update();" ng-model="Create.update" tabindex="9" ></input>
    <a style="text-decoration: none;" class="button" href="<?php print SITE_URL.VIEWUSERMASTER; ?>">Cancel</a>
   
</label>
</div></br>
</form>
 <?php include("../../footer.php") ?> 
 <script src="../js/boot_js/jquery-1.11.0.js"></script>
<script src="../js/boot_js/bootstrap.min.js"></script>
<script src="../js/boot_js/plugins/metisMenu/metisMenu.min.js"></script>
<script src="../js/boot_js/sb-admin-2.js"></script> 
</body>
</html>
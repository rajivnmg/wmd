<?php //session_start();
	include('root.php');
	include($root_path.'GlobalConfig.php');
	include( "../../Model/ReportModel/Report1Model.php");
?>
<?php include("../home/head.php") ?>
<?php
session_start();
if($_SESSION["USER"]!=null)
      {?>
      
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
     <!--<LINK href="eiStyle.css" rel=stylesheet> -->
    
     <script type='text/javascript' src='../js/ang_js/app.js'></script>
	 <script type="text/javascript">
		var SitePath = "<?php echo SitePath ?>";
		var AdminHomePath = "<?php echo AdminHomePath ?>";
		var SalesExecutiveHomePath = "<?php echo SalesExecutiveHomePath ?>";
		var ManagementHomePath = "<?php echo ManagementHomePath ?>";
</script>
	 
     <script type='text/javascript' src='../js/Masters_js/User.js'></script>
  </head>
  <body ng-app="ChangePassword_app">
  <form name="f1" method="post"  ng-controller="ChangePassword_Controller">
      <div id="wrapper">
        <?php include '../home/menu.php'; ?>
           <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
               <div class="row">
                 <div class="col-lg-8" style="width: 100%;">
                     <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-bar-chart-o fa-fw"></i>Change Password
                        </div>
                        <div class="panel-body">
                            <div align="center" style=" width:100%; height:auto;">
           <div><h2>Change Password</h2></div>
       
              <hidden id="irid"></hidden>
              <input type="password"  name="unit" id="ir1" value="" placeholder="Old Password" ng-model="ChangePassword.oldpass" class="form-control" style="width: 350px;"  required/><br/>
               <input type="password"  name="unit" id="ir2" value="" placeholder="New Password" ng-model="ChangePassword.newpass" class="form-control" style="width: 350px;"  required/><br/>
                <input type="password"  name="unit" id="ir3" value="" placeholder="Confirm Password" ng-model="ChangePassword.confirmpass" class="form-control" style="width: 350px;"  required/><br/>
              <button type="button" class="btn btn-primary" id="btnaddunit" name="B1" ng-click="Change();">Update</button>
              <a  style="text-decoration: none;" href="<?php session_start();
                      if($_SESSION['USER_TYPE'] == "A"){
                          print SITE_URL.ADMINHOME;
                      }
                      else
                      {
                          print SITE_URL.DASHBOARD;
                      }
                      ?>" class="button">Cancel</a>
              <!-- <button type="button" id="btnupdateunit" style="display:none;" name="B1" value="Update"  class="button" ng-click="Cancel();">Cancel</button> -->
             
    
            
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
      
<?php 
} 
else
{
    //echo 'notinsession';
    header('Location: '.SITE_URL.'View/masters/LoginUser.php');
}?>
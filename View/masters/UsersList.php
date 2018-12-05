<?php
include('root.php');
include($root_path."GlobalConfig.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	<title>User List</title>
<!--     <link href="../css/my_temp.css" rel="stylesheet" type="text/css" />-->
     <script type='text/javascript' src='../js/jquery.min.js'></script>
     <link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
     <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
     <script type='text/javascript' src='../js/Masters_js/Exception.js'></script>
  </head>
  <body>
  <?php 
	//include '../SalesExecutive/header.php'; 
	include("../../header.php");
  ?>
  <form name="form1"  id="form1" method="post" class="smart-green">
       <div id="wrapper">
        <div align="center"><h1>Users Privilege Management</h1></div>
           <div id="page-wrapper" style="margin: 0px; padding-top: 20px;">
               <div class="row">
                 <div class="col-lg-8" style="width: 100%;">
                     <div class="panel panel-default">
                       
                        <div class="panel-body">
                             <div align="center" style=" width:100%; height:auto;">
                                <div id="addunitdiv" style=" display:none;">
                                <div style="width:50%; margin-left:350px; float:left;">
                                    <label>
                                        <span>Unit Name</span>
                                        <hidden id="irid"></hidden>
                                        <input type="text"  name="unit" id="ir" value="" placeholder="Unit name" class="required mytext"/><br/>
                                        <input type="button" id="btnaddunit" name="B1" value="Save"  class="button" onclick="AddUnit();"></input>
                                        <input type="button" id="btnupdateunit" style="display:none;" name="B1" value="Update"  class="button" onclick="UpdateUnit();"></input>
                                        <input type="button" id="btncancle" name="B1" value="Cancel"  class="button" onclick="Cancle();"></input>
                                    </label>
                                  </div>
                                  <div class="clr"></div>
                                </div>
                                <div id="showunitdiv">
                                <table class="flexme4" style="display: none; width:100%;">
                                	
                                </table>
                                </div>
                                <script type='text/javascript' src='../js/Masters_js/user_list.js'></script>
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
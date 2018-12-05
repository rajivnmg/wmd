<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<html>
  <head>
     <link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
     <script type='text/javascript' src='../js/jquery.js'></script>
     <link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
     <script type='text/javascript' src='../js/jquery.min.js'></script>
     <script type='text/javascript' src='../js/flexigrid.pack.js'></script>
     <script type='text/javascript' src='../js/Masters_js/Exception.js'></script>
  </head>
  <body>
 
  <form name="form1"  id="form1" method="post" class="smart-green">
  <div align="center"><h1>Group Master Form<span></span></h1></div>
  	<div align="center">
     <div style="margin-left:-30px;" id="ShowData_Div">
         <table class="flexme4" style="display: none; width:100%;"></table>
     </div><br/><br/>
     <div id="Form_Div" style=" display:none;">
         <div style="margin-left:470px;">
              <div style="width:50%; float:left;">
                <label>
                    <span>Group Code:</span><hidden id="gid"></hidden>
                    <input type="text" name="groupc" id="gc" placeholder="Group Code" tabindex="1" readonly class="required mytext"></input>
                </label>
              </div>
              <div class="clr"></div>
              <div style="width:50%; float:left;">
                <label>
                    <span>Group Description:</span>
                    <input type="text" name="groupd" id="gd" placeholder="Group Description" tabindex="2" class="required mytext"></input>
                </label>
              </div>
              <div class="clr"></div>
              <div style="width:50%; float:left;">
                <label>
                    <span>Remarks:</span>
                    <textarea type="text" name="rmk" id="nts" placeholder="Remarks" tabindex="3"></textarea>
                </label>
              </div>
              <div class="clr"></div>
         </div>
         <div align="center">
             <input type="button" name="B1" id="btnaddgroup" value="Save"  class="button" onclick="AddGroup();"/>
             <input type="button" name="B2" id="btnupdategroup"  value="Update" class="button" onClick="UpdateGroup();"/>
             <input type="button" name="B2" id="btncancle"  value="Cancel" class="button" onClick="Cancle();"/>
         </div><br/><br/><br/><br/>
     </div>
   </div>
  <script type='text/javascript' src='../js/Masters_js/group.js'></script>
  </form>
  <?php include("../../footer.php") ?>
  </body>
</html>
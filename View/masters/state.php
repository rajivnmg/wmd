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
  <div align="center"><h1>State Master Form<span></span></h1></div>
   <div align="center" style=" width:100%; height:auto;">
     <div id="ShowData_Div">
          <table class="flexme4" style="display: none; width:100%;"></table>
     </div>
     <div id="Form_Div" style=" display:none;">
         <div style="width:50%; margin-left:350px; float:left;">
			<div style="width:50%;">
            <label>
              <span>State Name:</span>
              <hidden id="irid"></hidden>
              <input type="text"  name="state" id="ir" value="" placeholder="State Name" class="required mytext"/>
            </label>
			</div>
			<div style="width:50%;">
			<label>
              <span>State Code:</span>
              <input type="text"  name="state_code" id="state_code" value="" placeholder="State Code" class="required twochar mytext"/>
            </label>
			</div>
			<div style="width:50%;">
			<label>
              <span>Tin Number:</span>
              <input type="text" name="tin_number" id="tin_number" value="" placeholder="Tin Number" class="required twochar mytext"/>
			  
              <input type="button" name="B1" value="Save" id="btnsavestate"  class="button" onclick="AddState();"/>
              <input type="button" name="B1" value="Update" id="btnupdatestate"  class="button" onclick="UpdateState();"/>
              <input type="button" name="B1" value="Cancel" id="btncancle"  class="button" onclick="Cancle();"/>
            </label>
			<div>
          </div>
          <div class="clr"></div>
          
      </div>
   </div>
   <script type='text/javascript' src='../js/Masters_js/state.js'></script>
   
  </form>
  <?php include("../../footer.php") ?>
  </body>
</html>
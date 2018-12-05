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
  <div align="center"><h1>TAX Master Form<span></span></h1></div>
   <div align="center" style=" width:100%; height:auto;">
     <div id="ShowData_Div">
          <table class="flexme4" style="display: none; width:100%;"></table>
     </div>
     <div id="Form_Div" style=" display:none;">
         <!--<div style="width:50%; margin-left:350px; float:left;">
            <label>
              <span>TAX Rate:</span>
              <hidden id="irid"></hidden>
              <input type="text"  name="tax" id="ir" value="" placeholder="TAX Rate" class="required mytext"/>
              <input type="button" name="B1" value="Save" id="btnsavetax"  class="button" onclick="AddTax();"/>
              <input type="button" name="B1" value="Update" id="btnupdatetax"  class="button" onclick="UpdateTax();"/>
              <input type="button" name="B1" value="Cancel" id="btncancle"  class="button" onclick="Cancle();"/>
            </label>
          </div>
          <div class="clr"></div>-->
        <div style="margin-left:470px;">
              <div style="width:50%; float:left;">
                <label>
                    <span>TAX Rate(%):</span><hidden id="irid"></hidden>
                    <input type="text"  name="tax" id="ir" value="" placeholder="TAX Rate" class="required percentage mytext"/>
                </label>
              </div>
              <div class="clr"></div>
              <div style="width:50%; float:left;">
                <label>
                    <span>TAX Description:</span>
                    <input type="text" name="taxd" id="taxd" placeholder="TAX Description" tabindex="2" class="required mytext"></input>
                </label>
              </div>
              <div class="clr"></div>
         </div>
         <div align="center">
             <input type="button" name="B1" value="Save" id="btnsavetax"  class="button" onclick="AddTax();"/>
              <input type="button" name="B1" value="Update" id="btnupdatetax"  class="button" onclick="UpdateTax();"/>
              <input type="button" name="B1" value="Cancel" id="btncancle"  class="button" onclick="Cancle();"/>
         </div><br/><br/><br/><br/>
      </div>
   </div>
   <script type='text/javascript' src='../js/Masters_js/tax.js'></script>
   
  </form>
  <?php include("../../footer.php") ?>
  </body>
</html>
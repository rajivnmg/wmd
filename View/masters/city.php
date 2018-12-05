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
  <div align="center"><h1>City Master Form<span></span></h1></div>
  	
    <div align="center" style=" width:100%; height:auto;">
    <div id="ShowData_Div">
       <table class="flexme4" style="display: none; width:100%;"></table>
    </div>
    <div id="Form_Div" style=" display:none;">
        <div style="width:50%; margin-left:350px; float:left;">
            <label>
              <span>State:</span>
              <select name="state" id="state" tabindex="1" class="required myselect"><option value="0" title="select">All State</option>
            <?php include( "../../Model/Masters/StateMaster_Model.php"); 
			//include("../../Model/DBModel/DbModel.php");
                    $result =  StateMasterModel::GetStateList();
                    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                        echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
                    }?></select>
            </label>
        </div>
        <div class="clr"></div>
        <div style="width:50%; margin-left:350px; float:left;">
            <label>
              <span>City Name:</span>
              <hidden id="irid"></hidden>
              <input type="text"  name="city" id="ir" value="" tabindex="2" placeholder="City Name" class="required mytext"/>
              <input type="button" name="B1" value="Save" id="btnaddcity" tabindex="3"  class="button" onclick="AddCity();"/>
              <input type="button" name="B1" value="Update" id="btnupdatecity" tabindex="3" class="button" onclick="UpdateCity();"/>
              <input type="button" name="B1" value="Cancel" id="btncancle" tabindex="4"  class="button" onclick="Cancle();"/>
            </label>
        </div>
        <div class="clr"></div>  
    </div>
       
    </div>
    <script type='text/javascript' src='../js/Masters_js/city.js'></script>
  </form>
  <?php include("../../footer.php") ?>
  </body>
</html>
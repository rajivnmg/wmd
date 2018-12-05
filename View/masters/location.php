<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Location Master</title>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Masters_js/Exception.js'></script>
<style type="text/css">
.myselect
{
	width:50% !important;
	float:left;
}
.maindiv
{
	
}
.mylbl
{
 float: left;
    line-height: 36px;
    min-height: 35px;
    overflow-x: hidden;
    overflow-y: hidden;
    width: 150px;
}

.mytext
{
  width:50% !important;
  float: left;
}
</style>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>

<body>

<form id="form1" name="form1" method="post" action="" class="smart-green">
<div align="center"><h1>Location Master Form<span></span></h1></div>
<div align="center">
    <div style="margin-left:-30px;" id="ShowData_Div">
       <table class="flexme4" style="display: none; width:100%;"></table>
    </div><br/><br/><br/>
    <div id="Form_Div" style=" display:none;">
       <div style="width:50%; margin-left:350px; float:left;">
            <label>
              <label class="mylbl">State Name:</label>
              <?php
      include( "../../Model/Masters/StateMaster_Model.php");
     // include("../../Model/DBModel/DbModel.php");
      $result =  StateMasterModel::GetStateList();
      echo "<select name='state' id='state' onChange='showCity(this.value,0);' class='required myselect'> <option value='0'> Select State</option>";
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
          echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
      }
      echo("</select>");
                 ?>
                 <!--<label id="txtstate" style="display:none;"></label>-->
            </label>
        </div>
        <div class="clr"></div> 
        <div style="width:50%; margin-left:350px; float:left;">
            <label>
              <label class="mylbl">City Name:</label>            
              <select name="city" id="city" class="input1 required myselect"><option value="0">Select City</option>
    </select><!--<label id="txtcity" style="display:none;"></label>-->
            </label>
        </div>
        <div class="clr"></div> 
        <div style="width:50%; margin-left:350px; float:left;">
            <label>
              <label class="mylbl">Location Name:</label> 
              <hidden  id="locationid" ></hidden>
              <input type="text"  name="location" id="location" placeholder="Location Name" class="required mytext"/>             
            </label>
        </div>
        <div style="width:50%; margin-left:350px; float:left;">
              <input type="button" name="B1" value="Save" id="btnaddlocation"  class="button" onclick="Addlocation();"/>
              <input type="button" name="B1" value="Update" id="btnupdatelocation"  class="button" onclick="Updatelocation();"/>
              <input type="button" name="B3"  value="Cancel" class="button" onClick="Cancle();"/>
        </div>
        <div class="clr"></div> 
    </div>
</div>
<script type='text/javascript' src='../js/Masters_js/location.js'></script>
</form>
 <?php include("../../footer.php") ?>
</body>
</html>

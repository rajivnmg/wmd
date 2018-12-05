<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<script type='text/javascript' src='../js/jquery.min.js'></script>
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type="text/javascript" src="../js/a/jquery.autocomplete.js"></script>
<title>Buyer Master</title>
</head>
<body> 
<form name="f1" method="post" class="smart-green">
<div align="center"><h1>Buyer Master Search/List</h1></div>


<div style="">
<a class="button" href="<?php print SITE_URL.BUYERMASTER; ?>">&nbsp;New Buyer &nbsp;</a>
</div>
<div style="width:64%; float:left;">
    <label>
        <span>Buyer Name:</span><hidden id="buyerid"></hidden>
        <input type="text" id="autocomplete-ajax-buyer" style=" z-index: 2; background: transparent;" placeholder="Search By Buyer Name"/>
    </label>
</div>
<div style="width:30%; float:left;">
    <label>
        <span>Buyer Level:</span>
        <select name="select" id="byrlvl"  onChange="SearchBuyer(this.value);">
            <option value="0">Select Buyer Level</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="X">Blocked</option>
        </select>
    </label>
</div>

<div class="clr"></div>
<div style="width:30%; float:left;">
    <label>
        <span>State Name:</span>
        <?php include( "../../Model/Masters/StateMaster_Model.php");
		//include("../../Model/DBModel/DbModel.php");
$result =  StateMasterModel::GetStateList();
echo "<select name='state' id='state' onChange='showCity(this.value);'> <option value='0'> Select State</option>";
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
    echo "<option value='".$row['StateId']."' title='".$row['StateName']."'>".$row['StateName']."</option>";
}
echo("</select>");
            ?>
    </label>
</div>
<div style="width:30%; float:left;margin-left:3%;">
    <label>
        <span>City Name:</span>            
        <select name="city" id="city" onChange="showLocation(this.value);"><option value="0">Select City</option></select>
    </label>
</div>
<div style="width:30%; float:left;margin-left:2%;">
        <label>
            <span>Location:</span><select name="location" id="location" onChange="SearchBuyer(this.value);"><option value="0">Select Location</option></select>
        </label>
    </div>

<div class="clr"></div>
<div style=" margin-top:30px; margin-left:-50px;"><table class="BuyerList" style="width:100%;"></table></div>
<script type='text/javascript' src='../js/Masters_js/view_buyer.js'></script>
<br/><br/><br/><br/><br/>
</form>  
</body>
</html>
     
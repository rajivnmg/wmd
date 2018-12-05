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
  <div align="center"><h1>HSN Master Form<span></span></h1></div>
  	
     <div style="margin-left:-30px;" id="ShowData_Div">
		<div>
			<div><h3>Filter</h3></div>
			<div>
				<div class="clr"></div> 
				<div style="width:20%; float:left;">
					<label><span>Tax Rate:</span></label>
				</div>
				<div style="width:50%; float:left;">
					<select name="search_tax_rate" id="search_tax_rate" onchange="SearchHsn('T');">
						<option value="0">Select Tax Rate</option>
						<?php
						include_once( "../../Model/Masters/TaxMaster_Model.php");
						$result =  TaxMasterModel::GetTaxList();
						while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
							echo "<option value='".$row['tax_id']."' title='".$row['tax_rate']."'>".$row['tax_rate']."%</option>";
						}?>
					</select>
				</div> 
				<h3>OR</h3>
				<div class="clr"></div>
				<div style="width:20%; float:left;">
					<label><span>Search By HSN Code:</span></label>
				</div> 
				<div style="width:50%; float:left;">
					<input type="text" id="txt_search_hsn_code"/>
					<input type="button" class="button" name="B3"  value="Search by HSN Code" onClick="SearchHsn('C');"/>
				</div>
				<h3>OR</h3>
				<div class="clr"></div>
				<div style="width:20%; float:left;">
					<label><span>Search By HSN Description:</span></label>
				</div>
				<div style="width:50%; float:left;">
					<input type="text" id="txt_search_hsn_desc"/>
					<input type="button" class="button" name="B3"  value="Search by Discription" onClick="SearchHsn('D');"/>
				</div> 
				<div class="clr"></div>
			</div>
		</div>
         <table class="flexme4" style="display: none; width:100%;"></table>
     </div><br/><br/>
     <div id="Form_Div" style=" display:none;">
         <div style="margin-left:470px;">
              <div style="width:50%; float:left;">
                <label>
                    <span>HSN Code:</span><hidden id="gid"></hidden>
                    <input type="text" name="hsnc" id="gc" placeholder="HSN Code" tabindex="1" class="required mytext"></input>
                </label>
              </div>
              <div class="clr"></div>
              <div style="width:50%; float:left;">
                <label>
                    <span>HSN Description:</span>
                    <input type="text" name="hsnd" id="gd" placeholder="HSN Description" tabindex="2" class="required mytext"></input>
                </label>
              </div>
              <div class="clr"></div>
			  <div style="width:50%; float:left;">
					<label><span>Tax Rate:</span></label>
					<select name="txttax" id="txttax" class="required myselect"  style="width:90%;">
						<option value="0">Select Tax Rate</option>
						<?php
						include_once( "../../Model/Masters/TaxMaster_Model.php");
						$result =  TaxMasterModel::GetTaxList();
						while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
							echo "<option value='".$row['tax_id']."' title='".$row['tax_rate']."'>".$row['tax_rate']."%</option>";
						}?>
					</select>
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
             <input type="button" name="B1" id="btnaddhsn" value="Save"  class="button" onclick="AddHSN();"/>
             <input type="button" name="B2" id="btnupdatehsn"  value="Update" class="button" onClick="UpdateHSN();"/>
             <input type="button" name="B2" id="btncancle"  value="Cancel" class="button" onClick="Cancle();"/>
         </div><br/><br/><br/><br/>
     </div>
  <script type='text/javascript' src='../js/Masters_js/hsn.js'></script>
  </form>
  <?php include("../../footer.php") ?>
  </body>
</html>
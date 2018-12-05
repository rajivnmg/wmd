<?php 
include('root.php');
include($root_path."GlobalConfig.php");
include("../home/head.php");
include("../../header.php") 
 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/my_temp.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.js'></script>
<link href="../js/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src='../js/flexigrid.pack.js'></script>
<script type='text/javascript' src='../js/Masters_js/Exception.js'></script>
<style>
	.flexigrid div.pGroup input[type=text] {
		width: auto;  // this part
	}
</style>
</head>
<body>

<form name="form1"  id="form1" method="post" class="smart-green">
<div align="center"><h1>Item Master Form</h1></div>
<div id="ShowData_Div"  style="margin-left:-30px;">

   <div>
        <div><h3>Filter</h3></div>
        <div>
           <div style="width:20%; float:left;">
            <label><span>Group Description:</span></label>
      </div>
      <div style="width:50%; float:left;">
              <select name="gd" id="gd_search" onchange="SearchItem('G');">
		        <option value="0">Select Group</option>
		        <?php
                include( "../../Model/Masters/GroupMaster_Model.php");
               // include("../../Model/DBModel/DbModel.php");
                $result =  GroupMasterModel::GetGroupList();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['GroupId']."' title='".$row['GroupDesc']."'>".$row['GroupDesc']."</option>";
                }?>
		        </select>
      </div><h3>OR</h3>
      <div class="clr"></div>
      <div style="width:20%; float:left;">
            <label><span>Principal:</span></label>
      </div>
      <div style="width:50%; float:left;">
            <select name="gd2" id="principal_search" onchange="SearchItem('P');">
                <option value="0">Select Principal</option>
                <?php
                include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                $result =  Principal_Supplier_Master_Model::Get_Principal_List();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
                }?>
            </select>
      </div><h3>OR</h3>
      <div class="clr"></div>
      <div style="width:20%; float:left;">
            <label><span>Unit:</span></label>
        </div>
        <div style="width:50%; float:left;">
            <select name="unit" id="unit_search" onchange="SearchItem('U');">
			    <option value="0">Select Unit</option>
			    <?php
                include( "../../Model/Masters/UnitMaster_Model.php");
                $result =  UnitMasterModel::GetUnitList();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['UnitId']."' title='".$row['UNITNAME']."'>".$row['UNITNAME']."</option>";
                }?>
		    </select>
        </div><h3>OR</h3>
        <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Identification Mark:</span></label>
       </div> 
       <div style="width:50%; float:left;">
            <select name="idmark" id="identification_search" onchange="SearchItem('I');">
                <option value="0">Select Identification Mark</option>
                <?php include( "../../Model/Param/param_model.php");
			        $result =  ParamModel::GetParamList('ITEM','IDEN_MARK');
			        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			            echo "<option value='".$row['PARAM_VALUE2']."'>".$row['PARAM_VALUE1']."</option>";
                    }?>
                
		    </select>
       </div> <h3>OR</h3>
      <div class="clr"></div>
      <div style="width:20%; float:left;">
            <label><span>Search By CodePart:</span></label>
       </div> 
       <div style="width:50%; float:left;">
            <input type="text" id="txt_search_codepart"/>
            <input type="button" class="button" name="B3"  value="Search by Codepart" onClick="SearchItem('C');"/>
       </div> <h3>OR</h3>
      <div class="clr"></div>
      <div style="width:20%; float:left;">
            <label><span>Search By CodePart Description:</span></label>
       </div> 
       <div style="width:50%; float:left;">
            <input type="text" id="txt_search_codepart_desc"/>
            <input type="button" class="button" name="B3"  value="Search by Discription" onClick="SearchItem('D');"/>
       </div> 
      <div class="clr"></div>
        </div>
   </div>


<table class="flexme4" style="display: none; width:100%;"></table></div>
<br/><br/><br/><br/><br/>
<div align="center" id="Form_Div" style="display: none;"><hidden id="item_id"></hidden>
      <div style="width:20%; float:left;">
            <label><span>Group Description:</span></label>
      </div>
      <div style="width:50%; float:left;">
              <select name="gd" id="gd" class="required myselect"  style="width:90%;">
		        <option value="0">Select Group</option>
		        <?php
                //include( "../../Model/Masters/GroupMaster_Model.php");include("../../Model/DBModel/DbModel.php");
                $result =  GroupMasterModel::GetGroupList();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['GroupId']."' title='".$row['GroupDesc']."'>".$row['GroupDesc']."</option>";
                }?>
		        </select>
      </div>
      <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Principal:</span></label>
      </div>
      <div style="width:50%; float:left;">
            <select name="gd2" id="gd2" class="required myselect"  style="width:90%;">
                <option value="0">Select Principal</option>
                <?php
                //include( "../../Model/Masters/Principal_Supplier_Master_Model.php");
                $result =  Principal_Supplier_Master_Model::Get_Principal_List();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['Principal_Supplier_Id']."' title='".$row['Principal_Supplier_Name']."'>".$row['Principal_Supplier_Name']."</option>";
                }?>
            </select>
      </div>
      <div class="clr"></div> 
      <div style="width:20%; float:left;">
          <label><span>Item Code Part No:</span></label>
      </div>
      <div style="width:50%; float:left;">
          <input type="text"  name="cpartno" id="txtcodepart" value="" size="39" class="required mytext" />
      </div>
      <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Description:</span></label>
      </div>
      <div style="width:50%; float:left;">
            <input type="text"  name="desc" id="txtdesc" value="" size="39" class="required mytext" />
      </div>
      <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Unit:</span></label>
        </div>
        <div style="width:50%; float:left;">
            <select name="unit" id="ddlunit" class="required myselect"  style="width:90%;">
			    <option value="0">Select Unit</option>
			    <?php
                //include( "../../Model/Masters/UnitMaster_Model.php");
                $result =  UnitMasterModel::GetUnitList();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['UnitId']."' title='".$row['UNITNAME']."'>".$row['UNITNAME']."</option>";
                }?>
		    </select>
        </div>
        <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Identification Mark:</span></label>
       </div> 
       <div style="width:50%; float:left;">
            <select name="idmark" id="ddlidentification" class="required myselect"  style="width:90%;" onChange="NewIdentificationMark(this.value);">
                <option value="0">Select Identification Mark</option>
                <?php //include( "../../Model/Param/param_model.php");
			        $result =  ParamModel::GetParamList('ITEM','IDEN_MARK');
			        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
			            echo "<option value='".$row['PARAM_VALUE2']."'>".$row['PARAM_VALUE1']."</option>";
                    }?>
               <option value="NEW">NEW</option>		
		    </select>
		    
       </div> 
      <div class="clr"></div>
      <div style="display:none;" id="newidentificationDiv">
		<div style="width:20%; float:left;">
            <label><span>New Identification Mark*:</span></label>
        </div>
        <div style="width:50%; float:left;">
           <input type="text"  name="newidentification" id="newidentification" value=""/>
        </div>
        
        <div class="clr"></div> 
      
      </div> 
       
        
        <!-- BOF for replacing Tarrif Heading with HSN Code by Ayush Giri on 08-06-2017 -->
         <div style="width:20%; float:left;">
            <label><span>HSN Code:</span></label>
        </div>
        <!--<div style="width:50%; float:left;">
           <input type="text"  name="tfheading" id="txttarif" value=""/>
        </div> -->
        <div style="width:50%; float:left;">
            <select name="hsn_c" id="hsn_c" class="required myselect"  style="width:90%;" onChange="selectTAX(this.value);">
			    <option value="0">Select HSN Code</option>
			    <?php
                include( "../../Model/Masters/HSNMaster_Model.php");
                $result =  HSNMasterModel::GetHSNList();
                while($row=mysql_fetch_array($result, MYSQL_ASSOC)){                                                 
                    echo "<option value='".$row['hsn_code']."' title='".$row['hsn_code']."'>".$row['hsn_code']."</option>";
                }?>
		    </select>
        </div>
		<!-- EOF for replacing Tarrif Heading with HSN Code by Ayush Giri on 08-06-2017 -->
        <div class="clr"></div> 
		<div style="width:20%; float:left;">
			<label><span>Tax Rate:</span></label>
		</div>
		<div style="width:50%; float:left;">
			<input type="text"  name="gc2" id="txttaxrate" value="" readonly="" class="mytext"/>
		</div>
		<div class="clr"></div>
        <div style="width:20%; float:left;">
                <label><span>Basic Price:</span></label>
          </div>
          <div style="width:50%; float:left;">
                <input type="text"  name="gc2" id="txtcostprice" value="" class="required mytext"/>
          </div>
          <div class="clr"></div> 
          <div style="width:20%; float:left;">
                <label><span>LSC:</span></label>
          </div>
          <div style="width:50%; float:left;">
                <input type="text" name="lsc" id="txtlsc" class="required mytext"/>
          </div>
          <div class="clr"></div> 
      <div style="width:20%; float:left;">
                <label><span>USC:</span></label>
          </div>
          <div style="width:50%; float:left;">
                <input type="text"  name="usc" id="txtusc" value="" class="required mytext"/>
          </div>
          <div class="clr"></div> 
      <div style="width:20%; float:left;">
            <label><span>Remark:</span></label>
        </div>
        <div style="width:50%; float:left;">
           <textarea id="txtremarks" name="rmk" rows="5" cols="37"></textarea>
        </div>
        <div class="clr"></div> 
        <div>
             <input type="button" class="button" name="B1" value="Save" id="btnadditem"  onClick="AddItemMaster();"/>
              <input type="button" class="button" name="B2" value="Update" id="btnupdateitem"  onClick="UpdateItemMaster();"/>
		      <input type="button" class="button" name="B3"  value="Cancel" onClick="Cancle();"/>
        </div>
</div>
<script type='text/javascript' src='../js/Masters_js/item.js'></script>
<br/><br/><br/><br/><br/>
</form>
<?php include("../../footer.php") ?>
</body>
</html>

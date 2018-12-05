function LoadUnitData() {
$(".flexme4").flexigrid({
                url : '../../Controller/Master_Controller/UserType_Controller.php?TYP=',
                dataType : 'json',
                colModel : [ {display : 'SN', name : 'SN', width :80, sortable : true, align : 'left'}, 
                             {display : 'User Group Name', name : 'UserType', width : 500, sortable : true, align : 'left' },
                             {display : 'Permission', name : 'OPRATION', width :390, sortable : true, align : 'left' }, ],
                
                sortorder : "asc",
                usepager : false,
                title : 'User Group List ', align : 'left',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1000,
                height : 300,
                
            });
			
}
LoadUnitData();

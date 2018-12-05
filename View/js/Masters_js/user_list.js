function LoadUnitData() {
$(".flexme4").flexigrid({
    url: '../../Controller/Master_Controller/UserPrivileges_Controller.php?TYP=',
                dataType : 'json',
                colModel: [{display: 'SNO', name: 'SNO', width:100, sortable:false, align: 'left'},
                           {display: 'USER ID', name: 'USERID', width:225, sortable:false, align: 'left'},
                           {display: 'NAME', name: 'NAME', width: 400, sortable:false, align: 'left' },
                           {display: 'USER TYPE', name: 'USER_TYPE_NAME', width:225, sortable:false, align: 'left' },
                           {display: 'Action', name: 'ACTION', width:150, sortable:false, align: 'left' } ],
                
                sortorder : "asc",
                usepager : false,
                //title : 'UNIT Master',
                useRp : false,
                rp : 15,
                showTableToggleBtn : false,
                width : 1100,
                height : 400
                
            });
			
}
LoadUnitData();
function procme(celDiv, id) {
    $(celDiv).click(function () {
        var id = celDiv.innerText;
        var path = 'CreateUser.php?ID=' + id;
        window.location.href = path;
    });
}